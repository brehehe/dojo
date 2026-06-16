<?php

namespace App\Livewire\Admin;

use App\Exports\LaporanSeluruhJuaraExport;
use App\Models\Group\AgeGroup;
use App\Models\MatchNumber\MatchNumber;
use App\Models\TournamentResult;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Maatwebsite\Excel\Facades\Excel;

#[Layout('layouts.premium')]
class NewLaporanSeluruhJuaraIndex extends Component
{
    public string $search = '';

    public string $draftTypeFilter = '';

    public string $ageGroupFilter = '';

    public string $highlightFilter = ''; // '', 'yellow', 'green'

    public function mount(): void {}

    public function resetFilters(): void
    {
        $this->search = '';
        $this->draftTypeFilter = '';
        $this->ageGroupFilter = '';
        $this->highlightFilter = '';
    }

    public function getMatchData(): array
    {
        $matchNumbers = MatchNumber::query()
            ->leftJoin('match_number_merge_details', 'match_numbers.id', '=', 'match_number_merge_details.match_number_id')
            ->leftJoin('match_number_merges', 'match_number_merge_details.match_number_merge_id', '=', 'match_number_merges.id')
            ->select('match_numbers.*', 'match_number_merges.name as merge_group_name', 'match_number_merge_details.match_number_merge_id')
            ->with(['ageGroup', 'athletes.contingents'])
            ->orderBy('draft_type')
            ->orderBy('age_group_id')
            ->orderBy('order')
            ->get();

        // Pre-fetch registrations to avoid N+1 queries
        $registrationIds = $matchNumbers->flatMap(fn ($mn) => $mn->athletes->pluck('pivot.registration_id'))->filter()->unique()->toArray();
        $registrations = DB::table('registrations')
            ->whereIn('id', $registrationIds)
            ->pluck('contingent_id', 'id')
            ->toArray();

        // Group match numbers by their merge group
        $groups = [];
        foreach ($matchNumbers as $mn) {
            $mergeId = $mn->match_number_merge_id;
            if ($mergeId !== null) {
                $groupKey = 'merged_'.$mergeId;
                if (! isset($groups[$groupKey])) {
                    $groups[$groupKey] = [
                        'is_merged' => true,
                        'id' => $mergeId,
                        'name' => $mn->merge_group_name ?? $mn->name,
                        'draft_type' => $mn->draft_type,
                        'age_group_id' => $mn->age_group_id,
                        'age_group' => $mn->ageGroup?->name ?? '-',
                        'gender' => $mn->gender,
                        'gender_indo' => $mn->gender_indo,
                        'match_numbers' => [],
                    ];
                }
                $groups[$groupKey]['match_numbers'][] = $mn;
            } else {
                $groupKey = 'single_'.$mn->id;
                $groups[$groupKey] = [
                    'is_merged' => false,
                    'id' => $mn->id,
                    'name' => $mn->name,
                    'draft_type' => $mn->draft_type,
                    'age_group_id' => $mn->age_group_id,
                    'age_group' => $mn->ageGroup?->name ?? '-',
                    'gender' => $mn->gender,
                    'gender_indo' => $mn->gender_indo,
                    'match_numbers' => [$mn],
                ];
            }
        }

        $processed = [];
        foreach ($groups as $group) {
            // Filter by search (matches merge name or constituent match names)
            $searchMatched = false;
            if (empty($this->search)) {
                $searchMatched = true;
            } else {
                if (stripos($group['name'], $this->search) !== false) {
                    $searchMatched = true;
                } else {
                    foreach ($group['match_numbers'] as $mn) {
                        if (stripos($mn->name, $this->search) !== false) {
                            $searchMatched = true;
                            break;
                        }
                    }
                }
            }

            if (! $searchMatched) {
                continue;
            }

            // Filter by draft type
            if (! empty($this->draftTypeFilter) && strtolower($group['draft_type']) !== strtolower($this->draftTypeFilter)) {
                continue;
            }

            // Filter by age group
            if (! empty($this->ageGroupFilter) && $group['age_group_id'] != $this->ageGroupFilter) {
                continue;
            }

            // Calculate participant & contingent counts across all match numbers in this group
            $allAthletes = collect();
            $matchIds = [];
            foreach ($group['match_numbers'] as $mn) {
                $allAthletes = $allAthletes->merge($mn->athletes);
                $matchIds[] = $mn->id;
            }
            $allAthletes = $allAthletes->unique('id');
            $participantCount = $allAthletes->count();

            $contingentIds = [];
            foreach ($allAthletes as $athlete) {
                $regId = $athlete->pivot->registration_id;
                if ($regId && isset($registrations[$regId])) {
                    $contingentIds[] = $registrations[$regId];
                } else {
                    $primary = $athlete->contingents->first(fn ($c) => $c->pivot->is_primary);
                    if ($primary) {
                        $contingentIds[] = $primary->id;
                    }
                }
            }
            $contingentCount = count(array_unique($contingentIds));

            // Determine highlight color
            $color = 'green';
            if ($participantCount === 3) {
                if ($contingentCount >= 2) {
                    $color = 'yellow';
                }
            }

            // Filter by highlight color
            if (! empty($this->highlightFilter) && $color !== $this->highlightFilter) {
                continue;
            }

            // Load saved tournament results for constituent match numbers
            $saved = TournamentResult::whereIn('match_number_id', $matchIds)
                ->orderBy('rank')
                ->get()
                ->keyBy('rank');

            $juara = [];
            foreach ($saved as $rank => $res) {
                $juara[$rank] = [
                    'athlete_names' => $res->athlete_names,
                    'contingent_name' => $res->contingent_name,
                ];
            }

            // Map rank 4 to rank 3 if there are exactly 3 participants for Randori in display
            if (strtolower($group['draft_type']) === 'randori' && $participantCount === 3) {
                if (isset($juara[4]) && ! isset($juara[3])) {
                    $juara[3] = $juara[4];
                    unset($juara[4]);
                }
            }

            // Construct display name
            $displayName = $group['name'];
            if ($group['is_merged']) {
                $names = collect($group['match_numbers'])->pluck('name')->unique()->implode(', ');
                $displayName .= ' (Gabungan: '.$names.')';
            }

            $processed[] = [
                'id' => $group['id'],
                'name' => $displayName,
                'draft_type' => strtolower($group['draft_type']) === 'randori' ? 'Randori' : 'Embu',
                'age_group' => $group['age_group'],
                'gender' => $group['gender_indo'],
                'participant_count' => $participantCount,
                'contingent_count' => $contingentCount,
                'color' => $color,
                'juara1' => $juara[1] ?? null,
                'juara2' => $juara[2] ?? null,
                'juara3' => $juara[3] ?? null,
                'juara4' => $juara[4] ?? null,
            ];
        }

        return $processed;
    }

    public function downloadExcel()
    {
        $data = $this->getMatchData();
        $filename = 'Rekap_Laporan_Seluruh_Juara_'.date('Ymd_His').'.xlsx';

        return Excel::download(new LaporanSeluruhJuaraExport($data), $filename);
    }

    public function render()
    {
        $ageGroups = AgeGroup::orderBy('order')->get();
        $matchData = $this->getMatchData();

        return view('livewire.admin.new-laporan-rekap-juara-index', [
            'ageGroups' => $ageGroups,
            'matchData' => $matchData,
        ]);
    }
}

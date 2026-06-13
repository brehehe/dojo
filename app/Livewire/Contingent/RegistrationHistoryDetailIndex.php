<?php

namespace App\Livewire\Contingent;

use App\Models\Group\AgeGroup;
use App\Models\MatchNumber\MatchNumber;
use App\Models\Registration;
use App\Models\Technique\Technique;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.premium')]
class RegistrationHistoryDetailIndex extends Component
{
    public $registration;

    public $allTechniques;

    public array $editingPivotIds = [];

    public array $selectedTechniqueIds = [];

    public string $newTechniqueId = '';

    public function mount($registration): void
    {
        $contingent = Auth::user()->contingent;

        if (! $contingent) {
            abort(403, 'Anda tidak memiliki akses ke data ini.');
        }

        $this->registration = Registration::with([
            'contingent',
            'officials',
            'athletes.matchNumbers',
        ])->where('contingent_id', $contingent->id)
            ->findOrFail($registration);

        $this->allTechniques = Technique::pluck('name', 'id')->toArray();
    }

    public function getFeeDetailsProperty(): array
    {
        $contingentFee = 2500000;
        $ageGroups = AgeGroup::pluck('price', 'name')->toArray();

        $athleteFees = [];
        foreach ($this->registration->athletes as $athlete) {
            $groupName = $athlete->pivot->age_group;
            $price = $ageGroups[$groupName] ?? 0;

            if (! isset($athleteFees[$groupName])) {
                $athleteFees[$groupName] = [
                    'count' => 0,
                    'price' => $price,
                    'total' => 0,
                ];
            }
            $athleteFees[$groupName]['count']++;
            $athleteFees[$groupName]['total'] += $price;
        }

        return [
            'contingent_fee' => $contingentFee,
            'athlete_fees' => $athleteFees,
            'unique_code' => $this->registration->unique_code,
            'total_cost' => $this->registration->total_cost,
            'final_amount' => $this->registration->final_amount,
        ];
    }

    public function getGroupedMatchesProperty(): array
    {
        $pivotRecords = DB::table('athlete_match_number')
            ->where('registration_id', $this->registration->id)
            ->orderBy('id')
            ->get();

        $groupedPivots = $pivotRecords->groupBy('match_number_id');

        $matchNumberIds = $groupedPivots->keys()->toArray();
        $matchNumbers = MatchNumber::with('ageGroup')->whereIn('id', $matchNumberIds)->get()->keyBy('id');

        $regAthletes = $this->registration->athletes->keyBy('id');

        $matches = [];

        foreach ($groupedPivots as $mId => $pivots) {
            $match = $matchNumbers->get($mId);
            if (! $match) {
                continue;
            }

            $maxAthletes = $match->max_athletes ?: 1;

            // Automatically assign team_number if null based on sequential chunking
            $hasNullTeam = $pivots->contains(fn ($p) => is_null($p->team_number));
            if ($hasNullTeam) {
                $tempChunks = $pivots->chunk($maxAthletes);
                foreach ($tempChunks as $cIdx => $chunk) {
                    $assignedNum = $cIdx + 1;
                    foreach ($chunk as $p) {
                        if (is_null($p->team_number)) {
                            DB::table('athlete_match_number')
                                ->where('id', $p->id)
                                ->update(['team_number' => $assignedNum]);
                            $p->team_number = $assignedNum;
                        }
                    }
                }
            }

            // Group by team_number
            $teamGroups = $pivots->groupBy('team_number')->sortKeys();
            $totalTeams = $teamGroups->count();

            foreach ($teamGroups as $teamNum => $chunk) {
                $teamPivotIds = $chunk->pluck('id')->toArray();
                $firstPivot = $chunk->first();
                $techniques = json_decode($firstPivot->technique_ids ?? '[]', true);

                $teamAthletes = [];
                foreach ($chunk as $p) {
                    $ath = $regAthletes->get($p->athlete_id);
                    if ($ath) {
                        $teamAthletes[] = [
                            'model' => $ath,
                            'techniques' => json_decode($p->technique_ids ?? '[]', true),
                            'pivot_id' => $p->id,
                            'team_number' => $p->team_number,
                        ];
                    }
                }

                $matches[] = [
                    'details' => $match,
                    'max_athletes' => $maxAthletes,
                    'techniques' => $techniques,
                    'athletes' => $teamAthletes,
                    'pivot_ids' => $teamPivotIds,
                    'team_number' => $totalTeams > 1 ? $teamNum : null,
                ];
            }
        }

        // Sort by match name
        usort($matches, function ($a, $b) {
            $cmp = strcmp($a['details']->name, $b['details']->name);
            if ($cmp === 0) {
                return ($a['team_number'] ?? 1) <=> ($b['team_number'] ?? 1);
            }

            return $cmp;
        });

        return $matches;
    }

    public function openEditTechniques(array $pivotIds): void
    {
        $this->editingPivotIds = $pivotIds;

        $firstPivotId = $pivotIds[0] ?? null;
        if ($firstPivotId) {
            $firstAthleteMatch = DB::table('athlete_match_number')
                ->where('id', $firstPivotId)
                ->first();

            $this->selectedTechniqueIds = $firstAthleteMatch
                ? (json_decode($firstAthleteMatch->technique_ids ?? '[]', true) ?? [])
                : [];
        } else {
            $this->selectedTechniqueIds = [];
        }

        $this->newTechniqueId = '';
        $this->dispatch('open-techniques-modal');
    }

    public function addTechnique(): void
    {
        if (empty($this->newTechniqueId)) {
            return;
        }

        $this->selectedTechniqueIds[] = (int) $this->newTechniqueId;
        $this->newTechniqueId = '';
    }

    public function removeTechnique(int $index): void
    {
        if (isset($this->selectedTechniqueIds[$index])) {
            unset($this->selectedTechniqueIds[$index]);
            $this->selectedTechniqueIds = array_values($this->selectedTechniqueIds);
        }
    }

    public function moveTechniqueUp(int $index): void
    {
        if ($index > 0 && isset($this->selectedTechniqueIds[$index])) {
            $temp = $this->selectedTechniqueIds[$index - 1];
            $this->selectedTechniqueIds[$index - 1] = $this->selectedTechniqueIds[$index];
            $this->selectedTechniqueIds[$index] = $temp;
        }
    }

    public function moveTechniqueDown(int $index): void
    {
        if ($index < count($this->selectedTechniqueIds) - 1 && isset($this->selectedTechniqueIds[$index])) {
            $temp = $this->selectedTechniqueIds[$index + 1];
            $this->selectedTechniqueIds[$index + 1] = $this->selectedTechniqueIds[$index];
            $this->selectedTechniqueIds[$index] = $temp;
        }
    }

    public function saveTechniques(): void
    {
        if (! empty($this->editingPivotIds)) {
            DB::table('athlete_match_number')
                ->whereIn('id', $this->editingPivotIds)
                ->update([
                    'technique_ids' => json_encode($this->selectedTechniqueIds),
                    'updated_at' => now(),
                ]);
        }

        $this->editingPivotIds = [];
        $this->registration->load(['athletes.matchNumbers', 'officials']);

        $this->dispatch('close-techniques-modal');
        $this->dispatch('swal', [
            'title' => 'Tersimpan!',
            'text' => 'Komposisi teknik berhasil diperbarui.',
            'icon' => 'success',
        ]);
    }

    public function updateAthleteTeam(int $pivotId, int $newTeamNumber): void
    {
        DB::table('athlete_match_number')
            ->where('id', $pivotId)
            ->update([
                'team_number' => $newTeamNumber,
                'updated_at' => now(),
            ]);

        $this->registration->load(['athletes.matchNumbers', 'officials']);
        $this->dispatch('swal', [
            'title' => 'Tim Diubah!',
            'text' => 'Kenshi berhasil dipindahkan ke tim yang dipilih.',
            'icon' => 'success',
        ]);
    }

    public function render()
    {
        return view('livewire.contingent.registration-history-detail-index', [
            'groupedMatches' => $this->groupedMatches,
        ])->title('Detail Riwayat Pendaftaran');
    }
}

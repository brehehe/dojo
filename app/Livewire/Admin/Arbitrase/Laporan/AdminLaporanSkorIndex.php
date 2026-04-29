<?php

namespace App\Livewire\Admin\Arbitrase\Laporan;

use App\Models\Group\AgeGroup;
use App\Models\MatchNumber\MatchNumber;
use App\Models\RandoriMatchResult;
use App\Models\Registration;
use App\Traits\HasExcelExport;
use Illuminate\Support\Collection;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('layouts.admin')]
class AdminLaporanSkorIndex extends Component
{
    use HasExcelExport, WithPagination;

    public string $search = '';

    public string $draftTypeFilter = '';

    public string $ageGroupFilter = '';

    public string $genderFilter = '';

    protected $queryString = [
        'search' => ['except' => ''],
        'draftTypeFilter' => ['except' => ''],
        'ageGroupFilter' => ['except' => ''],
        'genderFilter' => ['except' => ''],
    ];

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function updatingDraftTypeFilter(): void
    {
        $this->resetPage();
    }

    public function updatingAgeGroupFilter(): void
    {
        $this->resetPage();
    }

    public function updatingGenderFilter(): void
    {
        $this->resetPage();
    }

    /**
     * Get ALL scores for an Embu match.
     */
    private function getEmbuScores(MatchNumber $matchNumber): Collection
    {
        $scores = $matchNumber->embuScores()
            ->with(['registration.athletes', 'registration.contingent'])
            ->where('tiebreak_round', 0)
            ->get();

        if ($scores->isEmpty()) {
            return collect();
        }

        $penyisihanMap = $scores->where('round_label', 'Penyisihan')->keyBy('registration_id');
        $finalMap = $scores->where('round_label', 'Final')->keyBy('registration_id');

        // Get all unique registration IDs that have scores
        $registrationIds = $scores->pluck('registration_id')->unique();

        return Registration::whereIn('id', $registrationIds)
            ->with(['athletes', 'contingent'])
            ->get()
            ->map(function ($reg) use ($penyisihanMap, $finalMap, $matchNumber) {
                $pScore = $penyisihanMap[$reg->id] ?? null;
                $fScore = $finalMap[$reg->id] ?? null;

                $pVal = $pScore ? (float) $pScore->nilai_akhir : 0;
                $fVal = $fScore ? (float) $fScore->nilai_akhir : 0;

                // Filter athletes assigned to THIS match
                $matchNumber->loadMissing('athletes');
                $athleteNames = $matchNumber->athletes
                    ->filter(fn ($a) => $a->pivot->registration_id == $reg->id)
                    ->pluck('name')
                    ->join(' & ') ?: ($reg->athletes->pluck('name')->join(' & ') ?: '-');

                return (object) [
                    'registration_id' => $reg->id,
                    'athlete_names' => $athleteNames,
                    'contingent_name' => $reg->contingent?->name ?? '-',
                    'penyisihan_score' => $pVal,
                    'final_score' => $fVal,
                    'accumulated_score' => $pVal + $fVal,
                ];
            })->sortByDesc('accumulated_score')->values();
    }

    /**
     * Get ALL match results for a Randori match with athlete details from drawing.
     */
    private function getRandoriScores(MatchNumber $matchNumber): Collection
    {
        $results = RandoriMatchResult::where('match_number_id', $matchNumber->id)
            ->with(['winner'])
            ->orderBy('bracket_node')
            ->get();

        $drawingData = $matchNumber->drawing_data ?? [];
        $nodeMap = [];

        // Flatten drawing data to map node => athletes
        // Upper Bracket
        foreach ($drawingData['upper_bracket']['rounds'] ?? [] as $rIdx => $round) {
            foreach ($round as $mIdx => $match) {
                $nodeMap["ub_{$rIdx}_{$mIdx}"] = $match;
            }
        }
        // Lower Bracket
        foreach ($drawingData['lower_bracket']['rounds'] ?? [] as $rIdx => $round) {
            foreach ($round as $mIdx => $match) {
                $nodeMap["lb_{$rIdx}_{$mIdx}"] = $match;
            }
        }
        // Grand Final
        if (isset($drawingData['grand_final'])) {
            $nodeMap['gf_0_0'] = $drawingData['grand_final'];
        }

        return $results->map(function ($res) use ($nodeMap) {
            $matchInfo = $nodeMap[$res->bracket_node] ?? null;
            $res->athlete1 = $matchInfo['athlete1'] ?? null;
            $res->athlete2 = $matchInfo['athlete2'] ?? null;

            return $res;
        });
    }

    public function exportExcel()
    {
        $matchNumbers = MatchNumber::with(['ageGroup'])
            ->when($this->search, fn ($q) => $q->where('name', 'ilike', '%'.$this->search.'%'))
            ->when($this->draftTypeFilter, fn ($q) => $q->where('draft_type', $this->draftTypeFilter))
            ->when($this->ageGroupFilter, fn ($q) => $q->where('age_group_id', $this->ageGroupFilter))
            ->when($this->genderFilter, fn ($q) => $q->where('gender', $this->genderFilter))
            ->orderBy('draft_type')
            ->orderBy('age_group_id')
            ->orderBy('order')
            ->get();

        $exportData = [];
        foreach ($matchNumbers as $mn) {
            $isEmbu = strtolower($mn->draft_type) === 'embu';
            $scores = $isEmbu ? $this->getEmbuScores($mn) : $this->getRandoriScores($mn);

            if ($scores->isEmpty()) {
                $exportData[] = [
                    'Nomor Pertandingan' => $mn->name,
                    'Tipe' => $mn->draft_type,
                    'Kategori' => $mn->ageGroup->name ?? '-',
                    'Gender' => $mn->gender,
                    'Data' => 'Belum ada penilaian',
                    'Skor/Status' => '-',
                ];

                continue;
            }

            foreach ($scores as $s) {
                if ($isEmbu) {
                    $exportData[] = [
                        'Nomor Pertandingan' => $mn->name,
                        'Tipe' => $mn->draft_type,
                        'Kategori' => $mn->ageGroup->name ?? '-',
                        'Gender' => $mn->gender,
                        'Data' => $s->athlete_names.' ('.$s->contingent_name.')',
                        'Penyisihan' => $s->penyisihan_score,
                        'Final' => $s->final_score,
                        'Total' => $s->accumulated_score,
                    ];
                } else {
                    // Randori
                    $exportData[] = [
                        'Nomor Pertandingan' => $mn->name,
                        'Tipe' => $mn->draft_type,
                        'Kategori' => $mn->ageGroup->name ?? '-',
                        'Gender' => $mn->gender,
                        'Data' => ($s->athlete1['name'] ?? '?').' vs '.($s->athlete2['name'] ?? '?'),
                        'Pemenang' => $s->winner->name ?? 'Belum ada',
                        'Status' => $s->status,
                    ];
                }
            }
        }

        $headings = $this->draftTypeFilter === 'embu'
            ? ['Nomor Pertandingan', 'Tipe', 'Kategori', 'Gender', 'Data (Atlet & Kontingen)', 'Penyisihan', 'Final', 'Total']
            : ['Nomor Pertandingan', 'Tipe', 'Kategori', 'Gender', 'Data (Pertandingan)', 'Pemenang', 'Status'];

        return $this->downloadExcel(
            $exportData,
            $headings,
            'Laporan_Skor_Menyeluruh',
            'Skor Menyeluruh'
        );
    }

    public function render()
    {
        $ageGroups = AgeGroup::orderBy('order')->get();

        $matchNumbers = MatchNumber::with(['ageGroup'])
            ->when($this->search, fn ($q) => $q->where('name', 'ilike', '%'.$this->search.'%'))
            ->when($this->draftTypeFilter, fn ($q) => $q->where('draft_type', $this->draftTypeFilter))
            ->when($this->ageGroupFilter, fn ($q) => $q->where('age_group_id', $this->ageGroupFilter))
            ->when($this->genderFilter, fn ($q) => $q->where('gender', $this->genderFilter))
            ->orderBy('draft_type')
            ->orderBy('age_group_id')
            ->orderBy('order')
            ->paginate(5); // Show fewer per page because details are long

        // Attach all score details for each match
        $matchNumbers->getCollection()->transform(function ($matchNumber) {
            if (strtolower($matchNumber->draft_type) === 'embu') {
                $matchNumber->all_scores = $this->getEmbuScores($matchNumber);
            } else {
                $matchNumber->all_scores = $this->getRandoriScores($matchNumber);
            }

            return $matchNumber;
        });

        return view('livewire.admin.arbitrase.laporan.admin-laporan-skor-index', [
            'matchNumbers' => $matchNumbers,
            'ageGroups' => $ageGroups,
        ]);
    }
}

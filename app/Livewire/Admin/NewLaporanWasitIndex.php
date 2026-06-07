<?php

namespace App\Livewire\Admin;

use App\Exports\RefereeAnalysisExport;
use App\Models\Court\Court;
use App\Models\Group\AgeGroup;
use App\Models\MatchNumber\MatchNumber;
use App\Models\Referee;
use App\Models\RefereeScoreDetail;
use App\Models\Registration;
use App\Traits\HasRefereeAnalysis;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithPagination;
use Maatwebsite\Excel\Facades\Excel;

#[Layout('layouts.premium')]
class NewLaporanWasitIndex extends Component
{
    use HasRefereeAnalysis, WithPagination;

    public string $tab = 'skw';

    public $search = '';

    public $ageGroupFilter = '';

    public $matchNumberFilter = '';

    public $refereeFilter = '';

    public $genderFilter = '';

    public $roundFilter = '';

    public $courtFilter = '';

    public $draftTypeFilter = '';

    protected $queryString = [
        'tab' => ['except' => 'skw'],
        'search' => ['except' => ''],
        'ageGroupFilter' => ['except' => ''],
        'matchNumberFilter' => ['except' => ''],
        'refereeFilter' => ['except' => ''],
        'genderFilter' => ['except' => ''],
        'roundFilter' => ['except' => ''],
        'courtFilter' => ['except' => ''],
        'draftTypeFilter' => ['except' => ''],
    ];

    public function updated($property)
    {
        if ($property !== 'tab') {
            $this->resetPage();
        }

        // Dispatch event for chart update
        $this->dispatch('refreshChart');
    }

    public function render()
    {
        $filters = [
            'search' => $this->search,
            'ageGroupFilter' => $this->ageGroupFilter,
            'matchNumberFilter' => $this->matchNumberFilter,
            'refereeFilter' => $this->refereeFilter,
            'genderFilter' => $this->genderFilter,
            'roundFilter' => $this->roundFilter,
            'courtFilter' => $this->courtFilter,
            'draftTypeFilter' => $this->draftTypeFilter,
        ];

        $refereeAnalysis = $this->getRefereeAnalysis($filters);

        // Data for Chart (Top 10 Referees by SKW)
        $performanceData = $refereeAnalysis->sortByDesc('skw')->take(10)->values()->map(fn ($rf) => [
            'name' => $rf['name'],
            'skw' => (float) $rf['skw'],
            'iaw' => (float) $rf['iaw'],
            'ik' => (float) ($rf['ik'] * 100),
            'iv' => (float) ($rf['iv'] * 100),
        ]);

        $counts = $refereeAnalysis->countBy('grade');
        $gradeDistribution = collect(['A' => 0, 'B' => 0, 'C' => 0, 'D' => 0, 'E' => 0])
            ->merge($counts)
            ->only(['A', 'B', 'C', 'D', 'E']);
        $gradeData = $gradeDistribution->map(fn ($val, $key) => ['name' => $key, 'value' => $val])->values();

        $this->dispatch('refreshChart', [
            'performance' => $performanceData,
            'grades' => $gradeData,
        ]);

        // Get detailed log for the bottom section
        $query = RefereeScoreDetail::with(['referee', 'scorable.contingent'])
            ->join('drawing_match_numbers', function ($join) {
                $join->on('referee_score_details.match_number_id', '=', 'drawing_match_numbers.match_number_id')
                    ->on('referee_score_details.scorable_id', '=', 'drawing_match_numbers.registration_id');
            })
            ->join('courts', 'drawing_match_numbers.court_id', '=', 'courts.id')
            ->select('referee_score_details.*', 'courts.name as court_name')
            ->where('scorable_type', Registration::class)
            ->where('total_calculated_score', '>', 0);

        // Apply filters to log query
        if (! empty($this->search)) {
            $query->whereHas('scorable.athletes', function ($q) {
                $q->where('name', 'ilike', '%'.$this->search.'%');
            });
        }
        if (! empty($this->ageGroupFilter)) {
            $query->whereHas('matchNumber', function ($q) {
                $q->where('age_group_id', $this->ageGroupFilter);
            });
        }
        if (! empty($this->matchNumberFilter)) {
            $matchId = $this->matchNumberFilter;
            $mergeDetails = DB::table('match_number_merge_details')
                ->where('match_number_id', $matchId)
                ->first();

            if ($mergeDetails) {
                $ids = DB::table('match_number_merge_details')
                    ->where('match_number_merge_id', $mergeDetails->match_number_merge_id)
                    ->pluck('match_number_id')
                    ->toArray();
                $query->whereIn('referee_score_details.match_number_id', $ids);
            } else {
                $query->where('referee_score_details.match_number_id', $matchId);
            }
        }
        if (! empty($this->refereeFilter)) {
            $query->where('referee_id', $this->refereeFilter);
        }
        if (! empty($this->genderFilter)) {
            $query->whereHas('matchNumber', function ($q) {
                $q->where('gender', $this->genderFilter);
            });
        }
        if (! empty($this->roundFilter)) {
            $query->where('drawing_match_numbers.round', $this->roundFilter);
        }
        if (! empty($this->courtFilter)) {
            $query->where('drawing_match_numbers.court_id', $this->courtFilter);
        }

        $assessments = $query->latest('referee_score_details.created_at')
            ->paginate(15)
            ->through(function ($item) {
                $details = $item->details ?? [];
                $teknik = 0;
                $ekspresi = 0;
                foreach ($details as $key => $val) {
                    if (str_starts_with($key, 'goho_') || str_starts_with($key, 'juho_')) {
                        $teknik += (float) $val;
                    } elseif (str_starts_with($key, 'ekspresi_')) {
                        $ekspresi += (float) $val;
                    }
                }

                return (object) [
                    'date' => $item->created_at->format('d/m/Y'),
                    'court' => $item->court_name ?? 'N/A',
                    'referee' => $item->referee->name,
                    'contingent' => $item->scorable->contingent->name ?? 'N/A',
                    'teknik' => round($teknik, 2),
                    'ekspresi' => round($ekspresi, 2),
                    'total' => round($item->total_calculated_score, 2),
                ];
            });

        return view('livewire.admin.new-laporan-wasit-index', [
            'refereeAnalysis' => $refereeAnalysis,
            'assessments' => $assessments,
            'chartData' => $performanceData,
            'ageGroups' => AgeGroup::all(),
            'matchNumbers' => MatchNumber::leftJoin('match_number_merge_details', 'match_numbers.id', '=', 'match_number_merge_details.match_number_id')
                ->leftJoin('match_number_merges', 'match_number_merge_details.match_number_merge_id', '=', 'match_number_merges.id')
                ->where(function ($q) {
                    $q->whereNull('match_number_merge_details.match_number_merge_id')
                        ->orWhereRaw('match_numbers.id = (SELECT MIN(m2.match_number_id) FROM match_number_merge_details m2 WHERE m2.match_number_merge_id = match_number_merge_details.match_number_merge_id)');
                })
                ->orderBy('match_numbers.name')
                ->select('match_numbers.*', 'match_number_merges.name as merge_group_name', 'match_number_merge_details.match_number_merge_id')
                ->get()
                ->map(function ($m) {
                    if ($m->match_number_merge_id) {
                        $mergedNames = DB::table('match_number_merge_details')
                            ->join('match_numbers', 'match_number_merge_details.match_number_id', '=', 'match_numbers.id')
                            ->where('match_number_merge_details.match_number_merge_id', $m->match_number_merge_id)
                            ->pluck('match_numbers.name')
                            ->join(', ');
                        $m->display_name = ($m->merge_group_name ?: 'Merged Group').' ('.$mergedNames.')';
                    } else {
                        $m->display_name = $m->name;
                    }

                    return $m;
                }),
            'referees' => Referee::all(),
            'courts' => Court::all(),
        ])->title('Laporan Penilaian Wasit');
    }

    public function exportExcel()
    {
        $filters = [
            'search' => $this->search,
            'ageGroupFilter' => $this->ageGroupFilter,
            'matchNumberFilter' => $this->matchNumberFilter,
            'refereeFilter' => $this->refereeFilter,
            'genderFilter' => $this->genderFilter,
            'roundFilter' => $this->roundFilter,
            'courtFilter' => $this->courtFilter,
            'draftTypeFilter' => $this->draftTypeFilter,
        ];

        $data = $this->getRefereeAnalysis($filters);
        $type = strtoupper($this->tab);
        if ($type === 'FULL') {
            $type = 'SKW';
        }

        return Excel::download(
            new RefereeAnalysisExport($data, $type),
            'Laporan_Penilaian_Wasit_'.$type.'_'.now()->format('Ymd_His').'.xlsx'
        );
    }
}

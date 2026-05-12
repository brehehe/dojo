<?php

namespace App\Livewire\Contingent;

use App\Models\Court\Court;
use App\Models\Group\AgeGroup;
use App\Models\MatchNumber\MatchNumber;
use App\Models\Referee;
use App\Models\RefereeScoreDetail;
use App\Models\Registration;
use App\Traits\HasRefereeAnalysis;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('layouts.premium')]
class LaporanWasit extends Component
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

    public $contingent;

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

    public function mount()
    {
        $user = Auth::user();
        if (!$user->contingent()->exists()) {
            return redirect()->route('contingent.setup');
        }
        $this->contingent = $user->contingent;
    }

    public function updated($property)
    {
        if ($property !== 'tab') {
            $this->resetPage();
        }
        
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
            'contingentId' => $this->contingent->id, // LOCKED TO OWN CONTINGENT
        ];

        $refereeAnalysis = $this->getRefereeAnalysis($filters);
        
        // Data for Chart (Top 10 Referees by SKW)
        $performanceData = $refereeAnalysis->sortByDesc('skw')->take(10)->values()->map(fn($rf) => [
            'name' => $rf['name'],
            'skw' => (float) $rf['skw'],
            'iaw' => (float) $rf['iaw'],
            'ik' => (float) ($rf['ik'] * 100),
            'iv' => (float) ($rf['iv'] * 100),
        ]);

        $gradeDistribution = collect(['A' => 0, 'B' => 0, 'C' => 0, 'D' => 0, 'E' => 0]);
        $refereeAnalysis->each(function ($rf) use (&$gradeDistribution) {
            if ($gradeDistribution->has($rf['grade'])) {
                $gradeDistribution[$rf['grade']]++;
            }
        });
        $gradeData = $gradeDistribution->map(fn($val, $key) => ['name' => $key, 'value' => $val])->values();

        $this->dispatch('refreshChart', [
            'performance' => $performanceData,
            'grades' => $gradeData,
        ]);

        // Get detailed log for the bottom section
        $query = RefereeScoreDetail::with(['referee', 'scorable.athletes'])
            ->join('drawing_match_numbers', function ($join) {
                $join->on('referee_score_details.match_number_id', '=', 'drawing_match_numbers.match_number_id')
                    ->on('referee_score_details.scorable_id', '=', 'drawing_match_numbers.registration_id');
            })
            ->join('courts', 'drawing_match_numbers.court_id', '=', 'courts.id')
            ->select('referee_score_details.*', 'courts.name as court_name')
            ->where('scorable_type', Registration::class)
            ->whereHas('scorable', function($q) {
                $q->where('contingent_id', $this->contingent->id);
            })
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
            $query->where('referee_score_details.match_number_id', $this->matchNumberFilter);
        }
        if (! empty($this->refereeFilter)) {
            $query->where('referee_id', $this->refereeFilter);
        }
        if (! empty($this->courtFilter)) {
            $query->where('drawing_match_numbers.court_id', $this->courtFilter);
        }
        if (! empty($this->draftTypeFilter)) {
            $query->whereHas('matchNumber', function($q) {
                $q->where('draft_type', $this->draftTypeFilter);
            });
        }

        $assessments = $query->latest('referee_score_details.created_at')
            ->paginate(20)
            ->through(function ($item) {
                $details = is_array($item->details) ? $item->details : json_decode($item->details, true);
                $teknik = $details['teknik'] ?? $details['score_teknik'] ?? 0;
                $ekspresi = $details['ekspresi'] ?? $details['score_ekspresi'] ?? 0;

                return (object) [
                    'date' => $item->created_at->format('d/m H:i'),
                    'court' => $item->court_name,
                    'referee' => $item->referee->name,
                    'contingent' => $item->scorable->contingent->name ?? 'N/A',
                    'teknik' => round($teknik, 2),
                    'ekspresi' => round($ekspresi, 2),
                    'total' => round($item->total_calculated_score, 2),
                ];
            });

        return view('livewire.contingent.laporan-wasit', [
            'refereeAnalysis' => $refereeAnalysis,
            'assessments' => $assessments,
            'chartData' => $performanceData,
            'ageGroups' => AgeGroup::all(),
            'matchNumbers' => MatchNumber::all(),
            'referees' => Referee::all(),
            'courts' => Court::all(),
        ])->title('Laporan Penilaian Wasit - ' . $this->contingent->name);
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
            'contingentId' => $this->contingent->id,
        ];

        $data = $this->getRefereeAnalysis($filters);
        $type = strtoupper($this->tab);
        if ($type === 'FULL' || $type === 'DETAIL') $type = 'SKW';

        return \Maatwebsite\Excel\Facades\Excel::download(
            new \App\Exports\RefereeAnalysisExport($data, $type),
            'Laporan_Wasit_' . str_replace(' ', '_', $this->contingent->name) . '.xlsx'
        );
    }
}

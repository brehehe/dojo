<?php

namespace App\Livewire\Admin\SmartWasit;

use App\Exports\RefereeAnalysisExport;
use App\Models\Court\Court;
use App\Models\Group\AgeGroup;
use App\Models\MatchNumber\MatchNumber;
use App\Models\Referee;
use App\Models\RefereeScoreDetail;
use App\Models\Registration;
use App\Traits\HasRefereeAnalysis;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithPagination;
use Maatwebsite\Excel\Facades\Excel;

#[Layout('layouts.premium')]
class NewLaporanSmartWasitSummaryIndex extends Component
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

    protected $queryString = [
        'tab' => ['except' => 'skw'],
        'search' => ['except' => ''],
        'ageGroupFilter' => ['except' => ''],
        'matchNumberFilter' => ['except' => ''],
        'refereeFilter' => ['except' => ''],
        'genderFilter' => ['except' => ''],
        'roundFilter' => ['except' => ''],
        'courtFilter' => ['except' => ''],
    ];

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
        ];

        $refereeAnalysis = $this->getRefereeAnalysis($filters);

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

        // Apply filters to log query as well
        if (! empty($this->search)) {
            $query->whereHas('scorable.athletes', function ($q) {
                $q->where('name', 'like', '%'.$this->search.'%');
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

        if (! empty($this->genderFilter)) {
            $query->whereHas('matchNumber', function ($q) {
                $q->where('gender', $this->genderFilter);
            });
        }

        if (! empty($this->roundFilter)) {
            $query->where('drawing_match_numbers.round_name', $this->roundFilter);
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

        return view('livewire.admin.smart-wasit.new-laporan-smart-wasit-summary-index', [
            'refereeAnalysis' => $refereeAnalysis,
            'assessments' => $assessments,
            'ageGroups' => AgeGroup::all(),
            'matchNumbers' => MatchNumber::all(),
            'referees' => Referee::all(),
            'courts' => Court::all(),
        ])->title('Laporan Smart Wasit');
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
        ];

        $data = $this->getRefereeAnalysis($filters);
        $type = strtoupper($this->tab);

        if ($type === 'FULL') {
            $type = 'SKW';
        }

        return Excel::download(
            new RefereeAnalysisExport($data, $type),
            'Laporan_Smart_Wasit_'.$type.'_'.now()->format('Ymd_His').'.xlsx'
        );
    }
}

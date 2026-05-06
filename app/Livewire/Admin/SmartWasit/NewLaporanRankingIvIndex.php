<?php

namespace App\Livewire\Admin\SmartWasit;

use App\Exports\RefereeAnalysisExport;
use App\Models\Group\AgeGroup;
use App\Models\MatchNumber\MatchNumber;
use App\Models\Referee;
use App\Traits\HasExcelExport;
use App\Traits\HasRefereeAnalysis;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithPagination;
use Maatwebsite\Excel\Facades\Excel;

#[Layout('layouts.premium')]
class NewLaporanRankingIvIndex extends Component
{
    use HasExcelExport, HasRefereeAnalysis, WithPagination;

    public string $search = '';

    public string $ageGroupFilter = '';

    public string $genderFilter = '';

    public string $matchNumberFilter = '';

    public string $refereeFilter = '';

    protected $queryString = [
        'search' => ['except' => ''],
        'ageGroupFilter' => ['except' => ''],
        'genderFilter' => ['except' => ''],
        'matchNumberFilter' => ['except' => ''],
        'refereeFilter' => ['except' => ''],
    ];

    public function render()
    {
        $ageGroups = AgeGroup::orderBy('order')->get();
        $referees = Referee::join('users', 'referees.user_id', '=', 'users.id')
            ->select('referees.*')
            ->orderBy('users.name')
            ->get();
        $matchNumbersForFilter = MatchNumber::where('draft_type', 'embu')->orderBy('name')->get();

        $refereeAnalysis = $this->getRefereeAnalysis()->sortByDesc('iv');

        return view('livewire.admin.smart-wasit.new-laporan-ranking-iv-index', [
            'ageGroups' => $ageGroups,
            'referees' => $referees,
            'matchNumbersForFilter' => $matchNumbersForFilter,
            'refereeAnalysis' => $refereeAnalysis,
        ])->title('Ranking IV');
    }

    public function exportExcel()
    {
        $data = $this->getRefereeAnalysis();

        return Excel::download(
            new RefereeAnalysisExport($data, 'IV'),
            'Ranking_IV_'.now()->format('Ymd_His').'.xlsx'
        );
    }
}

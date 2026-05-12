<?php

namespace App\Livewire\Admin;

use App\Models\Court\Court;
use App\Models\DrawingMatchNumber;
use App\Models\Group\AgeGroup;
use App\Models\MatchNumber\MatchNumber;
use App\Models\Pool\Pool;
use App\Models\Referee;
use App\Models\RefereeScoreDetail;
use App\Models\Registration;
use App\Models\Rundown\Rundown;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('layouts.premium')]
class NewLaporanWasitJuriIndex extends Component
{
    use WithPagination;

    public string $search = '';

    public string $ageGroupFilter = '';

    public string $matchNumberFilter = '';

    public string $refereeFilter = '';

    public string $genderFilter = '';

    public string $courtFilter = '';

    public string $poolFilter = '';

    public string $rundownFilter = '';

    protected $queryString = [
        'search' => ['except' => ''],
        'ageGroupFilter' => ['except' => ''],
        'matchNumberFilter' => ['except' => ''],
        'refereeFilter' => ['except' => ''],
        'genderFilter' => ['except' => ''],
        'courtFilter' => ['except' => ''],
        'poolFilter' => ['except' => ''],
        'rundownFilter' => ['except' => ''],
    ];

    public function updated($property): void
    {
        $this->resetPage();
    }

    public function render()
    {
        // Primary query: one row = one referee score entry per match
        $query = RefereeScoreDetail::with([
            'referee',
            'matchNumber.ageGroup',
        ])
            ->where('scorable_type', Registration::class)
            // Inner-join to drawing_match_numbers to get context (court, pool, etc.)
            ->join('drawing_match_numbers as dmn', function ($join) {
                $join->on('referee_score_details.match_number_id', '=', 'dmn.match_number_id')
                    ->on('referee_score_details.scorable_id', '=', 'dmn.registration_id');
            })
            ->join('registrations', 'referee_score_details.scorable_id', '=', 'registrations.id')
            ->join('contingents', 'registrations.contingent_id', '=', 'contingents.id')
            ->select(
                'referee_score_details.*',
                'dmn.id as drawing_id',
                'dmn.court_id',
                'dmn.pool_id',
                'dmn.rundown_id',
                'dmn.session_time_id',
                'dmn.round',
                'contingents.name as contingent_name',
            );

        // Filters
        if (! empty($this->refereeFilter)) {
            $query->where('referee_score_details.referee_id', $this->refereeFilter);
        }
        if (! empty($this->matchNumberFilter)) {
            $query->where('referee_score_details.match_number_id', $this->matchNumberFilter);
        }
        if (! empty($this->ageGroupFilter)) {
            $query->whereHas('matchNumber', fn ($q) => $q->where('age_group_id', $this->ageGroupFilter));
        }
        if (! empty($this->genderFilter)) {
            $query->whereHas('matchNumber', fn ($q) => $q->where('gender', $this->genderFilter));
        }
        if (! empty($this->courtFilter)) {
            $query->where('dmn.court_id', $this->courtFilter);
        }
        if (! empty($this->poolFilter)) {
            $query->where('dmn.pool_id', $this->poolFilter);
        }
        if (! empty($this->rundownFilter)) {
            $query->where('dmn.rundown_id', $this->rundownFilter);
        }
        if (! empty($this->search)) {
            $search = $this->search;
            $query->where(function ($q) use ($search) {
                $q->where('contingents.name', 'ilike', '%'.$search.'%')
                    ->orWhereHas('referee', fn ($r) => $r->where('name', 'ilike', '%'.$search.'%'));
            });
        }

        $scoreRows = $query->orderBy('referee_score_details.match_number_id')
            ->orderBy('referee_score_details.referee_id')
            ->orderBy('referee_score_details.judge_index')
            ->paginate(20);

        // Eager-load related drawing context for the current page in bulk
        $drawingIds = $scoreRows->pluck('drawing_id')->unique()->filter();
        $drawings = DrawingMatchNumber::with(['court', 'pool', 'rundown', 'sessionTime'])
            ->whereIn('id', $drawingIds)
            ->get()
            ->keyBy('id');

        // Chart: total score per referee (across current page)
        $chartByReferee = collect();
        foreach ($scoreRows as $row) {
            $name = $row->referee?->name ?? 'Unknown';
            if (! $chartByReferee->has($name)) {
                $chartByReferee[$name] = ['total' => 0, 'count' => 0];
            }
            $chartByReferee[$name] = [
                'total' => $chartByReferee[$name]['total'] + (float) $row->total_calculated_score,
                'count' => $chartByReferee[$name]['count'] + 1,
            ];
        }

        $chartData = $chartByReferee->map(fn ($v, $k) => [
            'name' => $k,
            'avg' => $v['count'] > 0 ? round($v['total'] / $v['count'], 2) : 0,
            'total' => round($v['total'], 2),
            'count' => $v['count'],
        ])->values();

        return view('livewire.admin.new-laporan-wasit-juri-index', [
            'scoreRows' => $scoreRows,
            'drawings' => $drawings,
            'chartData' => $chartData,
            'ageGroups' => AgeGroup::all(),
            'matchNumbers' => MatchNumber::where('draft_type', 'embu')->get(),
            'referees' => Referee::with('user')->join('users', 'referees.user_id', '=', 'users.id')->orderBy('users.name')->select('referees.*')->get(),
            'courts' => Court::orderBy('order')->get(),
            'pools' => Pool::orderBy('name')->get(),
            'rundowns' => Rundown::orderBy('order')->get(),
        ])->title('Laporan Analisis Per Juri');
    }
}

<?php

namespace App\Livewire\Admin;

use App\Models\ActiveCourtReferee;
use App\Models\Contingent;
use App\Models\Court\Court;
use App\Models\DrawingMatchNumber;
use App\Models\Group\AgeGroup;
use App\Models\MatchNumber\MatchNumber;
use App\Models\Pool\Pool;
use App\Models\Rundown\Rundown;
use App\Models\SessionTime;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('layouts.premium')]
class NewRekapitulasiEmbuIndex extends Component
{
    use WithPagination;

    public string $search = '';

    public string $filterCourt = '';

    public string $filterSession = '';

    public string $filterRundown = '';

    public string $filterPool = '';

    public string $filterRound = '';

    public string $filterType = 'embu'; // Default to embu

    public string $filterContingent = '';

    public string $filterAgeGroup = '';

    public string $filterMatchNumber = '';

    public string $filterGender = '';

    public function updated(string $property): void
    {
        if (str_starts_with($property, 'filter') || $property === 'search') {
            $this->resetPage();
        }
    }

    public function resetFilters(): void
    {
        $this->search = '';
        $this->filterCourt = '';
        $this->filterSession = '';
        $this->filterRundown = '';
        $this->filterPool = '';
        $this->filterRound = '';
        $this->filterType = 'embu';
        $this->filterContingent = '';
        $this->filterAgeGroup = '';
        $this->filterMatchNumber = '';
        $this->filterGender = '';

        $this->resetPage();
    }

    public function render()
    {
        $query = Court::with([
            'activeMatch',
            'activeDrawing.pool',
            'activeDrawing.sessionTime',
            'activeDrawing.rundown',
            'activeDrawing.registration.contingent',
        ])->orderBy('order');

        if (auth()->user()->court_id) {
            $query->where('id', auth()->user()->court_id);
        }

        $courts = $query->get();

        foreach ($courts as $court) {
            $court->current_referees = ActiveCourtReferee::with('referee.user')
                ->where('court_id', $court->id)
                ->orderBy('judge_index')
                ->get();
        }

        $sessions = SessionTime::orderBy('start_time')->get();
        $rundowns = Rundown::orderBy('date')->get();
        $pools = Pool::orderBy('order')->get();
        $contingents = Contingent::orderBy('name')->get();

        $rounds = DrawingMatchNumber::whereNotNull('round')
            ->distinct()
            ->orderBy('round')
            ->pluck('round');

        $ageGroups = AgeGroup::orderBy('order')->get();

        $matchNumberQuery = MatchNumber::orderBy('name');
        if ($this->filterAgeGroup) {
            $matchNumberQuery->where('age_group_id', $this->filterAgeGroup);
        }
        if ($this->filterGender) {
            $matchNumberQuery->where('gender', $this->filterGender);
        }
        if ($this->filterType) {
            $matchNumberQuery->where('draft_type', $this->filterType);
        }
        $matchNumbers = $matchNumberQuery->get();

        $query = DrawingMatchNumber::query()
            ->join('match_numbers', 'drawing_match_numbers.match_number_id', '=', 'match_numbers.id')
            ->leftJoin('match_number_merge_details', 'match_numbers.id', '=', 'match_number_merge_details.match_number_id')
            ->leftJoin('match_number_merges', 'match_number_merge_details.match_number_merge_id', '=', 'match_number_merges.id')
            ->select(
                'drawing_match_numbers.court_id',
                'drawing_match_numbers.pool_id',
                'drawing_match_numbers.session_time_id',
                'drawing_match_numbers.rundown_id',
                'drawing_match_numbers.round',
                'drawing_match_numbers.draft_type'
            )
            ->selectRaw('COALESCE(MAX(match_number_merges.name), \'\') as merge_name')
            ->selectRaw('STRING_AGG(DISTINCT match_numbers.name, \', \') as aggregated_match_names')
            ->selectRaw('MIN(drawing_match_numbers.match_number_id) as match_number_id')
            ->selectRaw('MIN(drawing_match_numbers.id) as id')
            ->selectRaw('COUNT(drawing_match_numbers.registration_id) as total_athletes')
            ->selectRaw('MIN(drawing_match_numbers.sequence_number) as sequence_number')
            ->where('drawing_match_numbers.draft_type', 'embu') // FORCE EMBU
            ->groupBy(
                'drawing_match_numbers.court_id',
                'drawing_match_numbers.pool_id',
                'drawing_match_numbers.session_time_id',
                'drawing_match_numbers.rundown_id',
                'drawing_match_numbers.round',
                'drawing_match_numbers.draft_type',
                DB::raw('COALESCE(match_number_merges.id, -drawing_match_numbers.match_number_id)')
            )
            ->with([
                'matchNumber.ageGroup',
                'pool',
                'court',
                'sessionTime',
                'rundown',
            ]);

        if (! empty($this->filterCourt)) {
            $query->where('court_id', $this->filterCourt);
        }
        if (! empty($this->filterSession)) {
            $query->where('session_time_id', $this->filterSession);
        }
        if (! empty($this->filterRundown)) {
            $query->where('rundown_id', $this->filterRundown);
        }
        if (! empty($this->filterPool)) {
            $query->where('pool_id', $this->filterPool);
        }
        if (! empty($this->filterRound)) {
            $query->where('round', $this->filterRound);
        }
        if (! empty($this->filterAgeGroup)) {
            $query->whereHas('matchNumber', function ($q) {
                $q->where('age_group_id', $this->filterAgeGroup);
            });
        }
        if (! empty($this->filterMatchNumber)) {
            $query->where('match_number_id', $this->filterMatchNumber);
        }
        if (! empty($this->filterGender)) {
            $query->whereHas('matchNumber', function ($q) {
                $q->where('gender', $this->filterGender);
            });
        }

        if (auth()->user()->court_id) {
            $query->where('court_id', auth()->user()->court_id);
        }

        if (! empty($this->filterContingent)) {
            $query->whereHas('registration.contingent', function ($q) {
                $q->where('contingents.id', $this->filterContingent);
            });
        }

        if (! empty($this->search)) {
            $search = $this->search;
            $query->where(function ($q) use ($search) {
                $q->whereHas('matchNumber', fn ($mq) => $mq->where('name', 'ilike', '%'.$search.'%'))
                    ->orWhereHas('registration.contingent', fn ($cq) => $cq->where('name', 'ilike', '%'.$search.'%'));
            });
        }

        $query->orderBy('rundown_id')->orderBy('session_time_id')->orderByRaw('MIN(sequence_number)');

        return view('livewire.admin.new-rekapitulasi-embu-index', [
            'drawings' => $query->paginate(10),
            'courts' => $courts,
            'sessions' => $sessions,
            'rundowns' => $rundowns,
            'pools' => $pools,
            'contingents' => $contingents,
            'rounds' => $rounds,
            'ageGroups' => $ageGroups,
            'matchNumbers' => $matchNumbers,
        ]);
    }
}

<?php

namespace App\Http\Controllers;

use App\Events\CourtUpdated;
use App\Http\Requests\ActivateMatchRequest;
use App\Http\Requests\ClearCourtRequest;
use App\Models\ActiveCourtReferee;
use App\Models\Athlete;
use App\Models\Contingent;
use App\Models\Court\Court;
use App\Models\DrawingMatchNumber;
use App\Models\EmbuScore;
use App\Models\Group\AgeGroup;
use App\Models\MatchNumber\MatchNumber;
use App\Models\Pool\Pool;
use App\Models\RandoriMatchResult;
use App\Models\Referee;
use App\Models\Rundown\Rundown;
use App\Models\SessionTime;
use App\Services\StateCache;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;
use Inertia\Response;

class ScoringDashboardController extends Controller
{
    public function __construct(
        protected StateCache $stateCache,
    ) {}

    public function scoringIndex(): Response
    {
        return Inertia::render('ScoringDashboard');
    }

    public function scoringEmbu(Request $request, MatchNumber $matchNumber): Response
    {
        return Inertia::render('ScoringEmbu', [
            'matchId' => $matchNumber->id,
            'urlRound' => $request->query('round'),
            'urlPoolId' => $request->query('pool_id') ? (int) $request->query('pool_id') : null,
            'urlFrom' => $request->query('from'),
        ]);
    }

    public function scoringRandori(Request $request, MatchNumber $matchNumber): Response
    {
        return Inertia::render('ScoringRandori', [
            'matchId' => $matchNumber->id,
            'urlRound' => $request->query('round'),
            'urlPoolId' => $request->query('pool_id') ? (int) $request->query('pool_id') : null,
            'urlFrom' => $request->query('from'),
        ]);
    }

    public function scoringDashboardState(Request $request): JsonResponse
    {
        $versions = ['dashboard' => $this->stateCache->version('dashboard')];
        if ($this->stateCache->hasValidEtag($request, $versions)) {
            return $this->stateCache->respond304($request, $versions);
        }

        $search = $request->input('search', '');
        $filterCourt = $request->input('filterCourt', '');
        $filterSession = $request->input('filterSession', '');
        $filterRundown = $request->input('filterRundown', '');
        $filterPool = $request->input('filterPool', '');
        $filterRound = $request->input('filterRound', '');
        $filterType = $request->input('filterType', '');
        $filterContingent = $request->input('filterContingent', '');
        $filterAgeGroup = $request->input('filterAgeGroup', '');
        $filterMatchNumber = $request->input('filterMatchNumber', '');
        $filterGender = $request->input('filterGender', '');
        $searchReferee = $request->input('searchReferee', '');

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

        $refereesByCourt = ActiveCourtReferee::with('referee.user')
            ->whereIn('court_id', $courts->pluck('id'))
            ->orderBy('judge_index')
            ->get()
            ->groupBy('court_id');

        foreach ($courts as $court) {
            $court->current_referees = $refereesByCourt->get($court->id, collect())->values();
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
        if ($filterAgeGroup) {
            $matchNumberQuery->where('age_group_id', $filterAgeGroup);
        }
        if ($filterGender) {
            $matchNumberQuery->where('gender', $filterGender);
        }
        if ($filterType) {
            $matchNumberQuery->where('draft_type', $filterType);
        }
        $matchNumbers = $matchNumberQuery->get();

        // Core query: grouped per scheduled match session
        $drawingsQuery = DrawingMatchNumber::query()
            ->join('match_numbers', 'drawing_match_numbers.match_number_id', '=', 'match_numbers.id')
            ->leftJoin('match_number_merge_details', 'match_numbers.id', '=', 'match_number_merge_details.match_number_id')
            ->leftJoin('match_number_merges', 'match_number_merge_details.match_number_merge_id', '=', 'match_number_merges.id')
            ->select(
                'drawing_match_numbers.court_id',
                'drawing_match_numbers.pool_id',
                'drawing_match_numbers.session_time_id',
                'drawing_match_numbers.rundown_id',
                'drawing_match_numbers.draft_type'
            )
            ->selectRaw("CASE WHEN drawing_match_numbers.draft_type = 'randori' THEN 'Full Bracket' ELSE drawing_match_numbers.round END as round")
            ->selectRaw('COALESCE(MAX(match_number_merges.name), \'\') as merge_name')
            ->selectRaw(DB::connection()->getDriverName() === 'sqlite'
                ? 'group_concat(DISTINCT match_numbers.name) as aggregated_match_names'
                : 'STRING_AGG(DISTINCT match_numbers.name, \', \') as aggregated_match_names')
            ->selectRaw('MIN(drawing_match_numbers.match_number_id) as match_number_id')
            ->selectRaw('MIN(drawing_match_numbers.id) as id')
            ->selectRaw('COUNT(drawing_match_numbers.registration_id) as total_athletes')
            ->selectRaw('MIN(drawing_match_numbers.sequence_number) as sequence_number')
            ->groupBy(
                'drawing_match_numbers.court_id',
                'drawing_match_numbers.pool_id',
                'drawing_match_numbers.session_time_id',
                'drawing_match_numbers.rundown_id',
                DB::raw("CASE WHEN drawing_match_numbers.draft_type = 'randori' THEN 'Full Bracket' ELSE drawing_match_numbers.round END"),
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

        // Filters
        if (! empty($filterCourt)) {
            $drawingsQuery->where('court_id', $filterCourt);
        }
        if (! empty($filterSession)) {
            $drawingsQuery->where('session_time_id', $filterSession);
        }
        if (! empty($filterRundown)) {
            $drawingsQuery->where('rundown_id', $filterRundown);
        }
        if (! empty($filterPool)) {
            $drawingsQuery->where('pool_id', $filterPool);
        }
        if (! empty($filterRound)) {
            $drawingsQuery->where('round', $filterRound);
        }
        if (! empty($filterType)) {
            $drawingsQuery->where('drawing_match_numbers.draft_type', $filterType);
        }
        if (! empty($filterAgeGroup)) {
            $drawingsQuery->whereHas('matchNumber', function ($q) use ($filterAgeGroup) {
                $q->where('age_group_id', $filterAgeGroup);
            });
        }
        if (! empty($filterMatchNumber)) {
            $drawingsQuery->where('match_number_id', $filterMatchNumber);
        }
        if (! empty($filterGender)) {
            $drawingsQuery->whereHas('matchNumber', function ($q) use ($filterGender) {
                $q->where('gender', $filterGender);
            });
        }

        if (auth()->user()->court_id) {
            $drawingsQuery->where('court_id', auth()->user()->court_id);
        }

        if (! empty($filterContingent)) {
            $drawingsQuery->whereHas('registration.contingent', function ($q) use ($filterContingent) {
                $q->where('contingents.id', $filterContingent);
            });
        }

        if (! empty($search)) {
            $drawingsQuery->where(function ($q) use ($search) {
                $q->whereHas('matchNumber', fn ($mq) => $mq->where('name', 'ilike', '%'.$search.'%'))
                    ->orWhereHas('registration.contingent', fn ($cq) => $cq->where('name', 'ilike', '%'.$search.'%'));
            });
        }

        $drawingsQuery->orderBy('rundown_id')->orderBy('session_time_id')->orderByRaw('MIN(sequence_number)');

        $drawings = $drawingsQuery->paginate(10);

        $refereesQuery = Referee::with('user');
        if (! empty($searchReferee)) {
            $refereesQuery->whereHas('user', function ($q) use ($searchReferee) {
                $q->where('name', 'ilike', '%'.$searchReferee.'%');
            })->orWhere('license_number', 'ilike', '%'.$searchReferee.'%')
                ->orWhere('certification_level', 'ilike', '%'.$searchReferee.'%');
        }
        $allReferees = $refereesQuery->get()->sortBy([
            ['certification_level', 'asc'],
        ])->values();

        $data = [
            'drawings' => $drawings,
            'courts' => $courts,
            'sessions' => $sessions,
            'rundowns' => $rundowns,
            'pools' => $pools,
            'contingents' => $contingents,
            'rounds' => $rounds,
            'ageGroups' => $ageGroups,
            'matchNumbers' => $matchNumbers,
            'allReferees' => $allReferees,
        ];

        return $this->stateCache->conditionalJson($request, $data, [
            'dashboard' => $this->stateCache->version('dashboard'),
        ]);
    }

    public function activateMatch(ActivateMatchRequest $request): JsonResponse
    {
        $drawingId = $request->input('drawing_id');
        $drawing = DrawingMatchNumber::with([
            'matchNumber',
            'court',
            'registration.contingent',
            'pool',
            'sessionTime',
            'rundown',
        ])->findOrFail($drawingId);

        $court = $drawing->court;

        if (! $court) {
            return response()->json([
                'success' => false,
                'message' => 'Drawing ini belum memiliki lapangan yang ditentukan.',
            ], 400);
        }

        $matchNumber = $drawing->matchNumber;
        $draftType = $drawing->draft_type ?? $matchNumber?->draft_type ?? 'embu';

        if ($draftType === 'embu') {
            $matchNumber?->update(['active_registration_id' => $drawing->registration_id]);
        } else {
            $matchNumber?->update(['active_bracket_node' => '0_0']);
        }

        $court->update([
            'active_match_id' => $drawing->match_number_id,
            'active_drawing_id' => $drawing->id,
            'active_registration_id' => $drawing->registration_id,
            'active_bracket_node' => $draftType !== 'embu' ? '0_0' : null,
        ]);

        Cache::put("court_{$court->id}_timer", [
            'status' => 'stopped',
            'elapsed_ms' => 0,
            'started_at_ms' => null,
        ]);

        $this->stateCache->bumpCourt($court->id);
        $this->stateCache->bumpMatch($drawing->match_number_id);

        $contingentName = $drawing->registration?->contingent?->name ?? '—';
        $poolLabel = $drawing->pool ? 'Pool '.$drawing->pool->name : null;
        $sessionLabel = $drawing->sessionTime?->name;

        $text = implode(' · ', array_filter([
            $matchNumber?->name ?? '—',
            $contingentName,
            $poolLabel,
            $sessionLabel,
            '→ '.$court->name,
        ]));

        event(new CourtUpdated($court->id, null, 'court'));

        return response()->json([
            'success' => true,
            'title' => 'Pertandingan Aktif!',
            'text' => $text,
        ])->header('Cache-Control', 'no-cache, no-store, must-revalidate');
    }

    public function clearCourt(ClearCourtRequest $request): JsonResponse
    {
        $courtId = $request->input('court_id');
        $court = Court::findOrFail($courtId);
        $court->update([
            'active_match_id' => null,
            'active_registration_id' => null,
            'active_bracket_node' => null,
            'active_drawing_id' => null,
        ]);

        Cache::forget("court_{$courtId}_timer");

        $this->stateCache->bumpCourt($courtId);

        event(new CourtUpdated($courtId, null, 'court'));

        return response()->json([
            'success' => true,
            'text' => $court->name.' sekarang idle / kosong.',
        ])->header('Cache-Control', 'no-cache, no-store, must-revalidate');
    }

    public function clearAllCourts(): JsonResponse
    {
        $allCourts = Court::all();

        foreach ($allCourts as $court) {
            $court->update([
                'active_match_id' => null,
                'active_registration_id' => null,
                'active_bracket_node' => null,
                'active_drawing_id' => null,
            ]);

            Cache::forget("court_{$court->id}_timer");

            $this->stateCache->bumpCourt($court->id);

            event(new CourtUpdated($court->id, null, 'court'));
        }

        MatchNumber::query()->update([
            'active_registration_id' => null,
            'active_bracket_node' => null,
        ]);
        $this->stateCache->bump('dashboard');

        return response()->json([
            'success' => true,
            'text' => 'Seluruh status aktif telah dibersihkan secara serentak.',
        ])->header('Cache-Control', 'no-cache, no-store, must-revalidate');
    }

    public function panggilDrawingIndex(): Response
    {
        return Inertia::render('PanggilDrawingDashboard');
    }

    public function panggilDrawingState(Request $request): JsonResponse
    {
        $search = $request->input('search', '');
        $filterCourt = $request->input('filterCourt', '');
        $filterSession = $request->input('filterSession', '');
        $filterRundown = $request->input('filterRundown', '');
        $filterPool = $request->input('filterPool', '');
        $filterRound = $request->input('filterRound', '');
        $filterType = $request->input('filterType', '');
        $filterContingent = $request->input('filterContingent', '');
        $filterAgeGroup = $request->input('filterAgeGroup', '');
        $filterMatchNumber = $request->input('filterMatchNumber', '');
        $filterGender = $request->input('filterGender', '');

        $query = DrawingMatchNumber::with([
            'matchNumber.ageGroup',
            'pool',
            'court',
            'sessionTime',
            'rundown',
            'registration.contingent',
        ]);

        // Filters
        if (! empty($filterCourt)) {
            $query->where('court_id', $filterCourt);
        }
        if (! empty($filterSession)) {
            $query->where('session_time_id', $filterSession);
        }
        if (! empty($filterRundown)) {
            $query->where('rundown_id', $filterRundown);
        }
        if (! empty($filterPool)) {
            $query->where('pool_id', $filterPool);
        }
        if (! empty($filterRound)) {
            $query->where('round', $filterRound);
        }
        if (! empty($filterType)) {
            $query->where('draft_type', $filterType);
        }
        if (! empty($filterAgeGroup)) {
            $query->whereHas('matchNumber', function ($q) use ($filterAgeGroup) {
                $q->where('age_group_id', $filterAgeGroup);
            });
        }
        if (! empty($filterMatchNumber)) {
            $query->where('match_number_id', $filterMatchNumber);
        }
        if (! empty($filterGender)) {
            $query->whereHas('matchNumber', function ($q) use ($filterGender) {
                $q->where('gender', $filterGender);
            });
        }
        if (! empty($filterContingent)) {
            $query->whereHas('registration.contingent', function ($q) use ($filterContingent) {
                $q->where('id', $filterContingent);
            });
        }
        if (auth()->user()->court_id) {
            $query->where('drawing_match_numbers.court_id', auth()->user()->court_id);
        }
        if (! empty($search)) {
            $query->where(function ($q) use ($search) {
                $q->whereHas('matchNumber', fn ($mq) => $mq->where('name', 'ilike', '%'.$search.'%'))
                    ->orWhereHas('registration.contingent', fn ($cq) => $cq->where('name', 'ilike', '%'.$search.'%'))
                    ->orWhereRaw("metadata->>'athlete_name' ilike ?", ['%'.$search.'%']);
            });
        }

        // Sorting: ASC date, ASC start_time, ASC sequence_number
        $query->join('rundowns', 'drawing_match_numbers.rundown_id', '=', 'rundowns.id')
            ->select('drawing_match_numbers.*')
            ->orderBy('rundowns.date', 'asc')
            ->orderBy(DB::raw("drawing_match_numbers.metadata->>'start_time'"), 'asc')
            ->orderBy('drawing_match_numbers.sequence_number', 'asc');

        $drawings = $query->paginate(15);

        // Map drawings to check if they have score/lock
        $drawings->getCollection()->transform(function ($drawing) {
            $hasScore = false;
            if ($drawing->draft_type === 'embu') {
                $hasScore = EmbuScore::where('drawing_id', $drawing->id)
                    ->orWhere(function ($q) use ($drawing) {
                        $q->where('registration_id', $drawing->registration_id)
                            ->where('match_number_id', $drawing->match_number_id);
                    })
                    ->exists();
            } else {
                // Randori score check
                $hasScore = RandoriMatchResult::where('match_number_id', $drawing->match_number_id)
                    ->exists();
            }
            $drawing->has_score = $hasScore;

            return $drawing;
        });

        // Other filters lists
        $courtQuery = Court::with([
            'activeMatch',
            'activeDrawing.pool',
            'activeDrawing.sessionTime',
            'activeDrawing.rundown',
            'activeDrawing.registration.contingent',
        ])->orderBy('order');

        if (auth()->user()->court_id) {
            $courtQuery->where('id', auth()->user()->court_id);
        }

        $courts = $courtQuery->get();

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
        if ($filterAgeGroup) {
            $matchNumberQuery->where('age_group_id', $filterAgeGroup);
        }
        if ($filterGender) {
            $matchNumberQuery->where('gender', $filterGender);
        }
        if ($filterType) {
            $matchNumberQuery->where('draft_type', $filterType);
        }
        $matchNumbers = $matchNumberQuery->get();

        $refereesQuery = Referee::with('user');
        $allReferees = $refereesQuery->get()->sortBy([
            ['certification_level', 'asc'],
        ])->values();

        return response()->json([
            'drawings' => $drawings,
            'courts' => $courts,
            'sessions' => $sessions,
            'rundowns' => $rundowns,
            'pools' => $pools,
            'contingents' => $contingents,
            'rounds' => $rounds,
            'ageGroups' => $ageGroups,
            'matchNumbers' => $matchNumbers,
            'allReferees' => $allReferees,
        ]);
    }

    public function scoringCorrectionIndex(Request $request): Response
    {
        return Inertia::render('ScoringCorrection', [
            'urlMatchId' => $request->query('match_id') ? (int) $request->query('match_id') : null,
        ]);
    }

    public function scoringCorrectionMatches(): JsonResponse
    {
        $matches = MatchNumber::orderBy('name')
            ->select('id', 'name', 'draft_type')
            ->get();

        return response()->json($matches);
    }

    public function scoringCorrectionMatchState(MatchNumber $matchNumber): JsonResponse
    {
        $mergeDetails = DB::table('match_number_merge_details')
            ->where('match_number_id', $matchNumber->id)
            ->first();

        if ($mergeDetails) {
            $matchNumberIds = DB::table('match_number_merge_details')
                ->where('match_number_merge_id', $mergeDetails->match_number_merge_id)
                ->pluck('match_number_id')
                ->toArray();
        } else {
            $matchNumberIds = [$matchNumber->id];
        }

        $drawings = DrawingMatchNumber::with([
            'registration.contingent',
            'registration.athletes',
            'matchNumber',
            'pool',
        ])
            ->whereIn('match_number_id', $matchNumberIds)
            ->orderBy('round')
            ->orderBy('sequence_number')
            ->get();

        $pivotAthletes = Athlete::whereHas('matchNumbers', function ($query) use ($matchNumberIds) {
            $query->whereIn('match_numbers.id', $matchNumberIds);
        })
            ->with(['matchNumbers' => fn ($query) => $query->whereIn('match_numbers.id', $matchNumberIds)])
            ->get()
            ->flatMap(function ($athlete) {
                return $athlete->matchNumbers->map(function ($matchNumber) use ($athlete) {
                    return [
                        'key' => $matchNumber->id.':'.$matchNumber->pivot->registration_id,
                        'athlete' => $athlete,
                    ];
                });
            })
            ->groupBy('key')
            ->map(fn ($items) => $items->pluck('athlete')->unique('id')->values());

        foreach ($drawings as $drawing) {
            $regId = $drawing->registration_id;
            $matchId = $drawing->match_number_id;

            $metaAthleteIds = $drawing->metadata['athlete_ids'] ?? [];
            if (! empty($metaAthleteIds)) {
                $drawingAthletes = $drawing->registration?->athletes->whereIn('id', $metaAthleteIds)->values() ?? collect();
            } else {
                $drawingAthletes = $pivotAthletes->get($matchId.':'.$regId, collect());
            }

            $drawing->setRelation('athletes', $drawingAthletes);
        }

        $embuScores = EmbuScore::whereIn('match_number_id', $matchNumberIds)
            ->orderBy('round_label')
            ->orderBy('tiebreak_round')
            ->get();

        $randoriResults = RandoriMatchResult::whereIn('match_number_id', $matchNumberIds)->get();

        return response()->json([
            'matchNumber' => $matchNumber,
            'matchNumberIds' => $matchNumberIds,
            'drawings' => $drawings,
            'embuScores' => $embuScores,
            'randoriResults' => $randoriResults,
        ]);
    }
}

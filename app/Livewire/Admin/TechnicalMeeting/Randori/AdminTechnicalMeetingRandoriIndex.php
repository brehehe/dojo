<?php

namespace App\Livewire\Admin\TechnicalMeeting\Randori;

use App\Models\Court\Court;
use App\Models\DrawingMatchNumber;
use App\Models\MatchNumber\MatchNumber;
use App\Models\RandoriMatchResult;
use App\Models\Rundown\Rundown;
use App\Models\SessionTime;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('layouts.admin')]
class AdminTechnicalMeetingRandoriIndex extends Component
{
    use WithPagination;

    public ?int $generatingMatchId = null;

    public ?int $selectedCourtId = null;

    public string $globalTab = 'sebelum';

    public function paginationView(): string
    {
        return 'livewire.admin.pagination';
    }

    // ──────────────────────────────────────────────────
    public function generateDrawing(int $matchId, string $round = 'Penyisihan'): void
    {
        $this->generatingMatchId = $matchId;
        $match = MatchNumber::findOrFail($matchId);

        // 1. Clear existing drawing records
        DrawingMatchNumber::where('match_number_id', $matchId)->delete();

        // 2. Get athletes, spread to avoid same contingent meeting early
        $athletesQuery = DB::table('athlete_match_number')
            ->join('athletes', 'athlete_match_number.athlete_id', '=', 'athletes.id')
            ->join('registrations', 'athlete_match_number.registration_id', '=', 'registrations.id')
            ->join('contingents', 'registrations.contingent_id', '=', 'contingents.id')
            ->where('athlete_match_number.match_number_id', $matchId)
            ->select('athletes.id', 'athletes.name', 'athlete_match_number.registration_id', 'contingents.name as contingent_name')
            ->distinct()
            ->get();

        $totalAthletes = $athletesQuery->count();

        if ($totalAthletes === 0) {
            $this->generatingMatchId = null;

            return;
        }

        // Spread athletes to avoid same contingent in early matchups
        $grouped = $athletesQuery->groupBy('contingent_name');
        $spreadAthletes = [];
        $maxPerContingent = $grouped->max(fn ($c) => $c->count());
        for ($i = 0; $i < $maxPerContingent; $i++) {
            foreach ($grouped as $members) {
                if ($members->count() > $i) {
                    $spreadAthletes[] = $members[$i];
                }
            }
        }

        $athletes = array_map(fn ($a) => [
            'id' => $a->id,
            'name' => $a->name,
            'contingent' => $a->contingent_name,
            'registration_id' => $a->registration_id,
        ], $spreadAthletes);

        // Bracket size = next power of 2
        $bracketSize = 1;
        while ($bracketSize < $totalAthletes) {
            $bracketSize *= 2;
        }

        // 3. Build Double Elimination structure
        $drawingData = $this->buildDoubleElimination($athletes, $bracketSize);

        $match->update([
            'drawing_data' => $drawingData,
            'drawing_generated_at' => now(),
        ]);

        // 4. Create DrawingMatchNumber records for scheduling
        $allMatchIds = MatchNumber::where('draft_type', 'randori')->orderBy('id')->pluck('id')->toArray();
        $matchIndex = array_search($matchId, $allMatchIds) ?: 0;
        $courts = Court::orderBy('order')->get();
        $sessions = SessionTime::orderBy('start_time')->get();
        $rundowns = Rundown::where('type', 'pertandingan')->orderBy('date', 'asc')->get();
        $court = $courts->get($matchIndex % max(1, $courts->count()));
        $sessionTime = $sessions->get(intval($matchIndex / max(1, $courts->count())) % max(1, $sessions->count()));
        $rundown = $rundowns->first();

        foreach ($athletes as $athlete) {
            DrawingMatchNumber::create([
                'match_number_id' => $matchId,
                'registration_id' => $athlete['registration_id'],
                'court_id' => $court?->id,
                'session_time_id' => $sessionTime?->id,
                'rundown_id' => $rundown?->id,
                'round' => 'Penyisihan',
                'draft_type' => 'randori',
                'metadata' => ['athlete_id' => $athlete['id']],
            ]);
        }

        $this->generatingMatchId = null;
        $this->dispatch('swal', [
            'icon' => 'success',
            'title' => 'Bagan Double Elimination Dibuat!',
            'text' => $totalAthletes.' atlet | Bagan '.$bracketSize.' slot | UB + LB',
        ]);
    }

    private function buildDoubleElimination(array $athletes, int $bracketSize): array
    {
        $n = count($athletes);

        // ── MAXIMIZE PEREMPATAN: floor(n/2) real matches, at most 1 lolos ────
        // numPrelim  = floor(n/2)  → everyone fights, except 1 if odd number
        // numDirect  = n % 2       → 0 if even (everyone fights), 1 if odd (top seed lolos)
        // e.g.  4 → 2 matches, 0 lolos | 5 → 2 matches, 1 lolos
        //        7 → 3 matches, 1 lolos | 9 → 4 matches, 1 lolos | 10 → 5 matches, 0 lolos
        $numPrelim = (int) ($n / 2); // floor(n/2)
        $numDirect = $n % 2;         // 0 or 1
        $directAthletes = array_slice($athletes, 0, $numDirect);
        $prelimAthletes = array_slice($athletes, $numDirect); // 2 * numPrelim athletes

        // ── Recalculate mainSize based on R1 athlete count = ceil(n/2) ────────
        // R1 will receive numPrelim winners + numDirect lolos = ceil(n/2) athletes.
        // mainSize = next power-of-2 ≥ ceil(n/2) to keep the bracket power-of-2.
        $r1AthleteCount = $numPrelim + $numDirect; // = ceil(n/2)
        $mainSize = 2;
        while ($mainSize < $r1AthleteCount) {
            $mainSize *= 2;
        }
        $bracketSize = $mainSize * 2; // full bracket size (unused for round calc but kept for info)

        // ── Seeded positions in main bracket (mainSize slots) ────────────────
        $mainSeedOrder = $this->generateSeedOrder($mainSize); // pos => seedNum
        $seedToPos = array_flip($mainSeedOrder);          // seedNum => pos

        // Pre-fill mainSize slots with direct athletes and reserve for prelim winners
        $r1Slots = array_fill(0, $mainSize, null);
        $r0TargetSlots = []; // r0 match index => r1 slot position

        for ($s = 1; $s <= $mainSize; $s++) {
            $pos = $seedToPos[$s] ?? ($s - 1);
            if ($s <= $numDirect) {
                $r1Slots[$pos] = $directAthletes[$s - 1];
            } else {
                $r0Idx = $s - $numDirect - 1;
                $r0TargetSlots[$r0Idx] = $pos;
            }
        }

        // ── LB / UB round counts (based on mainSize, not bracketSize) ────────
        $mainK = (int) log($mainSize, 2); // Main bracket UB rounds (R1..Rfinal)
        $hasPrelim = $numPrelim > 0;
        $lbRoundsCount = max(0, 2 * $mainK);
        $lbFinalIdx = $lbRoundsCount - 1;

        $ubRounds = [];
        $r0Matches = [];

        // ── R0: PRELIMINARY MATCHES (athletes who must fight first) ──────────
        for ($m = 0; $m < $numPrelim; $m++) {
            $a1 = $prelimAthletes[$m * 2] ?? null;
            $a2 = $prelimAthletes[$m * 2 + 1] ?? null;

            $r1Pos = $r0TargetSlots[$m] ?? ($numDirect + $m);
            $r1Match = (int) ($r1Pos / 2);
            $r1Slot = ($r1Pos % 2 === 0) ? 'athlete1' : 'athlete2';

            // FIX: pair losers two-by-two into the same LB R0 match
            // UB M0 loser → LB R0 M0 a1, UB M1 loser → LB R0 M0 a2
            // UB M2 loser → LB R0 M1 a1, UB M3 loser → LB R0 M1 a2, …
            $lbR0Match = (int) ($m / 2);
            $lbR0Slot = $m % 2 === 0 ? 'athlete1' : 'athlete2';

            $r0Matches[] = [
                'athlete1' => $a1,
                'athlete2' => $a2,
                'winner' => null,
                'winner_data' => null,
                'winner_next' => ['bracket' => 'ub', 'round' => 1, 'match' => $r1Match, 'slot' => $r1Slot],
                'loser_next' => ['bracket' => 'lb', 'round' => 0, 'match' => $lbR0Match, 'slot' => $lbR0Slot],
                'is_bye' => false,
                'is_prelim' => true,
                'is_direct' => false,
            ];
        }

        // ── R0: DIRECT PASS athletes (shown in Perempatan but don't fight) ───
        // Shown in Perempatan column as "Lolos Langsung" so ALL athletes appear
        // in the same column before the bracket progresses to Round 1.
        for ($s = 1; $s <= $numDirect; $s++) {
            $pos = $seedToPos[$s] ?? ($s - 1);
            $r1Match = (int) ($pos / 2);
            $r1Slot = ($pos % 2 === 0) ? 'athlete1' : 'athlete2';
            $athlete = $directAthletes[$s - 1];

            $r0Matches[] = [
                'athlete1' => $athlete,
                'athlete2' => null,
                'winner' => 'athlete1',
                'winner_data' => $athlete,
                'winner_next' => ['bracket' => 'ub', 'round' => 1, 'match' => $r1Match, 'slot' => $r1Slot],
                'loser_next' => ['bracket' => 'none'],
                'is_bye' => false,
                'is_prelim' => false,
                'is_direct' => true,
            ];
        }

        // Always push R0 (even if only direct-pass entries — still need the column)
        $ubRounds[] = $r0Matches;

        // ── MAIN BRACKET: UB R1..R_mainK ────────────────────────────────────
        $ubOffset = $hasPrelim ? 1 : 0; // UB round array index offset due to R0
        for ($r = 1; $r <= $mainK; $r++) {
            $matchCount = $mainSize >> $r; // mainSize / 2^r
            $isUBFinal = ($r === $mainK);
            $round = [];

            for ($m = 0; $m < $matchCount; $m++) {
                // Populate athletes for R1 from pre-filled slots
                $a1 = ($r === 1) ? ($r1Slots[$m * 2] ?? null) : null;
                $a2 = ($r === 1) ? ($r1Slots[$m * 2 + 1] ?? null) : null;

                if ($isUBFinal) {
                    $winnerNext = ['bracket' => 'gf', 'slot' => 'athlete1'];
                    $loserNext = ['bracket' => 'lb', 'round' => $lbFinalIdx, 'match' => 0, 'slot' => 'athlete1'];
                } else {
                    $winnerNext = ['bracket' => 'ub', 'round' => $r + $ubOffset, 'match' => (int) ($m / 2), 'slot' => $m % 2 === 0 ? 'athlete1' : 'athlete2'];
                    // FIX: r=1 → LB R1, r=2 → LB R3, r=3 → LB R5 … (standard double-elim mapping)
                    $lbDrop = 2 * ($r - 1) + 1;
                    $loserNext = ['bracket' => 'lb', 'round' => min($lbDrop, $lbFinalIdx - 1), 'match' => $m, 'slot' => 'athlete1'];
                }

                $round[] = [
                    'athlete1' => $a1,
                    'athlete2' => $a2,
                    'winner' => null,
                    'winner_data' => null,
                    'winner_next' => $winnerNext,
                    'loser_next' => $loserNext,
                    'is_bye' => false,
                    'is_prelim' => false,
                ];
            }
            $ubRounds[] = $round;
        }

        // ── LOWER BRACKET ────────────────────────────────────────────────────
        $lbRounds = [];
        for ($lr = 0; $lr < $lbRoundsCount; $lr++) {
            $isLBFinal = ($lr === $lbFinalIdx);

            // LB R0: one match per PAIR of UB R0 losers → ceil(numPrelim / 2)
            if ($lr === 0) {
                $lbMatchCount = max(1, (int) ceil($numPrelim / 2));
            } elseif ($isLBFinal) {
                $lbMatchCount = 1;
            } elseif ($lr % 2 === 1) {
                // Odd LB rounds receive UB losers from UB R = (lr+1)/2
                // That UB round has mainSize >> ubDropR matches → same count needed here
                $ubDropR = (int) (($lr + 1) / 2);
                $lbMatchCount = max(1, $mainSize >> $ubDropR); // FIX: removed the wrong +1
            } else {
                $lbMatchCount = max(1, (int) (count($lbRounds[$lr - 1]) / 2));
            }

            $round = [];
            for ($m = 0; $m < $lbMatchCount; $m++) {
                if ($isLBFinal) {
                    $winnerNext = ['bracket' => 'gf', 'slot' => 'athlete2'];
                    $loserNext = ['bracket' => 'ranked', 'rank' => 3];
                } elseif ($lr % 2 === 1 && $lr > 0) {
                    $nextLB = $lr + 1;
                    $winnerNext = $nextLB >= $lbFinalIdx
                        ? ['bracket' => 'lb', 'round' => $lbFinalIdx, 'match' => 0, 'slot' => 'athlete2']
                        : ['bracket' => 'lb', 'round' => $nextLB, 'match' => (int) ($m / 2), 'slot' => $m % 2 === 0 ? 'athlete1' : 'athlete2'];
                    $loserNext = ['bracket' => 'eliminated'];
                } else {
                    if ($lr === 0) {
                        $winnerNext = ['bracket' => 'lb', 'round' => 1, 'match' => $m, 'slot' => 'athlete2'];
                    } else {
                        $nextDrop = $lr + 1;
                        $winnerNext = $nextDrop >= $lbFinalIdx
                            ? ['bracket' => 'lb', 'round' => $lbFinalIdx, 'match' => 0, 'slot' => 'athlete2']
                            : ['bracket' => 'lb', 'round' => $nextDrop, 'match' => $m, 'slot' => 'athlete2'];
                    }
                    $loserNext = ['bracket' => 'eliminated'];
                }

                $round[] = [
                    'athlete1' => null,
                    'athlete2' => null,
                    'winner' => null,
                    'winner_data' => null,
                    'winner_next' => $winnerNext,
                    'loser_next' => $loserNext,
                    'is_bye' => false,
                ];
            }
            $lbRounds[] = $round;
        }

        return [
            'bracket_type' => 'double_elimination',
            'bracket_size' => $bracketSize,
            'total_athletes' => $n,
            'total_entries' => $n,
            'has_preliminary' => $hasPrelim,
            'upper_bracket' => ['rounds' => $ubRounds],
            'lower_bracket' => ['rounds' => $lbRounds],
            'grand_final' => ['athlete1' => null, 'athlete2' => null, 'winner' => null, 'winner_data' => null],
            'juara' => [],
        ];
    }

    /**
     * Generate standard tournament seed order for a bracket of given size.
     * Returns array[position => seedNumber] interleaved so top seeds meet only in the final.
     * e.g. size=4: [1,4,2,3] → Match0=(seed1 vs seed4), Match1=(seed2 vs seed3)
     */
    private function generateSeedOrder(int $bracketSize): array
    {
        $order = [1, 2];
        $size = (int) log($bracketSize, 2);

        for ($r = 0; $r < $size - 1; $r++) {
            $newOrder = [];
            $target = count($order) * 2 + 1;
            foreach ($order as $seed) {
                $newOrder[] = $seed;
                $newOrder[] = $target - $seed;
            }
            $order = $newOrder;
        }

        return array_combine(array_keys($order), $order);
    }

    public function generateAllDrawings(bool $forceRegenerate = true, string $round = 'Penyisihan'): void
    {
        $matches = MatchNumber::where('draft_type', 'randori');
        if (! $forceRegenerate) {
            $matches->whereNull('drawing_data');
        }

        $matchIds = $matches->pluck('id');
        foreach ($matchIds as $id) {
            $this->generateDrawing($id, $round);
        }

        $this->dispatch('all-drawings-generated');
    }

    public function resetDrawing(int $matchId): void
    {
        DrawingMatchNumber::where('match_number_id', $matchId)->delete();

        MatchNumber::findOrFail($matchId)->update([
            'drawing_data' => null,
            'drawing_generated_at' => null,
        ]);
    }

    /**
     * Generate standard tournament seeding array to space out athletes.
     * E.g., for 8: [0, 7, 3, 4, 1, 6, 2, 5]
     */
    private function generateSeedingArray(int $size): array
    {
        if ($size === 1) {
            return [0];
        }
        if ($size === 2) {
            return [0, 1];
        }

        $order = [0, 1];
        $currentSize = 2;

        while ($currentSize < $size) {
            $nextOrder = [];
            foreach ($order as $val) {
                // For each element X, we pair it with ($currentSize * 2 - 1 - X)
                $nextOrder[] = $val;
                $nextOrder[] = ($currentSize * 2) - 1 - $val;
            }
            $order = $nextOrder;
            $currentSize *= 2;
        }

        return $order;
    }

    // ──────────────────────────────────────────────────
    // RENDER
    // ──────────────────────────────────────────────────

    public function render()
    {
        $paginatedMatches = MatchNumber::where('draft_type', 'randori')
            ->has('athletes')
            ->with(['ageGroup'])
            ->latest()
            ->paginate(1000);

        $courts = Court::orderBy('order')->get();
        // Hanya ambil rundown dengan type pertandingan
        $rundowns = Rundown::where('type', 'pertandingan')->orderBy('date', 'asc')->get();
        $sessionTimes = SessionTime::orderBy('start_time')->get();

        $matchSummary = [];
        foreach ($paginatedMatches as $match) {
            $gender = $match->gender ?? 'Mix';
            $ageGroupName = $match->ageGroup->name ?? 'Unknown Age';

            // Fetch drawing results for this match from DB
            $drawingsQuery = DrawingMatchNumber::with(['pool', 'court', 'rundown', 'sessionTime', 'registration.contingent'])
                ->where('match_number_id', $match->id);

            if ($this->selectedCourtId) {
                $drawingsQuery->where('court_id', $this->selectedCourtId);
            }

            $drawingsFromDb = $drawingsQuery->get();
            $drawing = $match->drawing_data;

            if ($drawingsFromDb->isEmpty() && $this->selectedCourtId) {
                $drawing = null; // Filtered out by court
            }

            $matchAthletes = DB::table('athlete_match_number')
                ->join('athletes', 'athlete_match_number.athlete_id', '=', 'athletes.id')
                ->join('registration_athlete', function ($join) {
                    $join->on('athlete_match_number.athlete_id', '=', 'registration_athlete.athlete_id')
                        ->on('athlete_match_number.registration_id', '=', 'registration_athlete.registration_id');
                })
                ->join('registrations', 'athlete_match_number.registration_id', '=', 'registrations.id')
                ->join('contingents', 'registrations.contingent_id', '=', 'contingents.id')
                ->where('athlete_match_number.match_number_id', $match->id)
                ->select(
                    'athletes.name',
                    'registration_athlete.kyu as rank',
                    'contingents.name as contingent'
                )
                ->distinct()
                ->orderBy('contingents.name')
                ->get();

            // Double elimination results
            $randoriResults = RandoriMatchResult::where('match_number_id', $match->id)
                ->get()
                ->keyBy('bracket_node');

            $matchSummary[$gender][$ageGroupName][$match->id] = [
                'name' => $match->name,
                'athletes' => $matchAthletes->toArray(),
                'drawing_data' => $drawing,
                'db_drawing_entries' => $drawingsFromDb,
                'drawing_at' => $match->drawing_generated_at,
                'randori_results' => $randoriResults,
            ];
        }

        return view('livewire.admin.technical-meeting.randori.admin-technical-meeting-randori-index', [
            'paginatedMatches' => $paginatedMatches,
            'matchSummary' => $matchSummary,
            'courts' => $courts,
            'rundowns' => $rundowns,
            'sessionTimes' => $sessionTimes,
        ]);
    }
}

<?php

namespace App\Livewire\Admin\TechnicalMeeting\Randori;

use App\Models\Court\Court;
use App\Models\DrawingMatchNumber;
use App\Models\EmbuChampion;
use App\Models\EmbuScore;
use App\Models\Group\AgeGroup;
use App\Models\MatchNumber\MatchNumber;
use App\Models\Pool\Pool;
use App\Models\RandoriJudgeScore;
use App\Models\RandoriMatchResult;
use App\Models\RefereeScoreDetail;
use App\Models\Rundown\Rundown;
use App\Models\SessionTime;
use App\Models\TournamentResult;
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

    public ?int $selectedAgeGroupId = null;

    public ?int $selectedMatchNumberId = null;

    public ?int $selectedPoolId = null;

    public ?string $selectedGender = null;

    public string $globalTab = 'sebelum';

    public function updatedSelectedAgeGroupId()
    {
        $this->selectedMatchNumberId = null;
    }

    public function updatedSelectedCourtId()
    {
        $this->selectedMatchNumberId = null;
    }

    public function updatedSelectedPoolId()
    {
        $this->selectedMatchNumberId = null;
    }

    public function updatedSelectedGender()
    {
        $this->selectedMatchNumberId = null;
    }

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
        $uniqueContingentCount = $grouped->count();

        if ($uniqueContingentCount <= 3) {
            $this->generatingMatchId = null;

            // $this->dispatch('swal', [
            //     'icon' => 'error',
            //     'title' => 'Gagal Membuat Bagan',
            //     'text' => "Kelas ini hanya diikuti oleh {$uniqueContingentCount} kontingen. Minimal 4 kontingen agar dapat dipertandingkan.",
            // ]);
            return;
        }

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

        // 3. Build Elimination structure
        if ($totalAthletes <= 4) {
            $drawingData = $this->buildDoubleElimination($athletes, $bracketSize);
            $typeLabel = 'Double Elimination';
            $drawingData['type'] = 'double_elimination';
        } else {
            $drawingData = $this->buildSingleElimination($athletes, $bracketSize);
            $typeLabel = 'Single Elimination';
            $drawingData['type'] = 'single_elimination';
        }

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
            'title' => 'Bagan '.$typeLabel.' Dibuat!',
            'text' => $totalAthletes.' atlet | Bagan '.$bracketSize.' slot',
        ]);
    }

    private function buildDoubleElimination(array $athletes, int $bracketSize): array
    {
        $n = count($athletes);
        if ($n === 0) {
            return [];
        }

        // 1. Determine Bracket Size (Next Power of 2)
        $size = 2;
        while ($size < $n) {
            $size *= 2;
        }
        $bracketSize = $size;

        $mainK = (int) log($bracketSize, 2);
        // LB Rounds = 2 * log2(N) - 2
        $lbRoundsCount = max(0, 2 * $mainK - 2);

        // 2. Drawing Seeding
        $seedOrder = $this->generateSeedOrder($bracketSize);
        $seedToPos = array_flip($seedOrder);

        // 3. Fill initial slots, padding with BYE
        $slots = array_fill(0, $bracketSize, ['id' => 'BYE', 'name' => 'BYE', 'contingent' => '-']);
        for ($i = 0; $i < $n; $i++) {
            $pos = $seedToPos[$i + 1] ?? $i;
            $slots[$pos] = $athletes[$i];
        }

        // 4. Initialize Lower Bracket Structure
        $lbRounds = [];
        for ($lr = 0; $lr < $lbRoundsCount; $lr++) {
            $lbRounds[$lr] = [];
            if ($lr % 2 === 0) {
                // Rounds 0, 2, 4... receive losers from UB
                $lbMatchCount = $bracketSize / (2 ** (($lr / 2) + 2));
            } else {
                // Rounds 1, 3, 5... receive winners from previous LB round
                $lbMatchCount = $bracketSize / (2 ** ((($lr - 1) / 2) + 2));
            }

            for ($m = 0; $m < $lbMatchCount; $m++) {
                $isLBFinal = ($lr === $lbRoundsCount - 1);
                $isLBSemi = ($lr === $lbRoundsCount - 2);

                $winnerNext = $isLBFinal
                    ? ['bracket' => 'gf', 'slot' => 'athlete2']
                    : ['bracket' => 'lb', 'round' => $lr + 1, 'match' => ($lr % 2 === 0) ? $m : (int) ($m / 2), 'slot' => ($lr % 2 === 0) ? 'athlete2' : ($m % 2 === 0 ? 'athlete1' : 'athlete2')];

                $loserNext = ['bracket' => 'eliminated'];
                if ($isLBFinal) {
                    $loserNext = ['bracket' => 'ranked', 'rank' => 3];
                } elseif ($isLBSemi) {
                    $loserNext = ['bracket' => 'ranked', 'rank' => 4];
                }

                $lbRounds[$lr][] = [
                    'athlete1' => null,
                    'athlete2' => null,
                    'winner' => null,
                    'winner_data' => null,
                    'winner_next' => $winnerNext,
                    'loser_next' => $loserNext,
                    'is_bye' => false,
                ];
            }
        }

        // 5. Initialize Upper Bracket Structure
        $ubRounds = [];
        for ($r = 0; $r < $mainK; $r++) {
            $matchCount = $bracketSize / (2 ** ($r + 1));
            $isUBFinal = ($r === $mainK - 1);
            $round = [];

            for ($m = 0; $m < $matchCount; $m++) {
                $a1 = ($r === 0) ? $slots[$m * 2] : null;
                $a2 = ($r === 0) ? $slots[$m * 2 + 1] : null;

                $winnerNext = $isUBFinal
                    ? ['bracket' => 'gf', 'slot' => 'athlete1']
                    : ['bracket' => 'ub', 'round' => $r + 1, 'match' => (int) ($m / 2), 'slot' => $m % 2 === 0 ? 'athlete1' : 'athlete2'];

                if ($isUBFinal) {
                    $loserNext = $lbRoundsCount > 0 ? ['bracket' => 'lb', 'round' => $lbRoundsCount - 1, 'match' => 0, 'slot' => 'athlete1'] : ['bracket' => 'gf', 'slot' => 'athlete2'];
                } else {
                    if ($r === 0) {
                        $loserNext = $lbRoundsCount > 0 ? ['bracket' => 'lb', 'round' => 0, 'match' => (int) ($m / 2), 'slot' => $m % 2 === 0 ? 'athlete1' : 'athlete2'] : ['bracket' => 'eliminated'];
                    } else {
                        // Crossover: to avoid immediate rematches, we invert the match index for UB drops > R0
                        $dropMatch = $matchCount - 1 - $m;
                        $loserNext = $lbRoundsCount > 0 ? ['bracket' => 'lb', 'round' => 2 * $r - 1, 'match' => $dropMatch, 'slot' => 'athlete1'] : ['bracket' => 'eliminated'];
                    }
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

        // 6. Propagate BYEs through the bracket
        $this->propagateBracketByes($ubRounds, $lbRounds);

        return [
            'bracket_type' => 'double_elimination',
            'bracket_size' => $bracketSize,
            'total_athletes' => $n,
            'total_entries' => $n,
            'has_preliminary' => false,
            'upper_bracket' => ['rounds' => $ubRounds],
            'lower_bracket' => ['rounds' => $lbRounds],
            'grand_final' => ['athlete1' => null, 'athlete2' => null, 'winner' => null, 'winner_data' => null],
            'juara' => [],
        ];
    }

    private function propagateBracketByes(array &$ubRounds, array &$lbRounds): void
    {
        // 1. Propagate UB
        foreach ($ubRounds as $rIdx => &$round) {
            foreach ($round as $mIdx => &$match) {
                $a1IsBye = isset($match['athlete1']['id']) && $match['athlete1']['id'] === 'BYE';
                $a2IsBye = isset($match['athlete2']['id']) && $match['athlete2']['id'] === 'BYE';

                if ($a1IsBye && $a2IsBye) {
                    $match['is_bye'] = true;
                    $match['winner'] = 'none';
                    $match['winner_data'] = ['id' => 'BYE', 'name' => 'BYE', 'contingent' => '-'];
                    $loserData = ['id' => 'BYE', 'name' => 'BYE', 'contingent' => '-'];
                } elseif ($a1IsBye && $match['athlete2'] !== null) {
                    $match['is_bye'] = true;
                    $match['winner'] = 'athlete2';
                    $match['winner_data'] = $match['athlete2'];
                    $loserData = ['id' => 'BYE', 'name' => 'BYE', 'contingent' => '-'];
                } elseif ($a2IsBye && $match['athlete1'] !== null) {
                    $match['is_bye'] = true;
                    $match['winner'] = 'athlete1';
                    $match['winner_data'] = $match['athlete1'];
                    $loserData = ['id' => 'BYE', 'name' => 'BYE', 'contingent' => '-'];
                } else {
                    continue;
                }

                // Push winner up
                if ($match['winner_next']['bracket'] === 'ub') {
                    $wn = $match['winner_next'];
                    $ubRounds[$wn['round']][$wn['match']][$wn['slot']] = $match['winner_data'];
                } elseif ($match['winner_next']['bracket'] === 'gf') {
                    // GF handled at render/scoring time
                }

                // Push loser down to LB
                if ($match['loser_next']['bracket'] === 'lb') {
                    $ln = $match['loser_next'];
                    $lbRounds[$ln['round']][$ln['match']][$ln['slot']] = $loserData;
                }
            }
        }

        // 2. Propagate LB (Cascade)
        $changed = true;
        while ($changed) {
            $changed = false;
            foreach ($lbRounds as $rIdx => &$round) {
                foreach ($round as $mIdx => &$match) {
                    if ($match['is_bye'] || $match['winner'] !== null) {
                        continue;
                    }

                    $a1IsBye = isset($match['athlete1']['id']) && $match['athlete1']['id'] === 'BYE';
                    $a2IsBye = isset($match['athlete2']['id']) && $match['athlete2']['id'] === 'BYE';

                    if ($a1IsBye && $a2IsBye) {
                        $match['is_bye'] = true;
                        $match['winner'] = 'none';
                        $match['winner_data'] = ['id' => 'BYE', 'name' => 'BYE', 'contingent' => '-'];
                        $changed = true;
                    } elseif ($a1IsBye && $match['athlete2'] !== null) {
                        $match['is_bye'] = true;
                        $match['winner'] = 'athlete2';
                        $match['winner_data'] = $match['athlete2'];
                        $changed = true;
                    } elseif ($a2IsBye && $match['athlete1'] !== null) {
                        $match['is_bye'] = true;
                        $match['winner'] = 'athlete1';
                        $match['winner_data'] = $match['athlete1'];
                        $changed = true;
                    }

                    if ($match['is_bye'] && $match['winner'] !== null) {
                        if ($match['winner_next']['bracket'] === 'lb') {
                            $wn = $match['winner_next'];
                            $lbRounds[$wn['round']][$wn['match']][$wn['slot']] = $match['winner_data'];
                        }
                    }
                }
            }
        }
    }

    private function buildSingleElimination(array $athletes, int $bracketSize): array
    {
        $n = count($athletes);
        if ($n === 0) {
            return [];
        }

        $size = 2;
        while ($size < $n) {
            $size *= 2;
        }
        $bracketSize = $size;
        $mainK = (int) log($bracketSize, 2);

        $seedOrder = $this->generateSeedOrder($bracketSize);
        $seedToPos = array_flip($seedOrder);

        $slots = array_fill(0, $bracketSize, ['id' => 'BYE', 'name' => 'BYE', 'contingent' => '-']);
        for ($i = 0; $i < $n; $i++) {
            $pos = $seedToPos[$i + 1] ?? $i;
            $slots[$pos] = $athletes[$i];
        }

        $ubRounds = [];
        for ($r = 0; $r < $mainK; $r++) {
            $matchesInRound = $bracketSize / (2 ** ($r + 1));
            $round = [];
            for ($m = 0; $m < $matchesInRound; $m++) {
                $isFinal = ($r === $mainK - 1);
                $isSemi = ($r === $mainK - 2);

                if ($isFinal) {
                    $winnerNext = ['bracket' => 'ranked', 'rank' => 1];
                    $loserNext = ['bracket' => 'ranked', 'rank' => 2];
                } else {
                    $winnerNext = [
                        'bracket' => 'ub',
                        'round' => $r + 1,
                        'match' => intdiv($m, 2),
                        'slot' => ($m % 2 === 0) ? 'athlete1' : 'athlete2',
                    ];
                    if ($isSemi) {
                        // Juara 3 bersama: assign to rank 3 and rank 4
                        $rank = ($m % 2 === 0) ? 3 : 4;
                        $loserNext = ['bracket' => 'ranked', 'rank' => $rank];
                    } else {
                        $loserNext = ['bracket' => 'eliminated'];
                    }
                }

                $round[] = [
                    'athlete1' => $r === 0 ? $slots[$m * 2] : null,
                    'athlete2' => $r === 0 ? $slots[$m * 2 + 1] : null,
                    'winner' => null,
                    'winner_data' => null,
                    'winner_next' => $winnerNext,
                    'loser_next' => $loserNext,
                    'is_bye' => false,
                ];
            }
            $ubRounds[] = $round;
        }

        // Propagate BYE (BYE)
        foreach ($ubRounds as $rIdx => &$round) {
            foreach ($round as $mIdx => &$match) {
                $a1IsBye = isset($match['athlete1']['id']) && $match['athlete1']['id'] === 'BYE';
                $a2IsBye = isset($match['athlete2']['id']) && $match['athlete2']['id'] === 'BYE';

                if ($a1IsBye && $a2IsBye) {
                    $match['is_bye'] = true;
                    $match['winner'] = 'none';
                    $match['winner_data'] = ['id' => 'BYE', 'name' => 'BYE', 'contingent' => '-'];
                    $loserData = ['id' => 'BYE', 'name' => 'BYE', 'contingent' => '-'];
                } elseif ($a1IsBye && $match['athlete2'] !== null) {
                    $match['is_bye'] = true;
                    $match['winner'] = 'athlete2';
                    $match['winner_data'] = $match['athlete2'];
                    $loserData = ['id' => 'BYE', 'name' => 'BYE', 'contingent' => '-'];
                } elseif ($a2IsBye && $match['athlete1'] !== null) {
                    $match['is_bye'] = true;
                    $match['winner'] = 'athlete1';
                    $match['winner_data'] = $match['athlete1'];
                    $loserData = ['id' => 'BYE', 'name' => 'BYE', 'contingent' => '-'];
                } else {
                    continue;
                }

                if ($match['winner_next']['bracket'] === 'ub') {
                    $wn = $match['winner_next'];
                    $ubRounds[$wn['round']][$wn['match']][$wn['slot']] = $match['winner_data'];
                }
            }
        }

        return [
            'type' => 'single_elimination',
            'bracket_size' => $bracketSize,
            'upper_bracket' => ['rounds' => $ubRounds],
            'lower_bracket' => ['rounds' => []],
            'grand_final' => null,
            'juara' => [],
        ];
    }

    /**
     * Drawing standard tournament seed order for a bracket of given size.
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
        EmbuScore::where('match_number_id', $matchId)->delete();
        RandoriJudgeScore::where('match_number_id', $matchId)->delete();
        RefereeScoreDetail::where('match_number_id', $matchId)->delete();
        EmbuChampion::where('match_number_id', $matchId)->delete();
        RandoriMatchResult::where('match_number_id', $matchId)->delete();
        TournamentResult::where('match_number_id', $matchId)->delete();

        MatchNumber::findOrFail($matchId)->update([
            'drawing_data' => null,
            'drawing_generated_at' => null,
        ]);
    }

    public function updateDrawingField(int $id, string $field, $value): void
    {
        $drawing = DrawingMatchNumber::find($id);
        if ($drawing) {
            $drawing->update([$field => $value]);
            $this->dispatch('swal', [
                'icon' => 'success',
                'title' => 'Data Diperbarui',
                'text' => 'Data drawing berhasil diupdate.',
            ]);
        }
    }

    public function updateMatchDrawingsField(int $matchId, string $field, $value): void
    {
        DrawingMatchNumber::where('match_number_id', $matchId)->update([$field => $value]);

        $this->dispatch('swal', [
            'icon' => 'success',
            'title' => 'Data Diperbarui',
            'text' => 'Seluruh jadwal untuk match ini telah diperbarui.',
        ]);
    }

    /**
     * Drawing standard tournament seeding array to space out athletes.
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
        $paginatedMatchesQuery = MatchNumber::where('draft_type', 'randori')
            ->has('athletes')
            ->with(['ageGroup'])
            ->orderBy('name', 'asc');

        if ($this->selectedGender) {
            $paginatedMatchesQuery->where('gender', $this->selectedGender);
        }

        if ($this->selectedAgeGroupId) {
            $paginatedMatchesQuery->where('age_group_id', $this->selectedAgeGroupId);
        }

        if ($this->selectedMatchNumberId) {
            $paginatedMatchesQuery->where('id', $this->selectedMatchNumberId);
        }

        if ($this->selectedCourtId) {
            $paginatedMatchesQuery->whereHas('drawings', function ($q) {
                $q->where('court_id', $this->selectedCourtId);
            });
        }

        if ($this->selectedPoolId) {
            $paginatedMatchesQuery->whereHas('drawings', function ($q) {
                $q->where('pool_id', $this->selectedPoolId);
            });
        }

        $paginatedMatches = $paginatedMatchesQuery->paginate(1000);

        $filterAgeGroups = AgeGroup::orderBy('order', 'asc')->get();
        $filterMatchNumbersQuery = MatchNumber::where('draft_type', 'randori')
            ->has('athletes')
            ->with(['ageGroup']);

        if ($this->selectedGender) {
            $filterMatchNumbersQuery->where('gender', $this->selectedGender);
        }

        if ($this->selectedAgeGroupId) {
            $filterMatchNumbersQuery->where('age_group_id', $this->selectedAgeGroupId);
        }

        if ($this->selectedCourtId) {
            $filterMatchNumbersQuery->whereHas('drawings', function ($q) {
                $q->where('court_id', $this->selectedCourtId);
            });
        }

        if ($this->selectedPoolId) {
            $filterMatchNumbersQuery->whereHas('drawings', function ($q) {
                $q->where('pool_id', $this->selectedPoolId);
            });
        }

        $filterMatchNumbers = $filterMatchNumbersQuery->orderBy('name', 'asc')->get();
        $filterPools = Pool::orderBy('order', 'asc')->get();

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
            if ($this->selectedPoolId) {
                $drawingsQuery->where('pool_id', $this->selectedPoolId);
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
            'filterAgeGroups' => $filterAgeGroups,
            'filterMatchNumbers' => $filterMatchNumbers,
            'filterPools' => $filterPools,
        ]);
    }
}

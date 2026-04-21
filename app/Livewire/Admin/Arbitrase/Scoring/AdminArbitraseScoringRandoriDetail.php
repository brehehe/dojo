<?php

namespace App\Livewire\Admin\Arbitrase\Scoring;

use App\Models\MatchNumber\MatchNumber;
use App\Models\RandoriMatchResult;
use App\Models\RandoriJudgeScore;
use App\Services\RandoriScoringService;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Computed;
use Livewire\Component;

#[Layout('layouts.admin')]
class AdminArbitraseScoringRandoriDetail extends Component
{
    public MatchNumber $matchNumber;

    public $drawingData = [];

    public $activeMatch = null;

    public $scoreRed = 0;

    public $scoreBlue = 0;

    public $showModal = false;

    #[Computed]
    public function scoringStatus()
    {
        if (!$this->activeMatch) return collect();

        $nodeKey = $this->activeMatch['bracket'].'_'.$this->activeMatch['round'].'_'.$this->activeMatch['match'];
        
        return RandoriJudgeScore::where('match_number_id', $this->matchNumber->id)
            ->where('bracket_node', $nodeKey)
            ->get()
            ->keyBy('judge_index');
    }

    public function mount(MatchNumber $matchNumber)
    {
        try {
            $this->matchNumber = $matchNumber;
            $drawingData = $matchNumber->drawing_data ?? [];

            // Migrate legacy single-elimination to double_elimination if needed
            if (! isset($drawingData['bracket_type']) || $drawingData['bracket_type'] !== 'double_elimination') {
                $drawingData = $this->migrateLegacyBracket($drawingData);
                if ($drawingData) {
                    $this->matchNumber->update(['drawing_data' => $drawingData]);
                }
            }

            $this->drawingData = $drawingData ?? [];
        } catch (\Exception $e) {
            logger()->error('Error mounting Randori Scoring: '.$e->getMessage());
        }
    }

    /** Wrap old single-elimination 'rounds' format into double_elimination UB only. */
    private function migrateLegacyBracket(array $data): array
    {
        if (empty($data) || (! isset($data['rounds']) && ! isset($data['bracket']))) {
            return $data;
        }

        $rounds = $data['rounds'] ?? [];
        $bracketSize = $data['bracket_size'] ?? 0;

        if (empty($rounds) || $bracketSize < 2) {
            return $data;
        }

        // Build minimal double-elimination wrapper around the existing UB rounds
        $ubRounds = [];
        foreach ($rounds as $r => $matches) {
            $roundArr = [];
            foreach ($matches as $m => $match) {
                $roundArr[] = array_merge($match, [
                    'winner' => null,
                    'winner_data' => null,
                    'winner_next' => null,
                    'loser_next' => null,
                ]);
            }
            $ubRounds[] = $roundArr;
        }

        return [
            'bracket_type' => 'double_elimination',
            'bracket_size' => $bracketSize,
            'total_athletes' => $data['total_entries'] ?? 0,
            'upper_bracket' => ['rounds' => $ubRounds],
            'lower_bracket' => ['rounds' => []],
            'grand_final' => ['athlete1' => null, 'athlete2' => null, 'winner' => null, 'winner_data' => null],
            'juara' => [],
        ];
    }

    // ─── REPAIR BRACKET ROUTING ───────────────────────────────
    //
    // Bracket lama yang di-generate sebelum fix mungkin memiliki
    // winner_next / loser_next = null di beberapa match LB/UB.
    // Method ini memperbaiki routing tersebut berdasarkan logika
    // yang sama dengan generator, lalu mem-replay semua hasil
    // yang sudah tersimpan di RandoriMatchResult.

    public function repairBracket(): void
    {
        $data = $this->matchNumber->fresh()->drawing_data ?? [];

        if (empty($data['upper_bracket']['rounds'])) {
            return;
        }

        $ubRounds = $data['upper_bracket']['rounds'] ?? [];
        $lbRounds = $data['lower_bracket']['rounds'] ?? [];
        $totalUB = count($ubRounds);
        $totalLB = count($lbRounds);
        $lbFinalIdx = $totalLB - 1;

        // ── 1. Fix UB round routing ───────────────────────────
        foreach ($ubRounds as $r => $matches) {
            $isUBFinal = ($r === $totalUB - 1);
            $countInRound = count($matches);

            foreach ($matches as $m => $match) {
                // Skip if already has winner_next
                if (! empty($match['winner_next'])) {
                    continue;
                }

                if ($isUBFinal) {
                    $ubRounds[$r][$m]['winner_next'] = ['bracket' => 'gf', 'slot' => 'athlete1'];
                    $ubRounds[$r][$m]['loser_next'] = ['bracket' => 'lb', 'round' => $lbFinalIdx, 'match' => 0, 'slot' => 'athlete1'];
                } else {
                    $ubRounds[$r][$m]['winner_next'] = [
                        'bracket' => 'ub',
                        'round' => $r + 1,
                        'match' => (int) ($m / 2),
                        'slot' => $m % 2 === 0 ? 'athlete1' : 'athlete2',
                    ];
                    // Loser drops to LB — round depends on UB round
                    $ubRounds[$r][$m]['loser_next'] = [
                        'bracket' => 'lb',
                        'round' => $r === 0 ? 0 : max(0, 2 * ($r - 1) + 1),
                        'match' => $m,
                        'slot' => 'athlete1',
                    ];
                }

                // Ensure winner/winner_data keys exist
                $ubRounds[$r][$m]['winner'] = $ubRounds[$r][$m]['winner'] ?? null;
                $ubRounds[$r][$m]['winner_data'] = $ubRounds[$r][$m]['winner_data'] ?? null;
            }
        }

        // ── 2. Fix LB round routing ───────────────────────────
        foreach ($lbRounds as $lr => $matches) {
            $isLBFinal = ($lr === $lbFinalIdx);
            $countInPrev = $lr > 0 ? count($lbRounds[$lr - 1]) : 0;

            foreach ($matches as $m => $match) {
                // Ensure keys exist
                $lbRounds[$lr][$m]['winner'] = $lbRounds[$lr][$m]['winner'] ?? null;
                $lbRounds[$lr][$m]['winner_data'] = $lbRounds[$lr][$m]['winner_data'] ?? null;

                // Skip if routing already filled
                if (! empty($match['winner_next'])) {
                    continue;
                }

                if ($isLBFinal) {
                    $lbRounds[$lr][$m]['winner_next'] = ['bracket' => 'gf', 'slot' => 'athlete2'];
                    $lbRounds[$lr][$m]['loser_next'] = ['bracket' => 'ranked', 'rank' => 3];
                } elseif ($lr % 2 === 1) {
                    // Odd LB rounds: UB loser drops in, winners advance straight
                    $nextLB = $lr + 1;
                    $lbRounds[$lr][$m]['winner_next'] = $nextLB >= $lbFinalIdx
                        ? ['bracket' => 'lb', 'round' => $lbFinalIdx, 'match' => 0, 'slot' => 'athlete2']
                        : ['bracket' => 'lb', 'round' => $nextLB, 'match' => (int) ($m / 2), 'slot' => $m % 2 === 0 ? 'athlete1' : 'athlete2'];
                    $lbRounds[$lr][$m]['loser_next'] = ['bracket' => 'eliminated'];
                } else {
                    // Even LB rounds (including R0): internal LB matches, loser back to top bracket
                    if ($lr === 0) {
                        $lbRounds[$lr][$m]['winner_next'] = [
                            'bracket' => 'lb', 'round' => 1, 'match' => (int) ($m / 2),
                            'slot' => $m % 2 === 0 ? 'athlete1' : 'athlete2',
                        ];
                    } else {
                        $nextDrop = $lr + 1;
                        $lbRounds[$lr][$m]['winner_next'] = $nextDrop >= $lbFinalIdx
                            ? ['bracket' => 'lb', 'round' => $lbFinalIdx, 'match' => 0, 'slot' => 'athlete2']
                            : ['bracket' => 'lb', 'round' => $nextDrop, 'match' => $m, 'slot' => 'athlete2'];
                    }
                    $lbRounds[$lr][$m]['loser_next'] = ['bracket' => 'eliminated'];
                }
            }
        }

        $data['upper_bracket']['rounds'] = $ubRounds;
        $data['lower_bracket']['rounds'] = $lbRounds;

        // ── 3. Replay semua hasil dari RandoriMatchResult ─────
        // Reset semua posisi atlet di bracket (kecuali R0 yang sudah punya atlet)
        // lalu re-apply setiap result yang tersimpan secara berurutan.
        $results = RandoriMatchResult::where('match_number_id', $this->matchNumber->id)
            ->orderBy('id')
            ->get();

        foreach ($results as $result) {
            $parts = explode('_', $result->bracket_node);
            // nodes: ub_0_0, lb_1_2, gf_0_0
            if (count($parts) < 3) {
                continue;
            }

            $bracket = $parts[0];
            $roundIdx = (int) $parts[1];
            $matchIdx = (int) $parts[2];

            // Get match from repaired data
            if ($bracket === 'ub') {
                $match = $data['upper_bracket']['rounds'][$roundIdx][$matchIdx] ?? null;
            } elseif ($bracket === 'lb') {
                $match = $data['lower_bracket']['rounds'][$roundIdx][$matchIdx] ?? null;
            } elseif ($bracket === 'gf') {
                $match = $data['grand_final'] ?? null;
            } else {
                continue;
            }

            if (! $match) {
                continue;
            }

            $winnerSlot = $result->winner_color; // 'athlete1' | 'athlete2'
            $loserSlot = $winnerSlot === 'athlete1' ? 'athlete2' : 'athlete1';

            $winnerData = $match[$winnerSlot] ?? null;
            $loserData = $match[$loserSlot] ?? null;

            if (! $winnerData) {
                continue;
            }

            $match['winner'] = $winnerSlot;
            $match['winner_data'] = $winnerData;

            if ($bracket === 'ub') {
                $data['upper_bracket']['rounds'][$roundIdx][$matchIdx] = $match;
            } elseif ($bracket === 'lb') {
                $data['lower_bracket']['rounds'][$roundIdx][$matchIdx] = $match;
            } elseif ($bracket === 'gf') {
                $data['grand_final'] = $match;
            }

            // Propagate winner
            $winnerNext = $match['winner_next'] ?? null;
            if ($winnerNext) {
                $data = $this->placeAthlete($data, $winnerNext, $winnerData);
            }

            // Propagate loser
            $loserNext = $match['loser_next'] ?? null;
            if ($loserData && $loserNext) {
                $lb = $loserNext['bracket'] ?? 'eliminated';
                if ($lb === 'lb') {
                    $data = $this->placeAthlete($data, $loserNext, $loserData);
                } elseif ($lb === 'ranked') {
                    $data['juara'][$loserNext['rank']] = $loserData;
                }
            }

            // Grand Final
            if ($bracket === 'gf') {
                $data['juara'][1] = $winnerData;
                $data['juara'][2] = $loserData;
            }
        }

        $this->matchNumber->update(['drawing_data' => $data]);
        $this->drawingData = $data;

        $fixed = count($results);
        $this->dispatch('swal', [
            'icon' => 'success',
            'title' => 'Bracket Diperbaiki',
            'text' => 'Routing diperbaiki & '.$fixed.' hasil di-replay ulang. Lanjutkan input skor.',
        ]);
    }

    // ─── PANGGIL PERTANDINGAN ─────────────────────────────────

    public function callMatch(string $bracket, int $roundIdx, int $matchIdx)
    {
        $nodeKey = $bracket.'_'.$roundIdx.'_'.$matchIdx;
        $this->matchNumber->update(['active_bracket_node' => $nodeKey]);

        // Sinkronisasi dengan Lapangan agar TV Monitor terupdate
        $drawing = \App\Models\DrawingMatchNumber::with('court')
            ->where('match_number_id', $this->matchNumber->id)
            ->first();

        if ($drawing && $drawing->court_id) {
            $drawing->court->update([
                'active_match_id' => $this->matchNumber->id,
                'active_drawing_id' => $drawing->id,
                'active_registration_id' => null,
                'active_bracket_node' => $nodeKey,
            ]);
        }

        $this->dispatch('swal', [
            'icon' => 'success',
            'title' => 'Pertandingan Dipanggil',
            'text' => 'Wasit dan TV Monitor kini menampilkan pertandingan ini.',
        ]);
    }

    public function callGrandFinal()
    {
        $this->matchNumber->update(['active_bracket_node' => 'gf_0_0']);

        // Sinkronisasi dengan Lapangan agar TV Monitor terupdate
        $drawing = \App\Models\DrawingMatchNumber::with('court')
            ->where('match_number_id', $this->matchNumber->id)
            ->first();

        if ($drawing && $drawing->court_id) {
            $drawing->court->update([
                'active_match_id' => $this->matchNumber->id,
                'active_drawing_id' => $drawing->id,
                'active_registration_id' => null,
                'active_bracket_node' => 'gf_0_0',
            ]);
        }

        $this->dispatch('swal', [
            'icon' => 'success',
            'title' => 'Grand Final Dipanggil',
            'text' => 'Wasit dan TV Monitor kini menampilkan Grand Final.',
        ]);
    }

    // ─── BUKA MODAL SKOR ─────────────────────────────────────

    public function openMatchModal(string $bracket, int $roundIdx, int $matchIdx)
    {
        $match = $this->getMatchData($bracket, $roundIdx, $matchIdx);

        if (! $match || (! $match['athlete1'] && ! $match['athlete2'])) {
            return;
        }

        $this->activeMatch = [
            'bracket' => $bracket,
            'round' => $roundIdx,
            'match' => $matchIdx,
            'data' => $match,
        ];

        $nodeKey = $bracket.'_'.$roundIdx.'_'.$matchIdx;
        $existing = RandoriMatchResult::where('match_number_id', $this->matchNumber->id)
            ->where('bracket_node', $nodeKey)
            ->first();

        $this->scoreRed = $existing?->score_red ?? 0;
        $this->scoreBlue = $existing?->score_blue ?? 0;

        $this->showModal = true;
    }

    public function openGrandFinalModal()
    {
        $gf = $this->drawingData['grand_final'] ?? null;

        if (! $gf || (! $gf['athlete1'] && ! $gf['athlete2'])) {
            return;
        }

        $this->activeMatch = [
            'bracket' => 'gf',
            'round' => 0,
            'match' => 0,
            'data' => $gf,
        ];

        $existing = RandoriMatchResult::where('match_number_id', $this->matchNumber->id)
            ->where('bracket_node', 'gf_0_0')
            ->first();

        $this->scoreRed = $existing?->score_red ?? 0;
        $this->scoreBlue = $existing?->score_blue ?? 0;

        $this->showModal = true;
    }

    // ─── SIMPAN PEMENANG + PROPAGASI ─────────────────────────

    /** 
     * Auto Calculate and Determine Winner based on Judge Scores.
     */
    public function autoDetermineWinner(string $bracket, int $roundIdx, int $matchIdx, RandoriScoringService $scoringService)
    {
        $nodeKey = $bracket.'_'.$roundIdx.'_'.$matchIdx;
        $result = $scoringService->calculateResult($this->matchNumber->id, $nodeKey);
        
        $this->scoreRed = $result['total_aka'];
        $this->scoreBlue = $result['total_shiro'];
        
        if ($result['winnerColor']) {
            $this->selectWinner($bracket, $roundIdx, $matchIdx, $result['winnerColor']);
        } else {
            $this->dispatch('swal', [
                'icon' => 'warning',
                'title' => 'Skor Seri (Hikiwake)',
                'text' => 'Tentukan pemenang secara manual atau lakukan perpanjangan waktu.',
            ]);
        }
    }

    public function selectWinner(string $bracket, int $roundIdx, int $matchIdx, string $winnerSlot)
    {
        $data = $this->matchNumber->drawing_data;

        // Get match reference
        if ($bracket === 'ub') {
            $match = $data['upper_bracket']['rounds'][$roundIdx][$matchIdx] ?? null;
        } elseif ($bracket === 'lb') {
            $match = $data['lower_bracket']['rounds'][$roundIdx][$matchIdx] ?? null;
        } elseif ($bracket === 'gf') {
            $match = $data['grand_final'] ?? null;
        } else {
            return;
        }

        if (! $match) {
            return;
        }

        $winnerData = $match[$winnerSlot] ?? null;
        $loserSlot = ($winnerSlot === 'athlete1') ? 'athlete2' : 'athlete1';
        $loserData = $match[$loserSlot] ?? null;

        if (! $winnerData) {
            return;
        }

        // Mark winner
        $match['winner'] = $winnerSlot;
        $match['winner_data'] = $winnerData;

        // Update match back into data array
        if ($bracket === 'ub') {
            $data['upper_bracket']['rounds'][$roundIdx][$matchIdx] = $match;
        } elseif ($bracket === 'lb') {
            $data['lower_bracket']['rounds'][$roundIdx][$matchIdx] = $match;
        } elseif ($bracket === 'gf') {
            $data['grand_final'] = $match;
        }

        // ── Propagate WINNER ────────────────────────────────
        $winnerNext = $match['winner_next'] ?? null;
        if ($winnerNext) {
            $data = $this->placeAthlete($data, $winnerNext, $winnerData);
        }

        // ── Propagate LOSER ─────────────────────────────────
        $loserNext = $match['loser_next'] ?? null;
        if ($loserData && $loserNext) {
            $loserBracket = $loserNext['bracket'] ?? 'eliminated';

            if ($loserBracket === 'lb') {
                $data = $this->placeAthlete($data, $loserNext, $loserData);
            } elseif ($loserBracket === 'ranked') {
                $data['juara'][$loserNext['rank']] = $loserData;
            }
            // 'eliminated' — no action needed
        }

        // ── Grand Final special: record Juara 1 & 2 ─────────
        if ($bracket === 'gf') {
            $data['juara'][1] = $winnerData;
            $data['juara'][2] = $loserData;
        }

        // ── Save result to DB ────────────────────────────────
        $nodeKey = $bracket.'_'.$roundIdx.'_'.$matchIdx;
        RandoriMatchResult::updateOrCreate(
            ['match_number_id' => $this->matchNumber->id, 'bracket_node' => $nodeKey],
            [
                'bracket_node_index' => $roundIdx.'_'.$matchIdx,
                'bracket_section' => $bracket,
                'winner_color' => $winnerSlot,
                'score_red' => $this->scoreRed,
                'score_blue' => $this->scoreBlue,
            ]
        );

        $this->matchNumber->update(['drawing_data' => $data]);
        $this->drawingData = $data;
        $this->showModal = false;
        $this->scoreRed = 0;
        $this->scoreBlue = 0;

        $this->dispatch('swal', [
            'icon' => 'success',
            'title' => 'Pemenang Dicatat!',
            'text' => ($winnerData['name'] ?? '-').' maju ke babak berikutnya.',
        ]);
    }

    /** Place an athlete into the specified bracket position. */
    private function placeAthlete(array $data, array $next, array $athleteData): array
    {
        $b = $next['bracket'] ?? null;
        $slot = $next['slot'] ?? 'athlete1';

        if ($b === 'ub') {
            $data['upper_bracket']['rounds'][$next['round']][$next['match']][$slot] = $athleteData;
        } elseif ($b === 'lb') {
            $data['lower_bracket']['rounds'][$next['round']][$next['match']][$slot] = $athleteData;
        } elseif ($b === 'gf') {
            $data['grand_final'][$slot] = $athleteData;
        }

        return $data;
    }

    /** Get match data from drawing_data by bracket/round/match. */
    private function getMatchData(string $bracket, int $roundIdx, int $matchIdx): ?array
    {
        return match ($bracket) {
            'ub' => $this->drawingData['upper_bracket']['rounds'][$roundIdx][$matchIdx] ?? null,
            'lb' => $this->drawingData['lower_bracket']['rounds'][$roundIdx][$matchIdx] ?? null,
            default => null,
        };
    }

    // ─── RENDER ──────────────────────────────────────────────

    public function render()
    {
        $results = RandoriMatchResult::where('match_number_id', $this->matchNumber->id)
            ->get()
            ->keyBy('bracket_node');

        $this->drawingData = $this->matchNumber->fresh()->drawing_data ?? [];

        // Detect if bracket has any null winner_next / loser_next that need repair
        $needsRepair = $this->detectBrokenRouting($this->drawingData);

        return view('livewire.admin.arbitrase.scoring.admin-arbitrase-scoring-randori-detail', [
            'results' => $results,
            'drawingData' => $this->drawingData,
            'juara' => $this->drawingData['juara'] ?? [],
            'needsRepair' => $needsRepair,
        ]);
    }

    /** Check if any match in UB/LB has null winner_next. */
    private function detectBrokenRouting(array $data): bool
    {
        foreach ($data['upper_bracket']['rounds'] ?? [] as $r => $matches) {
            foreach ($matches as $match) {
                if (empty($match['winner_next'])) {
                    return true;
                }
            }
        }
        foreach ($data['lower_bracket']['rounds'] ?? [] as $r => $matches) {
            foreach ($matches as $match) {
                if (empty($match['winner_next'])) {
                    return true;
                }
            }
        }

        return false;
    }
}

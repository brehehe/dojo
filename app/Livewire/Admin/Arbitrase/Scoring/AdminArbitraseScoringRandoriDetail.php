<?php

namespace App\Livewire\Admin\Arbitrase\Scoring;

use App\Models\Court\Court;
use App\Models\DrawingMatchNumber;
use App\Models\MatchNumber\MatchNumber;
use App\Models\RandoriMatchResult;
use Illuminate\Support\Facades\Cache;
use Livewire\Attributes\Layout;
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

    public $scoringAka = [
        'mujoken_kachi' => 0,
        'ippon' => 0,
        'waza_ari' => 0,
        'hasil_batsu_5' => 0,
        'hasil_batsu_10' => 0,
        'yusei_kachi' => 0,
    ];

    public $scoringShiro = [
        'mujoken_kachi' => 0,
        'ippon' => 0,
        'waza_ari' => 0,
        'hasil_batsu_5' => 0,
        'hasil_batsu_10' => 0,
        'yusei_kachi' => 0,
    ];

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
            $isLBSemi = ($lr === $lbFinalIdx - 1);
            $countInPrev = $lr > 0 ? count($lbRounds[$lr - 1]) : 0;

            foreach ($matches as $m => $match) {
                // Ensure keys exist
                $lbRounds[$lr][$m]['winner'] = $lbRounds[$lr][$m]['winner'] ?? null;
                $lbRounds[$lr][$m]['winner_data'] = $lbRounds[$lr][$m]['winner_data'] ?? null;

                if ($isLBFinal) {
                    $lbRounds[$lr][$m]['winner_next'] = ['bracket' => 'gf', 'slot' => 'athlete2'];
                    $lbRounds[$lr][$m]['loser_next'] = ['bracket' => 'ranked', 'rank' => 3];
                } elseif ($isLBSemi) {
                    $lbRounds[$lr][$m]['winner_next'] = ['bracket' => 'lb', 'round' => $lbFinalIdx, 'match' => 0, 'slot' => 'athlete2'];
                    $lbRounds[$lr][$m]['loser_next'] = ['bracket' => 'ranked', 'rank' => 4];
                } elseif ($lr % 2 === 1) {
                    // Odd LB rounds: UB loser drops in, winners advance straight
                    $nextLB = $lr + 1;
                    if (empty($lbRounds[$lr][$m]['winner_next'])) {
                        $lbRounds[$lr][$m]['winner_next'] = $nextLB >= $lbFinalIdx
                            ? ['bracket' => 'lb', 'round' => $lbFinalIdx, 'match' => 0, 'slot' => 'athlete2']
                            : ['bracket' => 'lb', 'round' => $nextLB, 'match' => (int) ($m / 2), 'slot' => $m % 2 === 0 ? 'athlete1' : 'athlete2'];
                    }
                    $lbRounds[$lr][$m]['loser_next'] = ['bracket' => 'eliminated'];
                } else {
                    // Even LB rounds (including R0): internal LB matches
                    if ($lr === 0) {
                        if (empty($lbRounds[$lr][$m]['winner_next'])) {
                            $lbRounds[$lr][$m]['winner_next'] = [
                                'bracket' => 'lb',
                                'round' => 1,
                                'match' => (int) ($m / 2),
                                'slot' => $m % 2 === 0 ? 'athlete1' : 'athlete2',
                            ];
                        }
                    } else {
                        $nextDrop = $lr + 1;
                        if (empty($lbRounds[$lr][$m]['winner_next'])) {
                            $lbRounds[$lr][$m]['winner_next'] = $nextDrop >= $lbFinalIdx
                                ? ['bracket' => 'lb', 'round' => $lbFinalIdx, 'match' => 0, 'slot' => 'athlete2']
                                : ['bracket' => 'lb', 'round' => $nextDrop, 'match' => $m, 'slot' => 'athlete2'];
                        }
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

        // Pastikan drawingData ter-sync dengan DB
        $this->drawingData = $this->matchNumber->fresh()->drawing_data ?? [];

        // Sinkronisasi dengan Lapangan agar TV Monitor terupdate
        $drawing = DrawingMatchNumber::with('court')
            ->where('match_number_id', $this->matchNumber->id)
            ->first();

        if ($drawing && $drawing->court_id) {
            $drawing->court->update([
                'active_match_id' => $this->matchNumber->id,
                'active_drawing_id' => $drawing->id,
                'active_registration_id' => null,
                'active_bracket_node' => $nodeKey,
            ]);

            // Handle mapping bracket keys
            $targetBracket = $bracket;
            if ($bracket === 'ub') {
                $targetBracket = 'upper_bracket';
            }
            if ($bracket === 'lb') {
                $targetBracket = 'lower_bracket';
            }

            // Announcement
            $match = $this->drawingData[$targetBracket]['rounds'][$roundIdx][$matchIdx] ?? null;

            if ($match) {
                $this->dispatchAnnouncer($match, strtoupper($targetBracket).' Round '.($roundIdx + 1));
            }
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
        $drawing = DrawingMatchNumber::with('court')
            ->where('match_number_id', $this->matchNumber->id)
            ->first();

        if ($drawing && $drawing->court_id) {
            $drawing->court->update([
                'active_match_id' => $this->matchNumber->id,
                'active_drawing_id' => $drawing->id,
                'active_registration_id' => null,
                'active_bracket_node' => 'gf_0_0',
            ]);

            $this->drawingData = $this->matchNumber->fresh()->drawing_data ?? [];

            // Announcement
            $match = $this->drawingData['grand_final'] ?? null;
            if ($match) {
                $this->dispatchAnnouncer($match, 'GRAND FINAL');
            }
        }

        $this->dispatch('swal', [
            'icon' => 'success',
            'title' => 'Grand Final Dipanggil',
            'text' => 'Wasit dan TV Monitor kini menampilkan Grand Final.',
        ]);
    }

    public function dismissMatch()
    {
        // Reset Timer Cache
        $courtId = $this->getCourtId();
        if ($courtId) {
            $state = [
                'status' => 'stopped',
                'elapsed_ms' => 0,
                'started_at_ms' => null,
            ];
            Cache::put("court_{$courtId}_timer", $state);
            $this->dispatch('timer-updated');
        }

        $this->matchNumber->update(['active_bracket_node' => null]);

        $drawing = DrawingMatchNumber::with('court')
            ->where('match_number_id', $this->matchNumber->id)
            ->first();

        if ($drawing && $drawing->court_id) {
            $drawing->court->update([
                'active_match_id' => null,
                'active_drawing_id' => null,
                'active_registration_id' => null,
                'active_bracket_node' => null,
            ]);
        }

        $this->dispatch('swal', [
            'icon' => 'success',
            'title' => 'Panggilan Ditutup',
            'text' => 'Wasit, TV Monitor, dan Timer telah direset.',
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

        $this->loadScoringData($existing);

        $this->showModal = true;
    }

    private function loadScoringData(?RandoriMatchResult $existing)
    {
        $this->scoreRed = $existing?->score_red ?? 0;
        $this->scoreBlue = $existing?->score_blue ?? 0;

        $metadata = $existing?->metadata ? (is_array($existing->metadata) ? $existing->metadata : json_decode($existing->metadata, true)) : [];

        $this->scoringAka = $metadata['scoringAka'] ?? [
            'mujoken_kachi' => 0,
            'ippon' => 0,
            'waza_ari' => 0,
            'hasil_batsu_5' => 0,
            'hasil_batsu_10' => 0,
            'yusei_kachi' => 0,
        ];

        $this->scoringShiro = $metadata['scoringShiro'] ?? [
            'mujoken_kachi' => 0,
            'ippon' => 0,
            'waza_ari' => 0,
            'hasil_batsu_5' => 0,
            'hasil_batsu_10' => 0,
            'yusei_kachi' => 0,
        ];
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

        $this->loadScoringData($existing);

        $this->showModal = true;
    }

    public function updateScore(string $color, string $type, int $delta)
    {
        if ($color === 'aka') {
            $this->scoringAka[$type] = max(0, $this->scoringAka[$type] + $delta);
        } else {
            $this->scoringShiro[$type] = max(0, $this->scoringShiro[$type] + $delta);
        }
        $this->calculateTotals();
    }

    public function resetDetailedScoring()
    {
        $this->scoreRed = 0;
        $this->scoreBlue = 0;
        $this->scoringAka = [
            'mujoken_kachi' => 0,
            'ippon' => 0,
            'waza_ari' => 0,
            'hasil_batsu_5' => 0,
            'hasil_batsu_10' => 0,
            'yusei_kachi' => 0,
        ];
        $this->scoringShiro = [
            'mujoken_kachi' => 0,
            'ippon' => 0,
            'waza_ari' => 0,
            'hasil_batsu_5' => 0,
            'hasil_batsu_10' => 0,
            'yusei_kachi' => 0,
        ];
    }

    private function calculateTotals()
    {
        $weights = [
            'mujoken_kachi' => 15,
            'ippon' => 10,
            'waza_ari' => 5,
            'hasil_batsu_5' => -5, // Reduction
            'hasil_batsu_10' => -10, // Reduction
            'yusei_kachi' => 5,
        ];

        $this->scoreRed = 0;
        foreach ($this->scoringAka as $type => $count) {
            $this->scoreRed += $count * ($weights[$type] ?? 0);
        }

        $this->scoreBlue = 0;
        foreach ($this->scoringShiro as $type => $count) {
            $this->scoreBlue += $count * ($weights[$type] ?? 0);
        }
    }

    // ─── SIMPAN PEMENANG + PROPAGASI ─────────────────────────

    /**
     * Determine Winner based on manual tally from the scoring table.
     */
    public function submitScoring()
    {
        if (! $this->activeMatch) {
            return;
        }

        $bracket = $this->activeMatch['bracket'];
        $roundIdx = $this->activeMatch['round'];
        $matchIdx = $this->activeMatch['match'];

        if ($this->scoreRed > $this->scoreBlue) {
            $this->selectWinner($bracket, $roundIdx, $matchIdx, 'athlete1');
        } elseif ($this->scoreBlue > $this->scoreRed) {
            $this->selectWinner($bracket, $roundIdx, $matchIdx, 'athlete2');
        } else {
            $this->dispatch('swal', [
                'icon' => 'warning',
                'title' => 'Skor Seri (Hikiwake)',
                'text' => 'Poin sama (Hikiwake). Silahkan Masukan Nilai.',
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
            if (($winnerNext['bracket'] ?? '') === 'ranked') {
                $data['juara'][$winnerNext['rank']] = $winnerData;
            } else {
                $data = $this->placeAthlete($data, $winnerNext, $winnerData);
            }
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

        // ── Auto-Propagate BYE (BYE) matches ────────────────
        $data = $this->propagateBracketByes($data);

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
                'metadata' => json_encode([
                    'scoringAka' => $this->scoringAka,
                    'scoringShiro' => $this->scoringShiro,
                ]),
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

    private function propagateBracketByes(array $data): array
    {
        $ubRounds = $data['upper_bracket']['rounds'] ?? [];
        $lbRounds = $data['lower_bracket']['rounds'] ?? [];

        // Propagate LB (Cascade)
        $changed = true;
        while ($changed) {
            $changed = false;
            foreach ($lbRounds as $rIdx => &$round) {
                foreach ($round as $mIdx => &$match) {
                    if (($match['is_bye'] ?? false) || ($match['winner'] ?? null) !== null) {
                        continue;
                    }

                    $a1IsBye = isset($match['athlete1']['id']) && $match['athlete1']['id'] === 'BYE';
                    $a2IsBye = isset($match['athlete2']['id']) && $match['athlete2']['id'] === 'BYE';

                    if ($a1IsBye && $a2IsBye) {
                        $match['is_bye'] = true;
                        $match['winner'] = 'none';
                        $match['winner_data'] = ['id' => 'BYE', 'name' => 'BYE', 'contingent' => '-'];
                        $changed = true;
                    } elseif ($a1IsBye && ($match['athlete2'] ?? null) !== null) {
                        $match['is_bye'] = true;
                        $match['winner'] = 'athlete2';
                        $match['winner_data'] = $match['athlete2'];
                        $changed = true;
                    } elseif ($a2IsBye && ($match['athlete1'] ?? null) !== null) {
                        $match['is_bye'] = true;
                        $match['winner'] = 'athlete1';
                        $match['winner_data'] = $match['athlete1'];
                        $changed = true;
                    }

                    if (($match['is_bye'] ?? false) && ($match['winner'] ?? null) !== null) {
                        if (($match['winner_next']['bracket'] ?? '') === 'lb') {
                            $wn = $match['winner_next'];
                            $lbRounds[$wn['round']][$wn['match']][$wn['slot']] = $match['winner_data'];
                        }
                    }
                }
            }
        }

        $data['lower_bracket']['rounds'] = $lbRounds;

        return $data;
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

    // ─── TIMER CONTROLS (SYNC WITH MONITOR COURT) ─────────────────────────

    public function getCourtId()
    {
        return $this->matchNumber->drawings->first()?->court_id;
    }

    public function getTimerState()
    {
        $courtId = $this->getCourtId();
        if (! $courtId) {
            return ['status' => 'stopped', 'elapsed_ms' => 0, 'started_at_ms' => null];
        }

        return Cache::get("court_{$courtId}_timer", [
            'status' => 'stopped',
            'elapsed_ms' => 0,
            'started_at_ms' => null,
        ]);
    }

    public function startCountdown()
    {
        $courtId = $this->getCourtId();
        if (! $courtId) {
            return;
        }

        $state = Cache::get("court_{$courtId}_timer", ['status' => 'stopped', 'elapsed_ms' => 0, 'started_at_ms' => null]);
        $state['status'] = 'countdown';
        $state['countdown_end_ms'] = floor(microtime(true) * 1000) + 5000;
        Cache::put("court_{$courtId}_timer", $state);
        $this->dispatch('timer-updated');
    }

    public function startTimer()
    {
        $courtId = $this->getCourtId();
        if (! $courtId) {
            return;
        }

        $state = Cache::get("court_{$courtId}_timer", ['status' => 'stopped', 'elapsed_ms' => 0, 'started_at_ms' => null]);
        if ($state['status'] !== 'running') {
            $state['status'] = 'running';
            $state['started_at_ms'] = floor(microtime(true) * 1000);
            Cache::put("court_{$courtId}_timer", $state);
            $this->dispatch('timer-updated');
        }
    }

    public function pauseTimer()
    {
        $courtId = $this->getCourtId();
        if (! $courtId) {
            return;
        }

        $state = Cache::get("court_{$courtId}_timer", ['status' => 'stopped', 'elapsed_ms' => 0, 'started_at_ms' => null]);
        if ($state['status'] === 'running') {
            $now = floor(microtime(true) * 1000);
            $elapsedSinceStart = $now - $state['started_at_ms'];

            $state['status'] = 'paused';
            $state['elapsed_ms'] += $elapsedSinceStart;
            $state['started_at_ms'] = null;
            Cache::put("court_{$courtId}_timer", $state);
            $this->dispatch('timer-updated');
        }
    }

    public function stopTimer()
    {
        $courtId = $this->getCourtId();
        if (! $courtId) {
            return;
        }

        $state = [
            'status' => 'stopped',
            'elapsed_ms' => 0,
            'started_at_ms' => null,
        ];
        Cache::put("court_{$courtId}_timer", $state);
        $this->dispatch('timer-updated');
    }

    public function finishMatch()
    {
        $courtId = $this->getCourtId();
        if ($courtId) {
            $state = [
                'status' => 'stopped',
                'elapsed_ms' => 0,
                'started_at_ms' => null,
            ];
            Cache::put("court_{$courtId}_timer", $state);
            $this->dispatch('timer-updated');
        }

        // Close call
        $this->matchNumber->update(['active_bracket_node' => null]);

        $drawing = DrawingMatchNumber::with('court')
            ->where('match_number_id', $this->matchNumber->id)
            ->first();

        if ($drawing && $drawing->court_id) {
            $drawing->court->update([
                'active_match_id' => null,
                'active_drawing_id' => null,
                'active_registration_id' => null,
                'active_bracket_node' => null,
            ]);
        }

        $this->dispatch('swal', [
            'icon' => 'success',
            'title' => 'Pertandingan Selesai!',
            'text' => 'Panggilan ditutup.',
        ]);
    }

    private function dispatchAnnouncer(array $match, string $label)
    {
        $a1 = $match['athlete1'] ?? null;
        $a2 = $match['athlete2'] ?? null;
        $court = DrawingMatchNumber::with('court')
            ->where('match_number_id', $this->matchNumber->id)
            ->first()?->court;

        $courtName = $court ? $court->name : 'lapangan';
        $matchName = $this->matchNumber->name;

        $text = "Panggilan pertandingan untuk kategori {$matchName}, {$label}. ";

        if ($a1 && isset($a1['registration_id'])) {
            $names1 = $this->matchNumber->athletes()
                ->wherePivot('registration_id', $a1['registration_id'])
                ->pluck('name')
                ->implode(', ');
            $text .= "Sudut Merah, atas nama {$names1} dari {$a1['contingent']}. ";
        }
        if ($a2 && isset($a2['registration_id'])) {
            $names2 = $this->matchNumber->athletes()
                ->wherePivot('registration_id', $a2['registration_id'])
                ->pluck('name')
                ->implode(', ');
            $text .= "Sudut Putih, atas nama {$names2} dari {$a2['contingent']}. ";
        }

        $text .= "Silakan menuju {$courtName}. Sekali lagi, panggilan pertandingan untuk {$matchName}. Silakan menuju {$courtName}. Terima kasih.";

        $this->dispatch('play-announcer', ['text' => $text]);
    }

    public function clearCourt(int $courtId): void
    {
        $court = Court::findOrFail($courtId);
        $court->update([
            'active_match_id' => null,
            'active_registration_id' => null,
            'active_bracket_node' => null,
            'active_drawing_id' => null,
        ]);

        // Clear timer cache for this court
        Cache::forget("court_{$courtId}_timer");

        $this->dispatch('swal', [
            'icon' => 'info',
            'title' => 'Lapangan Dibersihkan',
            'text' => $court->name.' sekarang idle / kosong.',
        ]);
    }

    public function clearAllCourts(): void
    {
        $allCourts = Court::all();

        // Reset all courts
        foreach ($allCourts as $court) {
            $court->update([
                'active_match_id' => null,
                'active_registration_id' => null,
                'active_bracket_node' => null,
                'active_drawing_id' => null,
            ]);

            // Clear timer cache for each court
            Cache::forget("court_{$court->id}_timer");
        }

        // Reset all match numbers active states
        MatchNumber::query()->update([
            'active_registration_id' => null,
            'active_bracket_node' => null,
        ]);

        $this->dispatch('swal', [
            'icon' => 'success',
            'title' => 'Semua Lapangan & Match Di-reset',
            'text' => 'Seluruh status aktif telah dibersihkan secara serentak.',
        ]);
    }
}

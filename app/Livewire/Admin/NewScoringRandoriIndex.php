<?php

namespace App\Livewire\Admin;

use App\Models\Court\Court;
use App\Models\DrawingMatchNumber;
use App\Models\MatchNumber\MatchNumber;
use App\Models\MatchNumberMerge;
use App\Models\RandoriMatchResult;
use App\Models\SchedulePanitera;
use App\Models\ScheduleReferee;
use App\Models\TournamentResult;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.premium')]
class NewScoringRandoriIndex extends Component
{
    public MatchNumber $matchNumber;

    public $merge = null;

    public $displayName = '';

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

    public $matchNumberIds = [];

    public $sigArbitraseName = '';

    public $sigArbitraseData = null;

    public $sigKoordinatorName = '';

    public $sigKoordinatorData = null;

    public $sigWasitName = '';

    public $sigWasitData = null;

    public $sigPanitera = [];

    public $sigManagerRedName = '';

    public $sigManagerRedData = null;

    public $sigManagerWhiteName = '';

    public $sigManagerWhiteData = null;

    public function mount(MatchNumber $matchNumber)
    {
        try {
            $this->matchNumber = $matchNumber;

            // Check if this match is part of a merge
            $mergeDetails = DB::table('match_number_merge_details')
                ->where('match_number_id', $matchNumber->id)
                ->first();

            if ($mergeDetails) {
                $this->merge = MatchNumberMerge::find($mergeDetails->match_number_merge_id);
                $this->matchNumberIds = DB::table('match_number_merge_details')
                    ->where('match_number_merge_id', $mergeDetails->match_number_merge_id)
                    ->pluck('match_number_id')
                    ->toArray();
            } else {
                $this->matchNumberIds = [$matchNumber->id];
            }

            $drawingData = $matchNumber->drawing_data ?? [];

            // Migrate legacy single-elimination to double_elimination if needed
            if (! isset($drawingData['bracket_type']) || $drawingData['bracket_type'] !== 'double_elimination') {
                $drawingData = $this->migrateLegacyBracket($drawingData);
                if ($drawingData) {
                    $this->matchNumber->update(['drawing_data' => $drawingData]);
                }
            }

            $this->drawingData = $drawingData ?? [];

            if ($this->matchNumber->active_bracket_node) {
                $parts = explode('_', $this->matchNumber->active_bracket_node);
                if (count($parts) === 3) {
                    if ($parts[0] === 'gf') {
                        $this->openGrandFinalModal();
                    } else {
                        $this->openMatchModal($parts[0], (int) $parts[1], (int) $parts[2]);
                    }
                }
            }
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

            foreach ($matches as $m => $match) {
                if ($isUBFinal) {
                    $ubRounds[$r][$m]['winner_next'] = ['bracket' => 'gf', 'slot' => 'athlete1'];
                    $ubRounds[$r][$m]['loser_next'] = ['bracket' => 'lb', 'round' => $lbFinalIdx, 'match' => 0, 'slot' => 'athlete2'];
                } else {
                    $ubRounds[$r][$m]['winner_next'] = [
                        'bracket' => 'ub',
                        'round' => $r + 1,
                        'match' => (int) ($m / 2),
                        'slot' => $m % 2 === 0 ? 'athlete1' : 'athlete2',
                    ];

                    if ($r === 0) {
                        $ubRounds[$r][$m]['loser_next'] = [
                            'bracket' => 'lb',
                            'round' => 0,
                            'match' => (int) ($m / 2),
                            'slot' => $m % 2 === 0 ? 'athlete1' : 'athlete2',
                        ];
                    } else {
                        $ubRounds[$r][$m]['loser_next'] = [
                            'bracket' => 'lb',
                            'round' => 2 * $r - 1,
                            'match' => $m,
                            'slot' => 'athlete2',
                        ];
                    }
                }

                $ubRounds[$r][$m]['winner'] = $ubRounds[$r][$m]['winner'] ?? null;
                $ubRounds[$r][$m]['winner_data'] = $ubRounds[$r][$m]['winner_data'] ?? null;
            }
        }

        // ── 2. Fix LB round routing ───────────────────────────
        foreach ($lbRounds as $lr => $matches) {
            $isLBFinal = ($lr === $lbFinalIdx);
            $isLBSemi = ($lr === $lbFinalIdx - 1);

            foreach ($matches as $m => $match) {
                $lbRounds[$lr][$m]['winner'] = $lbRounds[$lr][$m]['winner'] ?? null;
                $lbRounds[$lr][$m]['winner_data'] = $lbRounds[$lr][$m]['winner_data'] ?? null;

                if ($isLBFinal) {
                    $lbRounds[$lr][$m]['winner_next'] = ['bracket' => 'gf', 'slot' => 'athlete2'];
                    $lbRounds[$lr][$m]['loser_next'] = ['bracket' => 'eliminated'];
                } elseif ($isLBSemi) {
                    $lbRounds[$lr][$m]['winner_next'] = ['bracket' => 'lb', 'round' => $lbFinalIdx, 'match' => 0, 'slot' => 'athlete1'];
                    $lbRounds[$lr][$m]['loser_next'] = ['bracket' => 'eliminated'];
                } elseif ($lr % 2 === 1) {
                    $lbRounds[$lr][$m]['winner_next'] = [
                        'bracket' => 'lb',
                        'round' => $lr + 1,
                        'match' => (int) ($m / 2),
                        'slot' => $m % 2 === 0 ? 'athlete1' : 'athlete2',
                    ];
                    $lbRounds[$lr][$m]['loser_next'] = ['bracket' => 'eliminated'];
                } else {
                    $lbRounds[$lr][$m]['winner_next'] = [
                        'bracket' => 'lb',
                        'round' => $lr + 1,
                        'match' => $m,
                        'slot' => 'athlete1',
                    ];
                    $lbRounds[$lr][$m]['loser_next'] = ['bracket' => 'eliminated'];
                }
            }
        }

        $data['upper_bracket']['rounds'] = $ubRounds;
        $data['lower_bracket']['rounds'] = $lbRounds;

        $results = RandoriMatchResult::whereIn('match_number_id', $this->matchNumberIds)
            ->orderBy('id')
            ->get();

        foreach ($results as $result) {
            $parts = explode('_', $result->bracket_node);
            if (count($parts) < 3) {
                continue;
            }

            $bracket = $parts[0];
            $roundIdx = (int) $parts[1];
            $matchIdx = (int) $parts[2];

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

            $winnerSlot = $result->winner_color;
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

            if ($match['winner_next'] ?? null) {
                $data = $this->placeAthlete($data, $match['winner_next'], $winnerData);
            }

            if ($loserData && ($match['loser_next'] ?? null)) {
                $lb = $match['loser_next']['bracket'] ?? 'eliminated';
                if ($lb === 'lb') {
                    $data = $this->placeAthlete($data, $match['loser_next'], $loserData);
                } elseif ($lb === 'ranked') {
                    $data['juara'][$match['loser_next']['rank']] = $loserData;
                }
            }

            if ($bracket === 'gf') {
                $data['juara'][1] = $winnerData;
                $data['juara'][2] = $loserData;
            }
        }

        // 4. Propagate BYEs cascadingly
        $data = $this->propagateBracketByes($data);

        $this->matchNumber->update(['drawing_data' => $data]);
        $this->drawingData = $data;

        $fixed = count($results);
        $this->dispatch('swal', [
            'icon' => 'success',
            'title' => 'Bracket Diperbaiki',
            'text' => 'Routing diperbaiki & '.$fixed.' hasil di-replay ulang.',
        ]);
    }

    public function callOfficials()
    {
        $drawings = DrawingMatchNumber::with(['court', 'sessionTime', 'registration.contingent'])
            ->whereIn('match_number_id', $this->matchNumberIds)
            ->get();

        $firstDrawing = $drawings->first();

        if ($firstDrawing) {
            $matchName = $this->matchNumber->name;
            $courtName = $firstDrawing->court->name ?? 'Lapangan';
            $sessionTime = $firstDrawing->sessionTime ? $firstDrawing->sessionTime->name : '';

            $intro = "Persiapan untuk pertandingan kategori {$matchName}, di {$courtName}, {$sessionTime}. ";

            $contingentNames = $drawings->pluck('registration.contingent.name')->unique()->filter()->values();
            $contingentCall = '';
            if ($contingentNames->isNotEmpty()) {
                $contingentCall = 'Kepada seluruh kontingen: '.$contingentNames->implode(', ').'. Silakan mempersiapkan atletnya. ';
            }

            $refereeNames = ScheduleReferee::with('referee.user')
                ->where('court_id', $firstDrawing->court_id)
                ->where('session_time_id', $firstDrawing->session_time_id)
                ->get()
                ->pluck('referee.user.name')
                ->unique()
                ->filter()
                ->values();

            $refereeCall = '';
            if ($refereeNames->isNotEmpty()) {
                $refereeCall = 'Kepada para dewan juri dan wasit: '.$refereeNames->implode(', ').'. Mohon segera menempati posisi. ';
            }

            $officials = $firstDrawing->metadata['officials'] ?? null;
            $officialCall = '';
            if ($officials) {
                $paniteras = is_array($officials['panitera'] ?? null) ? implode(', ', $officials['panitera']) : ($officials['panitera'] ?? '');
                $korlap = $officials['koordinator_lapangan'] ?? '';

                if ($paniteras) {
                    $officialCall .= "Kepada petugas panitera: {$paniteras}. ";
                }
                if ($korlap) {
                    $officialCall .= "Kepada koordinator lapangan: {$korlap}. ";
                }
            }

            $outro = "Sekali lagi, panggilan untuk seluruh official dan kontingen pada kategori {$matchName}. Mohon segera menuju {$courtName}. Terima kasih.";

            $fullText = $intro.$contingentCall.$refereeCall.$officialCall.$outro;

            $this->dispatch('play-announcer', ['text' => $fullText]);

            $this->dispatch('swal', [
                'icon' => 'success',
                'title' => 'Panggilan Detail Dilakukan',
                'text' => 'Seluruh kontingen, juri, panitera, dan korlap telah dipanggil secara spesifik.',
            ]);
        }
    }

    public function callMatch(string $nodeKey, int $roundIdx, int $matchIdx, string $bracket)
    {
        MatchNumber::whereIn('id', $this->matchNumberIds)->update(['active_bracket_node' => $nodeKey]);
        $this->drawingData = $this->matchNumber->fresh()->drawing_data ?? [];

        $drawing = DrawingMatchNumber::with('court')
            ->whereIn('match_number_id', $this->matchNumberIds)
            ->first();

        if ($drawing && $drawing->court_id) {
            $drawing->court->update([
                'active_match_id' => $drawing->match_number_id,
                'active_drawing_id' => $drawing->id,
                'active_registration_id' => null,
                'active_bracket_node' => $nodeKey,
            ]);

            $targetBracket = $bracket === 'ub' ? 'upper_bracket' : ($bracket === 'lb' ? 'lower_bracket' : $bracket);
            $match = $this->drawingData[$targetBracket]['rounds'][$roundIdx][$matchIdx] ?? null;

            if ($match) {
                $this->dispatchAnnouncer($match, strtoupper($targetBracket).' Round '.($roundIdx + 1));
            }
        }

        $this->openMatchModal($bracket, $roundIdx, $matchIdx);
    }

    public function callGrandFinal()
    {
        MatchNumber::whereIn('id', $this->matchNumberIds)->update(['active_bracket_node' => 'gf_0_0']);

        $drawing = DrawingMatchNumber::with('court')
            ->whereIn('match_number_id', $this->matchNumberIds)
            ->first();

        if ($drawing && $drawing->court_id) {
            $drawing->court->update([
                'active_match_id' => $drawing->match_number_id,
                'active_drawing_id' => $drawing->id,
                'active_registration_id' => null,
                'active_bracket_node' => 'gf_0_0',
            ]);

            $this->drawingData = $this->matchNumber->fresh()->drawing_data ?? [];
            $match = $this->drawingData['grand_final'] ?? null;
            if ($match) {
                $this->dispatchAnnouncer($match, 'GRAND FINAL');
            }
        }

        $this->openGrandFinalModal();
    }

    public function dismissMatch()
    {
        $courtId = $this->getCourtId();
        if ($courtId) {
            Cache::put("court_{$courtId}_timer", ['status' => 'stopped', 'elapsed_ms' => 0, 'started_at_ms' => null]);
            $this->dispatch('timer-updated');
        }

        MatchNumber::whereIn('id', $this->matchNumberIds)->update(['active_bracket_node' => null]);
        $drawing = DrawingMatchNumber::with('court')->whereIn('match_number_id', $this->matchNumberIds)->first();
        if ($drawing && $drawing->court_id) {
            $drawing->court->update(['active_match_id' => null, 'active_drawing_id' => null, 'active_registration_id' => null, 'active_bracket_node' => null]);
        }

        $this->activeMatch = null;
        $this->resetDetailedScoring();
        $this->stopTimer();

        $this->dispatch('swal', ['icon' => 'success', 'title' => 'Panggilan Ditutup']);
    }

    public function openMatchModal(string $bracket, int $roundIdx, int $matchIdx)
    {
        $match = $this->getMatchData($bracket, $roundIdx, $matchIdx);
        if (! $match || (! $match['athlete1'] && ! $match['athlete2'])) {
            return;
        }

        $this->activeMatch = ['bracket' => $bracket, 'round' => $roundIdx, 'match' => $matchIdx, 'data' => $match];
        $nodeKey = $bracket.'_'.$roundIdx.'_'.$matchIdx;
        $existing = RandoriMatchResult::whereIn('match_number_id', $this->matchNumberIds)->where('bracket_node', $nodeKey)->first();
        $this->loadScoringData($existing);
        $this->showModal = true;
        $this->dispatch('scroll-top');
    }

    private function loadScoringData(?RandoriMatchResult $existing)
    {
        $this->scoreRed = $existing?->score_red ?? 0;
        $this->scoreBlue = $existing?->score_blue ?? 0;
        $metadata = $existing?->metadata ? (is_array($existing->metadata) ? $existing->metadata : json_decode($existing->metadata, true)) : [];

        $this->scoringAka = $metadata['scoringAka'] ?? ['mujoken_kachi' => 0, 'ippon' => 0, 'waza_ari' => 0, 'hasil_batsu_5' => 0, 'hasil_batsu_10' => 0, 'yusei_kachi' => 0];
        $this->scoringShiro = $metadata['scoringShiro'] ?? ['mujoken_kachi' => 0, 'ippon' => 0, 'waza_ari' => 0, 'hasil_batsu_5' => 0, 'hasil_batsu_10' => 0, 'yusei_kachi' => 0];

        $sigs = $metadata['signatures'] ?? [];

        $firstDrawing = DrawingMatchNumber::whereIn('match_number_id', $this->matchNumberIds)->first();
        $officials = $firstDrawing?->metadata['officials'] ?? null;

        $arbitraseName = '';
        $firstWasitName = '';
        if ($firstDrawing) {
            $arbitraseReferee = ScheduleReferee::with('referee.user')
                ->where('rundown_id', $firstDrawing->rundown_id)
                ->where('session_time_id', $firstDrawing->session_time_id)
                ->whereNull('court_id')
                ->where('judge_index', 0)
                ->first();
            $arbitraseName = $arbitraseReferee?->referee?->user?->name ?? '';

            $wasitReferee = ScheduleReferee::with('referee.user')
                ->where('rundown_id', $firstDrawing->rundown_id)
                ->where('session_time_id', $firstDrawing->session_time_id)
                ->where('court_id', $firstDrawing->court_id)
                ->where('judge_index', '>', 0)
                ->orderBy('judge_index')
                ->first();
            $firstWasitName = $wasitReferee?->referee?->user?->name ?? '';
        }

        $this->sigArbitraseName = $sigs['arbitrase']['name'] ?? ($arbitraseName ?: '');
        $this->sigArbitraseData = $sigs['arbitrase']['signature'] ?? null;

        $this->sigKoordinatorName = $sigs['koordinator']['name'] ?? ($officials['koordinator_lapangan'] ?? '');
        $this->sigKoordinatorData = $sigs['koordinator']['signature'] ?? null;

        $this->sigWasitName = $sigs['wasit']['name'] ?? ($firstWasitName ?: '');
        $this->sigWasitData = $sigs['wasit']['signature'] ?? null;

        $this->sigPanitera = [];
        $savedPanitera = $sigs['panitera'] ?? [];
        $configuredPanitera = $officials['panitera'] ?? [];
        if (! is_array($configuredPanitera)) {
            $configuredPanitera = $configuredPanitera ? [$configuredPanitera] : [];
        }

        if (count($savedPanitera) > 0) {
            $this->sigPanitera = [];
            foreach ($savedPanitera as $p) {
                $this->sigPanitera[] = [
                    'id' => $p['id'] ?? uniqid(),
                    'name' => $p['name'] ?? '',
                    'signature' => $p['signature'] ?? null,
                ];
            }
        } else {
            foreach ($configuredPanitera as $pName) {
                $this->sigPanitera[] = [
                    'id' => uniqid(),
                    'name' => $pName,
                    'signature' => null,
                ];
            }
            if (empty($this->sigPanitera)) {
                $this->sigPanitera[] = [
                    'id' => uniqid(),
                    'name' => '',
                    'signature' => null,
                ];
            }
        }

        $this->sigManagerRedName = $sigs['manager_red']['name'] ?? '';
        $this->sigManagerRedData = $sigs['manager_red']['signature'] ?? null;

        $this->sigManagerWhiteName = $sigs['manager_white']['name'] ?? '';
        $this->sigManagerWhiteData = $sigs['manager_white']['signature'] ?? null;
    }

    public function openGrandFinalModal()
    {
        $gf = $this->drawingData['grand_final'] ?? null;
        if (! $gf || (! $gf['athlete1'] && ! $gf['athlete2'])) {
            return;
        }

        $this->activeMatch = ['bracket' => 'gf', 'round' => 0, 'match' => 0, 'data' => $gf];
        $existing = RandoriMatchResult::whereIn('match_number_id', $this->matchNumberIds)->where('bracket_node', 'gf_0_0')->first();
        $this->loadScoringData($existing);
        $this->showModal = true;
        $this->dispatch('scroll-top');
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
        $this->scoringAka = ['mujoken_kachi' => 0, 'ippon' => 0, 'waza_ari' => 0, 'hasil_batsu_5' => 0, 'hasil_batsu_10' => 0, 'yusei_kachi' => 0];
        $this->scoringShiro = ['mujoken_kachi' => 0, 'ippon' => 0, 'waza_ari' => 0, 'hasil_batsu_5' => 0, 'hasil_batsu_10' => 0, 'yusei_kachi' => 0];

        $this->sigArbitraseName = '';
        $this->sigArbitraseData = null;
        $this->sigKoordinatorName = '';
        $this->sigKoordinatorData = null;
        $this->sigWasitName = '';
        $this->sigWasitData = null;
        $this->sigPanitera = [];
        $this->sigManagerRedName = '';
        $this->sigManagerRedData = null;
        $this->sigManagerWhiteName = '';
        $this->sigManagerWhiteData = null;
    }

    public function addPanitera()
    {
        $this->sigPanitera[] = [
            'id' => uniqid(),
            'name' => '',
            'signature' => null,
        ];
    }

    public function removePanitera(int $index)
    {
        if (isset($this->sigPanitera[$index])) {
            unset($this->sigPanitera[$index]);
            $this->sigPanitera = array_values($this->sigPanitera);
        }
    }

    private function calculateTotals()
    {
        $weights = ['mujoken_kachi' => 15, 'ippon' => 10, 'waza_ari' => 5, 'hasil_batsu_5' => -5, 'hasil_batsu_10' => -10, 'yusei_kachi' => 5];
        $this->scoreRed = 0;
        foreach ($this->scoringAka as $t => $c) {
            $this->scoreRed += $c * ($weights[$t] ?? 0);
        }
        $this->scoreBlue = 0;
        foreach ($this->scoringShiro as $t => $c) {
            $this->scoreBlue += $c * ($weights[$t] ?? 0);
        }
    }

    public function submitScoring()
    {
        if (! $this->activeMatch) {
            return;
        }

        // Validate signatures
        if (empty($this->sigArbitraseName) || empty($this->sigArbitraseData)) {
            $this->dispatch('swal', ['icon' => 'warning', 'title' => 'Tanda Tangan Kurang', 'text' => 'Nama dan Tanda tangan Arbitrase wajib diisi.']);

            return;
        }
        if (empty($this->sigKoordinatorName) || empty($this->sigKoordinatorData)) {
            $this->dispatch('swal', ['icon' => 'warning', 'title' => 'Tanda Tangan Kurang', 'text' => 'Nama dan Tanda tangan Koordinator wajib diisi.']);

            return;
        }
        if (empty($this->sigWasitName) || empty($this->sigWasitData)) {
            $this->dispatch('swal', ['icon' => 'warning', 'title' => 'Tanda Tangan Kurang', 'text' => 'Nama dan Tanda tangan Wasit wajib diisi.']);

            return;
        }
        foreach ($this->sigPanitera as $idx => $p) {
            if (empty($p['name']) || empty($p['signature'])) {
                $this->dispatch('swal', ['icon' => 'warning', 'title' => 'Tanda Tangan Kurang', 'text' => 'Nama dan Tanda tangan Panitera ke-'.($idx + 1).' wajib diisi.']);

                return;
            }
        }
        if (empty($this->sigManagerRedName) || empty($this->sigManagerRedData)) {
            $this->dispatch('swal', ['icon' => 'warning', 'title' => 'Tanda Tangan Kurang', 'text' => 'Nama dan Tanda tangan Manajer Pita Merah wajib diisi.']);

            return;
        }
        if (empty($this->sigManagerWhiteName) || empty($this->sigManagerWhiteData)) {
            $this->dispatch('swal', ['icon' => 'warning', 'title' => 'Tanda Tangan Kurang', 'text' => 'Nama dan Tanda tangan Manajer Pita Putih wajib diisi.']);

            return;
        }

        if ($this->scoreRed > $this->scoreBlue) {
            $this->selectWinner($this->activeMatch['bracket'], $this->activeMatch['round'], $this->activeMatch['match'], 'athlete1');
        } elseif ($this->scoreBlue > $this->scoreRed) {
            $this->selectWinner($this->activeMatch['bracket'], $this->activeMatch['round'], $this->activeMatch['match'], 'athlete2');
        } else {
            $this->dispatch('swal', ['icon' => 'warning', 'title' => 'Skor Seri', 'text' => 'Poin sama (Hikiwake).']);
        }
    }

    public function selectWinner(string $bracket, int $roundIdx, int $matchIdx, string $winnerSlot)
    {
        $data = $this->matchNumber->drawing_data;
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
        $loserSlot = $winnerSlot === 'athlete1' ? 'athlete2' : 'athlete1';
        $loserData = $match[$loserSlot] ?? null;

        if (! $winnerData) {
            return;
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

        if ($match['winner_next'] ?? null) {
            if ($match['winner_next']['bracket'] === 'ranked') {
                $data['juara'][$match['winner_next']['rank']] = $winnerData;
            } else {
                $data = $this->placeAthlete($data, $match['winner_next'], $winnerData);
            }
        }

        if ($loserData && ($match['loser_next'] ?? null)) {
            if ($match['loser_next']['bracket'] === 'lb') {
                $data = $this->placeAthlete($data, $match['loser_next'], $loserData);
            } elseif ($match['loser_next']['bracket'] === 'ranked') {
                $data['juara'][$match['loser_next']['rank']] = $loserData;
            }
        }

        if ($bracket === 'gf') {
            $data['juara'][1] = $winnerData;
            $data['juara'][2] = $loserData;
        }

        $data = $this->propagateBracketByes($data);

        $matchId = $winnerData['match_number_id'] ?? $this->matchNumber->id;
        RandoriMatchResult::updateOrCreate(
            ['match_number_id' => $matchId, 'bracket_node' => $bracket.'_'.$roundIdx.'_'.$matchIdx],
            [
                'bracket_node_index' => $roundIdx.'_'.$matchIdx,
                'bracket_section' => $bracket,
                'winner_color' => $winnerSlot,
                'score_red' => $this->scoreRed,
                'score_blue' => $this->scoreBlue,
                'metadata' => json_encode([
                    'scoringAka' => $this->scoringAka,
                    'scoringShiro' => $this->scoringShiro,
                    'signatures' => [
                        'arbitrase' => [
                            'name' => $this->sigArbitraseName,
                            'signature' => $this->sigArbitraseData,
                        ],
                        'koordinator' => [
                            'name' => $this->sigKoordinatorName,
                            'signature' => $this->sigKoordinatorData,
                        ],
                        'wasit' => [
                            'name' => $this->sigWasitName,
                            'signature' => $this->sigWasitData,
                        ],
                        'panitera' => $this->sigPanitera,
                        'manager_red' => [
                            'name' => $this->sigManagerRedName,
                            'signature' => $this->sigManagerRedData,
                        ],
                        'manager_white' => [
                            'name' => $this->sigManagerWhiteName,
                            'signature' => $this->sigManagerWhiteData,
                        ],
                    ],
                ]),
            ]
        );

        MatchNumber::whereIn('id', $this->matchNumberIds)->update(['drawing_data' => $data, 'active_bracket_node' => null]);
        $this->drawingData = $data;
        $this->activeMatch = null;
        $this->resetDetailedScoring();
        $this->stopTimer();
        $this->showModal = false;
        $this->dispatch('swal', ['icon' => 'success', 'title' => 'Pemenang Dicatat!']);
    }

    private function placeAthlete(array $data, array $next, array $athleteData): array
    {
        $b = $next['bracket'];
        $slot = $next['slot'] ?? 'athlete1';
        if ($b === 'ub') {
            $data['upper_bracket']['rounds'][$next['round']][$next['match']][$slot] = $athleteData;
        } elseif ($b === 'lb') {
            $data['lower_bracket']['rounds'][$next['round']][$next['match']][$slot] = $athleteData;
        } elseif ($b === 'gf') {
            $data['grand_final'][$slot] = $athleteData;
        }

        // Update DrawingMatchNumber untuk Jadwal
        $round = $next['round'] ?? 0;
        $matchVal = $next['match'] ?? 0;
        $nodeKey = $b.'_'.$round.'_'.$matchVal;
        $side = $slot === 'athlete1' ? 'RED' : 'BLUE';

        $drawings = DrawingMatchNumber::whereIn('match_number_id', $this->matchNumberIds)->get();

        foreach ($drawings as $d) {
            $meta = $d->metadata;
            if (is_string($meta)) {
                $meta = json_decode($meta, true);
            }

            if (($meta['node_key'] ?? null) === $nodeKey && ($meta['side'] ?? null) === $side) {
                $d->registration_id = $athleteData['registration_id'] ?? null;
                $meta['athlete_id'] = $athleteData['id'] ?? null;
                $meta['athlete_name'] = $athleteData['name'] ?? 'TBD';
                $meta['contingent'] = $athleteData['contingent'] ?? 'TBD';
                $d->metadata = $meta;
                $d->save();
            }
        }

        return $data;
    }

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
        $changed = true;
        $maxIterations = 10; // Prevent infinite loops
        $iteration = 0;

        while ($changed && $iteration < $maxIterations) {
            $changed = false;
            $iteration++;

            // Process Upper Bracket
            if (isset($data['upper_bracket']['rounds'])) {
                foreach ($data['upper_bracket']['rounds'] as $rIdx => $round) {
                    foreach ($round as $mIdx => $match) {
                        // 1. Check for BYE winner
                        if (($match['winner'] ?? null) === null) {
                            $a1Bye = ($match['athlete1']['id'] ?? '') === 'BYE';
                            $a2Bye = ($match['athlete2']['id'] ?? '') === 'BYE';

                            if ($a1Bye || $a2Bye) {
                                $winnerSlot = $a1Bye ? 'athlete2' : 'athlete1';
                                $data['upper_bracket']['rounds'][$rIdx][$mIdx]['winner'] = $winnerSlot;
                                $data['upper_bracket']['rounds'][$rIdx][$mIdx]['winner_data'] = $match[$winnerSlot] ?? null;
                                $data['upper_bracket']['rounds'][$rIdx][$mIdx]['is_bye'] = true;
                                $changed = true;
                                // Refresh match variable for propagation check below
                                $match = $data['upper_bracket']['rounds'][$rIdx][$mIdx];
                            }
                        }

                        // 2. Propagate winner if set
                        if (($match['winner'] ?? null) !== null && ($match['winner_next'] ?? null)) {
                            $winnerData = $match['winner_data'] ?? $match[$match['winner']] ?? null;
                            if ($winnerData) {
                                $next = $match['winner_next'];
                                $target = $this->getAthleteInSlot($data, $next);
                                if (! $target || $target['id'] !== $winnerData['id']) {
                                    $data = $this->placeAthlete($data, $next, $winnerData);
                                    $changed = true;
                                }
                            }
                        }
                    }
                }
            }

            // Process Lower Bracket
            if (isset($data['lower_bracket']['rounds'])) {
                foreach ($data['lower_bracket']['rounds'] as $rIdx => $round) {
                    foreach ($round as $mIdx => $match) {
                        // 1. Check for BYE winner
                        if (($match['winner'] ?? null) === null) {
                            $a1Bye = ($match['athlete1']['id'] ?? '') === 'BYE';
                            $a2Bye = ($match['athlete2']['id'] ?? '') === 'BYE';

                            if ($a1Bye || $a2Bye) {
                                $winnerSlot = $a1Bye ? 'athlete2' : 'athlete1';
                                $data['lower_bracket']['rounds'][$rIdx][$mIdx]['winner'] = $winnerSlot;
                                $data['lower_bracket']['rounds'][$rIdx][$mIdx]['winner_data'] = $match[$winnerSlot] ?? null;
                                $data['lower_bracket']['rounds'][$rIdx][$mIdx]['is_bye'] = true;
                                $changed = true;
                                $match = $data['lower_bracket']['rounds'][$rIdx][$mIdx];
                            }
                        }

                        // 2. Propagate winner if set
                        if (($match['winner'] ?? null) !== null && ($match['winner_next'] ?? null)) {
                            $winnerData = $match['winner_data'] ?? $match[$match['winner']] ?? null;
                            if ($winnerData) {
                                $next = $match['winner_next'];
                                $target = $this->getAthleteInSlot($data, $next);
                                if (! $target || $target['id'] !== $winnerData['id']) {
                                    $data = $this->placeAthlete($data, $next, $winnerData);
                                    $changed = true;
                                }
                            }
                        }
                    }
                }
            }
        }

        return $data;
    }

    private function getAthleteInSlot(array $data, array $next): ?array
    {
        $b = $next['bracket'];
        $slot = $next['slot'] ?? 'athlete1';
        if ($b === 'ub') {
            return $data['upper_bracket']['rounds'][$next['round']][$next['match']][$slot] ?? null;
        }
        if ($b === 'lb') {
            return $data['lower_bracket']['rounds'][$next['round']][$next['match']][$slot] ?? null;
        }
        if ($b === 'gf') {
            return $data['grand_final'][$slot] ?? null;
        }

        return null;
    }

    public function getCourtId()
    {
        $d = DrawingMatchNumber::whereIn('match_number_id', $this->matchNumberIds)->first();

        return $d?->court_id;
    }

    public function confirmChampion()
    {
        $data = $this->drawingData;
        $juara = $data['juara'] ?? [];

        if (empty($juara)) {
            $gf = $data['grand_final'] ?? null;
            if ($gf && ($gf['winner'] ?? null)) {
                $juara[1] = $gf['winner_data'];
                $juara[2] = ($gf['winner'] === 'athlete1') ? $gf['athlete2'] : $gf['athlete1'];
            }
        }

        if (empty($juara)) {
            $this->dispatch('swal', [
                'icon' => 'warning',
                'title' => 'Belum Ada Juara',
                'text' => 'Tentukan pemenang Grand Final terlebih dahulu.',
            ]);

            return;
        }

        // Save it back to drawing_data
        $data['juara'] = $juara;
        $this->matchNumber->update(['drawing_data' => $data]);
        $this->drawingData = $data;

        // Delete old results for this match to avoid unique constraint violations on (match_id, rank)
        TournamentResult::whereIn('match_number_id', $this->matchNumberIds)->delete();

        foreach ($juara as $rank => $athlete) {
            if ($rank > 2) {
                continue; // Only Juara 1 & 2 for Randori
            }

            if (! $athlete || ! isset($athlete['id']) || $athlete['id'] === 'BYE') {
                continue;
            }

            TournamentResult::updateOrCreate(
                [
                    'match_number_id' => $athlete['match_number_id'] ?? $this->matchNumber->id,
                    'registration_id' => $athlete['registration_id'] ?? null,
                ],
                [
                    'draft_type' => $this->matchNumber->draft_type,
                    'rank' => (int) $rank,
                    'athlete_names' => $athlete['name'] ?? '',
                    'contingent_name' => $athlete['contingent'] ?? '',
                    'category_id' => $this->matchNumber->age_group_id,
                    'generated_by' => Auth::user()?->name ?? 'Admin',
                    'confirmed_at' => now(),
                    'accumulated_score' => 0,
                    'penyisihan_score' => 0,
                    'final_score' => 0,
                ]
            );
        }

        $this->dispatch('swal', [
            'icon' => 'success',
            'title' => 'Juara Disimpan',
            'text' => 'Daftar juara telah berhasil dicatat ke sistem.',
        ]);
    }

    public function startTimer()
    {
        $id = $this->getCourtId();
        if ($id) {
            $s = Cache::get("court_{$id}_timer", ['status' => 'stopped', 'elapsed_ms' => 0]);
            $s['status'] = 'running';
            $s['started_at_ms'] = round(microtime(true) * 1000);
            Cache::put("court_{$id}_timer", $s);
            $this->dispatch('timer-updated');
        }
    }

    public function pauseTimer()
    {
        $id = $this->getCourtId();
        if ($id) {
            $s = Cache::get("court_{$id}_timer");
            if ($s && $s['status'] === 'running') {
                $s['elapsed_ms'] += round(microtime(true) * 1000) - $s['started_at_ms'];
                $s['status'] = 'paused';
                $s['started_at_ms'] = null;
                Cache::put("court_{$id}_timer", $s);
                $this->dispatch('timer-updated');
            }
        }
    }

    public function stopTimer()
    {
        $id = $this->getCourtId();
        if ($id) {
            Cache::put("court_{$id}_timer", ['status' => 'stopped', 'elapsed_ms' => 0, 'started_at_ms' => null]);
            $this->dispatch('timer-updated');
        }
    }

    public function getTimerState()
    {
        $id = $this->getCourtId();
        if (! $id) {
            return ['status' => 'stopped', 'elapsed_ms' => 0, 'started_at_ms' => null, 'server_time_ms' => floor(microtime(true) * 1000)];
        }
        $state = Cache::get("court_{$id}_timer", ['status' => 'stopped', 'elapsed_ms' => 0, 'started_at_ms' => null]);
        $state['server_time_ms'] = floor(microtime(true) * 1000);

        return $state;
    }

    public function finishMatch()
    {
        //
    }

    private function dispatchAnnouncer(array $match, string $info)
    {
        $a1 = $match['athlete1']['name'] ?? 'Menunggu';
        $c1 = $match['athlete1']['contingent'] ?? '';
        $a2 = $match['athlete2']['name'] ?? 'Menunggu';
        $c2 = $match['athlete2']['contingent'] ?? '';
        $txt = "Pertandingan selanjutnya: {$info}. Di sudut Merah, {$a1} dari {$c1}. Di sudut Putih, {$a2} dari {$c2}. Mohon segera bersiap.";
        $this->dispatch('play-announcer', ['text' => $txt]);
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

    public function render()
    {
        if ($this->merge) {
            $mergedNames = MatchNumber::whereIn('id', $this->matchNumberIds)->pluck('name')->join(', ');
            $this->displayName = ($this->merge->name ?: 'Merged Group').' ('.$mergedNames.')';
        } else {
            $this->displayName = $this->matchNumber->name;
        }

        $firstDrawing = DrawingMatchNumber::whereIn('match_number_id', $this->matchNumberIds)->first();
        $officials = $firstDrawing?->metadata['officials'] ?? null;

        $assignedArbitrase = null;
        $assignedReferees = collect();
        $assignedKoordinators = collect();
        $assignedPaniteras = collect();

        if ($firstDrawing) {
            $assignedArbitrase = ScheduleReferee::with('referee.user')
                ->where('rundown_id', $firstDrawing->rundown_id)
                ->where('session_time_id', $firstDrawing->session_time_id)
                ->whereNull('court_id')
                ->where('judge_index', 0)
                ->first();

            $assignedReferees = ScheduleReferee::with('referee.user')
                ->where('rundown_id', $firstDrawing->rundown_id)
                ->where('session_time_id', $firstDrawing->session_time_id)
                ->where('court_id', $firstDrawing->court_id)
                ->where('judge_index', '>', 0)
                ->orderBy('judge_index')
                ->get();

            $assignedKoordinators = SchedulePanitera::with('user')
                ->where('rundown_id', $firstDrawing->rundown_id)
                ->where('session_time_id', $firstDrawing->session_time_id)
                ->where('court_id', $firstDrawing->court_id)
                ->where('role_type', 'koordinator')
                ->orderBy('slot_index')
                ->get();

            $assignedPaniteras = SchedulePanitera::with('user')
                ->where('rundown_id', $firstDrawing->rundown_id)
                ->where('session_time_id', $firstDrawing->session_time_id)
                ->where('court_id', $firstDrawing->court_id)
                ->where('role_type', 'panitera')
                ->orderBy('slot_index')
                ->get();
        }

        // Fetch saved tournament results (champions)
        $savedResults = TournamentResult::whereIn('match_number_id', $this->matchNumberIds)
            ->orderBy('rank')
            ->get();

        $juaraMap = [];
        foreach ($savedResults as $res) {
            $juaraMap[$res->rank] = [
                'name' => $res->athlete_names,
                'contingent' => $res->contingent_name,
                'registration_id' => $res->registration_id,
            ];
        }

        // If the database has no saved results, fall back to what's in drawing_data
        if (empty($juaraMap)) {
            $juaraMap = $this->drawingData['juara'] ?? [];
            if (empty($juaraMap)) {
                $gf = $this->drawingData['grand_final'] ?? null;
                if ($gf && ($gf['winner'] ?? null)) {
                    $juaraMap[1] = $gf['winner_data'];
                    $juaraMap[2] = ($gf['winner'] === 'athlete1') ? $gf['athlete2'] : $gf['athlete1'];
                }
            }
        }

        return view('livewire.admin.new-scoring-randori-index', [
            'officials' => $officials,
            'assignedArbitrase' => $assignedArbitrase,
            'assignedReferees' => $assignedReferees,
            'assignedKoordinators' => $assignedKoordinators,
            'assignedPaniteras' => $assignedPaniteras,
            'juara' => $juaraMap,
        ]);
    }
}

<?php

namespace App\Livewire\Admin;

use App\Models\Court\Court;
use App\Models\DrawingMatchNumber;
use App\Models\EmbuChampion;
use App\Models\EmbuScore;
use App\Models\Group\AgeGroup;
use App\Models\MatchNumber\MatchNumber;
use App\Models\Pool\Pool;
use App\Models\Registration;
use App\Models\Rundown\Rundown;
use App\Models\SessionTime;
use App\Models\TournamentResult;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.premium')]
class NewEmbuResultIndex extends Component
{
    // ─── Match selection ──────────────────────────────────────
    public ?int $selectedMatchId = null;

    public ?int $selectedAgeGroupId = null;

    // ─── Generate Final modal state ───────────────────────────
    public bool $showGenerateFinalModal = false;

    public int $finalQuota = 8;

    public ?int $finalCourtId = null;

    public ?int $finalPoolId = null;

    public ?int $finalSessionTimeId = null;

    public ?int $finalRundownId = null;

    public ?string $finalScheduleDate = null;

    // ─── Generate Tiebreak modal ──────────────────────────────
    public bool $showTiebreakModal = false;

    public string $tiebreakRound = 'Penyisihan';

    public ?int $tiebreakCourtId = null;

    public ?int $tiebreakSessionTimeId = null;

    public ?int $tiebreakRundownId = null;

    public ?string $tiebreakScheduleDate = null;

    public array $tiebreakRegistrationIds = [];

    // ─── Confirm champion modal ───────────────────────────────
    public bool $showChampionModal = false;

    private function getMatchNumberIds(): array
    {
        if (! $this->selectedMatchId) {
            return [];
        }

        $mergeDetails = DB::table('match_number_merge_details')
            ->where('match_number_id', $this->selectedMatchId)
            ->first();

        if ($mergeDetails) {
            return DB::table('match_number_merge_details')
                ->where('match_number_merge_id', $mergeDetails->match_number_merge_id)
                ->pluck('match_number_id')
                ->toArray();
        }

        return [$this->selectedMatchId];
    }

    // ─── LIFECYCLE ────────────────────────────────────────────

    public function mount(): void
    {
        $first = MatchNumber::where('draft_type', 'embu')
            ->whereHas('athletes')
            ->whereHas('drawings')
            ->first();

        if ($first) {
            $this->selectedMatchId = $first->id;
        }
    }

    public function updatedSelectedAgeGroupId(): void
    {
        $this->selectedMatchId = null;
        $this->reset(['showGenerateFinalModal', 'showTiebreakModal', 'showChampionModal', 'tiebreakRegistrationIds']);
    }

    public function updatedSelectedMatchId(): void
    {
        $this->reset([
            'showGenerateFinalModal', 'showTiebreakModal', 'showChampionModal',
            'tiebreakRegistrationIds',
        ]);
    }

    // ─── PENYISIHAN ───────────────────────────────────────────

    /** Return grouped and sorted Penyisihan registrations with scores & rank. */
    private function getPenyisihanRanking(): Collection
    {
        if (! $this->selectedMatchId) {
            return collect();
        }

        $matchIds = $this->getMatchNumberIds();

        $drawings = DrawingMatchNumber::with('pool')
            ->whereIn('match_number_id', $matchIds)
            ->where('round', 'Penyisihan')
            ->get();

        $drawingRegIds = $drawings->pluck('registration_id')->unique()->filter()->toArray();
        $registrations = Registration::with(['contingent', 'athletes'])->whereIn('id', $drawingRegIds)->get()->keyBy('id');
        $scores = EmbuScore::whereIn('match_number_id', $matchIds)
            ->where('round_label', 'Penyisihan')
            ->get();

        $participants = $drawings->map(function ($drawing) use ($scores, $registrations) {
            $regId = $drawing->registration_id;
            $reg = $registrations->get($regId);
            if (! $reg) {
                return null;
            }

            $specificMatchId = $drawing->match_number_id;

            // Correctly filter athletes for this specific team/drawing
            $athleteIds = $drawing->metadata['athlete_ids'] ?? [];
            $athletes = collect();
            if (! empty($athleteIds)) {
                $athletes = $reg->athletes->whereIn('id', $athleteIds)->values();
            } else {
                $athletes = $reg->athletes;
            }

            $score = $scores->where('registration_id', $regId)
                ->where('match_number_id', $specificMatchId)
                ->where('drawing_id', $drawing->id)
                ->where('tiebreak_round', 0)
                ->first();

            if (! $score) {
                $score = $scores->where('registration_id', $regId)
                    ->where('match_number_id', $specificMatchId)
                    ->whereNull('drawing_id')
                    ->where('tiebreak_round', 0)
                    ->first();
            }

            $tiebreakScore = $scores->where('registration_id', $regId)
                ->where('match_number_id', $specificMatchId)
                ->where('drawing_id', $drawing->id)
                ->where('tiebreak_round', '>', 0)
                ->sortByDesc('tiebreak_round')
                ->first();

            if (! $tiebreakScore) {
                $tiebreakScore = $scores->where('registration_id', $regId)
                    ->where('match_number_id', $specificMatchId)
                    ->whereNull('drawing_id')
                    ->where('tiebreak_round', '>', 0)
                    ->sortByDesc('tiebreak_round')
                    ->first();
            }
            $activeScoreObj = $tiebreakScore ?? $score;
            $calculatedTotal = 0;
            if ($activeScoreObj) {
                if ($activeScoreObj->nilai_akhir > 0) {
                    $calculatedTotal = $activeScoreObj->nilai_akhir;
                } else {
                    $judges = [(float) $activeScoreObj->judge_1, (float) $activeScoreObj->judge_2, (float) $activeScoreObj->judge_3, (float) $activeScoreObj->judge_4, (float) $activeScoreObj->judge_5];
                    $scoredCount = count(array_filter($judges, fn ($v) => $v > 0));
                    if ($scoredCount === 5) {
                        sort($judges);
                        $calculatedTotal = $judges[1] + $judges[2] + $judges[3];
                    } else {
                        $calculatedTotal = array_sum($judges);
                    }
                    $calculatedTotal = max(0, $calculatedTotal - $activeScoreObj->denda);
                }
            }

            return [
                'id' => $regId,
                'drawing_id' => $drawing->id,
                'match_number_id' => $specificMatchId,
                'athlete_ids' => $athleteIds,
                'pool_id' => $drawing->pool_id ?? 0,
                'pool_name' => $drawing->pool?->name ?? 'No Pool',
                'athletes' => $athletes,
                'contingent' => $reg->contingent,
                'score' => $score,
                'tiebreak_score' => $tiebreakScore,
                'effective_score' => $activeScoreObj,
                'calculated_score' => $calculatedTotal,
            ];
        })->filter()->values();

        // Sort: 1. Calculated Score (DESC), 2. Wasit Utama / judge_1 (DESC)
        $sorted = $participants->sort(function ($a, $b) {
            $naA = (float) ($a['calculated_score'] ?? -1);
            $naB = (float) ($b['calculated_score'] ?? -1);
            if ($naA !== $naB) {
                return $naB <=> $naA;
            }

            $j1A = (float) ($a['effective_score']?->judge_1 ?? -1);
            $j1B = (float) ($b['effective_score']?->judge_1 ?? -1);
            if ($j1A !== $j1B) {
                return $j1B <=> $j1A;
            }

            return 0;
        })->values();

        // Group by Pool
        return $sorted->groupBy('pool_id');
    }

    /** Return sorted Final registrations with scores. */
    private function getFinalRanking(): Collection
    {
        if (! $this->selectedMatchId) {
            return collect();
        }

        $matchIds = $this->getMatchNumberIds();
        $allScores = EmbuScore::whereIn('match_number_id', $matchIds)->get();

        // Use Drawings as primary source
        $finalDrawings = DrawingMatchNumber::whereIn('match_number_id', $matchIds)
            ->where('round', 'Final')
            ->get();

        $penyisihanDrawings = DrawingMatchNumber::whereIn('match_number_id', $matchIds)
            ->where('round', 'Penyisihan')
            ->get();

        $regIds = $finalDrawings->pluck('registration_id')->unique()->filter()->toArray();
        $registrations = Registration::with(['contingent', 'athletes'])->whereIn('id', $regIds)->get()->keyBy('id');

        return $finalDrawings->map(function ($drawing) use ($allScores, $registrations, $penyisihanDrawings) {
            $regId = $drawing->registration_id;
            $reg = $registrations->get($regId);
            $specificMatchId = $drawing->match_number_id;

            $athleteIds = $drawing->metadata['athlete_ids'] ?? [];

            // Find the matching Penyisihan drawing by athlete IDs
            $matchingPenyisihanDrawing = $penyisihanDrawings->where('registration_id', $regId)
                ->first(function ($d) use ($athleteIds) {
                    $dIds = $d->metadata['athlete_ids'] ?? [];

                    return empty(array_diff($athleteIds, $dIds)) && empty(array_diff($dIds, $athleteIds));
                });

            $penyisihanScore = null;
            if ($matchingPenyisihanDrawing) {
                $penyisihanScore = $allScores->where('registration_id', $regId)
                    ->where('match_number_id', $specificMatchId)
                    ->where('drawing_id', $matchingPenyisihanDrawing->id)
                    ->where('round_label', 'Penyisihan')
                    ->where('tiebreak_round', 0)
                    ->first();
            }

            if (! $penyisihanScore) {
                $penyisihanScore = $allScores->where('registration_id', $regId)
                    ->where('match_number_id', $specificMatchId)
                    ->where('round_label', 'Penyisihan')
                    ->where('tiebreak_round', 0)
                    ->first();
            }

            $penyisihanTbScore = $allScores->where('registration_id', $regId)
                ->where('match_number_id', $specificMatchId)
                ->where('drawing_id', $drawing->id)
                ->where('round_label', 'Penyisihan')
                ->where('tiebreak_round', '>', 0)
                ->sortByDesc('tiebreak_round')
                ->first();

            if (! $penyisihanTbScore) {
                $penyisihanTbScore = $allScores->where('registration_id', $regId)
                    ->where('match_number_id', $specificMatchId)
                    ->whereNull('drawing_id')
                    ->where('round_label', 'Penyisihan')
                    ->where('tiebreak_round', '>', 0)
                    ->sortByDesc('tiebreak_round')
                    ->first();
            }

            $finalScore = $allScores->where('registration_id', $regId)
                ->where('match_number_id', $specificMatchId)
                ->where('drawing_id', $drawing->id)
                ->where('round_label', 'Final')
                ->where('tiebreak_round', 0)
                ->first();

            if (! $finalScore) {
                $finalScore = $allScores->where('registration_id', $regId)
                    ->where('match_number_id', $specificMatchId)
                    ->whereNull('drawing_id')
                    ->where('round_label', 'Final')
                    ->where('tiebreak_round', 0)
                    ->first();
            }

            $finalTbScore = $allScores->where('registration_id', $regId)
                ->where('match_number_id', $specificMatchId)
                ->where('drawing_id', $drawing->id)
                ->where('round_label', 'Final')
                ->where('tiebreak_round', '>', 0)
                ->sortByDesc('tiebreak_round')
                ->first();

            $activePenyisihanObj = $penyisihanTbScore ?? $penyisihanScore;
            $calculatedPenyisihan = 0;
            if ($activePenyisihanObj) {
                if ($activePenyisihanObj->nilai_akhir > 0) {
                    $calculatedPenyisihan = $activePenyisihanObj->nilai_akhir;
                } else {
                    $judges = [(float) $activePenyisihanObj->judge_1, (float) $activePenyisihanObj->judge_2, (float) $activePenyisihanObj->judge_3, (float) $activePenyisihanObj->judge_4, (float) $activePenyisihanObj->judge_5];
                    $scoredCount = count(array_filter($judges, fn ($v) => $v > 0));
                    if ($scoredCount === 5) {
                        sort($judges);
                        $calculatedPenyisihan = $judges[1] + $judges[2] + $judges[3];
                    } else {
                        $calculatedPenyisihan = array_sum($judges);
                    }
                    $calculatedPenyisihan = max(0, $calculatedPenyisihan - $activePenyisihanObj->denda);
                }
            }

            $activeFinalObj = $finalTbScore ?? $finalScore;
            $calculatedFinal = 0;
            if ($activeFinalObj) {
                if ($activeFinalObj->nilai_akhir > 0) {
                    $calculatedFinal = $activeFinalObj->nilai_akhir;
                } else {
                    $judges = [(float) $activeFinalObj->judge_1, (float) $activeFinalObj->judge_2, (float) $activeFinalObj->judge_3, (float) $activeFinalObj->judge_4, (float) $activeFinalObj->judge_5];
                    $scoredCount = count(array_filter($judges, fn ($v) => $v > 0));
                    if ($scoredCount === 5) {
                        sort($judges);
                        $calculatedFinal = $judges[1] + $judges[2] + $judges[3];
                    } else {
                        $calculatedFinal = array_sum($judges);
                    }
                    $calculatedFinal = max(0, $calculatedFinal - $activeFinalObj->denda);
                }
            }

            $effectivePenyisihan = $calculatedPenyisihan;
            $effectiveFinal = $calculatedFinal;

            $accumulated = (float) $effectivePenyisihan + (float) $effectiveFinal;

            // Correctly filter athletes for this specific team/drawing
            $athleteIds = $drawing->metadata['athlete_ids'] ?? [];
            $athletes = collect();
            if (! empty($athleteIds)) {
                $athletes = $reg?->athletes->whereIn('id', $athleteIds)->values() ?? collect();
            } elseif ($reg) {
                $athletes = $reg->athletes;
            }

            return [
                'id' => $regId,
                'drawing_id' => $drawing->id,
                'match_number_id' => $specificMatchId,
                'athletes' => $athletes,
                'contingent' => $reg?->contingent,
                'penyisihan_score' => $penyisihanTbScore ?? $penyisihanScore,
                'final_score' => $finalTbScore ?? $finalScore,
                'accumulated' => $accumulated,
                'final_scored' => ($finalTbScore ?? $finalScore) !== null,
            ];
        })
            ->sort(function ($a, $b) {
                // 1. Total Accumulated
                $accA = (float) $a['accumulated'];
                $accB = (float) $b['accumulated'];
                if ($accA !== $accB) {
                    return $accB <=> $accA;
                }

                // 2. Final Round Judge 1 (Wasit Utama)
                $j1A = (float) ($a['final_score']?->judge_1 ?? -1);
                $j1B = (float) ($b['final_score']?->judge_1 ?? -1);
                if ($j1A !== $j1B) {
                    return $j1B <=> $j1A;
                }

                // 3. Penyisihan Judge 1 (Wasit Utama)
                $p1A = (float) ($a['penyisihan_score']?->judge_1 ?? -1);
                $p1B = (float) ($b['penyisihan_score']?->judge_1 ?? -1);

                return $p1B <=> $p1A;
            })
            ->values();
    }

    // ─── DETECT TIES IN FINAL ─────────────────────────────────

    public function detectFinalTies(): array
    {
        $rankings = $this->getFinalRanking();
        // Ignore those without scores to prevent false positive ties
        $scored = $rankings->filter(fn ($r) => $r['accumulated'] !== null && $r['accumulated'] > 0);

        if ($scored->count() < 2) {
            return [];
        }

        // Find top N by ties
        $grouped = $scored->groupBy('accumulated');

        // Look for any group with 2+ registrations at top positions
        $tiedIds = [];
        foreach ($grouped as $val => $group) {
            if ($group->count() > 1) {
                $tiedIds = array_merge($tiedIds, $group->pluck('id')->toArray());
            }
        }

        return $tiedIds;
    }

    // ─── THB RULES ────────────────────────────────────────────

    private function getPoolQualifiersLimit(int $poolCount): int
    {
        if ($poolCount === 1) {
            return 999;
        } // All participants go to final
        if ($poolCount === 2) {
            return 4;
        }   // 8 finalists
        if ($poolCount === 3) {
            return 3;
        }   // 9 finalists
        if ($poolCount >= 4) {
            return 2;
        }    // 8 finalists

        return 0;
    }

    // ─── DETECT TIES IN PENYISIHAN AT BOUNDARY ─────────────────

    private function detectPenyisihanBoundaryTies(): array
    {
        $poolRankings = $this->getPenyisihanRanking();
        $poolCount = $poolRankings->count();
        $quota = $this->getPoolQualifiersLimit($poolCount);

        $tiedIds = [];

        foreach ($poolRankings as $poolId => $ranking) {
            $ranked = $ranking->values();

            if ($ranked->count() <= $quota) {
                continue;
            }

            $boundaryIdx = $quota - 1;

            $boundaryNa = (float) ($ranked->get($boundaryIdx)['effective_score']?->nilai_akhir ?? -1);
            $boundaryJ1 = (float) ($ranked->get($boundaryIdx)['effective_score']?->judge_1 ?? -1);

            $nextNa = (float) ($ranked->get($boundaryIdx + 1)['effective_score']?->nilai_akhir ?? -1);
            $nextJ1 = (float) ($ranked->get($boundaryIdx + 1)['effective_score']?->judge_1 ?? -1);

            if ($boundaryNa >= 0 && $boundaryNa === $nextNa && $boundaryJ1 === $nextJ1) {
                // Find all who are tied with boundary
                $tied = $ranked->filter(function ($r) use ($boundaryNa, $boundaryJ1) {
                    $na = (float) ($r['effective_score']?->nilai_akhir ?? -1);
                    $j1 = (float) ($r['effective_score']?->judge_1 ?? -1);

                    return $na === $boundaryNa && $j1 === $boundaryJ1;
                });
                $tiedIds = array_merge($tiedIds, $tied->pluck('id')->toArray());
            }
        }

        return $tiedIds;
    }

    // ─── GENERATE FINAL ──────────────────────────────────────

    public function openGenerateFinalModal(): void
    {
        $matchIds = $this->getMatchNumberIds();
        $existing = DrawingMatchNumber::whereIn('match_number_id', $matchIds)
            ->where('round', 'Final')
            ->first();

        if ($existing) {
            $this->finalCourtId = $existing->court_id;
            $this->finalPoolId = $existing->pool_id;
            $this->finalSessionTimeId = $existing->session_time_id;
            $this->finalRundownId = $existing->rundown_id;
            $this->finalScheduleDate = $existing->schedule_date;
        } else {
            $existingPenyisihan = DrawingMatchNumber::whereIn('match_number_id', $matchIds)
                ->where('round', 'Penyisihan')
                ->first();

            $this->finalCourtId = $existingPenyisihan?->court_id;
            $this->finalPoolId = null;
            $this->finalSessionTimeId = $existingPenyisihan?->session_time_id;
            $this->finalRundownId = $existingPenyisihan?->rundown_id;
            $this->finalScheduleDate = $existingPenyisihan?->schedule_date;
        }
        $this->showGenerateFinalModal = true;
    }

    public function generateFinal(): void
    {
        $tiedIds = $this->detectPenyisihanBoundaryTies();
        if (! empty($tiedIds)) {
            $this->dispatch('swal', [
                'icon' => 'warning',
                'title' => 'Ada Nilai Seri di Batas Kuota!',
                'text' => count($tiedIds).' peserta memiliki nilai sama di batas lolos (Mengingat wasit utama juga sama). Buat jadwal tanding ulang terlebih dahulu.',
            ]);

            return;
        }

        $poolRankings = $this->getPenyisihanRanking();
        $poolCount = $poolRankings->count();
        $quota = $this->getPoolQualifiersLimit($poolCount);

        $qualifiers = collect();
        foreach ($poolRankings as $poolId => $ranking) {
            $poolQualifiers = $ranking->take($quota);
            $qualifiers = $qualifiers->merge($poolQualifiers);
        }

        if ($qualifiers->isEmpty()) {
            $this->dispatch('swal', [
                'icon' => 'warning',
                'title' => 'Gagal Generate',
                'text' => 'Tidak ada peserta di kelas ini.',
            ]);

            return;
        }

        $matchIds = $this->getMatchNumberIds();

        // Resolve court/session/rundown/pool from form or from any existing final drawing
        $existingFinal = DrawingMatchNumber::whereIn('match_number_id', $matchIds)
            ->where('round', 'Final')
            ->first();

        $courtId = $this->finalCourtId ?? $existingFinal?->court_id;
        $poolId = $this->finalPoolId ?? $existingFinal?->pool_id;
        $sessionTimeId = $this->finalSessionTimeId ?? $existingFinal?->session_time_id;
        $rundownId = $this->finalRundownId ?? $existingFinal?->rundown_id;
        $scheduleDate = $this->finalScheduleDate ?? $existingFinal?->schedule_date;

        // Delete ALL existing Final drawings for this match to avoid duplicates
        DrawingMatchNumber::whereIn('match_number_id', $matchIds)
            ->where('round', 'Final')
            ->delete();

        // Create fresh Final drawings for each qualifier
        foreach ($qualifiers->values() as $seq => $reg) {
            $meta = [
                'contingent' => $reg['contingent']?->name ?? 'Unknown',
                'athlete_name' => $reg['athletes']->pluck('name')->implode(', '),
                'athlete_ids' => $reg['athlete_ids'] ?? [],
            ];

            DrawingMatchNumber::create([
                'match_number_id' => $reg['match_number_id'],
                'registration_id' => $reg['id'],
                'round' => 'Final',
                'draft_type' => 'embu',
                'sequence_number' => $seq + 1,
                'court_id' => $courtId,
                'pool_id' => $poolId,
                'session_time_id' => $sessionTimeId,
                'rundown_id' => $rundownId,
                'schedule_date' => $scheduleDate,
                'metadata' => $meta,
            ]);
        }

        $this->showGenerateFinalModal = false;

        $this->dispatch('swal', [
            'icon' => 'success',
            'title' => 'Final Berhasil Digenerate',
            'text' => $qualifiers->count().' peserta terbaik telah dijadwalkan ke babak Final.',
        ]);
    }

    // ─── GENERATE TIEBREAK SCHEDULE ───────────────────────────

    public function openTiebreakModal(string $round, array $regIds): void
    {
        $this->tiebreakRound = $round;
        $this->tiebreakRegistrationIds = $regIds;

        $matchIds = $this->getMatchNumberIds();
        $existing = DrawingMatchNumber::whereIn('match_number_id', $matchIds)
            ->where('round', $round)
            ->first();

        $this->tiebreakCourtId = $existing?->court_id;
        $this->tiebreakSessionTimeId = $existing?->session_time_id;
        $this->tiebreakRundownId = $existing?->rundown_id;
        $this->tiebreakScheduleDate = $existing?->schedule_date;
        $this->showTiebreakModal = true;
    }

    public function generateTiebreakSchedule(): void
    {
        foreach ($this->tiebreakRegistrationIds as $regId) {
            // Create tiebreak score entry if not exists
            $lastTb = EmbuScore::where('match_number_id', $this->selectedMatchId)
                ->where('registration_id', $regId)
                ->where('round_label', $this->tiebreakRound)
                ->max('tiebreak_round');

            EmbuScore::create([
                'match_number_id' => $this->selectedMatchId,
                'registration_id' => $regId,
                'round_label' => $this->tiebreakRound,
                'tiebreak_round' => ((int) $lastTb) + 1,
                'judge_1' => 0,
                'judge_2' => 0,
                'judge_3' => 0,
                'judge_4' => 0,
                'judge_5' => 0,
                'total_score' => 0,
                'nilai_akhir' => 0,
                'denda' => 0,
            ]);

            // Update drawing with new schedule
            DrawingMatchNumber::updateOrCreate(
                [
                    'match_number_id' => $this->selectedMatchId,
                    'registration_id' => $regId,
                    'round' => $this->tiebreakRound.' Tiebreak',
                ],
                [
                    'draft_type' => 'embu',
                    'court_id' => $this->tiebreakCourtId,
                    'session_time_id' => $this->tiebreakSessionTimeId,
                    'rundown_id' => $this->tiebreakRundownId,
                    'schedule_date' => $this->tiebreakScheduleDate,
                    'sequence_number' => 0,
                ]
            );
        }

        $this->showTiebreakModal = false;

        $this->dispatch('swal', [
            'icon' => 'success',
            'title' => 'Jadwal Tanding Ulang Dibuat',
            'text' => count($this->tiebreakRegistrationIds).' peserta dijadwalkan ulang.',
        ]);
    }

    // ─── CONFIRM CHAMPION ─────────────────────────────────────

    public function confirmChampion(): void
    {
        $tiedIds = $this->detectFinalTies();
        if (! empty($tiedIds)) {
            $this->dispatch('swal', [
                'icon' => 'warning',
                'title' => 'Masih Ada Nilai Seri!',
                'text' => count($tiedIds).' peserta memiliki nilai akumulasi yang sama. Selesaikan tanding ulang terlebih dahulu.',
            ]);

            return;
        }

        $rankings = $this->getFinalRanking()->values();

        if ($rankings->isEmpty()) {
            $this->dispatch('swal', ['icon' => 'warning', 'title' => 'Gagal Konfirmasi', 'text' => 'Tidak ada peserta di babak Final.']);

            return;
        }

        $matchIds = $this->getMatchNumberIds();

        // Clear previous champions for all affected matches
        EmbuChampion::whereIn('match_number_id', $matchIds)->delete();
        TournamentResult::whereIn('match_number_id', $matchIds)->delete();

        foreach ($rankings as $idx => $reg) {
            $rank = $idx + 1;

            if ($rank > 4) {
                break;
            }

            // USE THE SPECIFIC MATCH ID from the drawing, not just the master ID
            $targetMatchId = $reg['match_number_id'] ?? $this->selectedMatchId;

            EmbuChampion::create([
                'match_number_id' => $targetMatchId,
                'registration_id' => $reg['id'],
                'drawing_id' => $reg['drawing_id'] ?? null,
                'rank' => $rank,
                'penyisihan_score' => $reg['penyisihan_score']?->nilai_akhir ?? 0,
                'final_score' => $reg['final_score']?->nilai_akhir ?? 0,
                'accumulated_score' => $reg['accumulated'] ?? 0,
            ]);

            $athleteNames = $reg['athletes']->unique('id')->pluck('name')->implode(', ');
            $contingentName = $reg['contingent']?->name ?? '-';

            TournamentResult::updateOrCreate(
                [
                    'match_number_id' => $targetMatchId,
                    'registration_id' => $reg['id'],
                    'rank' => $rank,
                ],
                [
                    'draft_type' => 'embu',
                    'athlete_names' => $athleteNames,
                    'contingent_name' => $contingentName,
                    'penyisihan_score' => $reg['penyisihan_score']?->nilai_akhir ?? 0,
                    'final_score' => $reg['final_score']?->nilai_akhir ?? 0,
                    'accumulated_score' => $reg['accumulated'] ?? 0,
                    'generated_by' => Auth::user()?->name ?? 'System',
                    'confirmed_at' => now(),
                ]
            );
        }

        $this->showChampionModal = false;

        $this->dispatch('swal', [
            'icon' => 'success',
            'title' => '🏆 Juara Dikonfirmasi!',
            'text' => 'Data juara berhasil disimpan ke database.',
        ]);
    }

    // ─── RENDER ──────────────────────────────────────────────

    public function render()
    {
        $ageGroups = AgeGroup::whereHas('matchNumbers', function ($q) {
            $q->where('draft_type', 'embu')
                ->whereHas('athletes')
                ->whereHas('drawings');
        })->orderBy('name')->get();

        $query = MatchNumber::where('draft_type', 'embu')
            ->whereHas('athletes')
            ->whereHas('drawings')
            ->when($this->selectedAgeGroupId, fn ($q) => $q->where('match_numbers.age_group_id', $this->selectedAgeGroupId))
            ->leftJoin('match_number_merge_details', 'match_numbers.id', '=', 'match_number_merge_details.match_number_id')
            ->leftJoin('match_number_merges', 'match_number_merge_details.match_number_merge_id', '=', 'match_number_merges.id')
            ->select('match_numbers.*', 'match_number_merges.name as merge_group_name', 'match_number_merge_details.match_number_merge_id')
            ->where(function ($q) {
                $q->whereNull('match_number_merge_details.match_number_merge_id')
                    ->orWhereRaw('match_numbers.id = (SELECT MIN(m2.match_number_id) FROM match_number_merge_details m2 WHERE m2.match_number_merge_id = match_number_merge_details.match_number_merge_id)');
            });

        $embuMatches = $query->orderBy('match_numbers.name')
            ->get()
            ->map(function ($m) {
                if ($m->match_number_merge_id) {
                    $m->display_name = $m->merge_group_name ?: 'Merged Group';
                } else {
                    $m->display_name = $m->name;
                }

                return $m;
            });

        $matchIds = $this->getMatchNumberIds();
        $penyisihanRanking = $this->getPenyisihanRanking();
        $finalRanking = $this->getFinalRanking();
        $finalExists = DrawingMatchNumber::whereIn('match_number_id', $matchIds)
            ->where('round', 'Final')
            ->exists();
        $tiedPenyisihanIds = $this->detectPenyisihanBoundaryTies();
        $tiedFinalIds = $this->detectFinalTies();

        $champions = $this->selectedMatchId
            ? EmbuChampion::whereIn('match_number_id', $matchIds)
                ->orderBy('rank')
                ->with(['registration.contingent', 'matchNumber.athletes'])
                ->get()
            : collect();

        $courts = Court::orderBy('name')->get();
        $pools = Pool::orderBy('name')->get();
        $sessionTimes = SessionTime::orderBy('name')->get();
        $rundowns = Rundown::orderBy('name')->get();
        $ageGroups = AgeGroup::whereHas('matchNumbers', function ($q) {
            $q->where('draft_type', 'embu')
                ->whereHas('athletes')
                ->whereHas('drawings');
        })->orderBy('name')->get();

        return view('livewire.admin.new-embu-result-index', [
            'embuMatches' => $embuMatches,
            'penyisihanRanking' => $penyisihanRanking,
            'finalRanking' => $finalRanking,
            'finalExists' => $finalExists,
            'tiedPenyisihanIds' => $tiedPenyisihanIds,
            'tiedFinalIds' => $tiedFinalIds,
            'champions' => $champions,
            'courts' => $courts,
            'pools' => $pools,
            'sessionTimes' => $sessionTimes,
            'rundowns' => $rundowns,
            'ageGroups' => $ageGroups,
        ]);
    }
}

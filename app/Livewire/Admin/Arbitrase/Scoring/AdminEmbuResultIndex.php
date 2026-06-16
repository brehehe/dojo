<?php

namespace App\Livewire\Admin\Arbitrase\Scoring;

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
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.admin')]
class AdminEmbuResultIndex extends Component
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

    // ─── LIFECYCLE ────────────────────────────────────────────

    public function mount(): void
    {
        $first = MatchNumber::where('draft_type', 'embu')
            ->whereHas('athletes')
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

        $drawings = DrawingMatchNumber::with('pool')
            ->where('match_number_id', $this->selectedMatchId)
            ->where('round', 'Penyisihan')
            ->get();

        $drawingRegIds = $drawings->pluck('registration_id')->unique()->filter()->toArray();
        $registrations = Registration::with(['contingent', 'athletes'])->whereIn('id', $drawingRegIds)->get()->keyBy('id');
        $scores = EmbuScore::where('match_number_id', $this->selectedMatchId)
            ->where('round_label', 'Penyisihan')
            ->get();

        $participants = $drawings->map(function ($drawing) use ($scores, $registrations) {
            $regId = $drawing->registration_id;
            $reg = $registrations->get($regId);
            if (! $reg) {
                return null;
            }

            // Correctly filter athletes for this specific team/drawing
            $athleteIds = $drawing->metadata['athlete_ids'] ?? [];
            $athletes = collect();
            if (! empty($athleteIds)) {
                $athletes = $reg->athletes->whereIn('id', $athleteIds)->values();
            } else {
                $athletes = $reg->athletes;
            }

            $score = $scores->where('registration_id', $regId)
                ->where('match_number_id', $this->selectedMatchId)
                ->where('drawing_id', $drawing->id)
                ->where('tiebreak_round', 0)
                ->first();

            if (! $score) {
                $score = $scores->where('registration_id', $regId)
                    ->where('match_number_id', $this->selectedMatchId)
                    ->whereNull('drawing_id')
                    ->where('tiebreak_round', 0)
                    ->first();
            }

            $tiebreakScore = $scores->where('registration_id', $regId)
                ->where('match_number_id', $this->selectedMatchId)
                ->where('drawing_id', $drawing->id)
                ->where('tiebreak_round', '>', 0)
                ->sortByDesc('tiebreak_round')
                ->first();

            if (! $tiebreakScore) {
                $tiebreakScore = $scores->where('registration_id', $regId)
                    ->where('match_number_id', $this->selectedMatchId)
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
                'match_number_id' => $this->selectedMatchId,
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

        $match = MatchNumber::with(['athletes', 'embuScores'])->find($this->selectedMatchId);
        if (! $match) {
            return collect();
        }

        // Only those with Final drawings
        $finalRegIds = DrawingMatchNumber::where('match_number_id', $this->selectedMatchId)
            ->where('round', 'Final')
            ->pluck('registration_id')
            ->unique();

        return $finalRegIds->map(function ($regId) use ($match) {
            $reg = Registration::with('contingent')->find($regId);

            $penyisihanScore = $match->embuScores
                ->where('registration_id', $regId)
                ->where('round_label', 'Penyisihan')
                ->where('tiebreak_round', 0)
                ->first();

            // Latest tiebreak for penyisihan
            $penyisihanTbScore = $match->embuScores
                ->where('registration_id', $regId)
                ->where('round_label', 'Penyisihan')
                ->where('tiebreak_round', '>', 0)
                ->sortByDesc('tiebreak_round')
                ->first();

            $finalScore = $match->embuScores
                ->where('registration_id', $regId)
                ->where('round_label', 'Final')
                ->where('tiebreak_round', 0)
                ->first();

            $finalTbScore = $match->embuScores
                ->where('registration_id', $regId)
                ->where('round_label', 'Final')
                ->where('tiebreak_round', '>', 0)
                ->sortByDesc('tiebreak_round')
                ->first();

            $effectivePenyisihan = ($penyisihanTbScore ?? $penyisihanScore)?->nilai_akhir ?? 0;
            $effectiveFinal = ($finalTbScore ?? $finalScore)?->nilai_akhir ?? 0;

            // Accumulated = penyisihan + final (always, even if final not scored yet)
            $accumulated = (float) $effectivePenyisihan + (float) $effectiveFinal;

            return [
                'id' => $regId,
                'athletes' => $match->athletes->filter(fn ($a) => $a->pivot->registration_id == $regId),
                'contingent' => $reg?->contingent,
                'penyisihan_score' => $penyisihanTbScore ?? $penyisihanScore,
                'final_score' => $finalTbScore ?? $finalScore,
                'accumulated' => $accumulated,
                'final_scored' => ($finalTbScore ?? $finalScore) !== null,
            ];
        })
            ->sortByDesc('accumulated')
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
        // Pre-fill from existing Penyisihan drawing
        $existing = DrawingMatchNumber::where('match_number_id', $this->selectedMatchId)
            ->where('round', 'Penyisihan')
            ->first();

        $this->finalCourtId = $existing?->court_id;
        $this->finalPoolId = null; // Usually Final is in a single pool, so we can leave it empty or create a 'FINAL POOL'
        $this->finalSessionTimeId = $existing?->session_time_id;
        $this->finalRundownId = $existing?->rundown_id;
        $this->finalScheduleDate = $existing?->schedule_date;
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

        $existingFinalDrawings = DrawingMatchNumber::where('match_number_id', $this->selectedMatchId)
            ->where('round', 'Final')
            ->orderBy('sequence_number')
            ->get();

        $schedules = [];
        foreach ($existingFinalDrawings as $drawing) {
            $schedules[$drawing->sequence_number] = [
                'court_id' => $drawing->court_id,
                'pool_id' => $drawing->pool_id,
                'session_time_id' => $drawing->session_time_id,
                'rundown_id' => $drawing->rundown_id,
                'schedule_date' => $drawing->schedule_date,
                'metadata' => $drawing->metadata,
            ];
        }

        $existingPenyisihan = DrawingMatchNumber::where('match_number_id', $this->selectedMatchId)
            ->where('round', 'Penyisihan')
            ->first();

        $firstFinal = $existingFinalDrawings->first();
        $courtId = $this->finalCourtId ?? $firstFinal?->court_id ?? $existingPenyisihan?->court_id;
        $poolId = $this->finalPoolId ?? $firstFinal?->pool_id ?? $existingPenyisihan?->pool_id;
        $sessionTimeId = $this->finalSessionTimeId ?? $firstFinal?->session_time_id ?? $existingPenyisihan?->session_time_id;
        $rundownId = $this->finalRundownId ?? $firstFinal?->rundown_id ?? $existingPenyisihan?->rundown_id;
        $scheduleDate = $this->finalScheduleDate ?? $firstFinal?->schedule_date ?? $existingPenyisihan?->schedule_date;

        // Delete existing Final drawings to start fresh, but DO NOT delete existing scores
        DrawingMatchNumber::where('match_number_id', $this->selectedMatchId)
            ->where('round', 'Final')
            ->delete();

        // Clear active court drawing if it matches the current match to avoid stale references
        if ($courtId) {
            $court = Court::find($courtId);
            if ($court && $court->active_match_id == $this->selectedMatchId) {
                $court->update([
                    'active_match_id' => null,
                    'active_drawing_id' => null,
                    'active_registration_id' => null,
                    'active_bracket_node' => null,
                ]);
            }
        }

        $session = SessionTime::find($sessionTimeId);
        $sessionStart = $session ? Carbon::parse($session->start_time) : null;
        $duration = 10;

        $qualifiersValues = $qualifiers->values();
        foreach ($qualifiersValues as $seq => $reg) {
            $order = $seq + 1;

            $cId = $this->finalCourtId ?? $courtId;
            $pId = $this->finalPoolId ?? $poolId;
            $sTimeId = $this->finalSessionTimeId ?? $sessionTimeId;
            $rId = $this->finalRundownId ?? $rundownId;
            $sDate = $this->finalScheduleDate ?? $scheduleDate;

            // Always calculate new sequential time slots starting from session start time
            if ($sessionStart) {
                $matchStart = $sessionStart->copy()->addMinutes($seq * $duration);
                $matchEnd = $matchStart->copy()->addMinutes($duration);
                $timeMeta = [
                    'start_time' => $matchStart->format('H:i'),
                    'end_time' => $matchEnd->format('H:i'),
                    'duration' => $duration,
                ];
            } else {
                $timeMeta = [];
            }

            // Get match number code prefix
            $matchObj = MatchNumber::find($this->selectedMatchId);
            $matchIdCode = $matchObj ? $matchObj->name_code.'-F-'.str_pad($order, 2, '0', STR_PAD_LEFT) : 'F-'.str_pad($order, 2, '0', STR_PAD_LEFT);

            $meta = [
                'contingent' => $reg['contingent']?->name ?? 'Unknown',
                'athlete_name' => $reg['athletes']->pluck('name')->implode(', '),
                'athlete_ids' => $reg['athlete_ids'] ?? [],
                'pool_label' => 'FINAL',
                'officials' => [],
                'match_id_code' => $matchIdCode,
            ];
            // Merge metadata with new sequential times
            $meta = array_merge($meta, $timeMeta);

            $newDrawing = DrawingMatchNumber::create([
                'match_number_id' => $this->selectedMatchId, // Force the main match_number_id to avoid jumping!
                'registration_id' => $reg['id'],
                'round' => 'Final',
                'draft_type' => 'embu',
                'sequence_number' => $order,
                'court_id' => $cId,
                'pool_id' => $pId,
                'session_time_id' => $sTimeId,
                'rundown_id' => $rId,
                'schedule_date' => $sDate,
                'metadata' => $meta,
            ]);

            // Update any existing scores for this registration and match to point to the new drawing ID
            EmbuScore::where('match_number_id', $this->selectedMatchId)
                ->where('registration_id', $reg['id'])
                ->where('round_label', 'Final')
                ->update(['drawing_id' => $newDrawing->id]);
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

        $existing = DrawingMatchNumber::where('match_number_id', $this->selectedMatchId)
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

        // Clear previous champions for this match
        EmbuChampion::where('match_number_id', $this->selectedMatchId)->delete();

        foreach ($rankings as $idx => $reg) {
            EmbuChampion::create([
                'match_number_id' => $this->selectedMatchId,
                'registration_id' => $reg['id'],
                'rank' => $idx + 1,
                'penyisihan_score' => $reg['penyisihan_score']?->nilai_akhir ?? 0,
                'final_score' => $reg['final_score']?->nilai_akhir ?? 0,
                'accumulated_score' => $reg['accumulated'] ?? 0,
            ]);
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
        $embuMatches = MatchNumber::where('draft_type', 'embu')
            ->whereHas('athletes')
            ->when($this->selectedAgeGroupId, fn ($q) => $q->where('age_group_id', $this->selectedAgeGroupId))
            ->with('ageGroup')
            ->orderBy('name')
            ->get();

        $penyisihanRanking = $this->getPenyisihanRanking();
        $finalRanking = $this->getFinalRanking();
        $finalExists = DrawingMatchNumber::where('match_number_id', $this->selectedMatchId)
            ->where('round', 'Final')
            ->exists();
        $tiedPenyisihanIds = $this->detectPenyisihanBoundaryTies();
        $tiedFinalIds = $this->detectFinalTies();

        $champions = $this->selectedMatchId
            ? EmbuChampion::where('match_number_id', $this->selectedMatchId)
                ->orderBy('rank')
                ->with(['registration.contingent', 'matchNumber.athletes'])
                ->get()
            : collect();

        $courts = Court::orderBy('name')->get();
        $pools = Pool::orderBy('name')->get();
        $sessionTimes = SessionTime::orderBy('name')->get();
        $rundowns = Rundown::orderBy('name')->get();
        $ageGroups = AgeGroup::orderBy('name')->get();

        return view('livewire.admin.arbitrase.scoring.admin-embu-result-index', [
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

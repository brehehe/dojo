<?php

namespace App\Livewire\Admin\Arbitrase\Scoring;

use App\Models\Athlete;
use App\Models\Court\Court;
use App\Models\DrawingMatchNumber;
use App\Models\EmbuScore;
use App\Models\MatchNumber\MatchNumber;
use App\Models\Registration;
use Illuminate\Support\Facades\Cache;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Url;
use Livewire\Component;

#[Layout('layouts.admin')]
class AdminArbitraseScoringEmbuDetail extends Component
{
    #[Url(as: 'round')]
    public ?string $urlRound = null;

    #[Url(as: 'pool_id')]
    public ?int $urlPoolId = null;

    public MatchNumber $matchNumber;

    public $scores = [];

    public $denda = 0;

    public $activeRegistrationId = null;

    public $showModal = false;

    public ?int $selectedPoolId = null;

    /** Current round being evaluated: 'Penyisihan' | 'Final' | 'Tiebreak' */
    public $currentRound = 'Penyisihan';

    public function mount(MatchNumber $matchNumber)
    {
        $this->matchNumber = $matchNumber->load([
            'athletes',
            'embuScores',
            'drawings.court',
            'drawings.sessionTime',
            'drawings.rundown',
            'drawings.pool',
            'ageGroup',
        ]);

        // Detect current round from URL or existing drawings
        if ($this->urlRound) {
            $this->currentRound = $this->urlRound;
        } else {
            $hasFinalists = $this->matchNumber->embuScores
                ->where('round_label', 'Final')
                ->count() > 0;
            $this->currentRound = $hasFinalists ? 'Final' : 'Penyisihan';
        }

        if ($this->urlPoolId) {
            $this->selectedPoolId = $this->urlPoolId;
        } else {
            $firstDrawing = $this->matchNumber->drawings->where('round', $this->currentRound)->whereNotNull('pool_id')->first();
            if ($firstDrawing) {
                $this->selectedPoolId = $firstDrawing->pool_id;
            }
        }
    }

    public function setPool(?int $poolId)
    {
        $this->selectedPoolId = $poolId;
        $this->urlPoolId = $poolId; // Update URL when switching
    }

    // ─── PANGGIL PESERTA ─────────────────────────────────────

    public function callParticipant($drawingId)
    {
        // Cari drawing yang SPESIFIK untuk slot ini
        $drawing = DrawingMatchNumber::with(['court', 'pool', 'registration.contingent', 'registration.athletes'])
            ->find($drawingId);

        if (! $drawing || ! $drawing->registration) {
            $this->dispatch('swal', [
                'icon' => 'warning',
                'title' => 'Babak Final Belum Tergenerate',
                'text' => 'Peserta lolos babak final belum digenerate.',
            ]);

            return;
        }

        $registrationId = $drawing->registration_id;

        // Update active_registration_id di match number (untuk referensi wasit)
        $this->matchNumber->update(['active_registration_id' => $registrationId]);

        // Hanya update court yang memang assigned ke registrasi ini
        if ($drawing && $drawing->court) {
            $drawing->court->update([
                'active_match_id' => $this->matchNumber->id,
                'active_drawing_id' => $drawing->id,
                'active_registration_id' => $registrationId,
                'active_bracket_node' => null,
            ]);

            // Announcement logic - Use specific athletes from drawing metadata if possible
            $metaAthleteIds = $drawing->metadata['athlete_ids'] ?? [];
            if (! empty($metaAthleteIds)) {
                $athletes = Athlete::whereIn('id', $metaAthleteIds)->pluck('name')->implode(', ');
            } else {
                $athletes = $this->matchNumber->athletes()
                    ->wherePivot('registration_id', $registrationId)
                    ->pluck('name')
                    ->implode(', ');
            }

            $contingent = $drawing->registration->contingent->name ?? '—';
            $matchName = $this->matchNumber->name;
            $courtName = $drawing->court->name;
            $poolName = $drawing->pool ? ' Pool '.$drawing->pool->name : '';

            $text = "Panggilan untuk kontingen {$contingent}. Atas nama {$athletes}. Silakan menuju {$courtName}. Untuk kategori {$matchName}{$poolName}. Sekali lagi, panggilan untuk kontingen {$contingent}. Atas nama {$athletes}. Silakan menuju {$courtName}. Terima kasih.";

            $this->dispatch('play-announcer', ['text' => $text]);
        }

        $this->dispatch('swal', [
            'icon' => 'success',
            'title' => 'Peserta Dipanggil',
            'text' => 'Layar wasit dan TV Monitor kini terpusat ke peserta ini.',
        ]);
    }

    // ─── INPUT SKOR (ADMIN OVERRIDE) ─────────────────────────

    public function openScoringModal($registrationId)
    {
        $this->activeRegistrationId = $registrationId;

        // Load latest score for this round
        $existing = EmbuScore::where('match_number_id', $this->matchNumber->id)
            ->where('registration_id', $registrationId)
            ->where('round_label', $this->currentRound)
            ->latest()
            ->first();

        $this->denda = $existing?->denda ?? 0;
        $this->scores = $existing ? [
            'judge_1' => $existing->judge_1,
            'judge_2' => $existing->judge_2,
            'judge_3' => $existing->judge_3,
            'judge_4' => $existing->judge_4,
            'judge_5' => $existing->judge_5,
        ] : ['judge_1' => 0, 'judge_2' => 0, 'judge_3' => 0, 'judge_4' => 0, 'judge_5' => 0];

        $this->showModal = true;
    }

    public function applyTimerPenalty($timeMs, $registrationId = null)
    {
        $seconds = floor($timeMs / 1000);
        $denda = 0;

        $matchNumber = null;
        if ($registrationId) {
            $drawing = DrawingMatchNumber::where('match_number_id', $this->matchNumber->id)
                ->where('registration_id', $registrationId)
                ->where('round', $this->currentRound)
                ->first();
            if ($drawing) {
                $matchNumber = $drawing->matchNumber;
            }
        }

        if (! $matchNumber) {
            $matchNumber = $this->matchNumber;
        }

        $isGroup = $matchNumber ? ($matchNumber->max_athletes > 1) : false;

        if ($isGroup) {
            // Beregu / Pasangan (Target 90s - 120s)
            if ($seconds >= 50 && $seconds <= 89) {
                $denda = 5;
            } elseif ($seconds < 50) {
                $denda = 10;
            } elseif ($seconds >= 121 && $seconds <= 135) {
                $denda = 5;
            } elseif ($seconds > 135) {
                $denda = 10;
            }
        } else {
            // Single / Solo / Tandoku (Target 90s)
            if ($seconds >= 76 && $seconds <= 89) {
                $denda = 5;
            } elseif ($seconds < 76) {
                $denda = 10;
            } elseif ($seconds >= 91 && $seconds <= 100) {
                $denda = 5;
            } elseif ($seconds > 100) {
                $denda = 10;
            }
        }

        $this->denda = $denda;

        $this->dispatch('swal', [
            'icon' => 'info',
            'title' => 'Kalkulasi Denda Waktu',
            'text' => "Waktu terukur: {$seconds} detik (".($isGroup ? 'Beregu/Pasangan' : 'Single')."). Potongan denda: {$denda}",
        ]);

        return $denda;
    }

    public function finishMatch($drawingId, $timeMs = 0)
    {
        $drawing = DrawingMatchNumber::with('court')
            ->find($drawingId);

        if (! $drawing) {
            return;
        }

        $registrationId = $drawing->registration_id;

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

        // Apply Penalty automatically
        $calculatedDenda = $this->applyTimerPenalty($timeMs, $registrationId);

        $seconds = floor($timeMs / 1000);
        $minutes = floor($seconds / 60);
        $remainingSeconds = $seconds % 60;
        $formattedTime = sprintf('%02d:%02d', $minutes, $remainingSeconds);

        // Save the penalty to the score model immediately
        $score = EmbuScore::firstOrCreate(
            [
                'match_number_id' => $this->matchNumber->id,
                'registration_id' => $registrationId,
                'round_label' => $this->currentRound,
                'drawing_id' => $drawing->id,
            ],
            [
                'judge_1' => 0,
                'judge_2' => 0,
                'judge_3' => 0,
                'judge_4' => 0,
                'judge_5' => 0,
                'denda' => 0,
                'total_score' => 0,
                'nilai_akhir' => 0,
            ]
        );

        $score->denda = $calculatedDenda;
        $score->waktu = $formattedTime;

        // Sum middle 3 if fully scored (5 judges)
        $judges = [
            $score->judge_1,
            $score->judge_2,
            $score->judge_3,
            $score->judge_4,
            $score->judge_5,
        ];
        $scoredCount = count(array_filter($judges, fn ($v) => $v > 0));
        if ($scoredCount === 5) {
            sort($judges);
            $total = $judges[1] + $judges[2] + $judges[3];
        } else {
            $total = array_sum($judges);
        }

        $score->total_score = $total;
        $score->nilai_akhir = max(0, $total - $score->denda);
        $score->save();

        // Close call
        $this->matchNumber->update(['active_registration_id' => null]);

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
            'text' => 'Waktu dan denda telah diakumulasi, Panggilan ditutup.',
        ]);
    }

    public function saveScore()
    {
        $judgeValues = array_values($this->scores);
        sort($judgeValues);

        // Sum middle 3 (exclude min and max)
        $total = $judgeValues[1] + $judgeValues[2] + $judgeValues[3];
        $nilaiAkhir = max(0, $total - (float) $this->denda);

        EmbuScore::updateOrCreate(
            [
                'match_number_id' => $this->matchNumber->id,
                'registration_id' => $this->activeRegistrationId,
                'round_label' => $this->currentRound,
            ],
            array_merge($this->scores, [
                'total_score' => $total,
                'denda' => $this->denda,
                'nilai_akhir' => $nilaiAkhir,
                'tiebreak_round' => 0,
            ])
        );

        $this->recalculateRanks();
        $this->showModal = false;
        $this->matchNumber->load('embuScores');

        $this->dispatch('swal', [
            'icon' => 'success',
            'title' => 'Nilai Berhasil Disimpan',
            'text' => 'Total: '.number_format($total, 1).' | Nilai Akhir: '.number_format($nilaiAkhir, 1),
        ]);
    }

    // ─── RANKING ─────────────────────────────────────────────

    /**
     * Rank from lowest to highest for Penyisihan, highest to lowest for Final.
     * Penyisihan: nilai terendah = peringkat terbaik (lolos ke final).
     */
    protected function recalculateRanks(): void
    {
        $scores = EmbuScore::where('match_number_id', $this->matchNumber->id)
            ->where('round_label', $this->currentRound)
            ->get();

        if ($this->currentRound === 'Penyisihan') {
            // Sort by Nilai Akhir (Primary) and Judge 1 (Secondary tie-break)
            $sorted = $scores->sort(function ($a, $b) {
                if ($a->nilai_akhir != $b->nilai_akhir) {
                    return $b->nilai_akhir <=> $a->nilai_akhir; // Descending
                }

                return $b->judge_1 <=> $a->judge_1; // Tie-break: Judge 1 Descending
            })->values();
        } else {
            // Final: highest accumulated score = rank 1
            $penyisihanScores = EmbuScore::where('match_number_id', $this->matchNumber->id)
                ->where('round_label', 'Penyisihan')
                ->get()
                ->groupBy('registration_id')
                ->map(fn ($group) => $group->sortByDesc('tiebreak_round')->first());

            $sorted = $scores->sort(function ($a, $b) use ($penyisihanScores) {
                $pA = $penyisihanScores[$a->registration_id] ?? null;
                $pB = $penyisihanScores[$b->registration_id] ?? null;

                $totalA = $a->nilai_akhir + ($pA ? $pA->nilai_akhir : 0);
                $totalB = $b->nilai_akhir + ($pB ? $pB->nilai_akhir : 0);

                if ($totalA != $totalB) {
                    return $totalB <=> $totalA; // Descending
                }

                // If tied, use Judge 1 from current (Final) round
                return $b->judge_1 <=> $a->judge_1;
            })->values();
        }

        foreach ($sorted as $index => $score) {
            $score->update(['rank' => $index + 1]);
        }
    }

    // ─── DETEKSI TIE ─────────────────────────────────────────

    /**
     * Returns registration IDs that are tied at or around the qualifying boundary.
     * For Penyisihan: those qualifying are the ones with LOWEST scores.
     * A tie exists if a score at the boundary equals another score just outside it.
     */
    public function detectTies(?int $qualifyingThreshold = null): array
    {
        $scores = EmbuScore::where('match_number_id', $this->matchNumber->id)
            ->where('round_label', $this->currentRound)
            ->orderByDesc('nilai_akhir') // highest first
            ->orderByDesc('judge_1')      // tie-break
            ->get();

        if ($scores->isEmpty()) {
            return [];
        }

        $threshold = $qualifyingThreshold ?? $this->getDefaultQualifyThreshold();

        if ($scores->count() <= $threshold) {
            return []; // no boundary, all qualify
        }

        // The score at the boundary (last qualifying position)
        $boundaryScoreObj = $scores->get($threshold - 1);
        $boundaryVal = $boundaryScoreObj?->nilai_akhir;
        $boundaryJ1 = $boundaryScoreObj?->judge_1;

        // Find all who share this exact score and Judge 1 tie-break (if needed)
        // Actually, a tie for qualifying usually means exactly same final score AND same tiebreaker
        $tied = $scores->filter(
            fn ($s) => (float) $s->nilai_akhir === (float) $boundaryVal &&
            (float) $s->judge_1 === (float) $boundaryJ1
        );

        if ($tied->count() <= 1) {
            return []; // not a tie
        }

        return $tied->pluck('registration_id')->toArray();
    }

    protected function getDefaultQualifyThreshold(): int
    {
        // Match drawings determine how many per pool qualify
        $drawing = $this->matchNumber->drawing_data;

        if ($drawing && isset($drawing['qualifiers'])) {
            return (int) $drawing['qualifiers'];
        }

        return 4; // Default: top 4 per pool qualify
    }

    // ─── TANDING ULANG ───────────────────────────────────────

    /**
     * Trigger a tiebreak for the listed registration IDs.
     * Create a new score record with tiebreak_round + 1.
     * The original scores are preserved as history.
     */
    public function requestTiebreak(array $registrationIds)
    {
        foreach ($registrationIds as $regId) {
            $lastTb = EmbuScore::where('match_number_id', $this->matchNumber->id)
                ->where('registration_id', $regId)
                ->where('round_label', $this->currentRound)
                ->max('tiebreak_round');

            EmbuScore::create([
                'match_number_id' => $this->matchNumber->id,
                'registration_id' => $regId,
                'round_label' => $this->currentRound,
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
        }

        $this->matchNumber->load('embuScores');

        $this->dispatch('swal', [
            'icon' => 'info',
            'title' => 'Tanding Ulang',
            'text' => count($registrationIds).' peserta akan mengulangi penilaian.',
        ]);
    }

    // ─── LOLOSKAN KE FINAL ───────────────────────────────────

    /**
     * From the Penyisihan scores (ranked lowest first), pick the top qualifiers
     * and create DrawingMatchNumber entries for the Final round.
     */
    public function advanceToFinal(?int $threshold = null)
    {
        $limitPerPool = $threshold ?? $this->getDefaultQualifyThreshold();

        // Cari semua pool yang ada di babak penyisihan
        $penyisihanDrawings = DrawingMatchNumber::where('match_number_id', $this->matchNumber->id)
            ->where('round', 'Penyisihan')
            ->get();

        $byPool = $penyisihanDrawings->groupBy('pool_id');
        $qualifiers = collect();
        $ties = [];

        foreach ($byPool as $poolId => $drawingsInPool) {
            $regIdsInPool = $drawingsInPool->pluck('registration_id');

            $scoresInPool = EmbuScore::where('match_number_id', $this->matchNumber->id)
                ->where('round_label', 'Penyisihan')
                ->where('tiebreak_round', 0)
                ->whereIn('registration_id', $regIdsInPool)
                ->orderBy('nilai_akhir') // lowest first
                ->get();

            $topInPool = $scoresInPool->take($limitPerPool);
            $qualifiers = $qualifiers->concat($topInPool);

            // Deteksi seri di batas kualifikasi per pool
            if ($scoresInPool->count() > $limitPerPool) {
                $boundaryScore = $scoresInPool->get($limitPerPool - 1)?->nilai_akhir;
                $tiedInPool = $scoresInPool->filter(fn ($s) => (float) $s->nilai_akhir === (float) $boundaryScore);

                if ($tiedInPool->count() > 1 && $tiedInPool->last()->nilai_akhir == $scoresInPool->get($limitPerPool)?->nilai_akhir) {
                    $ties = array_merge($ties, $tiedInPool->pluck('registration_id')->toArray());
                }
            }
        }

        if ($qualifiers->isEmpty()) {
            $this->dispatch('swal', ['icon' => 'warning', 'title' => 'Belum ada nilai penyisihan']);

            return;
        }

        if (! empty($ties)) {
            $this->dispatch('swal', [
                'icon' => 'warning',
                'title' => 'Ada Nilai Seri!',
                'text' => 'Terdapat '.count($ties).' peserta dengan nilai yang sama. Lakukan Tanding Ulang terlebih dahulu.',
            ]);

            return;
        }

        // Create Final round DrawingMatchNumbers
        $seq = 1;
        $shuffledQualifiers = $qualifiers->shuffle();

        foreach ($shuffledQualifiers as $score) {
            DrawingMatchNumber::updateOrCreate(
                [
                    'match_number_id' => $this->matchNumber->id,
                    'registration_id' => $score->registration_id,
                    'round' => 'Final',
                ],
                [
                    'draft_type' => $this->matchNumber->draft_type,
                    'court_id' => $this->matchNumber->drawings->first()?->court_id,
                    'session_time_id' => $this->matchNumber->drawings->first()?->session_time_id,
                    'rundown_id' => $this->matchNumber->drawings->first()?->rundown_id,
                    'pool_id' => $this->matchNumber->drawings->first()?->pool_id,
                    'sequence_number' => $seq++,
                ]
            );
        }

        $this->currentRound = 'Final';
        $this->matchNumber->load('embuScores');

        $this->dispatch('swal', [
            'icon' => 'success',
            'title' => 'Final Dibuka!',
            'text' => $qualifiers->count().' peserta berhasil lolos ke babak Final.',
        ]);
    }

    // ─── RENDER ──────────────────────────────────────────────

    public function render()
    {
        // Reload fresh
        $this->matchNumber->load(['athletes', 'embuScores', 'drawings.court', 'drawings.sessionTime', 'drawings.rundown', 'drawings.pool', 'ageGroup']);

        // Fetch all drawings for the current match number and round
        $drawingsQuery = DrawingMatchNumber::with(['registration.contingent'])
            ->where('match_number_id', $this->matchNumber->id)
            ->where('round', $this->currentRound);

        if ($this->currentRound === 'Penyisihan' && $this->selectedPoolId) {
            $drawingsQuery->where('pool_id', $this->selectedPoolId);
        }

        $drawingsList = $drawingsQuery->orderBy('sequence_number')->get();

        // Fetch all scores for this match in current round
        $allScores = EmbuScore::where('match_number_id', $this->matchNumber->id)
            ->where('round_label', $this->currentRound)
            ->get();

        $registrations = $drawingsList->map(function ($drawing) use ($allScores) {
            $regId = $drawing->registration_id;
            $matchId = $drawing->match_number_id;
            $registration = $drawing->registration;

            // Fetch athletes specific to THIS drawing entry to avoid duplication and mix-ups
            $metaAthleteIds = $drawing->metadata['athlete_ids'] ?? [];

            if (! empty($metaAthleteIds)) {
                $athletes = Athlete::whereIn('id', $metaAthleteIds)->get();
            } else {
                // Fallback to old logic for legacy drawings
                $athletes = Athlete::whereHas('matchNumbers', function ($q) use ($matchId, $regId) {
                    $q->where('match_numbers.id', $matchId)
                        ->where('athlete_match_number.registration_id', $regId);
                })->get();
            }

            // Get the latest score for this specific drawing
            $score = $allScores->where('registration_id', $regId)
                ->where('match_number_id', $matchId)
                ->where('drawing_id', $drawing->id)
                ->sortByDesc('tiebreak_round')
                ->first();

            if (! $score) {
                // Fallback for legacy scores or if drawing_id not yet filled
                $score = $allScores->where('registration_id', $regId)
                    ->where('match_number_id', $matchId)
                    ->whereNull('drawing_id')
                    ->sortByDesc('tiebreak_round')
                    ->first();
            }

            // All score history for this specific drawing
            $scoreHistory = $allScores->where('registration_id', $regId)
                ->where('match_number_id', $matchId)
                ->where('drawing_id', $drawing->id)
                ->sortBy('tiebreak_round')
                ->values();

            if ($scoreHistory->isEmpty()) {
                $scoreHistory = $allScores->where('registration_id', $regId)
                    ->where('match_number_id', $matchId)
                    ->whereNull('drawing_id')
                    ->sortBy('tiebreak_round')
                    ->values();
            }

            $accumulatedScore = 0;
            $penyisihanScore = null;

            if ($this->currentRound === 'Final') {
                $penyisihanScore = EmbuScore::where('match_number_id', $this->matchNumber->id)
                    ->where('registration_id', $regId)
                    ->where('round_label', 'Penyisihan')
                    ->orderByDesc('tiebreak_round')
                    ->first();

                if ($penyisihanScore) {
                    $accumulatedScore += $penyisihanScore->nilai_akhir;
                }
            }

            if ($score) {
                $accumulatedScore += $score->nilai_akhir;
            }

            $isGroup = $drawing->matchNumber ? ($drawing->matchNumber->max_athletes > 1) : ($this->matchNumber->max_athletes > 1);

            return [
                'id' => $regId,
                'drawing_id' => $drawing->id,
                'match_number_id' => $matchId,
                'match_name' => $drawing->matchNumber?->name ?? '—',
                'is_group' => $isGroup,
                'athletes' => $athletes->unique('id'),
                'contingent' => $registration?->contingent,
                'score' => $score,
                'score_history' => $scoreHistory,
                'penyisihan_score' => $penyisihanScore,
                'accumulated_score' => $accumulatedScore,
                'sequence_number' => $drawing->sequence_number ?? 999,
            ];
        });

        // Urutkan berdasarkan rank (jika sudah ada), jika belum gunakan sequence_number
        $registrations = $registrations->sort(function ($a, $b) {
            $rankA = $a['score']?->rank ?? 999;
            $rankB = $b['score']?->rank ?? 999;

            if ($rankA != $rankB) {
                return $rankA <=> $rankB;
            }

            return $a['sequence_number'] <=> $b['sequence_number'];
        })->values();

        $firstDrawingQuery = $this->matchNumber->drawings->where('round', $this->currentRound);
        if ($this->currentRound === 'Penyisihan' && $this->selectedPoolId) {
            $firstDrawingQuery = $firstDrawingQuery->where('pool_id', $this->selectedPoolId);
        }
        $firstDrawing = $firstDrawingQuery->first();

        $availablePools = collect();
        if ($this->currentRound === 'Penyisihan') {
            $availablePools = $this->matchNumber->drawings
                ->where('round', 'Penyisihan')
                ->whereNotNull('pool_id')
                ->pluck('pool')
                ->unique('id')
                ->values();
        }

        $tiedIds = $this->detectTies();

        $courtId = $this->getCourtId();
        $court = $courtId ? Court::find($courtId) : null;
        $activeDrawingId = $court?->active_drawing_id;

        return view('livewire.admin.arbitrase.scoring.admin-arbitrase-scoring-embu-detail', [
            'registrations' => $registrations,
            'firstDrawing' => $firstDrawing,
            'availablePools' => $availablePools,
            'tiedIds' => $tiedIds,
            'activeDrawingId' => $activeDrawingId,
        ]);
    }

    // ─── TIMER CONTROLS (SYNC WITH MONITOR COURT) ─────────────────────────

    public function getCourtId()
    {
        $drawings = $this->matchNumber->drawings->where('round', $this->currentRound);

        if ($this->currentRound === 'Penyisihan' && $this->selectedPoolId) {
            $drawings = $drawings->where('pool_id', $this->selectedPoolId);
        }

        return $drawings->first()?->court_id;
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
        $state['countdown_end_ms'] = floor(microtime(true) * 1000) + 2000;
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

    public function dismissParticipant()
    {
        $this->matchNumber->update(['active_registration_id' => null]);

        $courtId = $this->getCourtId();
        if ($courtId) {
            $state = [
                'status' => 'stopped',
                'elapsed_ms' => 0,
                'started_at_ms' => null,
            ];
            Cache::put("court_{$courtId}_timer", $state);

            Court::where('id', $courtId)->update([
                'active_registration_id' => null,
            ]);
        }

        $this->dispatch('swal', [
            'icon' => 'success',
            'title' => 'Panggilan Ditutup',
            'text' => 'Wasit, TV Monitor, dan Timer telah direset.',
        ]);
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

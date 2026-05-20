<?php

namespace App\Livewire\Admin;

use App\Models\Athlete;
use App\Models\Court\Court;
use App\Models\DrawingMatchNumber;
use App\Models\EmbuScore;
use App\Models\MatchNumber\MatchNumber;
use App\Models\MatchNumberMerge;
use App\Models\Registration;
use App\Models\ScheduleReferee;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Url;
use Livewire\Component;

#[Layout('layouts.premium')]
class NewScoringEmbuIndex extends Component
{
    #[Url(as: 'round')]
    public ?string $urlRound = null;

    #[Url(as: 'pool_id')]
    public ?int $urlPoolId = null;

    public MatchNumber $matchNumber;

    public $merge = null;

    public $displayName = '';

    public $scores = [];

    public $denda = 0;

    public $activeRegistrationId = null;

    public $showModal = false;

    public ?int $selectedPoolId = null;

    /** Current round being evaluated: 'Penyisihan' | 'Final' | 'Tiebreak' */
    public $currentRound = 'Penyisihan';

    public $matchNumberIds = [];

    public function mount(MatchNumber $matchNumber)
    {
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

        $this->matchNumber->load([
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

    public function callOfficials()
    {
        $courtId = $this->getCourtId();
        $drawingsQuery = DrawingMatchNumber::with(['court', 'pool', 'sessionTime', 'registration.contingent'])
            ->whereIn('match_number_id', $this->matchNumberIds)
            ->where('round', $this->currentRound);

        if ($this->currentRound === 'Penyisihan' && $this->selectedPoolId) {
            $drawingsQuery->where('pool_id', $this->selectedPoolId);
        }

        $drawings = $drawingsQuery->get();
        $firstDrawing = $drawings->first();

        if ($firstDrawing) {
            $matchName = $this->merge->name ?? $this->matchNumber->name;
            $courtName = $firstDrawing->court->name ?? 'Lapangan';
            $poolName = $firstDrawing->pool ? ' Pool '.$firstDrawing->pool->name : '';
            $sessionTime = $firstDrawing->sessionTime ? $firstDrawing->sessionTime->name : '';

            $intro = "Persiapan untuk pertandingan kategori {$matchName}{$poolName}, di {$courtName}, {$sessionTime}. ";

            // 1. Panggilan Kontingen
            $contingentNames = $drawings->pluck('registration.contingent.name')->unique()->filter()->values();
            $contingentCall = '';
            if ($contingentNames->isNotEmpty()) {
                $contingentCall = 'Kepada seluruh kontingen: '.$contingentNames->implode(', ').'. Silakan mempersiapkan atletnya. ';
            }

            // 2. Panggilan Wasit (dari ScheduleReferee)
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

            $outro = "Sekali lagi, panggilan untuk seluruh official dan kontingen pada kategori {$matchName}{$poolName}. Mohon segera menuju {$courtName}. Terima kasih.";

            $fullText = $intro.$contingentCall.$refereeCall.$outro;

            $this->dispatch('play-announcer', ['text' => $fullText]);

            $this->dispatch('swal', [
                'icon' => 'success',
                'title' => 'Panggilan Detail Dilakukan',
                'text' => 'Seluruh kontingen, juri, panitera, dan korlap telah dipanggil secara spesifik.',
            ]);
        }
    }

    // ─── PANGGIL PESERTA ─────────────────────────────────────

    public function callParticipant($drawingId)
    {
        // Cari drawing yang SPESIFIK untuk slot ini
        $drawing = DrawingMatchNumber::with(['court', 'pool', 'registration.contingent', 'registration.athletes', 'matchNumber'])
            ->find($drawingId);

        if (! $drawing) {
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
                $athletes = Athlete::whereHas('matchNumbers', function ($q) use ($drawing) {
                    $q->where('match_numbers.id', $drawing->match_number_id)
                        ->where('athlete_match_number.registration_id', $drawing->registration_id);
                })->pluck('name')->implode(', ');
            }

            $contingent = $drawing->registration->contingent->name;
            $matchName = $this->merge->name ?? $drawing->matchNumber->name;
            $courtName = $drawing->court->name;
            $poolName = $drawing->pool ? ' Pool '.$drawing->pool->name : '';

            $text = "Panggilan untuk kontingen {$contingent}. Atas nama {$athletes}. Silakan menuju {$courtName}. Untuk kategori {$matchName}{$poolName}.";

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
        $existing = EmbuScore::whereIn('match_number_id', $this->matchNumberIds)
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

    public function applyTimerPenalty($timeMs)
    {
        $seconds = floor($timeMs / 1000);
        $denda = 0;

        // Cek kategori dari atlet pertama
        $firstAthlete = $this->matchNumber->athletes->first();
        $isGroup = false;
        if ($firstAthlete) {
            $reg = Registration::find($firstAthlete->pivot->registration_id);
            if ($reg) {
                $isGroup = $reg->is_group;
            }
        }

        if ($isGroup) {
            // Beregu / Pasangan (Target 90s - 120s)
            if ($seconds >= 75 && $seconds <= 89) {
                $denda = 5;
            } elseif ($seconds < 75) {
                $denda = 10;
            } elseif ($seconds >= 121 && $seconds <= 135) {
                $denda = 5;
            } elseif ($seconds > 135) {
                $denda = 10;
            }
        } else {
            // Single / Solo (Target 60s - 90s)
            if ($seconds >= 50 && $seconds <= 59) {
                $denda = 5;
            } elseif ($seconds < 50) {
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

    public function finishMatch($registrationId, $timeMs = 0)
    {
        $drawing = DrawingMatchNumber::whereIn('match_number_id', $this->matchNumberIds)
            ->where('registration_id', $registrationId)
            ->where('round', $this->currentRound)
            ->first();

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
        $calculatedDenda = $this->applyTimerPenalty($timeMs);

        // Save the penalty to the score model immediately
        $score = EmbuScore::firstOrCreate(
            [
                'match_number_id' => $drawing->match_number_id ?? $this->matchNumber->id,
                'registration_id' => $registrationId,
                'round_label' => $this->currentRound,
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
            $total = $judges[1] + $judges[2] + $judges[3]; // Middle 3
        } else {
            $total = array_sum($judges); // If not fully scored yet
        }

        $score->total_score = $total;
        $score->nilai_akhir = max(0, $total - $score->denda);
        $score->save();

        // Close call - Do not clear court state yet, let Panitera close it manually via dismissParticipant
        $this->matchNumber->update(['active_registration_id' => null]);

        $this->dispatch('swal', [
            'icon' => 'success',
            'title' => 'Pertandingan Selesai!',
            'text' => 'Waktu dan denda telah diakumulasi, Panggilan ditutup.',
        ]);
    }

    public function saveScore()
    {
        $judgeValues = array_values($this->scores);
        $scoredCount = count(array_filter($judgeValues, fn ($v) => $v > 0));

        if ($scoredCount === 5) {
            sort($judgeValues);
            $total = $judgeValues[1] + $judgeValues[2] + $judgeValues[3]; // Middle 3
        } else {
            $total = array_sum($judgeValues);
        }

        $nilaiAkhir = max(0, $total - (float) $this->denda);

        $drawing = DrawingMatchNumber::whereIn('match_number_id', $this->matchNumberIds)
            ->where('registration_id', $this->activeRegistrationId)
            ->where('round', $this->currentRound)
            ->first();

        EmbuScore::updateOrCreate(
            [
                'match_number_id' => $drawing->match_number_id ?? $this->matchNumber->id,
                'registration_id' => $this->activeRegistrationId,
                'round_label' => $this->currentRound,
                'drawing_id' => $drawing->id ?? null,
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

    protected function recalculateRanks(): void
    {
        $scores = EmbuScore::whereIn('match_number_id', $this->matchNumberIds)
            ->where('round_label', $this->currentRound)
            ->get();

        if ($this->currentRound === 'Penyisihan') {
            // Penyisihan: higher score is better for ranking
            $sorted = $scores->sort(function ($a, $b) {
                if ($a->nilai_akhir != $b->nilai_akhir) {
                    return $b->nilai_akhir <=> $a->nilai_akhir; // Descending
                }

                return $b->judge_1 <=> $a->judge_1; // Tie-break: Judge 1 Descending
            })->values();
        } else {
            // Final: highest accumulated score = rank 1
            $penyisihanScores = EmbuScore::whereIn('match_number_id', $this->matchNumberIds)
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

                return $b->judge_1 <=> $a->judge_1; // Tie-break: Judge 1 Descending
            })->values();
        }

        foreach ($sorted as $index => $score) {
            $score->update(['rank' => $index + 1]);
        }
    }

    protected function detectTies(): array
    {
        if ($this->currentRound !== 'Penyisihan') {
            return [];
        }

        $scores = EmbuScore::whereIn('match_number_id', $this->matchNumberIds)
            ->where('round_label', 'Penyisihan')
            ->get();

        // Sort identical to recalculateRanks for Penyisihan
        $sorted = $scores->sort(function ($a, $b) {
            if ($a->nilai_akhir != $b->nilai_akhir) {
                return $b->nilai_akhir <=> $a->nilai_akhir;
            }

            return $b->judge_1 <=> $a->judge_1;
        })->values();

        $threshold = $this->getDefaultQualifyThreshold();
        if ($sorted->count() <= $threshold) {
            return [];
        }

        $boundaryScore = $sorted->get($threshold - 1);
        if (! $boundaryScore) {
            return [];
        }

        $tied = $sorted->filter(
            fn ($s) => (float) $s->nilai_akhir === (float) $boundaryScore->nilai_akhir &&
            (float) $s->judge_1 === (float) $boundaryScore->judge_1
        );

        return $tied->count() > 1 ? $tied->pluck('registration_id')->toArray() : [];
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
            $drawing = DrawingMatchNumber::whereIn('match_number_id', $this->matchNumberIds)
                ->where('registration_id', $regId)
                ->where('round', $this->currentRound)
                ->first();

            $targetMatchId = $drawing ? $drawing->match_number_id : $this->matchNumber->id;

            $lastScore = EmbuScore::where('match_number_id', $targetMatchId)
                ->where('registration_id', $regId)
                ->where('round_label', $this->currentRound)
                ->orderByDesc('tiebreak_round')
                ->first();

            $nextTiebreak = ($lastScore->tiebreak_round ?? 0) + 1;

            EmbuScore::create([
                'match_number_id' => $targetMatchId,
                'registration_id' => $regId,
                'round_label' => $this->currentRound,
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
        $penyisihanDrawings = DrawingMatchNumber::whereIn('match_number_id', $this->matchNumberIds)
            ->where('round', 'Penyisihan')
            ->get();

        $byPool = $penyisihanDrawings->groupBy('pool_id');
        $qualifiers = collect();
        $ties = [];

        foreach ($byPool as $poolId => $drawingsInPool) {
            $regIdsInPool = $drawingsInPool->pluck('registration_id');

            $scoresInPool = EmbuScore::whereIn('match_number_id', $this->matchNumberIds)
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
                    'match_number_id' => $score->match_number_id ?? $this->matchNumber->id,
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
        // Fetch all match numbers in this merge/group
        $allMatchNumbers = MatchNumber::whereIn('id', $this->matchNumberIds)->get();

        // Fetch all drawings for the current merge group and round
        $drawingsQuery = DrawingMatchNumber::with(['registration.contingent'])
            ->whereIn('match_number_id', $this->matchNumberIds)
            ->where('round', $this->currentRound);

        if ($this->currentRound === 'Penyisihan' && $this->selectedPoolId) {
            $drawingsQuery->where('pool_id', $this->selectedPoolId);
        }

        $drawingsList = $drawingsQuery->orderBy('sequence_number')->get();
        $validRegIds = $drawingsList->pluck('registration_id')->unique();

        // Fetch all scores for this group in current round
        $allScores = EmbuScore::whereIn('match_number_id', $this->matchNumberIds)
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
                $penyisihanScore = EmbuScore::whereIn('match_number_id', $this->matchNumberIds)
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

            return [
                'id' => $regId,
                'drawing_id' => $drawing->id,
                'match_number_id' => $matchId,
                'match_name' => $drawing->matchNumber?->name ?? '—',
                'is_group' => $registration?->is_group,
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

        // Calculate consolidated display name
        if ($this->merge) {
            $mergedNames = MatchNumber::whereIn('id', $this->matchNumberIds)->pluck('name')->join(', ');
            $this->displayName = ($this->merge->name ?: 'Merged Group').' ('.$mergedNames.')';
        } else {
            $this->displayName = $this->matchNumber->name;
        }

        $courtId = $this->getCourtId();
        $court = $courtId ? Court::find($courtId) : null;
        $activeDrawingId = $court?->active_drawing_id;

        return view('livewire.admin.new-scoring-embu-index', [
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
        $drawings = $this->matchNumber->drawings->where('round', $this->currentRound); // Note: drawings relation should be re-loaded or queried manually if it spans multiple matches

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
                'active_drawing_id' => null,
                'active_match_id' => null,
                'active_bracket_node' => null,
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

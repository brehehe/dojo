<?php

namespace App\Livewire\Admin\Arbitrase\Scoring;

use App\Models\DrawingMatchNumber;
use App\Models\EmbuScore;
use App\Models\MatchNumber\MatchNumber;
use App\Models\Registration;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.admin')]
class AdminArbitraseScoringEmbuDetail extends Component
{
    public MatchNumber $matchNumber;

    public $scores = [];

    public $denda = 0;

    public $activeRegistrationId = null;

    public $showModal = false;

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

        // Detect current round from existing drawings
        $hasFinalists = $this->matchNumber->embuScores
            ->where('round_label', 'Final')
            ->count() > 0;

        $this->currentRound = $hasFinalists ? 'Final' : 'Penyisihan';
    }

    // ─── PANGGIL PESERTA ─────────────────────────────────────

    public function callParticipant($registrationId)
    {
        $this->matchNumber->update(['active_registration_id' => $registrationId]);

        // Sinkronisasi dengan Lapangan agar TV Monitor terupdate
        $drawing = DrawingMatchNumber::with('court')
            ->where('match_number_id', $this->matchNumber->id)
            ->where('registration_id', $registrationId)
            ->where('round', $this->currentRound)
            ->first();

        if ($drawing && $drawing->court_id) {
            $drawing->court->update([
                'active_match_id' => $this->matchNumber->id,
                'active_drawing_id' => $drawing->id,
                'active_registration_id' => $registrationId,
                'active_bracket_node' => null,
            ]);
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
            // Lowest effective score = rank 1 (advances to final)
            $sorted = $scores->sortBy('nilai_akhir')->values();
        } else {
            // Final: highest score = rank 1
            $sorted = $scores->sortByDesc('nilai_akhir')->values();
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
            ->orderBy('nilai_akhir') // lowest first for Penyisihan
            ->get();

        if ($scores->isEmpty()) {
            return [];
        }

        $threshold = $qualifyingThreshold ?? $this->getDefaultQualifyThreshold();

        if ($scores->count() <= $threshold) {
            return []; // no boundary, all qualify
        }

        // The score at the boundary (last qualifying position)
        $boundaryScore = $scores->get($threshold - 1)?->nilai_akhir;

        // Find all who share this exact score (tied at boundary)
        $tied = $scores->filter(fn ($s) => (float) $s->nilai_akhir === (float) $boundaryScore);

        if ($tied->count() <= 1) {
            return []; // not a tie
        }

        return $tied->pluck('registration_id')->toArray();
    }

    protected function getDefaultQualifyThreshold(): int
    {
        // Match drawings determine how many per pool qualify
        $drawing = $this->matchNumber->drawings->first();

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
        $limit = $threshold ?? $this->getDefaultQualifyThreshold();

        $qualifiers = EmbuScore::where('match_number_id', $this->matchNumber->id)
            ->where('round_label', 'Penyisihan')
            ->where('tiebreak_round', 0)
            ->orderBy('nilai_akhir') // lowest first = best rank in penyisihan
            ->take($limit)
            ->get();

        if ($qualifiers->isEmpty()) {
            $this->dispatch('swal', ['icon' => 'warning', 'title' => 'Belum ada nilai penyisihan']);

            return;
        }

        $ties = $this->detectTies($limit);

        if (! empty($ties)) {
            $this->dispatch('swal', [
                'icon' => 'warning',
                'title' => 'Ada Nilai Seri!',
                'text' => 'Terdapat '.count($ties).' peserta dengan nilai yang sama. Lakukan Tanding Ulang terlebih dahulu.',
            ]);

            return;
        }

        // Create Final round DrawingMatchNumbers
        foreach ($qualifiers as $score) {
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

        // Group athletes by registration, but filter to valid registrations for current round
        $validRegIds = DrawingMatchNumber::where('match_number_id', $this->matchNumber->id)
            ->where('round', $this->currentRound)
            ->pluck('registration_id');

        $registrations = $this->matchNumber->athletes
            ->filter(fn ($athlete) => $validRegIds->contains($athlete->pivot->registration_id))
            ->groupBy('pivot.registration_id')
            ->map(function ($athletes, $regId) {
                $registration = Registration::find($regId);

                // Get the latest score for the current round
                $score = $this->matchNumber->embuScores
                    ->where('registration_id', $regId)
                    ->where('round_label', $this->currentRound)
                    ->sortByDesc('tiebreak_round')
                    ->first();

                // All score history for this reg in current round
                $scoreHistory = $this->matchNumber->embuScores
                    ->where('registration_id', $regId)
                    ->where('round_label', $this->currentRound)
                    ->sortBy('tiebreak_round')
                    ->values();

                return [
                    'id' => $regId,
                    'is_group' => $registration?->is_group,
                    'athletes' => $athletes,
                    'contingent' => $registration?->contingent,
                    'score' => $score,
                    'score_history' => $scoreHistory,
                ];
            });

        // For Penyisihan: sort lowest score first (nilai terendah = peringkat terbaik)
        // For Final: sort highest score first
        if ($this->currentRound === 'Penyisihan') {
            $registrations = $registrations
                ->sortBy(fn ($item) => $item['score']?->nilai_akhir ?? PHP_INT_MAX)
                ->values();
        } else {
            $registrations = $registrations
                ->sortByDesc(fn ($item) => $item['score']?->nilai_akhir ?? -1)
                ->values();
        }

        $firstDrawing = $this->matchNumber->drawings->first();
        $tiedIds = $this->detectTies();

        return view('livewire.admin.arbitrase.scoring.admin-arbitrase-scoring-embu-detail', [
            'registrations' => $registrations,
            'firstDrawing' => $firstDrawing,
            'tiedIds' => $tiedIds,
        ]);
    }
}

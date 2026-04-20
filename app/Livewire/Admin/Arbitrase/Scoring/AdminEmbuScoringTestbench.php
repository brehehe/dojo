<?php

namespace App\Livewire\Admin\Arbitrase\Scoring;

use App\Models\DrawingMatchNumber;
use App\Models\EmbuScore;
use App\Models\MatchNumber\MatchNumber;
use App\Models\Registration;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.admin')]
class AdminEmbuScoringTestbench extends Component
{
    // ─── Match selection ──────────────────────────────────────
    public ?int $selectedMatchId = null;

    public string $currentRound = 'Penyisihan';

    // ─── Active participant ───────────────────────────────────
    public ?int $activeRegistrationId = null;

    // ─── Scores per judge (5 judges) ─────────────────────────
    public array $judgeScores = [
        1 => ['goho_1' => 0, 'goho_2' => 0, 'goho_3' => 0, 'juho_1' => 0, 'juho_2' => 0, 'juho_3' => 0, 'ekspresi_1' => 0, 'ekspresi_2' => 0, 'ekspresi_3' => 0, 'ekspresi_4' => 0],
        2 => ['goho_1' => 0, 'goho_2' => 0, 'goho_3' => 0, 'juho_1' => 0, 'juho_2' => 0, 'juho_3' => 0, 'ekspresi_1' => 0, 'ekspresi_2' => 0, 'ekspresi_3' => 0, 'ekspresi_4' => 0],
        3 => ['goho_1' => 0, 'goho_2' => 0, 'goho_3' => 0, 'juho_1' => 0, 'juho_2' => 0, 'juho_3' => 0, 'ekspresi_1' => 0, 'ekspresi_2' => 0, 'ekspresi_3' => 0, 'ekspresi_4' => 0],
        4 => ['goho_1' => 0, 'goho_2' => 0, 'goho_3' => 0, 'juho_1' => 0, 'juho_2' => 0, 'juho_3' => 0, 'ekspresi_1' => 0, 'ekspresi_2' => 0, 'ekspresi_3' => 0, 'ekspresi_4' => 0],
        5 => ['goho_1' => 0, 'goho_2' => 0, 'goho_3' => 0, 'juho_1' => 0, 'juho_2' => 0, 'juho_3' => 0, 'ekspresi_1' => 0, 'ekspresi_2' => 0, 'ekspresi_3' => 0, 'ekspresi_4' => 0],
    ];

    public $denda = 0;

    // ─── Per-judge calculated totals (reactive) ───────────────
    public array $judgeTotals = [1 => 0, 2 => 0, 3 => 0, 4 => 0, 5 => 0];

    public float $finalNilaiAkhir = 0;

    // ─── UI state ─────────────────────────────────────────────
    public int $activeJudge = 1;

    public bool $showRankingPanel = false;

    // ─── LIFECYCLE ────────────────────────────────────────────

    public function mount(): void
    {
        // Pre-select first embu match with drawings
        $first = MatchNumber::where('draft_type', 'embu')
            ->whereHas('athletes')
            ->first();

        if ($first) {
            $this->selectedMatchId = $first->id;
        }
    }

    public function updatedSelectedMatchId(): void
    {
        $this->reset(['activeRegistrationId', 'denda', 'showRankingPanel']);
        $this->resetJudgeScores();
        $this->currentRound = 'Penyisihan';
    }

    public function updatedJudgeScores(): void
    {
        $this->recalculateTotals();
    }

    public function updatedDenda(): void
    {
        $this->recalculateTotals();
    }

    public function incrementDenda(): void
    {
        $this->denda = (float) $this->denda + 1;
        $this->recalculateTotals();
    }

    public function decrementDenda(): void
    {
        $this->denda = max(0, (float) $this->denda - 1);
        $this->recalculateTotals();
    }

    /** Increment or decrement a single score item for a given judge. */
    public function adjustScore(int $judge, string $key, float $delta): void
    {
        $current = (float) ($this->judgeScores[$judge][$key] ?? 0);
        $new = round(max(0, min(10, $current + $delta)), 1);
        $this->judgeScores[$judge][$key] = $new;
        $this->recalculateTotals();
    }

    // ─── SELECT PARTICIPANT ───────────────────────────────────

    public function selectParticipant(int $registrationId): void
    {
        $this->activeRegistrationId = $registrationId;
        $this->denda = 0;
        $this->resetJudgeScores();

        // Load existing scores if any
        if ($this->selectedMatchId) {
            $match = MatchNumber::find($this->selectedMatchId);
            foreach (range(1, 5) as $j) {
                $existing = EmbuScore::where('match_number_id', $this->selectedMatchId)
                    ->where('registration_id', $registrationId)
                    ->where('round_label', $this->currentRound)
                    ->latest()->first();

                if ($existing) {
                    $this->denda = (float) ($existing->denda ?? 0);
                    // Existing per-judge details stored in RefereeScoreDetail
                    // For simplicity, pre-fill each judge equally with judge_N values
                    $val = $existing->{'judge_'.$j} ?? 0;
                    foreach ($this->judgeScores[$j] as $k => $_) {
                        // Distribute score evenly across items (approximation for pre-fill)
                        $this->judgeScores[$j][$k] = 0;
                    }
                }
                break; // Only load once
            }
        }

        $this->recalculateTotals();
        $this->showRankingPanel = false;
    }

    // ─── CALL TO COURT ────────────────────────────────────────

    public function callParticipant(int $registrationId): void
    {
        MatchNumber::find($this->selectedMatchId)
            ?->update(['active_registration_id' => $registrationId]);

        $this->dispatch('swal', [
            'icon' => 'success',
            'title' => 'Peserta Dipanggil',
            'text' => 'Layar wasit sekarang menampilkan form penilaian untuk peserta ini.',
        ]);
    }

    // ─── SCORE CALCULATION ────────────────────────────────────

    private function recalculateTotals(): void
    {
        foreach (range(1, 5) as $j) {
            // Cast each item to float — wire:model sends strings from the browser
            $floatValues = array_map(fn ($v) => (float) $v, $this->judgeScores[$j]);
            $this->judgeTotals[$j] = array_sum($floatValues);
        }
        $this->calculateFinal();
    }

    private function calculateFinal(): void
    {
        $vals = array_values($this->judgeTotals);
        sort($vals);
        // Middle 3 (drop highest and lowest)
        $middle3Sum = $vals[1] + $vals[2] + $vals[3];
        $this->finalNilaiAkhir = max(0, $middle3Sum - (float) $this->denda);
    }

    // ─── SAVE ALL 5 JUDGES ────────────────────────────────────

    public function saveAllScores(): void
    {
        if (! $this->selectedMatchId || ! $this->activeRegistrationId) {
            return;
        }

        $vals = array_values($this->judgeTotals);
        sort($vals);
        $total = $vals[1] + $vals[2] + $vals[3];
        $nilaiAkhir = max(0, $total - (float) $this->denda);

        EmbuScore::updateOrCreate(
            [
                'match_number_id' => $this->selectedMatchId,
                'registration_id' => $this->activeRegistrationId,
                'round_label' => $this->currentRound,
                'tiebreak_round' => 0,
            ],
            [
                'judge_1' => $this->judgeTotals[1],
                'judge_2' => $this->judgeTotals[2],
                'judge_3' => $this->judgeTotals[3],
                'judge_4' => $this->judgeTotals[4],
                'judge_5' => $this->judgeTotals[5],
                'total_score' => $total,
                'denda' => $this->denda,
                'nilai_akhir' => $nilaiAkhir,
            ]
        );

        $this->recalculateRanks();
        $this->showRankingPanel = true;

        $this->dispatch('swal', [
            'icon' => 'success',
            'title' => 'Nilai Disimpan ✓',
            'text' => 'Total (3 tengah): '.number_format($total, 1).' | Nilai Akhir: '.number_format($nilaiAkhir, 1),
        ]);
    }

    // ─── ADVANCE TO FINAL ─────────────────────────────────────

    public function advanceToFinal(): void
    {
        $this->currentRound = 'Final';
        $this->reset(['activeRegistrationId']);
        $this->resetJudgeScores();
        $this->showRankingPanel = false;

        $this->dispatch('swal', [
            'icon' => 'info',
            'title' => 'Babak Final Dimulai',
            'text' => 'Pilih peserta yang lolos untuk dinilai pada babak Final.',
        ]);
    }

    public function backToPenyisihan(): void
    {
        $this->currentRound = 'Penyisihan';
        $this->reset(['activeRegistrationId']);
        $this->resetJudgeScores();
        $this->showRankingPanel = false;
    }

    // ─── RANKING ─────────────────────────────────────────────

    private function recalculateRanks(): void
    {
        $scores = EmbuScore::where('match_number_id', $this->selectedMatchId)
            ->where('round_label', $this->currentRound)
            ->get();

        $sorted = $this->currentRound === 'Penyisihan'
            ? $scores->sortBy('nilai_akhir')->values()
            : $scores->sortByDesc('nilai_akhir')->values();

        foreach ($sorted as $idx => $score) {
            $score->update(['rank' => $idx + 1]);
        }
    }

    // ─── HELPERS ─────────────────────────────────────────────

    private function resetJudgeScores(): void
    {
        $blank = ['goho_1' => 0, 'goho_2' => 0, 'goho_3' => 0, 'juho_1' => 0, 'juho_2' => 0, 'juho_3' => 0, 'ekspresi_1' => 0, 'ekspresi_2' => 0, 'ekspresi_3' => 0, 'ekspresi_4' => 0];
        $this->judgeScores = [1 => $blank, 2 => $blank, 3 => $blank, 4 => $blank, 5 => $blank];
        $this->judgeTotals = [1 => 0, 2 => 0, 3 => 0, 4 => 0, 5 => 0];
        $this->finalNilaiAkhir = 0;
        $this->activeJudge = 1;
    }

    // ─── RENDER ──────────────────────────────────────────────

    public function render()
    {
        $embuMatches = MatchNumber::where('draft_type', 'embu')
            ->whereHas('athletes')
            ->with('ageGroup')
            ->orderBy('name')
            ->get();

        $match = $this->selectedMatchId
            ? MatchNumber::with(['athletes', 'embuScores', 'ageGroup'])->find($this->selectedMatchId)
            : null;

        $registrations = collect();
        if ($match) {
            $validRegIds = DrawingMatchNumber::where('match_number_id', $this->selectedMatchId)
                ->where('round', $this->currentRound)
                ->pluck('registration_id');

            $registrations = $match->athletes
                ->filter(fn ($athlete) => $validRegIds->contains($athlete->pivot->registration_id))
                ->groupBy('pivot.registration_id')
                ->map(function ($athletes, $regId) use ($match) {
                    $registration = Registration::find($regId);
                    $score = $match->embuScores
                        ->where('registration_id', $regId)
                        ->where('round_label', $this->currentRound)
                        ->sortByDesc('tiebreak_round')
                        ->first();

                    return [
                        'id' => $regId,
                        'athletes' => $athletes,
                        'contingent' => $registration?->contingent,
                        'is_group' => $registration?->is_group,
                        'score' => $score,
                    ];
                });

            // Sort by nilai_akhir
            $registrations = $this->currentRound === 'Penyisihan'
                ? $registrations->sortBy(fn ($r) => $r['score']?->nilai_akhir ?? PHP_INT_MAX)->values()
                : $registrations->sortByDesc(fn ($r) => $r['score']?->nilai_akhir ?? -1)->values();
        }

        return view('livewire.admin.arbitrase.scoring.admin-embu-scoring-testbench', [
            'embuMatches' => $embuMatches,
            'match' => $match,
            'registrations' => $registrations,
        ]);
    }
}

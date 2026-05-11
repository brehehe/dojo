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

        $match = MatchNumber::with(['athletes', 'embuScores'])->find($this->selectedMatchId);
        if (! $match) {
            return collect();
        }

        $drawings = DrawingMatchNumber::with('pool')
            ->where('match_number_id', $this->selectedMatchId)
            ->where('round', 'Penyisihan')
            ->get()
            ->keyBy('registration_id');

        $participants = $match->athletes
            ->groupBy('pivot.registration_id')
            ->map(function ($athletes, $regId) use ($match, $drawings) {
                $reg = Registration::with('contingent')->find($regId);

                $score = $match->embuScores
                    ->where('registration_id', $regId)
                    ->where('round_label', 'Penyisihan')
                    ->where('tiebreak_round', 0)
                    ->first();

                $tiebreakScore = $match->embuScores
                    ->where('registration_id', $regId)
                    ->where('round_label', 'Penyisihan')
                    ->where('tiebreak_round', '>', 0)
                    ->sortByDesc('tiebreak_round')
                    ->first();

                return [
                    'id' => $regId,
                    'pool_id' => $drawings[$regId]?->pool_id ?? 0,
                    'pool_name' => $drawings[$regId]?->pool?->name ?? 'No Pool',
                    'athletes' => $athletes,
                    'contingent' => $reg?->contingent,
                    'score' => $score,
                    'tiebreak_score' => $tiebreakScore,
                    'effective_score' => $tiebreakScore ?? $score,
                ];
            })
            ->values();

        // Sort: 1. Nilai Akhir (DESC), 2. Wasit Utama / judge_1 (DESC)
        $sorted = $participants->sort(function ($a, $b) {
            $naA = (float) ($a['effective_score']?->nilai_akhir ?? -1);
            $naB = (float) ($b['effective_score']?->nilai_akhir ?? -1);
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

        $existingFinals = DrawingMatchNumber::where('match_number_id', $this->selectedMatchId)
            ->where('round', 'Final')
            ->orderBy('sequence_number')
            ->get();

        $usedIds = [];
        foreach ($qualifiers->values() as $seq => $reg) {
            $existing = $existingFinals->where('sequence_number', $seq + 1)->first();

            if ($existing) {
                $meta = $existing->metadata;
                $meta['contingent'] = $reg['contingent']?->name ?? 'Unknown';
                $meta['athlete_name'] = $reg['athletes']->pluck('name')->implode(', ');

                $existing->update([
                    'registration_id' => $reg['id'],
                    'metadata' => $meta,
                ]);
                $usedIds[] = $existing->id;
            } else {
                $newRecord = DrawingMatchNumber::create([
                    'match_number_id' => $this->selectedMatchId,
                    'registration_id' => $reg['id'],
                    'round' => 'Final',
                    'draft_type' => 'embu',
                    'sequence_number' => $seq + 1,
                    'court_id' => $this->finalCourtId,
                    'pool_id' => $this->finalPoolId,
                    'session_time_id' => $this->finalSessionTimeId,
                    'rundown_id' => $this->finalRundownId,
                    'schedule_date' => $this->finalScheduleDate,
                ]);
                $usedIds[] = $newRecord->id;
            }
        }

        // Clean up any remaining unused TBD final slots
        DrawingMatchNumber::where('match_number_id', $this->selectedMatchId)
            ->where('round', 'Final')
            ->whereNotIn('id', $usedIds)
            ->delete();

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
        TournamentResult::where('match_number_id', $this->selectedMatchId)->delete();

        foreach ($rankings as $idx => $reg) {
            $rank = $idx + 1;

            // Simpan sampai peringkat 4 saja untuk Embu (Juara 1, 2, 3, 3 Bersama)
            if ($rank > 4) {
                break;
            }

            EmbuChampion::create([
                'match_number_id' => $this->selectedMatchId,
                'registration_id' => $reg['id'],
                'rank' => $rank,
                'penyisihan_score' => $reg['penyisihan_score']?->nilai_akhir ?? 0,
                'final_score' => $reg['final_score']?->nilai_akhir ?? 0,
                'accumulated_score' => $reg['accumulated'] ?? 0,
            ]);

            $athleteNames = $reg['athletes']->pluck('name')->implode(', ');
            $contingentName = $reg['contingent']?->name ?? '-';

            TournamentResult::create([
                'match_number_id' => $this->selectedMatchId,
                'draft_type' => 'embu',
                'rank' => $rank,
                'registration_id' => $reg['id'],
                'athlete_names' => $athleteNames,
                'contingent_name' => $contingentName,
                'penyisihan_score' => $reg['penyisihan_score']?->nilai_akhir ?? 0,
                'final_score' => $reg['final_score']?->nilai_akhir ?? 0,
                'accumulated_score' => $reg['accumulated'] ?? 0,
                'generated_by' => Auth::user()?->name ?? 'System',
                'confirmed_at' => now(),
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
            ->whereHas('drawings')
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

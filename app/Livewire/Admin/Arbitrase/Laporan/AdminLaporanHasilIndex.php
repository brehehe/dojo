<?php

namespace App\Livewire\Admin\Arbitrase\Laporan;

use App\Models\EmbuChampion;
use App\Models\Group\AgeGroup;
use App\Models\MatchNumber\MatchNumber;
use App\Models\TournamentResult;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('layouts.admin')]
class AdminLaporanHasilIndex extends Component
{
    use WithPagination;

    public string $search = '';

    public string $draftTypeFilter = '';

    public string $ageGroupFilter = '';

    public bool $showConfirmModal = false;

    public ?int $generateMatchId = null;

    public string $generateMatchName = '';

    protected $queryString = [
        'search' => ['except' => ''],
        'draftTypeFilter' => ['except' => ''],
        'ageGroupFilter' => ['except' => ''],
    ];

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function updatingDraftTypeFilter(): void
    {
        $this->resetPage();
    }

    public function updatingAgeGroupFilter(): void
    {
        $this->resetPage();
    }

    // ─── EMBU JUARA CALCULATION ──────────────────────────────────

    private function computeEmbuJuara(MatchNumber $matchNumber): array
    {
        // First: use confirmed EmbuChampion records (already validated by admin)
        $confirmed = EmbuChampion::where('match_number_id', $matchNumber->id)
            ->with(['registration' => fn ($q) => $q->with(['athletes', 'contingent'])])
            ->whereIn('rank', [1, 2, 3, 4])
            ->orderBy('rank')
            ->get();

        if ($confirmed->isNotEmpty()) {
            // Load MatchNumber athletes with pivot (registration_id) to filter specific competing athletes
            $matchNumber->loadMissing('athletes');

            $juara = [];
            foreach ($confirmed as $champ) {
                // Filter only the specific athletes who competed in this match under this registration
                $competingAthletes = $matchNumber->athletes
                    ->filter(fn ($a) => $a->pivot->registration_id == $champ->registration_id)
                    ->pluck('name')
                    ->join(' & ');

                $contingent = $champ->registration?->contingent?->name ?? '-';

                $juara[$champ->rank] = [
                    'registration_id' => $champ->registration_id,
                    'athlete_names' => $competingAthletes ?: '-',
                    'contingent_name' => $contingent,
                    'penyisihan_score' => (float) $champ->penyisihan_score,
                    'final_score' => (float) $champ->final_score,
                    'accumulated_score' => (float) $champ->accumulated_score,
                ];
            }

            return $juara;
        }

        // Fallback: compute from raw scores if no champions confirmed yet
        $hasFinal = $matchNumber->embuScores()->where('round_label', 'Final')->exists();
        $round = $hasFinal ? 'Final' : 'Penyisihan';

        $scores = $matchNumber->embuScores()
            ->with(['registration.athletes', 'registration.contingent'])
            ->where('round_label', $round)
            ->where('tiebreak_round', 0)
            ->get();

        if ($scores->isEmpty()) {
            return [];
        }

        if ($round === 'Final') {
            $penyisihanMap = $matchNumber->embuScores()
                ->where('round_label', 'Penyisihan')
                ->where('tiebreak_round', 0)
                ->get()
                ->keyBy('registration_id');

            $ranked = $scores->map(function ($score) use ($penyisihanMap) {
                $pScore = $penyisihanMap[$score->registration_id] ?? null;
                $score->penyisihan_val = $pScore ? (float) $pScore->nilai_akhir : 0;
                $score->final_val = (float) $score->nilai_akhir;
                $score->accumulated = $score->penyisihan_val + $score->final_val;

                return $score;
            })->sortByDesc('accumulated')->values();
        } else {
            $ranked = $scores->map(function ($score) {
                $score->penyisihan_val = (float) $score->nilai_akhir;
                $score->final_val = 0;
                $score->accumulated = $score->penyisihan_val;

                return $score;
            })->sortByDesc('accumulated')->values();
        }

        $juara = [];
        foreach ($ranked as $idx => $score) {
            $rankNum = $idx + 1;
            if ($rankNum > 4) {
                break;
            }

            $reg = $score->registration;
            $juara[$rankNum] = [
                'registration_id' => $score->registration_id,
                'athlete_names' => $reg?->athletes->pluck('name')->join(' & ') ?? '-',
                'contingent_name' => $reg?->contingent?->name ?? '-',
                'penyisihan_score' => $score->penyisihan_val,
                'final_score' => $score->final_val,
                'accumulated_score' => $score->accumulated,
            ];
        }

        return $juara;
    }

    // ─── RANDORI JUARA FROM DRAWING DATA ─────────────────────────

    private function computeRandoriJuara(MatchNumber $matchNumber): array
    {
        $data = $matchNumber->drawing_data ?? [];
        $juaraRaw = $data['juara'] ?? [];

        $juara = [];
        foreach ($juaraRaw as $rank => $j) {
            if (! $j || empty($j['name'])) {
                continue;
            }

            // drawing_data['juara'] already stores the individual winner name & contingent
            // Note: $j['id'] is athlete_id, NOT registration_id — store null to avoid FK violation
            $juara[(int) $rank] = [
                'registration_id' => null,
                'athlete_names' => $j['name'],
                'contingent_name' => $j['contingent'] ?? '-',
                'penyisihan_score' => 0,
                'final_score' => 0,
                'accumulated_score' => 0,
            ];
        }

        return $juara;
    }

    // ─── GET JUARA (FOR DISPLAY) ──────────────────────────────────

    public function getJuaraForMatch(MatchNumber $matchNumber): array
    {
        return strtolower($matchNumber->draft_type) === 'embu'
            ? $this->computeEmbuJuara($matchNumber)
            : $this->computeRandoriJuara($matchNumber);
    }

    // ─── OPEN CONFIRM MODAL ───────────────────────────────────────

    public function openGenerateModal(int $matchId, string $matchName): void
    {
        $this->generateMatchId = $matchId;
        $this->generateMatchName = $matchName;
        $this->showConfirmModal = true;
    }

    // ─── GENERATE SINGLE MATCH ───────────────────────────────────

    public function generateSingleResult(): void
    {
        if (! $this->generateMatchId) {
            return;
        }

        $matchNumber = MatchNumber::with(['embuScores', 'athletes'])->find($this->generateMatchId);
        if (! $matchNumber) {
            return;
        }

        $this->saveResultForMatch($matchNumber);

        $this->showConfirmModal = false;
        $this->generateMatchId = null;
        $this->generateMatchName = '';

        $this->dispatch('swal', [
            'icon' => 'success',
            'title' => '✅ Berhasil!',
            'text' => 'Hasil pertandingan berhasil disimpan ke database.',
        ]);
    }

    // ─── GENERATE ALL RESULTS ────────────────────────────────────

    public function generateAllResults(): void
    {
        $matchNumbers = MatchNumber::with(['embuScores', 'athletes'])->get();
        $count = 0;

        foreach ($matchNumbers as $matchNumber) {
            $saved = $this->saveResultForMatch($matchNumber);
            if ($saved) {
                $count++;
            }
        }

        $this->dispatch('swal', [
            'icon' => 'success',
            'title' => '✅ Generate Selesai!',
            'text' => "{$count} nomor pertandingan berhasil disimpan.",
        ]);
    }

    // ─── CORE SAVE LOGIC ─────────────────────────────────────────

    private function saveResultForMatch(MatchNumber $matchNumber): bool
    {
        $juara = strtolower($matchNumber->draft_type) === 'embu'
            ? $this->computeEmbuJuara($matchNumber)
            : $this->computeRandoriJuara($matchNumber);

        if (empty($juara)) {
            return false;
        }

        // Delete old results for this match
        TournamentResult::where('match_number_id', $matchNumber->id)->delete();

        foreach ($juara as $rank => $data) {
            TournamentResult::create([
                'match_number_id' => $matchNumber->id,
                'draft_type' => $matchNumber->draft_type,
                'rank' => $rank,
                'registration_id' => $data['registration_id'] ?? null,
                'athlete_names' => $data['athlete_names'],
                'contingent_name' => $data['contingent_name'],
                'penyisihan_score' => $data['penyisihan_score'],
                'final_score' => $data['final_score'],
                'accumulated_score' => $data['accumulated_score'],
                'generated_by' => Auth::user()?->name ?? 'System',
                'confirmed_at' => now(),
            ]);
        }

        return true;
    }

    // ─── RENDER ──────────────────────────────────────────────────

    public function render()
    {
        $ageGroups = AgeGroup::orderBy('order')->get();

        $matchNumbers = MatchNumber::with(['ageGroup', 'embuScores', 'athletes', 'tournamentResults'])
            ->when($this->search, fn ($q) => $q->where('name', 'like', '%'.$this->search.'%'))
            ->when($this->draftTypeFilter, fn ($q) => $q->where('draft_type', $this->draftTypeFilter))
            ->when($this->ageGroupFilter, fn ($q) => $q->where('age_group_id', $this->ageGroupFilter))
            ->orderBy('draft_type')
            ->orderBy('age_group_id')
            ->orderBy('order')
            ->paginate(12);

        // Attach computed juara + saved status for each match
        $matchNumbers->getCollection()->transform(function ($matchNumber) {
            $matchNumber->computed_juara = $this->getJuaraForMatch($matchNumber);
            $matchNumber->saved_results = $matchNumber->tournamentResults->keyBy('rank');

            return $matchNumber;
        });

        return view('livewire.admin.arbitrase.laporan.admin-laporan-hasil-index', [
            'matchNumbers' => $matchNumbers,
            'ageGroups' => $ageGroups,
        ]);
    }
}

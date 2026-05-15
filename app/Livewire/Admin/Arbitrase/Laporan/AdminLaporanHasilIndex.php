<?php

namespace App\Livewire\Admin\Arbitrase\Laporan;

use App\Models\EmbuChampion;
use App\Models\Group\AgeGroup;
use App\Models\MatchNumber\MatchNumber;
use App\Models\TournamentResult;
use App\Traits\HasExcelExport;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('layouts.admin')]
class AdminLaporanHasilIndex extends Component
{
    use HasExcelExport, WithPagination;

    public string $search = '';

    public string $draftTypeFilter = '';

    public string $ageGroupFilter = '';

    public string $matchNumberFilter = '';

    public string $genderFilter = '';

    public string $hasWinnersFilter = ''; // 'all', 'yes', 'no'

    public bool $showConfirmModal = false;

    public ?int $generateMatchId = null;

    public string $generateMatchName = '';

    protected $queryString = [
        'search' => ['except' => ''],
        'draftTypeFilter' => ['except' => ''],
        'ageGroupFilter' => ['except' => ''],
        'matchNumberFilter' => ['except' => ''],
        'genderFilter' => ['except' => ''],
        'hasWinnersFilter' => ['except' => ''],
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

    public function updatingMatchNumberFilter(): void
    {
        $this->resetPage();
    }

    public function updatingGenderFilter(): void
    {
        $this->resetPage();
    }

    public function updatingHasWinnersFilter(): void
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
        $matchNumber->loadMissing('athletes');
        foreach ($ranked as $idx => $score) {
            $rankNum = $idx + 1;
            if ($rankNum > 4) {
                break;
            }

            $reg = $score->registration;
            $competingAthletes = $matchNumber->athletes
                ->filter(fn ($a) => $a->pivot->registration_id == $score->registration_id)
                ->pluck('name')
                ->join(' & ');

            $juara[$rankNum] = [
                'registration_id' => $score->registration_id,
                'athlete_names' => $competingAthletes ?: ($reg?->athletes->pluck('name')->join(' & ') ?? '-'),
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
        $matchIds = [$matchNumber->id];
        $mergeDetails = DB::table('match_number_merge_details')
            ->where('match_number_id', $matchNumber->id)
            ->first();

        if ($mergeDetails) {
            $matchIds = DB::table('match_number_merge_details')
                ->where('match_number_merge_id', $mergeDetails->match_number_merge_id)
                ->pluck('match_number_id')
                ->toArray();
        }

        // 1. Check for confirmed champions in these matches
        $confirmed = EmbuChampion::whereIn('match_number_id', $matchIds)
            ->with(['registration.athletes', 'registration.contingent', 'matchNumber.athletes'])
            ->whereIn('rank', [1, 2, 3, 4])
            ->orderBy('rank')
            ->get();

        if ($confirmed->isNotEmpty()) {
            $juara = [];
            foreach ($confirmed as $champ) {
                // Try to get specific athletes for this match+reg
                $athletes = $champ->matchNumber?->athletes?->filter(fn($a) => $a->pivot->registration_id == $champ->registration_id)->unique('id') ?? collect();
                $athleteNames = $athletes->pluck('name')->join(' & ');
                
                // Fallback to all registration athletes if not specific
                if (empty($athleteNames)) {
                    $athleteNames = $champ->registration?->athletes?->unique('id')->pluck('name')->join(' & ') ?? '-';
                }

                $juara[$champ->rank] = [
                    'registration_id' => $champ->registration_id,
                    'athlete_names' => $athleteNames,
                    'contingent_name' => $champ->registration?->contingent?->name ?? '-',
                    'penyisihan_score' => (float) $champ->penyisihan_score,
                    'final_score' => (float) $champ->final_score,
                    'accumulated_score' => (float) $champ->accumulated_score,
                ];
            }
            return $juara;
        }

        // 2. Fallback to raw computation
        if (strtolower($matchNumber->draft_type) === 'embu') {
            return $this->computeMergedEmbuJuara($matchIds);
        } else {
            return $this->computeRandoriJuara($matchNumber);
        }
    }

    private function computeMergedEmbuJuara(array $matchNumberIds): array
    {
        $hasFinal = \App\Models\EmbuScore::whereIn('match_number_id', $matchNumberIds)->where('round_label', 'Final')->exists();
        $round = $hasFinal ? 'Final' : 'Penyisihan';

        $scores = \App\Models\EmbuScore::with(['registration.athletes', 'registration.contingent'])
            ->whereIn('match_number_id', $matchNumberIds)
            ->where('round_label', $round)
            ->where('tiebreak_round', 0)
            ->get();

        if ($scores->isEmpty()) {
            return [];
        }

        if ($round === 'Final') {
            $penyisihanMap = \App\Models\EmbuScore::whereIn('match_number_id', $matchNumberIds)
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
            })->sort(function ($a, $b) {
                if ($a->accumulated !== $b->accumulated) {
                    return $b->accumulated <=> $a->accumulated;
                }
                $j1A = (float) ($a->final_val ? $a->judge_1 : ($a->penyisihan_val ? $a->judge_1 : 0));
                $j1B = (float) ($b->final_val ? $b->judge_1 : ($b->penyisihan_val ? $b->judge_1 : 0));
                return $j1B <=> $j1A;
            })->values();
        } else {
            $ranked = $scores->map(function ($score) {
                $score->penyisihan_val = (float) $score->nilai_akhir;
                $score->final_val = 0;
                $score->accumulated = $score->penyisihan_val;
                return $score;
            })->sort(function ($a, $b) {
                if ($a->accumulated !== $b->accumulated) {
                    return $b->accumulated <=> $a->accumulated;
                }
                $j1A = (float) $a->judge_1;
                $j1B = (float) $b->judge_1;
                return $j1B <=> $j1A;
            })->values();
        }

        $juara = [];
        foreach ($ranked as $idx => $score) {
            $rankNum = $idx + 1;
            if ($rankNum > 4) break;

            $reg = $score->registration;
            $juara[$rankNum] = [
                'registration_id' => $score->registration_id,
                'athlete_names' => $reg?->athletes->unique('id')->pluck('name')->join(' & ') ?? '-',
                'contingent_name' => $reg?->contingent?->name ?? '-',
                'penyisihan_score' => $score->penyisihan_val,
                'final_score' => $score->final_val,
                'accumulated_score' => $score->accumulated,
            ];
        }

        return $juara;
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
        $juara = $this->getJuaraForMatch($matchNumber);

        if (empty($juara)) {
            return false;
        }

        $matchNumberIds = [$matchNumber->id];
        $mergeDetails = DB::table('match_number_merge_details')
            ->where('match_number_id', $matchNumber->id)
            ->first();

        if ($mergeDetails) {
            $matchNumberIds = DB::table('match_number_merge_details')
                ->where('match_number_merge_id', $mergeDetails->match_number_merge_id)
                ->pluck('match_number_id')
                ->toArray();
        }

        // Delete old results for all affected matches
        TournamentResult::whereIn('match_number_id', $matchNumberIds)->delete();

        foreach ($matchNumberIds as $mid) {
            $mn = ($mid == $matchNumber->id) ? $matchNumber : MatchNumber::find($mid);
            if (!$mn) continue;

            foreach ($juara as $rank => $data) {
                TournamentResult::create([
                    'match_number_id' => $mn->id,
                    'draft_type' => $mn->draft_type,
                    'rank' => $rank,
                    'registration_id' => $data['registration_id'] ?? null,
                    'athlete_names' => $data['athlete_names'],
                    'contingent_name' => $data['contingent_name'],
                    'penyisihan_score' => $data['penyisihan_score'] ?? 0,
                    'final_score' => $data['final_score'] ?? 0,
                    'accumulated_score' => $data['accumulated_score'] ?? 0,
                    'generated_by' => Auth::user()?->name ?? 'System',
                    'confirmed_at' => now(),
                ]);
            }
        }

        return true;
    }

    // ─── RENDER ──────────────────────────────────────────────────

    public function exportExcel()
    {
        $matchNumbers = MatchNumber::with(['ageGroup', 'tournamentResults'])
            ->when($this->search, fn ($q) => $q->where('name', 'ilike', '%'.$this->search.'%'))
            ->when($this->draftTypeFilter, fn ($q) => $q->where('draft_type', $this->draftTypeFilter))
            ->when($this->ageGroupFilter, fn ($q) => $q->where('age_group_id', $this->ageGroupFilter))
            ->when($this->matchNumberFilter, fn ($q) => $q->where('id', $this->matchNumberFilter))
            ->when($this->genderFilter, fn ($q) => $q->where('gender', $this->genderFilter))
            ->when($this->hasWinnersFilter === 'yes', fn ($q) => $q->has('tournamentResults'))
            ->when($this->hasWinnersFilter === 'no', fn ($q) => $q->doesntHave('tournamentResults'))
            ->get();

        $exportData = [];
        foreach ($matchNumbers as $mn) {
            $juara = $this->getJuaraForMatch($mn);
            if (empty($juara)) {
                $exportData[] = [
                    'Nomor Pertandingan' => $mn->name,
                    'Tipe' => $mn->draft_type,
                    'Kategori' => $mn->ageGroup->name ?? '-',
                    'Gender' => $mn->gender,
                    'Rank' => '-',
                    'Atlet' => 'Belum ada data',
                    'Kontingen' => '-',
                    'Penyisihan' => 0,
                    'Final' => 0,
                    'Total' => 0,
                ];

                continue;
            }

            foreach ($juara as $rank => $data) {
                $exportData[] = [
                    'Nomor Pertandingan' => $mn->name,
                    'Tipe' => $mn->draft_type,
                    'Kategori' => $mn->ageGroup->name ?? '-',
                    'Gender' => $mn->gender,
                    'Rank' => $rank,
                    'Atlet' => $data['athlete_names'],
                    'Kontingen' => $data['contingent_name'],
                    'Penyisihan' => $data['penyisihan_score'],
                    'Final' => $data['final_score'],
                    'Total' => $data['accumulated_score'],
                ];
            }
        }

        return $this->downloadExcel(
            $exportData,
            ['Nomor Pertandingan', 'Tipe', 'Kategori', 'Gender', 'Rank', 'Atlet', 'Kontingen', 'Penyisihan', 'Final', 'Total'],
            'Laporan_Hasil_Juara',
            'Hasil Juara'
        );
    }

    public function render()
    {
        $ageGroups = AgeGroup::orderBy('order')->get();
        $allMatchNumbers = MatchNumber::leftJoin('match_number_merge_details', 'match_numbers.id', '=', 'match_number_merge_details.match_number_id')
            ->leftJoin('match_number_merges', 'match_number_merge_details.match_number_merge_id', '=', 'match_number_merges.id')
            ->where(function($q) {
                $q->whereNull('match_number_merge_details.match_number_merge_id')
                  ->orWhereRaw('match_numbers.id = (SELECT MIN(m2.match_number_id) FROM match_number_merge_details m2 WHERE m2.match_number_merge_id = match_number_merge_details.match_number_merge_id)');
            })
            ->select('match_numbers.*', 'match_number_merges.name as merge_group_name', 'match_number_merge_details.match_number_merge_id')
            ->orderBy('match_numbers.name')
            ->get()
            ->map(function($m) {
                if ($m->match_number_merge_id) {
                    $mergedNames = \Illuminate\Support\Facades\DB::table('match_number_merge_details')
                        ->join('match_numbers', 'match_number_merge_details.match_number_id', '=', 'match_numbers.id')
                        ->where('match_number_merge_details.match_number_merge_id', $m->match_number_merge_id)
                        ->pluck('match_numbers.name')
                        ->join(', ');
                    $m->display_name = ($m->merge_group_name ?: 'Merged Group') . " (" . $mergedNames . ")";
                } else {
                    $m->display_name = $m->name;
                }
                return $m;
            });

        $query = MatchNumber::with(['ageGroup', 'embuScores', 'athletes', 'tournamentResults'])
            ->leftJoin('match_number_merge_details', 'match_numbers.id', '=', 'match_number_merge_details.match_number_id')
            ->leftJoin('match_number_merges', 'match_number_merge_details.match_number_merge_id', '=', 'match_number_merges.id')
            ->select('match_numbers.*', 'match_number_merges.name as merge_group_name', 'match_number_merge_details.match_number_merge_id')
            ->where(function($q) {
                $q->whereNull('match_number_merge_details.match_number_merge_id')
                  ->orWhereRaw('match_numbers.id = (SELECT MIN(m2.match_number_id) FROM match_number_merge_details m2 WHERE m2.match_number_merge_id = match_number_merge_details.match_number_merge_id)');
            });

        if ($this->search) {
            $query->where(function($q) {
                $q->where('match_numbers.name', 'ilike', '%'.$this->search.'%')
                  ->orWhere('match_number_merges.name', 'ilike', '%'.$this->search.'%');
            });
        }

        if ($this->draftTypeFilter) $query->where('match_numbers.draft_type', $this->draftTypeFilter);
        if ($this->ageGroupFilter) $query->where('match_numbers.age_group_id', $this->ageGroupFilter);
        if ($this->matchNumberFilter) $query->where('match_numbers.id', $this->matchNumberFilter);
        if ($this->genderFilter) $query->where('match_numbers.gender', $this->genderFilter);

        if ($this->hasWinnersFilter === 'yes') $query->has('tournamentResults');
        if ($this->hasWinnersFilter === 'no') $query->doesntHave('tournamentResults');

        $matchNumbers = $query->orderBy('match_numbers.draft_type')
            ->orderBy('match_numbers.age_group_id')
            ->orderBy('match_numbers.order')
            ->paginate(12);

        // Attach computed juara + saved status for each match
        $matchNumbers->getCollection()->transform(function ($matchNumber) {
            $matchNumber->computed_juara = $this->getJuaraForMatch($matchNumber);
            $matchNumber->saved_results = $matchNumber->tournamentResults->keyBy('rank');
            
            if ($matchNumber->match_number_merge_id) {
                $mergedNames = DB::table('match_number_merge_details')
                    ->join('match_numbers', 'match_number_merge_details.match_number_id', '=', 'match_numbers.id')
                    ->where('match_number_merge_details.match_number_merge_id', $matchNumber->match_number_merge_id)
                    ->pluck('match_numbers.name')
                    ->join(', ');
                
                $matchNumber->display_name = ($matchNumber->merge_group_name ?: 'Merged Group') . " (" . $mergedNames . ")";
            } else {
                $matchNumber->display_name = $matchNumber->name;
            }

            return $matchNumber;
        });

        return view('livewire.admin.arbitrase.laporan.admin-laporan-hasil-index', [
            'matchNumbers' => $matchNumbers,
            'ageGroups' => $ageGroups,
            'allMatchNumbersDropdown' => $allMatchNumbers,
        ]);
    }
}

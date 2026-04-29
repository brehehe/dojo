<?php

namespace App\Livewire\Admin\Arbitrase\Laporan;

use App\Models\DrawingMatchNumber;
use App\Models\Group\AgeGroup;
use App\Models\MatchNumber\MatchNumber;
use App\Models\RandoriMatchResult;
use App\Traits\HasExcelExport;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('layouts.admin')]
class AdminLaporanRekapitulasiRandori extends Component
{
    use HasExcelExport, WithPagination;

    public string $search = '';

    public string $ageGroupFilter = '';

    public string $genderFilter = '';

    public string $matchNumberFilter = '';

    protected $queryString = [
        'search' => ['except' => ''],
        'ageGroupFilter' => ['except' => ''],
        'genderFilter' => ['except' => ''],
        'matchNumberFilter' => ['except' => ''],
    ];

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function updatingAgeGroupFilter(): void
    {
        $this->resetPage();
    }

    public function updatingGenderFilter(): void
    {
        $this->resetPage();
    }

    public function updatingMatchNumberFilter(): void
    {
        $this->resetPage();
    }

    private function getRoundLabel(string $nodeKey, MatchNumber $matchNumber): string
    {
        $parts = explode('_', $nodeKey);
        $bracket = $parts[0];
        $roundIdx = (int) ($parts[1] ?? 0);

        if ($bracket === 'gf') {
            return 'Final';
        }

        // Simple logic for labels
        $totalRounds = count($matchNumber->drawing_data['upper_bracket']['rounds'] ?? []);

        if ($bracket === 'ub') {
            $diff = $totalRounds - 1 - $roundIdx;

            return match ($diff) {
                0 => 'Semi Final',
                1 => 'Perempat Final',
                2 => 'Babak 16 Besar',
                default => 'Penyisihan',
            };
        }

        return 'Repechage';
    }

    public function callMatch($matchNumberId, $bracketNode)
    {
        $matchNumber = MatchNumber::findOrFail($matchNumberId);
        $matchNumber->update(['active_bracket_node' => $bracketNode]);

        // Sync with Court
        $drawing = DrawingMatchNumber::with('court')
            ->where('match_number_id', $matchNumberId)
            ->first();

        if ($drawing && $drawing->court) {
            $drawing->court->update([
                'active_match_id' => $matchNumberId,
                'active_drawing_id' => $drawing->id,
                'active_registration_id' => null,
                'active_bracket_node' => $bracketNode,
            ]);

            // Announcement
            $drawingData = $matchNumber->drawing_data ?? [];
            $nodeParts = explode('_', $bracketNode);
            $bracket = $nodeParts[0];
            $roundIdx = (int) ($nodeParts[1] ?? 0);
            $matchIdx = (int) ($nodeParts[2] ?? 0);

            $targetBracket = $bracket === 'ub' ? 'upper_bracket' : ($bracket === 'lb' ? 'lower_bracket' : 'grand_final');

            if ($targetBracket === 'grand_final') {
                $matchInfo = $drawingData['grand_final'] ?? null;
            } else {
                $matchInfo = $drawingData[$targetBracket]['rounds'][$roundIdx][$matchIdx] ?? null;
            }

            if ($matchInfo) {
                $aka = $matchInfo['athlete1']['name'] ?? '-';
                $shiro = $matchInfo['athlete2']['name'] ?? '-';
                $text = "Panggilan untuk partai berikutnya. Di sudut Merah {$aka}, melawan sudut Putih {$shiro}. Silahkan bersiap memasuki lapangan {$drawing->court->name}.";

                $this->dispatch('announce', ['text' => $text]);

                $this->dispatch('swal', [
                    'icon' => 'success',
                    'title' => 'Panggilan Dikirim',
                    'text' => "Memanggil: {$aka} vs {$shiro}",
                ]);
            }
        }
    }

    public function exportExcel()
    {
        $query = RandoriMatchResult::with(['matchNumber.ageGroup', 'winner'])
            ->whereHas('matchNumber', fn ($q) => $q->where('draft_type', 'randori'));

        if ($this->ageGroupFilter) {
            $query->whereHas('matchNumber', fn ($q) => $q->where('age_group_id', $this->ageGroupFilter));
        }
        if ($this->genderFilter) {
            $query->whereHas('matchNumber', fn ($q) => $q->where('gender', $this->genderFilter));
        }
        if ($this->matchNumberFilter) {
            $query->where('match_number_id', $this->matchNumberFilter);
        }
        if ($this->search) {
            $query->whereHas('matchNumber', fn ($q) => $q->where('name', 'ilike', '%'.$this->search.'%'));
        }

        $results = $query->get();
        $exportData = [];
        $no = 1;

        foreach ($results as $res) {
            $drawingData = $res->matchNumber->drawing_data ?? [];
            $nodeParts = explode('_', $res->bracket_node);
            $bracket = $nodeParts[0];
            $roundIdx = (int) ($nodeParts[1] ?? 0);
            $matchIdx = (int) ($nodeParts[2] ?? 0);

            $matchInfo = null;
            if ($bracket === 'ub') {
                $matchInfo = $drawingData['upper_bracket']['rounds'][$roundIdx][$matchIdx] ?? null;
            } elseif ($bracket === 'lb') {
                $matchInfo = $drawingData['lower_bracket']['rounds'][$roundIdx][$matchIdx] ?? null;
            } elseif ($bracket === 'gf') {
                $matchInfo = $drawingData['grand_final'] ?? null;
            }

            if (! $matchInfo) {
                continue;
            }

            $metadata = is_array($res->metadata) ? $res->metadata : json_decode($res->metadata, true);
            $scoringAka = $metadata['scoringAka'] ?? [];
            $scoringShiro = $metadata['scoringShiro'] ?? [];
            $roundLabel = $this->getRoundLabel($res->bracket_node, $res->matchNumber);

            // Red
            $exportData[] = [
                'No' => $no++,
                'Babak' => $roundLabel,
                'Kelas' => $res->matchNumber->name,
                'Pool' => $matchInfo['pool'] ?? ($matchInfo['pool_id'] ?? 'A'),
                'Pita' => 'Merah',
                'Nama Atlet' => $matchInfo['athlete1']['name'] ?? '-',
                'Kontingen' => $matchInfo['athlete1']['contingent'] ?? '-',
                'Ippon' => $scoringAka['ippon'] ?? 0,
                'Waza-ari' => $scoringAka['waza_ari'] ?? 0,
                'Batsu 5' => $scoringAka['hasil_batsu_5'] ?? 0,
                'Batsu 10' => $scoringAka['hasil_batsu_10'] ?? 0,
                'Total Nilai' => $res->score_red,
                'Status' => $res->winner_color === 'athlete1' ? 'Menang' : 'Kalah',
                'Keterangan' => $res->winner_color === 'athlete1' ? ($scoringAka['mujoken_kachi'] > 0 ? 'Mujoken' : '') : '',
            ];

            // White
            $exportData[] = [
                'No' => $no++,
                'Babak' => $roundLabel,
                'Kelas' => $res->matchNumber->name,
                'Pool' => $matchInfo['pool'] ?? ($matchInfo['pool_id'] ?? 'A'),
                'Pita' => 'Putih',
                'Nama Atlet' => $matchInfo['athlete2']['name'] ?? '-',
                'Kontingen' => $matchInfo['athlete2']['contingent'] ?? '-',
                'Ippon' => $scoringShiro['ippon'] ?? 0,
                'Waza-ari' => $scoringShiro['waza_ari'] ?? 0,
                'Batsu 5' => $scoringShiro['hasil_batsu_5'] ?? 0,
                'Batsu 10' => $scoringShiro['hasil_batsu_10'] ?? 0,
                'Total Nilai' => $res->score_blue,
                'Status' => $res->winner_color === 'athlete2' ? 'Menang' : 'Kalah',
                'Keterangan' => $res->winner_color === 'athlete2' ? ($scoringShiro['mujoken_kachi'] > 0 ? 'Mujoken' : '') : '',
            ];
        }

        return $this->downloadExcel(
            $exportData,
            ['No', 'Babak', 'Kelas', 'Pool', 'Pita', 'Nama Atlet', 'Kontingen', 'Ippon', 'Waza-ari', 'Batsu 5', 'Batsu 10', 'Total Nilai', 'Status', 'Keterangan'],
            'Rekapitulasi_Randori',
            'Rekapitulasi Randori'
        );
    }

    public function render()
    {
        $ageGroups = AgeGroup::orderBy('order')->get();
        $matchNumbersForFilter = MatchNumber::where('draft_type', 'randori')
            ->when($this->ageGroupFilter, fn ($q) => $q->where('age_group_id', $this->ageGroupFilter))
            ->when($this->genderFilter, fn ($q) => $q->where('gender', $this->genderFilter))
            ->orderBy('name')
            ->get();

        $query = RandoriMatchResult::with(['matchNumber.ageGroup', 'matchNumber.athletes', 'winner'])
            ->whereHas('matchNumber', fn ($q) => $q->where('draft_type', 'randori'));

        if ($this->ageGroupFilter) {
            $query->whereHas('matchNumber', fn ($q) => $q->where('age_group_id', $this->ageGroupFilter));
        }

        if ($this->genderFilter) {
            $query->whereHas('matchNumber', fn ($q) => $q->where('gender', $this->genderFilter));
        }

        if ($this->matchNumberFilter) {
            $query->where('match_number_id', $this->matchNumberFilter);
        }

        if ($this->search) {
            $query->whereHas('matchNumber', fn ($q) => $q->where('name', 'ilike', '%'.$this->search.'%'));
        }

        $results = $query->latest()->paginate(20);

        $rekapRows = [];
        foreach ($results as $res) {
            $drawingData = $res->matchNumber->drawing_data ?? [];
            $nodeParts = explode('_', $res->bracket_node);
            $bracket = $nodeParts[0];
            $roundIdx = (int) ($nodeParts[1] ?? 0);
            $matchIdx = (int) ($nodeParts[2] ?? 0);

            $matchInfo = null;
            if ($bracket === 'ub') {
                $matchInfo = $drawingData['upper_bracket']['rounds'][$roundIdx][$matchIdx] ?? null;
            } elseif ($bracket === 'lb') {
                $matchInfo = $drawingData['lower_bracket']['rounds'][$roundIdx][$matchIdx] ?? null;
            } elseif ($bracket === 'gf') {
                $matchInfo = $drawingData['grand_final'] ?? null;
            }

            if (! $matchInfo) {
                continue;
            }

            $metadata = is_array($res->metadata) ? $res->metadata : json_decode($res->metadata, true);
            $scoringAka = $metadata['scoringAka'] ?? [];
            $scoringShiro = $metadata['scoringShiro'] ?? [];

            $roundLabel = $this->getRoundLabel($res->bracket_node, $res->matchNumber);
            $pool = $matchInfo['pool'] ?? ($matchInfo['pool_id'] ?? 'A');

            // Row Aka (Red)
            $rekapRows[] = (object) [
                'match_number_id' => $res->match_number_id,
                'node_key' => $res->bracket_node,
                'no' => null, // Will be filled in view
                'babak' => $roundLabel,
                'kelas' => $res->matchNumber->name,
                'pool' => $pool,
                'pita' => 'Merah',
                'nama_atlet' => $matchInfo['athlete1']['name'] ?? '-',
                'kontingen' => $matchInfo['athlete1']['contingent'] ?? '-',
                'peringatan' => ($scoringAka['hasil_batsu_5'] ?? 0) + ($scoringAka['hasil_batsu_10'] ?? 0),
                'ippon' => $scoringAka['ippon'] ?? 0,
                'waza_ari' => $scoringAka['waza_ari'] ?? 0,
                'batsu_5' => $scoringAka['hasil_batsu_5'] ?? 0,
                'batsu_10' => $scoringAka['hasil_batsu_10'] ?? 0,
                'yusei_kachi' => $scoringAka['yusei_kachi'] ?? 0,
                'mujoken' => $scoringAka['mujoken_kachi'] ?? 0,
                'total_nilai' => $res->score_red,
                'status' => $res->winner_color === 'athlete1' ? 'Menang' : 'Kalah',
                'status_color' => $res->winner_color === 'athlete1' ? 'emerald' : 'rose',
                'win_method' => $res->winner_color === 'athlete1' ? ($scoringAka['mujoken_kachi'] > 0 ? 'Mujoken' : '') : '',
            ];

            // Row Shiro (White)
            $rekapRows[] = (object) [
                'match_number_id' => $res->match_number_id,
                'node_key' => $res->bracket_node,
                'no' => null,
                'babak' => $roundLabel,
                'kelas' => $res->matchNumber->name,
                'pool' => $pool,
                'pita' => 'Putih',
                'nama_atlet' => $matchInfo['athlete2']['name'] ?? '-',
                'kontingen' => $matchInfo['athlete2']['contingent'] ?? '-',
                'peringatan' => ($scoringShiro['hasil_batsu_5'] ?? 0) + ($scoringShiro['hasil_batsu_10'] ?? 0),
                'ippon' => $scoringShiro['ippon'] ?? 0,
                'waza_ari' => $scoringShiro['waza_ari'] ?? 0,
                'batsu_5' => $scoringShiro['hasil_batsu_5'] ?? 0,
                'batsu_10' => $scoringShiro['hasil_batsu_10'] ?? 0,
                'yusei_kachi' => $scoringShiro['yusei_kachi'] ?? 0,
                'mujoken' => $scoringShiro['mujoken_kachi'] ?? 0,
                'total_nilai' => $res->score_blue,
                'status' => $res->winner_color === 'athlete2' ? 'Menang' : 'Kalah',
                'status_color' => $res->winner_color === 'athlete2' ? 'emerald' : 'rose',
                'win_method' => $res->winner_color === 'athlete2' ? ($scoringShiro['mujoken_kachi'] > 0 ? 'Mujoken' : '') : '',
            ];
        }

        return view('livewire.admin.arbitrase.laporan.admin-laporan-rekapitulasi-randori', [
            'results' => $results,
            'rekapRows' => $rekapRows,
            'ageGroups' => $ageGroups,
            'matchNumbersForFilter' => $matchNumbersForFilter,
        ]);
    }
}

<?php

namespace App\Livewire\Admin\Arbitrase\Laporan;

use App\Models\DrawingMatchNumber;
use App\Models\EmbuScore;
use App\Models\Group\AgeGroup;
use App\Models\MatchNumber\MatchNumber;
use App\Traits\HasExcelExport;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('layouts.admin')]
class AdminLaporanRekapitulasiEmbu extends Component
{
    use HasExcelExport, WithPagination;

    public string $search = '';

    public string $ageGroupFilter = '';

    public string $genderFilter = '';

    public string $matchNumberFilter = '';

    public string $roundFilter = '';

    protected $queryString = [
        'search' => ['except' => ''],
        'ageGroupFilter' => ['except' => ''],
        'genderFilter' => ['except' => ''],
        'matchNumberFilter' => ['except' => ''],
        'roundFilter' => ['except' => ''],
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

    public function updatingRoundFilter(): void
    {
        $this->resetPage();
    }

    public function callParticipant($registrationId, $matchNumberId, $roundLabel)
    {
        $matchNumber = MatchNumber::findOrFail($matchNumberId);
        $matchNumber->update(['active_registration_id' => $registrationId]);

        $drawing = DrawingMatchNumber::with(['court', 'registration.contingent', 'registration.athletes'])
            ->where('match_number_id', $matchNumberId)
            ->where('registration_id', $registrationId)
            ->where('round', $roundLabel)
            ->first();

        if ($drawing && $drawing->court) {
            $drawing->court->update([
                'active_match_id' => $matchNumberId,
                'active_drawing_id' => $drawing->id,
                'active_registration_id' => $registrationId,
                'active_bracket_node' => null,
            ]);

            // Filter athletes specifically for this match
            $athletes = $matchNumber->athletes->where('pivot.registration_id', $registrationId)->pluck('name')->join(' dan ');

            $text = "Panggilan untuk peserta nomor undian {$drawing->sequence_number}, dari kontingen {$drawing->registration->contingent->name}, atas nama {$athletes}. Silahkan memasuki lapangan {$drawing->court->name}.";

            $this->dispatch('announce', ['text' => $text]);

            $this->dispatch('swal', [
                'icon' => 'success',
                'title' => 'Panggilan Dikirim',
                'text' => "Memanggil: {$athletes}",
            ]);
        } else {
            // Fallback if no specific drawing found for that round/pool
            $this->dispatch('swal', [
                'icon' => 'warning',
                'title' => 'Gagal Memanggil',
                'text' => "Data jadwal/lapangan untuk babak {$roundLabel} tidak ditemukan.",
            ]);
        }
    }

    public function exportExcel()
    {
        $query = EmbuScore::with(['matchNumber.ageGroup', 'registration.athletes', 'registration.contingent'])
            ->whereHas('matchNumber', fn ($q) => $q->where('draft_type', 'embu'));

        if ($this->ageGroupFilter) {
            $query->whereHas('matchNumber', fn ($q) => $q->where('age_group_id', $this->ageGroupFilter));
        }
        if ($this->genderFilter) {
            $query->whereHas('matchNumber', fn ($q) => $q->where('gender', $this->genderFilter));
        }
        if ($this->matchNumberFilter) {
            $matchId = $this->matchNumberFilter;
            $mergeDetails = \Illuminate\Support\Facades\DB::table('match_number_merge_details')
                ->where('match_number_id', $matchId)
                ->first();

            if ($mergeDetails) {
                $ids = \Illuminate\Support\Facades\DB::table('match_number_merge_details')
                    ->where('match_number_merge_id', $mergeDetails->match_number_merge_id)
                    ->pluck('match_number_id')
                    ->toArray();
                $query->whereIn('match_number_id', $ids);
            } else {
                $query->where('match_number_id', $matchId);
            }
        }
        if ($this->roundFilter) {
            $query->where('round_label', $this->roundFilter);
        }
        if ($this->search) {
            $query->whereHas('registration.athletes', function ($q) {
                $q->where('name', 'ilike', '%'.$this->search.'%');
            })->orWhereHas('matchNumber', function ($q) {
                $q->where('name', 'ilike', '%'.$this->search.'%');
            });
        }

        $scores = $query->orderBy('match_number_id')
            ->orderBy('round_label', 'desc')
            ->orderBy('nilai_akhir', 'desc')
            ->get();

        $exportData = [];
        $no = 1;

        foreach ($scores as $s) {
            $mn = $s->matchNumber;
            // Filter athletes specifically for this match
            $athletes = $mn->athletes->where('pivot.registration_id', $s->registration_id)->pluck('name')->join(' & ');

            $exportData[] = [
                'No' => $no++,
                'Nomor Pertandingan' => $mn->name,
                'Kelompok Umur' => $mn->ageGroup->name ?? '-',
                'Gender' => $mn->gender,
                'Babak' => $s->round_label,
                'Nama Atlet' => $athletes ?: ($s->registration->athletes->pluck('name')->join(' & ') ?? '-'),
                'Kontingen' => $s->registration->contingent->name ?? '-',
                'Nilai Teknik' => (float) $s->nilai_teknik,
                'Nilai Ekspresi' => (float) $s->nilai_ekspresi,
                'Nilai Akhir' => (float) $s->nilai_akhir,
            ];
        }

        return $this->downloadExcel(
            $exportData,
            ['No', 'Nomor Pertandingan', 'Kelompok Umur', 'Gender', 'Babak', 'Nama Atlet', 'Kontingen', 'Nilai Teknik', 'Nilai Ekspresi', 'Nilai Akhir'],
            'Rekapitulasi_Embu',
            'Rekapitulasi Embu'
        );
    }

    public function render()
    {
        $ageGroups = AgeGroup::orderBy('order')->get();
        $matchNumbersForFilter = MatchNumber::where('draft_type', 'embu')
            ->when($this->ageGroupFilter, fn ($q) => $q->where('age_group_id', $this->ageGroupFilter))
            ->when($this->genderFilter, fn ($q) => $q->where('gender', $this->genderFilter))
            ->leftJoin('match_number_merge_details', 'match_numbers.id', '=', 'match_number_merge_details.match_number_id')
            ->leftJoin('match_number_merges', 'match_number_merge_details.match_number_merge_id', '=', 'match_number_merges.id')
            ->where(function($q) {
                $q->whereNull('match_number_merge_details.match_number_merge_id')
                  ->orWhereRaw('match_numbers.id = (SELECT MIN(m2.match_number_id) FROM match_number_merge_details m2 WHERE m2.match_number_merge_id = match_number_merge_details.match_number_merge_id)');
            })
            ->orderBy('match_numbers.name')
            ->select('match_numbers.*', 'match_number_merges.name as merge_group_name', 'match_number_merge_details.match_number_merge_id')
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

        $query = EmbuScore::with(['matchNumber.ageGroup', 'matchNumber.athletes', 'registration.contingent'])
            ->whereHas('matchNumber', fn ($q) => $q->where('draft_type', 'embu'));

        if ($this->ageGroupFilter) {
            $query->whereHas('matchNumber', fn ($q) => $q->where('age_group_id', $this->ageGroupFilter));
        }

        if ($this->genderFilter) {
            $query->whereHas('matchNumber', fn ($q) => $q->where('gender', $this->genderFilter));
        }

        if ($this->matchNumberFilter) {
            $matchId = $this->matchNumberFilter;
            $mergeDetails = \Illuminate\Support\Facades\DB::table('match_number_merge_details')
                ->where('match_number_id', $matchId)
                ->first();

            if ($mergeDetails) {
                $ids = \Illuminate\Support\Facades\DB::table('match_number_merge_details')
                    ->where('match_number_merge_id', $mergeDetails->match_number_merge_id)
                    ->pluck('match_number_id')
                    ->toArray();
                $query->whereIn('match_number_id', $ids);
            } else {
                $query->where('match_number_id', $matchId);
            }
        }

        if ($this->roundFilter) {
            $query->where('round_label', $this->roundFilter);
        }

        if ($this->search) {
            $query->whereHas('registration.athletes', function ($q) {
                $q->where('name', 'ilike', '%'.$this->search.'%');
            })->orWhereHas('matchNumber', function ($q) {
                $q->where('name', 'ilike', '%'.$this->search.'%');
            });
        }

        // Usually rekap is sorted by MatchNumber -> Round -> Score
        $scores = $query->orderBy('match_number_id')
            ->orderBy('round_label', 'desc') // Final usually comes after Penyisihan alphabetically if we are lucky, but better explicit
            ->orderBy('nilai_akhir', 'desc')
            ->paginate(30);

        return view('livewire.admin.arbitrase.laporan.admin-laporan-rekapitulasi-embu', [
            'scores' => $scores,
            'ageGroups' => $ageGroups,
            'matchNumbersForFilter' => $matchNumbersForFilter,
        ]);
    }
}

<?php

namespace App\Livewire\Admin;

use App\Models\Group\AgeGroup;
use App\Models\MatchNumber\MatchNumber;
use App\Models\MatchNumberMerge;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('layouts.premium')]
class NewLaporanRekapPenilaianIndex extends Component
{
    use WithPagination;

    public string $search = '';

    public string $filterType = '';

    public string $filterAgeGroup = '';

    public string $filterGender = '';

    public function updated(string $property): void
    {
        if (in_array($property, ['search', 'filterType', 'filterAgeGroup', 'filterGender'])) {
            $this->resetPage();
        }
    }

    public function resetFilters(): void
    {
        $this->search = '';
        $this->filterType = '';
        $this->filterAgeGroup = '';
        $this->filterGender = '';
        $this->resetPage();
    }

    public function render()
    {
        $query = MatchNumber::with(['ageGroup'])
            ->withCount([
                'embuScores',
                'randoriResults',
                'tournamentResults',
                'athletes',
            ]);

        if (! empty($this->search)) {
            $likeOperator = DB::getDriverName() === 'sqlite' ? 'like' : 'ilike';
            $query->where('name', $likeOperator, '%'.$this->search.'%');
        }

        if (! empty($this->filterType)) {
            $query->where('draft_type', $this->filterType);
        }

        if (! empty($this->filterAgeGroup)) {
            $query->where('age_group_id', $this->filterAgeGroup);
        }

        if (! empty($this->filterGender)) {
            $query->where('gender', $this->filterGender);
        }

        // Only show match numbers that have at least some drawings (were scheduled)
        $query->whereHas('drawings');

        $query->orderBy('name');

        $matchNumbers = $query->paginate(20);

        // Enrich with merge info and champion/result status
        $mergeDetails = DB::table('match_number_merge_details')
            ->whereIn('match_number_id', $matchNumbers->pluck('id'))
            ->get()
            ->keyBy('match_number_id');

        $mergeNames = [];
        foreach ($mergeDetails as $detail) {
            $merge = MatchNumberMerge::find($detail->match_number_merge_id);
            $mergeNames[$detail->match_number_id] = $merge?->name ?? 'Merged';
        }

        foreach ($matchNumbers as $mn) {
            $mn->merge_name = $mergeNames[$mn->id] ?? null;

            // Check if there are any scores
            if ($mn->draft_type === 'embu') {
                $mn->has_results = $mn->embu_scores_count > 0;
                $mn->result_count = $mn->embu_scores_count;
            } else {
                $mn->has_results = $mn->randori_results_count > 0;
                $mn->result_count = $mn->randori_results_count;
            }

            $mn->has_champion = $mn->tournament_results_count > 0;
        }

        return view('livewire.admin.new-laporan-rekap-penilaian-index', [
            'matchNumbers' => $matchNumbers,
            'ageGroups' => AgeGroup::orderBy('order')->get(),
        ])->title('Laporan Rekap Penilaian');
    }
}

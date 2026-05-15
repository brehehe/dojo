<?php

namespace App\Livewire\Admin\Master;

use App\Models\Group\AgeGroup;
use App\Models\MatchNumber\MatchNumber;
use App\Models\MatchNumberMerge;
use App\Models\MatchNumberMergeDetail;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Livewire\WithPagination;

class NewMatchNumberMergeIndex extends Component
{
    use WithPagination;

    public $search = '';

    public $perPage = 10;

    // Form fields
    public $name;

    public $ageGroupId;

    public $type = 'embu';

    public $selectedMatchNumbers = [];

    public $showingModal = false;

    public $isEdit = false;

    public $mergeIdBeingEdited = null;

    protected $rules = [
        'name' => 'required|string|max:255',
        'ageGroupId' => 'required|exists:age_groups,id',
        'type' => 'required|in:randori,embu',
        'selectedMatchNumbers' => 'required|array|min:2',
    ];

    public function showCreateModal()
    {
        $this->resetValidation();
        $this->reset(['name', 'ageGroupId', 'type', 'selectedMatchNumbers', 'isEdit', 'mergeIdBeingEdited']);
        $this->showingModal = true;
    }

    public function save()
    {
        $this->validate();

        DB::transaction(function () {
            $merge = MatchNumberMerge::updateOrCreate(
                ['id' => $this->mergeIdBeingEdited],
                [
                    'name' => $this->name,
                    'age_group_id' => $this->ageGroupId,
                    'type' => $this->type,
                ]
            );

            // Sync match numbers
            $merge->details()->delete();
            foreach ($this->selectedMatchNumbers as $mnId) {
                MatchNumberMergeDetail::create([
                    'match_number_merge_id' => $merge->id,
                    'match_number_id' => $mnId,
                ]);
            }
        });

        $this->showingModal = false;
        $this->dispatch('swal', [
            'title' => 'Berhasil!',
            'text' => 'Penggabungan nomor pertandingan telah disimpan.',
            'icon' => 'success',
        ]);
    }

    public function edit($id)
    {
        $this->resetValidation();
        $merge = MatchNumberMerge::with('matchNumbers')->findOrFail($id);
        $this->mergeIdBeingEdited = $id;
        $this->name = $merge->name;
        $this->ageGroupId = $merge->age_group_id;
        $this->type = $merge->type;
        $this->selectedMatchNumbers = $merge->matchNumbers->pluck('id')->toArray();
        $this->isEdit = true;
        $this->showingModal = true;
    }

    public function delete($id)
    {
        MatchNumberMerge::findOrFail($id)->delete();
        $this->dispatch('swal', [
            'title' => 'Dihapus!',
            'text' => 'Penggabungan telah dihapus.',
            'icon' => 'success',
        ]);
    }

    public function render()
    {
        $query = MatchNumberMerge::with(['ageGroup', 'matchNumbers']);

        if ($this->search) {
            $query->where('name', 'like', '%'.$this->search.'%');
        }

        $merges = $query->latest()->paginate($this->perPage);

        $ageGroups = AgeGroup::orderBy('order')->get();

        // Match numbers available for merging (filtered by age group and type)
        $availableMatchNumbers = MatchNumber::where('age_group_id', $this->ageGroupId)
            ->where('draft_type', $this->type)
            ->where(function ($q) {
                $q->whereNotExists(function ($query) {
                    $query->select(DB::raw(1))
                        ->from('match_number_merge_details')
                        ->whereColumn('match_number_merge_details.match_number_id', 'match_numbers.id');
                });

                if ($this->mergeIdBeingEdited) {
                    $q->orWhereExists(function ($query) {
                        $query->select(DB::raw(1))
                            ->from('match_number_merge_details')
                            ->whereColumn('match_number_merge_details.match_number_id', 'match_numbers.id')
                            ->where('match_number_merge_details.match_number_merge_id', $this->mergeIdBeingEdited);
                    });
                }
            })
            ->orderBy('name')
            ->get()
            ->map(function ($mn) {
                $registrations = DB::table('athlete_match_number')
                    ->join('registrations', 'athlete_match_number.registration_id', '=', 'registrations.id')
                    ->join('contingents', 'registrations.contingent_id', '=', 'contingents.id')
                    ->where('athlete_match_number.match_number_id', $mn->id)
                    ->select('contingents.name', 'athlete_match_number.registration_id', DB::raw('count(*) as athlete_count'))
                    ->groupBy('contingents.name', 'athlete_match_number.registration_id')
                    ->get();

                $entryCount = 0;
                $contingentNames = collect();
                foreach ($registrations as $reg) {
                    $contingentNames->push($reg->name);
                    $max = $mn->max_athletes ?: 1;
                    $entryCount += (int) ceil($reg->athlete_count / $max);
                }

                $mn->contingent_list = $contingentNames->unique()->values();
                $mn->contingent_count = $entryCount;

                return $mn;
            });

        $selectedContingents = collect();
        if (! empty($this->selectedMatchNumbers)) {
            $registrations = DB::table('athlete_match_number')
                ->join('registrations', 'athlete_match_number.registration_id', '=', 'registrations.id')
                ->join('contingents', 'registrations.contingent_id', '=', 'contingents.id')
                ->join('match_numbers', 'athlete_match_number.match_number_id', '=', 'match_numbers.id')
                ->whereIn('athlete_match_number.match_number_id', $this->selectedMatchNumbers)
                ->select(
                    'contingents.name',
                    'athlete_match_number.match_number_id',
                    'athlete_match_number.registration_id',
                    'match_numbers.max_athletes',
                    DB::raw('count(*) as athlete_count')
                )
                ->groupBy('contingents.name', 'athlete_match_number.match_number_id', 'athlete_match_number.registration_id', 'match_numbers.max_athletes')
                ->get();

            $selectedContingents = $registrations->groupBy('name')
                ->map(function ($items, $name) {
                    $entryCount = 0;
                    foreach ($items as $item) {
                        $max = $item->max_athletes ?: 1;
                        $entryCount += (int) ceil($item->athlete_count / $max);
                    }

                    return (object) [
                        'name' => $name,
                        'reg_count' => $entryCount,
                    ];
                })
                ->values();
        }

        return view('livewire.admin.master.new-match-number-merge-index', [
            'merges' => $merges,
            'ageGroups' => $ageGroups,
            'availableMatchNumbers' => $availableMatchNumbers,
            'selectedContingents' => $selectedContingents,
        ])->layout('layouts.premium', ['title' => 'Merge Nomer Pertandingan']);
    }
}

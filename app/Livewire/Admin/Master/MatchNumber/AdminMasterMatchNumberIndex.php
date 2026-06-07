<?php

namespace App\Livewire\Admin\Master\MatchNumber;

use App\Models\Group\AgeGroup;
use App\Models\MatchNumber\MatchNumber;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('layouts.admin')]
class AdminMasterMatchNumberIndex extends Component
{
    use WithPagination;

    public function paginationView()
    {
        return 'livewire.admin.pagination';
    }

    public $search = '';

    public $perPage = 5;

    // User Fields
    public $name;

    public $gender;

    public $age_group_id = null;

    public $draft_type = null;

    public $max_athletes = 0;

    public $showingMatchNumberModal = false;

    public $matchNumberIdBeingEdited = null;

    public $filterAgeGroup = '';

    public $filterDraftType = '';

    public $showingAthletesModal = false;

    public $selectedMatchNumber = null;

    public $registeredAthletes = [];

    protected $queryString = [
        'search' => ['except' => ''],
        'perPage' => ['except' => 10],
        'filterAgeGroup' => ['except' => ''],
        'filterDraftType' => ['except' => ''],
    ];

    public function updatedFilterAgeGroup()
    {
        $this->resetPage();
    }

    public function updatedFilterDraftType()
    {
        $this->resetPage();
    }

    public function updatedSearch()
    {
        $this->resetPage();
    }

    public function showCreateModal()
    {
        $this->resetValidation();
        $this->reset(['name', 'gender', 'age_group_id', 'draft_type', 'max_athletes', 'matchNumberIdBeingEdited']);
        $this->showingMatchNumberModal = true;
    }

    public function showEditModal($matchNumberId)
    {
        $this->resetValidation();
        $this->matchNumberIdBeingEdited = $matchNumberId;
        $matchNumber = MatchNumber::findOrFail($matchNumberId);

        // Load User Data
        $this->name = $matchNumber->name;
        $this->gender = $matchNumber->gender;
        $this->age_group_id = $matchNumber->age_group_id;
        $this->draft_type = $matchNumber->draft_type;
        $this->max_athletes = $matchNumber->max_athletes;
        $this->showingMatchNumberModal = true;
    }

    public function saveMatchNumber()
    {
        $rules = [
            'name' => 'required|min:3',
            'gender' => 'required',
            'age_group_id' => 'required',
            'draft_type' => 'required',
            'max_athletes' => 'required',
        ];

        $this->validate($rules);

        DB::transaction(function () {
            if ($this->matchNumberIdBeingEdited) {
                $matchNumber = MatchNumber::findOrFail($this->matchNumberIdBeingEdited);

                $matchNumber->update([
                    'name' => $this->name,
                    'gender' => $this->gender,
                    'age_group_id' => $this->age_group_id,
                    'draft_type' => $this->draft_type,
                    'max_athletes' => $this->max_athletes,
                ]);

                $this->dispatch('swal', title: 'Berhasil!', text: 'Nomor pertandingan telah diperbarui.', icon: 'success');
            } else {
                MatchNumber::create([
                    'name' => $this->name,
                    'gender' => $this->gender,
                    'age_group_id' => $this->age_group_id,
                    'draft_type' => $this->draft_type,
                    'max_athletes' => $this->max_athletes,
                ]);

                $this->dispatch('swal', title: 'Berhasil!', text: 'Nomor pertandingan baru telah ditambahkan.', icon: 'success');
            }
        });

        $this->showingMatchNumberModal = false;
    }

    public function deleteMatchNumber($matchNumberId)
    {
        $matchNumber = MatchNumber::findOrFail($matchNumberId);
        DB::transaction(function () use ($matchNumber) {
            $matchNumber->delete();
        });

        $this->dispatch('swal', title: 'Dihapus!', text: 'Nomor pertandingan telah dihapus.', icon: 'success');
    }

    public function showAthletes($matchNumberId)
    {
        $this->selectedMatchNumber = MatchNumber::with('ageGroup')->findOrFail($matchNumberId);

        // Load athletes through the pivot table
        $this->registeredAthletes = DB::table('athlete_match_number')
            ->join('athletes', 'athlete_match_number.athlete_id', '=', 'athletes.id')
            ->join('registration_athlete', function ($join) {
                // Link to registration_athlete to get the specific contingent and other info
                $join->on('athlete_match_number.athlete_id', '=', 'registration_athlete.athlete_id')
                    ->on('athlete_match_number.registration_id', '=', 'registration_athlete.registration_id');
            })
            ->join('registrations', 'athlete_match_number.registration_id', '=', 'registrations.id')
            ->join('contingents', 'registrations.contingent_id', '=', 'contingents.id')
            ->where('athlete_match_number.match_number_id', $matchNumberId)
            ->select(
                'athletes.name as athlete_name',
                'athletes.nik',
                'contingents.name as contingent_name',
                'registration_athlete.kyu',
                'registration_athlete.weight'
            )
            ->get()
            ->toArray();

        $this->showingAthletesModal = true;
    }

    public function render()
    {
        $matchNumbers = MatchNumber::query()
            ->when($this->search, function ($query) {
                $query->where('name', 'ilike', '%'.$this->search.'%');
            })
            ->when($this->filterAgeGroup, function ($query) {
                $query->where('age_group_id', $this->filterAgeGroup);
            })
            ->when($this->filterDraftType, function ($query) {
                $query->where('draft_type', $this->filterDraftType);
            })
            ->latest()
            ->paginate($this->perPage === 'all' ? MatchNumber::count() : $this->perPage);

        $ageGroups = AgeGroup::all();

        return view('livewire.admin.master.match-number.admin-master-match-number-index', [
            'matchNumbers' => $matchNumbers,
            'ageGroups' => $ageGroups,
        ]);
    }
}

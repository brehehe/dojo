<?php

namespace App\Livewire\Admin;

use App\Models\Group\AgeGroup;
use App\Models\MatchNumber\MatchNumber;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Livewire\WithPagination;

class NewMatchNumberIndex extends Component
{
    use WithPagination;

    public $search = '';

    public $perPage = 10;

    // Fields
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

    public function showEditModal($id)
    {
        $this->resetValidation();
        $this->matchNumberIdBeingEdited = $id;
        $matchNumber = MatchNumber::findOrFail($id);

        $this->name = $matchNumber->name;
        $this->gender = $matchNumber->gender;
        $this->age_group_id = $matchNumber->age_group_id;
        $this->draft_type = $matchNumber->draft_type;
        $this->max_athletes = $matchNumber->max_athletes;
        $this->showingMatchNumberModal = true;
    }

    public function saveMatchNumber()
    {
        $this->validate([
            'name' => 'required|min:3',
            'gender' => 'required',
            'age_group_id' => 'required',
            'draft_type' => 'required',
            'max_athletes' => 'required|numeric|min:1',
        ]);

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

                $this->dispatch('swal', [
                    'title' => 'Berhasil!',
                    'text' => 'Nomor pertandingan telah diperbarui.',
                    'icon' => 'success',
                ]);
            } else {
                MatchNumber::create([
                    'name' => $this->name,
                    'gender' => $this->gender,
                    'age_group_id' => $this->age_group_id,
                    'draft_type' => $this->draft_type,
                    'max_athletes' => $this->max_athletes,
                ]);

                $this->dispatch('swal', [
                    'title' => 'Berhasil!',
                    'text' => 'Nomor pertandingan baru telah ditambahkan.',
                    'icon' => 'success',
                ]);
            }
        });

        $this->showingMatchNumberModal = false;
    }

    public function deleteMatchNumber($id)
    {
        $matchNumber = MatchNumber::findOrFail($id);
        DB::transaction(function () use ($matchNumber) {
            $matchNumber->delete();
        });

        $this->dispatch('swal', [
            'title' => 'Dihapus!',
            'text' => 'Nomor pertandingan telah dihapus.',
            'icon' => 'success',
        ]);
    }

    public function showAthletes($id)
    {
        $this->selectedMatchNumber = MatchNumber::with('ageGroup')->findOrFail($id);

        $this->registeredAthletes = DB::table('athlete_match_number')
            ->join('athletes', 'athlete_match_number.athlete_id', '=', 'athletes.id')
            ->join('registration_athlete', function ($join) {
                $join->on('athlete_match_number.athlete_id', '=', 'registration_athlete.athlete_id')
                    ->on('athlete_match_number.registration_id', '=', 'registration_athlete.registration_id');
            })
            ->join('registrations', 'athlete_match_number.registration_id', '=', 'registrations.id')
            ->join('contingents', 'registrations.contingent_id', '=', 'contingents.id')
            ->where('athlete_match_number.match_number_id', $id)
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
        $query = MatchNumber::query()->with('ageGroup');

        if ($this->search) {
            $query->where('name', 'like', '%'.$this->search.'%');
        }
        if ($this->filterAgeGroup) {
            $query->where('age_group_id', $this->filterAgeGroup);
        }
        if ($this->filterDraftType) {
            $query->where('draft_type', $this->filterDraftType);
        }

        $matchNumbers = $query->latest()->paginate($this->perPage === 'all' ? MatchNumber::count() : $this->perPage);
        $ageGroups = AgeGroup::all();

        return view('livewire.admin.new-match-number-index', [
            'matchNumbers' => $matchNumbers,
            'ageGroups' => $ageGroups,
        ])->layout('layouts.premium', ['title' => 'Master Nomor Pertandingan']);
    }
}

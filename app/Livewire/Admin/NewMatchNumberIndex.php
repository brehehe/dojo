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

    public $filterGender = '';

    public $showingAthletesModal = false;

    public $selectedMatchNumber = null;

    public $registeredAthletes = [];

    protected $queryString = [
        'search' => ['except' => ''],
        'perPage' => ['except' => 10],
        'filterAgeGroup' => ['except' => ''],
        'filterDraftType' => ['except' => ''],
        'filterGender' => ['except' => ''],
    ];

    public function mount()
    {
        // DB::transaction(function () {
        //     MatchNumber::where('name', 'ilike', '%Embu Beregu%')->update(['max_athletes' => 4]);
        //     MatchNumber::where('name', 'ilike', '%Embu Pasangan%')->update(['max_athletes' => 2]);
        // });
    }

    public function updatedFilterAgeGroup()
    {
        $this->resetPage();
    }

    public function updatedFilterDraftType()
    {
        $this->resetPage();
    }

    public function updatedFilterGender()
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
            $query->where('name', 'ilike', '%'.$this->search.'%');
        }
        if ($this->filterAgeGroup) {
            $query->where('age_group_id', $this->filterAgeGroup);
        }
        if ($this->filterDraftType) {
            $query->where('draft_type', $this->filterDraftType);
        }
        if ($this->filterGender) {
            $query->where('gender', $this->filterGender);
        }

        $matchNumbers = $query->latest()->paginate($this->perPage === 'all' ? MatchNumber::count() : $this->perPage);
        $ageGroups = AgeGroup::all();

        $likeOperator = DB::connection()->getDriverName() === 'pgsql' ? 'ilike' : 'like';
        $notLikeOperator = DB::connection()->getDriverName() === 'pgsql' ? 'not ilike' : 'not like';

        $totalEksebisi = MatchNumber::where('name', $likeOperator, '%eksebisi%')->count();
        $totalNonEksebisi = MatchNumber::where('name', $notLikeOperator, '%eksebisi%')->count();
        $totalEmbuEksebisi = MatchNumber::where('draft_type', 'embu')->where('name', $likeOperator, '%eksebisi%')->count();
        $totalEmbuNonEksebisi = MatchNumber::where('draft_type', 'embu')->where('name', $notLikeOperator, '%eksebisi%')->count();
        $totalRandoriEksebisi = MatchNumber::where('draft_type', 'randori')->where('name', $likeOperator, '%eksebisi%')->count();
        $totalRandoriNonEksebisi = MatchNumber::where('draft_type', 'randori')->where('name', $notLikeOperator, '%eksebisi%')->count();

        return view('livewire.admin.new-match-number-index', [
            'matchNumbers' => $matchNumbers,
            'ageGroups' => $ageGroups,
            'totalEksebisi' => $totalEksebisi,
            'totalNonEksebisi' => $totalNonEksebisi,
            'totalEmbuEksebisi' => $totalEmbuEksebisi,
            'totalEmbuNonEksebisi' => $totalEmbuNonEksebisi,
            'totalRandoriEksebisi' => $totalRandoriEksebisi,
            'totalRandoriNonEksebisi' => $totalRandoriNonEksebisi,
        ])->layout('layouts.premium', ['title' => 'Master Nomor Pertandingan']);
    }
}

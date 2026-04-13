<?php

namespace App\Livewire\Admin\Master\Athlete;

use App\Models\Athlete;
use App\Models\Category;
use App\Models\Contingent;
use App\Models\KyuLevel;
use Auth;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithFileUploads;

#[Layout('layouts.admin')]
class AdminMasterAthleteFormIndex extends Component
{
    use WithFileUploads;

    public $athleteId = null;

    public $isEdit = false;

    // Athlete Fields
    public $contingent_id;

    public $nik;

    public $name;

    public $gender;

    public $birth_date;

    public $weight;

    public $achievement_history = [''];

    public $kyu;

    public $dojo_origin;

    public $city;

    public $bpjs_number;

    public $bpjs_status = 'TIDAK_AKTIF';

    public $age;

    public $age_group;

    public $match_type;

    public $rank;

    // File Paths/Uploads
    public $bpjs_card;

    public $identity_card;

    public $identity_document;

    public $existing_bpjs_card_path;

    public $existing_identity_card_path;

    public $existing_identity_document_path;

    // Championship Selection
    public $selectedCategories = [];

    public $showForm = false;

    public function mount($athlete = null)
    {

        if ($athlete) {
            $this->isEdit = true;
            $this->athleteId = $athlete;
            $athleteModel = Athlete::with('registrations')->findOrFail($athlete);

            $this->fill($athleteModel->toArray());
            $this->birth_date = $athleteModel->birth_date?->format('Y-m-d');
            $this->achievement_history = is_array($athleteModel->achievement_history) ? $athleteModel->achievement_history : [''];
            $this->selectedCategories = $athleteModel->categories->pluck('id')->toArray();

            // Set current contingent and physical data from latest registration
            $latestReg = $athleteModel->latestRegistration();
            if ($latestReg) {
                $this->contingent_id = $latestReg->contingent_id;
                $this->weight = $latestReg->pivot->weight;
                $this->kyu = $latestReg->pivot->kyu;
                $this->dojo_origin = $latestReg->pivot->dojo_origin;
                $this->city = $latestReg->pivot->city;
                $this->age_group = $latestReg->pivot->age_group;
                $this->match_type = $latestReg->pivot->match_type;
                $this->rank = $latestReg->pivot->rank;
                $this->age = $latestReg->pivot->age;
            }

            $this->existing_identity_card_path = $athleteModel->identity_card_path;
            $this->existing_identity_document_path = $athleteModel->identity_document_path;
            $this->showForm = true;
        } else {
            $this->contingent_id = Auth::user()?->contingent?->id;
        }
    }

    public function searchAthleteByNik()
    {
        $this->validateOnly('nik', ['nik' => 'required|numeric|digits:16']);

        $athlete = Athlete::where('nik', $this->nik)->first();

        if ($athlete) {
            $this->loadAthleteData($athlete);
            $this->dispatch('swal', title: 'Data Ditemukan!', text: 'Atlet sudah terdaftar di Master Data. Anda sekarang mengedit data yang sudah ada.', icon: 'success');
        } else {
            $currentNik = $this->nik;
            $this->resetForm(false);
            $this->nik = $currentNik;
            $this->dispatch('swal', title: 'Tidak Ditemukan', text: 'Data tidak ditemukan. Silakan masukkan data baru untuk pendaftaran atlet ini.', icon: 'info');
        }

        $this->showForm = true;
    }

    public function resetForm($resetNik = true)
    {
        $this->isEdit = false;
        $this->athleteId = null;
        if ($resetNik) {
            $this->nik = '';
            $this->showForm = false;
        }

        $this->name = '';
        $this->contingent_id = '';
        $this->gender = '';
        $this->birth_date = '';
        $this->weight = '';
        $this->achievement_history = [''];
        $this->kyu = '';
        $this->dojo_origin = '';
        $this->city = '';
        $this->bpjs_number = '';
        $this->bpjs_status = 'TIDAK_AKTIF';
        $this->selectedCategories = [];
        $this->age = '';
        $this->age_group = '';
        $this->match_type = '';
        $this->rank = '';

        $this->existing_bpjs_card_path = null;
        $this->existing_identity_card_path = null;
        $this->existing_identity_document_path = null;

        $this->resetErrorBag();
    }

    public function updatedNik($value)
    {
        if (strlen($value) === 16) {
            $this->searchAthleteByNik();
        }
    }

    private function loadAthleteData(Athlete $athlete)
    {
        $this->isEdit = true;
        $this->athleteId = $athlete->id;

        $this->name = $athlete->name;
        $this->gender = $athlete->gender;
        $this->birth_date = $athlete->birth_date?->format('Y-m-d');
        $this->achievement_history = is_array($athlete->achievement_history) ? $athlete->achievement_history : [''];
        $this->bpjs_number = $athlete->bpjs_number;
        $this->bpjs_status = $athlete->bpjs_status;
        $this->selectedCategories = $athlete->categories->pluck('id')->toArray();

        // Load technical data from latest registration
        $latestReg = $athlete->latestRegistration();
        if ($latestReg) {
            $this->contingent_id = $latestReg->contingent_id;
            $this->weight = $latestReg->pivot->weight;
            $this->kyu = $latestReg->pivot->kyu;
            $this->dojo_origin = $latestReg->pivot->dojo_origin;
            $this->city = $latestReg->pivot->city;
            $this->age_group = $latestReg->pivot->age_group;
            $this->match_type = $latestReg->pivot->match_type;
            $this->rank = $latestReg->pivot->rank;
            $this->age = $latestReg->pivot->age;
        }

        $this->existing_bpjs_card_path = $athlete->bpjs_card_path;
        $this->existing_identity_card_path = $athlete->identity_card_path;
        $this->existing_identity_document_path = $athlete->identity_document_path;
    }

    public function addAchievement()
    {
        $this->achievement_history[] = '';
    }

    public function removeAchievement($index)
    {
        unset($this->achievement_history[$index]);
        $this->achievement_history = array_values($this->achievement_history);

        if (empty($this->achievement_history)) {
            $this->achievement_history = [''];
        }
    }

    public function save()
    {
        $rules = [
            'name' => 'required|min:3',
            'nik' => 'required|numeric|digits:16',
            'contingent_id' => 'required|exists:contingents,id',
            'gender' => 'required|in:Male,Female',
            'birth_date' => 'required|date',
            'weight' => 'required|numeric',
            'kyu' => 'required',
            'dojo_origin' => 'required',
            'city' => 'required',
            'match_type' => 'nullable',
            'rank' => 'nullable',
            'age_group' => 'nullable',
            'bpjs_status' => 'required',
        ];

        $this->validate($rules);

        $masterData = [
            'nik' => $this->nik,
            'name' => $this->name,
            'gender' => $this->gender,
            'birth_date' => $this->birth_date,
            'achievement_history' => $this->achievement_history,
            'bpjs_number' => $this->bpjs_number,
            'bpjs_status' => $this->bpjs_status,
        ];

        $pivotData = [
            'weight' => $this->weight,
            'kyu' => $this->kyu,
            'dojo_origin' => $this->dojo_origin,
            'city' => $this->city,
            'age' => $this->age,
            'age_group' => $this->age_group,
            'match_type' => $this->match_type,
            'rank' => $this->rank,
        ];

        // Handle File Uploads (Simulation logic for paths)
        if ($this->bpjs_card) {
            $masterData['bpjs_card_path'] = $this->bpjs_card->store('athletes/bpjs', 'public');
        }
        if ($this->identity_card) {
            $masterData['identity_card_path'] = $this->identity_card->store('athletes/identity', 'public');
        }
        if ($this->identity_document) {
            $masterData['identity_document_path'] = $this->identity_document->store('athletes/documents', 'public');
        }

        \Illuminate\Support\Facades\DB::transaction(function () use ($masterData, $pivotData) {
            if ($this->isEdit) {
                $athlete = Athlete::findOrFail($this->athleteId);
                $oldContingentId = $athlete->contingent?->id;

                $athlete->update($masterData);

                if ($oldContingentId != $this->contingent_id) {
                    // Update current primary to false
                    $athlete->contingents()->wherePivot('is_primary', true)->updateExistingPivot($oldContingentId, ['is_primary' => false]);
                    
                    // Add/Update new primary
                    $athlete->contingents()->syncWithoutDetaching([
                        $this->contingent_id => ['is_primary' => true, 'joined_at' => now()]
                    ]);

                    // Record history
                    $athlete->contingentHistories()->create([
                        'contingent_id' => $this->contingent_id,
                        'moved_at' => now(),
                        'notes' => 'Perpindahan kontingen via panel admin.',
                    ]);
                } else {
                    // Just ensure primary is set if for some reason it wasn't
                    $athlete->contingents()->syncWithoutDetaching([
                        $this->contingent_id => ['is_primary' => true]
                    ]);
                }

                // Sync with latest registration of the selected contingent
                $registration = Contingent::find($this->contingent_id)->registrations()->latest()->first();
                if ($registration) {
                    $athlete->registrations()->syncWithoutDetaching([$registration->id => $pivotData]);
                }

                $athlete->categories()->sync($this->selectedCategories);
                $this->dispatch('swal', title: 'Berhasil!', text: 'Data atlet diperbarui.', icon: 'success');
            } else {
                $athlete = Athlete::create($masterData);

                // Initial membership
                $athlete->contingents()->attach([
                    $this->contingent_id => ['is_primary' => true, 'joined_at' => now()]
                ]);

                // Record initial history
                $athlete->contingentHistories()->create([
                    'contingent_id' => $this->contingent_id,
                    'moved_at' => now(),
                    'notes' => 'Kontingen pendaftaran awal.',
                ]);

                // Link to latest registration for tournament
                $registration = Contingent::find($this->contingent_id)->registrations()->latest()->first();
                if ($registration) {
                    $athlete->registrations()->attach($registration->id, $pivotData);
                }

                $athlete->categories()->sync($this->selectedCategories);
                $this->dispatch('swal', title: 'Berhasil!', text: 'Atlet baru ditambahkan.', icon: 'success');
            }
        });

        return redirect()->route('admin.master.athletes.index');
    }

    public function render()
    {
        return view('livewire.admin.master.athlete.admin-master-athlete-form-index', [
            'contingents' => Contingent::orderBy('name')->get(),
            'categories' => Category::all(),
            'kyuLevels' => KyuLevel::orderBy('name')->get(),
        ]);
    }
}

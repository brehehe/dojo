<?php

namespace App\Livewire\Admin;

use App\Models\Athlete;
use App\Models\Category;
use App\Models\Contingent;
use App\Models\KyuLevel;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Livewire\WithFileUploads;

class NewAthleteCreate extends Component
{
    use WithFileUploads;

    // Step control
    public bool $showForm = false;

    public bool $isEdit = false;

    public ?int $athleteId = null;

    // Biodata
    public $nik = '';

    public $name = '';

    public $gender = '';

    public $birth_date = '';

    public $birth_place = '';

    public $address = '';

    public $phone = '';

    public $blood_type = '';

    public $achievement_history = [''];

    // Technical
    public $contingent_id = '';

    public $weight = '';

    public $kyu = '';

    public $dojo_origin = '';

    public $city = '';

    public $age = '';

    public $age_group = '';

    public $match_type = '';

    public $rank = '';

    // BPJS
    public $bpjs_number = '';

    public $bpjs_status = 'TIDAK_AKTIF';

    // Files
    public $bpjs_card;

    public $identity_card;

    public $existing_bpjs_card_path;

    public $existing_identity_card_path;

    // Categories
    public $selectedCategories = [];

    public function updatedNik(string $value): void
    {
        if (strlen($value) === 16) {
            $this->searchByNik();
        }
    }

    public function searchByNik(): void
    {
        $this->validateOnly('nik', ['nik' => 'required|numeric|digits:16']);

        $athlete = Athlete::where('nik', $this->nik)->first();

        if ($athlete) {
            $this->loadAthleteData($athlete);
            $this->dispatch('swal', [
                'title' => 'Data Ditemukan!',
                'text' => 'Atlet sudah terdaftar. Data dimuat untuk diedit.',
                'icon' => 'success',
            ]);
        } else {
            $savedNik = $this->nik;
            $this->resetForm();
            $this->nik = $savedNik;
            $this->dispatch('swal', [
                'title' => 'NIK Baru',
                'text' => 'NIK belum terdaftar. Silakan isi data atlet baru.',
                'icon' => 'info',
            ]);
        }

        $this->showForm = true;
    }

    private function loadAthleteData(Athlete $athlete): void
    {
        $this->isEdit = true;
        $this->athleteId = $athlete->id;
        $this->name = $athlete->name;
        $this->gender = $athlete->gender;
        $this->birth_date = $athlete->birth_date instanceof Carbon
            ? $athlete->birth_date->format('Y-m-d')
            : ($athlete->birth_date ? date('Y-m-d', strtotime($athlete->birth_date)) : '');
        $this->birth_place = $athlete->birth_place ?? '';
        $this->address = $athlete->address ?? '';
        $this->phone = $athlete->phone ?? '';
        $this->blood_type = $athlete->blood_type ?? '';
        $this->achievement_history = is_array($athlete->achievement_history) && count($athlete->achievement_history)
            ? $athlete->achievement_history
            : [''];
        $this->bpjs_number = $athlete->bpjs_number;
        $this->bpjs_status = $athlete->bpjs_status ?? 'TIDAK_AKTIF';
        $this->selectedCategories = $athlete->categories->pluck('id')->toArray();
        $this->existing_bpjs_card_path = $athlete->bpjs_card_path;
        $this->existing_identity_card_path = $athlete->identity_card_path;

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
    }

    public function resetForm(): void
    {
        $this->isEdit = false;
        $this->athleteId = null;
        $this->nik = '';
        $this->name = $this->gender = $this->birth_date = $this->birth_place = '';
        $this->address = $this->phone = $this->blood_type = '';
        $this->contingent_id = $this->weight = $this->kyu = $this->dojo_origin = $this->city = '';
        $this->age = $this->age_group = $this->match_type = $this->rank = '';
        $this->bpjs_number = '';
        $this->bpjs_status = 'TIDAK_AKTIF';
        $this->achievement_history = [''];
        $this->selectedCategories = [];
        $this->existing_bpjs_card_path = $this->existing_identity_card_path = null;
        $this->showForm = false;
        $this->resetErrorBag();
    }

    public function addAchievement(): void
    {
        $this->achievement_history[] = '';
    }

    public function removeAchievement(int $index): void
    {
        unset($this->achievement_history[$index]);
        $this->achievement_history = array_values($this->achievement_history);

        if (empty($this->achievement_history)) {
            $this->achievement_history = [''];
        }
    }

    public function save(): void
    {
        $this->validate([
            'name' => 'required|min:3',
            'nik' => 'required|numeric|digits:16',
            'contingent_id' => 'required|exists:contingents,id',
            'gender' => 'required|in:Male,Female',
            'birth_date' => 'required|date',
            'weight' => 'required|numeric',
            'kyu' => 'required',
            'dojo_origin' => 'required',
            'city' => 'required',
            'bpjs_status' => 'required',
            'bpjs_card' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048',
            'identity_card' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048',
        ]);

        $masterData = [
            'nik' => $this->nik,
            'name' => $this->name,
            'gender' => $this->gender,
            'birth_date' => $this->birth_date,
            'birth_place' => $this->birth_place,
            'address' => $this->address,
            'phone' => $this->phone,
            'blood_type' => $this->blood_type,
            'achievement_history' => array_values(array_filter($this->achievement_history)),
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

        if ($this->bpjs_card) {
            $masterData['bpjs_card_path'] = $this->bpjs_card->store('athletes/bpjs', 'public');
        }
        if ($this->identity_card) {
            $masterData['identity_card_path'] = $this->identity_card->store('athletes/identity', 'public');
        }

        DB::transaction(function () use ($masterData, $pivotData) {
            if ($this->isEdit) {
                $athlete = Athlete::findOrFail($this->athleteId);
                $oldContingentId = $athlete->contingent?->id;
                $athlete->update($masterData);

                if ($oldContingentId != $this->contingent_id) {
                    $athlete->contingents()->wherePivot('is_primary', true)
                        ->updateExistingPivot($oldContingentId, ['is_primary' => false]);
                    $athlete->contingents()->syncWithoutDetaching([
                        $this->contingent_id => ['is_primary' => true, 'joined_at' => now()],
                    ]);
                    $athlete->contingentHistories()->create([
                        'contingent_id' => $this->contingent_id,
                        'moved_at' => now(),
                        'notes' => 'Perpindahan kontingen via panel admin.',
                    ]);
                } else {
                    $athlete->contingents()->syncWithoutDetaching([
                        $this->contingent_id => ['is_primary' => true],
                    ]);
                }

                $registration = Contingent::find($this->contingent_id)?->registrations()->latest()->first();
                if ($registration) {
                    $athlete->registrations()->syncWithoutDetaching([$registration->id => $pivotData]);
                }
                $athlete->categories()->sync($this->selectedCategories);
                $this->athleteId = $athlete->id;
            } else {
                $athlete = Athlete::create($masterData);
                $athlete->contingents()->attach([
                    $this->contingent_id => ['is_primary' => true, 'joined_at' => now()],
                ]);
                $athlete->contingentHistories()->create([
                    'contingent_id' => $this->contingent_id,
                    'moved_at' => now(),
                    'notes' => 'Kontingen pendaftaran awal.',
                ]);
                $registration = Contingent::find($this->contingent_id)?->registrations()->latest()->first();
                if ($registration) {
                    $athlete->registrations()->attach($registration->id, $pivotData);
                }
                $athlete->categories()->sync($this->selectedCategories);
                $this->athleteId = $athlete->id;
            }
        });

        $this->redirect(route('admin.new-athletes.show', $this->athleteId));
    }

    public function render()
    {
        return view('livewire.admin.new-athlete-create', [
            'contingents' => Contingent::orderBy('name')->get(),
            'categories' => Category::orderBy('name')->get(),
            'kyuLevels' => KyuLevel::orderBy('name')->get(),
        ])->layout('layouts.premium', ['title' => 'Tambah Atlet']);
    }
}

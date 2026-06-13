<?php

namespace App\Livewire\Admin;

use App\Models\Athlete;
use App\Models\Category;
use App\Models\Contingent;
use App\Models\KyuLevel;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Livewire\WithFileUploads;

class NewAthleteEdit extends Component
{
    use WithFileUploads;

    public $athleteId;

    // Biodata
    public $nik;

    public $name;

    public $gender;

    public $birth_date;

    public $birth_place;

    public $address;

    public $phone;

    public $blood_type;

    public $achievement_history = [''];

    // Technical
    public $contingent_id;

    public $weight;

    public $kyu;

    public $dojo_origin;

    public $city;

    public $age;

    public $age_group;

    public $match_type;

    public $rank;

    // BPJS
    public $bpjs_number;

    public $bpjs_status = 'TIDAK_AKTIF';

    // Files
    public $bpjs_card;

    public $identity_card;

    public $identity_document;

    public $existing_bpjs_card_path;

    public $existing_identity_card_path;

    public $existing_identity_document_path;

    // Categories
    public $selectedCategories = [];

    // Active section for mobile
    public string $activeSection = 'biodata';

    public function mount(Athlete $athlete): void
    {
        $this->athleteId = $athlete->id;
        $this->fill($athlete->toArray());

        $this->birth_date = $athlete->birth_date?->format('Y-m-d');
        $this->achievement_history = is_array($athlete->achievement_history) && count($athlete->achievement_history)
            ? $athlete->achievement_history
            : [''];

        $this->selectedCategories = $athlete->categories->pluck('id')->toArray();

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

    public function switchSection(string $section): void
    {
        $this->activeSection = $section;
    }

    public function save(): void
    {
        $this->validate([
            'name' => 'required|min:3',
            'nik' => 'required|numeric|digits:16',
            'contingent_id' => 'required|exists:contingents,id',
            'gender' => 'required|in:Male,Female,L,P',
            'birth_date' => 'required|date',
            'weight' => 'required|numeric',
            'kyu' => 'required',
            'dojo_origin' => 'required',
            'city' => 'required',
            'bpjs_status' => 'required',
            'bpjs_card' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048',
            'identity_card' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048',
            'identity_document' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048',
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
        if ($this->identity_document) {
            $masterData['identity_document_path'] = $this->identity_document->store('athletes/documents', 'public');
        }

        DB::transaction(function () use ($masterData, $pivotData) {
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
        });

        $this->dispatch('swal', [
            'title' => 'Berhasil!',
            'text' => 'Data atlet berhasil diperbarui.',
            'icon' => 'success',
        ]);

        $this->redirect(route('admin.new-athletes.show', $this->athleteId));
    }

    public function render()
    {
        return view('livewire.admin.new-athlete-edit', [
            'contingents' => Contingent::orderBy('name')->get(),
            'categories' => Category::orderBy('name')->get(),
            'kyuLevels' => KyuLevel::orderBy('name')->get(),
            'athlete' => Athlete::find($this->athleteId),
        ])->layout('layouts.premium', ['title' => 'Edit Atlet']);
    }
}

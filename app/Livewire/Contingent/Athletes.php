<?php

namespace App\Livewire\Contingent;

use App\Models\Athlete;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\WithPagination;

#[Layout('layouts.premium')]
class Athletes extends Component
{
    use WithFileUploads, WithPagination;

    public $contingent;

    public $search = '';

    // Form fields
    public $athleteId = null;

    public $name = '';

    public $nik = '';

    public $nik_kenshi = '';

    public $gender = 'Male'; // Based on model getGenderIndoAttribute

    public $birth_place = '';

    public $birth_date = '';

    public $blood_type = '-';

    public $phone = '';

    public $address = '';

    public $dojo_origin = '';

    public $bpjs_number = '';

    public $bpjs_status = 'Aktif';

    public $photo = null;

    public $photo_path = null;

    public $isEditing = false;

    public function mount()
    {
        $user = Auth::user();
        if (! $user || ! $user->contingent()->exists()) {
            return redirect()->route('contingent.setup');
        }
        $this->contingent = $user->contingent;
    }

    public function updatedSearch()
    {
        $this->resetPage();
    }

    public function openCreate()
    {
        $this->resetForm();
        $this->isEditing = true;
    }

    public function openEdit($id)
    {
        $athlete = Athlete::findOrFail($id);
        $this->athleteId = $athlete->id;
        $this->name = $athlete->name;
        $this->nik = $athlete->nik;
        $this->nik_kenshi = $athlete->nik_kenshi;
        $this->gender = $athlete->gender ?? 'Male';
        $this->birth_place = $athlete->birth_place;
        $this->birth_date = $athlete->birth_date ? $athlete->birth_date->format('Y-m-d') : '';
        $this->blood_type = $athlete->blood_type;
        $this->phone = $athlete->phone;
        $this->address = $athlete->address;
        $this->dojo_origin = $athlete->dojo_origin;
        $this->bpjs_number = $athlete->bpjs_number;
        $this->bpjs_status = $athlete->bpjs_status ?? 'Aktif';
        $this->photo_path = $athlete->photo_path;

        $this->isEditing = true;
    }

    public function resetForm()
    {
        $this->athleteId = null;
        $this->name = '';
        $this->nik = '';
        $this->nik_kenshi = '';
        $this->gender = 'Male';
        $this->birth_place = '';
        $this->birth_date = '';
        $this->blood_type = '-';
        $this->phone = '';
        $this->address = '';
        $this->dojo_origin = '';
        $this->bpjs_number = '';
        $this->bpjs_status = 'Aktif';
        $this->photo = null;
        $this->photo_path = null;
        $this->isEditing = false;
    }

    public function save()
    {
        $this->validate([
            'name' => 'required|string|max:255',
            'nik' => 'nullable|string|max:16',
            'nik_kenshi' => 'nullable|string|max:255',
            'gender' => 'required|in:Male,Female',
            'birth_date' => 'nullable|date',
            'photo' => 'nullable|image|max:2048',
        ]);

        $data = [
            'name' => $this->name,
            'nik' => $this->nik,
            'nik_kenshi' => $this->nik_kenshi,
            'gender' => $this->gender,
            'birth_place' => $this->birth_place,
            'birth_date' => $this->birth_date,
            'blood_type' => $this->blood_type,
            'phone' => $this->phone,
            'address' => $this->address,
            'dojo_origin' => $this->dojo_origin,
            'bpjs_number' => $this->bpjs_number,
            'bpjs_status' => $this->bpjs_status,
        ];

        if ($this->photo) {
            $data['photo_path'] = $this->photo->store('athlete_photos', 'public');
        }

        if ($this->athleteId) {
            $athlete = Athlete::findOrFail($this->athleteId);
            $athlete->update($data);
            $msg = 'Data atlet berhasil diperbarui.';
        } else {
            $athlete = Athlete::create($data);
            // Link to contingent via pivot
            $athlete->contingents()->syncWithoutDetaching([
                $this->contingent->id => [
                    'is_primary' => true,
                    'joined_at' => now(),
                ],
            ]);
            $msg = 'Atlet baru berhasil ditambahkan.';
        }

        $this->resetForm();
        $this->dispatch('swal', ['icon' => 'success', 'title' => 'Berhasil', 'text' => $msg]);
    }

    public function delete($id)
    {
        $athlete = Athlete::findOrFail($id);
        // Only detach if we want to keep it in master, but usually user wants it gone from their view
        $athlete->contingents()->detach($this->contingent->id);

        // If it has no more contingents, maybe delete?
        // For simplicity and to avoid orphaned data in this specific project context:
        if ($athlete->contingents()->count() === 0) {
            $athlete->delete();
        }

        $this->dispatch('swal', ['icon' => 'success', 'title' => 'Terhapus', 'text' => 'Atlet telah dihapus dari data Anda.']);
    }

    public function render()
    {
        $athletes = $this->contingent->athletes()
            ->when($this->search, function ($q) {
                $q->where('name', 'ilike', '%'.$this->search.'%')
                    ->orWhere('nik', 'ilike', '%'.$this->search.'%')
                    ->orWhere('nik_kenshi', 'ilike', '%'.$this->search.'%');
            })
            ->latest()
            ->paginate(10);

        return view('livewire.contingent.athletes', [
            'athletes' => $athletes,
        ]);
    }
}

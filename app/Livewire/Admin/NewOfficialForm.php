<?php

namespace App\Livewire\Admin;

use App\Models\Contingent;
use App\Models\Official;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class NewOfficialForm extends Component
{
    public $officialId = null;

    public $isEdit = false;

    // Form Fields
    public $contingent_id;

    public $name;

    public $phone;

    public $role = 'Official';

    public function mount($id = null)
    {
        $this->contingent_id = Auth::user()?->contingent?->id;

        if ($id) {
            $this->isEdit = true;
            $this->officialId = $id;
            $officialModel = Official::findOrFail($id);

            $this->name = $officialModel->name;
            $this->phone = $officialModel->phone;
            $this->role = $officialModel->role;

            $latestReg = $officialModel->latestRegistration();
            if ($latestReg) {
                $this->contingent_id = $latestReg->contingent_id;
            } else {
                $this->contingent_id = $officialModel->contingent_id;
            }
        }
    }

    public function save()
    {
        $this->validate([
            'name' => 'required|min:3',
            'phone' => 'required|numeric',
            'contingent_id' => 'required|exists:contingents,id',
            'role' => 'required',
        ]);

        $data = [
            'name' => $this->name,
            'phone' => $this->phone,
            'role' => $this->role,
            'contingent_id' => $this->contingent_id,
        ];

        if ($this->isEdit) {
            $official = Official::findOrFail($this->officialId);
            $official->update($data);

            $registration = Contingent::find($this->contingent_id)->registrations()->latest()->first();
            if ($registration) {
                $official->registrations()->syncWithoutDetaching([
                    $registration->id => ['role' => $this->role],
                ]);
            }

            $this->dispatch('swal', [
                'title' => 'Berhasil!',
                'text' => 'Data official diperbarui.',
                'icon' => 'success',
            ]);
        } else {
            $official = Official::create($data);

            $registration = Contingent::find($this->contingent_id)->registrations()->latest()->first();
            if ($registration) {
                $official->registrations()->attach($registration->id, ['role' => $this->role]);
            }

            $this->dispatch('swal', [
                'title' => 'Berhasil!',
                'text' => 'Official baru ditambahkan.',
                'icon' => 'success',
            ]);
        }

        return $this->redirect(route('admin.new-officials'), navigate: true);
    }

    public function render()
    {
        return view('livewire.admin.new-official-form', [
            'contingents' => Contingent::orderBy('name')->get(),
        ])->layout('layouts.premium', ['title' => $this->isEdit ? 'Edit Official' : 'Tambah Official']);
    }
}

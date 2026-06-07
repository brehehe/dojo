<?php

namespace App\Livewire\Admin\Master\Official;

use App\Models\Contingent;
use App\Models\Official;
use Auth;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.admin')]
class AdminMasterOfficialFormIndex extends Component
{
    public $officialId = null;

    public $isEdit = false;

    // Form Fields
    public $contingent_id;

    public $name;

    public $phone;

    public $role = 'Official';

    public function mount($official = null)
    {
        $this->contingent_id = Auth::user()?->contingent?->id;

        if ($official) {
            $this->isEdit = true;
            $this->officialId = $official;
            $officialModel = Official::findOrFail($official);

            $this->name = $officialModel->name;
            $this->phone = $officialModel->phone;
            $this->role = $officialModel->role;

            // Get latest contingent and role attribution
            $latestReg = $officialModel->latestRegistration();
            if ($latestReg) {
                $this->contingent_id = $latestReg->contingent_id;
                // $this->role = $latestReg->pivot->role;
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

            // Link to registration if contingent changed or ensure linked to latest
            $registration = Contingent::find($this->contingent_id)->registrations()->latest()->first();
            if ($registration) {
                $official->registrations()->syncWithoutDetaching([
                    $registration->id => ['role' => $this->role],
                ]);
            }

            $this->dispatch('swal', title: 'Berhasil!', text: 'Data official diperbarui.', icon: 'success');
        } else {
            $official = Official::create($data);

            // Link to registration
            $registration = Contingent::find($this->contingent_id)->registrations()->latest()->first();
            if ($registration) {
                $official->registrations()->attach($registration->id, ['role' => $this->role]);
            }

            $this->dispatch('swal', title: 'Berhasil!', text: 'Official baru ditambahkan.', icon: 'success');
        }

        return redirect()->route('admin.master.officials.index');
    }

    public function render()
    {
        return view('livewire.admin.master.official.admin-master-official-form-index', [
            'contingents' => Contingent::orderBy('name')->get(),
        ]);
    }
}

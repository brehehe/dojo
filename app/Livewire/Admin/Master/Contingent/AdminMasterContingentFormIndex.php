<?php

namespace App\Livewire\Admin\Master\Contingent;

use App\Models\Contingent;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithFileUploads;

#[Layout('layouts.admin')]
class AdminMasterContingentFormIndex extends Component
{
    use WithFileUploads;

    public $contingentId;

    public $isEdit = false;

    // Form fields
    public $name;

    public $leader_name;

    public $leader_phone;

    public $email;

    public $address;

    public $kab_kota;

    public function mount($id = null)
    {
        if ($id) {
            $this->isEdit = true;
            $this->contingentId = $id;
            $contingent = Contingent::findOrFail($id);

            $this->name = $contingent->name;
            $this->leader_name = $contingent->leader_name;
            $this->leader_phone = $contingent->leader_phone;
            $this->email = $contingent->email;
            $this->address = $contingent->address;
            $this->kab_kota = $contingent->kab_kota;
        }
    }

    public function save()
    {
        $rules = [
            'name' => 'required|string|max:255',
            'leader_name' => 'required|string|max:255',
            'leader_phone' => 'required|string|max:20',
            'email' => 'required|email|max:255',
            'kab_kota' => 'required|string',
        ];

        $this->validate($rules);

        $data = [
            'name' => $this->name,
            'leader_name' => $this->leader_name,
            'leader_phone' => $this->leader_phone,
            'email' => $this->email,
            'address' => $this->address,
            'kab_kota' => $this->kab_kota,
        ];

        if ($this->isEdit) {
            $contingent = Contingent::findOrFail($this->contingentId);
            $contingent->update($data);
            $this->dispatch('swal', title: 'Berhasil!', text: 'Data kontingen diperbarui.', icon: 'success');
        } else {
            Contingent::create($data);
            $this->dispatch('swal', title: 'Berhasil!', text: 'Kontingen baru didaftarkan.', icon: 'success');
        }

        return $this->redirect(route('admin.master.contingents.index'), navigate: true);
    }

    public function render()
    {
        return view('livewire.admin.master.contingent.admin-master-contingent-form-index');
    }
}

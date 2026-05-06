<?php

namespace App\Livewire\Admin;

use App\Models\Contingent;
use Livewire\Component;
use Livewire\WithFileUploads;

class NewContingentForm extends Component
{
    use WithFileUploads;

    public $contingentId;

    public $isEdit = false;

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
        $this->validate([
            'name' => 'required|string|max:255',
            'leader_name' => 'required|string|max:255',
            'leader_phone' => 'required|string|max:20',
            'email' => 'required|email|max:255',
            'kab_kota' => 'required|string',
        ]);

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
            $this->dispatch('swal', [
                'title' => 'Berhasil!',
                'text' => 'Data kontingen diperbarui.',
                'icon' => 'success',
            ]);
        } else {
            Contingent::create($data);
            $this->dispatch('swal', [
                'title' => 'Berhasil!',
                'text' => 'Kontingen baru didaftarkan.',
                'icon' => 'success',
            ]);
        }

        return $this->redirect(route('admin.new-contingents'), navigate: true);
    }

    public function render()
    {
        return view('livewire.admin.new-contingent-form')
            ->layout('layouts.premium', ['title' => $this->isEdit ? 'Edit Kontingen' : 'Tambah Kontingen']);
    }
}

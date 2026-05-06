<?php

namespace App\Livewire\Admin;

use App\Models\Contingent;
use App\Models\Registration;
use Illuminate\Support\Str;
use Livewire\Component;

class NewContingentDetail extends Component
{
    public $contingent;

    public $activeTab = 'profile'; // profile, registration, atlet, official, history

    public $selectedRegistrationId;

    public function mount(Contingent $contingent)
    {
        $this->contingent = $contingent->load(['registrations.athletes.categories', 'registrations.officials']);

        $latestRegistration = $this->contingent->registrations()->latest()->first();
        if ($latestRegistration) {
            $this->selectedRegistrationId = $latestRegistration->id;
        }
    }

    public function getRegistrationProperty()
    {
        return Registration::find($this->selectedRegistrationId);
    }

    public function switchTab($tab)
    {
        $this->activeTab = $tab;
    }

    public function selectRegistration($id)
    {
        $this->selectedRegistrationId = $id;
        $this->activeTab = 'registration';
    }

    public function createNewRegistration()
    {
        $registration = Registration::create([
            'contingent_id' => $this->contingent->id,
            'referral_code' => strtoupper(Str::random(8)),
            'unique_code' => rand(100, 999),
            'status' => 'PENDING',
        ]);

        $this->contingent->load('registrations');
        $this->selectedRegistrationId = $registration->id;
        $this->activeTab = 'registration';

        $this->dispatch('swal', [
            'title' => 'Berhasil!',
            'text' => 'Batch pendaftaran baru berhasil dibuat.',
            'icon' => 'success',
        ]);
    }

    public function render()
    {
        return view('livewire.admin.new-contingent-detail')
            ->layout('layouts.premium', ['title' => 'Detail Kontingen']);
    }
}

<?php

namespace App\Livewire\Admin\Registration;

use App\Models\Registration;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('layouts.admin')]
class AdminRegistrationIndex extends Component
{
    use WithPagination;

    public $search = '';
    public $status = '';
    public $perPage = 5;

    protected $queryString = ['search', 'status', 'perPage'];

    public function updatedSearch()
    {
        $this->resetPage();
    }

    public function updatedStatus()
    {
        $this->resetPage();
    }

    public function deleteRegistration($id)
    {
        $registration = Registration::findOrFail($id);
        $registration->delete();

        $this->dispatch('swal', [
            'title' => 'Terhapus!',
            'text' => 'Data pendaftaran berhasil dihapus.',
            'icon' => 'success',
        ]);
    }

    public function render()
    {
        $registrations = Registration::with('contingent')
            ->when($this->search, function ($query) {
                $query->where('referral_code', 'ilike', '%' . $this->search . '%')
                    ->orWhereHas('contingent', function ($q) {
                        $q->where('name', 'ilike', '%' . $this->search . '%');
                    });
            })
            ->when($this->status, function ($query) {
                $query->where('status', $this->status);
            })
            ->latest()
            ->paginate($this->perPage);

        return view('livewire.admin.registration.admin-registration-index', [
            'registrations' => $registrations,
        ]);
    }
}

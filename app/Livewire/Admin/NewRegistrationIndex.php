<?php

namespace App\Livewire\Admin;

use App\Models\Registration;
use Livewire\Component;
use Livewire\WithPagination;

class NewRegistrationIndex extends Component
{
    use WithPagination;

    public string $search = '';

    public string $status = '';

    public int $perPage = 10;

    protected $queryString = ['search', 'status', 'perPage'];

    public function updatedSearch(): void
    {
        $this->resetPage();
    }

    public function updatedStatus(): void
    {
        $this->resetPage();
    }

    public function deleteRegistration(int $id): void
    {
        $registration = Registration::findOrFail($id);
        $registration->delete();

        $this->dispatch('swal', [
            'title' => 'Berhasil Dihapus!',
            'text' => 'Data pendaftaran berhasil dihapus dari sistem.',
            'icon' => 'success',
            'timer' => 3000,
        ]);
    }

    public function getStats(): array
    {
        return [
            'total' => Registration::count(),
            'pending' => Registration::where('status', 'pending')->count(),
            'verified' => Registration::where('status', 'verified')->count(),
            'rejected' => Registration::where('status', 'rejected')->count(),
            'total_amount' => Registration::where('status', 'verified')->sum('final_amount'),
        ];
    }

    public function render()
    {
        $registrations = Registration::with(['contingent'])
            ->when($this->search, function ($query) {
                $query->where('referral_code', 'ilike', '%'.$this->search.'%')
                    ->orWhereHas('contingent', function ($q) {
                        $q->where('name', 'ilike', '%'.$this->search.'%');
                    });
            })
            ->when($this->status, function ($query) {
                $query->where('status', $this->status);
            })
            ->latest()
            ->paginate($this->perPage);

        return view('livewire.admin.new-registration-index', [
            'registrations' => $registrations,
            'stats' => $this->getStats(),
        ])->layout('layouts.premium', ['title' => 'Data Registrasi']);
    }
}

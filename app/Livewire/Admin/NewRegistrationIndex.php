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

    public array $selectedRows = [];

    public bool $selectAll = false;

    /** @var array<int> IDs of expanded registration rows */
    public array $expanded = [];

    protected $queryString = ['search', 'status', 'perPage'];

    public function updatedSearch(): void
    {
        $this->resetPage();
        $this->selectedRows = [];
        $this->selectAll = false;
    }

    public function updatedStatus(): void
    {
        $this->resetPage();
        $this->selectedRows = [];
        $this->selectAll = false;
    }

    public function updatedPerPage(): void
    {
        $this->resetPage();
        $this->selectedRows = [];
        $this->selectAll = false;
    }

    public function updatedSelectAll($value): void
    {
        if ($value) {
            $this->selectedRows = $this->getFilteredRegistrationsQuery()
                ->pluck('id')
                ->map(fn ($id) => (string) $id)
                ->toArray();
        } else {
            $this->selectedRows = [];
        }
    }

    public function verifySelected(): void
    {
        if (empty($this->selectedRows)) {
            return;
        }

        Registration::whereIn('id', $this->selectedRows)->update(['status' => 'verified']);

        $count = count($this->selectedRows);
        $this->selectedRows = [];
        $this->selectAll = false;

        $this->dispatch('swal', [
            'title' => 'Terverifikasi!',
            'text' => "{$count} pendaftaran berhasil diverifikasi.",
            'icon' => 'success',
            'timer' => 3000,
        ]);
    }

    public function unverifySelected(): void
    {
        if (empty($this->selectedRows)) {
            return;
        }

        Registration::whereIn('id', $this->selectedRows)->update(['status' => 'pending']);

        $count = count($this->selectedRows);
        $this->selectedRows = [];
        $this->selectAll = false;

        $this->dispatch('swal', [
            'title' => 'Unverified!',
            'text' => "{$count} pendaftaran diubah status menjadi pending.",
            'icon' => 'warning',
            'timer' => 3000,
        ]);
    }

    /**
     * Toggle the expanded/collapsed state of a registration row.
     */
    public function toggleExpand(int $registrationId): void
    {
        if (in_array($registrationId, $this->expanded, true)) {
            $this->expanded = array_values(array_diff($this->expanded, [$registrationId]));
        } else {
            $this->expanded[] = $registrationId;
        }
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

    protected function getFilteredRegistrationsQuery()
    {
        return Registration::when($this->search, function ($query) {
            $query->where('referral_code', 'ilike', '%'.$this->search.'%')
                ->orWhereHas('contingent', function ($q) {
                    $q->where('name', 'ilike', '%'.$this->search.'%');
                });
        })
            ->when($this->status, function ($query) {
                $query->where('status', $this->status);
            });
    }

    public function render()
    {
        $registrations = $this->getFilteredRegistrationsQuery()
            ->with(['contingent', 'athletes.matchNumbers', 'officials'])
            ->latest()
            ->paginate($this->perPage);

        return view('livewire.admin.new-registration-index', [
            'registrations' => $registrations,
            'stats' => $this->getStats(),
        ])->layout('layouts.premium', ['title' => 'Data Registrasi']);
    }
}

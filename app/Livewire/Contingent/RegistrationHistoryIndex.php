<?php

namespace App\Livewire\Contingent;

use App\Models\Registration;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('layouts.premium')]
class RegistrationHistoryIndex extends Component
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

    public function render()
    {
        $contingent = Auth::user()->contingent;

        if (! $contingent) {
            return redirect()->route('contingent.setup');
        }

        $registrations = Registration::where('contingent_id', $contingent->id)
            ->when($this->search, function ($query) {
                $query->where('referral_code', 'ilike', '%'.$this->search.'%');
            })
            ->when($this->status, function ($query) {
                $query->where('status', $this->status);
            })
            ->latest()
            ->paginate($this->perPage);

        return view('livewire.contingent.registration-history-index', [
            'registrations' => $registrations,
        ])->title('Riwayat Pendaftaran');
    }
}

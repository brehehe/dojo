<?php

namespace App\Livewire\Contingent;

use App\Models\RefereeObservation;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('layouts.premium')]
class RefereeObservationIndex extends Component
{
    use WithPagination;

    public string $search = '';

    public int $perPage = 10;

    public $contingent;

    protected $queryString = ['search', 'perPage'];

    public function mount()
    {
        $user = Auth::user();
        if (! $user->contingent()->exists()) {
            return redirect()->route('contingent.setup');
        }
        $this->contingent = $user->contingent;
    }

    public function updatedSearch(): void
    {
        $this->resetPage();
    }

    public function deleteObservation($id): void
    {
        $observation = RefereeObservation::where('contingent_id', $this->contingent->id)->findOrFail($id);
        $observation->delete();

        $this->dispatch('swal', [
            'title' => 'Berhasil!',
            'text' => 'Observasi wasit telah dihapus.',
            'icon' => 'success',
        ]);
    }

    public function render()
    {
        $operator = DB::connection()->getDriverName() === 'sqlite' ? 'like' : 'ilike';

        $observations = RefereeObservation::with('referee.user')
            ->where('contingent_id', $this->contingent->id)
            ->when($this->search, function ($query) use ($operator) {
                $query->where(function ($sub) use ($operator) {
                    $sub->where('observer_name', $operator, '%'.$this->search.'%')
                        ->orWhere('court', $operator, '%'.$this->search.'%')
                        ->orWhere('round', $operator, '%'.$this->search.'%')
                        ->orWhereHas('referee.user', function ($q) use ($operator) {
                            $q->where('name', $operator, '%'.$this->search.'%');
                        });
                });
            })
            ->latest()
            ->paginate($this->perPage);

        return view('livewire.contingent.referee-observation-index', [
            'observations' => $observations,
        ])->title('Observasi Wasit - '.$this->contingent->name);
    }
}

<?php

namespace App\Livewire\Admin;

use App\Models\Athlete;
use App\Models\Contingent;
use Livewire\Component;
use Livewire\WithPagination;

class NewAthleteIndex extends Component
{
    use WithPagination;

    public string $search = '';

    public int $perPage = 10;

    public string $filterContingent = '';

    public string $filterGender = '';

    protected $queryString = ['search', 'perPage', 'filterContingent', 'filterGender'];

    public function updatedSearch(): void
    {
        $this->resetPage();
    }

    public function updatedFilterContingent(): void
    {
        $this->resetPage();
    }

    public function updatedFilterGender(): void
    {
        $this->resetPage();
    }

    public function deleteAthleteById(int $id): void
    {
        $athlete = Athlete::findOrFail($id);
        $athlete->delete();

        $this->dispatch('swal', [
            'title' => 'Berhasil!',
            'text' => 'Data atlet berhasil dihapus.',
            'icon' => 'success',
        ]);
    }

    public function getStats(): array
    {
        return [
            'total' => Athlete::count(),
            'male' => Athlete::where('gender', 'L')->count(),
            'female' => Athlete::where('gender', 'P')->count(),
            'contingents' => Contingent::count(),
        ];
    }

    public function render()
    {
        $athletes = Athlete::with(['contingents', 'categories'])
            ->when($this->search, function ($query) {
                $query->where('name', 'ilike', '%'.$this->search.'%')
                    ->orWhere('nik', 'ilike', '%'.$this->search.'%');
            })
            ->when($this->filterContingent, function ($query) {
                $query->whereHas('contingents', function ($q) {
                    $q->where('contingent_id', $this->filterContingent);
                });
            })
            ->when($this->filterGender, function ($query) {
                $query->where('gender', $this->filterGender);
            })
            ->latest()
            ->paginate($this->perPage);

        $contingents = Contingent::orderBy('name')->get();

        return view('livewire.admin.new-athlete-index', [
            'athletes' => $athletes,
            'contingents' => $contingents,
            'stats' => $this->getStats(),
        ])->layout('layouts.premium', ['title' => 'Data Atlet']);
    }
}

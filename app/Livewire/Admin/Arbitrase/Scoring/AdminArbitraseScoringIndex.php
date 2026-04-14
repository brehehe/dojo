<?php

namespace App\Livewire\Admin\Arbitrase\Scoring;

use App\Models\MatchNumber\MatchNumber;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('layouts.admin')]
class AdminArbitraseScoringIndex extends Component
{
    use WithPagination;

    public $search = '';

    public $type = 'all'; // all, embu, randori

    public function activateMatch($id)
    {
        // Deactivate all matches
        MatchNumber::where('is_active', true)->update(['is_active' => false]);

        // Activate the selected one
        $match = MatchNumber::findOrFail($id);
        $match->update(['is_active' => true]);

        $this->dispatch('swal', [
            'icon' => 'success',
            'title' => 'Pertandingan Aktif',
            'text' => $match->name . ' telah dipanggil ke lapangan.',
        ]);
    }

    public function render()
    {
        $query = MatchNumber::whereNotNull('drawing_data')
            ->has('athletes')
            ->orderBy('name', 'asc')
            ->with(['ageGroup']);

        if ($this->type !== 'all') {
            $query->where('draft_type', $this->type);
        }

        if (! empty($this->search)) {
            $query->where('name', 'ilike', '%' . $this->search . '%');
        }

        return view('livewire.admin.arbitrase.scoring.admin-arbitrase-scoring-index', [
            'matches' => $query->latest()->paginate(12),
        ]);
    }
}

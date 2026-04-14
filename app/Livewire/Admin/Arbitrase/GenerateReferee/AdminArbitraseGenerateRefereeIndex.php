<?php

namespace App\Livewire\Admin\Arbitrase\GenerateReferee;

use App\Models\MatchNumber\MatchNumber;
use App\Models\Referee;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('layouts.admin')]
class AdminArbitraseGenerateRefereeIndex extends Component
{
    use WithPagination;

    public $searchMatch = '';

    public $assigningMatchId = null;

    public $searchReferee = '';

    public $selectedReferees = [];

    public function paginationView(): string
    {
        return 'livewire.admin.pagination';
    }

    public function openAssignModal($matchId)
    {
        $this->assigningMatchId = $matchId;
        $match = MatchNumber::with('referees')->findOrFail($matchId);

        // Map to string for easy checkbox binding in Livewire
        $this->selectedReferees = $match->referees->pluck('id')->map(fn ($id) => (string) $id)->toArray();
        $this->searchReferee = '';
    }

    public function closeAssignModal()
    {
        $this->assigningMatchId = null;
        $this->selectedReferees = [];
        $this->searchReferee = '';
    }

    public function saveReferees()
    {
        if (count($this->selectedReferees) < 5) {
            $this->addError('referees', 'Minimal 5 Wasit harus dipilih untuk satu pertandingan.');

            return;
        }

        $match = MatchNumber::findOrFail($this->assigningMatchId);
        
        // Assign judge_index 1-5
        $syncData = [];
        foreach ($this->selectedReferees as $index => $refereeId) {
            $syncData[$refereeId] = ['judge_index' => $index + 1];
        }

        $match->referees()->sync($syncData);

        $this->closeAssignModal();
        session()->flash('message', 'Panel wasit untuk ' . $match->name . ' berhasil disimpan.');
        $this->dispatch('referees-saved');
    }

    public function autoGenerateAllReferees()
    {
        $allRefereeIds = Referee::pluck('id')->toArray();

        if (count($allRefereeIds) < 5) {
            session()->flash('error', 'Data Master Wasit tidak mencukupi (Minimal 5).');

            return;
        }

        $matches = MatchNumber::has('athletes')->get();
        $countGenerated = 0;

        foreach ($matches as $match) {
            // Only generate if not already have 5+ referees
            if ($match->referees()->count() < 5) {
                $randomIds = collect($allRefereeIds)->random(5)->toArray();
                
                $syncData = [];
                foreach ($randomIds as $index => $refereeId) {
                    $syncData[$refereeId] = ['judge_index' => $index + 1];
                }

                $match->referees()->sync($syncData);
                $countGenerated++;
            }
        }

        session()->flash('message', "Otomatisasi Selesai! $countGenerated Pertandingan berhasil ditugaskan wasit baru.");
    }

    public function render()
    {
        // Only get matches that have athletes
        $matchesQuery = MatchNumber::has('athletes')
            ->with(['ageGroup', 'referees.user', 'athletes.registrations.contingent']);

        if (! empty($this->searchMatch)) {
            $matchesQuery->where(function ($q) {
                $q->where('name', 'ilike', '%'.$this->searchMatch.'%')
                    ->orWhere('draft_type', 'ilike', '%'.$this->searchMatch.'%');
            });
        }

        $paginatedMatches = $matchesQuery->latest()->paginate(10);

        // Get referees for selection
        $refereesQuery = Referee::with('user');
        if (! empty($this->searchReferee)) {
            $refereesQuery->whereHas('user', function ($q) {
                $q->where('name', 'ilike', '%'.$this->searchReferee.'%');
            })->orWhere('license_number', 'ilike', '%'.$this->searchReferee.'%')
                ->orWhere('certification_level', 'ilike', '%'.$this->searchReferee.'%');
        }

        // Sorting referees reasonably
        $referees = $refereesQuery->get()->sortBy([
            ['certification_level', 'asc'],
        ]);

        return view('livewire.admin.arbitrase.generate-referee.admin-arbitrase-generate-referee-index', [
            'paginatedMatches' => $paginatedMatches,
            'availableReferees' => $refereesQuery->paginate(50, ['*'], 'refereePage'), // or we can just get all, but 100+ is okay for simple foreach.
            'allReferees' => $referees,
        ]);
    }
}

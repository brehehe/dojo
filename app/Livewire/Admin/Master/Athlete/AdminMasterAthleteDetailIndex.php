<?php

namespace App\Livewire\Admin\Master\Athlete;

use App\Models\Athlete;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.admin')]
class AdminMasterAthleteDetailIndex extends Component
{
    public $athlete;

    public $activeTab = 'identity'; // identity, medical, categories

    public function mount(Athlete $athlete)
    {
        $this->athlete = $athlete->load(['registrations.contingent', 'categories', 'contingentHistories.contingent']);
    }

    public function switchTab($tab)
    {
        $this->activeTab = $tab;
    }

    public function render()
    {
        return view('livewire.admin.master.athlete.admin-master-athlete-detail-index');
    }
}

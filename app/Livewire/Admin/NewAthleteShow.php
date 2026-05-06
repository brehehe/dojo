<?php

namespace App\Livewire\Admin;

use App\Models\Athlete;
use Livewire\Component;

class NewAthleteShow extends Component
{
    public $athlete;

    public string $activeTab = 'identity';

    public function mount(Athlete $athlete): void
    {
        $this->athlete = $athlete->load([
            'registrations.contingent',
            'categories',
            'contingentHistories.contingent',
        ]);
    }

    public function switchTab(string $tab): void
    {
        $this->activeTab = $tab;
    }

    public function render()
    {
        return view('livewire.admin.new-athlete-show')
            ->layout('layouts.premium', ['title' => 'Detail Atlet']);
    }
}

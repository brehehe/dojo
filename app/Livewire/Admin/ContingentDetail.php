<?php

namespace App\Livewire\Admin;

use App\Models\Contingent;
use Livewire\Component;

class ContingentDetail extends Component
{
    public Contingent $contingent;

    public function mount(Contingent $contingent)
    {
        $this->contingent = $contingent;
        $this->contingent->load(['athletes', 'athletes.categories', 'officials']);
    }

    public function confirm()
    {
        if (! auth()->user()->hasAnyRole(['Super Admin', 'Admin', 'Pendaftaran'])) {
            abort(403);
        }

        $this->contingent->update([
            'status' => 'confirmed',
            'verified_at' => now(),
            'verified_by' => auth()->id(),
        ]);

        session()->flash('message', 'Pendaftaran kontingen berhasil dikonfirmasi.');
    }

    public function reject()
    {
        if (! auth()->user()->hasAnyRole(['Super Admin', 'Admin', 'Pendaftaran'])) {
            abort(403);
        }

        $this->contingent->update([
            'status' => 'rejected',
            'verified_at' => now(),
            'verified_by' => auth()->id(),
        ]);

        session()->flash('message', 'Pendaftaran kontingen telah ditolak.');
    }

    public function render()
    {
        return view('livewire.admin.contingent-detail')
            ->layout('layouts.admin');
    }
}

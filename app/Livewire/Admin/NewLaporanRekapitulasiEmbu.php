<?php

namespace App\Livewire\Admin;

use App\Livewire\Admin\Arbitrase\Laporan\AdminLaporanRekapitulasiEmbu;
use Livewire\Attributes\Layout;

#[Layout('layouts.premium')]
class NewLaporanRekapitulasiEmbu extends AdminLaporanRekapitulasiEmbu
{
    public function render()
    {
        $parentView = parent::render();
        $data = $parentView->getData();

        return view('livewire.admin.new-laporan-rekapitulasi-embu', $data);
    }
}

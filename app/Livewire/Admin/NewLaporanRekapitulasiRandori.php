<?php

namespace App\Livewire\Admin;

use App\Livewire\Admin\Arbitrase\Laporan\AdminLaporanRekapitulasiRandori;
use Livewire\Attributes\Layout;

#[Layout('layouts.premium')]
class NewLaporanRekapitulasiRandori extends AdminLaporanRekapitulasiRandori
{
    public function render()
    {
        $parentView = parent::render();
        $data = $parentView->getData();

        return view('livewire.admin.new-laporan-rekapitulasi-randori', $data);
    }
}

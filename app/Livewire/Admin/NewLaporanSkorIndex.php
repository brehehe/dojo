<?php

namespace App\Livewire\Admin;

use App\Livewire\Admin\Arbitrase\Laporan\AdminLaporanSkorIndex;
use Livewire\Attributes\Layout;

#[Layout('layouts.premium')]
class NewLaporanSkorIndex extends AdminLaporanSkorIndex
{
    public function render()
    {
        $parentView = parent::render();
        $data = $parentView->getData();

        return view('livewire.admin.new-laporan-skor-index', $data);
    }
}

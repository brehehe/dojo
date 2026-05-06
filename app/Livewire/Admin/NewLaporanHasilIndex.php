<?php

namespace App\Livewire\Admin;

use App\Livewire\Admin\Arbitrase\Laporan\AdminLaporanHasilIndex;
use Livewire\Attributes\Layout;

#[Layout('layouts.premium')]
class NewLaporanHasilIndex extends AdminLaporanHasilIndex
{
    public function render()
    {
        // Run parent render to get the view data, then re-render with our new view
        $parentView = parent::render();
        $data = $parentView->getData();

        return view('livewire.admin.new-laporan-hasil-index', $data);
    }
}

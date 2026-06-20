<?php

use App\Livewire\PublicSchedule;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;

uses(RefreshDatabase::class);

test('public schedule page renders successfully for guests', function () {
    $this->get('/jadwal-pertandingan')
        ->assertStatus(200)
        ->assertSee('Jadwal Pertandingan');
});

test('public schedule livewire component works and can trigger export', function () {
    Livewire::test(PublicSchedule::class)
        ->assertStatus(200)
        ->call('export')
        ->assertFileDownloaded('jadwal_pertandingan_'.now()->format('Ymd_His').'.xlsx');
});

<?php

use App\Livewire\Contingent\LaporanWasit;
use App\Models\Contingent;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Spatie\Permission\Models\Role;

uses(RefreshDatabase::class);

beforeEach(function () {
    Role::firstOrCreate(['name' => 'Contingent']);
});

test('laporan wasit page renders without query exception', function () {
    $user = User::factory()->create();
    $user->assignRole('Contingent');

    $contingent = Contingent::factory()->create(['user_id' => $user->id]);

    Livewire::actingAs($user)
        ->test(LaporanWasit::class)
        ->assertStatus(200)
        ->assertSee('Laporan Penilaian Wasit');
});

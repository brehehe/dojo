<?php

use App\Livewire\Admin\NewRegistrationIndex;
use App\Models\Contingent;
use App\Models\Registration;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;

uses(RefreshDatabase::class);

beforeEach(function () {
    // Create Admin User
    $this->adminUser = User::factory()->create();

    // Create Contingent
    $this->contingent = Contingent::create([
        'name' => 'Surabaya Contingent',
        'kab_kota' => 'Surabaya',
        'leader_name' => 'John Doe',
        'leader_phone' => '0812345678',
        'email' => 'surabaya@example.com',
        'address' => 'Surabaya',
    ]);

    // Create Registrations
    $this->reg1 = Registration::create([
        'contingent_id' => $this->contingent->id,
        'status' => 'pending',
        'unique_code' => 123,
    ]);

    $this->reg2 = Registration::create([
        'contingent_id' => $this->contingent->id,
        'status' => 'pending',
        'unique_code' => 456,
    ]);
});

test('admin can view registrations index', function () {
    Livewire::actingAs($this->adminUser)
        ->test(NewRegistrationIndex::class)
        ->assertStatus(200)
        ->assertSee('Surabaya Contingent');
});

test('admin can toggle selection and verify selected registrations', function () {
    Livewire::actingAs($this->adminUser)
        ->test(NewRegistrationIndex::class)
        // Select all matching filter
        ->set('selectAll', true)
        ->assertSet('selectedRows', [(string) $this->reg1->id, (string) $this->reg2->id])
        ->call('verifySelected');

    $this->reg1->refresh();
    $this->reg2->refresh();

    expect($this->reg1->status)->toBe('verified');
    expect($this->reg2->status)->toBe('verified');
});

test('admin can toggle selection and unverify (set pending) selected registrations', function () {
    $this->reg1->update(['status' => 'verified']);
    $this->reg2->update(['status' => 'verified']);

    Livewire::actingAs($this->adminUser)
        ->test(NewRegistrationIndex::class)
        ->set('selectedRows', [(string) $this->reg1->id, (string) $this->reg2->id])
        ->call('unverifySelected');

    $this->reg1->refresh();
    $this->reg2->refresh();

    expect($this->reg1->status)->toBe('pending');
    expect($this->reg2->status)->toBe('pending');
});

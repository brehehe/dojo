<?php

use App\Livewire\Admin\NewKoordinatorIndex;
use App\Livewire\Admin\NewPaniteraIndex;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Spatie\Permission\Models\Role;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->admin = User::factory()->create();
    $this->adminRole = Role::findOrCreate('Admin', 'web');
    $this->admin->assignRole('Admin');

    Role::findOrCreate('Koordinator Lapangan', 'web');
    Role::findOrCreate('Panitera', 'web');
});

it('allows admin to view koordinator index page', function () {
    $this->actingAs($this->admin)
        ->get(route('admin.new-koordinator'))
        ->assertStatus(200);
});

it('allows admin to view panitera index page', function () {
    $this->actingAs($this->admin)
        ->get(route('admin.new-panitera'))
        ->assertStatus(200);
});

it('can create a koordinator user via livewire', function () {
    Livewire::actingAs($this->admin)
        ->test(NewKoordinatorIndex::class)
        ->set('name', 'Joko Lapangan')
        ->set('email', 'joko@example.com')
        ->set('password', 'password123')
        ->call('saveUser')
        ->assertSet('showingModal', false)
        ->assertDispatched('swal');

    $this->assertDatabaseHas('users', [
        'name' => 'Joko Lapangan',
        'email' => 'joko@example.com',
    ]);

    $user = User::where('email', 'joko@example.com')->first();
    expect($user->hasRole('Koordinator Lapangan'))->toBeTrue();
});

it('can edit a koordinator user via livewire', function () {
    $user = User::factory()->create(['name' => 'Old Name', 'email' => 'old@example.com']);
    $user->assignRole('Koordinator Lapangan');

    Livewire::actingAs($this->admin)
        ->test(NewKoordinatorIndex::class)
        ->call('showEditModal', $user->id)
        ->assertSet('name', 'Old Name')
        ->assertSet('email', 'old@example.com')
        ->set('name', 'Updated Name')
        ->call('saveUser')
        ->assertSet('showingModal', false)
        ->assertDispatched('swal');

    $this->assertDatabaseHas('users', [
        'id' => $user->id,
        'name' => 'Updated Name',
        'email' => 'old@example.com',
    ]);
});

it('can delete a koordinator user via livewire', function () {
    $user = User::factory()->create();
    $user->assignRole('Koordinator Lapangan');

    Livewire::actingAs($this->admin)
        ->test(NewKoordinatorIndex::class)
        ->call('deleteUser', $user->id)
        ->assertDispatched('swal');

    $this->assertDatabaseMissing('users', ['id' => $user->id]);
});

it('can create a panitera user via livewire', function () {
    Livewire::actingAs($this->admin)
        ->test(NewPaniteraIndex::class)
        ->set('name', 'Budi Panitera')
        ->set('email', 'budi@example.com')
        ->set('password', 'password123')
        ->call('saveUser')
        ->assertSet('showingModal', false)
        ->assertDispatched('swal');

    $this->assertDatabaseHas('users', [
        'name' => 'Budi Panitera',
        'email' => 'budi@example.com',
    ]);

    $user = User::where('email', 'budi@example.com')->first();
    expect($user->hasRole('Panitera'))->toBeTrue();
});

it('can edit a panitera user via livewire', function () {
    $user = User::factory()->create(['name' => 'Old Name', 'email' => 'old@example.com']);
    $user->assignRole('Panitera');

    Livewire::actingAs($this->admin)
        ->test(NewPaniteraIndex::class)
        ->call('showEditModal', $user->id)
        ->assertSet('name', 'Old Name')
        ->assertSet('email', 'old@example.com')
        ->set('name', 'Updated Name')
        ->call('saveUser')
        ->assertSet('showingModal', false)
        ->assertDispatched('swal');

    $this->assertDatabaseHas('users', [
        'id' => $user->id,
        'name' => 'Updated Name',
        'email' => 'old@example.com',
    ]);
});

it('can delete a panitera user via livewire', function () {
    $user = User::factory()->create();
    $user->assignRole('Panitera');

    Livewire::actingAs($this->admin)
        ->test(NewPaniteraIndex::class)
        ->call('deleteUser', $user->id)
        ->assertDispatched('swal');

    $this->assertDatabaseMissing('users', ['id' => $user->id]);
});

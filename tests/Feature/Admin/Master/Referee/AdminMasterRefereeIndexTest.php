<?php

use App\Livewire\Admin\Master\Referee\AdminMasterRefereeIndex;
use App\Models\Referee;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Livewire\Livewire;
use Spatie\Permission\Models\Role;

uses(RefreshDatabase::class);

beforeEach(function () {
    // Seed required roles
    Role::firstOrCreate(['name' => 'Perwasitan']);
});

test('master referee component can render', function () {
    $user = User::factory()->create();

    Livewire::actingAs($user)
        ->test(AdminMasterRefereeIndex::class)
        ->assertStatus(200);
});

test('can create a referee without a photo', function () {
    $user = User::factory()->create();

    Livewire::actingAs($user)
        ->test(AdminMasterRefereeIndex::class)
        ->set('name', 'Ref Name')
        ->set('email', 'referee@example.com')
        ->set('password', 'password123')
        ->set('phone', '0812345678')
        ->set('nik', '1234567890123456')
        ->set('certification_level', 'Internasional')
        ->call('saveReferee')
        ->assertHasNoErrors();

    $referee = Referee::first();
    expect($referee)->not->toBeNull()
        ->and($referee->name)->toBe('Ref Name')
        ->and($referee->user->email)->toBe('referee@example.com')
        ->and($referee->photo)->toBeNull();
});

test('can create a referee with a photo', function () {
    Storage::fake('public');
    $user = User::factory()->create();
    $photo = UploadedFile::fake()->image('wasit_photo.jpg');

    Livewire::actingAs($user)
        ->test(AdminMasterRefereeIndex::class)
        ->set('name', 'Ref Name Photo')
        ->set('email', 'refereephoto@example.com')
        ->set('password', 'password123')
        ->set('phone', '0812345678')
        ->set('nik', '1234567890123456')
        ->set('photo', $photo)
        ->call('saveReferee')
        ->assertHasNoErrors();

    $referee = Referee::where('nik', '1234567890123456')->first();
    expect($referee)->not->toBeNull()
        ->and($referee->photo)->not->toBeNull();

    // Verify storage has the photo
    Storage::disk('public')->assertExists($referee->photo);
});

test('can edit and update a referee and replace their photo', function () {
    Storage::fake('public');
    $admin = User::factory()->create();

    // Create a referee with a photo first
    $refUser = User::factory()->create(['name' => 'Old Name', 'email' => 'old@example.com']);
    $refUser->assignRole('Perwasitan');
    $oldPhotoPath = 'referees/photos/old.jpg';
    Storage::disk('public')->put($oldPhotoPath, 'content');

    $referee = Referee::create([
        'user_id' => $refUser->id,
        'nik' => '1234567890123456',
        'photo' => $oldPhotoPath,
    ]);

    $newPhoto = UploadedFile::fake()->image('new.png');

    Livewire::actingAs($admin)
        ->test(AdminMasterRefereeIndex::class)
        ->call('showEditModal', $referee->id)
        ->assertSet('existingPhoto', $oldPhotoPath)
        ->set('name', 'Updated Name')
        ->set('email', 'updated@example.com')
        ->set('photo', $newPhoto)
        ->call('saveReferee')
        ->assertHasNoErrors();

    $referee->refresh();
    expect($referee->name)->toBe('Updated Name')
        ->and($referee->photo)->not->toBe($oldPhotoPath);

    // Old photo should be deleted from disk
    Storage::disk('public')->assertMissing($oldPhotoPath);
    // New photo should exist
    Storage::disk('public')->assertExists($referee->photo);
});

test('deleting a referee deletes their photo from storage', function () {
    Storage::fake('public');
    $admin = User::factory()->create();

    $refUser = User::factory()->create();
    $refUser->assignRole('Perwasitan');
    $photoPath = 'referees/photos/photo.jpg';
    Storage::disk('public')->put($photoPath, 'content');

    $referee = Referee::create([
        'user_id' => $refUser->id,
        'nik' => '1234567890123456',
        'photo' => $photoPath,
    ]);

    Livewire::actingAs($admin)
        ->test(AdminMasterRefereeIndex::class)
        ->call('deleteReferee', $referee->id);

    expect(Referee::find($referee->id))->toBeNull();
    // Photo should be deleted from disk
    Storage::disk('public')->assertMissing($photoPath);
});

test('can delete an existing photo during edit without deleting the referee record', function () {
    Storage::fake('public');
    $admin = User::factory()->create();

    $refUser = User::factory()->create();
    $refUser->assignRole('Perwasitan');
    $oldPhotoPath = 'referees/photos/old.jpg';
    Storage::disk('public')->put($oldPhotoPath, 'content');

    $referee = Referee::create([
        'user_id' => $refUser->id,
        'nik' => '1234567890123456',
        'photo' => $oldPhotoPath,
    ]);

    Livewire::actingAs($admin)
        ->test(AdminMasterRefereeIndex::class)
        ->call('showEditModal', $referee->id)
        ->call('removePhoto')
        ->call('saveReferee')
        ->assertHasNoErrors();

    $referee->refresh();
    expect($referee->photo)->toBeNull();

    // Verify storage photo has been deleted from disk
    Storage::disk('public')->assertMissing($oldPhotoPath);
});

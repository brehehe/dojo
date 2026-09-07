<?php

use App\Livewire\Auth\Register;
use App\Mail\ContingentAccountCreatedMail;
use App\Models\Contingent;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Livewire\Livewire;

test('registration screen renders contingent setup fields without password input', function () {
    $response = $this->get('/register');

    $response->assertOk();
    $response->assertSee('Daftar Akun Kontingen');
    $response->assertSee('Nama Manager / Official');
    $response->assertSee('No. HP / WhatsApp');
    $response->assertSee('Nama Kontingen');
    $response->assertSee('Kabupaten / Kota');
    $response->assertSee('Alamat Kantor / Sekretariat Kontingen');
    $response->assertSee('Kata sandi akan dikirim langsung ke email yang didaftarkan');
    $response->assertDontSee('Konfirmasi Kata Sandi');
});

test('it registers user and contingent, assigns role, and sends credentials via email', function () {
    Mail::fake();

    Livewire::test(Register::class)
        ->set('name', 'Sensei Hartono')
        ->set('email', 'hartono@perkemi-sby.id')
        ->set('leader_phone', '081234567890')
        ->set('contingent_name', 'Dojo Gelora Surabaya')
        ->set('contingent_city', 'Kota Surabaya')
        ->set('address', 'Jl. Kertajaya No. 88, Surabaya')
        ->call('register')
        ->assertHasNoErrors()
        ->assertSet('registrationSuccess', true)
        ->assertSee('Pendaftaran Berhasil!')
        ->assertSee('hartono@perkemi-sby.id')
        ->assertSee('Dojo Gelora Surabaya');

    // Verify User in Database
    $user = User::where('email', 'hartono@perkemi-sby.id')->first();
    expect($user)->not->toBeNull();
    expect($user->name)->toBe('Sensei Hartono');
    expect($user->hasRole('Contingent'))->toBeTrue();

    // Verify Contingent in Database linked to User
    $contingent = Contingent::where('user_id', $user->id)->first();
    expect($contingent)->not->toBeNull();
    expect($contingent->name)->toBe('Dojo Gelora Surabaya');
    expect($contingent->kab_kota)->toBe('Kota Surabaya');
    expect($contingent->leader_name)->toBe('Sensei Hartono');
    expect($contingent->leader_phone)->toBe('081234567890');

    // Verify Mail Queued for background worker (Supervisor)
    Mail::assertQueued(ContingentAccountCreatedMail::class, function ($mail) use ($user) {
        return $mail->hasTo($user->email)
            && $mail->contingent->name === 'Dojo Gelora Surabaya'
            && ! empty($mail->plainPassword)
            && Hash::check($mail->plainPassword, $user->password);
    });
});

test('it validates required fields on registration', function () {
    Livewire::test(Register::class)
        ->set('name', '')
        ->set('email', '')
        ->set('leader_phone', '')
        ->set('contingent_name', '')
        ->set('contingent_city', '')
        ->set('address', '')
        ->call('register')
        ->assertHasErrors([
            'name' => 'required',
            'email' => 'required',
            'leader_phone' => 'required',
            'contingent_name' => 'required',
            'contingent_city' => 'required',
            'address' => 'required',
        ]);
});

test('it prevents registering with duplicate email', function () {
    User::factory()->create([
        'email' => 'existing@kempo.id',
    ]);

    Livewire::test(Register::class)
        ->set('name', 'New Manager')
        ->set('email', 'existing@kempo.id')
        ->set('leader_phone', '08987654321')
        ->set('contingent_name', 'Dojo Baru')
        ->set('contingent_city', 'Kota Malang')
        ->set('address', 'Jl. Ijen No. 12')
        ->call('register')
        ->assertHasErrors(['email' => 'unique']);
});

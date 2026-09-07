<?php

use App\Livewire\Auth\NewLoginIndex;
use App\Livewire\Auth\Register;
use Livewire\Livewire;

test('login screen displays register link', function () {
    $response = $this->get('/login');

    $response->assertOk();
    $response->assertSee('Belum punya akun?');
    $response->assertSee(route('register'));
    $response->assertSee('Daftar di sini');

    Livewire::test(NewLoginIndex::class)
        ->assertSee('Belum punya akun?')
        ->assertSee(route('register'))
        ->assertSee('Daftar di sini');
});

test('register screen displays login link', function () {
    $response = $this->get('/register');

    $response->assertOk();
    $response->assertSee('Sudah punya akun?');
    $response->assertSee(route('login'));
    $response->assertSee('Masuk di sini');

    Livewire::test(Register::class)
        ->assertSee('Sudah punya akun?')
        ->assertSee(route('login'))
        ->assertSee('Masuk di sini');
});

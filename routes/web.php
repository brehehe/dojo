<?php

use App\Livewire\Admin\ContingentDetail;
use App\Livewire\Admin\ContingentIndex;
use App\Livewire\Admin\Dashboard as AdminDashboard;
use App\Livewire\Auth\Login;
use App\Livewire\GeneralDashboard;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::view('/', 'welcome');
Route::view('/piala_walikotasby2026', 'register')->name('register');


Route::middleware('guest')->group(function () {
    Route::get('login', Login::class)->name('login');
});

Route::middleware('auth')->group(function () {
    Route::get('dashboard', GeneralDashboard::class)->name('dashboard');

    Route::post('logout', function () {
        Auth::logout();
        request()->session()->invalidate();
        request()->session()->regenerateToken();

        return redirect('/');
    })->name('logout');

    Route::middleware('role:Super Admin|Admin Pendaftaran')->prefix('admin')->name('admin.')->group(function () {
        Route::get('/dashboard', AdminDashboard::class)->name('dashboard');
        Route::get('/contingents', ContingentIndex::class)->name('contingents.index');
        Route::get('/contingents/{contingent}', ContingentDetail::class)->name('contingents.detail');
    });
});

// require __DIR__.'/auth.php'; // Disabling Breeze auth routes

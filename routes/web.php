<?php

use App\Livewire\Admin\ContingentDetail;
use App\Livewire\Admin\ContingentIndex;
use App\Livewire\Admin\Dashboard as AdminDashboard;
use App\Livewire\Admin\Master\AgeGroup\AdminMasterAgeGroupIndex;
use App\Livewire\Admin\Master\KyuLevel\AdminMasterKyuLevelIndex;
use App\Livewire\Admin\Master\MatchNumber\AdminMasterMatchNumberIndex;
use App\Livewire\Admin\Master\Referee\AdminMasterRefereeIndex;
use App\Livewire\Admin\Master\Technique\AdminMasterTechniqueIndex;
use App\Livewire\Admin\Master\User\AdminMasterUserIndex;
use App\Livewire\Admin\Master\WeightGroup\AdminMasterWeightGroupIndex;
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

    Route::prefix('admin')->name('admin.')->group(function () {
        Route::get('/dashboard', AdminDashboard::class)->name('dashboard');
        Route::get('/contingents', ContingentIndex::class)->name('contingents.index');
        Route::get('/contingents/{contingent}', ContingentDetail::class)->name('contingents.detail');

        Route::prefix('master')->name('master.')->group(function () {
            Route::get('/users', AdminMasterUserIndex::class)->name('users');
            Route::get('/referees', AdminMasterRefereeIndex::class)->name('referees');
            Route::get('/kyu-levels', AdminMasterKyuLevelIndex::class)->name('kyu-levels');
            Route::get('/age-groups', AdminMasterAgeGroupIndex::class)->name('age-groups');
            Route::get('/weight-groups', AdminMasterWeightGroupIndex::class)->name('weight-groups');
            Route::get('/techniques', AdminMasterTechniqueIndex::class)->name('techniques');
            Route::get('/match-numbers', AdminMasterMatchNumberIndex::class)->name('match-numbers');
        });
    });
});

// require __DIR__.'/auth.php'; // Disabling Breeze auth routes

<?php

use App\Livewire\Admin\Dashboard as AdminDashboard;
use App\Livewire\Admin\Master\AgeGroup\AdminMasterAgeGroupIndex;
use App\Livewire\Admin\Master\Athlete\AdminMasterAthleteDetailIndex;
use App\Livewire\Admin\Master\Athlete\AdminMasterAthleteFormIndex;
use App\Livewire\Admin\Master\Athlete\AdminMasterAthleteIndex;
use App\Livewire\Admin\Master\Contingent\AdminMasterContingentDetailIndex;
use App\Livewire\Admin\Master\Contingent\AdminMasterContingentFormIndex;
use App\Livewire\Admin\Master\Contingent\AdminMasterContingentIndex;
use App\Livewire\Admin\Master\Court\AdminMasterCourtIndex;
use App\Livewire\Admin\Master\Official\AdminMasterOfficialIndex;
use App\Livewire\Admin\Master\Official\AdminMasterOfficialFormIndex;
use App\Livewire\Admin\Master\KyuLevel\AdminMasterKyuLevelIndex;
use App\Livewire\Admin\Master\MatchNumber\AdminMasterMatchNumberIndex;
use App\Livewire\Admin\Master\Referee\AdminMasterRefereeIndex;
use App\Livewire\Admin\Master\Role\AdminMasterRoleFormIndex;
use App\Livewire\Admin\Master\Role\AdminMasterRoleIndex;
use App\Livewire\Admin\Master\Rundown\AdminMasterRundownIndex;
use App\Livewire\Admin\Master\Technique\AdminMasterTechniqueIndex;
use App\Livewire\Admin\Master\User\AdminMasterUserIndex;
use App\Livewire\Admin\Master\WeightGroup\AdminMasterWeightGroupIndex;
use App\Livewire\Admin\Registration\AdminRegistrationIndex;
use App\Livewire\Admin\Registration\AdminRegistrationShow;
use App\Livewire\Auth\Login;
use App\Livewire\GeneralDashboard;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::view('/', 'welcome');
Route::view('/piala_walikotasby2026', 'register')->name('register');

Route::middleware('guest')->group(function () {
    Route::get('login', Login::class)->name('login');
    Route::get('register', \App\Livewire\Auth\Register::class)->name('register');
});

Route::get('/piala_walikotasby2026', function () {
    return view('register');
})->name('old_register');

Route::middleware('auth')->group(function () {
    Route::get('dashboard', GeneralDashboard::class)->name('dashboard');
    Route::get('setup', \App\Livewire\Contingent\Setup::class)->name('contingent.setup');
    Route::get('contingent/dashboard', \App\Livewire\Contingent\Dashboard::class)->name('contingent.dashboard');

    Route::post('logout', function () {
        Auth::logout();
        request()->session()->invalidate();
        request()->session()->regenerateToken();

        return redirect('/');
    })->name('logout');

    Route::prefix('admin')->name('admin.')->group(function () {
        Route::get('/dashboard', AdminDashboard::class)->name('dashboard');

        Route::prefix('master')->name('master.')->group(function () {
            Route::get('/users', AdminMasterUserIndex::class)->name('users');
            Route::get('/referees', AdminMasterRefereeIndex::class)->name('referees');
            Route::get('/kyu-levels', AdminMasterKyuLevelIndex::class)->name('kyu-levels');
            Route::get('/age-groups', AdminMasterAgeGroupIndex::class)->name('age-groups');
            Route::get('/weight-groups', AdminMasterWeightGroupIndex::class)->name('weight-groups');
            Route::get('/techniques', AdminMasterTechniqueIndex::class)->name('techniques');
            Route::get('/match-numbers', AdminMasterMatchNumberIndex::class)->name('match-numbers');
            Route::get('/rundown', AdminMasterRundownIndex::class)->name('rundown');
            Route::get('/court', AdminMasterCourtIndex::class)->name('court');

            // Role Management
            Route::get('/roles', AdminMasterRoleIndex::class)->name('roles.index');
            Route::get('/roles/create', AdminMasterRoleFormIndex::class)->name('roles.create');
            Route::get('/roles/{id}/edit', AdminMasterRoleFormIndex::class)->name('roles.edit');

            // Contingent Management
            Route::get('/contingents', AdminMasterContingentIndex::class)->name('contingents.index');
            Route::get('/contingents/create', AdminMasterContingentFormIndex::class)->name('contingents.create');
            Route::get('/contingents/{contingent}', AdminMasterContingentDetailIndex::class)->name('contingents.detail');
            Route::get('/contingents/{contingent}/edit', AdminMasterContingentFormIndex::class)->name('contingents.edit');

            // Athlete Management
            Route::get('/athletes', AdminMasterAthleteIndex::class)->name('athletes.index');
            Route::get('/athletes/create', AdminMasterAthleteFormIndex::class)->name('athletes.create');
            Route::get('/athletes/{athlete}', AdminMasterAthleteDetailIndex::class)->name('athletes.detail');
            Route::get('/athletes/{athlete}/edit', AdminMasterAthleteFormIndex::class)->name('athletes.edit');

            // Official Management
            Route::get('/officials', AdminMasterOfficialIndex::class)->name('officials.index');
            Route::get('/officials/create', AdminMasterOfficialFormIndex::class)->name('officials.create');
            Route::get('/officials/{official}/edit', AdminMasterOfficialFormIndex::class)->name('officials.edit');
        });

        // Registration Transactions
        Route::get('/registrations', AdminRegistrationIndex::class)->name('registrations.index');
        Route::get('/registrations/{registration}', AdminRegistrationShow::class)->name('registrations.show');

        // Verified Match Numbers report
        Route::get('/match-numbers/verified', \App\Livewire\Admin\MatchNumber\AdminMatchNumberVerifiedIndex::class)->name('match-numbers.verified');
    });
});

// require __DIR__.'/auth.php'; // Disabling Breeze auth routes

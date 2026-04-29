<?php

use App\Http\Controllers\WelcomeController;
use App\Livewire\Admin\Announcer\AnnouncerIndex;
use App\Livewire\Admin\Arbitrase\GenerateReferee\AdminArbitraseGenerateRefereeIndex;
use App\Livewire\Admin\Arbitrase\Laporan\AdminLaporanHasilIndex;
use App\Livewire\Admin\Arbitrase\Laporan\AdminLaporanRekapitulasiEmbu;
use App\Livewire\Admin\Arbitrase\Laporan\AdminLaporanRekapitulasiRandori;
use App\Livewire\Admin\Arbitrase\Laporan\AdminLaporanSkorIndex;
use App\Livewire\Admin\Arbitrase\Scoring\AdminArbitraseScoringEmbuDetail;
use App\Livewire\Admin\Arbitrase\Scoring\AdminArbitraseScoringIndex;
use App\Livewire\Admin\Arbitrase\Scoring\AdminArbitraseScoringRandoriDetail;
use App\Livewire\Admin\Arbitrase\Scoring\AdminEmbuResultIndex;
use App\Livewire\Admin\Arbitrase\Scoring\AdminEmbuScoringTestbench;
use App\Livewire\Admin\Arbitrase\Scoring\MonitorCourtIndex;
use App\Livewire\Admin\Arbitrase\Scoring\MonitorHasilIndex;
use App\Livewire\Admin\Arbitrase\Scoring\MonitorTimerIndex;
use App\Livewire\Admin\Dashboard as AdminDashboard;
use App\Livewire\Admin\HomeDashboardIndex;
use App\Livewire\Admin\Master\AgeGroup\AdminMasterAgeGroupIndex;
use App\Livewire\Admin\Master\Athlete\AdminMasterAthleteDetailIndex;
use App\Livewire\Admin\Master\Athlete\AdminMasterAthleteFormIndex;
use App\Livewire\Admin\Master\Athlete\AdminMasterAthleteIndex;
use App\Livewire\Admin\Master\Contingent\AdminMasterContingentDetailIndex;
use App\Livewire\Admin\Master\Contingent\AdminMasterContingentFormIndex;
use App\Livewire\Admin\Master\Contingent\AdminMasterContingentIndex;
use App\Livewire\Admin\Master\Court\AdminMasterCourtIndex;
use App\Livewire\Admin\Master\KyuLevel\AdminMasterKyuLevelIndex;
use App\Livewire\Admin\Master\MatchNumber\AdminMasterMatchNumberIndex;
use App\Livewire\Admin\Master\Official\AdminMasterOfficialFormIndex;
use App\Livewire\Admin\Master\Official\AdminMasterOfficialIndex;
use App\Livewire\Admin\Master\PaymentMethod\AdminMasterPaymentIndex;
use App\Livewire\Admin\Master\Pool\AdminMasterPoolIndex;
use App\Livewire\Admin\Master\Referee\AdminMasterRefereeIndex;
use App\Livewire\Admin\Master\Role\AdminMasterRoleFormIndex;
use App\Livewire\Admin\Master\Role\AdminMasterRoleIndex;
use App\Livewire\Admin\Master\Rundown\AdminMasterRundownIndex;
use App\Livewire\Admin\Master\SessionTime\AdminMasterSessionTimeIndex;
use App\Livewire\Admin\Master\Technique\AdminMasterTechniqueIndex;
use App\Livewire\Admin\Master\User\AdminMasterUserIndex;
use App\Livewire\Admin\Master\WeightGroup\AdminMasterWeightGroupIndex;
use App\Livewire\Admin\MatchNumber\AdminMatchNumberVerifiedIndex;
use App\Livewire\Admin\NewDashboardIndex;
use App\Livewire\Admin\Profile\AdminProfileIndex;
use App\Livewire\Admin\Registration\AdminRegistrationIndex;
use App\Livewire\Admin\Registration\AdminRegistrationShow;
use App\Livewire\Admin\Reports\AdminAthleteBiodataReport;
use App\Livewire\Admin\Reports\AdminMatchClassReport;
use App\Livewire\Admin\Reports\AdminRegistrationByNameReport;
use App\Livewire\Admin\Reports\AdminRegistrationByNumberReport;
use App\Livewire\Admin\SmartWasit\AdminLaporanPerbabakIndex;
use App\Livewire\Admin\SmartWasit\AdminLaporanRankingIawIndex;
use App\Livewire\Admin\SmartWasit\AdminLaporanRankingIkIndex;
use App\Livewire\Admin\SmartWasit\AdminLaporanRankingSkwIndex;
use App\Livewire\Admin\SmartWasit\AdminLaporanSmartWasitSummaryIndex;
use App\Livewire\Admin\TechnicalMeeting\Embu\AdminTechnicalMeetingEmbuIndex;
use App\Livewire\Admin\TechnicalMeeting\Randori\AdminTechnicalMeetingRandoriIndex;
use App\Livewire\Auth\Login;
use App\Livewire\Auth\Register;
use App\Livewire\Contingent\Dashboard;
use App\Livewire\Contingent\Results;
use App\Livewire\Contingent\Schedule;
use App\Livewire\Contingent\Setup;
use App\Livewire\Contingent\Standings;
use App\Livewire\GeneralDashboard;
use App\Livewire\Referee\RefereeScoringDashboard;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

// Welcome page templates (preview routes)
Route::get('/', [WelcomeController::class, 'template1'])->name('welcome');
Route::get('/welcome/1', [WelcomeController::class, 'template1'])->name('welcome.1');
Route::get('/welcome/2', [WelcomeController::class, 'template2'])->name('welcome.2');
Route::get('/welcome/3', [WelcomeController::class, 'template3'])->name('welcome.3');
Route::get('/welcome/4', [WelcomeController::class, 'template4'])->name('welcome.4');
Route::get('/welcome/4/{color}', [WelcomeController::class, 'template4Color'])->name('welcome.4.color');
Route::get('/welcome/5', [WelcomeController::class, 'template5'])->name('welcome.5');

Route::view('/piala_walikotasby2026', 'register')->name('register');

Route::middleware('guest')->group(function () {
    Route::get('login', Login::class)->name('login');
    Route::get('register', Register::class)->name('register');
});

Route::get('/piala_walikotasby2026', function () {
    return view('register');
})->name('old_register');

Route::middleware('auth')->group(function () {
    Route::get('dashboard', GeneralDashboard::class)->name('dashboard');
    Route::get('setup', Setup::class)->name('contingent.setup');
    Route::get('contingent/dashboard', Dashboard::class)->name('contingent.dashboard');
    Route::get('contingent/jadwal', Schedule::class)->name('contingent.schedule');
    Route::get('contingent/hasil', Results::class)->name('contingent.results');
    Route::get('contingent/klasemen', Standings::class)->name('contingent.standings');

    Route::post('logout', function () {
        Auth::logout();
        request()->session()->invalidate();
        request()->session()->regenerateToken();

        return redirect('/');
    })->name('logout');

    Route::prefix('admin')->name('admin.')->group(function () {
        Route::get('/dashboard', AdminDashboard::class)->name('dashboard');
        Route::get('/home-dashboard', HomeDashboardIndex::class)->name('home-dashboard');
        Route::get('/new-dashboard', NewDashboardIndex::class)->name('new-dashboard');
        Route::get('/profile', AdminProfileIndex::class)->name('profile');

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
            Route::get('/pool', AdminMasterPoolIndex::class)->name('pool');
            Route::get('/session-time', AdminMasterSessionTimeIndex::class)->name('session-time');
            Route::get('/payment-method', AdminMasterPaymentIndex::class)->name('payment-method');

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
        Route::get('/match-numbers/verified', AdminMatchNumberVerifiedIndex::class)->name('match-numbers.verified');

        // Registration by Number Report (Excel)
        Route::get('/reports/registration-by-number', AdminRegistrationByNumberReport::class)->name('reports.registration-by-number');

        // Registration by Name Report (Excel)
        Route::get('/reports/registration-by-name', AdminRegistrationByNameReport::class)->name('reports.registration-by-name');

        // Match Number & Class Report (Excel)
        Route::get('/reports/match-class', AdminMatchClassReport::class)->name('reports.match-class');

        // Athlete Biodata Report (Grid)
        Route::get('/reports/athlete-biodata', AdminAthleteBiodataReport::class)->name('reports.athlete-biodata');

        Route::prefix('technical-meeting')->name('technical-meeting.')->group(function () {
            Route::get('/embu', AdminTechnicalMeetingEmbuIndex::class)->name('embu');
            Route::get('/randori', AdminTechnicalMeetingRandoriIndex::class)->name('randori');
        });

        Route::prefix('arbitrase')->name('arbitrase.')->group(function () {
            Route::get('/referees', AdminMasterRefereeIndex::class)->name('referees');
            Route::get('/generate-referee', AdminArbitraseGenerateRefereeIndex::class)->name('generate-referee');
            Route::get('/laporan-hasil', AdminLaporanHasilIndex::class)->name('laporan-hasil');
            Route::get('/laporan-skor', AdminLaporanSkorIndex::class)->name('laporan-skor');
            Route::get('/rekapitulasi-randori', AdminLaporanRekapitulasiRandori::class)->name('rekapitulasi-randori');
            Route::get('/rekapitulasi-embu', AdminLaporanRekapitulasiEmbu::class)->name('rekapitulasi-embu');

            Route::prefix('scoring')->name('scoring.')->group(function () {
                Route::get('/', AdminArbitraseScoringIndex::class)->name('index');
                Route::get('/monitor/{courtId}', MonitorCourtIndex::class)->name('monitor');
                Route::get('/monitor-hasil/court/{courtId}', MonitorHasilIndex::class)->name('monitor-hasil.court');
                Route::get('/monitor-hasil/match/{matchId}', MonitorHasilIndex::class)->name('monitor-hasil.match');
                Route::get('/embu/{matchNumber}', AdminArbitraseScoringEmbuDetail::class)->name('embu.detail');
                Route::get('/randori/{matchNumber}', AdminArbitraseScoringRandoriDetail::class)->name('randori.detail');
                Route::get('/monitor-timer/court/{courtId}', MonitorTimerIndex::class)->name('monitor-timer.court');
            });
        });

        Route::prefix('smart-wasit')->name('smart-wasit.')->group(function () {
            Route::get('/summary', AdminLaporanSmartWasitSummaryIndex::class)->name('summary');
            Route::get('/ranking-skw', AdminLaporanRankingSkwIndex::class)->name('ranking-skw');
            Route::get('/ranking-iaw', AdminLaporanRankingIawIndex::class)->name('ranking-iaw');
            Route::get('/ranking-ik', AdminLaporanRankingIkIndex::class)->name('ranking-ik');
            Route::get('/perbabak', AdminLaporanPerbabakIndex::class)->name('perbabak');
        });

        Route::prefix('panitera')->name('panitera.')->group(function () {
            Route::get('/generate-referee', AdminArbitraseGenerateRefereeIndex::class)->name('generate-referee');

            Route::prefix('scoring')->name('scoring.')->group(function () {
                Route::get('/', AdminArbitraseScoringIndex::class)->name('index');
                Route::get('/embu/{matchNumber}', AdminArbitraseScoringEmbuDetail::class)->name('embu.detail');
                Route::get('/randori/{matchNumber}', AdminArbitraseScoringRandoriDetail::class)->name('randori.detail');
                Route::get('/embu-testbench', AdminEmbuScoringTestbench::class)->name('embu.testbench');
                Route::get('/embu-result', AdminEmbuResultIndex::class)->name('embu.result');
            });
            Route::get('/announcer', AnnouncerIndex::class)->name('announcer');
        });

        Route::prefix('referee')->name('referee.')->group(function () {
            Route::get('/scoring', RefereeScoringDashboard::class)->name('scoring');
        });
    });
});

// require __DIR__.'/auth.php'; // Disabling Breeze auth routes

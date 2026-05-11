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
use App\Livewire\Admin\Arbitrase\Scoring\AdminEmbuScoringTestbench;
use App\Livewire\Admin\Arbitrase\Scoring\MonitorCourtIndex;
use App\Livewire\Admin\Arbitrase\Scoring\MonitorHasilIndex;
use App\Livewire\Admin\Arbitrase\Scoring\MonitorRefereeIndex;
use App\Livewire\Admin\Arbitrase\Scoring\MonitorRekapitulasiHasilIndex;
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
use App\Livewire\Admin\NewAgeGroupIndex;
use App\Livewire\Admin\NewAthleteCreate;
use App\Livewire\Admin\NewAthleteEdit;
use App\Livewire\Admin\NewAthleteIndex;
use App\Livewire\Admin\NewAthleteShow;
use App\Livewire\Admin\NewContingentDetail;
use App\Livewire\Admin\NewContingentForm;
use App\Livewire\Admin\NewContingentIndex;
use App\Livewire\Admin\NewCourtIndex;
use App\Livewire\Admin\NewDashboardIndex;
use App\Livewire\Admin\NewEmbuResultIndex;
use App\Livewire\Admin\NewGenerateRefereeIndex;
use App\Livewire\Admin\NewKyuLevelIndex;
use App\Livewire\Admin\NewLaporanHasilIndex;
use App\Livewire\Admin\NewLaporanRekapitulasiEmbu;
use App\Livewire\Admin\NewLaporanRekapitulasiRandori;
use App\Livewire\Admin\NewLaporanSkorIndex;
use App\Livewire\Admin\NewMatchNumberIndex;
use App\Livewire\Admin\NewOfficialForm;
use App\Livewire\Admin\NewOfficialIndex;
use App\Livewire\Admin\NewPaymentMethodIndex;
use App\Livewire\Admin\NewPoolIndex;
use App\Livewire\Admin\NewRefereeIndex;
use App\Livewire\Admin\NewRegistrationIndex;
use App\Livewire\Admin\NewRegistrationShow;
use App\Livewire\Admin\NewRoleForm;
use App\Livewire\Admin\NewRoleIndex;
use App\Livewire\Admin\NewRundownIndex;
use App\Livewire\Admin\NewScoringEmbuIndex;
use App\Livewire\Admin\NewScoringIndex;
use App\Livewire\Admin\NewScoringRandoriIndex;
use App\Livewire\Admin\NewSessionTimeIndex;
use App\Livewire\Admin\NewTechnicalMeetingDrawingIndex;
use App\Livewire\Admin\NewTechniqueIndex;
use App\Livewire\Admin\NewUserIndex;
use App\Livewire\Admin\NewWeightGroupIndex;
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
use App\Livewire\Admin\SmartWasit\AdminLaporanRankingIvIndex;
use App\Livewire\Admin\SmartWasit\AdminLaporanRankingSkwIndex;
use App\Livewire\Admin\SmartWasit\AdminLaporanSmartWasitSummaryIndex;
use App\Livewire\Admin\SmartWasit\NewLaporanPerbabakIndex;
use App\Livewire\Admin\SmartWasit\NewLaporanRankingIawIndex;
use App\Livewire\Admin\SmartWasit\NewLaporanRankingIkIndex;
use App\Livewire\Admin\SmartWasit\NewLaporanRankingIvIndex;
use App\Livewire\Admin\SmartWasit\NewLaporanRankingSkwIndex;
use App\Livewire\Admin\SmartWasit\NewLaporanSmartWasitSummaryIndex;
use App\Livewire\Admin\TechnicalMeeting\Embu\AdminTechnicalMeetingEmbuIndex;
use App\Livewire\Admin\TechnicalMeeting\Randori\AdminTechnicalMeetingRandoriIndex;
use App\Livewire\Auth\NewLoginIndex;
use App\Livewire\Auth\Register;
use App\Livewire\Contingent\Athletes;
use App\Livewire\Contingent\Dashboard;
use App\Livewire\Contingent\Officials;
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
    Route::get('login', NewLoginIndex::class)->name('login');
    Route::get('new-login', NewLoginIndex::class)->name('new-login');
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
    Route::get('contingent/athletes', Athletes::class)->name('contingent.athletes');
    Route::get('contingent/officials', Officials::class)->name('contingent.officials');

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
        Route::get('/new-registrations', NewRegistrationIndex::class)->name('new-registrations');
        Route::get('/new-registrations/{registration}', NewRegistrationShow::class)->name('new-registrations.show');
        Route::get('/new-athletes', NewAthleteIndex::class)->name('new-athletes');
        Route::get('/new-athletes/create', NewAthleteCreate::class)->name('new-athletes.create');
        Route::get('/new-athletes/{athlete}', NewAthleteShow::class)->name('new-athletes.show');
        Route::get('/new-athletes/{athlete}/edit', NewAthleteEdit::class)->name('new-athletes.edit');
        Route::get('/new-age-groups', NewAgeGroupIndex::class)->name('new-age-groups');
        Route::get('/new-kyu-levels', NewKyuLevelIndex::class)->name('new-kyu-levels');
        Route::get('/new-weight-groups', NewWeightGroupIndex::class)->name('new-weight-groups');
        Route::get('/new-techniques', NewTechniqueIndex::class)->name('new-techniques');
        Route::get('/new-match-numbers', NewMatchNumberIndex::class)->name('new-match-numbers');
        Route::get('/new-payment-methods', NewPaymentMethodIndex::class)->name('new-payment-methods');
        Route::get('/new-courts', NewCourtIndex::class)->name('new-courts');
        Route::get('/new-pools', NewPoolIndex::class)->name('new-pools');
        Route::get('/new-session-times', NewSessionTimeIndex::class)->name('new-session-times');
        Route::get('/new-rundowns', NewRundownIndex::class)->name('new-rundowns');
        Route::get('/new-tm-drawing', NewTechnicalMeetingDrawingIndex::class)->name('new-tm-drawing');
        Route::get('/new-users', NewUserIndex::class)->name('new-users');
        Route::get('/master/new-referees', NewRefereeIndex::class)->name('master.new-referees');
        Route::get('/arbitrase/new-referees', NewRefereeIndex::class)->name('arbitrase.new-referees');
        Route::get('/new-generate-referee', NewGenerateRefereeIndex::class)->name('new-generate-referee');

        Route::get('/new-scoring', NewScoringIndex::class)->name('new-scoring-index');
        Route::get('/new-scoring/embu/{matchNumber}', NewScoringEmbuIndex::class)->name('new-scoring-embu-index');
        Route::get('/new-scoring/randori/{matchNumber}', NewScoringRandoriIndex::class)->name('new-scoring-randori-index');

        Route::get('/new-roles', NewRoleIndex::class)->name('new-roles');
        Route::get('/new-roles/create', NewRoleForm::class)->name('new-roles.create');
        Route::get('/new-roles/{id}/edit', NewRoleForm::class)->name('new-roles.edit');

        Route::get('/new-contingents', NewContingentIndex::class)->name('new-contingents');
        Route::get('/new-contingents/create', NewContingentForm::class)->name('new-contingents.create');
        Route::get('/new-contingents/{id}/edit', NewContingentForm::class)->name('new-contingents.edit');
        Route::get('/new-contingents/{contingent}/detail', NewContingentDetail::class)->name('new-contingents.detail');

        Route::get('/new-officials', NewOfficialIndex::class)->name('new-officials');
        Route::get('/new-officials/create', NewOfficialForm::class)->name('new-officials.create');
        Route::get('/new-officials/{id}/edit', NewOfficialForm::class)->name('new-officials.edit');
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

            // New Premium Laporan Views
            Route::get('/new-laporan-hasil', NewLaporanHasilIndex::class)->name('new-laporan-hasil');
            Route::get('/new-laporan-skor', NewLaporanSkorIndex::class)->name('new-laporan-skor');
            Route::get('/new-rekapitulasi-randori', NewLaporanRekapitulasiRandori::class)->name('new-rekapitulasi-randori');
            Route::get('/new-rekapitulasi-embu', NewLaporanRekapitulasiEmbu::class)->name('new-rekapitulasi-embu');

            Route::prefix('scoring')->name('scoring.')->group(function () {
                Route::get('/', AdminArbitraseScoringIndex::class)->name('index');
                Route::get('/monitor/{courtId}', MonitorCourtIndex::class)->name('monitor');
                Route::get('/monitor-hasil/court/{courtId}', MonitorHasilIndex::class)->name('monitor-hasil.court');
                Route::get('/monitor-rekapitulasi-hasil/court/{courtId}', MonitorRekapitulasiHasilIndex::class)->name('monitor-rekapitulasi-hasil.court');
                Route::get('/monitor-hasil/match/{matchId}', MonitorHasilIndex::class)->name('monitor-hasil.match');
                Route::get('/embu/{matchNumber}', AdminArbitraseScoringEmbuDetail::class)->name('embu.detail');
                Route::get('/randori/{matchNumber}', AdminArbitraseScoringRandoriDetail::class)->name('randori.detail');
                Route::get('/monitor-timer/court/{courtId}', MonitorTimerIndex::class)->name('monitor-timer.court');
                Route::get('/monitor-referee/court/{courtId}', MonitorRefereeIndex::class)->name('monitor-referee.court');
            });
        });

        Route::prefix('smart-wasit')->name('smart-wasit.')->group(function () {
            Route::get('/summary', NewLaporanSmartWasitSummaryIndex::class)->name('summary');
            Route::get('/ranking-skw', NewLaporanRankingSkwIndex::class)->name('ranking-skw');
            Route::get('/ranking-iaw', NewLaporanRankingIawIndex::class)->name('ranking-iaw');
            Route::get('/ranking-ik', NewLaporanRankingIkIndex::class)->name('ranking-ik');
            Route::get('/ranking-iv', NewLaporanRankingIvIndex::class)->name('ranking-iv');
            Route::get('/perbabak', NewLaporanPerbabakIndex::class)->name('perbabak');

            // Legacy Views (Old UI)
            Route::get('/old-summary', AdminLaporanSmartWasitSummaryIndex::class)->name('old-summary');
            Route::get('/old-ranking-skw', AdminLaporanRankingSkwIndex::class)->name('old-ranking-skw');
            Route::get('/old-ranking-iaw', AdminLaporanRankingIawIndex::class)->name('old-ranking-iaw');
            Route::get('/old-ranking-ik', AdminLaporanRankingIkIndex::class)->name('old-ranking-ik');
            Route::get('/old-ranking-iv', AdminLaporanRankingIvIndex::class)->name('old-ranking-iv');
            Route::get('/old-perbabak', AdminLaporanPerbabakIndex::class)->name('old-perbabak');
        });

        Route::prefix('panitera')->name('panitera.')->group(function () {
            Route::get('/generate-referee', AdminArbitraseGenerateRefereeIndex::class)->name('generate-referee');

            Route::prefix('scoring')->name('scoring.')->group(function () {
                Route::get('/', AdminArbitraseScoringIndex::class)->name('index');
                Route::get('/embu/{matchNumber}', AdminArbitraseScoringEmbuDetail::class)->name('embu.detail');
                Route::get('/randori/{matchNumber}', AdminArbitraseScoringRandoriDetail::class)->name('randori.detail');
                Route::get('/embu-testbench', AdminEmbuScoringTestbench::class)->name('embu.testbench');
                Route::get('/embu-result', NewEmbuResultIndex::class)->name('embu.result');
            });
            Route::get('/announcer', AnnouncerIndex::class)->name('announcer');
        });

        Route::prefix('referee')->name('referee.')->group(function () {
            Route::get('/scoring', RefereeScoringDashboard::class)->name('scoring');
        });
    });
});

Route::prefix('admin')->name('admin.')->group(function () {
    Route::prefix('arbitrase')->name('arbitrase.')->group(function () {
        Route::prefix('scoring')->name('scoring.')->group(function () {
            Route::get('/monitor/{courtId}', MonitorCourtIndex::class)->name('monitor');
            Route::get('/monitor-hasil/court/{courtId}', MonitorHasilIndex::class)->name('monitor-hasil.court');
            Route::get('/monitor-hasil/match/{matchId}', MonitorHasilIndex::class)->name('monitor-hasil.match');
            Route::get('/monitor-timer/court/{courtId}', MonitorTimerIndex::class)->name('monitor-timer.court');
            Route::get('/monitor-referee/court/{courtId}', MonitorRefereeIndex::class)->name('monitor-referee.court');
        });
    });
});

// require __DIR__.'/auth.php'; // Disabling Breeze auth routes

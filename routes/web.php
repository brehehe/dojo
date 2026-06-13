<?php

use App\Http\Controllers\RefereeScoringController;
use App\Http\Controllers\SvelteMonitorController;
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
use App\Livewire\Admin\Master\NewMatchNumberMergeIndex;
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
use App\Livewire\Admin\NewArbitraseIndex;
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
use App\Livewire\Admin\NewGeneratePaniteraIndex;
use App\Livewire\Admin\NewGenerateRefereeIndex;
use App\Livewire\Admin\NewKoordinatorIndex;
use App\Livewire\Admin\NewKyuLevelIndex;
use App\Livewire\Admin\NewLaporanHasilIndex;
use App\Livewire\Admin\NewLaporanRekapitulasiEmbu;
use App\Livewire\Admin\NewLaporanRekapitulasiRandori;
use App\Livewire\Admin\NewLaporanRekapPenilaianDetail;
use App\Livewire\Admin\NewLaporanRekapPenilaianIndex;
use App\Livewire\Admin\NewLaporanSkorIndex;
use App\Livewire\Admin\NewLaporanWasitIndex;
use App\Livewire\Admin\NewLaporanWasitJuriIndex;
use App\Livewire\Admin\NewMatchNumberIndex;
use App\Livewire\Admin\NewMultiNomorReportIndex;
use App\Livewire\Admin\NewOfficialForm;
use App\Livewire\Admin\NewOfficialIndex;
use App\Livewire\Admin\NewPaniteraIndex;
use App\Livewire\Admin\NewPaymentMethodIndex;
use App\Livewire\Admin\NewPoolIndex;
use App\Livewire\Admin\NewRefereeIndex;
use App\Livewire\Admin\NewRegistrationIndex;
use App\Livewire\Admin\NewRegistrationShow;
use App\Livewire\Admin\NewRegistrationVerificationIndex;
use App\Livewire\Admin\NewRekapitulasiEmbuDetailIndex;
use App\Livewire\Admin\NewRekapitulasiEmbuIndex;
use App\Livewire\Admin\NewRoleForm;
use App\Livewire\Admin\NewRoleIndex;
use App\Livewire\Admin\NewRundownIndex;
use App\Livewire\Admin\NewSessionTimeIndex;
use App\Livewire\Admin\NewTechnicalMeetingDrawingIndex;
use App\Livewire\Admin\NewTechniqueIndex;
use App\Livewire\Admin\NewUnregisteredAthleteReportIndex;
use App\Livewire\Admin\NewUserIndex;
use App\Livewire\Admin\NewWeightGroupIndex;
use App\Livewire\Admin\Profile\AdminProfileIndex;
use App\Livewire\Admin\Registration\AdminRegistrationIndex;
use App\Livewire\Admin\Registration\AdminRegistrationShow;
use App\Livewire\Admin\Reports\AdminRefereeObservationsIndex;
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
use App\Livewire\Contingent\AthleteVerificationIndex;
use App\Livewire\Contingent\Dashboard;
use App\Livewire\Contingent\LaporanWasit;
use App\Livewire\Contingent\Officials;
use App\Livewire\Contingent\RefereeObservationForm;
use App\Livewire\Contingent\RefereeObservationIndex;
use App\Livewire\Contingent\RegistrationHistoryDetailIndex;
use App\Livewire\Contingent\RegistrationHistoryIndex;
use App\Livewire\Contingent\RekapPertandingan;
use App\Livewire\Contingent\Results;
use App\Livewire\Contingent\Schedule;
use App\Livewire\Contingent\Setup;
use App\Livewire\Contingent\Standings;
use App\Livewire\GeneralDashboard;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
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

Route::middleware(['guest', 'nocache'])->group(function () {
    Route::get('login', NewLoginIndex::class)->name('login');
    Route::get('new-login', NewLoginIndex::class)->name('new-login');
    Route::get('register', Register::class)->name('register');
});

Route::get('/piala_walikotasby2026', function () {
    return view('register');
})->name('old_register');

Route::match(['get', 'post'], 'logout', function () {
    Auth::logout();
    request()->session()->invalidate();
    request()->session()->regenerateToken();

    return redirect('/login');
})->name('logout');

Route::middleware('auth')->group(function () {
    Route::get('dashboard', GeneralDashboard::class)->name('dashboard');
    Route::get('setup', Setup::class)->name('contingent.setup');
    Route::get('contingent/dashboard', Dashboard::class)->name('contingent.dashboard');
    Route::get('contingent/jadwal', Schedule::class)->name('contingent.schedule');
    Route::get('contingent/hasil', Results::class)->name('contingent.results');
    Route::get('contingent/klasemen', Standings::class)->name('contingent.standings');
    Route::get('contingent/athletes', Athletes::class)->name('contingent.athletes');
    Route::get('contingent/officials', Officials::class)->name('contingent.officials');
    Route::get('contingent/laporan-wasit', LaporanWasit::class)->name('contingent.laporan-wasit');
    Route::get('contingent/registration-history', RegistrationHistoryIndex::class)->name('contingent.registration-history');
    Route::get('contingent/registration-history/{registration}', RegistrationHistoryDetailIndex::class)->name('contingent.registration-history.show');
    Route::get('contingent/athlete-verification', AthleteVerificationIndex::class)->name('contingent.athlete-verification');
    Route::get('contingent/rekap-pertandingan', RekapPertandingan::class)->name('contingent.rekap-pertandingan');
    Route::get('contingent/observasi-wasit', RefereeObservationIndex::class)->name('contingent.observasi-wasit.index');
    Route::get('contingent/observasi-wasit/create', RefereeObservationForm::class)->name('contingent.observasi-wasit.create');
    Route::get('contingent/observasi-wasit/{observation}', RefereeObservationForm::class)->name('contingent.observasi-wasit.show');
    Route::get('contingent/observasi-wasit/{observation}/edit', RefereeObservationForm::class)->name('contingent.observasi-wasit.edit');

    Route::prefix('admin')->name('admin.')->group(function () {
        Route::get('/dashboard', AdminDashboard::class)->name('dashboard');
        Route::get('/home-dashboard', HomeDashboardIndex::class)->name('home-dashboard');
        Route::get('/new-dashboard', NewDashboardIndex::class)->name('new-dashboard');
        Route::get('/new-registrations', NewRegistrationIndex::class)->name('new-registrations');
        Route::get('/new-registrations/{registration}', NewRegistrationShow::class)->name('new-registrations.show');
        Route::get('/new-registration-verification', NewRegistrationVerificationIndex::class)->name('new-registration-verification');
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
        Route::get('/match-number-merges', NewMatchNumberMergeIndex::class)->name('match-number-merges');
        Route::get('/new-users', NewUserIndex::class)->name('new-users');
        Route::get('/new-koordinator', NewKoordinatorIndex::class)->name('new-koordinator');
        Route::get('/new-panitera', NewPaniteraIndex::class)->name('new-panitera');
        Route::get('/master/new-referees', NewRefereeIndex::class)->name('master.new-referees');
        Route::get('/arbitrase/new-referees', function () {
            return redirect()->route('admin.master.new-referees');
        });
        Route::get('/arbitrase/new-arbitrators', NewArbitraseIndex::class)->name('arbitrase.new-arbitrators');
        Route::get('/new-generate-referee', NewGenerateRefereeIndex::class)->name('new-generate-referee');
        Route::get('/new-generate-panitera', NewGeneratePaniteraIndex::class)->name('new-generate-panitera');
        Route::get('new-multi-nomor-report', NewMultiNomorReportIndex::class)->name('new-multi-nomor-report');
        Route::get('new-unregistered-athlete-report', NewUnregisteredAthleteReportIndex::class)->name('new-unregistered-athlete-report');
        Route::get('laporan-rekap-penilaian', NewLaporanRekapPenilaianIndex::class)->name('laporan-rekap-penilaian');
        Route::get('laporan-rekap-penilaian/{matchNumber}/cetak', NewLaporanRekapPenilaianDetail::class)->name('laporan-rekap-penilaian.cetak');

        Route::get('/new-scoring', [SvelteMonitorController::class, 'scoringIndex'])->name('new-scoring-index');
        Route::get('/new-scoring/embu/{matchNumber}', [SvelteMonitorController::class, 'scoringEmbu'])->name('new-scoring-embu-index');
        Route::get('/new-scoring/randori/{matchNumber}', [SvelteMonitorController::class, 'scoringRandori'])->name('new-scoring-randori-index');
        Route::get('/panitera/panggil-drawing', [SvelteMonitorController::class, 'panggilDrawingIndex'])->name('panitera.panggil-drawing');
        Route::get('/api/scoring/panggil-drawing-state', [SvelteMonitorController::class, 'panggilDrawingState'])->name('api.scoring.panggil-drawing-state');

        Route::prefix('api/scoring')->name('api.scoring.')->group(function () {
            Route::get('/dashboard-state', [SvelteMonitorController::class, 'scoringDashboardState'])->name('dashboard-state');
            Route::post('/activate-match', [SvelteMonitorController::class, 'activateMatch'])->name('activate-match');
            Route::post('/clear-court', [SvelteMonitorController::class, 'clearCourt'])->name('clear-court');
            Route::post('/clear-all-courts', [SvelteMonitorController::class, 'clearAllCourts'])->name('clear-all-courts');
            Route::post('/save-referee-assignment', [SvelteMonitorController::class, 'saveRefereeAssignment'])->name('save-referee-assignment');
            Route::post('/reset-active-referees', [SvelteMonitorController::class, 'resetActiveReferees'])->name('reset-active-referees');
            Route::post('/reset-court-referees', [SvelteMonitorController::class, 'resetCourtReferees'])->name('reset-court-referees');
            Route::post('/timer-control', [SvelteMonitorController::class, 'timerControl'])->name('timer-control');

            Route::prefix('embu')->name('embu.')->group(function () {
                Route::get('/{matchNumber}/state', [SvelteMonitorController::class, 'scoringEmbuState'])->name('state');
                Route::post('/call-officials', [SvelteMonitorController::class, 'embuCallOfficials'])->name('call-officials');
                Route::post('/call-participant', [SvelteMonitorController::class, 'embuCallParticipant'])->name('call-participant');
                Route::post('/save-score', [SvelteMonitorController::class, 'embuSaveScore'])->name('save-score');
                Route::post('/request-tiebreak', [SvelteMonitorController::class, 'embuRequestTiebreak'])->name('request-tiebreak');
                Route::post('/advance-to-final', [SvelteMonitorController::class, 'embuAdvanceToFinal'])->name('advance-to-final');
                Route::post('/dismiss-participant', [SvelteMonitorController::class, 'embuDismissParticipant'])->name('dismiss-participant');
                Route::post('/finish-match', [SvelteMonitorController::class, 'embuFinishMatch'])->name('finish-match');
            });

            Route::prefix('randori')->name('randori.')->group(function () {
                Route::get('/{matchNumber}/state', [SvelteMonitorController::class, 'scoringRandoriState'])->name('state');
                Route::post('/repair-bracket', [SvelteMonitorController::class, 'randoriRepairBracket'])->name('repair-bracket');
                Route::post('/call-officials', [SvelteMonitorController::class, 'randoriCallOfficials'])->name('call-officials');
                Route::post('/call-match', [SvelteMonitorController::class, 'randoriCallMatch'])->name('call-match');
                Route::post('/call-grand-final', [SvelteMonitorController::class, 'randoriCallGrandFinal'])->name('call-grand-final');
                Route::post('/dismiss-match', [SvelteMonitorController::class, 'randoriDismissMatch'])->name('dismiss-match');
                Route::post('/submit-scoring', [SvelteMonitorController::class, 'randoriSubmitScoring'])->name('submit-scoring');
                Route::post('/confirm-champion', [SvelteMonitorController::class, 'randoriConfirmChampion'])->name('confirm-champion');
            });
        });

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

        // Registration by Name Report (Excel)

        // Match Number & Class Report (Excel)

        // Athlete Biodata Report (Grid)
        Route::get('/reports/contingent-observations', AdminRefereeObservationsIndex::class)->name('reports.contingent-observations');

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
            Route::get('/new-laporan-wasit', NewLaporanWasitIndex::class)->name('new-laporan-wasit');
            Route::get('/new-laporan-wasit-juri', NewLaporanWasitJuriIndex::class)->name('new-laporan-wasit-juri');
            Route::get('/new-rekapitulasi-embu-index', NewRekapitulasiEmbuIndex::class)->name('new-rekapitulasi-embu-index');
            Route::get('/new-rekapitulasi-embu-detail/{matchNumber}', NewRekapitulasiEmbuDetailIndex::class)->name('new-rekapitulasi-embu-detail');

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
            Route::get('/scoring', [RefereeScoringController::class, 'index'])->name('scoring');
            Route::get('/scoring/state', [RefereeScoringController::class, 'state'])->name('scoring.state');
            Route::post('/scoring/save', [RefereeScoringController::class, 'save'])->name('scoring.save');
            Route::post('/scoring/submit', [RefereeScoringController::class, 'submit'])->name('scoring.submit');
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

Route::get('/api/court/{courtId}/timer-state', function ($courtId) {
    $state = Cache::get("court_{$courtId}_timer", [
        'status' => 'stopped',
        'elapsed_ms' => 0,
        'started_at_ms' => null,
    ]);
    $state['server_time_ms'] = floor(microtime(true) * 1000);

    return response()->json($state);
})->name('api.court.timer-state');

// Inertia Svelte Monitor Routes
Route::prefix('svelte-monitor')->name('svelte-monitor.')->group(function () {
    Route::get('/court/{court}', [SvelteMonitorController::class, 'monitorCourt'])->name('court');
    Route::get('/hasil/court/{court}', [SvelteMonitorController::class, 'monitorHasilCourt'])->name('hasil.court');
    Route::get('/hasil/match/{match}', [SvelteMonitorController::class, 'monitorHasilMatch'])->name('hasil.match');
    Route::get('/referee/court/{court}', [SvelteMonitorController::class, 'monitorReferee'])->name('referee');
    Route::get('/rekapitulasi-hasil/court/{court}', [SvelteMonitorController::class, 'monitorRekapitulasiHasil'])->name('rekapitulasi-hasil');
    Route::get('/timer/court/{court}', [SvelteMonitorController::class, 'monitorTimer'])->name('timer');
    Route::get('/timer-standalone', [SvelteMonitorController::class, 'monitorTimerStandalone'])->name('timer-standalone');
});

// JSON API Monitor Routes for Polling
Route::prefix('api/svelte-monitor')->name('api.svelte-monitor.')->group(function () {
    Route::get('/court/{court}/state', [SvelteMonitorController::class, 'monitorCourtState'])->name('court.state');
    Route::get('/hasil/court/{court}/state', [SvelteMonitorController::class, 'monitorHasilCourtState'])->name('hasil.court.state');
    Route::get('/hasil/match/{match}/state', [SvelteMonitorController::class, 'monitorHasilMatchState'])->name('hasil.match.state');
    Route::get('/referee/court/{court}/state', [SvelteMonitorController::class, 'monitorRefereeState'])->name('referee.state');
    Route::get('/rekapitulasi-hasil/court/{court}/state', [SvelteMonitorController::class, 'monitorRekapitulasiHasilState'])->name('rekapitulasi-hasil.state');
    Route::get('/timer/court/{court}/state', [SvelteMonitorController::class, 'monitorTimerState'])->name('timer.state');
});

<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\landing\HomeController;
use App\Http\Controllers\dashboard\DashboardController;
use App\Http\Controllers\auth\AuthController;
use App\Http\Controllers\dashboard\BpkhFormController;
use App\Http\Controllers\dashboard\UserProfileController;
use App\Http\Controllers\dashboard\ProdusenFormController;
use App\Http\Controllers\dashboard\UserManagementController;
use App\Http\Controllers\dashboard\SyncFormController;
use App\Http\Controllers\dashboard\BpkhPresentationController;
use App\Http\Controllers\dashboard\ProdusenPresentationController;
use App\Http\Controllers\dashboard\BpkhExhibitionController;
use App\Http\Controllers\dashboard\ProdusenExhibitionController;
use App\Http\Controllers\dashboard\PresentationSessionController;
use App\Http\Controllers\dashboard\CmsController;
use App\Http\Controllers\DashboardPeserta\AuthController as PesertaAuthController;

Route::get('/', [HomeController::class, 'index'])->name('home');

Route::get('/thanks-for-submit', [HomeController::class, 'thanksForSubmit'])->name('thanks-for-submit');

Route::get('/announcement', [HomeController::class, 'announcement'])->name('announcement');

// Result of presentation (Google Sheets tables)
Route::get('/result-presentation', [HomeController::class, 'resultPresentation'])->name('result-presentation');

Route::get('/cv-juri', [HomeController::class, 'cvJuri'])->name('cv-juri');

Route::get('/poster-criteria', [HomeController::class, 'posterCriteria'])->name('poster-criteria');

// Auth Routes (Guest only - redirect to dashboard if already logged in)
Route::middleware(['guest'])->group(function () {
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
    Route::get('/refresh-captcha', [AuthController::class, 'refreshCaptcha'])->name('refresh.captcha');
});

// Logout route (authenticated users only)
Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');

// Peserta Auth Routes (Guest only - redirect if already logged in)
Route::middleware(['guest:peserta'])->prefix('peserta')->group(function () {
    Route::get('/login', [PesertaAuthController::class, 'showLoginForm'])->name('peserta.login');
    Route::post('/login', [PesertaAuthController::class, 'login'])->name('peserta.login.post');
    Route::get('/daftar', [PesertaAuthController::class, 'showRegisterForm'])->name('peserta.register');
    Route::post('/daftar', [PesertaAuthController::class, 'register'])->name('peserta.register.post');
    Route::get('/refresh-captcha', [PesertaAuthController::class, 'refreshCaptcha'])->name('peserta.refresh-captcha');
});

// Peserta Logout route (authenticated peserta only)
Route::post('/peserta/logout', [PesertaAuthController::class, 'logout'])->name('peserta.logout')->middleware('auth:peserta');

// Peserta Dashboard Routes (Protected with peserta guard)
Route::middleware(['auth:peserta'])->prefix('peserta')->group(function () {
    Route::get('/dashboard', [PesertaAuthController::class, 'dashboard'])->name('peserta.dashboard');
});

// Dashboard Routes (Protected)
Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard.index');

    // Hasil Penilaian Final
    Route::get('/hasil-penilaian', [\App\Http\Controllers\dashboard\HasilPenilaianController::class, 'index'])->name('dashboard.hasil.index');

    // Profile Routes
    Route::get('/profile', [UserProfileController::class, 'index'])->name('profile.index');
    Route::put('/profile', [UserProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile/delete-image', [UserProfileController::class, 'deleteImage'])->name('profile.delete-image');



    // Detail & Nilai BPKH
    Route::get('/form-bpkh', [BpkhFormController::class, 'index'])->name('dashboard.form.bpkh.index');
    Route::get('/form-bpkh/export/excel', [BpkhFormController::class, 'exportExcel'])->name('dashboard.form.bpkh.export.excel');
    Route::get('/form-bpkh/export/csv', [BpkhFormController::class, 'exportCsv'])->name('dashboard.form.bpkh.export.csv');
    Route::get('/form-bpkh/export/pdf', [BpkhFormController::class, 'exportPdf'])->name('dashboard.form.bpkh.export.pdf');
    Route::get('/form-bpkh/export/excel-detail', [BpkhFormController::class, 'exportExcelDetail'])->name('dashboard.form.bpkh.export.excel-detail');
    Route::get('/form-bpkh/export/csv-detail', [BpkhFormController::class, 'exportCsvDetail'])->name('dashboard.form.bpkh.export.csv-detail');
    Route::get('/form-bpkh/export/pdf-detail', [BpkhFormController::class, 'exportPdfDetail'])->name('dashboard.form.bpkh.export.pdf-detail');
    Route::get('/form-bpkh/{respondentId}', [BpkhFormController::class, 'show'])->name('dashboard.form.bpkh.show');
    Route::get('/form-bpkh/{respondentId}/nilai', [BpkhFormController::class, 'editScore'])->name('dashboard.form.bpkh.score.edit')->middleware('prevent.admin.view');
    Route::post('/form-bpkh/{respondentId}/nilai', [BpkhFormController::class, 'updateScore'])->name('dashboard.form.bpkh.score.update')->middleware('prevent.admin.view');
    Route::get('/form-bpkh/{respondentId}/history', [BpkhFormController::class, 'getAssessmentHistory'])->name('dashboard.form.bpkh.history');


    // Detail & Nilai Produsen DG
    Route::get('/form-produsen-dg', [ProdusenFormController::class, 'index'])->name('dashboard.form.produsen-dg.index');
    Route::get('/form-produsen-dg/export/excel', [ProdusenFormController::class, 'exportExcel'])->name('dashboard.form.produsen-dg.export.excel');
    Route::get('/form-produsen-dg/export/csv', [ProdusenFormController::class, 'exportCsv'])->name('dashboard.form.produsen-dg.export.csv');
    Route::get('/form-produsen-dg/export/pdf', [ProdusenFormController::class, 'exportPdf'])->name('dashboard.form.produsen-dg.export.pdf');
    Route::get('/form-produsen-dg/export/excel-detail', [ProdusenFormController::class, 'exportExcelDetail'])->name('dashboard.form.produsen-dg.export.excel-detail');
    Route::get('/form-produsen-dg/export/csv-detail', [ProdusenFormController::class, 'exportCsvDetail'])->name('dashboard.form.produsen-dg.export.csv-detail');
    Route::get('/form-produsen-dg/export/pdf-detail', [ProdusenFormController::class, 'exportPdfDetail'])->name('dashboard.form.produsen-dg.export.pdf-detail');
    Route::get('/form-produsen-dg/{respondentId}', [ProdusenFormController::class, 'show'])->name('dashboard.form.produsen-dg.show');
    Route::get('/form-produsen-dg/{respondentId}/nilai', [ProdusenFormController::class, 'editScore'])->name('dashboard.form.produsen-dg.score.edit')->middleware('prevent.admin.view');
    Route::post('/form-produsen-dg/{respondentId}/nilai', [ProdusenFormController::class, 'updateScore'])->name('dashboard.form.produsen-dg.score.update')->middleware('prevent.admin.view');
    Route::get('/form-produsen-dg/{respondentId}/history', [ProdusenFormController::class, 'getAssessmentHistory'])->name('dashboard.form.produsen-dg.history');

    // Penilaian Presentasi BPKH
    Route::get('/presentation-bpkh', [BpkhPresentationController::class, 'index'])->name('dashboard.presentation.bpkh.index');
    // Bulk Scoring BPKH (must be BEFORE dynamic routes)
    Route::get('/presentation-bpkh/bulk-score', [BpkhPresentationController::class, 'bulkScoreForm'])->name('dashboard.presentation.bpkh.bulk-score')->middleware('prevent.admin.view');
    Route::post('/presentation-bpkh/bulk-score', [BpkhPresentationController::class, 'bulkScoreStore'])->name('dashboard.presentation.bpkh.bulk-score.store')->middleware('prevent.admin.view');
    Route::get('/presentation-bpkh/{respondentId}', [BpkhPresentationController::class, 'show'])->name('dashboard.presentation.bpkh.show');
    Route::get('/presentation-bpkh/{respondentId}/nilai', [BpkhPresentationController::class, 'edit'])->name('dashboard.presentation.bpkh.edit')->middleware('prevent.admin.view');
    Route::post('/presentation-bpkh/{respondentId}/nilai', [BpkhPresentationController::class, 'update'])->name('dashboard.presentation.bpkh.update')->middleware('prevent.admin.view');
    Route::get('/presentation-bpkh/{respondentId}/history', [BpkhPresentationController::class, 'history'])->name('dashboard.presentation.bpkh.history');

    // Penilaian Presentasi Produsen DG
    Route::get('/presentation-produsen-dg', [ProdusenPresentationController::class, 'index'])->name('dashboard.presentation.produsen.index');
    // Bulk Scoring Produsen (must be BEFORE dynamic routes)
    Route::get('/presentation-produsen-dg/bulk-score', [ProdusenPresentationController::class, 'bulkScoreForm'])->name('dashboard.presentation.produsen.bulk-score')->middleware('prevent.admin.view');
    Route::post('/presentation-produsen-dg/bulk-score', [ProdusenPresentationController::class, 'bulkScoreStore'])->name('dashboard.presentation.produsen.bulk-score.store')->middleware('prevent.admin.view');
    Route::get('/presentation-produsen-dg/{respondentId}', [ProdusenPresentationController::class, 'show'])->name('dashboard.presentation.produsen.show');
    Route::get('/presentation-produsen-dg/{respondentId}/nilai', [ProdusenPresentationController::class, 'edit'])->name('dashboard.presentation.produsen.edit')->middleware('prevent.admin.view');
    Route::post('/presentation-produsen-dg/{respondentId}/nilai', [ProdusenPresentationController::class, 'update'])->name('dashboard.presentation.produsen.update')->middleware('prevent.admin.view');
    Route::get('/presentation-produsen-dg/{respondentId}/history', [ProdusenPresentationController::class, 'history'])->name('dashboard.presentation.produsen.history');

    // Penilaian Exhibition/Poster BPKH
    Route::get('/exhibition-bpkh', [BpkhExhibitionController::class, 'index'])->name('dashboard.exhibition.bpkh.index');
    Route::get('/exhibition-bpkh/bulk-score', [BpkhExhibitionController::class, 'bulkScoreForm'])->name('dashboard.exhibition.bpkh.bulk-score')->middleware('prevent.admin.view');
    Route::post('/exhibition-bpkh/bulk-score', [BpkhExhibitionController::class, 'bulkScoreStore'])->name('dashboard.exhibition.bpkh.bulk-score.store')->middleware('prevent.admin.view');
    Route::get('/exhibition-bpkh/{respondentId}', [BpkhExhibitionController::class, 'show'])->name('dashboard.exhibition.bpkh.show');
    Route::get('/exhibition-bpkh/{respondentId}/nilai', [BpkhExhibitionController::class, 'edit'])->name('dashboard.exhibition.bpkh.edit')->middleware('prevent.admin.view');
    Route::post('/exhibition-bpkh/{respondentId}/nilai', [BpkhExhibitionController::class, 'update'])->name('dashboard.exhibition.bpkh.update')->middleware('prevent.admin.view');

    // Penilaian Exhibition/Poster Produsen DG
    Route::get('/exhibition-produsen-dg', [ProdusenExhibitionController::class, 'index'])->name('dashboard.exhibition.produsen.index');
    Route::get('/exhibition-produsen-dg/bulk-score', [ProdusenExhibitionController::class, 'bulkScoreForm'])->name('dashboard.exhibition.produsen.bulk-score')->middleware('prevent.admin.view');
    Route::post('/exhibition-produsen-dg/bulk-score', [ProdusenExhibitionController::class, 'bulkScoreStore'])->name('dashboard.exhibition.produsen.bulk-score.store')->middleware('prevent.admin.view');
    Route::get('/exhibition-produsen-dg/{respondentId}', [ProdusenExhibitionController::class, 'show'])->name('dashboard.exhibition.produsen.show');
    Route::get('/exhibition-produsen-dg/{respondentId}/nilai', [ProdusenExhibitionController::class, 'edit'])->name('dashboard.exhibition.produsen.edit')->middleware('prevent.admin.view');
    Route::post('/exhibition-produsen-dg/{respondentId}/nilai', [ProdusenExhibitionController::class, 'update'])->name('dashboard.exhibition.produsen.update')->middleware('prevent.admin.view');

    // User Management Routes (Admin & Superadmin only)
    Route::middleware(['role:admin,superadmin'])->group(function () {
        Route::get('/user-management', [UserManagementController::class, 'index'])->name('dashboard.user-management.index');
        Route::get('/user-management/{id}', [UserManagementController::class, 'show'])->name('dashboard.user-management.show');
        Route::post('/user-management', [UserManagementController::class, 'store'])->name('dashboard.user-management.store');
        Route::put('/user-management/{id}', [UserManagementController::class, 'update'])->name('dashboard.user-management.update');
        Route::delete('/user-management/{id}', [UserManagementController::class, 'destroy'])->name('dashboard.user-management.destroy');
    });

    // Sync Form Routes (Superadmin only)
    Route::middleware(['role:superadmin'])->group(function () {
        Route::get('/sync-form', [SyncFormController::class, 'index'])->name('sync-form.index');
        Route::post('/sync-form/bpkh', [SyncFormController::class, 'syncFormBpkh'])->name('sync-form.bpkh');
        Route::post('/sync-form/produsen', [SyncFormController::class, 'syncFormProdusen'])->name('sync-form.produsen');
        
        // Presentation Session Management Routes (Superadmin only)
        Route::get('/presentation-session', [PresentationSessionController::class, 'index'])->name('dashboard.presentation-session.index');
        Route::post('/presentation-session/bpkh', [PresentationSessionController::class, 'storeBpkh'])->name('dashboard.presentation-session.bpkh.store');
        Route::post('/presentation-session/produsen', [PresentationSessionController::class, 'storeProdusen'])->name('dashboard.presentation-session.produsen.store');
        Route::delete('/presentation-session/bpkh/{id}', [PresentationSessionController::class, 'destroyBpkh'])->name('dashboard.presentation-session.bpkh.destroy');
        Route::delete('/presentation-session/produsen/{id}', [PresentationSessionController::class, 'destroyProdusen'])->name('dashboard.presentation-session.produsen.destroy');
        Route::post('/presentation-session/update-order', [PresentationSessionController::class, 'updateOrder'])->name('dashboard.presentation-session.update-order');
        Route::post('/presentation-session/update-session-order', [PresentationSessionController::class, 'updateSessionOrder'])->name('dashboard.presentation-session.update-session-order');
        Route::post('/presentation-session/config', [PresentationSessionController::class, 'storeSessionConfig'])->name('dashboard.presentation-session.config.store');
        Route::delete('/presentation-session/config/{id}', [PresentationSessionController::class, 'destroySessionConfig'])->name('dashboard.presentation-session.config.destroy');
        
        // CMS Launch Date Routes (Superadmin only)
        Route::get('/cms/launch-date', [CmsController::class, 'launchDateIndex'])->name('dashboard.cms.launch-date.index');
        Route::post('/cms/launch-date', [CmsController::class, 'launchDateStore'])->name('dashboard.cms.launch-date.store');
        Route::put('/cms/launch-date/{id}', [CmsController::class, 'launchDateUpdate'])->name('dashboard.cms.launch-date.update');
        Route::delete('/cms/launch-date/{id}', [CmsController::class, 'launchDateDestroy'])->name('dashboard.cms.launch-date.destroy');
        Route::post('/cms/launch-date/update-order', [CmsController::class, 'launchDateUpdateOrder'])->name('dashboard.cms.launch-date.update-order');
    });

});

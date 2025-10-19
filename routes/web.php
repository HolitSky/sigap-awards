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

Route::get('/', [HomeController::class, 'index'])->name('home');

Route::get('/thanks-for-submit', [HomeController::class, 'thanksForSubmit'])->name('thanks-for-submit');

// Auth Routes
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
Route::get('/refresh-captcha', [AuthController::class, 'refreshCaptcha'])->name('refresh.captcha');

// Dashboard Routes (Protected)
Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard.index');

    // Profile Routes
    Route::get('/profile', [UserProfileController::class, 'index'])->name('profile.index');
    Route::put('/profile', [UserProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile/delete-image', [UserProfileController::class, 'deleteImage'])->name('profile.delete-image');



    // Detail & Nilai BPKH
    Route::get('/form-bpkh', [BpkhFormController::class, 'index'])->name('dashboard.form.bpkh.index');
    Route::get('/form-bpkh/{respondentId}', [BpkhFormController::class, 'show'])->name('dashboard.form.bpkh.show');
    Route::get('/form-bpkh/{respondentId}/nilai', [BpkhFormController::class, 'editScore'])->name('dashboard.form.bpkh.score.edit');
    Route::post('/form-bpkh/{respondentId}/nilai', [BpkhFormController::class, 'updateScore'])->name('dashboard.form.bpkh.score.update');
    Route::get('/form-bpkh/{respondentId}/history', [BpkhFormController::class, 'getAssessmentHistory'])->name('dashboard.form.bpkh.history');


    // Detail & Nilai Produsen DG
    Route::get('/form-produsen-dg', [ProdusenFormController::class, 'index'])->name('dashboard.form.produsen-dg.index');
    Route::get('/form-produsen-dg/{respondentId}', [ProdusenFormController::class, 'show'])->name('dashboard.form.produsen-dg.show');
    Route::get('/form-produsen-dg/{respondentId}/nilai', [ProdusenFormController::class, 'editScore'])->name('dashboard.form.produsen-dg.score.edit');
    Route::post('/form-produsen-dg/{respondentId}/nilai', [ProdusenFormController::class, 'updateScore'])->name('dashboard.form.produsen-dg.score.update');
    Route::get('/form-produsen-dg/{respondentId}/history', [ProdusenFormController::class, 'getAssessmentHistory'])->name('dashboard.form.produsen-dg.history');

    // Penilaian Presentasi BPKH
    Route::get('/presentation-bpkh', [BpkhPresentationController::class, 'index'])->name('dashboard.presentation.bpkh.index');
    Route::get('/presentation-bpkh/{respondentId}', [BpkhPresentationController::class, 'show'])->name('dashboard.presentation.bpkh.show');
    Route::get('/presentation-bpkh/{respondentId}/nilai', [BpkhPresentationController::class, 'edit'])->name('dashboard.presentation.bpkh.edit');
    Route::post('/presentation-bpkh/{respondentId}/nilai', [BpkhPresentationController::class, 'update'])->name('dashboard.presentation.bpkh.update');
    Route::get('/presentation-bpkh/{respondentId}/history', [BpkhPresentationController::class, 'history'])->name('dashboard.presentation.bpkh.history');

    // Penilaian Presentasi Produsen DG
    Route::get('/presentation-produsen-dg', [ProdusenPresentationController::class, 'index'])->name('dashboard.presentation.produsen.index');
    Route::get('/presentation-produsen-dg/{respondentId}', [ProdusenPresentationController::class, 'show'])->name('dashboard.presentation.produsen.show');
    Route::get('/presentation-produsen-dg/{respondentId}/nilai', [ProdusenPresentationController::class, 'edit'])->name('dashboard.presentation.produsen.edit');
    Route::post('/presentation-produsen-dg/{respondentId}/nilai', [ProdusenPresentationController::class, 'update'])->name('dashboard.presentation.produsen.update');
    Route::get('/presentation-produsen-dg/{respondentId}/history', [ProdusenPresentationController::class, 'history'])->name('dashboard.presentation.produsen.history');


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
    });

});

<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\landing\HomeController;
use App\Http\Controllers\dashboard\DashboardController;
use App\Http\Controllers\auth\AuthController;
use App\Http\Controllers\dashboard\BpkhFormController;
use App\Http\Controllers\dashboard\UserProfileController;

Route::get('/', [HomeController::class, 'index'])->name('home');

Route::get('/thanks-for-submit', [HomeController::class, 'thanksForSubmit'])->name('thanks-for-submit');

// Auth Routes
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Dashboard Routes (Protected)
Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard.index');

    // Profile Routes
    Route::get('/profile', [UserProfileController::class, 'index'])->name('profile.index');
    Route::put('/profile', [UserProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile/delete-image', [UserProfileController::class, 'deleteImage'])->name('profile.delete-image');

    Route::get('/form-bpkh', [BpkhFormController::class, 'index'])->name('dashboard.form.bpkh.index');
    Route::get('/form-produsen-dg', [DashboardController::class, 'produsenDg'])->name('dashboard.form.produsen-dg.index');


     // Detail & Nilai
  Route::get('/form-bpkh/{respondentId}', [BpkhFormController::class, 'show'])->name('dashboard.form.bpkh.show');
  Route::get('/form-bpkh/{respondentId}/nilai', [BpkhFormController::class, 'editScore'])->name('dashboard.form.bpkh.score.edit');
  Route::post('/form-bpkh/{respondentId}/nilai', [BpkhFormController::class, 'updateScore'])->name('dashboard.form.bpkh.score.update');

});

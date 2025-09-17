<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\auth\AuthController;
use App\Http\Controllers\dashboard\UserManagementController;

// AUTH
Route::prefix('auth')->group(function () {
    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/login',    [AuthController::class, 'login'])->middleware('throttle:auth');

    Route::middleware('auth:sanctum')->group(function () {
        Route::get('/me',          [AuthController::class, 'me']);
        Route::post('/logout',     [AuthController::class, 'logout']);
        Route::post('/logout-all', [AuthController::class, 'logoutAll']);
    });
});

// PESERTA area (peserta + role lebih tinggi)
Route::middleware(['auth:sanctum','role:peserta,panitia,admin,superadmin'])
    ->get('/peserta/ping', fn() => ['ok' => true]);

// PANITIA area (panitia/admin/superadmin)
Route::middleware(['auth:sanctum','role:panitia,admin,superadmin'])
    ->get('/panitia/ping', fn() => ['ok' => true]);

// ADMIN area (admin/superadmin)
Route::middleware(['auth:sanctum','role:admin,superadmin'])
    ->prefix('admin')->group(function () {
        Route::get('/ping', fn() => ['ok' => true]);
        Route::get('/users', [UserManagementController::class, 'index']);
        Route::post('/users', [UserManagementController::class, 'store']);
    });

// SUPERADMIN only
Route::middleware(['auth:sanctum','role:superadmin'])
    ->prefix('superadmin')->group(function () {
        Route::get('/ping', fn() => ['ok' => true]);
        Route::patch('/users/{user}/role', [UserManagementController::class, 'updateRole']);
    });

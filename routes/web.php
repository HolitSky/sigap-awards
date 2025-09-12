<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\landing\HomeController;

Route::get('/', [HomeController::class, 'index'])->name('home');

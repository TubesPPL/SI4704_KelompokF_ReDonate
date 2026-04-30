<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DashboardController;
use Illuminate\Support\Facades\Route;

// ========================================
// PUBLIC ROUTES (Guest Only)
// ========================================
Route::middleware('guest')->group(function () {

    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);

    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
});

// ========================================
// AUTH ROUTES
// ========================================
Route::middleware('auth')->group(function () {

    // DASHBOARD
    Route::get('/dashboard', [DashboardController::class, 'index'])
        ->name('dashboard');

    // PROFILE (PBI 2–4)
    Route::prefix('profile')->name('profile.')->group(function () {

        Route::get('/', [ProfileController::class, 'index'])->name('index');
        Route::put('/update', [ProfileController::class, 'update'])->name('update');
        Route::post('/deactivate', [ProfileController::class, 'deactivate'])->name('deactivate');
        Route::delete('/destroy', [ProfileController::class, 'destroy'])->name('destroy');
    });

    // LOGOUT (IMPORTANT FIX: HARUS POST)
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
});

// ========================================
// DEFAULT ROUTE
// ========================================
Route::get('/', function () {
    return redirect()->route('login');
});
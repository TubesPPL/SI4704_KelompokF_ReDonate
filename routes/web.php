<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\RequestItemController;
use Illuminate\Support\Facades\Route;

// ========================================
//              PUBLIC ROUTES
// ========================================
Route::middleware('guest')->group(function () {

    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);

    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
});


// ========================================
//              AUTH ROUTES
// ========================================
Route::middleware('auth')->group(function () {

    // DASHBOARD UMUM
    Route::get('/dashboard', [DashboardController::class, 'index'])
        ->name('dashboard');

    // DASHBOARD PER ROLE
    Route::get('/dashboard/penerima', [DashboardController::class, 'penerima'])
        ->name('dashboard.penerima');

    Route::get('/dashboard/donatur', [DashboardController::class, 'donatur'])
        ->name('dashboard.donatur');


    // ========================================
    //               REQUEST BARANG
    // ========================================
    Route::get('/request', [RequestItemController::class, 'create'])
        ->name('request.create');

    Route::post('/request', [RequestItemController::class, 'store'])
        ->name('request.store');

    // ========================================
    //       PANEL DONATUR (PBI 17 - 20)
    // ========================================
    Route::get('/donatur/requests', [RequestItemController::class, 'donaturRequests'])
        ->name('donatur.requests.index');
        
    Route::patch('/donatur/requests/{id}/approve', [RequestItemController::class, 'approve'])
        ->name('donatur.requests.approve');
        
    Route::patch('/donatur/requests/{id}/reject', [RequestItemController::class, 'reject'])
        ->name('donatur.requests.reject');
        
    Route::delete('/donatur/requests/clear', [RequestItemController::class, 'clearHistory'])
        ->name('donatur.requests.clear');



    // PBI #14: Menampilkan daftar permintaan
    Route::get('/my-requests', [ItemRequestController::class, 'index'])->name('requests.index');

    // PBI #13: Mekanisme pembuatan permintaan barang
    Route::get('/items/{item}/request', [ItemRequestController::class, 'create'])->name('requests.create');
    Route::post('/items/{item}/request', [ItemRequestController::class, 'store'])->name('requests.store');

    // PBI #15: Memperbarui preferensi (Edit Request)
    Route::get('/requests/{requestItem}/edit', [ItemRequestController::class, 'edit'])->name('requests.edit');
    Route::put('/requests/{requestItem}', [ItemRequestController::class, 'update'])->name('requests.update');

    // PBI #16: Mekanisme pembatalan permintaan
    Route::patch('/requests/{requestItem}/cancel', [ItemRequestController::class, 'cancel'])->name('requests.cancel');


    // ========================================
    //                  PROFILE
    // ========================================
    Route::prefix('profile')->name('profile.')->group(function () {

        Route::get('/', [ProfileController::class, 'index'])->name('index');
        Route::put('/update', [ProfileController::class, 'update'])->name('update');
        Route::post('/deactivate', [ProfileController::class, 'deactivate'])->name('deactivate');
        Route::delete('/destroy', [ProfileController::class, 'destroy'])->name('destroy');
    });


    // LOGOUT
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
});


// ========================================
//              DEFAULT ROUTE
// ========================================
Route::get('/', function () {
    return redirect()->route('login');
});
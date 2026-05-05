<?php

use Illuminate\Http\Request;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\RequestItemController;
use App\Http\Controllers\ItemRequestController;
use App\Http\Controllers\ItemPenerimaController;
use App\Http\Controllers\ItemController; // Tambahkan controller Item
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
//            ITEMS (PUBLIC)
// ========================================
Route::get('/items/create', function () {
    return view('items.create');
})->name('items.create');

Route::get('/items/{id}', function ($id) {
    return view('items.show', compact('id'));
})->name('items.show');

Route::get('/items/{id}/edit', function ($id) {
    return view('items.edit', compact('id'));
})->name('items.edit');


// ========================================
//              AUTH ROUTES
// ========================================
Route::middleware('auth')->group(function () {

    // DASHBOARD UMUM
    Route::get('/dashboard', [DashboardController::class, 'index'])
        ->name('dashboard');

    // MENGATASI ERROR 404 SAAT MENGKLIK ITEM DI DASHBOARD
    Route::get('/items/{id}', [ItemController::class, 'show'])->name('items.show');

    // DASHBOARD PER ROLE
    Route::get('/dashboard/penerima', [DashboardController::class, 'penerima'])
        ->name('dashboard.penerima');

    Route::get('/dashboard/donatur', [DashboardController::class, 'donatur'])
        ->name('dashboard.donatur');


    // ========================================
    //               PENERIMAAN BARANG
    // ========================================
    // PBI #14: Menampilkan daftar permintaan
    Route::get('/my-requests', [ItemPenerimaController::class, 'index'])->name('requests.index');

    // PBI #13: Mekanisme pembuatan permintaan barang
    Route::get('/items/{item}/request', [ItemPenerimaController::class, 'create'])->name('requests.create');
    Route::post('/items/{item}/request', [ItemPenerimaController::class, 'store'])->name('requests.store');

    // PBI #15: Memperbarui preferensi (Edit Request)
    Route::get('/requests/{requestItem}/edit', [ItemPenerimaController::class, 'edit'])->name('requests.edit');
    Route::put('/requests/{requestItem}', [ItemPenerimaController::class, 'update'])->name('requests.update');

    // PBI #16: Mekanisme pembatalan permintaan
    Route::patch('/requests/{requestItem}/cancel', [ItemPenerimaController::class, 'cancel'])->name('requests.cancel');

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


    // ========================================
    //                   PROFILE
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
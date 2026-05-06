<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\{
    AuthController, DashboardController, ItemController, 
    ItemPenerimaController, RequestItemController, ProfileController
};

Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
});

Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // ITEMS
    Route::get('/items/create', fn() => view('items.create'))->name('items.create');
    Route::post('/items', [ItemController::class, 'store'])->name('items.store');
    Route::get('/items/{id}', [ItemController::class, 'show'])->name('items.show');

    // PENERIMA (PBI 14-16)
    Route::get('/my-requests', [ItemPenerimaController::class, 'index'])->name('requests.index');
    Route::get('/requests/{requestItem}/edit', [ItemPenerimaController::class, 'edit'])->name('requests.edit');
    Route::put('/requests/{requestItem}', [ItemPenerimaController::class, 'update'])->name('requests.update');
    Route::patch('/requests/{requestItem}/cancel', [ItemPenerimaController::class, 'cancel'])->name('requests.cancel');

    // PROSES REQUEST (PBI 13)
    Route::post('/request/store', [RequestItemController::class, 'store'])->name('request.store');

    // DONATUR (PBI 17-20)
    Route::get('/donatur/requests', [RequestItemController::class, 'donaturRequests'])->name('donatur.requests.index');
    Route::patch('/donatur/requests/{id}/approve', [RequestItemController::class, 'approve'])->name('donatur.requests.approve');
    Route::patch('/donatur/requests/{id}/reject', [RequestItemController::class, 'reject'])->name('donatur.requests.reject');
    
    // BARIS INI YANG SEBELUMNYA HILANG DAN MENYEBABKAN ERROR:
    Route::delete('/donatur/requests/clear', [RequestItemController::class, 'clearHistory'])->name('donatur.requests.clear');

    // LOGOUT
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
});

Route::get('/', fn() => redirect()->route('login'));
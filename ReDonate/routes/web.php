<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\AuthController;

// tampil halaman login
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');

// proses login
Route::post('/login', [AuthController::class, 'login']);

// tampil halaman register
Route::get('/register', [AuthController::class, 'showRegister'])->name('register');

// proses register
Route::post('/register', [AuthController::class, 'register']);

// logout
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::middleware('auth')->group(function () {

    Route::get('/profile', [ProfileController::class, 'index'])
        ->name('profile');

    Route::put('/profile/update', [ProfileController::class, 'update'])
        ->name('profile.update');

    Route::put('/profile/deactivate', [ProfileController::class, 'deactivate']);

    Route::delete('/profile/delete', [ProfileController::class, 'destroy']);
});

Route::get('/dashboard', function () {
    return view('dashboard.main');
})->middleware('auth');

Route::get('/dashboard/donatur', function () {
    return view('dashboard.donatur');
})->middleware('auth');

Route::get('/dashboard/penerima', function () {
    return view('dashboard.penerima');
})->middleware('auth');

// default
Route::get('/', function () {
    return redirect('/login');
});
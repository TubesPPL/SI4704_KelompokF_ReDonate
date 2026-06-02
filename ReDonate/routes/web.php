<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\WelcomeController;

// 1. Public Routes
Route::get('/', [WelcomeController::class, 'index'])->name('home');

Route::get('/user/{id}', [ProfileController::class, 'show'])->name('profile.show');

// Nanti tambahkan route katalog, detail item, event publik di sini

// 2. Auth Routes
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', [\App\Http\Controllers\DashboardController::class, 'index'])->name('dashboard');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::post('/profile/avatar', [ProfileController::class, 'uploadAvatar'])->name('profile.avatar.upload');

    // Manajemen Item Donatur
    Route::resource('items', \App\Http\Controllers\ItemController::class)->except(['show']);
    Route::patch('/items/{slug}/status', [\App\Http\Controllers\ItemController::class, 'updateStatus'])->name('items.update-status');

    // Manajemen Klaim oleh Donatur
    Route::patch('/claims/{claim}/approve', [\App\Http\Controllers\ClaimController::class, 'approve'])->name('claims.approve');
    Route::patch('/claims/{claim}/reject', [\App\Http\Controllers\ClaimController::class, 'reject'])->name('claims.reject');
    Route::patch('/claims/{claim}/complete', [\App\Http\Controllers\ClaimController::class, 'complete'])->name('claims.complete');

    // Pengajuan Klaim oleh Penerima
    Route::post('/claims/{item}', [\App\Http\Controllers\RecipientClaimController::class, 'store'])->name('claims.store');
    
    // Dashboard Penerima (Riwayat Klaim)
    Route::get('/recipient/dashboard', [\App\Http\Controllers\RecipientController::class, 'dashboard'])->name('recipient.dashboard');

    // Ulasan
    Route::get('/reviews/create/{claim}', [\App\Http\Controllers\ReviewController::class, 'create'])->name('reviews.create');
    Route::post('/reviews/{claim}', [\App\Http\Controllers\ReviewController::class, 'store'])->name('reviews.store');

    // Chat
    Route::prefix('chat')->name('chat.')->group(function () {
        Route::get('/{claim}', [\App\Http\Controllers\MessageController::class, 'show'])->name('show');
        Route::post('/{claim}', [\App\Http\Controllers\MessageController::class, 'store'])->name('store');
        Route::get('/{claim}/poll', [\App\Http\Controllers\MessageController::class, 'poll'])->name('poll');
        Route::post('/{claim}/read', [\App\Http\Controllers\MessageController::class, 'markRead'])->name('markRead');
    });

    // Wishlist (Pribadi)
    Route::get('/wishlist', [\App\Http\Controllers\WishlistController::class, 'index'])->name('wishlist.index');
    Route::post('/wishlist/{item}/toggle', [\App\Http\Controllers\WishlistController::class, 'toggle'])->name('wishlist.toggle');
    Route::delete('/wishlist/{item}', [\App\Http\Controllers\WishlistController::class, 'destroy'])->name('wishlist.destroy');

    // Wishlist Requests (Auth)
    Route::resource('wishlist-requests', \App\Http\Controllers\WishlistRequestController::class)->except(['index', 'show']);
    Route::post('/wishlist-requests/{wishlist_request}/fulfill', [\App\Http\Controllers\WishlistRequestController::class, 'fulfill'])->name('wishlist-requests.fulfill');

    // Pelaporan (Report)
    Route::get('/report/create', [\App\Http\Controllers\ReportController::class, 'create'])->name('report.create');
    Route::post('/report', [\App\Http\Controllers\ReportController::class, 'store'])->name('report.store');
});

// Route publik detail item (harus di luar middleware auth jika bisa dilihat publik)
Route::get('/items/{slug}', [\App\Http\Controllers\ItemController::class, 'show'])->name('items.show');

// Route publik Wishlist Requests
Route::get('/wishlist-requests', [\App\Http\Controllers\WishlistRequestController::class, 'index'])->name('wishlist-requests.index');
Route::get('/wishlist-requests/{wishlist_request}', [\App\Http\Controllers\WishlistRequestController::class, 'show'])->name('wishlist-requests.show');

// Route Katalog
Route::get('/katalog', [\App\Http\Controllers\CatalogController::class, 'index'])->name('catalog.index');

// Route Events Publik
Route::get('/events', [\App\Http\Controllers\EventController::class, 'index'])->name('events.index');
Route::get('/events/{slug}', [\App\Http\Controllers\EventController::class, 'show'])->name('events.show');

// 3. Admin Routes
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/', [\App\Http\Controllers\Admin\DashboardController::class, 'index'])->name('dashboard');
    Route::get('/dashboard', [\App\Http\Controllers\Admin\DashboardController::class, 'index']);

    // Users
    Route::get('/users', [\App\Http\Controllers\Admin\UserController::class, 'index'])->name('users.index');
    Route::patch('/users/{user}/toggle-role', [\App\Http\Controllers\Admin\UserController::class, 'toggleRole'])->name('users.toggleRole');
    Route::patch('/users/{user}/toggle-verified', [\App\Http\Controllers\Admin\UserController::class, 'toggleVerified'])->name('users.toggleVerified');

    // Items
    Route::get('/items', [\App\Http\Controllers\Admin\ItemController::class, 'index'])->name('items.index');
    Route::patch('/items/{item}/status', [\App\Http\Controllers\Admin\ItemController::class, 'updateStatus'])->name('items.updateStatus');
    Route::delete('/items/{item}', [\App\Http\Controllers\Admin\ItemController::class, 'destroy'])->name('items.destroy');

    // Categories (CRUD)
    Route::get('/categories', [\App\Http\Controllers\Admin\CategoryController::class, 'index'])->name('categories.index');
    Route::post('/categories', [\App\Http\Controllers\Admin\CategoryController::class, 'store'])->name('categories.store');
    Route::put('/categories/{category}', [\App\Http\Controllers\Admin\CategoryController::class, 'update'])->name('categories.update');
    Route::delete('/categories/{category}', [\App\Http\Controllers\Admin\CategoryController::class, 'destroy'])->name('categories.destroy');

    // Events (admin management)
    Route::get('/events', [\App\Http\Controllers\Admin\EventController::class, 'index'])->name('events.index');
    Route::patch('/events/{event}/cancel', [\App\Http\Controllers\Admin\EventController::class, 'cancel'])->name('events.cancel');

    // Claims (read-only overview)
    Route::get('/claims', [\App\Http\Controllers\Admin\ClaimController::class, 'index'])->name('claims.index');

    // Reports (Moderation)
    Route::resource('reports', \App\Http\Controllers\Admin\ReportController::class)->only(['index', 'show', 'update']);
});

// Route Event Admin (menggunakan middleware auth biasa untuk sementara karena belum ada seeder admin)
// Idealnya ini dimasukkan ke dalam group admin di atas
Route::middleware(['auth'])->group(function () {
    Route::get('/admin/events/create', [\App\Http\Controllers\EventController::class, 'create'])->name('events.create');
    Route::post('/admin/events', [\App\Http\Controllers\EventController::class, 'store'])->name('events.store');
    Route::get('/admin/events/{slug}/edit', [\App\Http\Controllers\EventController::class, 'edit'])->name('events.edit');
    Route::put('/admin/events/{slug}', [\App\Http\Controllers\EventController::class, 'update'])->name('events.update');
    Route::delete('/admin/events/{slug}', [\App\Http\Controllers\EventController::class, 'destroy'])->name('events.destroy');

    // Notifikasi
    Route::get('/notifications', [\App\Http\Controllers\NotificationController::class, 'index'])->name('notifications.index');
    Route::get('/notifications/{id}/read', [\App\Http\Controllers\NotificationController::class, 'markAsRead'])->name('notifications.read');
    Route::post('/notifications/mark-all-read', [\App\Http\Controllers\NotificationController::class, 'markAllRead'])->name('notifications.markAllRead');
    Route::delete('/notifications/{id}', [\App\Http\Controllers\NotificationController::class, 'destroy'])->name('notifications.destroy');
});

require __DIR__.'/auth.php';

<?php

// routes/api.php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ItemController;


// ─── Public Routes ────────────────────────────────────────────────────────────
Route::prefix('v1')->group(function () {

    // Daftar kategori (publik, untuk dropdown)
    Route::get('categories', [ItemController::class, 'categories']);

    // Daftar semua barang yang tersedia (publik)
    Route::get('items', [ItemController::class, 'index']);

    // Detail satu barang (publik)
    Route::get('items/{item}', [ItemController::class, 'show']);
});

    Route::prefix('v1')->group(function () {

    // PBI #5 – Upload barang baru
    Route::post('items', [ItemController::class, 'store']);

    // PBI #7 – Edit barang
    Route::post('items/{item}', [ItemController::class, 'update']);
    // ↑ Menggunakan POST bukan PUT karena multipart/form-data (file upload)
    //   Frontend kirim _method=PUT untuk method spoofing Laravel

    // PBI #8 – Hapus barang (soft delete)
    Route::delete('items/{item}', [ItemController::class, 'destroy']);

    // Barang milik donatur yang login
    Route::get('my-items', [ItemController::class, 'myItems']);
});

/*
|--------------------------------------------------------------------------
| Catatan Integrasi
|--------------------------------------------------------------------------
| Base URL  : http://localhost:8000/api/v1
| Auth      : Bearer Token (Laravel Sanctum)
| Headers   : Accept: application/json
|             Authorization: Bearer {token}
|
| Untuk upload foto gunakan multipart/form-data
| Untuk edit item gunakan POST + _method=PUT (method spoofing)
|--------------------------------------------------------------------------
*/
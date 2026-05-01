<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ItemRequestController;

Route::get('/', function () {
    return view('welcome');
});

Route::middleware('auth:sanctum')->group(function () {

    // PBI #14: Menampilkan daftar seluruh permintaan yang pernah diajukan
    Route::get('/requests', [ItemRequestController::class, 'index']);

    // PBI #13: Mekanisme pembuatan permintaan (request) barang
    Route::post('/requests', [ItemRequestController::class, 'store']);

    // PBI #15: Memperbarui preferensi metode pengambilan
    // Menggunakan PATCH karena kita hanya mengubah satu bagian data (parsial), bukan keseluruhan record
    Route::patch('/requests/{id}/pickup-method', [ItemRequestController::class, 'updatePickupMethod']);

    // PBI #16: Mekanisme pembatalan permintaan oleh penerima
    // Menggunakan PATCH juga karena kita hanya mengubah statusnya saja
    Route::patch('/requests/{id}/cancel', [ItemRequestController::class, 'cancel']);

});

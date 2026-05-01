<?php

namespace App\Http\Controllers;

use App\Models\Item;
use App\Models\ItemRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class ItemRequestController extends Controller
{
    /**
     * PBI #14: Menampilkan daftar seluruh permintaan yang pernah diajukan oleh pengguna
     */
    public function index()
    {
        $userId = Auth::id() ?? 1; 

        // Karena relasi sudah diatur rapi, pemanggilan 'with' akan sangat efisien
        $requests = ItemRequest::with('item')
            ->where('requester_id', $userId)
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json([
            'success' => true,
            'message' => 'Daftar permintaan berhasil diambil.',
            'data' => $requests
        ]);
    }

    /**
     * PBI #13: Mekanisme pembuatan permintaan (request) untuk barang yang tersedia
     */
    public function store(Request $request)
    {
        $request->validate([
            'item_id' => 'required|exists:items,id', // Validasi ke kolom 'id' di tabel items
            'pickup_method' => 'nullable|string'
        ]);

        // findOrFail otomatis mencari berdasarkan kolom 'id'
        $item = Item::findOrFail($request->item_id);

        if ($item->status != 1) {
            return response()->json([
                'success' => false,
                'message' => 'Barang sudah tidak tersedia.'
            ], 400);
        }

        $userId = Auth::id() ?? 1;

        $itemRequest = ItemRequest::create([
            'item_id' => $item->id,
            'requester_id' => $userId,
            'status' => 'pending', 
            'request_date' => Carbon::now(),
            'pickup_method' => $request->pickup_method ?? 'cod' 
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Permintaan barang berhasil diajukan.',
            'data' => $itemRequest
        ], 201);
    }

    /**
     * PBI #15: Fitur untuk memperbarui preferensi metode pengambilan
     */
    public function updatePickupMethod(Request $request, $id)
    {
        $request->validate([
            'pickup_method' => 'required|string|in:cod,delivery,pickup'
        ]);

        $itemRequest = ItemRequest::findOrFail($id);

        $userId = Auth::id() ?? 1;
        if ($itemRequest->requester_id !== $userId) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $itemRequest->update([
            'pickup_method' => $request->pickup_method
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Preferensi metode pengambilan berhasil diperbarui.',
            'data' => $itemRequest
        ]);
    }

    /**
     * PBI #16: Mekanisme pembatalan permintaan oleh penerima
     */
    public function cancel($id)
    {
        $itemRequest = ItemRequest::findOrFail($id);

        $userId = Auth::id() ?? 1;
        if ($itemRequest->requester_id !== $userId) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        if (in_array($itemRequest->status, ['completed', 'cancelled'])) {
            return response()->json([
                'success' => false,
                'message' => 'Permintaan ini tidak dapat dibatalkan karena statusnya saat ini: ' . $itemRequest->status
            ], 400);
        }

        $itemRequest->update([
            'status' => 'cancelled'
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Permintaan berhasil dibatalkan.'
        ]);
    }
}

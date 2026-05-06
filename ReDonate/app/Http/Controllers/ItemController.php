<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Item;
use App\Models\Category;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class ItemController extends Controller
{
    public function show($id)
    {
        $item = Item::with(['user', 'category'])->findOrFail($id);
        return view('items.show', compact('item'));
    }

    /**
     * PBI #5: Perbaikan Total - Menyelaraskan nama input dengan Frontend
     */
    public function store(Request $request)
    {
        // 1. Validasi harus menggunakan nama yang dikirim oleh Alpine.js
        $request->validate([
            'item_name'   => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'condition'   => 'required|string',
            'location'    => 'required|string',
            'description' => 'nullable|string',
            'items.*'     => 'image|mimes:jpeg,png,jpg|max:2048' // Nama field file diseragamkan
        ]);

        try {
            $imageUrl = null;
            // 2. Cek file menggunakan nama 'items' (sesuai array di Alpine)
            if ($request->hasFile('items')) {
                $file = $request->file('items')[0]; 
                $imageUrl = $file->store('items_images', 'public');
            }

            // 3. Simpan ke database dengan status 'available'
            $item = Item::create([
                'user_id'     => Auth::id(), 
                'category_id' => $request->category_id,
                'item_name'   => $request->item_name,
                'description' => $request->description,
                'condition'   => $request->condition,
                'location'    => $request->location,
                'image_url'   => $imageUrl,
                'status'      => 'available' 
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Barang berhasil didonasikan',
                'redirect' => route('dashboard')
            ], 200);

        } catch (\Exception $e) {
            Log::error('Donation Error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan sistem: ' . $e->getMessage()
            ], 500);
        }
    }
}
<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Item;

class ItemController extends Controller
{
    public function show($id)
    {
        // Mencari barang berdasarkan ID, sekaligus menarik data relasi (user dan kategori)
        // Jika ID tidak ditemukan, otomatis akan memunculkan halaman 404
        $item = Item::with(['user', 'category'])->findOrFail($id);
        
        // Mengirim data ke file resources/views/items/show.blade.php
        return view('items.show', compact('item'));
    }
}
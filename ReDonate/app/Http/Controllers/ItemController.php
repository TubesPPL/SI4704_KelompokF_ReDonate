<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Item;

class ItemController extends Controller
{
    public function show($id)
    {
        // Ambil data item beserta relasi user dan category
        $item = Item::with(['user', 'category'])->findOrFail($id);
        
        // PENTING: Kembalikan ke view 'items.show', BUKAN response()->json()
        return view('items.show', compact('item'));
    }
}
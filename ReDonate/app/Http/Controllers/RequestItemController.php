<?php

namespace App\Http\Controllers;

use App\Models\RequestItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RequestItemController extends Controller
{
    /**
     * Tampilkan form request barang
     */
    public function create()
    {
        return view('request.create');
    }

    /**
     * Simpan request barang ke database
     */
    public function store(Request $request)
    {
        // ✅ VALIDASI LEBIH JELAS
        $validated = $request->validate([
            'title' => 'required|string|max:100',
            'description' => 'nullable|string|max:500',
            'category' => 'nullable|string|max:50',
            'quantity' => 'required|integer|min:1'
        ], [
            'title.required' => 'Judul wajib diisi',
            'quantity.required' => 'Jumlah wajib diisi',
            'quantity.min' => 'Jumlah minimal 1'
        ]);

        // ✅ SIMPAN DATA
        RequestItem::create([
            'user_id' => Auth::id(),
            'title' => $validated['title'],
            'description' => $validated['description'] ?? null,
            'category' => $validated['category'] ?? null,
            'quantity' => $validated['quantity'],
        ]);

        // ✅ REDIRECT + FLASH MESSAGE
        return redirect()->route('dashboard')
            ->with('success', 'Request barang berhasil dibuat');
    }
}
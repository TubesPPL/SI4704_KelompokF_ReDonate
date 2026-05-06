<?php

namespace App\Http\Controllers;

use App\Models\RequestItem;
use App\Models\Item;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ItemPenerimaController extends Controller
{
    /**
     * PBI #14: Menampilkan daftar permintaan (My Requests)
     */
    public function index()
    {
        // Mengambil semua request milik user yang sedang login
        $requests = RequestItem::with(['item.user'])
            ->where('user_id', Auth::id())
            ->orderBy('created_at', 'desc')
            ->get();

        // KOREKSI: Mengarah ke folder requestPenerima dan file index.blade.php
        return view('requestPenerima.index', compact('requests'));
    }

    /**
     * PBI #15: Form Memperbarui preferensi (Edit Request)
     */
    public function edit($id)
    {
        $requestItem = RequestItem::findOrFail($id);
        
        // Keamanan: Hanya peminta asli yang boleh edit
        if ($requestItem->user_id !== Auth::id()) abort(403);

        // KOREKSI: Mengarah ke folder requestPenerima dan file edit.blade.php
        return view('requestPenerima.edit', compact('requestItem'));
    }

    /**
     * PBI #15: Proses Update preferensi
     */
    public function update(Request $request, $id)
    {
        $requestItem = RequestItem::findOrFail($id);
        if ($requestItem->user_id !== Auth::id()) abort(403);

        $request->validate([
            'description' => 'required|string|max:500'
        ]);

        $requestItem->update([
            'description' => $request->description
        ]);

        return redirect()->route('requests.index')->with('success', 'Catatan permintaan berhasil diperbarui!');
    }

    /**
     * PBI #16: Mekanisme pembatalan permintaan
     */
    public function cancel($id)
    {
        $requestItem = RequestItem::findOrFail($id);
        if ($requestItem->user_id !== Auth::id()) abort(403);

        // Ubah status request menjadi canceled
        $requestItem->update(['status' => 'canceled']);

        return redirect()->route('requests.index')->with('success', 'Permintaan barang berhasil dibatalkan.');
    }

    /**
     * PBI #23 & #24: Konfirmasi Barang Diterima (Selesai)
     */
    public function complete($id)
    {
        $requestItem = RequestItem::findOrFail($id);
        if ($requestItem->user_id !== Auth::id()) abort(403);

        $requestItem->update(['status' => 'completed']);
        $requestItem->item->update(['status' => 'selesai']); // Update status barang

        return redirect()->route('requests.index')->with('success', 'Barang telah Anda terima.');
    }
}
<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Item;
use App\Models\RequestItem;
use Illuminate\Support\Facades\Auth;

class ItemPenerimaController extends Controller
{
    // PBI #14: Menampilkan daftar permintaan
    public function index()
    {
        $requests = RequestItem::with('item')->where('user_id', Auth::id())->latest()->get();
        return view('requestPenerima.index', compact('requests'));
    }

    // PBI #13: Halaman form pembuatan permintaan
    public function create(Item $item)
    {
        return view('requestPenerima.create', compact('item'));
    }

    // PBI #13: Proses simpan pembuatan permintaan
    public function store(Request $request, Item $item)
    {
        $request->validate([
            'message' => 'nullable|string',
            'pickup_method' => 'required|string|in:ambil_sendiri,kurir,cod',
        ]);

        RequestItem::create([
            'user_id' => Auth::id(),
            'item_id' => $item->id,
            'status' => 'pending',
            'message' => $request->message,
            'pickup_method' => $request->pickup_method,
        ]);

        // Opsional: Ubah status barang menjadi requested
        $item->update(['status' => 'requested']);

        return redirect()->route('requests.index')->with('success', 'Permintaan barang berhasil diajukan!');
    }

    // PBI #15: Halaman form update preferensi
    public function edit(RequestItem $requestItem)
    {
        if ($requestItem->user_id !== Auth::id()) abort(403);
        return view('requestPenerima.edit', compact('requestItem'));
    }

    // PBI #15: Proses update preferensi
    public function update(Request $request, RequestItem $requestItem)
    {
        if ($requestItem->user_id !== Auth::id()) abort(403);

        $request->validate([
            'pickup_method' => 'required|string|in:ambil_sendiri,kurir,cod',
        ]);

        $requestItem->update([
            'pickup_method' => $request->pickup_method,
        ]);

        return redirect()->route('requests.index')->with('success', 'Metode pengambilan berhasil diperbarui!');
    }

    // PBI #16: Mekanisme pembatalan permintaan
    public function cancel(RequestItem $requestItem)
    {
        if ($requestItem->user_id !== Auth::id()) abort(403);

        $requestItem->update(['status' => 'cancelled']);
        
        // Kembalikan status barang menjadi available
        $requestItem->item->update(['status' => 'available']);

        return redirect()->route('requests.index')->with('success', 'Permintaan berhasil dibatalkan.');
    }
}
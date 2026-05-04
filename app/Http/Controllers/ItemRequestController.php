<?php

namespace App\Http\Controllers;

use App\Models\Item;
use App\Models\RequestItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ItemRequestController extends Controller
{
    // PBI #14: Menampilkan daftar seluruh permintaan yang pernah diajukan
    public function index()
    {
        $requests = RequestItem::with('item')
            ->where('user_id', Auth::id())
            ->orderBy('created_at', 'desc')
            ->get();

        return view('requestPenerima.index', compact('requests'));
    }

    // PBI #13: Form pembuatan permintaan
    public function create(Item $item)
    {
        if ($item->status !== 'available') {
            return redirect()->back()->with('error', 'Maaf, barang ini sudah tidak tersedia.');
        }

        return view('requestPenerima.create', compact('item'));
    }

    // PBI #13: Proses simpan data permintaan
    public function store(Request $request, Item $item)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string', 
            'quantity' => 'required|integer|min:1',
        ]);

        RequestItem::create([
            'user_id' => Auth::id(),
            'item_id' => $item->item_id,
            'title' => $request->title,
            'description' => $request->description,
            'category' => $item->category->category_name ?? 'Umum',
            'quantity' => $request->quantity,
            'status' => 'pending' 
        ]);

        return redirect()->route('requests.index')->with('success', 'Permintaan barang berhasil diajukan!');
    }

    // PBI #15: Form edit preferensi metode pengambilan
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
            'description' => 'required|string', 
        ]);

        $requestItem->update([
            'description' => $request->description,
        ]);

        return redirect()->route('requests.index')->with('success', 'Preferensi pengambilan berhasil diperbarui.');
    }

    // PBI #16: Mekanisme pembatalan permintaan
    public function cancel(RequestItem $requestItem)
    {
        if ($requestItem->user_id !== Auth::id()) abort(403);

        $requestItem->update(['status' => 'cancelled']);

        return redirect()->back()->with('success', 'Permintaan berhasil dibatalkan.');
    }
}
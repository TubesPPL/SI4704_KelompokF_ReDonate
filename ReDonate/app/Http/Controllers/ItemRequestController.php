<?php

namespace App\Http\Controllers;

use App\Models\RequestItem;
use App\Models\Item;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RequestItemController extends Controller
{
    public function create(Request $request)
    {
        // Pastikan barang ada dan statusnya 'available'
        $item = Item::findOrFail($request->item_id);
        
        if ($item->status !== 'available') {
            return redirect()->route('dashboard')->with('error', 'Barang sudah tidak tersedia.');
        }

        return view('request.create', compact('item'));
    }

    // PBI #13: Menyimpan request ke database
    public function store(Request $request)
    {
        $validated = $request->validate([
            'item_id' => 'required|exists:items,id',
            'title' => 'required|string|max:100',
            'description' => 'nullable|string|max:500',
            'category' => 'nullable|string|max:50',
            'quantity' => 'required|integer|min:1'
        ]);
        
        $item = Item::findOrFail($validated['item_id']);
        
        if ($item->status !== 'available') {
            return redirect()->route('dashboard')->with('error', 'Barang sudah tidak tersedia.');
        }

        RequestItem::create([
            'user_id' => Auth::id(), // ID Penerima
            'item_id' => $validated['item_id'],
            'status' => 'pending', 
            'title' => $validated['title'],
            'description' => $validated['description'] ?? null,
            'category' => $validated['category'] ?? null,
            'quantity' => $validated['quantity'],
        ]);
       
        return redirect()->route('dashboard')->with('success', 'Request barang berhasil dikirim!');
    }

    // PBI #17: Halaman Panel Donatur
    public function donaturRequests()
    {
        $requests = RequestItem::whereHas('item', function ($query) {
            $query->where('user_id', Auth::id());
        })
        ->with(['item', 'user']) 
        ->orderBy('created_at', 'desc')
        ->get();

        return view('request.donatur_index', compact('requests'));
    }

    // PBI #18: Menyetujui (Approve)
    public function approve($id)
    {
        $requestItem = RequestItem::findOrFail($id);
        
        if ($requestItem->item->user_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        // Ubah status request jadi approved
        $requestItem->update(['status' => 'approved']);
        
        // Opsional: Ubah status barang jadi 'diproses' / 'unavailable'
        $requestItem->item->update(['status' => 'diproses']);

        return redirect()->back()->with('success', 'Permintaan disetujui!');
    }

    // PBI #19: Menolak (Reject)
    public function reject($id)
    {
        $requestItem = RequestItem::findOrFail($id);
        
        if ($requestItem->item->user_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        $requestItem->update(['status' => 'rejected']);
        return redirect()->back()->with('success', 'Permintaan ditolak!');
    }

    // PBI #20: Membersihkan riwayat
    public function clearHistory()
    {
        RequestItem::whereHas('item', function ($query) {
            $query->where('user_id', Auth::id());
        })
        ->where('status', 'rejected')
        ->delete();

        return redirect()->back()->with('success', 'Riwayat berhasil dihapus.');
    }
}
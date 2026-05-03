<?php

namespace App\Http\Controllers;

use App\Models\RequestItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RequestItemController extends Controller
{

    public function create()
    {
        return view('request.create');
    }

    // Simpan request barang ke database
    public function store(Request $request)
    {
        
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

        
        RequestItem::create([
            'user_id' => Auth::id(),
            'title' => $validated['title'],
            'description' => $validated['description'] ?? null,
            'category' => $validated['category'] ?? null,
            'quantity' => $validated['quantity'],
        ]);

       
        return redirect()->route('dashboard')
            ->with('success', 'Request barang berhasil dibuat');
    }
    //PBI #17: Halaman Panel Donatur
    
    public function donaturRequests()
    {
        // Ambil request yang item-nya dimiliki oleh donatur yang sedang login
        $requests = RequestItem::whereHas('item', function ($query) {
            $query->where('user_id', Auth::id());
        })
        ->with(['item', 'user']) // Eager load barang dan penerima
        ->orderBy('created_at', 'desc')
        ->get();

        return view('request.donatur_index', compact('requests'));
    }

    // PBI #18: Menyetujui (Approve)
    public function approve($id)
    {
        $requestItem = RequestItem::findOrFail($id);
        
        // Pastikan hanya pemilik barang yang bisa approve
        if ($requestItem->item->user_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        $requestItem->update(['status' => 'approved']);
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

    // PBI #20: Membersihkan riwayat yang ditolak (Clear History)
    public function clearHistory()
    {
        RequestItem::whereHas('item', function ($query) {
            $query->where('user_id', Auth::id());
        })
        ->where('status', 'rejected')
        ->delete();

        return redirect()->back()->with('success', 'Riwayat permintaan yang ditolak berhasil dihapus.');
    }
}
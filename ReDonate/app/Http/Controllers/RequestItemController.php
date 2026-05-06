<?php

namespace App\Http\Controllers;

use App\Models\RequestItem;
use App\Models\Item;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RequestItemController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'item_id' => 'required|exists:items,id',
            'title' => 'required|string|max:100',
        ]);
        
        RequestItem::create([
            'user_id' => Auth::id(),
            'item_id' => $validated['item_id'],
            'status' => 'pending', 
            'title' => $validated['title'],
            'description' => $request->description ?? 'Tertarik dengan barang ini',
            'quantity' => $request->quantity ?? 1,
        ]);
       
        return redirect()->route('dashboard')->with('success', 'Request telah terkirim!');
    }

    public function donaturRequests()
    {
        $requests = RequestItem::whereHas('item', function ($query) {
            $query->where('user_id', Auth::id());
        })->with(['item', 'user'])->latest()->get();

        return view('request.donatur_index', compact('requests'));
    }

    public function approve($id)
    {
        $requestItem = RequestItem::findOrFail($id);
        $requestItem->update(['status' => 'approved']);
        
        // PBI #22: Update status barang menjadi diproses
        $requestItem->item->update(['status' => 'diproses']);

        return redirect()->back()->with('success', 'Permintaan disetujui!');
    }

    public function reject($id)
    {
        $requestItem = RequestItem::findOrFail($id);
        $requestItem->update(['status' => 'rejected']);
        return redirect()->back()->with('success', 'Permintaan ditolak.');
    }

    /**
     * PBI #20: Menghapus riwayat permintaan yang sudah diproses (DITAMBAHKAN KEMBALI)
     */
    public function clearHistory()
    {
        RequestItem::whereHas('item', function ($query) {
            $query->where('user_id', Auth::id());
        })->whereIn('status', ['approved', 'rejected'])->delete();

        return redirect()->back()->with('success', 'Riwayat permintaan berhasil dibersihkan.');
    }
}
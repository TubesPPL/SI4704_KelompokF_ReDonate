<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Item;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ItemController extends Controller
{
    public function index(Request $request)
    {
        $query = Item::with(['user', 'category'])->latest();

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        if ($request->filled('search')) {
            $query->where('title', 'like', '%' . $request->search . '%');
        }

        $items = $query->paginate(20)->withQueryString();

        return view('admin.items.index', compact('items'));
    }

    public function updateStatus(Request $request, Item $item)
    {
        $request->validate(['status' => 'required|in:active,draft,cancelled']);
        $item->update(['status' => $request->status]);

        return back()->with('success', "Status barang \"{$item->title}\" diubah ke {$request->status}.");
    }

    public function destroy(Item $item)
    {
        // Hapus gambar dari storage
        if ($item->images) {
            foreach ($item->images as $img) {
                Storage::disk('public')->delete($img);
            }
        }
        $item->delete();

        return back()->with('success', "Barang \"{$item->title}\" berhasil dihapus permanen.");
    }
}

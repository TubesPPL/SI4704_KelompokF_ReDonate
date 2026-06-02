<?php

namespace App\Http\Controllers;

use App\Models\Item;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class WishlistController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        
        $wishlistedItems = $user->wishlistedItems()->latest('wishlists.created_at')->get();
        
        // Memisahkan berdasarkan status ketersediaan
        $availableItems = $wishlistedItems->filter(function ($item) {
            return in_array($item->status, ['active', 'draft']);
        });
        
        $unavailableItems = $wishlistedItems->filter(function ($item) {
            return !in_array($item->status, ['active', 'draft']);
        });
        
        $myRequests = $user->wishlistRequests()->latest()->get();
        
        return view('wishlist.index', compact('availableItems', 'unavailableItems', 'myRequests'));
    }

    public function toggle(Item $item)
    {
        $user = Auth::user();
        
        if ($item->user_id === $user->id) {
            return response()->json(['error' => 'Anda tidak bisa menambahkan barang milik sendiri ke wishlist.'], 403);
        }
        
        $wishlisted = $user->wishlistedItems()->toggle($item->id);
        
        $isWishlisted = count($wishlisted['attached']) > 0;
        
        return response()->json([
            'success' => true,
            'wishlisted' => $isWishlisted,
            'count' => $item->wishlistedByUsers()->count()
        ]);
    }
    
    public function destroy(Item $item)
    {
        Auth::user()->wishlistedItems()->detach($item->id);
        
        return back()->with('success', 'Barang berhasil dihapus dari wishlist.');
    }
}

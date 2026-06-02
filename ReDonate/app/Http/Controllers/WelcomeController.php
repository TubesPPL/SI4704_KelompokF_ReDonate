<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Event;
use App\Models\Item;
use App\Models\User;
use App\Models\Claim;
use Illuminate\Http\Request;

class WelcomeController extends Controller
{
    public function index()
    {
        // Statistik
        $totalItems = Item::count();
        $totalDonors = Item::select('user_id')->distinct()->count();
        $totalReceivers = Claim::where('status', 'completed')->select('user_id')->distinct()->count();

        // Kategori Populer (Top 8) dengan jumlah item aktif
        $categories = Category::withCount(['items' => function ($query) {
            $query->where('status', 'active');
        }])->orderByDesc('items_count')->take(8)->get();

        // Barang Terbaru (6 item aktif)
        $recentItems = Item::with(['category', 'user'])->active()->latest()->take(6)->get();

        // Event Aktif (1 event)
        $activeEvent = Event::where('status', 'active')->latest()->first();

        // Permintaan Publik Terbaru (3 item aktif)
        $wishlistRequests = \App\Models\WishlistRequest::with(['user', 'category'])->active()->latest()->take(3)->get();

        return view('welcome', compact('totalItems', 'totalDonors', 'totalReceivers', 'categories', 'recentItems', 'activeEvent', 'wishlistRequests'));
    }
}

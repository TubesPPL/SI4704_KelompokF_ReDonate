<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Item;
use App\Models\Claim;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        // Statistik
        $activeItemsCount = $user->items()->active()->count();
        $claimedItemsCount = $user->items()->where('status', 'claimed')->count();
        $completedItemsCount = $user->items()->where('status', 'completed')->count();
        $totalViews = $user->items()->sum('views');

        // Daftar Item Donatur (dengan search/filter opsional)
        $items = $user->items()->latest()->paginate(10);

        // Klaim Masuk (untuk item milik user yang berstatus pending)
        $incomingClaims = Claim::whereHas('item', function ($query) use ($user) {
            $query->where('user_id', $user->id);
        })->where('status', 'pending')->with(['item', 'user'])->latest()->get();

        return view('donor.dashboard', compact(
            'activeItemsCount',
            'claimedItemsCount',
            'completedItemsCount',
            'totalViews',
            'items',
            'incomingClaims'
        ));
    }
}

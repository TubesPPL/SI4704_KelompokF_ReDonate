<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\Item;
use App\Models\Category;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();

        if (!$user || !$user->is_active) {
            Auth::logout();
            return redirect()->route('login');
        }

        $user->load(['logs' => function ($query) {
            $query->latest()->limit(5);
        }]);

        // ambil item available
        $query = Item::with(['user', 'category'])
                    ->available();

        /*
        ==========================
        PBI 9 - SEARCH
        ==========================
        */
        if ($request->filled('search')) {
            $query->where('item_name', 'like', '%' . $request->search . '%');
        }

        /*
        ==========================
        PBI 10 - FILTER CATEGORY
        ==========================
        */
        if ($request->filled('category')) {
            $query->where('category_id', $request->category);
        }

        /*
        ==========================
        PBI 11 - SORTING
        ==========================
        */
        if ($request->sort == 'oldest') {
            $query->orderBy('created_at', 'asc');
        } else {
            $query->latest();
        }

        $items = $query->get();
        $categories = Category::all();

        return view('dashboard.dashboard', compact(
            'user',
            'items',
            'categories'
        ));
    }
}
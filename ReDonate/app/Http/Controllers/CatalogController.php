<?php

namespace App\Http\Controllers;

use App\Models\Item;
use App\Models\Category;
use Illuminate\Http\Request;

class CatalogController extends Controller
{
    public function index(Request $request)
    {
        $query = Item::with(['category', 'user'])->active();

        // Filter Kategori
        if ($request->has('categories') && !empty($request->categories)) {
            $categoriesArray = is_array($request->categories) ? $request->categories : explode(',', $request->categories);
            $query->whereIn('category_id', $categoriesArray);
        }

        // Filter Kondisi
        if ($request->has('conditions') && !empty($request->conditions)) {
            $conditionsArray = is_array($request->conditions) ? $request->conditions : explode(',', $request->conditions);
            $query->whereIn('condition', $conditionsArray);
        }

        // Filter Metode Penyerahan
        if ($request->has('delivery') && !empty($request->delivery) && $request->delivery !== 'all') {
            if ($request->delivery == 'pickup') {
                $query->whereIn('delivery_method', ['pickup', 'both']);
            } elseif ($request->delivery == 'delivery') {
                $query->whereIn('delivery_method', ['delivery', 'both']);
            }
        }

        // Filter Lokasi
        if ($request->has('location') && !empty($request->location)) {
            $query->where('location', 'like', '%' . $request->location . '%');
        }

        // Pencarian Teks
        if ($request->has('q') && !empty($request->q)) {
            $query->where(function($q) use ($request) {
                $q->where('title', 'like', '%' . $request->q . '%')
                  ->orWhere('description', 'like', '%' . $request->q . '%');
            });
        }

        // Pengurutan
        $sort = $request->get('sort', 'newest');
        if ($sort === 'oldest') {
            $query->oldest();
        } elseif ($sort === 'popular') {
            $query->orderByDesc('views');
        } else {
            $query->latest(); // newest
        }

        $items = $query->paginate(12)->withQueryString();
        
        // Return Partial View untuk AJAX Fetch (Alpine.js)
        if ($request->ajax() || $request->wantsJson()) {
            return response()
                ->view('catalog.partials.grid', compact('items'))
                ->header('Vary', 'X-Requested-With');
        }

        $categories = Category::all();
        return view('catalog.index', compact('items', 'categories'));
    }
}

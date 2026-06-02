<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Item;
use App\Models\WishlistRequest;
use App\Services\NotificationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class WishlistRequestController extends Controller
{
    public function index(Request $request)
    {
        $query = WishlistRequest::with(['user', 'category'])->active()->latest();

        if ($request->filled('category')) {
            $query->whereIn('category_id', $request->category);
        }

        $wishlistRequests = $query->paginate(12)->withQueryString();
        $categories = Category::orderBy('name')->get();

        return view('wishlist-request.index', compact('wishlistRequests', 'categories'));
    }

    public function create()
    {
        $categories = Category::orderBy('name')->get();
        return view('wishlist-request.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'category_id' => 'required|exists:categories,id',
            'title' => 'required|string|min:5|max:100',
            'description' => 'required|string|min:20|max:500',
            'condition_needed' => 'nullable|string|max:50',
            'expires_at' => 'nullable|date|after:today|before_or_equal:+3 months',
        ]);

        Auth::user()->wishlistRequests()->create($validated);

        return redirect()->route('wishlist.index')->with('success', 'Permintaan barang berhasil dibuat.');
    }

    public function show(WishlistRequest $wishlistRequest)
    {
        $wishlistRequest->load(['user', 'category', 'fulfilledByItem']);
        
        $myActiveItems = collect();
        if (Auth::check() && Auth::id() !== $wishlistRequest->user_id) {
            $myActiveItems = Auth::user()->items()
                ->active()
                ->where('category_id', $wishlistRequest->category_id)
                ->get();
        }

        return view('wishlist-request.show', compact('wishlistRequest', 'myActiveItems'));
    }

    public function destroy(WishlistRequest $wishlistRequest)
    {
        if ($wishlistRequest->user_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        $wishlistRequest->delete();

        return redirect()->route('wishlist.index')->with('success', 'Permintaan barang berhasil dihapus.');
    }

    public function fulfill(Request $request, WishlistRequest $wishlistRequest)
    {
        if ($wishlistRequest->user_id === Auth::id()) {
            abort(403, 'Anda tidak bisa merespons permintaan sendiri.');
        }

        if ($wishlistRequest->is_fulfilled) {
            return back()->with('error', 'Permintaan ini sudah dipenuhi oleh orang lain.');
        }

        $validated = $request->validate([
            'item_id' => 'required|exists:items,id'
        ]);

        $item = Item::where('id', $validated['item_id'])
            ->where('user_id', Auth::id())
            ->where('status', 'active')
            ->firstOrFail();

        $wishlistRequest->is_fulfilled = true;
        $wishlistRequest->fulfilled_by_item_id = $item->id;
        $wishlistRequest->save();

        NotificationService::send(
            $wishlistRequest->user_id,
            NotificationService::WISHLIST_REQUEST_RESPONSE,
            'Permintaan Anda Direspons!',
            Auth::user()->name . ' merespons permintaan "' . $wishlistRequest->title . '" dengan barang "' . $item->title . '".',
            ['action_url' => route('items.show', $item->slug)]
        );

        return back()->with('success', 'Berhasil merespons permintaan. Barang Anda telah ditautkan.');
    }
}

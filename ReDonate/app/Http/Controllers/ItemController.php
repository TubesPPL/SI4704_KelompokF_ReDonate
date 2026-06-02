<?php

namespace App\Http\Controllers;

use App\Models\Item;
use App\Models\Category;
use App\Models\Event;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ItemController extends Controller
{
    public function index()
    {
        // Redirect to dashboard as it handles the item listing
        return redirect()->route('dashboard');
    }

    public function create()
    {
        $categories = Category::all();
        $events = Event::where('status', 'active')->get();
        $selectedEventId = request('event_id');
        return view('items.create', compact('categories', 'events', 'selectedEventId'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'event_id' => 'nullable|exists:events,id',
            'description' => 'required|string',
            'condition' => 'required|in:new,like_new,good,fair',
            'quantity' => 'required|integer|min:1',
            'location' => 'required|string|max:255',
            'delivery_method' => 'required|in:pickup,delivery,both',
            'images' => 'required|array|min:1|max:5',
            'images.*' => 'image|mimes:jpeg,png,jpg,webp|max:2048',
            'action' => 'required|in:draft,publish'
        ]);

        $imagePaths = [];
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                $path = $image->store('items/' . Auth::id(), 'public');
                $imagePaths[] = $path;
            }
        }

        // Generate unique slug
        $slug = Str::slug($validated['title']);
        $originalSlug = $slug;
        $counter = 1;
        while (Item::where('slug', $slug)->exists()) {
            $slug = $originalSlug . '-' . $counter;
            $counter++;
        }

        $item = new Item();
        $item->user_id = Auth::id();
        $item->category_id = $validated['category_id'];
        $item->event_id = $validated['event_id'] ?? null;
        $item->title = $validated['title'];
        $item->slug = $slug;
        $item->description = $validated['description'];
        $item->condition = $validated['condition'];
        $item->quantity = $validated['quantity'];
        $item->location = $validated['location'];
        $item->delivery_method = $validated['delivery_method'];
        $item->status = $validated['action'] === 'publish' ? 'active' : 'draft';
        $item->images = $imagePaths;
        $item->save();

        $message = $item->status === 'active' ? 'Barang berhasil dipublikasikan.' : 'Barang disimpan sebagai draft.';
        return redirect()->route('dashboard')->with('success', $message);
    }

    public function show($slug)
    {
        $item = Item::with(['user', 'category', 'event', 'claims' => function($query) {
            if(Auth::check()) {
                $query->where('user_id', Auth::id());
            }
        }])->where('slug', $slug)->firstOrFail();
        
        // Increment views only once per session
        $sessionKey = 'viewed_item_' . $item->id;
        if (!session()->has($sessionKey)) {
            $item->increment('views');
            session()->put($sessionKey, true);
        }
        
        $userClaim = Auth::check() ? $item->claims->first() : null;
        
        return view('items.show', compact('item', 'userClaim'));
    }

    public function edit($slug)
    {
        $item = Item::where('slug', $slug)->where('user_id', Auth::id())->firstOrFail();
        $categories = Category::all();
        $events = Event::where('status', 'active')->get();
        return view('items.edit', compact('item', 'categories', 'events'));
    }

    public function update(Request $request, $slug)
    {
        $item = Item::where('slug', $slug)->where('user_id', Auth::id())->firstOrFail();

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'event_id' => 'nullable|exists:events,id',
            'description' => 'required|string',
            'condition' => 'required|in:new,like_new,good,fair',
            'quantity' => 'required|integer|min:1',
            'location' => 'required|string|max:255',
            'delivery_method' => 'required|in:pickup,delivery,both',
            'images' => 'nullable|array|max:5',
            'images.*' => 'image|mimes:jpeg,png,jpg,webp|max:2048',
            'existing_images' => 'nullable|array',
            'action' => 'required|in:draft,publish'
        ]);

        $currentImages = $item->images ?? [];
        $keptImages = $request->input('existing_images', []);

        // Delete removed images from storage
        foreach ($currentImages as $image) {
            if (!in_array($image, $keptImages)) {
                Storage::disk('public')->delete($image);
            }
        }

        // Add new images
        $imagePaths = $keptImages;
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                // Ensure max 5 images
                if (count($imagePaths) < 5) {
                    $path = $image->store('items/' . Auth::id(), 'public');
                    $imagePaths[] = $path;
                }
            }
        }

        if ($item->title !== $validated['title']) {
            $slug = Str::slug($validated['title']);
            $originalSlug = $slug;
            $counter = 1;
            while (Item::where('slug', $slug)->where('id', '!=', $item->id)->exists()) {
                $slug = $originalSlug . '-' . $counter;
                $counter++;
            }
            $item->slug = $slug;
        }

        $item->title = $validated['title'];
        $item->category_id = $validated['category_id'];
        $item->event_id = $validated['event_id'] ?? null;
        $item->description = $validated['description'];
        $item->condition = $validated['condition'];
        $item->quantity = $validated['quantity'];
        $item->location = $validated['location'];
        $item->delivery_method = $validated['delivery_method'];
        $item->status = $validated['action'] === 'publish' ? 'active' : 'draft';
        $item->images = $imagePaths;
        $item->save();

        return redirect()->route('dashboard')->with('success', 'Barang berhasil diperbarui.');
    }

    public function destroy($slug)
    {
        $item = Item::where('slug', $slug)->where('user_id', Auth::id())->firstOrFail();
        $item->status = 'cancelled';
        $item->save();

        return redirect()->route('dashboard')->with('success', 'Barang telah dibatalkan.');
    }

    public function updateStatus(Request $request, $slug)
    {
        $item = Item::where('slug', $slug)->where('user_id', Auth::id())->firstOrFail();
        
        $validated = $request->validate([
            'status' => 'required|in:active,draft,cancelled'
        ]);

        $item->status = $validated['status'];
        $item->save();

        return back()->with('success', 'Status barang berhasil diubah.');
    }
}

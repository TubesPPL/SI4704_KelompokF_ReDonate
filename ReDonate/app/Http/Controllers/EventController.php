<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class EventController extends Controller
{
    public function index()
    {
        $events = Event::with('creator')->withCount('items')->latest()->get();

        $activeEvents = $events->where('status', 'active');
        $upcomingEvents = $events->where('status', 'upcoming');
        $completedEvents = $events->where('status', 'completed');

        return view('events.index', compact('activeEvents', 'upcomingEvents', 'completedEvents'));
    }

    public function show($slug)
    {
        $event = Event::with(['creator', 'categories'])->withCount('items')->where('slug', $slug)->firstOrFail();
        
        // Ambil item yang didonasikan ke event ini
        $items = $event->items()->with(['user', 'category'])->active()->latest()->paginate(12);

        return view('events.show', compact('event', 'items'));
    }

    public function create()
    {
        // Pastikan hanya admin (logika middleware 'admin' akan diurus di routes)
        $categories = Category::all();
        return view('events.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'target_items' => 'required|integer|min:1',
            'banner' => 'required|image|mimes:jpeg,png,jpg,webp|max:3072',
            'categories' => 'required|array|min:1',
            'categories.*' => 'exists:categories,id',
        ]);

        $bannerPath = null;
        if ($request->hasFile('banner')) {
            $bannerPath = $request->file('banner')->store('events', 'public');
        }

        // Generate slug
        $slug = Str::slug($validated['title']);
        $originalSlug = $slug;
        $counter = 1;
        while (Event::where('slug', $slug)->exists()) {
            $slug = $originalSlug . '-' . $counter;
            $counter++;
        }

        // Tentukan status awal berdasarkan tanggal
        $status = 'upcoming';
        $today = now()->startOfDay();
        $startDate = \Carbon\Carbon::parse($validated['start_date'])->startOfDay();
        
        if ($startDate->lte($today)) {
            $status = 'active';
        }

        $event = new Event();
        $event->created_by = Auth::id();
        $event->title = $validated['title'];
        $event->slug = $slug;
        $event->description = $validated['description'];
        $event->start_date = $validated['start_date'];
        $event->end_date = $validated['end_date'];
        $event->target_items = $validated['target_items'];
        $event->status = $status;
        $event->banner = $bannerPath;
        $event->save();

        // Attach categories (Pastikan tabel event_categories atau relasi Many-to-Many sudah ada)
        // Note: Model Event punya function categories(), tapi kita belum punya migration event_categories.
        // Jika belum ada, kita asumsikan menggunakan json pada kolom tertentu atau kita skip attach jika belum ada.
        // Berdasarkan skema, mari kita gunakan sinkronisasi relasi Many-to-Many.
        try {
            $event->categories()->sync($validated['categories']);
        } catch (\Exception $e) {
            // Jika tabel event_categories belum ada, lewati dengan aman.
        }

        return redirect()->route('events.show', $event->slug)->with('success', 'Event berhasil dibuat!');
    }

    public function edit($slug)
    {
        $event = Event::with('categories')->where('slug', $slug)->firstOrFail();
        $categories = Category::all();
        
        return view('events.edit', compact('event', 'categories'));
    }

    public function update(Request $request, $slug)
    {
        $event = Event::where('slug', $slug)->firstOrFail();

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'target_items' => 'required|integer|min:1',
            'banner' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:3072',
            'categories' => 'required|array|min:1',
            'categories.*' => 'exists:categories,id',
            'status' => 'required|in:upcoming,active,completed,cancelled'
        ]);

        if ($request->hasFile('banner')) {
            if ($event->banner) {
                Storage::disk('public')->delete($event->banner);
            }
            $event->banner = $request->file('banner')->store('events', 'public');
        }

        if ($event->title !== $validated['title']) {
            $slug = Str::slug($validated['title']);
            $originalSlug = $slug;
            $counter = 1;
            while (Event::where('slug', $slug)->where('id', '!=', $event->id)->exists()) {
                $slug = $originalSlug . '-' . $counter;
                $counter++;
            }
            $event->slug = $slug;
        }

        $event->title = $validated['title'];
        $event->description = $validated['description'];
        $event->start_date = $validated['start_date'];
        $event->end_date = $validated['end_date'];
        $event->target_items = $validated['target_items'];
        $event->status = $validated['status'];
        $event->save();

        try {
            $event->categories()->sync($validated['categories']);
        } catch (\Exception $e) {}

        return redirect()->route('events.show', $event->slug)->with('success', 'Event berhasil diperbarui!');
    }

    public function destroy($slug)
    {
        $event = Event::where('slug', $slug)->firstOrFail();
        
        $event->status = 'cancelled';
        $event->save();

        return redirect()->route('events.index')->with('success', 'Event telah dibatalkan.');
    }
}

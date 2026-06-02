<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Event;
use Illuminate\Http\Request;

class EventController extends Controller
{
    public function index(Request $request)
    {
        $query = Event::with('creator')->withCount('items')->latest();

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        if ($request->filled('search')) {
            $query->where('title', 'like', '%' . $request->search . '%');
        }

        $events = $query->paginate(20)->withQueryString();

        return view('admin.events.index', compact('events'));
    }

    public function cancel(Event $event)
    {
        $event->update(['status' => 'cancelled']);
        return back()->with('success', "Event \"{$event->title}\" telah dibatalkan.");
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    public function index(Request $request)
    {
        $query = Auth::user()->notifications()->latest();

        if ($request->has('filter') && $request->filter === 'unread') {
            $query->whereNull('read_at');
        }

        $notifications = $query->paginate(15);

        return view('notifications.index', compact('notifications'));
    }

    public function markAsRead($id)
    {
        $notification = Auth::user()->notifications()->findOrFail($id);
        
        if (is_null($notification->read_at)) {
            $notification->update(['read_at' => now()]);
        }

        // Redirect to action URL if present, otherwise back
        $actionUrl = $notification->data['action_url'] ?? route('notifications.index');
        
        return redirect($actionUrl);
    }

    public function markAllRead()
    {
        Auth::user()->notifications()->whereNull('read_at')->update(['read_at' => now()]);

        return back()->with('success', 'Semua notifikasi telah ditandai sebagai dibaca.');
    }

    public function destroy($id)
    {
        $notification = Auth::user()->notifications()->findOrFail($id);
        $notification->delete();

        return back()->with('success', 'Notifikasi berhasil dihapus.');
    }
}

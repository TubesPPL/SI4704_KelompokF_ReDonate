<?php

namespace App\Http\Controllers;

use App\Models\Item;
use App\Models\Claim;
use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RecipientClaimController extends Controller
{
    public function store(Request $request, $itemId)
    {
        $item = Item::findOrFail($itemId);

        // Validasi state
        if ($item->status !== 'active') {
            return back()->with('error', 'Barang ini sudah tidak tersedia untuk diklaim.');
        }

        if ($item->user_id === Auth::id()) {
            return back()->with('error', 'Anda tidak dapat mengklaim barang milik Anda sendiri.');
        }

        $existingClaim = Claim::where('item_id', $item->id)
                              ->where('user_id', Auth::id())
                              ->first();

        if ($existingClaim) {
            return back()->with('error', 'Anda sudah pernah mengajukan klaim untuk barang ini.');
        }

        $validated = $request->validate([
            'message' => 'required|string|min:20|max:500',
            'pickup_date' => 'nullable|date|after_or_equal:today',
        ]);

        $claim = new Claim();
        $claim->item_id = $item->id;
        $claim->user_id = Auth::id();
        $claim->message = $validated['message'];
        $claim->pickup_date = $validated['pickup_date'] ?? null;
        $claim->status = 'pending';
        $claim->save();

        // Notifikasi ke donatur
        \App\Services\NotificationService::send(
            $item->user_id,
            \App\Services\NotificationService::CLAIM_RECEIVED,
            'Pengajuan Klaim Baru!',
            Auth::user()->name . ' baru saja mengajukan klaim untuk barang "' . $item->title . '".',
            ['action_url' => route('dashboard')]
        );

        return back()->with('success', 'Pengajuan klaim berhasil dikirim! Menunggu persetujuan donatur.');
    }
}

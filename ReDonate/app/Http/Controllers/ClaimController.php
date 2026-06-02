<?php

namespace App\Http\Controllers;

use App\Models\Claim;
use App\Models\Item;
use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ClaimController extends Controller
{
    public function approve($id)
    {
        $claim = Claim::with('item')->findOrFail($id);

        // Pastikan user yang login adalah pemilik barang
        if ($claim->item->user_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        // Setujui klaim ini
        $claim->status = 'approved';
        $claim->save();

        // Tolak klaim lain untuk item ini
        Claim::where('item_id', $claim->item_id)
            ->where('id', '!=', $claim->id)
            ->where('status', 'pending')
            ->update(['status' => 'rejected', 'notes' => 'Barang telah diberikan kepada pemohon lain.']);

        // Ubah status barang menjadi claimed
        $claim->item->status = 'claimed';
        $claim->item->save();

        // Kirim notifikasi ke pemohon
        \App\Services\NotificationService::send(
            $claim->user_id,
            \App\Services\NotificationService::CLAIM_APPROVED,
            'Klaim Disetujui!',
            'Klaim Anda untuk barang "' . $claim->item->title . '" telah disetujui. Silakan cek detail untuk proses serah terima.',
            ['action_url' => route('recipient.dashboard')]
        );

        // Beritahu user lain yang mewishlist barang ini bahwa barang sudah diklaim
        $wishlistedUsers = $claim->item->wishlistedByUsers()->where('user_id', '!=', $claim->user_id)->get();
        foreach ($wishlistedUsers as $wUser) {
            \App\Services\NotificationService::send(
                $wUser->id,
                \App\Services\NotificationService::WISHLIST_ITEM_CLAIMED,
                'Barang di Wishlist Anda Telah Diklaim',
                'Barang "' . $claim->item->title . '" yang ada di wishlist Anda telah diklaim oleh orang lain.',
                ['action_url' => route('items.show', $claim->item->slug)]
            );
        }

        return back()->with('success', 'Klaim berhasil disetujui.');
    }

    public function reject(Request $request, $id)
    {
        $claim = Claim::with('item')->findOrFail($id);

        if ($claim->item->user_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        $validated = $request->validate([
            'notes' => 'nullable|string|max:255'
        ]);

        $claim->status = 'rejected';
        $claim->notes = $validated['notes'] ?? 'Klaim ditolak oleh donatur.';
        $claim->save();

        \App\Services\NotificationService::send(
            $claim->user_id,
            \App\Services\NotificationService::CLAIM_REJECTED,
            'Klaim Ditolak',
            'Mohon maaf, klaim Anda untuk barang "' . $claim->item->title . '" ditolak. Alasan: ' . $claim->notes,
            ['action_url' => route('recipient.dashboard')]
        );

        return back()->with('success', 'Klaim berhasil ditolak.');
    }

    public function complete($id)
    {
        $claim = Claim::with('item')->findOrFail($id);

        if ($claim->item->user_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        if ($claim->status !== 'approved') {
            return back()->with('error', 'Klaim harus disetujui terlebih dahulu sebelum dapat diselesaikan.');
        }

        $claim->status = 'completed';
        $claim->save();

        $claim->item->status = 'completed';
        $claim->item->save();

        \App\Services\NotificationService::send(
            $claim->user_id,
            \App\Services\NotificationService::CLAIM_COMPLETED,
            'Donasi Selesai',
            'Proses serah terima untuk barang "' . $claim->item->title . '" telah selesai. Jangan lupa tinggalkan ulasan untuk donatur!',
            ['action_url' => route('recipient.dashboard')]
        );

        return back()->with('success', 'Donasi berhasil diselesaikan!');
    }
}

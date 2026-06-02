<?php

namespace App\Http\Controllers;

use App\Models\Claim;
use App\Models\Review;
use App\Models\User;
use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReviewController extends Controller
{
    public function create($claimId)
    {
        $claim = Claim::with(['item', 'item.user'])->findOrFail($claimId);

        // Validasi state
        if ($claim->user_id !== Auth::id()) {
            abort(403, 'Unauthorized');
        }

        if ($claim->status !== 'completed') {
            return redirect()->route('recipient.dashboard')->with('error', 'Anda hanya bisa memberikan ulasan untuk donasi yang sudah selesai.');
        }

        if ($claim->review()->exists()) {
            return redirect()->route('recipient.dashboard')->with('error', 'Anda sudah memberikan ulasan untuk donasi ini.');
        }

        return view('reviews.create', compact('claim'));
    }

    public function store(Request $request, $claimId)
    {
        $claim = Claim::with(['item', 'item.user'])->findOrFail($claimId);

        if ($claim->user_id !== Auth::id() || $claim->status !== 'completed' || $claim->review()->exists()) {
            abort(403, 'Unauthorized or invalid state');
        }

        $validated = $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'nullable|string|max:1000',
        ]);

        $review = new Review();
        $review->claim_id = $claim->id;
        $review->donor_id = $claim->item->user_id;
        $review->reviewer_id = Auth::id();
        $review->rating = $validated['rating'];
        $review->comment = $validated['comment'] ?? null;
        $review->save();

        // Update rating donatur (opsional: bisa dihitung dinamis atau disimpan di tabel users)
        // Kita simpan rata-rata ke model User jika ada field rating (atau cukup query dinamis di profil).

        \App\Services\NotificationService::send(
            $claim->item->user_id,
            \App\Services\NotificationService::REVIEW_RECEIVED,
            'Ulasan Baru Diterima!',
            Auth::user()->name . ' memberikan Anda ulasan bintang ' . $review->rating . ' atas barang "' . $claim->item->title . '".',
            ['action_url' => route('dashboard')]
        );

        return redirect()->route('recipient.dashboard')->with('success', 'Terima kasih! Ulasan Anda berhasil dikirim.');
    }
}

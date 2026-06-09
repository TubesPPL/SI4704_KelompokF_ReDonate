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
        $review->reviewer_id = Auth::id();
        $review->reviewee_id = $claim->item->user_id;
        $review->rating = $validated['rating'];
        $review->comment = $validated['comment'] ?? null;
        $review->save();

        \App\Services\NotificationService::send(
            $claim->item->user_id,
            \App\Services\NotificationService::REVIEW_RECEIVED,
            'Ulasan Baru Diterima!',
            Auth::user()->name . ' memberikan Anda ulasan bintang ' . $review->rating . ' atas barang "' . $claim->item->title . '".',
            ['action_url' => route('profile.show', $claim->item->user_id)]
        );

        return redirect()->route('recipient.dashboard')->with('success', 'Terima kasih! Ulasan Anda berhasil dikirim.');
    }

    public function edit($id)
    {
        $review = Review::with(['claim', 'claim.item', 'claim.item.user'])->findOrFail($id);

        if ($review->reviewer_id !== Auth::id()) {
            abort(403, 'Unauthorized');
        }

        return view('reviews.edit', compact('review'));
    }

    public function update(Request $request, $id)
    {
        $review = Review::findOrFail($id);

        if ($review->reviewer_id !== Auth::id()) {
            abort(403, 'Unauthorized');
        }

        $validated = $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'nullable|string|max:1000',
        ]);

        $review->update([
            'rating' => $validated['rating'],
            'comment' => $validated['comment'] ?? null,
        ]);

        return redirect()->route('profile.show', $review->reviewee_id)->with('success', 'Ulasan Anda berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $review = Review::findOrFail($id);

        if ($review->reviewer_id !== Auth::id()) {
            abort(403, 'Unauthorized');
        }

        $revieweeId = $review->reviewee_id;
        $review->delete();

        return redirect()->route('profile.show', $revieweeId)->with('success', 'Ulasan Anda berhasil dihapus.');
    }

    public function reply(Request $request, $id)
    {
        $review = Review::findOrFail($id);

        if ($review->reviewee_id !== Auth::id()) {
            abort(403, 'Unauthorized');
        }

        $validated = $request->validate([
            'reply' => 'required|string|max:1000',
        ]);

        $review->update([
            'reply' => $validated['reply'],
            'reply_at' => now(),
        ]);

        \App\Services\NotificationService::send(
            $review->reviewer_id,
            \App\Services\NotificationService::REVIEW_RECEIVED,
            'Ulasan Anda Dibalas!',
            Auth::user()->name . ' membalas ulasan yang Anda berikan.',
            ['action_url' => route('profile.show', $review->reviewee_id)]
        );

        return redirect()->route('profile.show', $review->reviewee_id)->with('success', 'Tanggapan Anda berhasil dikirim.');
    }

    public function destroyReply($id)
    {
        $review = Review::findOrFail($id);

        if ($review->reviewee_id !== Auth::id()) {
            abort(403, 'Unauthorized');
        }

        $review->update([
            'reply' => null,
            'reply_at' => null,
        ]);

        return redirect()->route('profile.show', $review->reviewee_id)->with('success', 'Tanggapan Anda berhasil dihapus.');
    }
}

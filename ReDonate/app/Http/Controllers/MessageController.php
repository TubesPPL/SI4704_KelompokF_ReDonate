<?php

namespace App\Http\Controllers;

use App\Models\Claim;
use App\Models\Message;
use App\Services\NotificationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MessageController extends Controller
{
    /**
     * Pastikan hanya donatur (pemilik barang) atau penerima (pembuat klaim) yang bisa mengakses
     */
    private function authorizeChat(Claim $claim)
    {
        $userId = Auth::id();
        if ($claim->user_id !== $userId && $claim->item->user_id !== $userId) {
            abort(403, 'Anda tidak memiliki akses ke obrolan ini.');
        }
    }

    public function show(Claim $claim)
    {
        $claim->load(['item.user', 'user']);
        $this->authorizeChat($claim);

        // Tandai pesan dari lawan bicara sebagai sudah dibaca
        $claim->messages()
            ->where('sender_id', '!=', Auth::id())
            ->whereNull('read_at')
            ->update(['read_at' => now()]);

        $messages = $claim->messages()->with('sender')->oldest()->get();

        return view('chat.show', compact('claim', 'messages'));
    }

    public function store(Request $request, Claim $claim)
    {
        $this->authorizeChat($claim);

        if ($claim->status !== 'approved') {
            return response()->json(['error' => 'Klaim belum disetujui atau sudah selesai.'], 403);
        }

        $validated = $request->validate([
            'body' => 'required|string|max:1000',
        ]);

        $message = $claim->messages()->create([
            'sender_id' => Auth::id(),
            'body' => $validated['body'],
        ]);

        // Tentukan siapa lawan bicara
        $receiverId = $claim->user_id === Auth::id() ? $claim->item->user_id : $claim->user_id;

        // Kirim notifikasi menggunakan NotificationService
        NotificationService::send(
            $receiverId,
            NotificationService::NEW_MESSAGE,
            'Pesan Baru: ' . $claim->item->title,
            Auth::user()->name . ': ' . mb_strimwidth($message->body, 0, 50, '...'),
            ['action_url' => route('chat.show', $claim->id)]
        );

        return response()->json([
            'success' => true,
            'message' => $message->load('sender')
        ]);
    }

    public function poll(Request $request, Claim $claim)
    {
        $this->authorizeChat($claim);

        $lastId = $request->query('last_id', 0);

        $newMessages = $claim->messages()
            ->with('sender')
            ->where('id', '>', $lastId)
            ->oldest()
            ->get();

        // Tandai pesan dari lawan bicara sebagai sudah dibaca
        if ($newMessages->isNotEmpty()) {
            $claim->messages()
                ->where('id', '>', $lastId)
                ->where('sender_id', '!=', Auth::id())
                ->whereNull('read_at')
                ->update(['read_at' => now()]);
        }

        return response()->json($newMessages);
    }

    public function markRead(Claim $claim)
    {
        $this->authorizeChat($claim);

        $updated = $claim->messages()
            ->where('sender_id', '!=', Auth::id())
            ->whereNull('read_at')
            ->update(['read_at' => now()]);

        return response()->json(['success' => true, 'updated' => $updated]);
    }
}

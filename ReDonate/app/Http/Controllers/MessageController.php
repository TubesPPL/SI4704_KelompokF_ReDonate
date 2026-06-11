<?php

namespace App\Http\Controllers;

use App\Models\Claim;
use App\Models\Message;
use App\Services\NotificationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\JsonResponse; // menambahkan impor
use Illuminate\View\View; // menambahan impor

class MessageController extends Controller
{
    /**
     * hanya donatur atau penerima yang bisa mengakses.
     *
     * @param  \App\Models\Claim  $claim
     * @return void
     */
    private function authorizeChat(Claim $claim): void
    {
        $userId = Auth::id();
        if ($claim->user_id !== $userId && $claim->item->user_id !== $userId) {
            abort(403, 'Anda tidak memiliki akses ke obrolan ini.');
        }
    }

    /**
     * menampilkan halaman obrolan (chat) berdasarkan klaim.
     *
     * @param  \App\Models\Claim  $claim
     * @return \Illuminate\View\View
     */
    public function show(Claim $claim)
    {
        $claim->load(['item.user', 'user']);
        $this->authorizeChat($claim);

        // menandai semua pesan masuk dari lawan bicara sebagai sudah dibaca
        $claim->messages()
            ->where('sender_id', '!=', Auth::id())
            ->whereNull('read_at')
            ->update(['read_at' => now()]);

        $messages = $claim->messages()->with('sender')->oldest()->get();

        return view('chat.show', compact('claim', 'messages'));
    }

    /**
     * menyimpan pesan baru ke dalam database.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Claim  $claim
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request, Claim $claim): JsonResponse
    {
        $this->authorizeChat($claim);

        // memastikan klaim sudah disetujui
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

        // menentukan ID penerima pesan (lawan bicara)
        $receiverId = $claim->user_id === Auth::id() ? $claim->item->user_id : $claim->user_id;

        // mengirim notifikasi ke lawan bicara menggunakan NotificationService
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

    /**
     * Mengambil pesan-pesan terbaru secara asynchronous.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Claim  $claim
     * @return \Illuminate\Http\JsonResponse
     */
    public function poll(Request $request, Claim $claim): JsonResponse
    {
        $this->authorizeChat($claim);

        $lastId = $request->query('last_id', 0);

        $newMessages = $claim->messages()
            ->with('sender')
            ->where('id', '>', $lastId)
            ->oldest()
            ->get();

        // jika ada pesan baru, langsung tandai sebagai sudah dibaca
        if ($newMessages->isNotEmpty()) {
            $claim->messages()
                ->where('id', '>', $lastId)
                ->where('sender_id', '!=', Auth::id())
                ->whereNull('read_at')
                ->update(['read_at' => now()]);
        }

        return response()->json($newMessages);
    }

    /**
     * memperbarui status pesan menjadi telah dibaca
     *
     * @param  \App\Models\Claim  $claim
     * @return \Illuminate\Http\JsonResponse
     */
    public function markRead(Claim $claim): JsonResponse
    {
        $this->authorizeChat($claim);

        $updated = $claim->messages()
            ->where('sender_id', '!=', Auth::id())
            ->whereNull('read_at')
            ->update(['read_at' => now()]);

        return response()->json(['success' => true, 'updated' => $updated]);
    }
}
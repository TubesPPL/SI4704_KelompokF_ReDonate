<?php

namespace App\Console\Commands;

use App\Models\WishlistRequest;
use App\Services\NotificationService;
use Illuminate\Console\Command;

class ExpireWishlistRequests extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'wishlist:expire';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Cari dan tandai wishlist requests yang sudah kedaluwarsa, lalu kirim notifikasi.';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $expiredRequests = WishlistRequest::where('is_fulfilled', false)
            ->whereNotNull('expires_at')
            ->where('expires_at', '<', now()->startOfDay())
            ->get();

        $count = 0;
        foreach ($expiredRequests as $request) {
            NotificationService::send(
                $request->user_id,
                NotificationService::WISHLIST_REQUEST_EXPIRED,
                'Permintaan Kedaluwarsa',
                'Permintaan barang Anda "' . $request->title . '" telah kedaluwarsa karena melebihi batas waktu.',
                ['action_url' => route('wishlist.index')]
            );
            $count++;
        }

        $this->info("Berhasil memproses {$count} permintaan yang kedaluwarsa.");
    }
}

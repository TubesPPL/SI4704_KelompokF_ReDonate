<?php

namespace App\Console\Commands;

use App\Models\Event;
use Carbon\Carbon;
use Illuminate\Console\Command;

class UpdateEventStatus extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'events:update-status';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Perbarui status event berdasarkan tanggal mulai dan tanggal selesai';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $today = Carbon::now()->startOfDay();

        // 1. Ubah upcoming -> active jika start_date <= hari ini
        $activated = Event::where('status', 'upcoming')
            ->where('start_date', '<=', $today)
            ->update(['status' => 'active']);

        // 2. Ubah active -> completed jika end_date < hari ini
        $completed = Event::where('status', 'active')
            ->where('end_date', '<', $today)
            ->update(['status' => 'completed']);

        $this->info("Berhasil memperbarui status event: $activated diaktifkan, $completed diselesaikan.");
    }
}

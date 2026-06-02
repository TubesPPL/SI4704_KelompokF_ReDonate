<?php

namespace Database\Seeders;

use App\Models\Event;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class EventSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $admin = User::where('role', 'admin')->first() ?? User::factory()->create(['role' => 'admin']);

        // 1 Active Event
        Event::create([
            'created_by' => $admin->id,
            'title' => 'Donasi Pakaian Layak Pakai Ramadhan',
            'slug' => Str::slug('Donasi Pakaian Layak Pakai Ramadhan'),
            'description' => 'Mari berbagi pakaian layak pakai untuk saudara kita yang membutuhkan di bulan suci.',
            'start_date' => now()->subDays(5)->format('Y-m-d'),
            'end_date' => now()->addDays(20)->format('Y-m-d'),
            'target_items' => 500,
            'status' => 'active',
        ]);

        // 1 Upcoming Event
        Event::create([
            'created_by' => $admin->id,
            'title' => 'Buku untuk Pelosok Negeri',
            'slug' => Str::slug('Buku untuk Pelosok Negeri'),
            'description' => 'Kumpulkan buku bacaan anak dan pendidikan untuk disalurkan ke sekolah di pelosok.',
            'start_date' => now()->addDays(10)->format('Y-m-d'),
            'end_date' => now()->addDays(40)->format('Y-m-d'),
            'target_items' => 1000,
            'status' => 'upcoming',
        ]);
    }
}

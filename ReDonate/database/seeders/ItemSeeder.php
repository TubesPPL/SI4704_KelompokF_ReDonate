<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ItemSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('items')->insert([
            [
                'user_id' => 1, 
                'category_id' => 1, 
                'event_id' => null, 
                'item_name' => 'Kemeja Flannel Pria (Ukuran L)', 
                'description' => 'Kemeja lengan panjang masih sangat bagus, jarang dipakai karena kebesaran. Cocok untuk cuaca dingin.', 
                'condition' => 'sangat baik', 
                // Menggunakan link gambar baju
                'image_url' => 'https://images.unsplash.com/photo-1523381210434-271e8be1f52b?auto=format&fit=crop&q=80&w=400', 
                'status' => 'available', 
                'location' => 'Asrama Putra Telkom University',
                'created_at' => now(), 
                'updated_at' => now(), 
                'deleted_at' => null
            ],
            [
                'user_id' => 1, 
                'category_id' => 2, 
                'event_id' => null, 
                'item_name' => 'Buku Pemrograman Web dengan Laravel', 
                'description' => 'Buku panduan belajar Laravel. Halaman masih lengkap dan tidak ada coretan.', 
                'condition' => 'baik', 
                // Menggunakan link gambar buku
                'image_url' => 'https://images.unsplash.com/photo-1524995997946-a1c2e315a42f?auto=format&fit=crop&q=80&w=400', 
                'status' => 'available', 
                'location' => 'Fakultas Rekayasa Industri',
                'created_at' => now(), 
                'updated_at' => now(), 
                'deleted_at' => null
            ],
            [
                'user_id' => 2, 
                'category_id' => 1, 
                'event_id' => null, 
                'item_name' => 'Sepatu Sneakers Putih (Ukuran 42)', 
                'description' => 'Sepatu bekas pakai, kondisi alas masih tebal. Hanya perlu dicuci sedikit agar bersih kembali.', 
                'condition' => 'cukup baik', 
                // Menggunakan link gambar sepatu
                'image_url' => 'https://images.unsplash.com/photo-1595950653106-6c9ebd614c3a?auto=format&fit=crop&q=80&w=400', 
                'status' => 'available', 
                'location' => 'Bojongsoang, Bandung',
                'created_at' => now(), 
                'updated_at' => now(), 
                'deleted_at' => null
            ],
            [
                'user_id' => 2, 
                'category_id' => 3, 
                'event_id' => null, 
                'item_name' => 'Monitor Komputer 19 Inch', 
                'description' => 'Monitor masih menyala normal. Disumbangkan karena sudah tidak dipakai. Tidak termasuk kabel power.', 
                'condition' => 'baik', 
                // Menggunakan link gambar monitor
                'image_url' => 'https://images.unsplash.com/photo-1527443224154-c4a3942d3acf?auto=format&fit=crop&q=80&w=400', 
                'status' => 'requested', 
                'location' => 'Sukapura, Dayeuhkolot',
                'created_at' => now(), 
                'updated_at' => now(), 
                'deleted_at' => null
            ],
            [
                'user_id' => 3, 
                'category_id' => 2, 
                'event_id' => null, 
                'item_name' => 'Kumpulan Novel Fiksi Remaja', 
                'description' => 'Paket berisi 3 buku novel. Kondisi masih segel dan belum pernah dibaca.', 
                'condition' => 'baru', 
                // Menggunakan link gambar novel/buku tumpuk
                'image_url' => 'https://images.unsplash.com/photo-1544947950-fa07a98d237f?auto=format&fit=crop&q=80&w=400', 
                'status' => 'available', 
                'location' => 'Gedung TULT',
                'created_at' => now(), 
                'updated_at' => now(), 
                'deleted_at' => null
            ],
        ]);
    }
}
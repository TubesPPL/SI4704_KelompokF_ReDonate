<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        DB::table('categories')->insert([
            [
                'id' => 1, 
                'category_name' => 'Pakaian', 
                'description' => 'Segala jenis pakaian pria, wanita, dan anak-anak.', 
                'created_at' => now(), 
                'updated_at' => now()
            ],
            [
                'id' => 2, 
                'category_name' => 'Buku', 
                'description' => 'Buku pelajaran, novel, komik, dan bahan bacaan lainnya.', 
                'created_at' => now(), 
                'updated_at' => now()
            ],
            [
                'id' => 3, 
                'category_name' => 'Elektronik', 
                'description' => 'Barang elektronik rumah tangga dan gadget.', 
                'created_at' => now(), 
                'updated_at' => now()
            ],
        ]);
    }
}
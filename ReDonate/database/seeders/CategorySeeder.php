<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            'Pakaian',
            'Elektronik',
            'Buku',
            'Perabot Rumah',
            'Mainan Anak',
            'Olahraga',
            'Alat Dapur',
            'Lainnya'
        ];

        foreach ($categories as $category) {
            Category::create([
                'name' => $category,
                'slug' => Str::slug($category),
                'description' => 'Kategori untuk ' . strtolower($category),
            ]);
        }
    }
}

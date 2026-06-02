<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Item;
use App\Models\User;
use Illuminate\Database\Seeder;

class ItemSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = User::all();
        $categories = Category::all();

        if ($users->count() == 0 || $categories->count() == 0) {
            return; // Pastikan user dan kategori sudah di-seed
        }

        for ($i = 0; $i < 20; $i++) {
            Item::factory()->create([
                'user_id' => $users->random()->id,
                'category_id' => $categories->random()->id,
                // Status dan kondisi akan di-generate oleh factory
            ]);
        }
    }
}

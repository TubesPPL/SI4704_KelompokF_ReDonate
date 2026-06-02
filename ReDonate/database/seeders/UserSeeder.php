<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1 Admin
        User::create([
            'name' => 'Admin ReDonate',
            'email' => 'admin@redonate.com',
            'password' => Hash::make('password'),
            'role' => 'admin',
            'is_verified' => true,
        ]);

        // 5 User Biasa
        User::factory()->count(5)->create([
            'role' => 'user',
        ]);
    }
}

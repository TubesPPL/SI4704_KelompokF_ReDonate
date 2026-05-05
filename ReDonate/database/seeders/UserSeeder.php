<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('users')->insert([
            [
                'id' => 1, 
                'name' => 'Budi Donatur', 
                'email' => 'budi@example.com', 
                'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 
                'role' => 'donatur', 
                'is_active' => 1, 
                'created_at' => now(), 
                'updated_at' => now()
            ],
            [
                'id' => 2, 
                'name' => 'Siti Donatur', 
                'email' => 'siti@example.com', 
                'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 
                'role' => 'donatur', 
                'is_active' => 1, 
                'created_at' => now(), 
                'updated_at' => now()
            ],
            [
                'id' => 3, 
                'name' => 'Agus Admin', 
                'email' => 'agus@example.com', 
                'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 
                'role' => 'both', 
                'is_active' => 1, 
                'created_at' => now(), 
                'updated_at' => now()
            ],
        ]);
    }
}
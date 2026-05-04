<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('categories', function (Blueprint $table) {
            $table->id('category_id');
            $table->string('category_name', 100);
            $table->text('description')->nullable();
            $table->timestamps();
        });

        // Seed default categories
        DB::table('categories')->insert([
            ['category_name' => 'Pakaian',      'description' => 'Baju, celana, sepatu, aksesoris'],
            ['category_name' => 'Elektronik',   'description' => 'HP, laptop, TV, peralatan elektronik'],
            ['category_name' => 'Furnitur',     'description' => 'Meja, kursi, lemari, tempat tidur'],
            ['category_name' => 'Buku',         'description' => 'Buku pelajaran, novel, majalah'],
            ['category_name' => 'Mainan',       'description' => 'Mainan anak-anak'],
            ['category_name' => 'Peralatan Rumah', 'description' => 'Peralatan dapur, alat bersih-bersih'],
            ['category_name' => 'Olahraga',     'description' => 'Peralatan olahraga'],
            ['category_name' => 'Lainnya',      'description' => 'Barang lainnya yang layak pakai'],
        ]);
    }

    public function down(): void
    {
        Schema::dropIfExists('categories');
    }
};
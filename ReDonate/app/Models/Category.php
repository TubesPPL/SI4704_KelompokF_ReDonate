<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;

    // HAPUS ATAU COMMENT BARIS INI JIKA ADA:
    // protected $primaryKey = 'category_id';

    protected $fillable = [
        'category_name',
        'description',
    ];

    public function items()
    {
        // Cukup gunakan relasi bawaan, Laravel otomatis membaca foreign key 'category_id' dan local key 'id'
        return $this->hasMany(Item::class); 
    }
}
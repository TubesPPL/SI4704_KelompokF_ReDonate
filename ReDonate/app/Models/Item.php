<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
    protected $fillable = [
        'user_id', 'category_id', 'event_id', 'item_name', 
        'description', 'condition', 'image_url', 'status'
    ];

    public function requests()
    {
        // Otomatis mencari kolom 'item_id' di tabel requests yang merujuk ke 'id' di tabel items
        return $this->hasMany(ItemRequest::class);
    }
}
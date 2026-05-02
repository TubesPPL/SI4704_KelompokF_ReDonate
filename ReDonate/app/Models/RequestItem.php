<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RequestItem extends Model
{
    protected $fillable = [
        'user_id',
        'item_id', 
        'title',
        'description',
        'category',
        'quantity',
        'status'
    ];

   // Pending otomatis
    protected $attributes = [
        'status' => 'pending'
    ];

    
    // Relasi ke user
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    
     // Relasi ke item (Barang yang diminta)
     
    public function item()
    {
        return $this->belongsTo(Item::class, 'item_id', 'item_id');
    }
}
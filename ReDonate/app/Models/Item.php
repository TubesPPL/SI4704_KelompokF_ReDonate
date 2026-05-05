<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Item extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id',
        'category_id',
        'event_id',
        'item_name',
        'description',
        'condition',
        'image_url',
        'status',
        'location',
    ];

    // Tambahkan Local Scope ini
    public function scopeAvailable($query)
    {
        return $query->where('status', 'available');
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
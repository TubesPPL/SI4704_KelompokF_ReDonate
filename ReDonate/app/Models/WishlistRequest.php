<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WishlistRequest extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'category_id',
        'title',
        'description',
        'condition_needed',
        'expires_at',
        'is_fulfilled',
        'fulfilled_by_item_id',
    ];

    protected function casts(): array
    {
        return [
            'expires_at' => 'date',
            'is_fulfilled' => 'boolean',
        ];
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function fulfilledByItem()
    {
        return $this->belongsTo(Item::class, 'fulfilled_by_item_id');
    }

    public function scopeActive($query)
    {
        return $query->where('is_fulfilled', false)
            ->where(function ($q) {
                $q->whereNull('expires_at')
                  ->orWhere('expires_at', '>=', now()->startOfDay());
            });
    }
}

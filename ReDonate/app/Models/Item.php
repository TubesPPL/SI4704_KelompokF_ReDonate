<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'category_id',
        'event_id',
        'title',
        'slug',
        'description',
        'condition',
        'quantity',
        'location',
        'delivery_method',
        'status',
        'images',
        'views',
    ];

    protected function casts(): array
    {
        return [
            'images' => 'json',
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

    public function event()
    {
        return $this->belongsTo(Event::class);
    }

    public function claims()
    {
        return $this->hasMany(Claim::class);
    }

    public function wishlistedByUsers()
    {
        return $this->belongsToMany(User::class, 'wishlists')->withTimestamps();
    }

    public function reports()
    {
        return $this->morphMany(Report::class, 'reportable');
    }

    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }
}

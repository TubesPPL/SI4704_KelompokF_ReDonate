<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'icon',
        'description',
    ];

    public function items()
    {
        return $this->hasMany(Item::class);
    }

    public function events()
    {
        return $this->belongsToMany(Event::class, 'event_categories');
    }
}

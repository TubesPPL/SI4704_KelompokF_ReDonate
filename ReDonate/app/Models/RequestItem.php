<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RequestItem extends Model
{
    protected $fillable = [
        'user_id',
        'title',
        'description',
        'category',
        'quantity',
        'status'
    ];

    /**
     * (biar otomatis pending)
     */
    protected $attributes = [
        'status' => 'pending'
    ];

    /**
     * Relasi ke user
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
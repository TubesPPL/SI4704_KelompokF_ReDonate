<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RequestItem extends Model
{
    use HasFactory;

    // Arahkan ke nama tabel yang benar
    protected $table = 'request';

    protected $fillable = [
        'user_id',
        'item_id',
        'status',
        'message',
        'pickup_method'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function item()
    {
        return $this->belongsTo(Item::class);
    }
}
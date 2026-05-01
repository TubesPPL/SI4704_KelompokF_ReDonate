<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ItemRequest extends Model
{
    // Tetap dideklarasikan karena nama tabelnya 'requests', bukan default 'item_requests'
    protected $table = 'requests'; 

    protected $fillable = [
        'item_id', 
        'requester_id', 
        'status', 
        'request_date',
        'pickup_method' 
    ];

    public function item()
    {
        // Otomatis menghubungkan 'item_id' di tabel ini ke 'id' di tabel items
        return $this->belongsTo(Item::class);
    }

    public function requester()
    {
        // Di sini kita tetap butuh parameter kedua karena nama foreign key-nya 'requester_id', 
        // bukan 'user_id' seperti standar default Laravel. Ini merujuk ke 'id' di tabel users.
        return $this->belongsTo(User::class, 'requester_id');
    }
}
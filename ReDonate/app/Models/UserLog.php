<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserLog extends Model
{
    protected $table = 'user_logs';

    protected $fillable = [
        'user_id',
        'action',
        'old_data',
        'new_data',
        'ip_address',
        'user_agent',
    ];

    // =========================
    // RELATION TO USER
    // =========================
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // =========================
    // CAST JSON AUTO
    // =========================
    protected $casts = [
        'old_data' => 'array',
        'new_data' => 'array',
    ];
}
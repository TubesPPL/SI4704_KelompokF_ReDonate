<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class Report extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'reportable_type',
        'reportable_id',
        'reason',
        'description',
        'status',
        'admin_notes',
    ];

    /**
     * User yang melakukan pelaporan
     */
    public function reporter(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Entitas yang dilaporkan (bisa User atau Item)
     */
    public function reportable(): MorphTo
    {
        return $this->morphTo();
    }

    /**
     * Dapatkan URL untuk melihat detail subject yang dilaporkan
     */
    public function getSubjectUrlAttribute(): string
    {
        if ($this->reportable_type === User::class && $this->reportable) {
            return route('profile.show', $this->reportable->id);
        }
        
        if ($this->reportable_type === Item::class && $this->reportable) {
            return route('items.show', $this->reportable->slug);
        }
        
        return '#';
    }
}

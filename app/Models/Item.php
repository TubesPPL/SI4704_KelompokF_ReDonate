<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Item extends Model
{
    use HasFactory, SoftDeletes;

    protected $primaryKey = 'item_id';

    protected $fillable = [
        'user_id',
        'category_id',
        'event_id',
        'item_name',
        'description',
        'location', 
        'condition',
        'image_url',
        'status',
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime',
    ];

    // ─── Relationships ────────────────────────────────────────────────────────

    /** Item dimiliki satu donatur (user) */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /** Item termasuk satu kategori */
    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id', 'category_id');
    }

    /** Item bisa terkait event (opsional) */
    public function event()
    {
        return $this->belongsTo(Event::class, 'event_id');
    }

    /** Satu item bisa punya banyak request dari penerima */
    public function requests()
    {
        return $this->hasMany(ItemRequest::class, 'item_id', 'item_id');
    }

    // ─── Scopes ───────────────────────────────────────────────────────────────

    /** Filter hanya item yang tersedia */
    public function scopeAvailable($query)
    {
        return $query->where('status', 'available');
    }

    /** Filter item milik user tertentu */
    public function scopeOwnedBy($query, $userId)
    {
        return $query->where('user_id', $userId);
    }

    // ─── Accessors ────────────────────────────────────────────────────────────

    /** URL lengkap foto barang */
    public function getImageFullUrlAttribute(): ?string
    {
        if (!$this->image_url) return null;
        return asset('storage/' . $this->image_url);
    }

    /** Label kondisi yang lebih ramah */
    public function getConditionLabelAttribute(): string
    {
        return ucfirst($this->condition);
    }

    public function getRouteKeyName()
    {
        return 'item_id';
    }
}
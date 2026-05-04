<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Storage;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, SoftDeletes;

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'photo_url',
        'phone',
        'address',
        'is_active'
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'is_active' => 'boolean',
    ];

    // =========================
    // RELATIONSHIP
    // =========================
    public function logs()
    {
        return $this->hasMany(UserLog::class);
    }

<<<<<<< HEAD
    public function itemRequests()
    {
        return $this->hasMany(ItemRequest::class, 'requester_id');
    }
}
=======
    // =========================
    // ROLE CHECK
    // =========================
    public function canDonate(): bool
    {
        return in_array($this->role, ['donatur', 'both']);
    }

    public function canReceive(): bool
    {
        return in_array($this->role, ['penerima', 'both']);
    }

    public function canManage(): bool
    {
        return $this->role === 'both';
    }

    // =========================
    // PHOTO ACCESSOR
    // =========================
    public function getPhotoUrlAttribute($value): string
    {
        if ($value && Storage::disk('public')->exists($value)) {
            return Storage::url($value);
        }

        return match ($this->role) {
            'donatur' => asset('/images/donatur-avatar.png'),
            'penerima' => asset('/images/penerima-avatar.png'),
            'both' => asset('/images/admin-avatar.png'),
            default => asset('/images/default-avatar.png')
        };
    }

    // =========================
    // ROLE LABEL
    // =========================
    public function getRoleDisplayAttribute(): string
    {
        return match ($this->role) {
            'donatur' => 'Donatur',
            'penerima' => 'Penerima Bantuan',
            'both' => 'Admin / Pengelola',
            default => 'User'
        };
    }

    // =========================
    // MODEL BOOT (FIXED SAFE)
    // =========================
    protected static function booted()
    {
        static::deleting(function ($user) {

            // SAFE CHECK tokens
            if (method_exists($user, 'tokens')) {
                $user->tokens()->delete();
            }

            // SAFE LOG (avoid crash kalau UserLog error)
            try {
                \App\Models\UserLog::create([
                    'user_id' => $user->id,
                    'action' => 'soft_delete_account',
                    'old_data' => json_encode($user->toArray()),
                    'ip_address' => request()->ip() ?? 'unknown',
                    'user_agent' => request()->userAgent(),
                ]);
            } catch (\Throwable $e) {
                // jangan crash sistem
            }
        });
    }

    // =========================
    // SCOPES
    // =========================
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeByRole($query, string $role)
    {
        return $query->whereIn('role', [$role, 'both']);
    }
}
>>>>>>> d509f52a9616b5a55d2155d2ef7b4614306d552f

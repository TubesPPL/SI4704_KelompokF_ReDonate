<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

#[Fillable(['name', 'email', 'password', 'phone', 'address', 'avatar', 'bio', 'is_verified', 'role'])]
#[Hidden(['password', 'remember_token'])]
class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'is_verified' => 'boolean',
        ];
    }

    public function items()
    {
        return $this->hasMany(Item::class);
    }

    public function claims()
    {
        return $this->hasMany(Claim::class);
    }

    public function events()
    {
        return $this->hasMany(Event::class, 'created_by');
    }

    public function reviewsGiven()
    {
        return $this->hasMany(Review::class, 'reviewer_id');
    }

    public function reviewsReceived()
    {
        return $this->hasMany(Review::class, 'reviewee_id');
    }

    public function notifications()
    {
        return $this->hasMany(Notification::class);
    }

    public function unreadChatCount()
    {
        return Message::where('sender_id', '!=', $this->id)
            ->whereNull('read_at')
            ->whereHas('claim', function ($query) {
                $query->where('user_id', $this->id)
                      ->orWhereHas('item', function ($q) {
                          $q->where('user_id', $this->id);
                      });
            })
            ->count();
    }

    public function wishlistedItems()
    {
        return $this->belongsToMany(Item::class, 'wishlists')->withTimestamps();
    }

    public function wishlistRequests()
    {
        return $this->hasMany(WishlistRequest::class);
    }

    public function madeReports()
    {
        return $this->hasMany(Report::class, 'user_id');
    }

    public function reports()
    {
        return $this->morphMany(Report::class, 'reportable');
    }
}

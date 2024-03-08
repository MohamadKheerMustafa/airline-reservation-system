<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable implements HasMedia
{
    use HasApiTokens, HasFactory, Notifiable, InteractsWithMedia;

    protected $guarded = [];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function getCreatedAtAttribute($value)
    {
        return Carbon::parse($value)->format("M d, Y");
    }

    // Scopes
    public function scopeCustomer($query)
    {
        return $query->where('is_admin', 0);
    }

    public function scopeEmployee($query)
    {
        return $query->where('is_Employee', 1);
    }
    public function scopeAdmin($query)
    {
        return $query->where('is_admin', 1);
    }

    // Relations

    public function tickets()
    {
        return $this->hasMany(Ticket::class, 'user_id');
    }
    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('Profiles');
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use MongoDB\Laravel\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'phone',
        'location',
        'farm_size',
        'role',
        'avatar',
        'google_id',
        'google_token',
        'google_refresh_token',
        'is_active',
    ];

    protected $hidden = [
        'password',
        'remember_token',
        'google_token',
        'google_refresh_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'is_active' => 'boolean',
    ];

    public const ROLE_FARMER = 'farmer';
    public const ROLE_BUYER = 'buyer';
    public const ROLE_ADMIN = 'admin';
    public const ROLES = [self::ROLE_FARMER, self::ROLE_BUYER, self::ROLE_ADMIN];

    // Relationships
    public function crops()
    {
        return $this->hasMany(Crop::class);
    }

    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    public function salesOrders()
    {
        return $this->hasManyThrough(Order::class, Crop::class);
    }

    public function isFarmer(): bool
    {
        return $this->role === self::ROLE_FARMER;
    }

    public function isBuyer(): bool
    {
        return $this->role === self::ROLE_BUYER;
    }

    public function isAdmin(): bool
    {
        return $this->role === self::ROLE_ADMIN;
    }

    // Accessors
    public function getAvatarUrlAttribute()
    {
        if ($this->avatar) {
            return asset('storage/' . $this->avatar);
        }
        return 'https://ui-avatars.com/api/?name=' . urlencode($this->name) . '&background=2E7D32&color=ffffff';
    }

    public function getTotalEarningsAttribute()
    {
        if ($this->role === self::ROLE_BUYER) {
            return 0.0;
        }
        return (float) (string) Order::whereHas('crop', fn($q) => $q->where('user_id', $this->id))
            ->whereIn('status', ['confirmed', 'processing', 'completed'])
            ->sum('total_amount');
    }

    public function getTotalSpentAttribute()
    {
        return (float) (string) $this->orders()
            ->whereIn('status', ['confirmed', 'processing', 'completed'])
            ->sum('total_amount');
    }

    public function getActiveCropsCountAttribute()
    {
        return $this->crops()->where('status', 'active')->count();
    }
}

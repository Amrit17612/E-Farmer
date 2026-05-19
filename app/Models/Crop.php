<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use MongoDB\Laravel\Eloquent\Model;

class Crop extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'name',
        'category',
        'quantity',
        'unit',
        'price_per_unit',
        'description',
        'harvest_date',
        'location',
        'image',
        'status',
    ];

    protected $casts = [
        'harvest_date' => 'date',
        'price_per_unit' => 'float',
        'quantity' => 'float',
    ];

    const STATUSES = ['active', 'sold', 'pending', 'expired'];
    const CATEGORIES = ['Grains', 'Vegetables', 'Fruits', 'Pulses', 'Oilseeds', 'Spices', 'Others'];
    const UNITS = ['kg', 'quintal', 'ton', 'piece', 'dozen', 'liter'];

    // Relationships
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    public function marketPrice()
    {
        return $this->hasOne(MarketPrice::class, 'crop_name', 'name');
    }

    // Accessors
    public function getImageUrlAttribute()
    {
        if ($this->image) {
            return asset('storage/' . $this->image);
        }
        return asset('images/crop-placeholder.jpg');
    }

    public function getTotalValueAttribute()
    {
        return $this->quantity * $this->price_per_unit;
    }

    public function getStatusBadgeAttribute()
    {
        return match($this->status) {
            'active'  => '<span class="badge badge-success">Active</span>',
            'sold'    => '<span class="badge badge-secondary">Sold</span>',
            'pending' => '<span class="badge badge-warning">Pending</span>',
            'expired' => '<span class="badge badge-danger">Expired</span>',
            default   => '<span class="badge badge-secondary">' . ucfirst($this->status) . '</span>',
        };
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    public function scopeForUser($query, $userId)
    {
        return $query->where('user_id', $userId);
    }

    // Accessors for Decimal128 to float conversion
    public function getPricePerUnitAttribute($value)
    {
        return $value instanceof \MongoDB\BSON\Decimal128 ? (float) (string) $value : (float) $value;
    }

    public function getQuantityAttribute($value)
    {
        return $value instanceof \MongoDB\BSON\Decimal128 ? (float) (string) $value : (float) $value;
    }
}

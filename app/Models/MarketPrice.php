<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use MongoDB\Laravel\Eloquent\Model;

class MarketPrice extends Model
{
    use HasFactory;

    protected $fillable = [
        'crop_name',
        'category',
        'min_price',
        'max_price',
        'modal_price',
        'unit',
        'market_name',
        'state',
        'district',
        'price_date',
        'trend',
    ];

    protected $casts = [
        'min_price'   => 'float',
        'max_price'   => 'float',
        'modal_price' => 'float',
        'price_date'  => 'date',
    ];

    const TRENDS = ['up', 'down', 'stable'];

    // Accessors
    public function getTrendIconAttribute()
    {
        return match($this->trend) {
            'up'     => '↑',
            'down'   => '↓',
            'stable' => '→',
            default  => '→',
        };
    }

    public function getTrendClassAttribute()
    {
        return match($this->trend) {
            'up'     => 'text-success',
            'down'   => 'text-danger',
            'stable' => 'text-warning',
            default  => 'text-muted',
        };
    }

    // Scopes
    public function scopeSearch($query, $term)
    {
        return $query->where('crop_name', 'LIKE', "%{$term}%")
                     ->orWhere('market_name', 'LIKE', "%{$term}%")
                     ->orWhere('state', 'LIKE', "%{$term}%");
    }

    public function scopeByCategory($query, $category)
    {
        return $query->where('category', $category);
    }

    // Accessors for Decimal128 to float conversion
    public function getMinPriceAttribute($value)
    {
        return $value instanceof \MongoDB\BSON\Decimal128 ? (float) (string) $value : (float) $value;
    }

    public function getMaxPriceAttribute($value)
    {
        return $value instanceof \MongoDB\BSON\Decimal128 ? (float) (string) $value : (float) $value;
    }

    public function getModalPriceAttribute($value)
    {
        return $value instanceof \MongoDB\BSON\Decimal128 ? (float) (string) $value : (float) $value;
    }
}

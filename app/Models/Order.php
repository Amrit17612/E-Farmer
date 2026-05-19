<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use MongoDB\Laravel\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'crop_id',
        'buyer_name',
        'buyer_phone',
        'buyer_email',
        'quantity',
        'price_per_unit',
        'total_amount',
        'status',
        'payment_method',
        'notes',
        'delivery_date',
    ];

    protected $casts = [
        'total_amount'  => 'float',
        'price_per_unit'=> 'float',
        'quantity'      => 'float',
        'delivery_date' => 'date',
    ];

    const STATUSES = ['pending', 'confirmed', 'processing', 'completed', 'cancelled'];
    const PAYMENT_METHODS = ['cash', 'bank_transfer', 'upi', 'cheque'];

    // Relationships
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function crop()
    {
        return $this->belongsTo(Crop::class);
    }

    // Accessors
    public function getStatusBadgeAttribute()
    {
        return match($this->status) {
            'pending'    => '<span class="badge badge-warning">Pending</span>',
            'confirmed'  => '<span class="badge badge-info">Confirmed</span>',
            'processing' => '<span class="badge badge-primary">Processing</span>',
            'completed'  => '<span class="badge badge-success">Completed</span>',
            'cancelled'  => '<span class="badge badge-danger">Cancelled</span>',
            default      => '<span class="badge badge-secondary">' . ucfirst($this->status) . '</span>',
        };
    }

    // Scopes
    public function scopeCompleted($query)
    {
        return $query->where('status', 'completed');
    }

    // Accessors for Decimal128 to float conversion
    public function getTotalAmountAttribute($value)
    {
        return $value instanceof \MongoDB\BSON\Decimal128 ? (float) (string) $value : (float) $value;
    }

    public function getPricePerUnitAttribute($value)
    {
        return $value instanceof \MongoDB\BSON\Decimal128 ? (float) (string) $value : (float) $value;
    }

    public function getQuantityAttribute($value)
    {
        return $value instanceof \MongoDB\BSON\Decimal128 ? (float) (string) $value : (float) $value;
    }
}

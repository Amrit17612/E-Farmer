<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use MongoDB\Laravel\Eloquent\Model;

class Scheme extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'eligibility',
        'benefits',
        'documents_required',
        'apply_link',
        'ministry',
        'category',
        'deadline',
        'image',
        'is_active',
    ];

    protected $casts = [
        'deadline'   => 'date',
        'is_active'  => 'boolean',
    ];

    const CATEGORIES = ['Subsidy', 'Loan', 'Insurance', 'Training', 'Equipment', 'Seeds & Fertilizers'];

    // Relationships
    public function applications()
    {
        return $this->hasMany(SchemeApplication::class);
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeByCategory($query, $category)
    {
        return $query->where('category', $category);
    }

    // Accessors
    public function getImageUrlAttribute()
    {
        if ($this->image) {
            return asset('storage/' . $this->image);
        }
        return asset('images/scheme-placeholder.jpg');
    }

    public function getDocumentsRequiredAttribute($value)
    {
        if (is_array($value)) {
            return $value;
        }

        $documents = json_decode($value ?? '[]', true);

        if (is_string($documents)) {
            $documents = json_decode($documents, true);
        }

        if (! is_array($documents)) {
            return [];
        }

        return array_values(array_filter($documents));
    }

    public function setDocumentsRequiredAttribute($value)
    {
        $this->attributes['documents_required'] = is_array($value) ? json_encode($value) : $value;
    }

    public function getIsExpiredAttribute()
    {
        return $this->deadline && $this->deadline->isPast();
    }
}

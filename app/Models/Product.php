<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'category_id',
        'chemical_formula',
        'cas_number',
        'short_description',
        'description',
        'applications',
        'specifications',
        'image',
        'is_active',
        'is_featured',
        'sort_order'
    ];

    protected $casts = [
        'specifications' => 'array',
        'is_active' => 'boolean',
        'is_featured' => 'boolean',
    ];

    public function category()
    {
        return $this->belongsTo(ProductCategory::class, 'category_id');
    }

    public function getApplicationsListAttribute()
    {
        if (!$this->applications) {
            return [];
        }
        return array_filter(array_map('trim', explode("\n", $this->applications)));
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true);
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('sort_order')->orderBy('name');
    }
}

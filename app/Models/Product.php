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
        'sort_order',
        'styles'
    ];

    protected $casts = [
        'specifications' => 'array',
        'is_active' => 'boolean',
        'is_featured' => 'boolean',
        'styles' => 'array',
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

    /**
     * Get the product image URL or a curated aesthetic fallback.
     */
    public function getMainImageUrlAttribute(): string
    {
        if ($this->image) {
            return \Illuminate\Support\Facades\Storage::url($this->image);
        }

        // Professional industrial/chemical fallbacks
        $fallbacks = [
            'https://images.unsplash.com/photo-1581091226825-a6a2a5aee158?auto=format&fit=crop&q=80&w=800', // Lab/Industrial
            'https://images.unsplash.com/photo-1518152006812-edab29b069ac?auto=format&fit=crop&q=80&w=800', // Chemical plant
            'https://images.unsplash.com/photo-1628155930542-3c7a64e2c833?auto=format&fit=crop&q=80&w=800', // Blue chemical drums
            'https://images.unsplash.com/photo-1532187875605-2fe358a3d4d2?auto=format&fit=crop&q=80&w=800', // Scientific research
            'https://images.unsplash.com/photo-1581092160562-40aa08e78837?auto=format&fit=crop&q=80&w=800', // Industrial logistics
        ];

        // Deterministic fallback based on ID
        return $fallbacks[$this->id % count($fallbacks)];
    }

    public function getStyleString(string $field = 'card'): string
    {
        if (empty($this->styles) || empty($this->styles[$field])) return '';
        return collect($this->styles[$field])
            ->map(fn($v, $k) => strtolower(preg_replace('/([A-Z])/', '-$1', $k)) . ':' . $v)
            ->implode(';');
    }
}

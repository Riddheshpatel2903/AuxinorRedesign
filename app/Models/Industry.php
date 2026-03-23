<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\Storage;

class Industry extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 'slug', 'icon', 'desc', 'image', 'is_active', 'sort_order', 'styles'
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'styles' => 'array',
    ];

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('sort_order')->orderBy('name');
    }

    public function getMainImageUrlAttribute()
    {
        if ($this->image && \Illuminate\Support\Facades\Storage::disk('public')->exists($this->image)) {
            return \Illuminate\Support\Facades\Storage::url($this->image);
        }

        // Deterministic fallback based on ID
        $fallbacks = [
            'https://images.unsplash.com/photo-1518152006812-edab29b069ac?q=80&w=2070&auto=format&fit=crop', // Lab
            'https://images.unsplash.com/photo-1554469384-e58fac16e23a?q=80&w=1974&auto=format&fit=crop', // Building/Industry
            'https://images.unsplash.com/photo-1485081666276-039ac58296ec?q=80&w=2070&auto=format&fit=crop', // Pipes
            'https://images.unsplash.com/photo-1581091226825-a6a2a5aee158?q=80&w=2070&auto=format&fit=crop', // Tech
            'https://images.unsplash.com/photo-1504384308090-c894fdcc538d?q=80&w=2070&auto=format&fit=crop', // Data/Chem
            'https://images.unsplash.com/photo-1454165205744-3b78555e5572?q=80&w=2070&auto=format&fit=crop', // Office/Plan
        ];

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

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductCategory extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'description',
        'icon',
        'image',
        'sort_order',
        'is_active',
        'styles',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'styles' => 'array',
    ];

    public function products()
    {
        return $this->hasMany(Product::class, 'category_id');
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('sort_order');
    }

    public function getStyleString(string $field = 'card'): string
    {
        if (empty($this->styles) || empty($this->styles[$field])) return '';
        return collect($this->styles[$field])
            ->map(fn($v, $k) => strtolower(preg_replace('/([A-Z])/', '-$1', $k)) . ':' . $v)
            ->implode(';');
    }
}

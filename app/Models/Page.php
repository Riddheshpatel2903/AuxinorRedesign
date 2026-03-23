<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Page extends Model
{
    protected $fillable = [
        'title',
        'slug',
        'status',
        'content',
        'meta_title',
        'meta_description',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'content' => 'array',
    ];

    public function scopeActive($query)
    {
        return $query->where(function ($query) {
            $query->where('status', 'published');

            if (\Illuminate\Support\Facades\Schema::hasColumn('pages', 'is_active')) {
                $query->orWhere('is_active', true);
            }
        });
    }

    public function scopePublished($query)
    {
        return $query->where('status', 'published');
    }

    public function publish(): void
    {
        $this->forceFill([
            'status' => 'published',
            'is_active' => true,
        ])->save();
    }

    public function isPublished(): bool
    {
        return $this->status === 'published' || (bool) $this->is_active;
    }

    public function countSections(): int
    {
        return is_array($this->content) ? count($this->content) : 0;
    }

    public function getUrlAttribute(): string
    {
        return $this->slug === 'home' ? '/' : '/' . $this->slug;
    }
}

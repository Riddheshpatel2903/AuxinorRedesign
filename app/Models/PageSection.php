<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PageSection extends Model
{
    use HasFactory;

    protected $fillable = ['page_slug','section_key','section_label','sort_order',
                           'is_visible','content','styles','published_at'];
    protected $casts = ['content'=>'array','styles'=>'array','is_visible'=>'boolean',
                        'published_at'=>'datetime'];

    public function elements()
    {
        return $this->hasMany(PageElement::class, 'section_id');
    }

    public function scopeForPage($q, $slug)
    {
        return $q->where('page_slug', $slug)->orderBy('sort_order');
    }

    public function getStyleString(): string
    {
        if (empty($this->styles)) return '';
        return collect($this->styles)
            ->map(fn($v, $k) => strtolower(preg_replace('/([A-Z])/', '-$1', $k)) . ':' . $v)
            ->implode(';') . ';';
    }

    public function val(string $key, string $default = ''): string
    {
        return $this->content[$key] ?? $default;
    }
}

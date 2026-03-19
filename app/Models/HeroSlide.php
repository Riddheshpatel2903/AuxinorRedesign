<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HeroSlide extends Model
{
    use HasFactory;

    protected $fillable = ['image_url','image_alt','overlay_opacity','sort_order','is_active'];
    protected $casts = ['is_active' => 'boolean', 'overlay_opacity' => 'float'];

    public function scopeActive($q) { 
        return $q->where('is_active', true)->orderBy('sort_order'); 
    }
}

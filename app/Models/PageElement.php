<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PageElement extends Model
{
    use HasFactory;

    protected $fillable = ['section_id', 'element_key', 'element_type', 'content', 'href', 'image_url', 'styles', 'sort_order'];
    protected $casts = ['styles' => 'array'];

    public function section()
    {
        return $this->belongsTo(PageSection::class, 'section_id');
    }
}

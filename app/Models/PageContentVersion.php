<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PageContentVersion extends Model
{
    protected $fillable = [
        'page_content_id',
        'components_json',
        'styles_json',
        'html_output',
        'saved_by',
    ];

    public function page(): BelongsTo
    {
        return $this->belongsTo(PageContent::class, 'page_content_id');
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class PageContent extends Model
{
    protected $fillable = [
        'page_slug',
        'page_title',
        'components_json',
        'styles_json',
        'html_output',
        'last_edited_by',
        'last_edited_at',
        'is_published',
    ];

    protected $casts = [
        'is_published' => 'boolean',
        'last_edited_at' => 'datetime',
    ];

    public function versions(): HasMany
    {
        return $this->hasMany(PageContentVersion::class);
    }

    public function saveVersion(string $savedBy = 'admin'): void
    {
        $this->versions()->create([
            'components_json' => $this->components_json,
            'styles_json'     => $this->styles_json,
            'html_output'     => $this->html_output,
            'saved_by'        => $savedBy,
        ]);

        // Keep only last 20 versions
        $count = $this->versions()->count();
        if ($count > 20) {
            $this->versions()->oldest()->limit($count - 20)->delete();
        }
    }
}

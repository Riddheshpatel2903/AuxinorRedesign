<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class SiteImage extends Model
{
    protected $fillable = [
        'filename', 'path', 'disk', 'used_in',
        'file_size', 'mime_type', 'width', 'height'
    ];

    /**
     * Get the full URL for the image.
     */
    public function getUrlAttribute(): string
    {
        return Storage::disk($this->disk)->url($this->path);
    }

    /**
     * Store an uploaded file and create a record.
     */
    public static function store(\Illuminate\Http\UploadedFile $file, string $folder = 'uploads/sections', string $usedIn = ''): self
    {
        $path = $file->store($folder, 'public');
        
        // Try to get dimensions
        [$width, $height] = @getimagesize(Storage::disk('public')->path($path)) ?: [null, null];

        return self::create([
            'filename'  => $file->getClientOriginalName(),
            'path'      => $path,
            'disk'      => 'public',
            'used_in'   => $usedIn,
            'file_size' => $file->getSize(),
            'mime_type' => $file->getMimeType(),
            'width'     => $width,
            'height'    => $height,
        ]);
    }
}

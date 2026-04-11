<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;

class ImageService
{
    private $manager;

    public function __construct()
    {
        $this->manager = new ImageManager(new Driver());
    }

    /**
     * Process an uploaded image: validate, convert to WebP, and store.
     */
    public function upload(UploadedFile $file, string $folder = 'uploads'): string
    {
        $year = date('Y');
        $month = date('m');
        $filename = Str::uuid() . '.webp';
        $directory = "{$folder}/{$year}/{$month}";
        $path = "{$directory}/{$filename}";

        // Ensure directory exists
        if (!Storage::disk('media')->exists($directory)) {
            Storage::disk('media')->makeDirectory($directory);
        }

        // Read and convert to WebP
        $image = $this->manager->read($file);
        $encoded = $image->toWebp(80); // 80% quality

        // Store
        Storage::disk('media')->put($path, (string) $encoded);

        return Storage::disk('media')->url($path);
    }

    /**
     * Delete an image by its URL or path.
     */
    public function delete(string $url): bool
    {
        $path = str_replace(url('media'), '', $url);
        $path = ltrim($path, '/');
        if (Storage::disk('media')->exists($path)) {
            return Storage::disk('media')->delete($path);
        }
        return false;
    }
}

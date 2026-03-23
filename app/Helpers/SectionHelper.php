<?php

namespace App\Helpers;

use App\Models\PageSection;
use Illuminate\Support\Collection;

class SectionHelper
{
    /**
     * Get the inline style string for a section.
     */
    public static function sectionStyle($pageSections, string $key): string
    {
        $section = ($pageSections instanceof Collection)
            ? $pageSections->get($key)
            : ($pageSections[$key] ?? null);

        if (!$section) return '';
        
        // This leverages the getStyleString method we added to the PageSection model
        return $section->getStyleString();
    }

    /**
     * Check if a section is marked as visible.
     */
    public static function sectionVisible($pageSections, string $key): bool
    {
        $section = ($pageSections instanceof Collection)
            ? $pageSections->get($key)
            : ($pageSections[$key] ?? null);
            
        return $section ? (bool)$section->is_visible : true;
    }
    
    /**
     * Helper to get element data for Blade.
     */
    public static function element($pageSections, string $sectionKey, string $elementKey)
    {
        $section = ($pageSections instanceof Collection)
            ? $pageSections->get($sectionKey)
            : ($pageSections[$sectionKey] ?? null);
            
        if (!$section) return null;
        
        return $section->element($elementKey);
    }
}


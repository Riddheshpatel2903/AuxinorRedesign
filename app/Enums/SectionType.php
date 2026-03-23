<?php

namespace App\Enums;

enum SectionType: string
{
    // Hero sections
    case HERO = 'hero';

    // Content sections
    case MISSION = 'mission';
    case CONTENT = 'content';

    // Layout sections
    case GRID = 'grid';
    case FEATURES = 'features';
    case STATS = 'stats';

    // Action sections
    case CTA = 'cta';
    case CONTACT = 'contact';

    // Special sections
    case CATALOGUE_GRID = 'catalogue_grid';

    /**
     * Get the human-readable label
     */
    public function label(): string
    {
        return match ($this) {
            self::HERO => 'Hero Banner',
            self::MISSION => 'Mission Statement',
            self::CONTENT => 'Content Block',
            self::GRID => 'Grid Layout',
            self::FEATURES => 'Feature Cards',
            self::STATS => 'Statistics',
            self::CTA => 'Call to Action',
            self::CONTACT => 'Contact Form',
            self::CATALOGUE_GRID => 'Catalogue Grid',
        };
    }

    /**
     * Get description
     */
    public function description(): string
    {
        return config("sections.{$this->value}.description", '');
    }

    /**
     * Get all section type options for dropdowns
     */
    public static function options(): array
    {
        return collect(self::cases())
            ->mapWithKeys(fn($case) => [$case->value => $case->label()])
            ->all();
    }

    /**
     * Check if section type exists in config
     */
    public function exists(): bool
    {
        return config("sections.{$this->value}") !== null;
    }
}

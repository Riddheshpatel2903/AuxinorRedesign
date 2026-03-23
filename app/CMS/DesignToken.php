<?php

namespace App\CMS;

use Illuminate\Support\Collection;

/**
 * DesignToken
 * 
 * Represents a single design token (color, spacing, typography property, etc.)
 * Provides methods to query and validate token values.
 */
class DesignToken
{
    private string $category;
    private string $name;
    private array $token;

    public function __construct(string $category, string $name, array $token)
    {
        $this->category = $category;
        $this->name = $name;
        $this->token = $token;
    }

    /**
     * Get token label for UI
     */
    public function label(): string
    {
        return $this->token['label'] ?? ucwords(str_replace('-', ' ', $this->name));
    }

    /**
     * Get token value (for colors, this is the hex/rgb value)
     */
    public function value(): string
    {
        return $this->token['value'] ?? '';
    }

    /**
     * Get all token properties
     */
    public function toArray(): array
    {
        return $this->token;
    }

    /**
     * Get category name
     */
    public function category(): string
    {
        return $this->category;
    }

    /**
     * Get token name (key)
     */
    public function name(): string
    {
        return $this->name;
    }

    /**
     * Check if this is a color token
     */
    public function isColor(): bool
    {
        return $this->category === 'colors';
    }

    /**
     * Check if this is a typography token
     */
    public function isTypography(): bool
    {
        return $this->category === 'typography';
    }

    /**
     * Get CSS property value (for colors: returns hex value)
     */
    public function cssValue(): string
    {
        if ($this->isColor()) {
            return $this->token['hex'] ?? $this->token['value'] ?? '';
        }

        return $this->token['value'] ?? '';
    }

    // ============================================================================
    // Static methods for token queries
    // ============================================================================

    /**
     * Get all tokens by category
     */
    public static function byCategory(string $category): Collection
    {
        $tokens = config('design-tokens');
        $categoryTokens = $tokens[$category] ?? [];

        return collect($categoryTokens)
            ->mapWithKeys(function($token, $name) use ($category) {
                // Handle flat array values (key => value)
                if (!is_array($token)) {
                    $token = [
                        'label' => (string)$name,
                        'value' => (string)$token,
                    ];
                }
                return [
                    $name => new self($category, $name, $token)
                ];
            });
    }

    /**
     * Get all color tokens
     */
    public static function colors(): Collection
    {
        return self::byCategory('colors');
    }

    /**
     * Get all typography tokens
     */
    public static function typography(): Collection
    {
        return self::byCategory('typography');
    }

    /**
     * Get all spacing tokens
     */
    public static function spacing(): Collection
    {
        return self::byCategory('spacing');
    }

    /**
     * Get all border radius tokens
     */
    public static function borderRadius(): Collection
    {
        return self::byCategory('borderRadius');
    }

    /**
     * Get all shadow tokens
     */
    public static function shadows(): Collection
    {
        return self::byCategory('shadows');
    }

    /**
     * Check if a color value exists in color tokens
     */
    public static function hasColor(string $value): bool
    {
        return self::colors()
            ->contains(fn($token) => $token->cssValue() === $value || $token->value() === $value);
    }

    /**
     * Get color token by value
     */
    public static function findColorByValue(string $value): ?DesignToken
    {
        return self::colors()
            ->first(fn($token) => $token->cssValue() === $value || $token->value() === $value);
    }

    /**
     * Get color token by name
     */
    public static function color(string $name): ?DesignToken
    {
        return self::colors()->get($name);
    }

    /**
     * Get typography token by name
     */
    public static function getTypography(string $name): ?DesignToken
    {
        return self::typography()->get($name);
    }

    /**
     * Get spacing value by name
     */
    public static function getSpacing(string $name): ?string
    {
        $spacing = self::spacing();
        return $spacing->get($name)?->value();
    }

    /**
     * Get all tokens for editor dropdown
     */
    public static function options(): array
    {
        return [
            'colors' => self::colors()
                ->map(fn($token) => [
                    'name' => $token->name(),
                    'label' => $token->label(),
                    'value' => $token->cssValue(),
                ])
                ->values()
                ->toArray(),
            'typography' => self::typography()
                ->map(fn($token) => [
                    'name' => $token->name(),
                    'label' => $token->label(),
                    'values' => $token->toArray(),
                ])
                ->values()
                ->toArray(),
            'spacing' => self::spacing()
                ->map(fn($token) => [
                    'name' => $token->name(),
                    'label' => $token->name(),
                    'value' => $token->value(),
                ])
                ->values()
                ->toArray(),
        ];
    }
}

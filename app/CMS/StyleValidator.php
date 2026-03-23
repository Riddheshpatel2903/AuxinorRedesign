<?php

namespace App\CMS;

use Illuminate\Support\Collection;
use Illuminate\Validation\ValidationException;

/**
 * StyleValidator
 * 
 * PHASE 6: Style System
 * 
 * Validates CSS property values against:
 * 1. Whitelist of allowed CSS properties
 * 2. Design token constraints (color values, spacing, typography)
 * 3. Type checking (numeric, color, reference, etc.)
 */
class StyleValidator
{
    /**
     * CSS properties allowed to be edited by visual editor
     * Maps property name to allowed value types
     */
    private static array $allowedProperties = [
        'color' => 'color',
        'backgroundColor' => 'color',
        'borderColor' => 'color',
        'fontSize' => 'typography',
        'fontWeight' => 'typography',
        'fontFamily' => 'typography',
        'lineHeight' => 'typography',
        'letterSpacing' => 'typography',
        'paddingTop' => 'spacing',
        'paddingBottom' => 'spacing',
        'paddingLeft' => 'spacing',
        'paddingRight' => 'spacing',
        'marginTop' => 'spacing',
        'marginBottom' => 'spacing',
        'marginLeft' => 'spacing',
        'marginRight' => 'spacing',
        'borderRadius' => 'borderRadius',
        'boxShadow' => 'shadow',
        'textAlign' => 'enum:left,center,right,justify',
        'display' => 'enum:block,inline,inline-block,flex,grid,none',
        'alignItems' => 'enum:flex-start,center,flex-end,stretch',
        'justifyContent' => 'enum:flex-start,center,flex-end,space-between,space-around',
        'gap' => 'spacing',
        'width' => 'dimension',
        'minHeight' => 'dimension',
        'maxWidth' => 'dimension',
        'height' => 'dimension',
        'minWidth' => 'dimension',
        'zIndex' => 'numeric',
        'opacity' => 'float:0,1',
        'transition' => 'transition',
        'transform' => 'text',
        'backgroundSize' => 'enum:cover,contain,auto',
        'backgroundPosition' => 'enum:top,center,bottom,left,right',
    ];

    private array $styles;
    private array $errors = [];

    public function __construct(array $styles = [])
    {
        $this->styles = $styles;
    }

    /**
     * Validate all styles
     */
    public function validate(): bool
    {
        $this->errors = [];

        foreach ($this->styles as $property => $value) {
            if (!$this->isPropertyAllowed($property)) {
                $this->errors[$property] = "Property '{$property}' is not allowed.";
                continue;
            }

            if (!$this->isValueValid($property, $value)) {
                $this->errors[$property] = "Invalid value for '{$property}': {$value}";
            }
        }

        return empty($this->errors);
    }

    /**
     * Validate or throw exception
     */
    public function validateOrFail(): void
    {
        if (!$this->validate()) {
            throw ValidationException::withMessages($this->errors);
        }
    }

    /**
     * Get validation errors
     */
    public function errors(): array
    {
        return $this->errors;
    }

    /**
     * Get first error
     */
    public function getError(): ?string
    {
        return array_values($this->errors)[0] ?? null;
    }

    /**
     * Check if property is in whitelist
     */
    private function isPropertyAllowed(string $property): bool
    {
        return isset(self::$allowedProperties[$property]);
    }

    /**
     * Validate value based on property type
     */
    private function isValueValid(string $property, mixed $value): bool
    {
        if (!is_string($value)) {
            return false;
        }

        $valueType = self::$allowedProperties[$property];

        return match (true) {
            $valueType === 'color' => $this->isValidColor($value),
            $valueType === 'typography' => $this->isValidTypography($property, $value),
            $valueType === 'spacing' => $this->isValidSpacing($value),
            $valueType === 'borderRadius' => $this->isValidBorderRadius($value),
            $valueType === 'shadow' => $this->isValidShadow($value),
            $valueType === 'transition' => $this->isValidTransition($value),
            str_starts_with($valueType, 'enum:') => $this->isValidEnum($valueType, $value),
            str_starts_with($valueType, 'float:') => $this->isValidFloat($valueType, $value),
            $valueType === 'numeric' => $this->isValidNumeric($value),
            $valueType === 'dimension' => $this->isValidDimension($value),
            $valueType === 'text' => $this->isValidText($value),
            default => false,
        };
    }

    /**
     * Validate color value against design tokens
     */
    private function isValidColor(string $value): bool
    {
        // Check if it's a design token color
        if (DesignToken::hasColor($value)) {
            return true;
        }

        // Check if it's a valid hex color
        if (preg_match('/^#([A-Fa-f0-9]{6}|[A-Fa-f0-9]{3})$/', $value)) {
            return true;
        }

        // Check if it's rgb/rgba
        if (preg_match('/^rgba?\([0-9]{1,3},\s?[0-9]{1,3},\s?[0-9]{1,3}(?:,\s?[0-9.]{1,4})?\)$/', $value)) {
            return true;
        }

        return false;
    }

    /**
     * Validate typography property value
     */
    private function isValidTypography(string $property, string $value): bool
    {
        // For font-family, allow common font stacks
        if ($property === 'fontFamily') {
            return preg_match('/^[A-Za-z\s\-,"\']+$/', $value) && strlen($value) < 200;
        }

        // For fontSize, check if it's a valid dimension
        if ($property === 'fontSize') {
            return preg_match('/^\d+(?:px|em|rem|%)$/', $value);
        }

        // For fontWeight, allow 100-900
        if ($property === 'fontWeight') {
            return in_array($value, ['100', '200', '300', '400', '500', '600', '700', '800', '900', 'bold', 'normal']);
        }

        // For lineHeight, check format
        if ($property === 'lineHeight') {
            return preg_match('/^(?:\d+(?:\.\d+)?|[0-9]+%|[0-9]+(?:px|em|rem))$/', $value);
        }

        // For letterSpacing, check format
        if ($property === 'letterSpacing') {
            return preg_match('/^[+-]?(?:\d+(?:\.\d+)?)?(?:px|em|rem)$/', $value);
        }

        return false;
    }

    /**
     * Validate spacing value
     */
    private function isValidSpacing(string $value): bool
    {
        // Check if it's a design token spacing
        if (DesignToken::getSpacing($value)) {
            return true;
        }

        // Check if it's a valid dimension
        return $this->isValidDimension($value);
    }

    /**
     * Validate border radius value
     */
    private function isValidBorderRadius(string $value): bool
    {
        // Check if it's a design token
        $tokens = DesignToken::borderRadius();
        if ($tokens->has($value)) {
            return true;
        }

        // Check if it's a valid dimension
        return $this->isValidDimension($value);
    }

    /**
     * Validate box shadow value
     */
    private function isValidShadow(string $value): bool
    {
        // Check if it's a design token
        $tokens = DesignToken::shadows();
        if ($tokens->has($value)) {
            return true;
        }

        // Check if it's 'none'
        if ($value === 'none') {
            return true;
        }

        // Validate shadow syntax (simplified)
        return preg_match('/^[0-9a-fA-F\s\(\),#\.%\-+]+$/', $value);
    }

    /**
     * Validate transition value
     */
    private function isValidTransition(string $value): bool
    {
        // Check if it's a design token
        $tokens = config('design-tokens.transitions', []);
        if (isset($tokens[$value])) {
            return true;
        }

        // Validate custom transition (simplified)
        return preg_match('/^[a-z\-]+\s+\d+ms\s+[a-z\-]+$/', $value);
    }

    /**
     * Validate enum values
     */
    private function isValidEnum(string $typeDefinition, string $value): bool
    {
        $allowedValues = explode(',', substr($typeDefinition, 5));
        $allowedValues = array_map('trim', $allowedValues);

        return in_array($value, $allowedValues);
    }

    /**
     * Validate numeric value (with optional min/max)
     */
    private function isValidNumeric(string $value): bool
    {
        return is_numeric($value) || $value === 'auto';
    }

    /**
     * Validate float with range (e.g., float:0,1)
     */
    private function isValidFloat(string $typeDefinition, string $value): bool
    {
        if (!is_numeric($value)) {
            return false;
        }

        $parts = explode(',', substr($typeDefinition, 6));
        $min = (float) ($parts[0] ?? 0);
        $max = (float) ($parts[1] ?? 1);
        $float = (float) $value;

        return $float >= $min && $float <= $max;
    }

    /**
     * Validate dimension (px, em, rem, %)
     */
    private function isValidDimension(string $value): bool
    {
        if ($value === 'auto') {
            return true;
        }

        return preg_match('/^-?(?:\d+(?:\.\d+)?)?(?:px|em|rem|%|vw|vh|vmin|vmax)$/', $value);
    }

    /**
     * Validate free text (for transforms, etc.)
     */
    private function isValidText(string $value): bool
    {
        return strlen($value) > 0 && strlen($value) <= 500;
    }

    /**
     * Get sanitized styles (only allowed properties with valid values)
     */
    public function sanitize(): array
    {
        $sanitized = [];

        foreach ($this->styles as $property => $value) {
            if ($this->isPropertyAllowed($property) && $this->isValueValid($property, $value)) {
                $sanitized[$property] = $value;
            }
        }

        return $sanitized;
    }

    /**
     * Get list of allowed properties
     */
    public static function allowedProperties(): array
    {
        return self::$allowedProperties;
    }

    /**
     * Check if a property is allowed
     */
    public static function isAllowed(string $property): bool
    {
        return isset(self::$allowedProperties[$property]);
    }

    /**
     * Get all property options for editor
     */
    public static function propertyOptions(): Collection
    {
        return collect(self::$allowedProperties)
            ->mapWithKeys(fn($type, $property) => [
                $property => [
                    'property' => $property,
                    'type' => $type,
                    'label' => ucwords(str_replace(['camelCase', 'Color'], ' ', $property)),
                ]
            ]);
    }
}

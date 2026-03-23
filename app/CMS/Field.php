<?php

namespace App\CMS;

/**
 * Represents a single field definition from config/sections.php
 * 
 * Example:
 * [
 *     'key' => 'heading',
 *     'type' => 'text',
 *     'label' => 'Main Heading',
 *     'placeholder' => 'Enter title...',
 *     'maxLength' => 200,
 *     'required' => true,
 * ]
 */
class Field
{
    public function __construct(
        private array $definition,
    ) {
    }

    /**
     * Get field key (must match config definition)
     */
    public function key(): string
    {
        return $this->definition['key'] ?? throw new \InvalidArgumentException('Field definition must have a key');
    }

    /**
     * Get field type (text, html, image, url, etc.)
     */
    public function type(): string
    {
        return $this->definition['type'] ?? 'text';
    }

    /**
     * Get human-readable label
     */
    public function label(): string
    {
        return $this->definition['label'] ?? ucfirst(str_replace('_', ' ', $this->key()));
    }

    /**
     * Get placeholder text
     */
    public function placeholder(): ?string
    {
        return $this->definition['placeholder'] ?? null;
    }

    /**
     * Get description
     */
    public function description(): ?string
    {
        return $this->definition['description'] ?? null;
    }

    /**
     * Get default value
     */
    public function default(): mixed
    {
        return $this->definition['default'] ?? null;
    }

    /**
     * Check if field is required
     */
    public function isRequired(): bool
    {
        return $this->definition['required'] ?? false;
    }

    /**
     * Check if field is read-only (cannot be changed)
     */
    public function isLocked(): bool
    {
        return $this->definition['locked'] ?? false;
    }

    /**
     * Get max length (for text fields)
     */
    public function maxLength(): ?int
    {
        return $this->definition['maxLength'] ?? null;
    }

    /**
     * Get max size in KB (for image fields)
     */
    public function maxSize(): ?int
    {
        return $this->definition['maxSize'] ?? null;
    }

    /**
     * Get acceptable file types (for image fields)
     */
    public function accept(): ?string
    {
        return $this->definition['accept'] ?? null;
    }

    /**
     * Get select options (for select fields)
     */
    public function options(): ?array
    {
        return $this->definition['options'] ?? null;
    }

    /**
     * Get the raw definition
     */
    public function definition(): array
    {
        return $this->definition;
    }

    /**
     * Check if this field accepts a given type of value
     */
    public function acceptsType(string $type): bool
    {
        return match ($this->type()) {
            'text', 'url', 'number' => in_array($type, ['text', 'string']),
            'html' => in_array($type, ['text', 'html']),
            'image' => in_array($type, ['image']),
            'select', 'boolean' => in_array($type, ['select', 'boolean', 'text']),
            'json' => true,
            default => false,
        };
    }

    /**
     * Validate a value against this field
     */
    public function validate(mixed $value): array
    {
        $errors = [];

        if ($this->isRequired() && empty($value)) {
            $errors[] = "{$this->label()} is required";
        }

        if ($value !== null && $this->maxLength() && strlen((string)$value) > $this->maxLength()) {
            $errors[] = "{$this->label()} cannot exceed {$this->maxLength()} characters";
        }

        if ($this->type() === 'url' && $value && !filter_var($value, FILTER_VALIDATE_URL)) {
            $errors[] = "{$this->label()} must be a valid URL";
        }

        if ($this->type() === 'select' && $value && !in_array($value, $this->options() ?? [])) {
            $errors[] = "{$this->label()} has an invalid option";
        }

        return $errors;
    }
}

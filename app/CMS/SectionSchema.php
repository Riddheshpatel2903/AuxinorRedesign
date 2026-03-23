<?php

namespace App\CMS;

use App\Enums\SectionType;
use Illuminate\Support\Collection;

/**
 * Schema for a section type
 * 
 * Loaded from config/sections.php
 * 
 * Usage:
 * $schema = SectionSchema::for('hero');
 * $fields = $schema->fields();
 * $field = $schema->field('heading');
 */
class SectionSchema
{
    private array $definition;

    private function __construct(
        private string $type,
        array $definition,
    ) {
        $this->definition = $definition;
    }

    /**
     * Load schema for a section type
     */
    public static function for(string $type): self
    {
        $definition = config("sections.{$type}");

        if (!$definition) {
            throw new \InvalidArgumentException("Section type '{$type}' not found in config/sections.php");
        }

        return new self($type, $definition);
    }

    /**
     * Try to load schema, return null if not found
     */
    public static function tryFor(string $type): ?self
    {
        try {
            return self::for($type);
        } catch (\InvalidArgumentException) {
            return null;
        }
    }

    /**
     * Get the section type
     */
    public function type(): string
    {
        return $this->type;
    }

    /**
     * Get the enum version
     */
    public function typeEnum(): SectionType
    {
        return SectionType::from($this->type);
    }

    /**
     * Get the human-readable label
     */
    public function label(): string
    {
        return $this->definition['label'] ?? $this->type;
    }

    /**
     * Get the description
     */
    public function description(): string
    {
        return $this->definition['description'] ?? '';
    }

    /**
     * Get all fields
     */
    public function fields(): Collection
    {
        $fields = $this->definition['fields'] ?? [];
        
        // Handle new simplified format: ['key' => 'type']
        if (!empty($fields) && !isset($fields[0])) {
            return collect($fields)->map(function($type, $key) {
                return new Field([
                    'key' => $key,
                    'type' => is_array($type) ? ($type['type'] ?? 'text') : $type,
                    'label' => is_array($type) ? ($type['label'] ?? null) : null,
                ]);
            });
        }

        return collect($fields)->map(fn($def) => new Field($def));
    }

    /**
     * Get a specific field by key
     */
    public function field(string $key): ?Field
    {
        return $this->fields()
            ->firstWhere('key', $key);
    }

    /**
     * Get field keys
     */
    public function fieldKeys(): array
    {
        return $this->fields()
            ->pluck('key')
            ->all();
    }

    /**
     * Get allowed style properties
     */
    public function styles(): array
    {
        return $this->definition['styles'] ?? [];
    }

    /**
     * Check if a field exists in schema
     */
    public function hasField(string $key): bool
    {
        return $this->field($key) !== null;
    }

    /**
     * Validate content against schema
     * 
     * Returns array of validation errors, empty if valid
     */
    public function validate(array $content): array
    {
        $errors = [];

        foreach ($this->fields() as $field) {
            $value = $content[$field->key()] ?? null;
            $fieldErrors = $field->validate($value);
            $errors = array_merge($errors, $fieldErrors);
        }

        // Skip unknown fields (don't error, just ignore them in the CMS)
        /*
        foreach (array_keys($content) as $key) {
            if (!$this->hasField($key)) {
                $errors[] = "Unknown field: {$key}";
            }
        }
        */

        return $errors;
    }

    /**
     * Check if content is valid
     */
    public function isValid(array $content): bool
    {
        return empty($this->validate($content));
    }

    /**
     * Get raw definition
     */
    public function definition(): array
    {
        return $this->definition;
    }

    /**
     * Get note/help text
     */
    public function note(): ?string
    {
        return $this->definition['note'] ?? null;
    }

    /**
     * Check if this is a catalogue section (read-only items from database)
     */
    public function isCatalogue(): bool
    {
        return str_starts_with($this->type, 'catalogue_');
    }

    /**
     * Get icon identifier
     */
    public function icon(): string
    {
        return $this->definition['icon'] ?? 'layout';
    }

    /**
     * Get fields grouped by category (if defined)
     */
    public function fieldsByGroup(): array
    {
        $grouped = [];

        foreach ($this->fields() as $field) {
            $group = $field->definition()['group'] ?? 'General';
            $grouped[$group][] = $field;
        }

        return $grouped;
    }

    /**
     * Get a template for new content with default values
     */
    public function template(): array
    {
        return $this->fields()
            ->mapWithKeys(fn($field) => [$field->key() => $field->default()])
            ->all();
    }

    /**
     * Get required fields
     */
    public function requiredFields(): Collection
    {
        return $this->fields()
            ->filter(fn($field) => $field->isRequired());
    }

    /**
     * Check if schema requires any fields
     */
    public function hasRequiredFields(): bool
    {
        return $this->requiredFields()->isNotEmpty();
    }
}

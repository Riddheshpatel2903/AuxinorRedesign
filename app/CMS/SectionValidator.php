<?php

namespace App\CMS;

use Illuminate\Validation\Validator;

/**
 * Validates section content against schema definitions
 * 
 * Usage:
 * $validator = new SectionValidator('hero');
 * $validator->validate(['heading' => 'My Title', 'image' => $file]);
 * 
 * Or with Illuminate\Validation\Validator:
 * $validator = Validator::make($data, SectionValidator::rules('hero'));
 */
class SectionValidator
{
    private SectionSchema $schema;

    public function __construct(string $sectionType)
    {
        $this->schema = SectionSchema::for($sectionType);
    }

    /**
     * Generate Laravel validation rules from schema
     */
    public static function rules(string $sectionType): array
    {
        $schema = SectionSchema::for($sectionType);
        $rules = [];

        foreach ($schema->fields() as $field) {
            $rules[$field->key()] = self::fieldRules($field);
        }

        return $rules;
    }

    /**
     * Generate rules for specific field
     */
    private static function fieldRules(Field $field): string
    {
        $rules = [];

        // Required
        if ($field->isRequired()) {
            $rules[] = 'required';
        } else {
            $rules[] = 'nullable';
        }

        // Type-specific rules
        $rules[] = match ($field->type()) {
            'text', 'select' => 'string',
            'html' => 'string',
            'url' => 'url',
            'image' => 'file|image|mimes:jpeg,jpg,png,webp|max:' . ($field->maxSize() ?: 8192),
            'number' => 'numeric',
            'boolean' => 'boolean',
            'json' => 'json',
            default => 'string',
        };

        // Length rules
        if ($field->maxLength()) {
            $rules[] = 'max:' . $field->maxLength();
        }

        // Select options
        if ($field->type() === 'select' && $field->options()) {
            $options = implode(',', $field->options());
            $rules[] = "in:{$options}";
        }

        return implode('|', array_filter($rules));
    }

    /**
     * Validate content against schema
     * 
     * Returns array of validation errors
     */
    public function validate(array $content): array
    {
        return $this->schema->validate($content);
    }

    /**
     * Check if content is valid
     */
    public function isValid(array $content): bool
    {
        return $this->schema->isValid($content);
    }

    /**
     * Get the schema
     */
    public function schema(): SectionSchema
    {
        return $this->schema;
    }

    /**
     * Get validation error messages in human-readable format
     */
    public function getErrorMessages(array $content): array
    {
        $errors = $this->validate($content);

        if (empty($errors)) {
            return [];
        }

        return array_map(fn($error) => "Validation error: {$error}", $errors);
    }

    /**
     * Throw exception if invalid
     */
    public function validateOrFail(array $content): void
    {
        $errors = $this->validate($content);

        if (!empty($errors)) {
            throw new \InvalidArgumentException('Schema validation failed: ' . implode(', ', $errors));
        }
    }
}

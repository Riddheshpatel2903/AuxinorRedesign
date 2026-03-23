<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Create the new section_contents table.
     * 
     * This replaces the messy page_elements table with a clean key-value structure.
     * 
     * Schema:
     * - section_id: which section this content belongs to
     * - key: field key from config/sections.php (e.g., 'heading', 'description')
     * - type: field type (text, html, image, url)
     * - value: the actual content (TEXT for large content)
     * - meta: JSON for additional metadata (alt text, image size, etc.)
     */
    public function up(): void
    {
        Schema::create('section_contents', function (Blueprint $table) {
            $table->id();
            
            // Reference to section
            $table->foreignId('section_id')
                ->constrained('page_sections')
                ->cascadeOnDelete();
            
            // Field key (must match schema definition)
            $table->string('key')->index(); // heading, description, button_text, etc.
            
            // Field type (for validation & rendering hints)
            $table->enum('type', [
                'text',      // single-line text
                'html',      // rich HTML content
                'image',     // image URL + local path
                'url',       // hyperlink
                'select',    // option value
                'number',    // numeric value
                'boolean',   // true/false
                'json',      // structured data
            ])->default('text');
            
            // The actual content (use TEXT for large content)
            $table->text('value')->nullable();
            
            // Additional metadata (image alt, file size, etc.)
            $table->json('meta')->nullable();
            
            // Ordering (if needed for repeating fields like features)
            $table->integer('sort_order')->default(0);
            
            $table->timestamps();
            
            // Ensure unique key per section (one value per field)
            $table->unique(['section_id', 'key']);
            
            // Index for fast lookups
            $table->index(['section_id', 'type']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('section_contents');
    }
};

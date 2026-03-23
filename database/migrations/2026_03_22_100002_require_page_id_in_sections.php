<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Prepare page_sections for deprecation of old columns.
     * 
     * This migration:
     * - Makes page_id required (all sections must have a page)
     * - Marks old columns for future removal (section_key, section_label, content)
     * 
     * Run this AFTER section_contents data migration is complete.
     */
    public function up(): void
    {
        Schema::table('page_sections', function (Blueprint $table) {
            // Make page_id not nullable (all sections must belong to a page)
            $table->foreignId('page_id')
                ->nullable(false)
                ->change();
        });

        // Note: We'll keep these columns for backwards compatibility temporarily:
        // - section_key (now just for reference, section_type is the source of truth)
        // - section_label (informational, pulled from config/sections.php)
        // - content (will be completely replaced by section_contents table)
        // 
        // These will be removed in a future cleanup migration once all code
        // has been updated to use section_type and section_contents.
    }

    public function down(): void
    {
        Schema::table('page_sections', function (Blueprint $table) {
            $table->foreignId('page_id')
                ->nullable()
                ->change();
        });
    }
};

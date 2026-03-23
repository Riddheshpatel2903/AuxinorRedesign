<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('page_sections', function (Blueprint $table) {
            // Add section type — maps to config/sections.php keys
            if (!Schema::hasColumn('page_sections', 'section_type')) {
                $table->string('section_type')->default('content')->after('page_id');
            }
        });

        // Note: page_slug and section_label will be deprecated in next migrations
        // We keep them for now to maintain backwards compatibility during transition
    }

    public function down(): void
    {
        Schema::table('page_sections', function (Blueprint $table) {
            $table->dropColumn('section_type');
        });
    }
};

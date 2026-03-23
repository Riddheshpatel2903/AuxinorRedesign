<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('page_elements', function (Blueprint $table) {
            // Change element_type to support more types (remove enum restriction if any)
            $table->string('element_type')->change(); 
            
            // Add new columns
            $table->string('image_path')->nullable()->after('image_url'); // local storage path
            $table->string('image_alt')->nullable()->after('image_path');
            $table->string('button_text')->nullable()->after('href');
            $table->string('button_style')->nullable()->after('button_text'); // 'primary', 'secondary', 'ghost'
            $table->boolean('is_visible')->default(true)->after('sort_order');
            
            // Ensure unique constraint for elements within a section
            $table->unique(['section_id', 'element_key']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('page_elements', function (Blueprint $table) {
            $table->dropUnique(['section_id', 'element_key']);
            $table->dropColumn(['image_path', 'image_alt', 'button_text', 'button_style', 'is_visible']);
        });
    }
};

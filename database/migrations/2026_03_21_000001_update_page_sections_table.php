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
        Schema::table('page_sections', function (Blueprint $table) {
            $table->string('bg_image')->nullable()->after('styles');     // stored path: 'editor/abc.jpg'
            $table->decimal('bg_overlay', 3, 2)->default(0.00)->after('bg_image'); // 0.00 to 1.00
            $table->string('bg_color')->nullable()->after('bg_overlay'); // hex color fallback
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('page_sections', function (Blueprint $table) {
            $table->dropColumn(['bg_image', 'bg_overlay', 'bg_color']);
        });
    }
};

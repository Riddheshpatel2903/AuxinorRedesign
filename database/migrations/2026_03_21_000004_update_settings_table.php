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
        Schema::table('settings', function (Blueprint $table) {
            $table->string('type')->default('text')->after('group');
            // type: 'text', 'textarea', 'image', 'url', 'email', 'phone', 'number', 'boolean', 'html'
            $table->string('label')->nullable()->after('type');         // human-readable label for admin UI
            $table->text('description')->nullable()->after('label');    // hint text shown in admin
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('settings', function (Blueprint $table) {
            $table->dropColumn(['type', 'label', 'description']);
        });
    }
};

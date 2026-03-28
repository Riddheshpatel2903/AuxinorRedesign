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
        Schema::create('page_content_versions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('page_content_id')->constrained('page_contents')->cascadeOnDelete();
            $table->longText('components_json')->nullable();
            $table->longText('styles_json')->nullable();
            $table->longText('html_output')->nullable();
            $table->string('saved_by')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('page_content_versions');
    }
};

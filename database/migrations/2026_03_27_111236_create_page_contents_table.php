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
        Schema::create('page_contents', function (Blueprint $table) {
            $table->id();
            $table->string('page_slug')->unique()->comment('e.g. home, about, services');
            $table->string('page_title')->default('Untitled Page');
            $table->longText('components_json')->nullable()->comment('GrapesJS component tree JSON');
            $table->longText('styles_json')->nullable()->comment('GrapesJS CSS JSON');
            $table->longText('html_output')->nullable()->comment('Rendered HTML for public display');
            $table->string('last_edited_by')->nullable();
            $table->timestamp('last_edited_at')->nullable();
            $table->boolean('is_published')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('page_contents');
    }
};

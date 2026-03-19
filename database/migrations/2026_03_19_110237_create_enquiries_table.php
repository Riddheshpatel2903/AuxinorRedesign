<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('enquiries', function (Blueprint $table) {
            $table->id();
            $table->string('company_name')->nullable();
            $table->string('contact_person');
            $table->string('email');
            $table->string('phone')->nullable();
            $table->string('product_category')->nullable();
            $table->string('product_name')->nullable();
            $table->string('quantity')->nullable();
            $table->text('message');
            $table->enum('status', ['new', 'read', 'replied'])->default('new');
            $table->string('source')->nullable();
            $table->text('admin_notes')->nullable();
            $table->timestamp('replied_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('enquiries');
    }
};

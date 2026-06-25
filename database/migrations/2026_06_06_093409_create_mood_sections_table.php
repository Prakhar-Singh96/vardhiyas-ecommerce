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
        Schema::create('mood_sections', function (Blueprint $table) {
            $table->id();
            $table->string('admin_label')->nullable();  // Admin ke liye sirf
            $table->string('image');                    // Card image
            $table->string('label_top')->nullable();    // "MEN EVERYDAY"
            $table->string('label_main')->nullable();   // "BASICS"
            $table->foreignId('category_id')            // Kis category ka page
                ->nullable()
                ->constrained()
                ->nullOnDelete();
            $table->string('custom_url')->nullable();   // Ya custom URL
            $table->integer('sort_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mood_sections');
    }
};

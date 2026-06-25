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
        Schema::create('filter_values', function (Blueprint $table) {
            $table->id();
            $table->foreignId('filter_type_id')->constrained()->cascadeOnDelete();
            $table->string('value');          // "S", "M", "L", "XL", "#FF0000"
            $table->string('label');          // Display: "Small", "Medium", "Red"
            $table->string('meta')->nullable(); // Extra data (hex code for color)
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
        Schema::dropIfExists('filter_values');
    }
};

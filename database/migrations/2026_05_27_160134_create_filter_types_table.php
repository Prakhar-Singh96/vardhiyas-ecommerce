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
        Schema::create('filter_types', function (Blueprint $table) {
            $table->id();
            $table->string('name');           // "Size", "Color", "Stock"
            $table->string('type');           // 'select', 'color', 'boolean', 'range'
            $table->string('display_name');   // Frontend pe dikhne wala naam
            $table->boolean('is_active')->default(true);
            $table->integer('sort_order')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('filter_types');
    }
};

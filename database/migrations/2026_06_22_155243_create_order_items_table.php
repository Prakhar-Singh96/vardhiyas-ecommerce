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
        Schema::create('order_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained()->cascadeOnDelete();
            $table->foreignId('product_id')->nullable()->constrained()->nullOnDelete();
            $table->string('product_name');
            $table->string('image_path')->nullable();
            $table->foreignId('size_id')->nullable()->constrained('filter_values')->nullOnDelete();
            $table->foreignId('color_id')->nullable()->constrained('filter_values')->nullOnDelete();
            $table->string('size_label')->nullable();
            $table->string('color_label')->nullable();
            $table->decimal('price', 10, 2);
            $table->integer('qty');
            $table->decimal('subtotal', 10, 2);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('order_items');
    }
};

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
        Schema::create('payment_settings', function (Blueprint $table) {
            $table->id();
            $table->enum('mode', ['test', 'live'])->default('test');
            $table->string('test_key')->nullable();
            $table->string('test_secret')->nullable();
            $table->string('live_key')->nullable();
            $table->string('live_secret')->nullable();
            $table->boolean('razorpay_enabled')->default(false);
            $table->boolean('cod_enabled')->default(true);
            $table->decimal('shipping_charge', 10, 2)->default(49);
            $table->decimal('free_shipping_above', 10, 2)->default(999);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payment_settings');
    }
};

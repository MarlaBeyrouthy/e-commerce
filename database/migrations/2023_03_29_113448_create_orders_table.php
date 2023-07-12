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
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
           // $table->unsignedBigInteger('cart_id');
            $table->unsignedBigInteger('user_id');
            $table->foreignId('place_id')->nullable();
            $table->foreignId('city_id')->nullable();
            $table->string('shipping_address');
            $table->decimal('Total_price',8,2)->default(0);
            $table->timestamp('order_date')->default(now());
            $table->boolean('checked')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};

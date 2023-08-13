<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('description')->nullable();
            $table->decimal('price',8,2);
            $table->string('category');
            $table->string('gender');
            $table->string('brand_name')->nullable();
            $table->foreignId('user_id');
            $table->string('material')->nullable();
            $table->string('photo')->nullable();
            $table->json('sizes');
            $table->integer('quantity');
            $table->integer('sale')->default(0);
            $table->boolean('in_stock')->default(true);
            $table->decimal('average_rating', 3, 2)->default(0);
            $table->timestamps();

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
        /*Schema::dropIfExists('product__colors');
        Schema::dropIfExists('products');
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        Schema::dropIfExists('colors');
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');*/
    }
};

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
            $table->string('product')->nullable();
            $table->text('description')->nullable();
            $table->string('brand_name')->nullable();
            $table->decimal('price',8,2)->nullable();
            $table->string('photo_product')->nullable();
            $table->string('material')->nullable();
            $table->string('size')->nullable();



            $table->unsignedBigInteger('user_id')->nullable();
            $table->unsignedBigInteger('seller_id')->nullable();
            $table->unsignedBigInteger('subcategory_id')->nullable();
            $table->unsignedBigInteger('color_id')->nullable();
           // $table->unsignedBigInteger('size_id')->nullable();
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

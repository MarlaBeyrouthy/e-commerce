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
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->unique();
            $table->foreignId('permission_id')->default(1);
            $table->string('password');
            $table->foreignId('place_id')->nullable();
            $table->foreignId('city_id')->nullable();
            $table->string('address')->nullable();
            $table->string('phone')->nullable();
            $table->string('contact')->nullable();
            $table->string('photo')->nullable();
            $table->string('photo_profile')->nullable();
            $table->text('bio')->nullable();
           // $table->rememberToken();
            $table->timestamps();

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};

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
           // $table->boolean('verified')->default(false);
          //  $table->string('verification_code',6)->nullable()->unique();
           // $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->string('address');
            $table->string('phone');
            $table->string('contact');

            $table->string('photo')->nullable();
            $table->string('photo_profile')->nullable();
            $table->text('bio')->nullable();
           // $table->rememberToken();
            $table->timestamps();
           // $table->timestamp('created_at')->nullable();
            //$table->timestamp('expires_at')->default(now()); // Set a default value for expires_at column
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

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
        Schema::create('social_profiles', function (Blueprint $table) {
            $table->id();
            $table->uuid();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('category_id')->constrained('categories')->cascadeOnDelete();
            $table->string('template');
            $table->string('username');
            $table->string('name')->nullable();
            $table->mediumText('bio')->nullable();
            $table->string('email')->nullable();
            $table->string('phone_number')->nullable();
            $table->string('avatar')->nullable();
            $table->string('cover')->nullable();
            $table->text('customization')->nullable();
            $table->longText('products')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('social_profiles');
    }
};

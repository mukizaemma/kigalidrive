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
        if (Schema::hasTable('property_reviews')) {
            return; // Table already exists, skip migration
        }

        Schema::create('property_reviews', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('hotel_id')->nullable(); // For backward compatibility
            $table->unsignedBigInteger('property_id')->nullable(); // For new Property model
            $table->enum('reviewable_type', ['hotel', 'property'])->default('hotel');
            $table->integer('rating')->unsigned(); // 1-5 stars
            $table->text('comment')->nullable();
            $table->string('title')->nullable();
            $table->boolean('is_approved')->default(false);
            $table->boolean('is_featured')->default(false);
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('hotel_id')->references('id')->on('hotels')->onDelete('cascade');
            // Note: property_id foreign key will be added in a separate migration if properties table exists
            // For now, we'll just add the index
            
            $table->index(['hotel_id', 'reviewable_type']);
            $table->index(['property_id', 'reviewable_type']);
        });

        // Add property_id foreign key if properties table exists
        if (Schema::hasTable('properties')) {
            Schema::table('property_reviews', function (Blueprint $table) {
                $table->foreign('property_id')->references('id')->on('properties')->onDelete('cascade');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('property_reviews');
    }
};

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * 
     * Unified properties table that will eventually replace hotels table
     * This maintains backward compatibility by keeping hotels table intact
     */
    public function up(): void
    {
        Schema::create('properties', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('owner_id'); // Property owner (user)
            $table->unsignedBigInteger('category_id')->nullable(); // Destination/category
            $table->unsignedBigInteger('program_id')->nullable(); // Service program
            $table->unsignedBigInteger('partner_id')->nullable(); // Partner reference
            
            // Basic Information
            $table->string('name');
            $table->string('slug')->unique();
            $table->enum('property_type', ['hotel', 'apartment'])->default('hotel');
            $table->string('stars')->nullable(); // For hotels
            $table->text('description')->nullable();
            
            // Location
            $table->string('address')->nullable();
            $table->string('city')->nullable();
            $table->string('location')->nullable();
            $table->double('latitude')->nullable();
            $table->double('longitude')->nullable();
            
            // Contact
            $table->string('phone')->nullable();
            $table->string('email')->nullable();
            $table->string('website')->nullable();
            
            // Media
            $table->string('featured_image')->nullable(); // Main image
            
            // Status
            $table->enum('status', ['Active', 'Inactive', 'Pending'])->default('Pending');
            $table->boolean('is_featured')->default(false);
            $table->boolean('is_verified')->default(false);
            
            // Metadata
            $table->json('meta_data')->nullable(); // For flexible additional data
            
            $table->softDeletes();
            $table->timestamps();
            
            // Foreign Keys
            $table->foreign('owner_id')->references('id')->on('users')->onDelete('cascade');
            if (Schema::hasTable('categories')) {
                $table->foreign('category_id')->references('id')->on('categories')->onDelete('set null');
            }
            if (Schema::hasTable('programs')) {
                $table->foreign('program_id')->references('id')->on('programs')->onDelete('set null');
            }
            if (Schema::hasTable('partners')) {
                $table->foreign('partner_id')->references('id')->on('partners')->onDelete('set null');
            }
            
            // Indexes
            $table->index('property_type');
            $table->index('status');
            $table->index('is_featured');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('properties');
    }
};


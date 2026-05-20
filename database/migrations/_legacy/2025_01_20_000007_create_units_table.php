<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * 
     * Unified units table (replaces hotel_rooms)
     * Supports both hotel rooms and apartment units
     */
    public function up(): void
    {
        Schema::create('units', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('property_id'); // Hotel or Apartment
            $table->unsignedBigInteger('unit_type_id')->nullable(); // Room type classification
            $table->unsignedBigInteger('added_by'); // User who added the unit
            
            // Basic Information
            $table->string('name')->nullable(); // Optional unit name
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            
            // Capacity
            $table->integer('max_occupancy')->default(1);
            $table->integer('bedrooms')->default(0);
            $table->integer('bathrooms')->default(1);
            $table->integer('beds')->default(1);
            $table->integer('size_sqm')->nullable(); // Size in square meters
            
            // Inventory
            $table->integer('total_units')->default(1); // Total number of this unit type
            $table->integer('available_units')->default(1); // Currently available
            
            // Pricing (base price, can be overridden by unit_pricing)
            $table->decimal('base_price_per_night', 10, 2)->nullable();
            $table->decimal('base_price_per_month', 10, 2)->nullable(); // For apartments
            
            // Media
            $table->string('featured_image')->nullable();
            
            // Status
            $table->enum('status', ['Available', 'Unavailable', 'Maintenance'])->default('Available');
            $table->boolean('is_active')->default(true);
            
            // Metadata
            $table->json('meta_data')->nullable(); // For flexible additional data
            
            $table->softDeletes();
            $table->timestamps();
            
            // Foreign Keys
            $table->foreign('property_id')
                  ->references('id')
                  ->on('properties')
                  ->onDelete('cascade');
                  
            if (Schema::hasTable('unit_types')) {
                $table->foreign('unit_type_id')
                      ->references('id')
                      ->on('unit_types')
                      ->onDelete('set null');
            }
                  
            $table->foreign('added_by')
                  ->references('id')
                  ->on('users')
                  ->onDelete('cascade');
                  
            // Indexes
            $table->index('property_id');
            $table->index('status');
            $table->index('is_active');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('units');
    }
};


<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * 
     * Dynamic pricing table for units
     * Supports seasonal pricing, weekend rates, special offers, etc.
     */
    public function up(): void
    {
        Schema::create('unit_pricing', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('unit_id');
            
            // Pricing Details
            $table->decimal('price_per_night', 10, 2);
            $table->decimal('price_per_month', 10, 2)->nullable(); // For apartments
            $table->decimal('weekend_price', 10, 2)->nullable();
            $table->decimal('holiday_price', 10, 2)->nullable();
            
            // Date Range
            $table->date('start_date');
            $table->date('end_date')->nullable(); // null means ongoing
            
            // Conditions
            $table->integer('min_nights')->default(1);
            $table->integer('max_nights')->nullable();
            $table->integer('min_guests')->default(1);
            $table->integer('max_guests')->nullable();
            
            // Status
            $table->boolean('is_active')->default(true);
            $table->string('pricing_type')->default('standard'); // standard, seasonal, promotional, etc.
            
            $table->text('notes')->nullable();
            $table->timestamps();
            $table->softDeletes();
            
            $table->foreign('unit_id')
                  ->references('id')
                  ->on('units')
                  ->onDelete('cascade');
                  
            $table->index('unit_id');
            $table->index(['start_date', 'end_date']);
            $table->index('is_active');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('unit_pricing');
    }
};











<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * 
     * Pivot table for property-facility many-to-many relationship
     */
    public function up(): void
    {
        Schema::create('property_facilities', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('property_id');
            $table->unsignedBigInteger('facility_id'); // References amenities table (to be renamed to facilities)
            $table->timestamps();
            
            $table->foreign('property_id')
                  ->references('id')
                  ->on('properties')
                  ->onDelete('cascade');
                  
            $table->foreign('facility_id')
                  ->references('id')
                  ->on('amenities')
                  ->onDelete('cascade');
                  
            // Ensure unique combination
            $table->unique(['property_id', 'facility_id']);
            
            $table->index('property_id');
            $table->index('facility_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('property_facilities');
    }
};











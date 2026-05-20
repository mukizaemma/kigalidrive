<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * 
     * Pivot table for unit-facility many-to-many relationship
     * Replaces JSON amenities in hotel_rooms
     */
    public function up(): void
    {
        Schema::create('unit_facilities', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('unit_id');
            $table->unsignedBigInteger('facility_id'); // References amenities table
            $table->timestamps();
            
            $table->foreign('unit_id')
                  ->references('id')
                  ->on('units')
                  ->onDelete('cascade');
                  
            $table->foreign('facility_id')
                  ->references('id')
                  ->on('amenities')
                  ->onDelete('cascade');
                  
            // Ensure unique combination
            $table->unique(['unit_id', 'facility_id']);
            
            $table->index('unit_id');
            $table->index('facility_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('unit_facilities');
    }
};











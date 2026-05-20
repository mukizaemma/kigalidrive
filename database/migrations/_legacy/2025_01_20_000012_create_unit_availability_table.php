<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * 
     * Availability calendar for units
     * Tracks which dates are available, booked, blocked, etc.
     */
    public function up(): void
    {
        if (Schema::hasTable('unit_availability')) {
            return; // Table already exists, skip migration
        }

        Schema::create('unit_availability', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('unit_id');
            $table->date('date');
            $table->integer('available_units')->default(1); // How many units available on this date
            $table->enum('status', ['available', 'booked', 'blocked', 'maintenance'])->default('available');
            $table->decimal('price_override', 10, 2)->nullable(); // Override base price for this date
            $table->text('notes')->nullable();
            $table->timestamps();
            
            $table->foreign('unit_id')
                  ->references('id')
                  ->on('units')
                  ->onDelete('cascade');
                  
            // Ensure one record per unit per date
            $table->unique(['unit_id', 'date']);
            
            $table->index('unit_id');
            $table->index('date');
            $table->index('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('unit_availability');
    }
};


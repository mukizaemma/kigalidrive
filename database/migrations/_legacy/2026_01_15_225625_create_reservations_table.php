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
        Schema::create('reservations', function (Blueprint $table) {
            $table->id();
            
            // Service type: 'enquiry', 'hotel_booking', 'tour_booking', 'question'
            $table->enum('service_type', ['enquiry', 'hotel_booking', 'tour_booking', 'question'])->default('enquiry');
            
            // Contact information
            $table->string('names');
            $table->string('phone');
            $table->string('email');
            $table->text('message');
            $table->enum('status', ['pending', 'confirmed', 'cancelled'])->default('pending');
            
            // Hotel booking fields
            $table->unsignedBigInteger('room_id')->nullable();
            $table->unsignedBigInteger('facility_id')->nullable();
            $table->integer('nights')->nullable();
            $table->integer('guests')->nullable();
            $table->date('checkin_date')->nullable();
            $table->date('checkout_date')->nullable();
            
            // Tour booking fields
            $table->unsignedBigInteger('tour_id')->nullable();
            $table->date('tour_date')->nullable();
            $table->integer('tour_people')->nullable();
            
            $table->timestamps();
            
            // Foreign keys
            if (Schema::hasTable('hotel_rooms')) {
                $table->foreign('room_id')->references('id')->on('hotel_rooms')->onDelete('cascade');
            }
            
            if (Schema::hasTable('facilities')) {
                $table->foreign('facility_id')->references('id')->on('facilities')->onDelete('cascade');
            }
            
            if (Schema::hasTable('trips')) {
                $table->foreign('tour_id')->references('id')->on('trips')->onDelete('cascade');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reservations');
    }
};

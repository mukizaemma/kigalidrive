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
        Schema::create('hotel_bookings', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('hotel_id')->nullable(); // Keep for backward compatibility
            $table->unsignedBigInteger('property_id')->nullable(); // New unified property reference
            $table->unsignedBigInteger('room_id')->nullable(); // Keep for backward compatibility
            $table->unsignedBigInteger('unit_id')->nullable(); // New unified unit reference
            $table->date('check_in');
            $table->date('check_out');
            $table->integer('guests_count')->default(1);
            $table->decimal('total_amount', 10, 2);
            $table->longtext('description')->nullable();
            $table->enum('payment_status', ['pending', 'paid', 'refunded'])->default('pending');
            $table->enum('booking_status', ['pending', 'confirmed', 'cancelled', 'availability_requested'])->default('pending');
            $table->string('reference_number')->unique();
            $table->softDeletes();
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            if (Schema::hasTable('hotels')) {
                $table->foreign('hotel_id')->references('id')->on('hotels')->onDelete('cascade');
            }
            if (Schema::hasTable('properties')) {
                $table->foreign('property_id')->references('id')->on('properties')->onDelete('cascade');
            }
            if (Schema::hasTable('hotel_rooms')) {
                $table->foreign('room_id')->references('id')->on('hotel_rooms')->onDelete('cascade');
            }
            if (Schema::hasTable('units')) {
                $table->foreign('unit_id')->references('id')->on('units')->onDelete('cascade');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('hotel_bookings');
    }
};

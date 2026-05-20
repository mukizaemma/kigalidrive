<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('booking_extras', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('hotel_booking_id');
            $table->unsignedBigInteger('unit_extra_charge_id');
            $table->decimal('price_snapshot', 10, 2); // price at time of booking
            $table->string('charge_name')->nullable(); // snapshot of name for display
            $table->timestamps();

            $table->foreign('hotel_booking_id')->references('id')->on('hotel_bookings')->onDelete('cascade');
            $table->foreign('unit_extra_charge_id')->references('id')->on('unit_extra_charges')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('booking_extras');
    }
};

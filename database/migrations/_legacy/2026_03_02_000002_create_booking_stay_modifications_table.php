<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('booking_stay_modifications', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('hotel_booking_id');
            $table->unsignedBigInteger('requested_by');
            $table->date('actual_check_out');
            $table->text('reason');
            $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending');
            $table->unsignedBigInteger('reviewed_by')->nullable();
            $table->timestamp('reviewed_at')->nullable();
            $table->text('admin_notes')->nullable();
            $table->decimal('adjusted_total_amount', 10, 2)->nullable();
            $table->decimal('adjusted_commission_amount', 10, 2)->nullable();
            $table->timestamps();

            $table->foreign('hotel_booking_id')->references('id')->on('hotel_bookings')->onDelete('cascade');
            $table->foreign('requested_by')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('reviewed_by')->references('id')->on('users')->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('booking_stay_modifications');
    }
};

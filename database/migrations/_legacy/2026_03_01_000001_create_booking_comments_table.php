<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('booking_comments', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('hotel_booking_id');
            $table->unsignedBigInteger('user_id');
            $table->string('author_type', 20); // 'staff' | 'owner'
            $table->text('comment');
            $table->timestamps();

            $table->foreign('hotel_booking_id')->references('id')->on('hotel_bookings')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->index(['hotel_booking_id', 'created_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('booking_comments');
    }
};

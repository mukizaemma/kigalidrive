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
        Schema::create('trip_bookings', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('trip_id');
            $table->decimal('total_amount', 10, 2);
            $table->string('phone')->nullable();
            $table->string('email')->nullable();
            $table->string('channel')->nullable();
            $table->date('arrival_date')->nullable();
            $table->date('date_in')->nullable();
            $table->date('date_out')->nullable();
            $table->integer('guests')->nullable();
            $table->integer('nights')->nullable();
            $table->longText('message')->nullable();
            $table->enum('booking_status', ['pending', 'confirmed', 'cancelled'])->default('pending');
            $table->enum('payment_status', ['pending', 'paid', 'refunded'])->default('pending');
            $table->softDeletes();
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('trip_id')->references('id')->on('trips')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('trip_bookings');
    }
};

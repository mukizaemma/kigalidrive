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
        Schema::create('luggage_bookings', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('drop_location_id')->nullable();;
            $table->unsignedBigInteger('pickup_location_id')->nullable();;
            $table->date('drop_date')->nullable();;
            $table->date('pickup_date')->nullable();;
            $table->text('description_of_bags')->nullable();
            $table->integer('number_of_bags')->nullable();;
            $table->decimal('amount', 10, 2)->nullable();;
            $table->string('image')->nullable();
            $table->enum('payment_status', ['pending', 'paid', 'refunded'])->default('pending');
            $table->enum('booking_status', ['pending', 'confirmed', 'cancelled'])->default('pending');
            $table->string('reference_number')->unique();
            $table->softDeletes();
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('drop_location_id')->references('id')->on('luggage_locations')->onDelete('cascade');
            $table->foreign('pickup_location_id')->references('id')->on('luggage_locations')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('luggage_bookings');
    }
};

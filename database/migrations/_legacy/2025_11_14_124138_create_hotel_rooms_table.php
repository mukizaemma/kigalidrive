<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{

    public function up(): void
    {
        Schema::create('hotel_rooms', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('hotel_id');
            $table->unsignedBigInteger('added_by');
            $table->string('room_type');
            $table->string('image');
            $table->string('slug');
            $table->integer('max_occupancy');
            $table->decimal('price_per_night', 10, 2);
            $table->integer('total_rooms');
            $table->integer('available_rooms');
            $table->longtext('description')->nullable();
            $table->json('amenities')->nullable();
            $table->enum('status', ['Available', 'Unavailable'])->default('Available');
            $table->softDeletes();
            $table->timestamps();

            $table->foreign('hotel_id')->references('id')->on('hotels')->onDelete('cascade');
            $table->foreign('added_by')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('hotel_rooms');
    }
};

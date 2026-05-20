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
        Schema::create('trip_destination_images', function (Blueprint $table) {
            $table->id();
            $table->string('image');
            $table->string('caption')->nullable();
            $table->unsignedBigInteger('trip_destination_id');
            $table->unsignedBigInteger('added_by');
            $table->foreign('trip_destination_id')->references('id')->on('trip_destinations')->onDelete('cascade');
            $table->foreign('added_by')->references('id')->on('users')->onDelete('cascade');
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('trip_destination_images');
    }
};

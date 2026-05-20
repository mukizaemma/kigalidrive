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
        Schema::create('carimages', function (Blueprint $table) {
            $table->id();
            $table->string('image');
            $table->string('caption')->nullable();

            $table->unsignedBigInteger('added_by');
            $table->unsignedBigInteger('car_id')->nullable();
            $table->foreign("added_by")->references("id")->on("users")->onDelete("cascade");
            $table->foreign("car_id")->references("id")->on("cars")->onDelete("cascade");
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('carimages');
    }
};

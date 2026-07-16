<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('car_details')) {
            Schema::create('car_details', function (Blueprint $table) {
                $table->id();
                $table->string('name');
                $table->string('slug')->unique();
                $table->string('icon')->nullable();
                $table->unsignedInteger('sort_order')->default(0);
                $table->boolean('is_active')->default(true);
                $table->timestamps();
            });
        }

        if (! Schema::hasTable('car_car_detail')) {
            Schema::create('car_car_detail', function (Blueprint $table) {
                $table->id();
                $table->foreignId('car_id')->constrained('cars')->cascadeOnDelete();
                $table->foreignId('car_detail_id')->constrained('car_details')->cascadeOnDelete();
                $table->timestamps();

                $table->unique(['car_id', 'car_detail_id']);
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('car_car_detail');
        Schema::dropIfExists('car_details');
    }
};

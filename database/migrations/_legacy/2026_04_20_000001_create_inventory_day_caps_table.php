<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('inventory_day_caps', function (Blueprint $table) {
            $table->id();
            $table->morphs('bookable'); // HotelRoom, Unit
            $table->date('date');
            $table->unsignedSmallInteger('max_remaining');
            $table->timestamps();

            $table->unique(['bookable_type', 'bookable_id', 'date'], 'inventory_day_caps_bookable_date_unique');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('inventory_day_caps');
    }
};

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
        Schema::table('hotel_bookings', function (Blueprint $table) {
            // Allow guest bookings by making user_id nullable
            if (Schema::hasColumn('hotel_bookings', 'user_id')) {
                $table->unsignedBigInteger('user_id')->nullable()->change();
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('hotel_bookings', function (Blueprint $table) {
            // NOTE: We won't force user_id back to NOT NULL automatically to avoid breaking data.
            // If you need to revert, handle NULL user_id rows first, then change the column manually.
        });
    }
};


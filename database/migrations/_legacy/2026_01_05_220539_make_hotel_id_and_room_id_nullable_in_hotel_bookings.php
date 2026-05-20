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
            // Make hotel_id nullable if it exists
            if (Schema::hasColumn('hotel_bookings', 'hotel_id')) {
                $table->unsignedBigInteger('hotel_id')->nullable()->change();
            }
            
            // Make room_id nullable if it exists
            if (Schema::hasColumn('hotel_bookings', 'room_id')) {
                $table->unsignedBigInteger('room_id')->nullable()->change();
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('hotel_bookings', function (Blueprint $table) {
            // Note: We can't easily reverse this without potentially breaking existing data
            // If you need to reverse, you'll need to handle NULL values first
        });
    }
};

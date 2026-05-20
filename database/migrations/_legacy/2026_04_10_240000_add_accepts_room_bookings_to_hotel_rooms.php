<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('hotel_rooms')) {
            return;
        }
        Schema::table('hotel_rooms', function (Blueprint $table) {
            if (! Schema::hasColumn('hotel_rooms', 'accepts_room_bookings')) {
                $table->boolean('accepts_room_bookings')->default(true)->after('status');
            }
        });
    }

    public function down(): void
    {
        if (! Schema::hasTable('hotel_rooms')) {
            return;
        }
        Schema::table('hotel_rooms', function (Blueprint $table) {
            if (Schema::hasColumn('hotel_rooms', 'accepts_room_bookings')) {
                $table->dropColumn('accepts_room_bookings');
            }
        });
    }
};

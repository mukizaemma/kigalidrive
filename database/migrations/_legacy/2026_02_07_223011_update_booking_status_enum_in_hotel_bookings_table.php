<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // For MySQL, we need to modify the enum using raw SQL
        // First check if the column exists and is an enum
        if (Schema::hasColumn('hotel_bookings', 'booking_status')) {
            // Get the database connection
            $connection = DB::connection();
            $driver = $connection->getDriverName();
            
            if ($driver === 'mysql') {
                // MySQL: Modify enum using ALTER TABLE
                DB::statement("ALTER TABLE hotel_bookings MODIFY COLUMN booking_status ENUM('pending', 'confirmed', 'cancelled', 'availability_requested') DEFAULT 'pending'");
            } else {
                // For other databases, change to string
                Schema::table('hotel_bookings', function (Blueprint $table) {
                    $table->string('booking_status')->default('pending')->change();
                });
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasColumn('hotel_bookings', 'booking_status')) {
            $connection = DB::connection();
            $driver = $connection->getDriverName();
            
            if ($driver === 'mysql') {
                // Revert to original enum values
                DB::statement("ALTER TABLE hotel_bookings MODIFY COLUMN booking_status ENUM('pending', 'confirmed', 'cancelled') DEFAULT 'pending'");
            } else {
                // For other databases, keep as string (can't easily revert)
                Schema::table('hotel_bookings', function (Blueprint $table) {
                    $table->string('booking_status')->default('pending')->change();
                });
            }
        }
    }
};

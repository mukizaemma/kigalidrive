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
            // Add property_id if it doesn't exist
            if (!Schema::hasColumn('hotel_bookings', 'property_id')) {
                $table->unsignedBigInteger('property_id')->nullable()->after('hotel_id');
                if (Schema::hasTable('properties')) {
                    $table->foreign('property_id')->references('id')->on('properties')->onDelete('cascade');
                }
            }
            
            // Add unit_id if it doesn't exist
            if (!Schema::hasColumn('hotel_bookings', 'unit_id')) {
                $table->unsignedBigInteger('unit_id')->nullable()->after('room_id');
                if (Schema::hasTable('units')) {
                    $table->foreign('unit_id')->references('id')->on('units')->onDelete('cascade');
                }
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('hotel_bookings', function (Blueprint $table) {
            if (Schema::hasColumn('hotel_bookings', 'property_id')) {
                $table->dropForeign(['property_id']);
                $table->dropColumn('property_id');
            }
            if (Schema::hasColumn('hotel_bookings', 'unit_id')) {
                $table->dropForeign(['unit_id']);
                $table->dropColumn('unit_id');
            }
        });
    }
};

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
            // Add guest information fields for non-authenticated bookings
            if (!Schema::hasColumn('hotel_bookings', 'guest_name')) {
                $table->string('guest_name')->nullable()->after('guests_count');
            }
            if (!Schema::hasColumn('hotel_bookings', 'guest_email')) {
                $table->string('guest_email')->nullable()->after('guest_name');
            }
            if (!Schema::hasColumn('hotel_bookings', 'guest_country')) {
                $table->string('guest_country')->nullable()->after('guest_email');
            }
            if (!Schema::hasColumn('hotel_bookings', 'guest_phone')) {
                $table->string('guest_phone', 100)->nullable()->after('guest_country');
            }
            if (!Schema::hasColumn('hotel_bookings', 'special_requests')) {
                $table->text('special_requests')->nullable()->after('guest_phone');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('hotel_bookings', function (Blueprint $table) {
            if (Schema::hasColumn('hotel_bookings', 'guest_name')) {
                $table->dropColumn('guest_name');
            }
            if (Schema::hasColumn('hotel_bookings', 'guest_email')) {
                $table->dropColumn('guest_email');
            }
            if (Schema::hasColumn('hotel_bookings', 'guest_country')) {
                $table->dropColumn('guest_country');
            }
            if (Schema::hasColumn('hotel_bookings', 'guest_phone')) {
                $table->dropColumn('guest_phone');
            }
            if (Schema::hasColumn('hotel_bookings', 'special_requests')) {
                $table->dropColumn('special_requests');
            }
        });
    }
};

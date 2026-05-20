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
        Schema::table('car_rentals', function (Blueprint $table) {
            // Add booking type: 'view_car', 'rent', 'buy'
            $table->enum('booking_type', ['view_car', 'rent', 'buy'])->default('view_car')->after('car_id');
            
            // Add fields for booking
            $table->string('name')->nullable()->after('user_id');
            $table->string('email')->nullable()->after('name');
            $table->string('phone')->nullable()->after('email');
            $table->date('preferred_date')->nullable()->after('dropoff_date'); // For viewing appointments
            $table->time('preferred_time')->nullable()->after('preferred_date'); // For viewing appointments
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('car_rentals', function (Blueprint $table) {
            $table->dropColumn(['booking_type', 'name', 'email', 'phone', 'preferred_date', 'preferred_time']);
        });
    }
};

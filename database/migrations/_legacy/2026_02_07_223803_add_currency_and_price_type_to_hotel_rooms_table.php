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
        Schema::table('hotel_rooms', function (Blueprint $table) {
            if (!Schema::hasColumn('hotel_rooms', 'price_per_month')) {
                $table->decimal('price_per_month', 10, 2)->nullable()->after('price_per_night');
            }
            if (!Schema::hasColumn('hotel_rooms', 'currency')) {
                $table->string('currency', 3)->default('USD')->after('price_per_month');
            }
            if (!Schema::hasColumn('hotel_rooms', 'price_display_type')) {
                $table->enum('price_display_type', ['per_night', 'per_month', 'both'])->default('per_night')->after('currency');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('hotel_rooms', function (Blueprint $table) {
            if (Schema::hasColumn('hotel_rooms', 'price_per_month')) {
                $table->dropColumn('price_per_month');
            }
            if (Schema::hasColumn('hotel_rooms', 'currency')) {
                $table->dropColumn('currency');
            }
            if (Schema::hasColumn('hotel_rooms', 'price_display_type')) {
                $table->dropColumn('price_display_type');
            }
        });
    }
};

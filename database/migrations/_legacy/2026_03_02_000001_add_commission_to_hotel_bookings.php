<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('hotel_bookings', function (Blueprint $table) {
            $table->decimal('commission_rate', 5, 2)->default(10)->after('total_amount');
            $table->decimal('commission_amount', 10, 2)->default(0)->after('commission_rate');
        });

        \DB::table('hotel_bookings')->orderBy('id')->chunk(100, function ($bookings) {
            foreach ($bookings as $b) {
                \DB::table('hotel_bookings')->where('id', $b->id)->update([
                    'commission_rate' => 10,
                    'commission_amount' => round((float) $b->total_amount * 0.10, 2),
                ]);
            }
        });
    }

    public function down(): void
    {
        Schema::table('hotel_bookings', function (Blueprint $table) {
            $table->dropColumn(['commission_rate', 'commission_amount']);
        });
    }
};

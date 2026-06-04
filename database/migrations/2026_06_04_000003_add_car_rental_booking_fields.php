<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('car_rentals')) {
            return;
        }

        Schema::table('car_rentals', function (Blueprint $table) {
            if (! Schema::hasColumn('car_rentals', 'rental_package')) {
                $table->string('rental_package', 64)->nullable()->after('rental_duration');
            }
            if (! Schema::hasColumn('car_rentals', 'pickup_time')) {
                $table->time('pickup_time')->nullable()->after('pickup_date');
            }
            if (! Schema::hasColumn('car_rentals', 'dropoff_time')) {
                $table->time('dropoff_time')->nullable()->after('dropoff_date');
            }
        });
    }

    public function down(): void
    {
        if (! Schema::hasTable('car_rentals')) {
            return;
        }

        Schema::table('car_rentals', function (Blueprint $table) {
            $cols = ['rental_package', 'pickup_time', 'dropoff_time'];
            foreach ($cols as $col) {
                if (Schema::hasColumn('car_rentals', $col)) {
                    $table->dropColumn($col);
                }
            }
        });
    }
};

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('reservations', function (Blueprint $table) {
            if (!Schema::hasColumn('reservations', 'selected_trip_ids')) {
                $table->text('selected_trip_ids')->nullable()->after('tour_id');
            }
            if (!Schema::hasColumn('reservations', 'trip_destination_id')) {
                $table->unsignedBigInteger('trip_destination_id')->nullable()->after('selected_trip_ids');
            }
            if (!Schema::hasColumn('reservations', 'admin_response')) {
                $table->text('admin_response')->nullable()->after('message');
            }
            if (!Schema::hasColumn('reservations', 'quoted_cost')) {
                $table->decimal('quoted_cost', 12, 2)->nullable()->after('admin_response');
            }
            if (!Schema::hasColumn('reservations', 'responded_at')) {
                $table->timestamp('responded_at')->nullable()->after('quoted_cost');
            }
        });

        if (Schema::hasTable('trip_destinations') && Schema::hasColumn('reservations', 'trip_destination_id')) {
            try {
                Schema::table('reservations', function (Blueprint $table) {
                    $table->foreign('trip_destination_id')
                          ->references('id')
                          ->on('trip_destinations')
                          ->onDelete('set null');
                });
            } catch (\Exception $e) {
                // Foreign key may already exist
            }
        }
    }

    public function down(): void
    {
        Schema::table('reservations', function (Blueprint $table) {
            try {
                $table->dropForeign(['trip_destination_id']);
            } catch (\Exception $e) {
                // ignore
            }
            if (Schema::hasColumn('reservations', 'responded_at')) {
                $table->dropColumn('responded_at');
            }
            if (Schema::hasColumn('reservations', 'quoted_cost')) {
                $table->dropColumn('quoted_cost');
            }
            if (Schema::hasColumn('reservations', 'admin_response')) {
                $table->dropColumn('admin_response');
            }
            if (Schema::hasColumn('reservations', 'trip_destination_id')) {
                $table->dropColumn('trip_destination_id');
            }
            if (Schema::hasColumn('reservations', 'selected_trip_ids')) {
                $table->dropColumn('selected_trip_ids');
            }
        });
    }
};

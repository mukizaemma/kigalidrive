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
        if (!Schema::hasTable('terms')) {
            return;
        }

        Schema::table('terms', function (Blueprint $table) {
            if (!Schema::hasColumn('terms', 'privacy_details')) {
                $table->text('privacy_details')->nullable()->after('privacy');
            }
            if (!Schema::hasColumn('terms', 'cookies')) {
                $table->text('cookies')->nullable()->after('privacy_details');
            }
            if (!Schema::hasColumn('terms', 'refunds')) {
                $table->text('refunds')->nullable()->after('cookies');
            }
            if (!Schema::hasColumn('terms', 'booking_cancellation')) {
                $table->text('booking_cancellation')->nullable()->after('refunds');
            }
            if (!Schema::hasColumn('terms', 'listing_commission')) {
                $table->text('listing_commission')->nullable()->after('booking_cancellation');
            }
            if (!Schema::hasColumn('terms', 'payment_methods')) {
                $table->text('payment_methods')->nullable()->after('listing_commission');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (!Schema::hasTable('terms')) {
            return;
        }

        Schema::table('terms', function (Blueprint $table) {
            if (Schema::hasColumn('terms', 'payment_methods')) {
                $table->dropColumn('payment_methods');
            }
            if (Schema::hasColumn('terms', 'listing_commission')) {
                $table->dropColumn('listing_commission');
            }
            if (Schema::hasColumn('terms', 'booking_cancellation')) {
                $table->dropColumn('booking_cancellation');
            }
            if (Schema::hasColumn('terms', 'refunds')) {
                $table->dropColumn('refunds');
            }
            if (Schema::hasColumn('terms', 'cookies')) {
                $table->dropColumn('cookies');
            }
            if (Schema::hasColumn('terms', 'privacy_details')) {
                $table->dropColumn('privacy_details');
            }
        });
    }
};

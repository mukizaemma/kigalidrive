<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('terms')) {
            return;
        }

        Schema::table('terms', function (Blueprint $table) {
            if (! Schema::hasColumn('terms', 'terms')) {
                $table->longText('terms')->nullable();
            }
            if (! Schema::hasColumn('terms', 'privacy')) {
                $table->longText('privacy')->nullable();
            }
            if (! Schema::hasColumn('terms', 'privacy_details')) {
                $table->longText('privacy_details')->nullable();
            }
            if (! Schema::hasColumn('terms', 'cookies')) {
                $table->longText('cookies')->nullable();
            }
            if (! Schema::hasColumn('terms', 'refunds')) {
                $table->longText('refunds')->nullable();
            }
            if (! Schema::hasColumn('terms', 'booking_cancellation')) {
                $table->longText('booking_cancellation')->nullable();
            }
            if (! Schema::hasColumn('terms', 'listing_commission')) {
                $table->longText('listing_commission')->nullable();
            }
            if (! Schema::hasColumn('terms', 'payment_methods')) {
                $table->longText('payment_methods')->nullable();
            }
            if (! Schema::hasColumn('terms', 'return')) {
                $table->longText('return')->nullable();
            }
            if (! Schema::hasColumn('terms', 'support')) {
                $table->longText('support')->nullable();
            }
            if (! Schema::hasColumn('terms', 'added_by')) {
                $table->unsignedBigInteger('added_by')->nullable();
            }
        });
    }

    public function down(): void
    {
        if (! Schema::hasTable('terms')) {
            return;
        }

        Schema::table('terms', function (Blueprint $table) {
            foreach ([
                'terms', 'privacy', 'privacy_details', 'cookies', 'refunds',
                'booking_cancellation', 'listing_commission', 'payment_methods',
                'return', 'support', 'added_by',
            ] as $col) {
                if (Schema::hasColumn('terms', $col)) {
                    $table->dropColumn($col);
                }
            }
        });
    }
};

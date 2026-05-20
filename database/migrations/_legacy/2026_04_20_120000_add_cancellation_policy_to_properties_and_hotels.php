<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('properties')) {
            Schema::table('properties', function (Blueprint $table) {
                if (! Schema::hasColumn('properties', 'cancellation_free_period')) {
                    $table->text('cancellation_free_period')->nullable();
                }
                if (! Schema::hasColumn('properties', 'cancellation_refund_conditions')) {
                    $table->text('cancellation_refund_conditions')->nullable();
                }
                if (! Schema::hasColumn('properties', 'cancellation_no_show_policy')) {
                    $table->text('cancellation_no_show_policy')->nullable();
                }
            });
        }

        if (Schema::hasTable('hotels')) {
            Schema::table('hotels', function (Blueprint $table) {
                if (! Schema::hasColumn('hotels', 'cancellation_free_period')) {
                    $table->text('cancellation_free_period')->nullable();
                }
                if (! Schema::hasColumn('hotels', 'cancellation_refund_conditions')) {
                    $table->text('cancellation_refund_conditions')->nullable();
                }
                if (! Schema::hasColumn('hotels', 'cancellation_no_show_policy')) {
                    $table->text('cancellation_no_show_policy')->nullable();
                }
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('properties')) {
            Schema::table('properties', function (Blueprint $table) {
                if (Schema::hasColumn('properties', 'cancellation_no_show_policy')) {
                    $table->dropColumn('cancellation_no_show_policy');
                }
                if (Schema::hasColumn('properties', 'cancellation_refund_conditions')) {
                    $table->dropColumn('cancellation_refund_conditions');
                }
                if (Schema::hasColumn('properties', 'cancellation_free_period')) {
                    $table->dropColumn('cancellation_free_period');
                }
            });
        }

        if (Schema::hasTable('hotels')) {
            Schema::table('hotels', function (Blueprint $table) {
                if (Schema::hasColumn('hotels', 'cancellation_no_show_policy')) {
                    $table->dropColumn('cancellation_no_show_policy');
                }
                if (Schema::hasColumn('hotels', 'cancellation_refund_conditions')) {
                    $table->dropColumn('cancellation_refund_conditions');
                }
                if (Schema::hasColumn('hotels', 'cancellation_free_period')) {
                    $table->dropColumn('cancellation_free_period');
                }
            });
        }
    }
};

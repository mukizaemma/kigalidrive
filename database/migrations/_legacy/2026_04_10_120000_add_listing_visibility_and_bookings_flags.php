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
                if (!Schema::hasColumn('properties', 'is_listing_visible')) {
                    $table->boolean('is_listing_visible')->default(true)->after('is_verified');
                }
                if (!Schema::hasColumn('properties', 'accepts_bookings')) {
                    $table->boolean('accepts_bookings')->default(true)->after('is_listing_visible');
                }
            });
        }

        if (Schema::hasTable('hotels')) {
            Schema::table('hotels', function (Blueprint $table) {
                if (!Schema::hasColumn('hotels', 'is_listing_visible')) {
                    $table->boolean('is_listing_visible')->default(true)->after('status');
                }
                if (!Schema::hasColumn('hotels', 'accepts_bookings')) {
                    $table->boolean('accepts_bookings')->default(true)->after('is_listing_visible');
                }
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('properties')) {
            Schema::table('properties', function (Blueprint $table) {
                if (Schema::hasColumn('properties', 'accepts_bookings')) {
                    $table->dropColumn('accepts_bookings');
                }
                if (Schema::hasColumn('properties', 'is_listing_visible')) {
                    $table->dropColumn('is_listing_visible');
                }
            });
        }

        if (Schema::hasTable('hotels')) {
            Schema::table('hotels', function (Blueprint $table) {
                if (Schema::hasColumn('hotels', 'accepts_bookings')) {
                    $table->dropColumn('accepts_bookings');
                }
                if (Schema::hasColumn('hotels', 'is_listing_visible')) {
                    $table->dropColumn('is_listing_visible');
                }
            });
        }
    }
};

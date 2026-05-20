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
                if (! Schema::hasColumn('properties', 'listing_terms')) {
                    $table->longText('listing_terms')->nullable();
                }
            });
        }

        if (Schema::hasTable('hotels')) {
            Schema::table('hotels', function (Blueprint $table) {
                if (! Schema::hasColumn('hotels', 'listing_terms')) {
                    $table->longText('listing_terms')->nullable();
                }
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('properties') && Schema::hasColumn('properties', 'listing_terms')) {
            Schema::table('properties', function (Blueprint $table) {
                $table->dropColumn('listing_terms');
            });
        }

        if (Schema::hasTable('hotels') && Schema::hasColumn('hotels', 'listing_terms')) {
            Schema::table('hotels', function (Blueprint $table) {
                $table->dropColumn('listing_terms');
            });
        }
    }
};

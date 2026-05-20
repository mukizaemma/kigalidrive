<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('settings')) {
            return;
        }

        Schema::table('settings', function (Blueprint $table) {
            if (!Schema::hasColumn('settings', 'google_business_url')) {
                $table->string('google_business_url', 500)->nullable()->after('map_embed');
            }
            if (!Schema::hasColumn('settings', 'google_place_id')) {
                $table->string('google_place_id', 255)->nullable()->after('google_business_url');
            }
            if (!Schema::hasColumn('settings', 'google_rating')) {
                $table->decimal('google_rating', 3, 2)->nullable()->after('google_place_id');
            }
            if (!Schema::hasColumn('settings', 'google_review_count')) {
                $table->unsignedInteger('google_review_count')->nullable()->after('google_rating');
            }
        });
    }

    public function down(): void
    {
        if (!Schema::hasTable('settings')) {
            return;
        }

        Schema::table('settings', function (Blueprint $table) {
            foreach (['google_business_url', 'google_place_id', 'google_rating', 'google_review_count'] as $col) {
                if (Schema::hasColumn('settings', $col)) {
                    $table->dropColumn($col);
                }
            }
        });
    }
};

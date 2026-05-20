<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('property_reviews', function (Blueprint $table) {
            if (!Schema::hasColumn('property_reviews', 'guest_name')) {
                $table->string('guest_name')->nullable()->after('user_id');
            }
            if (!Schema::hasColumn('property_reviews', 'guest_email')) {
                $table->string('guest_email')->nullable()->after('guest_name');
            }
        });
        // Make user_id nullable so guests can submit without an account
        $driver = Schema::getConnection()->getDriverName();
        if ($driver === 'mysql') {
            DB::statement('ALTER TABLE property_reviews MODIFY user_id BIGINT UNSIGNED NULL');
        }
    }

    public function down(): void
    {
        DB::statement('ALTER TABLE property_reviews MODIFY user_id BIGINT UNSIGNED NOT NULL');
        Schema::table('property_reviews', function (Blueprint $table) {
            if (Schema::hasColumn('property_reviews', 'guest_name')) {
                $table->dropColumn('guest_name');
            }
            if (Schema::hasColumn('property_reviews', 'guest_email')) {
                $table->dropColumn('guest_email');
            }
        });
    }
};

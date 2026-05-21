<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('listing_requests')) {
            return;
        }

        Schema::table('listing_requests', function (Blueprint $table) {
            if (! Schema::hasColumn('listing_requests', 'submission_channel')) {
                $table->string('submission_channel', 20)->nullable()->after('status');
            }
        });
    }

    public function down(): void
    {
        if (! Schema::hasTable('listing_requests')) {
            return;
        }

        Schema::table('listing_requests', function (Blueprint $table) {
            if (Schema::hasColumn('listing_requests', 'submission_channel')) {
                $table->dropColumn('submission_channel');
            }
        });
    }
};

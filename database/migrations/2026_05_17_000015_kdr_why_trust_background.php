<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('settings') && ! Schema::hasColumn('settings', 'why_trust_background_image')) {
            Schema::table('settings', function (Blueprint $table) {
                $table->string('why_trust_background_image')->nullable()->after('home_background_image');
            });
        }
    }

    public function down(): void
    {
    }
};

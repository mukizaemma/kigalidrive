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
        Schema::table('settings', function (Blueprint $table) {
            if (!Schema::hasColumn('settings', 'home_header_image')) {
                $table->string('home_header_image')->nullable()->after('logo');
            }
            if (!Schema::hasColumn('settings', 'home_background_image')) {
                $table->string('home_background_image')->nullable()->after('home_header_image');
            }
            if (!Schema::hasColumn('settings', 'contact_us_middle_image')) {
                $table->string('contact_us_middle_image')->nullable()->after('home_background_image');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('settings', function (Blueprint $table) {
            $columns = ['home_header_image', 'home_background_image', 'contact_us_middle_image'];
            foreach ($columns as $col) {
                if (Schema::hasColumn('settings', $col)) {
                    $table->dropColumn($col);
                }
            }
        });
    }
};

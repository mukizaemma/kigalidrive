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
        Schema::table('units', function (Blueprint $table) {
            if (!Schema::hasColumn('units', 'currency')) {
                $table->string('currency', 3)->default('USD')->after('base_price_per_month');
            }
            if (!Schema::hasColumn('units', 'price_display_type')) {
                $table->enum('price_display_type', ['per_night', 'per_month', 'both'])->default('per_night')->after('currency');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('units', function (Blueprint $table) {
            if (Schema::hasColumn('units', 'currency')) {
                $table->dropColumn('currency');
            }
            if (Schema::hasColumn('units', 'price_display_type')) {
                $table->dropColumn('price_display_type');
            }
        });
    }
};

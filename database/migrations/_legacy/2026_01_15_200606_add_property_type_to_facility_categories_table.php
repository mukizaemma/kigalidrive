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
        Schema::table('facility_categories', function (Blueprint $table) {
            // Add property_type column to categorize facilities by property type
            // 'hotel' = hotel only, 'apartment' = apartment only, null = both/all
            $table->enum('property_type', ['hotel', 'apartment'])->nullable()->after('icon');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('facility_categories', function (Blueprint $table) {
            $table->dropColumn('property_type');
        });
    }
};

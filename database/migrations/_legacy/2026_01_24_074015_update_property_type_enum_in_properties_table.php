<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Update enum to include guesthouse and lodge (MySQL)
        if (Schema::hasTable('properties') && Schema::hasColumn('properties', 'property_type')) {
            DB::statement("ALTER TABLE `properties` MODIFY `property_type` ENUM('hotel','apartment','guesthouse','lodge') NOT NULL DEFAULT 'hotel'");
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('properties') && Schema::hasColumn('properties', 'property_type')) {
            DB::statement("ALTER TABLE `properties` MODIFY `property_type` ENUM('hotel','apartment') NOT NULL DEFAULT 'hotel'");
        }
    }
};

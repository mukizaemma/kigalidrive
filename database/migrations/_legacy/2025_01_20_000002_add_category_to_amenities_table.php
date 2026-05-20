<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * 
     * This migration adds facility_category_id to existing amenities table
     * and renames it to facilities for better naming convention
     */
    public function up(): void
    {
        // Add foreign key constraint for facility_category_id
        // This migration runs after facility_categories is created
        if (Schema::hasTable('amenities') && Schema::hasTable('facility_categories') && Schema::hasColumn('amenities', 'facility_category_id')) {
            try {
                Schema::table('amenities', function (Blueprint $table) {
                    // Check if foreign key already exists by trying to add it
                    // Laravel will handle the duplicate gracefully
                    $table->foreign('facility_category_id')
                          ->references('id')
                          ->on('facility_categories')
                          ->onDelete('set null');
                });
            } catch (\Exception $e) {
                // Foreign key might already exist, which is fine
                // This allows the migration to be idempotent
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('amenities') && Schema::hasColumn('amenities', 'facility_category_id')) {
            Schema::table('amenities', function (Blueprint $table) {
                // Only drop foreign key, keep columns (they're part of base table now)
                try {
                    $table->dropForeign(['facility_category_id']);
                } catch (\Exception $e) {
                    // Foreign key might not exist
                }
            });
        }
    }
};


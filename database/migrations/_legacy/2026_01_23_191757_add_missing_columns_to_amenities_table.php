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
        Schema::table('amenities', function (Blueprint $table) {
            // Add facility_category_id if it doesn't exist
            if (!Schema::hasColumn('amenities', 'facility_category_id')) {
                $table->unsignedBigInteger('facility_category_id')->nullable()->after('id');
            }
            
            // Add description if it doesn't exist
            if (!Schema::hasColumn('amenities', 'description')) {
                $table->text('description')->nullable()->after('icon');
            }
            
            // Add sort_order if it doesn't exist
            if (!Schema::hasColumn('amenities', 'sort_order')) {
                $table->integer('sort_order')->default(0)->after('description');
            }
            
            // Add is_active if it doesn't exist
            if (!Schema::hasColumn('amenities', 'is_active')) {
                $table->boolean('is_active')->default(true)->after('sort_order');
            }
        });

        // Add foreign key constraint if facility_categories table exists
        if (Schema::hasTable('facility_categories') && Schema::hasColumn('amenities', 'facility_category_id')) {
            try {
                Schema::table('amenities', function (Blueprint $table) {
                    $table->foreign('facility_category_id')
                          ->references('id')
                          ->on('facility_categories')
                          ->onDelete('set null');
                });
            } catch (\Exception $e) {
                // Foreign key might already exist, which is fine
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('amenities', function (Blueprint $table) {
            // Drop foreign key first if it exists
            try {
                $table->dropForeign(['facility_category_id']);
            } catch (\Exception $e) {
                // Foreign key might not exist
            }
            
            // Drop columns if they exist
            if (Schema::hasColumn('amenities', 'is_active')) {
                $table->dropColumn('is_active');
            }
            if (Schema::hasColumn('amenities', 'sort_order')) {
                $table->dropColumn('sort_order');
            }
            if (Schema::hasColumn('amenities', 'description')) {
                $table->dropColumn('description');
            }
            if (Schema::hasColumn('amenities', 'facility_category_id')) {
                $table->dropColumn('facility_category_id');
            }
        });
    }
};

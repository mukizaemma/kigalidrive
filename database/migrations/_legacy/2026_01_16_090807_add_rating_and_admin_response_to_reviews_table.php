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
        Schema::table('reviews', function (Blueprint $table) {
            if (!Schema::hasColumn('reviews', 'rating')) {
                $table->integer('rating')->unsigned()->nullable()->after('testimony'); // 1-5 stars
            }
            if (!Schema::hasColumn('reviews', 'admin_response')) {
                $table->text('admin_response')->nullable()->after('is_featured');
            }
        });
        
        // Update is_approved default to true for new records (if column exists)
        if (Schema::hasColumn('reviews', 'is_approved')) {
            try {
                DB::statement("ALTER TABLE reviews MODIFY COLUMN is_approved BOOLEAN DEFAULT TRUE");
            } catch (\Exception $e) {
                // If modification fails, continue - default will be handled in application code
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('reviews', function (Blueprint $table) {
            if (Schema::hasColumn('reviews', 'rating')) {
                $table->dropColumn('rating');
            }
            if (Schema::hasColumn('reviews', 'admin_response')) {
                $table->dropColumn('admin_response');
            }
        });
    }
};

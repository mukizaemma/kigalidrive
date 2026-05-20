<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('amenities')) {
            Schema::table('amenities', function (Blueprint $table) {
                if (!Schema::hasColumn('amenities', 'title') && Schema::hasColumn('amenities', 'name')) {
                    // Legacy rows use `name`; KDR admin forms expect `title`.
                }
                if (!Schema::hasColumn('amenities', 'is_active')) {
                    $table->boolean('is_active')->default(true);
                }
                if (!Schema::hasColumn('amenities', 'sort_order')) {
                    $table->unsignedInteger('sort_order')->default(0);
                }
                if (!Schema::hasColumn('amenities', 'deleted_at')) {
                    $table->softDeletes();
                }
            });

            if (Schema::hasColumn('amenities', 'name') && !Schema::hasColumn('amenities', 'title')) {
                Schema::table('amenities', function (Blueprint $table) {
                    $table->string('title')->nullable()->after('name');
                });
                \Illuminate\Support\Facades\DB::table('amenities')->whereNotNull('name')->update([
                    'title' => \Illuminate\Support\Facades\DB::raw('name'),
                ]);
            }
        }

        if (Schema::hasTable('reviews') && !Schema::hasColumn('reviews', 'deleted_at')) {
            Schema::table('reviews', function (Blueprint $table) {
                $table->softDeletes();
            });
        }

        if (Schema::hasTable('carimages') && !Schema::hasColumn('carimages', 'deleted_at')) {
            Schema::table('carimages', function (Blueprint $table) {
                $table->softDeletes();
            });
        }
    }

    public function down(): void
    {
    }
};

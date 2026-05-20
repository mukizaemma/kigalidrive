<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /** @var array<int, string> */
    protected array $softDeleteTables = [
        'facility_categories',
        'unit_types',
        'property_images',
        'unit_images',
        'unit_pricing',
    ];

    public function up(): void
    {
        foreach ($this->softDeleteTables as $table) {
            if (Schema::hasTable($table) && ! Schema::hasColumn($table, 'deleted_at')) {
                Schema::table($table, function (Blueprint $blueprint) {
                    $blueprint->softDeletes();
                });
            }
        }

        if (Schema::hasTable('facility_categories')) {
            Schema::table('facility_categories', function (Blueprint $table) {
                if (! Schema::hasColumn('facility_categories', 'icon')) {
                    $table->string('icon')->nullable();
                }
                if (! Schema::hasColumn('facility_categories', 'description')) {
                    $table->text('description')->nullable();
                }
                if (! Schema::hasColumn('facility_categories', 'sort_order')) {
                    $table->unsignedInteger('sort_order')->default(0);
                }
                if (! Schema::hasColumn('facility_categories', 'is_active')) {
                    $table->boolean('is_active')->default(true);
                }
            });
        }

        if (Schema::hasTable('unit_types')) {
            Schema::table('unit_types', function (Blueprint $table) {
                if (! Schema::hasColumn('unit_types', 'property_type')) {
                    $table->string('property_type')->default('apartment');
                }
                if (! Schema::hasColumn('unit_types', 'sort_order')) {
                    $table->unsignedInteger('sort_order')->default(0);
                }
                if (! Schema::hasColumn('unit_types', 'is_active')) {
                    $table->boolean('is_active')->default(true);
                }
            });
        }

        if (Schema::hasTable('properties')) {
            if (Schema::hasColumn('properties', 'property_type')) {
                try {
                    DB::statement("ALTER TABLE `properties` MODIFY `property_type` VARCHAR(50) NOT NULL DEFAULT 'apartment'");
                } catch (\Throwable $e) {
                    // Driver may not support MODIFY; enum values still work for apartment/villa.
                }
            }

            Schema::table('properties', function (Blueprint $table) {
                if (! Schema::hasColumn('properties', 'website')) {
                    $table->string('website')->nullable();
                }
                if (! Schema::hasColumn('properties', 'stars')) {
                    $table->string('stars', 10)->nullable();
                }
                if (! Schema::hasColumn('properties', 'is_verified')) {
                    $table->boolean('is_verified')->default(false);
                }
                if (! Schema::hasColumn('properties', 'accepts_bookings')) {
                    $table->boolean('accepts_bookings')->default(true);
                }
            });
        }

        if (Schema::hasTable('property_images')) {
            Schema::table('property_images', function (Blueprint $table) {
                if (! Schema::hasColumn('property_images', 'is_primary')) {
                    $table->boolean('is_primary')->default(false);
                }
                if (! Schema::hasColumn('property_images', 'caption')) {
                    $table->string('caption')->nullable();
                }
                if (! Schema::hasColumn('property_images', 'uploaded_by')) {
                    $table->foreignId('uploaded_by')->nullable()->constrained('users')->nullOnDelete();
                }
                if (! Schema::hasColumn('property_images', 'image_path') && Schema::hasColumn('property_images', 'image')) {
                    $table->string('image_path')->nullable()->after('image');
                }
            });

            if (Schema::hasColumn('property_images', 'image') && Schema::hasColumn('property_images', 'image_path')) {
                DB::table('property_images')
                    ->whereNull('image_path')
                    ->whereNotNull('image')
                    ->update(['image_path' => DB::raw('image')]);
            }
        }
    }

    public function down(): void
    {
    }
};

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('facility_categories')) {
            Schema::create('facility_categories', function (Blueprint $table) {
                $table->id();
                $table->string('name');
                $table->string('slug')->nullable();
                $table->string('property_type')->default('apartment');
                $table->timestamps();
            });
        }

        if (!Schema::hasTable('amenities')) {
            Schema::create('amenities', function (Blueprint $table) {
                $table->id();
                $table->foreignId('facility_category_id')->nullable()->constrained()->nullOnDelete();
                $table->string('name');
                $table->string('slug')->nullable();
                $table->string('icon')->nullable();
                $table->enum('status', ['Active', 'Inactive'])->default('Active');
                $table->timestamps();
            });
        }

        if (!Schema::hasTable('unit_types')) {
            Schema::create('unit_types', function (Blueprint $table) {
                $table->id();
                $table->string('name');
                $table->string('slug')->nullable();
                $table->text('description')->nullable();
                $table->timestamps();
            });
        }

        if (!Schema::hasTable('properties')) {
            Schema::create('properties', function (Blueprint $table) {
                $table->id();
                $table->foreignId('owner_id')->constrained('users')->cascadeOnDelete();
                $table->string('name');
                $table->string('slug')->unique();
                $table->enum('property_type', ['apartment', 'villa'])->default('apartment');
                $table->enum('listing_type', ['rent', 'sale', 'both'])->default('rent');
                $table->text('description')->nullable();
                $table->string('address')->nullable();
                $table->string('city')->nullable();
                $table->string('location')->nullable();
                $table->decimal('latitude', 10, 7)->nullable();
                $table->decimal('longitude', 10, 7)->nullable();
                $table->text('map_embed_code')->nullable();
                $table->string('phone')->nullable();
                $table->string('email')->nullable();
                $table->string('featured_image')->nullable();
                $table->enum('status', ['Active', 'Inactive', 'Pending'])->default('Pending');
                $table->boolean('is_featured')->default(false);
                $table->boolean('is_listing_visible')->default(true);
                $table->text('cancellation_free_period')->nullable();
                $table->text('cancellation_refund_conditions')->nullable();
                $table->text('cancellation_no_show_policy')->nullable();
                $table->longText('listing_terms')->nullable();
                $table->json('meta_data')->nullable();
                $table->softDeletes();
                $table->timestamps();
                $table->index(['property_type', 'status']);
            });
        }

        if (!Schema::hasTable('property_images')) {
            Schema::create('property_images', function (Blueprint $table) {
                $table->id();
                $table->foreignId('property_id')->constrained()->cascadeOnDelete();
                $table->string('image');
                $table->unsignedInteger('sort_order')->default(0);
                $table->timestamps();
            });
        }

        if (!Schema::hasTable('property_facilities')) {
            Schema::create('property_facilities', function (Blueprint $table) {
                $table->id();
                $table->foreignId('property_id')->constrained()->cascadeOnDelete();
                $table->foreignId('amenity_id')->constrained()->cascadeOnDelete();
                $table->timestamps();
            });
        }

        if (!Schema::hasTable('units')) {
            Schema::create('units', function (Blueprint $table) {
                $table->id();
                $table->foreignId('property_id')->constrained()->cascadeOnDelete();
                $table->foreignId('unit_type_id')->nullable()->constrained()->nullOnDelete();
                $table->foreignId('added_by')->constrained('users')->cascadeOnDelete();
                $table->string('name')->nullable();
                $table->string('slug')->unique();
                $table->text('description')->nullable();
                $table->unsignedInteger('max_occupancy')->default(1);
                $table->unsignedInteger('bedrooms')->default(0);
                $table->unsignedInteger('bathrooms')->default(1);
                $table->unsignedInteger('beds')->default(1);
                $table->unsignedInteger('size_sqm')->nullable();
                $table->unsignedInteger('total_units')->default(1);
                $table->unsignedInteger('available_units')->default(1);
                $table->decimal('base_price_per_night', 12, 2)->nullable();
                $table->decimal('base_price_per_day', 12, 2)->nullable();
                $table->decimal('base_price_per_month', 12, 2)->nullable();
                $table->decimal('sale_price', 14, 2)->nullable();
                $table->boolean('furnished')->default(false);
                $table->string('featured_image')->nullable();
                $table->enum('status', ['Available', 'Unavailable', 'Maintenance'])->default('Available');
                $table->boolean('is_active')->default(true);
                $table->boolean('accepts_bookings')->default(true);
                $table->json('meta_data')->nullable();
                $table->softDeletes();
                $table->timestamps();
            });
        }

        if (!Schema::hasTable('unit_images')) {
            Schema::create('unit_images', function (Blueprint $table) {
                $table->id();
                $table->foreignId('unit_id')->constrained()->cascadeOnDelete();
                $table->string('image');
                $table->unsignedInteger('sort_order')->default(0);
                $table->timestamps();
            });
        }

        if (!Schema::hasTable('unit_facilities')) {
            Schema::create('unit_facilities', function (Blueprint $table) {
                $table->id();
                $table->foreignId('unit_id')->constrained()->cascadeOnDelete();
                $table->foreignId('amenity_id')->constrained()->cascadeOnDelete();
                $table->timestamps();
            });
        }

        if (!Schema::hasTable('unit_pricing')) {
            Schema::create('unit_pricing', function (Blueprint $table) {
                $table->id();
                $table->foreignId('unit_id')->constrained()->cascadeOnDelete();
                $table->date('start_date')->nullable();
                $table->date('end_date')->nullable();
                $table->decimal('price_per_night', 12, 2)->nullable();
                $table->decimal('price_per_day', 12, 2)->nullable();
                $table->string('currency', 3)->default('RWF');
                $table->timestamps();
            });
        }

        if (!Schema::hasTable('unit_availability')) {
            Schema::create('unit_availability', function (Blueprint $table) {
                $table->id();
                $table->foreignId('unit_id')->constrained()->cascadeOnDelete();
                $table->date('date');
                $table->unsignedInteger('available_units')->default(0);
                $table->boolean('is_blocked')->default(false);
                $table->timestamps();
                $table->unique(['unit_id', 'date']);
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('unit_availability');
        Schema::dropIfExists('unit_pricing');
        Schema::dropIfExists('unit_facilities');
        Schema::dropIfExists('unit_images');
        Schema::dropIfExists('units');
        Schema::dropIfExists('property_facilities');
        Schema::dropIfExists('property_images');
        Schema::dropIfExists('properties');
        Schema::dropIfExists('unit_types');
        Schema::dropIfExists('amenities');
        Schema::dropIfExists('facility_categories');
    }
};

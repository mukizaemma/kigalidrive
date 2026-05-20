<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('settings')) {
            Schema::table('settings', function (Blueprint $table) {
                if (!Schema::hasColumn('settings', 'tagline')) $table->string('tagline')->nullable();
                if (!Schema::hasColumn('settings', 'whatsapp')) $table->string('whatsapp')->nullable();
                if (!Schema::hasColumn('settings', 'whatsapp_enabled')) $table->boolean('whatsapp_enabled')->default(true);
                if (!Schema::hasColumn('settings', 'booking_email')) $table->string('booking_email')->nullable();
                if (!Schema::hasColumn('settings', 'booking_email_enabled')) $table->boolean('booking_email_enabled')->default(true);
                if (!Schema::hasColumn('settings', 'map_embed')) $table->text('map_embed')->nullable();
                if (!Schema::hasColumn('settings', 'business_hours')) $table->string('business_hours')->nullable();
                if (!Schema::hasColumn('settings', 'home_header_image')) $table->string('home_header_image')->nullable();
                if (!Schema::hasColumn('settings', 'home_background_image')) $table->string('home_background_image')->nullable();
                if (!Schema::hasColumn('settings', 'contact_us_middle_image')) $table->string('contact_us_middle_image')->nullable();
            });
        }

        if (Schema::hasTable('abouts')) {
            Schema::table('abouts', function (Blueprint $table) {
                foreach (['background', 'vision', 'core_values', 'team', 'what_we_do', 'commitment'] as $col) {
                    if (!Schema::hasColumn('abouts', $col)) $table->longText($col)->nullable();
                }
                foreach (['cta_services_url', 'cta_book_url', 'cta_contact_url'] as $col) {
                    if (!Schema::hasColumn('abouts', $col)) $table->string($col)->nullable();
                }
            });
        }

        if (Schema::hasTable('properties')) {
            Schema::table('properties', function (Blueprint $table) {
                if (!Schema::hasColumn('properties', 'listing_type')) $table->string('listing_type')->default('rent');
                if (!Schema::hasColumn('properties', 'map_embed_code')) $table->text('map_embed_code')->nullable();
                if (!Schema::hasColumn('properties', 'is_listing_visible')) $table->boolean('is_listing_visible')->default(true);
            });
        }

        if (Schema::hasTable('units')) {
            Schema::table('units', function (Blueprint $table) {
                if (!Schema::hasColumn('units', 'base_price_per_day')) $table->decimal('base_price_per_day', 12, 2)->nullable();
                if (!Schema::hasColumn('units', 'sale_price')) $table->decimal('sale_price', 14, 2)->nullable();
                if (!Schema::hasColumn('units', 'furnished')) $table->boolean('furnished')->default(false);
                if (!Schema::hasColumn('units', 'accepts_bookings')) $table->boolean('accepts_bookings')->default(true);
            });
        }

        if (Schema::hasTable('cars')) {
            Schema::table('cars', function (Blueprint $table) {
                if (!Schema::hasColumn('cars', 'brand')) $table->string('brand')->nullable();
                if (!Schema::hasColumn('cars', 'listing_type')) $table->string('listing_type')->default('rent');
                if (!Schema::hasColumn('cars', 'price_per_week')) $table->decimal('price_per_week', 12, 2)->nullable();
                if (!Schema::hasColumn('cars', 'driver_available')) $table->boolean('driver_available')->default(true);
                if (!Schema::hasColumn('cars', 'self_drive')) $table->boolean('self_drive')->default(true);
                if (!Schema::hasColumn('cars', 'image')) $table->string('image')->nullable();
            });
        }

        if (Schema::hasTable('car_rentals')) {
            Schema::table('car_rentals', function (Blueprint $table) {
                if (!Schema::hasColumn('car_rentals', 'booking_number')) $table->unsignedInteger('booking_number')->nullable()->unique();
                if (!Schema::hasColumn('car_rentals', 'full_address')) $table->string('full_address')->nullable();
                if (!Schema::hasColumn('car_rentals', 'time_needed')) $table->string('time_needed')->nullable();
                if (!Schema::hasColumn('car_rentals', 'rental_duration')) $table->string('rental_duration')->nullable();
                if (!Schema::hasColumn('car_rentals', 'with_driver')) $table->boolean('with_driver')->nullable();
                if (!Schema::hasColumn('car_rentals', 'additional_request')) $table->text('additional_request')->nullable();
                if (!Schema::hasColumn('car_rentals', 'submission_channel')) $table->string('submission_channel')->default('form');
            });
        }

        if (Schema::hasTable('hotel_bookings')) {
            Schema::table('hotel_bookings', function (Blueprint $table) {
                if (!Schema::hasColumn('hotel_bookings', 'booking_number')) $table->unsignedInteger('booking_number')->nullable()->unique();
                if (!Schema::hasColumn('hotel_bookings', 'full_address')) $table->string('full_address')->nullable();
                if (!Schema::hasColumn('hotel_bookings', 'time_needed')) $table->string('time_needed')->nullable();
                if (!Schema::hasColumn('hotel_bookings', 'booking_type')) $table->string('booking_type')->nullable();
                if (!Schema::hasColumn('hotel_bookings', 'additional_request')) $table->text('additional_request')->nullable();
                if (!Schema::hasColumn('hotel_bookings', 'submission_channel')) $table->string('submission_channel')->default('form');
                if (!Schema::hasColumn('hotel_bookings', 'guest_name')) $table->string('guest_name')->nullable();
                if (!Schema::hasColumn('hotel_bookings', 'guest_email')) $table->string('guest_email')->nullable();
                if (!Schema::hasColumn('hotel_bookings', 'guest_phone')) $table->string('guest_phone')->nullable();
            });
        }

        if (Schema::hasTable('reviews')) {
            Schema::table('reviews', function (Blueprint $table) {
                if (!Schema::hasColumn('reviews', 'booking_number')) $table->unsignedInteger('booking_number')->nullable();
                if (!Schema::hasColumn('reviews', 'rating')) $table->unsignedTinyInteger('rating')->default(5);
                if (!Schema::hasColumn('reviews', 'admin_response')) $table->text('admin_response')->nullable();
                if (!Schema::hasColumn('reviews', 'deleted_at')) $table->softDeletes();
            });
        }

        if (Schema::hasTable('listing_agreement_templates') && !Schema::hasColumn('listing_agreement_templates', 'template_type')) {
            Schema::table('listing_agreement_templates', function (Blueprint $table) {
                $table->string('template_type')->default('car_rental');
            });
        }
    }

    public function down(): void
    {
    }
};

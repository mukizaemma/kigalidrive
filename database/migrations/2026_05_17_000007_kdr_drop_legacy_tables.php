<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /** @var array<int, string> */
    protected array $legacyTables = [
        'Kigali Drive Rentals_invoice_bookings',
        'Kigali Drive Rentals_invoices',
        'listing_agreement_signatures',
        'inventory_day_caps',
        'booking_extras',
        'unit_extra_charges',
        'extra_charge_types',
        'property_reviews',
        'car_reviews',
        'trip_reviews',
        'trip_bookings',
        'tripimages',
        'trips',
        'trip_destination_images',
        'trip_destinations',
        'car_rental_requests',
        'car_rental_contents',
        'reservations',
        'luggage_bookings',
        'luggage_locations',
        'leftbags',
        'ticketings',
        'amenity_hotel_rooms',
        'hotel_amenities',
        'hotel_room_images',
        'hotel_rooms',
        'hotel_images',
        'hotels',
        'tourimages',
        'tours',
        'facilityimages',
        'facilities',
        'promotions',
        'subscribers',
        'messages',
        'galleries',
        'images',
        'rooms',
        'roomimages',
        'posts',
        'restaurants',
        'events',
        'eventimages',
        'services',
        'programs',
        'categories',
        'partners',
        'countries',
        'languages',
        'plans',
        'teams',
    ];

    public function up(): void
    {
        $this->dropLegacyForeignKeys();

        foreach ($this->legacyTables as $table) {
            Schema::dropIfExists($table);
        }

        $this->dropLegacyColumns();
    }

    protected function dropLegacyForeignKeys(): void
    {
        if (Schema::hasTable('hotel_bookings')) {
            foreach (['hotel_id', 'room_id'] as $col) {
                if (!Schema::hasColumn('hotel_bookings', $col)) {
                    continue;
                }
                try {
                    Schema::table('hotel_bookings', function (Blueprint $table) use ($col) {
                        $table->dropForeign([$col]);
                    });
                } catch (\Throwable $e) {
                }
            }
        }

        if (Schema::hasTable('cars') && Schema::hasColumn('cars', 'program_id')) {
            try {
                Schema::table('cars', function (Blueprint $table) {
                    $table->dropForeign(['program_id']);
                });
            } catch (\Throwable $e) {
            }
        }

        if (Schema::hasTable('properties')) {
            Schema::table('properties', function (Blueprint $table) {
                foreach (['category_id', 'program_id', 'partner_id'] as $col) {
                    if (!Schema::hasColumn('properties', $col)) {
                        continue;
                    }
                    try {
                        $table->dropForeign([$col]);
                    } catch (\Throwable $e) {
                    }
                }
            });
        }
    }

    protected function dropLegacyColumns(): void
    {
        if (Schema::hasTable('hotel_bookings')) {
            Schema::table('hotel_bookings', function (Blueprint $table) {
                foreach (['hotel_id', 'room_id'] as $col) {
                    if (Schema::hasColumn('hotel_bookings', $col)) {
                        $table->dropColumn($col);
                    }
                }
            });
        }

        if (Schema::hasTable('cars') && Schema::hasColumn('cars', 'program_id')) {
            Schema::table('cars', function (Blueprint $table) {
                $table->dropColumn('program_id');
            });
        }

        if (Schema::hasTable('properties')) {
            Schema::table('properties', function (Blueprint $table) {
                foreach (['category_id', 'program_id', 'partner_id'] as $col) {
                    if (Schema::hasColumn('properties', $col)) {
                        $table->dropColumn($col);
                    }
                }
            });
        }

        if (Schema::hasTable('users')) {
            Schema::table('users', function (Blueprint $table) {
                foreach (['country_origin_id', 'country_work_id', 'plan_id'] as $col) {
                    if (Schema::hasColumn('users', $col)) {
                        try {
                            $table->dropForeign([$col]);
                        } catch (\Throwable $e) {
                        }
                        $table->dropColumn($col);
                    }
                }
            });
        }
    }

    public function down(): void
    {
        // Legacy tables are not recreated automatically.
    }
};

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
                if (!Schema::hasColumn('settings', 'whatsapp')) {
                    $table->string('whatsapp')->nullable()->after('phone');
                }
                if (!Schema::hasColumn('settings', 'whatsapp_enabled')) {
                    $table->boolean('whatsapp_enabled')->default(true)->after('whatsapp');
                }
                if (!Schema::hasColumn('settings', 'booking_email')) {
                    $table->string('booking_email')->nullable()->after('email');
                }
                if (!Schema::hasColumn('settings', 'booking_email_enabled')) {
                    $table->boolean('booking_email_enabled')->default(true)->after('booking_email');
                }
                if (!Schema::hasColumn('settings', 'tagline')) {
                    $table->string('tagline')->nullable()->after('company');
                }
                if (!Schema::hasColumn('settings', 'map_embed')) {
                    $table->text('map_embed')->nullable()->after('address');
                }
                if (!Schema::hasColumn('settings', 'business_hours')) {
                    $table->string('business_hours')->nullable()->after('map_embed');
                }
            });
        }

        if (Schema::hasTable('cars')) {
            Schema::table('cars', function (Blueprint $table) {
                if (!Schema::hasColumn('cars', 'brand')) {
                    $table->string('brand')->nullable()->after('name');
                }
                if (!Schema::hasColumn('cars', 'listing_type')) {
                    $table->enum('listing_type', ['rent', 'sale', 'both'])->default('rent')->after('brand');
                }
                if (!Schema::hasColumn('cars', 'price_per_week')) {
                    $table->decimal('price_per_week', 12, 2)->nullable()->after('price_per_day');
                }
                if (!Schema::hasColumn('cars', 'driver_available')) {
                    $table->boolean('driver_available')->default(true)->after('transmission');
                }
                if (!Schema::hasColumn('cars', 'self_drive')) {
                    $table->boolean('self_drive')->default(true)->after('driver_available');
                }
            });
        }

        if (Schema::hasTable('car_rentals')) {
            Schema::table('car_rentals', function (Blueprint $table) {
                if (Schema::hasColumn('car_rentals', 'user_id')) {
                    $table->unsignedBigInteger('user_id')->nullable()->change();
                }
                if (!Schema::hasColumn('car_rentals', 'booking_number')) {
                    $table->unsignedInteger('booking_number')->nullable()->unique()->after('id');
                }
                if (!Schema::hasColumn('car_rentals', 'full_address')) {
                    $table->string('full_address')->nullable()->after('phone');
                }
                if (!Schema::hasColumn('car_rentals', 'time_needed')) {
                    $table->string('time_needed')->nullable()->after('full_address');
                }
                if (!Schema::hasColumn('car_rentals', 'rental_duration')) {
                    $table->string('rental_duration')->nullable()->after('booking_type');
                }
                if (!Schema::hasColumn('car_rentals', 'with_driver')) {
                    $table->boolean('with_driver')->nullable()->after('rental_duration');
                }
                if (!Schema::hasColumn('car_rentals', 'additional_request')) {
                    $table->text('additional_request')->nullable()->after('message');
                }
                if (!Schema::hasColumn('car_rentals', 'submission_channel')) {
                    $table->enum('submission_channel', ['email', 'whatsapp', 'form'])->default('form')->after('payment_status');
                }
            });
        }

        if (Schema::hasTable('hotel_bookings')) {
            Schema::table('hotel_bookings', function (Blueprint $table) {
                if (!Schema::hasColumn('hotel_bookings', 'booking_number')) {
                    $table->unsignedInteger('booking_number')->nullable()->unique()->after('id');
                }
                if (!Schema::hasColumn('hotel_bookings', 'full_address')) {
                    $table->string('full_address')->nullable();
                }
                if (!Schema::hasColumn('hotel_bookings', 'time_needed')) {
                    $table->string('time_needed')->nullable();
                }
                if (!Schema::hasColumn('hotel_bookings', 'additional_request')) {
                    $table->text('additional_request')->nullable();
                }
                if (!Schema::hasColumn('hotel_bookings', 'submission_channel')) {
                    $table->enum('submission_channel', ['email', 'whatsapp', 'form'])->default('form');
                }
                if (!Schema::hasColumn('hotel_bookings', 'booking_type')) {
                    $table->string('booking_type')->nullable();
                }
            });
        }

        if (Schema::hasTable('reviews') && !Schema::hasColumn('reviews', 'booking_number')) {
            Schema::table('reviews', function (Blueprint $table) {
                $table->unsignedInteger('booking_number')->nullable()->after('email');
            });
        }

        if (Schema::hasTable('listing_agreement_templates') && !Schema::hasColumn('listing_agreement_templates', 'template_type')) {
            Schema::table('listing_agreement_templates', function (Blueprint $table) {
                $table->string('template_type')->default('listing')->after('id');
            });
        }

        if (!Schema::hasTable('listing_requests')) {
            Schema::create('listing_requests', function (Blueprint $table) {
                $table->id();
                $table->enum('product_type', ['car', 'apartment']);
                $table->enum('ad_type', ['rent', 'sale']);
                $table->string('contact_name');
                $table->string('phone');
                $table->string('email')->nullable();
                $table->decimal('amount', 14, 2)->nullable();
                $table->string('location');
                $table->text('details')->nullable();
                $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending');
                $table->text('admin_notes')->nullable();
                $table->timestamps();
            });
        }

        if (Schema::hasTable('abouts')) {
            Schema::table('abouts', function (Blueprint $table) {
                if (!Schema::hasColumn('abouts', 'core_values')) {
                    $table->longText('core_values')->nullable();
                }
                if (!Schema::hasColumn('abouts', 'team')) {
                    $table->longText('team')->nullable();
                }
                if (!Schema::hasColumn('abouts', 'background')) {
                    $table->longText('background')->nullable();
                }
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('listing_requests');

        $drops = [
            'settings' => ['whatsapp', 'whatsapp_enabled', 'booking_email', 'booking_email_enabled', 'tagline', 'map_embed', 'business_hours'],
            'cars' => ['brand', 'listing_type', 'price_per_week', 'driver_available', 'self_drive'],
            'car_rentals' => ['booking_number', 'full_address', 'time_needed', 'rental_duration', 'with_driver', 'additional_request', 'submission_channel'],
            'hotel_bookings' => ['booking_number', 'full_address', 'time_needed', 'additional_request', 'submission_channel'],
            'reviews' => ['booking_number'],
            'listing_agreement_templates' => ['template_type'],
            'abouts' => ['core_values', 'team', 'background'],
        ];

        foreach ($drops as $table => $columns) {
            if (!Schema::hasTable($table)) {
                continue;
            }
            Schema::table($table, function (Blueprint $table) use ($columns) {
                foreach ($columns as $col) {
                    if (Schema::hasColumn($table->getTable(), $col)) {
                        $table->dropColumn($col);
                    }
                }
            });
        }
    }
};

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('hotel_bookings')) {
            Schema::create('hotel_bookings', function (Blueprint $table) {
                $table->id();
                $table->unsignedInteger('booking_number')->nullable()->unique();
                $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
                $table->foreignId('property_id')->nullable()->constrained()->cascadeOnDelete();
                $table->foreignId('unit_id')->nullable()->constrained()->nullOnDelete();
                $table->date('check_in');
                $table->date('check_out');
                $table->unsignedInteger('guests_count')->default(1);
                $table->string('guest_name')->nullable();
                $table->string('guest_email')->nullable();
                $table->string('guest_phone')->nullable();
                $table->string('guest_country')->nullable();
                $table->string('full_address')->nullable();
                $table->string('time_needed')->nullable();
                $table->string('booking_type')->nullable();
                $table->text('special_requests')->nullable();
                $table->text('additional_request')->nullable();
                $table->decimal('total_amount', 12, 2)->nullable();
                $table->decimal('commission_rate', 5, 2)->nullable();
                $table->decimal('commission_amount', 12, 2)->nullable();
                $table->enum('payment_status', ['pending', 'paid', 'refunded'])->default('pending');
                $table->enum('booking_status', ['pending', 'confirmed', 'cancelled', 'availability_requested'])->default('pending');
                $table->enum('submission_channel', ['email', 'whatsapp', 'form'])->default('form');
                $table->string('reference_number')->unique();
                $table->longText('description')->nullable();
                $table->softDeletes();
                $table->timestamps();
            });
        }

        if (!Schema::hasTable('booking_comments')) {
            Schema::create('booking_comments', function (Blueprint $table) {
                $table->id();
                $table->foreignId('hotel_booking_id')->constrained()->cascadeOnDelete();
                $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
                $table->text('comment');
                $table->timestamps();
            });
        }

        if (!Schema::hasTable('booking_stay_modifications')) {
            Schema::create('booking_stay_modifications', function (Blueprint $table) {
                $table->id();
                $table->foreignId('hotel_booking_id')->constrained()->cascadeOnDelete();
                $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
                $table->date('previous_check_in')->nullable();
                $table->date('previous_check_out')->nullable();
                $table->date('new_check_in')->nullable();
                $table->date('new_check_out')->nullable();
                $table->text('note')->nullable();
                $table->timestamps();
            });
        }

        if (!Schema::hasTable('reviews')) {
            Schema::create('reviews', function (Blueprint $table) {
                $table->id();
                $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
                $table->string('names');
                $table->string('email');
                $table->unsignedInteger('booking_number')->nullable();
                $table->text('testimony');
                $table->unsignedTinyInteger('rating')->default(5);
                $table->string('website')->nullable();
                $table->boolean('is_approved')->default(false);
                $table->text('admin_response')->nullable();
                $table->timestamps();
            });
        }

        if (!Schema::hasTable('review_images')) {
            Schema::create('review_images', function (Blueprint $table) {
                $table->id();
                $table->foreignId('review_id')->constrained()->cascadeOnDelete();
                $table->string('image');
                $table->unsignedInteger('sort_order')->default(0);
                $table->timestamps();
            });
        }

        if (!Schema::hasTable('listing_agreement_templates')) {
            Schema::create('listing_agreement_templates', function (Blueprint $table) {
                $table->id();
                $table->string('template_type')->default('car_rental');
                $table->string('platform_name');
                $table->string('platform_representative_name')->nullable();
                $table->string('platform_signature_path')->nullable();
                $table->longText('intro_text')->nullable();
                $table->json('sections')->nullable();
                $table->timestamps();
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
    }

    public function down(): void
    {
        Schema::dropIfExists('listing_requests');
        Schema::dropIfExists('listing_agreement_templates');
        Schema::dropIfExists('review_images');
        Schema::dropIfExists('reviews');
        Schema::dropIfExists('booking_stay_modifications');
        Schema::dropIfExists('booking_comments');
        Schema::dropIfExists('hotel_bookings');
    }
};

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('cars')) {
            Schema::create('cars', function (Blueprint $table) {
                $table->id();
                $table->foreignId('added_by')->constrained('users')->cascadeOnDelete();
                $table->string('name');
                $table->string('slug')->unique();
                $table->string('brand')->nullable();
                $table->enum('listing_type', ['rent', 'sale', 'both'])->default('rent');
                $table->string('model')->nullable();
                $table->string('fuel_type')->nullable();
                $table->unsignedTinyInteger('seats')->nullable();
                $table->string('transmission')->nullable();
                $table->boolean('driver_available')->default(true);
                $table->boolean('self_drive')->default(true);
                $table->decimal('price_per_day', 12, 2)->nullable();
                $table->decimal('price_per_week', 12, 2)->nullable();
                $table->decimal('price_per_month', 12, 2)->nullable();
                $table->decimal('price_to_buy', 14, 2)->nullable();
                $table->string('image')->nullable();
                $table->longText('description')->nullable();
                $table->enum('status', ['available', 'rented', 'maintenance'])->default('available');
                $table->softDeletes();
                $table->timestamps();
            });
        }

        if (!Schema::hasTable('carimages')) {
            Schema::create('carimages', function (Blueprint $table) {
                $table->id();
                $table->foreignId('car_id')->constrained('cars')->cascadeOnDelete();
                $table->string('image');
                $table->timestamps();
            });
        }

        if (!Schema::hasTable('car_rentals')) {
            Schema::create('car_rentals', function (Blueprint $table) {
                $table->id();
                $table->unsignedInteger('booking_number')->nullable()->unique();
                $table->foreignId('car_id')->constrained()->cascadeOnDelete();
                $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
                $table->enum('booking_type', ['view_car', 'rent', 'buy'])->default('rent');
                $table->string('rental_duration')->nullable();
                $table->boolean('with_driver')->nullable();
                $table->string('name');
                $table->string('email');
                $table->string('phone');
                $table->string('full_address')->nullable();
                $table->string('time_needed')->nullable();
                $table->string('pickup_location')->nullable();
                $table->string('dropoff_location')->nullable();
                $table->date('pickup_date')->nullable();
                $table->date('dropoff_date')->nullable();
                $table->date('preferred_date')->nullable();
                $table->time('preferred_time')->nullable();
                $table->text('message')->nullable();
                $table->text('additional_request')->nullable();
                $table->decimal('total_amount', 12, 2)->nullable();
                $table->enum('rental_status', ['pending', 'confirmed', 'cancelled'])->default('pending');
                $table->enum('payment_status', ['pending', 'paid', 'refunded'])->default('pending');
                $table->enum('submission_channel', ['email', 'whatsapp', 'form'])->default('form');
                $table->softDeletes();
                $table->timestamps();
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('car_rentals');
        Schema::dropIfExists('carimages');
        Schema::dropIfExists('cars');
    }
};

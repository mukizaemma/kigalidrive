<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('Kigali Drive Rentals_invoices', function (Blueprint $table) {
            $table->id();
            $table->string('invoice_number')->unique();
            $table->unsignedBigInteger('property_id');
            $table->date('period_start');
            $table->date('period_end');
            $table->decimal('total_booking_amount', 12, 2)->default(0);
            $table->decimal('commission_amount', 12, 2)->default(0);
            $table->enum('status', ['draft', 'sent', 'paid'])->default('draft');
            $table->unsignedBigInteger('created_by')->nullable();
            $table->timestamp('sent_at')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->foreign('property_id')->references('id')->on('properties')->onDelete('cascade');
            $table->foreign('created_by')->references('id')->on('users')->onDelete('set null');
        });

        Schema::create('Kigali Drive Rentals_invoice_bookings', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('Kigali Drive Rentals_invoice_id');
            $table->unsignedBigInteger('hotel_booking_id');
            $table->decimal('booking_total', 10, 2);
            $table->decimal('commission', 10, 2);
            $table->timestamps();

            $table->foreign('Kigali Drive Rentals_invoice_id')->references('id')->on('Kigali Drive Rentals_invoices')->onDelete('cascade');
            $table->foreign('hotel_booking_id')->references('id')->on('hotel_bookings')->onDelete('cascade');
            $table->unique(['Kigali Drive Rentals_invoice_id', 'hotel_booking_id'], 'sn_inv_bookings_inv_booking_unique');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('Kigali Drive Rentals_invoice_bookings');
        Schema::dropIfExists('Kigali Drive Rentals_invoices');
    }
};

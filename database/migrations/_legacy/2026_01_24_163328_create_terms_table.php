<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('terms', function (Blueprint $table) {
            $table->id();
            $table->text('terms')->nullable();
            $table->text('privacy')->nullable();
            $table->text('privacy_details')->nullable();
            $table->text('cookies')->nullable();
            $table->text('refunds')->nullable();
            $table->text('booking_cancellation')->nullable();
            $table->text('listing_commission')->nullable();
            $table->text('payment_methods')->nullable();
            $table->text('return')->nullable();
            $table->text('support')->nullable();
            $table->unsignedBigInteger('added_by')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('terms');
    }
};

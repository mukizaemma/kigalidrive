<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('car_rental_contents', function (Blueprint $table) {
            $table->id();
            $table->string('heading')->nullable();
            $table->string('subheading')->nullable();
            $table->text('description')->nullable();
            $table->longText('fleet_content')->nullable();
            $table->longText('why_content')->nullable();
            $table->longText('services_content')->nullable();
            $table->longText('booking_content')->nullable();
            $table->string('cta_book_label')->nullable();
            $table->string('cta_quote_label')->nullable();
            $table->string('hero_image')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('car_rental_contents');
    }
};


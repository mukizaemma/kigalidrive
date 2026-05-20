<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('property_reviews')) {
            return;
        }

        Schema::create('property_reviews', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->string('guest_name')->nullable();
            $table->string('guest_email')->nullable();
            $table->unsignedBigInteger('hotel_id')->nullable();
            $table->foreignId('property_id')->nullable()->constrained()->cascadeOnDelete();
            $table->string('reviewable_type')->default('property');
            $table->unsignedTinyInteger('rating')->default(5);
            $table->text('comment')->nullable();
            $table->string('title')->nullable();
            $table->boolean('is_approved')->default(false);
            $table->boolean('is_featured')->default(false);
            $table->timestamps();
            $table->softDeletes();

            $table->index(['property_id', 'reviewable_type']);
            $table->index(['hotel_id', 'reviewable_type']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('property_reviews');
    }
};

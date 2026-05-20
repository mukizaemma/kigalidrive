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
        Schema::create('amenities', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('facility_category_id')->nullable(); // Added from start
            $table->string('title');
            $table->string('slug')->unique()->nullable(); // Added from start
            $table->string('icon')->nullable();
            $table->text('description')->nullable(); // Added from start
            $table->integer('sort_order')->default(0); // Added from start
            $table->boolean('is_active')->default(true); // Added from start
            $table->softDeletes();
            $table->timestamps();
            
            // Foreign key will be added in the "add category" migration if facility_categories exists
            // This allows amenities to be created before facility_categories if needed
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('amenities');
    }
};

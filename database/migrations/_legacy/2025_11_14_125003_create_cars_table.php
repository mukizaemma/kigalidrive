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
        Schema::create('cars', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('added_by');
            $table->string('name');
            $table->string('slug')->nullable();
            $table->string('model')->nullable();
            $table->string('fuel_type')->nullable();
            $table->integer('seats')->nullable();
            $table->string('transmission')->nullable();
            $table->decimal('price_per_day', 10, 2)->nullable();
            $table->decimal('price_per_month', 10, 2)->nullable();
            $table->decimal('price_to_buy', 10, 2)->nullable();
            $table->json('images')->nullable();
            $table->longtext('description')->nullable();
            $table->enum('status', ['available', 'rented', 'maintenance'])->default('available');
            $table->softDeletes();
            $table->timestamps();

            $table->foreign('added_by')->references('id')->on('users')->onDelete('cascade');
            $table->unsignedBigInteger('program_id');
            $table->foreign("program_id")->references("id")->on("programs")->onDelete("cascade");
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('cars');
    }
};

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
        Schema::create('trips', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('slug');
            $table->string('image')->nullable();
            $table->longText('description')->nullable();
            $table->longText('itinerary')->nullable();
            $table->longText('expectations')->nullable();
            $table->longText('recommendations')->nullable();
            $table->longText('inclusions')->nullable();
            $table->longText('exclusions')->nullable();
            $table->string('location')->nullable();
            $table->string('duration')->nullable();
            $table->string('languages')->nullable();
            $table->string('currency')->nullable();
            $table->string('maxPeople')->nullable();
            $table->string('minAge')->nullable();
            $table->unsignedBigInteger('price')->default(0);
            $table->unsignedBigInteger('couplePrice')->default(0);
            $table->enum('status', ['Active', 'Inactive'])->default('Active');

            $table->unsignedBigInteger('added_by');
            $table->foreign("added_by")->references("id")->on("users")->onDelete("cascade");
            $table->unsignedBigInteger('category_id');
            $table->foreign("category_id")->references("id")->on("categories")->onDelete("cascade");
            $table->unsignedBigInteger('program_id');
            $table->foreign("program_id")->references("id")->on("programs")->onDelete("cascade");
            $table->softDeletes();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('trips');
    }
};

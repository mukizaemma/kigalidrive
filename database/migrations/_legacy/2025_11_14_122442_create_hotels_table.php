<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{

    public function up(): void
    {
        Schema::create('hotels', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('added_by');
            $table->unsignedBigInteger('category_id');
            $table->unsignedBigInteger('program_id');
            $table->unsignedBigInteger('partner_id')->nullable(); // Added from start
            $table->string('name');
            $table->string('slug');
            $table->string('type')->nullable();
            $table->string('stars')->nullable();
            $table->string('address')->nullable(); // Added from start
            $table->string('location')->nullable();
            $table->string('phone')->nullable();
            $table->string('email')->nullable();
            $table->string('city')->nullable();
            $table->double('latitude')->nullable();
            $table->double('longitude')->nullable();
            $table->string('image')->nullable();
            $table->longtext('description')->nullable();
            $table->enum('status', ['Active', 'Inactive'])->default('Active');
            $table->softDeletes();
            $table->timestamps();

            $table->foreign('added_by')->references('id')->on('users')->onDelete('cascade');
            if (Schema::hasTable('categories')) {
                $table->foreign('category_id')->references('id')->on('categories')->onDelete('cascade');
            }
            if (Schema::hasTable('programs')) {
                $table->foreign('program_id')->references('id')->on('programs')->onDelete('cascade');
            }
            if (Schema::hasTable('partners')) {
                $table->foreign('partner_id')->references('id')->on('partners')->onDelete('set null');
            }
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('hotels');
    }
};

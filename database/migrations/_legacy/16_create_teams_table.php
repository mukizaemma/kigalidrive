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
        Schema::create('teams', function (Blueprint $table) {
            $table->id();
            $table->text('names');
            $table->text('position');
            $table->string('email')->nullable();
            $table->text('department')->nullable();
            $table->longText('description')->nullable();
            $table->string('image')->nullable();
            $table->string('facebook')->nullable();
            $table->string('instagram')->nullable();
            $table->string('twitter')->nullable();
            $table->string('youtube')->nullable();
            $table->string('website')->nullable();
            $table->string('linkedin')->nullable();
            $table->boolean('display')->default(true);
            $table->boolean('userAccess')->default(false);
            $table->enum('status', ['Active', 'Inactive'])->default('Active');
            $table->string('slug')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('teams');
    }
};

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
        Schema::create('abouts', function (Blueprint $table) {
            $table->id();
            $table->string('title')->nullable();
            $table->text('subTitle')->nullable();
            $table->longText('welcomeMessage')->nullable();
            $table->longText('WhyChooseUs')->nullable();
            $table->longText('terms')->nullable();
            $table->longText('mission')->nullable();
            $table->string('rate')->nullable();
            $table->string('image1')->nullable();
            $table->string('image2')->nullable();
            $table->string('image3')->nullable();
            $table->string('image4')->nullable();

            $table->unsignedBigInteger('user_id');
            $table->foreign("user_id")->references("id")->on("users")->onDelete("cascade");
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('abouts');
    }
};

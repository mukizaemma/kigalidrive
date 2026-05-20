<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('settings')) {
            Schema::create('settings', function (Blueprint $table) {
                $table->id();
                $table->string('company')->nullable();
                $table->string('tagline')->nullable();
                $table->string('address')->nullable();
                $table->text('map_embed')->nullable();
                $table->string('business_hours')->nullable();
                $table->string('phone')->nullable();
                $table->string('email')->nullable();
                $table->string('whatsapp')->nullable();
                $table->boolean('whatsapp_enabled')->default(true);
                $table->string('booking_email')->nullable();
                $table->boolean('booking_email_enabled')->default(true);
                $table->longText('quote')->nullable();
                $table->string('facebook')->nullable();
                $table->string('instagram')->nullable();
                $table->string('twitter')->nullable();
                $table->string('youtube')->nullable();
                $table->string('linkedin')->nullable();
                $table->string('tiktok')->nullable();
                $table->string('logo')->nullable();
                $table->string('home_header_image')->nullable();
                $table->string('home_background_image')->nullable();
                $table->string('contact_us_middle_image')->nullable();
                $table->foreignId('user_id')->constrained()->cascadeOnDelete();
                $table->softDeletes();
                $table->timestamps();
            });
        }

        if (!Schema::hasTable('abouts')) {
            Schema::create('abouts', function (Blueprint $table) {
                $table->id();
                $table->string('title')->nullable();
                $table->text('subTitle')->nullable();
                $table->longText('welcomeMessage')->nullable();
                $table->longText('background')->nullable();
                $table->longText('mission')->nullable();
                $table->longText('vision')->nullable();
                $table->longText('WhyChooseUs')->nullable();
                $table->longText('core_values')->nullable();
                $table->longText('team')->nullable();
                $table->longText('what_we_do')->nullable();
                $table->longText('commitment')->nullable();
                $table->string('rate')->nullable();
                $table->string('image1')->nullable();
                $table->string('image2')->nullable();
                $table->string('image3')->nullable();
                $table->string('image4')->nullable();
                $table->string('cta_services_url')->nullable();
                $table->string('cta_book_url')->nullable();
                $table->string('cta_contact_url')->nullable();
                $table->foreignId('user_id')->constrained()->cascadeOnDelete();
                $table->timestamps();
                $table->softDeletes();
            });
        }

        if (!Schema::hasTable('terms')) {
            Schema::create('terms', function (Blueprint $table) {
                $table->id();
                $table->string('title')->nullable();
                $table->longText('content')->nullable();
                $table->string('type')->default('general');
                $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
                $table->timestamps();
            });
        }

        if (!Schema::hasTable('slides')) {
            Schema::create('slides', function (Blueprint $table) {
                $table->id();
                $table->string('title')->nullable();
                $table->string('subtitle')->nullable();
                $table->string('image')->nullable();
                $table->string('link')->nullable();
                $table->enum('status', ['Active', 'Inactive'])->default('Active');
                $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
                $table->timestamps();
            });
        }

        if (!Schema::hasTable('blogs')) {
            Schema::create('blogs', function (Blueprint $table) {
                $table->id();
                $table->string('title');
                $table->string('slug')->unique();
                $table->longText('description')->nullable();
                $table->string('image')->nullable();
                $table->enum('status', ['Active', 'Inactive'])->default('Active');
                $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
                $table->timestamps();
                $table->softDeletes();
            });
        }

        if (!Schema::hasTable('blog_comments')) {
            Schema::create('blog_comments', function (Blueprint $table) {
                $table->id();
                $table->foreignId('blog_id')->constrained()->cascadeOnDelete();
                $table->string('names');
                $table->string('email');
                $table->text('comment');
                $table->boolean('is_approved')->default(false);
                $table->timestamps();
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('blog_comments');
        Schema::dropIfExists('blogs');
        Schema::dropIfExists('slides');
        Schema::dropIfExists('terms');
        Schema::dropIfExists('abouts');
        Schema::dropIfExists('settings');
    }
};

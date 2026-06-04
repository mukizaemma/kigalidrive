<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('home_hire_intro')) {
            Schema::create('home_hire_intro', function (Blueprint $table) {
                $table->id();
                $table->string('eyebrow')->default('Car hire in Kigali');
                $table->string('headline');
                $table->text('hook');
                $table->string('hook_highlight')->nullable();
                $table->string('cta_primary_label')->default('Browse fleet');
                $table->string('cta_primary_url')->default('/cars');
                $table->string('cta_secondary_label')->nullable();
                $table->string('cta_secondary_url')->nullable();
                $table->boolean('show_on_hero')->default(true);
                $table->boolean('is_active')->default(true);
                $table->timestamps();
            });
        }

        if (! Schema::hasTable('home_hire_scenarios')) {
            Schema::create('home_hire_scenarios', function (Blueprint $table) {
                $table->id();
                $table->string('icon')->default('fa-car');
                $table->string('title');
                $table->string('description');
                $table->unsignedSmallInteger('sort_order')->default(0);
                $table->boolean('is_active')->default(true);
                $table->timestamps();
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('home_hire_scenarios');
        Schema::dropIfExists('home_hire_intro');
    }
};

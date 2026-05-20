<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('listing_agreement_templates', function (Blueprint $table) {
            $table->id();
            $table->string('platform_name')->default('Stay Nets');
            $table->string('platform_representative_name')->nullable();
            $table->string('platform_signature_path')->nullable();
            $table->text('intro_text')->nullable();
            $table->json('sections')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('listing_agreement_templates');
    }
};

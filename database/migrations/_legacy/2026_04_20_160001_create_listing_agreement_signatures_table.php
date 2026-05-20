<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('listing_agreement_signatures', function (Blueprint $table) {
            $table->id();
            $table->morphs('signable');
            $table->string('owner_signature_path')->nullable();
            $table->timestamp('signed_at')->nullable();
            $table->timestamp('template_version_at')->nullable();
            $table->string('signer_ip', 45)->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('listing_agreement_signatures');
    }
};

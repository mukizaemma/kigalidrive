<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('enquiries')) {
            return;
        }

        Schema::create('enquiries', function (Blueprint $table) {
            $table->id();
            $table->string('form_type', 40);
            $table->string('submission_channel', 20);
            $table->string('names');
            $table->string('email');
            $table->string('phone')->nullable();
            $table->string('subject')->nullable();
            $table->text('message');
            $table->json('meta')->nullable();
            $table->string('status', 20)->default('pending');
            $table->timestamps();

            $table->index(['form_type', 'submission_channel']);
            $table->index('created_at');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('enquiries');
    }
};

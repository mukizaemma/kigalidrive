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
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->uuid('user_id')->unique();
            $table->string('role')->default(0);
            $table->string('email')->unique();
            $table->string('password');
            $table->string('company_name')->nullable();
            $table->string('field_of_work')->nullable();
            $table->string('career')->nullable();
            $table->date('birth_date')->nullable();
            $table->string('job_interest')->nullable();
            $table->string('cv')->nullable();
            $table->string('passport')->nullable();
            $table->boolean('profile_completed')->default(false);
            $table->unsignedBigInteger('role_id')->nullable();
            $table->foreign('role_id')->references('id')->on('roles')->onDelete('cascade');
            $table->unsignedBigInteger('country_origin_id')->nullable();
            $table->foreign('country_origin_id')->references('id')->on('countries')->onDelete('cascade');
            $table->unsignedBigInteger('country_work_id')->nullable();
            $table->foreign('country_work_id')->references('id')->on('countries')->onDelete('cascade');
            $table->unsignedBigInteger('plan_id')->nullable();
            $table->foreign('plan_id')->references('id')->on('plans')->onDelete('cascade');
            $table->enum('status', ['Active', 'Inactive'])->default('Active');
            $table->timestamp('email_verified_at')->nullable();
            $table->rememberToken();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('unit_extra_charges', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('unit_id');
            $table->unsignedBigInteger('extra_charge_type_id');
            $table->decimal('price', 10, 2)->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->foreign('unit_id')->references('id')->on('units')->onDelete('cascade');
            $table->foreign('extra_charge_type_id')->references('id')->on('extra_charge_types')->onDelete('cascade');
            $table->unique(['unit_id', 'extra_charge_type_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('unit_extra_charges');
    }
};

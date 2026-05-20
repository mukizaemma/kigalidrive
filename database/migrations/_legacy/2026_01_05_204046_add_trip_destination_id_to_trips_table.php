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
        Schema::table('trips', function (Blueprint $table) {
            // Make category_id nullable for backward compatibility
            $table->unsignedBigInteger('category_id')->nullable()->change();
            
            // Add trip_destination_id
            $table->unsignedBigInteger('trip_destination_id')->nullable()->after('category_id');
            $table->foreign('trip_destination_id')->references('id')->on('trip_destinations')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('trips', function (Blueprint $table) {
            $table->dropForeign(['trip_destination_id']);
            $table->dropColumn('trip_destination_id');
            // Note: We don't revert category_id to non-nullable as it might break existing data
        });
    }
};

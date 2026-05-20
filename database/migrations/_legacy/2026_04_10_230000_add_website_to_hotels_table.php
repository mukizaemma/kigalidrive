<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('hotels')) {
            return;
        }
        Schema::table('hotels', function (Blueprint $table) {
            if (! Schema::hasColumn('hotels', 'website')) {
                $table->string('website')->nullable()->after('email');
            }
        });
    }

    public function down(): void
    {
        if (! Schema::hasTable('hotels')) {
            return;
        }
        Schema::table('hotels', function (Blueprint $table) {
            if (Schema::hasColumn('hotels', 'website')) {
                $table->dropColumn('website');
            }
        });
    }
};

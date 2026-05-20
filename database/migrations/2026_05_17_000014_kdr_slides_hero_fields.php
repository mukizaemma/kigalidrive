<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('slides')) {
            return;
        }

        Schema::table('slides', function (Blueprint $table) {
            if (! Schema::hasColumn('slides', 'caption')) {
                $table->string('caption')->nullable()->after('id');
            }
            if (! Schema::hasColumn('slides', 'sort_order')) {
                $table->unsignedInteger('sort_order')->default(0)->after('status');
            }
        });
    }

    public function down(): void
    {
    }
};

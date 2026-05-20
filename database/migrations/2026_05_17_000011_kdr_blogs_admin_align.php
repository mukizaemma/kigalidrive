<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('blogs')) {
            return;
        }

        Schema::table('blogs', function (Blueprint $table) {
            if (! Schema::hasColumn('blogs', 'body')) {
                $table->longText('body')->nullable();
            }
            if (! Schema::hasColumn('blogs', 'views')) {
                $table->unsignedBigInteger('views')->default(0);
            }
            if (! Schema::hasColumn('blogs', 'added_by') && Schema::hasColumn('blogs', 'user_id')) {
                $table->unsignedBigInteger('added_by')->nullable();
            }
        });

        if (Schema::hasColumn('blogs', 'description') && Schema::hasColumn('blogs', 'body')) {
            DB::table('blogs')
                ->whereNull('body')
                ->whereNotNull('description')
                ->update(['body' => DB::raw('description')]);
        }

        if (Schema::hasColumn('blogs', 'user_id') && Schema::hasColumn('blogs', 'added_by')) {
            DB::table('blogs')
                ->whereNull('added_by')
                ->whereNotNull('user_id')
                ->update(['added_by' => DB::raw('user_id')]);
        }
    }

    public function down(): void
    {
    }
};

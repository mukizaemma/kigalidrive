<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('slides')) {
            Schema::table('slides', function (Blueprint $table) {
                if (! Schema::hasColumn('slides', 'heading')) {
                    $table->string('heading')->nullable();
                }
                if (! Schema::hasColumn('slides', 'subheading')) {
                    $table->string('subheading')->nullable();
                }
                if (! Schema::hasColumn('slides', 'button')) {
                    $table->string('button')->nullable();
                }
            });

            if (Schema::hasColumn('slides', 'title') && Schema::hasColumn('slides', 'heading')) {
                DB::table('slides')
                    ->whereNull('heading')
                    ->whereNotNull('title')
                    ->update(['heading' => DB::raw('title')]);
            }

            if (Schema::hasColumn('slides', 'subtitle') && Schema::hasColumn('slides', 'subheading')) {
                DB::table('slides')
                    ->whereNull('subheading')
                    ->whereNotNull('subtitle')
                    ->update(['subheading' => DB::raw('subtitle')]);
            }
        }

        if (! Schema::hasTable('inventory_day_caps')) {
            Schema::create('inventory_day_caps', function (Blueprint $table) {
                $table->id();
                $table->morphs('bookable');
                $table->date('date');
                $table->unsignedSmallInteger('max_remaining');
                $table->timestamps();

                $table->unique(['bookable_type', 'bookable_id', 'date'], 'inventory_day_caps_bookable_date_unique');
            });
        }
    }

    public function down(): void
    {
    }
};

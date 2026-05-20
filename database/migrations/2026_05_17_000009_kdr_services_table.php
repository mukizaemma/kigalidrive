<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('services')) {
            Schema::create('services', function (Blueprint $table) {
                $table->id();
                $table->string('title');
                $table->string('slug')->unique();
                $table->string('icon')->nullable();
                $table->string('image')->nullable();
                $table->string('excerpt', 500)->nullable();
                $table->longText('description')->nullable();
                $table->enum('status', ['Active', 'Inactive'])->default('Active');
                $table->unsignedInteger('sort_order')->default(0);
                $table->foreignId('added_by')->nullable()->constrained('users')->nullOnDelete();
                $table->softDeletes();
                $table->timestamps();
            });

            return;
        }

        Schema::table('services', function (Blueprint $table) {
            if (!Schema::hasColumn('services', 'icon')) {
                $table->string('icon')->nullable()->after('slug');
            }
            if (!Schema::hasColumn('services', 'excerpt')) {
                $table->string('excerpt', 500)->nullable()->after('image');
            }
            if (!Schema::hasColumn('services', 'sort_order')) {
                $table->unsignedInteger('sort_order')->default(0)->after('status');
            }
        });
    }

    public function down(): void
    {
        if (!Schema::hasTable('services')) {
            return;
        }

        Schema::table('services', function (Blueprint $table) {
            foreach (['icon', 'excerpt', 'sort_order'] as $col) {
                if (Schema::hasColumn('services', $col)) {
                    $table->dropColumn($col);
                }
            }
        });
    }
};

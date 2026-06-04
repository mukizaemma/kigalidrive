<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('blog_comments')) {
            return;
        }

        Schema::table('blog_comments', function (Blueprint $table) {
            if (! Schema::hasColumn('blog_comments', 'status')) {
                $table->string('status', 20)->default('Unpublished')->after('comment');
            }
            if (! Schema::hasColumn('blog_comments', 'ip_address')) {
                $table->string('ip_address', 45)->nullable()->after('email');
            }
            if (! Schema::hasColumn('blog_comments', 'rejection_reason')) {
                $table->string('rejection_reason')->nullable()->after('comment');
            }
        });

        if (Schema::hasColumn('blog_comments', 'is_approved')) {
            DB::table('blog_comments')->where('is_approved', true)->update(['status' => 'Published']);
            DB::table('blog_comments')->where('is_approved', false)->where(function ($q) {
                $q->whereNull('status')->orWhere('status', '');
            })->update(['status' => 'Unpublished']);
        }
    }

    public function down(): void
    {
        if (! Schema::hasTable('blog_comments')) {
            return;
        }

        Schema::table('blog_comments', function (Blueprint $table) {
            foreach (['status', 'ip_address', 'rejection_reason'] as $col) {
                if (Schema::hasColumn('blog_comments', $col)) {
                    $table->dropColumn($col);
                }
            }
        });
    }
};

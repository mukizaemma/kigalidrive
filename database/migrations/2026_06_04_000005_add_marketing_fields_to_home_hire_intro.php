<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('home_hire_intro')) {
            return;
        }

        Schema::table('home_hire_intro', function (Blueprint $table) {
            if (! Schema::hasColumn('home_hire_intro', 'section_eyebrow')) {
                $table->string('section_eyebrow')->nullable()->after('hook_highlight');
            }
            if (! Schema::hasColumn('home_hire_intro', 'section_title')) {
                $table->string('section_title')->nullable()->after('section_eyebrow');
            }
            if (! Schema::hasColumn('home_hire_intro', 'section_lead')) {
                $table->text('section_lead')->nullable()->after('section_title');
            }
        });
    }

    public function down(): void
    {
        if (! Schema::hasTable('home_hire_intro')) {
            return;
        }

        Schema::table('home_hire_intro', function (Blueprint $table) {
            foreach (['section_eyebrow', 'section_title', 'section_lead'] as $col) {
                if (Schema::hasColumn('home_hire_intro', $col)) {
                    $table->dropColumn($col);
                }
            }
        });
    }
};

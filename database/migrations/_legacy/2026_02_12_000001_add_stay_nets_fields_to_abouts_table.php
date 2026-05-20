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
        Schema::table('abouts', function (Blueprint $table) {
            if (!Schema::hasColumn('abouts', 'vision')) {
                $table->longText('vision')->nullable()->after('mission');
            }
            if (!Schema::hasColumn('abouts', 'what_we_do')) {
                $table->longText('what_we_do')->nullable()->after('vision');
            }
            if (!Schema::hasColumn('abouts', 'commitment')) {
                $table->longText('commitment')->nullable()->after('WhyChooseUs');
            }
            if (!Schema::hasColumn('abouts', 'cta_services_url')) {
                $table->string('cta_services_url')->nullable()->after('commitment');
            }
            if (!Schema::hasColumn('abouts', 'cta_book_url')) {
                $table->string('cta_book_url')->nullable()->after('cta_services_url');
            }
            if (!Schema::hasColumn('abouts', 'cta_contact_url')) {
                $table->string('cta_contact_url')->nullable()->after('cta_book_url');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('abouts', function (Blueprint $table) {
            $columns = ['vision', 'what_we_do', 'commitment', 'cta_services_url', 'cta_book_url', 'cta_contact_url'];
            foreach ($columns as $col) {
                if (Schema::hasColumn('abouts', $col)) {
                    $table->dropColumn($col);
                }
            }
        });
    }
};

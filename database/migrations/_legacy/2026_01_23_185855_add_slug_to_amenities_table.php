<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;
use App\Models\Amenity;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Check if column doesn't exist before adding
        if (!Schema::hasColumn('amenities', 'slug')) {
            Schema::table('amenities', function (Blueprint $table) {
                $table->string('slug')->nullable()->after('title');
            });
        }

        // Generate slugs for existing amenities that don't have one
        $amenities = Amenity::where(function($query) {
            $query->whereNull('slug')->orWhere('slug', '');
        })->get();
        
        foreach ($amenities as $amenity) {
            $slug = Str::slug($amenity->title);
            $originalSlug = $slug;
            $counter = 1;
            
            // Ensure slug is unique
            while (Amenity::where('slug', $slug)->where('id', '!=', $amenity->id)->exists()) {
                $slug = $originalSlug . '-' . $counter;
                $counter++;
            }
            
            $amenity->slug = $slug;
            $amenity->saveQuietly(); // Use saveQuietly to avoid triggering events
        }

        // Add unique constraint after populating slugs (only if not already unique)
        if (Schema::hasColumn('amenities', 'slug')) {
            try {
                Schema::table('amenities', function (Blueprint $table) {
                    $table->string('slug')->nullable()->unique()->change();
                });
            } catch (\Exception $e) {
                // Unique constraint might already exist, which is fine
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasColumn('amenities', 'slug')) {
            Schema::table('amenities', function (Blueprint $table) {
                $table->dropColumn('slug');
            });
        }
    }
};

<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\UnitType;
use Illuminate\Support\Str;

class UnitTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $unitTypes = [
            // Hotel Room Types
            ['name' => 'Standard Room', 'property_type' => 'hotel', 'description' => 'Basic room with essential amenities'],
            ['name' => 'Deluxe Room', 'property_type' => 'hotel', 'description' => 'Upgraded room with enhanced amenities'],
            ['name' => 'Superior Room', 'property_type' => 'hotel', 'description' => 'Premium room with better views'],
            ['name' => 'Executive Room', 'property_type' => 'hotel', 'description' => 'Business-focused room with workspace'],
            ['name' => 'Suite', 'property_type' => 'hotel', 'description' => 'Spacious suite with separate living area'],
            ['name' => 'Presidential Suite', 'property_type' => 'hotel', 'description' => 'Luxury suite with premium amenities'],
            ['name' => 'Family Room', 'property_type' => 'hotel', 'description' => 'Room suitable for families'],
            ['name' => 'Twin Room', 'property_type' => 'hotel', 'description' => 'Room with two separate beds'],
            ['name' => 'Double Room', 'property_type' => 'hotel', 'description' => 'Room with double bed'],
            ['name' => 'Single Room', 'property_type' => 'hotel', 'description' => 'Room for single occupancy'],
            
            // Apartment Types
            ['name' => 'Studio Apartment', 'property_type' => 'apartment', 'description' => 'Single room apartment with kitchenette'],
            ['name' => '1 Bedroom Apartment', 'property_type' => 'apartment', 'description' => 'One bedroom apartment'],
            ['name' => '2 Bedroom Apartment', 'property_type' => 'apartment', 'description' => 'Two bedroom apartment'],
            ['name' => '3 Bedroom Apartment', 'property_type' => 'apartment', 'description' => 'Three bedroom apartment'],
            ['name' => 'Penthouse', 'property_type' => 'apartment', 'description' => 'Luxury top-floor apartment'],
            ['name' => 'Loft', 'property_type' => 'apartment', 'description' => 'Open-plan apartment'],
            
            // Both
            ['name' => 'Villa', 'property_type' => 'both', 'description' => 'Standalone property'],
            ['name' => 'Cottage', 'property_type' => 'both', 'description' => 'Small standalone property'],
        ];

        foreach ($unitTypes as $index => $type) {
            UnitType::create([
                'name' => $type['name'],
                'slug' => Str::slug($type['name']),
                'property_type' => $type['property_type'],
                'description' => $type['description'],
                'sort_order' => $index,
                'is_active' => true,
            ]);
        }
    }
}











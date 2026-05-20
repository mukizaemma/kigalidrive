<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\FacilityCategory;
use Illuminate\Support\Str;

class FacilityCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            // 1. Guest Room Amenities
            [
                'name' => 'Guest Room Amenities',
                'icon' => 'fa fa-bed',
                'description' => 'In-room comfort amenities for guests',
            ],

            // 2. Hotel General Facilities
            [
                'name' => 'Hotel General Facilities',
                'icon' => 'fa fa-hotel',
                'description' => 'General hotel facilities available to all guests',
            ],

            // 3. Food & Beverage
            [
                'name' => 'Food & Beverage',
                'icon' => 'fa fa-utensils',
                'description' => 'Dining, drinks and food-related services',
            ],

            // 4. Leisure & Recreation
            [
                'name' => 'Leisure & Recreation',
                'icon' => 'fa fa-swimmer',
                'description' => 'Recreational and wellness facilities',
            ],

            // 5. Business & Event Facilities
            [
                'name' => 'Business & Event Facilities',
                'icon' => 'fa fa-briefcase',
                'description' => 'Business, meetings and event facilities',
            ],

            // 6. Family & Kids Facilities
            [
                'name' => 'Family & Kids Facilities',
                'icon' => 'fa fa-child',
                'description' => 'Facilities and services for families and children',
            ],

            // 7. Parking & Transport
            [
                'name' => 'Parking & Transport',
                'icon' => 'fa fa-car',
                'description' => 'Parking, shuttle and transport-related services',
            ],

            // 8. Guest Services & Convenience
            [
                'name' => 'Guest Services & Convenience',
                'icon' => 'fa fa-concierge-bell',
                'description' => 'Guest services and convenience features',
            ],

            // 9. Pet-Friendly Services
            [
                'name' => 'Pet-Friendly Services',
                'icon' => 'fa fa-paw',
                'description' => 'Services and facilities for guests with pets',
            ],

            // 10. Connectivity & Business Extras
            [
                'name' => 'Connectivity & Business Extras',
                'icon' => 'fa fa-wifi',
                'description' => 'Connectivity and additional business services',
            ],

            // 11. Payment & Billing Options
            [
                'name' => 'Payment & Billing Options',
                'icon' => 'fa fa-credit-card',
                'description' => 'Payment methods and billing options',
            ],
        ];

        foreach ($categories as $index => $category) {
            FacilityCategory::updateOrCreate(
                ['slug' => Str::slug($category['name'])],
                [
                    'name' => $category['name'],
                    'icon' => $category['icon'],
                    'description' => $category['description'],
                    'property_type' => 'apartment',
                    'sort_order' => $index,
                    'is_active' => true,
                ]
            );
        }
    }
}











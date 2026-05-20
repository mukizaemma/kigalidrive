<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\FacilityCategory;
use App\Models\Amenity;
use Illuminate\Support\Str;

class HotelAndApartmentFacilityCategoriesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Hotel Facility Categories with Amenities
        $hotelCategories = [
            [
                'name' => 'Guest Room Amenities',
                'property_type' => 'hotel',
                'icon' => 'fa fa-bed',
                'sort_order' => 1,
                'amenities' => [
                    'Comfortable beds (queen, king, twin)',
                    'Premium mattresses & pillows',
                    'Bedside tables & reading lights',
                    'Extra blankets',
                    'Blackout curtains',
                    'Air conditioning / climate control',
                    'Heating',
                    'Soundproof walls/windows',
                    'Carpet or hardwood floors',
                    'Desk / workspace',
                    'Seating area / sofa',
                    'Wardrobe / closet',
                    'Safe deposit box',
                    'Iron & ironing board',
                    'Full-length mirror',
                    'Luggage rack',
                    'Clothes hangers',
                    'Flat-screen TV',
                    'Cable / satellite TV',
                    'Streaming service access',
                    'Telephone',
                    'High-speed Wi-Fi',
                    'USB charging ports',
                    'Mini-bar / fridge',
                    'Tea & coffee making facilities',
                    'Complimentary bottled water',
                    'Room service menu',
                    'Private bathroom',
                    'Shower / bathtub',
                    'Hairdryer',
                    'Toiletries (soap, shampoo, conditioner)',
                    'Lotion',
                    'Towels (bath, hand, face)',
                    'Bathrobes & slippers',
                ],
            ],
            [
                'name' => 'Hotel General Facilities',
                'property_type' => 'hotel',
                'icon' => 'fa fa-building',
                'sort_order' => 2,
                'amenities' => [
                    '24/7 front desk / reception',
                    'Concierge desk',
                    'Luggage storage',
                    'Bellhop / porter service',
                    'Multilingual staff',
                    'CCTV cameras',
                    'Keycard access',
                    'Fire alarms & extinguishers',
                    'Emergency exits',
                    'Security guards',
                ],
            ],
            [
                'name' => 'Food & Beverage',
                'property_type' => 'hotel',
                'icon' => 'fa fa-utensils',
                'sort_order' => 3,
                'amenities' => [
                    'Main restaurant',
                    'Specialty / theme restaurant',
                    'Café / coffee shop',
                    'Lounge / bar',
                    'Breakfast buffet',
                    'Kids\' menu',
                    'Room service (24/7 or limited hours)',
                    'In-room dining tray',
                    'Buffet, à la carte, set menus',
                ],
            ],
            [
                'name' => 'Leisure & Recreation',
                'property_type' => 'hotel',
                'icon' => 'fa fa-swimming-pool',
                'sort_order' => 4,
                'amenities' => [
                    'Gym / fitness center',
                    'Personal trainers (optional)',
                    'Yoga / aerobics room',
                    'Sports courts (tennis, badminton)',
                    'Outdoor pool',
                    'Indoor pool',
                    'Kids\' pool',
                    'Jacuzzi / hot tub',
                    'Sauna / steam room',
                    'Spa & wellness center',
                    'Massage rooms',
                    'Beauty salon',
                    'Garden / terrace',
                    'Sun loungers',
                    'Outdoor seating',
                ],
            ],
            [
                'name' => 'Business & Event Facilities',
                'property_type' => 'hotel',
                'icon' => 'fa fa-briefcase',
                'sort_order' => 5,
                'amenities' => [
                    'Business center',
                    'Computers for guest use',
                    'Fax & photocopying',
                    'Printing services',
                    'Small meeting rooms',
                    'Conference hall',
                    'Banquet facilities',
                    'Wedding venue',
                    'AV equipment (projectors, mics, screens)',
                    'Event planning support',
                ],
            ],
            [
                'name' => 'Family & Kids Facilities',
                'property_type' => 'hotel',
                'icon' => 'fa fa-child',
                'sort_order' => 6,
                'amenities' => [
                    'Kids\' club / play area',
                    'Game room',
                    'Babysitting services',
                    'Children\'s pool',
                    'High chairs / baby cots',
                    'Family rooms',
                ],
            ],
            [
                'name' => 'Parking & Transport',
                'property_type' => 'hotel',
                'icon' => 'fa fa-car',
                'sort_order' => 7,
                'amenities' => [
                    'Free parking',
                    'Valet parking',
                    'Covered parking',
                    'Shuttle service (to/from airport or attractions)',
                    'Car rental desk',
                    'Taxi service / arrangements',
                    'EV charging stations',
                ],
            ],
            [
                'name' => 'Guest Services & Convenience',
                'property_type' => 'hotel',
                'icon' => 'fa fa-concierge-bell',
                'sort_order' => 8,
                'amenities' => [
                    'Daily housekeeping',
                    'Turndown service',
                    'Laundry & dry cleaning',
                    'Ironing service',
                    'Wake-up calls',
                    'Mail delivery / postal service',
                    'Currency exchange',
                    'ATM on site',
                    'Gift / souvenir shop',
                    'Pharmacy access',
                    'Tour desk / ticketing',
                    'Local transportation assistance',
                    'Valet services',
                ],
            ],
            [
                'name' => 'Pet-Friendly Services',
                'property_type' => 'hotel',
                'icon' => 'fa fa-paw',
                'sort_order' => 9,
                'amenities' => [
                    'Pet accommodation / beds',
                    'Pet meals',
                    'Grooming / pet wash area',
                    'Pet walking area',
                ],
            ],
            [
                'name' => 'Connectivity & Business Extras',
                'property_type' => 'hotel',
                'icon' => 'fa fa-wifi',
                'sort_order' => 10,
                'amenities' => [
                    'Free Wi-Fi (lobby & rooms)',
                    'Conference Wi-Fi',
                    'Charging stations',
                    'Dedicated business desks in rooms',
                    'Video conferencing setup',
                ],
            ],
            [
                'name' => 'Payment & Billing Options',
                'property_type' => 'hotel',
                'icon' => 'fa fa-credit-card',
                'sort_order' => 11,
                'amenities' => [
                    'Credit/debit card acceptance',
                    'Mobile pay / e-wallet',
                    'Billing to room',
                    'Group billing services',
                ],
            ],
        ];

        // Apartment Facility Categories with Amenities
        $apartmentCategories = [
            [
                'name' => 'In-Apartment Facilities',
                'property_type' => 'apartment',
                'icon' => 'fa fa-home',
                'sort_order' => 1,
                'amenities' => [
                    'Living room',
                    'Bedroom(s)',
                    'Fully equipped kitchen / kitchenette',
                    'Bathroom(s) with hot water',
                    'Dining area',
                    'Balcony / terrace',
                    'Built-in wardrobes / closets',
                    'Storage space',
                    'Air conditioning',
                    'Fans',
                    'Washing machine',
                    'Refrigerator / freezer',
                    'Microwave / oven',
                    'Cooker / stove',
                    'TV & cable connection',
                    'High-speed internet / Wi-Fi',
                    'Curtains / blinds',
                    'Safe box (optional)',
                ],
            ],
            [
                'name' => 'Building & Security Facilities',
                'property_type' => 'apartment',
                'icon' => 'fa fa-shield-alt',
                'sort_order' => 2,
                'amenities' => [
                    '24/7 security guards',
                    'CCTV surveillance',
                    'Gated compound',
                    'Controlled access',
                    'Fire extinguishers',
                    'Emergency exits',
                    'Reception / front desk',
                    'Elevators / lifts',
                    'On-site management office',
                ],
            ],
            [
                'name' => 'Parking & Transport',
                'property_type' => 'apartment',
                'icon' => 'fa fa-car',
                'sort_order' => 3,
                'amenities' => [
                    'Secure parking',
                    'Covered parking',
                    'Visitor parking',
                    'Bicycle parking',
                    'Airport shuttle (optional)',
                ],
            ],
            [
                'name' => 'Shared Amenities',
                'property_type' => 'apartment',
                'icon' => 'fa fa-users',
                'sort_order' => 4,
                'amenities' => [
                    'Garden / green area',
                    'Children\'s playground',
                    'Gym / fitness center',
                    'Swimming pool',
                    'Outdoor seating area',
                    'BBQ area',
                    'Rooftop terrace',
                    'Community lounge',
                ],
            ],
            [
                'name' => 'Services & Utilities',
                'property_type' => 'apartment',
                'icon' => 'fa fa-tools',
                'sort_order' => 5,
                'amenities' => [
                    'Reliable water supply',
                    'Electricity supply',
                    'Backup generator',
                    'Garbage collection',
                    'Housekeeping services',
                    'Laundry services',
                    'Maintenance services',
                    'Pest control',
                    'Internet service support',
                ],
            ],
            [
                'name' => 'Premium / Optional Facilities',
                'property_type' => 'apartment',
                'icon' => 'fa fa-star',
                'sort_order' => 6,
                'amenities' => [
                    'Restaurant / café',
                    'Mini‑market',
                    'Spa / wellness center',
                    'Conference / meeting room',
                    'Business center',
                    'Storage rooms',
                    'Pet-friendly facilities (where allowed)',
                ],
            ],
        ];

        // Seed Hotel Categories
        foreach ($hotelCategories as $categoryData) {
            $amenities = $categoryData['amenities'];
            unset($categoryData['amenities']);

            $category = FacilityCategory::updateOrCreate(
                [
                    'slug' => Str::slug($categoryData['name'] . '-hotel'),
                ],
                array_merge($categoryData, [
                    'description' => $categoryData['name'] . ' for hotels',
                    'is_active' => true,
                ])
            );

            // Add amenities to this category
            foreach ($amenities as $amenityTitle) {
                Amenity::updateOrCreate(
                    [
                        'title' => $amenityTitle,
                        'facility_category_id' => $category->id,
                    ],
                    [
                        'slug' => Str::slug($amenityTitle),
                        'is_active' => true,
                        'sort_order' => 0,
                    ]
                );
            }
        }

        // Seed Apartment Categories
        foreach ($apartmentCategories as $categoryData) {
            $amenities = $categoryData['amenities'];
            unset($categoryData['amenities']);

            $category = FacilityCategory::updateOrCreate(
                [
                    'slug' => Str::slug($categoryData['name'] . '-apartment'),
                ],
                array_merge($categoryData, [
                    'description' => $categoryData['name'] . ' for apartments',
                    'is_active' => true,
                ])
            );

            // Add amenities to this category
            foreach ($amenities as $amenityTitle) {
                Amenity::updateOrCreate(
                    [
                        'title' => $amenityTitle,
                        'facility_category_id' => $category->id,
                    ],
                    [
                        'slug' => Str::slug($amenityTitle),
                        'is_active' => true,
                        'sort_order' => 0,
                    ]
                );
            }
        }

        $this->command->info('Hotel and Apartment facility categories and amenities seeded successfully!');
    }
}

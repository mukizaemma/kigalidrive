<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Amenity;
use App\Models\FacilityCategory;
use Illuminate\Support\Str;

class FacilitySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Map the new ordered categories
        $guestRoomCategory = FacilityCategory::where('slug', 'guest-room-amenities')->first();
        $hotelGeneralCategory = FacilityCategory::where('slug', 'hotel-general-facilities')->first();
        $foodBeverageCategory = FacilityCategory::where('slug', 'food-beverage')->first();
        $leisureCategory = FacilityCategory::where('slug', 'leisure-recreation')->first();
        $businessEventCategory = FacilityCategory::where('slug', 'business-event-facilities')->first();
        $familyKidsCategory = FacilityCategory::where('slug', 'family-kids-facilities')->first();
        $parkingTransportCategory = FacilityCategory::where('slug', 'parking-transport')->first();
        $guestServicesCategory = FacilityCategory::where('slug', 'guest-services-convenience')->first();
        $petFriendlyCategory = FacilityCategory::where('slug', 'pet-friendly-services')->first();
        $connectivityCategory = FacilityCategory::where('slug', 'connectivity-business-extras')->first();
        $paymentBillingCategory = FacilityCategory::where('slug', 'payment-billing-options')->first();

        $facilities = [
            // 1. Guest Room Amenities
            ['title' => 'Comfortable beds (queen, king, twin)', 'icon' => 'fa fa-bed', 'category' => $guestRoomCategory],
            ['title' => 'Premium mattresses & pillows', 'icon' => 'fa fa-bed', 'category' => $guestRoomCategory],
            ['title' => 'Bedside tables & reading lights', 'icon' => 'fa fa-lightbulb', 'category' => $guestRoomCategory],
            ['title' => 'Extra blankets', 'icon' => 'fa fa-blanket', 'category' => $guestRoomCategory],
            ['title' => 'Blackout curtains', 'icon' => 'fa fa-moon', 'category' => $guestRoomCategory],
            ['title' => 'Air conditioning / climate control', 'icon' => 'fa fa-wind', 'category' => $guestRoomCategory],
            ['title' => 'Heating', 'icon' => 'fa fa-fire', 'category' => $guestRoomCategory],
            ['title' => 'Soundproof walls/windows', 'icon' => 'fa fa-volume-mute', 'category' => $guestRoomCategory],
            ['title' => 'Carpet or hardwood floors', 'icon' => 'fa fa-border-all', 'category' => $guestRoomCategory],
            ['title' => 'Desk / workspace', 'icon' => 'fa fa-briefcase', 'category' => $guestRoomCategory],
            ['title' => 'Seating area / sofa', 'icon' => 'fa fa-couch', 'category' => $guestRoomCategory],
            ['title' => 'Wardrobe / closet', 'icon' => 'fa fa-archive', 'category' => $guestRoomCategory],
            ['title' => 'Safe deposit box', 'icon' => 'fa fa-lock', 'category' => $guestRoomCategory],
            ['title' => 'Iron & ironing board', 'icon' => 'fa fa-tshirt', 'category' => $guestRoomCategory],
            ['title' => 'Full-length mirror', 'icon' => 'fa fa-circle', 'category' => $guestRoomCategory],
            ['title' => 'Luggage rack', 'icon' => 'fa fa-suitcase', 'category' => $guestRoomCategory],
            ['title' => 'Clothes hangers', 'icon' => 'fa fa-hanger', 'category' => $guestRoomCategory],
            ['title' => 'Flat-screen TV', 'icon' => 'fa fa-tv', 'category' => $guestRoomCategory],
            ['title' => 'Cable / satellite TV', 'icon' => 'fa fa-satellite-dish', 'category' => $guestRoomCategory],
            ['title' => 'Streaming service access', 'icon' => 'fa fa-play-circle', 'category' => $guestRoomCategory],
            ['title' => 'Telephone', 'icon' => 'fa fa-phone', 'category' => $guestRoomCategory],
            ['title' => 'High-speed Wi-Fi', 'icon' => 'fa fa-wifi', 'category' => $guestRoomCategory],
            ['title' => 'USB charging ports', 'icon' => 'fa fa-plug', 'category' => $guestRoomCategory],
            ['title' => 'Mini-bar / fridge', 'icon' => 'fa fa-snowflake', 'category' => $guestRoomCategory],
            ['title' => 'Tea & coffee making facilities', 'icon' => 'fa fa-coffee', 'category' => $guestRoomCategory],
            ['title' => 'Complimentary bottled water', 'icon' => 'fa fa-tint', 'category' => $guestRoomCategory],
            ['title' => 'Room service menu', 'icon' => 'fa fa-concierge-bell', 'category' => $guestRoomCategory],
            ['title' => 'Private bathroom', 'icon' => 'fa fa-bath', 'category' => $guestRoomCategory],
            ['title' => 'Shower / bathtub', 'icon' => 'fa fa-shower', 'category' => $guestRoomCategory],
            ['title' => 'Hairdryer', 'icon' => 'fa fa-wind', 'category' => $guestRoomCategory],
            ['title' => 'Toiletries (soap, shampoo, conditioner)', 'icon' => 'fa fa-soap', 'category' => $guestRoomCategory],
            ['title' => 'Lotion', 'icon' => 'fa fa-soap', 'category' => $guestRoomCategory],
            ['title' => 'Towels (bath, hand, face)', 'icon' => 'fa fa-bath', 'category' => $guestRoomCategory],
            ['title' => 'Bathrobes & slippers', 'icon' => 'fa fa-tshirt', 'category' => $guestRoomCategory],

            // 2. Hotel General Facilities
            ['title' => '24/7 front desk / reception', 'icon' => 'fa fa-concierge-bell', 'category' => $hotelGeneralCategory],
            ['title' => 'Concierge desk', 'icon' => 'fa fa-info-circle', 'category' => $hotelGeneralCategory],
            ['title' => 'Luggage storage', 'icon' => 'fa fa-suitcase', 'category' => $hotelGeneralCategory],
            ['title' => 'Bellhop / porter service', 'icon' => 'fa fa-user', 'category' => $hotelGeneralCategory],
            ['title' => 'Multilingual staff', 'icon' => 'fa fa-users', 'category' => $hotelGeneralCategory],
            ['title' => 'CCTV cameras', 'icon' => 'fa fa-video', 'category' => $hotelGeneralCategory],
            ['title' => 'Keycard access', 'icon' => 'fa fa-key', 'category' => $hotelGeneralCategory],
            ['title' => 'Fire alarms & extinguishers', 'icon' => 'fa fa-fire-extinguisher', 'category' => $hotelGeneralCategory],
            ['title' => 'Emergency exits', 'icon' => 'fa fa-door-open', 'category' => $hotelGeneralCategory],
            ['title' => 'Security guards', 'icon' => 'fa fa-shield-alt', 'category' => $hotelGeneralCategory],

            // 3. Food & Beverage
            ['title' => 'Main restaurant', 'icon' => 'fa fa-utensils', 'category' => $foodBeverageCategory],
            ['title' => 'Specialty / theme restaurant', 'icon' => 'fa fa-utensils', 'category' => $foodBeverageCategory],
            ['title' => 'Café / coffee shop', 'icon' => 'fa fa-coffee', 'category' => $foodBeverageCategory],
            ['title' => 'Lounge / bar', 'icon' => 'fa fa-glass-martini-alt', 'category' => $foodBeverageCategory],
            ['title' => 'Breakfast buffet', 'icon' => 'fa fa-bread-slice', 'category' => $foodBeverageCategory],
            ['title' => 'Kids’ menu', 'icon' => 'fa fa-child', 'category' => $foodBeverageCategory],
            ['title' => 'Room service (24/7 or limited hours)', 'icon' => 'fa fa-concierge-bell', 'category' => $foodBeverageCategory],
            ['title' => 'In-room dining tray', 'icon' => 'fa fa-utensils', 'category' => $foodBeverageCategory],
            ['title' => 'Buffet, à la carte, set menus', 'icon' => 'fa fa-list', 'category' => $foodBeverageCategory],

            // 4. Leisure & Recreation
            ['title' => 'Gym / fitness center', 'icon' => 'fa fa-dumbbell', 'category' => $leisureCategory],
            ['title' => 'Personal trainers (optional)', 'icon' => 'fa fa-user', 'category' => $leisureCategory],
            ['title' => 'Yoga / aerobics room', 'icon' => 'fa fa-heartbeat', 'category' => $leisureCategory],
            ['title' => 'Sports courts (tennis, badminton)', 'icon' => 'fa fa-basketball-ball', 'category' => $leisureCategory],
            ['title' => 'Outdoor pool', 'icon' => 'fa fa-swimming-pool', 'category' => $leisureCategory],
            ['title' => 'Indoor pool', 'icon' => 'fa fa-swimming-pool', 'category' => $leisureCategory],
            ['title' => 'Kids’ pool', 'icon' => 'fa fa-child', 'category' => $leisureCategory],
            ['title' => 'Jacuzzi / hot tub', 'icon' => 'fa fa-hot-tub', 'category' => $leisureCategory],
            ['title' => 'Sauna / steam room', 'icon' => 'fa fa-hot-tub', 'category' => $leisureCategory],
            ['title' => 'Spa & wellness center', 'icon' => 'fa fa-spa', 'category' => $leisureCategory],
            ['title' => 'Massage rooms', 'icon' => 'fa fa-spa', 'category' => $leisureCategory],
            ['title' => 'Beauty salon', 'icon' => 'fa fa-magic', 'category' => $leisureCategory],
            ['title' => 'Garden / terrace', 'icon' => 'fa fa-tree', 'category' => $leisureCategory],
            ['title' => 'Sun loungers', 'icon' => 'fa fa-sun', 'category' => $leisureCategory],
            ['title' => 'Outdoor seating', 'icon' => 'fa fa-chair', 'category' => $leisureCategory],

            // 5. Business & Event Facilities
            ['title' => 'Business center', 'icon' => 'fa fa-briefcase', 'category' => $businessEventCategory],
            ['title' => 'Computers for guest use', 'icon' => 'fa fa-desktop', 'category' => $businessEventCategory],
            ['title' => 'Fax & photocopying', 'icon' => 'fa fa-print', 'category' => $businessEventCategory],
            ['title' => 'Printing services', 'icon' => 'fa fa-print', 'category' => $businessEventCategory],
            ['title' => 'Small meeting rooms', 'icon' => 'fa fa-handshake', 'category' => $businessEventCategory],
            ['title' => 'Conference hall', 'icon' => 'fa fa-users', 'category' => $businessEventCategory],
            ['title' => 'Banquet facilities', 'icon' => 'fa fa-glass-cheers', 'category' => $businessEventCategory],
            ['title' => 'Wedding venue', 'icon' => 'fa fa-ring', 'category' => $businessEventCategory],
            ['title' => 'AV equipment (projectors, mics, screens)', 'icon' => 'fa fa-microphone', 'category' => $businessEventCategory],
            ['title' => 'Event planning support', 'icon' => 'fa fa-tasks', 'category' => $businessEventCategory],

            // 6. Family & Kids Facilities
            ['title' => 'Kids’ club / play area', 'icon' => 'fa fa-child', 'category' => $familyKidsCategory],
            ['title' => 'Game room', 'icon' => 'fa fa-gamepad', 'category' => $familyKidsCategory],
            ['title' => 'Babysitting services', 'icon' => 'fa fa-baby', 'category' => $familyKidsCategory],
            ['title' => 'Children’s pool', 'icon' => 'fa fa-child', 'category' => $familyKidsCategory],
            ['title' => 'High chairs / baby cots', 'icon' => 'fa fa-baby-carriage', 'category' => $familyKidsCategory],
            ['title' => 'Family rooms', 'icon' => 'fa fa-home', 'category' => $familyKidsCategory],

            // 7. Parking & Transport
            ['title' => 'Free parking', 'icon' => 'fa fa-parking', 'category' => $parkingTransportCategory],
            ['title' => 'Valet parking', 'icon' => 'fa fa-car', 'category' => $parkingTransportCategory],
            ['title' => 'Covered parking', 'icon' => 'fa fa-car', 'category' => $parkingTransportCategory],
            ['title' => 'Shuttle service (to/from airport or attractions)', 'icon' => 'fa fa-shuttle-van', 'category' => $parkingTransportCategory],
            ['title' => 'Car rental desk', 'icon' => 'fa fa-car', 'category' => $parkingTransportCategory],
            ['title' => 'Taxi service / arrangements', 'icon' => 'fa fa-taxi', 'category' => $parkingTransportCategory],
            ['title' => 'EV charging stations', 'icon' => 'fa fa-charging-station', 'category' => $parkingTransportCategory],

            // 8. Guest Services & Convenience
            ['title' => 'Daily housekeeping', 'icon' => 'fa fa-broom', 'category' => $guestServicesCategory],
            ['title' => 'Turndown service', 'icon' => 'fa fa-bed', 'category' => $guestServicesCategory],
            ['title' => 'Laundry & dry cleaning', 'icon' => 'fa fa-tshirt', 'category' => $guestServicesCategory],
            ['title' => 'Ironing service', 'icon' => 'fa fa-tshirt', 'category' => $guestServicesCategory],
            ['title' => 'Wake-up calls', 'icon' => 'fa fa-bell', 'category' => $guestServicesCategory],
            ['title' => 'Mail delivery / postal service', 'icon' => 'fa fa-envelope', 'category' => $guestServicesCategory],
            ['title' => 'Currency exchange', 'icon' => 'fa fa-money-bill', 'category' => $guestServicesCategory],
            ['title' => 'ATM on site', 'icon' => 'fa fa-university', 'category' => $guestServicesCategory],
            ['title' => 'Gift / souvenir shop', 'icon' => 'fa fa-gift', 'category' => $guestServicesCategory],
            ['title' => 'Pharmacy access', 'icon' => 'fa fa-medkit', 'category' => $guestServicesCategory],
            ['title' => 'Tour desk / ticketing', 'icon' => 'fa fa-ticket-alt', 'category' => $guestServicesCategory],
            ['title' => 'Local transportation assistance', 'icon' => 'fa fa-bus', 'category' => $guestServicesCategory],
            ['title' => 'Valet services', 'icon' => 'fa fa-user-tie', 'category' => $guestServicesCategory],

            // 9. Pet-Friendly Services
            ['title' => 'Pet accommodation / beds', 'icon' => 'fa fa-paw', 'category' => $petFriendlyCategory],
            ['title' => 'Pet meals', 'icon' => 'fa fa-bone', 'category' => $petFriendlyCategory],
            ['title' => 'Grooming / pet wash area', 'icon' => 'fa fa-shower', 'category' => $petFriendlyCategory],
            ['title' => 'Pet walking area', 'icon' => 'fa fa-tree', 'category' => $petFriendlyCategory],

            // 10. Connectivity & Business Extras
            ['title' => 'Free Wi-Fi (lobby & rooms)', 'icon' => 'fa fa-wifi', 'category' => $connectivityCategory],
            ['title' => 'Conference Wi-Fi', 'icon' => 'fa fa-wifi', 'category' => $connectivityCategory],
            ['title' => 'Charging stations', 'icon' => 'fa fa-bolt', 'category' => $connectivityCategory],
            ['title' => 'Dedicated business desks in rooms', 'icon' => 'fa fa-briefcase', 'category' => $connectivityCategory],
            ['title' => 'Video conferencing setup', 'icon' => 'fa fa-video', 'category' => $connectivityCategory],

            // 11. Payment & Billing Options
            ['title' => 'Credit/debit card acceptance', 'icon' => 'fa fa-credit-card', 'category' => $paymentBillingCategory],
            ['title' => 'Mobile pay / e-wallet', 'icon' => 'fa fa-mobile-alt', 'category' => $paymentBillingCategory],
            ['title' => 'Billing to room', 'icon' => 'fa fa-file-invoice-dollar', 'category' => $paymentBillingCategory],
            ['title' => 'Group billing services', 'icon' => 'fa fa-users', 'category' => $paymentBillingCategory],
        ];

        foreach ($facilities as $index => $facility) {
            if (!$facility['category']) {
                continue;
            }

            Amenity::updateOrCreate(
                [
                    'title' => $facility['title'],
                    'facility_category_id' => $facility['category']->id,
                ],
                [
                    'slug' => Str::slug($facility['title']),
                    'icon' => $facility['icon'],
                    'description' => null,
                    'sort_order' => $index,
                    'is_active' => true,
                ]
            );
        }
    }
}











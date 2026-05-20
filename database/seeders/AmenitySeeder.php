<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Support\Carbon;


class AmenitySeeder extends Seeder
{

    public function run(): void
    {
        $amenities = [
            ['title' => 'One Double Bed', 'icon' => 'fa fa-bed'],
            ['title' => 'Two Double Beds', 'icon' => 'fa fa-bed'],
            ['title' => 'One Single Bed', 'icon' => 'fa fa-bed'],
            ['title' => 'Two Single Beds', 'icon' => 'fa fa-bed'],
            ['title' => 'Two Double Bedrooms', 'icon' => 'fa fa-bed'],
            ['title' => 'Two Single Bedrooms', 'icon' => 'fa fa-bed'],
            ['title' => 'Double Bedroom', 'icon' => 'fa fa-bed'],
            ['title' => 'Single Bedroom', 'icon' => 'fa fa-bed'],
            ['title' => 'Private Washroom', 'icon' => 'fa fa-bath'],
            ['title' => 'Shared Washroom', 'icon' => 'fa fa-bath'],
            ['title' => 'Sitting Room', 'icon' => 'fa fa-couch'],
            ['title' => 'Bonfire Experience', 'icon' => 'fa fa-fire'],
            ['title' => 'Refrigerator', 'icon' => 'fa fa-snowflake'],
            ['title' => 'Dining table', 'icon' => 'fa fa-utensils'],
            ['title' => 'Electric kettle', 'icon' => 'fa fa-mug-hot'],
            ['title' => 'Garden Views', 'icon' => 'fa fa-tree'],
            ['title' => 'Lake View', 'icon' => 'fa fa-water'],
            ['title' => 'Free Wi-Fi', 'icon' => 'fa fa-wifi'],
            ['title' => 'Breakfast', 'icon' => 'fa fa-bread-slice'],
            ['title' => 'Flat-screen TV', 'icon' => 'fa fa-tv'],
            ['title' => 'Air Conditioning', 'icon' => 'fa fa-wind'],
            ['title' => 'Room Service', 'icon' => 'fa fa-bell'],
            ['title' => 'Coffee and Tea Station', 'icon' => 'fa fa-coffee'],
            ['title' => 'Towels and Toiletries', 'icon' => 'fa fa-soap'],
            ['title' => 'Closet and Hangers', 'icon' => 'fa fa-hanger'],
            ['title' => 'Working Desk', 'icon' => 'fa fa-briefcase'],
            ['title' => 'Balcony Access', 'icon' => 'fa fa-door-open'],
            ['title' => 'Daily Housekeeping', 'icon' => 'fa fa-broom'],
            ['title' => 'City View', 'icon' => 'fa fa-city'],
            ['title' => 'Free parking', 'icon' => 'fa fa-parking'],
            ['title' => 'Telephone', 'icon' => 'fa fa-phone'],
            ['title' => 'Slippers', 'icon' => 'fa fa-shoe-prints'],
            ['title' => 'Bathrobe', 'icon' => 'fa fa-tshirt'],
            ['title' => 'Shower', 'icon' => 'fa fa-shower'],
            ['title' => 'Ironing service', 'icon' => 'fa fa-tshirt'],
            ['title' => 'Socket near the bed', 'icon' => 'fa fa-plug'],
            ['title' => 'Safety deposit box', 'icon' => 'fa fa-lock'],
            ['title' => 'Fire extinguishers', 'icon' => 'fa fa-fire-extinguisher'],
            ['title' => 'Mosquito net', 'icon' => 'fa fa-bug'],
        ];

        foreach ($amenities as $amenity) {
            DB::table('amenities')->insert([
                'title' => $amenity['title'],
                'icon' => $amenity['icon'],
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]);
        }
    }
}

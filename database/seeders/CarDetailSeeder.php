<?php

namespace Database\Seeders;

use App\Models\CarDetail;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class CarDetailSeeder extends Seeder
{
    public function run(): void
    {
        $items = [
            ['name' => 'With driver', 'icon' => 'fa-user-tie', 'sort_order' => 1],
            ['name' => 'Without driver', 'icon' => 'fa-user', 'sort_order' => 2],
            ['name' => 'Fuel included', 'icon' => 'fa-gas-pump', 'sort_order' => 3],
            ['name' => 'Fuel not included', 'icon' => 'fa-gas-pump', 'sort_order' => 4],
            ['name' => 'Brand new', 'icon' => 'fa-star', 'sort_order' => 5],
            ['name' => 'Automatic', 'icon' => 'fa-cogs', 'sort_order' => 6],
            ['name' => 'Manual', 'icon' => 'fa-cogs', 'sort_order' => 7],
            ['name' => 'Petrol', 'icon' => 'fa-gas-pump', 'sort_order' => 8],
            ['name' => 'Diesel', 'icon' => 'fa-gas-pump', 'sort_order' => 9],
            ['name' => 'Hybrid', 'icon' => 'fa-leaf', 'sort_order' => 10],
            ['name' => 'Airport transfer ready', 'icon' => 'fa-plane-arrival', 'sort_order' => 11],
        ];

        foreach ($items as $item) {
            $slug = Str::slug($item['name']);

            CarDetail::query()->updateOrCreate(
                ['slug' => $slug],
                [
                    'name' => $item['name'],
                    'icon' => $item['icon'],
                    'sort_order' => $item['sort_order'],
                    'is_active' => true,
                ]
            );
        }
    }
}

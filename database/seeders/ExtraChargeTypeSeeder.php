<?php

namespace Database\Seeders;

use App\Models\ExtraChargeType;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class ExtraChargeTypeSeeder extends Seeder
{
    public function run(): void
    {
        $types = [
            ['name' => 'Airport Transfer', 'description' => 'Transfer to/from airport', 'sort_order' => 1],
            ['name' => 'Breakfast', 'description' => 'Breakfast included', 'sort_order' => 2],
            ['name' => 'Half Board', 'description' => 'Breakfast & dinner included', 'sort_order' => 3],
            ['name' => 'Full Board', 'description' => 'Breakfast, lunch & dinner included', 'sort_order' => 4],
            ['name' => 'Tour Package', 'description' => 'Guided tour or excursion', 'sort_order' => 5],
            ['name' => 'Extra Bed', 'description' => 'Additional bed', 'sort_order' => 6],
            ['name' => 'Late Check-out', 'description' => 'Extended check-out time', 'sort_order' => 7],
            ['name' => 'Early Check-in', 'description' => 'Early check-in', 'sort_order' => 8],
        ];

        foreach ($types as $item) {
            ExtraChargeType::updateOrCreate(
                ['slug' => Str::slug($item['name'])],
                [
                    'name' => $item['name'],
                    'description' => $item['description'] ?? null,
                    'sort_order' => $item['sort_order'],
                    'is_active' => true,
                ]
            );
        }
    }
}

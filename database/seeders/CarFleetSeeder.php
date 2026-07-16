<?php

namespace Database\Seeders;

use App\Models\Car;
use App\Models\User;
use Illuminate\Database\Seeder;

class CarFleetSeeder extends Seeder
{
    public function run(): void
    {
        $addedBy = User::query()->orderBy('id')->value('id');

        if (!$addedBy) {
            $this->command->error('No users found. Create an admin user first (e.g. php artisan db:seed --class=UsersSeeder).');

            return;
        }

        $fleet = [
            // —— Toyota Land Cruiser V8 ——
            [
                'slug' => 'toyota-v8-self-drive-no-fuel',
                'name' => 'Toyota Land Cruiser V8 — Self Drive (No Fuel)',
                'brand' => 'Toyota',
                'model' => 'V8',
                'fuel_type' => 'Petrol (fuel not included)',
                'seats' => 7,
                'transmission' => 'Automatic',
                'driver_available' => true,
                'self_drive' => false,
                'price_per_day' => 150000,
                'price_per_month' => 3000000,
                'description' => $this->pricingBlock([
                    'Self drive, fuel not included' => '150,000 RWF / day',
                    'Monthly rate' => '3,000,000 RWF / month',
                ]),
            ],
            [
                'slug' => 'toyota-v8-with-driver-fuel-included',
                'name' => 'Toyota Land Cruiser V8 — With Driver (Fuel Included)',
                'brand' => 'Toyota',
                'model' => 'V8',
                'fuel_type' => 'Petrol (fuel included)',
                'seats' => 7,
                'transmission' => 'Automatic',
                'driver_available' => true,
                'self_drive' => false,
                'price_per_day' => 250000,
                'price_per_month' => 3000000,
                'description' => $this->pricingBlock([
                    'With driver, fuel included' => '250,000 RWF / day',
                    'Monthly rate' => '3,000,000 RWF / month',
                ]),
            ],
            [
                'slug' => 'toyota-v8-with-driver-no-fuel',
                'name' => 'Toyota Land Cruiser V8 — With Driver (No Fuel)',
                'brand' => 'Toyota',
                'model' => 'V8',
                'fuel_type' => 'Petrol (fuel not included)',
                'seats' => 7,
                'transmission' => 'Automatic',
                'driver_available' => true,
                'self_drive' => false,
                'price_per_day' => 170000,
                'price_per_month' => 3000000,
                'description' => $this->pricingBlock([
                    'With driver, fuel not included' => '170,000 RWF / day',
                    'Monthly rate' => '3,000,000 RWF / month',
                ]),
            ],

            // —— Toyota TXL ——
            [
                'slug' => 'toyota-txl-with-driver-fuel-included',
                'name' => 'Toyota TXL — With Driver (Fuel Included)',
                'brand' => 'Toyota',
                'model' => 'TXL',
                'fuel_type' => 'Petrol (fuel included)',
                'seats' => 7,
                'transmission' => 'Automatic',
                'driver_available' => true,
                'self_drive' => false,
                'price_per_day' => 250000,
                'price_per_month' => 2000000,
                'description' => $this->pricingBlock([
                    'With driver, fuel included' => '250,000 RWF / day',
                    'Monthly rate' => '2,000,000 RWF / month',
                ]),
            ],
            [
                'slug' => 'toyota-txl-self-drive-no-fuel',
                'name' => 'Toyota TXL — Self Drive (No Fuel)',
                'brand' => 'Toyota',
                'model' => 'TXL',
                'fuel_type' => 'Petrol (fuel not included)',
                'seats' => 7,
                'transmission' => 'Automatic',
                'driver_available' => true,
                'self_drive' => false,
                'price_per_day' => 130000,
                'price_per_month' => 2000000,
                'description' => $this->pricingBlock([
                    'Self drive, fuel not included' => '130,000 RWF / day',
                    'Monthly rate' => '2,000,000 RWF / month',
                ]),
            ],
            [
                'slug' => 'toyota-txl-with-driver-no-fuel',
                'name' => 'Toyota TXL — With Driver (No Fuel)',
                'brand' => 'Toyota',
                'model' => 'TXL',
                'fuel_type' => 'Petrol (fuel not included)',
                'seats' => 7,
                'transmission' => 'Automatic',
                'driver_available' => true,
                'self_drive' => false,
                'price_per_day' => 170000,
                'price_per_month' => 2000000,
                'description' => $this->pricingBlock([
                    'With driver, fuel not included' => '170,000 RWF / day',
                    'Monthly rate' => '2,000,000 RWF / month',
                ]),
            ],

            // —— Toyota RAV4 (2022–2024) ——
            [
                'slug' => 'toyota-rav4-2022-with-driver-fuel-included',
                'name' => 'Toyota RAV4 (2022–2024) — With Driver (Fuel Included)',
                'brand' => 'Toyota',
                'model' => 'RAV4 (2022–2024)',
                'fuel_type' => 'Hybrid',
                'seats' => 5,
                'transmission' => 'Automatic',
                'driver_available' => true,
                'self_drive' => false,
                'price_per_day' => 150000,
                'price_per_month' => 1700000,
                'description' => '<p>Hybrid, full condition.</p>' . $this->pricingBlock([
                    'With driver, fuel included' => '150,000 RWF / day',
                    'Monthly rate' => '1,700,000 RWF / month',
                ]),
            ],
            [
                'slug' => 'toyota-rav4-2022-self-drive',
                'name' => 'Toyota RAV4 (2022–2024) — Self Drive',
                'brand' => 'Toyota',
                'model' => 'RAV4 (2022–2024)',
                'fuel_type' => 'Hybrid',
                'seats' => 5,
                'transmission' => 'Automatic',
                'driver_available' => true,
                'self_drive' => false,
                'price_per_day' => 80000,
                'price_per_month' => 1700000,
                'description' => '<p>Hybrid, full condition. No driver; fuel not included.</p>' . $this->pricingBlock([
                    'Within Kigali' => '80,000 RWF / day',
                    'Outside Kigali' => '90,000 RWF / day',
                    'Monthly rate' => '1,700,000 RWF / month',
                ]),
            ],

            // —— Kia Sorento (2018) ——
            [
                'slug' => 'kia-sorento-2018-with-driver-fuel-included',
                'name' => 'Kia Sorento (2018) — With Driver (Fuel Included)',
                'brand' => 'Kia',
                'model' => 'Sorento (2018)',
                'fuel_type' => 'Petrol (fuel included)',
                'seats' => 7,
                'transmission' => 'Automatic',
                'driver_available' => true,
                'self_drive' => false,
                'price_per_day' => 150000,
                'price_per_month' => 1700000,
                'description' => $this->pricingBlock([
                    'With driver, fuel included' => '150,000 RWF / day',
                    'Monthly rate' => '1,700,000 RWF / month',
                ]),
            ],
            [
                'slug' => 'kia-sorento-2018-self-drive',
                'name' => 'Kia Sorento (2018) — Self Drive',
                'brand' => 'Kia',
                'model' => 'Sorento (2018)',
                'fuel_type' => 'Petrol (fuel not included)',
                'seats' => 7,
                'transmission' => 'Automatic',
                'driver_available' => true,
                'self_drive' => false,
                'price_per_day' => 80000,
                'price_per_month' => 1700000,
                'description' => '<p>No driver; fuel not included.</p>' . $this->pricingBlock([
                    'Within Kigali' => '80,000 RWF / day',
                    'Outside Kigali' => '90,000 RWF / day',
                    'Monthly rate' => '1,700,000 RWF / month',
                ]),
            ],

            // —— Kia Sorento (2012) ——
            [
                'slug' => 'kia-sorento-2012-self-drive',
                'name' => 'Kia Sorento (2012) — Self Drive',
                'brand' => 'Kia',
                'model' => 'Sorento (2012)',
                'fuel_type' => 'Petrol (fuel not included)',
                'seats' => 7,
                'transmission' => 'Automatic',
                'driver_available' => true,
                'self_drive' => false,
                'price_per_day' => 60000,
                'price_per_month' => 1200000,
                'description' => '<p>No driver; fuel not included.</p>' . $this->pricingBlock([
                    'Within Kigali' => '60,000 RWF / day',
                    'Outside Kigali' => '70,000 RWF / day',
                    'Monthly rate' => '1,200,000 RWF / month',
                ]),
            ],

            // —— Kia K7 ——
            [
                'slug' => 'kia-k7-self-drive',
                'name' => 'Kia K7 — Self Drive',
                'brand' => 'Kia',
                'model' => 'K7',
                'fuel_type' => 'Petrol (fuel not included)',
                'seats' => 5,
                'transmission' => 'Automatic',
                'driver_available' => true,
                'self_drive' => false,
                'price_per_day' => 60000,
                'price_per_month' => 1200000,
                'description' => '<p>No driver; fuel not included.</p>' . $this->pricingBlock([
                    'Within Kigali' => '60,000 RWF / day',
                    'Outside Kigali' => '70,000 RWF / day',
                    'Monthly rate' => '1,200,000 RWF / month',
                ]),
            ],

            // —— Kia K5 ——
            [
                'slug' => 'kia-k5-self-drive',
                'name' => 'Kia K5 — Self Drive',
                'brand' => 'Kia',
                'model' => 'K5',
                'fuel_type' => 'Petrol (fuel not included)',
                'seats' => 5,
                'transmission' => 'Automatic',
                'driver_available' => true,
                'self_drive' => false,
                'price_per_day' => 45000,
                'price_per_month' => 1000000,
                'description' => '<p>No driver; fuel not included.</p>' . $this->pricingBlock([
                    'Within Kigali' => '45,000 RWF / day',
                    'Outside Kigali' => '50,000 RWF / day',
                    'Monthly rate' => '1,000,000 RWF / month',
                ]),
            ],

            // —— Hyundai Sonata ——
            [
                'slug' => 'hyundai-sonata-self-drive',
                'name' => 'Hyundai Sonata — Self Drive',
                'brand' => 'Hyundai',
                'model' => 'Sonata',
                'fuel_type' => 'Petrol (fuel not included)',
                'seats' => 5,
                'transmission' => 'Automatic',
                'driver_available' => true,
                'self_drive' => false,
                'price_per_day' => 40000,
                'price_per_month' => 900000,
                'description' => '<p>No driver; fuel not included.</p>' . $this->pricingBlock([
                    'Within Kigali' => '40,000 RWF / day',
                    'Outside Kigali' => '45,000 RWF / day',
                    'Monthly rate' => '900,000 RWF / month',
                ]),
            ],

            // —— Toyota Prius ——
            [
                'slug' => 'toyota-prius-self-drive',
                'name' => 'Toyota Prius — Self Drive',
                'brand' => 'Toyota',
                'model' => 'Prius',
                'fuel_type' => 'Hybrid (fuel not included)',
                'seats' => 5,
                'transmission' => 'Automatic',
                'driver_available' => true,
                'self_drive' => false,
                'price_per_day' => 40000,
                'price_per_month' => 900000,
                'description' => '<p>No driver; fuel not included.</p>' . $this->pricingBlock([
                    'Within Kigali' => '40,000 RWF / day',
                    'Outside Kigali' => '45,000 RWF / day',
                    'Monthly rate' => '900,000 RWF / month',
                ]),
            ],
        ];

        foreach ($fleet as $row) {
            Car::updateOrCreate(
                ['slug' => $row['slug']],
                array_merge($row, [
                    'added_by' => $addedBy,
                    'listing_type' => 'rent',
                    'status' => 'available',
                ])
            );
        }

        $this->command->info('Seeded ' . count($fleet) . ' rental vehicles.');
    }

    /**
     * @param  array<string, string>  $lines
     */
    private function pricingBlock(array $lines): string
    {
        $html = '<ul class="mb-0">';
        foreach ($lines as $label => $price) {
            $html .= '<li><strong>' . e($label) . ':</strong> ' . e($price) . '</li>';
        }
        $html .= '</ul>';

        return $html;
    }
}

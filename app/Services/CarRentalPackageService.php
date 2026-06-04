<?php

namespace App\Services;

use App\Models\Car;
use Illuminate\Validation\ValidationException;

class CarRentalPackageService
{
    /**
     * @return array<int, array{key: string, label: string, period: string, with_driver: bool, price: float, price_formatted: string, rental_duration: string}>
     */
    public function packagesFor(Car $car): array
    {
        $selfDrive = (bool) $car->self_drive;
        $driverAvailable = (bool) $car->driver_available;

        if (! $selfDrive && ! $driverAvailable) {
            $selfDrive = true;
            $driverAvailable = true;
        }

        $periods = [
            'day' => ['price' => $car->price_per_day, 'label' => 'Daily', 'duration' => 'daily'],
            'week' => ['price' => $car->price_per_week, 'label' => 'Weekly', 'duration' => 'weekly'],
            'month' => ['price' => $car->price_per_month, 'label' => 'Monthly', 'duration' => 'monthly'],
        ];

        $packages = [];

        foreach ($periods as $period => $meta) {
            $price = (float) ($meta['price'] ?? 0);
            if ($price <= 0) {
                continue;
            }

            if ($selfDrive) {
                $packages[] = $this->buildPackage($period, false, $meta, $price);
            }
            if ($driverAvailable) {
                $packages[] = $this->buildPackage($period, true, $meta, $price);
            }
        }

        return $packages;
    }

    /**
     * @param  array{label: string, duration: string}  $meta
     * @return array{key: string, label: string, period: string, with_driver: bool, price: float, price_formatted: string, rental_duration: string}
     */
    protected function buildPackage(string $period, bool $withDriver, array $meta, float $price): array
    {
        $driverLabel = $withDriver ? 'With driver' : 'Self-drive';
        $suffix = $withDriver ? 'driver' : 'self';

        return [
            'key' => $period . '_' . $suffix,
            'label' => $meta['label'] . ' — ' . $driverLabel,
            'period' => $period,
            'with_driver' => $withDriver,
            'price' => $price,
            'price_formatted' => formatUsd($price),
            'rental_duration' => $meta['duration'],
        ];
    }

    /**
     * @return array{key: string, label: string, period: string, with_driver: bool, price: float, price_formatted: string, rental_duration: string}|null
     */
    public function findPackage(Car $car, string $key): ?array
    {
        foreach ($this->packagesFor($car) as $package) {
            if ($package['key'] === $key) {
                return $package;
            }
        }

        return null;
    }

    public function labelFor(Car $car, ?string $key): ?string
    {
        if (! $key) {
            return null;
        }

        $package = $this->findPackage($car, $key);

        return $package['label'] ?? $key;
    }

    /**
     * @return array<int, string>
     */
    public function allowedKeys(Car $car): array
    {
        return array_map(fn (array $p) => $p['key'], $this->packagesFor($car));
    }

    public function assertValidPackage(Car $car, string $key): array
    {
        $package = $this->findPackage($car, $key);

        if (! $package) {
            throw ValidationException::withMessages([
                'rental_package' => 'Please select a valid rental package.',
            ]);
        }

        return $package;
    }
}

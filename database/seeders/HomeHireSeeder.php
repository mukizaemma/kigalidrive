<?php

namespace Database\Seeders;

use App\Models\HomeHireIntro;
use App\Models\HomeHireScenario;
use Illuminate\Database\Seeder;

class HomeHireSeeder extends Seeder
{
    public function run(): void
    {
        HomeHireIntro::query()->updateOrCreate(
            ['id' => 1],
            [
                'eyebrow' => 'Car hire in Kigali',
                'headline' => 'The right car for your Rwanda moment',
                'hook' => 'Premium rentals for visitors, diaspora, and Kigali businesses — one team, clear USD pricing.',
                'hook_highlight' => 'Kigali Drive Rentals',
                'section_eyebrow' => '',
                'section_title' => 'Rent with confidence. Drive Rwanda your way.',
                'section_lead' => '',
                'cta_primary_label' => 'Browse our listings',
                'cta_primary_url' => '/cars',
                'cta_secondary_label' => 'Tell us what you need',
                'cta_secondary_url' => '/contact',
                'show_on_hero' => true,
                'is_active' => true,
            ]
        );

        if (HomeHireScenario::exists()) {
            return;
        }

        $items = [
            ['icon' => 'fa-tags', 'title' => 'Clear USD pricing', 'description' => 'Listed daily, weekly & monthly rates — know the cost upfront', 'sort_order' => 1],
            ['icon' => 'fa-user-tie', 'title' => 'Driver or self-drive', 'description' => 'Chauffeur for meetings or drive yourself with flexible terms', 'sort_order' => 2],
            ['icon' => 'fa-comments', 'title' => 'Fast WhatsApp booking', 'description' => 'Share your dates and route — we confirm quickly', 'sort_order' => 3],
            ['icon' => 'fa-plane-arrival', 'title' => 'Airport & city transfers', 'description' => 'KGL pickups and comfortable rides across Kigali', 'sort_order' => 4],
            ['icon' => 'fa-shield', 'title' => 'Maintained fleet', 'description' => 'Vehicles prepared for local roads and upcountry trips', 'sort_order' => 5],
            ['icon' => 'fa-route', 'title' => 'Upcountry ready', 'description' => 'Vehicles suited for Kigali, safari, and weekend getaways', 'sort_order' => 6],
        ];

        foreach ($items as $item) {
            HomeHireScenario::create($item + ['is_active' => true]);
        }
    }
}

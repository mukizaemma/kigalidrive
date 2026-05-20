<?php

namespace Database\Seeders;

use App\Models\Service;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class KdrServicesSeeder extends Seeder
{
    public function run(): void
    {
        $addedBy = User::query()->orderBy('id')->value('id');

        $items = [
            [
                'title' => 'Car rental',
                'icon' => 'fa-car-side',
                'excerpt' => 'Daily, weekly, and monthly rentals — self-drive or with a professional driver.',
                'description' => "Browse our fleet of sedans, SUVs, and luxury vehicles.\n\n• Self-drive and chauffeur options\n• Transparent RWF pricing\n• Kigali and upcountry trips\n• Flexible rental periods",
                'sort_order' => 1,
            ],
            [
                'title' => 'Car sales',
                'icon' => 'fa-handshake',
                'excerpt' => 'Buy quality pre-owned and new vehicles through our trusted network.',
                'description' => "We help you find the right vehicle for personal or business use.\n\n• Verified listings\n• Inspection support\n• Financing guidance on request",
                'sort_order' => 2,
            ],
            [
                'title' => 'Apartment & villa rentals',
                'icon' => 'fa-building',
                'excerpt' => 'Furnished apartments and villas for short stays and long-term living in Kigali.',
                'description' => "Premium stays for tourists, corporates, NGOs, and families.\n\n• Verified properties\n• Online booking\n• Amenities listed clearly\n• Flexible lease terms",
                'sort_order' => 3,
            ],
            [
                'title' => 'Chauffeur & airport transfer',
                'icon' => 'fa-plane-arrival',
                'excerpt' => 'Meet-and-greet, airport pickups, and executive transport across Rwanda.',
                'description' => "Professional drivers who know Kigali and major routes.\n\n• Kigali International Airport transfers\n• Corporate travel\n• Event and delegation transport",
                'sort_order' => 4,
            ],
            [
                'title' => 'Corporate fleet solutions',
                'icon' => 'fa-briefcase',
                'excerpt' => 'Dedicated vehicles and billing for companies, NGOs, and embassies.',
                'description' => "Tailored packages for teams that need reliable mobility.\n\n• Monthly contracts\n• Multiple vehicle types\n• Priority support",
                'sort_order' => 5,
            ],
            [
                'title' => 'List your property or vehicle',
                'icon' => 'fa-list',
                'excerpt' => 'Owners and agents: list cars or apartments with Kigali Drive Rentals.',
                'description' => "Reach local and international clients through our platform.\n\n• Simple listing process\n• Admin review and approval\n• Marketing on our website",
                'sort_order' => 6,
            ],
        ];

        foreach ($items as $item) {
            $slug = Str::slug($item['title']);

            Service::updateOrCreate(
                ['slug' => $slug],
                array_merge($item, [
                    'slug' => $slug,
                    'status' => 'Active',
                    'added_by' => $addedBy,
                ])
            );
        }

        $this->command?->info('Seeded ' . count($items) . ' services.');
    }
}

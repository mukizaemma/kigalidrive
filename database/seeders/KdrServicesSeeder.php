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
                'title' => 'Chauffeur & airport transfer',
                'icon' => 'fa-plane-arrival',
                'excerpt' => 'Meet-and-greet, airport pickups, and executive transport across Rwanda.',
                'description' => "Professional drivers who know Kigali and major routes.\n\n• Kigali International Airport transfers\n• Corporate travel\n• Event and delegation transport",
                'sort_order' => 3,
            ],
            [
                'title' => 'Corporate fleet solutions',
                'icon' => 'fa-briefcase',
                'excerpt' => 'Dedicated vehicles and billing for companies, NGOs, and embassies.',
                'description' => "Tailored packages for teams that need reliable mobility.\n\n• Monthly contracts\n• Multiple vehicle types\n• Priority support",
                'sort_order' => 4,
            ],
            [
                'title' => 'Vehicle delivery & inspection',
                'icon' => 'fa-clipboard-check',
                'excerpt' => 'We arrange viewings, test drives, and delivery for rentals and purchases.',
                'description' => "Convenient handover for renters and buyers.\n\n• Scheduled viewings\n• Pre-rental checks\n• Airport or hotel delivery on request",
                'sort_order' => 5,
            ],
            [
                'title' => 'List your car',
                'icon' => 'fa-list',
                'excerpt' => 'Owners and dealers: list vehicles for rent or sale with Kigali Drive Rentals.',
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

        Service::query()
            ->where(function ($q) {
                $q->where('slug', 'like', '%apartment%')
                    ->orWhere('slug', 'like', '%villa%')
                    ->orWhere('title', 'like', '%apartment%')
                    ->orWhere('title', 'like', '%List your property%');
            })
            ->update(['status' => 'Inactive']);

        $this->command?->info('Seeded ' . count($items) . ' services.');
    }
}

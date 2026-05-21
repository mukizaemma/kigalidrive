<?php

namespace Database\Seeders;

use App\Models\About;
use App\Models\Setting;
use Illuminate\Database\Seeder;

class KigaliDriveSeeder extends Seeder
{
    public function run(): void
    {
        $setting = Setting::first();
        if ($setting) {
            $setting->update([
                'company' => 'Kigali Drive Rentals',
                'tagline' => 'Drive Better. Buy Smarter. Rent with Confidence.',
                'address' => 'Kisimenti, Kigali, Rwanda',
                'business_hours' => 'Open Daily',
                'whatsapp_enabled' => true,
                'booking_email_enabled' => true,
            ]);
        }

        $about = About::first();
        if ($about) {
            $about->update([
                'title' => 'Welcome to Kigali Drive Rentals',
                'subTitle' => 'Rwanda\'s trusted car rental and sales partner',
                'welcomeMessage' => '<p>At Kigali Drive Rentals, we connect clients to quality vehicles for rent and purchase across Kigali and Rwanda.</p><p>Based in Kisimenti, we serve tourists, business travelers, NGOs, corporate teams, and local buyers who need reliable mobility.</p>',
                'mission' => 'To provide reliable service, trusted vehicle listings, and memorable customer experiences.',
                'vision' => 'To be Kigali\'s most trusted platform for car rentals and vehicle sales.',
                'WhyChooseUs' => 'Wide vehicle selection|Rent or buy|Flexible terms|Transparent pricing|Multilingual team|Fast support|Easy booking|Local expertise',
                'core_values' => 'Integrity|Excellence|Transparency|Customer-first|Innovation',
                'team' => 'A dynamic team speaking English, French, Kinyarwanda, and Swahili — ready to serve local and international clients.',
                'background' => 'Kigali Drive Rentals was built to offer a professional, modern marketplace for car rentals and sales in Kigali.',
                'cta_services_url' => null,
                'cta_book_url' => null,
            ]);
        }
    }
}

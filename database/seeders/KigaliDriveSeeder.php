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
                'tagline' => 'Drive Better. Stay Smarter. Invest Confidently.',
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
                'subTitle' => 'Your trusted partner for mobility and housing in Rwanda',
                'welcomeMessage' => '<p>At Kigali Drive Rentals, we simplify mobility and housing solutions in Rwanda by connecting clients to quality vehicles and premium properties.</p><p>Based in Kisimenti, we proudly serve tourists, business travelers, families, NGOs, corporate clients, property seekers, and investors.</p>',
                'mission' => 'To provide reliable service, trusted listings, and memorable customer experiences.',
                'vision' => 'To be Kigali\'s most trusted platform for car rentals, sales, and premium apartments.',
                'WhyChooseUs' => 'Wide vehicle selection|Verified apartments|Flexible terms|Transparent pricing|Multilingual team|Fast support|Easy booking|Local expertise',
                'core_values' => 'Integrity|Excellence|Transparency|Customer-first|Innovation',
                'team' => 'A dynamic team speaking English, French, Kinyarwanda, and Swahili — ready to serve local and international clients.',
                'background' => 'Kigali Drive Rentals was built to offer a professional, modern alternative for car and apartment listings in Kigali.',
            ]);
        }
    }
}

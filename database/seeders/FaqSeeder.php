<?php

namespace Database\Seeders;

use App\Models\Faq;
use Illuminate\Database\Seeder;

class FaqSeeder extends Seeder
{
    public function run(): void
    {
        if (Faq::count() > 0) {
            return;
        }

        $items = [
            ['How do I book a car without an account?', 'Choose a vehicle, fill in the reservation form, and submit via email or WhatsApp. You receive a booking reference instantly.'],
            ['What rental periods are available?', 'Daily, weekly, monthly, annual, or custom durations — with or without a driver.'],
            ['Can I buy a car through Kigali Drive Rentals?', 'Yes. Browse our Cars for Sale section or contact us for vehicles listed for purchase.'],
            ['Do you offer self-drive and chauffeur options?', 'Most vehicles support self-drive, with-driver, or both. Check each listing for availability.'],
            ['Where do I leave a review?', 'We use Google Business reviews only. Open our Google Reviews page and tap “Write a review on Google” — reviews are not submitted on this website.'],
            ['How can I list my car for rent or sale?', 'Use List your car and our team will review your submission before publishing.'],
        ];

        foreach ($items as $i => [$question, $answer]) {
            Faq::create([
                'question' => $question,
                'answer' => $answer,
                'sort_order' => $i + 1,
                'is_active' => true,
            ]);
        }
    }
}

<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ListingAgreementTemplate;
use App\Models\Setting;
use Illuminate\Http\Request;

class RentalAgreementController extends Controller
{
    public function edit()
    {
        $template = ListingAgreementTemplate::query()
            ->where('template_type', 'car_rental')
            ->first();

        if (!$template) {
            $template = ListingAgreementTemplate::create([
                'template_type' => 'car_rental',
                'platform_name' => 'Kigali Drive Rentals',
                'intro_text' => $this->defaultIntro(),
                'sections' => $this->defaultSections(),
            ]);
        }

        $setting = Setting::first();

        return view('admin.rental-agreement.edit', compact('template', 'setting'));
    }

    public function update(Request $request)
    {
        $template = ListingAgreementTemplate::query()
            ->where('template_type', 'car_rental')
            ->firstOrFail();

        $validated = $request->validate([
            'platform_name' => 'required|string|max:255',
            'platform_representative_name' => 'nullable|string|max:255',
            'intro_text' => 'required|string',
            'sections' => 'required|array',
            'sections.*.heading' => 'required|string',
            'sections.*.items' => 'required|array',
            'sections.*.items.*' => 'nullable|string',
        ]);

        $template->update($validated);

        return back()->with('success', 'Car rental agreement template saved.');
    }

    public function preview()
    {
        $template = ListingAgreementTemplate::query()
            ->where('template_type', 'car_rental')
            ->firstOrFail();

        $setting = Setting::first();

        return view('admin.rental-agreement.preview', compact('template', 'setting'));
    }

    protected function defaultIntro(): string
    {
        return "This Car Rental Agreement is entered into between Kigali Drive Rentals (\"Company\") and the Client named in the reservation.";
    }

    protected function defaultSections(): array
    {
        return [
            [
                'heading' => '1. Vehicle & rental period',
                'items' => ['Vehicle details, pickup/return dates, and locations are as stated in the confirmed reservation.'],
            ],
            [
                'heading' => '2. Driver options',
                'items' => ['Rental may be with or without driver as selected. Client is responsible for valid documents when self-driving.'],
            ],
            [
                'heading' => '3. Payment & deposit',
                'items' => ['Fees, deposits, and fuel policy are communicated before confirmation.'],
            ],
            [
                'heading' => '4. Liability',
                'items' => ['Client agrees to use the vehicle responsibly and report incidents immediately.'],
            ],
        ];
    }
}

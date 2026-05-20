<?php

namespace App\Http\Controllers;

use App\Models\Enquiry;
use App\Models\Setting;
use App\Services\EnquirySubmissionService;
use Illuminate\Http\Request;

class EnquiryController extends Controller
{
    public function __construct(
        protected EnquirySubmissionService $submitter
    ) {}

    public function store(Request $request)
    {
        if ($request->filled('website_url')) {
            abort(422, 'Spam detected.');
        }

        if (! $request->filled('form_type')) {
            $request->merge(['form_type' => Enquiry::FORM_CONTACT]);
        }

        $validated = $request->validate([
            'form_type' => 'required|in:contact,car_enquiry,apartment_enquiry',
            'channel' => 'required|in:email,whatsapp,form',
            'names' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'nullable|string|max:50',
            'subject' => 'nullable|string|max:255',
            'message' => 'required|string|max:5000',
            'car_type' => 'nullable|string|max:255',
            'people' => 'nullable|integer|min:1',
            'rental_date' => 'nullable|date',
        ]);

        $setting = Setting::firstOrFail();

        $meta = [];
        $metaLines = [];

        if ($validated['form_type'] === Enquiry::FORM_CAR_ENQUIRY) {
            if (! empty($validated['car_type'])) {
                $meta['car_type'] = $validated['car_type'];
                $metaLines[] = 'Car type: ' . $validated['car_type'];
            }
            if (! empty($validated['people'])) {
                $meta['people'] = $validated['people'];
                $metaLines[] = 'People: ' . $validated['people'];
            }
            if (! empty($validated['rental_date'])) {
                $meta['rental_date'] = $validated['rental_date'];
                $metaLines[] = 'Preferred date: ' . $validated['rental_date'];
            }
        }

        $subject = $validated['subject'] ?? match ($validated['form_type']) {
            Enquiry::FORM_CAR_ENQUIRY => 'Car rental enquiry',
            Enquiry::FORM_APARTMENT_ENQUIRY => 'Apartment enquiry',
            default => 'General enquiry',
        };

        $redirectRoute = match ($validated['form_type']) {
            Enquiry::FORM_CAR_ENQUIRY => 'showCars',
            Enquiry::FORM_APARTMENT_ENQUIRY => 'apartments',
            default => 'contact',
        };

        return $this->submitter->storeAndDispatch(
            $setting,
            $validated['form_type'],
            $validated['channel'],
            [
                'names' => $validated['names'],
                'email' => $validated['email'],
                'phone' => $validated['phone'] ?? null,
                'subject' => $subject,
                'message' => $validated['message'],
                'meta' => $meta ?: null,
                'meta_lines' => $metaLines,
            ],
            $redirectRoute
        );
    }
}

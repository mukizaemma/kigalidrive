<?php

namespace App\Http\Controllers;

use App\Models\Setting;
use App\Services\ListingSubmissionService;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class ListingRequestController extends Controller
{
    public function __construct(
        protected ListingSubmissionService $submitter
    ) {}

    public function create()
    {
        return view('frontend.list-your-property');
    }

    public function store(Request $request)
    {
        if ($request->filled('website_url')) {
            abort(422, 'Spam detected.');
        }

        $channel = $request->input('channel');

        $validated = $request->validate([
            'product_type' => 'required|in:car,apartment',
            'ad_type' => 'required|in:rent,sale',
            'contact_name' => 'required|string|max:255',
            'phone' => 'required|string|max:50',
            'email' => [
                Rule::requiredIf(in_array($channel, ['email', 'form'], true)),
                'nullable',
                'email',
                'max:255',
            ],
            'amount' => 'nullable|numeric|min:0',
            'location' => 'required|string|max:255',
            'vehicle_info' => 'nullable|string|max:255',
            'details' => 'nullable|string|max:3000',
            'channel' => 'required|in:email,whatsapp,form',
        ]);

        $details = trim((string) ($validated['details'] ?? ''));
        if (! empty($validated['vehicle_info'])) {
            $vehicleLine = 'Vehicle: ' . trim($validated['vehicle_info']);
            $details = $details !== '' ? $vehicleLine . "\n\n" . $details : $vehicleLine;
        }

        $setting = Setting::firstOrFail();

        return $this->submitter->storeAndDispatch(
            $request,
            $setting,
            $validated['channel'],
            [
                'product_type' => 'car',
                'ad_type' => $validated['ad_type'],
                'contact_name' => $validated['contact_name'],
                'phone' => $validated['phone'],
                'email' => $validated['email'] ?? null,
                'amount' => $validated['amount'] ?? null,
                'location' => $validated['location'],
                'details' => $details ?: null,
                'vehicle_info' => $validated['vehicle_info'] ?? null,
            ]
        );
    }
}

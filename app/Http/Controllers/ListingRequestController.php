<?php

namespace App\Http\Controllers;

use App\Mail\AdminNotification;
use App\Models\ListingRequest;
use App\Models\Setting;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class ListingRequestController extends Controller
{
    public function create()
    {
        return view('frontend.list-your-property');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'product_type' => 'required|in:car,apartment',
            'ad_type' => 'required|in:rent,sale',
            'contact_name' => 'required|string|max:255',
            'phone' => 'required|string|max:50',
            'email' => 'nullable|email|max:255',
            'amount' => 'nullable|numeric|min:0',
            'location' => 'required|string|max:255',
            'details' => 'nullable|string|max:3000',
        ]);

        $listing = ListingRequest::create($validated + ['status' => 'pending']);

        $setting = Setting::first();
        $admins = User::where('role', 1)->get();
        $body = "New listing request from {$listing->contact_name}\n"
            . "Type: {$listing->product_type} ({$listing->ad_type})\n"
            . "Location: {$listing->location}\n"
            . "Amount: " . ($listing->amount ? number_format($listing->amount) . ' RWF' : '—') . "\n"
            . "Phone: {$listing->phone}\n"
            . ($listing->email ? "Email: {$listing->email}\n" : '')
            . ($listing->details ? "Details: {$listing->details}" : '');

        foreach ($admins as $admin) {
            try {
                Mail::to($admin->email)->send(new AdminNotification([
                    'subject' => 'New listing request — Kigali Drive Rentals',
                    'greeting' => 'Hello,',
                    'body' => $body,
                    'lastline' => 'Review in admin: Listing Requests',
                ]));
            } catch (\Throwable $e) {
                // continue
            }
        }

        if ($setting && $setting->whatsapp_enabled && $setting->whatsapp) {
            $wa = 'https://wa.me/' . preg_replace('/\D+/', '', $setting->whatsapp)
                . '?text=' . rawurlencode("Hello Kigali Drive Rentals, I submitted a listing request for my {$listing->product_type}.");
            return redirect()->back()->with('success', 'Your listing request was submitted. Our team will contact you shortly.')
                ->with('whatsapp_followup', $wa);
        }

        return redirect()->back()->with('success', 'Your listing request was submitted. Our team will contact you shortly.');
    }
}

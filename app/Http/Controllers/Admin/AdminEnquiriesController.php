<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CarRental;
use App\Models\Enquiry;
use App\Models\HotelBooking;
use App\Models\Setting;
use Illuminate\Support\Facades\DB;

class AdminEnquiriesController extends Controller
{
    public function index()
    {
        $enquiries = Enquiry::latest()->paginate(20);

        $channelStats = $this->channelStats();
        $formStats = $this->formStats();

        return view('admin.enquiries.index', [
            'enquiries' => $enquiries,
            'channelStats' => $channelStats,
            'formStats' => $formStats,
            'setting' => Setting::first(),
        ]);
    }

    public function destroy($id)
    {
        Enquiry::findOrFail($id)->delete();

        return back()->with('success', 'Enquiry deleted.');
    }

    protected function channelStats(): array
    {
        $fromEnquiries = Enquiry::query()
            ->select('submission_channel', DB::raw('count(*) as total'))
            ->groupBy('submission_channel')
            ->pluck('total', 'submission_channel');

        $fromCars = CarRental::query()
            ->whereNotNull('submission_channel')
            ->select('submission_channel', DB::raw('count(*) as total'))
            ->groupBy('submission_channel')
            ->pluck('total', 'submission_channel');

        $fromApartments = HotelBooking::query()
            ->whereNotNull('submission_channel')
            ->select('submission_channel', DB::raw('count(*) as total'))
            ->groupBy('submission_channel')
            ->pluck('total', 'submission_channel');

        $merged = [];
        foreach (['email', 'whatsapp', 'form'] as $ch) {
            $merged[$ch] = (int) ($fromEnquiries[$ch] ?? 0)
                + (int) ($fromCars[$ch] ?? 0)
                + (int) ($fromApartments[$ch] ?? 0);
        }

        return $merged;
    }

    protected function formStats(): array
    {
        $enquiryForms = Enquiry::query()
            ->select('form_type', DB::raw('count(*) as total'))
            ->groupBy('form_type')
            ->pluck('total', 'form_type');

        return [
            'contact' => (int) ($enquiryForms[Enquiry::FORM_CONTACT] ?? 0),
            'car_enquiry' => (int) ($enquiryForms[Enquiry::FORM_CAR_ENQUIRY] ?? 0),
            'apartment_enquiry' => (int) ($enquiryForms[Enquiry::FORM_APARTMENT_ENQUIRY] ?? 0),
            'car_reservation' => CarRental::count(),
            'apartment_reservation' => HotelBooking::count(),
        ];
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\PropertyReview;
use App\Models\CarReview;
use App\Models\TripReview;
use App\Models\Setting;
use App\Services\GoogleBusinessReviewService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReviewController extends Controller
{
    public function __construct(
        protected GoogleBusinessReviewService $googleReviews
    ) {}

    /**
     * Display Google Business reviews.
     */
    public function index()
    {
        $setting = Setting::first();
        $googleData = $this->googleReviews->getData($setting);
        $latestReviews = $this->googleReviews->getReviews($setting, 6);

        return view('frontend.reviews.index', compact('googleData', 'setting', 'latestReviews'));
    }

    /**
     * Redirect legacy review URLs to Google profile.
     */
    public function show($id)
    {
        $profileUrl = $this->googleReviews->profileUrl(Setting::first());

        if ($profileUrl) {
            return redirect()->away($profileUrl);
        }

        return redirect()->route('reviews.index');
    }

    /**
     * Website review submissions are disabled — send users to Google.
     */
    public function store(Request $request)
    {
        $writeUrl = $this->googleReviews->writeReviewUrl(Setting::first());

        if ($writeUrl) {
            return redirect()->away($writeUrl);
        }

        return redirect()
            ->route('reviews.index')
            ->with('error', 'Google Business Profile is not configured yet. Please contact us.');
    }

    /**
     * Store a property review (anyone can rate: logged-in user or guest name/email)
     */
    public function storePropertyReview(Request $request, $propertySlugOrId)
    {
        $property = \App\Models\Property::publishedForGuests()
            ->where(function ($q) use ($propertySlugOrId) {
                if (is_numeric($propertySlugOrId)) {
                    $q->where('id', $propertySlugOrId);
                } else {
                    $q->where('slug', $propertySlugOrId);
                }
            })
            ->firstOrFail();

        $rules = [
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'nullable|string|max:1000',
            'title' => 'nullable|string|max:255',
        ];
        if (!Auth::check()) {
            $rules['guest_name'] = 'required|string|max:255';
            $rules['guest_email'] = 'required|email';
        }
        $request->validate($rules);

        $review = PropertyReview::create([
            'user_id' => Auth::id(),
            'guest_name' => Auth::check() ? null : $request->guest_name,
            'guest_email' => Auth::check() ? null : $request->guest_email,
            'property_id' => $property->id,
            'reviewable_type' => 'property',
            'rating' => $request->rating,
            'comment' => $request->comment,
            'title' => $request->title,
            'is_approved' => false,
        ]);

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Thank you! Your rating has been submitted and will appear after approval.',
                'review' => $review,
            ]);
        }

        return back()->with('success', 'Thank you! Your rating has been submitted and will appear after approval.');
    }

    /**
     * Store a property review (legacy: hotel_id)
     */
    public function storeProperty(Request $request, $hotelId)
    {
        $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'nullable|string|max:1000',
            'title' => 'nullable|string|max:255',
        ]);

        $review = PropertyReview::create([
            'user_id' => Auth::id(),
            'hotel_id' => $hotelId,
            'reviewable_type' => 'hotel',
            'rating' => $request->rating,
            'comment' => $request->comment,
            'title' => $request->title,
            'is_approved' => false,
        ]);

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Review submitted successfully. It will be published after approval.',
                'review' => $review
            ]);
        }

        return back()->with('success', 'Review submitted successfully. It will be published after approval.');
    }

    /**
     * Store a car review
     */
    public function storeCar(Request $request, $carId)
    {
        $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'nullable|string|max:1000',
            'title' => 'nullable|string|max:255',
        ]);

        $review = CarReview::create([
            'user_id' => Auth::id(),
            'car_id' => $carId,
            'rating' => $request->rating,
            'comment' => $request->comment,
            'title' => $request->title,
            'is_approved' => false,
        ]);

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Review submitted successfully. It will be published after approval.',
                'review' => $review
            ]);
        }

        return back()->with('success', 'Review submitted successfully. It will be published after approval.');
    }

    /**
     * Store a trip review
     */
    public function storeTrip(Request $request, $tripId)
    {
        $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'nullable|string|max:1000',
            'title' => 'nullable|string|max:255',
        ]);

        $review = TripReview::create([
            'user_id' => Auth::id(),
            'trip_id' => $tripId,
            'rating' => $request->rating,
            'comment' => $request->comment,
            'title' => $request->title,
            'is_approved' => false,
        ]);

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Review submitted successfully. It will be published after approval.',
                'review' => $review
            ]);
        }

        return back()->with('success', 'Review submitted successfully. It will be published after approval.');
    }
}

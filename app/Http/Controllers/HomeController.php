<?php

namespace App\Http\Controllers;

use DB;
use App\Models\Blog;
use App\Models\Car;
use App\Models\CarRental;
use App\Models\User;
use App\Models\Hotel;
use App\Models\Property;
use App\Models\HotelBooking;
use App\Models\Leftbag;
use App\Models\Ticketing;
use App\Models\HotelRoom;
use App\Models\Category;
use App\Models\Trip;
use App\Models\About;
use App\Models\Slide;
use App\Models\Review;
use App\Models\Message;
use App\Models\Program;
use App\Models\Reservation;
use App\Models\Setting;
use App\Models\Facility;
use App\Models\Promotion;
use App\Models\Subscriber;
use App\Models\BlogComment;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Auth;
use App\Mail\BookingNotification;
use App\Mail\BookingConfirmation;
use App\Mail\AdminNotification;
use App\Mail\ReservationSubmitted;
use App\Mail\ReservationAdminNotification;
use Illuminate\Support\Collection;
use Carbon\Carbon;
use App\Services\BookingInventoryService;
use App\Services\SubmissionChannelService;
use App\Http\Controllers\EnquiryController;


class HomeController extends Controller
{
    public function index(Request $request)
    {
        try {
            $featuredCars = Car::where('status', 'available')
                ->latest()
                ->take(6)
                ->get();
        } catch (\Throwable $e) {
            report($e);
            $featuredCars = collect();
        }

        $googleReviews = app(\App\Services\GoogleBusinessReviewService::class)->getData(Setting::first());
        $businessReviews = collect($googleReviews['reviews'] ?? [])->take(3);

        try {
            $slidesQuery = \App\Models\Slide::query()->active();
            if (\Illuminate\Support\Facades\Schema::hasColumn('slides', 'sort_order')) {
                $slidesQuery->orderBy('sort_order')->orderByDesc('id');
            } else {
                $slidesQuery->latest();
            }
            $slides = $slidesQuery->get();
        } catch (\Throwable $e) {
            report($e);
            $slides = collect();
        }

        $about = About::first();

        try {
            $carBrands = Car::query()
                ->where('status', 'available')
                ->whereNotNull('brand')
                ->distinct()
                ->orderBy('brand')
                ->pluck('brand');
        } catch (\Throwable $e) {
            report($e);
            $carBrands = collect();
        }

        return view('frontend.kdr-home', compact(
            'featuredCars',
            'businessReviews',
            'googleReviews',
            'slides',
            'about',
            'carBrands'
        ));
    }

    public function faq()
    {
        return view('frontend.faq');
    }

public function hotelsSearch(Request $request)
{
    $q = trim((string) $request->input('q', ''));
    $district = trim((string) $request->input('district', $request->input('location', '')));
    $listingType = $request->input('listing_type');
    if ($listingType === 'sell') {
        $listingType = 'sale';
    }
    $orderby = $request->input('orderby');

    $query = Property::query()
        ->publishedForGuests()
        ->with(['units' => function ($unitQuery) {
            $unitQuery->where('status', 'Available')->orderBy('base_price_per_night', 'asc');
        }])
        ->whereIn('property_type', ['apartment', 'villa']);

    if (\Illuminate\Support\Facades\Schema::hasTable('property_reviews')) {
        $query->withCount('reviews')->withAvg('reviews', 'rating');
    }

    if ($q !== '') {
        $query->where(function ($searchQuery) use ($q) {
            $searchQuery->where('name', 'like', "%{$q}%")
                ->orWhere('description', 'like', "%{$q}%")
                ->orWhere('address', 'like', "%{$q}%")
                ->orWhere('city', 'like', "%{$q}%")
                ->orWhere('location', 'like', "%{$q}%")
                ->orWhere('property_type', 'like', "%{$q}%");
        });
    }

    if ($district !== '' && strtolower($district) !== 'all') {
        $query->where(function ($locationQuery) use ($district) {
            $locationQuery->where('location', 'like', "%{$district}%")
                ->orWhere('city', 'like', "%{$district}%")
                ->orWhere('address', 'like', "%{$district}%");
        });
    }

    if ($request->filled('listing_type') && \Illuminate\Support\Facades\Schema::hasColumn('properties', 'listing_type')) {
        if ($listingType === 'rent') {
            $query->whereIn('listing_type', ['rent', 'both']);
        } elseif ($listingType === 'sale') {
            $query->whereIn('listing_type', ['sale', 'both']);
        } else {
            $query->where('listing_type', $listingType);
        }
    }

    switch ($orderby) {
        case 'price':
            $query->orderBy('name', 'asc');
            break;
        case 'price-desc':
            $query->orderBy('name', 'desc');
            break;
        case 'rating':
            $query->orderByRaw('COALESCE(CAST(properties.stars AS UNSIGNED), 0) DESC');
            break;
        case 'date':
            $query->orderBy('created_at', 'desc');
            break;
        default:
            $query->latest();
    }

    $rooms = $query->paginate(12)->appends($request->query());
    $districtGroups = config('kdr_districts', []);

    if ($request->ajax()) {
        $html = view('frontend.partials.accommodations_results', compact('rooms'))->render();

        return response()->json(['html' => $html]);
    }

    return view('frontend.hotelsSearch', compact('rooms', 'districtGroups'));
}


    public function apartments(Request $request)
    {
        return $this->hotelsSearch($request);
    }

    public function contact()
    {
        $setting = Setting::first();
        $about = About::first();

        return view('frontend.contact', compact('setting', 'about'));
    }



    public function showCars(Request $request)
    {
        $q = $request->input('q');
        $orderby = $request->input('orderby', 'date');
        $rentalPeriod = $request->input('rental_period', 'day');

        $query = Car::query()
            ->where('status', 'available')
            ->with('images');

        if ($request->filled('q')) {
            $search = $request->input('q');
            $query->where(function ($qb) use ($search) {
                $qb->where('name', 'like', "%{$search}%")
                    ->orWhere('model', 'like', "%{$search}%")
                    ->orWhere('brand', 'like', "%{$search}%")
                    ->orWhere('fuel_type', 'like', "%{$search}%");
            });
        }

        if ($request->filled('model')) {
            $query->where('model', 'like', '%' . $request->input('model') . '%');
        }

        if ($request->filled('listing_type')) {
            $type = $request->input('listing_type');
            if ($type === 'rent') {
                $query->whereIn('listing_type', ['rent', 'both']);
            } elseif ($type === 'sale') {
                $query->whereIn('listing_type', ['sale', 'both']);
            } else {
                $query->where('listing_type', $type);
            }
        }

        if ($request->filled('brand')) {
            $query->where('brand', $request->input('brand'));
        }

        if ($request->filled('driver')) {
            match ($request->input('driver')) {
                'with_driver' => $query->where('driver_available', true),
                'self_drive' => $query->where('self_drive', true),
                default => null,
            };
        }

        if ($request->filled('fuel_type')) {
            match ($request->input('fuel_type')) {
                'included' => $query->where('fuel_type', 'like', '%included%'),
                'not_included' => $query->where(function ($qb) {
                    $qb->where('fuel_type', 'like', '%not included%')
                        ->orWhere(function ($q2) {
                            $q2->whereNotNull('fuel_type')
                                ->where('fuel_type', 'not like', '%included%');
                        });
                }),
                default => $query->where('fuel_type', 'like', '%' . $request->input('fuel_type') . '%'),
            };
        }

        if ($request->filled('transmission')) {
            $query->where('transmission', $request->input('transmission'));
        }

        if ($request->filled('rental_period')) {
            match ($request->input('rental_period')) {
                'week' => $query->whereNotNull('price_per_week')->where('price_per_week', '>', 0),
                'month' => $query->whereNotNull('price_per_month')->where('price_per_month', '>', 0),
                default => $query->whereNotNull('price_per_day')->where('price_per_day', '>', 0),
            };
        }

        $priceColumn = match ($rentalPeriod) {
            'week' => 'price_per_week',
            'month' => 'price_per_month',
            default => 'price_per_day',
        };

        switch ($orderby) {
            case 'price':
                $query->orderBy($priceColumn, 'asc');
                break;
            case 'price-desc':
                $query->orderBy($priceColumn, 'desc');
                break;
            case 'name':
                $query->orderBy('name', 'asc');
                break;
            default:
                $query->latest();
        }

        $cars = $query->paginate(10)->withQueryString();

        $baseFilters = Car::query()->where('status', 'available');

        $filterOptions = [
            'brands' => (clone $baseFilters)->whereNotNull('brand')->distinct()->orderBy('brand')->pluck('brand'),
            'models' => (clone $baseFilters)->whereNotNull('model')->distinct()->orderBy('model')->pluck('model'),
            'transmissions' => (clone $baseFilters)->whereNotNull('transmission')->distinct()->orderBy('transmission')->pluck('transmission'),
        ];

        if ($request->ajax()) {
            return response()->json([
                'html' => view('frontend.partials.cars_results', compact('cars', 'rentalPeriod'))->render(),
            ]);
        }

        return view('frontend.cars', compact('cars', 'filterOptions', 'rentalPeriod'));
    }

    public function carDetails($slug){
        $car = Car::with('images')->where('slug', $slug)->firstOrFail();

        $images = $car->images;
        $allCars = Car::where('id','!=',$car->id)->where('status', 'available')->limit(3)->get();
        return view('frontend.carDetails',[
            'car'=>$car,
            'images'=>$images,
            'allCars'=>$allCars,
        ]);
    }

    public function storeCarBooking(Request $request)
    {
        try {
            $validated = $request->validate([
                'car_id' => 'required|exists:cars,id',
                'booking_type' => 'required|in:view_car,rent,buy',
                'name' => 'required|string|max:255',
                'email' => 'required|email|max:255',
                'phone' => 'required|string|max:50',
                'preferred_date' => 'required_if:booking_type,view_car|nullable|date|after_or_equal:today',
                'preferred_time' => 'nullable',
                'pickup_date' => 'required_if:booking_type,rent|nullable|date|after_or_equal:today',
                'dropoff_date' => 'required_if:booking_type,rent|nullable|date|after:pickup_date',
                'pickup_location' => 'nullable|string|max:255',
                'dropoff_location' => 'nullable|string|max:255',
                'message' => 'nullable|string|max:1000',
            ], [
                'car_id.required' => 'Car ID is required.',
                'car_id.exists' => 'Selected car does not exist.',
                'booking_type.required' => 'Please select a booking type.',
                'booking_type.in' => 'Invalid booking type selected.',
                'name.required' => 'Your name is required.',
                'email.required' => 'Your email is required.',
                'email.email' => 'Please enter a valid email address.',
                'phone.required' => 'Your phone number is required.',
                'preferred_date.required_if' => 'Preferred date is required for viewing appointments.',
                'preferred_date.after_or_equal' => 'Preferred date must be today or later.',
                'pickup_date.required_if' => 'Pickup date is required for rentals.',
                'pickup_date.after_or_equal' => 'Pickup date must be today or later.',
                'dropoff_date.required_if' => 'Drop-off date is required for rentals.',
                'dropoff_date.after' => 'Drop-off date must be after pickup date.',
            ]);

            $car = Car::findOrFail($request->car_id);
            
            // Calculate total amount if rent
            $totalAmount = null;
            if ($request->booking_type === 'rent' && $request->pickup_date && $request->dropoff_date) {
                $pickup = new \DateTime($request->pickup_date);
                $dropoff = new \DateTime($request->dropoff_date);
                $days = $pickup->diff($dropoff)->days;
                
                if ($days > 0) {
                    if ($days >= 30 && $car->price_per_month) {
                        $months = floor($days / 30);
                        $remainingDays = $days % 30;
                        $totalAmount = ($months * $car->price_per_month) + ($remainingDays * $car->price_per_day);
                    } elseif ($car->price_per_day) {
                        $totalAmount = $days * $car->price_per_day;
                    }
                }
            } elseif ($request->booking_type === 'buy' && $car->price_to_buy) {
                $totalAmount = $car->price_to_buy;
            }

            $bookingNumber = app(\App\Services\BookingReferenceService::class)->generate();

            $booking = \App\Models\CarRental::create([
                'car_id' => $car->id,
                'user_id' => auth()->id(),
                'booking_number' => $bookingNumber,
                'booking_type' => $request->booking_type,
                'name' => $request->name,
                'email' => $request->email,
                'phone' => $request->phone,
                'preferred_date' => $request->preferred_date,
                'preferred_time' => $request->preferred_time,
                'pickup_location' => $request->pickup_location,
                'dropoff_location' => $request->dropoff_location,
                'pickup_date' => $request->pickup_date,
                'dropoff_date' => $request->dropoff_date,
                'message' => $request->message,
                'total_amount' => $totalAmount,
                'rental_status' => 'pending',
                'payment_status' => 'pending',
            ]);

            // Notify all admins (role = 1)
            $admins = \App\Models\User::where('role', 1)->get();
            if ($admins->isNotEmpty()) {
                $bookingTypeLabel = match($request->booking_type) {
                    'view_car' => 'View Car Request',
                    'rent' => 'Car Rental Booking',
                    'buy' => 'Car Purchase Request',
                    default => 'Car Booking'
                };

                $adminDetails = [
                    'subject' => 'New Car Booking Request: ' . $bookingTypeLabel,
                    'greeting' => 'Hello Admin,',
                    'body' => "A new car booking request has been submitted.\n\n"
                             . "Car: {$car->name}\n"
                             . "Booking Type: {$bookingTypeLabel}\n"
                             . "Customer: {$request->name} ({$request->email})\n"
                             . "Phone: {$request->phone}\n"
                             . ($totalAmount ? "Total Amount: " . number_format($totalAmount) . " RWF\n" : "")
                             . "\nYou can view and manage this booking in the admin panel:\n"
                             . route('admin.carBookings.index'),
                    'lastline' => 'Please log in to review and respond to this booking request.',
                ];

                foreach ($admins as $admin) {
                    Mail::to($admin->email)
                        ->send(new AdminNotification($adminDetails));
                }
            }

            return redirect()->route('carDetails', $car->slug)
                ->with('success', 'Reservation submitted! Your booking number is #' . $bookingNumber . '. Keep it for your review.');
        } catch (\Illuminate\Validation\ValidationException $e) {
            return back()->withInput()->withErrors($e->errors());
        } catch (\Exception $e) {
            \Log::error('Car booking error: ' . $e->getMessage());
            return back()->withInput()->with('error', 'Something went wrong. Please try again: ' . $e->getMessage());
        }
    }


    public function about(){
        $setting = Setting::first();
        $about = About::first();
        $googleData = app(\App\Services\GoogleBusinessReviewService::class)->getData($setting);

        return view('frontend.about', [
            'setting' => $setting,
            'about' => $about,
            'googleData' => $googleData,
        ]);
    }

    public function leftBags(){
        $data = Leftbag::first();
        $trips = Trip::oldest()->get();
        return view('frontend.leftBags',[
            'data'=>$data,
            'trips'=>$trips,
        ]);
    }

    public function leftBagsRequest(){
        $data = Leftbag::first();
        $trips = Trip::oldest()->get();
        return view('frontend.leftBagsRequest',[
            'data'=>$data,
            'trips'=>$trips,
        ]);
    }

    public function ticketing(){
        $data = Ticketing::first();
        $trips = Trip::oldest()->get();
        return view('frontend.ticketing',[
            'data'=>$data,
            'trips'=>$trips,
        ]);
    }

    public function ticketingRequest(){
        $data = Ticketing::first();
        $trips = Trip::oldest()->get();
        return view('frontend.ticketingRequest',[
            'data'=>$data,
            'trips'=>$trips,
        ]);
    }

    public function services(){
        $services = Program::oldest()->get();
        $trips = Trip::oldest()->get();
        return view('frontend.services',[
            'services'=>$services,
            'trips'=>$trips,
        ]);
    }

    public function service($slug)
    {
        $service = Program::where('slug', $slug)->firstOrFail();
        $hotels = $service->hotels;
        $trips = Trip::Oldest()->get();                         
        return view('frontend.service', [

            'hotels' => $hotels,
            'trips' => $trips,
            'service' => $service,
        ]);
    }

public function accommodations(Request $request)
{
    $q = $request->input('q');
    $orderby = $request->input('orderby');

    // base query: only active hotels (optional)
    $query = \App\Models\Hotel::query()->where('status', 'Active')->where('is_listing_visible', true);

    // search by name or location or city
    if (!empty($q)) {
        $query->where(function($qbuilder) use ($q) {
            $qbuilder->where('name', 'like', "%{$q}%")
                     ->orWhere('location', 'like', "%{$q}%")
                     ->orWhere('city', 'like', "%{$q}%");
        });
    }

    // ordering
    switch ($orderby) {
        case 'date':
            $query->orderBy('created_at', 'desc');
            break;
        case 'price':
            // assumes you have hotel min price computed, otherwise skip or join rooms; fallback to name
            $query->orderBy('name', 'asc');
            break;
        case 'price-desc':
            $query->orderBy('name', 'desc');
            break;
        case 'rating':
            // if you have ratings column, order by it; otherwise fallback
            $query->orderBy('name', 'asc');
            break;
        default:
            $query->oldest();
    }

    // paginate (12 per page - adjust as needed)
    $rooms = $query->paginate(12)->appends($request->query());

    // If AJAX: return only the partial HTML used to inject into the page
    if ($request->ajax()) {
        // render the partial view and return HTML
        $html = view('frontend.partials.accommodations_results', compact('rooms'))->render();
        return response()->json(['html' => $html]);
    }

    // non-AJAX: normal full page
    // $trips = \App\Models\Trip::oldest()->get();
    return view('frontend.accommodations', [
        // 'trips' => $trips,
        'rooms' => $rooms,
    ]);
}

public function showAccommodation(Request $request, $slug)
{
    try {
        $hotel = Property::with([
            'units' => function($q) {
                $q->where('status', 'Available')
                    ->with(['images', 'facilities', 'unitType', 'extraCharges'])
                    ->orderBy('base_price_per_night', 'asc');
            },
            'images',
            'facilities.category',
            'reviews' => function($q) {
                $q->latest();
            },
            'owner',
            'category'
        ])
        ->where(function($q) use ($slug) {
            if (is_numeric($slug)) {
                $q->where('id', $slug);
            } else {
                $q->where('slug', $slug);
            }
        })
        ->publishedForGuests()
        ->firstOrFail();

        // Sort property images in PHP (handles DBs where is_primary/sort_order may be missing)
        $hotel->setRelation('images', $hotel->images->sortByDesc('is_primary')->sortBy('sort_order')->values());

        // Group amenities by category; hide categories that are inactive if column exists
        $amenitiesByCategory = $hotel->facilities->groupBy('facility_category_id')->map(function($amenities) {
            return $amenities->first()->category ?? null;
        })->filter(function($cat) {
            return $cat && ($cat->is_active ?? true);
        });

        // Related properties: same type or same category, exclude current
        $relatedQuery = Property::with(['images', 'units'])
            ->where('id', '!=', $hotel->id)
            ->publishedForGuests()
            ->where(function ($q) use ($hotel) {
                $q->where('property_type', $hotel->property_type)
                    ->orWhere('category_id', $hotel->category_id);
            });

        if (\Illuminate\Support\Facades\Schema::hasTable('property_reviews')) {
            $relatedQuery->withCount('reviews')->withAvg('reviews', 'rating');
        }

        $relatedProperties = $relatedQuery->latest()->take(4)->get();

        $roomsDataJson = $hotel->units->map(function ($u) {
            $pt = $u->price_display_type ?? 'per_night';
            $price = ($pt === 'per_month') ? (float)($u->base_price_per_month ?? 0) : (float)($u->base_price_per_night ?? 0);
            $unitTypeName = $u->unitType->name ?? null;
            $unitDisplayName = $unitTypeName && $unitTypeName !== $u->name ? $unitTypeName . ' – ' . $u->name : $u->name;
            return [
                'id' => $u->id,
                'price' => $price,
                'name' => $unitDisplayName,
                'currency' => $u->currency ?? 'USD',
                'currencySymbol' => getCurrencySymbol($u->currency ?? 'USD'),
                'priceType' => $pt,
                'available' => $u->available_units > 0,
            ];
        })->values()->toArray();

        return view('frontend.accommodation', [
            'hotel' => $hotel,
            'rooms' => $hotel->units,
            'amenitiesByCategory' => $amenitiesByCategory,
            'relatedProperties' => $relatedProperties,
            'roomsDataJson' => $roomsDataJson,
        ]);
    } catch (\Illuminate\Database\QueryException $e) {
        \Illuminate\Support\Facades\Log::error('showAccommodation query error', [
            'slug' => $slug,
            'message' => $e->getMessage(),
            'sql' => $e->getSql(),
        ]);
        throw $e;
    }
}

/**
 * Show single unit/room page for a property (Property + Unit model)
 */
public function showUnit($property, $unit)
{
    $propertyModel = Property::with(['facilities.category', 'category'])
        ->where(function($q) use ($property) {
            if (is_numeric($property)) {
                $q->where('id', $property);
            } else {
                $q->where('slug', $property);
            }
        })
        ->publishedForGuests()
        ->firstOrFail();

    $unitModel = \App\Models\Unit::with(['images', 'facilities', 'unitType', 'extraCharges'])
        ->where('property_id', $propertyModel->id)
        ->where(function($q) use ($unit) {
            if (is_numeric($unit)) {
                $q->where('id', $unit);
            } else {
                $q->where('slug', $unit);
            }
        })
        ->where('status', 'Available')
        ->firstOrFail();

    // Build room gallery from unit images only
    $roomImages = collect();
    if ($unitModel->images && $unitModel->images->isNotEmpty()) {
        foreach ($unitModel->images->sortByDesc('is_primary')->sortBy('sort_order') as $img) {
            $roomImages->push([
                'url' => asset('storage/images/units/' . $img->image_path),
                'caption' => $img->caption ?? $unitModel->name,
            ]);
        }
    }
    if ($unitModel->featured_image && !$roomImages->contains('url', asset('storage/images/units/' . $unitModel->featured_image))) {
        $roomImages->prepend([
            'url' => asset('storage/images/units/' . $unitModel->featured_image),
            'caption' => $unitModel->name . ' - Featured',
        ]);
    }
    if ($roomImages->isEmpty()) {
        $roomImages->push([
            'url' => asset('assets/img/tour/tour_3_1.jpg'),
            'caption' => $unitModel->name,
        ]);
    }

    return view('frontend.unit-details', [
        'property' => $propertyModel,
        'unit' => $unitModel,
        'roomImages' => $roomImages,
    ]);
}

/**
 * Store a booking for a property unit
 */
public function storeBooking(Request $request)
{
    $request->validate([
        'property_id' => 'required|exists:properties,id',
        'unit_id' => 'required|exists:units,id',
        'check_in' => 'required|date|after_or_equal:today',
        'check_out' => 'required|date|after:check_in',
        'guests_count' => 'required|integer|min:1',
        'guest_name' => 'required|string|max:255',
        'guest_email' => 'required|email|max:255',
        'guest_country' => 'nullable|string|max:255',
        'guest_phone' => 'nullable|string|max:100',
        'special_requests' => 'nullable|string',
        'extra_charges' => 'nullable|array',
        'extra_charges.*' => 'integer|exists:unit_extra_charges,id',
        'channel' => 'required|in:email,whatsapp,form',
    ]);

    $setting = Setting::firstOrFail();
    $channels = app(SubmissionChannelService::class);
    $channels->assertAnyChannelActive($setting, 'booking');
    $channels->assertChannelActive($setting, $request->channel, 'booking');

    // Get unit to calculate price
    $unit = \App\Models\Unit::with('extraCharges')->findOrFail($request->unit_id);
    $property = Property::findOrFail($request->property_id);

    $maxGuests = max(1, (int) ($unit->max_occupancy ?? 1));
    if ((int) $request->guests_count > $maxGuests) {
        return redirect()->back()
            ->withInput()
            ->withErrors(['guests_count' => "This room accepts at most {$maxGuests} guest".($maxGuests === 1 ? '' : 's').'.']);
    }

    if (! ($property->accepts_bookings ?? true)) {
        return redirect()->back()->with('error', 'This property is not accepting new booking requests at the moment.');
    }

    // Verify unit belongs to property
    if ($unit->property_id != $property->id) {
        return redirect()->back()->with('error', 'Invalid unit for this property.');
    }

    $checkInStay = Carbon::parse($request->check_in)->startOfDay();
    $checkOutStay = Carbon::parse($request->check_out)->startOfDay();
    $minUnitRem = BookingInventoryService::minEffectiveRemainingUnitStay($unit, $checkInStay, $checkOutStay);
    if ($minUnitRem < 1) {
        return redirect()->back()
            ->withInput()
            ->with('error', 'There are no remaining units for this category on one or more nights in your selected dates. Please choose different dates.');
    }

    // Validate extra_charges belong to this unit
    $unitExtraChargeIds = $unit->extraCharges->pluck('id')->toArray();
    $selectedExtraIds = array_map('intval', $request->input('extra_charges', []));
    $validExtraIds = array_intersect($selectedExtraIds, $unitExtraChargeIds);

    // Calculate base total (per night or per month)
    $priceType = $unit->price_display_type ?? 'per_night';
    if ($priceType === 'per_month') {
        $baseTotal = (float) ($unit->base_price_per_month ?? 0);
    } else {
        $checkIn = new \DateTime($request->check_in);
        $checkOut = new \DateTime($request->check_out);
        $nights = $checkIn->diff($checkOut)->days;
        $baseTotal = ((float) ($unit->base_price_per_night ?? 0)) * $nights;
    }

    // Add extras total
    $extrasTotal = 0;
    $extrasForBooking = [];
    foreach ($unit->extraCharges as $extra) {
        if (in_array($extra->id, $validExtraIds)) {
            $extrasTotal += (float) $extra->price;
            $extrasForBooking[] = [
                'unit_extra_charge_id' => $extra->id,
                'price_snapshot' => $extra->price,
                'charge_name' => $extra->extraChargeType->name ?? 'Extra',
            ];
        }
    }
    $totalAmount = $baseTotal + $extrasTotal;

    $commissionRate = 10;
    $commissionAmount = round($totalAmount * ($commissionRate / 100), 2);

    // Generate reference number
    $referenceNumber = 'BK' . strtoupper(uniqid());

    // Create booking
    $booking = HotelBooking::create([
        'user_id' => Auth::id(),
        'hotel_id' => null,
        'property_id' => $request->property_id,
        'room_id' => null,
        'unit_id' => $request->unit_id,
        'check_in' => $request->check_in,
        'check_out' => $request->check_out,
        'guests_count' => $request->guests_count,
        'guest_name' => $request->guest_name,
        'guest_email' => $request->guest_email,
        'guest_country' => $request->guest_country,
        'guest_phone' => $request->guest_phone,
        'special_requests' => $request->special_requests,
        'total_amount' => $totalAmount,
        'commission_rate' => $commissionRate,
        'commission_amount' => $commissionAmount,
        'reference_number' => $referenceNumber,
        'payment_status' => 'pending',
        'booking_status' => 'availability_requested',
        'submission_channel' => $request->channel,
    ]);

    // Attach booking extras
    foreach ($extrasForBooking as $item) {
        \App\Models\BookingExtra::create([
            'hotel_booking_id' => $booking->id,
            'unit_extra_charge_id' => $item['unit_extra_charge_id'],
            'price_snapshot' => $item['price_snapshot'],
            'charge_name' => $item['charge_name'],
        ]);
    }

    // Load relationships for email
    $booking->load(['property', 'unit', 'property.owner', 'bookingExtras']);

    $waMessage = implode("\n", array_filter([
        'Kigali Drive Rentals — Apartment booking request',
        'Reference: ' . $referenceNumber,
        'Property: ' . ($property->name ?? '—'),
        'Unit: ' . ($unit->name ?? '—'),
        'Check-in: ' . $request->check_in,
        'Check-out: ' . $request->check_out,
        'Guests: ' . $request->guests_count,
        'Name: ' . $request->guest_name,
        'Email: ' . $request->guest_email,
        $request->guest_phone ? 'Phone: ' . $request->guest_phone : null,
        $request->special_requests ? 'Requests: ' . $request->special_requests : null,
    ]));

    $redirectUrl = route('hotel', $property->slug);
    $successMessage = 'Your availability request has been received. Reference: ' . $referenceNumber . '. We will check availability and contact you to confirm your booking.';

    if ($request->channel === 'whatsapp') {
        $externalUrl = $channels->whatsappUrl($setting, $waMessage);
        if ($externalUrl) {
            return $channels->submissionResponse($request, $redirectUrl, $successMessage, $externalUrl);
        }
    }

    if ($request->channel === 'email') {
        $adminEmail = $channels->adminEmail($setting, 'booking');
        if ($adminEmail) {
            $subject = 'Apartment booking #' . $referenceNumber . ' — Kigali Drive Rentals';
            $externalUrl = 'mailto:' . $adminEmail
                . '?subject=' . rawurlencode($subject)
                . '&body=' . rawurlencode($waMessage);

            return $channels->submissionResponse($request, $redirectUrl, $successMessage, $externalUrl);
        }
    }

    if ($request->channel === 'form') {
        $notificationRecipients = array_filter([config('mail.admin_email')]);
        if ($booking->property && $booking->property->owner && $booking->property->owner->email) {
            $ownerEmail = $booking->property->owner->email;
            if (! in_array($ownerEmail, $notificationRecipients)) {
                $notificationRecipients[] = $ownerEmail;
            }
        }
        try {
            foreach ($notificationRecipients as $email) {
                Mail::to($email)->send(new BookingNotification($booking));
            }
        } catch (\Exception $e) {
            \Log::error('Failed to send booking notification: ' . $e->getMessage());
        }

        try {
            $guestEmail = $booking->guest_email ?? ($booking->user->email ?? null);
            if ($guestEmail) {
                Mail::to($guestEmail)->send(new BookingConfirmation($booking));
            }
        } catch (\Exception $e) {
            \Log::error('Failed to send client booking confirmation: ' . $e->getMessage());
        }
    }

    return $channels->submissionResponse($request, $redirectUrl, $successMessage);
}

/**
 * JSON: minimum remaining inventory for each night of a stay (hotel room type).
 */
public function checkHotelRoomBookingAvailability(Request $request, string $hotelSlug, string $roomSlug)
{
    $hotel = Hotel::where('slug', $hotelSlug)->firstOrFail();
    $room = HotelRoom::where('hotel_id', $hotel->id)->where('slug', $roomSlug)->firstOrFail();

    $validated = $request->validate([
        'check_in' => 'required|date',
        'check_out' => 'required|date|after:check_in',
    ]);

    $checkIn = Carbon::parse($validated['check_in'])->startOfDay();
    $checkOut = Carbon::parse($validated['check_out'])->startOfDay();
    $min = BookingInventoryService::minEffectiveRemainingHotelRoomStay($room, $checkIn, $checkOut);

    return response()->json([
        'available' => $min >= 1,
        'min_remaining' => $min,
        'message' => $min < 1
            ? 'There are no remaining rooms for this category on one or more nights in your selected dates.'
            : null,
    ]);
}

/**
 * JSON: minimum remaining inventory for each night of a stay (property unit).
 */
public function checkUnitBookingAvailability(Request $request, $property, $unit)
{
    $propertyModel = Property::query()
        ->where(function ($q) use ($property) {
            if (is_numeric($property)) {
                $q->where('id', $property);
            } else {
                $q->where('slug', $property);
            }
        })
        ->publishedForGuests()
        ->firstOrFail();

    $unitModel = \App\Models\Unit::query()
        ->where('property_id', $propertyModel->id)
        ->where(function ($q) use ($unit) {
            if (is_numeric($unit)) {
                $q->where('id', $unit);
            } else {
                $q->where('slug', $unit);
            }
        })
        ->firstOrFail();

    $validated = $request->validate([
        'check_in' => 'required|date',
        'check_out' => 'required|date|after:check_in',
    ]);

    $checkIn = Carbon::parse($validated['check_in'])->startOfDay();
    $checkOut = Carbon::parse($validated['check_out'])->startOfDay();
    $min = BookingInventoryService::minEffectiveRemainingUnitStay($unitModel, $checkIn, $checkOut);

    return response()->json([
        'available' => $min >= 1,
        'min_remaining' => $min,
        'message' => $min < 1
            ? 'There are no remaining units for this category on one or more nights in your selected dates.'
            : null,
    ]);
}

/**
 * Guest booking request for a hotel room (Hotel + HotelRoom models).
 */
public function storeHotelRoomBookingRequest(Request $request, string $hotelSlug, string $roomSlug)
{
    $hotel = Hotel::where('slug', $hotelSlug)->firstOrFail();
    $room = HotelRoom::where('hotel_id', $hotel->id)->where('slug', $roomSlug)->firstOrFail();

    $validated = $request->validate([
        'check_in' => 'required|date|after_or_equal:today',
        'check_out' => 'required|date|after:check_in',
        'guests_count' => 'required|integer|min:1',
        'guest_name' => 'required|string|max:255',
        'guest_email' => 'required|email|max:255',
        'guest_phone' => 'nullable|string|max:100',
        'special_requests' => 'nullable|string|max:2000',
    ]);

    $maxGuests = max(1, (int) ($room->max_occupancy ?? 1));
    if ((int) $validated['guests_count'] > $maxGuests) {
        return redirect()->back()
            ->withInput()
            ->withErrors(['guests_count' => "Maximum {$maxGuests} guests for this room."]);
    }

    if (! ($hotel->accepts_bookings ?? true)) {
        return redirect()->back()->with('error', 'This property is not accepting new bookings.');
    }
    if (! ($room->accepts_room_bookings ?? true)) {
        return redirect()->back()->with('error', 'This room is not open for booking (marked fully booked).');
    }

    $checkIn = Carbon::parse($validated['check_in'])->startOfDay();
    $checkOut = Carbon::parse($validated['check_out'])->startOfDay();

    $minRem = BookingInventoryService::minEffectiveRemainingHotelRoomStay($room, $checkIn, $checkOut);
    if ($minRem < 1) {
        return redirect()->back()
            ->withInput()
            ->with('error', 'There are no remaining rooms for this category on one or more nights in your selected dates. Please choose different dates.');
    }

    $nights = max(0, $checkIn->diffInDays($checkOut));
    $nightPrice = (float) ($room->price_per_night ?? 0);
    $totalAmount = $nights * $nightPrice;
    $commissionRate = 10;
    $commissionAmount = round($totalAmount * ($commissionRate / 100), 2);
    $referenceNumber = 'BK' . strtoupper(uniqid());

    $booking = HotelBooking::create([
        'user_id' => Auth::id(),
        'hotel_id' => $hotel->id,
        'property_id' => null,
        'room_id' => $room->id,
        'unit_id' => null,
        'check_in' => $checkIn->toDateString(),
        'check_out' => $checkOut->toDateString(),
        'guests_count' => (int) $validated['guests_count'],
        'guest_name' => $validated['guest_name'],
        'guest_email' => $validated['guest_email'],
        'guest_phone' => $validated['guest_phone'] ?? null,
        'special_requests' => $validated['special_requests'] ?? null,
        'total_amount' => $totalAmount,
        'commission_rate' => $commissionRate,
        'commission_amount' => $commissionAmount,
        'reference_number' => $referenceNumber,
        'payment_status' => 'pending',
        'booking_status' => 'availability_requested',
    ]);

    $booking->load(['hotel.owner', 'room']);

    $notificationRecipients = array_filter([config('mail.admin_email')]);
    if ($booking->hotel && $booking->hotel->owner && $booking->hotel->owner->email) {
        $notificationRecipients[] = $booking->hotel->owner->email;
    }
    try {
        foreach (array_unique($notificationRecipients) as $email) {
            Mail::to($email)->send(new BookingNotification($booking));
        }
    } catch (\Exception $e) {
        \Log::error('Hotel room booking notification: ' . $e->getMessage());
    }
    try {
        if (! empty($validated['guest_email'])) {
            Mail::to($validated['guest_email'])->send(new BookingConfirmation($booking));
        }
    } catch (\Exception $e) {
        \Log::error('Hotel room booking confirmation: ' . $e->getMessage());
    }

    return redirect()->route('roomDetails', ['hotel' => $hotel->slug, 'room' => $room->slug])
        ->with('success', 'Your request has been received. Reference: ' . $referenceNumber . '. We will confirm availability shortly.');
}


    public function destinations()
    {
        $destinations = Category::oldest()->get();
        $trips = Trip::oldest()->get();

        return view('frontend.destinations', [
            'trips' => $trips,
            'destinations' => $destinations,
        ]);
    }

    /**
     * Show a destination page with its properties and filters.
     */
    public function destination(Request $request, $slug)
    {
        $category = Category::where('slug', $slug)->firstOrFail();

        // Base query: all active hotels/properties under this destination
        $query = $category
            ->hotels()
            ->where('status', 'Active')
            ->with([
                'rooms' => function ($q) {
                    $q->where('status', 'Active')
                      ->orderBy('price_per_night', 'asc');
                },
                'reviews',
            ]);

        // Filter by property type (stored on hotels.type), e.g. hotel, apartment, etc.
        $propertyType = $request->input('property_type');
        if (!empty($propertyType) && $propertyType !== 'all') {
            $query->where('type', $propertyType);
        }

        // Search by name / location / city within this destination
        if ($request->filled('q')) {
            $search = $request->input('q');
            $query->where(function ($qbuilder) use ($search) {
                $qbuilder->where('name', 'like', '%' . $search . '%')
                         ->orWhere('location', 'like', '%' . $search . '%')
                         ->orWhere('city', 'like', '%' . $search . '%');
            });
        }

        // Sort newest first
        $hotels = $query
            ->orderBy('created_at', 'desc')
            ->paginate(12)
            ->appends($request->query());

        // Get tour activities for this destination
        $trips = Trip::where('status', 'Active')
            ->where('category_id', $category->id)
            ->with(['images', 'reviews', 'destination'])
            ->latest()
            ->get();

        return view('frontend.destination', [
            'trips' => $trips,
            'category' => $category,
            'hotels' => $hotels,
        ]);
    }

public function hotelRooms($hotelSlug)
{
    $hotel = Hotel::with(['amenities.category'])->where('slug', $hotelSlug)->firstOrFail();

    $rooms = $hotel->rooms()
        ->where('status', 'Available')
        ->orderBy('price_per_night', 'asc')
        ->paginate(12);

    $trips = Trip::oldest()->get();

    return view('frontend.hotelRooms', [
        'hotel' => $hotel,
        'rooms' => $rooms,
        'trips' => $trips,
    ]);
}

public function roomDetails($hotelSlug, $roomSlug)
{
    $hotel = Hotel::with(['images', 'rooms.images', 'amenities.category'])->where('slug', $hotelSlug)->firstOrFail();

    $room = HotelRoom::with(['images', 'roomAmenities.category'])->where('hotel_id', $hotel->id)
        ->where('slug', $roomSlug)
        ->firstOrFail();

    // Build combined image collection (property + room images)
    $allImages = collect();
    
    // Add property images
    if($hotel->images && $hotel->images->isNotEmpty()) {
        foreach($hotel->images as $img) {
            if(isset($img->image)) {
                $allImages->push([
                    'url' => asset('storage/images/hotels/' . $img->image),
                    'type' => 'property',
                    'caption' => ($img->caption ?? $hotel->name)
                ]);
            }
        }
    }
    
    // Add property featured image if exists
    if($hotel->image) {
        $featuredUrl = asset('storage/images/hotels/' . $hotel->image);
        if(!$allImages->contains('url', $featuredUrl)) {
            $allImages->prepend([
                'url' => $featuredUrl,
                'type' => 'property',
                'caption' => $hotel->name . ' - Featured'
            ]);
        }
    }
    
    // Add room cover image
    if($room->image) {
        $roomCoverUrl = asset('storage/images/rooms/' . $room->image);
        if(!$allImages->contains('url', $roomCoverUrl)) {
            $allImages->push([
                'url' => $roomCoverUrl,
                'type' => 'room',
                'caption' => $room->room_type . ' - Cover'
            ]);
        }
    }
    
    // Add room gallery images
    if($room->images && $room->images->isNotEmpty()) {
        foreach($room->images as $roomImg) {
            if(isset($roomImg->image)) {
                $roomImgUrl = asset('storage/images/rooms/' . $roomImg->image);
                if(!$allImages->contains('url', $roomImgUrl)) {
                    $allImages->push([
                        'url' => $roomImgUrl,
                        'type' => 'room',
                        'caption' => ($roomImg->caption ?? $room->room_type)
                    ]);
                }
            }
        }
    }
    
    // Fallback if no images
    if($allImages->isEmpty()) {
        $allImages->push([
            'url' => asset('assets/img/tour/tour_inner_2_1.jpg'),
            'type' => 'room',
            'caption' => $room->room_type
        ]);
    }
    
    $images = $allImages;

    // Room amenities: prefer pivot (owner dashboard); legacy JSON column is fallback only.
    if ($room->roomAmenities->isEmpty() && ! empty($room->amenities)) {
        $raw = is_array($room->amenities) ? $room->amenities : (json_decode($room->amenities, true) ?? []);
        if (! empty($raw) && array_is_list($raw) && is_numeric($raw[0] ?? null)) {
            $legacy = \App\Models\Amenity::whereIn('id', $raw)->get();
            $room->setRelation('roomAmenities', $legacy);
        }
    }

    $relatedRooms = HotelRoom::with('roomAmenities')->where('hotel_id', $hotel->id)
        ->where('id', '!=', $room->id)
        ->where('status', 'Available')
        ->orderBy('price_per_night', 'asc')
        ->take(6)
        ->get();

    $trips = \App\Models\Trip::oldest()->get();

    return view('frontend.roomDetails', [
        'hotel' => $hotel,
        'room' => $room,
        'images' => collect($images),
        'relatedRooms' => $relatedRooms,
        'trips' => $trips,
    ]);
}




    public function facilities(){
        $facilities = Facility::with('images')->oldest()->get();
        $setting = Setting::first();
        $about = About::first();
        return view('frontend.facilities',[
            'facilities'=>$facilities,
            'setting'=>$setting,
            'about'=>$about,
        ]);
    }

    public function facility($slug){
        $facility = Facility::with('images')->where('slug', $slug)->firstOrFail();

        $images = $facility->images;
        $allFacilities = Facility::where('id','!=',$facility->id)->get();
        $facilities = Facility::all();
        $setting = Setting::first();
        $about = About::first();
        return view('frontend.facility',[
            'facility'=>$facility,
            'images'=>$images,
            'allFacilities'=>$allFacilities,
            'facilities'=>$facilities,
            'setting'=>$setting,
            'about'=>$about,
        ]);
    }


    public function promotions(){
        $promotions = Promotion::oldest()->get();
        $setting = Setting::first();
        $about = About::first();
        return view('frontend.promotions',[
            'promotions'=>$promotions,
            'about'=>$about,
            'setting'=>$setting,
        ]);
    }

    public function promotion($slug){
        $promotion = Promotion::where('slug', $slug)->firstOrFail();
        $allPromotions = Promotion::where('id','!=',$promotion->id)->get();

        $setting = Setting::first();
        $about = About::first();
        return view('frontend.promotion',[
            'promotion'=>$promotion,
            'allPromotions'=>$allPromotions,
            'setting'=>$setting,
            'about'=>$about,
        ]);
    }

    public function events(){
        $event = Eventpage::with('images')->first();
        $images = $event->images;
        return view('frontend.events',[
            'event'=>$event,
            'images'=>$images,
        ]);
    }

public function gallery()
{
    // Fetch images from both tables
    $roomImages = \DB::table('rooms')
        ->select('image', 'created_at')
        ->addSelect(\DB::raw("'room' as type"))
        ->get();

    $facilityImages = \DB::table('facilities')
        ->select('image', 'created_at')
        ->addSelect(\DB::raw("'facility' as type"))
        ->get();

    // Merge & sort by latest
    $merged = $roomImages
        ->merge($facilityImages)
        ->sortByDesc('created_at')
        ->values();

    // Paginate manually
    $perPage = 12;
    $page = request()->get('page', 1);
    $offset = ($page - 1) * $perPage;

    $gallery = new LengthAwarePaginator(
        $merged->slice($offset, $perPage)->values(),
        $merged->count(),
        $perPage,
        $page,
        ['path' => request()->url(), 'query' => request()->query()]
    );

    $setting = Setting::first();

    return view('frontend.gallery', [
        'gallery' => $gallery,
        'setting' => $setting,
    ]);
}



public function terms(){
    try {
        $featuredCars = Car::where('status', 'available')->latest()->take(6)->get();
    } catch (\Throwable $e) {
        report($e);
        $featuredCars = collect();
    }
    $setting = Setting::first();
    $about = About::first();
    $terms = \App\Models\Term::first();
    return view('frontend.terms',[
        'setting'=>$setting,
        'about'=>$about,
        'featuredCars'=>$featuredCars,
        'terms'=>$terms,
    ]);
}

public function bookNow(Request $request)
{
    try {
        // Validate based on service type
        $rules = [
            'service_type' => 'required|in:enquiry,hotel_booking,tour_booking,question,ticketing,left_bags',
            'names' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'required|string|max:50',
            'message' => 'required|string',
        ];

        // Add validation rules based on service type
        if ($request->service_type === 'hotel_booking') {
            $rules['room_id'] = 'required|exists:hotel_rooms,id';
            $rules['nights'] = 'nullable|integer|min:1';
            $rules['guests'] = 'nullable|integer|min:1';
            $rules['checkin_date'] = 'nullable|date|after_or_equal:today';
            $rules['checkout_date'] = 'nullable|date|after:checkin_date';
            $rules['facility_id'] = 'nullable|exists:facilities,id';
        } elseif ($request->service_type === 'tour_booking') {
            $rules['tour_id'] = 'nullable|exists:trips,id';
            $rules['tour_date'] = 'nullable|date|after_or_equal:today';
            $rules['tour_people'] = 'nullable|integer|min:1';
        }

        $validated = $request->validate($rules);

        // Prepare data based on service type
        $bookingData = [
            'service_type' => $request->service_type,
            'names' => $request->names,
            'email' => $request->email,
            'phone' => $request->phone,
            'message' => $request->message,
            'status' => 'pending',
        ];

        // Add hotel booking specific fields
        if ($request->service_type === 'hotel_booking') {
            $bookingData['room_id'] = $request->room_id;
            $bookingData['facility_id'] = $request->facility_id;
            $bookingData['nights'] = $request->nights;
            $bookingData['guests'] = $request->guests;
            $bookingData['checkin_date'] = $request->checkin_date;
            $bookingData['checkout_date'] = $request->checkout_date;
        }

        // Add tour booking specific fields
        if ($request->service_type === 'tour_booking') {
            $bookingData['tour_id'] = $request->tour_id;
            $bookingData['tour_date'] = $request->tour_date;
            $bookingData['tour_people'] = $request->tour_people;
        }

        $booking = \App\Models\Reservation::create($bookingData);

        if ($booking) {
            try {
                $booking->load('tour');
                Mail::to($booking->email)->send(new ReservationSubmitted($booking));
            } catch (\Exception $e) {
                \Log::error('Failed to send reservation submitted email: ' . $e->getMessage());
            }
            try {
                Mail::to(config('mail.admin_email'))->send(new ReservationAdminNotification($booking));
            } catch (\Exception $e) {
                \Log::error('Failed to send reservation admin notification: ' . $e->getMessage());
            }
            $successMessages = [
                'enquiry' => "✅ Your enquiry has been received! We'll contact you soon.",
                'hotel_booking' => "✅ Your hotel booking request has been received! We'll contact you soon to confirm availability.",
                'tour_booking' => "✅ Your tour booking request has been received! We'll contact you soon to confirm details.",
                'question' => "✅ Your question has been received! We'll get back to you soon.",
            ];

            $message = $successMessages[$request->service_type] ?? "✅ Your request has been received! We'll contact you soon.";
            return redirect()->back()->with('success', $message);
        } else {
            return redirect()->back()->withInput()->with('error', '❌ Sorry, your request could not be submitted. Please try again.');
        }
    } catch (\Illuminate\Validation\ValidationException $e) {
        return redirect()->back()->withInput()->withErrors($e->errors());
    } catch (\Exception $e) {
        \Log::error('Contact form error: ' . $e->getMessage());
        return redirect()->back()->withInput()->with('error', '❌ Something went wrong. Please try again.');
    }
}



    public function tours(){
        // Show trip destinations instead of trips directly
        $destinations = \App\Models\TripDestination::where('status', 'Active')->with('trips')->oldest()->get();
        $setting = Setting::first();
        $about = About::first();
        return view('frontend.tours',[
            'destinations'=>$destinations,
            'setting'=>$setting,
            'about'=>$about,
        ]);
    }

    public function tripDestination($slug){
        $destination = \App\Models\TripDestination::with(['trips' => function($query) {
            $query->where('status', 'Active')->oldest();
        }, 'images'])->where('slug', $slug)->where('status', 'Active')->firstOrFail();
        
        $relatedDestinations = \App\Models\TripDestination::where('id', '!=', $destination->id)
            ->where('status', 'Active')
            ->oldest()
            ->take(3)
            ->get();
        
        $setting = Setting::first();
        $about = About::first();
        
        return view('frontend.tripDestination',[
            'destination'=>$destination,
            'relatedDestinations'=>$relatedDestinations,
            'setting'=>$setting,
            'about'=>$about,
        ]);
    }

    public function tour($slug){
        $tour = Trip::with('images')->where('slug',$slug)->firstOrFail();
        $images = $tour->images ?? collect();
        $tours = Trip::where('id','!=',$tour->id)->oldest()->get();
        $allTrips = Trip::all();
        $setting = Setting::first();
        $about = About::first();
        return view('frontend.trip',[
            'tour'=>$tour,
            'images'=>$images,
            'tours'=>$tours,
            'allTrips'=>$allTrips,
            'setting'=>$setting,
            'about'=>$about,
        ]);
    }

    public function connect()
    {
        return redirect()->route('contact', [], 301);
    }

    public function tripInquiry(Request $request)
    {
        $validatedData = $request->validate([
            'trip_id' => 'required|exists:trips,id',
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'required|string|max:255',
            'preferred_date' => 'nullable|date|after_or_equal:today',
            'number_of_people' => 'nullable|integer|min:1',
            'message' => 'nullable|string',
        ]);

        $trip = \App\Models\Trip::find($validatedData['trip_id']);

        // Build the message with all details
        $messageContent = "Trip Reservation Inquiry\n\n";
        $messageContent .= "Trip: " . ($request->input('trip_title') ?? 'N/A') . "\n";
        if ($request->filled('preferred_date')) {
            $messageContent .= "Preferred Date: " . $request->input('preferred_date') . "\n";
        }
        if ($request->filled('number_of_people')) {
            $messageContent .= "Number of People: " . $request->input('number_of_people') . "\n";
        }
        if ($request->filled('message')) {
            $messageContent .= "\nAdditional Message:\n" . $request->input('message');
        }

        // Create reservation using the Reservation model
        $reservation = \App\Models\Reservation::create([
            'names' => $validatedData['name'],
            'email' => $validatedData['email'],
            'phone' => $validatedData['phone'],
            'message' => $messageContent,
            'service_type' => 'tour_booking',
            'tour_id' => $validatedData['trip_id'],
            'selected_trip_ids' => json_encode([$validatedData['trip_id']]),
            'trip_destination_id' => $trip?->trip_destination_id,
            'tour_date' => $request->input('preferred_date'),
            'tour_people' => $request->input('number_of_people') ?? 1,
            'guests' => $request->input('number_of_people') ?? 1,
            'status' => 'pending',
        ]);

        if ($reservation) {
            try {
                $reservation->load('tour');
                Mail::to($reservation->email)->send(new ReservationSubmitted($reservation));
            } catch (\Exception $e) {
                \Log::error('Failed to send reservation submitted email: ' . $e->getMessage());
            }
            try {
                Mail::to(config('mail.admin_email'))->send(new ReservationAdminNotification($reservation));
            } catch (\Exception $e) {
                \Log::error('Failed to send reservation admin notification: ' . $e->getMessage());
            }
            return redirect()->back()->with('success', '✅ Your reservation inquiry has been received! We\'ll contact you soon to confirm your booking.');
        } else {
            return redirect()->back()->with('error', '❌ Sorry, your inquiry could not be submitted. Please try again.');
        }
    }

    public function tripRequestMultiple(Request $request)
    {
        $validatedData = $request->validate([
            'trip_destination_id' => 'required|exists:trip_destinations,id',
            'trip_ids' => 'required|array|min:1',
            'trip_ids.*' => 'exists:trips,id',
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'required|string|max:255',
            'preferred_date' => 'nullable|date|after_or_equal:today',
            'number_of_people' => 'nullable|integer|min:1',
            'message' => 'nullable|string',
        ]);

        $trips = \App\Models\Trip::whereIn('id', $validatedData['trip_ids'])->get();
        $tripTitles = $trips->pluck('title')->filter()->implode(', ');

        $messageContent = "Multi-Activity Trip Request\n\n";
        $messageContent .= "Selected Activities: " . ($tripTitles ?: 'N/A') . "\n";
        if ($request->filled('preferred_date')) {
            $messageContent .= "Preferred Date: " . $request->input('preferred_date') . "\n";
        }
        if ($request->filled('number_of_people')) {
            $messageContent .= "Number of People: " . $request->input('number_of_people') . "\n";
        }
        if ($request->filled('message')) {
            $messageContent .= "\nAdditional Message:\n" . $request->input('message');
        }

        $reservation = \App\Models\Reservation::create([
            'service_type' => 'tour_booking',
            'names' => $validatedData['name'],
            'email' => $validatedData['email'],
            'phone' => $validatedData['phone'],
            'message' => $messageContent,
            'selected_trip_ids' => json_encode($validatedData['trip_ids']),
            'trip_destination_id' => $validatedData['trip_destination_id'],
            'tour_date' => $request->input('preferred_date'),
            'tour_people' => $request->input('number_of_people') ?? 1,
            'guests' => $request->input('number_of_people') ?? 1,
            'status' => 'pending',
        ]);

        if ($reservation) {
            try {
                $reservation->load('tour');
                Mail::to($reservation->email)->send(new ReservationSubmitted($reservation));
            } catch (\Exception $e) {
                \Log::error('Failed to send reservation submitted email: ' . $e->getMessage());
            }
            try {
                Mail::to(config('mail.admin_email'))->send(new ReservationAdminNotification($reservation));
            } catch (\Exception $e) {
                \Log::error('Failed to send reservation admin notification: ' . $e->getMessage());
            }
            return redirect()->back()->with('success', '✅ Your trip request has been received! We will send you a plan and quote.');
        }

        return redirect()->back()->with('error', '❌ Sorry, your request could not be submitted. Please try again.');
    }


    public function singleBlog($slug) {
        $blog = Blog::where('slug', $slug)->firstOrFail();
        $latestBlogs = Blog::where('status', 'Published')->where('id', '!=',$blog->id)->latest()->paginate(10);

        $setting = Setting::first();

        if ($blog) {
            $blog->increment('views');
            $comments = BlogComment::where('status','Published')->latest()->get();
            $commentsCount = $comments->count();

            $relatedBlogs = Blog::where('id', '!=', $blog->id)
                                    ->where('status', 'Published')
                                    ->take(5) 
                                    ->get();
        } else {

            return redirect()->route('blogs')->with('error', 'Article not found');
        }
    

        return view('frontend.blog', [
            'blog' => $blog, 
            'latestBlogs' => $latestBlogs, 
            'comments' => $comments, 
            'commentsCount' => $commentsCount, 
            'setting' => $setting, 
            'relatedBlogs'=>$relatedBlogs,
        ]);
    }
    public function blogs()
    {
        $blogs = Blog::where('status', 'Published')->latest()->paginate(10);

        return view('frontend.blogs', [
            'blogs' => $blogs,
            'setting' => Setting::first(),
        ]);
    }


    public function subscribe(Request $request) {
        $request->validate([
            'email' => [
                'required',
                'email',
                Rule::unique('subscribers', 'email'),
            ],
        ]);

        $email = $request->input('email');

        $subscribed = Subscriber::create([
            'email' => $email,
        ]);


        if($subscribed){
            //$subscriber = Subscriber::where('email', $email)->firstOrFail();
            //Mail::to("mukizaemma34@gmail.com")->send(new NewSubscriberNotification($subscriber));
    
            return redirect()->back()->with('success', 'Thank you for subscribing to Kigali Drive Rentals Resort, we will get back to you');
        }

        else{
            return redirect()->back()->with('error', 'Something Went Wrong. Try again later!');
        }        
    
    }
   

    public function sendMessage(Request $request)
    {
        $setting = Setting::firstOrFail();
        $channelService = app(SubmissionChannelService::class);
        $available = $channelService->availableChannels($setting, 'contact');

        $request->merge([
            'form_type' => \App\Models\Enquiry::FORM_CONTACT,
            'phone' => $request->input('phone') ?: null,
            'channel' => $request->input('channel'),
        ]);

        return app(EnquiryController::class)->store($request);
    }
    
    

    public function testimony(Request $request){

        $review = Review::create([
            'names' => $request->input('names'),
            'email' => $request->input('email'),
            'testimony' => $request->input('testimony'),
        ]);
    
        if (!$review) {
            return redirect()->back()->with('error', 'Failed to submit your testimony. Please try again.');
        }
    
        return redirect()->back()->with('success', 'Your testimony has submitted successfully!');
    }

    public function sendComment(Request $request) {
        $user = auth()->user();
    
        $comment = BlogComment::create([
            'blog_id' => $request->input('blog_id'),
            'names' => $request->input('names'),
            'email' => $request->input('email'),
            'comment' => $request->input('comment'),
            'user_id' => $user ? $user->id : null,
        ]);
    
        if ($comment) {
            // Mail::to('mukizaemma34@gmail.com')->send(new BlogCommentsNotofications($comment));
            return redirect()->back()->with('success', 'Comment added successfully');
        }
    
        else{
            return redirect()->back()->with('error', 'Failed to add the comment. Please try again.');
        }
    }

    /**
     * Logout the authenticated user
     */
    public function logouts()
    {
        Auth::logout();
        
        request()->session()->invalidate();
        request()->session()->regenerateToken();
        
        return redirect()->route('home')->with('success', 'You have been logged out successfully.');
    }

}

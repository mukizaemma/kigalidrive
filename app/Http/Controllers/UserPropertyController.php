<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Program;
use App\Models\Category;
use App\Models\Hotel;
use App\Models\HotelRoom;
use App\Models\HotelRoomImage;
use App\Models\HotelImage;
use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Mail;
use App\Mail\AdminNotification;
use App\Models\FacilityCategory;
use App\Models\Property;
use App\Models\ListingAgreementTemplate;
use App\Models\ListingAgreementSignature;
use App\Services\RoomBookingCalendarService;
use App\Services\ListingCompletionService;

class UserPropertyController extends Controller
{
    /**
     * Facility categories with amenities (same structure as admin room editor).
     */
    protected function facilityCategoriesForRooms()
    {
        return FacilityCategory::with(['facilities' => function ($query) {
            $query->where('is_active', true)->orderBy('title');
        }])
            ->where('is_active', true)
            ->orderBy('sort_order')
            ->get();
    }

public function index()
{
    $hotels = collect();
    $ownedProperties = collect();
    $hotelCompletions = [];
    $propertyCompletions = [];
    $amenities = collect();
    $earnings = 0;
    $upcomingBookings = collect();
    $bookingsHistory = collect();
    $bookingCalendarData = [];
    $bookingCalendarPayload = null;
    $calendarYearUrls = [];
    $selectedCalendarHotelId = null;
    $selectedCalendarListingKey = null;
    $calendarListingOptions = collect();
    $calCalendarView = \App\Services\RoomBookingCalendarService::VIEW_UPCOMING;

    if (auth()->check()) {
        $userId = auth()->id();
        $hotels = \App\Models\Hotel::where('added_by', $userId)
            ->with(['rooms.images', 'images', 'listingAgreementSignature'])
            ->latest()
            ->get();

        $ownedProperties = Property::where('owner_id', $userId)
            ->with(['units.unitType', 'images', 'listingAgreementSignature'])
            ->latest()
            ->get();

        $hotelCompletions = [];
        foreach ($hotels as $h) {
            $hotelCompletions[$h->id] = ListingCompletionService::forHotel($h);
        }

        $propertyCompletions = [];
        foreach ($ownedProperties as $p) {
            $propertyCompletions[$p->id] = ListingCompletionService::forProperty($p);
        }

        $amenities = \App\Models\Amenity::orderBy('title')->get();

        // Get property IDs for Property model (owner_id)
        $propertyIds = \App\Models\Property::where('owner_id', $userId)->pluck('id')->toArray();
        $hotelIds = $hotels->pluck('id')->toArray();

        // Bookings for owner's hotels or properties
        $bookingsQuery = \App\Models\HotelBooking::query()
            ->with(['hotel', 'property', 'room', 'unit']);

        if (!empty($hotelIds) || !empty($propertyIds)) {
            $bookingsQuery->where(function ($q) use ($hotelIds, $propertyIds) {
                if (!empty($hotelIds)) {
                    $q->whereIn('hotel_id', $hotelIds);
                }
                if (!empty($propertyIds)) {
                    if (!empty($hotelIds)) {
                        $q->orWhereIn('property_id', $propertyIds);
                    } else {
                        $q->whereIn('property_id', $propertyIds);
                    }
                }
            });
        } else {
            $bookingsQuery->whereRaw('1 = 0'); // No properties = no bookings
        }

        // Earnings (total from paid/completed bookings)
        $earnings = (clone $bookingsQuery)
            ->whereIn('payment_status', ['paid', 'completed'])
            ->whereIn('booking_status', ['confirmed', 'completed', 'checked_out'])
            ->sum('total_amount');

        // Upcoming bookings (check_in >= today)
        $upcomingBookings = (clone $bookingsQuery)
            ->where('check_in', '>=', now()->toDateString())
            ->orderBy('check_in', 'asc')
            ->limit(20)
            ->get();

        // Bookings history: most recent activity (upcoming + current + past)
        $bookingsHistory = (clone $bookingsQuery)
            ->orderByDesc('created_at')
            ->limit(50)
            ->get();

        // Room / date grid: hotels (Hotel model) + properties (Property model)
        $calYear = (int) request('cal_year', now()->year);
        $calYear = max(2020, min(2035, $calYear));
        $calCalendarView = request('cal_calendar_view', \App\Services\RoomBookingCalendarService::VIEW_UPCOMING);
        if (! in_array($calCalendarView, [\App\Services\RoomBookingCalendarService::VIEW_UPCOMING, \App\Services\RoomBookingCalendarService::VIEW_HISTORY], true)) {
            $calCalendarView = \App\Services\RoomBookingCalendarService::VIEW_UPCOMING;
        }

        foreach ($hotels as $h) {
            $calendarListingOptions->push(['key' => 'h-'.$h->id, 'label' => $h->name]);
        }
        foreach ($ownedProperties as $p) {
            $calendarListingOptions->push(['key' => 'p-'.$p->id, 'label' => $p->name]);
        }
        $calendarListingOptions = $calendarListingOptions->sortBy('label', SORT_NATURAL | SORT_FLAG_CASE)->values();

        $calListing = request('cal_listing');
        $legacyCalHotel = request('cal_hotel');
        if (($calListing === null || $calListing === '') && $legacyCalHotel !== null && $legacyCalHotel !== '') {
            $calListing = 'h-'.(int) $legacyCalHotel;
        }

        if ($calendarListingOptions->isNotEmpty()) {
            $validKeys = $calendarListingOptions->pluck('key')->all();
            if (! $calListing || ! in_array($calListing, $validKeys, true)) {
                $calListing = $calendarListingOptions->first()['key'];
            }
            $selectedCalendarListingKey = $calListing;

            if (str_starts_with($calListing, 'h-')) {
                $hid = (int) substr($calListing, 2);
                $selectedCalHotel = $hotels->firstWhere('id', $hid);
                if ($selectedCalHotel) {
                    $selectedCalendarHotelId = $selectedCalHotel->id;
                    $bookingCalendarPayload = RoomBookingCalendarService::buildForHotel($selectedCalHotel, $calYear, $calCalendarView);
                }
            } elseif (str_starts_with($calListing, 'p-')) {
                $pid = (int) substr($calListing, 2);
                $selectedCalProperty = $ownedProperties->firstWhere('id', $pid);
                if ($selectedCalProperty) {
                    $bookingCalendarPayload = RoomBookingCalendarService::buildForProperty($selectedCalProperty, $calYear, $calCalendarView);
                }
            }

            foreach ([$calYear - 1, $calYear, $calYear + 1] as $y) {
                if ($y < 2020 || $y > 2035) {
                    continue;
                }
                $calendarYearUrls[$y] = route('myProperties', array_filter([
                    'cal_year' => $y,
                    'cal_listing' => $selectedCalendarListingKey,
                    'cal_calendar_view' => $calCalendarView,
                ], static function ($v) {
                    return $v !== null && $v !== '';
                }), true).'#calendar';
            }
        }
    }

    return view('frontend.myProperties', [
        'hotels' => $hotels,
        'ownedProperties' => $ownedProperties ?? collect(),
        'hotelCompletions' => $hotelCompletions ?? [],
        'propertyCompletions' => $propertyCompletions ?? [],
        'amenities' => $amenities,
        'earnings' => $earnings,
        'upcomingBookings' => $upcomingBookings,
        'bookingsHistory' => $bookingsHistory,
        'bookingCalendarData' => $bookingCalendarData,
        'bookingCalendarPayload' => $bookingCalendarPayload,
        'calendarYearUrls' => $calendarYearUrls,
        'selectedCalendarHotelId' => $selectedCalendarHotelId,
        'calendarListingOptions' => $calendarListingOptions,
        'selectedCalendarListingKey' => $selectedCalendarListingKey,
        'calendarView' => $calCalendarView,
    ]);
}



    public function myPropertyCreate()
    {
        $userId = auth()->id();
        $destinations = Category::all();
        $services = Program::all();
        $hotels = Hotel::where('added_by', $userId)->latest()->get();
        
        // Get facility categories for hotels and apartments
        $hotelCategories = \App\Models\FacilityCategory::where('property_type', 'hotel')
            ->where('is_active', true)
            ->with(['facilities' => function($query) {
                $query->where('is_active', true)->orderBy('sort_order');
            }])
            ->orderBy('sort_order')
            ->get();
            
        $apartmentCategories = \App\Models\FacilityCategory::where('property_type', 'apartment')
            ->where('is_active', true)
            ->with(['facilities' => function($query) {
                $query->where('is_active', true)->orderBy('sort_order');
            }])
            ->orderBy('sort_order')
            ->get();
        
        return view('frontend.myPropertyCreate',[
            'destinations'=>$destinations,
            'services'=>$services,
            'hotels'=>$hotels,
            'hotelCategories'=>$hotelCategories,
            'apartmentCategories'=>$apartmentCategories,
        ]);
    }


public function storeHotel(Request $request)
{
try {
        $request->validate([
            'cancellation_free_period' => 'nullable|string|max:10000',
            'cancellation_refund_conditions' => 'nullable|string|max:10000',
            'cancellation_no_show_policy' => 'nullable|string|max:10000',
            'listing_terms' => 'nullable|string|max:65000',
        ]);

        $fileName = '';

        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $path = $file->store('public/images/hotels');
            $fileName = basename($path);
        }

        $slug = Str::slug($request->name);
        $partnerUid = Str::uuid();

        $defaultProgramId = Program::where('title', 'Hotel & Apartment Booking Support')
            ->value('id') ?? 1;

        $hotel = Hotel::create([
            'partner_uid' => $partnerUid,
            'name' => $request->name,
            'type' => $request->type,
            'stars' => $request->stars,
            'location' => $request->location,
            'address' => $request->address,
            'email' => $request->email,
            'phone' => $request->phone,
            'city' => $request->city,
            'description' => $request->description,
            'cancellation_free_period' => $request->cancellation_free_period,
            'cancellation_refund_conditions' => $request->cancellation_refund_conditions,
            'cancellation_no_show_policy' => $request->cancellation_no_show_policy,
            'listing_terms' => $request->listing_terms,
            'image' => $fileName,
            'website' => $request->website,
            'category_id' => 1,
            'program_id' => $defaultProgramId,
            'added_by' => $request->user()->id,
            'slug' => $slug,
            'status' => 'Pending', // New properties created by users default to Pending for admin review
        ]);

        if (!$hotel) {
            return back()
                ->withInput()
                ->with('error', 'Hotel could not be saved. Please try again.');
        }

        // Save amenities if provided
        if ($request->has('amenities') && is_array($request->amenities)) {
            $hotel->amenities()->sync($request->amenities);
        }

        // Notify the property owner (user) about the new property submission
        $owner = $request->user();
        if ($owner) {
            $ownerDetails = [
                'subject'  => 'Your property has been submitted for review',
                'greeting' => 'Hello ' . $owner->name . ',',
                'body'     => "Thank you for adding your property \"{$hotel->name}\" to our platform.\n\n"
                             . "Our admin team will review your submission shortly. You will be notified once it is approved or if any changes are required.",
                'lastline' => 'You can log in any time to view your properties in the My Properties section.',
            ];

            Mail::to($owner->email)->send(new AdminNotification($ownerDetails));
        }

        // Notify Kigali Drive Rentals management (company email) - review and confirm within 30 minutes
        $setting = \App\Models\Setting::first();
        $companyEmail = $setting->email ?? null;
        if ($companyEmail) {
            $companyDetails = [
                'subject'  => 'New property submitted – please review within 30 minutes',
                'greeting' => 'Hello Kigali Drive Rentals Team,',
                'body'     => "A new property has been submitted and requires your review.\n\n"
                             . "Property: {$hotel->name}\n"
                             . "Owner: {$owner->name} ({$owner->email})\n\n"
                             . "Please log in to the admin dashboard, review and confirm this property before 30 minutes:\n"
                             . route('admin.properties.index') . '?status=Pending',
                'lastline' => 'New properties must be approved by Kigali Drive Rentals management before being displayed on the site.',
            ];

            Mail::to($companyEmail)->send(new AdminNotification($companyDetails));
        }

        // Also notify admins (role = 1) as fallback
        $admins = User::where('role', 1)->get();
        if ($admins->isNotEmpty()) {
            $adminDetails = [
                'subject'  => 'New property submitted for approval',
                'greeting' => 'Hello Admin,',
                'body'     => "A new property has been submitted by a user.\n\n"
                             . "Property: {$hotel->name}\n"
                             . "Owner: {$owner->name} ({$owner->email})\n\n"
                             . "Please log in to review and approve/reject within 30 minutes:\n"
                             . route('admin.properties.index') . '?status=Pending',
                'lastline' => 'New properties require approval before being displayed.',
            ];

            foreach ($admins as $admin) {
                Mail::to($admin->email)->send(new AdminNotification($adminDetails));
            }
        }

        return redirect('myProperties')
            ->with('success', 'New Hotel has been saved successfully');

    } catch (\Throwable $e) {
        return back()
            ->withInput()
            ->with('error', 'Something went wrong while saving the hotel.');
    }
}


    public function editHotel(Hotel $hotel)
    {
        $this->authorizeOwner($hotel);
        $hotel->load(['amenities', 'images']);

        $hotelCategories = FacilityCategory::where('property_type', 'hotel')
            ->where('is_active', true)
            ->with(['facilities' => function ($query) {
                $query->where('is_active', true)->orderBy('sort_order');
            }])
            ->orderBy('sort_order')
            ->get();

        $apartmentCategories = FacilityCategory::where('property_type', 'apartment')
            ->where('is_active', true)
            ->with(['facilities' => function ($query) {
                $query->where('is_active', true)->orderBy('sort_order');
            }])
            ->orderBy('sort_order')
            ->get();

        $selectedAmenities = $hotel->amenities->pluck('id')->toArray();

        return view('frontend.owner.hotel-edit', [
            'hotel' => $hotel,
            'hotelCategories' => $hotelCategories,
            'apartmentCategories' => $apartmentCategories,
            'selectedAmenities' => $selectedAmenities,
        ]);
    }

    public function updateHotel(Request $request, Hotel $hotel)
    {
        $this->authorizeOwner($hotel);

        $data = $request->validate([
            'name' => 'required|string|max:255',
            'slug' => ['nullable', 'string', 'max:255', Rule::unique('hotels', 'slug')->ignore($hotel->id)],
            'type' => 'nullable|string|max:100',
            'stars' => 'nullable|string|max:10',
            'location' => 'nullable|string|max:255',
            'address' => 'nullable|string|max:255',
            'phone' => 'nullable|string|max:50',
            'email' => 'nullable|email|max:255',
            'city' => 'nullable|string|max:255',
            'website' => 'nullable|string|max:255',
            'latitude' => 'nullable|numeric',
            'longitude' => 'nullable|numeric',
            'description' => 'nullable|string',
            'cancellation_free_period' => 'nullable|string|max:10000',
            'cancellation_refund_conditions' => 'nullable|string|max:10000',
            'cancellation_no_show_policy' => 'nullable|string|max:10000',
            'listing_terms' => 'nullable|string|max:65000',
            'image' => 'nullable|image|max:4096',
            'category_id' => 'nullable|integer',
            'program_id' => 'nullable|integer',
            'amenities' => 'nullable|array',
            'amenities.*' => 'integer|exists:amenities,id',
        ]);

        if ($request->hasFile('image')) {
            if ($hotel->image && Storage::exists('public/images/hotels/' . $hotel->image)) {
                Storage::delete('public/images/hotels/' . $hotel->image);
            }
            $path = $request->file('image')->store('public/images/hotels');
            $data['image'] = basename($path);
        }

        unset($data['amenities']);

        $data['slug'] = $data['slug'] ?? Str::slug($data['name']) . '-' . Str::random(5);

        $hotel->update($data);

        if ($request->has('amenities')) {
            $hotel->amenities()->sync($request->amenities);
        } else {
            $hotel->amenities()->detach();
        }

        return redirect()->route('my.properties.hotels.edit', $hotel)->with('success', 'Property updated successfully.');
    }

    public function showHotel(Hotel $hotel)
    {
        $this->authorizeOwner($hotel);

        return redirect()->route('my.properties.hotels.edit', $hotel);
    }

    public function createRoom(Hotel $hotel)
    {
        $this->authorizeOwner($hotel);
        $facilityCategories = $this->facilityCategoriesForRooms();

        return view('frontend.owner.room-form', [
            'hotel' => $hotel,
            'room' => null,
            'facilityCategories' => $facilityCategories,
            'selectedAmenities' => [],
        ]);
    }

    public function showRoom(Hotel $hotel, HotelRoom $room)
    {
        if ((int) $room->hotel_id !== (int) $hotel->id) {
            abort(404);
        }
        $this->authorizeOwner($hotel);
        $room->load(['images', 'roomAmenities', 'hotel']);

        return view('frontend.owner.room-show', [
            'hotel' => $hotel,
            'room' => $room,
        ]);
    }

    public function storeRoom(Request $request, Hotel $hotel)
    {
        $this->authorizeOwner($hotel);

        $data = $request->validate([
            'room_type' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255|unique:hotel_rooms,slug',
            'image' => 'nullable|image|max:4096',
            'max_occupancy' => 'nullable|integer',
            'price_per_night' => 'required|numeric',
            'price_per_month' => 'nullable|numeric',
            'currency' => 'nullable|string|max:3',
            'price_display_type' => 'nullable|in:per_night,per_month,both',
            'total_rooms' => 'nullable|integer',
            'available_rooms' => 'nullable|integer',
            'description' => 'nullable|string',
            'amenities' => 'nullable|array',
            'amenities.*' => 'integer|exists:amenities,id',
            'status' => ['nullable', Rule::in(['Available', 'Unavailable'])],
            'accepts_room_bookings' => 'nullable|boolean',
        ]);

        $amenityIds = $data['amenities'] ?? [];
        unset($data['amenities']);

        $data['accepts_room_bookings'] = $request->has('accepts_room_bookings');

        $data['hotel_id'] = $hotel->id;
        $data['added_by'] = auth()->id();
        $data['status'] = $data['status'] ?? 'Available';
        $data['slug'] = $data['slug'] ?? Str::slug($data['room_type']) . '-' . Str::random(5);

        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('public/images/rooms');
            $data['image'] = basename($path);
        }

        $room = HotelRoom::create($data);

        if (! empty($amenityIds)) {
            $room->roomAmenities()->sync($amenityIds);
        }

        return redirect()
            ->route('my.properties.rooms.show', [$hotel, $room])
            ->with('success', 'Room added. You can upload more images below.');
    }

    public function editRoom(HotelRoom $room)
    {
        $hotel = $room->hotel;
        $this->authorizeOwner($hotel);
        $room->load(['images', 'roomAmenities']);
        $facilityCategories = $this->facilityCategoriesForRooms();
        $selectedAmenities = $room->roomAmenities->pluck('id')->toArray();

        return view('frontend.owner.room-form', [
            'hotel' => $hotel,
            'room' => $room,
            'facilityCategories' => $facilityCategories,
            'selectedAmenities' => $selectedAmenities,
        ]);
    }

    public function updateRoom(Request $request, HotelRoom $room)
    {
        $hotel = $room->hotel;
        $this->authorizeOwner($hotel);

        $data = $request->validate([
            'room_type' => 'required|string|max:255',
            'slug' => ['nullable', 'string', 'max:255', Rule::unique('hotel_rooms', 'slug')->ignore($room->id)],
            'image' => 'nullable|image|max:4096',
            'max_occupancy' => 'nullable|integer',
            'price_per_night' => 'required|numeric',
            'price_per_month' => 'nullable|numeric',
            'currency' => 'nullable|string|max:3',
            'price_display_type' => 'nullable|in:per_night,per_month,both',
            'total_rooms' => 'nullable|integer',
            'available_rooms' => 'nullable|integer',
            'description' => 'nullable|string',
            'amenities' => 'nullable|array',
            'amenities.*' => 'integer|exists:amenities,id',
            'status' => ['nullable', Rule::in(['Available', 'Unavailable'])],
            'accepts_room_bookings' => 'nullable|boolean',
        ]);

        unset($data['amenities']);

        $data['accepts_room_bookings'] = $request->has('accepts_room_bookings');

        if ($request->hasFile('image')) {
            if ($room->image && Storage::exists('public/images/rooms/'.$room->image)) {
                Storage::delete('public/images/rooms/'.$room->image);
            }
            $path = $request->file('image')->store('public/images/rooms');
            $data['image'] = basename($path);
        }

        $room->update($data);

        if ($request->has('amenities')) {
            $room->roomAmenities()->sync($request->input('amenities', []));
        } else {
            $room->roomAmenities()->detach();
        }

        return redirect()
            ->route('my.properties.rooms.show', [$hotel, $room])
            ->with('success', 'Room updated successfully.');
    }

    /**
     * Soft-delete a property and its rooms (files kept for possible admin restore).
     */
    public function destroyHotel(Hotel $hotel)
    {
        $this->authorizeOwner($hotel);

        $hotel->rooms()->delete();
        $hotel->delete();

        return redirect()
            ->route('myProperties')
            ->with('success', 'Property removed from your dashboard. Records are archived and may be restored by an administrator.');
    }

    /**
     * Soft-delete a room (files kept for possible restore).
     */
    public function destroyRoom(HotelRoom $room)
    {
        $hotel = $room->hotel;
        $this->authorizeOwner($hotel);

        $room->delete();

        return redirect()
            ->route('my.properties.hotels.edit', $hotel)
            ->with('success', 'Room removed from your listing. It can be restored by an administrator if needed.');
    }

    /**
     * Add images to a room gallery (user-facing)
     */
    public function addRoomImage(Request $request, HotelRoom $room)
    {
        $hotel = $room->hotel;
        $this->authorizeOwner($hotel);

        $request->validate([
            'image.*' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        if ($request->hasFile('image')) {
            foreach ($request->file('image') as $image) {
                $path = $image->store('public/images/rooms');
                $fileName = basename($path);

                HotelRoomImage::create([
                    'image' => $fileName,
                    'hotel_room_id' => $room->id,
                    'added_by' => auth()->id()
                ]);
            }

            return redirect()->back()->with('success', 'Images uploaded successfully!');
        }

        return redirect()->back()->with('error', 'No images were uploaded.');
    }

    /**
     * Delete an image from room gallery (user-facing)
     */
    public function deleteRoomImage($id)
    {
        $image = HotelRoomImage::findOrFail($id);
        $room = $image->room;
        $hotel = $room->hotel;
        
        $this->authorizeOwner($hotel);

        $imagePath = 'public/images/rooms/' . $image->image;

        if (Storage::exists($imagePath)) {
            Storage::delete($imagePath);
        }

        $image->delete();

        return redirect()->back()->with('success', 'Image has been deleted successfully.');
    }

    /**
     * Add images to a property gallery (user-facing)
     */
    public function addPropertyImage(Request $request, Hotel $hotel)
    {
        $this->authorizeOwner($hotel);

        $request->validate([
            'image.*' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:4096',
        ]);

        if ($request->hasFile('image')) {
            foreach ($request->file('image') as $image) {
                $path = $image->store('public/images/hotels');
                $fileName = basename($path);

                HotelImage::create([
                    'hotel_id' => $hotel->id,
                    'added_by' => auth()->id(),
                    'image' => $fileName,
                ]);
            }

            return redirect()->back()->with('success', 'Images uploaded successfully!');
        }

        return redirect()->back()->with('error', 'No images were uploaded.');
    }

    /**
     * Delete an image from property gallery (user-facing)
     */
    public function deletePropertyImage($id)
    {
        $image = HotelImage::findOrFail($id);
        $hotel = $image->hotel;
        
        $this->authorizeOwner($hotel);

        $imagePath = 'public/images/hotels/' . $image->image;

        if (Storage::exists($imagePath)) {
            Storage::delete($imagePath);
        }

        $image->delete();

        return redirect()->back()->with('success', 'Image has been deleted successfully.');
    }

    public function showHotelListingAgreement(Hotel $hotel)
    {
        $this->authorizeOwner($hotel);
        $hotel->load('listingAgreementSignature');
        $template = ListingAgreementTemplate::current();

        return view('frontend.owner.listing-agreement', [
            'template' => $template,
            'hotel' => $hotel,
            'propertyModel' => null,
            'completion' => ListingCompletionService::forHotel($hotel),
        ]);
    }

    public function signHotelListingAgreement(Request $request, Hotel $hotel)
    {
        $this->authorizeOwner($hotel);

        $request->validate([
            'signature_image' => 'required|image|max:4096',
            'confirm_agreement' => 'required|accepted',
        ]);

        $template = ListingAgreementTemplate::current();
        $path = $request->file('signature_image')->store('public/listing-agreements/owner');
        $relative = str_replace('public/', '', $path);

        $existing = $hotel->listingAgreementSignature;
        if ($existing && $existing->owner_signature_path && Storage::exists('public/'.$existing->owner_signature_path)) {
            Storage::delete('public/'.$existing->owner_signature_path);
        }

        ListingAgreementSignature::updateOrCreate(
            [
                'signable_type' => Hotel::class,
                'signable_id' => $hotel->id,
            ],
            [
                'owner_signature_path' => $relative,
                'signed_at' => now(),
                'template_version_at' => $template->fresh()->updated_at,
                'signer_ip' => $request->ip(),
            ]
        );

        return redirect()->to(route('myProperties').'#properties')->with('success', 'Listing agreement signed. Your listing setup is updated.');
    }

    public function showPropertyListingAgreement(Property $property)
    {
        $this->authorizePropertyOwner($property);
        $property->load('listingAgreementSignature', 'units', 'images');
        $template = ListingAgreementTemplate::current();

        return view('frontend.owner.listing-agreement', [
            'template' => $template,
            'hotel' => null,
            'propertyModel' => $property,
            'completion' => ListingCompletionService::forProperty($property),
        ]);
    }

    public function signPropertyListingAgreement(Request $request, Property $property)
    {
        $this->authorizePropertyOwner($property);

        $request->validate([
            'signature_image' => 'required|image|max:4096',
            'confirm_agreement' => 'required|accepted',
        ]);

        $template = ListingAgreementTemplate::current();
        $path = $request->file('signature_image')->store('public/listing-agreements/owner');
        $relative = str_replace('public/', '', $path);

        $existing = $property->listingAgreementSignature;
        if ($existing && $existing->owner_signature_path && Storage::exists('public/'.$existing->owner_signature_path)) {
            Storage::delete('public/'.$existing->owner_signature_path);
        }

        ListingAgreementSignature::updateOrCreate(
            [
                'signable_type' => Property::class,
                'signable_id' => $property->id,
            ],
            [
                'owner_signature_path' => $relative,
                'signed_at' => now(),
                'template_version_at' => $template->fresh()->updated_at,
                'signer_ip' => $request->ip(),
            ]
        );

        return redirect()->to(route('myProperties').'#properties')->with('success', 'Listing agreement signed.');
    }

    protected function authorizePropertyOwner(Property $property): void
    {
        if ((int) $property->owner_id !== (int) auth()->id()) {
            abort(403);
        }
    }

    protected function authorizeOwner(Hotel $hotel)
    {
        if ($hotel->added_by !== auth()->id()) {
            abort(403);
        }
    }
}

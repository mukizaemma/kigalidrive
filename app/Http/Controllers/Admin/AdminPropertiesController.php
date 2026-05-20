<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Property;
use App\Models\Hotel;
use App\Models\Category;
use App\Support\SchemaHelper;
use App\Models\Amenity;
use App\Models\FacilityCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class AdminPropertiesController extends Controller
{
    /**
     * Full admin (super admin): can change status/verified/featured.
     * Role 2 and others: manage only their own listings without admin-only fields.
     */
    protected function isPropertySuperAdmin(): bool
    {
        $user = Auth::user();

        return $user && ($user->role == '1' || $user->role === 1);
    }

    protected function legacyHotelsEnabled(): bool
    {
        return SchemaHelper::legacyHotelsEnabled();
    }

    /**
     * Property managers must verify email before managing listings (super admin exempt).
     */
    protected function ensureVerifiedForPropertyManagers(): ?\Illuminate\Http\RedirectResponse
    {
        $user = Auth::user();
        if ($user && $user->exemptFromEmailVerification()) {
            return null;
        }
        if ($user && ! $user->hasVerifiedEmail()) {
            return redirect()->route('verification.notice')
                ->with('error', 'Please verify your email address before managing properties and rooms.');
        }

        return null;
    }

    protected function formatPropertyRoomTypeSummary(Property $property): string
    {
        if ($property->units->isEmpty()) {
            return '';
        }

        return $property->units->groupBy('unit_type_id')->map(function ($units) {
            $label = optional($units->first()->unitType)->name ?? __('Room');
            $n = (int) $units->sum('total_units');

            return $label . ': ' . $n;
        })->implode(' · ');
    }

    protected function formatHotelRoomTypeSummary(Hotel $hotel): string
    {
        if (!$hotel->relationLoaded('rooms') || $hotel->rooms->isEmpty()) {
            return '';
        }

        return $hotel->rooms->groupBy('room_type')->map(function ($rooms, $type) {
            $label = $type ?: __('Standard');
            $n = (int) $rooms->sum('total_rooms');

            return $label . ': ' . $n;
        })->implode(' · ');
    }

    /**
     * Display a listing of properties.
     * Shows both admin-created properties (Property model) and user-created properties (Hotel model).
     */
    public function index(Request $request)
    {
        // Query admin-created properties (Property model)
        $propertyQuery = Property::with(['owner', 'units.unitType']);

        if (! $this->isPropertySuperAdmin()) {
            $propertyQuery->where('owner_id', Auth::id());
        } elseif ($request->boolean('mine')) {
            $propertyQuery->where('owner_id', Auth::id());
        }

        // Filter by type
        if ($request->has('type') && $request->type) {
            if ($request->type == 'hotel') {
                $propertyQuery->where('property_type', 'hotel');
            } elseif ($request->type == 'apartment') {
                $propertyQuery->where('property_type', 'apartment');
            }
        }

        // Search filter
        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $propertyQuery->where(function ($q) use ($search) {
                $q->where('name', 'LIKE', "%$search%")
                  ->orWhere('location', 'LIKE', "%$search%")
                  ->orWhere('city', 'LIKE', "%$search%")
                  ->orWhere('phone', 'LIKE', "%$search%");
            });
        }

        // Filter by status
        if ($request->has('status') && $request->status) {
            $propertyQuery->where('status', $request->status);
        }

        $properties = $propertyQuery->latest()->get();
        $hotels = collect();
        if ($this->legacyHotelsEnabled()) {
            $hotelQuery = Hotel::with(['owner', 'rooms']);
            if (! $this->isPropertySuperAdmin()) {
                $hotelQuery->where('added_by', Auth::id());
            } elseif ($request->boolean('mine')) {
                $hotelQuery->where('added_by', Auth::id());
            }
            if ($request->has('type') && $request->type === 'apartment') {
                $hotelQuery->whereRaw('1 = 0');
            }
            if ($request->has('search') && $request->search) {
                $search = $request->search;
                $hotelQuery->where(function ($q) use ($search) {
                    $q->where('name', 'LIKE', "%$search%")
                      ->orWhere('location', 'LIKE', "%$search%")
                      ->orWhere('city', 'LIKE', "%$search%")
                      ->orWhere('phone', 'LIKE', "%$search%");
                });
            }
            if ($request->has('status') && $request->status) {
                $hotelQuery->where('status', $request->status);
            }
            $hotels = $hotelQuery->latest()->get();
        }

        // Transform hotels to match property structure for unified display
        $allProperties = $properties->map(function ($property) {
            return (object) [
                'id' => $property->id,
                'name' => $property->name,
                'type' => $property->property_type ?? 'hotel',
                'property_type' => $property->property_type ?? 'hotel',
                'owner' => $property->owner,
                'location' => $property->location,
                'city' => $property->city,
                'status' => $property->status ?? 'Pending',
                'stars' => $property->stars,
                'is_featured' => $property->is_featured ?? false,
                'is_verified' => $property->is_verified ?? false,
                'units' => $property->units ?? collect(),
                'room_type_summary' => $this->formatPropertyRoomTypeSummary($property),
                'total_room_inventory' => (int) $property->units->sum('total_units'),
                'image' => $property->featured_image ?? null,
                'phone' => $property->phone,
                'email' => $property->email,
                'is_hotel_model' => false, // Flag to identify Property model
            ];
        });

        $allHotels = $hotels->map(function ($hotel) {
            return (object) [
                'id' => $hotel->id,
                'name' => $hotel->name,
                'type' => $hotel->type ?? 'hotel',
                'property_type' => $hotel->type ?? 'hotel',
                'owner' => $hotel->owner,
                'location' => $hotel->location,
                'city' => $hotel->city,
                'status' => $hotel->status ?? 'Pending',
                'stars' => $hotel->stars,
                'is_featured' => false,
                'is_verified' => false,
                'units' => $hotel->rooms ?? collect(),
                'room_type_summary' => $this->formatHotelRoomTypeSummary($hotel),
                'total_room_inventory' => (int) $hotel->rooms->sum('total_rooms'),
                'image' => $hotel->image,
                'phone' => $hotel->phone,
                'email' => $hotel->email,
                'is_hotel_model' => true, // Flag to identify Hotel model
            ];
        });

        // Merge and sort by created_at
        $merged = $allProperties->merge($allHotels)->sortByDesc(function ($item) {
            if ($item->is_hotel_model && $this->legacyHotelsEnabled()) {
                return Hotel::find($item->id)?->created_at ?? now();
            }

            return Property::find($item->id)?->created_at ?? now();
        });

        // Manual pagination
        $page = $request->get('page', 1);
        $perPage = 15;
        $total = $merged->count();
        $items = $merged->slice(($page - 1) * $perPage, $perPage)->values();

        // Create paginator manually
        $properties = new \Illuminate\Pagination\LengthAwarePaginator(
            $items,
            $total,
            $perPage,
            $page,
            ['path' => $request->url(), 'query' => $request->query()]
        );

        $categories = SchemaHelper::legacyCategoriesEnabled() ? Category::all() : collect();
        $programs = collect();
        $setting = \App\Models\Setting::first();

        if ($this->isPropertySuperAdmin()) {
            $pendingCount = Property::where('status', 'Pending')->count();
            $activeCount = Property::where('status', 'Active')->count();
            $inactiveCount = Property::where('status', 'Inactive')->count();
            if ($this->legacyHotelsEnabled()) {
                $pendingCount += Hotel::where('status', 'Pending')->count();
                $activeCount += Hotel::where('status', 'Active')->count();
                $inactiveCount += Hotel::where('status', 'Inactive')->count();
            }
        } else {
            $uid = Auth::id();
            $pendingCount = Property::where('owner_id', $uid)->where('status', 'Pending')->count();
            $activeCount = Property::where('owner_id', $uid)->where('status', 'Active')->count();
            $inactiveCount = Property::where('owner_id', $uid)->where('status', 'Inactive')->count();
            if ($this->legacyHotelsEnabled()) {
                $pendingCount += Hotel::where('added_by', $uid)->where('status', 'Pending')->count();
                $activeCount += Hotel::where('added_by', $uid)->where('status', 'Active')->count();
                $inactiveCount += Hotel::where('added_by', $uid)->where('status', 'Inactive')->count();
            }
        }

        return view('admin.properties.index', [
            'properties' => $properties,
            'categories' => $categories,
            'programs' => $programs,
            'setting' => $setting,
            'pendingCount' => $pendingCount,
            'activeCount' => $activeCount,
            'inactiveCount' => $inactiveCount,
            'isPropertySuperAdmin' => $this->isPropertySuperAdmin(),
            'mineOnly' => $request->boolean('mine'),
        ]);
    }

    /**
     * Show the form for creating a new property.
     */
    public function create()
    {
        if ($redirect = $this->ensureVerifiedForPropertyManagers()) {
            return $redirect;
        }

        $categories = SchemaHelper::legacyCategoriesEnabled() ? Category::all() : collect();
        $programs = collect();
        $amenities = Amenity::with('category')->orderBy('title')->get();
        $facilityCategories = FacilityCategory::with(['facilities' => function($query) {
            $query->active()->orderBy('title');
        }])->get();
        $setting = \App\Models\Setting::first();

        return view('admin.properties.create', [
            'categories' => $categories,
            'programs' => $programs,
            'amenities' => $amenities,
            'facilityCategories' => $facilityCategories,
            'setting' => $setting,
            'isPropertySuperAdmin' => $this->isPropertySuperAdmin(),
        ]);
    }

    /**
     * Store a newly created property.
     */
    public function store(Request $request)
    {
        if ($redirect = $this->ensureVerifiedForPropertyManagers()) {
            return $redirect;
        }

        $super = $this->isPropertySuperAdmin();

        $rules = [
            'name' => 'required|string|max:255',
            'property_type' => 'required|in:apartment,villa,hotel,guesthouse,lodge',
            'category_id' => SchemaHelper::legacyCategoriesEnabled() ? 'nullable|exists:categories,id' : 'nullable',
            'stars' => 'nullable|string|max:10',
            'description' => 'nullable|string',
            'address' => 'nullable|string|max:255',
            'city' => 'nullable|string|max:255',
            'location' => 'required|string|max:255',
            'latitude' => 'nullable|numeric',
            'longitude' => 'nullable|numeric',
            'map_embed_code' => 'nullable|string',
            'phone' => 'nullable|string|max:50',
            'email' => 'nullable|email|max:255',
            'contact_person_name' => 'nullable|string|max:255',
            'website' => 'nullable|string|max:255',
            'featured_image' => 'nullable|image|max:4096',
            'facilities' => 'nullable|array',
            'facilities.*' => 'exists:amenities,id',
            'listing_terms' => 'nullable|string|max:65000',
        ];

        if ($super) {
            $rules['status'] = 'nullable|in:Active,Inactive,Pending';
            $rules['is_featured'] = 'nullable|boolean';
            $rules['is_verified'] = 'nullable|boolean';
        }

        $request->validate($rules);

        $slug = Str::slug($request->name);
        $originalSlug = $slug;
        $counter = 1;
        while (Property::where('slug', $slug)->exists()) {
            $slug = $originalSlug . '-' . $counter;
            $counter++;
        }

        $fileName = null;
        if ($request->hasFile('featured_image')) {
            $file = $request->file('featured_image');
            $path = $file->store('public/images/properties');
            $fileName = basename($path);
        }

        $meta = [];
        if ($request->filled('contact_person_name')) {
            $meta['contact_person_name'] = $request->input('contact_person_name');
        }

        $property = Property::create([
            'owner_id' => Auth::id(),
            'name' => $request->name,
            'slug' => $slug,
            'property_type' => $request->property_type,
            'stars' => $request->stars,
            'description' => $request->description,
            'cancellation_free_period' => null,
            'cancellation_refund_conditions' => null,
            'cancellation_no_show_policy' => null,
            'listing_terms' => $request->listing_terms,
            'address' => $request->address,
            'city' => $request->city,
            'location' => $request->location,
            'latitude' => $request->latitude,
            'longitude' => $request->longitude,
            'map_embed_code' => $request->map_embed_code,
            'phone' => $request->phone,
            'email' => $request->email,
            'website' => $request->website,
            'featured_image' => $fileName,
            'status' => $super ? ($request->input('status') ?? 'Pending') : 'Active',
            'is_featured' => $super && $request->has('is_featured'),
            'is_verified' => $super && $request->has('is_verified'),
            'is_listing_visible' => true,
            'accepts_bookings' => true,
            'meta_data' => !empty($meta) ? $meta : null,
        ]);

        // Attach facilities
        if ($request->has('facilities')) {
            $property->facilities()->sync($request->facilities);
        }

        return redirect()->route('admin.properties.index')
            ->with('success', 'Property created successfully');
    }

    /**
     * Display the specified property.
     * Handles both Property model (admin-created) and Hotel model (user-created) properties.
     */
    public function show($id)
    {
        // Try Property model first
        $property = Property::with(['owner', 'units.unitType', 'images', 'facilities'])->find($id);
        $isHotelModel = false;
        $roomInventory = collect();

        // If not found, try legacy Hotel model (pre-KDR listings)
        if (! $property && $this->legacyHotelsEnabled()) {
            $hotel = Hotel::with(['owner', 'rooms'])->findOrFail($id);
            if (!$this->isPropertySuperAdmin() && (int) $hotel->added_by !== (int) Auth::id()) {
                abort(403);
            }
            $isHotelModel = true;
            $roomInventory = $hotel->rooms->groupBy('room_type')->map(function ($rooms, $type) {
                return [
                    'type_name' => $type ?: __('Standard'),
                    'count' => (int) $rooms->sum('total_rooms'),
                ];
            })->values();
            // Transform hotel to match property structure for view compatibility
            $property = (object) [
                'id' => $hotel->id,
                'name' => $hotel->name,
                'property_type' => $hotel->type ?? 'hotel',
                'owner' => $hotel->owner,
                'location' => $hotel->location,
                'city' => $hotel->city,
                'status' => $hotel->status ?? 'Pending',
                'stars' => $hotel->stars,
                'description' => $hotel->description,
                'phone' => $hotel->phone,
                'email' => $hotel->email,
                'website' => $hotel->website,
                'address' => $hotel->address,
                'image' => $hotel->image,
                'units' => $hotel->rooms,
                'images' => collect(),
                'facilities' => collect(),
                'is_hotel_model' => true,
                'hotel' => $hotel, // Keep reference to original hotel model
                'meta_data' => [],
            ];
        } elseif ($property) {
            if (! $this->isPropertySuperAdmin() && (int) $property->owner_id !== (int) Auth::id()) {
                abort(403);
            }
            $roomInventory = $property->units->groupBy('unit_type_id')->map(function ($units) {
                return [
                    'type_name' => optional($units->first()->unitType)->name ?? __('Unclassified'),
                    'count' => (int) $units->sum('total_units'),
                ];
            })->values();
        } else {
            abort(404);
        }

        $setting = \App\Models\Setting::first();

        return view('admin.properties.show', [
            'property' => $property,
            'setting' => $setting,
            'isHotelModel' => $isHotelModel,
            'roomInventory' => $roomInventory,
            'isPropertySuperAdmin' => $this->isPropertySuperAdmin(),
        ]);
    }

    /**
     * Show the form for editing the specified property.
     */
    public function edit($id)
    {
        if ($redirect = $this->ensureVerifiedForPropertyManagers()) {
            return $redirect;
        }

        $property = Property::with(['facilities', 'images' => function($query) {
            $query->orderBy('is_primary', 'desc')->orderBy('sort_order');
        }])->findOrFail($id);

        if (!$this->isPropertySuperAdmin() && (int) $property->owner_id !== (int) Auth::id()) {
            abort(403);
        }

        $categories = SchemaHelper::legacyCategoriesEnabled() ? Category::all() : collect();
        $programs = collect();
        $amenities = Amenity::with('category')->active()->orderBy('title')->get();
        $facilityCategories = FacilityCategory::with(['facilities' => function($query) {
            $query->active()->orderBy('title');
        }])->where('is_active', true)->orderBy('sort_order')->get();
        $setting = \App\Models\Setting::first();

        return view('admin.properties.edit', [
            'property' => $property,
            'categories' => $categories,
            'programs' => $programs,
            'amenities' => $amenities,
            'facilityCategories' => $facilityCategories,
            'setting' => $setting,
            'isPropertySuperAdmin' => $this->isPropertySuperAdmin(),
        ]);
    }

    /**
     * Update the specified property.
     */
    public function update(Request $request, $id)
    {
        if ($redirect = $this->ensureVerifiedForPropertyManagers()) {
            return $redirect;
        }

        try {
            $property = Property::findOrFail($id);

            if (!$this->isPropertySuperAdmin() && (int) $property->owner_id !== (int) Auth::id()) {
                abort(403);
            }

            $super = $this->isPropertySuperAdmin();

            $rules = [
                'name' => 'required|string|max:255',
                'property_type' => 'required|in:apartment,villa,hotel,guesthouse,lodge',
                'category_id' => 'nullable|exists:categories,id',
                'stars' => 'nullable|string|max:10',
                'description' => 'nullable|string',
                'address' => 'nullable|string|max:255',
                'city' => 'nullable|string|max:255',
                'location' => 'required|string|max:255',
                'latitude' => 'nullable|numeric',
                'longitude' => 'nullable|numeric',
                'map_embed_code' => 'nullable|string',
                'phone' => 'nullable|string|max:50',
                'email' => 'nullable|email|max:255',
                'contact_person_name' => 'nullable|string|max:255',
                'website' => 'nullable|string|max:255',
                'featured_image' => 'nullable|image|max:4096',
                'facilities' => 'nullable|array',
                'facilities.*' => 'exists:amenities,id',
                'listing_terms' => 'nullable|string|max:65000',
            ];

            if ($super) {
                $rules['status'] = 'required|in:Active,Inactive,Pending';
                $rules['is_featured'] = 'nullable|boolean';
                $rules['is_verified'] = 'nullable|boolean';
            }

            $request->validate($rules);

            $slug = Str::slug($request->name);
            if ($slug !== $property->slug) {
                $originalSlug = $slug;
                $counter = 1;
                while (Property::where('slug', $slug)->where('id', '!=', $id)->exists()) {
                    $slug = $originalSlug . '-' . $counter;
                    $counter++;
                }
            }

            $newFeaturedFileName = null;
            if ($request->hasFile('featured_image')) {
                if ($property->featured_image && Storage::exists('public/images/properties/' . $property->featured_image)) {
                    Storage::delete('public/images/properties/' . $property->featured_image);
                }
                $file = $request->file('featured_image');
                $path = $file->store('public/images/properties');
                $newFeaturedFileName = basename($path);
            }

            $meta = $property->meta_data ?? [];
            if ($request->has('contact_person_name')) {
                $meta['contact_person_name'] = $request->input('contact_person_name');
            }

            $updatePayload = [
                'category_id' => $request->category_id,
                'name' => $request->name,
                'slug' => $slug,
                'property_type' => $request->property_type,
                'stars' => $request->stars,
                'description' => $request->description,
                'cancellation_free_period' => null,
                'cancellation_refund_conditions' => null,
                'cancellation_no_show_policy' => null,
                'listing_terms' => $request->listing_terms,
                'address' => $request->address,
                'city' => $request->city,
                'location' => $request->location,
                'latitude' => $request->latitude,
                'longitude' => $request->longitude,
                'map_embed_code' => $request->map_embed_code,
                'phone' => $request->phone,
                'email' => $request->email,
                'website' => $request->website,
                'meta_data' => $meta,
                'partner_id' => null,
            ];

            if ($newFeaturedFileName !== null) {
                $updatePayload['featured_image'] = $newFeaturedFileName;
            }

            if ($super) {
                $updatePayload['status'] = $request->status;
                $updatePayload['is_featured'] = $request->has('is_featured');
                $updatePayload['is_verified'] = $request->has('is_verified');
                $updatePayload['is_listing_visible'] = $request->has('is_listing_visible');
                $updatePayload['accepts_bookings'] = $request->has('accepts_bookings');
            }

            $property->update($updatePayload);

            // Sync facilities
            if ($request->has('facilities')) {
                $property->facilities()->sync($request->facilities);
            } else {
                $property->facilities()->detach();
            }

            return redirect()->route('admin.properties.index')
                ->with('success', 'Property updated successfully');
        } catch (\Illuminate\Validation\ValidationException $e) {
            return redirect()->back()
                ->withErrors($e->errors())
                ->withInput();
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'An error occurred while updating the property: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Update property status (Approve/Inactive/Active).
     * Supports both POST (with status in request body) and GET (with status in URL parameter).
     * Handles both Property model (admin-created) and Hotel model (user-created) properties.
     */
    public function updateStatus(Request $request, $id, $status = null)
    {
        if (!$this->isPropertySuperAdmin()) {
            if ($request->ajax() || $request->wantsJson() || $request->expectsJson() || $request->header('X-Requested-With') == 'XMLHttpRequest') {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthorized',
                ], 403);
            }
            abort(403);
        }

        try {
            // Get status from URL parameter (GET request) or request body (POST request)
            $newStatus = $status ?? $request->input('status');
            
            // Try to find in Property model first (admin-created properties)
            $property = Property::find($id);
            if ($property) {
                $property->update(['status' => $newStatus]);
                $propertyName = $property->name;
            } elseif ($this->legacyHotelsEnabled()) {
                $hotel = Hotel::find($id);
                if ($hotel) {
                    $hotel->update(['status' => $newStatus]);
                    $propertyName = $hotel->name;
                } else {
                    return response()->json([
                        'success' => false,
                        'message' => 'Property not found',
                    ], 404);
                }
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'Property not found',
                ], 404);
            }

            // Validate status
            if (!$newStatus || !in_array($newStatus, ['Active', 'Inactive', 'Pending'])) {
                if ($request->ajax() || $request->wantsJson() || $request->expectsJson() || $request->header('X-Requested-With') == 'XMLHttpRequest') {
                    return response()->json([
                        'success' => false,
                        'message' => 'Invalid status. Must be Active, Inactive, or Pending.',
                    ], 422);
                }
                return redirect()->route('admin.properties.index')
                    ->with('error', 'Invalid status. Must be Active, Inactive, or Pending.');
            }

            $statusMessages = [
                'Active' => 'Property approved and set to Active',
                'Inactive' => 'Property set to Inactive',
                'Pending' => 'Property set to Pending',
            ];

            $finalStatus = $newStatus;

            // Always return JSON for AJAX requests
            if ($request->ajax() || $request->wantsJson() || $request->expectsJson() || $request->header('X-Requested-With') == 'XMLHttpRequest') {
                return response()->json([
                    'success' => true,
                    'message' => $statusMessages[$newStatus] ?? 'Status updated successfully',
                    'status' => $finalStatus,
                ]);
            }

            return redirect()->route('admin.properties.index')
                ->with('success', $statusMessages[$newStatus] ?? 'Status updated successfully');
        } catch (\Illuminate\Validation\ValidationException $e) {
            if ($request->ajax() || $request->wantsJson() || $request->expectsJson() || $request->header('X-Requested-With') == 'XMLHttpRequest') {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors' => $e->errors(),
                ], 422);
            }
            throw $e;
        } catch (\Exception $e) {
            if ($request->ajax() || $request->wantsJson() || $request->expectsJson() || $request->header('X-Requested-With') == 'XMLHttpRequest') {
                return response()->json([
                    'success' => false,
                    'message' => 'An error occurred: ' . $e->getMessage(),
                ], 500);
            }
            throw $e;
        }
    }

    /**
     * Remove the specified property.
     */
    public function destroy($id)
    {
        if ($redirect = $this->ensureVerifiedForPropertyManagers()) {
            return $redirect;
        }

        $property = Property::findOrFail($id);

        if (!$this->isPropertySuperAdmin() && (int) $property->owner_id !== (int) Auth::id()) {
            abort(403);
        }

        $property->delete();

        return redirect()->route('admin.properties.index')
            ->with('success', 'Property deleted successfully');
    }
}


<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Unit;
use App\Models\Property;
use App\Models\UnitType;
use App\Models\Amenity;
use App\Models\FacilityCategory;
use App\Models\ExtraChargeType;
use App\Models\UnitExtraCharge;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Builder;

class AdminUnitsController extends Controller
{
    protected function isPropertySuperAdmin(): bool
    {
        $user = Auth::user();

        return $user && ($user->role == '1' || $user->role === 1);
    }

    /**
     * Properties the current user may attach units to (all for super admin; own listings for owners).
     */
    protected function manageablePropertiesQuery(): Builder
    {
        $query = Property::query()->orderBy('name');
        if (! $this->isPropertySuperAdmin()) {
            $query->where('owner_id', Auth::id());
        }

        return $query;
    }

    protected function userMayAccessPropertyId(int $propertyId): bool
    {
        if ($this->isPropertySuperAdmin()) {
            return Property::whereKey($propertyId)->exists();
        }

        return Property::whereKey($propertyId)->where('owner_id', Auth::id())->exists();
    }

    protected function authorizeUnitAccess(Unit $unit): void
    {
        if ($this->isPropertySuperAdmin()) {
            return;
        }
        $unit->loadMissing('property');
        if (! $unit->property || (int) $unit->property->owner_id !== (int) Auth::id()) {
            abort(403);
        }
    }

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

    /**
     * Display a listing of units.
     */
    public function index(Request $request)
    {
        $query = Unit::with(['property', 'unitType', 'addedBy']);

        if (! $this->isPropertySuperAdmin()) {
            $query->whereHas('property', function ($q) {
                $q->where('owner_id', Auth::id());
            });
        }

        if ($request->has('property_id') && $request->property_id) {
            if (! $this->userMayAccessPropertyId((int) $request->property_id)) {
                abort(403);
            }
            $query->where('property_id', $request->property_id);
        }

        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'LIKE', "%$search%")
                  ->orWhere('slug', 'LIKE', "%$search%");
            });
        }

        $units = $query->latest()->paginate(15);
        $properties = $this->manageablePropertiesQuery()->get();
        $setting = \App\Models\Setting::first();

        return view('admin.units.index', [
            'units' => $units,
            'properties' => $properties,
            'setting' => $setting,
        ]);
    }

    /**
     * Show the form for creating a new unit.
     */
    public function create(Request $request)
    {
        if ($redirect = $this->ensureVerifiedForPropertyManagers()) {
            return $redirect;
        }

        $propertyId = $request->get('property_id');
        if ($propertyId && ! $this->userMayAccessPropertyId((int) $propertyId)) {
            abort(403);
        }
        $properties = $this->manageablePropertiesQuery()->get();
        $unitTypes = UnitType::active()->orderBy('name')->get();
        $amenities = Amenity::with('category')->active()->orderBy('title')->get();
        $facilityCategories = FacilityCategory::with(['facilities' => function($query) {
            $query->active()->orderBy('title');
        }])->where('is_active', true)->orderBy('sort_order')->get();
        $setting = \App\Models\Setting::first();
        $extraChargeTypes = ExtraChargeType::where('is_active', true)->orderBy('sort_order')->get();

        return view('admin.units.create', [
            'propertyId' => $propertyId,
            'properties' => $properties,
            'unitTypes' => $unitTypes,
            'amenities' => $amenities,
            'facilityCategories' => $facilityCategories,
            'extraChargeTypes' => $extraChargeTypes,
            'setting' => $setting,
        ]);
    }

    /**
     * Store a newly created unit.
     */
    public function store(Request $request)
    {
        if ($redirect = $this->ensureVerifiedForPropertyManagers()) {
            return $redirect;
        }

        $request->validate([
            'property_id' => 'required|exists:properties,id',
            'unit_type_id' => 'nullable|exists:unit_types,id',
            'name' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'max_occupancy' => 'required|integer|min:1',
            'bedrooms' => 'nullable|integer|min:0',
            'bathrooms' => 'nullable|integer|min:1',
            'beds' => 'nullable|integer|min:1',
            'size_sqm' => 'nullable|integer|min:0',
            'total_units' => 'required|integer|min:1',
            'available_units' => 'required|integer|min:0',
            'base_price_per_night' => 'nullable|numeric|min:0',
            'base_price_per_month' => 'nullable|numeric|min:0',
            'currency' => 'nullable|string|max:3',
            'price_display_type' => 'nullable|in:per_night,per_month,both',
            'featured_image' => 'nullable|image|max:4096',
            'status' => 'nullable|in:Available,Unavailable,Maintenance', // Status will default to Available if not provided
            'is_active' => 'nullable|boolean',
            'facilities' => 'nullable|array',
            'facilities.*' => 'exists:amenities,id',
            'extra_charges' => 'nullable|array',
            'extra_charges.*' => 'nullable|numeric|min:0',
        ]);

        if (! $this->userMayAccessPropertyId((int) $request->property_id)) {
            abort(403);
        }

        $name = $request->name ?: 'Unit ' . Str::random(6);
        $slug = Str::slug($name);
        $originalSlug = $slug;
        $counter = 1;
        while (Unit::where('slug', $slug)->exists()) {
            $slug = $originalSlug . '-' . $counter;
            $counter++;
        }

        $fileName = null;
        if ($request->hasFile('featured_image')) {
            $file = $request->file('featured_image');
            $path = $file->store('public/images/units');
            $fileName = basename($path);
        }

        $unit = Unit::create([
            'property_id' => $request->property_id,
            'unit_type_id' => $request->unit_type_id,
            'added_by' => auth()->id(),
            'name' => $name,
            'slug' => $slug,
            'description' => $request->description,
            'max_occupancy' => $request->max_occupancy,
            'bedrooms' => $request->bedrooms ?? 0,
            'bathrooms' => $request->bathrooms ?? 1,
            'beds' => $request->beds ?? 1,
            'size_sqm' => $request->size_sqm,
            'total_units' => $request->total_units,
            'available_units' => $request->available_units,
            'base_price_per_night' => $request->base_price_per_night,
            'base_price_per_month' => $request->base_price_per_month,
            'currency' => $request->currency ?? 'USD',
            'price_display_type' => $request->price_display_type ?? 'per_night',
            'featured_image' => $fileName,
            'status' => $request->status ?? 'Available', // Use provided status or default to Available
            'is_active' => $request->has('is_active'),
        ]);

        // Attach facilities
        if ($request->has('facilities')) {
            $unit->facilities()->sync($request->facilities);
        }

        // Sync additional/extra charges
        $unit->extraChargesAll()->delete();
        if ($request->has('extra_charges') && is_array($request->extra_charges)) {
            foreach ($request->extra_charges as $typeId => $price) {
                $price = is_numeric($price) ? (float) $price : 0;
                if ($price > 0 && ExtraChargeType::find($typeId)) {
                    UnitExtraCharge::create([
                        'unit_id' => $unit->id,
                        'extra_charge_type_id' => $typeId,
                        'price' => $price,
                        'is_active' => true,
                    ]);
                }
            }
        }

        return redirect()->route('admin.units.index', ['property_id' => $request->property_id])
            ->with('success', 'Unit created successfully');
    }

    /**
     * Show the form for editing the specified unit.
     */
    public function edit($id)
    {
        if ($redirect = $this->ensureVerifiedForPropertyManagers()) {
            return $redirect;
        }

        $unit = Unit::with(['facilities', 'extraChargesAll', 'images' => function($query) {
            $query->orderBy('is_primary', 'desc')->orderBy('sort_order');
        }])->findOrFail($id);

        $this->authorizeUnitAccess($unit);

        $properties = $this->manageablePropertiesQuery()->get();
        $unitTypes = UnitType::active()->orderBy('name')->get();
        $amenities = Amenity::with('category')->active()->orderBy('title')->get();
        $facilityCategories = FacilityCategory::with(['facilities' => function($query) {
            $query->active()->orderBy('title');
        }])->where('is_active', true)->orderBy('sort_order')->get();
        $setting = \App\Models\Setting::first();
        $extraChargeTypes = ExtraChargeType::where('is_active', true)->orderBy('sort_order')->get();

        return view('admin.units.edit', [
            'unit' => $unit,
            'properties' => $properties,
            'unitTypes' => $unitTypes,
            'amenities' => $amenities,
            'facilityCategories' => $facilityCategories,
            'extraChargeTypes' => $extraChargeTypes,
            'setting' => $setting,
        ]);
    }

    /**
     * Update the specified unit.
     */
    public function update(Request $request, $id)
    {
        if ($redirect = $this->ensureVerifiedForPropertyManagers()) {
            return $redirect;
        }

        $unit = Unit::findOrFail($id);

        $this->authorizeUnitAccess($unit);

        $request->validate([
            'property_id' => 'required|exists:properties,id',
            'unit_type_id' => 'nullable|exists:unit_types,id',
            'name' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'max_occupancy' => 'required|integer|min:1',
            'bedrooms' => 'nullable|integer|min:0',
            'bathrooms' => 'nullable|integer|min:1',
            'beds' => 'nullable|integer|min:1',
            'size_sqm' => 'nullable|integer|min:0',
            'total_units' => 'required|integer|min:1',
            'available_units' => 'required|integer|min:0',
            'base_price_per_night' => 'nullable|numeric|min:0',
            'base_price_per_month' => 'nullable|numeric|min:0',
            'currency' => 'nullable|string|max:3',
            'price_display_type' => 'nullable|in:per_night,per_month,both',
            'featured_image' => 'nullable|image|max:4096',
            'status' => 'required|in:Available,Unavailable,Maintenance',
            'is_active' => 'nullable|boolean',
            'facilities' => 'nullable|array',
            'facilities.*' => 'exists:amenities,id',
            'extra_charges' => 'nullable|array',
            'extra_charges.*' => 'nullable|numeric|min:0',
        ]);

        if (! $this->userMayAccessPropertyId((int) $request->property_id)) {
            abort(403);
        }

        if ($request->hasFile('featured_image')) {
            if ($unit->featured_image && Storage::exists('public/images/units/' . $unit->featured_image)) {
                Storage::delete('public/images/units/' . $unit->featured_image);
            }
            $file = $request->file('featured_image');
            $path = $file->store('public/images/units');
            $unit->featured_image = basename($path);
        }

        $unit->update([
            'property_id' => $request->property_id,
            'unit_type_id' => $request->unit_type_id,
            'name' => $request->name,
            'description' => $request->description,
            'max_occupancy' => $request->max_occupancy,
            'bedrooms' => $request->bedrooms ?? 0,
            'bathrooms' => $request->bathrooms ?? 1,
            'beds' => $request->beds ?? 1,
            'size_sqm' => $request->size_sqm,
            'total_units' => $request->total_units,
            'available_units' => $request->available_units,
            'base_price_per_night' => $request->base_price_per_night,
            'base_price_per_month' => $request->base_price_per_month,
            'currency' => $request->currency ?? 'USD',
            'price_display_type' => $request->price_display_type ?? 'per_night',
            'status' => $request->status,
            'is_active' => $request->has('is_active'),
        ]);

        // Sync facilities
        if ($request->has('facilities')) {
            $unit->facilities()->sync($request->facilities);
        } else {
            $unit->facilities()->detach();
        }

        // Sync additional/extra charges
        $unit->extraChargesAll()->delete();
        if ($request->has('extra_charges') && is_array($request->extra_charges)) {
            foreach ($request->extra_charges as $typeId => $price) {
                $price = is_numeric($price) ? (float) $price : 0;
                if ($price > 0 && ExtraChargeType::find($typeId)) {
                    UnitExtraCharge::create([
                        'unit_id' => $unit->id,
                        'extra_charge_type_id' => $typeId,
                        'price' => $price,
                        'is_active' => true,
                    ]);
                }
            }
        }

        return redirect()->route('admin.units.index', ['property_id' => $unit->property_id])
            ->with('success', 'Unit updated successfully');
    }

    /**
     * Remove the specified unit.
     */
    public function destroy($id)
    {
        if ($redirect = $this->ensureVerifiedForPropertyManagers()) {
            return $redirect;
        }

        $unit = Unit::findOrFail($id);

        $this->authorizeUnitAccess($unit);

        $propertyId = $unit->property_id;
        $unit->delete();

        return redirect()->route('admin.units.index', ['property_id' => $propertyId])
            ->with('success', 'Unit deleted successfully');
    }
}


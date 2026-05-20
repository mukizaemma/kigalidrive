<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\FacilityCategory;
use App\Models\Amenity;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class FacilityCategoriesController extends Controller
{
    /**
     * List all facility categories.
     */
    public function index()
    {
        $categories = FacilityCategory::withCount('facilities')
            ->orderBy('sort_order')
            ->orderBy('name')
            ->get();
        $setting = \App\Models\Setting::first();

        return view('admin.facility-categories.index', [
            'categories' => $categories,
            'setting' => $setting,
        ]);
    }

    /**
     * Show form to create a new category.
     */
    public function create()
    {
        $setting = \App\Models\Setting::first();
        return view('admin.facility-categories.create', ['setting' => $setting]);
    }

    /**
     * Store a new facility category.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255',
            'icon' => 'nullable|string|max:255',
            'property_type' => 'nullable|in:hotel,apartment',
            'description' => 'nullable|string',
            'sort_order' => 'nullable|integer|min:0',
            'is_active' => 'nullable|boolean',
        ]);

        $data = [
            'name' => $request->name,
            'slug' => $request->slug ?: Str::slug($request->name),
            'icon' => $request->icon,
            'property_type' => $request->property_type,
            'description' => $request->description,
            'sort_order' => (int) ($request->sort_order ?? 0),
            'is_active' => $request->has('is_active'),
        ];

        // Ensure slug is unique
        $baseSlug = $data['slug'];
        $n = 1;
        while (FacilityCategory::where('slug', $data['slug'])->exists()) {
            $data['slug'] = $baseSlug . '-' . $n;
            $n++;
        }

        $category = FacilityCategory::create($data);

        return redirect()->route('admin.facility-categories.edit', $category->id)
            ->with('success', 'Category created. You can now add amenities to it.');
    }

    /**
     * Show form to edit a category and manage its amenities.
     */
    public function edit($id)
    {
        $category = FacilityCategory::with(['facilities' => fn ($q) => $q->orderBy('sort_order')->orderBy('title')])
            ->findOrFail($id);
        $setting = \App\Models\Setting::first();

        return view('admin.facility-categories.edit', [
            'category' => $category,
            'setting' => $setting,
        ]);
    }

    /**
     * Update a facility category.
     */
    public function update(Request $request, $id)
    {
        $category = FacilityCategory::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255',
            'icon' => 'nullable|string|max:255',
            'property_type' => 'nullable|in:hotel,apartment',
            'description' => 'nullable|string',
            'sort_order' => 'nullable|integer|min:0',
            'is_active' => 'nullable|boolean',
        ]);

        $slug = $request->slug ?: Str::slug($request->name);
        if ($slug !== $category->slug) {
            $baseSlug = $slug;
            $n = 1;
            while (FacilityCategory::where('slug', $slug)->where('id', '!=', $id)->exists()) {
                $slug = $baseSlug . '-' . $n;
                $n++;
            }
        } else {
            $slug = $category->slug;
        }

        $category->update([
            'name' => $request->name,
            'slug' => $slug,
            'icon' => $request->icon,
            'property_type' => $request->property_type,
            'description' => $request->description,
            'sort_order' => (int) ($request->sort_order ?? 0),
            'is_active' => $request->has('is_active'),
        ]);

        return redirect()->route('admin.facility-categories.edit', $category->id)
            ->with('success', 'Category updated successfully.');
    }

    /**
     * Delete a facility category. Amenities in this category will have facility_category_id set to null.
     */
    public function destroy($id)
    {
        $category = FacilityCategory::findOrFail($id);
        // Detach amenities (set facility_category_id to null) so they become "uncategorized"
        $category->facilities()->update(['facility_category_id' => null]);
        $category->delete();

        return redirect()->route('admin.facility-categories.index')
            ->with('success', 'Category deleted. Its amenities are now uncategorized.');
    }
}

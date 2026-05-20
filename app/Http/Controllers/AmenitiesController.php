<?php

namespace App\Http\Controllers;

use App\Models\Amenity;
use App\Models\FacilityCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Schema;
use Illuminate\Validation\Rule;

class AmenitiesController extends Controller
{
    /**
     * Display a listing of amenities by category (tabs).
     */
    public function index()
    {
        $categories = FacilityCategory::with([
            'facilities' => fn ($q) => $q->orderBy('sort_order')->orderBy('title')
        ])->orderBy('sort_order')->get();

        $uncategorized = Amenity::whereNull('facility_category_id')
            ->orderBy('sort_order')
            ->orderBy('title')
            ->get();

        $setting = \App\Models\Setting::first();

        return view('admin.amenities.index', [
            'categories' => $categories,
            'uncategorized' => $uncategorized,
            'setting' => $setting,
        ]);
    }

    /**
     * Store a newly created amenity.
     * Prevents duplicate title within the same category.
     */
    public function store(Request $request)
    {
        $request->validate([
            'title' => [
                'required',
                'string',
                'max:255',
                Rule::unique('amenities', 'title')
                    ->where('facility_category_id', $request->facility_category_id)
                    ->whereNull('deleted_at'),
            ],
            'facility_category_id' => 'required|exists:facility_categories,id',
            'icon' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'sort_order' => 'nullable|integer|min:0',
            'is_active' => 'nullable|boolean',
        ], [
            'title.unique' => 'An amenity with this title already exists in this category. Use a different title or add it to another category.',
        ]);

        $data = [
            'title' => $request->title,
            'icon' => $request->icon,
        ];

        // Only add columns if they exist in the database
        if (Schema::hasColumn('amenities', 'facility_category_id')) {
            $data['facility_category_id'] = $request->facility_category_id;
        }
        if (Schema::hasColumn('amenities', 'description')) {
            $data['description'] = $request->description;
        }
        if (Schema::hasColumn('amenities', 'sort_order')) {
            $data['sort_order'] = $request->sort_order ?? 0;
        }
        if (Schema::hasColumn('amenities', 'is_active')) {
            $data['is_active'] = $request->has('is_active') ? true : false;
        }

        // Only handle slug if the column exists
        if (Schema::hasColumn('amenities', 'slug')) {
            $slug = Str::slug($request->title);
            // Ensure slug is unique
            $originalSlug = $slug;
            $counter = 1;
            while (Amenity::where('slug', $slug)->exists()) {
                $slug = $originalSlug . '-' . $counter;
                $counter++;
            }
            $data['slug'] = $slug;
        }

        $amenity = Amenity::create($data);

        // If AJAX request, return JSON response
        if ($request->ajax() || $request->wantsJson() || $request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Amenity has been created successfully',
                'amenity' => [
                    'id' => $amenity->id,
                    'title' => $amenity->title,
                    'icon' => $amenity->icon ?? null,
                    'facility_category_id' => $amenity->facility_category_id,
                ]
            ]);
        }

        // Redirect back to category edit page if that's where the form was submitted from
        $redirectId = $request->input('redirect_category_id');
        if ($redirectId && \App\Models\FacilityCategory::where('id', $redirectId)->exists()) {
            return redirect()->route('admin.facility-categories.edit', $redirectId)
                ->with('success', 'Amenity has been created successfully');
        }

        return redirect()->route('amenities.index')
            ->with('success', 'Amenity has been created successfully');
    }

    /**
     * Show the form for editing the specified amenity.
     */
    public function edit($id)
    {
        $amenity = Amenity::findOrFail($id);
        $categories = FacilityCategory::where('is_active', true)->orderBy('sort_order')->get();
        $setting = \App\Models\Setting::first();
        
        return view('admin.amenities.edit', [
            'amenity' => $amenity,
            'categories' => $categories,
            'setting' => $setting,
        ]);
    }

    /**
     * Update the specified amenity.
     */
    public function update(Request $request, $id)
    {
        $amenity = Amenity::findOrFail($id);

        $request->validate([
            'title' => [
                'required',
                'string',
                'max:255',
                Rule::unique('amenities', 'title')
                    ->where('facility_category_id', $request->facility_category_id)
                    ->whereNull('deleted_at')
                    ->ignore($amenity->id),
            ],
            'facility_category_id' => 'required|exists:facility_categories,id',
            'icon' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'sort_order' => 'nullable|integer|min:0',
            'is_active' => 'nullable|boolean',
        ], [
            'title.unique' => 'An amenity with this title already exists in this category. Use a different title.',
        ]);

        $data = [
            'title' => $request->title,
            'icon' => $request->icon,
        ];

        if (Schema::hasColumn('amenities', 'facility_category_id')) {
            $data['facility_category_id'] = $request->facility_category_id;
        }
        if (Schema::hasColumn('amenities', 'description')) {
            $data['description'] = $request->description;
        }
        if (Schema::hasColumn('amenities', 'sort_order')) {
            $data['sort_order'] = $request->sort_order ?? 0;
        }
        if (Schema::hasColumn('amenities', 'is_active')) {
            $data['is_active'] = $request->has('is_active') ? true : false;
        }

        if (Schema::hasColumn('amenities', 'slug')) {
            $slug = Str::slug($request->title);
            $currentSlug = $amenity->slug ?? null;
            if ($slug !== $currentSlug) {
                $baseSlug = $slug;
                $n = 1;
                while (Amenity::where('slug', $slug)->where('id', '!=', $id)->exists()) {
                    $slug = $baseSlug . '-' . $n;
                    $n++;
                }
            } else {
                $slug = $currentSlug;
            }
            $data['slug'] = $slug;
        }

        $amenity->update($data);

        return redirect()->route('amenities.index')
            ->with('success', 'Amenity has been updated successfully');
    }

    /**
     * Remove the specified amenity.
     */
    public function destroy(Request $request, $id)
    {
        $amenity = Amenity::findOrFail($id);
        $amenity->delete();

        // If AJAX request, return JSON response
        if ($request->ajax() || $request->wantsJson() || $request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Amenity has been deleted successfully'
            ]);
        }

        return redirect()->route('amenities.index')
            ->with('success', 'Amenity has been deleted successfully');
    }
}



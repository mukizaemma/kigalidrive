<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Unit;
use App\Models\UnitImage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class UnitImagesController extends Controller
{
    /**
     * Store a newly created unit image.
     */
    public function store(Request $request, $unitId)
    {
        $unit = Unit::findOrFail($unitId);

        $request->validate([
            'image' => 'nullable|image|max:4096',
            'images.*' => 'nullable|image|max:4096',
            'caption' => 'nullable|string|max:255',
            'is_primary' => 'nullable|boolean',
        ]);

        // Handle single image upload
        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $path = $file->store('public/images/units');
            $fileName = basename($path);

            // If this is set as primary, unset others
            if ($request->has('is_primary')) {
                UnitImage::where('unit_id', $unitId)
                    ->update(['is_primary' => false]);
            }

            $maxSortOrder = UnitImage::where('unit_id', $unitId)->max('sort_order') ?? 0;

            UnitImage::create([
                'unit_id' => $unitId,
                'uploaded_by' => auth()->id(),
                'image_path' => $fileName,
                'caption' => $request->caption,
                'sort_order' => $maxSortOrder + 1,
                'is_primary' => $request->has('is_primary'),
            ]);
        }

        // Handle multiple images upload
        if ($request->hasFile('images')) {
            $maxSortOrder = UnitImage::where('unit_id', $unitId)->max('sort_order') ?? 0;
            $isFirst = true;
            $hasPrimary = UnitImage::where('unit_id', $unitId)->where('is_primary', true)->exists();

            foreach ($request->file('images') as $file) {
                $path = $file->store('public/images/units');
                $fileName = basename($path);

                // Set first image as primary if no primary exists and checkbox is checked
                $isPrimary = $isFirst && !$hasPrimary && $request->has('is_primary');
                if ($isPrimary) {
                    $hasPrimary = true; // Mark that we now have a primary
                }

                $maxSortOrder++;

                UnitImage::create([
                    'unit_id' => $unitId,
                    'uploaded_by' => auth()->id(),
                    'image_path' => $fileName,
                    'caption' => $request->caption,
                    'sort_order' => $maxSortOrder,
                    'is_primary' => $isPrimary,
                ]);

                $isFirst = false;
            }
        }

        return redirect()->back()
            ->with('success', 'Image(s) uploaded successfully');
    }

    /**
     * Remove the specified unit image.
     */
    public function destroy($id)
    {
        $image = UnitImage::findOrFail($id);

        if (Storage::exists('public/images/units/' . $image->image_path)) {
            Storage::delete('public/images/units/' . $image->image_path);
        }

        $image->delete();

        return redirect()->back()
            ->with('success', 'Image deleted successfully');
    }

    /**
     * Update the specified unit image.
     */
    public function update(Request $request, $id)
    {
        $image = UnitImage::findOrFail($id);

        $request->validate([
            'caption' => 'nullable|string|max:255',
        ]);

        $image->update([
            'caption' => $request->caption,
        ]);

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Image updated successfully',
                'image' => $image
            ]);
        }

        return redirect()->back()
            ->with('success', 'Image updated successfully');
    }

    /**
     * Set image as primary.
     */
    public function setPrimary($id)
    {
        $image = UnitImage::findOrFail($id);

        // Unset other primary images
        UnitImage::where('unit_id', $image->unit_id)
            ->update(['is_primary' => false]);

        // Set this as primary
        $image->update(['is_primary' => true]);

        return redirect()->back()
            ->with('success', 'Primary image updated');
    }
}


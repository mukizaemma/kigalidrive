<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Property;
use App\Models\PropertyImage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PropertyImagesController extends Controller
{
    /**
     * Store a newly created property image.
     */
    public function store(Request $request, $propertyId)
    {
        $property = Property::findOrFail($propertyId);

        $request->validate([
            'image' => 'nullable|image|max:4096',
            'images.*' => 'nullable|image|max:4096',
            'caption' => 'nullable|string|max:255',
            'is_primary' => 'nullable|boolean',
        ]);

        // Ensure at least one image is provided
        if (!$request->hasFile('image') && !$request->hasFile('images')) {
            return redirect()->back()
                ->withErrors(['images' => 'Please select at least one image to upload.'])
                ->withInput();
        }

        // Handle single image upload
        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $path = $file->store('public/images/properties');
            $fileName = basename($path);

            // If this is set as primary, unset others
            if ($request->has('is_primary')) {
                PropertyImage::where('property_id', $propertyId)
                    ->update(['is_primary' => false]);
            }

            $maxSortOrder = PropertyImage::where('property_id', $propertyId)->max('sort_order') ?? 0;

            PropertyImage::create([
                'property_id' => $propertyId,
                'uploaded_by' => auth()->id(),
                'image_path' => $fileName,
                'caption' => $request->caption,
                'sort_order' => $maxSortOrder + 1,
                'is_primary' => $request->has('is_primary'),
            ]);
        }

        // Handle multiple images upload
        if ($request->hasFile('images')) {
            $maxSortOrder = PropertyImage::where('property_id', $propertyId)->max('sort_order') ?? 0;
            $isFirst = true;
            $hasPrimary = PropertyImage::where('property_id', $propertyId)->where('is_primary', true)->exists();

            foreach ($request->file('images') as $file) {
                $path = $file->store('public/images/properties');
                $fileName = basename($path);

                // Set first image as primary if no primary exists and checkbox is checked
                $isPrimary = $isFirst && !$hasPrimary && $request->has('is_primary');
                if ($isPrimary) {
                    $hasPrimary = true; // Mark that we now have a primary
                }

                $maxSortOrder++;

                PropertyImage::create([
                    'property_id' => $propertyId,
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
     * Remove the specified property image.
     */
    public function destroy($id)
    {
        $image = PropertyImage::findOrFail($id);
        $propertyId = $image->property_id;

        if (Storage::exists('public/images/properties/' . $image->image_path)) {
            Storage::delete('public/images/properties/' . $image->image_path);
        } elseif (Storage::disk('local')->exists('public/images/properties/' . $image->image_path)) {
            Storage::disk('local')->delete('public/images/properties/' . $image->image_path);
        }

        $image->delete();

        return redirect()->back()
            ->with('success', 'Image deleted successfully');
    }

    /**
     * Update the specified property image.
     */
    public function update(Request $request, $id)
    {
        $image = PropertyImage::findOrFail($id);

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
        $image = PropertyImage::findOrFail($id);

        // Unset other primary images
        PropertyImage::where('property_id', $image->property_id)
            ->update(['is_primary' => false]);

        // Set this as primary
        $image->update(['is_primary' => true]);

        return redirect()->back()
            ->with('success', 'Primary image updated');
    }
}


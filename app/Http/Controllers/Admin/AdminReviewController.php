<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Review;
use App\Models\ReviewImage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AdminReviewController extends Controller
{
    /**
     * Display a listing of reviews.
     */
    public function index(Request $request)
    {
        $query = Review::with(['user', 'images']);

        // Filter by approval status
        if ($request->has('status') && $request->status !== '') {
            if ($request->status == 'approved') {
                $query->where('is_approved', true);
            } elseif ($request->status == 'pending') {
                $query->where('is_approved', false);
            }
        }

        // Filter by featured
        if ($request->has('featured') && $request->featured !== '') {
            $query->where('is_featured', $request->featured == '1');
        }

        // Search
        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('names', 'LIKE', "%$search%")
                  ->orWhere('email', 'LIKE', "%$search%")
                  ->orWhere('testimony', 'LIKE', "%$search%");
            });
        }

        $reviews = $query->latest()->paginate(20);
        $setting = \App\Models\Setting::first();

        return view('admin.reviews.index', [
            'reviews' => $reviews,
            'setting' => $setting,
        ]);
    }

    /**
     * Show the form for creating a new review.
     */
    public function create()
    {
        $setting = \App\Models\Setting::first();
        return view('admin.reviews.create', compact('setting'));
    }

    /**
     * Store a newly created review.
     */
    public function store(Request $request)
    {
        $request->validate([
            'names' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'testimony' => 'required|string|min:10',
            'rating' => 'required|integer|min:1|max:5',
            'website' => 'nullable|string|max:255',
            'is_approved' => 'boolean',
            'is_featured' => 'boolean',
            'images.*' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:5120',
        ]);

        $review = Review::create([
            'user_id' => $request->user_id ?? null,
            'names' => $request->names,
            'email' => $request->email,
            'testimony' => $request->testimony,
            'rating' => $request->rating,
            'website' => $request->website,
            'is_approved' => $request->has('is_approved') ? true : false,
            'is_featured' => $request->has('is_featured') ? true : false,
        ]);

        // Handle image uploads
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $index => $image) {
                $imagePath = $image->store('reviews', 'public');
                
                ReviewImage::create([
                    'review_id' => $review->id,
                    'image' => $imagePath,
                    'sort_order' => $index,
                ]);
            }
        }

        return redirect()->route('admin.reviews.index')
            ->with('success', 'Review created successfully.');
    }

    /**
     * Display the specified review.
     */
    public function show($id)
    {
        $review = Review::with(['user', 'images'])->findOrFail($id);
        $setting = \App\Models\Setting::first();

        return view('admin.reviews.show', [
            'review' => $review,
            'setting' => $setting,
        ]);
    }

    /**
     * Show the form for editing the specified review.
     */
    public function edit($id)
    {
        $review = Review::with(['user', 'images'])->findOrFail($id);
        $setting = \App\Models\Setting::first();

        return view('admin.reviews.edit', [
            'review' => $review,
            'setting' => $setting,
        ]);
    }

    /**
     * Update the specified review.
     */
    public function update(Request $request, $id)
    {
        $review = Review::findOrFail($id);

        $request->validate([
            'names' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'testimony' => 'required|string|min:10',
            'rating' => 'required|integer|min:1|max:5',
            'website' => 'nullable|string|max:255',
            'admin_response' => 'nullable|string',
            'is_approved' => 'boolean',
            'is_featured' => 'boolean',
            'images.*' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:5120',
        ]);

        $review->update([
            'names' => $request->names,
            'email' => $request->email,
            'testimony' => $request->testimony,
            'rating' => $request->rating,
            'website' => $request->website,
            'admin_response' => $request->admin_response,
            'is_approved' => $request->has('is_approved') ? true : false,
            'is_featured' => $request->has('is_featured') ? true : false,
        ]);

        // Handle new image uploads
        if ($request->hasFile('images')) {
            $existingCount = $review->images()->count();
            foreach ($request->file('images') as $index => $image) {
                $imagePath = $image->store('reviews', 'public');
                
                ReviewImage::create([
                    'review_id' => $review->id,
                    'image' => $imagePath,
                    'sort_order' => $existingCount + $index,
                ]);
            }
        }

        return redirect()->route('admin.reviews.index')
            ->with('success', 'Review updated successfully.');
    }

    /**
     * Approve a review.
     */
    public function approve($id)
    {
        $review = Review::findOrFail($id);
        $review->update(['is_approved' => true]);

        return back()->with('success', 'Review approved successfully.');
    }

    /**
     * Reject a review (set is_approved to false).
     */
    public function reject($id)
    {
        $review = Review::findOrFail($id);
        $review->update(['is_approved' => false]);

        return back()->with('success', 'Review rejected. It will no longer appear on public pages.');
    }

    /**
     * Add admin response to a review.
     */
    public function respond(Request $request, $id)
    {
        $request->validate([
            'admin_response' => 'required|string|min:5',
        ]);

        $review = Review::findOrFail($id);
        $review->update(['admin_response' => $request->admin_response]);

        return back()->with('success', 'Response added successfully.');
    }

    /**
     * Delete a review image.
     */
    public function deleteImage($reviewId, $imageId)
    {
        $image = ReviewImage::where('review_id', $reviewId)->findOrFail($imageId);
        
        // Delete file from storage
        if (Storage::disk('public')->exists($image->image)) {
            Storage::disk('public')->delete($image->image);
        }
        
        $image->delete();

        return back()->with('success', 'Image deleted successfully.');
    }

    /**
     * Remove the specified review.
     */
    public function destroy($id)
    {
        $review = Review::with('images')->findOrFail($id);

        // Delete associated images
        foreach ($review->images as $image) {
            if (Storage::disk('public')->exists($image->image)) {
                Storage::disk('public')->delete($image->image);
            }
        }

        $review->delete();

        return redirect()->route('admin.reviews.index')
            ->with('success', 'Review deleted successfully.');
    }
}

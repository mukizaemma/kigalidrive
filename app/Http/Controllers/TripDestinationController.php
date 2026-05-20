<?php

namespace App\Http\Controllers;

use App\Models\Setting;
use App\Models\TripDestination;
use App\Models\TripDestinationImage;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Cache;
use Illuminate\Database\QueryException;

class TripDestinationController extends Controller
{
    public function index()
    {
        $destinations = TripDestination::with('trips', 'images')->latest()->get();
        $setting = Setting::first();
    
        return view('admin.tours.destinations', [
            'destinations' => $destinations,
            'setting' => $setting,
        ]);
    }
    
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'location' => 'nullable|string|max:255',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        $fileName = '';
        if($request->hasFile('image')){
            $file = $request->file('image');
            $path = $file->store('public/images/trip-destinations');
            $fileName = basename($path);
        }
    
        $slug = Str::of($request->input('name'))->slug();

        // Ensure unique slug (include soft-deleted rows so we don't hit DB unique constraint)
        $originalSlug = $slug;
        $counter = 1;
        while (TripDestination::withTrashed()->where('slug', $slug)->exists()) {
            $slug = $originalSlug . '-' . $counter;
            $counter++;
        }

        try {
            $destination = new TripDestination();
            $destination->name = $request->input('name');
            $destination->slug = $slug;
            $destination->description = $request->input('description');
            $destination->location = $request->input('location');
            $destination->image = $fileName;
            $destination->status = $request->input('status', 'Active');
            $destination->added_by = $request->user()->id;
            $destination->save();
        } catch (QueryException $e) {
            if (str_contains($e->getMessage(), 'Duplicate entry')) {
                return redirect()->back()->withInput()->with('error', 'A trip destination with this name already exists. Please use a different name.');
            }
            return redirect()->back()->withInput()->with('error', 'Unable to save the trip destination. Please try again.');
        }

        Cache::forget('shared_trip_destinations');
        return redirect()->route('getTripDestinations')->with('success', 'New Trip Destination has been saved successfully');
    }

    public function edit($id)
    {
        $destination = TripDestination::findOrFail($id);
        $images = $destination->images ?? collect();
        $totalImages = $images->count();
        $setting = Setting::first();

        return view('admin.tours.destinationUpdate', [
            'destination' => $destination,
            'images' => $images,
            'totalImages' => $totalImages,
            'setting' => $setting,
        ]);
    }

    public function update(Request $request, $id)
    {
        try {
            $destination = TripDestination::findOrFail($id);
    
            if ($request->hasFile('image')) {
                $file = $request->file('image');
                $path = $file->store('public/images/trip-destinations');
                if ($destination->image) {
                    Storage::delete('public/images/trip-destinations/' . $destination->image);
                }
                $destination->image = basename($path);
            }
    
            $fields = ['name', 'description', 'location', 'status'];
            foreach ($fields as $field) {
                if ($request->has($field)) {
                    $destination->$field = $request->input($field);
                }
            }
    
            if ($destination->isDirty('name')) {
                $slug = Str::of($destination->name)->slug();
                if (TripDestination::withTrashed()->where('slug', $slug)->where('id', '!=', $destination->id)->exists()) {
                    $slug .= '-' . uniqid();
                }
                $destination->slug = $slug;
            }

            $destination->save();

            Cache::forget('shared_trip_destinations');
            return redirect()->route('getTripDestinations')->with('success', 'Trip Destination has been updated successfully');
        } catch (QueryException $e) {
            if (str_contains($e->getMessage(), 'Duplicate entry')) {
                return redirect()->back()->withInput()->with('error', 'A trip destination with this name already exists. Please use a different name.');
            }
            return redirect()->back()->withInput()->with('error', 'Unable to update the trip destination. Please try again.');
        } catch (\Exception $e) {
            return redirect()->back()->withInput()->with('error', 'Something went wrong. Please try again.');
        }
    }
    
    public function destroy($id)
    {
        $destination = TripDestination::find($id); 
        if (!$destination) {
            return back()->with('error', 'Destination not found');
        }
        if ($destination->image) {
            Storage::delete('public/images/trip-destinations/' . $destination->image);
        }
        $destination->delete();
        Cache::forget('shared_trip_destinations');
        return back()->with('success', 'Trip Destination deleted successfully');
    }

    public function addDestinationImage(Request $request)
    {
        $request->validate([
            'image.*' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'trip_destination_id' => 'required|exists:trip_destinations,id', 
        ]);

        if ($request->hasFile('image')) {
            foreach ($request->file('image') as $image) {
                $dir = 'public/images/trip-destinations';
                $path = $image->store($dir);
                $fileName = str_replace($dir . '/', '', $path);

                TripDestinationImage::create([
                    'image' => $fileName,
                    'caption' => $request->input('caption'),
                    'trip_destination_id' => $request->trip_destination_id, 
                    'added_by' => $request->user()->id
                ]);
            }

            return redirect()->back()->with('success', 'Images uploaded successfully!');
        }

        return redirect()->back()->with('error', 'No images were uploaded.');
    }

    public function deleteDestinationImage($id)
    {
        $image = TripDestinationImage::findOrFail($id);

        $imagePath = 'public/images/trip-destinations/' . $image->image;

        if (Storage::exists($imagePath)) {
            Storage::delete($imagePath);
        }

        $image->delete();

        return redirect()->back()->with('warning', 'Image has been deleted');
    }
}

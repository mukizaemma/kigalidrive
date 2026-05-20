<?php

namespace App\Http\Controllers;

use App\Models\Setting;
use App\Models\Trip;
use App\Models\Category;
use App\Models\TripDestination;
use App\Models\Program;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Models\Tripimage;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Storage;

class TripsController extends Controller
{
    public function index(Request $request)
    {
        $query = Trip::with(['tripDestination', 'images']);
        
        // Filter by destination if provided
        if ($request->has('destination') && $request->destination) {
            $query->where('trip_destination_id', $request->destination);
        }
        
        $trips = $query->latest()->get();

        $categories = Category::oldest()->get();
        $tripDestinations = TripDestination::oldest()->get();
        $programs   = Program::oldest()->get();
        $setting = Setting::first();
    
        return view('admin.tours.trips', [
            'trips' => $trips,
            'setting' => $setting,
            'categories' => $categories,
            'tripDestinations' => $tripDestinations,
            'programs' => $programs,
            'selectedDestination' => $request->destination,
        ]);
    }
    


    public function store(Request $request): RedirectResponse
    {
        $fileName = '';
        if($request->hasFile('image')){
            $file = $request->file('image');
            $path = $file->store('public/images/trips');
            $fileName = basename($path);
        }
    
        $slug = Str::of($request->input('title'))->slug();
    
        $blog = new Trip();
        $blog->title = $request->input('title');
        $blog->description = $request->input('description');
        $blog->itinerary = $request->input('itinerary');
        $blog->expectations = $request->input('expectations');
        $blog->recommendations = $request->input('recommendations');
        $blog->inclusions = $request->input('inclusions');
        $blog->exclusions = $request->input('exclusions');
        $blog->location = $request->input('location');
        $blog->duration = $request->input('duration');
        $blog->languages = $request->input('languages');
        $blog->currency = $request->input('currency');
        $blog->maxPeople = $request->input('maxPeople');
        $blog->minAge = $request->input('minAge');
        $blog->price = $request->input('price');
        $blog->program_id = $request->input('program_id');
        $blog->category_id = $request->input('category_id'); // Keep for backward compatibility
        $blog->trip_destination_id = $request->input('trip_destination_id');
        $blog->status = $request->input('status', 'Active');
        $blog->image = $fileName;
        $blog->slug = $slug;
        $blog->added_by = $request->user()->id;
        $blog->save();
    
        return redirect()->route('getTrips')->with('success', 'New Facility has been saved successfully');
    }

    
    public function edit($id)
    {
        $trip = Trip::find($id);
        $images = $trip->images ?? collect();
        $totalImages = $images->count();
        $tripDestinations = TripDestination::oldest()->get();
        $categories = Category::oldest()->get();
        $programs = Program::oldest()->get();
        $setting = Setting::first();

        return view('admin.tours.tripUpdate', [
            'trip'=>$trip,
            'images'=>$images,
            'totalImages'=>$totalImages,
            'tripDestinations'=>$tripDestinations,
            'categories'=>$categories,
            'programs'=>$programs,
            'setting'=>$setting,
        ]);
    }
    public function view($id)
    {
        $trip = Trip::find($id);
        $program= Trip::all();
        return view('admin.posts.blogView', [
            'service'=>$trip,
            'program'=>$program,
        ]);
    }

    public function update(Request $request, $id)
    {
        try {
            $trip = Trip::findOrFail($id);
    
            if ($request->hasFile('image')) {
                $file = $request->file('image');
                $path = $file->store('public/images/trips');
                Storage::delete('public/images/trips/' . $trip->image);
                $trip->image = basename($path);
            }
    
            $fields = [
                'title', 'description', 'status','itinerary','expectations','currency','maxPeople',
                'recommendations','inclusions','exclusions','location','duration','languages',
                'minAge','price','couplePrice','trip_destination_id','category_id','program_id',
                ];
            foreach ($fields as $field) {
                if ($request->has($field) && $request->input($field) !== $trip->$field) {
                    $trip->$field = $request->input($field);
                }
            }
    
            if ($trip->isDirty('title')) {
                $slug = Str::of($trip->title)->slug();
                if (Trip::where('slug', $slug)->where('id', '!=', $trip->id)->exists()) {
                    $slug .= '-' . uniqid();
                }
                $trip->slug = $slug;
            }
    
            $trip->save();
    
            return redirect()->route('getTrips')->with('success', 'Trip has been updated successfully');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Something went wrong');
        }
    }
    


    public function destroy($id)
    {
        $trip = Trip::find($id); 
        if (!$trip) {
            return back()->with('error', 'Content not found');
        }
        if ($trip->image) {
            Storage::delete('public/images/trips/' . $trip->image);
        }
        $trip->delete($id);
        return back()
            ->with('success', 'Story deleted successfully');
    }

        
    public function addTripImage(Request $request)
        {
            $request->validate([
                'image.*'           => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
                'trip_id' => 'required|exists:trips,id', 
            ]);

            if ($request->hasFile('image')) {
                foreach ($request->file('image') as $image) {
                    $dir = 'public/images/trips';
                    $path = $image->store($dir);
                    $fileName = str_replace($dir . '/', '', $path);

                    Tripimage::create([
                        'image'             => $fileName,
                        'trip_id' => $request->trip_id, 
                        'added_by' => $request->user()->id
                    ]);
                }

                return redirect()->back()->with('success', 'Images uploaded successfully!');
            }

            return redirect()->back()->with('error', 'No images were uploaded.');
        }

    public function deleteTripImage($id){
        $image = Tripimage::findOrFail($id);

        $imagePath = 'public/images/trips/' . $image->filename;

        if (Storage::exists($imagePath)) {
            Storage::delete($imagePath);
        }

        $image->delete();

        return redirect()->back()->with('warning', 'Image has been deleted');
    }

}

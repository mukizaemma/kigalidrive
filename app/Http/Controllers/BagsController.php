<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Setting;
use App\Models\Car;
use Illuminate\Support\Str;
use App\Models\Tripimage;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Storage;

class CarsController extends Controller
{
        public function index()
    {
    
        $cars = Car::latest()->get();
    
        return view('admin.tours.trips', [
            'cars' => $cars,
        ]);
    }
    


    public function store(Request $request): RedirectResponse
    {
        $fileName = '';
        if($request->hasFile('image')){
            $file = $request->file('image');
            $path = $file->store('public/images/cars');
            $fileName = basename($path);
        }
    
        $slug = Str::of($request->input('title'))->slug();
    
        $blog = new Car();
        $blog->name = $request->input('name');
        $blog->model = $request->input('model')->nullable();
        $blog->fuel_type = $request->input('fuel_type')->nullable();
        $blog->seats = $request->input('seats')->nullable();
        $blog->transmission = $request->input('transmission')->nullable();
        $blog->price_per_day = $request->input('price_per_day')->nullable();
        $blog->program_id = $request->input('program_id');
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

        return view('admin.tours.tripUpdate', [
            'trip'=>$trip,
            'images'=>$images,
            'totalImages'=>$totalImages,
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
                'minAge','price','couplePrice',
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
}

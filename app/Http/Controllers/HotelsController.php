<?php

namespace App\Http\Controllers;

use App\Models\Hotel;
use App\Models\Setting;
use App\Models\Category;
use App\Models\Program;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\File\File;

class HotelsController extends Controller
{

public function index(Request $request)
{
    $query = Hotel::query();

    if ($request->has('search') && $request->search != '') {
        $search = $request->search;

        $query->where('name', 'LIKE', "%$search%")
              ->orWhere('phone', 'LIKE', "%$search%")
              ->orWhere('location', 'LIKE', "%$search%")
              ->orWhere('status', 'LIKE', "%$search%");
    }

    $hotels = $query->orderBy('name')->paginate(10);

    $setting = Setting::first();
    $destinations = Category::all();
    $services = Program::all();
    return view('admin.hotels.hotels', [
        'hotels'   => $hotels,
        'services'  => $services,
        'destinations'  => $destinations,
        'search'   => $request->search
    ]);
}



    public function store(Request $request): RedirectResponse
    {
        $fileName = '';
        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $path = $file->store('public/images/hotels');
            $fileName = basename($path);
        }

        $slug = Str::slug($request->name);
        $partnerUid = Str::uuid();

        Hotel::create([
            'partner_uid' => $partnerUid,
            'name' => $request->name,
            'type' => $request->type,
            'stars' => $request->stars,
            'location' => $request->location,
            'email' => $request->email,
            'phone' => $request->phone,
            'city' => $request->city,
            'description' => $request->description,
            'status' => $request->status ?? 'Active',
            'image' => $fileName,
            'website' => $request->website,
            'category_id' => $request->category_id,
            'program_id' => $request->program_id,
            'added_by' => $request->user()->id,
            'slug' => $slug,
        ]);

        return redirect('getHotels')->with('success', 'New Hotel has been saved successfully');
    }


    
    public function edit($id)
    {

        $hotel = Hotel::with('images')->findOrFail($id);

        $images = $hotel->images ?? collect();
        $totalImages = $images->count();
        
        return view('admin.hotels.hotelUpdate', [
            'hotel' => $hotel,
            'images' => $images,
            'totalImages' => $totalImages,
        ]);

    }

public function update(Request $request, $id)
{
    try {
        $hotel = Hotel::findOrFail($id);

        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $path = $file->store('public/images/hotels');
            Storage::delete('public/images/hotels/' . $hotel->image);
            $hotel->image = basename($path);
        }

        $hotel->name = $request->name;
        $hotel->type = $request->type;
        $hotel->stars = $request->stars;
        $hotel->location = $request->location;
        $hotel->phone = $request->phone;
        $hotel->email = $request->email;
        $hotel->city = $request->city;
        $hotel->latitude = $request->latitude;
        $hotel->longitude = $request->longitude;
        $hotel->description = $request->description;
        $hotel->status = $request->status;

        $hotel->save();

        return redirect()->route('getHotels')
            ->with('success', 'Hotel has been updated successfully');
    } catch (\Exception $e) {
        dd($e->getMessage()); // Enable this for debugging during development
        return redirect()->back()->with('error', 'Something went wrong');
    }
}






    public function destroy($id)
    {
        $partner = Hotel::find($id); 
        $partner->delete($id);
        return back()
            ->with('success', 'Hotel deleted successfully');
    }
}

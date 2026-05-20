<?php

namespace App\Http\Controllers;

use App\Models\Amenity;
use App\Models\Setting;
use App\Models\Hotel;
use App\Models\HotelRoom;
use App\Models\HotelRoomImage;
use App\Models\Roomimage;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Storage;

class RoomsController extends Controller
{

    public function index(Request $request)
    {
        $userId = auth()->id();
        $user = auth()->user();
        $isAdmin = $user && $user->role == 1; // Check if user is admin (role = 1)
        
        $search = $request->search;
        $hotels = Hotel::latest()->get();
        
        // If admin, show ALL rooms. Otherwise, only show rooms from user's hotels
        if ($isAdmin) {
            $roomsQuery = HotelRoom::with(['hotel', 'hotel.owner']);
        } else {
            $hotelIds = Hotel::where('added_by', $userId)->pluck('id');
            $roomsQuery = HotelRoom::whereIn('hotel_id', $hotelIds);
        }

        $rooms = $roomsQuery
            ->when($search, function ($q) use ($search) {
                $q->where('room_type', 'LIKE', "%$search%")
                ->orWhere('description', 'LIKE', "%$search%")
                ->orWhere('price_per_night', 'LIKE', "%$search%")
                ->orWhereJsonContains('amenities', $search);
            })
            ->latest()
            ->get();

        $amenities = Amenity::with('category')->active()->orderBy('title')->get();
        $facilityCategories = \App\Models\FacilityCategory::with(['facilities' => function($query) {
            $query->active()->orderBy('title');
        }])->where('is_active', true)->orderBy('sort_order')->get();

        return view('admin.hotels.rooms', [
            'rooms'              => $rooms,
            'hotels'             => $hotels,
            'amenities'          => $amenities,
            'facilityCategories' => $facilityCategories,
            'setting'            => Setting::first(),
            'search'             => $search,
            'isAdmin'            => $isAdmin,
        ]);
    }


    


    public function store(Request $request): RedirectResponse
    {
        $fileName = '';
        if($request->hasFile('image')){
            $file = $request->file('image');
            $path = $file->store('public/images/rooms');
            $fileName = basename($path);
        }
    
        $slug = Str::of($request->input('title'))->slug();
    
        $room = new HotelRoom();
        $room->room_type = $request->input('room_type');
        $room->max_occupancy = $request->input('max_occupancy');
        $room->price_per_night = $request->input('price_per_night');
        $room->price_per_month = $request->input('price_per_month');
        $room->currency = $request->input('currency', 'USD');
        $room->price_display_type = $request->input('price_display_type', 'per_night');
        $room->total_rooms = $request->input('total_rooms');
        $room->available_rooms = $request->input('available_rooms');
        $room->description = $request->input('description');
        $room->hotel_id = $request->input('hotel_id');
        $room->image = $fileName;
        $room->slug = $slug;
        $room->added_by = $request->user()->id;
        $room->save();

        if ($request->has('amenities')) {
        $room->roomAmenities()->sync($request->input('amenities'));
    }
    
        return redirect()->route('getRooms')->with('success', 'New Room has been saved successfully');
    }

    
public function edit($id)
{
    $room = HotelRoom::with(['images', 'hotel', 'hotel.owner', 'roomAmenities'])->findOrFail($id);
    $user = auth()->user();
    $isAdmin = $user && $user->role == 1;
    
    // If not admin, check if user owns the hotel that this room belongs to
    if (!$isAdmin && $room->hotel && $room->hotel->added_by !== $user->id) {
        abort(403, 'You do not have permission to edit this room.');
    }
    
    $hotels = Hotel::latest()->get();

    // Selected amenities from pivot relation
    $selectedAmenities = $room->roomAmenities->pluck('id')->toArray();

    $images = $room->images ?? collect();
    $totalImages = $images->count();

    $amenities = Amenity::with('category')->active()->orderBy('title')->get();
    $facilityCategories = \App\Models\FacilityCategory::with(['facilities' => function($query) {
        $query->active()->orderBy('title');
    }])->where('is_active', true)->orderBy('sort_order')->get();
    
    return view('admin.hotels.roomUpdate', [
        'room'               => $room,
        'amenities'          => $amenities,
        'facilityCategories' => $facilityCategories,
        'selectedAmenities'  => $selectedAmenities,
        'images'             => $images,
        'hotels'             => $hotels,
        'totalImages'        => $totalImages,
        'isAdmin'            => $isAdmin,
    ]);
}


    public function view($id)
    {
        $room = HotelRoom::find($id);
        $program= Hotel::all();
        return view('admin.posts.blogView', [
            'service'=>$room,
            'program'=>$program,
        ]);
    }

public function update(Request $request, $id)
{
    try {
        $room = HotelRoom::with('hotel')->findOrFail($id);
        $user = auth()->user();
        $isAdmin = $user && $user->role == 1;
        
        // If not admin, check if user owns the hotel that this room belongs to
        if (!$isAdmin && $room->hotel && $room->hotel->added_by !== $user->id) {
            abort(403, 'You do not have permission to update this room.');
        }

        // Upload new cover image
        if ($request->hasFile('image')) {
            Storage::delete('public/images/rooms/' . $room->image);

            $path = $request->file('image')->store('public/images/rooms');
            $room->image = basename($path);
        }


        $room->hotel_id = $request->hotel_id;
        $room->room_type = $request->room_type;
        $room->price_per_night = $request->price_per_night;
        $room->price_per_month = $request->price_per_month;
        $room->currency = $request->currency ?? 'USD';
        $room->price_display_type = $request->price_display_type ?? 'per_night';
        $room->max_occupancy = $request->max_occupancy;
        $room->total_rooms = $request->total_rooms;
        $room->available_rooms = $request->available_rooms;
        $room->description = $request->description;

        // Store selected amenities as JSON (for legacy search) 
        $room->amenities = json_encode($request->amenities ?? []);

        // Slug only updates if room_type changed
        if ($room->isDirty('room_type')) {
            $slug = Str::slug($room->room_type);

            if (HotelRoom::where('slug', $slug)->where('id', '!=', $room->id)->exists()) {
                $slug .= '-' . uniqid();
            }

            $room->slug = $slug;
        }

        $room->save();

        // Sync pivot table for amenities relationship
        if ($request->has('amenities')) {
            $room->roomAmenities()->sync($request->amenities);
        } else {
            $room->roomAmenities()->detach();
        }

        return redirect()->route('getRooms')->with('success', 'Room updated successfully');

    } catch (\Exception $e) {
        return redirect()->back()->with('error', 'Something went wrong');
    }
}

    


    public function destroy($id)
    {
        $room = HotelRoom::find($id); 
        if (!$room) {
            return back()->with('error', 'Content not found');
        }
        if ($room->image) {
            Storage::delete('public/images/rooms/' . $room->image);
        }
        $room->delete($id);
        return back()
            ->with('success', 'Story deleted successfully');
    }

    
    public function addRoomImage(Request $request)
    {
        $request->validate([
            'image.*' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'hotel_room_id' => 'required|exists:hotel_rooms,id',
        ]);

        $room = HotelRoom::with('hotel')->findOrFail($request->hotel_room_id);
        $user = auth()->user();
        $isAdmin = $user && $user->role == 1;
        
        // Check if user has permission (admin or owner of the room's hotel)
        if (!$isAdmin && $room->hotel && $room->hotel->added_by !== $user->id) {
            abort(403, 'You do not have permission to add images to this room.');
        }

        if ($request->hasFile('image')) {
            foreach ($request->file('image') as $image) {
                $dir = 'public/images/rooms';
                $path = $image->store($dir);
                $fileName = str_replace($dir . '/', '', $path);

                HotelRoomImage::create([
                    'image' => $fileName,
                    'hotel_room_id' => $request->hotel_room_id, 
                    'added_by' => $request->user()->id
                ]);
            }

            return redirect()->back()->with('success', 'Images uploaded successfully!');
        }

        return redirect()->back()->with('error', 'No images were uploaded.');
    }

    public function deleteRoomImage($id){
        $image = HotelRoomImage::findOrFail($id);
        $user = auth()->user();
        $isAdmin = $user && $user->role == 1;
        
        // Check if user has permission (admin or owner of the room's hotel)
        if (!$isAdmin) {
            $room = $image->room;
            if ($room && $room->hotel && $room->hotel->added_by !== $user->id) {
                abort(403, 'You do not have permission to delete this image.');
            }
        }

        $imagePath = 'public/images/rooms/' . $image->image;

        if (Storage::exists($imagePath)) {
            Storage::delete($imagePath);
        }

        $image->delete();

        return redirect()->back()->with('warning', 'Image has been deleted');
    }

}

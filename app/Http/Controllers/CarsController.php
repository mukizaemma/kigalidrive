<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Setting;
use App\Models\Car;
use App\Models\CarDetail;
use App\Models\CarRental;
use Illuminate\Support\Str;
use App\Models\Tripimage;
use App\Models\Carimage;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use App\Services\ReservationNotificationService;

class CarsController extends Controller
{
        public function index()
    {
        $cars = Car::latest()->get();
        $pendingBookings = CarRental::where('rental_status', 'pending')->count();
        $carDetails = CarDetail::active()->ordered()->get();

        return view('admin.cars.cars', [
            'cars' => $cars,
            'pendingBookings' => $pendingBookings,
            'carDetails' => $carDetails,
        ]);
    }

    /**
     * Display car bookings/requests
     */
    public function carBookings(Request $request)
    {
        $query = CarRental::with(['car', 'user'])
            ->latest();
        
        // Filter by status
        if ($request->has('status') && $request->status && $request->status !== 'all') {
            $query->where('rental_status', $request->status);
        }
        
        // Filter by booking type
        if ($request->has('booking_type') && $request->booking_type && $request->booking_type !== 'all') {
            $query->where('booking_type', $request->booking_type);
        }
        
        $bookings = $query->paginate(20)->appends($request->query());
        $setting = Setting::first();
        
        // Get counts for filter tabs
        $counts = [
            'all' => CarRental::count(),
            'pending' => CarRental::where('rental_status', 'pending')->count(),
            'confirmed' => CarRental::where('rental_status', 'confirmed')->count(),
            'cancelled' => CarRental::where('rental_status', 'cancelled')->count(),
        ];
        
        return view('admin.cars.bookings', [
            'bookings' => $bookings,
            'setting' => $setting,
            'counts' => $counts,
            'filters' => [
                'status' => $request->status ?? 'all',
                'booking_type' => $request->booking_type ?? 'all',
            ],
        ]);
    }
    


    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'model' => 'nullable|string|max:255',
            'fuel_type' => 'nullable|string|max:50',
            'transmission' => 'nullable|string|max:50',
            'seats' => 'nullable|integer|min:1',
            'brand' => 'nullable|string|max:100',
            'advert_type' => 'required|in:rent',
            'cover_image' => 'required|image|mimes:jpeg,png,jpg,gif|max:5120',
            'car_images' => 'nullable|array',
            'car_images.*' => 'image|mimes:jpeg,png,jpg,gif|max:5120',
            'description' => 'nullable|string',
            'detail_ids' => 'nullable|array',
            'detail_ids.*' => 'integer|exists:car_details,id',
        ]);

        try {
            // Handle cover image
            $coverImageName = null;
            if ($request->hasFile('cover_image')) {
                $coverFile = $request->file('cover_image');
                $coverPath = $coverFile->store('public/images/cars');
                $coverImageName = basename($coverPath);
            }

            // Generate slug
            $slug = Str::slug($request->input('name'));
            $uniqueSlug = $slug;
            $counter = 1;
            while (Car::where('slug', $uniqueSlug)->exists()) {
                $uniqueSlug = $slug . '-' . $counter++;
            }

            // Create car (chauffeur-only — no self-drive)
            $car = Car::create([
                'name' => $request->input('name'),
                'slug' => $uniqueSlug,
                'model' => $request->input('model'),
                'fuel_type' => $request->input('fuel_type'),
                'seats' => $request->input('seats'),
                'transmission' => $request->input('transmission'),
                'driver_available' => true,
                'self_drive' => false,
                'price_per_day' => $request->input('price_per_day'),
                'price_per_week' => $request->input('price_per_week'),
                'price_per_month' => $request->input('price_per_month'),
                'price_to_buy' => null,
                'image' => $coverImageName, // Cover image
                'description' => $request->input('description'),
                'brand' => $request->input('brand'),
                'listing_type' => 'rent',
                'added_by' => $request->user()->id,
                'status' => 'available',
            ]);

            $car->details()->sync($request->input('detail_ids', []));

            // Handle additional car images
            if ($request->hasFile('car_images')) {
                foreach ($request->file('car_images') as $imageFile) {
                    $imagePath = $imageFile->store('public/images/cars');
                    $imageName = basename($imagePath);
                    
                    \App\Models\Carimage::create([
                        'car_id' => $car->id,
                        'image' => $imageName,
                        'added_by' => $request->user()->id,
                    ]);
                }
            }

         return redirect()->route('getCars')->with('success', 'New Car has been saved successfully');
        } catch (\Exception $e) {
            \Log::error('Car creation error: ' . $e->getMessage());
            return redirect()->route('getCars')->with('error', 'Something went wrong: ' . $e->getMessage());
       }
    }

    
    public function edit($id)
    {
        $car = Car::with('details')->findOrFail($id);
        $carImages = Carimage::where('car_id', $car->id)->get();
        $carDetails = CarDetail::active()->ordered()->get();

        return view('admin.cars.carUpdate', [
            'car' => $car,
            'carImages' => $carImages,
            'carDetails' => $carDetails,
            'advertType' => 'rent',
        ]);
    }
    public function view($id)
    {
        $car = Trip::find($id);
        $program= Trip::all();
        return view('admin.posts.blogView', [
            'service'=>$car,
            'program'=>$program,
        ]);
    }

    public function update(Request $request, $id)
    {
        try {
            $car = Car::findOrFail($id);
    
            $request->validate([
                'name' => 'required|string|max:255',
                'model' => 'nullable|string|max:255',
                'fuel_type' => 'nullable|string|max:50',
                'transmission' => 'nullable|string|max:50',
                'seats' => 'nullable|integer|min:1',
                'brand' => 'nullable|string|max:100',
                'advert_type' => 'required|in:rent',
                'cover_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:5120',
                'car_images' => 'nullable|array',
                'car_images.*' => 'image|mimes:jpeg,png,jpg,gif|max:5120',
                'description' => 'nullable|string',
                'status' => 'required|in:available,rented,maintenance',
                'detail_ids' => 'nullable|array',
                'detail_ids.*' => 'integer|exists:car_details,id',
            ]);
    
            // Handle cover image update
            if ($request->hasFile('cover_image')) {
                // Delete old cover image if exists
                if ($car->image) {
                Storage::delete('public/images/cars/' . $car->image);
                }
                
                $coverFile = $request->file('cover_image');
                $coverPath = $coverFile->store('public/images/cars');
                $car->image = basename($coverPath);
            }
    
            // Update car details
            $car->name = $request->input('name');
            $car->model = $request->input('model');
            $car->fuel_type = $request->input('fuel_type');
            $car->seats = $request->input('seats');
            $car->transmission = $request->input('transmission');
            $car->description = $request->input('description');
            $car->brand = $request->input('brand');
            $car->listing_type = $request->input('advert_type') === 'sell' ? 'sale' : 'rent';
            $car->status = $request->input('status');
            
            $car->price_per_day = $request->input('price_per_day');
            $car->price_per_week = $request->input('price_per_week');
            $car->price_per_month = $request->input('price_per_month');
            $car->price_to_buy = null;
            $car->driver_available = true;
            $car->self_drive = false;
    
            // Update slug if name changed
            if ($car->isDirty('name')) {
                $slug = Str::slug($car->name);
                $uniqueSlug = $slug;
                $counter = 1;
                while (Car::where('slug', $uniqueSlug)->where('id', '!=', $car->id)->exists()) {
                    $uniqueSlug = $slug . '-' . $counter++;
                }
                $car->slug = $uniqueSlug;
            }
    
            $car->save();

            $car->details()->sync($request->input('detail_ids', []));
    
            // Handle additional car images
            if ($request->hasFile('car_images')) {
                foreach ($request->file('car_images') as $imageFile) {
                    $imagePath = $imageFile->store('public/images/cars');
                    $imageName = basename($imagePath);
                    
                    Carimage::create([
                        'car_id' => $car->id,
                        'image' => $imageName,
                        'added_by' => $request->user()->id,
                    ]);
                }
            }
    
            return redirect()->route('getCars')->with('success', 'Car has been updated successfully');
        } catch (\Exception $e) {
            Log::error('Car update error: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Something went wrong: ' . $e->getMessage());
        }
    }
    


    public function destroy($id)
    {
        try {
            $car = Car::findOrFail($id);

            // Delete cover image from disk (ignore missing files)
            if ($car->image) {
                Storage::delete('public/images/cars/' . $car->image);
            }

            // Delete gallery images via relation (not the legacy JSON attribute)
            $carImages = $car->images()->get();
            foreach ($carImages as $carImage) {
                if ($carImage->image) {
                    Storage::delete('public/images/cars/' . $carImage->image);
                }
                $carImage->delete();
            }

            $car->delete();

            return redirect()->route('getCars')->with('success', 'Car deleted successfully');
        } catch (\Exception $e) {
            Log::error('Car delete error: ' . $e->getMessage());
            return redirect()->route('getCars')->with('error', 'Something went wrong while deleting the car');
        }
    }

    /**
     * Add images to a car
     */
    public function addCarImage(Request $request, $id)
    {
        try {
            $car = Car::findOrFail($id);
            
            $request->validate([
                'images' => 'required|array',
                'images.*' => 'image|mimes:jpeg,png,jpg,gif|max:5120',
            ]);

            if ($request->hasFile('images')) {
                foreach ($request->file('images') as $imageFile) {
                    $imagePath = $imageFile->store('public/images/cars');
                    $imageName = basename($imagePath);
                    
                    Carimage::create([
                        'car_id' => $car->id,
                        'image' => $imageName,
                        'added_by' => $request->user()->id,
                    ]);
                }
            }

            return back()->with('success', 'Images added successfully');
        } catch (\Exception $e) {
            Log::error('Add car image error: ' . $e->getMessage());
            return back()->with('error', 'Something went wrong while adding images');
        }
    }

    /**
     * Delete a car image
     */
    public function deleteCarImage($id)
    {
        try {
            $carImage = Carimage::findOrFail($id);
            
            if ($carImage->image) {
                Storage::delete('public/images/cars/' . $carImage->image);
            }
            
            $carImage->delete();
            
            return back()->with('success', 'Image deleted successfully');
        } catch (\Exception $e) {
            Log::error('Delete car image error: ' . $e->getMessage());
            return back()->with('error', 'Something went wrong while deleting the image');
        }
    }

    /**
     * Update car booking status and notify the client by email.
     */
    public function updateBookingStatus(Request $request, $id)
    {
        try {
            $booking = CarRental::with('car')->findOrFail($id);

            $request->validate([
                'status' => 'required|in:pending,confirmed,cancelled',
                'notify_client' => 'nullable|boolean',
                'admin_message' => 'nullable|string|max:2000',
            ]);

            $booking->rental_status = $request->status;
            $booking->save();

            $shouldNotify = $request->boolean('notify_client', true);
            if ($shouldNotify && $booking->email) {
                try {
                    app(ReservationNotificationService::class)->notifyClientUpdate(
                        $booking,
                        $request->input('admin_message')
                    );
                } catch (\Throwable $e) {
                    Log::error('Car booking client notify error: ' . $e->getMessage());
                    return back()->with('success', 'Booking status updated, but the client email could not be sent. You can resend it from Actions.');
                }
            }

            return back()->with('success', 'Booking status updated' . ($shouldNotify ? ' and the client was notified by email.' : ' successfully.'));
        } catch (\Exception $e) {
            Log::error('Update booking status error: ' . $e->getMessage());
            return back()->with('error', 'Something went wrong while updating the booking');
        }
    }

    /**
     * Resend the “booking received” confirmation email to the client.
     */
    public function resendBookingEmail($id)
    {
        try {
            $booking = CarRental::with('car')->findOrFail($id);

            if (! $booking->email) {
                return back()->with('error', 'This booking has no client email address.');
            }

            app(ReservationNotificationService::class)->resendClientReceived($booking);

            return back()->with('success', 'Confirmation email resent to ' . $booking->email . '.');
        } catch (\Throwable $e) {
            Log::error('Resend car booking email error: ' . $e->getMessage());
            return back()->with('error', 'Could not resend the confirmation email. Check mail settings and try again.');
        }
    }

    /**
     * Send a custom status/update email to the client about this booking.
     */
    public function sendBookingUpdate(Request $request, $id)
    {
        try {
            $booking = CarRental::with('car')->findOrFail($id);

            $validated = $request->validate([
                'admin_message' => 'required|string|max:2000',
                'status' => 'nullable|in:pending,confirmed,cancelled',
            ]);

            if (! empty($validated['status']) && $validated['status'] !== $booking->rental_status) {
                $booking->rental_status = $validated['status'];
                $booking->save();
            }

            if (! $booking->email) {
                return back()->with('error', 'This booking has no client email address.');
            }

            app(ReservationNotificationService::class)->notifyClientUpdate(
                $booking->fresh(['car']),
                $validated['admin_message'],
                'Update on booking #' . $booking->booking_number . ' — Kigali Drive Rentals'
            );

            return back()->with('success', 'Update email sent to ' . $booking->email . '.');
        } catch (\Throwable $e) {
            Log::error('Send car booking update error: ' . $e->getMessage());
            return back()->with('error', 'Could not send the update email. Check mail settings and try again.');
        }
    }
}

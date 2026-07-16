<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class RedirectAdminToDashboard
{
    /**
     * Handle an incoming request.
     * Redirect admins to dashboard if they try to access frontend pages
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Only check if user is authenticated
        if (Auth::check()) {
            $user = Auth::user();
            
            // Keep primary / legacy role-1 admins inside the panel on frontend hits
            if ($user->isSuperAdmin() || (int) ($user->role ?? 0) === 1) {
                // Check if current path is an admin path
                $isAdminPath = $request->is('dashboard*')
                            || $request->is('my-properties*')
                            || $request->is('inventory-day-cap')
                            || $request->is('inventory-day-cap/*')
                            || $request->is('Users*')
                            || $request->is('deleteUser*')
                            || $request->is('email/verify*')
                            || $request->is('logouts')
                            || $request->is('my-profile*')
                            || $request->is('admin/*')
                            || $request->is('Comments*')
                            || $request->is('Subscribers*')
                            || $request->is('getMessages*')
                            || $request->is('deleteMessages*')
                            || $request->is('setting*')
                            || $request->is('getLeftBags*')
                            || $request->is('updateBags*')
                            || $request->is('getTicketing*')
                            || $request->is('getFacilities*')
                            || $request->is('storeFacility*')
                            || $request->is('editFacility*')
                            || $request->is('updateFacility*')
                            || $request->is('deleteFacility*')
                            || $request->is('addFacilityImage*')
                            || $request->is('deleteFacilityImage*')
                            || $request->is('getTrips*')
                            || $request->is('getImages*')
                            || $request->is('saveGallery*')
                            || $request->is('editGallery*')
                            || $request->is('updateGallery*')
                            || $request->is('getPartners*')
                            || $request->is('savePartner*')
                            || $request->is('editPartner*')
                            || $request->is('updatePartner*')
                            || $request->is('destroyPartner*')
                            || $request->is('saveSlide*')
                            || $request->is('editSlide*')
                            || $request->is('updateSlide*')
                            || $request->is('destroySlide*')
                            || $request->is('images*')
                            || $request->is('saveImage*')
                            || $request->is('editImage*')
                            || $request->is('updateImage*')
                            || $request->is('destroyImage*')
                            || $request->is('getCars*')
                            || $request->is('storeCar*')
                            || $request->is('editCar*')
                            || $request->is('updateCar*')
                            || $request->is('deleteCar*');
                
                // If accessing frontend pages, redirect to dashboard
                if (!$isAdminPath) {
                    return redirect()->route('dashboard');
                }
            }
        }

        return $next($request);
    }
}

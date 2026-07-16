<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Admin (Kigali Drive Rentals)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth'])->group(function () {
    Route::get('/my-profile', [App\Http\Controllers\ProfileController::class, 'show'])->name('my.profile');
    Route::put('/my-profile', [App\Http\Controllers\ProfileController::class, 'update'])->name('my.profile.update');
    Route::put('/my-profile/password', [App\Http\Controllers\ProfileController::class, 'updatePassword'])->name('my.profile.password');

    Route::get('/inventory-day-cap', [App\Http\Controllers\InventoryDayCapController::class, 'show'])->name('inventory-day-cap.show');
    Route::post('/inventory-day-cap', [App\Http\Controllers\InventoryDayCapController::class, 'update'])->name('inventory-day-cap.update');

    // Available to any authenticated user (including the primary super admin)
    Route::get('/logouts', [App\Http\Controllers\AdminController::class, 'logouts'])->name('logouts');
});

Route::middleware(['auth', 'admin'])->group(function () {
    Route::get('/dashboard', [App\Http\Controllers\AdminController::class, 'index'])->name('dashboard');

    Route::middleware('superadmin')->group(function () {
        Route::get('/Users', [App\Http\Controllers\AdminController::class, 'users'])->name('users');
        Route::post('/Users', [App\Http\Controllers\AdminController::class, 'storeUser'])->name('admin.users.store');
        Route::get('/Users/{id}/show', [App\Http\Controllers\AdminController::class, 'showUser'])->name('admin.users.show');
        Route::post('/Users/{id}/update', [App\Http\Controllers\AdminController::class, 'updateUser'])->name('admin.users.update');
        Route::post('/Users/{id}/password', [App\Http\Controllers\AdminController::class, 'setUserPassword'])->name('admin.users.set-password');
        Route::post('/Users/{id}/password-reset', [App\Http\Controllers\AdminController::class, 'sendUserPasswordReset'])->name('admin.users.password-reset');
        Route::get('/Users/{id}/verify', [App\Http\Controllers\AdminController::class, 'verifyUserEmail'])->name('admin.users.verify');
        Route::get('/Users/{id}/makeAdmin', [App\Http\Controllers\AdminController::class, 'makeAdmin'])->name('makeAdmin');
        Route::get('/Users/{id}/removeAdmin', [App\Http\Controllers\AdminController::class, 'removeAdmin'])->name('removeAdmin');
        Route::post('/Users/bulk-delete', [App\Http\Controllers\AdminController::class, 'bulkDeleteUsers'])->name('admin.users.bulk-delete');
        Route::get('/deleteUser/{id}', [App\Http\Controllers\AdminController::class, 'deleteUser'])->name('deleteUser');
    });

    Route::get('/Comments', [App\Http\Controllers\AdminController::class, 'blogsComment'])->name('blogsComment');
    Route::post('/Comment/approve/{comment}', [App\Http\Controllers\AdminController::class, 'commentApprove'])->name('commentApprove');
    Route::post('/Comment/reject/{comment}', [App\Http\Controllers\AdminController::class, 'commentReject'])->name('commentReject');
    Route::get('/CommentDelete/{id}', [App\Http\Controllers\AdminController::class, 'destroyBlogComment'])->name('destroyBlogComment');

    Route::get('/admin/faqs', [App\Http\Controllers\Admin\AdminFaqController::class, 'index'])->name('admin.faqs.index');
    Route::post('/admin/faqs', [App\Http\Controllers\Admin\AdminFaqController::class, 'store'])->name('admin.faqs.store');
    Route::put('/admin/faqs/{faq}', [App\Http\Controllers\Admin\AdminFaqController::class, 'update'])->name('admin.faqs.update');
    Route::delete('/admin/faqs/{faq}', [App\Http\Controllers\Admin\AdminFaqController::class, 'destroy'])->name('admin.faqs.destroy');

    Route::get('/admin/car-details', [App\Http\Controllers\Admin\AdminCarDetailController::class, 'index'])->name('admin.car-details.index');
    Route::post('/admin/car-details', [App\Http\Controllers\Admin\AdminCarDetailController::class, 'store'])->name('admin.car-details.store');
    Route::put('/admin/car-details/{carDetail}', [App\Http\Controllers\Admin\AdminCarDetailController::class, 'update'])->name('admin.car-details.update');
    Route::delete('/admin/car-details/{carDetail}', [App\Http\Controllers\Admin\AdminCarDetailController::class, 'destroy'])->name('admin.car-details.destroy');

    Route::get('/admin/home-hire', [App\Http\Controllers\Admin\AdminHomeHireController::class, 'index'])->name('admin.home-hire.index');
    Route::put('/admin/home-hire/intro', [App\Http\Controllers\Admin\AdminHomeHireController::class, 'updateIntro'])->name('admin.home-hire.intro.update');
    Route::post('/admin/home-hire/scenarios', [App\Http\Controllers\Admin\AdminHomeHireController::class, 'storeScenario'])->name('admin.home-hire.scenarios.store');
    Route::put('/admin/home-hire/scenarios/{scenario}', [App\Http\Controllers\Admin\AdminHomeHireController::class, 'updateScenario'])->name('admin.home-hire.scenarios.update');
    Route::delete('/admin/home-hire/scenarios/{scenario}', [App\Http\Controllers\Admin\AdminHomeHireController::class, 'destroyScenario'])->name('admin.home-hire.scenarios.destroy');

    Route::get('/admin/reviews', [App\Http\Controllers\Admin\AdminReviewController::class, 'index'])->name('admin.reviews.index');
    Route::get('/admin/reviews/create', [App\Http\Controllers\Admin\AdminReviewController::class, 'create'])->name('admin.reviews.create');
    Route::post('/admin/reviews', [App\Http\Controllers\Admin\AdminReviewController::class, 'store'])->name('admin.reviews.store');
    Route::get('/admin/reviews/{id}', [App\Http\Controllers\Admin\AdminReviewController::class, 'show'])->name('admin.reviews.show');
    Route::get('/admin/reviews/{id}/edit', [App\Http\Controllers\Admin\AdminReviewController::class, 'edit'])->name('admin.reviews.edit');
    Route::put('/admin/reviews/{id}', [App\Http\Controllers\Admin\AdminReviewController::class, 'update'])->name('admin.reviews.update');
    Route::post('/admin/reviews/{id}/approve', [App\Http\Controllers\Admin\AdminReviewController::class, 'approve'])->name('admin.reviews.approve');
    Route::post('/admin/reviews/{id}/reject', [App\Http\Controllers\Admin\AdminReviewController::class, 'reject'])->name('admin.reviews.reject');
    Route::post('/admin/reviews/{id}/respond', [App\Http\Controllers\Admin\AdminReviewController::class, 'respond'])->name('admin.reviews.respond');
    Route::delete('/admin/reviews/{id}', [App\Http\Controllers\Admin\AdminReviewController::class, 'destroy'])->name('admin.reviews.destroy');
    Route::delete('/admin/reviews/{reviewId}/images/{imageId}', [App\Http\Controllers\Admin\AdminReviewController::class, 'deleteImage'])->name('admin.reviews.deleteImage');

    Route::get('/setting', [App\Http\Controllers\SettingsController::class, 'setting'])->name('setting');
    Route::post('/saveSetting', [App\Http\Controllers\SettingsController::class, 'saveSetting'])->name('saveSetting');
    Route::get('/terms', [App\Http\Controllers\SettingsController::class, 'getTerms'])->name('getTerms');
    Route::post('/terms/{id}', [App\Http\Controllers\SettingsController::class, 'saveTerms'])->name('saveTerms');
    Route::get('/homePage', [App\Http\Controllers\SettingsController::class, 'homePage'])->name('homePage');
    Route::post('/saveHome', [App\Http\Controllers\SettingsController::class, 'saveHome'])->name('saveHome');
    Route::get('/aboutPage', [App\Http\Controllers\SettingsController::class, 'aboutPage'])->name('aboutPage');
    Route::post('/saveAbout', [App\Http\Controllers\SettingsController::class, 'saveAbout'])->name('saveAbout');
    Route::post('/saveSiteImages', [App\Http\Controllers\SettingsController::class, 'saveSiteImages'])->name('saveSiteImages');

    Route::get('/getBlogs', [App\Http\Controllers\BlogsController::class, 'index'])->name('getBlogs');
    Route::post('/saveBlog', [App\Http\Controllers\BlogsController::class, 'store'])->name('saveBlog');
    Route::get('/blog/{id}', [App\Http\Controllers\BlogsController::class, 'edit'])->name('editBlog');
    Route::get('/blogView/{id}', [App\Http\Controllers\BlogsController::class, 'view'])->name('viewBlog');
    Route::post('/updateBlog/{id}', [App\Http\Controllers\BlogsController::class, 'update'])->name('updateBlog');
    Route::get('/deleteBlog/{id}', [App\Http\Controllers\BlogsController::class, 'destroy'])->name('deleteBlog');
    Route::get('/Blog/{blog}/publish', [App\Http\Controllers\BlogsController::class, 'publish'])->name('publishBlog');

    Route::get('/slides', [App\Http\Controllers\SlidesController::class, 'index'])->name('slides');
    Route::post('/saveSlide', [App\Http\Controllers\SlidesController::class, 'store'])->name('saveSlide');
    Route::get('/editSlide/{id}', [App\Http\Controllers\SlidesController::class, 'edit'])->name('editSlide');
    Route::post('/updateSlide/{id}', [App\Http\Controllers\SlidesController::class, 'update'])->name('updateSlide');
    Route::get('/destroySlide/{id}', [App\Http\Controllers\SlidesController::class, 'destroy'])->name('destroySlide');

    Route::get('/amenities', [App\Http\Controllers\AmenitiesController::class, 'index'])->name('amenities.index');
    Route::post('/amenities', [App\Http\Controllers\AmenitiesController::class, 'store'])->name('amenities.store');
    Route::get('/amenities/{id}/edit', [App\Http\Controllers\AmenitiesController::class, 'edit'])->name('amenities.edit');
    Route::post('/amenities/{id}', [App\Http\Controllers\AmenitiesController::class, 'update'])->name('amenities.update');
    Route::get('/amenities/{id}/delete', [App\Http\Controllers\AmenitiesController::class, 'destroy'])->name('amenities.destroy');

    Route::get('/admin/facility-categories', [App\Http\Controllers\Admin\FacilityCategoriesController::class, 'index'])->name('admin.facility-categories.index');
    Route::get('/admin/facility-categories/create', [App\Http\Controllers\Admin\FacilityCategoriesController::class, 'create'])->name('admin.facility-categories.create');
    Route::post('/admin/facility-categories', [App\Http\Controllers\Admin\FacilityCategoriesController::class, 'store'])->name('admin.facility-categories.store');
    Route::get('/admin/facility-categories/{id}/edit', [App\Http\Controllers\Admin\FacilityCategoriesController::class, 'edit'])->name('admin.facility-categories.edit');
    Route::post('/admin/facility-categories/{id}', [App\Http\Controllers\Admin\FacilityCategoriesController::class, 'update'])->name('admin.facility-categories.update');
    Route::get('/admin/facility-categories/{id}/delete', [App\Http\Controllers\Admin\FacilityCategoriesController::class, 'destroy'])->name('admin.facility-categories.destroy');

    Route::get('/admin/properties', [App\Http\Controllers\Admin\AdminPropertiesController::class, 'index'])->name('admin.properties.index');
    Route::get('/admin/properties/create', [App\Http\Controllers\Admin\AdminPropertiesController::class, 'create'])->name('admin.properties.create');
    Route::post('/admin/properties', [App\Http\Controllers\Admin\AdminPropertiesController::class, 'store'])->name('admin.properties.store');
    Route::get('/admin/properties/{id}/edit', [App\Http\Controllers\Admin\AdminPropertiesController::class, 'edit'])->name('admin.properties.edit');
    Route::get('/admin/properties/{id}/delete', [App\Http\Controllers\Admin\AdminPropertiesController::class, 'destroy'])->name('admin.properties.destroy');
    Route::get('/admin/properties/{id}/status/{status}', [App\Http\Controllers\Admin\AdminPropertiesController::class, 'updateStatus'])->name('admin.properties.updateStatus.get');
    Route::post('/admin/properties/{id}/status', [App\Http\Controllers\Admin\AdminPropertiesController::class, 'updateStatus'])->name('admin.properties.updateStatus');
    Route::put('/admin/properties/{id}', [App\Http\Controllers\Admin\AdminPropertiesController::class, 'update'])->name('admin.properties.update');
    Route::post('/admin/properties/{id}', [App\Http\Controllers\Admin\AdminPropertiesController::class, 'update'])->name('admin.properties.update.post');
    Route::get('/admin/properties/{id}', [App\Http\Controllers\Admin\AdminPropertiesController::class, 'show'])->name('admin.properties.show');

    Route::post('/admin/properties/{propertyId}/images', [App\Http\Controllers\Admin\PropertyImagesController::class, 'store'])->name('admin.properties.images.store');
    Route::put('/admin/properties/images/{id}', [App\Http\Controllers\Admin\PropertyImagesController::class, 'update'])->name('admin.properties.images.update');
    Route::get('/admin/properties/images/{id}/delete', [App\Http\Controllers\Admin\PropertyImagesController::class, 'destroy'])->name('admin.properties.images.destroy');
    Route::get('/admin/properties/images/{id}/primary', [App\Http\Controllers\Admin\PropertyImagesController::class, 'setPrimary'])->name('admin.properties.images.primary');

    Route::get('/admin/units', [App\Http\Controllers\Admin\AdminUnitsController::class, 'index'])->name('admin.units.index');
    Route::get('/admin/units/create', [App\Http\Controllers\Admin\AdminUnitsController::class, 'create'])->name('admin.units.create');
    Route::post('/admin/units', [App\Http\Controllers\Admin\AdminUnitsController::class, 'store'])->name('admin.units.store');
    Route::get('/admin/units/{id}/edit', [App\Http\Controllers\Admin\AdminUnitsController::class, 'edit'])->name('admin.units.edit');
    Route::get('/admin/units/{id}/delete', [App\Http\Controllers\Admin\AdminUnitsController::class, 'destroy'])->name('admin.units.destroy');
    Route::post('/admin/units/{id}', [App\Http\Controllers\Admin\AdminUnitsController::class, 'update'])->name('admin.units.update');

    Route::post('/admin/units/{unitId}/images', [App\Http\Controllers\Admin\UnitImagesController::class, 'store'])->name('admin.units.images.store');
    Route::put('/admin/units/images/{id}', [App\Http\Controllers\Admin\UnitImagesController::class, 'update'])->name('admin.units.images.update');
    Route::get('/admin/units/images/{id}/delete', [App\Http\Controllers\Admin\UnitImagesController::class, 'destroy'])->name('admin.units.images.destroy');
    Route::get('/admin/units/images/{id}/primary', [App\Http\Controllers\Admin\UnitImagesController::class, 'setPrimary'])->name('admin.units.images.primary');

    Route::post('/admin/units/{unitId}/pricing', [App\Http\Controllers\Admin\UnitPricingController::class, 'store'])->name('admin.units.pricing.store');
    Route::post('/admin/units/pricing/{id}', [App\Http\Controllers\Admin\UnitPricingController::class, 'update'])->name('admin.units.pricing.update');
    Route::get('/admin/units/pricing/{id}/delete', [App\Http\Controllers\Admin\UnitPricingController::class, 'destroy'])->name('admin.units.pricing.destroy');

    Route::post('/admin/units/{unitId}/availability', [App\Http\Controllers\Admin\UnitAvailabilityController::class, 'store'])->name('admin.units.availability.store');
    Route::post('/admin/units/{unitId}/availability/bulk', [App\Http\Controllers\Admin\UnitAvailabilityController::class, 'bulkUpdate'])->name('admin.units.availability.bulk');
    Route::get('/admin/units/availability/{id}/delete', [App\Http\Controllers\Admin\UnitAvailabilityController::class, 'destroy'])->name('admin.units.availability.destroy');

    Route::get('/getCars', [App\Http\Controllers\CarsController::class, 'index'])->name('getCars');
    Route::post('/storeCar', [App\Http\Controllers\CarsController::class, 'store'])->name('storeCar');
    Route::get('/editCar/{id}', [App\Http\Controllers\CarsController::class, 'edit'])->name('editCar');
    Route::post('/updateCar/{id}', [App\Http\Controllers\CarsController::class, 'update'])->name('updateCar');
    Route::get('/deleteCar/{id}', [App\Http\Controllers\CarsController::class, 'destroy'])->name('deleteCar');
    Route::post('/addCarImage/{id}', [App\Http\Controllers\CarsController::class, 'addCarImage'])->name('addCarImage');
    Route::get('/deleteCarImage/{id}', [App\Http\Controllers\CarsController::class, 'deleteCarImage'])->name('deleteCarImage');
    Route::get('/car-bookings', [App\Http\Controllers\CarsController::class, 'carBookings'])->name('admin.carBookings.index');
    Route::put('/car-bookings/{id}/status', [App\Http\Controllers\CarsController::class, 'updateBookingStatus'])->name('admin.carBookings.updateStatus');
    Route::post('/car-bookings/{id}/resend-email', [App\Http\Controllers\CarsController::class, 'resendBookingEmail'])->name('admin.carBookings.resendEmail');
    Route::post('/car-bookings/{id}/send-update', [App\Http\Controllers\CarsController::class, 'sendBookingUpdate'])->name('admin.carBookings.sendUpdate');

    Route::get('/admin/listing-requests', [App\Http\Controllers\Admin\ListingRequestsController::class, 'index'])->name('admin.listing-requests.index');
    Route::put('/admin/listing-requests/{listingRequest}', [App\Http\Controllers\Admin\ListingRequestsController::class, 'update'])->name('admin.listing-requests.update');

    Route::get('/admin/rental-agreement', [App\Http\Controllers\Admin\RentalAgreementController::class, 'edit'])->name('admin.rental-agreement.edit');
    Route::put('/admin/rental-agreement', [App\Http\Controllers\Admin\RentalAgreementController::class, 'update'])->name('admin.rental-agreement.update');
    Route::get('/admin/rental-agreement/preview', [App\Http\Controllers\Admin\RentalAgreementController::class, 'preview'])->name('admin.rental-agreement.preview');

    Route::get('/admin/services', [App\Http\Controllers\Admin\AdminServicesController::class, 'index'])->name('admin.services.index');
    Route::get('/admin/services/create', [App\Http\Controllers\Admin\AdminServicesController::class, 'create'])->name('admin.services.create');
    Route::post('/admin/services', [App\Http\Controllers\Admin\AdminServicesController::class, 'store'])->name('admin.services.store');
    Route::get('/admin/services/{service}/edit', [App\Http\Controllers\Admin\AdminServicesController::class, 'edit'])->name('admin.services.edit');
    Route::put('/admin/services/{service}', [App\Http\Controllers\Admin\AdminServicesController::class, 'update'])->name('admin.services.update');
    Route::delete('/admin/services/{service}', [App\Http\Controllers\Admin\AdminServicesController::class, 'destroy'])->name('admin.services.destroy');

    Route::get('/admin/booking-calendar', [App\Http\Controllers\Admin\AdminBookingCalendarController::class, 'index'])->name('admin.booking-calendar.index');

    Route::get('/admin/enquiries', [App\Http\Controllers\Admin\AdminEnquiriesController::class, 'index'])->name('admin.enquiries.index');
    Route::get('/admin/enquiries/{id}/delete', [App\Http\Controllers\Admin\AdminEnquiriesController::class, 'destroy'])->name('admin.enquiries.destroy');

    Route::post('/admin/bookings/{bookingId}/stay-modification/{modificationId}/approve', [App\Http\Controllers\Admin\AdminBookingsController::class, 'approveStayModification'])->name('admin.bookings.stay-modification.approve');
    Route::post('/admin/bookings/{bookingId}/stay-modification/{modificationId}/reject', [App\Http\Controllers\Admin\AdminBookingsController::class, 'rejectStayModification'])->name('admin.bookings.stay-modification.reject');
});

Route::middleware(['auth', 'allow.booking.owner'])->group(function () {
    Route::get('/admin/bookings', [App\Http\Controllers\Admin\AdminBookingsController::class, 'index'])->name('admin.bookings.index');
    Route::get('/admin/bookings/{id}', [App\Http\Controllers\Admin\AdminBookingsController::class, 'show'])->name('admin.bookings.show');
    Route::post('/admin/bookings/{id}/status', [App\Http\Controllers\Admin\AdminBookingsController::class, 'updateStatus'])->name('admin.bookings.updateStatus');
    Route::post('/admin/bookings/{id}/comment', [App\Http\Controllers\Admin\AdminBookingsController::class, 'storeComment'])->name('admin.bookings.comment.store');
    Route::post('/admin/bookings/{id}/stay-modification', [App\Http\Controllers\Admin\AdminBookingsController::class, 'storeStayModification'])->name('admin.bookings.stay-modification.store');
    Route::get('/admin/bookings/{id}/delete', [App\Http\Controllers\Admin\AdminBookingsController::class, 'destroy'])->name('admin.bookings.destroy');
});

/*
|--------------------------------------------------------------------------
| Public site (Kigali Drive Rentals)
|--------------------------------------------------------------------------
*/
Route::middleware(['redirect.admin'])->group(function () {
    Route::get('/', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
    Route::get('/about-us', [App\Http\Controllers\HomeController::class, 'about'])->name('about');
    Route::get('/faq', [App\Http\Controllers\HomeController::class, 'faq'])->name('faq');
    Route::get('/contact', [App\Http\Controllers\HomeController::class, 'contact'])->name('contact');

    Route::get('/cars', [App\Http\Controllers\HomeController::class, 'showCars'])->name('showCars');
    Route::get('/cars/{slug}', [App\Http\Controllers\HomeController::class, 'carDetails'])->name('carDetails');
    Route::post('/reservations/car', [App\Http\Controllers\ReservationController::class, 'storeCar'])->name('reservations.car');

    Route::get('/services', [App\Http\Controllers\ServiceController::class, 'index'])->name('services.index');
    Route::get('/services/{slug}', [App\Http\Controllers\ServiceController::class, 'show'])->name('services.show');

    Route::redirect('/apartments', '/cars', 301);
    Route::redirect('/apartments/{any}', '/cars', 301)->where('any', '.*');
    Route::post('/storeBookings', [App\Http\Controllers\HomeController::class, 'storeBooking'])->name('bookings.store');
    Route::post('/reservations/apartment', [App\Http\Controllers\ReservationController::class, 'storeApartment'])->name('reservations.apartment');
    Route::post('/accommodations/{property}/reviews', [App\Http\Controllers\ReviewController::class, 'storePropertyReview'])->name('property.reviews.store');

    Route::get('/list-your-property', [App\Http\Controllers\ListingRequestController::class, 'create'])->name('listYourProperty');
    Route::post('/list-your-property', [App\Http\Controllers\ListingRequestController::class, 'store'])->name('listYourProperty.store');
    Route::redirect('/list-your-car', '/list-your-property', 301);

    Route::get('/updates', [App\Http\Controllers\HomeController::class, 'blogs'])->name('blogs');
    Route::get('/updates/{slug}', [App\Http\Controllers\HomeController::class, 'singleBlog'])->name('singleBlog');

    Route::post('/enquiries', [App\Http\Controllers\EnquiryController::class, 'store'])->name('enquiries.store');
    Route::post('/sendMessage', [App\Http\Controllers\EnquiryController::class, 'store'])->name('sendMessage');
    Route::post('/sendComment', [App\Http\Controllers\HomeController::class, 'sendComment'])
        ->middleware('throttle:8,1')
        ->name('sendComment');
});

Route::get('/reviews', [App\Http\Controllers\ReviewController::class, 'index'])->name('reviews.index');
Route::get('/reviews/{id}', [App\Http\Controllers\ReviewController::class, 'show'])->name('reviews.show');
Route::post('/reviews', [App\Http\Controllers\ReviewController::class, 'store'])->name('reviews.store');

Route::get('/terms-and-conditions', [App\Http\Controllers\HomeController::class, 'terms'])->name('terms');

Route::get('/sitemap.xml', [App\Http\Controllers\SitemapController::class, 'index'])->name('sitemap');
Route::get('/robots.txt', [App\Http\Controllers\SitemapController::class, 'robots'])->name('robots');

/*
|--------------------------------------------------------------------------
| Legacy URL redirects (old Kigali Drive Rentals routes)
|--------------------------------------------------------------------------
*/
Route::redirect('/transport', '/cars', 301);
Route::redirect('/transport/{any}', '/cars', 301)->where('any', '.*');
Route::redirect('/accommodations', '/cars', 301);
Route::redirect('/accommodations/hotelsSearch', '/cars', 301);
Route::redirect('/accommodations/hotels', '/cars', 301);
Route::redirect('/our-apartments', '/cars', 301);
Route::redirect('/villas', '/cars', 301);
Route::redirect('/hotels', '/cars', 301);
Route::redirect('/connect', '/contact', 301);
Route::redirect('/articles', '/updates', 301);
Route::redirect('/our-services', '/services', 301);
Route::redirect('/destinations', '/cars', 301);
Route::redirect('/tours', '/cars', 301);
Route::redirect('/services/ticketing', '/contact', 301);
Route::redirect('/services/left-bags', '/contact', 301);
Route::redirect('/promotions', '/cars', 301);
Route::redirect('/facilities', '/cars', 301);
Route::redirect('/events', '/updates', 301);
Route::redirect('/gallery', '/updates', 301);
Route::get('/accommodations/{slug}', fn () => redirect('/cars', 301));
Route::get('/accommodations/{property}/rooms/{unit}', fn () => redirect('/cars', 301));

Route::middleware(['auth'])->group(function () {
    Route::get('/email/verify', [App\Http\Controllers\Auth\VerificationController::class, 'show'])->name('verification.notice');
    Route::get('/email/verify/{id}/{hash}', [App\Http\Controllers\Auth\VerificationController::class, 'verify'])
        ->middleware(['signed', 'throttle:6,1'])->name('verification.verify');
    Route::post('/email/verification-notification', [App\Http\Controllers\Auth\VerificationController::class, 'resend'])
        ->middleware('throttle:6,1')->name('verification.send');
});

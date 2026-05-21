<?php

namespace App\Http\Controllers;

use App\Models\Home;
use App\Models\Term;
use App\Models\Leftbag;
use App\Models\Ticketing;
use App\Models\About;
use App\Models\Aboutus;
use App\Models\Setting;
use App\Services\GoogleBusinessReviewService;
use Illuminate\Support\Facades\Cache;
use App\Models\CarRentalContent;
use App\Models\Getinvolved;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Schema;

class SettingsController extends Controller
{
    public function setting(){
        $data = Setting::first();
        $setting = $data;

        if ($data === null) {
            $data = Setting::create([
                'company' => 'Kigali Drive Rentals',
                'tagline' => 'Drive Better. Buy Smarter. Rent with Confidence.',
                'address' => 'Kisimenti, Kigali, Rwanda',
                'business_hours' => 'Open Daily',
                'user_id' => Auth::id(),
                'whatsapp_enabled' => true,
                'booking_email_enabled' => true,
            ]);
            $setting = $data;
            Cache::forget('shared_setting');
        }

        return view('admin.setting', ['data' => $data, 'setting' => $setting]);
    }



    public function saveSetting(Request $request){
        $data = Setting::first();
        if (!$data) {
            return redirect()->route('setting');
        }
        $data->company = $request->input('company');
        $data->tagline = $request->input('tagline');
        $data->address = $request->input('address');
        $data->business_hours = $request->input('business_hours');
        $data->phone = $request->input('phone');
        $data->email = $request->input('email');
        $data->whatsapp = $request->input('whatsapp');
        $data->whatsapp_enabled = $request->boolean('whatsapp_enabled');
        $data->booking_email = $request->input('booking_email');
        $data->booking_email_enabled = $request->boolean('booking_email_enabled');
        $data->map_embed = $request->input('map_embed');
        $data->google_business_url = $request->input('google_business_url');
        $googlePlaceId = trim((string) $request->input('google_place_id', ''));
        if ($googlePlaceId === '' && $data->google_business_url) {
            $googlePlaceId = app(GoogleBusinessReviewService::class)
                ->extractPlaceId($data->google_business_url) ?? '';
        }
        $data->google_place_id = $googlePlaceId ?: null;
        $data->facebook = $request->input('facebook');
        $data->instagram = $request->input('instagram');
        $data->youtube = $request->input('youtube');
        $data->linkedin = $request->input('linkedin');
        if (Schema::hasColumn('settings', 'linktree')) {
            $data->linktree = $request->input('linktree');
        }
        if (Schema::hasColumn('settings', 'keywords')) {
            $data->keywords = $request->input('keywords');
        }
        $data->quote = $request->input('quote');
        $data->user_id = Auth()->user()->id;


        if ($request->hasFile('logo') && request('logo') != '') {
            $dir = 'public/images';

            if (File::exists($dir)) {
                unlink($dir);
            }
            $path = $request->file('logo')->store($dir);
            $fileName = str_replace($dir, '', $path);

            $data->logo = $fileName;
        }


        if (Schema::hasColumn('settings', 'donate') && $request->hasFile('donate') && $request->file('donate')->isValid()) {
            $dir = 'public/images';
            if ($data->donate && File::exists($dir . $data->donate)) {
                @unlink($dir . $data->donate);
            }
            $path = $request->file('donate')->store($dir);
            $data->donate = str_replace($dir, '', $path);
        }

        $saved = $data->save();
        Cache::forget('shared_setting');

        if ($saved && $data->google_place_id) {
            Cache::forget('kdr_google_reviews_' . md5($data->google_place_id));
        }

        if($saved){
            return redirect()->back()->with('success', 'Setting has been updated successfully');
        }
        else{
            abort(404);
        }
    }

    public function aboutPage(){
        $data = $this->ensureDefaultAbout();
        $setting = Setting::first();
        $terms = $this->ensureDefaultTerms();

        return view('admin.pages.about', ['data' => $data, 'setting' => $setting, 'terms' => $terms]);
    }

    protected function ensureDefaultAbout(): About
    {
        $about = About::first();
        if ($about) {
            return $about;
        }

        return About::create([
            'title' => 'Welcome to Kigali Drive Rentals',
            'subTitle' => 'Your trusted partner for mobility and housing in Rwanda',
            'welcomeMessage' => '<p>At Kigali Drive Rentals, we simplify mobility and housing solutions in Rwanda.</p>',
            'user_id' => Auth::id() ?? 1,
        ]);
    }

    protected function ensureDefaultTerms(): Term
    {
        $terms = Term::first();
        if ($terms) {
            return $terms;
        }

        $defaults = [
            'title' => 'Policies',
            'type' => 'general',
            'user_id' => Auth::id(),
        ];

        if (Schema::hasColumn('terms', 'terms')) {
            $defaults['terms'] = 'Our Terms and conditions';
            $defaults['privacy'] = 'Our Privacy policies';
            $defaults['privacy_details'] = 'We do not share or use your data for third-party marketing.';
            $defaults['cookies'] = 'We use cookies to improve site experience.';
            $defaults['refunds'] = 'Refund policy details.';
            $defaults['booking_cancellation'] = 'Booking cancellation policy.';
            $defaults['listing_commission'] = 'Listing commission policy.';
            $defaults['payment_methods'] = 'Accepted payment methods.';
            $defaults['return'] = 'Our Return policies';
            $defaults['support'] = 'Our Support policies';
            $defaults['added_by'] = Auth::id();
        } else {
            $defaults['content'] = '<p>Terms and policies — edit in admin.</p>';
        }

        return Term::create($defaults);
    }

    public function saveAbout(Request $request){
        $data = About::first();
        if (!$data) {
            $data = new About();
            $data->user_id = Auth::id() ?? 1;
        }

        $data->title = $request->input('title', $data->title);
        $data->subTitle = $request->input('subTitle', $data->subTitle);
        $data->welcomeMessage = $request->input('welcomeMessage', $data->welcomeMessage);
        $data->mission = $request->input('mission', $data->mission);
        $data->WhyChooseUs = $request->input('WhyChooseUs', $data->WhyChooseUs);
        if (Schema::hasColumn('abouts', 'terms')) {
            $data->terms = $request->input('terms', $data->terms);
        }
        $data->vision = $request->input('vision', $data->vision ?? null);
        $data->what_we_do = $request->input('what_we_do', $data->what_we_do ?? null);
        $data->commitment = $request->input('commitment', $data->commitment ?? null);
        $data->cta_services_url = $request->input('cta_services_url') ?: null;
        $data->cta_book_url = $request->input('cta_book_url') ?: null;
        $data->cta_contact_url = $request->input('cta_contact_url') ?: null;

        $dir = 'public/images/about/';

        if ($request->hasFile('image1') && $request->file('image1')->isValid()) {
            if ($data->image1 && \Illuminate\Support\Facades\Storage::exists($dir . $data->image1)) {
                \Illuminate\Support\Facades\Storage::delete($dir . $data->image1);
            }
            $path = $request->file('image1')->store($dir);
            $data->image1 = str_replace($dir, '', $path);
        }

        if ($request->hasFile('image2') && $request->file('image2')->isValid()) {
            if ($data->image2 && \Illuminate\Support\Facades\Storage::exists($dir . $data->image2)) {
                \Illuminate\Support\Facades\Storage::delete($dir . $data->image2);
            }
            $path = $request->file('image2')->store($dir);
            $data->image2 = str_replace($dir, '', $path);
        }

        if ($request->hasFile('image3') && $request->file('image3')->isValid()) {
            if ($data->image3 && \Illuminate\Support\Facades\Storage::exists($dir . $data->image3)) {
                \Illuminate\Support\Facades\Storage::delete($dir . $data->image3);
            }
            $path = $request->file('image3')->store($dir);
            $data->image3 = str_replace($dir, '', $path);
        }

        if ($request->hasFile('image4') && $request->file('image4')->isValid()) {
            if ($data->image4 && \Illuminate\Support\Facades\Storage::exists($dir . $data->image4)) {
                \Illuminate\Support\Facades\Storage::delete($dir . $data->image4);
            }
            $path = $request->file('image4')->store($dir);
            $data->image4 = str_replace($dir, '', $path);
        }

        $data->save();
        Cache::forget('shared_about');

        return redirect()->back()->with('success', 'About us page has been updated successfully.');
    }

    public function saveSiteImages(Request $request)
    {
        $data = Setting::first();
        if (!$data) {
            return redirect()->back()->with('error', 'Settings not found.');
        }

        $dir = 'public/images/site/';

        if ($request->hasFile('home_header_image') && $request->file('home_header_image')->isValid()) {
            if ($data->home_header_image && \Illuminate\Support\Facades\Storage::exists($dir . $data->home_header_image)) {
                \Illuminate\Support\Facades\Storage::delete($dir . $data->home_header_image);
            }
            $path = $request->file('home_header_image')->store($dir);
            $data->home_header_image = str_replace($dir, '', $path);
        }

        if ($request->hasFile('home_background_image') && $request->file('home_background_image')->isValid()) {
            if ($data->home_background_image && \Illuminate\Support\Facades\Storage::exists($dir . $data->home_background_image)) {
                \Illuminate\Support\Facades\Storage::delete($dir . $data->home_background_image);
            }
            $path = $request->file('home_background_image')->store($dir);
            $data->home_background_image = str_replace($dir, '', $path);
        }

        if ($request->hasFile('why_trust_background_image') && $request->file('why_trust_background_image')->isValid()) {
            if ($data->why_trust_background_image && \Illuminate\Support\Facades\Storage::exists($dir . $data->why_trust_background_image)) {
                \Illuminate\Support\Facades\Storage::delete($dir . $data->why_trust_background_image);
            }
            $path = $request->file('why_trust_background_image')->store($dir);
            $data->why_trust_background_image = str_replace($dir, '', $path);
        }

        if ($request->hasFile('contact_us_middle_image') && $request->file('contact_us_middle_image')->isValid()) {
            if ($data->contact_us_middle_image && \Illuminate\Support\Facades\Storage::exists($dir . $data->contact_us_middle_image)) {
                \Illuminate\Support\Facades\Storage::delete($dir . $data->contact_us_middle_image);
            }
            $path = $request->file('contact_us_middle_image')->store($dir);
            $data->contact_us_middle_image = str_replace($dir, '', $path);
        }

        $data->save();
        cache()->forget('shared_setting');

        return redirect()->back()->with('success', 'Site images have been updated successfully.');
    }

    public function getLeftBags(){
        $data = Leftbag::first();
        $setting = Setting::first();
        if($data===null)
        {
            $data = new Leftbag();
            $data->heading = 'About Us';
            $data->description = 'About Us';
            $data->user_id = 1;
            $data->save();
            $data = Leftbag::first();
        }

        return view('admin.pages.leftBags', ['data'=>$data,'setting'=>$setting]);
    }

    public function updateBags(Request $request){
        $data = Leftbag::first();
        $heading = [];
        $description = [];
    
        if ($data->heading != $request->input('heading')) {
            $data->heading = $request->input('heading');
            $heading[] = 'heading';
        }
    
        if ($data->description != $request->input('description')) {
            $data->description = $request->input('description');
            $description[] = 'description';
        }

        // Handle file uploads
        $dir = 'public/images/leftbags/';
    
        if ($request->hasFile('image') && request('image') != '') {
            // Delete old file
            File::delete($dir . $data->image);
            // Store new file
            $fileName = $request->file('image')->store($dir);
            $data->image = str_replace($dir, '', $fileName);
            $image[] = 'image';
        }

    
        $saved = $data->update();
    
        if($saved){
            return redirect()->back()->with('success', 'Page fields have been updated successfully');

        }

        return redirect()->back()->with('error', 'No fields were updated');
    }
    public function getTicketing(){
        $data = Ticketing::first();
        $setting = Setting::first();
        if($data===null)
        {
            $data = new Ticketing();
            $data->heading = 'About Us';
            $data->description = 'About Us';
            $data->user_id = 1;
            $data->save();
            $data = Leftbag::first();
        }

        return view('admin.pages.ticketing', ['data'=>$data,'setting'=>$setting]);
    }

    public function updateTicketing(Request $request){
        $data = Ticketing::first();
        $heading = [];
        $description = [];
    
        if ($data->heading != $request->input('heading')) {
            $data->heading = $request->input('heading');
            $heading[] = 'heading';
        }
    
        if ($data->description != $request->input('description')) {
            $data->description = $request->input('description');
            $description[] = 'description';
        }

        // Handle file uploads
        $dir = 'public/images/ticketing/';
    
        if ($request->hasFile('image') && request('image') != '') {
            // Delete old file
            File::delete($dir . $data->image);
            // Store new file
            $fileName = $request->file('image')->store($dir);
            $data->image = str_replace($dir, '', $fileName);
            $image[] = 'image';
        }

    
        $saved = $data->update();
    
        if($saved){
            return redirect()->back()->with('success', 'Page fields have been updated successfully');

        }

        return redirect()->back()->with('error', 'No fields were updated');
    }

    // Car rental content
    public function getCarRental()
    {
        $setting = Setting::first();
        $data = CarRentalContent::first();
        if ($data === null) {
            $data = CarRentalContent::create([
                'heading' => 'Stay Nets Car Rental Services',
                'subheading' => 'Reliable, Comfortable, and Flexible Car Rental in Rwanda',
                'description' => 'Travel Rwanda and East Africa with ease and comfort through Stay Nets Car Rental Services.',
                'fleet_content' => '',
                'why_content' => '',
                'services_content' => '',
                'booking_content' => '',
                'cta_book_label' => 'Book Your Car',
                'cta_quote_label' => 'Request a Quote',
            ]);
        }

        return view('admin.pages.carRental', ['data' => $data, 'setting' => $setting]);
    }

    public function updateCarRental(Request $request)
    {
        $data = CarRentalContent::firstOrFail();

        $data->heading = $request->input('heading');
        $data->subheading = $request->input('subheading');
        $data->description = $request->input('description');
        $data->fleet_content = $request->input('fleet_content');
        $data->why_content = $request->input('why_content');
        $data->services_content = $request->input('services_content');
        $data->booking_content = $request->input('booking_content');
        $data->cta_book_label = $request->input('cta_book_label');
        $data->cta_quote_label = $request->input('cta_quote_label');

        // Handle hero image upload
        $dir = 'public/images/car-rental/';
        if ($request->hasFile('hero_image') && $request->file('hero_image')->isValid()) {
            if ($data->hero_image) {
                \Illuminate\Support\Facades\File::delete($dir . $data->hero_image);
            }
            $stored = $request->file('hero_image')->store($dir);
            $data->hero_image = str_replace($dir, '', $stored);
        }

        $data->save();

        return redirect()->back()->with('success', 'Car rental content updated successfully');
    }



    // Terms and policies

    public function getTerms(){
        $data = $this->ensureDefaultTerms();

        return view('admin.terms', ['data' => $data]);
    }

    public function saveTerms(Request $request){
        $data = $this->ensureDefaultTerms();

        if (Schema::hasColumn('terms', 'privacy')) {
            $data->privacy = $request->input('privacy');
            $data->privacy_details = $request->input('privacy_details');
            $data->cookies = $request->input('cookies');
            $data->refunds = $request->input('refunds');
            $data->booking_cancellation = $request->input('booking_cancellation');
            $data->listing_commission = $request->input('listing_commission');
            $data->payment_methods = $request->input('payment_methods');
            $data->return = $request->input('return');
            $data->terms = $request->input('terms');
            $data->support = $request->input('support');
        } elseif (Schema::hasColumn('terms', 'content')) {
            $data->content = $request->input('content', $data->content);
        }

        $data->save();

        return redirect()->back()->with('success', 'Terms and policies has been updated successfully');
    }
}

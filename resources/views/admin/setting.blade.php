@extends('layouts.adminBase')

@section('content')
    @include('admin.includes.sidebar') <!-- Sidebar Start -->

    <div class="content">
        @include('admin.includes.navbar') <!-- Navbar Start -->

        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-header">
                            <h2 class="btn btn-primary">Contacts Setting</h2>
                            @if (session()->has('success'))
                                <div class="alert alert-success alert-dismissible">
                                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                                    {{ session()->get('success') }}
                                </div>
                            @endif
                        </div>

                        <div class="card-body">
                            <form action="{{ route('saveSetting', $data->id) }}" method="POST" enctype="multipart/form-data">
                                @csrf

                                <!-- General Information Section -->
                                <h4 class="mb-3">General Information</h4>
                                <div class="row mb-4">
                                    <div class="col-lg-6">
                                        <label for="company" class="form-label">Website Title</label>
                                        <input type="text" class="form-control" value="{{ $data->company }}" name="company">
                                    </div>
                                    <div class="col-lg-6">
                                        <label for="address" class="form-label">Address</label>
                                        <input type="text" class="form-control" value="{{ $data->address }}" name="address">
                                    </div>
                                </div>

                                <div class="row mb-4">
                                    <div class="col-lg-6">
                                        <label for="tagline" class="form-label">Tagline</label>
                                        <input type="text" class="form-control" value="{{ $data->tagline ?? '' }}" name="tagline">
                                    </div>
                                    <div class="col-lg-6">
                                        <label for="business_hours" class="form-label">Business hours</label>
                                        <input type="text" class="form-control" value="{{ $data->business_hours ?? 'Open Daily' }}" name="business_hours">
                                    </div>
                                </div>
                                <div class="row mb-4">
                                    <div class="col-lg-6">
                                        <label for="phone" class="form-label">Phone</label>
                                        <input type="text" class="form-control" value="{{ $data->phone }}" name="phone">
                                    </div>
                                    <div class="col-lg-6">
                                        <label for="email" class="form-label">Email</label>
                                        <input type="email" class="form-control" value="{{ $data->email }}" name="email">
                                    </div>
                                </div>
                                <h4 class="mb-3">Booking channels</h4>
                                <div class="row mb-4">
                                    <div class="col-lg-6">
                                        <label for="whatsapp" class="form-label">WhatsApp</label>
                                        <input type="text" class="form-control" value="{{ $data->whatsapp ?? $data->phone }}" name="whatsapp">
                                        <div class="form-check mt-2">
                                            <input class="form-check-input" type="checkbox" name="whatsapp_enabled" value="1" @checked($data->whatsapp_enabled ?? true)>
                                            <label class="form-check-label">Enable WhatsApp reservations</label>
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <label for="booking_email" class="form-label">Reservation email</label>
                                        <input type="email" class="form-control" value="{{ $data->booking_email ?? $data->email }}" name="booking_email">
                                        <div class="form-check mt-2">
                                            <input class="form-check-input" type="checkbox" name="booking_email_enabled" value="1" @checked($data->booking_email_enabled ?? true)>
                                            <label class="form-check-label">Enable email reservations</label>
                                        </div>
                                    </div>
                                </div>
                                <div class="row mb-4">
                                    <div class="col-12">
                                        <label for="map_embed" class="form-label">Google Maps embed</label>
                                        <textarea name="map_embed" class="form-control" rows="3">{{ $data->map_embed ?? '' }}</textarea>
                                    </div>
                                </div>

                                <h4 class="mb-3">Google Business Profile</h4>
                                <p class="text-muted small mb-3">Reviews on the website come from your Google Business Profile. Customers leave reviews on Google — not through this admin panel.</p>
                                <div class="row mb-4">
                                    <div class="col-12">
                                        <label for="google_business_url" class="form-label">Google Business / Maps URL</label>
                                        <input type="url" class="form-control" id="google_business_url" name="google_business_url" value="{{ old('google_business_url', $data->google_business_url ?? '') }}" placeholder="https://maps.google.com/... or https://g.page/your-business">
                                        <small class="text-muted">Paste the public link to your business on Google Maps or Google Business Profile.</small>
                                    </div>
                                </div>
                                <div class="row mb-4">
                                    <div class="col-lg-6">
                                        <label for="google_place_id" class="form-label">Google Place ID <span class="text-muted">(optional)</span></label>
                                        <input type="text" class="form-control" id="google_place_id" name="google_place_id" value="{{ old('google_place_id', $data->google_place_id ?? '') }}" placeholder="ChIJ...">
                                        <small class="text-muted">Auto-filled from the URL when possible. Needed to display reviews (requires GOOGLE_PLACES_API_KEY in .env).</small>
                                    </div>
                                    <div class="col-lg-6">
                                        @php
                                            $writeUrl = app(\App\Services\GoogleBusinessReviewService::class)->writeReviewUrl($data);
                                        @endphp
                                        @if($writeUrl)
                                        <label class="form-label d-block">Review link preview</label>
                                        <a href="{{ $writeUrl }}" target="_blank" rel="noopener" class="btn btn-outline-primary btn-sm">Open Google review form</a>
                                        @endif
                                        @if($data->google_rating)
                                        <p class="small text-muted mt-2 mb-0">Cached: {{ number_format($data->google_rating, 1) }} ★ · {{ $data->google_review_count }} reviews on Google</p>
                                        @endif
                                    </div>
                                </div>

                                <!-- Social Media Section -->
                                <h4 class="mb-3">Social Media</h4>
                                <div class="row mb-4">
                                    <div class="col-lg-6">
                                        <label for="facebook" class="form-label">Facebook</label>
                                        <input type="text" class="form-control" value="{{ $data->facebook }}" name="facebook">
                                    </div>
                                    <div class="col-lg-6">
                                        <label for="instagram" class="form-label">Instagram</label>
                                        <input type="text" class="form-control" value="{{ $data->instagram }}" name="instagram">
                                    </div>
                                </div>
                                <div class="row mb-4">
                                    <div class="col-lg-6">
                                        <label for="twitter" class="form-label">YouTube</label>
                                        <input type="text" class="form-control" value="{{ $data->youtube }}" name="youtube">
                                    </div>
                                    <div class="col-lg-6">
                                        <label for="linkedin" class="form-label">LinkedIn</label>
                                        <input type="text" class="form-control" value="{{ $data->linkedin }}" name="linkedin">
                                    </div>
                                </div>
                                @if(\Illuminate\Support\Facades\Schema::hasColumn('settings', 'linktree'))
                                <div class="row mb-4">
                                    <div class="col-12">
                                        <label for="linktree" class="form-label">Booking Link</label>
                                        <input type="text" class="form-control" value="{{ $data->linktree ?? '' }}" name="linktree">
                                    </div>
                                </div>
                                @endif

                                <!-- Logo Section -->
                                <h4 class="mb-3">Logo</h4>
                                <div class="row mb-4" style="display: flex; justify-content: space-between; align-items: center;">
                                    <!-- Header Logo -->
                                    <div class="col-lg-6 text-center" style="padding: 20px; border: 1px solid #ddd; border-radius: 10px;">
                                        <label for="currentLogo" class="form-label" style="font-weight: bold; font-size: 16px;">Header Logo</label>
                                        @if(!empty($data->logo))
                                        <div style="margin: 10px 0;">
                                            <img src="{{ asset('storage/images/' . ltrim($data->logo, '/')) }}" alt="Logo" style="width: 150px; border-radius: 8px; box-shadow: 0 2px 6px rgba(0,0,0,0.1);">
                                        </div>
                                        @endif
                                        <label for="logo" class="form-label" style="margin-top: 10px;">Change the Header Logo</label>
                                        <input type="file" class="form-control mt-2" name="logo">
                                    </div>
                                
                                    @if(\Illuminate\Support\Facades\Schema::hasColumn('settings', 'donate'))
                                    <!-- Footer Logo -->
                                    <div class="col-lg-6 text-center" style="padding: 20px; border: 1px solid #ddd; border-radius: 10px;">
                                        <label for="currentFooter" class="form-label" style="font-weight: bold; font-size: 16px;">Footer Logo</label>
                                        @if(!empty($data->donate))
                                        <div style="margin: 10px 0;">
                                            <img src="{{ asset('storage/images/' . ltrim($data->donate, '/')) }}" alt="Footer logo" style="width: 150px; border-radius: 8px; box-shadow: 0 2px 6px rgba(0,0,0,0.1);">
                                        </div>
                                        @endif
                                        <label for="donate" class="form-label" style="margin-top: 10px;">Change the Footer Logo</label>
                                        <input type="file" class="form-control mt-2" name="donate">
                                    </div>
                                    @endif
                                </div>
                                

                                <!-- Additional Settings (Admin Only) -->
                                @if(Auth()->user()->email == "admin@iremetech.com" && \Illuminate\Support\Facades\Schema::hasColumn('settings', 'keywords'))
                                    <h4 class="mb-3">Additional Settings</h4>
                                    <div class="row mb-4">
                                        <div class="col-lg-12">
                                            <label for="keywords" class="form-label">Keywords</label>
                                            <input type="text" class="form-control" value="{{ $data->keywords ?? '' }}" name="keywords">
                                        </div>
                                    </div>
                                @endif

                                <!-- Submit Button -->
                                <div class="text-end">
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fa fa-save"></i> Save Changes
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @include('admin.includes.footer')
@endsection

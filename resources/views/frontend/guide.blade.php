@extends('layouts.frontbase')

@section('content')
<div class="container py-5" style="max-width: 900px;">
    <div class="d-flex align-items-center justify-content-between flex-wrap gap-3 mb-4">
        <h1 class="h3 mb-0">User Guide</h1>
        @auth
        <a href="{{ route('myProperties') }}" class="btn btn-outline-primary btn-sm">My Properties</a>
        <a href="{{ route('myPropertyCreate') }}" class="btn btn-primary btn-sm">Add Property</a>
        @else
        <a href="{{ route('login') }}" class="btn btn-primary btn-sm">Sign In to Add a Property</a>
        @endauth
    </div>

    <div class="card border-0 shadow-sm mb-4">
        <div class="card-body p-4">
            <h2 class="h5 border-bottom pb-2 mb-3">How to Add a Property</h2>
            <p class="text-muted mb-4">Follow these steps to list your hotel, lodge, apartment, or other accommodation on the platform. You must be <strong>signed in</strong> to add or manage properties.</p>

            <ol class="list-group list-group-numbered list-group-flush">
                <li class="list-group-item border-0 px-0">
                    <strong>Go to Add Property</strong><br>
                    From the main menu click <strong>Add Property</strong>, or go to <strong>My Properties</strong> and click <strong>Add New Hotel</strong> (or <strong>Add First Hotel</strong> if you have none).
                </li>
                <li class="list-group-item border-0 px-0">
                    <strong>Fill in the required details</strong><br>
                    <ul class="mb-0 mt-1">
                        <li><strong>Property Name</strong> – Business or property name (required).</li>
                        <li><strong>Cover Image</strong> – One main image for the property (required).</li>
                        <li><strong>Property Type</strong> – Hotel, Lodge, Guest House, Apartment, Motel, or Resort (required).</li>
                        <li><strong>Stars</strong> – Star rating or “Not Ranked” (required).</li>
                        <li><strong>Location</strong> – District (required).</li>
                        <li><strong>Address</strong> – Street, building, etc. (optional but recommended).</li>
                        <li><strong>City</strong> – e.g. Kigali, Musanze (required).</li>
                        <li><strong>Contact Email, Phone, Website</strong> – Optional but recommended.</li>
                        <li><strong>Property Description</strong> – Describe your property (required).</li>
                        <li><strong>Facilities & Amenities</strong> – Select the amenities that apply (categories depend on property type: Hotel/Lodge/etc. or Apartment).</li>
                    </ul>
                </li>
                <li class="list-group-item border-0 px-0">
                    <strong>Submit the form</strong><br>
                    Click the submit/save button. Your property will be saved with status <strong>Pending</strong>. It will not appear on the public site until an administrator approves it.
                </li>
                <li class="list-group-item border-0 px-0">
                    <strong>Add rooms and more images</strong><br>
                    From <strong>My Properties</strong>, open your property and use <strong>Add Room</strong> to create room types (name, occupancy, price, amenities, image). You can also add more <strong>property images</strong> and manage <strong>room images</strong> from the same page.
                </li>
                <li class="list-group-item border-0 px-0">
                    <strong>Wait for approval</strong><br>
                    An administrator will review your property and set it to <strong>Active</strong> so it appears on the site. You can still edit the property and rooms while it is Pending.
                </li>
            </ol>
        </div>
    </div>

    <div class="card border-0 shadow-sm mb-4">
        <div class="card-body p-4">
            <h2 class="h5 border-bottom pb-2 mb-3">Required vs optional</h2>
            <table class="table table-sm table-bordered mb-0">
                <thead>
                    <tr><th>Field</th><th>Required?</th></tr>
                </thead>
                <tbody>
                    <tr><td>Property Name</td><td>Yes</td></tr>
                    <tr><td>Cover Image</td><td>Yes</td></tr>
                    <tr><td>Property Type</td><td>Yes</td></tr>
                    <tr><td>Stars</td><td>Yes</td></tr>
                    <tr><td>Location (District)</td><td>Yes</td></tr>
                    <tr><td>City</td><td>Yes</td></tr>
                    <tr><td>Description</td><td>Yes</td></tr>
                    <tr><td>Address, Email, Phone, Website</td><td>Optional</td></tr>
                    <tr><td>Amenities</td><td>Optional (recommended)</td></tr>
                </tbody>
            </table>
        </div>
    </div>

    <div class="card border-0 shadow-sm mb-4">
        <div class="card-body p-4">
            <h2 class="h5 border-bottom pb-2 mb-3">Managing your properties</h2>
            <p class="mb-2">From <strong>My Properties</strong> you can:</p>
            <ul class="mb-0">
                <li><strong>View</strong> – See property details, rooms, and images.</li>
                <li><strong>Edit</strong> – Change property details, then add or edit rooms and images.</li>
                <li><strong>Add Room</strong> – Create room types with name, occupancy, price per night, amenities, and image.</li>
                <li><strong>Delete</strong> – Remove a property or a room (use with care).</li>
            </ul>
        </div>
    </div>

    @if(auth()->check() && auth()->user()->role == 1)
    <div class="card border-0 shadow-sm border-primary mb-4">
        <div class="card-body p-4">
            <h2 class="h5 border-bottom pb-2 mb-3">Administrator</h2>
            <p class="mb-2">You are logged in as an administrator. For a full guide on the admin panel (where to start and what to do under each feature), use the link below.</p>
            <a href="{{ route('admin.guide') }}" class="btn btn-outline-primary" target="_blank">Open Admin Guide</a>
        </div>
    </div>
    @endif
</div>
@endsection

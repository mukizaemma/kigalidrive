@extends('layouts.adminBase')

@section('content')
@include('admin.includes.sidebar')
<div class="content">
    @include('admin.includes.navbar')

    <div class="container-fluid pt-4 px-4">
        <div class="bg-light rounded p-4">
            <h5 class="mb-4">Admin User Guide</h5>
            <p class="text-muted mb-4">Use this guide to know where to start and what to do under each feature in the admin panel.</p>

            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-primary text-white">
                    <strong>Where to start</strong>
                </div>
                <div class="card-body">
                    <ol class="mb-0">
                        <li class="mb-2"><strong>Dashboard</strong> – Start here for an overview. Use the sidebar to reach each section.</li>
                        <li class="mb-2"><strong>Facility Categories & Amenities</strong> – Set up categories (e.g. Room Amenities, Hotel Facilities) and add amenities so that properties and rooms can use them. Properties use these when owners add or edit listings.</li>
                        <li class="mb-2"><strong>Properties</strong> – View all properties (including those submitted by users). Approve or reject pending properties so they appear or stay hidden on the site.</li>
                        <li class="mb-2"><strong>Units/Rooms</strong> – Manage room types and unit listings if needed.</li>
                        <li>Then use <strong>Services</strong>, <strong>Trips</strong>, <strong>Cars</strong>, <strong>About Us</strong>, <strong>Contacts</strong>, and <strong>Reviews</strong> as needed.</li>
                    </ol>
                </div>
            </div>

            <h6 class="mb-3">What to do under each feature</h6>

            <div class="accordion" id="guideAccordion">
                <div class="accordion-item">
                    <h2 class="accordion-header">
                        <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#g-dashboard">Dashboard</button>
                    </h2>
                    <div id="g-dashboard" class="accordion-collapse collapse show" data-bs-parent="#guideAccordion">
                        <div class="accordion-body">View the admin home. Use the sidebar to go to any section.</div>
                    </div>
                </div>
                <div class="accordion-item">
                    <h2 class="accordion-header">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#g-services">Services</button>
                    </h2>
                    <div id="g-services" class="accordion-collapse collapse" data-bs-parent="#guideAccordion">
                        <div class="accordion-body">Add, edit, or delete services offered on the site (e.g. accommodation, tours, car rental). These appear on the public services page.</div>
                    </div>
                </div>
                <div class="accordion-item">
                    <h2 class="accordion-header">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#g-properties">Properties</button>
                    </h2>
                    <div id="g-properties" class="accordion-collapse collapse" data-bs-parent="#guideAccordion">
                        <div class="accordion-body">View all properties (yours and user-submitted). Create new properties or open existing ones to edit details, images, and status. <strong>Approve</strong> or <strong>Reject</strong> user-submitted properties so they go live or stay hidden. Manage property images and details.</div>
                    </div>
                </div>
                <div class="accordion-item">
                    <h2 class="accordion-header">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#g-units">Units/Rooms</button>
                    </h2>
                    <div id="g-units" class="accordion-collapse collapse" data-bs-parent="#guideAccordion">
                        <div class="accordion-body">Manage room types and units linked to properties. Add, edit, or delete rooms; set pricing, occupancy, and amenities. You can also edit rooms added by property owners.</div>
                    </div>
                </div>
                <div class="accordion-item">
                    <h2 class="accordion-header">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#g-trips">Trip Activities</button>
                    </h2>
                    <div id="g-trips" class="accordion-collapse collapse" data-bs-parent="#guideAccordion">
                        <div class="accordion-body">Create and manage tour/trip packages (e.g. gorilla trekking, safaris). Add titles, descriptions, images, and pricing. These appear on the tours/destinations section of the site.</div>
                    </div>
                </div>
                <div class="accordion-item">
                    <h2 class="accordion-header">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#g-trip-requests">Trip Requests</button>
                    </h2>
                    <div id="g-trip-requests" class="accordion-collapse collapse" data-bs-parent="#guideAccordion">
                        <div class="accordion-body">View and manage trip/tour booking requests from visitors. Update status and respond as needed.</div>
                    </div>
                </div>
                <div class="accordion-item">
                    <h2 class="accordion-header">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#g-bookings">Bookings</button>
                    </h2>
                    <div id="g-bookings" class="accordion-collapse collapse" data-bs-parent="#guideAccordion">
                        <div class="accordion-body">View and manage accommodation bookings. Confirm, cancel, or update booking status.</div>
                    </div>
                </div>
                <div class="accordion-item">
                    <h2 class="accordion-header">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#g-cars">Cars</button>
                    </h2>
                    <div id="g-cars" class="accordion-collapse collapse" data-bs-parent="#guideAccordion">
                        <div class="accordion-body">Add, edit, or delete cars for rental or sale. Set cover image, gallery, daily/monthly rent, sale price, and details (model, fuel, transmission, seats).</div>
                    </div>
                </div>
                <div class="accordion-item">
                    <h2 class="accordion-header">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#g-car-requests">Car Rental Requests</button>
                    </h2>
                    <div id="g-car-requests" class="accordion-collapse collapse" data-bs-parent="#guideAccordion">
                        <div class="accordion-body">View car booking/rental requests (view car, rent, or buy). Approve or reject requests and follow up with customers.</div>
                    </div>
                </div>
                <div class="accordion-item">
                    <h2 class="accordion-header">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#g-leftbags">Left Bags</button>
                    </h2>
                    <div id="g-leftbags" class="accordion-collapse collapse" data-bs-parent="#guideAccordion">
                        <div class="accordion-body">Manage luggage storage content and settings shown on the Left Bags service page.</div>
                    </div>
                </div>
                <div class="accordion-item">
                    <h2 class="accordion-header">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#g-ticketing">Ticketing</button>
                    </h2>
                    <div id="g-ticketing" class="accordion-collapse collapse" data-bs-parent="#guideAccordion">
                        <div class="accordion-body">Manage air ticketing page content and information.</div>
                    </div>
                </div>
                <div class="accordion-item">
                    <h2 class="accordion-header">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#g-car-rental">Car Rental Content</button>
                    </h2>
                    <div id="g-car-rental" class="accordion-collapse collapse" data-bs-parent="#guideAccordion">
                        <div class="accordion-body">Edit the main car rental page text and layout (not the car listings themselves, which are under Cars).</div>
                    </div>
                </div>
                <div class="accordion-item">
                    <h2 class="accordion-header">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#g-articles">Articles</button>
                    </h2>
                    <div id="g-articles" class="accordion-collapse collapse" data-bs-parent="#guideAccordion">
                        <div class="accordion-body">Create, edit, and publish blog articles. Manage comments if enabled.</div>
                    </div>
                </div>
                <div class="accordion-item">
                    <h2 class="accordion-header">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#g-destinations">Destinations</button>
                    </h2>
                    <div id="g-destinations" class="accordion-collapse collapse" data-bs-parent="#guideAccordion">
                        <div class="accordion-body">Manage tour destinations (e.g. national parks, regions). These group and display trip activities on the site.</div>
                    </div>
                </div>
                <div class="accordion-item">
                    <h2 class="accordion-header">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#g-facility-categories">Facility Categories</button>
                    </h2>
                    <div id="g-facility-categories" class="accordion-collapse collapse" data-bs-parent="#guideAccordion">
                        <div class="accordion-body">Create and edit categories for amenities (e.g. Room Amenities, Hotel Facilities). Each category has its own tab. Add or edit amenities within each category so they are available when adding or editing properties and rooms.</div>
                    </div>
                </div>
                <div class="accordion-item">
                    <h2 class="accordion-header">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#g-amenities">Amenities</button>
                    </h2>
                    <div id="g-amenities" class="accordion-collapse collapse" data-bs-parent="#guideAccordion">
                        <div class="accordion-body">View all amenities in tabs by category. Add new amenities from each category tab (title and active status only; icon and sort order can be set when editing). Edit or delete existing amenities. Avoid duplicate titles within the same category.</div>
                    </div>
                </div>
                <div class="accordion-item">
                    <h2 class="accordion-header">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#g-about">About Us</button>
                    </h2>
                    <div id="g-about" class="accordion-collapse collapse" data-bs-parent="#guideAccordion">
                        <div class="accordion-body">Edit the public About Us page: header image, title, tagline, intro, mission, vision, What We Do, Why Choose Us, Our Commitment, and CTA button URLs. The Terms & Policies tab lets you manage privacy, cookies, refunds, and other policy text.</div>
                    </div>
                </div>
                <div class="accordion-item">
                    <h2 class="accordion-header">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#g-contacts">Contacts</button>
                    </h2>
                    <div id="g-contacts" class="accordion-collapse collapse" data-bs-parent="#guideAccordion">
                        <div class="accordion-body">Update site settings: company name, logo, address, phone, email, and social links. These appear in the header, footer, and contact page.</div>
                    </div>
                </div>
                <div class="accordion-item">
                    <h2 class="accordion-header">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#g-reviews">Reviews</button>
                    </h2>
                    <div id="g-reviews" class="accordion-collapse collapse" data-bs-parent="#guideAccordion">
                        <div class="accordion-body">View all customer reviews. Approve or reject submissions, add admin responses, and feature reviews. You can also create reviews from the admin side.</div>
                    </div>
                </div>
                <div class="accordion-item">
                    <h2 class="accordion-header">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#g-users">Users</button>
                    </h2>
                    <div id="g-users" class="accordion-collapse collapse" data-bs-parent="#guideAccordion">
                        <div class="accordion-body">(If visible to you.) Manage user accounts, verify emails, and assign admin rights. Restrict access as needed.</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@include('admin.includes.footer')
@endsection

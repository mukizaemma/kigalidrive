@extends('layouts.frontbase')

@section('content')
<section class="py-5 mt-4">
    <div class="container col-lg-8">
        <h1 class="kdr-section-title mb-2">List Your Car</h1>
        <p class="text-muted mb-4">Share a few details about your vehicle — our team in Kisimenti will review and contact you before anything goes live.</p>

        <form action="{{ route('listYourProperty.store') }}" method="POST" class="kdr-card p-4">
            @csrf
            <input type="hidden" name="product_type" value="car">
            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label">Ad type</label>
                    <select name="ad_type" class="form-select" required>
                        <option value="rent">For rent</option>
                        <option value="sale">For sale</option>
                    </select>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Vehicle make / model</label>
                    <input type="text" name="vehicle_info" class="form-control" placeholder="e.g. Toyota RAV4 2020" value="{{ old('vehicle_info') }}">
                </div>
                <div class="col-md-6">
                    <label class="form-label">Your name</label>
                    <input type="text" name="contact_name" class="form-control" required value="{{ old('contact_name') }}">
                </div>
                <div class="col-md-6">
                    <label class="form-label">Phone</label>
                    <input type="text" name="phone" class="form-control" required value="{{ old('phone') }}">
                </div>
                <div class="col-md-6">
                    <label class="form-label">Email</label>
                    <input type="email" name="email" class="form-control" value="{{ old('email') }}">
                </div>
                <div class="col-md-6">
                    <label class="form-label">Asking price (RWF)</label>
                    <input type="number" name="amount" class="form-control" min="0" step="1" value="{{ old('amount') }}">
                </div>
                <div class="col-12">
                    <label class="form-label">Location</label>
                    <input type="text" name="location" class="form-control" required value="{{ old('location') }}" placeholder="e.g. Kisimenti, Kigali">
                </div>
                <div class="col-12">
                    <label class="form-label">Additional details</label>
                    <textarea name="details" class="form-control" rows="4" placeholder="Condition, mileage, transmission, driver option, etc.">{{ old('details') }}</textarea>
                </div>
                <div class="col-12">
                    <button type="submit" class="th-btn btn-kdr-primary">Submit for review</button>
                </div>
            </div>
        </form>
    </div>
</section>
@endsection

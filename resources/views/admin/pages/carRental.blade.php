@extends('layouts.adminBase')

@section('content')

@include('admin.includes.sidebar')

<div class="content">
    @include('admin.includes.navbar')

    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h2 class="btn btn-primary mb-0">Car Rental Page</h2>
                        <a href="{{ route('getCars') }}" class="btn btn-outline-secondary">Manage Cars</a>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('updateCarRental') }}" method="POST" enctype="multipart/form-data"
                              style="padding:20px;background:#f9f9f9;border-radius:10px;box-shadow:0 0 10px rgba(0,0,0,0.1);">
                            @csrf

                            {{-- Heading --}}
                            <div class="mb-4">
                                <label class="form-label fw-bold">Main Heading</label>
                                <input type="text" name="heading" class="form-control"
                                       value="{{ old('heading', $data->heading) }}" required>
                            </div>

                            {{-- Subheading --}}
                            <div class="mb-4">
                                <label class="form-label fw-bold">Subheading</label>
                                <input type="text" name="subheading" class="form-control"
                                       value="{{ old('subheading', $data->subheading) }}" required>
                            </div>

                            {{-- Intro Description --}}
                            <div class="mb-4">
                                <label class="form-label fw-bold">Intro Description</label>
                                <textarea name="description" rows="4" class="form-control"
                                          required>{{ old('description', $data->description) }}</textarea>
                            </div>

                            {{-- Hero Image --}}
                            <div class="mb-4">
                                <label class="form-label fw-bold">Heading Image</label><br>
                                @if($data->hero_image)
                                    <img src="{{ asset('storage/images/car-rental/' . $data->hero_image) }}"
                                         width="200" class="mb-3 rounded shadow-sm">
                                @endif
                                <input type="file" name="hero_image" class="form-control">
                                <small class="text-muted">Leave empty to keep current image</small>
                            </div>

                            {{-- Our Fleet --}}
                            <div class="mb-4">
                                <label class="form-label fw-bold">Our Fleet Content</label>
                                <textarea name="fleet_content" rows="6" class="form-control"
                                          id="fleetContent">{{ old('fleet_content', $data->fleet_content) }}</textarea>
                                <small class="text-muted">Use bullet points for each vehicle category.</small>
                            </div>

                            {{-- Why Choose --}}
                            <div class="mb-4">
                                <label class="form-label fw-bold">Why Choose Stay Nets Car Rental</label>
                                <textarea name="why_content" rows="6" class="form-control"
                                          id="whyContent">{{ old('why_content', $data->why_content) }}</textarea>
                            </div>

                            {{-- Services Include --}}
                            <div class="mb-4">
                                <label class="form-label fw-bold">Services Included</label>
                                <textarea name="services_content" rows="6" class="form-control"
                                          id="servicesContent">{{ old('services_content', $data->services_content) }}</textarea>
                            </div>

                            {{-- Booking Steps --}}
                            <div class="mb-4">
                                <label class="form-label fw-bold">Booking Steps Content</label>
                                <textarea name="booking_content" rows="6" class="form-control"
                                          id="bookingContent">{{ old('booking_content', $data->booking_content) }}</textarea>
                            </div>

                            {{-- CTA labels --}}
                            <div class="row mb-4">
                                <div class="col-md-6">
                                    <label class="form-label fw-bold">Primary CTA Label</label>
                                    <input type="text" name="cta_book_label" class="form-control"
                                           value="{{ old('cta_book_label', $data->cta_book_label ?? 'Book Your Car') }}">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-bold">Secondary CTA Label</label>
                                    <input type="text" name="cta_quote_label" class="form-control"
                                           value="{{ old('cta_quote_label', $data->cta_quote_label ?? 'Request a Quote') }}">
                                </div>
                            </div>

                            <div class="text-center mt-4">
                                <button type="submit" class="btn btn-primary px-4">
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

@endsection


@extends('layouts.frontbase')

@section('content')
    <section class="space bg-smoke">
        <div class="container">
            <div class="row justify-content-center mb-4">
                <div class="col-lg-8 text-center">
                    <h2 class="sec-title">Left Bags Reservation Form</h2>
                    <p class="sec-text">Fill in the form below to request safe luggage storage while you travel freely.</p>
                </div>
            </div>

            <div class="row justify-content-center">
                <div class="col-lg-8">
                    <div class="card border-0 shadow-sm">
                        <div class="card-body p-4">
                            <form action="{{ route('bookNow') }}" method="POST">
                                @csrf
                                <input type="hidden" name="service_type" value="left_bags">

                                <div class="mb-3">
                                    <label class="form-label">Full Name *</label>
                                    <input type="text" name="names" class="form-control" required>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">Phone Number *</label>
                                    <input type="tel" name="phone" class="form-control" required>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">Email Address</label>
                                    <input type="email" name="email" class="form-control">
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">Drop-off Date *</label>
                                    <input type="date" name="dropoff_date" class="form-control" required>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">Pick-up Date *</label>
                                    <input type="date" name="pickup_date" class="form-control" required>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">Number of Bags *</label>
                                    <input type="number" name="bags_count" class="form-control" min="1" required>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">Additional Notes</label>
                                    <textarea name="message" rows="3" class="form-control" placeholder="Any special instructions or information"></textarea>
                                </div>

                                <div class="d-flex justify-content-end gap-2">
                                    <a href="{{ route('leftBags') }}" class="btn btn-outline-secondary">Back</a>
                                    <button type="submit" class="btn btn-primary">Submit Request</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

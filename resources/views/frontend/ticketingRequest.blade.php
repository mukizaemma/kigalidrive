@extends('layouts.frontbase')

@section('content')
    <section class="space bg-smoke">
        <div class="container">
            <div class="row justify-content-center mb-4">
                <div class="col-lg-8 text-center">
                    <h2 class="sec-title">Ticketing Reservation Form</h2>
                    <p class="sec-text">Fill in the form below and our team will help you find, compare, and book the best flight options for your trip.</p>
                </div>
            </div>

            <div class="row justify-content-center">
                <div class="col-lg-8">
                    <div class="card border-0 shadow-sm">
                        <div class="card-body p-4">
                            <form action="{{ route('bookNow') }}" method="POST">
                                @csrf
                                <input type="hidden" name="service_type" value="ticketing">

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
                                    <label class="form-label">Trip Type *</label>
                                    <select name="trip_type" class="form-control" required>
                                        <option value="" disabled selected>Select trip type</option>
                                        <option value="one_way">One Way</option>
                                        <option value="round_trip">Round Trip</option>
                                        <option value="multi_city">Multi-city</option>
                                    </select>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">Departure City / Airport *</label>
                                    <input type="text" name="departure" class="form-control" placeholder="e.g. Kigali (KGL)" required>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">Destination City / Airport *</label>
                                    <input type="text" name="destination" class="form-control" placeholder="e.g. Nairobi (NBO)" required>
                                </div>

                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Departure Date *</label>
                                        <input type="date" name="departure_date" class="form-control" required>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Return Date (if applicable)</label>
                                        <input type="date" name="return_date" class="form-control">
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">Number of Passengers *</label>
                                    <input type="number" name="passengers" class="form-control" min="1" value="1" required>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">Preferred Travel Class</label>
                                    <select name="travel_class" class="form-control">
                                        <option value="">No preference</option>
                                        <option value="economy">Economy</option>
                                        <option value="premium_economy">Premium Economy</option>
                                        <option value="business">Business</option>
                                        <option value="first_class">First Class</option>
                                    </select>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">Additional Requests or Notes</label>
                                    <textarea name="message" rows="3" class="form-control" placeholder="Flexible dates, preferred airline, budget range, special needs, etc."></textarea>
                                </div>

                                <div class="d-flex justify-content-end gap-2">
                                    <a href="{{ route('ticketing') }}" class="btn btn-outline-secondary">Back</a>
                                    <button type="submit" class="btn btn-primary">Request Ticketing Support</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

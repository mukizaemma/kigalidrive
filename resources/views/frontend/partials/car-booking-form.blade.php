@php
    $rentalPackages = $rentalPackages ?? [];
    $hasRent = count($rentalPackages) > 0;
    $defaultType = old('booking_type', $hasRent ? 'rent' : 'view_car');
@endphp

<div class="modal fade" id="carBookingModal" tabindex="-1" aria-labelledby="carBookingModalLabel" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
    <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable kdr-modal-landscape">
        <div class="modal-content">
            <div class="modal-header border-0 pb-0">
                <div>
                    <h5 class="modal-title" id="carBookingModalLabel">Book {{ $car->name }}</h5>
                    <p class="text-muted small mb-0">Choose your package, schedule, then send via WhatsApp or Email.</p>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <form action="{{ route('reservations.car') }}" method="POST" id="carBookingForm"
                  class="kdr-channel-form kdr-car-booking d-flex flex-column flex-grow-1"
                  data-packages='@json($rentalPackages)'>
                @csrf
                <input type="hidden" name="car_id" value="{{ $car->id }}">
                <input type="hidden" name="rental_duration" id="rental_duration" value="{{ old('rental_duration') }}">
                <input type="hidden" name="with_driver" id="with_driver" value="{{ old('with_driver') }}">

                <div class="modal-body pt-2">
                    @if(session('success'))
                        <div class="alert alert-success">{{ session('success') }}</div>
                    @endif
                    @if($errors->any())
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    @if($hasRent)
                    <ul class="nav nav-pills kdr-car-booking__type-tabs mb-3" role="tablist">
                        <li class="nav-item" role="presentation">
                            <button type="button" class="nav-link {{ $defaultType === 'rent' ? 'active' : '' }}"
                                    data-booking-type="rent" aria-selected="{{ $defaultType === 'rent' ? 'true' : 'false' }}">
                                <i class="fa fa-car me-1"></i> Rent
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button type="button" class="nav-link {{ $defaultType === 'view_car' ? 'active' : '' }}"
                                    data-booking-type="view_car">
                                <i class="fa fa-eye me-1"></i> View car
                            </button>
                        </li>
                    </ul>
                    @endif

                    <input type="hidden" name="booking_type" id="booking_type" value="{{ $defaultType }}">

                    {{-- Rent wizard --}}
                    <div class="kdr-car-booking__panel" data-panel="rent" @if($defaultType !== 'rent') hidden @endif>
                        @if(!$hasRent)
                            <div class="alert alert-warning mb-0">
                                Rental pricing is not set for this vehicle. Please contact us by phone or email on this page.
                            </div>
                        @else
                            <div class="kdr-car-booking__steps mb-3" aria-hidden="true">
                                <span class="kdr-car-booking__step is-active" data-step-indicator="1">1. Package</span>
                                <span class="kdr-car-booking__step" data-step-indicator="2">2. Schedule</span>
                                <span class="kdr-car-booking__step" data-step-indicator="3">3. Your details</span>
                                <span class="kdr-car-booking__step" data-step-indicator="4">4. Send</span>
                            </div>

                            <div class="kdr-car-booking__step-pane is-active" data-step="1">
                                <label class="form-label fw-semibold">Select a package <span class="text-danger">*</span></label>
                                <div class="kdr-package-grid mb-2">
                                    @foreach($rentalPackages as $package)
                                    <label class="kdr-package-card">
                                        <input type="radio" name="rental_package" value="{{ $package['key'] }}"
                                               data-duration="{{ $package['rental_duration'] }}"
                                               data-with-driver="{{ $package['with_driver'] ? '1' : '0' }}"
                                               data-label="{{ $package['label'] }}"
                                               data-price="{{ $package['price_formatted'] }}"
                                               @checked(old('rental_package') === $package['key']) required>
                                        <span class="kdr-package-card__body">
                                            <span class="kdr-package-card__title">{{ $package['label'] }}</span>
                                            <span class="kdr-package-card__price">{{ $package['price_formatted'] }}</span>
                                        </span>
                                    </label>
                                    @endforeach
                                </div>
                                @error('rental_package')
                                    <div class="text-danger small">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="kdr-car-booking__step-pane" data-step="2" hidden>
                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <label class="form-label">Pickup date <span class="text-danger">*</span></label>
                                        <input type="date" name="pickup_date" id="pickup_date" class="form-control" data-panel-field
                                               min="{{ date('Y-m-d') }}" value="{{ old('pickup_date') }}" required>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label">Pickup time</label>
                                        <input type="time" name="pickup_time" id="pickup_time" class="form-control" data-panel-field
                                               value="{{ old('pickup_time', '09:00') }}">
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label">Return date <span class="text-danger">*</span></label>
                                        <input type="date" name="dropoff_date" id="dropoff_date" class="form-control" data-panel-field
                                               min="{{ date('Y-m-d') }}" value="{{ old('dropoff_date') }}" required>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label">Return time</label>
                                        <input type="time" name="dropoff_time" id="dropoff_time" class="form-control" data-panel-field
                                               value="{{ old('dropoff_time', '18:00') }}">
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label">Pickup location</label>
                                        <input type="text" name="pickup_location" class="form-control" data-panel-field
                                               value="{{ old('pickup_location') }}" placeholder="e.g. Kigali Airport">
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label">Return location</label>
                                        <input type="text" name="dropoff_location" class="form-control" data-panel-field
                                               value="{{ old('dropoff_location') }}" placeholder="Same as pickup or other">
                                    </div>
                                    <div class="col-12">
                                        <p id="rental_period_summary" class="small text-muted mb-0 kdr-car-booking__period-summary" aria-live="polite"></p>
                                    </div>
                                </div>
                            </div>

                            <div class="kdr-car-booking__step-pane" data-step="3" hidden>
                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <label class="form-label">Full name <span class="text-danger">*</span></label>
                                        <input type="text" name="name" class="form-control" data-panel-field required
                                               value="{{ auth()->check() ? auth()->user()->name : old('name') }}">
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label">Phone <span class="text-danger">*</span></label>
                                        <input type="tel" name="phone" class="form-control" data-panel-field required
                                               value="{{ old('phone') }}" placeholder="+250 7XX XXX XXX">
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label">Email <span class="text-danger">*</span></label>
                                        <input type="email" name="email" class="form-control" data-panel-field required
                                               value="{{ auth()->check() ? auth()->user()->email : old('email') }}">
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label">Your address</label>
                                        <input type="text" name="full_address" class="form-control" data-panel-field
                                               value="{{ old('full_address') }}" placeholder="Home or hotel address">
                                    </div>
                                    <div class="col-12">
                                        <label class="form-label">Additional request</label>
                                        <textarea name="additional_request" class="form-control" rows="3" data-panel-field
                                                  placeholder="Child seat, extra driver, etc.">{{ old('additional_request') }}</textarea>
                                    </div>
                                </div>
                            </div>

                            <div class="kdr-car-booking__step-pane" data-step="4" hidden>
                                <div class="kdr-car-booking__summary kdr-card p-3 mb-3" id="carBookingSummary" aria-live="polite">
                                    <p class="small text-muted mb-2">Review your request</p>
                                    <dl class="kdr-car-booking__summary-list mb-0"></dl>
                                </div>
                            </div>

                            <div class="d-flex justify-content-between gap-2 mt-2 kdr-car-booking__nav">
                                <button type="button" class="btn btn-outline-secondary kdr-car-booking__prev" hidden>Back</button>
                                <button type="button" class="btn btn-primary th-btn btn-kdr-primary ms-auto kdr-car-booking__next">Continue</button>
                            </div>
                        @endif
                    </div>

                    {{-- View car --}}
                    <div class="kdr-car-booking__panel" data-panel="view_car" @if($defaultType !== 'view_car') hidden @endif>
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label">Full name <span class="text-danger">*</span></label>
                                <input type="text" name="name" class="form-control" data-panel-field
                                       value="{{ auth()->check() ? auth()->user()->name : old('name') }}">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Phone <span class="text-danger">*</span></label>
                                <input type="tel" name="phone" class="form-control" data-panel-field required
                                       value="{{ old('phone') }}" placeholder="+250 7XX XXX XXX">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Email <span class="text-danger">*</span></label>
                                <input type="email" name="email" class="form-control" data-panel-field required
                                       value="{{ auth()->check() ? auth()->user()->email : old('email') }}">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Preferred date <span class="text-danger">*</span></label>
                                <input type="date" name="preferred_date" id="preferred_date" class="form-control" data-panel-field
                                       min="{{ date('Y-m-d') }}" value="{{ old('preferred_date') }}">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Preferred time</label>
                                <input type="time" name="preferred_time" id="preferred_time" class="form-control" data-panel-field
                                       value="{{ old('preferred_time') }}">
                            </div>
                            <div class="col-12">
                                <label class="form-label">Notes</label>
                                <textarea name="additional_request" class="form-control" rows="2" data-panel-field>{{ old('additional_request') }}</textarea>
                            </div>
                        </div>
                    </div>

                </div>

                <div class="modal-footer kdr-modal-footer--stacked flex-column align-items-stretch">
                    <div class="kdr-car-booking__channels" data-channels-footer @if($defaultType === 'rent' && $hasRent) hidden @endif>
                        @include('frontend.partials.kdr-submission-channels', ['channelContext' => 'car_booking'])
                    </div>
                    <div class="kdr-modal-footer__actions d-flex gap-2 justify-content-end">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary th-btn btn-kdr-primary kdr-car-booking__submit">
                            <i class="fa fa-paper-plane me-2"></i>Save &amp; open to send
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

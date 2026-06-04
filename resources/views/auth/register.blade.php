<x-guest-layout>

    @php
        $data = App\Models\About::first();
        $setting = App\Models\Setting::first();
    @endphp

    <!--==============================
    Mobile Menu
    ============================== -->
    <div class="th-menu-wrapper onepage-nav">
        <div class="th-menu-area text-center">
            <button class="th-menu-toggle"><i class="fal fa-times"></i></button>
            <div class="mobile-logo">
                <a href="{{ route('home') }}"><img src="{{ asset('storage/images') . ($setting->logo ?? '') }}" alt="Kigali Drive Rentals" width="120px"></a>
            </div>
            <div class="th-mobile-menu">
                <ul>
                    <li><a href="{{ route('home') }}">Home</a></li>
                    <li><a href="{{ route('showCars') }}">Cars</a></li>
                    <li><a href="{{ route('showCars', ['listing_type' => 'sale']) }}">Cars for Sale</a></li>
                    <li><a href="{{ route('services.index') }}">Services</a></li>
                    <li><a href="{{ route('contact') }}">Contact</a></li>
                    <li><a href="{{ route('listYourProperty') }}">List your car</a></li>
                    @if(auth()->check())
                        <li>
                            <form id="logout-mobile-form" action="{{ route('logout') }}" method="POST">
                                @csrf
                                <button type="submit" class="btn btn-link p-0">Logout</button>
                            </form>
                        </li>
                    @else
                        <li><a href="{{ route('login') }}">Sign In</a></li>
                        <li><a href="{{ route('register') }}">Register</a></li>
                    @endif
                </ul>
            </div>
        </div>
    </div>

    <!--==============================
    Header Area
    ==============================-->
    <header class="th-header header-layout1 header-layout4 header-layout7">
        <div class="sticky-wrapper">
            <!-- Main Menu Area -->
            <div class="menu-area">
                <div class="container th-container">
                    <div class="row align-items-center justify-content-between">
                        <div class="col-auto">
                            <div class="header-logo">
                                <a href="{{ route('home') }}"><img src="{{ asset('storage/images') . ($setting->logo ?? '') }}" alt="Kigali Drive Rentals" width="150px"></a>
                            </div>
                        </div>
                        <div class="col-auto me-xl-auto">
                            <nav class="main-menu d-none d-xl-inline-block">
                                <ul>
                                    <li><a href="{{ route('home') }}">Home</a></li>
                                    <li><a href="{{ route('showCars') }}">Rent a Car</a></li>
                                    <li><a href="{{ route('showCars', ['listing_type' => 'sale']) }}">Buy a Car</a></li>
                                    <li><a href="{{ route('services.index') }}">Services</a></li>
                                    <li><a href="{{ route('contact') }}">Contact</a></li>
                                    <li><a href="{{ route('listYourProperty') }}">List your car</a></li>
                                </ul>
                            </nav>
                            <button type="button" class="th-menu-toggle d-block d-xl-none"><i class="far fa-bars"></i></button>
                        </div>
                        <div class="col-auto d-none d-xl-block">
                            <div class="header-button">
                                <a href="{{ route('listYourProperty') }}" class="th-btn style3 th-icon">List your car</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </header>

    <div class="min-h-screen grid grid-cols-1 md:grid-cols-2"
         style="width: 60%; max-width: 1200px; margin: 40px auto; padding-top: 40px;">

        <!-- LEFT COLUMN -->
        <div class="hidden md:flex items-center justify-center kdr-register-banner rounded-l-2xl overflow-hidden">
            <img src="{{ asset('storage/images/about') . ($data->image1 ?? '') }}"
                 alt="Banner"
                 class="h-full w-full object-cover">
        </div>

        <!-- RIGHT COLUMN -->
        <div class="flex items-center justify-center bg-gray-100 p-6 rounded-r-2xl">
            <x-authentication-card class="w-full max-w-md shadow-xl rounded-2xl bg-white p-6">

        <x-slot name="logo">
                    <h4 class="text-2xl font-bold text-center text-gray-800">
                        Create your account
                    </h4>
        </x-slot>

        <x-validation-errors class="mb-4" />

                <form method="POST" action="{{ route('register') }}" class="space-y-4">
            @csrf

            <div>
                        <x-label for="name" value="Full Name" />
                        <x-input id="name"
                                 class="block mt-1 w-full"
                                 type="text"
                                 name="name"
                                 :value="old('name')"
                                 placeholder="Enter your full name"
                                 required autofocus autocomplete="name" />
            </div>

                    <div>
                        <x-label for="email" value="Email Address" />
                        <x-input id="email"
                                 class="block mt-1 w-full"
                                 type="email"
                                 name="email"
                                 :value="old('email')"
                                 placeholder="example@mail.com"
                                 required autocomplete="username" />
            </div>

                    <div>
                        <x-label for="password" value="Password" />
                        <x-input id="password"
                                 class="block mt-1 w-full"
                                 type="password"
                                 name="password"
                                 placeholder="Enter your password"
                                 required autocomplete="new-password" />
            </div>

                    <div>
                        <x-label for="password_confirmation" value="Confirm Password" />
                        <x-input id="password_confirmation"
                                 class="block mt-1 w-full"
                                 type="password"
                                 name="password_confirmation"
                                 placeholder="Confirm your password"
                                 required autocomplete="new-password" />
            </div>

            @if (Laravel\Jetstream\Jetstream::hasTermsAndPrivacyPolicyFeature())
                        <div class="mt-3">
                            <label class="flex items-start">
                                <x-checkbox name="terms" required />
                                <span class="ml-2 text-sm text-gray-600">
                                {!! __('I agree to the :terms_of_service and :privacy_policy', [
                                        'terms_of_service' => '<a target="_blank" href="'.route('terms.show').'" class="underline kdr-link">Terms</a>',
                                        'privacy_policy' => '<a target="_blank" href="'.route('policy.show').'" class="underline kdr-link">Privacy Policy</a>',
                                ]) !!}
                                </span>
                            </label>
                </div>
            @endif

                    <x-button class="kdr-btn kdr-btn-primary kdr-btn-block py-2.5">
                        Create Account
                    </x-button>

                    <a href="{{ route('login') }}" class="kdr-btn kdr-btn-outline kdr-btn-block mt-2">
                        Already have an account? Log in
                    </a>

                    <a href="{{ route('home') }}" class="kdr-btn kdr-btn-muted kdr-btn-block mt-2">
                        ← Back to Home
                    </a>
        </form>

    </x-authentication-card>
        </div>
    </div>

</x-guest-layout>

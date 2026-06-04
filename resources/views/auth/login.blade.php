<x-guest-layout>
    <div class="kdr-auth-page">
        <div class="kdr-auth-hero">
            <img src="{{ asset('storage/images/about') . (optional($about)->image1 ?? '') }}"
                 alt="{{ optional($setting)->company ?? 'Kigali Drive Rentals' }}">
            <div class="kdr-auth-hero__overlay"></div>
            <div class="kdr-auth-hero__content">
                <h2>Manage your listings with confidence</h2>
                <p class="mb-0 small opacity-90">Secure dashboard for cars, bookings, and availability.</p>
            </div>
        </div>

        <div class="kdr-auth-panel">
            <div class="kdr-auth-card">
                <h4 class="kdr-auth-card__title">Sign in to your account</h4>
                <p class="kdr-auth-card__subtitle">Log in to access your listings, calendars, and bookings.</p>

                <x-validation-errors class="mb-4" />

                @if (session('error'))
                    <div class="alert alert-danger py-2 small">{{ session('error') }}</div>
                @endif

                @if (session('status'))
                    <div class="alert alert-success py-2 small">{{ session('status') }}</div>
                @endif

                <form method="POST" action="{{ route('login') }}" class="space-y-4">
                    @csrf

                    <div>
                        <x-label for="email" value="Email Address" />
                        <x-input id="email" class="block mt-1 w-full" type="email" name="email"
                                 :value="old('email')" placeholder="example@mail.com" required autofocus />
                    </div>

                    <div>
                        <x-label for="password" value="Password" />
                        <x-input id="password" class="block mt-1 w-full" type="password" name="password"
                                 placeholder="Enter your password" required />
                    </div>

                    <div class="d-flex align-items-center justify-content-between flex-wrap gap-2 small">
                        <label class="d-flex align-items-center gap-2 mb-0">
                            <x-checkbox name="remember" />
                            <span class="text-muted">Remember me</span>
                        </label>
                        @if (Route::has('password.request'))
                            <a class="kdr-link" href="{{ route('password.request') }}">Forgot password?</a>
                        @endif
                    </div>

                    <x-button class="kdr-btn kdr-btn-primary kdr-btn-block py-2.5 mt-2">
                        Log in
                    </x-button>

                    <a href="{{ route('register') }}" class="kdr-btn kdr-btn-outline kdr-btn-block mt-2">
                        New here? Create an account
                    </a>

                    <a href="{{ route('home') }}" class="kdr-btn kdr-btn-muted kdr-btn-block mt-2">
                        ← Back to Home
                    </a>

                    <p class="text-center text-muted small mt-3 mb-0">
                        Browse our cars on the homepage.
                    </p>
                </form>
            </div>
        </div>
    </div>
</x-guest-layout>

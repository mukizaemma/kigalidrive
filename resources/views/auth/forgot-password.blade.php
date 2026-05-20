<x-guest-layout>
    <div class="kdr-auth-simple-wrap">
        <div class="kdr-auth-simple-card">
            <div class="text-center mb-4">
                <a href="{{ route('home') }}" class="d-inline-block mb-3">
                    <img src="{{ ($setting->logo ?? '') ? asset('storage/images/' . $setting->logo) : asset('assets/img/kdr-logo.png') }}" alt="{{ optional($setting)->company ?? 'Kigali Drive Rentals' }}" width="120">
                </a>
                <h2 class="kdr-auth-card__title">Reset your password</h2>
                <p class="kdr-auth-card__subtitle mb-0">Enter your email and we will send you a reset link.</p>
            </div>

            <x-validation-errors class="mb-4" />

            @if (session('status'))
                <div class="alert alert-success py-2 small mb-3">{{ session('status') }}</div>
            @endif

            <form method="POST" action="{{ route('password.email') }}" class="space-y-4">
                @csrf
                <div class="mb-3">
                    <label for="email" class="form-label">Email address</label>
                    <input id="email" name="email" type="email" required autofocus
                           class="form-control" placeholder="Enter your email" value="{{ old('email') }}">
                </div>
                <button type="submit" class="kdr-btn kdr-btn-primary kdr-btn-block w-100">
                    Send password reset link
                </button>
                <div class="text-center mt-3">
                    <a href="{{ route('login') }}" class="kdr-link small">Back to login</a>
                </div>
            </form>
        </div>
    </div>
</x-guest-layout>

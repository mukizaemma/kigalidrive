<x-guest-layout>
    <div class="kdr-auth-simple-wrap">
        <div class="kdr-auth-simple-card">
            <div class="text-center mb-4">
                <h2 class="kdr-auth-card__title">Set a new password</h2>
                <p class="kdr-auth-card__subtitle mb-0">Choose a strong password for your account.</p>
            </div>

            <x-validation-errors class="mb-4" />

            <form method="POST" action="{{ route('password.update') }}">
                @csrf
                <input type="hidden" name="token" value="{{ $request->route('token') }}">

                <div class="mb-3">
                    <label for="email" class="form-label">Email address</label>
                    <input id="email" name="email" type="email" required autofocus
                           class="form-control" value="{{ old('email', $request->email) }}">
                </div>
                <div class="mb-3">
                    <label for="password" class="form-label">New password</label>
                    <input id="password" name="password" type="password" required class="form-control">
                </div>
                <div class="mb-3">
                    <label for="password_confirmation" class="form-label">Confirm password</label>
                    <input id="password_confirmation" name="password_confirmation" type="password" required class="form-control">
                </div>

                <button type="submit" class="kdr-btn kdr-btn-primary kdr-btn-block w-100">Reset password</button>
                <div class="text-center mt-3">
                    <a href="{{ route('login') }}" class="kdr-link small">Back to login</a>
                </div>
            </form>
        </div>
    </div>
</x-guest-layout>

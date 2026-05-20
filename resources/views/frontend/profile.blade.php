@extends('layouts.frontbase')

@section('title', 'My profile | ' . (optional($setting)->company ?? 'Kigali Drive Rentals'))

@section('content')
<section class="py-5" style="background: var(--kdr-cream, #f8f6f2); min-height: 70vh;">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <h1 class="kdr-section-title mb-4">My profile</h1>

                @if(session('success'))
                    <div class="alert alert-success">{{ session('success') }}</div>
                @endif

                @if($errors->any())
                    <div class="alert alert-danger">
                        <ul class="mb-0">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
                    </div>
                @endif

                <div class="kdr-card p-4 mb-4">
                    <h2 class="h5 mb-3">Account information</h2>
                    <form method="POST" action="{{ route('my.profile.update') }}">
                        @csrf
                        @method('PUT')
                        <div class="mb-3">
                            <label for="name" class="form-label">Full name</label>
                            <input type="text" class="form-control" id="name" name="name" value="{{ old('name', $user->name) }}" required>
                        </div>
                        <div class="mb-3">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" class="form-control" id="email" name="email" value="{{ old('email', $user->email) }}" required>
                        </div>
                        <button type="submit" class="th-btn btn-kdr-primary">Save profile</button>
                    </form>
                </div>

                <div class="kdr-card p-4">
                    <h2 class="h5 mb-3">Change password</h2>
                    <form method="POST" action="{{ route('my.profile.password') }}">
                        @csrf
                        @method('PUT')
                        <div class="mb-3">
                            <label for="current_password" class="form-label">Current password</label>
                            <input type="password" class="form-control" id="current_password" name="current_password" required>
                        </div>
                        <div class="mb-3">
                            <label for="password" class="form-label">New password</label>
                            <input type="password" class="form-control" id="password" name="password" required>
                        </div>
                        <div class="mb-3">
                            <label for="password_confirmation" class="form-label">Confirm new password</label>
                            <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" required>
                        </div>
                        <button type="submit" class="th-btn btn-kdr-primary">Update password</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection

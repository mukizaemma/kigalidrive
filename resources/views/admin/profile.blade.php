@extends('layouts.adminBase')

@section('content')
@include('admin.includes.sidebar')

<div class="content">
    @include('admin.includes.navbar')

    <div class="container-fluid pt-4 px-4">
        <div class="bg-light rounded p-4">
            <h4 class="mb-4">My profile</h4>

            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show">{{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @if($errors->any())
                <div class="alert alert-danger alert-dismissible fade show">
                    <ul class="mb-0 ps-3">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            <div class="row g-4">
                <div class="col-lg-6">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-header bg-white">
                            <h6 class="mb-0">Account information</h6>
                        </div>
                        <div class="card-body">
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
                                    @if(!$user->hasVerifiedEmail())
                                        <small class="text-warning">Email not verified.</small>
                                    @endif
                                </div>
                                <button type="submit" class="btn btn-primary">Save profile</button>
                            </form>
                        </div>
                    </div>
                </div>

                <div class="col-lg-6">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-header bg-white">
                            <h6 class="mb-0">Change password</h6>
                        </div>
                        <div class="card-body">
                            <form method="POST" action="{{ route('my.profile.password') }}">
                                @csrf
                                @method('PUT')
                                <div class="mb-3">
                                    <label for="current_password" class="form-label">Current password</label>
                                    <input type="password" class="form-control" id="current_password" name="current_password" required autocomplete="current-password">
                                </div>
                                <div class="mb-3">
                                    <label for="password" class="form-label">New password</label>
                                    <input type="password" class="form-control" id="password" name="password" required autocomplete="new-password">
                                </div>
                                <div class="mb-3">
                                    <label for="password_confirmation" class="form-label">Confirm new password</label>
                                    <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" required autocomplete="new-password">
                                </div>
                                <button type="submit" class="btn btn-primary">Update password</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

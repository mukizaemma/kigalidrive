@extends('layouts.frontbase')

@section('content')
<div class="min-h-screen flex items-center justify-center bg-gray-100 py-12 px-4 sm:px-6 lg:px-8" style="padding: 80px 20px;">
    <div class="max-w-md w-full space-y-8 bg-white p-8 rounded-lg shadow-lg">
        <div class="text-center">
            <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full mb-4" style="background: rgba(197,160,89,0.15);">
                <i class="fas fa-envelope text-2xl" style="color: #c5a059;"></i>
            </div>
            <h2 class="text-3xl font-extrabold text-gray-900">
                Verify Your Email
            </h2>
            <p class="mt-2 text-sm text-gray-600">
                Before continuing, could you verify your email address by clicking on the link we just emailed to you?
                Accounts that stay unverified are removed automatically after {{ config('registration.verification_ttl_hours', 24) }} hours.
            </p>
            @if (auth()->check())
                <p class="mt-2 text-sm text-gray-500">
                    Email: <strong>{{ auth()->user()->email }}</strong>
                </p>
            @endif
        </div>

        @if (session('status') == 'verification-link-sent')
            <div class="mb-4 font-medium text-sm text-green-600 bg-green-50 p-4 rounded-lg">
                <i class="fas fa-check-circle me-2"></i>
                A new verification link has been sent to the email address you provided.
            </div>
        @endif

        @if (session('message'))
            <div class="mb-4 font-medium text-sm text-yellow-600 bg-yellow-50 p-4 rounded-lg">
                <i class="fas fa-exclamation-circle me-2"></i>
                {{ session('message') }}
            </div>
        @endif

        @if (session('error'))
            <div class="mb-4 font-medium text-sm text-red-600 bg-red-50 p-4 rounded-lg">
                <i class="fas fa-times-circle me-2"></i>
                {{ session('error') }}
            </div>
        @endif

        <div class="mt-6 space-y-4">
            <form method="POST" action="{{ route('verification.send') }}" id="resend-verification-form">
                @csrf
                <button type="submit" class="w-full flex justify-center py-3 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium" style="background: linear-gradient(135deg, #c5a059, #b8860b); color: #001f3f; font-weight: 700;">
                    <i class="fas fa-paper-plane me-2"></i>
                    Resend Verification Email
                </button>
            </form>

            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="w-full flex justify-center py-3 px-4 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50">
                    <i class="fas fa-sign-out-alt me-2"></i>
                    Log Out
                </button>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    $('#resend-verification-form').on('submit', function(e) {
        e.preventDefault();
        var $form = $(this);
        var $btn = $form.find('button[type="submit"]');
        var origText = $btn.html();
        $btn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin me-2"></i>Sending...');
        
        $.ajax({
            url: $form.attr('action'),
            method: 'POST',
            data: $form.serialize(),
            success: function(response) {
                $btn.prop('disabled', false).html(origText);
                if (response.message) {
                    alert(response.message);
                }
                location.reload();
            },
            error: function(xhr) {
                $btn.prop('disabled', false).html(origText);
                var msg = 'Failed to send verification email. Please try again.';
                if (xhr.responseJSON && xhr.responseJSON.message) {
                    msg = xhr.responseJSON.message;
                }
                alert(msg);
            }
        });
    });
});
</script>
@endpush
@endsection

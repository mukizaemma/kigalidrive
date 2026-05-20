{{-- Register popup form --}}
<div class="register-form-wrapper" style="margin-bottom: 0;">
    <form id="popup-register-form" method="POST" action="{{ route('register') }}" class="login-form">
        @csrf

        <div class="row">
            <div class="form-group col-12">
                <label for="name" class="form-label">
                    <i class="fas fa-user me-2"></i>Full Name
                </label>
                <input
                    id="name"
                    name="name"
                    type="text"
                    class="form-control form-control-lg @error('name') is-invalid @enderror"
                    value="{{ old('name') }}"
                    placeholder="Enter your full name"
                    required
                    autofocus
                >
                @error('name')
                    <div class="text-danger small mt-1">{{ $message }}</div>
                @enderror
                <div class="invalid-feedback d-none" id="name-error-ajax"></div>
            </div>

            <div class="form-group col-12">
                <label for="email" class="form-label">
                    <i class="fas fa-envelope me-2"></i>Email Address
                </label>
                <input
                    id="email"
                    name="email"
                    type="email"
                    class="form-control form-control-lg @error('email') is-invalid @enderror"
                    value="{{ old('email') }}"
                    placeholder="Enter your email"
                    required
                >
                @error('email')
                    <div class="text-danger small mt-1">{{ $message }}</div>
                @enderror
                <div class="invalid-feedback d-none" id="email-error-ajax"></div>
            </div>

            <div class="form-group col-12">
                <label for="password" class="form-label">
                    <i class="fas fa-lock me-2"></i>Password
                </label>
                <div class="password-input-wrapper">
                    <input
                        id="password"
                        name="password"
                        type="password"
                        class="form-control form-control-lg @error('password') is-invalid @enderror"
                        placeholder="Create a password"
                        required
                        autocomplete="new-password"
                    >
                    <button type="button" class="password-toggle" onclick="togglePassword('password')">
                        <i class="fas fa-eye" id="password-eye"></i>
                    </button>
                </div>
                @error('password')
                    <div class="text-danger small mt-1">{{ $message }}</div>
                @enderror
                <div class="invalid-feedback d-none" id="password-error-ajax"></div>
                <small class="text-muted">Must be at least 8 characters</small>
            </div>

            <div class="form-group col-12">
                <label for="password_confirmation" class="form-label">
                    <i class="fas fa-lock me-2"></i>Confirm Password
                </label>
                <div class="password-input-wrapper">
                    <input
                        id="password_confirmation"
                        name="password_confirmation"
                        type="password"
                        class="form-control form-control-lg"
                        placeholder="Confirm your password"
                        required
                        autocomplete="new-password"
                    >
                    <button type="button" class="password-toggle" onclick="togglePassword('password_confirmation')">
                        <i class="fas fa-eye" id="password_confirmation-eye"></i>
                    </button>
                </div>
            </div>

            @if (Laravel\Jetstream\Jetstream::hasTermsAndPrivacyPolicyFeature())
                <div class="form-group col-12">
                    <label class="form-check-label">
                        <input type="checkbox" name="terms" class="form-check-input" required>
                        <span class="ms-2">I agree to the <a href="{{ route('terms.show') }}" target="_blank" class="text-primary">Terms of Service</a> and <a href="{{ route('policy.show') }}" target="_blank" class="text-primary">Privacy Policy</a></span>
                    </label>
                </div>
            @endif

            <div class="form-btn mt-3 col-12">
                <button type="submit" id="register-submit" class="th-btn btn-fw th-radius2 w-100 btn-register">
                    <i class="fas fa-user-plus me-2"></i>Create Account
                </button>
            </div>

            <div class="col-12 mt-2">
                <p class="form-messages mb-0 text-center" id="register-message"></p>
            </div>
        </div>
    </form>
</div>

<style>
.register-form-wrapper {
    padding: 20px 0;
}

.register-form-wrapper .form-label {
    font-weight: 600;
    color: #333;
    margin-bottom: 8px;
    display: flex;
    align-items: center;
}

.register-form-wrapper .form-control-lg {
    padding: 12px 15px;
    border-radius: 8px;
    border: 2px solid #e0e0e0;
    transition: all 0.3s ease;
}

.register-form-wrapper .form-control-lg:focus {
    border-color: #25D366;
    box-shadow: 0 0 0 0.2rem rgba(37, 211, 102, 0.25);
}

.btn-register {
    background: linear-gradient(135deg, #25D366, #128C7E);
    border: none;
    padding: 12px 30px;
    font-weight: 600;
    transition: all 0.3s ease;
}

.btn-register:hover {
    transform: translateY(-2px);
    box-shadow: 0 5px 15px rgba(37, 211, 102, 0.4);
}
</style>

{{-- Optional AJAX submit (remove if you want normal non-AJAX submit) --}}
@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    if (typeof jQuery === 'undefined') return;

    $('#popup-register-form').on('submit', function (e) {
        e.preventDefault();

        $('#register-message').text('').removeClass('text-danger text-success');
        $('#name-error-ajax, #email-error-ajax, #password-error-ajax').text('').addClass('d-none');
        $('.is-invalid').removeClass('is-invalid');

        var $btn = $('#register-submit');
        var origText = $btn.html();
        $btn.prop('disabled', true).html('Signing up...');

        $.ajax({
            url: @json(route('register')),
            method: 'POST',
            data: $(this).serialize(),
            dataType: 'json',
        }).done(function (res, status, xhr) {
            // Show success message about email verification
            $('#register-message').addClass('text-success').html(
                '<i class="fas fa-check-circle me-2"></i>Registration successful! Please check your email to verify your account before logging in.'
            );
            // Clear form
            $('#popup-register-form')[0].reset();
            // Switch to login tab after 3 seconds
            setTimeout(function () {
                $('#pills-home-tab').click();
                $('#register-message').removeClass('text-success').text('');
            }, 3000);
        }).fail(function (xhr) {
            $btn.prop('disabled', false).html(origText);

            if (xhr.status === 422 && xhr.responseJSON) {
                var errors = xhr.responseJSON.errors || {};
                if (errors.name) {
                    $('#name').addClass('is-invalid');
                    $('#name-error-ajax').removeClass('d-none').text(errors.name.join(' '));
                }
                if (errors.email) {
                    $('#email').addClass('is-invalid');
                    $('#email-error-ajax').removeClass('d-none').text(errors.email.join(' '));
                }
                if (errors.password) {
                    $('#password').addClass('is-invalid');
                    $('#password-error-ajax').removeClass('d-none').text(errors.password.join(' '));
                }
                if (xhr.responseJSON.message) {
                    $('#register-message').addClass('text-danger').text(xhr.responseJSON.message);
                }
            } else {
                var msg = 'Registration failed. Please try again.';
                try {
                    var json = JSON.parse(xhr.responseText);
                    if (json.message) msg = json.message;
                } catch (e) {}
                $('#register-message').addClass('text-danger').text(msg);
            }
        });

        return false;
    });
});
</script>
@endpush

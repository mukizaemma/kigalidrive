<div class="login-form-wrapper">
    <form id="popup-login-form" method="POST" action="{{ route('login') }}" class="login-form" style="margin-bottom: 0;">
        @csrf

        <div class="row">
            <div class="form-group col-12">
                <label for="email" class="form-label">
                    <i class="fas fa-envelope me-2"></i>Email Address
                </label>
                <input
                    type="email"
                    class="form-control form-control-lg @error('email') is-invalid @enderror"
                    name="email"
                    id="email"
                    value="{{ old('email') }}"
                    placeholder="Enter your email"
                    required
                    autofocus
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
                        type="password"
                        class="form-control form-control-lg @error('password') is-invalid @enderror"
                        name="password"
                        id="password"
                        placeholder="Enter your password"
                        required
                        autocomplete="current-password"
                    >
                    <button type="button" class="password-toggle" onclick="togglePassword('password')">
                        <i class="fas fa-eye" id="password-eye"></i>
                    </button>
                </div>
                @error('password')
                    <div class="text-danger small mt-1">{{ $message }}</div>
                @enderror
                <div class="invalid-feedback d-none" id="password-error-ajax"></div>
            </div>

            <div class="form-group col-12 d-flex justify-content-between align-items-center mb-3">
                <label class="form-check-label">
                    <input type="checkbox" name="remember" class="form-check-input" id="remember" {{ old('remember') ? 'checked' : '' }}>
                    <span class="ms-2">Remember me</span>
                </label>
                <a href="javascript:void(0)" class="forgot-password-link" data-bs-toggle="modal" data-bs-target="#forgotPasswordModal">
                    Forgot password?
                </a>
            </div>

            <div class="form-btn mb-3 col-12">
                <button type="submit" class="th-btn btn-fw th-radius2 w-100 btn-login" id="login-submit">
                    <i class="fas fa-sign-in-alt me-2"></i>Sign In
                </button>
            </div>

            <div class="col-12">
                <p class="form-messages mb-0 mt-2 text-center" id="login-message"></p>
            </div>
        </div>
    </form>
</div>

<style>
.login-form-wrapper {
    padding: 20px 0;
}

.login-form .form-label {
    font-weight: 600;
    color: #333;
    margin-bottom: 8px;
    display: flex;
    align-items: center;
}

.login-form .form-control-lg {
    padding: 12px 15px;
    border-radius: 8px;
    border: 2px solid #e0e0e0;
    transition: all 0.3s ease;
}

.login-form .form-control-lg:focus {
    border-color: #25D366;
    box-shadow: 0 0 0 0.2rem rgba(37, 211, 102, 0.25);
}

.password-input-wrapper {
    position: relative;
}

.password-toggle {
    position: absolute;
    right: 15px;
    top: 50%;
    transform: translateY(-50%);
    background: none;
    border: none;
    color: #666;
    cursor: pointer;
    padding: 5px;
}

.password-toggle:hover {
    color: #25D366;
}

.forgot-password-link {
    color: #25D366;
    text-decoration: none;
    font-size: 14px;
    font-weight: 500;
    transition: all 0.3s ease;
}

.forgot-password-link:hover {
    color: #128C7E;
    text-decoration: underline;
}

.btn-login {
    background: linear-gradient(135deg, #25D366, #128C7E);
    border: none;
    padding: 12px 30px;
    font-weight: 600;
    transition: all 0.3s ease;
}

.btn-login:hover {
    transform: translateY(-2px);
    box-shadow: 0 5px 15px rgba(37, 211, 102, 0.4);
}

.form-messages {
    font-size: 14px;
    padding: 10px;
    border-radius: 6px;
}

.form-messages.text-danger {
    background-color: #fee;
    color: #c33;
}

.form-messages.text-success {
    background-color: #efe;
    color: #3c3;
}
</style>

<script>
function togglePassword(inputId) {
    const input = document.getElementById(inputId);
    const eye = document.getElementById(inputId + '-eye');
    if (input.type === 'password') {
        input.type = 'text';
        eye.classList.remove('fa-eye');
        eye.classList.add('fa-eye-slash');
    } else {
        input.type = 'password';
        eye.classList.remove('fa-eye-slash');
        eye.classList.add('fa-eye');
    }
}
</script>


            @push('scripts')
            <script>
            document.addEventListener('DOMContentLoaded', function () {
                // Only run AJAX if jQuery is present
                if (typeof jQuery === 'undefined') return;

                $('#popup-login-form').on('submit', function (e) {
                    e.preventDefault();

                    // clear previous messages
                    $('#login-message').text('');
                    $('#email-error-ajax, #password-error-ajax').text('').addClass('d-none');
                    $('.is-invalid').removeClass('is-invalid');

                    var $btn = $('#login-submit');
                    var origText = $btn.html();
                    $btn.prop('disabled', true).html('Signing in...');

                    $.ajax({
                        url: @json(route('login')),
                        method: 'POST',
                        data: $(this).serialize(),
                        dataType: 'json',
                    }).done(function (res) {
                        // Check if email verification is required
                        if (res.verified === false) {
                            // Email not verified - redirect to verification notice
                            var msg = res.message || 'Please verify your email address before logging in.';
                            $('#login-message').addClass('text-warning').html(
                                msg + ' <a href="' + (res.resend_url || '{{ route("verification.send") }}') + '" class="text-primary">Resend verification email</a>'
                            );
                            // Redirect to verification notice page
                            setTimeout(function() {
                                window.location.href = res.redirect || '{{ route("verification.notice") }}';
                            }, 2000);
                            return;
                        }
                        
                        // Email verified - proceed with normal redirect
                        var redirect = res.redirect || '{{ url()->previous() }}' || '{{ url('/') }}';
                        window.location.href = redirect;
                    }).fail(function (xhr) {
                        $btn.prop('disabled', false).html('<i class="fas fa-sign-in-alt me-2"></i>Sign In');

                        if (xhr.status === 403 && xhr.responseJSON && !xhr.responseJSON.verified) {
                            // Email not verified
                            var msg = xhr.responseJSON.message || 'Please verify your email address before logging in.';
                            $('#login-message').addClass('text-danger').html(
                                msg + ' <a href="' + (xhr.responseJSON.resend_url || '{{ route("verification.send") }}') + '" class="text-primary">Resend verification email</a>'
                            );
                        } else if (xhr.status === 422 && xhr.responseJSON) {
                            // validation errors
                            var errors = xhr.responseJSON.errors || {};
                            if (errors.email) {
                                $('#email').addClass('is-invalid');
                                $('#email-error-ajax').removeClass('d-none').text(errors.email.join(' '));
                            }
                            if (errors.password) {
                                $('#password').addClass('is-invalid');
                                $('#password-error-ajax').removeClass('d-none').text(errors.password.join(' '));
                            }
                            if (errors._error) {
                                $('#login-message').addClass('text-danger').text(errors._error.join ? errors._error.join(' ') : errors._error);
                            }
                        } else if (xhr.status === 429) {
                            $('#login-message').addClass('text-danger').text('Too many attempts. Please try again later.');
                        } else {
                            // Generic error
                            var msg = 'Login failed. Check credentials and try again.';
                            try {
                                var json = JSON.parse(xhr.responseText);
                                if (json.message) msg = json.message;
                            } catch (e) {}
                            $('#login-message').addClass('text-danger').text(msg);
                        }
                    });

                    return false;
                });
            });
            </script>
            @endpush
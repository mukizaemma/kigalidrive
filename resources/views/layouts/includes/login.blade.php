            <form id="popup-login-form" method="POST" action="{{ route('login') }}" class="login-form">
                @csrf

                <div class="row">
                    <div class="form-group col-12">
                        <label for="email">Username or email</label>
                        <input
                            type="text"
                            class="form-control @error('email') is-invalid @enderror"
                            name="email"
                            id="email"
                            value="{{ old('email') }}"
                            required
                            autofocus
                        >
                        @error('email')
                            <div class="text-danger small mt-1">{{ $message }}</div>
                        @enderror
                        <div class="invalid-feedback d-none" id="email-error-ajax"></div>
                    </div>

                    <div class="form-group col-12">
                        <label for="password">Password</label>
                        <input
                            type="password"
                            class="form-control @error('password') is-invalid @enderror"
                            name="password"
                            id="password"
                            required
                            autocomplete="current-password"
                        >
                        @error('password')
                            <div class="text-danger small mt-1">{{ $message }}</div>
                        @enderror
                        <div class="invalid-feedback d-none" id="password-error-ajax"></div>
                    </div>

                    <div class="form-group col-12 mb-2">
                        <label class="form-check">
                            <input type="checkbox" name="remember" class="form-check-input" id="remember" {{ old('remember') ? 'checked' : '' }}>
                            <span class="form-check-label ms-2">Remember me</span>
                        </label>
                    </div>

                    <div class="form-btn mb-20 col-12">
                        <button type="submit" class="th-btn btn-fw th-radius2 w-100" id="login-submit">
                            Log in
                        </button>
                    </div>

                    <div class="col-12">
                        @if (Route::has('password.request'))
                            <a href="{{ route('password.request') }}" id="forgot_url">Forgot your password?</a>
                        @endif
                    </div>

                    <div class="col-12">
                        <p class="form-messages mb-0 mt-3" id="login-message"></p>
                    </div>
                </div>
            </form>


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
                        // Laravel normally returns a redirect on success; we'll redirect client-side.
                        // If backend returns JSON success -> redirect to home or intended.
                        var redirect = res.redirect || '{{ url()->previous() }}' || '{{ url('/') }}';
                        window.location.href = redirect;
                    }).fail(function (xhr) {
                        $btn.prop('disabled', false).html(origText);

                        if (xhr.status === 422 && xhr.responseJSON) {
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
                            // Generic error (possible failed login - Laravel returns 302 redirect with errors for non-AJAX)
                            // Try to parse JSON response if any
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
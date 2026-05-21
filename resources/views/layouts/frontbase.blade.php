<!doctype html>
<html class="no-js" lang="zxx">

<head>
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <title>@yield('title', optional($setting)->company ?? 'Kigali Drive Rentals')</title>
    <meta name="author" content="Kigali Drive Rentals">
    <meta name="description" content="{{ optional($setting)->tagline ?? 'Premium car rentals and car sales in Kigali, Rwanda. Self-drive or with driver.' }}">
    <meta name="keywords" content="{{ optional($setting)->company ?? 'Kigali Drive Rentals' }}">
    <meta name="robots" content="INDEX,FOLLOW">

    <!-- Mobile Specific Metas -->
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    @php
        $favicon = optional($setting)->logo
            ? asset('storage/images/' . optional($setting)->logo)
            : asset('assets/img/favicons/apple-icon-180x180.png');
    @endphp

    <!-- Favicons - Place favicon.ico in the root directory -->
    <link rel="apple-touch-icon" sizes="57x57" href="{{ $favicon }}">
    <link rel="apple-touch-icon" sizes="60x60" href="{{ $favicon }}">
    <link rel="apple-touch-icon" sizes="72x72" href="{{ $favicon }}">
    <link rel="apple-touch-icon" sizes="76x76" href="{{ $favicon }}">
    <link rel="apple-touch-icon" sizes="114x114" href="{{ $favicon }}">
    <link rel="apple-touch-icon" sizes="120x120" href="{{ $favicon }}">
    <link rel="apple-touch-icon" sizes="144x144" href="{{ $favicon }}">
    <link rel="apple-touch-icon" sizes="152x152" href="{{ $favicon }}">
    <link rel="apple-touch-icon" sizes="180x180" href="{{ $favicon }}">
    <link rel="icon" type="image/png" sizes="192x192" href="{{ $favicon }}">
    <link rel="icon" type="image/png" sizes="32x32" href="{{ $favicon }}">
    <link rel="icon" type="image/png" sizes="96x96" href="{{ $favicon }}">
    <link rel="icon" type="image/png" sizes="16x16" href="{{ $favicon }}">
    <link rel="manifest" href="{{ asset('assets/img/favicons/manifest.json') }}">
    <meta name="msapplication-TileColor" content="#ffffff">
    <meta name="msapplication-TileImage" content="{{ $favicon }}">
    <meta name="theme-color" content="#ffffff">

    <!--==============================
	  Google Fonts
	============================== -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:ital,opsz,wght@0,9..40,100..1000&family=Outfit:wght@400..800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('assets/css/kigali-drive.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/kdr-auth-admin.css') }}">

    <!--==============================
	    All CSS File
	============================== -->
    <!-- Bootstrap -->
    <link rel="stylesheet" href="{{ asset('assets/css/bootstrap.min.css') }}">
    <!-- Fontawesome Icon -->
    <link rel="stylesheet" href="{{ asset('assets/css/fontawesome.min.css') }}">
    <!-- Magnific Popup -->
    <link rel="stylesheet" href="{{ asset('assets/css/magnific-popup.min.css') }}">

    <!-- Swiper css -->
    <link rel="stylesheet" href="{{ asset('assets/css/swiper-bundle.min.css') }}">

    <!-- Theme Custom CSS -->
    <link rel="stylesheet" href="{{ asset('assets/css/style.css') }}">
    
    @include('layouts.includes.site-footer-styles')
    @stack('styles')

</head>

<body class="kigali-drive">

    @if (session('success'))
        <script>
            document.addEventListener("DOMContentLoaded", function () {
                Swal.fire({
                    icon: 'success',
                    title: 'Success!',
                    text: '{{ session('success') }}',
                    confirmButtonColor: '#c5a059'
                });
            });
        </script>
    @endif

    @if (session('kdr_open_url'))
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                var externalUrl = @json(session('kdr_open_url'));
                if (externalUrl) {
                    var opened = window.open(externalUrl, '_blank', 'noopener,noreferrer');
                    if (!opened) {
                        window.location.href = externalUrl;
                    }
                }
            });
        </script>
    @endif

    @if (session('error'))
        <script>
            document.addEventListener("DOMContentLoaded", function () {
                Swal.fire({
                    icon: 'error',
                    title: 'Oops!',
                    text: '{{ session('error') }}',
                    confirmButtonColor: '#d33'
                });
            });
        </script>
    @endif



    <div class="sidemenu-wrapper sidemenu-info ">
        <div class="sidemenu-content">
            <button class="closeButton sideMenuCls"><i class="far fa-times"></i></button>
            <div class="widget  ">
                <div class="th-widget-about">
                    <div class="about-logo">
                        <a href="{{ route('home') }}"><img src="{{ asset('storage/images') . (optional($setting)->logo ?? '') }}" alt="{{ optional($setting)->company ?? 'Kigali Drive Rentals' }}"></a>
                    </div>
                    <p class="about-text">{{ optional($setting)->tagline ?? 'Premium car rentals and sales in Kigali, Rwanda.' }}</p>
                    <div class="th-social">
                        <a href="https://www.facebook.com/"><i class="fab fa-facebook-f"></i></a>
                        <a href="https://www.twitter.com/"><i class="fab fa-twitter"></i></a>
                        <a href="https://www.linkedin.com/"><i class="fab fa-linkedin-in"></i></a>
                        <a href="https://www.whatsapp.com/"><i class="fab fa-whatsapp"></i></a>
                    </div>
                </div>
            </div>
            <div class="widget">
                <h3 class="widget_title">Get In Touch</h3>
                <div class="th-widget-contact">
                    <div class="info-box_text">
                        <div class="icon">
                            <img src="{{ asset('assets/img/icon/phone.svg') }}" alt="img">
                        </div>
                        <div class="details">
                            <p><a href="tel:{{ $setting->phone ?? '' }}" class="info-box_link">{{ $setting->phone ?? '' }}</a></p>
                            <p><a href="tel:{{ $setting->phone1 ?? '' }}" class="info-box_link">{{ $setting->phone1 ?? '' }}</a></p>
                        </div>
                    </div>
                    <div class="info-box_text">
                        <div class="icon">
                            <img src="{{ asset('assets/img/icon/envelope.svg') }}" alt="img">
                        </div>
                        <div class="details">
                            <p><a href="mailto:{{ $setting->email ?? '' }}" class="info-box_link">{{ $setting->email ?? '' }}</a></p>
                            {{-- <p><a href="mailto:support24@Kigali Drive Rentals.com" class="info-box_link">support24@Kigali Drive Rentals.com</a></p> --}}
                        </div>
                    </div>
                    <div class="info-box_text">
                        <div class="icon"><img src="{{ asset('assets/img/icon/location-dot.svg') }}" alt="img"></div>
                        <div class="details">
                            <p>{{ $setting->address ?? '' }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="popup-search-box">
        <button class="searchClose"><i class="fal fa-times"></i></button>
        <form action="#">
            <input type="text" placeholder="What are you looking for?">
            <button type="submit"><i class="fal fa-search"></i></button>
        </form>
    </div>
    <!--==============================
    Mobile Menu
  ============================== -->
    <div class="th-menu-wrapper onepage-nav">
        <div class="th-menu-area text-center">
            <button class="th-menu-toggle"><i class="fal fa-times"></i></button>
            <div class="mobile-logo">
                <a href="{{ route('home') }}"><img src="{{ asset('storage/images') . (optional($setting)->logo ?? '') }}" alt="{{ optional($setting)->company ?? 'Kigali Drive Rentals' }}" width="120px"></a>
            </div>
            <div class="th-mobile-menu">
                <ul>

                    <li><a href="{{ route('home') }}">Home</a></li>
                    <li><a href="{{ route('showCars') }}">Cars</a></li>
                    <li><a href="{{ route('showCars', ['listing_type' => 'sale']) }}">Cars for Sale</a></li>
                    <li><a href="{{ route('services.index') }}">Services</a></li>
                    <li><a href="{{ route('about') }}">About</a></li>
                    <li><a href="{{ route('faq') }}">FAQ</a></li>
                    <li><a href="{{ route('blogs') }}">Updates</a></li>
                    <li><a href="{{ route('contact') }}">Contact</a></li>
                    <li><a href="{{ route('listYourProperty') }}">List your car</a></li>

                </ul>
            </div>
        </div>
    </div> <!--==============================
	Header Area
==============================-->
    <header class="th-header header-layout1 header-layout4 header-layout7">
        <div class="sticky-wrapper">
            <!-- Main Menu Area -->
            <div class="menu-area">
                <div class="container th-container">
                    <div class="row align-items-center">
                        <div class="col-auto">
                            <div class="header-logo">
                                <a href="{{ route('home') }}"><img src="{{ asset('storage/images') . (optional($setting)->logo ?? '') }}" alt="{{ optional($setting)->company ?? 'Kigali Drive Rentals' }}" width="150px"></a>
                            </div>
                        </div>
                        <div class="col d-flex justify-content-center d-none d-xl-flex">
                            <nav class="main-menu">
                                <ul>
                                    <li><a href="{{ route('home') }}" class="{{ request()->routeIs('home') ? 'active' : '' }}">Home</a></li>
                                    <li><a href="{{ route('showCars') }}" class="{{ request()->routeIs('showCars', 'carDetails') && request('listing_type') !== 'sale' ? 'active' : '' }}">Rent a Car</a></li>
                                    <li><a href="{{ route('showCars', ['listing_type' => 'sale']) }}" class="{{ request('listing_type') === 'sale' ? 'active' : '' }}">Buy a Car</a></li>
                                    <li><a href="{{ route('services.index') }}" class="{{ request()->routeIs('services.index', 'services.show') ? 'active' : '' }}">Services</a></li>
                                    <li><a href="{{ route('about') }}" class="{{ request()->routeIs('about') ? 'active' : '' }}">About</a></li>
                                    <li><a href="{{ route('faq') }}">FAQ</a></li>
                                    <li><a href="{{ route('blogs') }}">Updates</a></li>
                                    <li><a href="{{ route('contact') }}">Contact</a></li>
                                </ul>
                            </nav>
                        </div>
                        <div class="col-auto d-none d-xl-block">
                            <a href="{{ route('listYourProperty') }}" class="th-btn btn-kdr-primary btn-sm">
                                <i class="fas fa-plus-circle me-1"></i> List your car
                            </a>
                        </div>
                        <div class="col-auto ms-auto d-xl-none">
                            <button type="button" class="th-menu-toggle"><i class="far fa-bars"></i></button>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Search bar removed from header so it does not stick on scroll - see below (page-top search) --}}
        </div>
    </header>


    @if(request()->routeIs('apartments', 'hotel', 'unit.details'))
    <div class="global-search-bar-section bg-light border-bottom d-none d-lg-block">
        <div class="container th-container py-3">
            <form action="{{ route('apartments') }}" method="GET" class="global-search-bar bg-white rounded-3 shadow-sm border p-3">
                <div class="row g-2 align-items-center">
                    <div class="col-xl-3 col-lg-3 col-md-4">
                        <div class="position-relative">
                            <i class="fas fa-map-marker-alt position-absolute" style="left: 14px; top: 50%; transform: translateY(-50%); color: #888; font-size: 14px;"></i>
                            <input type="text" name="location" list="header-destinations" class="form-control form-control-sm" placeholder="Type destination, address or city — or choose All" value="{{ request('location') }}" autocomplete="off" style="padding-left: 38px; border-radius: 8px; height: 44px;">
                            <datalist id="header-destinations">
                                <option value="All">All Destinations</option>
                                @if(isset($searchLocations) && $searchLocations->isNotEmpty())
                                    @foreach($searchLocations as $loc)
                                        <option value="{{ $loc }}">{{ $loc }}</option>
                                    @endforeach
                                @endif
                            </datalist>
                        </div>
                    </div>
                    <div class="col-xl-2 col-lg-2 col-md-3">
                        <div class="position-relative">
                            <i class="fas fa-calendar-check position-absolute" style="left: 14px; top: 50%; transform: translateY(-50%); color: #888; font-size: 14px;"></i>
                            <input type="date" name="checkin" class="form-control form-control-sm" placeholder="Check-in" value="{{ request('checkin') }}" min="{{ date('Y-m-d') }}" style="padding-left: 38px; border-radius: 8px; height: 44px;">
                        </div>
                    </div>
                    <div class="col-xl-2 col-lg-2 col-md-3">
                        <div class="position-relative">
                            <i class="fas fa-calendar-times position-absolute" style="left: 14px; top: 50%; transform: translateY(-50%); color: #888; font-size: 14px;"></i>
                            <input type="date" name="checkout" class="form-control form-control-sm" placeholder="Check-out" value="{{ request('checkout') }}" min="{{ date('Y-m-d', strtotime('+1 day')) }}" style="padding-left: 38px; border-radius: 8px; height: 44px;">
                        </div>
                    </div>
                    <div class="col-xl-2 col-lg-2 col-md-3">
                        @include('frontend.partials.guests_rooms_selector', ['selectorId' => 'header-guests-rooms'])
                    </div>
                    <div class="col-xl-2 col-lg-2 col-md-4">
                        <button type="submit" class="btn btn-primary w-100" style="border-radius: 8px; height: 44px; font-weight: 600;">
                            <i class="fas fa-search me-2"></i>Search
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
    @endif



    <div class="container-fluid">

        @yield('content')
    </div>

    @include('frontend.partials.kdr-why-choose-us')
    
    @include('layouts.includes.site-footer')

    @stack('modals')

    <!--********************************
			Code End  Here 
	******************************** -->

    <!-- Scroll To Top -->
    <div class="scroll-top">
        <svg class="progress-circle svg-content" width="100%" height="100%" viewBox="-1 -1 102 102">
            <path d="M50,1 a49,49 0 0,1 0,98 a49,49 0 0,1 0,-98" style="transition: stroke-dashoffset 10ms linear 0s; stroke-dasharray: 307.919, 307.919; stroke-dashoffset: 307.919;">
            </path>
        </svg>
    </div>
    <!--==============================
modal Area  
==============================-->
    <div id="login-form" class="popup-login-register mfp-hide">
        <ul class="nav" id="pills-tab" role="tablist">
            <li class="nav-item" role="presentation">
                <button class="nav-menu active" id="pills-home-tab" data-bs-toggle="pill" data-bs-target="#pills-home" type="button" role="tab" aria-controls="pills-home" aria-selected="true">Login</button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-menu" id="pills-profile-tab" data-bs-toggle="pill" data-bs-target="#pills-profile" type="button" role="tab" aria-controls="pills-profile" aria-selected="false">Register</button>
            </li>
        </ul>
        <div class="tab-content" id="pills-tabContent">
            <div class="tab-pane fade active show" id="pills-home" role="tabpanel" aria-labelledby="pills-home-tab">
                <h3 class="box-title mb-30">Sign in to your account</h3>
                <div class="th-login-form">
                    @include('frontend.includes.login')
                </div>
                <div class="text-center mt-3">
                    <p class="mb-0">Don't have an account? <a href="javascript:void(0)" onclick="switchToRegister()" class="text-primary" style="text-decoration: none; font-weight: 600;">Register here</a></p>
                </div>
            </div>
            <div class="tab-pane fade" id="pills-profile" role="tabpanel" aria-labelledby="pills-profile-tab">
                <h3 class="th-form-title mb-30">Create your account</h3>
                <div class="th-register-form">
                    @include('frontend.includes.register')
                </div>
                <div class="text-center mt-3">
                    <p class="mb-0">Already have an account? <a href="javascript:void(0)" onclick="switchToLogin()" class="text-primary" style="text-decoration: none; font-weight: 600;">Login here</a></p>
                </div>
            </div>
        </div>
    </div>

    <script>
    function switchToRegister() {
        $('#pills-profile-tab').tab('show');
    }
    
    function switchToLogin() {
        $('#pills-home-tab').tab('show');
    }
    
    // Ensure login tab is active when modal opens
    $(document).on('click', '.popup-content', function() {
        setTimeout(function() {
            $('#pills-home-tab').tab('show');
        }, 100);
    });
    </script>

    <!--==============================
    Forgot Password Modal
==============================-->
    <div class="modal fade" id="forgotPasswordModal" tabindex="-1" aria-labelledby="forgotPasswordModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content" style="border-radius: 15px; border: none;">
                <div class="modal-header" style="border-bottom: 1px solid #e0e0e0; padding: 20px 25px;">
                    <h5 class="modal-title" id="forgotPasswordModalLabel" style="font-weight: 600; color: #333;">
                        <i class="fas fa-key me-2" style="color: #c5a059;"></i>Reset Password
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body" style="padding: 25px;">
                    <p class="text-muted mb-4">Enter your email address and we'll send you a link to reset your password.</p>
                    
                    <form id="forgot-password-form" method="POST" action="{{ route('password.email') }}">
                        @csrf
                        
                        <div class="form-group mb-4">
                            <label for="forgot_email" class="form-label" style="font-weight: 600;">
                                <i class="fas fa-envelope me-2"></i>Email Address
                            </label>
                            <input
                                type="email"
                                class="form-control form-control-lg"
                                id="forgot_email"
                                name="email"
                                placeholder="Enter your email"
                                required
                                style="border-radius: 8px; border: 2px solid #e0e0e0; padding: 12px 15px;"
                            >
                            <div class="invalid-feedback d-none" id="forgot-email-error"></div>
                        </div>

                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-primary btn-lg" id="forgot-submit-btn" style="background: linear-gradient(135deg, #c5a059, #b8860b); border: none; border-radius: 8px; padding: 12px; font-weight: 600; color: #001f3f;">
                                <i class="fas fa-paper-plane me-2"></i>Send Reset Link
                            </button>
                        </div>

                        <div class="mt-3">
                            <p class="form-messages mb-0 text-center" id="forgot-message"></p>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <style>
    #forgotPasswordModal .modal-content {
        box-shadow: 0 10px 40px rgba(0,0,0,0.1);
    }
    
    #forgotPasswordModal .form-control-lg:focus {
        border-color: #c5a059;
        box-shadow: 0 0 0 0.2rem rgba(37, 211, 102, 0.25);
    }
    
    #forgotPasswordModal .btn-primary:hover {
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(37, 211, 102, 0.4);
    }
    </style>

    <script>
    $(document).ready(function() {
        $('#forgot-password-form').on('submit', function(e) {
            e.preventDefault();
            
            var $btn = $('#forgot-submit-btn');
            var origText = $btn.html();
            $btn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin me-2"></i>Sending...');
            $('#forgot-message').text('').removeClass('text-danger text-success');
            $('#forgot_email').removeClass('is-invalid');
            
            $.ajax({
                url: $(this).attr('action'),
                method: 'POST',
                data: $(this).serialize(),
                dataType: 'json'
            }).done(function(res) {
                $('#forgot-message').addClass('text-success').html(
                    '<i class="fas fa-check-circle me-2"></i>Password reset link has been sent to your email address!'
                );
                $('#forgot-password-form')[0].reset();
                setTimeout(function() {
                    $('#forgotPasswordModal').modal('hide');
                }, 2000);
            }).fail(function(xhr) {
                $btn.prop('disabled', false).html(origText);
                
                if (xhr.status === 422 && xhr.responseJSON) {
                    var errors = xhr.responseJSON.errors || {};
                    if (errors.email) {
                        $('#forgot_email').addClass('is-invalid');
                        $('#forgot-email-error').removeClass('d-none').text(errors.email[0]);
                    }
                }
                
                var msg = xhr.responseJSON?.message || 'Unable to send reset link. Please try again.';
                $('#forgot-message').addClass('text-danger').text(msg);
            });
        });
    });
    </script>



    <!--==============================
    All Js File
============================== -->
    <!-- Jquery -->
    <!-- Jquery -->
    <script src="{{ asset('assets/js/vendor/jquery-3.6.0.min.js') }}"></script>
    <!-- Swiper Js -->
    <script src="{{ asset('assets/js/swiper-bundle.min.js') }}"></script>
    <!-- Bootstrap -->
    <script src="{{ asset('assets/js/bootstrap.min.js') }}"></script>
    <!-- Magnific Popup -->
    <script src="{{ asset('assets/js/jquery.magnific-popup.min.js') }}"></script>
    <!-- Counter Up -->
    <script src="{{ asset('assets/js/jquery.counterup.min.js') }}"></script>
    <!-- Range Slider -->
    <script src="{{ asset('assets/js/jquery-ui.min.js') }}"></script>
    <!-- imagesloaded -->
    <script src="{{ asset('assets/js/imagesloaded.pkgd.min.js') }}"></script>
    <!-- isotope -->
    <script src="{{ asset('assets/js/isotope.pkgd.min.js') }}"></script>
    <!-- gsap -->
    <script src="{{ asset('assets/js/gsap.min.js') }}"></script>

    <!-- circle-progress -->
    <script src="{{ asset('assets/js/circle-progress.js') }}"></script>

    <script src="{{ asset('assets/js/matter.min.js') }}"></script>
    <script src="{{ asset('assets/js/matterjs-custom.js') }}"></script>


    <!-- nice select -->
    <script src="{{ asset('assets/js/nice-select.min.js') }}"></script>

    <!-- Main Js File -->
    <script src="{{ asset('assets/js/main.js') }}"></script>
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
    (function () {
        function moveModalsToBody() {
            document.querySelectorAll('.modal').forEach(function (modal) {
                if (modal.parentElement !== document.body) {
                    document.body.appendChild(modal);
                }
            });
        }
        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', moveModalsToBody);
        } else {
            moveModalsToBody();
        }
        document.addEventListener('show.bs.modal', function (event) {
            var modal = event.target;
            if (modal && modal.classList && modal.classList.contains('modal') && modal.parentElement !== document.body) {
                document.body.appendChild(modal);
            }
        });
    })();
    </script>
    <script src="{{ asset('assets/js/kdr-channel-submit.js') }}" defer></script>
    <script src="{{ asset('assets/js/kdr-scroll-reveal.js') }}" defer></script>
    @stack('scripts')

</body>

</html>

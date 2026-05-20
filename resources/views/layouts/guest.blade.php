<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta name="csrf-token" content="{{ csrf_token() }}" />

    <title>{{ optional($setting)->company ?? config('app.name', 'Kigali Drive Rentals') }} — Account</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:ital,opsz,wght@0,9..40,100..1000&family=Outfit:wght@400..800&display=swap" rel="stylesheet">

    @php
        $viteManifest = public_path('build/manifest.json');
        $isAuthPage = request()->routeIs('login', 'register', 'password.*', 'two-factor.*');
    @endphp

    @if (file_exists($viteManifest))
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    @endif

    <link rel="stylesheet" href="{{ asset('assets/css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/fontawesome.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/kigali-drive.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/kdr-auth-admin.css') }}">

    @unless($isAuthPage)
        <link rel="stylesheet" href="{{ asset('assets/css/style.css') }}">
        @include('layouts.includes.site-footer-styles')
    @endunless

    @livewireStyles
</head>
<body class="antialiased {{ $isAuthPage ? 'kdr-auth' : '' }}">
    <div class="font-sans text-gray-900">
        {{ $slot }}
    </div>

    @unless($isAuthPage)
        @include('layouts.includes.site-footer')
    @endunless

    @livewireScripts

    <script src="{{ asset('assets/js/vendor/jquery-3.6.0.min.js') }}" defer></script>
    <script src="{{ asset('assets/js/bootstrap.min.js') }}" defer></script>
    @unless($isAuthPage)
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11" defer></script>
    @endunless
</body>
</html>

<!doctype html>
<html lang="en">
<head>
    @php
        $data = App\Models\Setting::first();
        $favicon = $data->logo
            ? asset('storage/images/' . $data->logo)
            : asset('assets/img/favicon.png');
    @endphp
    <base href="/public">
    <head>
        <meta charset="utf-8" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <title>{{ $data->company ?? '' }}</title>
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <!-- Use Minified Plugins Version For Fast Page Load -->
        <link rel="stylesheet" type="text/css" media="screen" href="css/plugins.css" />
        <link rel="stylesheet" type="text/css" media="screen" href="css/main.css" />
        <link rel="shortcut icon" type="image/x-icon" href="{{ $favicon }}">


        {{-- Gallery assests --}}
        <link href='assets/plugins/fontawesome-5.15.2/css/all.min.css' rel='stylesheet'>
        <link href='assets/plugins/fontawesome-5.15.2/css/fontawesome.min.css' rel='stylesheet'>
        <link href='assets/plugins/animate/animate.css' rel='stylesheet'>

        <link href='assets/plugins/fancybox/jquery.fancybox.min.css' rel='stylesheet'>
        <link href='assets/plugins/isotope/isotope.min.css' rel='stylesheet'>


        <!-- Fonts -->
        <link href="https://fonts.googleapis.com/css?family=Dosis:300,400,600,700|Open+Sans:300,400,600,700" rel="stylesheet">

        <!-- Custom css -->
        <link href="assets/css/kidz.css" id="option_style" rel="stylesheet">

        <!-- Favicon -->
        <link href="{{ $favicon }}" rel="shortcut icon">

        {{-- Same footer as main site (frontbase) --}}
        <link rel="stylesheet" href="{{ asset('assets/css/bootstrap.min.css') }}">
        <link rel="stylesheet" href="{{ asset('assets/css/style.css') }}">
        <link rel="stylesheet" href="{{ asset('assets/css/fontawesome.min.css') }}">
        @include('layouts.includes.site-footer-styles')

    </head>
    
    <body>



<div class="container-fluid">
    {{-- @show --}}
    @yield('content')
</div>


    @include('layouts.includes.site-footer')
    <!-- Use Minified Plugins Version For Fast Page Load -->
    <script src="js/plugins.js"></script>
    <script src="js/ajax-mail.js"></script>
    <script src="js/custom.js"></script>

    {{-- Gallery Scripts --}}

    <script src='assets/plugins/jquery/jquery.min.js'></script>
    <script src='assets/plugins/bootstrap/js/bootstrap.bundle.min.js'></script>

    <script src='assets/plugins/fancybox/jquery.fancybox.min.js'></script>
    <script src='assets/plugins/isotope/isotope.min.js'></script>
    <script src='assets/plugins/images-loaded/js/imagesloaded.pkgd.min.js'></script>

    <script src='assets/plugins/lazyestload/lazyestload.js'></script>
    <script src='assets/plugins/velocity/velocity.min.js'></script>
    <script src='assets/plugins/smoothscroll/SmoothScroll.js'></script>

</body>

</html>
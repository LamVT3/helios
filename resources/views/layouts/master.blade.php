<!DOCTYPE html>
<html lang="{{ config('app.locale') }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">

    <title>{{ $page_title != "" ? $page_title : "Trippy Admin" }}</title>
    <meta name="description" content="">
    <meta name="author" content="">

    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- Basic Styles -->
    <link rel="stylesheet" type="text/css" media="screen" href="{{ URL::to('css/bootstrap.min.css') }}">
    <link rel="stylesheet" type="text/css" media="screen" href="{{ URL::to('css/font-awesome.min.css') }}">

    <!-- SmartAdmin Styles : Caution! DO NOT change the order -->
    <link rel="stylesheet" type="text/css" media="screen"
          href="{{ URL::to('css/smartadmin-production-plugins.min.css') }}">
    <link rel="stylesheet" type="text/css" media="screen"
          href="{{ URL::to('css/smartadmin-production.min.css') }}">
    <link rel="stylesheet" type="text/css" media="screen" href="{{ URL::to('css/smartadmin-skins.min.css') }}">

    <!-- SmartAdmin RTL Support is under construction-->
    <link rel="stylesheet" type="text/css" media="screen" href="{{ URL::to('css/smartadmin-rtl.min.css') }}">

    @if(isset($page_css))
        @foreach ($page_css as $css)
            <link rel="stylesheet" type="text/css" media="screen" href="{{ URL::to('css/' . $css) }}">
        @endforeach
    @endif

    <link rel="stylesheet" type="text/css" media="screen" href="{{ URL::to('css/helios.css') }}">

    <!-- FAVICONS -->
    <link rel="shortcut icon" href="{{ URL::to('img/favicon.png') }}" type="image/x-icon">
    <link rel="icon" href="{{ URL::to('img/favicon.png') }}" type="image/x-icon">

    <!-- GOOGLE FONT -->
    <link rel="stylesheet" href="http://fonts.googleapis.com/css?family=Open+Sans:400italic,700italic,300,400,700">

    <!-- Specifying a Webpage Icon for Web Clip
         Ref: https://developer.apple.com/library/ios/documentation/AppleApplications/Reference/SafariWebContent/ConfiguringWebApplications/ConfiguringWebApplications.html -->
    <link rel="apple-touch-icon" href="{{ URL::to('img/splash/sptouch-icon-iphone.png') }}">
    <link rel="apple-touch-icon" sizes="76x76" href="{{ URL::to('img/splash/touch-icon-ipad.png') }}">
    <link rel="apple-touch-icon" sizes="120x120"
          href="{{ URL::to('img/splash/touch-icon-iphone-retina.png') }}">
    <link rel="apple-touch-icon" sizes="152x152" href="{{ URL::to('img/splash/touch-icon-ipad-retina.png') }}">

    <!-- iOS web-app metas : hides Safari UI Components and Changes Status Bar Appearance -->
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black">

    <!-- Startup image for web apps -->
    <link rel="apple-touch-startup-image" href="{{ URL::to('img/splash/ipad-landscape.png') }}"
          media="screen and (min-device-width: 481px) and (max-device-width: 1024px) and (orientation:landscape)">
    <link rel="apple-touch-startup-image" href="{{ URL::to('img/splash/ipad-portrait.png') }}"
          media="screen and (min-device-width: 481px) and (max-device-width: 1024px) and (orientation:portrait)">
    <link rel="apple-touch-startup-image" href="{{ URL::to('img/splash/iphone.png') }}"
          media="screen and (max-device-width: 320px)">

    <!-- Scripts -->
    <script>
        window.Laravel = {!! json_encode([
            'csrfToken' => csrf_token(),
        ]) !!};
    </script>
</head>
<body class="smart-style-4">

@if (!$no_main_header)
    @include('layouts.header')
    @include('components.nav')
@endif

@yield('content')

@include('layouts.footer')

@include('layouts.scripts')

@yield('script')

@auth
    @include('components.notify')
@endauth

<!-- Your GOOGLE ANALYTICS CODE Below -->

</body>
</html>

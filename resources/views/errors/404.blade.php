<!DOCTYPE html>
<html lang="{{ config('app.locale') }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">

    <title>Permission Denied</title>
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

    <!-- FAVICONS -->
    <link rel="shortcut icon" href="{{ URL::to('img/favicon/favicon.ico') }}" type="image/x-icon">
    <link rel="icon" href="{{ URL::to('img/favicon/favicon.ico') }}" type="image/x-icon">

    <!-- GOOGLE FONT -->
    <link rel="stylesheet" href="http://fonts.googleapis.com/css?family=Open+Sans:400italic,700italic,300,400,700">
<style>
    .full-height {
        height: 100vh;
    }

    .flex-center {
        align-items: center;
        display: flex;
        justify-content: center;
    }

    .position-ref {
        position: relative;
        background: rgba(0, 0, 0, 0.15);
        color: #fff;
    }

    .top-right {
        position: absolute;
        right: 10px;
        top: 18px;
    }

    .content {
        text-align: center;
    }

    .title {
        font-size: 84px;
        font-weight: bold;
        /* color: #fff; */
    }
</style>
</head>
<body class="smart-style-4">

        <!-- MAIN CONTENT -->
        <div class="flex-center position-ref full-height">
            <div class="content">
                <div class="title m-b-md">
                    Sorry! You've lost your way
                </div>
            </div>
        </div>
</body>
</html>
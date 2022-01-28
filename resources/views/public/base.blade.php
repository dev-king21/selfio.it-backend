<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Fav Icon  -->
    <link href="{{ asset('favicon.png') }}" rel="shortcut icon" type="image/png">
    <link href="{{ asset('favicon.png') }}" rel="icon" type="image/png">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title')</title>

    <!-- Font Awesome Icons -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    <link href="https://fonts.googleapis.com/css?family=Droid+Sans:400,700" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/baguettebox.js/1.8.1/baguetteBox.min.css">
    <link rel="stylesheet" href="{{ asset('css/gallery-grid.css') }}">


    <style>
        body, html {
            margin: 0;
            background-color: black;
            min-height: 100vh;
        }

        .flex-center {
            align-items: center;
            display: flex;
            justify-content: center;
            height: 100%;
        }

        img {
            max-width: 100%;
            max-height: 100vh;
            display: block;
        }

        .custom-inline-flex-grid {
            display: flex;
            flex-flow: row wrap;
            align-content: space-between;
            justify-content: center;
        }

        .download {
            position: absolute;
            bottom: 15px;
            right: 15px;
        }
    </style>
    @yield('style')
</head>
<body>
<div id="app" class="gallery-container flex-center">
    @yield('content')
</div>

<!-- Scripts -->

<!-- jQuery -->
<script src="{{asset('plugins/jquery/jquery.js')}}"></script>

<!-- Bootstrap 4 -->
<script src="{{asset('plugins/bootstrap/js/bootstrap.bundle.js')}}"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/baguettebox.js/1.8.1/baguetteBox.min.js"></script>

<script src="{{ asset('js/download.js') }}"></script>

@yield('script')
</body>
</html>

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

    <!-- Google Font: Source Sans Pro -->
    <link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700" rel="stylesheet">

    <!-- Font Awesome Icons -->
    <link rel="stylesheet" href="{{asset('plugins/fontawesome-free/css/all.min.css')}}">

    <!-- Datatable style -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.10.22/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/1.6.4/css/buttons.dataTables.min.css">

    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">

    <style>
        .form-inline {
            width: 100%;
        }

        th, td {
            text-align: center;
            vertical-align: middle;
        }

        .img_holder {
            height: 250px;
            width: 250px;
        }

        .img_holder:hover {
            box-shadow: 0 4px 8px 0 rgba(0, 0, 0, 0.2), 0 6px 20px 0 rgba(0, 0, 0, 0.2);
            transition: 0.2s;
        }

        .card-img {
            background-size: cover;
            background-position: center;
        }

        .card-img:hover {
            opacity: 0.7;
        }

        .img_holder img {
            width: 100%;
        }

        .event_menu li i {
            padding: 0 10px 0 0;
        }

        .event_menu li a {
            padding: 0 10px;
            display: block;
            text-decoration-line: none;
            color: rgba(0, 0, 0, 0.7);
        }

        .event_menu li a:hover {
            color: rgba(0, 0, 0, 1);
        }

        .event_menu li:hover {
            background-color: rgba(0, 0, 0, 0.05);
        }

        .card_selected {
            outline: 3px solid #1BA9BE;
        }

        .w-30 {
            width: 30%;
        }

        .btn {
            margin: 2px;
        }

        form.btn {
            margin: 0;
            padding: 0;
            border: unset;
        }

        .input-group ul li {
            text-align: center;
            width: 100%;
        }

        /* Tooltip container */
        ._tooltip {
            position: relative;
            display: inline-block;
            border-bottom: 1px dotted black; /* If you want dots under the hoverable text */
        }

        /* Tooltip text */
        ._tooltip .tooltiptext {
            visibility: hidden;
            width: 180px;
            background-color: #555;
            color: #fff;
            text-align: center;
            padding: 5px 0;
            border-radius: 6px;

            /* Position the tooltip text */
            position: absolute;
            z-index: 1;
            top: 125%;
            left: 50%;
            margin-left: -90px;

            /* Fade in tooltip */
            opacity: 0;
            transition: opacity 0.3s;
        }

        /* Tooltip arrow */
        ._tooltip .tooltiptext::after {
            content: "";
            position: absolute;
            bottom: 100%;
            left: 50%;
            margin-bottom: -5px;
            margin-left: -5px;
            border-width: 5px;
            border-style: solid;
            border-color: #555 transparent transparent transparent;
        }

        /* Show the tooltip text when you mouse over the tooltip container */
        ._tooltip:hover .tooltiptext {
            visibility: visible;
            opacity: 1;
        }

        @media (max-width: 720px) {
            .w-30 {
                width: 100% !important;
                justify-content: flex-start !important;
            }

            .w-30 + input {
                width: 100% !important;
            }
        }

        label {
            justify-content: center !important;
            align-items: center;
        }

        .hide {
            display: none !important;
        }

        .modal-body input {
            text-align: center !important;
        }

        .delete-form {
            justify-content: space-between;
            display: flex;
            flex: 0 0 30%;
        }
    </style>
    @yield('style')
</head>
<body>
<div id="app">
    <nav class="navbar navbar-expand-md navbar-light bg-white shadow-sm">
        <div class="container">
            <a class="navbar-brand" href="{{ url('/') }}">
                Welcome to {{ config('app.name', 'Laravel') }}
            </a>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent"
                    aria-controls="navbarSupportedContent" aria-expanded="false"
                    aria-label="{{ __('Toggle navigation') }}">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <!-- Left Side Of Navbar -->
                <ul class="navbar-nav mr-auto">

                </ul>

                <!-- Right Side Of Navbar -->
                <ul class="navbar-nav ml-auto">
                    <!-- Authentication Links -->
                    @if (Auth::guard('user')->guest())
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('user.login') }}">{{ __('Login') }}</a>
                        </li>
                        @if (Route::has('user.register'))
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('user.register') }}">{{ __('Register') }}</a>
                            </li>
                        @endif
                    @else
                        <li class="nav-item dropdown">
                            <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button"
                               data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                                {{ Auth::guard('user')->user()->name }} <span class="caret"></span>
                            </a>

                            <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
                                <a class="dropdown-item" href="{{ route('user.home') }}">
                                    {{ __('Home') }}
                                </a>
                                <a class="dropdown-item" href="{{ route('user.album') }}">
                                    {{ __('Album') }}
                                </a>
                                <a class="dropdown-item" href="{{ route('user.logout') }}"
                                   onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                                    {{ __('Logout') }}
                                </a>

                                <form id="logout-form" action="{{ route('user.logout') }}" method="POST"
                                      style="display: none;">
                                    @csrf
                                </form>
                            </div>
                        </li>
                    @endguest
                </ul>
            </div>
        </div>
    </nav>

    <main class="py-4">
        @yield('content')
    </main>
</div>

<!-- Scripts -->

<!-- jQuery -->
<script src="{{asset('plugins/jquery/jquery.js')}}"></script>

<!-- Bootstrap 4 -->
<script src="{{asset('plugins/bootstrap/js/bootstrap.bundle.js')}}"></script>

<!-- Datatable -->
<script src="{{asset('plugins/datatables/jquery.dataTables.min.js')}}"></script>
<script src="{{asset('plugins/datatables-buttons/js/dataTables.buttons.js')}}"></script>
<script src="{{asset('plugins/datatables-buttons/js/buttons.flash.js')}}"></script>
<script src="{{asset('plugins/datatables-buttons/js/buttons.html5.js')}}"></script>
<script src="{{asset('plugins/datatables-buttons/js/buttons.print.js')}}"></script>
<script src="{{asset('plugins/jszip/jszip.js')}}"></script>
<script src="{{asset('plugins/pdfmake/pdfmake.js')}}"></script>
<script src="{{asset('plugins/pdfmake/vfs_fonts.js')}}"></script>

<script src="{{ asset('js/download.js') }}"></script>

@yield('script')
</body>
</html>

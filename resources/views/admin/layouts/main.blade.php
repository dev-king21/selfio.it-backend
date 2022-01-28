<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Fav Icon  -->
    <link href="{{asset('favicon.png')}}" rel="shortcut icon" type="image/png">
    <link href="{{asset('favicon.png')}}" rel="icon" type="image/png">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name') }} | @yield('title')</title>

    <!-- Font Awesome Icons -->
    <link rel="stylesheet" href="{{asset('plugins/fontawesome-free/css/all.min.css')}}">

    <!-- Theme style -->
    <link rel="stylesheet" href="{{asset('dist/css/adminlte.min.css')}}">

    <!-- Datatable style -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.10.22/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/1.6.4/css/buttons.dataTables.min.css">

    <!-- Google Font: Source Sans Pro -->
    <link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700" rel="stylesheet">

    <style>
        .nav-item .select2 {
            width: 120px !important;
            height: 100% !important;
        }

        .select2-selection {
            padding: 0 !important;
        }

        th, td {
            text-align: center;
            vertical-align: middle;
        }

        .select2-search {
            display: none;
        }

        .img_holder {
            height: 120px;
            width: 120px;
        }

        .img_holder img {
            max-height: 100%;
            max-width: 100%;
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

        @media (min-width: 640px) {
            .modal-dialog {
                max-width: 600px;
            }
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
            justify-content: flex-end !important;
            align-items: center;
        }

        .loader {
            height: 100%;
            position: absolute;
            width: 100%;
            z-index: 1000;
            background: #fcfcfc url("http://www.mvgen.com/loader.gif") no-repeat scroll center center / 120px 120px;
            top: 0;
            left: 0;
            opacity: 0.5;
        }

    </style>
    @yield('style')
</head>
<body class="hold-transition sidebar-mini layout-fixed layout-navbar-fixed layout-footer-fixed">
<!-- Site wrapper -->
<div class="wrapper">
    <!-- Navbar -->
    <nav class="main-header navbar navbar-expand navbar-white navbar-light">
        <!-- Left navbar links -->
        <ul class="navbar-nav">
            <li class="nav-item">
                <a class="nav-link" data-widget="pushmenu" href="#"><i class="fa fa-bars"></i></a>
            </li>
        </ul>

        <!-- Right navbar links -->
        <ul class="navbar-nav ml-auto">
            <li class="nav-item">
                <a class="dropdown-item" href="{{ route('admin.logout') }}"
                   onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                    {{ __('Logout') }}
                </a>

                <form id="logout-form" action="{{ route('admin.logout') }}" method="POST" style="display: none;">
                    @csrf
                </form>
            </li>
        </ul>
    </nav>
    <!-- /.navbar -->

    <!-- Main Sidebar Container -->
    <aside class="main-sidebar sidebar-dark-primary elevation-4">
        <a href="{{route('admin.home')}}" class="brand-link">
            <img src="{{asset('favicon.png')}}" class="brand-image elevation-3" style="opacity: .8">
            <span class="brand-text font-weight-light">{{ config('app.name', 'Selfio Photo Booth') }}</span>
        </a>
    @php
        $url = Route::currentRouteName();
    @endphp

    <!-- Sidebar -->
        <div class="sidebar">
            <!-- Sidebar Menu -->
            <nav class="mt-2">
                <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu"
                    data-accordion="false">
                    <!-- Add icons to the links using the .nav-icon class
                         with font-awesome or any other icon font library -->
                    <li class="nav-item">
                        <a href="{{ route('admin.users') }}" class="nav-link @if($url==='admin.users') active @endif">
                            <i class="nav-icon fa fa-users"></i>
                            <p>{{ __('Users') }}</p>
                        </a>
                    </li>

                    <li class="nav-item">
                        <a href="{{ route('admin.events') }}"
                           class="nav-link @if($url==='admin.events') active @endif">
                            <i class="nav-icon fa fa-list-alt"></i>
                            <p>{{ __('Events') }}</p>
                        </a>
                    </li>

                    <li class="nav-item">
                        <a href="{{ route('admin.devices') }}"
                           class="nav-link @if($url==='admin.devices') active @endif">
                            <i class="nav-icon fa fa-mobile-alt"></i>
                            <p>{{ __('Devices') }}</p>
                        </a>
                    </li>

                    <li class="nav-item">
                        <a href="{{ route('admin.plans') }}"
                           class="nav-link @if($url==='admin.plans') active @endif">
                            <i class="nav-icon fa fa-shopping-cart"></i>
                            <p>{{ __('Plans') }}</p>
                        </a>
                    </li>

                    <li class="nav-item">
                        <a href="{{ route('admin.payments') }}"
                           class="nav-link @if($url==='admin.payments') active @endif">
                            <i class="nav-icon fa fa-receipt"></i>
                            <p>{{ __('Payments') }}</p>
                        </a>
                    </li>

                    {{--                    <li class="nav-item has-treeview @if(in_array($url, ['password', 'note', 'feedback', 'background', 'color'])) menu-open @endif">--}}
                    {{--                        <a href=""--}}
                    {{--                           class="nav-link @if(in_array($url, ['password', 'note', 'feedback', 'background', 'color'])) active @endif">--}}
                    {{--                            <i class="nav-icon fa fa-cog"></i>--}}
                    {{--                            <p>--}}
                    {{--                                {{ __('admin.settings') }}--}}
                    {{--                                <i class="right fa fa-angle-left"></i>--}}
                    {{--                            </p>--}}
                    {{--                        </a>--}}
                    {{--                        <ul class="nav nav-treeview" style="padding-left: 10px;">--}}
                    {{--                            <li class="nav-item">--}}
                    {{--                                <a href="{{ route('password') }}" class="nav-link @if($url==='password') active @endif">--}}
                    {{--                                    <i class="nav-icon fa fa-key"></i>--}}
                    {{--                                    <p>{{ __('admin.password') }}</p>--}}
                    {{--                                </a>--}}
                    {{--                            </li>--}}
                    {{--                            <li class="nav-item">--}}
                    {{--                                <a href="{{ route('note') }}" class="nav-link @if($url==='note') active @endif">--}}
                    {{--                                    <i class="nav-icon fa fa-sticky-note-o"></i>--}}
                    {{--                                    <p>{{ __('admin.note') }}</p>--}}
                    {{--                                </a>--}}
                    {{--                            </li>--}}
                    {{--                            <li class="nav-item">--}}
                    {{--                                <a href="{{ route('feedback') }}" class="nav-link @if($url==='feedback') active @endif">--}}
                    {{--                                    <i class="nav-icon fa fa-comments-o"></i>--}}
                    {{--                                    <p>{{ __('admin.feedback') }}</p>--}}
                    {{--                                </a>--}}
                    {{--                            </li>--}}
                    {{--                            <li class="nav-item">--}}
                    {{--                                <a href="{{ route('background') }}"--}}
                    {{--                                   class="nav-link @if($url==='background') active @endif">--}}
                    {{--                                    <i class="nav-icon fa fa-picture-o"></i>--}}
                    {{--                                    <p>{{ __('admin.background') }}</p>--}}
                    {{--                                </a>--}}
                    {{--                            </li>--}}
                    {{--                            <li class="nav-item">--}}
                    {{--                                <a href="{{ route('color') }}"--}}
                    {{--                                   class="nav-link @if($url==='color') active @endif">--}}
                    {{--                                    <i class="nav-icon fa fa-tachometer"></i>--}}
                    {{--                                    <p>{{ __('admin.color') }}</p>--}}
                    {{--                                </a>--}}
                    {{--                            </li>--}}
                    {{--                        </ul>--}}
                    {{--                    </li>--}}
                </ul>
            </nav>
            <!-- /.sidebar-menu -->
        </div>
        <!-- /.sidebar -->
    </aside>

    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1>@yield('title')</h1>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="{{route('admin.home')}}">{{config('app.name')}}</a>
                            </li>
                            <li class="breadcrumb-item active">@yield('title')</li>
                        </ol>
                    </div>
                </div>
            </div><!-- /.container-fluid -->
        </section>
        @yield('content')
    </div>
    <!-- /.content-wrapper -->

    <!-- Control Sidebar -->
    <aside class="control-sidebar control-sidebar-dark">
        <!-- Control sidebar content goes here -->
    </aside>
    <!-- /.control-sidebar -->

    <!-- Main Footer -->
    <footer class="main-footer">
        <strong>{{ __('Copyright') }} &copy; 2020-2020 <a href="{{ url('/') }}">Selfio.it</a>.</strong>
        {{ __('All rights reserved.') }}
        <div class="float-right d-none d-sm-inline-block">
            <b>{{ __('Version') }}</b> 1.0.0
        </div>
    </footer>
</div>
<!-- ./wrapper -->

<!-- jQuery -->
<script src="{{asset('plugins/jquery/jquery.js')}}"></script>
<!-- Bootstrap 4 -->
<script src="{{asset('plugins/bootstrap/js/bootstrap.bundle.js')}}"></script>
<!-- AdminLTE App -->
<script src="{{asset('dist/js/adminlte.min.js')}}"></script>
<!-- ChartJS -->
<script src="{{asset('plugins/chart.js/Chart.min.js')}}"></script>

<!-- Datatable -->
<script src="{{asset('plugins/datatables/jquery.dataTables.min.js')}}"></script>
<script src="{{asset('plugins/datatables-buttons/js/dataTables.buttons.js')}}"></script>
<script src="{{asset('plugins/datatables-buttons/js/buttons.flash.js')}}"></script>
<script src="{{asset('plugins/datatables-buttons/js/buttons.html5.js')}}"></script>
<script src="{{asset('plugins/datatables-buttons/js/buttons.print.js')}}"></script>
<script src="{{asset('plugins/jszip/jszip.js')}}"></script>
<script src="{{asset('plugins/pdfmake/pdfmake.js')}}"></script>
<script src="{{asset('plugins/pdfmake/vfs_fonts.js')}}"></script>

@yield('script')
</body>
</html>

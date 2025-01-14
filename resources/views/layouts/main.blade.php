<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <title>SIPMI | @yield('title' ?? '')</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta content="A fully featured admin theme which can be used to build CRM, CMS, etc." name="description">
    <meta content="Coderthemes" name="author">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- App favicon -->
    <link rel="shortcut icon" href="{{ asset('design-sistem/assets/images/favicon.ico') }}">
    <!-- App css -->
    <link href="{{ asset('design-sistem/assets/css/icons.min.css') }}" rel="stylesheet" type="text/css">
    <link href="{{ asset('design-sistem/assets/css/app.min.css') }}" rel="stylesheet" type="text/css" id="light-style">
    <link href="{{ asset('design-sistem/assets/css/app-dark.min.css') }}" rel="stylesheet" type="text/css"
        id="dark-style">
    {{-- toast --}}
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/toastify-js/src/toastify.min.css">
    <!-- Datatables css -->
    <link href="{{ asset('design-sistem/assets/css/vendor/dataTables.bootstrap5.css') }}" rel="stylesheet"
        type="text/css" />
    <link href="{{ asset('design-sistem/assets/css/vendor/responsive.bootstrap5.css') }}" rel="stylesheet"
        type="text/css" />
    {{-- datepicker --}}
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    @yield('style')

</head>

<body class="loading"
    data-layout-config='{"leftSideBarTheme":"dark","layoutBoxed":false, "leftSidebarCondensed":false, "leftSidebarScrollable":false,"darkMode":false, "showRightSidebarOnStart": true}'>
    <!-- Begin page -->
    <div class="wrapper">
        <!-- ========== Left Sidebar Start ========== -->
        <div class="leftside-menu">

            <!-- LOGO -->
            <a href="index.html" class="logo text-center logo-light">
                <span class="logo-lg">
                    <img src="{{ asset('design-sistem/assets/images/logo.png') }}" alt="" height="16">
                </span>
                <span class="logo-sm">
                    <img src="{{ asset('design-sistem/assets/images/logo_sm.png') }}" alt="" height="16">
                </span>
            </a>

            <!-- LOGO -->
            <a href="index.html" class="logo text-center logo-dark">
                <span class="logo-lg">
                    <img src="{{ asset('design-sistem/assets/images/logo-dark.png') }}" alt="" height="16">
                </span>
                <span class="logo-sm">
                    <img src="{{ asset('design-sistem/assets/images/logo_sm_dark.png') }}" alt=""
                        height="16">
                </span>
            </a>

            <div class="h-100" id="leftside-menu-container" data-simplebar="">

                <!--- Sidemenu -->
                @include('layouts.sidebar')

                <!-- Help Box -->
                <div class="help-box text-white text-center">
                    <a href="javascript: void(0);" class="float-end close-btn text-white">
                        <i class="mdi mdi-close"></i>
                    </a>
                    <img src="{{ asset('design-sistem/assets/images/help-icon.svg') }}" height="90"
                        alt="Helper Icon Image">
                    <h5 class="mt-3">Unlimited Access</h5>
                    <p class="mb-3">Upgrade to plan to get access to unlimited reports</p>
                    <a href="javascript: void(0);" class="btn btn-outline-light btn-sm">Upgrade</a>
                </div>
                <!-- end Help Box -->
                <!-- End Sidebar -->

                <div class="clearfix"></div>

            </div>
            <!-- Sidebar -left -->

        </div>
        <!-- Left Sidebar End -->

        <!-- ============================================================== -->
        <!-- Start Page Content here -->
        <!-- ============================================================== -->

        <div class="content-page">
            <div class="content">
                <!-- Topbar Start -->
                @include('layouts.header')
                <!-- end Topbar -->

                <!-- Start Content-->
                <div class="container-fluid">

                    <!-- start page title -->
                    <div class="row">
                        <div class="col-12">
                            <div class="page-title-box">

                                @yield('content')
                            </div>
                        </div>
                    </div>
                    <!-- end page title -->

                </div> <!-- container -->

            </div> <!-- content -->

            @include('layouts.footer')

        </div>

        <!-- ============================================================== -->
        <!-- End Page content -->
        <!-- ============================================================== -->


    </div>
    <!-- END wrapper -->


    {{-- jquery --}}
    <script src="https://code.jquery.com/jquery-3.6.1.min.js"></script>
    <!-- bundle -->
    <script src="{{ asset('design-sistem/assets/js/vendor.min.js') }}"></script>
    <script src="{{ asset('design-sistem/assets/js/app.min.js') }}"></script>
    <!-- Datatables js -->
    <script src="{{ asset('design-sistem/assets/js/vendor/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('design-sistem/assets/js/vendor/dataTables.bootstrap5.js') }}"></script>
    <script src="{{ asset('design-sistem/assets/js/vendor/dataTables.responsive.min.js') }}"></script>
    <script src="{{ asset('design-sistem/assets/js/vendor/responsive.bootstrap5.min.js') }}"></script>
    {{-- custom js --}}
    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/toastify-js"></script>
    <script src="{{ asset('js/custom.js') }}"></script>
    {{-- select2 --}}
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <!-- Datatable Init js -->
    <script src="{{ asset('design-sistem/assets/js/pages/demo.datatable-init.js') }}"></script>
    {{-- datepicker --}}
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    @yield('script')

</body>

</html>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <title>Log In | SIPMI </title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta content="A fully featured admin theme which can be used to build CRM, CMS, etc." name="description" />
    <meta content="Coderthemes" name="author" />
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- App favicon -->
    <link rel="shortcut icon" href="{{ asset('design-sistem/assets/images/favicon.ico') }}">

    <!-- App css -->
    <link href="{{ asset('design-sistem/assets/css/icons.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('design-sistem/assets/css/app.min.css') }}" rel="stylesheet" type="text/css"
        id="light-style" />
    <link href="{{ asset('design-sistem/assets/css/app-dark.min.css') }}" rel="stylesheet" type="text/css"
        id="dark-style" />
    {{-- toast --}}
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/toastify-js/src/toastify.min.css">
</head>

<body class="loading authentication-bg"
    data-layout-config='{"leftSideBarTheme":"dark","layoutBoxed":false, "leftSidebarCondensed":false, "leftSidebarScrollable":false,"darkMode":false, "showRightSidebarOnStart": true}'>
    <div class="account-pages pt-2 pt-sm-5 pb-4 pb-sm-5">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-xxl-4 col-lg-5">
                    <div class="card">

                        <!-- Logo -->
                        <div class="card-header pt-4 pb-4 text-center bg-primary">
                            <a href="index.html">
                                <span><img src="{{ asset('design-sistem/assets/images/logo.png') }}" alt=""
                                        height="18"></span>
                            </a>
                        </div>

                        <div class="card-body p-4">

                            <div class="text-center w-75 m-auto">
                                <h4 class="text-dark-50 text-center pb-0 fw-bold">Sign In</h4>
                                <p class="text-muted mb-4">Enter your email address and password to access admin panel.
                                </p>
                            </div>

                            <form action="{{ route('login.post') }}" method="POST" id="handleLogin">
                                @csrf
                                @method('POST')
                                <div class="mb-3">
                                    <label for="email" class="form-label">Email address</label>
                                    <input class="form-control" type="email" id="email" name="email"
                                        placeholder="Enter your email">
                                </div>

                                <div class="mb-3">
                                    <label for="password" class="form-label">Password</label>
                                    <div class="input-group input-group-merge">
                                        <input type="password" id="password" name="password" class="form-control"
                                            placeholder="Enter your password">
                                        <div class="input-group-text position-absolute end-0" data-password="false">
                                            <span class="password-eye"></span>
                                        </div>
                                    </div>
                                </div>

                                <div class="mb-1 mt-2 text-center">
                                    <button class="btn btn-primary w-50" type="submit"> Log In </button>
                                </div>

                            </form>
                        </div> <!-- end card-body -->
                    </div>
                    <!-- end card -->

                </div> <!-- end col -->
            </div>
            <!-- end row -->
        </div>
        <!-- end container -->
    </div>
    <!-- end page -->

    <footer class="footer footer-alt">
        2018 - 2021 © Hyper - Coderthemes.com
    </footer>

    <!-- bundle -->
    <script src="{{ asset('design-sistem/assets/js/vendor.min.js') }}"></script>
    <script src="{{ asset('design-sistem/assets/js/app.min.js') }}"></script>
    {{-- jquery --}}
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"
        integrity="sha512-v2CJ7UaYy4JwqLDIrZUI/4hqeoQieOmAZNXBeQyjo21dadnwR+8ZaIJVT8EE2iyI61OV8e6M8PP2/4hpQINQ/g=="
        crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    {{-- toast --}}
    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/toastify-js"></script>
    <script src="{{ asset('js/custom.js') }}"></script>

    <script>
        $(function() {

            $(document).ready(function() {
                $("#handleLogin").submit(function(e) {
                    e.preventDefault();
                    var form = $(this);
                    $(this).find("[type='submit']").html(`
                                <div class="spinner-border spinner-border-sm" role="status">
                                    <span class="visually-hidden">Loading...</span>
                                </div> Loading...                 
                            `);
                    $.ajax({
                        url: $(this).attr('action'),
                        data: $(this).serialize(),
                        type: "POST",
                        dataType: 'json',
                        success: function(data) {
                            $(form).find("[type='submit']").html("Login");
                            if (data.status) {
                                window.location = data.redirect;
                            } else {
                                loopErrors(data.errors);
                                $('input-group-text').attr('type', 'hidden');
                            }
                        }

                    });
                    return false;
                });

            });

        });
    </script>
</body>

</html>

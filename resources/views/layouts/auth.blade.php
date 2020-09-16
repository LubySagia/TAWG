<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'TAWG') }}</title>

    <!-- Scripts -->
    <script src="{{ asset('js/admin.js') }}" defer></script>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Roboto&display=swap" rel="stylesheet">
    <!--end::Fonts -->

    <!--begin::Global Theme Styles(used by all pages) -->
    <link href="{{ asset('assets/admin/css/pages/login/login-1.css')}}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('assets/admin/css/style.bundle.css')}}" rel="stylesheet" type="text/css" />

    <!--end::Global Theme Styles -->

    <!--begin::Layout Skins(used by all pages) -->
    <link href="{{ asset('assets/admin/css/skins/header/base/light.css')}}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('assets/admin/css/skins/header/menu/light.css')}}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('assets/admin/css/skins/brand/dark.css')}}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('assets/admin/css/skins/aside/dark.css')}}" rel="stylesheet" type="text/css" />

    <!--end::Layout Skins -->
    <link rel="shortcut icon" href="{{ asset('assets/admin/media/logos/favicon.ico')}}" />
    <!-- Styles -->
    <link href="{{ asset('css/admin.css') }}" rel="stylesheet">
</head>
<body class="kt-quick-panel--right kt-demo-panel--right kt-offcanvas-panel--right kt-header--fixed kt-header-mobile--fixed kt-subheader--enabled kt-subheader--fixed kt-subheader--solid kt-aside--enabled kt-aside--fixed kt-page--loading">

        <!-- begin:: Page -->
        <div class="kt-grid kt-grid--ver kt-grid--root">
            <div class="kt-grid kt-grid--hor kt-grid--root  kt-login kt-login--v1" id="kt_login">
                <div class="kt-grid__item kt-grid__item--fluid kt-grid kt-grid--desktop kt-grid--ver-desktop kt-grid--hor-tablet-and-mobile">

                    <!--begin::Aside-->
                    <div class="kt-grid__item kt-grid__item--order-tablet-and-mobile-2 kt-grid kt-grid--hor kt-login__aside" style="background-image: url({{ asset('assets/admin/media/bg/bg-4.jpg)')}};">
                        <div class="kt-grid__item">
                            <a href="#" class="kt-login__logo">
                                <img src="{{ asset('assets/admin/media/logos/logo.png')}}">
                            </a>
                        </div>
                        <div class="kt-grid__item kt-grid__item--fluid kt-grid kt-grid--ver">
                            <div class="kt-grid__item kt-grid__item--middle">
                                <h3 class="kt-login__title">TAWG Admin</h3>
                                <h4 class="kt-login__subtitle">Chào mừng đến với trang quản trị TAWG</h4>
                            </div>
                        </div>
                        <div class="kt-grid__item">
                            <div class="kt-login__info">
                                <div class="kt-login__copyright">
                                    &copy 2020 @if(date('Y')>2020) - {{date('Y')}}@endif TAWG
                                </div>
                            </div>
                        </div>
                    </div>

                    <!--begin::Aside-->

                    <!--begin::Content-->
                    <div class="kt-grid__item kt-grid__item--fluid  kt-grid__item--order-tablet-and-mobile-1  kt-login__wrapper">

                        <!--begin::Body-->
                        <div class="kt-login__body">

                            <!--begin::Signin-->
                            <div class="kt-login__form">
                                <div class="kt-login__title">
                                    <h3>Vui lòng nhập thông tin tài khoản</h3>
                                </div>

                                <!--begin::Form-->
                                <form class="kt-form" method="POST" action="{{ route('login') }}" novalidate="novalidate" id="kt_login_form">
                                    {{ csrf_field() }}
                                    <div class="form-group">
                                        <input class="form-control @error('email') is-invalid @enderror" type="text" placeholder="Địa chỉ email" name="email" autocomplete="off" {{ old('email') }}>
                                        @error('email')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                    <div class="form-group">
                                        <input class="form-control @error('password') is-invalid @enderror" type="password" placeholder="Mật Khẩu" name="password" autocomplete="off" value="{{ old('password') }}">
                                        @error('password')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                    
                                    <!--begin::Action-->
                                    <div class="kt-login__actions">
                                        <button id="kt_login_signin_submit" class="btn btn-primary btn-elevate kt-login__btn-primary text-right">Đăng Nhập</button>
                                    </div>
                                    @if (session('success'))
                                      <div class="form-group-default">
                                        <div class="alert alert-success  alert-dismissible margin-top-5" role="alert"><strong> {{ session('success') }}.</strong> </div>
                                      </div>
                                    @endif
                                    @if (session('error'))
                                      <div class="form-group-default">
                                       <div class="alert alert-danger  alert-dismissible margin-top-5" role="alert"><strong> {{ session('error') }}.</strong></div>
                                       </div>
                                    @endif
                                    <!--end::Action-->
                                </form>

                                <!--end::Form-->

                            </div>

                            <!--end::Signin-->
                        </div>

                        <!--end::Body-->
                    </div>

                    <!--end::Content-->
                </div>
            </div>
        </div>

        <!-- end:: Page -->

        <!-- begin::Global Config(global config for global JS sciprts) -->
        <script>
            var KTAppOptions = {
                "colors": {
                    "state": {
                        "brand": "#5d78ff",
                        "dark": "#282a3c",
                        "light": "#ffffff",
                        "primary": "#5867dd",
                        "success": "#34bfa3",
                        "info": "#36a3f7",
                        "warning": "#ffb822",
                        "danger": "#fd3995"
                    },
                    "base": {
                        "label": [
                            "#c5cbe3",
                            "#a1a8c3",
                            "#3d4465",
                            "#3e4466"
                        ],
                        "shape": [
                            "#f0f3ff",
                            "#d9dffa",
                            "#afb4d4",
                            "#646c9a"
                        ]
                    }
                }
            };
        </script>

        <!-- end::Global Config -->

        <!--begin::Global Theme Bundle(used by all pages) -->
        <script src="{{ asset('assets/admin/plugins/global/plugins.bundle.js')}}" type="text/javascript"></script>
        <script src="{{ asset('assets/admin/js/scripts.bundle.js')}}" type="text/javascript"></script>

        <!--end::Global Theme Bundle -->

        <!--begin::Page Scripts(used by this page) -->
        <script type="text/javascript">
             $.extend($.validator.messages, {
                    required: "Vui lòng nhập dữ liệu.",
                    remote: "Hãy sửa cho đúng.",
                    email: "Hãy nhập email.",
                    url: "Hãy nhập URL.",
                    date: "Hãy nhập ngày.",
                    dateISO: "Hãy nhập ngày (ISO).",
                    number: "Hãy nhập số.",
                    digits: "Hãy nhập chữ số.",
                    creditcard: "Hãy nhập số thẻ tín dụng.",
                    equalTo: "Hãy nhập thêm lần nữa.",
                    extension: "Phần mở rộng không đúng.",
                    maxlength: $.validator.format( "Hãy nhập từ {0} kí tự trở xuống." ),
                    minlength: $.validator.format( "Hãy nhập từ {0} kí tự trở lên." ),
                    rangelength: $.validator.format( "Hãy nhập từ {0} đến {1} kí tự." ),
                    range: $.validator.format( "Hãy nhập từ {0} đến {1}." ),
                    max: $.validator.format( "Hãy nhập từ {0} trở xuống." ),
                    min: $.validator.format( "Hãy nhập từ {0} trở lên." )
                });
        </script>
        <script src="{{ asset('assets/admin/js/pages/custom/login/login-1.js')}}" type="text/javascript"></script>

        <!--end::Page Scripts -->
    </body>

    <!-- end::Body -->
</html>

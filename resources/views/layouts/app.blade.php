<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0,user-scalable=0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="icon" href="{{ asset('images/favicon.png') }}" type="image/x-icon" />
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" />
    <link rel="stylesheet" href="{{ asset('css/main.css') }}">
    <link rel="stylesheet" href="{{ asset('css/toast-plugin.min.css') }}">
    <link rel="stylesheet" href="//cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css" id="theme-styles">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <title>{{ config('app.name', 'Laravel') }}</title>
</head>

<body>
    <div class="body-wrapper">
        <!-- Navigation header start -->
        <header>
            <nav class="head-nav">
                <div class="container-lg">
                    <div class="wrapper">
                        <div class="brand-logo">
                            <a href="{{ url('/') }}">
                                <img src="{{ asset('images/logo.png') }}" alt="Gems Tools image" width="182px"
                                    height="46px">
                            </a>
                        </div>
                        <div class="nav-links">
                            <div class="hemburger" id="hemburger">
                                <button>
                                    <span></span>
                                    <span></span>
                                    <span></span>
                                </button>
                            </div>
                            @auth
                                <ul class="admin-list">
                                    <li class="hide-at-mobile">
                                        <a href="{{ route('upcoming-nft') }}" class="{{Request::is('admin/upcoming-nft') ? 'active' : ''}}">Upcoming Nft</a>
                                    </li>
                                    <li class="hide-at-mobile">
                                        <a href="{{ route('userlist') }}" class="{{Request::is('admin/userlist') ? 'active' : ''}}">User List</a>
                                    </li>
                                    <li class="hide-at-mobile">
                                        <a href="{{ route('nftlist') }}" class="{{Request::is('admin/nftlist') ? 'active' : ''}}">NFTs List</a>
                                    </li>
                                    <li class="position-relative">
                                        <a id="logout-user" href="#">{{ Auth::user()->name }}
                                            <span><i class="fas fa-chevron-circle-down"></i></span>
                                        </a>
                                        <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
                                            <a class="dropdown-item show-at-mobile"
                                                href="{{ route('upcoming-nft') }}">Upcoming Nft</a>
                                            <a class="dropdown-item show-at-mobile" href="{{ route('userlist') }}">User
                                                List</a>
                                            <a class="dropdown-item show-at-mobile" href="{{ route('nftlist') }}">NFTs
                                                List</a>
                                            <a class="dropdown-item" href="{{ route('logout') }}"
                                                onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                                <i class="fas fa-sign-out-alt"></i>
                                                <span> {{ __('Logout') }} </span>
                                            </a>

                                            <form id="logout-form" action="{{ route('logout') }}" method="POST"
                                                class="d-none">
                                                @csrf
                                            </form>
                                        </div>

                                    </li>
                                </ul>
                            @else
                                @if (!Route::has('login'))
                                    <ul class="user-list">
                                        <li>
                                            <a href="#">Submit NFTs</a>
                                        </li>
                                    </ul>
                                @endif
                            @endauth
                        </div>
                        <div class="mobile-nav">
                            @auth
                                <ul class="mobile-adminlist">
                                    <li>
                                        <a href="{{ route('upcoming-nft') }}">Upcoming Nft</a>
                                    </li>
                                    <li>
                                        <a href="{{ route('userlist') }}">User List</a>
                                    </li>
                                    <li>
                                        <a href="{{ route('nftlist') }}">NFTs List</a>
                                    </li>
                                    <li class="position-relative">
                                        <a id="mobile-logout-user" href="#">{{ Auth::user()->name }}
                                            <span><i class="fas fa-chevron-circle-down"></i></span>
                                        </a>
                                        <div class="dropdown-menu dropdown-menu-right position-absolute"
                                            aria-labelledby="navbarDropdown">
                                            <a class="dropdown-item" href="{{ route('logout') }}"
                                                onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                                <i class="fas fa-sign-out-alt"></i>
                                                <span> {{ __('Logout') }} </span>
                                            </a>

                                            <form id="logout-form" action="{{ route('logout') }}" method="POST"
                                                class="d-none">
                                                @csrf
                                            </form>
                                        </div>
                                    </li>
                                </ul>
                            @endauth
                        </div>
                    </div>
                </div>
            </nav>
        </header>
        <!-- Navigation header end -->
        @yield('content')
        <footer>
            <div class="gemstool-footer">
                <div class="container-lg">
                    <div class="credits">
                        <div class="copyright"><span>Copyright 2022 | Gems Tools</span></div>
                        <div class="developed"><span>Designed and Developed by <a href="https://qalbit.com/"
                                    target="_blank"> QalbIT Solution </a> </span></div>
                    </div>
                </div>
            </div>
        </footer>
    </div>
    <style>
        *{
            font-family: "Nunito", sans-serif;
        }
        @media (max-width: 950px) {
            .hide-at-mobile {
                display: none
            }
        }
        @media (min-width: 950px) {
            .show-at-mobile {
                display: none
            }
        }

    </style>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js"></script>
    <script src="{{ asset('js/toast-plugin.min.js') }}"></script>
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/moment.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment-timezone/0.5.34/moment-timezone-with-data-10-year-range.js" ></script>
    <script>
        $(document).ready(function() {
            $('.mobile-nav').css('display', 'none');
            // $('#logout-user,#mobile-logout-user').click(function() {
            //     $('.dropdown-menu-right').toggleClass('d-block');
            // });

            $(document).on('click', function(e){
                const target1 = e.target.closest('#logout-user')
                const target2 = e.target.closest('#mobile-logout-user')
                const target3 = e.target.closest('.hemburger')
                if(target3 !== null) {
                    $('.mobile-nav').slideToggle();
                } else if (target1 !== null || target2 !== null) {
                    $('.dropdown-menu-right').toggleClass('d-block');
                } else {
                    $('.mobile-nav').slideUp();
                    $('.dropdown-menu-right').removeClass('d-block');
                }
            });

            // $('.hemburger').click(function() {
            //     $('.mobile-nav').slideToggle();
            // });
        });
        @if (Session::has('success'))
            mdtoast.success("{{ Session::get('success') }}", {
            duration: 5000,
            position: "top right",
            progressBar :true
            });
        @endif
        @if (Session::has('error'))
            mdtoast.error("{{ Session::get('error') }}", {
            duration: 5000,
            position: "top right",
            progressBar :true
            });
        @endif
    </script>
</body>

</html>

<?php /* <nav class="navbar navbar-expand-md navbar-light bg-white shadow-sm">
    <div class="container">
       
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="{{ __('Toggle navigation') }}">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <!-- Left Side Of Navbar -->
            <ul class="navbar-nav me-auto">

            </ul>

            <!-- Right Side Of Navbar -->
            <ul class="navbar-nav ms-auto">
                <!-- Authentication Links -->
                @guest
                    @if (Route::has('login'))
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('login') }}">{{ __('Login') }}</a>
                        </li>
                    @endif

                    @if (Route::has('register'))
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('register') }}">{{ __('Register') }}</a>
                        </li>
                    @endif
                @else
                    <li class="nav-item dropdown">
                        <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                            {{ Auth::user()->name }}
                        </a>

                        <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
                            <a class="dropdown-item" href="{{ route('logout') }}"
                               onclick="event.preventDefault();
                                             document.getElementById('logout-form').submit();">
                                {{ __('Logout') }}
                            </a>

                            <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                @csrf
                            </form>
                        </div>
                    </li>
                @endguest
            </ul>
        </div>
    </div>
</nav> */
?>

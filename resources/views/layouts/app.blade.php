<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link rel="shortcut icon" type="image/png" href="{{ asset('backend/asset/img/logos/Logo HV.png') }}" />
    <link rel="stylesheet" href="{{ asset('backend/asset/css/styles.min.css') }}" />
    <link rel="stylesheet" href="{{ asset('backend/asset/css/myStyle.css') }}" />

    @yield('title')
    @yield('css')
</head>

<body>
    <!--  Body Wrapper -->
    <div class="page-wrapper" id="main-wrapper" data-layout="vertical" data-navbarbg="skin6" data-sidebartype="full"
        data-sidebar-position="fixed" data-header-position="fixed">

        @include('components.sidebar')

        <!--  Main wrapper -->
        <div class="body-wrapper">
            <!--  Header Start -->
            <header class="app-header">
                <nav class="navbar navbar-expand-lg navbar-light">

                    <div class="navbar-collapse justify-content-end px-0" id="navbarNav">
                        <ul class="navbar-nav flex-row ms-auto align-items-center justify-content-end">
                            <li class="nav-item dropdown">
                                <a class="nav-link nav-icon-hover" href="javascript:void(0)" id="drop2"
                                    data-bs-toggle="dropdown" aria-expanded="false">
                                    <img src="{{ asset('backend/asset/img/profile/user-1.png') }}" alt=""
                                        width="35" height="35" class="rounded-circle">
                                </a>
                                <div class="dropdown-menu dropdown-menu-end dropdown-menu-animate-up"
                                    aria-labelledby="drop2">
                                    <div class="message-body">
                                        <div class="card-body p-4">
                                            <div>
                                                <h3>{{ Auth::user()->username }}</h3>
                                                <p>{{ Auth::user()->email }}</p>
                                                <a class="sidebar-link" href="{{ route('logout') }}"
                                                    aria-expanded="false"
                                                    onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                                    <span class="hide-menu" style="color: red"><i
                                                            class="ti ti-logout"></i> Logout</span>
                                                </a>
                                                <form id="logout-form" action="{{ route('logout') }}" method="POST"
                                                    style="display: none;">
                                                    @csrf
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </li>
                        </ul>
                    </div>
                </nav>
            </header>
            <!--  Header End -->

            @yield('content')
        </div>
        <!-- End Main wrapper -->
    </div>
    <!-- End of Body Wrapper -->

    <script src="{{ asset('backend/asset/libs/jquery/dist/jquery.min.js') }}"></script>
    <script src="{{ asset('backend/asset/libs/bootstrap/dist/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('backend/asset/js/new/sidebarmenu.js') }}"></script>
    <script src="{{ asset('backend/asset/js/new/app.min.js') }}"></script>
    <script src="{{ asset('backend/asset/libs/apexcharts/dist/apexcharts.min.js') }}"></script>
    <script src="{{ asset('backend/asset/libs/simplebar/dist/simplebar.js') }}"></script>
    <script src="{{ asset('backend/asset/js/new/myScript.js') }}"></script>
    @yield('js')
</body>

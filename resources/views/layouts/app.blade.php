<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <title>{{ config('app.name') }} - @yield('title')</title>

    <link rel="shortcut icon" href="{{ asset('img/logo.png') }}" type="image/x-icon">
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <link href="{{ asset('css/main.css') }}" rel="stylesheet">
    @stack('styles')

</head>

<body id="page-top">

    <div id="app">
        <!-- Page Wrapper -->
        <div id="wrapper">

            <!-- Sidebar -->
            <ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">

                <!-- Sidebar - Brand -->
                <a class="sidebar-brand d-flex align-items-center justify-content-center"
                    href="{{ route('dashboard') }}">
                    <div class="sidebar-brand-icon rotate-n-15">
                        <i class="fas fa-money-bill"></i>
                    </div>
                    <div class="sidebar-brand-text mx-3">{{ config('app.name') }}</div>
                </a>

                @can('index dashboard')
                <!-- Divider -->
                <hr class="sidebar-divider my-0">

                <!-- Nav Item - Dashboard -->
                <li class="nav-item {{ pageActive('dashboard') ? 'active' : '' }}">
                    <a class="nav-link" href="{{ route('dashboard') }}">
                        <i class="fas fa-fw fa-tachometer-alt"></i>
                        <span>{{ __('main.dashboard') }}</span></a>
                </li>

                @endcan

                @if(auth()->user()->can('index transactions') || auth()->user()->can('index dues') || auth()->user()->can('index cash'))

                <!-- Divider -->
                <hr class="sidebar-divider my-0">

                @can('index transactions')

                <!-- Nav Item - Incomes/Expenses -->
                <li class="nav-item {{ pageActive('transactions.index') ? 'active' : '' }}">
                    <a class="nav-link" href="{{ route('transactions.index') }}">
                        <i class="fas fa-fw fa-random"></i>
                        <span>{{ __('main.incomes') }} / {{ __('main.expenses') }}</span></a>
                </li>

                @endcan

                @can('index dues')

                <!-- Nav Item - Lending/Borrowing -->
                <li class="nav-item">
                    <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#lendingborrowing"
                        aria-expanded="true" aria-controls="lendingborrowing">
                        <i class="fas fa-fw fa-hand-holding-usd"></i>
                        <span>{{ __('main.lendings') }} / {{ __('main.borrowings') }}</span>
                    </a>
                    <div id="lendingborrowing"
                        class="collapse {{ pageActive('lendings.index') || pageActive('borrowings.index') ? 'show' : '' }}"
                        aria-labelledby="headingUtilities" data-parent="#accordionSidebar">
                        <div class="bg-white py-2 collapse-inner rounded">
                            <a class="collapse-item {{ pageActive('lendings.index') ? 'active' : '' }}"
                                href="{{ route('lendings.index') }}">{{ __('main.lendings') }}</a>
                            <a class="collapse-item {{ pageActive('borrowings.index') ? 'active' : '' }}"
                                href="{{ route('borrowings.index') }}">{{ __('main.borrowings') }}</a>
                        </div>
                    </div>
                </li>

                @endcan

                @can('index cash')

                <!-- Nav Item - Current Cash -->
                <li class="nav-item {{ pageActive('cash.index') ? 'active' : '' }}">
                    <a class="nav-link" href="{{ route('cash.index') }}">
                        <i class="fas fa-fw fa-wallet"></i>
                        <span>{{ __('main.current_cash') }}</span></a>
                </li>

                @endcan

                @endif

                @if(auth()->user()->can('index people') || auth()->user()->can('index accounts'))

                <!-- Divider -->
                <hr class="sidebar-divider my-0">

                @can('index people')

                <!-- Nav Item - People -->
                <li class="nav-item {{ pageActive('people.index') ? 'active' : '' }}">
                    <a class="nav-link" href="{{ route('people.index') }}">
                        <i class="fas fa-fw fa-users"></i>
                        <span>{{ __('main.people') }}</span></a>
                </li>

                @endcan

                @can('index accounts')

                <!-- Nav Item - Accounts -->
                <li class="nav-item {{ pageActive('accounts.index') ? 'active' : '' }}">
                    <a class="nav-link" href="{{ route('accounts.index') }}">
                        <i class="fas fa-fw fa-user"></i>
                        <span>{{ __('main.accounts') }}</span></a>
                </li>

                @endcan

                @endif

                @if(auth()->user()->hasRole('admin'))

                <!-- Divider -->
                <hr class="sidebar-divider my-0">

                <!-- Nav Item - Application Settings -->
                <li class="nav-item {{ pageActive('application.settings.index') ? 'active' : '' }}">
                    <a class="nav-link" href="{{ route('application.settings.index') }}">
                        <i class="fas fa-fw fa-globe"></i>
                        <span>{{ __('main.application_settings') }}</span></a>
                </li>

                @endif

                <!-- Divider -->
                <hr class="sidebar-divider d-none d-md-block">

                <!-- Sidebar Toggler (Sidebar) -->
                <div class="text-center d-none d-md-inline">
                    <button class="rounded-circle border-0" id="sidebarToggle"></button>
                </div>

            </ul>
            <!-- End of Sidebar -->

            <!-- Content Wrapper -->
            <div id="content-wrapper" class="d-flex flex-column">

                <!-- Main Content -->
                <div id="content">

                    <!-- Topbar -->
                    <nav class="navbar navbar-expand navbar-light bg-white topbar mb-4 static-top shadow">

                        <!-- Sidebar Toggle (Topbar) -->
                        <button id="sidebarToggleTop" class="btn btn-link d-md-none rounded-circle mr-3">
                            <i class="fa fa-bars"></i>
                        </button>

                        <!-- Topbar Search -->
                        <form class="d-none d-sm-inline-block form-inline mr-auto ml-md-3 my-2 my-md-0 mw-100 navbar-search" action="{{ route('process') }}" method="GET">
                            <div class="input-group">
                                <input type="text" class="form-control bg-light border-0 small"
                                    name="serial"
                                    placeholder="{{ __('main.search_by_process_serial') }}" value="{{ request()->get('serial') }}" aria-label="Search"
                                    aria-describedby="basic-addon2">
                                <div class="input-group-append">
                                    <button class="btn btn-primary" type="submit">
                                        <i class="fas fa-search fa-sm"></i>
                                    </button>
                                </div>
                            </div>
                        </form>

                        <!-- Topbar Navbar -->
                        <ul class="navbar-nav ml-auto">

                            <!-- Nav Item - Search Dropdown (Visible Only XS) -->
                            <li class="nav-item dropdown no-arrow d-sm-none">
                                <a class="nav-link dropdown-toggle" href="#" id="searchDropdown" role="button"
                                    data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    <i class="fas fa-search fa-fw"></i>
                                </a>
                                <!-- Dropdown - Messages -->
                                <div class="dropdown-menu dropdown-menu-right p-3 shadow animated--grow-in"
                                    aria-labelledby="searchDropdown">
                                    <form class="form-inline mr-auto w-100 navbar-search" action="{{ route('process') }}" method="GET">
                                        <div class="input-group">
                                            <input type="text" class="form-control bg-light border-0 small"
                                                placeholder="{{ __('main.search_by_process_serial') }}" value="{{ request()->get('serial') }}" aria-label="Search"
                                                aria-describedby="basic-addon2">
                                            <div class="input-group-append">
                                                <button class="btn btn-primary" type="button">
                                                    <i class="fas fa-search fa-sm"></i>
                                                </button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </li>

                            <!-- Nav Item - User Information -->
                            <li class="nav-item dropdown no-arrow">
                                <a class="nav-link dropdown-toggle" id="userDropdown" role="button"
                                    data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    <span
                                        class="mr-2 d-inline text-gray-600 small">{{ auth()->user()->username }}</span>
                                    <img class="img-profile rounded-circle" src="{{ asset('img/undraw_profile.svg') }}">
                                </a>
                                <!-- Dropdown - User Information -->
                                <div class="dropdown-menu dropdown-menu-right shadow animated--grow-in"
                                    aria-labelledby="userDropdown">
                                    <a class="dropdown-item" href="{{ route('settings.edit') }}">
                                        <i class="fas fa-cogs fa-sm fa-fw mr-2 text-gray-400"></i>
                                        {{ __('main.settings') }}
                                    </a>
                                    <div class="dropdown-divider"></div>
                                    <button class="dropdown-item"
                                        onclick="event.preventDefault();document.getElementById('logout-form').submit();">
                                        <i class="fas fa-sign-out-alt fa-sm fa-fw mr-2 text-gray-400"></i>
                                        {{ __('main.logout') }}
                                    </button>
                                    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                        @csrf
                                    </form>
                                </div>
                            </li>

                        </ul>

                    </nav>
                    <!-- End of Topbar -->

                    <!-- Begin Page Content -->
                    <div class="container-fluid">

                        @yield('content')

                    </div>
                    <!-- /.container-fluid -->

                </div>
                <!-- End of Main Content -->

            </div>
            <!-- End of Content Wrapper -->

        </div>
        <!-- End of Page Wrapper -->

        <!-- Scroll to Top Button-->
        <a class="scroll-to-top rounded" href="#page-top">
            <i class="fas fa-angle-up"></i>
        </a>
    </div>

    <script src="{{ route('assets.lang') }}"></script>
    <script src="{{ asset('js/app.js') }}"></script>
    <script src="{{ asset('vendor/jquery-easing/jquery.easing.min.js') }}"></script>
    <script src="{{ asset('js/main.js') }}"></script>
    <script src="{{ asset('js/custom.js') }}"></script>
    @stack('scripts')

</body>

</html>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <title>{{ config('app.name') . ' - ' . __('main.login') }}</title>

    <link rel="shortcut icon" href="{{ asset('img/logo.png') }}" type="image/x-icon">
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <link href="{{ asset('css/main.css') }}" rel="stylesheet">

</head>

<body class="bg-gradient-primary">

    <div id="app">
        <div class="container">

            <!-- Outer Row -->
            <div class="row justify-content-center">

                <div class="col-lg-6">

                    <div class="card o-hidden border-0 shadow-lg my-5">
                        <div class="card-body p-0">
                            <!-- Nested Row within Card Body -->
                            <div class="p-5">
                                <div class="d-flex justify-content-center align-items-center pb-5" style="color: #3057c9">
                                    <i class="fas fa-money-bill fa-3x rotate-n-15"></i>
                                    <div class="sidebar-brand-text h2 mx-3 mb-0 text-capitalize" style="font-weight: 800">Finance Tracker</div>
                                </div>
                                <div class="text-center">
                                    <h1 class="h4 text-gray-900 mb-4">{{ __('main.welcome_back') }}!</h1>
                                </div>
                                <form class="user" action="{{ route('login') }}" method="POST" id="loginForm"  onsubmit="document.getElementById('loginBtn').disabled = true">
                                    @csrf
                                    <div class="form-group">
                                        <input type="text" class="form-control form-control-user" name="username"
                                            placeholder="{{ __('main.username') }}" value="{{ old('username') }}" autofocus>
                                        @error('username')
                                            <span class="invalid-feedback d-block mt-2 ml-2" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                    <div class="form-group">
                                        <input type="password" class="form-control form-control-user" name="password"
                                            placeholder="{{ __('main.password') }}">
                                        @error('password')
                                            <span class="invalid-feedback d-block mt-2 ml-2" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                    <div class="form-group">
                                        <div class="custom-control custom-checkbox small">
                                            <input type="checkbox" class="custom-control-input" id="remember_me">
                                            <label class="custom-control-label"
                                                for="remember_me">{{ __('main.remember_me') }}</label>
                                        </div>
                                    </div>
                                    <button type="submit" class="btn btn-primary btn-user btn-block" id="loginBtn">
                                        {{ __('main.login') }}
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>

                </div>

            </div>

        </div>
    </div>

    <script src="{{ asset('js/app.js') }}"></script>
    <script src="{{ asset('vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('vendor/jquery-easing/jquery.easing.min.js') }}"></script>
    <script src="{{ asset('js/main.js') }}"></script>

</body>

</html>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0">
    <title>{{ !empty($meta_title) ? $meta_title : 'Login' }}</title>

    <link rel="shortcut icon" href="{{ asset('assets/img/favicon.png') }}">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('assets/plugins/feather/feather.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/plugins/icons/flags/flags.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/plugins/fontawesome/css/fontawesome.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/plugins/fontawesome/css/all.min.css') }}">
    @vite(['resources/css/login.css', 'resources/js/login.js'])
</head>

<body>
    <div class="main-wrapper login-body">
        <div class="login-wrapper">
            <div class="container">
                <div class="loginbox">
                    <div class="login-left">
                        <img class="img-fluid" src="{{ asset('assets/img/logingambar.png') }}" alt="Ilustrasi Login">
                    </div>
                    <div class="login-right">
                        <div class="login-right-wrap">
                            <h1>Selamat Datang !</h1>
                            <p class="account-subtitle">Silakan masuk untuk menggunakan aplikasi </p>
                            <h2>Sign in</h2>

                            @if ($errors->any())
                                <div class="alert alert-danger py-2">{{ $errors->first() }}</div>
                            @endif

                            <form action="{{ route('login.post') }}" method="POST" novalidate>
                                @csrf
                                <div class="form-group">
                                    <label>Username atau Email <span class="login-danger">*</span></label>
                                    <input class="form-control" type="text" name="login"
                                        value="{{ old('login') }}" required autofocus>
                                    <span class="profile-views"><i class="fas fa-user-circle"></i></span>
                                </div>
                                <div class="form-group">
                                    <label>Password <span class="login-danger">*</span></label>
                                    <input class="form-control pass-input" type="password" name="password" required>
                                    <span class="profile-views feather-eye toggle-password"></span>
                                </div>
                                <div class="forgotpass">
                                    <div class="remember-me">
                                        <label class="custom_check mr-2 mb-0 d-inline-flex remember-me"> Ingat saya
                                            <input type="checkbox" name="remember" value="1">
                                            <span class="checkmark"></span>
                                        </label>
                                    </div>
                                    {{-- <a href="{{ route('password.request') }}">Lupa password?</a> --}}
                                </div>
                                <div class="form-group">
                                    <button class="btn btn-primary btn-block" type="submit">Login</button>

                                </div>
                                <label class=" mr-2 mb-0 d-inline-flex text-end "> Belum Punya
                                    Akun ?</label><a href="/register">Daftar !</a>
                            </form>

                        </div>
                    </div>
                </div> <!-- /.loginbox -->
            </div>
        </div>
    </div>

    <script src="{{ asset('assets/js/feather.min.js') }}"></script>
    <script src="{{ asset('assets/js/script.js') }}"></script>
</body>

</html>

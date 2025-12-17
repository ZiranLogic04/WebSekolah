<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <title inertia>{{ config('app.name', 'Peschool') }}</title>

  <!-- Load Owl Carousel -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/assets/owl.carousel.min.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/assets/owl.theme.default.min.css">
  
  <!-- Animate.css -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css">
  
  <!-- Fonts -->
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@700;800&display=swap" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,400;0,500;0,700;0,900;1,400;1,500;1,700&display=swap" rel="stylesheet">
  
  <!-- Admin Template CSS - PAKAI asset() SEMUA -->
  <link rel="stylesheet" href="{{ asset('assets/plugins/bootstrap/css/bootstrap.min.css') }}">
  <link rel="stylesheet" href="{{ asset('assets/plugins/feather/feather.css') }}">
  <link rel="stylesheet" href="{{ asset('assets/plugins/icons/flags/flags.css') }}">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
  <link rel="stylesheet" href="{{ asset('assets/css/style.css') }}">
  <style>
    .navbar .dropdown-toggle::after {
      font-family: 'feather' !important;
      content: "\e92e" !important;
      border: 0 !important;
      font-weight: normal !important;
      vertical-align: middle;
      margin-left: 5px;
    }
  </style>
  
  @vite(['resources/js/app.js', 'resources/css/app.css'])
  @inertiaHead
</head>

<body>
  @inertia

  <!-- jQuery HARUS PERTAMA -->
  <script src="{{ asset('assets/js/jquery-3.6.0.min.js') }}"></script>
  
  <!-- Owl Carousel -->
  <script src="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/owl.carousel.min.js"></script>
  
  <!-- Bootstrap JS -->
  <script src="{{ asset('assets/plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
  
  <!-- Feather Icons -->
  <script src="{{ asset('assets/js/feather.min.js') }}"></script>
  
  <!-- Slimscroll -->
  <script src="{{ asset('assets/plugins/slimscroll/jquery.slimscroll.min.js') }}"></script>
  
  <!-- Apex Charts (optional, kalau pakai chart) -->
  <script src="{{ asset('assets/plugins/apexchart/apexcharts.min.js') }}"></script>
  <script src="{{ asset('assets/plugins/apexchart/chart-data.js') }}"></script>
  
  <!-- Script.js HARUS TERAKHIR -->
  <script src="{{ asset('assets/js/script.js') }}"></script>
</body>

</html>
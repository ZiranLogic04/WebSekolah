<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>WEBSEKOLAHKU</title>
    <meta name="description" content="">
    <meta name="keywords" content="">

    <!-- 1) FONT (Google Fonts) -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@700;800&display=swap" rel="stylesheet">
    <!-- favicon -->
    <link rel="icon" type="image/x-icon" href="assets/img/hope/images.jpg">

    @vite(['resources/css/website.css', 'resources/js/website.js'])
</head>

<body>

    <!-- header area -->
    <header class="header w-100">

        @include('partialweb.header_top')
        @include('partialweb.navbar')
    </header>
    <!-- header area end -->

    <!-- popup search -->
    <div class="search-popup">
        <button class="close-search"><span class="far fa-times"></span></button>
        <form action="#">
            <div class="form-group">
                <input type="search" name="search-field" placeholder="Search Here..." required="">
                <button type="submit"><i class="far fa-search"></i></button>
            </div>
        </form>
    </div>
    <!-- popup search end -->

    <main class="main">
        @yield('main-content')

    </main>
    <!-- footer area -->
    @include('partialweb.footer')

    <!-- footer area end -->

    <!-- scroll-top -->
    <a href="#" id="scroll-top"><i class="fa fa-arrow-up"></i></a>
    <!-- scroll-top end -->




</body>

</html>

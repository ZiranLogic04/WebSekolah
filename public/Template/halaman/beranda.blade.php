@extends('index')
@section('main-content')
    <!-- hero slider -->
    @include('partialweb.slider')
    <!-- hero slider end -->

    <!-- feature area -->
    @include('beranda.feature_area')
    <!-- feature area end -->

    <!-- about area -->
    @include('beranda.about_area')
    <!-- about area end -->

    <!-- counter area -->
    @include('beranda.counter_area')
    <!-- counter area end -->

    <!-- department area -->
    @include('beranda.departmen_area')
    <!-- department area end -->

    <!-- video-area -->
    @include('beranda.video_area')
    <!-- video-area end -->

    <!-- team-area -->
    @include('beranda.team_area')
    <!-- team-area end -->

    <!-- choose-area -->
    @include('beranda.choose_area')
    <!-- choose-area end -->

    <!-- portfolio-area -->
    @include('beranda.portfolio_area')
    <!-- portfolio-area end -->

    <!-- event area -->
    @include('beranda.event_area')
    <!-- event area end -->

    <!-- testimonial area -->
    @include('beranda.testimonial_area')
    <!-- testimonial area end -->

    <!-- blog area -->
    @include('beranda.blog_area')
    <!-- blog area end -->

    <!-- partner area -->
    @include('beranda.partner_area')
    <!-- partner area end -->
@endsection

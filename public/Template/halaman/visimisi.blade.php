@extends('index')

@section('main-content')
    <div class="py-120">
        <div class="container">

            {{-- Heading --}}
            <div class="row">
                <div class="col-lg-6 mx-auto">
                    <div class="site-heading text-center">
                        <span class="site-title-tagline"><i class="fa fa-book-open"></i> Visi & Misi</span>
                        <h2 class="site-title">VISI <span>MISI</span></h2>
                        <p>Landasan arah pengembangan MI Al-Hikmah untuk menumbuhkan iman, ilmu, dan akhlak mulia.</p>
                    </div>
                </div>
            </div>

            {{-- Visi & Misi --}}
            <div class="row g-4">
                {{-- Visi --}}
                <div class="col-lg-5">
                    <div class="p-4 rounded-3 shadow-sm h-100 bg-white wow fadeInLeft" data-wow-delay=".15s">
                        <h4 class="mb-2">Visi</h4>
                        <div class="d-inline-block mb-3 px-3 py-1 rounded-pill"
                            style="background:#f3f4f6; font-weight:600;">
                            TAMANKU
                        </div>
                        <blockquote class="mb-0" style="font-size:1.05rem; line-height:1.7;">
                            <em>“Terwujudnya Insan yang <strong>Tafaqquh fid-din</strong>, <strong>Mandiri</strong>, dan
                                <strong>Berakhlakul Karimah</strong> (TAMANKU).”</em>
                        </blockquote>
                    </div>
                </div>

                {{-- Misi --}}
                <div class="col-lg-7">
                    <div class="p-4 rounded-3 shadow-sm h-100 bg-white wow fadeInRight" data-wow-delay=".15s">
                        <h4 class="mb-3">Misi</h4>
                        <ul class="list-unstyled m-0" style="line-height:1.8;">
                            <li class="mb-2"><i class="far fa-check-circle me-2 text-success"></i>Mewujudkan generasi yang
                                rajin dan khusyuk beribadah melalui kegiatan keagamaan.</li>
                            <li class="mb-2"><i class="far fa-check-circle me-2 text-success"></i>Menumbuhkan perilaku
                                santun melalui pembiasaan <strong>5S</strong> dan agen karakter.</li>
                            <li class="mb-2"><i class="far fa-check-circle me-2 text-success"></i>Meningkatkan kualitas
                                dan efektivitas proses belajar mengajar.</li>
                            <li class="mb-2"><i class="far fa-check-circle me-2 text-success"></i>Mendorong prestasi
                                akademik dan non-akademik melalui kegiatan ekstrakurikuler.</li>
                            <li class="mb-2"><i class="far fa-check-circle me-2 text-success"></i>Membiasakan budaya
                                disiplin bagi seluruh warga madrasah melalui program bahasa.</li>
                            <li class="mb-2"><i class="far fa-check-circle me-2 text-success"></i>Menciptakan lingkungan
                                <strong>Aman, Bersih, Indah, Rindang, Asri</strong> melalui kegiatan “5 menit bersih”
                                sebelum pulang.
                            </li>
                            <li class="mb-2"><i class="far fa-check-circle me-2 text-success"></i>Menjaga dan mengelola
                                lingkungan hidup melalui komunitas <strong>Green and Clean School (GCC)</strong>.</li>
                            <li class="mb-2"><i class="far fa-check-circle me-2 text-success"></i>Meningkatkan kemampuan
                                literasi melalui <strong>Gerakan Literasi Madrasah (GLM)</strong>.</li>
                        </ul>
                    </div>
                </div>
            </div>

        </div>
    </div>
@endsection

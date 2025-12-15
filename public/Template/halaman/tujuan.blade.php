@extends('index')

@section('main-content')
    <div class="py-120">
        <div class="container">

            {{-- Heading --}}
            <div class="row">
                <div class="col-lg-8 mx-auto">
                    <div class="site-heading text-center">
                        <span class="site-title-tagline"><i class="fa fa-book-open-reader"></i> Tujuan</span>
                        <h2 class="site-title">Tujuan <span>Madrasah</span></h2>
                        <p>Landasan arah pengembangan MI Al-Hikmah untuk menumbuhkan iman, ilmu, dan akhlak mulia.</p>
                    </div>
                </div>
            </div>

            <div class="row g-4 align-items-start">
                {{-- Daftar Tujuan --}}
                <div class="col-lg-7">
                    <div class="p-4 rounded-3 shadow-sm bg-white wow fadeInLeft" data-wow-delay=".1s">
                        <ol class="list-unstyled m-0" style="line-height:1.9;">
                            <li><i class="far fa-check-circle me-2 text-success"></i>Menyiapkan peserta didik agar memiliki
                                dasar keimanan yang kokoh, akhlak mulia, dan semangat
                                ibadah dalam kehidupan sehari-hari.</li>
                            <li><i class="far fa-check-circle me-2 text-success"></i>Menumbuhkembangkan karakter siswa yang
                                mandiri, disiplin, tanggung jawab, dan cinta terhadap
                                ilmu pengetahuan.</li>
                            <li><i class="far fa-check-circle me-2 text-success"></i>Menanamkan pemahaman Islam yang benar
                                dan moderat, serta mencintai Al-Qur’an sebagai pedoman
                                hidup.</li>
                            <li><i class="far fa-check-circle me-2 text-success"></i>Membekali peserta didik dengan
                                keterampilan dasar membaca, menulis, berhitung, dan berpikir
                                kritis sesuai jenjang usianya.</li>
                            <li><i class="far fa-check-circle me-2 text-success"></i>Mengembangkan potensi peserta didik
                                secara optimal baik di bidang akademik maupun
                                non-akademik.</li>
                            <li><i class="far fa-check-circle me-2 text-success"></i>Membangun budaya sekolah yang sehat,
                                harmonis, dan peduli lingkungan demi iklim belajar yang
                                nyaman dan menyenangkan.</li>
                            <li><i class="far fa-check-circle me-2 text-success"></i>Menanamkan semangat nasionalisme dan
                                cinta tanah air dalam bingkai nilai-nilai Islami.</li>
                        </ol>
                    </div>
                </div>

                {{-- Kotak Pendukung (opsional) --}}
                <div class="col-lg-5">
                    <div class="p-4 rounded-3 shadow-sm bg-white h-100 wow fadeInRight" data-wow-delay=".1s">
                        <h5 class="mb-3">Program yang Mendukung</h5>
                        <ul class="m-0" style="line-height:1.9;">
                            <li>Tahfidz & Tadarus Harian</li>
                            <li>Gerakan Literasi Madrasah (GLM)</li>
                            <li>Green & Clean School (GCC)</li>
                            <li>Ekstrakurikuler: Pramuka, Kaligrafi, Hadroh, KIR, English Club, olahraga</li>
                        </ul>
                        <a href="/program" class="theme-btn mt-3">Lihat Program<i
                                class="fas fa-arrow-right-long ms-2"></i></a>
                    </div>
                </div>
            </div>

        </div>
    </div>
@endsection

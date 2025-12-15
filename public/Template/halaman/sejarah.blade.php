@extends('index')

@section('main-content')
    <div class="py-120">
        <div class="container">

            {{-- Heading --}}
            <div class="row">
                <div class="col-lg-8 mx-auto">
                    <div class="site-heading text-center">
                        <span class="site-title-tagline"><i class="fa fa-book-open-reader"></i> Sejarah Singkat</span>
                        <h2 class="site-title">Sejarah <span>MI Al-Hikmah</span></h2>
                        <p>Perjalanan MI Al-Hikmah dalam membangun pendidikan dasar yang berlandaskan iman, ilmu, dan akhlak
                            mulia.</p>
                    </div>
                </div>
            </div>

            <div class="row g-4 align-items-start">
                {{-- Narasi + Timeline --}}
                <div class="col-lg-7">
                    <div class="p-4 rounded-3 shadow-sm bg-white wow fadeInLeft" data-wow-delay=".1s">
                        <h5 class="mb-3">Perjalanan Singkat</h5>
                        <p>
                            MI Al-Hikmah berdiri atas semangat masyarakat dan para pendidik untuk menghadirkan layanan
                            pendidikan
                            dasar yang religius, ramah anak, dan berkualitas. Seiring waktu, madrasah mengembangkan program
                            unggulan seperti Tahfiz & BTQ, Gerakan Literasi Madrasah (GLM), Green & Clean School (GCC),
                            serta beragam ekstrakurikuler yang menumbuhkan bakat dan karakter siswa.
                        </p>
                        <p class="mb-4">
                            Peningkatan mutu dilakukan berkelanjutan melalui pembelajaran aktif, pendampingan guru, dan
                            keterlibatan orang tua/komite. Implementasi kurikulum terkini pun dijalankan agar selaras dengan
                            tuntutan zaman, tanpa meninggalkan kekhasan keislaman dan budaya lokal.
                        </p>

                        <ul class="timeline list-unstyled m-0">
                            <li class="mb-3">
                                <div class="tl-dot"></div>
                                <div class="tl-content">
                                    <span class="tl-year">{{ $founded ?? '2008' }}</span>
                                    <h6 class="mb-1">Pendirian MI Al-Hikmah</h6>
                                    <p class="mb-0">Awal mula layanan pendidikan dasar berbasis keislaman untuk masyarakat
                                        sekitar.</p>
                                </div>
                            </li>
                            <li class="mb-3">
                                <div class="tl-dot"></div>
                                <div class="tl-content">
                                    <span class="tl-year">{{ $tahfiz_started ?? '2014' }}</span>
                                    <h6 class="mb-1">Penguatan Tahfiz & BTQ</h6>
                                    <p class="mb-0">Program tahsin, setoran berkala, dan pembiasaan ibadah harian.</p>
                                </div>
                            </li>
                            <li class="mb-3">
                                <div class="tl-dot"></div>
                                <div class="tl-content">
                                    <span class="tl-year">{{ $literasi_started ?? '2017' }}</span>
                                    <h6 class="mb-1">Gerakan Literasi Madrasah (GLM)</h6>
                                    <p class="mb-0">Membaca 15 menit, pojok baca kelas, dan kegiatan literasi kreatif.</p>
                                </div>
                            </li>
                            <li class="mb-3">
                                <div class="tl-dot"></div>
                                <div class="tl-content">
                                    <span class="tl-year">{{ $gcc_started ?? '2019' }}</span>
                                    <h6 class="mb-1">Green & Clean School (GCC)</h6>
                                    <p class="mb-0">Pembiasaan kebersihan, penghijauan, dan sekolah yang asri.</p>
                                </div>
                            </li>
                            <li class="mb-3">
                                <div class="tl-dot"></div>
                                <div class="tl-content">
                                    <span class="tl-year">{{ $latest_accreditation_year ?? '2022' }}</span>
                                    <h6 class="mb-1">Akreditasi Madrasah</h6>
                                    <p class="mb-0">Pencapaian akreditasi <strong>{{ $accreditation ?? 'B' }}</strong>
                                        sebagai wujud komitmen mutu.</p>
                                </div>
                            </li>
                            <li class="mb-1">
                                <div class="tl-dot"></div>
                                <div class="tl-content">
                                    <span class="tl-year">2024</span>
                                    <h6 class="mb-1">Penyesuaian Kurikulum Terbaru</h6>
                                    <p class="mb-0">Implementasi regulasi kurikulum terkini dengan penekanan karakter &
                                        kompetensi dasar.</p>
                                </div>
                            </li>
                        </ul>
                    </div>
                </div>

                {{-- Fakta Singkat --}}
                <div class="col-lg-5">
                    <div class="p-4 rounded-3 shadow-sm bg-white h-100 wow fadeInRight" data-wow-delay=".1s">
                        <h5 class="mb-3">Fakta Singkat</h5>
                        <ul class="list-unstyled mb-0" style="line-height:1.9;">
                            <li><i class="far fa-hashtag me-2 text-success"></i>NSM:
                                <strong>{{ $nsm ?? '111232040148' }}</strong>
                            </li>
                            <li><i class="fa fa-award me-2 text-success"></i>Akreditasi:
                                <strong>{{ $accreditation ?? 'B' }}</strong>
                            </li>
                            <li><i class="fa fa-user-tie me-2 text-success"></i>Kepala:
                                <strong>{{ $headmaster ?? 'Ahmad Soleh, S.Pd.I' }}</strong>
                            </li>
                            <li><i class="fa fa-map-marker-alt me-2 text-success"></i>Alamat:
                                <strong>{{ $address ?? 'Kp. Cihaneut RT 01/RW 11, Desa Drawati, Paseh, Bandung' }}</strong>
                            </li>
                            <li><i class="fa fa-users me-2 text-success"></i>Siswa Aktif:
                                <strong>{{ $students ?? '194' }}</strong>
                            </li>
                            <li><i class="fa fa-chalkboard-teacher me-2 text-success"></i>Guru & Tendik:
                                <strong>{{ $staff ?? '14' }}</strong>
                            </li>
                            <li><i class="fa fa-layer-group me-2 text-success"></i>Rombel:
                                <strong>{{ $rombel ?? '6' }}</strong>
                            </li>
                        </ul>
                        <a href="/profil" class="theme-btn mt-3">Profil Lengkap<i
                                class="fas fa-arrow-right-long ms-2"></i></a>
                    </div>
                </div>
            </div>

        </div>
    </div>
@endsection

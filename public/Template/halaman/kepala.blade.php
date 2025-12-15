@extends('index')

@section('main-content')
    <div class="py-120">
        <div class="container">

            {{-- Heading --}}
            <div class="row">
                <div class="col-lg-8 mx-auto">
                    <div class="site-heading text-center">
                        <span class="site-title-tagline"><i class="fa fa-user-tie"></i> Kepala Madrasah</span>
                        <h2 class="site-title">Profil <span>Kepala Madrasah</span></h2>
                        <p>Kepemimpinan yang menumbuhkan iman, ilmu, dan akhlak mulia di MI Al-Hikmah.</p>
                    </div>
                </div>
            </div>

            @php
                // === Bisa di-override dari Controller ===
                $head_name = $head_name ?? 'Ahmad Soleh, S.Pd.I';
                $head_email = $head_email ?? 'mialhikmahpaseh@gmail.com';
                $head_phone = $head_phone ?? '0812-1444-7944';
                $head_period = $head_period ?? 'Periode 2023–sekarang';
                $head_nip = $head_nip ?? '—';
                $head_photo = $head_photo ?? 'assets/img/team/kepala.jpg'; // ganti dengan foto Anda
                $head_quote =
                    $head_quote ??
                    '“Membentuk generasi yang Tafaqquh fid-din, Mandiri, dan Berakhlakul Karimah (TAMANKU).”';
            @endphp

            {{-- Foto + Sambutan --}}
            <div class="row g-4 align-items-start">
                <div class="col-lg-4">
                    <div class="p-4 rounded-3 shadow-sm bg-white text-center">
                        <div class="ratio ratio-3x4 rounded-3 overflow-hidden mb-3">
                            <img src="{{ asset($head_photo) }}" class="w-100 h-100" style="object-fit:cover;"
                                alt="Foto Kepala Madrasah">
                        </div>
                        <h4 class="mb-1">{{ $head_name }}</h4>
                        <div class="text-muted mb-2">Kepala Madrasah</div>

                        <ul class="list-unstyled small text-start m-0">
                            <li class="mb-1"><i class="far fa-id-badge me-2 text-success"></i>NIP: {{ $head_nip }}
                            </li>
                            <li class="mb-1"><i class="far fa-calendar me-2 text-success"></i>{{ $head_period }}</li>
                            <li class="mb-1"><i class="far fa-envelope me-2 text-success"></i><a
                                    href="mailto:{{ $head_email }}">{{ $head_email }}</a></li>
                            <li><i class="far fa-phone me-2 text-success"></i><a
                                    href="tel:{{ preg_replace('/\D/', '', $head_phone) }}">{{ $head_phone }}</a></li>
                        </ul>

                        <div class="d-flex justify-content-center gap-2 mt-3">
                            <a class="theme-btn theme-btn2" href="/kontak">Hubungi<i
                                    class="fas fa-arrow-right-long ms-2"></i></a>
                            <a class="theme-btn" href="/unduhan">Unduhan SK<i class="fas fa-arrow-right-long ms-2"></i></a>
                        </div>
                    </div>
                </div>

                <div class="col-lg-8">
                    <div class="p-4 rounded-3 shadow-sm bg-white h-100">
                        <h5 class="mb-3">Sambutan Kepala Madrasah</h5>
                        <blockquote class="mb-3" style="border-left:4px solid #16a34a; padding-left:12px; color:#0f5132;">
                            {{ $head_quote }}
                        </blockquote>
                        <p>
                            Assalamu’alaikum warahmatullahi wabarakatuh.
                            Selamat datang di MI Al-Hikmah. Kami berkomitmen menghadirkan layanan pendidikan dasar
                            yang berkualitas, religius, dan ramah anak. Program unggulan seperti Tahfiz & BTQ, Gerakan
                            Literasi
                            Madrasah (GLM), serta Green & Clean School (GCC) kami jalankan secara konsisten untuk
                            menumbuhkan
                            karakter dan kompetensi peserta didik.
                        </p>
                        <p class="mb-0">
                            Kami percaya kolaborasi sekolah–orang tua–komite adalah kunci. Semoga kehadiran laman ini
                            memudahkan informasi dan mempererat silaturahmi. Terima kasih atas kepercayaan Bapak/Ibu kepada
                            MI Al-Hikmah.
                            Wassalamu’alaikum warahmatullahi wabarakatuh.
                        </p>
                    </div>
                </div>
            </div>

            {{-- Profil Singkat & Riwayat --}}
            <div class="row g-4 align-items-start mt-2">
                <div class="col-lg-6">
                    <div class="p-4 rounded-3 shadow-sm bg-white">
                        <h5 class="mb-3">Profil Singkat</h5>
                        <div class="table-responsive">
                            <table class="table table-sm align-middle mb-0">
                                <tbody>
                                    <tr>
                                        <th style="width:170px;">Nama Lengkap</th>
                                        <td>{{ $head_name }}</td>
                                    </tr>
                                    <tr>
                                        <th>Jabatan</th>
                                        <td>Kepala Madrasah</td>
                                    </tr>
                                    <tr>
                                        <th>Masa Jabatan</th>
                                        <td>{{ $head_period }}</td>
                                    </tr>
                                    <tr>
                                        <th>NIP</th>
                                        <td>{{ $head_nip }}</td>
                                    </tr>
                                    <tr>
                                        <th>Email</th>
                                        <td><a href="mailto:{{ $head_email }}">{{ $head_email }}</a></td>
                                    </tr>
                                    <tr>
                                        <th>Telepon</th>
                                        <td><a
                                                href="tel:{{ preg_replace('/\D/', '', $head_phone) }}">{{ $head_phone }}</a>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <div class="col-lg-6">
                    <div class="p-4 rounded-3 shadow-sm bg-white h-100">
                        <h5 class="mb-3">Riwayat Singkat</h5>
                        <ul class="m-0" style="line-height:1.9;">
                            <li><i class="far fa-check-circle me-2 text-success"></i>Pendidikan terakhir: <em>(isi dari data
                                    resmi)</em></li>
                            <li><i class="far fa-check-circle me-2 text-success"></i>Pengalaman mengajar & kepemimpinan
                                madrasah <em>(ringkas)</em></li>
                            <li><i class="far fa-check-circle me-2 text-success"></i>Fokus pengembangan: Tahfiz/BTQ, GLM,
                                GCC, layanan ramah anak</li>
                            <li><i class="far fa-check-circle me-2 text-success"></i>Kolaborasi aktif dengan komite & orang
                                tua/wali</li>
                        </ul>
                        <a href="/profil" class="theme-btn mt-3">Profil Madrasah<i
                                class="fas fa-arrow-right-long ms-2"></i></a>
                    </div>
                </div>
            </div>

        </div>
    </div>

    {{-- Opsional gaya kecil untuk rasio 3x4 kalau belum pakai Bootstrap 5 --}}
    <style>
        .ratio.ratio-3x4 {
            position: relative;
            width: 100%;
            padding-top: 133.333%;
        }

        .ratio.ratio-3x4>img {
            position: absolute;
            inset: 0;
        }
    </style>
@endsection

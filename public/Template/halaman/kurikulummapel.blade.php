@extends('index')

@section('main-content')
    <div class="py-120">
        <div class="container">

            {{-- Heading --}}
            <div class="row">
                <div class="col-lg-8 mx-auto">
                    <div class="site-heading text-center">
                        <span class="site-title-tagline"><i class="fa fa-book-open-reader"></i> Kurikulum</span>
                        <h2 class="site-title">Kurikulum & <span>Mata Pelajaran</span></h2>
                        <p>Struktur pembelajaran MI Al-Hikmah yang religius, ramah anak, dan menumbuhkan literasi–numerasi.
                        </p>
                    </div>
                </div>
            </div>

            {{-- Ringkasan Kurikulum + Unduhan --}}
            <div class="row g-4 align-items-start">
                <div class="col-lg-8">
                    <div class="p-4 rounded-3 shadow-sm bg-white">
                        <h5 class="mb-2">Ringkasan Kurikulum</h5>
                        <p class="mb-3">
                            MI Al-Hikmah menerapkan kurikulum madrasah yang terintegrasi dengan penguatan karakter,
                            tahfiz/BTQ, serta pembiasaan ibadah harian. Pembelajaran dirancang aktif, kolaboratif, dan
                            kontekstual
                            untuk menguatkan fondasi <em>literasi</em> dan <em>numerasi</em> sejak dini.
                        </p>
                        <ul class="m-0" style="line-height:1.9;">
                            <li><i class="far fa-check-circle me-2 text-success"></i> Integrasi
                                <strong>intrakurikuler</strong>, <strong>kokurikuler</strong> (projek/pendalaman), dan
                                <strong>ekstrakurikuler</strong>.</li>
                            <li><i class="far fa-check-circle me-2 text-success"></i> Penguatan kekhasan madrasah:
                                <strong>Tahfiz & BTQ</strong>, pembiasaan ibadah, akhlak mulia.</li>
                            <li><i class="far fa-check-circle me-2 text-success"></i> Budaya sekolah: <strong>Gerakan
                                    Literasi</strong> & <strong>Clinic Numerasi</strong>, sekolah ramah anak.</li>
                        </ul>
                    </div>
                </div>

                <div class="col-lg-4">
                    <div class="p-4 rounded-3 shadow-sm bg-white h-100">
                        <h5 class="mb-3">Dokumen Pembelajaran</h5>
                        <div class="d-grid gap-2">
                            <a class="theme-btn" href="/unduhan/silabus">Silabus<i
                                    class="fas fa-arrow-right-long ms-2"></i></a>
                            <a class="theme-btn" href="/unduhan/prota">Program Tahunan (Prota)<i
                                    class="fas fa-arrow-right-long ms-2"></i></a>
                            <a class="theme-btn" href="/unduhan/prosem">Program Semester (Prosem)<i
                                    class="fas fa-arrow-right-long ms-2"></i></a>
                            <a class="theme-btn" href="/unduhan/kkm">KKTP/KKM<i
                                    class="fas fa-arrow-right-long ms-2"></i></a>
                            <a class="theme-btn theme-btn2" href="/unduhan">Semua Dokumen<i
                                    class="fas fa-arrow-right-long ms-2"></i></a>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Daftar Mapel (kelompok) --}}
            @php
                // Bisa di-override dari controller sesuai struktur madrasah
                $mapel_keagamaan = $mapel_keagamaan ?? [
                    'Al-Qur’an Hadits',
                    'Akidah Akhlak',
                    'Fikih',
                    'Sejarah Kebudayaan Islam (SKI)',
                    'Bahasa Arab',
                    'Tahfiz & BTQ',
                ];
                $mapel_umum = $mapel_umum ?? [
                    'Pendidikan Pancasila & Kewarganegaraan (PPKn)',
                    'Bahasa Indonesia',
                    'Matematika',
                    'IPAS (Ilmu Pengetahuan Alam & Sosial)',
                    'Pendidikan Jasmani, Olahraga, & Kesehatan (PJOK)',
                    'Seni Budaya & Prakarya (SBdP)',
                    'Muatan Lokal (mis. Bahasa Daerah)',
                    'Informatika/TIK (opsional sesuai kondisi)',
                ];
            @endphp

            <div class="row g-4 mt-2">
                <div class="col-lg-6">
                    <div class="p-4 rounded-3 shadow-sm bg-white h-100">
                        <h5 class="mb-3"><i class="far fa-mosque me-2"></i> Kelompok Keagamaan</h5>
                        <ul class="m-0" style="columns:2; column-gap:24px; line-height:1.9;">
                            @foreach ($mapel_keagamaan as $m)
                                <li>{{ $m }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="p-4 rounded-3 shadow-sm bg-white h-100">
                        <h5 class="mb-3"><i class="far fa-books me-2"></i> Kelompok Umum & Keterampilan</h5>
                        <ul class="m-0" style="columns:2; column-gap:24px; line-height:1.9;">
                            @foreach ($mapel_umum as $m)
                                <li>{{ $m }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>

            {{-- Alokasi Waktu (contoh dapat disesuaikan) --}}
            @php
                // Alokasi contoh per minggu (JP = 35 menit). Ubah dari controller jika ada SK terbaru.
                $alokasi = $alokasi ?? [
                    'I' => [
                        'PPKn' => 4,
                        'B. Indonesia' => 7,
                        'Matematika' => 6,
                        'IPAS Dasar' => 3,
                        'SBdP' => 3,
                        'PJOK' => 4,
                        'PAI (QH, AA, Fikih, SKI)' => 6,
                        'Bahasa Arab' => 2,
                        'BTQ/Tahfiz' => 3,
                    ],
                    'II' => [
                        'PPKn' => 4,
                        'B. Indonesia' => 7,
                        'Matematika' => 6,
                        'IPAS Dasar' => 3,
                        'SBdP' => 3,
                        'PJOK' => 4,
                        'PAI (QH, AA, Fikih, SKI)' => 6,
                        'Bahasa Arab' => 2,
                        'BTQ/Tahfiz' => 3,
                    ],
                    'III' => [
                        'PPKn' => 4,
                        'B. Indonesia' => 7,
                        'Matematika' => 6,
                        'IPAS Dasar' => 4,
                        'SBdP' => 3,
                        'PJOK' => 4,
                        'PAI (QH, AA, Fikih, SKI)' => 6,
                        'Bahasa Arab' => 2,
                        'BTQ/Tahfiz' => 3,
                    ],
                    'IV' => [
                        'PPKn' => 4,
                        'B. Indonesia' => 6,
                        'Matematika' => 6,
                        'IPAS' => 5,
                        'SBdP' => 3,
                        'PJOK' => 4,
                        'PAI (QH, AA, Fikih, SKI)' => 6,
                        'Bahasa Arab' => 2,
                        'BTQ/Tahfiz' => 3,
                    ],
                    'V' => [
                        'PPKn' => 4,
                        'B. Indonesia' => 6,
                        'Matematika' => 6,
                        'IPAS' => 5,
                        'SBdP' => 3,
                        'PJOK' => 4,
                        'PAI (QH, AA, Fikih, SKI)' => 6,
                        'Bahasa Arab' => 2,
                        'BTQ/Tahfiz' => 3,
                    ],
                    'VI' => [
                        'PPKn' => 4,
                        'B. Indonesia' => 6,
                        'Matematika' => 6,
                        'IPAS' => 5,
                        'SBdP' => 3,
                        'PJOK' => 4,
                        'PAI (QH, AA, Fikih, SKI)' => 6,
                        'Bahasa Arab' => 2,
                        'BTQ/Tahfiz' => 3,
                    ],
                ];
                $total_jp = function ($arr) {
                    return array_sum($arr);
                };
            @endphp

            <div class="p-4 mt-4 rounded-3 shadow-sm bg-white">
                <div class="d-flex align-items-center justify-content-between mb-3">
                    <h5 class="mb-0">Alokasi Waktu per Minggu <small class="text-muted">(contoh, JP = 35 menit)</small>
                    </h5>
                    <a href="/unduhan/alokasi-waktu" class="theme-btn theme-btn2">Unduhan Alokasi<i
                            class="fas fa-arrow-right-long ms-2"></i></a>
                </div>

                <div class="table-responsive">
                    <table class="table table-sm align-middle">
                        <thead>
                            <tr>
                                <th>Kelas</th>
                                @php $headers = array_keys(reset($alokasi)); @endphp
                                @foreach ($headers as $h)
                                    <th class="text-nowrap">{{ $h }}</th>
                                @endforeach
                                <th>Total JP</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($alokasi as $kelas => $row)
                                <tr>
                                    <td><strong>{{ $kelas }}</strong></td>
                                    @foreach ($headers as $h)
                                        <td class="text-center">{{ $row[$h] ?? '—' }}</td>
                                    @endforeach
                                    <td class="text-center"><strong>{{ $total_jp($row) }}</strong></td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <small class="text-muted d-block mt-2">
                    * Angka di atas adalah contoh yang <em>dapat disesuaikan</em> dengan ketentuan madrasah & regulasi
                    terbaru.
                </small>
            </div>

            {{-- Penilaian & Program Penguat --}}
            <div class="row g-4 mt-2">
                <div class="col-lg-6">
                    <div class="p-4 rounded-3 shadow-sm bg-white h-100">
                        <h5 class="mb-3">Penilaian</h5>
                        <ul class="m-0" style="line-height:1.9;">
                            <li>Asesmen diagnostik awal untuk pemetaan kemampuan.</li>
                            <li>Penilaian formatif (harian/tugas/projek) & sumatif (PTS/PAS).</li>
                            <li>Penilaian sikap/karakter terintegrasi dalam pembiasaan ibadah & adab.</li>
                            <li>Portofolio karya & jurnal belajar untuk refleksi kemajuan siswa.</li>
                        </ul>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="p-4 rounded-3 shadow-sm bg-white h-100">
                        <h5 class="mb-3">Program Penguat</h5>
                        <ul class="m-0" style="line-height:1.9;">
                            <li>Tahfiz & BTQ (tahsin bacaan, setoran berkala, target juz bertahap).</li>
                            <li>Gerakan Literasi (15 menit membaca) & Klinik Numerasi.</li>
                            <li>Projek karakter & kepemimpinan melalui kegiatan kepramukaan/ekskul.</li>
                            <li>Green & Clean School (GCC) dan Sekolah Ramah Anak.</li>
                        </ul>
                    </div>
                </div>
            </div>

            {{-- Ekstrakurikuler --}}
            @php
                $ekskul = $ekskul ?? ['Pramuka', 'Hadroh', 'Kaligrafi', 'KIR / Sains', 'Olahraga', 'English Club'];
            @endphp
            <div class="p-4 mt-4 rounded-3 shadow-sm bg-white">
                <h5 class="mb-3">Ekstrakurikuler</h5>
                <div class="d-flex flex-wrap gap-2">
                    @foreach ($ekskul as $e)
                        <span class="badge rounded-pill"
                            style="background:#f3f4f6; color:#111827; font-weight:600;">{{ $e }}</span>
                    @endforeach
                </div>
            </div>

        </div>
    </div>

    {{-- gaya kecil agar tabel & badge rapi --}}
    <style>
        .badge {
            padding: .6rem .9rem;
        }

        .table thead th {
            white-space: nowrap;
        }
    </style>
@endsection

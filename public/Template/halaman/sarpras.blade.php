@extends('index')

@section('main-content')
    <div class="py-120">
        <div class="container">

            {{-- Heading --}}
            <div class="row">
                <div class="col-lg-8 mx-auto">
                    <div class="site-heading text-center">
                        <span class="site-title-tagline"><i class="fa fa-book-open-reader"></i> Sarana & Prasarana</span>
                        <h2 class="site-title">Fasilitas <span>MI Al-Hikmah</span></h2>
                        <p>Ringkasan fasilitas pembelajaran dan penunjang di MI Al-Hikmah untuk mendukung proses belajar
                            yang aman, nyaman, dan bermakna.</p>
                    </div>
                </div>
            </div>

            {{-- Ringkasan angka kunci --}}
            @php
                $rombel = $rombel ?? 6; // diketahui: 6 rombel
                $students = $students ?? null; // isi dari controller jika ada (mis. 194)
                $staff = $staff ?? null; // mis. 14
            @endphp
            <div class="row g-4 mb-4">
                <div class="col-md-4">
                    <div class="p-4 rounded-3 shadow-sm bg-white text-center">
                        <div class="mb-1 text-muted">Jumlah Rombel</div>
                        <h3 class="m-0">{{ $rombel }}</h3>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="p-4 rounded-3 shadow-sm bg-white text-center">
                        <div class="mb-1 text-muted">Siswa Aktif</div>
                        <h3 class="m-0">{{ $students ?? '—' }}</h3>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="p-4 rounded-3 shadow-sm bg-white text-center">
                        <div class="mb-1 text-muted">Guru & Tendik</div>
                        <h3 class="m-0">{{ $staff ?? '—' }}</h3>
                    </div>
                </div>
            </div>

            {{-- GRID fasilitas (kartu) --}}
            @php
                // daftar kartu bisa diubah dari controller (urut & ikon mengikuti aset template)
                $cards = $cards ?? [
                    [
                        'icon' => 'open-book.svg',
                        'title' => 'Ruang Kelas',
                        'desc' => "Tersedia {$rombel} ruang kelas yang nyaman dan terang.",
                    ],
                    [
                        'icon' => 'course-material.svg',
                        'title' => 'Perpustakaan & Pojok Baca',
                        'desc' => 'Koleksi bacaan siswa & pojok baca di tiap kelas.',
                    ],
                    [
                        'icon' => 'teacher-2.svg',
                        'title' => 'Ruang Guru & Kepala',
                        'desc' => 'Ruang kerja guru, kepala, serta TU/Operator.',
                    ],
                    [
                        'icon' => 'health.svg',
                        'title' => 'UKS & Keamanan Anak',
                        'desc' => 'Layanan kesehatan dasar & Satgas Anti-Bullying.',
                    ],
                    [
                        'icon' => 'monitor.svg',
                        'title' => 'Perangkat TIK',
                        'desc' => 'Perangkat presentasi & akses TIK terjadwal.',
                    ],
                    [
                        'icon' => 'data.svg',
                        'title' => 'Lapangan & Olahraga',
                        'desc' => 'Kegiatan senam, scout, dan permainan edukatif.',
                    ],
                    [
                        'icon' => 'global-education.svg',
                        'title' => 'Mushola / Pembiasaan Ibadah',
                        'desc' => 'Kegiatan salat berjamaah & tadarus harian.',
                    ],
                    [
                        'icon' => 'art.svg',
                        'title' => 'Seni & Ekstrakurikuler',
                        'desc' => 'Pramuka, hadroh, kaligrafi, dan kegiatan kreatif.',
                    ],
                    [
                        'icon' => 'money.svg',
                        'title' => 'Sanitasi & Kebersihan',
                        'desc' => 'Toilet terpisah, tempat cuci tangan, program GCC.',
                    ],
                ];
            @endphp

            <div class="row g-4">
                @foreach ($cards as $c)
                    <div class="col-sm-6 col-lg-4">
                        <div class="p-4 h-100 rounded-3 shadow-sm bg-white wow fadeInUp" data-wow-delay=".1s">
                            <div class="d-flex align-items-center mb-3">
                                <img src="{{ asset('assets/img/icon/' . $c['icon']) }}" alt=""
                                    style="width:40px;height:40px" class="me-3">
                                <h5 class="m-0">{{ $c['title'] }}</h5>
                            </div>
                            <p class="mb-0">{{ $c['desc'] }}</p>
                        </div>
                    </div>
                @endforeach
            </div>

            {{-- Tabel inventaris ringkas --}}
            @php
                // bisa diisi dari DB; default aman (silakan ganti jumlah pasti di controller)
                $sarpras = $sarpras ?? [
                    ['name' => 'Ruang Kelas', 'qty' => $rombel, 'cond' => 'Baik', 'note' => 'Meja-kursi proporsional'],
                    ['name' => 'Perpustakaan', 'qty' => '—', 'cond' => '—', 'note' => 'Koleksi bacaan siswa'],
                    ['name' => 'Ruang Guru', 'qty' => '1', 'cond' => '—', 'note' => '—'],
                    ['name' => 'Ruang Kepala', 'qty' => '1', 'cond' => '—', 'note' => '—'],
                    ['name' => 'TU/Operator', 'qty' => '1', 'cond' => '—', 'note' => 'Administrasi & Emis'],
                    ['name' => 'UKS', 'qty' => '1', 'cond' => '—', 'note' => 'P3K dasar'],
                    ['name' => 'Mushola', 'qty' => '1', 'cond' => '—', 'note' => 'Ibadah & tadarus'],
                    [
                        'name' => 'Perangkat TIK',
                        'qty' => '—',
                        'cond' => '—',
                        'note' => 'Laptop/Proyektor (jadwal pakai)',
                    ],
                    ['name' => 'Lapangan', 'qty' => '1', 'cond' => '—', 'note' => 'Olahraga & pramuka'],
                    ['name' => 'Toilet (L/P)', 'qty' => '—', 'cond' => '—', 'note' => 'Terpisah & bersih'],
                    ['name' => 'Tempat Cuci Tangan', 'qty' => '—', 'cond' => '—', 'note' => 'Setiap area strategis'],
                    ['name' => 'Gudang', 'qty' => '1', 'cond' => '—', 'note' => 'Perlengkapan sekolah'],
                ];
            @endphp

            <div class="row g-4 mt-2">
                <div class="col-12">
                    <div class="p-4 rounded-3 shadow-sm bg-white">
                        <div class="d-flex align-items-center justify-content-between mb-3">
                            <h5 class="mb-0">Inventaris Ringkas</h5>
                            <a href="/unduhan" class="theme-btn theme-btn2">Unduhan Daftar Inventaris<i
                                    class="fas fa-arrow-right-long ms-2"></i></a>
                        </div>
                        <div class="table-responsive">
                            <table class="table table-sm align-middle">
                                <thead>
                                    <tr>
                                        <th>Fasilitas</th>
                                        <th>Jumlah</th>
                                        <th>Kondisi</th>
                                        <th>Keterangan</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($sarpras as $row)
                                        <tr>
                                            <td>{{ $row['name'] }}</td>
                                            <td>{{ $row['qty'] }}</td>
                                            <td>{{ $row['cond'] }}</td>
                                            <td>{{ $row['note'] }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <small class="text-muted d-block mt-2">* Data di atas bersifat ringkas. Versi lengkap dan terbaru
                            mengikuti dokumen inventaris resmi madrasah.</small>
                    </div>
                </div>
            </div>

        </div>
    </div>
@endsection

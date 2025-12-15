@extends('index')

@section('main-content')
    <div class="py-120">
        <div class="container">

            {{-- Heading --}}
            <div class="row">
                <div class="col-lg-8 mx-auto">
                    <div class="site-heading text-center">
                        <span class="site-title-tagline"><i class="fa fa-user-plus"></i> PPDB</span>
                        <h2 class="site-title">Syarat & <span>Alur Pendaftaran</span></h2>
                        <p>Informasi resmi penerimaan peserta didik baru MI Al-Hikmah: persyaratan, tahapan pendaftaran,
                            jadwal, dan kontak panitia.</p>
                    </div>
                </div>
            </div>

            <div class="row g-4 align-items-start">
                {{-- Kolom kiri: Syarat & Alur --}}
                <div class="col-lg-8">

                    {{-- SYARAT PENDAFTARAN --}}
                    <div class="p-4 rounded-3 shadow-sm bg-white mb-3">
                        <h5 class="mb-3"><i class="fa fa-clipboard-check me-2 text-success"></i> Syarat Pendaftaran</h5>

                        <div class="row g-3">
                            <div class="col-md-6">
                                <h6 class="mb-2">Administratif</h6>
                                <ul class="m-0" style="line-height:1.9;">
                                    <li>Fotokopi <strong>Akte Kelahiran</strong> (1 lembar)</li>
                                    <li>Fotokopi <strong>Kartu Keluarga</strong> (1 lembar)</li>
                                    <li>Fotokopi <strong>KTP Orang Tua/Wali</strong> (1 lembar)</li>
                                    <li>Pas foto terbaru <strong>3×4</strong> (2 lembar)</li>
                                    <li><em>Opsional:</em> KIP/PKH/Jamkes (jika ada)</li>
                                    <li><em>Khusus pindahan:</em> Surat Pindah & Rapor Terakhir</li>
                                </ul>
                            </div>
                            <div class="col-md-6">
                                <h6 class="mb-2">Ketentuan Usia & Akademik</h6>
                                <ul class="m-0" style="line-height:1.9;">
                                    <li>Usia minimal <strong>6 tahun</strong> per <strong>1 Juli</strong> tahun berjalan*
                                    </li>
                                    <li>Jalur khusus (<em>early entry</em>) mengikuti rekomendasi psikolog/sekolah</li>
                                    <li>Mengikuti observasi ringan kesiapan belajar</li>
                                    <li><em>Opsional:</em> Screening BTQ (untuk minat program Tahfiz)</li>
                                </ul>
                                <small class="text-muted d-block mt-1">* Mengacu ketentuan Kemenag/Disdik setempat</small>
                            </div>
                        </div>

                        <div class="alert alert-info d-flex align-items-start gap-2 mt-3 mb-0">
                            <i class="fa fa-info-circle mt-1"></i>
                            <div>
                                Berkas dapat diunggah saat daftar online atau dibawa ke loket PPDB. Pastikan seluruh salinan
                                <strong>terbaca jelas</strong>.
                            </div>
                        </div>
                    </div>

                    {{-- ALUR PENDAFTARAN --}}
                    <div class="p-4 rounded-3 shadow-sm bg-white">
                        <h5 class="mb-3"><i class="fa fa-route me-2 text-success"></i> Alur Pendaftaran</h5>

                        <ol class="ppdb-steps list-unstyled m-0">
                            <li class="step">
                                <div class="step-num">1</div>
                                <div class="step-body">
                                    <h6 class="mb-1">Isi Formulir</h6>
                                    <p class="mb-0">Calon peserta didik/ortu mengisi formulir <strong>online</strong> atau
                                        <strong>offline</strong> di sekolah.
                                    </p>
                                    <div class="d-flex gap-2 mt-2">
                                        <a href="{{ $ppdb_form ?? url('/ppdb/daftar') }}" class="theme-btn">Daftar Online<i
                                                class="fas fa-arrow-right-long ms-2"></i></a>
                                        <a href="{{ $brosur ?? url('/unduhan/brosur-ppdb') }}"
                                            class="theme-btn theme-btn2">Unduh Brosur<i
                                                class="fas fa-file-arrow-down ms-2"></i></a>
                                    </div>
                                </div>
                            </li>
                            <li class="step">
                                <div class="step-num">2</div>
                                <div class="step-body">
                                    <h6 class="mb-1">Verifikasi Dokumen</h6>
                                    <p class="mb-0">Panitia memeriksa berkas & menghubungi via
                                        <strong>WhatsApp/Email</strong> untuk konfirmasi.
                                    </p>
                                </div>
                            </li>
                            <li class="step">
                                <div class="step-num">3</div>
                                <div class="step-body">
                                    <h6 class="mb-1">Observasi/Wawancara</h6>
                                    <p class="mb-0">Observasi kesiapan belajar & wawancara singkat orang tua (screening
                                        BTQ bagi jalur Tahfiz).</p>
                                </div>
                            </li>
                            <li class="step">
                                <div class="step-num">4</div>
                                <div class="step-body">
                                    <h6 class="mb-1">Pengumuman & Daftar Ulang</h6>
                                    <p class="mb-0">Peserta yang diterima melakukan <strong>daftar ulang</strong>
                                        (administrasi awal, seragam, dsb.).</p>
                                    <a href="{{ $jadwal_link ?? url('/ppdb/jadwal') }}"
                                        class="small d-inline-flex align-items-center mt-1">
                                        Lihat jadwal lengkap <i class="fa fa-arrow-right ms-1"></i>
                                    </a>
                                </div>
                            </li>
                        </ol>
                    </div>

                    {{-- JADWAL & KUOTA --}}
                    <div class="row g-3 mt-3">
                        <div class="col-md-6">
                            <div class="p-4 rounded-3 shadow-sm bg-white h-100">
                                <h5 class="mb-2">Jadwal PPDB</h5>
                                <ul class="m-0" style="line-height:1.9;">
                                    <li>Pendaftaran: <strong>{{ $jadwal['daftar'] ?? '—' }}</strong></li>
                                    <li>Verifikasi: <strong>{{ $jadwal['verifikasi'] ?? '—' }}</strong></li>
                                    <li>Observasi: <strong>{{ $jadwal['observasi'] ?? '—' }}</strong></li>
                                    <li>Pengumuman: <strong>{{ $jadwal['pengumuman'] ?? '—' }}</strong></li>
                                    <li>Daftar Ulang: <strong>{{ $jadwal['daftar_ulang'] ?? '—' }}</strong></li>
                                </ul>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="p-4 rounded-3 shadow-sm bg-white h-100">
                                <h5 class="mb-2">Kuota & Biaya</h5>
                                <ul class="m-0" style="line-height:1.9;">
                                    <li>Kuota Kelas I: <strong>{{ $kuota ?? '—' }}</strong> siswa</li>
                                    <li>Biaya Pendaftaran: <strong>{{ $biaya['daftar'] ?? '—' }}</strong></li>
                                    <li>SPP/Bulanan: <strong>{{ $biaya['spp'] ?? '—' }}</strong></li>
                                    <li>Paket Seragam/Perlengkapan: <strong>{{ $biaya['paket'] ?? '—' }}</strong></li>
                                </ul>
                                <a href="{{ $biaya_link ?? url('/ppdb/biaya') }}" class="theme-btn mt-2">Detail Biaya<i
                                        class="fas fa-arrow-right-long ms-2"></i></a>
                            </div>
                        </div>
                    </div>

                </div>

                {{-- Kolom kanan: Info & Kontak --}}
                <div class="col-lg-4">
                    <div class="p-4 rounded-3 shadow-sm bg-white position-sticky" style="top: 90px;">
                        <h5 class="mb-3">Info & Kontak Panitia</h5>
                        <ul class="list-unstyled mb-3" style="line-height:1.9;">
                            <li><i class="fa fa-location-dot me-2 text-success"></i>Kp. Cihaneut RT 01/RW 11, Desa Drawati,
                                Paseh, Bandung</li>
                            <li><i class="far fa-envelope me-2 text-success"></i><a
                                    href="mailto:mialhikmahpaseh@gmail.com">mialhikmahpaseh@gmail.com</a></li>
                            <li><i class="fab fa-whatsapp me-2 text-success"></i><a
                                    href="https://wa.me/{{ $wa ?? '6281214447944' }}" target="_blank" rel="noopener">WA
                                    Panitia: {{ $wa_text ?? '0812-1444-7944' }}</a></li>
                            <li><i class="fa fa-phone me-2 text-success"></i><a
                                    href="tel:{{ $tel ?? '081214447944' }}">Telp: {{ $tel_text ?? '0812-1444-7944' }}</a>
                            </li>
                        </ul>
                        <h6 class="mb-2">Jam Layanan</h6>
                        <ul class="list-unstyled m-0" style="line-height:1.9;">
                            <li>Senin–Jumat: 08.00–14.00</li>
                            <li>Sabtu: 08.00–12.00</li>
                            <li>Minggu & Tanggal Merah: Tutup</li>
                        </ul>

                        <hr>

                        <div class="d-grid gap-2">
                            <a href="{{ $ppdb_form ?? url('/ppdb/daftar') }}" class="theme-btn">Daftar Online<i
                                    class="fas fa-arrow-right-long ms-2"></i></a>
                            <a href="{{ $panduan ?? url('/unduhan/panduan-ppdb') }}"
                                class="theme-btn theme-btn2">Panduan/FAQ PPDB<i class="fas fa-file-lines ms-2"></i></a>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>

    {{-- STEP STYLES --}}
    <style>
        .ppdb-steps .step {
            position: relative;
            display: flex;
            gap: 14px;
            padding: 14px 0;
        }

        .ppdb-steps .step+.step {
            border-top: 1px dashed rgba(0, 0, 0, .12);
        }

        .ppdb-steps .step-num {
            flex: 0 0 36px;
            height: 36px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            background: #16a34a;
            color: #fff;
            font-weight: 700;
            box-shadow: 0 6px 16px rgba(22, 163, 74, .25);
        }

        .ppdb-steps .step-body h6 {
            font-weight: 700;
        }
    </style>
@endsection

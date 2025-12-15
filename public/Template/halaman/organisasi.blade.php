@extends('index')

@section('main-content')
    <div class="py-120">
        <div class="container">

            {{-- Heading --}}
            <div class="row">
                <div class="col-lg-8 mx-auto">
                    <div class="site-heading text-center">
                        <span class="site-title-tagline"><i class="fa fa-book-open-reader"></i> Struktur Organisasi</span>
                        <h2 class="site-title">Struktur <span>Organisasi MI Al-Hikmah</span></h2>
                        <p>Bagan organisasi madrasah: pengawas, pimpinan, koordinator, wali kelas, serta dukungan
                            administrasi & komite.</p>
                    </div>
                </div>
            </div>

            @php
                // Nilai dapat di-override dari controller
                $pengawas = $pengawas ?? 'Ayi Ridwan';
                $kepala = $kepala ?? 'Ahmad Soleh, S.Pd.I';
                $waka_kur = $waka_kur ?? 'Wakil Kepala (Kurikulum)';
                $waka_siswa = $waka_siswa ?? 'Wakil Kepala (Kesiswaan)';
                $waka_sar = $waka_sar ?? 'Wakil Kepala (Sarpras/Humas)';
                $kor_tahfiz = $kor_tahfiz ?? 'Koordinator Tahfiz & BTQ';
                $kor_lit = $kor_lit ?? 'Koordinator Literasi & Numerasi';
                $kor_uks = $kor_uks ?? 'UKS & Satgas Anti-Bullying';
                $operator = $operator ?? 'Operator/Emis & TU';
                $komite = $komite ?? 'Iis Kurniasih (Ketua Komite)';

                $wali = $wali ?? [
                    ['kelas' => 'Kelas I', 'nama' => '—'],
                    ['kelas' => 'Kelas II', 'nama' => '—'],
                    ['kelas' => 'Kelas III', 'nama' => '—'],
                    ['kelas' => 'Kelas IV', 'nama' => '—'],
                    ['kelas' => 'Kelas V', 'nama' => '—'],
                    ['kelas' => 'Kelas VI', 'nama' => '—'],
                ];
            @endphp

            {{-- ORG CHART --}}
            <div class="row">
                <div class="col-12">
                    <div class="org-wrap">
                        <ul class="org">
                            <li>
                                <div class="node">
                                    <div class="node-title">Pengawas Madrasah</div>
                                    <div class="node-name">{{ $pengawas }}</div>
                                </div>

                                <ul>
                                    <li>
                                        <div class="node node-main">
                                            <div class="node-title">Kepala Madrasah</div>
                                            <div class="node-name">{{ $kepala }}</div>
                                        </div>

                                        <ul>
                                            <li>
                                                <div class="node">
                                                    <div class="node-title">Pimpinan</div>
                                                    <div class="node-name">
                                                        <div>{{ $waka_kur }}</div>
                                                        <div>{{ $waka_siswa }}</div>
                                                        <div>{{ $waka_sar }}</div>
                                                    </div>
                                                </div>
                                            </li>

                                            <li>
                                                <div class="node">
                                                    <div class="node-title">Koordinator Program</div>
                                                    <div class="node-name">
                                                        <div>{{ $kor_tahfiz }}</div>
                                                        <div>{{ $kor_lit }}</div>
                                                        <div>{{ $kor_uks }}</div>
                                                    </div>
                                                </div>
                                            </li>

                                            <li>
                                                <div class="node">
                                                    <div class="node-title">Administrasi</div>
                                                    <div class="node-name">{{ $operator }}</div>
                                                </div>
                                            </li>

                                            <li>
                                                <div class="node">
                                                    <div class="node-title">Komite Madrasah</div>
                                                    <div class="node-name">{{ $komite }}</div>
                                                </div>
                                            </li>

                                        </ul>
                                    </li>
                                </ul>

                            </li>
                        </ul>
                    </div>
                </div>
            </div>

            {{-- WALI KELAS --}}
            <div class="row mt-5">
                <div class="col-lg-8">
                    <div class="p-4 rounded-3 shadow-sm bg-white">
                        <h5 class="mb-3">Wali Kelas</h5>
                        <div class="table-responsive">
                            <table class="table table-sm align-middle">
                                <thead>
                                    <tr>
                                        <th style="min-width:120px;">Kelas</th>
                                        <th>Nama Wali</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($wali as $row)
                                        <tr>
                                            <td>{{ $row['kelas'] }}</td>
                                            <td>{{ $row['nama'] }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <a href="/unduhan" class="theme-btn mt-2">Unduh SK Struktur<i
                                class="fas fa-arrow-right-long ms-2"></i></a>
                    </div>
                </div>

                <div class="col-lg-4">
                    <div class="p-4 rounded-3 shadow-sm bg-white h-100">
                        <h5 class="mb-3">Catatan</h5>
                        <ul class="mb-0">
                            <li>Bagan dapat diperbarui sewaktu-waktu sesuai SK terbaru.</li>
                            <li>Struktur koordinator menyesuaikan program aktif (Tahfiz/BTQ, Literasi, UKS/Satgas).</li>
                            <li>Kontak humas untuk perubahan data GTK.</li>
                        </ul>
                    </div>
                </div>
            </div>

        </div>
    </div>

    {{-- Gaya ringan untuk bagan (bisa pindah ke app.css) --}}
    <style>
        /* === ORG CHART (versi stabil flexbox) === */
        .org-wrap {
            overflow-x: auto;
            padding: 8px 0;
        }

        .org,
        .org ul {
            margin: 0;
            padding: 0;
            list-style: none;
        }

        /* root center */
        .org {
            display: flex;
            justify-content: center;
        }

        /* baris anak-anak: rata tengah + jarak konsisten */
        .org>li>ul,
        .org ul ul {
            display: flex;
            justify-content: center;
            align-items: flex-start;
            gap: 24px;
            /* jarak antar node */
            padding-top: 20px;
            position: relative;
        }

        /* garis vertikal dari parent ke baris anak */
        .org>li>ul::before,
        .org ul ul::before {
            content: "";
            position: absolute;
            top: 0;
            left: 50%;
            transform: translateX(-50%);
            width: 1px;
            height: 20px;
            background: rgba(0, 0, 0, .15);
        }

        /* setiap node li */
        .org li {
            position: relative;
            padding-top: 20px;
            flex: 0 0 auto;
            /* lebar mengikuti kartu */
            text-align: center;
        }

        /* garis horizontal penghubung antar-sibling */
        .org li::before,
        .org li::after {
            content: "";
            position: absolute;
            top: 0;
            width: 50%;
            height: 1px;
            border-top: 1px solid rgba(0, 0, 0, .15);
        }

        .org li::before {
            left: -50%;
        }

        .org li::after {
            right: -50%;
        }

        /* hapus garis di ujung kiri/kanan dan single-child */
        .org li:only-child::before,
        .org li:only-child::after {
            display: none;
        }

        .org li:first-child::before {
            display: none;
        }

        .org li:last-child::after {
            display: none;
        }

        /* kartu */
        .node {
            display: inline-block;
            min-width: 220px;
            max-width: 280px;
            background: #fff;
            border: 1px solid rgba(0, 0, 0, .08);
            border-radius: 12px;
            box-shadow: 0 6px 16px rgba(0, 0, 0, .06);
            padding: 12px 14px;
        }

        .node-main {
            border: 1px solid rgba(22, 163, 74, .25);
            box-shadow: 0 10px 24px rgba(22, 163, 74, .12);
        }

        .node-title {
            font-weight: 700;
            font-size: .95rem;
            margin-bottom: 4px;
        }

        .node-name {
            font-size: .95rem;
            line-height: 1.5;
        }

        .node-name>div+div {
            margin-top: 2px;
        }

        /* responsive: biar muat, aktifkan scroll-x di wrapper */
        @media (max-width:576px) {
            .node {
                min-width: 200px;
            }
        }
    </style>
@endsection

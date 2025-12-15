@extends('index')

@section('main-content')
    <div class="py-120">
        <div class="container">

            {{-- Heading --}}
            <div class="row">
                <div class="col-lg-9 mx-auto">
                    <div class="site-heading text-center">
                        <span class="site-title-tagline"><i class="fa fa-calendar-check"></i> PPDB</span>
                        <h2 class="site-title">Jadwal & <span>Kuota</span> PPDB</h2>
                        <p>Informasi resmi penerimaan peserta didik baru MI Al-Hikmah untuk Tahun Ajaran
                            {{ $tahun_ajar ?? '2026/2027' }}.</p>
                    </div>
                </div>
            </div>

            {{-- Filter Tahun Ajaran (opsional) --}}
            @if (!empty($tahun_list))
                <div class="row g-3 mb-3">
                    <div class="col-md-4 ms-auto">
                        <form method="get" action="">
                            <div class="input-group">
                                <span class="input-group-text"><i class="far fa-calendar"></i></span>
                                <select name="ta" class="form-select" onchange="this.form.submit()">
                                    @foreach ($tahun_list as $ta)
                                        <option value="{{ $ta }}"
                                            {{ request('ta', $tahun_ajar ?? '') == $ta ? 'selected' : '' }}>
                                            {{ $ta }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </form>
                    </div>
                </div>
            @endif

            {{-- Jadwal PPDB (kartu tahap) --}}
            @php
                $jadwal = $jadwal ?? [
                    [
                        'stage' => 'Pendaftaran',
                        'range' => '1 Feb – 31 Mar 2026',
                        'desc' => 'Daftar online/offline & unggah berkas',
                    ],
                    [
                        'stage' => 'Verifikasi',
                        'range' => 'H+1 s.d. H+3 (rolling)',
                        'desc' => 'Validasi berkas & konfirmasi WA/Email',
                    ],
                    [
                        'stage' => 'Observasi',
                        'range' => 'Setiap Sabtu, 09.00–11.00',
                        'desc' => 'Observasi siswa & wawancara orang tua',
                    ],
                    [
                        'stage' => 'Pengumuman',
                        'range' => 'Setiap Senin, 12.00',
                        'desc' => 'Hasil seleksi & penempatan sementara',
                    ],
                    [
                        'stage' => 'Daftar Ulang',
                        'range' => 'Maks. 7 hari setelah pengumuman',
                        'desc' => 'Administrasi awal & pengukuran seragam',
                    ],
                ];
            @endphp

            <div class="row g-4 mb-4">
                @foreach ($jadwal as $i => $j)
                    <div class="col-sm-6 col-lg-4">
                        <div class="p-4 rounded-3 shadow-sm bg-white h-100 wow fadeInUp"
                            data-wow-delay=".{{ 15 + ($i % 4) * 15 }}s">
                            <div class="d-flex align-items-center gap-2 mb-1">
                                <i class="fa fa-flag-checkered text-success"></i>
                                <h6 class="m-0">{{ $j['stage'] }}</h6>
                            </div>
                            <div class="small text-muted mb-2"><i class="fa fa-calendar me-1"></i>{{ $j['range'] }}</div>
                            <p class="mb-0">{{ $j['desc'] }}</p>
                        </div>
                    </div>
                @endforeach
            </div>

            {{-- Ringkas Kuota --}}
            @php
                /* Kuota per rombel: rombel, kapasitas, terisi */
                $kuota_rombel = $kuota_rombel ?? [
                    ['rombel' => 'Kelas I A', 'kapasitas' => 28, 'terisi' => 17],
                    ['rombel' => 'Kelas I B', 'kapasitas' => 28, 'terisi' => 14],
                ];
                $cap = array_sum(array_column($kuota_rombel, 'kapasitas'));
                $fill = array_sum(array_column($kuota_rombel, 'terisi'));
                $left = max($cap - $fill, 0);
                $pct = $cap ? round(($fill / $cap) * 100) : 0;
                $last_update = $last_update ?? null; // '2025-10-01'
            @endphp

            <div class="row g-4 mb-3">
                <div class="col-6 col-lg-3">
                    <div class="p-4 rounded-3 shadow-sm bg-white text-center">
                        <div class="mb-1 text-muted">Total Kuota</div>
                        <h3 class="m-0">{{ $cap }}</h3>
                    </div>
                </div>
                <div class="col-6 col-lg-3">
                    <div class="p-4 rounded-3 shadow-sm bg-white text-center">
                        <div class="mb-1 text-muted">Terisi</div>
                        <h3 class="m-0">{{ $fill }}</h3>
                    </div>
                </div>
                <div class="col-6 col-lg-3">
                    <div class="p-4 rounded-3 shadow-sm bg-white text-center">
                        <div class="mb-1 text-muted">Sisa</div>
                        <h3 class="m-0">{{ $left }}</h3>
                    </div>
                </div>
                <div class="col-6 col-lg-3">
                    <div class="p-4 rounded-3 shadow-sm bg-white text-center">
                        <div class="mb-1 text-muted">Persentase Terisi</div>
                        <h3 class="m-0">{{ $pct }}%</h3>
                    </div>
                </div>
            </div>

            {{-- Progress total --}}
            <div class="mb-4">
                <div class="progress-wrap rounded-3 shadow-sm bg-white p-3">
                    <div class="d-flex justify-content-between mb-1 small">
                        <span>Keterisian Kuota Tahun Ajaran {{ $tahun_ajar ?? '2026/2027' }}</span>
                        <span>{{ $pct }}%</span>
                    </div>
                    <div class="progress">
                        <div class="progress-bar" role="progressbar" style="width: {{ $pct }}%;"
                            aria-valuenow="{{ $pct }}" aria-valuemin="0" aria-valuemax="100"></div>
                    </div>
                    @if ($last_update)
                        <small class="text-muted d-block mt-1">Terakhir diperbarui:
                            {{ \Carbon\Carbon::parse($last_update)->translatedFormat('d M Y, H:i') }}</small>
                    @endif
                </div>
            </div>

            {{-- Tabel Kuota per Rombel --}}
            <div class="p-4 rounded-3 shadow-sm bg-white">
                <div class="d-flex align-items-center justify-content-between mb-3">
                    <h5 class="mb-0"><i class="fa fa-layer-group me-2 text-success"></i>Kuota Per Rombel</h5>
                    <a href="{{ url('/ppdb/daftar') }}" class="theme-btn">Daftar Online<i
                            class="fas fa-arrow-right-long ms-2"></i></a>
                </div>

                <div class="table-responsive">
                    <table class="table table-sm align-middle">
                        <thead>
                            <tr>
                                <th>Rombel</th>
                                <th class="text-center">Kapasitas</th>
                                <th class="text-center">Terisi</th>
                                <th class="text-center">Sisa</th>
                                <th style="min-width:220px;">Progress</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($kuota_rombel as $r)
                                @php
                                    $sisa = max(($r['kapasitas'] ?? 0) - ($r['terisi'] ?? 0), 0);
                                    $p = $r['kapasitas'] ?? 0 ? round(($r['terisi'] / $r['kapasitas']) * 100) : 0;
                                @endphp
                                <tr>
                                    <td><strong>{{ $r['rombel'] }}</strong></td>
                                    <td class="text-center">{{ $r['kapasitas'] }}</td>
                                    <td class="text-center">{{ $r['terisi'] }}</td>
                                    <td class="text-center">{{ $sisa }}</td>
                                    <td>
                                        <div class="progress mini">
                                            <div class="progress-bar {{ $p >= 90 ? 'bg-danger' : ($p >= 70 ? 'bg-warning' : 'bg-success') }}"
                                                role="progressbar" style="width: {{ $p }}%;"
                                                aria-valuenow="{{ $p }}" aria-valuemin="0" aria-valuemax="100">
                                                {{ $p }}%
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5">
                                        <div class="alert alert-info mb-0">Data kuota rombel belum tersedia.</div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="d-flex flex-wrap gap-2 mt-2 small">
                    <span class="badge rounded-pill" style="background:#e8f5e9; color:#166534;">Kuota longgar
                        (&lt;70%)</span>
                    <span class="badge rounded-pill" style="background:#fff7ed; color:#92400e;">Hampir penuh (70–89%)</span>
                    <span class="badge rounded-pill" style="background:#fee2e2; color:#991b1b;">Penuh/nyaris penuh
                        (≥90%)</span>
                </div>
            </div>

            {{-- CTA bawah --}}
            <div class="text-center mt-40">
                <a href="{{ url('/ppdb/daftar') }}" class="theme-btn">Daftar Sekarang<i
                        class="fas fa-arrow-right-long ms-2"></i></a>
                <a href="{{ url('/ppdb/panduan') }}" class="theme-btn theme-btn2 ms-2">Panduan & FAQ<i
                        class="far fa-file-lines ms-2"></i></a>
            </div>

        </div>
    </div>

    {{-- Style kecil untuk progress --}}
    <style>
        .progress {
            height: 12px;
            background: #eef2f7;
        }

        .progress.mini {
            height: 10px;
        }

        .progress-bar {
            transition: width .5s ease;
        }
    </style>
@endsection

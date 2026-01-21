<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Laporan Piutang</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { 
            font-family: 'Times New Roman', Times, serif; 
            font-size: 12pt; 
            line-height: 1.3;
            color: #000;
        }
        
        /* KOP SURAT - Sama seperti template Surat */
        .kop-surat { 
            width: 100%; 
            border-bottom: 3px double #000; 
            margin-bottom: 15px; 
            padding-bottom: 8px; 
        }
        .kop-table { width: 100%; border-collapse: collapse; }
        .kop-table td { vertical-align: middle; }
        .logo-cell { width: 15%; text-align: center; }
        .logo-img { max-height: 70px; width: auto; }
        .text-cell { width: 70%; text-align: center; }
        .kop-header1 { font-size: 12pt; font-weight: bold; text-transform: uppercase; margin: 0; }
        .kop-header2 { font-size: 14pt; font-weight: bold; text-transform: uppercase; margin: 0; }
        .kop-header3 { font-size: 12pt; font-weight: bold; margin: 0; }
        .kop-text { font-size: 10pt; margin: 0; }
        
        /* JUDUL */
        .judul { 
            text-align: center; 
            margin: 20px 0 15px 0; 
        }
        .judul h2 { 
            font-size: 13pt; 
            font-weight: bold;
            text-decoration: underline; 
            text-transform: uppercase;
            margin: 0;
        }
        .judul p { 
            font-size: 11pt; 
            margin-top: 3px; 
        }
        
        /* INFO */
        .info { 
            margin-bottom: 10px; 
            font-size: 11pt;
        }
        .info table { width: 100%; }
        .info td { padding: 2px 0; }
        .info-label { font-weight: bold; width: 120px; }
        
        /* TABLE */
        table.data { 
            width: 100%; 
            border-collapse: collapse; 
            margin-bottom: 15px;
        }
        table.data th { 
            background: #f0f0f0;
            font-weight: bold; 
            text-align: center; 
            text-transform: uppercase;
            font-size: 10pt;
            padding: 6px 4px;
            border: 1px solid #000;
        }
        table.data td { 
            border: 1px solid #000; 
            padding: 5px 6px;
            font-size: 10pt;
        }
        table.data td.center { text-align: center; }
        table.data td.right { text-align: right; }
        table.data tr:nth-child(even) { background: #f9f9f9; }
        .overdue { color: #900; font-weight: bold; }
        .sisa-cell { font-weight: bold; }
        
        /* REKAP */
        .rekap {
            margin: 15px 0;
            width: 100%;
            clear: both;
            text-align: left;
        }
        .rekap-table {
            width: 280px;
            border-collapse: collapse;
            border: 1px solid #000;
        }
        .rekap-table td {
            padding: 6px 10px;
            font-size: 11pt;
            border: 1px solid #000;
        }
        .rekap-header {
            background: #333;
            color: #fff;
            font-weight: bold;
            text-align: center;
        }
        .rekap-label { font-weight: bold; }
        .rekap-value { text-align: right; font-weight: bold; }
        
        /* TTD */
        .ttd-container { width: 100%; margin-top: 30px; page-break-inside: avoid; clear: both; }
        .ttd { 
            width: 200px; 
            text-align: center; 
            font-size: 11pt;
            margin-left: auto; /* Push to right */
        }
        .ttd p { margin: 2px 0; color: #000; }
        .ttd-space { height: 50px; }
        .ttd-nama { font-weight: bold; text-decoration: underline; color: #000; }
        .ttd-nip { font-size: 10pt; color: #000; }
        .clearfix::after { content: ""; clear: both; display: table; }
        
        .no-data {
            text-align: center;
            padding: 20px;
            font-style: italic;
            color: #666;
        }
    </style>
</head>
<body>
    {{-- KOP SURAT --}}
    <div class="kop-surat">
        <table class="kop-table">
            <tr>
                @if($lembaga->logo_kiri)
                <td class="logo-cell">
                    <img src="{{ public_path('storage/' . $lembaga->logo_kiri) }}" class="logo-img" alt="Logo">
                </td>
                @else
                <td class="logo-cell"></td>
                @endif
                <td class="text-cell">
                    @if($lembaga->kop_baris1) <h3 class="kop-header1">{{ $lembaga->kop_baris1 }}</h3> @endif
                    @if($lembaga->kop_baris2) <h2 class="kop-header2">{{ $lembaga->kop_baris2 }}</h2> @endif
                    @if($lembaga->kop_baris3) <h3 class="kop-header3">{{ $lembaga->kop_baris3 }}</h3> @endif
                    @if($lembaga->kop_baris4) <p class="kop-text">{{ $lembaga->kop_baris4 }}</p> @endif
                </td>
                @if($lembaga->logo_kanan)
                <td class="logo-cell">
                    <img src="{{ public_path('storage/' . $lembaga->logo_kanan) }}" class="logo-img" alt="Logo">
                </td>
                @else
                <td class="logo-cell"></td>
                @endif
            </tr>
        </table>
    </div>

    {{-- JUDUL --}}
    <div class="judul">
        <h2>Laporan Daftar Piutang Siswa</h2>
        <p>Periode: {{ $periodeText }}</p>
    </div>

    {{-- INFO --}}
    <div class="info">
        <table>
            <tr>
                <td class="info-label">Tanggal Cetak</td>
                <td>: {{ \Carbon\Carbon::now()->locale('id')->translatedFormat('l, d F Y') }}</td>
            </tr>
            <tr>
                <td class="info-label">Total Data</td>
                <td>: {{ count($tagihans) }} tagihan</td>
            </tr>
        </table>
    </div>

    {{-- TABLE --}}
    <table class="data">
        <thead>
            <tr>
                <th width="5%">No</th>
                <th width="12%">NIS</th>
                <th width="25%">Nama Siswa</th>
                <th width="10%">Kelas</th>
                <th width="20%">Jenis Tagihan</th>
                <th width="15%">Sisa Piutang</th>
                <th width="13%">Jatuh Tempo</th>
            </tr>
        </thead>
        <tbody>
            @forelse($tagihans as $i => $t)
            @php
                $isOverdue = $t->tenggat_waktu && \Carbon\Carbon::parse($t->tenggat_waktu)->isPast();
            @endphp
            <tr>
                <td class="center">{{ $i + 1 }}</td>
                <td>{{ $t->siswa->nis ?? '-' }}</td>
                <td>{{ $t->siswa->nama ?? '-' }}</td>
                <td class="center">{{ $t->siswa->kelas ?? '-' }}</td>
                <td>{{ $t->nama_tagihan }}</td>
                <td class="right sisa-cell">Rp {{ number_format($t->sisa ?? $t->jumlah, 0, ',', '.') }}</td>
                <td class="center {{ $isOverdue ? 'overdue' : '' }}">{{ $t->tenggat_waktu ? \Carbon\Carbon::parse($t->tenggat_waktu)->format('d/m/Y') : '-' }}</td>
            </tr>
            @empty
            <tr>
                <td colspan="7" class="no-data">— Tidak ada data piutang pada periode ini —</td>
            </tr>
            @endforelse
        </tbody>
    </table>

    {{-- REKAP --}}
    <div class="rekap clearfix">
        <table class="rekap-table">
            <tr>
                <td colspan="2" class="rekap-header">REKAPITULASI</td>
            </tr>
            <tr>
                <td class="rekap-label">Total Sisa Piutang</td>
                <td class="rekap-value">Rp {{ number_format($totalSisa, 0, ',', '.') }}</td>
            </tr>
        </table>
    </div>

    {{-- TTD --}}
    <div class="ttd-container clearfix">
        <div class="ttd">
            <p>{{ $lembaga->alamat ? explode(',', $lembaga->alamat)[0] : '................' }},</p>
            <p>{{ \Carbon\Carbon::now()->locale('id')->translatedFormat('d F Y') }}</p>
            <br>
            <p>Mengetahui,</p>
            <p>Kepala Madrasah</p>
            <div class="ttd-space"></div>
            <p class="ttd-nama">{{ $lembaga->kepala_sekolah ?? '................................' }}</p>
            <p class="ttd-nip">NIP. {{ $lembaga->nip_kepala_sekolah ?? '................................' }}</p>
        </div>
    </div>

</body>
</html>

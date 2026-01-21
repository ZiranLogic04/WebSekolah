<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Buku Agenda Surat</title>
    <style>
        body {
            font-family: "Times New Roman", serif;
            font-size: 12pt;
            color: #000;
        }

        @page {
            margin: 15mm 15mm 15mm 20mm;
        }

        /* KOP */
        .kop-table {
            width: 100%;
            border-collapse: collapse;
        }
        .kop-text {
            text-align: center;
            color: #1a3a5c;
        }
        .kop-instansi {
            font-size: 13pt;
            font-weight: bold;
        }
        .kop-nama {
            font-size: 16pt;
            font-weight: bold;
        }
        .kop-alamat {
            font-size: 10pt;
        }
        .kop-line {
            border-top: 3px solid #1a3a5c;
            margin-top: 10px;
        }
        .kop-line2 {
            border-top: 1px solid #1a3a5c;
            margin-top: 2px;
            margin-bottom: 18px;
        }

        /* JUDUL */
        .title {
            text-align: center;
            margin-bottom: 20px;
        }
        .title h2 {
            font-size: 16pt;
            font-weight: bold;
            text-decoration: underline;
            margin: 0;
            letter-spacing: 1px;
        }
        .title-sub {
            font-size: 12pt;
            margin-top: 6px;
        }

        /* INFO */
        .info td {
            padding: 3px 0;
            font-size: 12pt;
        }

        /* SECTION */
        .section-title {
            font-size: 12pt;
            font-weight: bold;
            margin: 22px 0 10px 0;
        }

        /* DATA TABLE */
        table.data {
            width: 100%;
            border-collapse: collapse;
        }
        table.data th {
            background-color: #1a3a5c;
            color: #ffffff;
            border: 1px solid #1a3a5c;
            padding: 7px;
            font-size: 11pt;
            font-weight: bold;
            text-align: center;
        }
        table.data td {
            border: 1px solid #333;
            padding: 6px 8px;
            font-size: 11pt;
        }

        /* REKAP */
        .rekap-box {
            width: 260px;
            border: 1px solid #333;
            padding: 12px 15px;
            font-size: 12pt;
            margin-top: 25px;
        }
        .rekap-row {
            margin: 5px 0;
        }
        .rekap-total {
            margin-top: 10px;
            padding-top: 8px;
            border-top: 1px solid #999;
            font-weight: bold;
        }

        /* TTD */
        .ttd {
            width: 100%;
            margin-top: 40px;
        }
    </style>
</head>
<body>

{{-- KOP SURAT --}}
<table class="kop-table">
    <tr>
        <td width="12%" align="center">
            @if($lembaga->logo_kiri)
            <img src="{{ public_path('storage/' . $lembaga->logo_kiri) }}" width="80" height="80">
            @endif
        </td>
        <td width="76%" class="kop-text">
            @if($lembaga->kop_baris1)<div class="kop-instansi">{{ strtoupper($lembaga->kop_baris1) }}</div>@endif
            @if($lembaga->kop_baris2)<div class="kop-nama">{{ strtoupper($lembaga->kop_baris2) }}</div>@endif
            @if($lembaga->kop_baris3)<div class="kop-alamat">{{ $lembaga->kop_baris3 }}</div>@endif
            @if($lembaga->kop_baris4)<div class="kop-alamat">{{ $lembaga->kop_baris4 }}</div>@endif
            @if($lembaga->kop_baris5)<div class="kop-alamat">{{ $lembaga->kop_baris5 }}</div>@endif
        </td>
        <td width="12%" align="center">
            @if($lembaga->logo_kanan)
            <img src="{{ public_path('storage/' . $lembaga->logo_kanan) }}" width="80" height="80">
            @endif
        </td>
    </tr>
</table>
<div class="kop-line"></div>
<div class="kop-line2"></div>

{{-- JUDUL --}}
<div class="title">
    <h2>LAPORAN REKAP SURAT</h2>
</div>

{{-- INFO --}}
<table class="info">
    <tr>
        <td width="110">Periode</td>
        <td width="10">:</td>
        <td>{{ \Carbon\Carbon::parse($startDate)->locale('id')->translatedFormat('d F Y') }} s.d. {{ \Carbon\Carbon::parse($endDate)->locale('id')->translatedFormat('d F Y') }}</td>
    </tr>
    <tr>
        <td>Jenis Laporan</td>
        <td>:</td>
        <td>@if($filterType === 'all') Semua Surat (Masuk & Keluar) @elseif($filterType === 'masuk') Surat Masuk @else Surat Keluar @endif</td>
    </tr>
    <tr>
        <td>Tanggal Cetak</td>
        <td>:</td>
        <td>{{ now()->locale('id')->translatedFormat('d F Y, H:i') }} WIB</td>
    </tr>
</table>

{{-- SURAT MASUK --}}
@if(in_array($filterType, ['all','masuk']))
<div class="section-title">@if($filterType === 'all')A. @endif DAFTAR SURAT MASUK</div>
<table class="data">
    <tr>
        <th width="5%">No</th>
        <th width="13%">Tanggal</th>
        <th width="17%">Nomor Surat</th>
        <th width="25%">Pengirim</th>
        <th width="40%">Perihal</th>
    </tr>
    @forelse($suratMasuk as $i => $s)
    <tr>
        <td align="center">{{ $i + 1 }}</td>
        <td>{{ \Carbon\Carbon::parse($s['date'])->format('d/m/Y') }}</td>
        <td>{{ $s['no_surat'] ?? '-' }}</td>
        <td>{{ $s['pengirim'] ?? '-' }}</td>
        <td>{{ $s['perihal'] ?? '-' }}</td>
    </tr>
    @empty
    <tr><td colspan="5" align="center"><em>Tidak ada data</em></td></tr>
    @endforelse
</table>
@endif

{{-- SURAT KELUAR --}}
@if(in_array($filterType, ['all','keluar']))
<div class="section-title">@if($filterType === 'all')B. @endif DAFTAR SURAT KELUAR</div>
<table class="data">
    <tr>
        <th width="5%">No</th>
        <th width="13%">Tanggal</th>
        <th width="17%">Nomor Surat</th>
        <th width="25%">Tujuan</th>
        <th width="40%">Perihal</th>
    </tr>
    @forelse($suratKeluar as $i => $s)
    <tr>
        <td align="center">{{ $i + 1 }}</td>
        <td>{{ \Carbon\Carbon::parse($s['date'])->format('d/m/Y') }}</td>
        <td>{{ $s['no_surat'] ?? '-' }}</td>
        <td>{{ $s['tujuan'] ?? '-' }}</td>
        <td>{{ $s['perihal'] ?? '-' }}</td>
    </tr>
    @empty
    <tr><td colspan="5" align="center"><em>Tidak ada data</em></td></tr>
    @endforelse
</table>
@endif

{{-- REKAP --}}
<div class="rekap-box" style="page-break-inside: avoid;">
    @if(in_array($filterType, ['all','masuk']))
    <div class="rekap-row">Jumlah Surat Masuk &nbsp;&nbsp;: <strong>{{ count($suratMasuk) }}</strong> surat</div>
    @endif
    @if(in_array($filterType, ['all','keluar']))
    <div class="rekap-row">Jumlah Surat Keluar : <strong>{{ count($suratKeluar) }}</strong> surat</div>
    @endif
    @if($filterType === 'all')
    <div class="rekap-total">TOTAL : {{ count($suratMasuk) + count($suratKeluar) }} surat</div>
    @endif
</div>

{{-- TTD --}}
<table class="ttd" style="page-break-inside: avoid;">
    <tr>
        <td width="55%"></td>
        <td width="45%" align="center">
            @php
                $alamatParts = $lembaga->alamat ? explode(',', $lembaga->alamat) : [];
                $kota = count($alamatParts) > 1 ? trim($alamatParts[1]) : (count($alamatParts) > 0 ? trim($alamatParts[0]) : 'Kota');
            @endphp
            {{ $kota }}, {{ now()->locale('id')->translatedFormat('d F Y') }}<br>
            Kepala Madrasah,<br><br><br><br>
            <strong><u>{{ $lembaga->kepala_sekolah ?? '........................' }}</u></strong><br>
            NIP. {{ $lembaga->nip_kepala_sekolah ?? '........................' }}
        </td>
    </tr>
</table>

</body>
</html>

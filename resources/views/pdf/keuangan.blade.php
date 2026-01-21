<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Laporan Keuangan</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { 
            font-family: 'Times New Roman', Times, serif; 
            font-size: 11pt; 
            line-height: 1.4;
            color: #000;
        }
        
        /* KOP SURAT */
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
        .kop-header1 { font-size: 11pt; font-weight: bold; text-transform: uppercase; margin: 0; }
        .kop-header2 { font-size: 14pt; font-weight: bold; text-transform: uppercase; margin: 0; }
        .kop-text { font-size: 10pt; margin: 2px 0; }
        
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
            margin-top: 5px; 
        }
        
        /* INFO */
        .info { 
            margin-bottom: 15px; 
            font-size: 11pt;
        }
        .info table { width: 100%; }
        .info td { padding: 2px 0; }
        .info-label { width: 120px; }
        
        /* TABLE */
        table.data { 
            width: 100%; 
            border-collapse: collapse; 
            margin-bottom: 15px;
        }
        table.data th { 
            background: #e8e8e8;
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
        
        /* REKAP */
        .rekap {
            clear: both;
            text-align: left;
            margin-top: 10px;
            page-break-inside: avoid;
        }
        .rekap-table { 
            width: 50%;
            margin-left: auto;
            border-collapse: collapse;
        }
        .rekap-table td { 
            padding: 4px 8px;
            font-size: 11pt;
        }
        .rekap-table .label { width: 150px; }
        .rekap-table .separator { width: 10px; }
        .rekap-table .total-row { 
            border-top: 1px solid #000;
            font-weight: bold;
        }
        
        /* TTD */
        .ttd-container {
            margin-top: 30px;
            width: 100%;
            page-break-inside: avoid;
        }
        .ttd {
            width: 45%;
            margin-left: auto;
            text-align: center;
        }
        .ttd-line {
            border-bottom: 1px solid #000;
            margin: 50px auto 5px auto;
            width: 200px;
        }
        .ttd-name { font-weight: bold; text-transform: uppercase; }
        .ttd-nip { font-size: 10pt; }
    </style>
</head>
<body>
    <!-- KOP SURAT -->
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
                    @if($lembaga->kop_baris1) <p class="kop-header1">{{ $lembaga->kop_baris1 }}</p> @endif
                    @if($lembaga->kop_baris2) <p class="kop-header2">{{ $lembaga->kop_baris2 }}</p> @endif
                    @if($lembaga->kop_baris3) <p class="kop-header1">{{ $lembaga->kop_baris3 }}</p> @endif
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
    
    <!-- JUDUL -->
    <div class="judul">
        <h2>Laporan Keuangan Sekolah</h2>
        <p>Periode: {{ $periode }}</p>
    </div>
    
    <!-- INFO -->
    <div class="info">
        <table>
            <tr>
                <td class="info-label">Tanggal Cetak</td>
                <td>: {{ $tanggalCetak }}</td>
            </tr>
        </table>
    </div>
    
    <!-- TABEL BUKU KAS UMUM -->
    <table class="data">
        <thead>
            <tr>
                <th style="width: 5%;">No</th>
                <th style="width: 14%;">Tanggal</th>
                <th>Uraian</th>
                <th style="width: 15%;">Pemasukan</th>
                <th style="width: 15%;">Pengeluaran</th>
                <th style="width: 15%;">Saldo</th>
            </tr>
        </thead>
        <tbody>
            <!-- Saldo Awal -->
            <tr>
                <td class="center">-</td>
                <td class="center">{{ $startDate }}</td>
                <td><strong>Saldo Awal</strong></td>
                <td class="right">-</td>
                <td class="right">-</td>
                <td class="right"><strong>{{ $saldoAwalFormatted }}</strong></td>
            </tr>
            @php $no = 1; $runningBalance = $saldoAwal; @endphp
            @foreach($transaksi as $item)
                @php
                    $masuk = $item->tipe === 'Masuk' ? $item->jumlah : 0;
                    $keluar = $item->tipe === 'Keluar' ? $item->jumlah : 0;
                    $runningBalance += $masuk - $keluar;
                @endphp
                <tr>
                    <td class="center">{{ $no++ }}</td>
                    <td class="center">{{ \Carbon\Carbon::parse($item->tanggal)->format('d/m/Y') }}</td>
                    <td>
                        @if($item->kategori && is_object($item->kategori))
                            {{ $item->kategori->nama }}
                        @elseif($item->kategori && is_string($item->kategori))
                            {{ $item->kategori }}
                        @else
                            Lainnya
                        @endif
                    </td>
                    <td class="right">{{ $masuk > 0 ? 'Rp ' . number_format($masuk, 0, ',', '.') : '-' }}</td>
                    <td class="right">{{ $keluar > 0 ? 'Rp ' . number_format($keluar, 0, ',', '.') : '-' }}</td>
                    <td class="right">Rp {{ number_format($runningBalance, 0, ',', '.') }}</td>
                </tr>
            @endforeach
            @if(count($transaksi) === 0)
                <tr>
                    <td colspan="6" class="center" style="padding: 20px;">Tidak ada transaksi pada periode ini.</td>
                </tr>
            @endif
        </tbody>
    </table>
    
    <!-- REKAPITULASI -->
    <div class="rekap">
        <table class="rekap-table">
            <tr>
                <td class="label">Total Pemasukan</td>
                <td class="separator">:</td>
                <td class="right">Rp {{ number_format($totalPemasukan, 0, ',', '.') }}</td>
            </tr>
            <tr>
                <td class="label">Total Pengeluaran</td>
                <td class="separator">:</td>
                <td class="right">Rp {{ number_format($totalPengeluaran, 0, ',', '.') }}</td>
            </tr>
            <tr class="total-row">
                <td class="label">Saldo Akhir</td>
                <td class="separator">:</td>
                <td class="right">Rp {{ number_format($saldoAkhir, 0, ',', '.') }}</td>
            </tr>
        </table>
    </div>
    
    <!-- TANDA TANGAN -->
    <div class="ttd-container">
        <table style="width: 100%; margin-top: 40px;">
            <tr>
                <td style="width: 50%; text-align: center; vertical-align: top;">
                    <p>Mengetahui,</p>
                    <p>Kepala Sekolah</p>
                    <br><br><br><br>
                    <p class="ttd-name">{{ $lembaga->kepala_sekolah ?? '(................................)' }}</p>
                    <p class="ttd-nip">NIP. {{ $lembaga->nip_kepala ?? '................................' }}</p>
                </td>
                <td style="width: 50%; text-align: center; vertical-align: top;">
                    <p>{{ $lembaga->kota ?? 'Kota' }}, {{ $tanggalCetak }}</p>
                    <p>Bendahara Sekolah</p>
                    <br><br><br><br>
                    <p class="ttd-name">{{ $bendahara ?? '(................................)' }}</p>
                    <p class="ttd-nip">NIP. ................................</p>
                </td>
            </tr>
        </table>
    </div>
</body>
</html>

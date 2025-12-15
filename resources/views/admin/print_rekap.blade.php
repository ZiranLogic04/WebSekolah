<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rekap Surat - {{ $lembaga->nama_sekolah ?? 'Sekolah' }}</title>
    <style>
        body { font-family: 'Times New Roman', serif; font-size: 12pt; }
        .header { text-align: center; margin-bottom: 20px; border-bottom: 3px double black; padding-bottom: 10px; }
        .header h3, .header h4 { margin: 0; text-transform: uppercase; }
        .meta { margin-bottom: 20px; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
        th, td { border: 1px solid black; padding: 5px 8px; font-size: 11pt; }
        th { background-color: #f0f0f0; }
        .text-center { text-align: center; }
        @media print {
            .no-print { display: none; }
        }
    </style>
</head>
<body onload="window.print()">

    <div class="no-print" style="margin-bottom: 20px;">
        <button onclick="window.print()">Cetak Halaman</button>
        <button onclick="window.close()">Tutup</button>
    </div>

    <div class="header">
        @if($lembaga)
        <h3>{{ $lembaga->kop_baris1 }}</h3>
        <h2>{{ $lembaga->kop_baris2 }}</h2>
        <p>{{ $lembaga->alamat }}</p>
        @else 
        <h2>BUKU AGENDA SURAT</h2>
        @endif
    </div>

    <div class="meta">
        <strong>Laporan:</strong> Rekapitulasi Surat {{ ucfirst($filterType) }}<br>
        <strong>Periode:</strong> {{ \Carbon\Carbon::parse($startDate)->format('d M Y') }} s/d {{ \Carbon\Carbon::parse($endDate)->format('d M Y') }}
    </div>

    <table>
        <thead>
            <tr>
                <th width="5%">No</th>
                <th width="15%">Tanggal</th>
                <th width="20%">Nomor Surat</th>
                <th width="20%">Jenis/Asal</th>
                <th width="40%">Perihal</th>
            </tr>
        </thead>
        <tbody>
            @foreach($data as $index => $item)
            <tr>
                <td class="text-center">{{ $index + 1 }}</td>
                <td class="text-center">{{ \Carbon\Carbon::parse($item['date'])->format('d/m/Y') }}</td>
                <td>{{ $item['no_surat'] }}</td>
                <td>
                    <b>{{ $item['jenis'] }}</b><br>
                    <small>{{ $item['pengirim_penerima'] }}</small>
                </td>
                <td>{{ $item['perihal'] }}</td>
            </tr>
            @endforeach
            @if(count($data) == 0)
            <tr>
                <td colspan="5" class="text-center">Tidak ada data surat pada periode ini.</td>
            </tr>
            @endif
        </tbody>
    </table>

    <div style="float: right; margin-top: 30px; text-align: right;">
        <p>Dicetak pada: {{ date('d F Y') }}</p>
        <br><br><br>
        <p><strong>(Administrator)</strong></p>
    </div>

</body>
</html>

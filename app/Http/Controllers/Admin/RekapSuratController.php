<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Surat; // Surat Keluar
use App\Models\SuratMasuk;
use Inertia\Inertia;
use App\Models\Lembaga;

class RekapSuratController extends Controller
{
    public function index(Request $request)
    {
        // filter
        $startDate = $request->input('start_date', date('Y-m-01'));
        $endDate = $request->input('end_date', date('Y-m-t'));
        $filterType = $request->input('type', 'all'); // all, masuk, keluar

        $data = $this->fetchData($startDate, $endDate, $filterType);

        return Inertia::render('Admin/RekapSurat', [
            'data' => $data,
            'filters' => [
                'start_date' => $startDate,
                'end_date' => $endDate,
                'type' => $filterType
            ]
        ]);
    }

    public function print(Request $request)
    {
        $startDate = $request->input('start_date', date('Y-m-01'));
        $endDate = $request->input('end_date', date('Y-m-t'));
        $filterType = $request->input('type', 'all');

        $dataResult = $this->fetchDataSeparated($startDate, $endDate, $filterType);
        $lembaga = Lembaga::first();
        if (!$lembaga) $lembaga = new Lembaga();

        // Generate PDF using mPDF
        $mpdf = new \Mpdf\Mpdf([
            'mode' => 'utf-8',
            'format' => 'A4',
            'orientation' => 'P',
            'margin_top' => 15,
            'margin_bottom' => 15,
            'margin_left' => 15,
            'margin_right' => 15,
        ]);

        $suratMasuk = $dataResult['masuk'];
        $suratKeluar = $dataResult['keluar'];
        
        $html = view('pdf.rekap_surat', compact('suratMasuk', 'suratKeluar', 'startDate', 'endDate', 'filterType', 'lembaga'))->render();
        $mpdf->WriteHTML($html);
        
        // Save PDF to storage
        $filename = 'rekap-surat-' . md5($startDate . $endDate . $filterType) . '.pdf';
        $path = storage_path('app/public/rekap-surat/' . $filename);
        
        // Ensure directory exists
        if (!file_exists(storage_path('app/public/rekap-surat'))) {
            mkdir(storage_path('app/public/rekap-surat'), 0755, true);
        }
        
        // Only generate if file doesn't exist or is older than 1 hour
        if (!file_exists($path) || (time() - filemtime($path)) > 3600) {
            $mpdf->Output($path, 'F');
        }
        
        // Return file (browser's native PDF viewer will handle it)
        return response()->file($path);
    }

    private function fetchData($start, $end, $type)
    {
        $results = collect();

        if ($type === 'all' || $type === 'masuk') {
            $masuk = SuratMasuk::whereBetween('tgl_diterima', [$start, $end])
                ->get()
                ->map(function ($item) {
                    return [
                        'date' => $item->tgl_diterima,
                        'no_surat' => $item->nomor_surat,
                        'pengirim_penerima' => $item->pengirim,
                        'perihal' => $item->perihal,
                        'jenis' => 'Surat Masuk'
                    ];
                });
            $results = $results->merge($masuk);
        }

        if ($type === 'all' || $type === 'keluar') {
            // Only archived letters are counted as 'Keluar' logic
            $keluar = Surat::where('status', 'archived')
                ->whereBetween('created_at', [$start . ' 00:00:00', $end . ' 23:59:59'])
                ->get()
                ->map(function ($item) {
                    return [
                        'date' => $item->created_at->format('Y-m-d'),
                        'no_surat' => $item->kode_surat,
                        'pengirim_penerima' => 'Pihak Sekolah', // Or destination if we had it
                        'perihal' => $item->jenis_surat, // Or content snippet
                        'jenis' => 'Surat Keluar'
                    ];
                });
            $results = $results->merge($keluar);
        }

        // Sort by date
        return $results->sortBy('date')->values();
    }

    private function fetchDataSeparated($start, $end, $type)
    {
        $masuk = collect();
        $keluar = collect();

        if ($type === 'all' || $type === 'masuk') {
            $masuk = SuratMasuk::whereBetween('tgl_diterima', [$start, $end])
                ->orderBy('tgl_diterima')
                ->get()
                ->map(function ($item) {
                    return [
                        'date' => $item->tgl_diterima,
                        'no_surat' => $item->nomor_surat,
                        'pengirim' => $item->pengirim,
                        'perihal' => $item->perihal,
                    ];
                });
        }

        if ($type === 'all' || $type === 'keluar') {
            $keluar = Surat::where('status', 'archived')
                ->whereBetween('created_at', [$start . ' 00:00:00', $end . ' 23:59:59'])
                ->orderBy('created_at')
                ->get()
                ->map(function ($item) {
                    $dataJson = is_array($item->data_json) ? $item->data_json : (json_decode($item->data_json, true) ?? []);
                    return [
                        'date' => $item->created_at->format('Y-m-d'),
                        'no_surat' => $item->kode_surat,
                        'tujuan' => $dataJson['nama_siswa'] ?? 'Umum',
                        'perihal' => $item->jenis_surat,
                    ];
                });
        }

        return [
            'masuk' => $masuk->values(),
            'keluar' => $keluar->values(),
        ];
    }
}

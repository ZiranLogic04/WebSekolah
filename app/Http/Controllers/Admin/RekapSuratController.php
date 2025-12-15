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

        $data = $this->fetchData($startDate, $endDate, $filterType);
        $lembaga = Lembaga::first();

        // return view for printing (blade, not Inertia, simpler for raw print)
        return view('admin.print_rekap', compact('data', 'startDate', 'endDate', 'filterType', 'lembaga'));
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
}

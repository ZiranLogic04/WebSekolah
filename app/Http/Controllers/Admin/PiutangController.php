<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class PiutangController extends Controller
{
    /**
     * Display a listing of individual unpaid bills (Tagihan).
     */
    public function index(Request $request)
    {
        // Start with only unpaid bills, or allow filter?
        // Usually Piutang = Belum Lunas + Cicilan (Status != Lunas)
        $query = \App\Models\Tagihan::with(['siswa', 'jenisTagihan'])
            ->where('status', '!=', 'Lunas');

        if ($request->search) {
            $query->whereHas('siswa', function($q) use ($request) {
                $q->where('nama', 'like', '%' . $request->search . '%')
                  ->orWhere('nis', 'like', '%' . $request->search . '%');
            });
        }

        if ($request->kelas) {
            $query->whereHas('siswa', function($q) use ($request) {
                $q->where('kelas', $request->kelas);
            });
        }

        if ($request->jenis_tagihan_id) {
            $query->where('jenis_tagihan_id', $request->jenis_tagihan_id);
        }

        if ($request->tahun_ajaran_id) {
            $query->where('tahun_ajaran_id', $request->tahun_ajaran_id);
        }

        $tagihans = $query->latest()->paginate(10)->withQueryString();
        $kelasList = \App\Models\Kelas::orderBy('nama')->pluck('nama');
        $jenisTagihans = \App\Models\JenisTagihan::all();
        $tahunAjarans = \App\Models\TahunAjaran::orderBy('is_active', 'desc')->orderBy('tahun', 'desc')->get();

        return inertia('Admin/Piutang/Index', [
            'tagihans' => $tagihans,
            'filters' => $request->only(['search', 'kelas', 'jenis_tagihan_id', 'tahun_ajaran_id']),
            'kelasList' => $kelasList,
            'jenisTagihans' => $jenisTagihans,
            'tahunAjarans' => $tahunAjarans,
        ]);
    }


    /**
     * Print/Preview Piutang Report (PDF)
     */
    public function print(Request $request)
    {
        $type = $request->input('type', 'year'); // 'year' atau 'month'
        $bulanNames = ['', 'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];

        if ($type === 'year') {
            // Per Tahun
            $tahun = $request->input('tahun', date('Y'));
            $startDate = "$tahun-01-01";
            $endDate = "$tahun-12-31";
            $periodeText = "Tahun $tahun";
        } else {
            // Rentang Bulan
            $startMonth = $request->input('start_month', date('Y-m'));
            $endMonth = $request->input('end_month', date('Y-m'));
            $startDate = $startMonth . "-01";
            $endDate = date('Y-m-t', strtotime($endMonth . "-01"));
            
            $startParts = explode('-', $startMonth);
            $endParts = explode('-', $endMonth);
            $periodeText = $bulanNames[(int)$startParts[1]] . ' ' . $startParts[0];
            if ($startMonth !== $endMonth) {
                $periodeText .= ' s.d. ' . $bulanNames[(int)$endParts[1]] . ' ' . $endParts[0];
            }
        }

        $query = \App\Models\Tagihan::with(['siswa', 'jenisTagihan'])
            ->where('status', '!=', 'Lunas')
            ->whereBetween('created_at', [$startDate . ' 00:00:00', $endDate . ' 23:59:59']);

        $tagihans = $query->orderBy('created_at', 'desc')->get();
        $lembaga = \App\Models\Lembaga::first();
        if (!$lembaga) $lembaga = new \App\Models\Lembaga();

        // Calculate totals
        $totalPiutang = $tagihans->sum('jumlah');
        $totalTerbayar = $tagihans->sum('terbayar');
        $totalSisa = $tagihans->sum('sisa');

        $mpdf = new \Mpdf\Mpdf([
            'mode' => 'utf-8',
            'format' => 'A4',
            'orientation' => 'P',
            'margin_top' => 15,
            'margin_bottom' => 15,
            'margin_left' => 10,
            'margin_right' => 10,
        ]);

        $html = view('pdf.piutang', compact('tagihans', 'lembaga', 'periodeText', 'totalPiutang', 'totalTerbayar', 'totalSisa'))->render();
        $mpdf->WriteHTML($html);

        // Save PDF to storage for Native Preview
        $filename = 'Laporan-Piutang-' . md5($startDate . $endDate) . '.pdf';
        $path = storage_path('app/public/laporan/' . $filename);
        
        // Ensure directory exists
        if (!file_exists(storage_path('app/public/laporan'))) {
            mkdir(storage_path('app/public/laporan'), 0755, true);
        }

        // Output to file
        $mpdf->Output($path, 'F');

        // Return file response (Browser will preview it accurately)
        return response()->file($path);
    }
}

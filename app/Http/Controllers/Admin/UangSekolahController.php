<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\UangSekolah;
use Illuminate\Support\Facades\DB;

class UangSekolahController extends Controller
{
    public function index(Request $request)
    {
        // Set locale ke Bahasa Indonesia
        \Carbon\Carbon::setLocale('id');
        
        // 1. Determine Date Range based on Filter (Default: Month)
        $startDate = null;
        $endDate = null;
        $periodLabel = 'Bulan Ini';

        if ($request->start_date && $request->end_date) {
            $startDate = $request->start_date;
            $endDate = $request->end_date;
            $periodLabel = 'Periode ' . $startDate . ' s/d ' . $endDate;
        } elseif ($request->period === 'week') {
            $startDate = now()->startOfWeek()->format('Y-m-d');
            $endDate = now()->endOfWeek()->format('Y-m-d');
            $periodLabel = 'Minggu Ini';
        } elseif ($request->period === 'year') {
            $startDate = now()->startOfYear()->format('Y-m-d');
            $endDate = now()->endOfYear()->format('Y-m-d');
            $periodLabel = 'Tahun Ini';
        } else {
            // Default: Month
            $startDate = now()->startOfMonth()->format('Y-m-d');
            $endDate = now()->endOfMonth()->format('Y-m-d');
            $periodLabel = 'Bulan Ini (' . now()->translatedFormat('F Y') . ')';
        }

        // 2. Base Query for Filtered Data
        $query = UangSekolah::query()->with('jenisKategori');

        if ($request->search) {
            $query->where(function($q) use ($request) {
                $q->where('sumber', 'like', '%' . $request->search . '%')
                  ->orWhere('keterangan', 'like', '%' . $request->search . '%')
                  ->orWhereHas('kategori', function($k) use ($request) {
                      $k->where('nama', 'like', '%' . $request->search . '%');
                  });
            });
        }

        if ($request->tipe) {
            $query->where('tipe', $request->tipe);
        }

        // Apply Date Filter to Transaction List
        $query->whereBetween('tanggal', [$startDate, $endDate]);

        $transaksi = $query->latest('tanggal')->latest('created_at')->paginate(20)->withQueryString();

        // 3. Global Stats (All Time - Cash on Hand)
        $totalMasukAllTime = UangSekolah::where('tipe', 'Masuk')->sum('jumlah');
        $totalKeluarAllTime = UangSekolah::where('tipe', 'Keluar')->sum('jumlah');
        $saldoSekolah = $totalMasukAllTime - $totalKeluarAllTime;

        // 4. Periodic Stats (Based on Filter)
        $pemasukanPeriode = UangSekolah::where('tipe', 'Masuk')->whereBetween('tanggal', [$startDate, $endDate])->sum('jumlah');
        $pengeluaranPeriode = UangSekolah::where('tipe', 'Keluar')->whereBetween('tanggal', [$startDate, $endDate])->sum('jumlah');
        
        // 5. Categorized Breakdown (For Reports)
        $rincianPemasukan = UangSekolah::where('tipe', 'Masuk')
            ->whereBetween('tanggal', [$startDate, $endDate])
            // Group by kategori (string) to capture manual inputs
            ->selectRaw('COALESCE(kategori, "Lainnya") as kategori_nama, sum(jumlah) as total, count(*) as count')
            ->groupBy('kategori_nama') 
            ->get()
            ->map(function($item) {
                return [
                    'kategori' => $item->kategori_nama,
                    'total' => $item->total,
                    'count' => $item->count
                ];
            });

        $rincianPengeluaran = UangSekolah::where('tipe', 'Keluar')
            ->whereBetween('tanggal', [$startDate, $endDate])
            // Group by kategori (string) to capture manual inputs
            ->selectRaw('COALESCE(kategori, "Lainnya") as kategori_nama, sum(jumlah) as total, count(*) as count')
            ->groupBy('kategori_nama')
            ->get()
            ->map(function($item) {
                return [
                    'kategori' => $item->kategori_nama,
                    'total' => $item->total,
                    'count' => $item->count
                ];
            });

        // Fetch categories for manual entry form
        $kategoriPemasukan = \App\Models\JenisTagihan::where('tipe', 'Pemasukan')->get(['id', 'nama', 'kode']);
        $kategoriPengeluaran = \App\Models\JenisTagihan::where('tipe', 'Pengeluaran')->get(['id', 'nama', 'kode']);

        return inertia('Admin/Keuangan/Index', [
            'transaksi' => $transaksi,
            'summary' => [
                'saldo_sekolah' => $saldoSekolah,
                'pemasukan_periode' => $pemasukanPeriode,
                'pengeluaran_periode' => $pengeluaranPeriode,
                'label_periode' => $periodLabel
            ],
            'laporan' => [
                'pemasukan' => $rincianPemasukan,
                'pengeluaran' => $rincianPengeluaran
            ],
            'filters' => $request->only(['search', 'tipe', 'start_date', 'end_date', 'period']),
            'kategoriPemasukan' => $kategoriPemasukan,
            'kategoriPengeluaran' => $kategoriPengeluaran
        ]);
    }

    public function print(Request $request)
    {
        // Set Local to ID
        \Carbon\Carbon::setLocale('id');
        
        // 1. Determine Date Range (Default: Month)
        $startDate = null;
        $endDate = null;
        $periodLabel = 'Bulan Ini';

        if ($request->start_date && $request->end_date) {
            $startDate = $request->start_date;
            $endDate = $request->end_date;
            $periodLabel = \Carbon\Carbon::parse($startDate)->translatedFormat('d F Y') . ' - ' . \Carbon\Carbon::parse($endDate)->translatedFormat('d F Y');
        } elseif ($request->period === 'week') {
            $startDate = now()->startOfWeek()->format('Y-m-d');
            $endDate = now()->endOfWeek()->format('Y-m-d');
            $periodLabel = 'Minggu Ini (' . \Carbon\Carbon::parse($startDate)->translatedFormat('d M') . ' - ' . \Carbon\Carbon::parse($endDate)->translatedFormat('d M Y') . ')';
        } elseif ($request->period === 'year') {
            $startDate = now()->startOfYear()->format('Y-m-d');
            $endDate = now()->endOfYear()->format('Y-m-d');
            $periodLabel = 'Tahun ' . now()->year;
        } else {
            // Default: Month
            $startDate = now()->startOfMonth()->format('Y-m-d');
            $endDate = now()->endOfMonth()->format('Y-m-d');
            $periodLabel = now()->translatedFormat('F Y');
        }

        // 2. Opening Balance (Saldo Awal)
        $pemasukanLalu = UangSekolah::where('tipe', 'Masuk')->where('tanggal', '<', $startDate)->sum('jumlah');
        $pengeluaranLalu = UangSekolah::where('tipe', 'Keluar')->where('tanggal', '<', $startDate)->sum('jumlah');
        $saldoAwal = $pemasukanLalu - $pengeluaranLalu;

        // 3. Periodic Stats
        $pemasukanPeriode = UangSekolah::where('tipe', 'Masuk')->whereBetween('tanggal', [$startDate, $endDate])->sum('jumlah');
        $pengeluaranPeriode = UangSekolah::where('tipe', 'Keluar')->whereBetween('tanggal', [$startDate, $endDate])->sum('jumlah');

        // 4. Full Transaction List
        $transaksi = UangSekolah::with('jenisKategori')
            ->whereBetween('tanggal', [$startDate, $endDate])
            ->orderBy('tanggal', 'asc')
            ->get();

        $lembaga = \App\Models\Lembaga::first();

        // Calculate Period Ending Balance
        $saldoAkhir = $saldoAwal + $pemasukanPeriode - $pengeluaranPeriode;

        // Calculate Tahun Ajaran
        $month = \Carbon\Carbon::parse($startDate)->month;
        $year = \Carbon\Carbon::parse($startDate)->year;
        $tahunAjaran = $month >= 7 ? "{$year}/" . ($year + 1) : ($year - 1) . "/{$year}";

        // Data untuk Blade
        $data = [
            'lembaga' => $lembaga,
            'periode' => $periodLabel,
            'tahunAjaran' => $tahunAjaran,
            'tanggalCetak' => now()->translatedFormat('d F Y'),
            'startDate' => \Carbon\Carbon::parse($startDate)->format('d/m/Y'),
            'endDate' => \Carbon\Carbon::parse($endDate)->format('d/m/Y'),
            'saldoAwal' => $saldoAwal,
            'saldoAwalFormatted' => 'Rp ' . number_format($saldoAwal, 0, ',', '.'),
            'transaksi' => $transaksi,
            'totalPemasukan' => $pemasukanPeriode,
            'totalPengeluaran' => $pengeluaranPeriode,
            'saldoAkhir' => $saldoAkhir,
            'bendahara' => auth()->user()->name ?? null,
        ];

        // Render Blade template
        $html = view('pdf.keuangan', $data)->render();

        // Generate PDF using mPDF
        $mpdf = new \Mpdf\Mpdf([
            'mode' => 'utf-8',
            'format' => 'A4',
            'orientation' => 'P',
            'margin_left' => 15,
            'margin_right' => 15,
            'margin_top' => 15,
            'margin_bottom' => 15,
        ]);

        $mpdf->WriteHTML($html);

        // Save to temp file
        $filename = 'laporan-keuangan-' . now()->format('YmdHis') . '.pdf';
        $path = storage_path('app/public/temp/' . $filename);
        
        // Ensure temp directory exists
        if (!file_exists(storage_path('app/public/temp'))) {
            mkdir(storage_path('app/public/temp'), 0755, true);
        }

        $mpdf->Output($path, 'F');

        // Return file response (inline preview in browser)
        return response()->file($path);
    }

    public function store(Request $request)
    {
        $request->validate([
            'tipe' => 'required|in:Masuk,Keluar',
            'kategori_id' => 'nullable|exists:jenis_tagihans,id', // Added validation
            'sumber' => 'required|string|max:255', 
            'jumlah' => 'required|numeric|min:1',
            'tanggal' => 'required|date',
            'keterangan' => 'nullable|string'
        ]);

        UangSekolah::create([
            'tipe' => $request->tipe,
            'kategori_id' => $request->kategori_id, // Added
            'sumber' => $request->sumber,
            'jumlah' => $request->jumlah,
            'tanggal' => $request->tanggal,
            'keterangan' => $request->keterangan
            // transaksi_id is null for manual entries
        ]);

        return redirect()->back()->with('success', 'Data keuangan berhasil disimpan.');
    }

    public function destroy(UangSekolah $uangSekolah)
    {
        if ($uangSekolah->transaksi_id) {
             return redirect()->back()->withErrors(['message' => 'Tidak dapat menghapus data yang terhubung dengan pembayaran siswa otomatis.']);
        }

        $uangSekolah->delete();
        return redirect()->back()->with('success', 'Data berhasil dihapus.');
    }

    /**
     * Halaman Transaksi Manual
     */
    public function transaksiManual()
    {
        // Riwayat transaksi manual saja (yang is_manual = true) - PAGINATED
        $riwayat = UangSekolah::where('is_manual', true)
            ->orderBy('tanggal', 'desc')
            ->orderBy('created_at', 'desc')
            ->paginate(10)
            ->withQueryString();
        
        // Hitung SALDO SEKOLAH (SEMUA transaksi, bukan hanya manual)
        $totalMasukSekolah = UangSekolah::where('tipe', 'Masuk')->sum('jumlah');
        $totalKeluarSekolah = UangSekolah::where('tipe', 'Keluar')->sum('jumlah');
        $saldoSekolah = $totalMasukSekolah - $totalKeluarSekolah;
        
        return inertia('Admin/Keuangan/TransaksiManual', [
            'riwayat' => $riwayat,
            'saldoSekolah' => $saldoSekolah,
        ]);
    }

    /**
     * Simpan Transaksi Manual
     */
    public function storeManual(Request $request)
    {
        $request->validate([
            'tipe' => 'required|in:Masuk,Keluar',
            'kategori' => 'required|string|max:255',
            'jumlah' => 'required|numeric|min:1',
            'tanggal' => 'required|date',
            'keterangan' => 'nullable|string|max:500',
        ], [
            'tipe.required' => 'Tipe transaksi wajib dipilih.',
            'tipe.in' => 'Tipe transaksi tidak valid.',
            'kategori.required' => 'Kategori wajib diisi.',
            'jumlah.required' => 'Jumlah wajib diisi.',
            'jumlah.numeric' => 'Jumlah harus berupa angka.',
            'jumlah.min' => 'Jumlah minimal 1.',
            'tanggal.required' => 'Tanggal wajib diisi.',
            'keterangan.max' => 'Keterangan maksimal 500 karakter.',
        ]);
        
        $tahunAjaran = \App\Models\TahunAjaran::where('is_active', true)->first();
        
        if (!$tahunAjaran) {
             return redirect()->back()->withErrors(['message' => 'Tidak ada Tahun Ajaran Aktif. Silakan atur di menu Data Lembaga.']);
        }

        UangSekolah::create([
            'tipe' => $request->tipe,
            'kategori' => $request->kategori,
            'jumlah' => $request->jumlah,
            'tanggal' => $request->tanggal,
            'sumber' => $request->keterangan,
            'keterangan' => $request->keterangan,
            'is_manual' => true,
            'tahun_ajaran_id' => $tahunAjaran->id,
        ]);
        
        return redirect()->back()->with('success', 'Transaksi berhasil dicatat.');
    }

    /**
     * Halaman Pengeluaran
     */
    public function pengeluaran()
    {
        // Kategori pengeluaran
        $kategoriPengeluaran = \App\Models\JenisTagihan::where('tipe', 'Pengeluaran')->get(['id', 'nama', 'kode']);
        
        // Riwayat pengeluaran terbaru (10 terakhir) dengan relasi kategori
        $riwayat = UangSekolah::with('kategori:id,nama')
            ->where('tipe', 'Keluar')
            ->latest('tanggal')
            ->limit(10)
            ->get();
        
        // Total pengeluaran bulan ini
        $startMonth = now()->startOfMonth();
        $endMonth = now()->endOfMonth();
        $totalBulanIni = UangSekolah::where('tipe', 'Keluar')
            ->whereBetween('tanggal', [$startMonth, $endMonth])
            ->sum('jumlah');

        return inertia('Admin/Keuangan/Pengeluaran', [
            'kategoriPengeluaran' => $kategoriPengeluaran,
            'riwayat' => $riwayat,
            'totalBulanIni' => $totalBulanIni
        ]);
    }

    /**
     * Simpan Pengeluaran
     */
    public function storePengeluaran(Request $request)
    {
        $request->validate([
            'sumber' => 'nullable|string|max:255',
            'jumlah' => 'required|numeric|min:1',
            'tanggal' => 'required|date',
            'kategori_id' => 'nullable|exists:jenis_tagihans,id',
        ]);

        $tahunAjaran = \App\Models\TahunAjaran::where('is_active', true)->first();
        if (!$tahunAjaran) {
            return redirect()->back()->withErrors(['message' => 'Tidak ada Tahun Ajaran Aktif. Silakan atur di Data Lembaga.']);
        }

        UangSekolah::create([
            'tipe' => 'Keluar',
            'kategori_id' => $request->kategori_id,
            'sumber' => $request->sumber ?: null,
            'jumlah' => $request->jumlah,
            'tanggal' => $request->tanggal,
            'tahun_ajaran_id' => $tahunAjaran->id,
        ]);

        // Hitung Saldo
        $totalPemasukan = UangSekolah::where('tipe', 'Masuk')->sum('jumlah') ?? 0;
        $totalPengeluaran = UangSekolah::where('tipe', 'Keluar')->sum('jumlah') ?? 0;
        $saldoSekolah = $totalPemasukan - $totalPengeluaran;
        $saldoAwal = $saldoSekolah + $request->jumlah;

        return redirect()->back()->with('success', 'Pengeluaran berhasil dicatat.')
            ->with('popup_data', [
                'jumlah' => $request->jumlah,
                'keterangan' => $request->sumber,
                'saldo_awal' => $saldoAwal,
                'saldo_sekolah' => $saldoSekolah
            ]);
    }
}

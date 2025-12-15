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
        $query = UangSekolah::query()->with('kategori');

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

        $transaksi = $query->latest('tanggal')->paginate(20)->withQueryString();

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
            ->selectRaw('kategori_id, sum(jumlah) as total, count(*) as count')
            ->groupBy('kategori_id')
            ->with('kategori')
            ->get()
            ->map(function($item) {
                return [
                    'kategori' => $item->kategori ? $item->kategori->nama : 'Lainnya / Tanpa Kategori',
                    'total' => $item->total,
                    'count' => $item->count
                ];
            });

        $rincianPengeluaran = UangSekolah::where('tipe', 'Keluar')
            ->whereBetween('tanggal', [$startDate, $endDate])
            ->selectRaw('kategori_id, sum(jumlah) as total, count(*) as count')
            ->groupBy('kategori_id')
            ->with('kategori')
            ->get()
            ->map(function($item) {
                return [
                    'kategori' => $item->kategori ? $item->kategori->nama : 'Lainnya / Tanpa Kategori',
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

        // 2. Global Stats (All Time)
        $totalMasukAllTime = UangSekolah::where('tipe', 'Masuk')->sum('jumlah');
        $totalKeluarAllTime = UangSekolah::where('tipe', 'Keluar')->sum('jumlah');
        $saldoSekolah = $totalMasukAllTime - $totalKeluarAllTime;

        // 3. Opening Balance (Saldo Awal) - Critical for "Buku Kas Umum"
        // Sum of all transactions before the start date
        $pemasukanLalu = UangSekolah::where('tipe', 'Masuk')->where('tanggal', '<', $startDate)->sum('jumlah');
        $pengeluaranLalu = UangSekolah::where('tipe', 'Keluar')->where('tanggal', '<', $startDate)->sum('jumlah');
        $saldoAwal = $pemasukanLalu - $pengeluaranLalu;

        // 3. Periodic Stats
        $pemasukanPeriode = UangSekolah::where('tipe', 'Masuk')->whereBetween('tanggal', [$startDate, $endDate])->sum('jumlah');
        $pengeluaranPeriode = UangSekolah::where('tipe', 'Keluar')->whereBetween('tanggal', [$startDate, $endDate])->sum('jumlah');

        // 4. Categorized Breakdown
        $rincianPemasukan = UangSekolah::where('tipe', 'Masuk')
            ->whereBetween('tanggal', [$startDate, $endDate])
            ->selectRaw('kategori_id, sum(jumlah) as total')
            ->groupBy('kategori_id')
            ->with('kategori')
            ->get()
            ->map(function($item) {
                return [
                    'kategori' => $item->kategori ? $item->kategori->nama : 'Lainnya',
                    'total' => $item->total
                ];
            });

        $rincianPengeluaran = UangSekolah::where('tipe', 'Keluar')
            ->whereBetween('tanggal', [$startDate, $endDate])
            ->selectRaw('kategori_id, sum(jumlah) as total')
            ->groupBy('kategori_id')
            ->with('kategori')
            ->get()
            ->map(function($item) {
                return [
                    'kategori' => $item->kategori ? $item->kategori->nama : 'Lainnya',
                    'total' => $item->total
                ];
            });
        
        // 5. Full Transaction List for Print (Restored for BKU Format)
        $transaksi = UangSekolah::with('kategori')
            ->whereBetween('tanggal', [$startDate, $endDate])
            ->orderBy('tanggal', 'asc')
            ->get();

        $lembaga = \App\Models\Lembaga::first();

        // Calculate Period Ending Balance
        $saldoAkhir = $saldoAwal + $pemasukanPeriode - $pengeluaranPeriode;

        return inertia('Admin/Keuangan/Print', [
            'summary' => [
                'saldo_sekolah' => $saldoSekolah, // Still useful for reference if needed
                'saldo_awal' => $saldoAwal,
                'saldo_akhir_periode' => $saldoAkhir, // Correct ending balance for this report
                'pemasukan_periode' => $pemasukanPeriode,
                'pengeluaran_periode' => $pengeluaranPeriode,
                'label_periode' => $periodLabel,
                'start_date' => $startDate,
                'end_date' => $endDate
            ],
            'laporan' => [
                'pemasukan' => $rincianPemasukan,
                'pengeluaran' => $rincianPengeluaran
            ],
            'transaksi' => $transaksi,
            'lembaga' => $lembaga
        ]);
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
}

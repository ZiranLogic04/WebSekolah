<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\UangSekolah;
use App\Models\JenisTagihan;

class PengeluaranController extends Controller
{
    public function index()
    {
        // Kategori pengeluaran
        $kategoriPengeluaran = JenisTagihan::where('tipe', 'Pengeluaran')->get(['id', 'nama', 'kode']);
        
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

    public function store(Request $request)
    {
        $request->validate([
            'sumber' => 'nullable|string|max:255',
            'jumlah' => 'required|numeric|min:1',
            'tanggal' => 'required|date',
            'kategori_id' => 'nullable|exists:jenis_tagihans,id',
        ]);

        UangSekolah::create([
            'tipe' => 'Keluar',
            'kategori_id' => $request->kategori_id,
            'sumber' => $request->sumber ?: null,
            'jumlah' => $request->jumlah,
            'tanggal' => $request->tanggal,
        ]);

        // Hitung Saldo
        $totalPemasukan = UangSekolah::where('tipe', 'Masuk')->sum('jumlah') ?? 0;
        $totalPengeluaran = UangSekolah::where('tipe', 'Keluar')->sum('jumlah') ?? 0;
        $saldoSekolah = $totalPemasukan - $totalPengeluaran;
        
        // Saldo Awal = Saldo Akhir + Pengeluaran (karena uang keluar, saldo berkurang)
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

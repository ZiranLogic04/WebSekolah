<?php

namespace App\Http\Controllers\Admin;

use App\Models\User;
use App\Models\Siswa;
use App\Models\Guru;
use App\Models\Post;
use App\Models\Surat;
use App\Models\SuratMasuk;
use App\Models\Tagihan;
use App\Models\Transaksi;
use App\Models\UangSekolah;
use Inertia\Inertia;
use App\Http\Controllers\Controller;

class DashboardController extends Controller
{
    public function index()
    {
        // Statistik utama
        $totalSiswa = Siswa::count();
        $totalGuru = Guru::count();
        $totalUser = User::count();
        $totalPost = Post::where('status', 'published')->count();
        
        // Statistik keuangan dari UangSekolah
        $totalPemasukan = UangSekolah::where('tipe', 'Masuk')->sum('jumlah') ?? 0;
        $totalPengeluaran = UangSekolah::where('tipe', 'Keluar')->sum('jumlah') ?? 0;
        $saldoKeuangan = $totalPemasukan - $totalPengeluaran;
        
        // Statistik tagihan
        $tagihanLunas = Tagihan::where('status', 'Lunas')->count();
        $tagihanBelumLunas = Tagihan::where('status', '!=', 'Lunas')->count();
        
        // Statistik surat
        $totalSuratMasuk = SuratMasuk::count();
        $totalSuratKeluar = Surat::where('status', 'archived')->count();
        
        // Post/Berita terbaru
        $postTerbaru = Post::where('status', 'published')
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get(['id', 'title', 'slug', 'created_at']);
        
        // Surat masuk terbaru
        $suratMasukTerbaru = SuratMasuk::orderBy('created_at', 'desc')
            ->take(5)
            ->get(['id', 'nomor_surat', 'perihal', 'pengirim', 'tgl_surat', 'created_at']);
        
        // Transaksi pembayaran terbaru (dari tabel transaksis)
        $transaksiTerbaru = Transaksi::with(['tagihan.siswa:id,nama'])
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();

        return Inertia::render('Admin/Dashboard', [
            'stats' => [
                'totalSiswa' => $totalSiswa,
                'totalGuru' => $totalGuru,
                'totalUser' => $totalUser,
                'totalPost' => $totalPost,
                'totalPemasukan' => $totalPemasukan,
                'totalPengeluaran' => $totalPengeluaran,
                'saldoKeuangan' => $saldoKeuangan,
                'tagihanLunas' => $tagihanLunas,
                'tagihanBelumLunas' => $tagihanBelumLunas,
                'totalSuratMasuk' => $totalSuratMasuk,
                'totalSuratKeluar' => $totalSuratKeluar,
            ],
            'postTerbaru' => $postTerbaru,
            'suratMasukTerbaru' => $suratMasukTerbaru,
            'transaksiTerbaru' => $transaksiTerbaru,
        ]);
    }
}

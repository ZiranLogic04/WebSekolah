<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Siswa;
use App\Models\Tagihan;
use App\Models\Transaksi;
use App\Models\UangSekolah;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class TransaksiController extends Controller
{
    public function index()
    {
        // We'll load the page. Student searching will be done via separate API or simply passing all students 
        // if the number is small. For scalability, let's pass all active students with minimal fields for Select2.
        $students = Siswa::select('id', 'nama', 'kelas', 'nis')->where('status', 'Aktif')->get();
        $sekolah = \App\Models\Lembaga::first();
        
        // Latest global transactions for initial view
        $latestTransactions = Transaksi::with(['tagihan.siswa'])
            ->latest()
            ->paginate(10);

        return inertia('Admin/Transaksi/Index', [
            'students' => $students,
            'sekolah' => $sekolah,
            'latestTransactions' => $latestTransactions
        ]);
    }

    public function getSiswaBill(Siswa $siswa)
    {
        $bills = Tagihan::where('siswa_id', $siswa->id)
            ->with('jenisTagihan')
            ->orderByRaw("FIELD(status, 'Belum Lunas', 'Lunas')") // Prioritize unpaid bills
            ->orderBy('tenggat_waktu', 'asc')
            ->get();
        
        return response()->json($bills);
    }

    public function getSiswaTransactions(Siswa $siswa)
    {
        $transactions = Transaksi::select('transaksis.*')
            ->join('tagihans', 'transaksis.tagihan_id', '=', 'tagihans.id')
            ->where('tagihans.siswa_id', $siswa->id)
            ->with(['tagihan'])
            ->latest()
            ->limit(10)
            ->get();

        return response()->json($transactions);
    }

    public function store(Request $request)
    {
        $request->validate([
            'tagihan_id' => 'required|exists:tagihans,id',
            'jumlah_bayar' => 'required|numeric|min:1',
            'metode_pembayaran' => 'required|string',
            'keterangan' => 'nullable|string'
        ]);

        return DB::transaction(function () use ($request) {
            $tagihan = Tagihan::lockForUpdate()
                ->find($request->tagihan_id);

            if ($tagihan->status === 'Lunas' || $tagihan->sisa <= 0) {
                return redirect()->back()->withErrors(['message' => 'Tagihan ini sudah lunas.']);
            }

            if ($request->jumlah_bayar > $tagihan->sisa) {
                return redirect()->back()->withErrors(['message' => 'Jumlah bayar melebihi sisa tagihan.']);
            }

            // 1. Create Transaksi
            $transaksi = Transaksi::create([
                'tagihan_id' => $tagihan->id,
                'kode_transaksi' => 'TRX-' . strtoupper(Str::random(10)),
                'jumlah_bayar' => $request->jumlah_bayar,
                'tanggal_bayar' => now(),
                'metode_pembayaran' => $request->metode_pembayaran,
                'keterangan' => $request->keterangan
            ]);

            // 2. Update Tagihan
            $tagihan->terbayar += $request->jumlah_bayar;
            $tagihan->sisa -= $request->jumlah_bayar;
            
            if ($tagihan->sisa <= 0) {
                $tagihan->sisa = 0;
                $tagihan->status = 'Lunas';
            }
            $tagihan->save();

            // 3. Catat Pemasukan Sekolah (UangSekolah)
            UangSekolah::create([
                'transaksi_id' => $transaksi->id,
                'kategori_id' => $tagihan->jenis_tagihan_id, // Ambil dari jenis tagihan
                'sumber' => "Pembayaran " . $tagihan->nama_tagihan . " - " . $tagihan->siswa->nama,
                'jumlah' => $request->jumlah_bayar,
                'tipe' => 'Masuk',
                'tanggal' => now(),
                'keterangan' => "Ref: " . $transaksi->kode_transaksi
            ]);

            // 4. Calculate Current Balance
            $totalPemasukan = \App\Models\UangSekolah::where('tipe', 'Masuk')->sum('jumlah') ?? 0;
            $totalPengeluaran = \App\Models\UangSekolah::where('tipe', 'Keluar')->sum('jumlah') ?? 0;
            $saldoSekolah = $totalPemasukan - $totalPengeluaran;
            $saldoAwal = $saldoSekolah - $request->jumlah_bayar;

            return redirect()->back()->with('success', 'Pembayaran berhasil disimpan. Kode: ' . $transaksi->kode_transaksi)
                ->with('print_data', [
                    'transaksi' => $transaksi,
                    'tagihan' => $tagihan,
                    'siswa' => $tagihan->siswa,
                    'sekolah' => \App\Models\Lembaga::first(),
                    'saldo_awal' => $saldoAwal,
                    'saldo_sekolah' => $saldoSekolah
                ]);
        });
    }
}

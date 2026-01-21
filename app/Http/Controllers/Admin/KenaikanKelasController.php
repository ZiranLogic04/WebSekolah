<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Siswa;
use App\Models\Kelas;
use App\Models\TahunAjaran;
use App\Models\RiwayatKelas;
use Inertia\Inertia;
use Illuminate\Support\Facades\DB;

class KenaikanKelasController extends Controller
{
    public function index()
    {
        $kelas = Kelas::orderBy('nama')->get();
        // Ambil semua tahun ajaran untuk dropdown
        $tahunAjaranList = TahunAjaran::orderBy('tahun', 'desc')->get();
        // Ambil tahun ajaran aktif sebagai default
        $activeTahunAjaran = TahunAjaran::where('is_active', true)->first() ?? TahunAjaran::latest()->first();

        return Inertia::render('Admin/KenaikanKelas/Index', [
            'kelasList' => $kelas,
            'tahunAjaranList' => $tahunAjaranList,
            'activeTahunAjaranId' => $activeTahunAjaran?->id
        ]);
    }

    public function getSiswaByKelas(Request $request)
    {
        $kelas = $request->kelas;
        $siswa = Siswa::where('kelas', $kelas)
            ->where('status', 'Aktif')
            ->orderBy('nama')
            ->get(['id', 'nis', 'nama', 'kelas', 'status']);
        
        return response()->json($siswa);
    }

    public function store(Request $request)
    {
        // Debug: Log incoming request
        \Log::info('Kenaikan Kelas Request:', $request->all());

        $request->validate([
            'siswa_ids' => 'required|array|min:1',
            'kelas_asal' => 'required|string',
            'aksi' => 'required|in:naik,lulus,tinggal,keluar,pindah',
            'kelas_tujuan' => 'required_if:aksi,naik',
            'tahun_lulus' => 'required_if:aksi,lulus',
        ], [
            'siswa_ids.required' => 'Pilih setidaknya satu siswa.',
            'siswa_ids.min' => 'Pilih setidaknya satu siswa.',
            'kelas_asal.required' => 'Kelas asal wajib dipilih.',
            'aksi.required' => 'Aksi kenaikan kelas wajib dipilih.',
            'aksi.in' => 'Pilihan aksi tidak valid.',
            'kelas_tujuan.required_if' => 'Kelas tujuan wajib dipilih jika status Naik.',
            'tahun_lulus.required_if' => 'Tahun lulus wajib dipilih jika status Lulus.',
        ]);

        \Log::info('Validation passed, processing...');

        DB::beginTransaction();
        try {
            $siswaIds = $request->siswa_ids;
            $aksi = $request->aksi; // 'naik', 'lulus', 'tinggal'
            $kelasTujuan = $request->kelas_tujuan;
            $tahunLulus = $request->tahun_lulus;

            $statusRiwayat = match($aksi) {
                'naik' => 'Naik Kelas',
                'lulus' => 'Lulus',
                'tinggal' => 'Tinggal Kelas', // Sebenarnya status riwayatnya 'Tinggal Kelas' tapi status siswa tetap 'Aktif'
                'keluar' => 'Keluar',
                'pindah' => 'Pindah',
            };

            $statusSiswa = match($aksi) {
                'naik', 'tinggal' => 'Aktif',
                'lulus' => 'Lulus',
                'keluar' => 'Keluar',
                'pindah' => 'Pindah',
            };

            foreach ($siswaIds as $id) {
                $siswa = Siswa::find($id);

                // Catat Riwayat
                RiwayatKelas::create([
                    'siswa_id' => $siswa->id,
                    'kelas_sebelumnya' => $request->kelas_asal,
                    'kelas_sesudahnya' => ($aksi === 'naik' || $aksi === 'tinggal') ? $kelasTujuan : null,
                    'tahun_ajaran_id' => TahunAjaran::where('is_active', true)->firstOrFail()->id,
                    'status' => $statusRiwayat,
                    'keterangan' => "Proses {$statusRiwayat}" . ($aksi === 'lulus' ? " Tahun {$tahunLulus}" : "")
                ]);

                // Update Siswa
                $updateData = ['status' => $statusSiswa];
                
                if ($aksi === 'naik') {
                    $updateData['kelas'] = $kelasTujuan;
                } elseif ($aksi === 'lulus') {
                    // Untuk lulus: simpan tahun lulus yang dipilih
                    $updateData['tahun_lulus'] = $tahunLulus;
                }
                // Jika tinggal kelas, kelas tetap sama (kelas_asal) dan tidak perlu tahun

                $siswa->update($updateData);
            }

            DB::commit();
            return redirect()->back()->with('success', 'Proses kenaikan kelas berhasil disimpan.');

        } catch (\Exception $e) {
            DB::rollback();
            \Log::error('Kenaikan Kelas ERROR: ' . $e->getMessage());
            \Log::error('Stack trace: ' . $e->getTraceAsString());
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }
}

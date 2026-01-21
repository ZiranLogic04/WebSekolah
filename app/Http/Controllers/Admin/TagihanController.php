<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class TagihanController extends Controller
{
    public function index(Request $request)
    {
        $query = \App\Models\TagihanBatch::with(['jenisTagihan']);

        if ($request->search) {
            $query->where('nama_tagihan', 'like', '%' . $request->search . '%');
        }

        if ($request->jenis_tagihan_id) {
            $query->where('jenis_tagihan_id', $request->jenis_tagihan_id);
        }
        
        // Filter by target kelas string
        if ($request->kelas) {
             $query->where('target_kelas', $request->kelas);
        }

        $batches = $query->latest()->paginate(10)->withQueryString();
        
        // For filters (though target_kelas is a string, we can still use avail clases)
        $kelasList = \App\Models\Kelas::orderBy('nama')->pluck('nama');
        $jenisTagihans = \App\Models\JenisTagihan::all();
        
        return inertia('Admin/Tagihan/Index', [
            'batches' => $batches,
            'filters' => $request->only(['search', 'kelas', 'jenis_tagihan_id']),
            'kelasList' => $kelasList,
            'jenisTagihans' => $jenisTagihans,
        ]); 
    }

    public function create()
    {
        $jenisTagihans = \App\Models\JenisTagihan::all();
        // Ambil data kelas unik dari tabel siswa
        $kelas = \App\Models\Kelas::orderBy('nama')->pluck('nama');
        
        $tahunAjaran = \App\Models\TahunAjaran::where('is_active', true)->first();

        return inertia('Admin/Tagihan/Create', [
            'jenisTagihans' => $jenisTagihans,
            'kelasList' => $kelas,
            'tahunAjaran' => $tahunAjaran
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'jenis_tagihan_id' => 'required|exists:jenis_tagihans,id',
            'jumlah' => 'required|numeric|min:0',
            'tenggat_waktu' => 'required|date',
            'filter_kelas' => 'nullable|string', // Jika null = Semua Kelas
            'filter_gender' => 'nullable|in:L,P,Semua', // Added 'Semua' to validation
        ], [
            'jenis_tagihan_id.required' => 'Jenis tagihan wajib dipilih.',
            'jenis_tagihan_id.exists' => 'Jenis tagihan tidak valid.',
            'jumlah.required' => 'Jumlah tagihan wajib diisi.',
            'jumlah.numeric' => 'Jumlah harus berupa angka.',
            'jumlah.min' => 'Jumlah minimal 0.',
            'tenggat_waktu.required' => 'Tenggat waktu wajib diisi.',
            'tenggat_waktu.date' => 'Format tanggal tidak valid.',
        ]);

        $query = \App\Models\Siswa::query()->where('status', 'Aktif');

        if ($request->filter_kelas && $request->filter_kelas !== 'Semua') {
            $query->where('kelas', $request->filter_kelas);
        }

        if ($request->filter_gender && $request->filter_gender !== 'Semua') {
            $query->where('jenis_kelamin', $request->filter_gender);
        }

        $siswas = $query->get();

        if ($siswas->isEmpty()) {
            return redirect()->back()->withErrors(['message' => 'Tidak ada siswa yang sesuai dengan filter tersebut.']);
        }
        
        // Ambil tahun ajaran AKTIF dari database (bukan session)
        $tahunAjaran = \App\Models\TahunAjaran::where('is_active', true)->first();
        
        if (!$tahunAjaran) {
             return redirect()->back()->withErrors(['message' => 'Tidak ada Tahun Ajaran Aktif. Silakan atur di menu Data Lembaga.']);
        }
        
        $tahunAjaranId = $tahunAjaran->id;

        $jenisTagihan = \App\Models\JenisTagihan::find($request->jenis_tagihan_id);
        
        // Create Batch dengan total_siswa SNAPSHOT
        $batch = \App\Models\TagihanBatch::create([
            'jenis_tagihan_id' => $jenisTagihan->id,
            'nama_tagihan' => $jenisTagihan->nama . ' - ' . now()->format('d M Y'), 
            'target_kelas' => $request->filter_kelas === 'Semua' ? null : $request->filter_kelas,
            'target_gender' => $request->filter_gender === 'Semua' ? null : $request->filter_gender,
            'jumlah' => $request->jumlah,
            'tenggat_waktu' => $request->tenggat_waktu,
            'keterangan' => "Dibuat otomatis untuk " . $siswas->count() . " siswa",
            'tahun_ajaran_id' => $tahunAjaranId,
            'total_siswa' => $siswas->count(), // SNAPSHOT jumlah siswa
        ]);

        $count = 0;

        foreach ($siswas as $siswa) {
            \App\Models\Tagihan::create([
                'tagihan_batch_id' => $batch->id,
                'siswa_id' => $siswa->id,
                'nama_siswa' => $siswa->nama,   // SNAPSHOT nama siswa
                'kelas_siswa' => $siswa->kelas, // SNAPSHOT kelas siswa
                'jenis_tagihan_id' => $jenisTagihan->id,
                'nama_tagihan' => $jenisTagihan->nama,
                'jumlah' => $request->jumlah,
                'terbayar' => 0,
                'sisa' => $request->jumlah,
                'status' => 'Belum Lunas',
                'tenggat_waktu' => $request->tenggat_waktu,
                'tahun_ajaran_id' => $tahunAjaranId,
            ]);
            $count++;
        }

        return redirect()->route('tagihan.index')->with('success', "Berhasil membuat tagihan batch untuk $count siswa (Tahun Ajaran: {$tahunAjaran->tahun}).");
    }
    public function destroy($id)
    {
        $batch = \App\Models\TagihanBatch::findOrFail($id);
        $batch->delete(); // Cascades on DB level if set, or we rely on model boot if needed. 
        // Migration was: $table->foreignId('tagihan_batch_id')->...->onDelete('cascade');
        // So this is safe.

        return redirect()->back()->with('success', 'Batch tagihan berhasil dihapus.');
    }

    public function detail($id)
    {
        $batch = \App\Models\TagihanBatch::findOrFail($id);
        
        // Paginate 20 per page untuk performa
        $tagihans = \App\Models\Tagihan::where('tagihan_batch_id', $id)
            ->with('siswa:id,nama,kelas')
            ->select(['id', 'siswa_id', 'nama_siswa', 'kelas_siswa', 'status', 'terbayar', 'jumlah'])
            ->paginate(20);
        
        return response()->json([
            'batch' => $batch,
            'tagihans' => $tagihans->items(),
            'pagination' => [
                'current_page' => $tagihans->currentPage(),
                'last_page' => $tagihans->lastPage(),
                'per_page' => $tagihans->perPage(),
                'total' => $tagihans->total(),
            ]
        ]);
    }
}

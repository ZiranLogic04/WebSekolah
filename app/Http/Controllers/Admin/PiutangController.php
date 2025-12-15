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

        $tagihans = $query->latest()->paginate(10)->withQueryString();
        $kelasList = \App\Models\Kelas::orderBy('nama')->pluck('nama');
        $jenisTagihans = \App\Models\JenisTagihan::all();

        return inertia('Admin/Piutang/Index', [
            'tagihans' => $tagihans,
            'filters' => $request->only(['search', 'kelas', 'jenis_tagihan_id']),
            'kelasList' => $kelasList,
            'jenisTagihans' => $jenisTagihans,
        ]);
    }
}

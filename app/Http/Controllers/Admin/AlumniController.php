<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Siswa;
use Illuminate\Http\Request;
use Inertia\Inertia;

class AlumniController extends Controller
{
    public function index(Request $request)
    {
        $query = Siswa::query()
            ->whereIn('status', ['Lulus', 'Pindah', 'Keluar', 'Dikeluarkan', 'Mengundurkan Diri']);

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('nama', 'like', "%{$search}%")
                  ->orWhere('nis', 'like', "%{$search}%");
            });
        }

        if ($request->filled('tahun_lulus')) {
            $query->where('tahun_lulus', $request->tahun_lulus);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $alumni = $query->orderBy('tahun_lulus', 'desc')
            ->orderBy('nama', 'asc')
            ->paginate(15)
            ->withQueryString();

        // Get unique graduation years for filter
        $tahunLulusList = Siswa::whereNotNull('tahun_lulus')
            ->distinct()
            ->orderBy('tahun_lulus', 'desc')
            ->pluck('tahun_lulus');

        return Inertia::render('Admin/DataAlumni', [
            'alumni' => $alumni,
            'tahunLulusList' => $tahunLulusList,
            'filters' => $request->only(['search', 'tahun_lulus', 'status'])
        ]);
    }
}

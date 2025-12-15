<?php

namespace App\Http\Controllers\Admin;

use Inertia\Inertia;
use App\Models\Siswa;
use App\Imports\SiswaImport;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Redirect;

use App\Models\Kelas;

class SiswaController extends Controller
{
    public function index(Request $request)
    {
        $query = Siswa::query();
        
        $filters = $request->only(['search', 'kelas', 'status', 'jenis_kelamin']);
        
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('nis', 'like', "%{$search}%")
                  ->orWhere('nama', 'like', "%{$search}%");
            });
        }
        
        if ($request->filled('kelas')) {
            $query->where('kelas', $request->kelas);
        }
        
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        
        if ($request->filled('jenis_kelamin')) {
            $query->where('jenis_kelamin', $request->jenis_kelamin);
        }
        
        $siswa = $query->orderBy('created_at', 'desc')
            ->paginate(10)
            ->withQueryString();

        $kelasList = Kelas::orderBy('nama')->pluck('nama');
        
        return Inertia::render('Admin/DataSiswa', [
            'siswa' => $siswa,
            'filters' => $filters,
            'kelasList' => $kelasList,
        ]);
    }

    public function storeKelas(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|unique:kelas,nama'
        ]);

        Kelas::create(['nama' => $request->nama]);

        return redirect()->back()->with('success', 'Kelas berhasil ditambahkan');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nis' => 'required|unique:siswa,nis',
            'nama' => 'required|string|max:255',
            'kelas' => 'required|exists:kelas,nama',
            'jenis_kelamin' => 'required|in:L,P',
            'status' => 'required|in:Aktif,Non-Aktif,Pindah,Lulus',
        ]);

        Siswa::create($request->all());

        // POST menggunakan redirect biasa (akan jadi 302, tapi POST ke GET tidak masalah)
        return redirect()->route('siswa.index')
            ->with('success', 'Data siswa berhasil ditambahkan');
    }

    public function update(Request $request, Siswa $siswa)
    {   
        $request->validate([
            'nis' => 'required|unique:siswa,nis,' . $siswa->id,
            'nama' => 'required|string|max:255',
            'kelas' => 'required|string|max:10',
            'jenis_kelamin' => 'required|in:L,P',
            'status' => 'required|in:Aktif,Non-Aktif,Pindah,Lulus',
        ]);

        $siswa->update($request->all());

        return redirect()->back()
            ->with('success', 'Data siswa berhasil diperbarui');
    }

    public function destroy(Siswa $siswa)
    {
        $siswa->delete();
        
        // PERBAIKAN: Gunakan to_route() dengan status 303
        return to_route('siswa.index')
            ->with('success', 'Data siswa berhasil dihapus');
    }

    public function bulkDestroy(Request $request)
    {
        $request->validate([
            'ids' => 'required|array',
            'ids.*' => 'exists:siswa,id',
        ]);

        Siswa::whereIn('id', $request->ids)->delete();

        // PERBAIKAN: Gunakan to_route() dengan status 303
        return to_route('siswa.index')
            ->with('success', count($request->ids) . ' data siswa berhasil dihapus');
    }

    public function bulkUpdate(Request $request)
    {
        $request->validate([
            'ids' => 'required|array',
            'ids.*' => 'exists:siswa,id',
            'status' => 'nullable|in:Aktif,Non-Aktif,Pindah,Lulus',
            'kelas' => 'nullable|string|max:10',
        ]);

        $updateData = [];
        if ($request->filled('status')) {
            $updateData['status'] = $request->status;
        }
        if ($request->filled('kelas')) {
            $updateData['kelas'] = $request->kelas;
        }

        if (!empty($updateData)) {
            Siswa::whereIn('id', $request->ids)->update($updateData);
        }

        return redirect()->back()
            ->with('success', count($request->ids) . ' data siswa berhasil diperbarui');
    }
    public function import(Request $request)
{
    $request->validate([
        'file' => 'required|mimes:xlsx,xls,csv'
    ]);

    try {
        Excel::import(new SiswaImport, $request->file('file'));

        return redirect()->back()->with([
            'success' => 'Import data siswa berhasil!'
        ]);
    } catch (\Exception $e) {
        return redirect()->back()->with([
            'error' => 'Terjadi kesalahan saat import: ' . $e->getMessage()
        ]);
    }
}
    public function downloadTemplate()
    {
        return Excel::download(new \App\Exports\SiswaTemplateExport, 'template_siswa.xlsx');
    }
}
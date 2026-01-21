<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Kelas;
use Illuminate\Http\Request;

use Inertia\Inertia;
use App\Models\Guru;
use Illuminate\Support\Facades\DB;

class KelasController extends Controller
{
    public function index()
    {
        $kelas = Kelas::with('waliKelas')
            ->withCount(['siswa' => function ($query) {
                $query->where('status', 'Aktif');
            }])
            ->orderBy('nama')
            ->get();
            
        $guruList = Guru::select('id', 'nama', 'nip')->orderBy('nama')->get();

        return Inertia::render('Admin/DataKelas', [
            'kelasData' => $kelas,
            'guruList' => $guruList
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|unique:kelas,nama',
            'wali_kelas_id' => 'nullable|exists:guru,id'
        ], [
            'nama.required' => 'Nama kelas wajib diisi.',
            'nama.unique' => 'Nama kelas sudah ada.',
            'wali_kelas_id.exists' => 'Wali kelas tidak valid.'
        ]);

        Kelas::create($request->only('nama', 'wali_kelas_id'));

        return redirect()->back()->with('success', 'Kelas berhasil ditambahkan');
    }

    public function update(Request $request, Kelas $kelas)
    {
        $request->validate([
            'nama' => 'required|string|unique:kelas,nama,' . $kelas->id,
            'wali_kelas_id' => 'nullable|exists:guru,id'
        ], [
            'nama.required' => 'Nama kelas wajib diisi.',
            'nama.unique' => 'Nama kelas sudah ada.',
            'wali_kelas_id.exists' => 'Wali kelas tidak valid.'
        ]);

        $kelas->update($request->only('nama', 'wali_kelas_id'));

        return redirect()->back()->with('success', 'Kelas berhasil diperbarui');
    }

    public function destroy($id)
    {
        $kelas = Kelas::findOrFail($id);
        
        // Cek apakah kelas dipakai di tabel siswa yang AKTIF
        // Assuming 'kelas' column stores class name string
        $exists = \App\Models\Siswa::where('kelas', $kelas->nama)
                    ->where('status', 'Aktif')
                    ->exists(); 
        
        if ($exists) {
             return redirect()->back()->with('error', 'Gagal menghapus! Kelas masih memiliki siswa aktif.');
        }

        // Force delete via DB to ensure it's gone
        DB::table('kelas')->where('id', $id)->delete();

        return to_route('kelas.index')->with('success', "Kelas {$kelas->nama} berhasil dihapus.");
    }
}

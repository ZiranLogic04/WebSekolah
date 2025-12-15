<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Kelas;
use Illuminate\Http\Request;

class KelasController extends Controller
{
    public function index()
    {
        return response()->json(\App\Models\Kelas::orderBy('nama')->get());
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|unique:kelas,nama'
        ]);

        $kelas = Kelas::create($request->only('nama'));

        return redirect()->back()->with('success', 'Kelas berhasil ditambahkan');
    }

    public function update(Request $request, Kelas $kelas)
    {
        $request->validate([
            'nama' => 'required|string|unique:kelas,nama,' . $kelas->id
        ]);

        $kelas->update($request->only('nama'));

        return redirect()->back()->with('success', 'Kelas berhasil diperbarui');
    }

    public function destroy(Kelas $kelas)
    {
        // Cek apakah kelas dipakai di tabel siswa
        $exists = \App\Models\Siswa::where('kelas', $kelas->nama)->exists(); // Note: Siswa stores class name string currently
        
        if ($exists) {
             return redirect()->back()->withErrors(['message' => 'Gagal menghapus! Kelas sedang digunakan oleh data siswa.']);
        }

        $kelas->delete();

        return redirect()->back()->with('success', 'Kelas berhasil dihapus');
    }
}

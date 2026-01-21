<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\JenisTagihan;
use Illuminate\Http\Request;

class JenisTagihanController extends Controller
{
    public function index(Request $request)
    {
        $query = JenisTagihan::query();
        
        // Filter by tipe if provided
        if ($request->tipe) {
            $query->where('tipe', $request->tipe);
        }
        
        $jenisTagihans = $query->latest()->get();
        
        return inertia('Admin/Tagihan/Jenis', [
            'jenisTagihans' => $jenisTagihans,
            'filters' => $request->only('tipe')
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama' => 'required|string|max:255',
            'kode' => 'nullable|string|max:50',
            'keterangan' => 'nullable|string',
        ], [
            'nama.required' => 'Nama jenis tagihan wajib diisi.',
            'nama.max' => 'Nama maksimal 255 karakter.',
        ]);
        
        // Force tipe to Pemasukan as per user request (Pengeluaran is manual now)
        $validated['tipe'] = 'Pemasukan';

        JenisTagihan::create($validated);

        return redirect()->back()->with('success', 'Jenis tagihan berhasil ditambahkan.');
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'nama' => 'required|string|max:255',
            'kode' => 'nullable|string|max:50',
            'keterangan' => 'nullable|string',
        ], [
            'nama.required' => 'Nama jenis tagihan wajib diisi.',
            'nama.max' => 'Nama maksimal 255 karakter.',
        ]);
        
        // Ensure we don't accidentally change old data's type if we just want to edit name
        // But if user wants UNIFORMITY, we should probably ignore tipe update or force it.
        // Let's just update other fields.

        $jenisTagihan = JenisTagihan::findOrFail($id);
        $jenisTagihan->update($validated);

        return redirect()->back()->with('success', 'Jenis tagihan berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $jenisTagihan = JenisTagihan::findOrFail($id);
        
        // Check if there are related tagihans
        if ($jenisTagihan->tipe === 'Pemasukan') {
            $usedCount = \App\Models\Tagihan::where('jenis_tagihan_id', $id)->count();
            if ($usedCount > 0) {
                return redirect()->back()->withErrors(['message' => 'Tidak dapat menghapus kategori yang masih digunakan oleh ' . $usedCount . ' tagihan.']);
            }
        }
        
        $jenisTagihan->delete();

        return redirect()->back()->with('success', 'Jenis tagihan berhasil dihapus.');
    }
    
    /**
     * API: Get categories by type (for AJAX)
     */
    public function getByType(Request $request)
    {
        $tipe = $request->query('tipe', 'Pemasukan');
        $categories = JenisTagihan::where('tipe', $tipe)->get(['id', 'nama', 'kode']);
        
        return response()->json($categories);
    }
}

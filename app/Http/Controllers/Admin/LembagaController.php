<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Lembaga;
use Inertia\Inertia;
use Illuminate\Support\Facades\Storage;

class LembagaController extends Controller
{
    public function index()
    {
        $lembaga = Lembaga::first();
        $tahunAjarans = \App\Models\TahunAjaran::orderBy('tahun', 'desc')->get();
        
        return Inertia::render('Admin/DataLembaga', [
            'lembaga' => $lembaga,
            'tahunAjarans' => $tahunAjarans
        ]);
    }

    public function update(Request $request)
    {
        $request->validate([
            'nama_sekolah' => 'required|string|max:255',
            'email' => 'nullable|email|max:255',
            'telepon' => 'nullable|string|max:20',
            'alamat' => 'nullable|string',
            'akreditasi' => 'nullable|string|max:2',
            'npsn' => 'nullable|string|max:20',
            'status' => 'required|in:Negeri,Swasta',
            'tahun_berdiri' => 'nullable|integer|digits:4|min:1900|max:' . (date('Y') + 1),
            'kepala_sekolah' => 'nullable|string|max:255',
            'tahun_ajaran_aktif_id' => 'nullable|exists:tahun_ajarans,id',
            'semester_aktif' => 'required|in:Ganjil,Genap',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ], [
            'nama_sekolah.required' => 'Nama sekolah wajib diisi.',
            'status.required' => 'Status sekolah wajib dipilih.',
            'semester_aktif.required' => 'Semester aktif wajib dipilih.',
            'email.email' => 'Format email tidak valid.',
            'tahun_berdiri.digits' => 'Tahun berdiri harus 4 digit.',
            'tahun_berdiri.min' => 'Tahun berdiri tidak valid (min 1900).',
            'tahun_berdiri.max' => 'Tahun berdiri tidak boleh lebih dari tahun depan.',
            'logo.image' => 'File logo harus berupa gambar.',
            'logo.max' => 'Ukuran logo maksimal 2MB.',
        ]);

        $lembaga = Lembaga::first();
        if (!$lembaga) {
            $lembaga = new Lembaga();
        }

        $data = $request->except(['logo']);

        // Handle Logo Upload
        if ($request->hasFile('logo')) {
            // Delete old logo if exists
            if ($lembaga->logo && Storage::disk('public')->exists($lembaga->logo)) {
                Storage::disk('public')->delete($lembaga->logo);
            }
            $logoPath = $request->file('logo')->store('lembaga', 'public');
            $data['logo'] = $logoPath;
        }

        if ($lembaga->exists) {
            $lembaga->update($data);
        } else {
            $lembaga->fill($data);
            $lembaga->save();
        }

        // Sync Tahun Ajaran Status
        if ($request->has('tahun_ajaran_aktif_id') && $request->tahun_ajaran_aktif_id) {
            \App\Models\TahunAjaran::query()->update(['is_active' => false]);
            \App\Models\TahunAjaran::where('id', $request->tahun_ajaran_aktif_id)->update(['is_active' => true]);
        }

        return redirect()->back()->with('success', 'Data lembaga berhasil diperbarui.');
    }
}

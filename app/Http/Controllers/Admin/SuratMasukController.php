<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SuratMasuk;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\Storage;

class SuratMasukController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $suratMasuk = SuratMasuk::latest()->get();
        return Inertia::render('Admin/SuratMasuk', [
            'suratMasuk' => $suratMasuk
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nomor_agenda' => 'nullable|string',
            'nomor_surat' => 'required|string',
            'pengirim'    => 'required|string',
            'perihal'     => 'required|string',
            'tgl_surat'   => 'required|date',
            'tgl_diterima'=> 'required|date',
            'file_scan'   => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
            'keterangan'  => 'nullable|string'
        ], [
            'nomor_surat.required' => 'Nomor surat wajib diisi.',
            'pengirim.required' => 'Nama pengirim wajib diisi.',
            'perihal.required' => 'Perihal surat wajib diisi.',
            'tgl_surat.required' => 'Tanggal surat wajib diisi.',
            'tgl_diterima.required' => 'Tanggal diterima wajib diisi.',
            'file_scan.mimes' => 'File harus berformat PDF, JPG, atau PNG.',
            'file_scan.max' => 'Ukuran file maksimal 2MB.',
        ]);

        if ($request->hasFile('file_scan')) {
            $file = $request->file('file_scan');
            $filename = time() . '_' . $file->getClientOriginalName();
            $path = $file->storeAs('surat-masuk', $filename, 'public');
            $validated['file_path'] = $path;
        }

        SuratMasuk::create($validated);

        return redirect()->route('surat-masuk.index')->with('success', 'Surat Masuk berhasil dicatat.');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, SuratMasuk $suratMasuk)
    {
        $validated = $request->validate([
            'nomor_agenda' => 'nullable|string',
            'nomor_surat' => 'required|string',
            'pengirim'    => 'required|string',
            'perihal'     => 'required|string',
            'tgl_surat'   => 'required|date',
            'tgl_diterima'=> 'required|date',
            'file_scan'   => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
            'keterangan'  => 'nullable|string'
        ], [
            'nomor_surat.required' => 'Nomor surat wajib diisi.',
            'pengirim.required' => 'Nama pengirim wajib diisi.',
            'perihal.required' => 'Perihal surat wajib diisi.',
            'tgl_surat.required' => 'Tanggal surat wajib diisi.',
            'tgl_diterima.required' => 'Tanggal diterima wajib diisi.',
            'file_scan.mimes' => 'File harus berformat PDF, JPG, atau PNG.',
            'file_scan.max' => 'Ukuran file maksimal 2MB.',
        ]);

        if ($request->hasFile('file_scan')) {
            // Delete old file
            if ($suratMasuk->file_path && Storage::disk('public')->exists($suratMasuk->file_path)) {
                Storage::disk('public')->delete($suratMasuk->file_path);
            }
            $file = $request->file('file_scan');
            $filename = time() . '_' . $file->getClientOriginalName();
            $path = $file->storeAs('surat-masuk', $filename, 'public');
            $validated['file_path'] = $path;
        }

        $suratMasuk->update($validated);

        return redirect()->route('surat-masuk.index')->with('success', 'Data Surat Masuk diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(SuratMasuk $suratMasuk)
    {
        if ($suratMasuk->file_path && Storage::disk('public')->exists($suratMasuk->file_path)) {
            Storage::disk('public')->delete($suratMasuk->file_path);
        }
        $suratMasuk->delete();
        return redirect()->route('surat-masuk.index')->with('success', 'Surat Masuk dihapus.');
    }

    /**
     * View/Preview document in browser (inline, not download)
     */
    public function viewDocument(SuratMasuk $suratMasuk)
    {
        if (!$suratMasuk->file_path) {
            abort(404, 'File tidak ditemukan.');
        }

        $path = Storage::disk('public')->path($suratMasuk->file_path);

        if (!file_exists($path)) {
            abort(404, 'File fisik tidak ditemukan di server.');
        }

        // Return response using file helper which handles headers correctly for preview
        return response()->file($path);
    }
}

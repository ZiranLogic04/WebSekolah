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
        ]);

        if ($request->hasFile('file_scan')) {
            $path = $request->file('file_scan')->store('surat-masuk', 'public');
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
        ]);

        if ($request->hasFile('file_scan')) {
            // Delete old file
            if ($suratMasuk->file_path && Storage::disk('public')->exists($suratMasuk->file_path)) {
                Storage::disk('public')->delete($suratMasuk->file_path);
            }
            $path = $request->file('file_scan')->store('surat-masuk', 'public');
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
}

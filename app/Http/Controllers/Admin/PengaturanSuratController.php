<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Lembaga;
use App\Models\LetterTemplate;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;

class PengaturanSuratController extends Controller
{
    public function index()
    {
        $lembaga = Lembaga::first();
        $templates = LetterTemplate::orderBy('name')->get();

        return Inertia::render('Admin/PengaturanSurat', [
            'lembaga' => $lembaga,
            'templates' => $templates
        ]);
    }

    public function updateKop(Request $request)
    {
        $request->validate([
            'kop_baris1' => 'nullable|string|max:255',
            'kop_baris2' => 'nullable|string|max:255',
            'kop_baris3' => 'nullable|string|max:255',
            'kop_baris4' => 'nullable|string|max:255',
            'kop_baris5' => 'nullable|string|max:255',
            'logo_kiri' => 'nullable|image|max:2048',
            'logo_kanan' => 'nullable|image|max:2048',
        ], [
            'logo_kiri.image' => 'Logo kiri harus berupa file gambar.',
            'logo_kiri.max' => 'Ukuran logo kiri maksimal 2MB.',
            'logo_kanan.image' => 'Logo kanan harus berupa file gambar.',
            'logo_kanan.max' => 'Ukuran logo kanan maksimal 2MB.',
        ]);

        $lembaga = Lembaga::first();
        if (!$lembaga) {
            $lembaga = new Lembaga();
            $lembaga->nama_sekolah = 'Sekolah Baru'; // Default
            $lembaga->save();
        }

        $data = $request->only(['kop_baris1', 'kop_baris2', 'kop_baris3', 'kop_baris4', 'kop_baris5']);

        // Handle Logo Kiri
        if ($request->hasFile('logo_kiri')) {
            if ($lembaga->logo_kiri && Storage::disk('public')->exists($lembaga->logo_kiri)) {
                Storage::disk('public')->delete($lembaga->logo_kiri);
            }
            $data['logo_kiri'] = $request->file('logo_kiri')->store('lembaga', 'public');
        }

        // Handle Logo Kanan
        if ($request->hasFile('logo_kanan')) {
            if ($lembaga->logo_kanan && Storage::disk('public')->exists($lembaga->logo_kanan)) {
                Storage::disk('public')->delete($lembaga->logo_kanan);
            }
            $data['logo_kanan'] = $request->file('logo_kanan')->store('lembaga', 'public');
        }

        $lembaga->update($data);

        return redirect()->back()->with('success', 'Kop Surat berhasil diperbarui.');
    }
}

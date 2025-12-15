<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Surat;
use App\Models\Lembaga;
use App\Models\LetterTemplate; // Import
use Inertia\Inertia;

class SuratController extends Controller
{
    public function index()
    {
        // For "Pengaturan Surat" - Only show Drafts (or all? User wanted separation).
        // User requested "di tabel baru ada arsipkan".
        // Let's make index ONLY Drafts.
        $surats = Surat::where('status', 'draft')->latest()->get();
        $templates = LetterTemplate::all();
        $lembaga = Lembaga::first();

        return Inertia::render('Admin/PengaturanSurat', [
            'surats' => $surats,
            'templates' => $templates,
            'lembaga' => $lembaga
        ]);
    }

    public function outgoing()
    {
        // For "Surat Keluar" - Only show Archived/Final
        $surats = Surat::where('status', 'archived')->latest()->get();
        
        return Inertia::render('Admin/SuratKeluar', [
            'surats' => $surats
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'jenis_surat' => 'required|string|max:255',
            'isi_surat' => 'required',
        ]);

        Surat::create($request->all());

        return redirect()->back()->with('success', 'Draft surat berhasil disimpan.');
    }

    public function update(Request $request, Surat $surat)
    {
        $request->validate([
            'jenis_surat' => 'required|string|max:255',
            'isi_surat' => 'required',
        ]);

        $surat->update($request->all());

        return redirect()->back()->with('success', 'Draft surat berhasil diperbarui.');
    }

    public function destroy(Surat $surat)
    {
        $surat->delete();
        return redirect()->back()->with('success', 'Surat berhasil dihapus.');
    }

    public function show(Surat $surat)
    {
        // For printing purposes
        $lembaga = Lembaga::first();
        return Inertia::render('Admin/CetakSurat', [
            'surat' => $surat,
            'lembaga' => $lembaga
        ]);
    }
    public function markAsArchived(Surat $surat)
    {
        $surat->update(['status' => 'archived']);
        return redirect()->back()->with('success', 'Surat berhasil diarsipkan.');
    }
}

<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Surat;
use App\Models\Lembaga;
use App\Models\LetterTemplate;
use Inertia\Inertia;
use Mpdf\Mpdf;
use Illuminate\Support\Facades\Storage;

class SuratController extends Controller
{
    /**
     * Display a listing of Drafts (Menu: Buat Surat - List) -> REDIRECT TO CREATE
     */
    public function index()
    {
        return redirect()->route('surat.create');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $templates = LetterTemplate::all();
        $lembaga = Lembaga::first();
        return Inertia::render('Admin/BuatSurat/Create', [
            'templates' => $templates,
            'lembaga' => $lembaga,
            'surat' => null,
            'isEditing' => false,
        ]);
    }

    /**
     * Display Outgoing/Archived letters (Menu: Surat Keluar).
     */
    public function outgoing()
    {
        $surats = Surat::where('status', 'archived')->latest()->get();
        return Inertia::render('Admin/SuratKeluar', [
            'surats' => $surats
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'template_id' => 'required',
            'jenis_surat' => 'required',
            'kode_surat' => 'required',
            'isi_surat' => 'required',
            'data_json' => 'nullable',
            'ukuran_kertas' => 'required',
            'orientation' => 'nullable',
        ], [
            'template_id.required' => 'Silakan pilih template surat.',
            'jenis_surat.required' => 'Jenis surat wajib diisi.',
            'kode_surat.required' => 'Nomor surat wajib diisi.',
            'isi_surat.required' => 'Isi surat tidak boleh kosong.',
        ]);
        
        $data['orientation'] = $data['orientation'] ?? 'Portrait';
        $data['status'] = 'draft';

        $surat = Surat::create($data);
        
        // Generate PDF immediately
        $this->generateAndSavePDF($surat);

        return redirect()->route('surat.edit', $surat)->with('success', 'Surat berhasil dibuat dan siap dicetak.');
    }

    public function update(Request $request, Surat $surat)
    {
        $data = $request->validate([
            'template_id' => 'required',
            'jenis_surat' => 'required',
            'kode_surat' => 'required',
            'isi_surat' => 'required',
            'data_json' => 'nullable',
            'ukuran_kertas' => 'required',
            'orientation' => 'nullable',
        ], [
            'template_id.required' => 'Silakan pilih template surat.',
            'jenis_surat.required' => 'Jenis surat wajib diisi.',
            'kode_surat.required' => 'Nomor surat wajib diisi.',
            'isi_surat.required' => 'Isi surat tidak boleh kosong.',
        ]);
        
        $data['orientation'] = $data['orientation'] ?? 'Portrait';

        $surat->update($data);

        // Regenerate PDF on update
        $this->generateAndSavePDF($surat);

        return redirect()->route('surat.edit', $surat)->with('success', 'Surat berhasil diperbarui.');
    }

    public function destroy(Surat $surat)
    {
        if ($surat->file_path && Storage::disk('public')->exists($surat->file_path)) {
            Storage::disk('public')->delete($surat->file_path);
        }
        $surat->delete();
        return redirect()->back()->with('success', 'Surat berhasil dihapus.');
    }

    public function edit(Surat $surat)
    {
        $templates = LetterTemplate::orderBy('name')->get();
        $lembaga = Lembaga::first();
        return Inertia::render('Admin/BuatSurat/Create', [
            'surat' => $surat,
            'templates' => $templates,
            'lembaga' => $lembaga,
            'isEditing' => true,
        ]);
    }

    public function markAsArchived(Surat $surat)
    {
        $surat->update(['status' => 'archived']);
        return redirect()->back()->with('success', 'Surat berhasil diarsipkan.');
    }

    /**
     * Print PDF using mPDF
     */
    /**
     * Print/View Saved PDF
     */
    public function print(Surat $surat)
    {
        // Otomatis arsipkan surat saat dicetak/dilihat final
        if ($surat->status !== 'archived') {
            $surat->update(['status' => 'archived']);
        }

        $relativePath = $surat->file_path;

        // Ensure PDF exists
        if (!$relativePath || !Storage::disk('public')->exists($relativePath)) {
            $relativePath = $this->generateAndSavePDF($surat);
        }
        
        // PENTING: Gunakan relativePath yang dijamin ada isinya
        $fullPath = Storage::disk('public')->path($relativePath);

        return response()->file($fullPath);
    }

    /**
     * Generate PDF and save to storage
     */
    private function generateAndSavePDF(Surat $surat)
    {
        $lembaga = Lembaga::first();
        if (!$lembaga) $lembaga = new Lembaga();

        // Convert Paper Size
        $format = $surat->ukuran_kertas ?? 'A4';
        if ($format === 'F4') {
            $format = [215, 330]; // Custom size for F4/Folio in mm
        }

        // --- Replacements Logic ---
        $content = $surat->isi_surat;
        
        // 1. [NOMOR_SURAT]
        if ($surat->kode_surat) {
            $content = str_replace('[NOMOR_SURAT]', $surat->kode_surat, $content);
        }

        // 2. {{ variable }} from data_json
        $dataVars = $surat->data_json ?? [];
        if (is_array($dataVars)) {
            foreach ($dataVars as $key => $value) {
                $content = str_replace('{{' . $key . '}}', $value, $content);
                $content = str_replace('{{ ' . $key . ' }}', $value, $content);
            }
        }
        
        // 3. Convert whitespace for mPDF compatibility
        $content = str_replace("\t", '&nbsp;&nbsp;&nbsp;&nbsp;', $content); 
        $content = preg_replace('/  /', '&nbsp;&nbsp;', $content); 
        
        // Use a temporary object for view rendering to avoid modifying the actual model instance
        $suratForView = clone $surat;
        $suratForView->isi_surat = $content;

        $orientation = ($surat->orientation === 'Landscape') ? 'L' : 'P';

        $mpdf = new Mpdf([
            'mode' => 'utf-8',
            'format' => $format,
            'orientation' => $orientation,
            'margin_top' => 20,
            'margin_bottom' => 10,
            'margin_left' => 15,
            'margin_right' => 15,
        ]);

        $html = view('pdf.surat', ['surat' => $suratForView, 'lembaga' => $lembaga])->render();
        $mpdf->WriteHTML($html);
        
        // Define Filename
        $filename = 'Surat-keluar-' . time() . '-' . $surat->id . '.pdf';
        $path = 'surat-keluar/' . $filename;
        
        // Save to storage (public disk)
        Storage::disk('public')->put($path, $mpdf->Output('', 'S'));
        
        // Update model
        $surat->update(['file_path' => $path]);
        
        return $path;
    }

    /**
     * Preview PDF Stream from Post Data (No Save)
     */
    public function previewStream(Request $request)
    {
        $data = $request->all();
        $surat = new Surat($data); // Temp object
        
        $lembaga = Lembaga::first();
        if (!$lembaga) $lembaga = new Lembaga();

        // Convert Paper Size
        $format = $surat->ukuran_kertas ?? 'A4';
        if ($format === 'F4') {
            $format = [215, 330];
        }

        // --- Replacements Logic ---
        $content = $surat->isi_surat;
        if ($surat->kode_surat) {
            $content = str_replace('[NOMOR_SURAT]', $surat->kode_surat, $content);
        }
        $dataVars = $surat->data_json ?? [];
        if (is_array($dataVars)) {
            foreach ($dataVars as $key => $value) {
                $content = str_replace('{{' . $key . '}}', $value, $content);
                $content = str_replace('{{ ' . $key . ' }}', $value, $content);
            }
        }
        $surat->isi_surat = $content;
        // --------------------------

        $orientation = ($surat->orientation === 'Landscape') ? 'L' : 'P';

        $mpdf = new Mpdf([
            'mode' => 'utf-8',
            'format' => $format,
            'orientation' => $orientation,
            'margin_top' => 20,
            'margin_bottom' => 10,
            'margin_left' => 15,
            'margin_right' => 15,
        ]);

        $html = view('pdf.surat', compact('surat', 'lembaga'))->render();
        $mpdf->WriteHTML($html);
        return response($mpdf->Output("preview.pdf", 'S'))
            ->header('Content-Type', 'application/pdf');
    }
}

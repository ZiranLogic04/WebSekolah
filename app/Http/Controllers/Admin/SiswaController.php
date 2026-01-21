<?php

namespace App\Http\Controllers\Admin;

use Inertia\Inertia;
use App\Models\Siswa;
use App\Imports\SiswaImport;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Maatwebsite\Excel\Facades\Excel;

use App\Models\Kelas;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\File;

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
        
        // Filter status - default hanya tampilkan siswa Aktif
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        } else {
            // Default: hanya tampilkan siswa Aktif (tidak termasuk Lulus, Pindah, Keluar)
            $query->where('status', 'Aktif');
        }
        
        if ($request->filled('jenis_kelamin')) {
            $query->where('jenis_kelamin', $request->jenis_kelamin);
        }
        
        // Support per_page dari frontend
        $perPage = $request->input('per_page', 10);
        
        $siswa = $query->orderBy('created_at', 'desc')
            ->paginate($perPage)
            ->withQueryString();

        $kelasList = Kelas::orderBy('nama')->pluck('nama');
        
        return Inertia::render('Admin/DataSiswa', [
            'siswa' => $siswa,
            'filters' => $filters,
            'kelasList' => $kelasList,
        ]);
    }



    public function store(Request $request)
    {
        $request->validate([
            'nis' => 'required|unique:siswa,nis',
            'nisn' => 'nullable|unique:siswa,nisn',
            'nik' => 'nullable|unique:siswa,nik',
            'nama' => 'required|string|max:255',
            'kelas' => 'required|exists:kelas,nama',
            'jenis_kelamin' => 'required|in:L,P',
            'status' => 'nullable|in:Aktif,Non-Aktif,Pindah,Lulus,Keluar,Dikeluarkan,Mengundurkan Diri',
            'file_foto' => 'nullable|image|max:2048',
            'file_akte' => 'nullable|file|max:2048',
            'file_kk' => 'nullable|file|max:2048',
            'file_kip' => 'nullable|file|max:2048',
            'file_ktp_ortu' => 'nullable|file|max:2048',
        ], [
            'nis.unique' => 'NIS sudah terdaftar. Gunakan NIS yang berbeda.',
            'nisn.unique' => 'NISN sudah terdaftar untuk siswa lain.',
            'nik.unique' => 'NIK sudah terdaftar untuk siswa lain.',
            'kelas.exists' => 'Kelas yang dipilih tidak valid.',
            'nama.required' => 'Nama wajib diisi.',
            'jenis_kelamin.required' => 'Jenis kelamin wajib dipilih.',
            'kelas.required' => 'Kelas wajib dipilih.',
        ]);

        $data = $request->except(['file_foto', 'file_akte', 'file_kk', 'file_kip', 'file_ktp_ortu']);
        
        $data = $this->handleFiles($request, $data);
        
        // Default status
        $data['status'] = $data['status'] ?? 'Aktif';

        Siswa::create($data);

        return redirect()->route('siswa.index')->with('success', 'Data siswa berhasil ditambahkan');
    }

    public function update(Request $request, Siswa $siswa)
    {   
        $request->validate([
            'nis' => 'required|unique:siswa,nis,' . $siswa->id,
            'nisn' => 'nullable|unique:siswa,nisn,' . $siswa->id,
            'nik' => 'nullable|unique:siswa,nik,' . $siswa->id,
            'nama' => 'required|string|max:255',
            'kelas' => 'required|string|max:10',
            'jenis_kelamin' => 'required|in:L,P',
            'status' => 'nullable|in:Aktif,Non-Aktif,Pindah,Lulus,Keluar,Dikeluarkan,Mengundurkan Diri',
            'file_foto' => 'nullable|image|max:2048',
            'file_akte' => 'nullable|file|max:2048',
            'file_kk' => 'nullable|file|max:2048',
            'file_kip' => 'nullable|file|max:2048',
            'file_ktp_ortu' => 'nullable|file|max:2048',
        ], [
            'nis.unique' => 'NIS sudah terdaftar. Gunakan NIS yang berbeda.',
            'nisn.unique' => 'NISN sudah terdaftar untuk siswa lain.',
            'nik.unique' => 'NIK sudah terdaftar untuk siswa lain.',
            'nama.required' => 'Nama wajib diisi.',
            'jenis_kelamin.required' => 'Jenis kelamin wajib dipilih.',
            'kelas.required' => 'Kelas wajib dipilih.',
        ]);

        $data = $request->except(['file_foto', 'file_akte', 'file_kk', 'file_kip', 'file_ktp_ortu']);
        
        $data = $this->handleFiles($request, $data, $siswa);
        
        // Keep existing status if not provided
        $data['status'] = $data['status'] ?? $siswa->status;

        $siswa->update($data);

        return redirect()->back()->with('success', 'Data siswa berhasil diperbarui');
    }

    private function handleFiles(Request $request, array $data, Siswa $siswa = null)
    {
        $fileFields = ['file_foto', 'file_akte', 'file_kk', 'file_kip', 'file_ktp_ortu'];
        
        foreach ($fileFields as $field) {
            if ($request->hasFile($field)) {
                // Delete old file if updating
                if ($siswa && $siswa->$field && \Storage::disk('public')->exists($siswa->$field)) {
                    \Storage::disk('public')->delete($siswa->$field);
                }
                $data[$field] = $this->processFileUpload($request->file($field), $field);
            }
        }
        return $data;
    }
    /**
     * Process file upload with WebP conversion for images
     */
    private function processFileUpload($file, $fieldName)
    {
        $fileName = time() . '_' . $fieldName;
        
        $info = @getimagesize($file);
        if ($info) {
            $mime = $info['mime'];
            $webpPath = 'siswa/' . $fileName . '.webp';
            $fullPath = storage_path('app/public/' . $webpPath);
            
            if (!file_exists(dirname($fullPath))) {
                mkdir(dirname($fullPath), 0755, true);
            }
            
            switch ($mime) {
                case 'image/jpeg':
                    $image = imagecreatefromjpeg($file);
                    break;
                case 'image/png':
                    $image = imagecreatefrompng($file);
                    imagepalettetotruecolor($image);
                    imagealphablending($image, true);
                    imagesavealpha($image, true);
                    break;
                case 'image/gif':
                    $image = imagecreatefromgif($file);
                    break;
                case 'image/webp':
                    $image = imagecreatefromwebp($file);
                    break;
                default:
                    return $file->storeAs('siswa', $fileName . '.' . $file->getClientOriginalExtension(), 'public');
            }
            
            imagewebp($image, $fullPath, 80);
            imagedestroy($image);
            
            return $webpPath;
        }
        
        // Non-image files (PDF)
        return $file->storeAs('siswa', $fileName . '.' . $file->getClientOriginalExtension(), 'public');
    }

    public function destroy(Siswa $siswa)
    {
        // Delete associated files
        $fileFields = ['file_foto', 'file_akte', 'file_kk', 'file_kip', 'file_ktp_ortu'];
        foreach ($fileFields as $field) {
            if ($siswa->$field && \Storage::disk('public')->exists($siswa->$field)) {
                \Storage::disk('public')->delete($siswa->$field);
            }
        }
        
        $siswa->delete();
        
        return to_route('siswa.index')
            ->with('success', 'Data siswa berhasil dihapus');
    }

    /**
     * Check if student has pending tagihan before delete
     */
    public function checkTagihan(Siswa $siswa)
    {
        $tagihans = \App\Models\Tagihan::where('siswa_id', $siswa->id)
            ->where('status', 'Belum Lunas')
            ->get();
        
        $totalPiutang = $tagihans->sum('sisa');
        $jumlahTagihan = $tagihans->count();
        
        return response()->json([
            'has_tagihan' => $jumlahTagihan > 0,
            'jumlah_tagihan' => $jumlahTagihan,
            'total_piutang' => $totalPiutang,
            'nama_siswa' => $siswa->nama,
        ]);
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
    public function previewImport(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xlsm,xls,csv|max:5120'
        ]);

        try {
            // Gunakan SiswaImport untuk mapping heading
            $data = Excel::toArray(new SiswaImport, $request->file('file'));
            
            if (empty($data) || empty($data[0])) {
                return response()->json(['message' => 'File kosong atau format tidak sesuai'], 422);
            }
            
            $rows = $data[0];
            $previewRows = [];
            $validCount = 0;
            $invalidCount = 0;
            
            // Ambil headers dari keys baris pertama (slugified by Laravel Excel)
            // Jika row kosong tidak punya keys, kita bisa ambil dari SiswaImport headingRow logic kalau perlu
            // Tapi sederhananya ambil keys dari row pertama yang tidak kosong
            $headers = [];
            if (count($rows) > 0) {
                $headers = array_keys($rows[0]);
            }

            // Track NIS di file ini untuk cek duplikat internal
            $nisInFile = [];

            foreach ($rows as $index => $rowArray) {
                // Identifikasi Field Utama
                $nis = $rowArray['nis'] ?? null;
                // Nama siswa bisa 'nama' atau 'nama_siswa'
                $nama = $rowArray['nama_siswa'] ?? ($rowArray['nama'] ?? null);
                
                // Skip jika baris kosong (semua value null/empty strings)
                if (empty(array_filter($rowArray))) {
                    continue;
                }
                
                // Skip jika identifier utama kosong tapi ada data lain (mungkin baris sampah)
                // Tapi kalau user mau import data tanpa NIS (auto generate?) -> sementara wajib NIS
                if (empty($nis) && empty($nama)) {
                    continue;
                }

                // Mapping Data Penting untuk Validasi
                $kelasNama = $rowArray['kelas'] ?? ($rowArray['diterima_di_kelas'] ?? null);
                $jenisKelamin = $rowArray['l_p'] ?? ($rowArray['lp'] ?? ($rowArray['jenis_kelamin'] ?? ($rowArray['jk'] ?? null)));
                
                // Validasi Database
                $validator = \Illuminate\Support\Facades\Validator::make([
                    'nis' => $nis,
                    'nama' => $nama,
                    'kelas' => $kelasNama,
                    'jenis_kelamin' => $jenisKelamin,
                ], [
                    'nis' => 'required|unique:siswa,nis',
                    'nama' => 'required',
                    'kelas' => 'required', 
                    'jenis_kelamin' => 'required|in:L,P',
                ], [
                    'nis.required' => 'NIS wajib diisi',
                    'nis.unique' => 'NIS sudah terdaftar di sistem',
                    'nama.required' => 'Nama wajib diisi',
                    'kelas.required' => 'Kelas wajib diisi',
                    'jenis_kelamin.required' => 'Jenis Kelamin (L/P) wajib diisi',
                    'jenis_kelamin.in' => 'Jenis Kelamin harus L atau P',
                ]);

                $errors = $validator->errors()->all();
                
                // Validasi Duplikat Internal File
                if ($nis && in_array($nis, $nisInFile)) {
                    $errors[] = "NIS '$nis' duplikat di dalam file ini";
                } elseif ($nis) {
                    $nisInFile[] = $nis;
                }
                
                // Cek Kelas Exists
                if ($kelasNama && !\App\Models\Kelas::where('nama', $kelasNama)->exists()) {
                    $errors[] = "Kelas '$kelasNama' tidak ada di database";
                }

                $isValid = empty($errors);

                if ($isValid) {
                    $validCount++;
                } else {
                    $invalidCount++;
                }
                
                // Tambahkan info status/errors ke row data asli untuk ditampilkan di FE
                $rowArray['__row'] = $index + 2;
                $rowArray['__status'] = $isValid ? 'Valid' : 'Invalid';
                $rowArray['__errors'] = $errors;
                
                // Normalisasi Key untuk ditampilkan di Frontend
                $rowArray['nama'] = $nama; 
                $rowArray['kelas'] = $kelasNama;
                
                // Robust mapping untuk L/P (variasi slug)
                $rowArray['jenis_kelamin'] = $rowArray['l_p'] ?? ($rowArray['lp'] ?? ($rowArray['jenis_kelamin'] ?? ($rowArray['jk'] ?? null)));
                
                $rowArray['nisn'] = $rowArray['nisn'] ?? null;
                
                // Field tambahan yang "bagus" untuk ditampilkan
                $rowArray['tempat_lahir'] = $rowArray['tempat_lahir'] ?? null;
                $rowArray['tanggal_lahir'] = $rowArray['tgl_lahir'] ?? ($rowArray['tanggal_lahir'] ?? null);
                $rowArray['nama_ayah'] = $rowArray['nama_ayah'] ?? null;

                // Ambil semua data (tanpa limit, sesuai request user)
                $previewRows[] = $rowArray;
            }
            
            return response()->json([
                'headers' => $headers, // Kirim headers untuk render kolom dinamis
                'preview' => $previewRows,
                'total_rows' => count($rows),
                'valid_count' => $validCount,
                'invalid_count' => $invalidCount
            ]);
            
        } catch (\Exception $e) {
            return response()->json(['message' => 'Gagal memproses file: ' . $e->getMessage()], 500);
        }
    }

    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls,csv,xlsm|max:5120'
        ]);

        try {
            $import = new SiswaImport;
            Excel::import($import, $request->file('file'));
            
            $successCount = $import->getSuccessCount();
            $errorCount = $import->getErrorCount();
            $errors = $import->getErrors();
            
            // Return JSON untuk diproses frontend (Show Result Modal)
            return response()->json([
                'success' => true,
                'importResult' => [
                    'successCount' => $successCount,
                    'errorCount' => $errorCount,
                    'errors' => $errors,
                ],
                'message' => "Import selesai. Berhasil: {$successCount}, Gagal: {$errorCount}"
            ]);
                
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat import: ' . $e->getMessage()
            ], 500);
        }
    }
    public function downloadTemplate()
    {
        return Excel::download(new \App\Exports\SiswaTemplateExport, 'template_siswa.xlsx');
    }

    public function viewDocument(Siswa $siswa, $type)
    {
        // Validasi tipe dokumen yang diizinkan
        $allowedTypes = [
            'Foto Siswa' => 'file_foto',
            'Akte Kelahiran' => 'file_akte',
            'Kartu Keluarga' => 'file_kk',
            'Kartu Indonesia Pintar' => 'file_kip',
            'KTP Orang Tua' => 'file_ktp_ortu',
            // Mapping juga key langsung kalau-kalau frontend kirim key
            'file_foto' => 'file_foto',
            'file_akte' => 'file_akte',
            'file_kk' => 'file_kk',
            'file_kip' => 'file_kip',
            'file_ktp_ortu' => 'file_ktp_ortu',
        ];

        // Normalisasi type (bisa dikirim label atau key)
        $dbField = $allowedTypes[$type] ?? $type;
        
        if (!in_array($dbField, $allowedTypes)) {
            abort(404, 'Tipe dokumen tidak valid.');
        }

        $filename = $siswa->$dbField;

        if (!$filename) {
            abort(404, 'Dokumen belum diupload.');
        }

        // Path penyimpanan (sesuaikan dengan config filesystems default: public)
        // Storage::path akan mengembalikan full absolute path
        $path = Storage::disk('public')->path($filename);

        if (!File::exists($path)) {
            abort(404, 'File fisik tidak ditemukan di server.');
        }

        // Return response using file helper which handles headers correctly for preview
        return response()->file($path);
    }
}
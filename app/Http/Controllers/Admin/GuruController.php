<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Guru;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\GuruImport;
use App\Exports\GuruExport;

class GuruController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Guru::query();
        
        // Filtering
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('nip', 'like', "%{$search}%")
                  ->orWhere('nama', 'like', "%{$search}%")
                  ->orWhere('bidang_studi', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }
        
        if ($request->filled('status_kepegawaian')) {
            $query->where('status_kepegawaian', $request->status_kepegawaian);
        }
        
        if ($request->filled('status_aktif')) {
            $query->where('status_aktif', $request->status_aktif);
        }
        
        if ($request->filled('jenis_kelamin')) {
            $query->where('jenis_kelamin', $request->jenis_kelamin);
        }
        
        // Sorting
        $sortField = $request->get('sort', 'created_at');
        $sortDirection = $request->get('direction', 'desc');
        
        if (in_array($sortField, ['nip', 'nama', 'bidang_studi', 'jenis_kelamin', 'tanggal_lahir', 'no_telepon', 'status_kepegawaian', 'status_aktif'])) {
            $query->orderBy($sortField, $sortDirection);
        } else {
            $query->orderBy('created_at', 'desc');
        }
        
        $guru = $query->paginate($request->get('per_page', 10));
        
        return inertia('Admin/DataGuru', [
            'guru' => $guru,
            'filters' => $request->only(['search', 'status_kepegawaian', 'status_aktif', 'jenis_kelamin']),
        ]);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nip' => 'required|unique:guru,nip|max:20',
            'nama' => 'required|max:100',
            'bidang_studi' => 'nullable|max:50',
            'jenis_kelamin' => 'required|in:L,P',
            'tempat_lahir' => 'nullable|max:50',
            'tanggal_lahir' => 'nullable|date',
            'alamat' => 'nullable',
            'email' => 'nullable|email|unique:guru,email',
            'no_telepon' => 'nullable|max:15',
            'status_kepegawaian' => 'nullable|in:PNS,Honorer,Kontrak,Tetap',
            'status_aktif' => 'required|in:Aktif,Non-Aktif,Cuti,Pensiun',
            'pendidikan_terakhir' => 'nullable|max:100',
            'tanggal_mulai_bekerja' => 'nullable|date',
            'foto' => 'nullable|image|max:2048',
            'facebook' => 'nullable|url|max:255',
            'instagram' => 'nullable|url|max:255',
            'twitter' => 'nullable|url|max:255',
            'linkedin' => 'nullable|url|max:255',
        ], [
            'nip.required' => 'NIP wajib diisi.',
            'nip.unique' => 'NIP sudah terdaftar.',
            'nama.required' => 'Nama wajib diisi.',
            'jenis_kelamin.required' => 'Jenis kelamin wajib dipilih.',
            'status_aktif.required' => 'Status aktif wajib dipilih.',
            'email.unique' => 'Email sudah terdaftar.',
            'foto.max' => 'Ukuran foto maksimal 2MB.',
            'foto.image' => 'File harus berupa gambar.',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        try {
            $data = $request->except(['foto']);
            
            // Handle file upload
            if ($request->hasFile('foto')) {
                $data['foto'] = $this->processFileUpload($request->file('foto'), 'foto_guru');
            }

            Guru::create($data);
            
            return redirect()->back()->with('success', 'Data guru berhasil ditambahkan');
                
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage())->withInput();
        }
    }

    public function update(Request $request, string $id)
    {
        $guru = Guru::findOrFail($id);
        
        $validator = Validator::make($request->all(), [
            'nip' => 'required|max:20|unique:guru,nip,' . $id,
            'nama' => 'required|max:100',
            'bidang_studi' => 'nullable|max:50',
            'jenis_kelamin' => 'required|in:L,P',
            'tempat_lahir' => 'nullable|max:50',
            'tanggal_lahir' => 'nullable|date',
            'alamat' => 'nullable',
            'email' => 'nullable|email|unique:guru,email,' . $id,
            'no_telepon' => 'nullable|max:15',
            'status_kepegawaian' => 'nullable|in:PNS,Honorer,Kontrak,Tetap',
            'status_aktif' => 'required|in:Aktif,Non-Aktif,Cuti,Pensiun',
            'pendidikan_terakhir' => 'nullable|max:100',
            'tanggal_mulai_bekerja' => 'nullable|date',
            'foto' => 'nullable|image|max:2048',
            'facebook' => 'nullable|url|max:255',
            'instagram' => 'nullable|url|max:255',
            'twitter' => 'nullable|url|max:255',
            'linkedin' => 'nullable|url|max:255',
        ], [
            'nip.required' => 'NIP wajib diisi.',
            'nip.unique' => 'NIP sudah terdaftar.',
            'nama.required' => 'Nama wajib diisi.',
            'jenis_kelamin.required' => 'Jenis kelamin wajib dipilih.',
            'status_aktif.required' => 'Status aktif wajib dipilih.',
            'email.unique' => 'Email sudah terdaftar.',
            'foto.max' => 'Ukuran foto maksimal 2MB.',
            'foto.image' => 'File harus berupa gambar.',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        try {
            $data = $request->except(['foto']);

            if ($request->hasFile('foto')) {
                // Delete old image
                if ($guru->foto && \Storage::disk('public')->exists($guru->foto)) {
                    \Storage::disk('public')->delete($guru->foto);
                }
                $data['foto'] = $this->processFileUpload($request->file('foto'), 'foto_guru');
            }

            $guru->update($data);
            
            return redirect()->back()->with('success', 'Data guru berhasil diperbarui');
                
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage())->withInput();
        }
    }

    public function destroy(string $id)
    {
        try {
            $guru = Guru::findOrFail($id);
            $nama = $guru->nama;
            
            if ($guru->foto && \Storage::disk('public')->exists($guru->foto)) {
                \Storage::disk('public')->delete($guru->foto);
            }

            $guru->delete();
            
            return redirect()->back()->with('success', "Data guru {$nama} berhasil dihapus");
                
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Terjadi kesalahan sistem saat menghapus data guru.');
        }
    }

    public function bulkUpdate(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'ids' => 'required|array',
            'ids.*' => 'exists:guru,id',
            'status_aktif' => 'nullable|in:Aktif,Non-Aktif,Cuti,Pensiun',
            'status_kepegawaian' => 'nullable|in:PNS,Honorer,Kontrak,Tetap',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->with('error', 'Data tidak valid');
        }

        try {
            $updateData = [];
            
            if ($request->filled('status_aktif')) {
                $updateData['status_aktif'] = $request->status_aktif;
            }
            
            if ($request->filled('status_kepegawaian')) {
                $updateData['status_kepegawaian'] = $request->status_kepegawaian;
            }
            
            if (!empty($updateData)) {
                Guru::whereIn('id', $request->ids)->update($updateData);
            }
            
            return redirect()->back()->with('success', "Berhasil mengupdate data guru yang dipilih");
                
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function bulkDestroy(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'ids' => 'required|array',
            'ids.*' => 'exists:guru,id',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->with('error', 'Data tidak valid');
        }

        try {
            $gurus = Guru::whereIn('id', $request->ids)->get();
            $count = $gurus->count();
            
            foreach ($gurus as $guru) {
                 if ($guru->foto && \Storage::disk('public')->exists($guru->foto)) {
                    \Storage::disk('public')->delete($guru->foto);
                }
                $guru->delete();
            }
            
            return redirect()->back()->with('success', "Berhasil menghapus {$count} data guru");
                
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function previewImport(Request $request) 
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls,csv|max:5120'
        ]);

        try {
            $file = $request->file('file');
            $data = Excel::toCollection(new GuruImport, $file)->first();
            
            $preview = [];
            $validCount = 0;
            $invalidCount = 0;

            foreach ($data as $index => $row) {
                // Skip empty rows
                if (empty($row['nip']) && empty($row['nama'])) continue;

                $validator = Validator::make($row->toArray(), [
                   'nip' => 'required|unique:guru,nip',
                   'nama' => 'required',
                   'jenis_kelamin' => 'required|in:L,P',
                ]);

                $isValid = !$validator->fails();
                $errors = $validator->errors()->all();

                if ($isValid) $validCount++;
                else $invalidCount++;

                $preview[] = [
                    'nip' => $row['nip'],
                    'nama' => $row['nama'],
                    'jenis_kelamin' => $row['jenis_kelamin'] ?? 'L',
                    'bidang_studi' => $row['bidang_studi'] ?? '',
                    'status_kepegawaian' => $row['status_kepegawaian'] ?? '',
                    'is_valid' => $isValid,
                    'errors' => $errors
                ];

                // Limit preview to 10 rows for speed if needed, but for now show all to user
            }

            return response()->json([
                'preview_data' => $preview,
                'stats' => [
                    'valid' => $validCount,
                    'invalid' => $invalidCount,
                    'total' => count($preview)
                ]
            ]);

        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function import(Request $request)
    {
        // Support both file upload (legacy/direct) and confirmed JSON data
        // If 'data' is present, use that. Else look for 'file'.
        
        try {
             if ($request->has('data')) {
                 $rows = $request->input('data');
                 $successCount = 0;
                 $errorCount = 0;
                 
                 foreach ($rows as $row) {
                     if (!$row['is_valid']) {
                         $errorCount++;
                         continue;
                     }
                     
                     try {
                         Guru::create([
                             'nip' => $row['nip'],
                             'nama' => $row['nama'],
                             'jenis_kelamin' => $row['jenis_kelamin'],
                             'bidang_studi' => $row['bidang_studi'],
                             'status_kepegawaian' => $row['status_kepegawaian'],
                             'status_aktif' => 'Aktif', // Default
                             'created_at' => now(),
                             'updated_at' => now(),
                         ]);
                         $successCount++;
                     } catch (\Exception $e) {
                         $errorCount++;
                     }
                 }
                 
                 if ($successCount > 0) {
                     return redirect()->route('guru.index')->with('success', "Berhasil mengimport {$successCount} data guru.");
                 } else {
                     return redirect()->route('guru.index')->with('error', "Gagal mengimport data.");
                 }
                 
             } else {
                // Legacy Direct File Import (Fallback)
                $request->validate(['file' => 'required|mimes:xlsx,xls,csv|max:5120']);
                Excel::import(new GuruImport, $request->file('file'));
                return redirect()->back()->with('success', 'Import berhasil');
             }

        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Terjadi kesalahan saat import: ' . $e->getMessage());
        }
    }

    public function downloadTemplate()
    {
        return Excel::download(new GuruExport, 'template-import-guru.xlsx');
    }

    /**
     * Helper: Process file upload with WebP conversion
     */
    private function processFileUpload($file, $fieldName)
    {
        $fileName = time() . '_' . $fieldName;
        
        $info = @getimagesize($file);
        if ($info) {
            $mime = $info['mime'];
            $webpPath = 'guru/' . $fileName . '.webp'; // Store in guru folder
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
                    return $file->storeAs('guru', $fileName . '.' . $file->getClientOriginalExtension(), 'public');
            }
            
            imagewebp($image, $fullPath, 80);
            imagedestroy($image);
            
            return $webpPath;
        }
        
        return $file->storeAs('guru', $fileName . '.' . $file->getClientOriginalExtension(), 'public');
    }


   
}
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

    /**
     * Store a newly created resource in storage.
     */
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
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        try {
            Guru::create($request->all());
            
            return redirect()->route('guru.index')
                ->with('success', 'Data guru berhasil ditambahkan');
                
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Update the specified resource in storage.
     */
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
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        try {
            $guru->update($request->all());
            
            return redirect()->route('guru.index')
                ->with('success', 'Data guru berhasil diperbarui');
                
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $guru = Guru::findOrFail($id);
            $nama = $guru->nama;
            $guru->delete();
            
            return redirect()->route('guru.index')
                ->with('success', "Data guru {$nama} berhasil dihapus");
                
        } catch (\Exception $e) {
            \Log::error('Error deleting guru: ' . $e->getMessage());
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan sistem saat menghapus data guru.');
        }
    }

    /**
     * Bulk update guru data
     */
    public function bulkUpdate(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'ids' => 'required|array',
            'ids.*' => 'exists:guru,id',
            'status_aktif' => 'nullable|in:Aktif,Non-Aktif,Cuti,Pensiun',
            'status_kepegawaian' => 'nullable|in:PNS,Honorer,Kontrak,Tetap',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->with('error', 'Data tidak valid');
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
            
            return redirect()->route('guru.index')
                ->with('success', "Berhasil mengupdate {$request->ids} data guru");
                
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * Bulk delete guru
     */
    public function bulkDestroy(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'ids' => 'required|array',
            'ids.*' => 'exists:guru,id',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->with('error', 'Data tidak valid');
        }

        try {
            $count = Guru::whereIn('id', $request->ids)->count();
            Guru::whereIn('id', $request->ids)->delete();
            
            return redirect()->route('guru.index')
                ->with('success', "Berhasil menghapus {$count} data guru");
                
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * Import data from Excel
     */
    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls,csv|max:5120'
        ]);

        try {
            $import = new GuruImport;
            Excel::import($import, $request->file('file'));
            
            $successCount = $import->getSuccessCount();
            $errorCount = $import->getErrorCount();
            $errors = $import->getErrors();
            
            // Tentukan flash message berdasarkan hasil
            $redirect = redirect()->route('guru.index')
                ->with('importResult', [
                    'successCount' => $successCount,
                    'errorCount' => $errorCount,
                    'errors' => $errors,
                ]);
            
            if ($successCount > 0 && $errorCount === 0) {
                $redirect->with('success', "Berhasil mengimport {$successCount} data guru");
            } elseif ($successCount > 0 && $errorCount > 0) {
                $redirect->with('warning', "Import selesai: {$successCount} berhasil, {$errorCount} gagal");
            } elseif ($successCount === 0 && $errorCount > 0) {
                $redirect->with('error', "Import gagal: semua {$errorCount} data tidak valid");
            } else {
                $redirect->with('warning', "Tidak ada data yang diimport");
            }
            
            return $redirect;
                
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan saat import: ' . $e->getMessage());
        }
    }

    /**
     * Download template for import
     */
    public function downloadTemplate()
    {
        return Excel::download(new GuruExport, 'template-import-guru.xlsx');
    }

   
}
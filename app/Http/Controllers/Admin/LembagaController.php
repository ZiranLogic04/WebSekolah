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
        
        // If not exists, create a default empty one or just pass null to be handled by frontend
        // But for easier handling, let's pass null and handle in Store/Update
        
        return Inertia::render('Admin/DataLembaga', [
            'lembaga' => $lembaga
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
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
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

        return redirect()->back()->with('success', 'Data lembaga berhasil diperbarui.');
    }
}

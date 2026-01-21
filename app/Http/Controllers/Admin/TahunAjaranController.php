<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\TahunAjaran;
use Illuminate\Http\Request;

class TahunAjaranController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'tahun' => 'required|string|max:20|unique:tahun_ajarans,tahun',
        ], [
            'tahun.required' => 'Tahun ajaran harus diisi.',
            'tahun.unique' => 'Tahun ajaran sudah ada.',
        ]);

        TahunAjaran::create([
            'tahun' => $request->tahun,
        ]);

        return redirect()->back()->with('success', 'Tahun ajaran berhasil ditambahkan.');
    }
}

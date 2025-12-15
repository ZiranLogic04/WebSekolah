<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\UangSekolah;
use Illuminate\Http\Request;
use Inertia\Inertia;

class TransaksiManualController extends Controller
{
    public function index()
    {
        // Riwayat transaksi manual saja (yang is_manual = true)
        $riwayat = UangSekolah::where('is_manual', true)
            ->orderBy('tanggal', 'desc')
            ->orderBy('created_at', 'desc')
            ->take(20)
            ->get();
        
        return Inertia::render('Admin/Keuangan/TransaksiManual', [
            'riwayat' => $riwayat,
        ]);
    }
    
    public function store(Request $request)
    {
        $request->validate([
            'tipe' => 'required|in:Masuk,Keluar',
            'kategori' => 'required|string|max:255',
            'jumlah' => 'required|numeric|min:1',
            'tanggal' => 'required|date',
            'keterangan' => 'nullable|string|max:500',
        ]);
        
        UangSekolah::create([
            'tipe' => $request->tipe,
            'kategori' => $request->kategori,
            'jumlah' => $request->jumlah,
            'tanggal' => $request->tanggal,
            'sumber' => $request->keterangan,
            'keterangan' => $request->keterangan,
            'is_manual' => true,
        ]);
        
        return redirect()->back()->with('success', 'Transaksi berhasil dicatat.');
    }
}

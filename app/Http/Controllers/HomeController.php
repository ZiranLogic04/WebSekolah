<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        // Berita terbaru (TETAP DINAMIS)
        $posts = \App\Models\Post::published()->with('author')->latest('published_at')->take(3)->get();
        
        // Statistik dari Halaman Sarpras (MANUAL)
        $sarpras = \App\Models\Section::where('key', 'sarpras')->first();
        $content = $sarpras ? $sarpras->content : [];
        
        $stats = [
            'siswa_aktif' => $content['jumlah_siswa'] ?? '0',
            'guru' => $content['jumlah_guru'] ?? '0',
            'rombel' => $content['jumlah_rombel'] ?? '0',
            'alumni' => $content['jumlah_alumni'] ?? '0',
        ];

        return Inertia::render('Beranda', [
            'posts' => $posts,
            'stats' => $stats
        ]);
    }
}

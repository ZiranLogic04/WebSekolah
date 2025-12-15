<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        $posts = \App\Models\Post::published()->with('author')->latest('published_at')->take(3)->get();
        $sections = \App\Models\Section::all()->keyBy('key');
        
        $stats = [
            'siswa_aktif' => \App\Models\Siswa::where('status', '!=', 'Lulus')->where('status', '!=', 'Keluar')->count(),
            'guru' => \App\Models\Guru::count(),
            'rombel' => \App\Models\Kelas::count(),
            'alumni' => \App\Models\Siswa::where('status', 'Lulus')->count(),
        ];

        return Inertia::render('Beranda', [
            'posts' => $posts,
            'sections' => $sections,
            'stats' => $stats
        ]);
    }
}

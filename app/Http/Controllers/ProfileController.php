<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\Section;

class ProfileController extends Controller
{
    /**
     * Display the specified profile page.
     *
     * @param  string  $slug
     * @return \Inertia\Response
     */
    public function show($slug)
    {
        // Map slug to section key
        $keyMap = [
            // Profil Madrasah
            'visi-misi' => 'visi_misi',
            'sejarah' => 'sejarah',
            'sambutan' => 'sambutan',
            'kepala-madrasah' => 'sambutan',
            'struktur-organisasi' => 'organisasi',
            'sarana-prasarana' => 'sarpras',
            'tujuan' => 'tujuan',
            // Akademik
            'kurikulum-mapel' => 'kurikulum',
            // Guru
            'direktori-guru' => 'direktori_guru',
            // PPDB
            'syarat-dan-alur' => 'ppdb_syarat',
            'jadwal-dan-kuota' => 'ppdb_kuota',
        ];

        $key = $keyMap[$slug] ?? null;

        if ($key) {
            $section = Section::where('key', $key)->first();
            $content = $section ? $section->content : [];
            $title = $this->getTitle($key);
        } else {
            // Coba cari di MenuItem (untuk halaman generik)
            $menuItem = \App\Models\MenuItem::where('slug', $slug)->where('is_active', true)->first();
            
            if (!$menuItem) {
                // Cek juga di MenuNavbar (level 1)
                $menuNavbar = \App\Models\MenuNavbar::where('slug', $slug)->where('is_active', true)->first();
                if (!$menuNavbar) abort(404);
                
                $title = $menuNavbar->nama;
                $content = ['content' => '']; // Atau ambil konten jika ada kolom konten di menu_navbars
            } else {
                $title = $menuItem->nama;
                // Asumsikan MenuItem punya kolom 'content' atau kita ambil dari field lain.
                // Jika struktur DB MenuItem belum punya 'content', kita mungkin perlu join atau pakai tabel lain.
                // Untuk sementara kita pakai $menuItem->content (pastikan model dan migrasi mendukung)
                $content = ['content' => $menuItem->content]; 
            }
        }

        // Get guru data for direktori guru page
        $gurus = [];
        if ($key === 'direktori_guru') {
            $gurus = \App\Models\Guru::orderBy('nama')->get();
        }
        
        // Real data for sidebar
        $recentPosts = \App\Models\Post::where('status', 'published')
            ->latest()
            ->take(3)
            ->get()
            ->map(function ($post) {
                return [
                    'title' => $post->title,
                    'slug' => $post->slug,
                    'date' => $post->created_at->format('d M Y'),
                    'image' => $post->image ? asset('storage/' . $post->image) : asset('assets/img/logo/logo.png'), // Fallback image
                ];
            });

        // Kategori belum ada tabel/model-nya, jadi kosongkan dulu
        $categories = [];

        $sidebarData = [
            'recent_posts' => $recentPosts,
            'categories' => $categories
        ];

        // Get counter data for sarpras page
        $counter = [];
        if ($key === 'sarpras') {
            $counterSection = Section::where('key', 'counter')->first();
            $counter = $counterSection ? $counterSection->content : [];
        }

        return Inertia::render('Profil/Show', [
            'slug' => $slug,
            'title' => $title,
            'content' => $content,
            'gurus' => $gurus,
            'sidebarData' => $sidebarData,
            'counter' => $counter,
        ]);
    }

    private function getTitle($key)
    {
        $titles = [
            'visi_misi' => 'Visi & Misi',
            'sejarah' => 'Sejarah Sekolah',
            'sambutan' => 'Kepala Madrasah',
            'organisasi' => 'Struktur Organisasi',
            'sarpras' => 'Sarana & Prasarana',
            'tujuan' => 'Tujuan Pendidikan',
            'kurikulum' => 'Kurikulum & Mata Pelajaran',
            'direktori_guru' => 'Direktori Guru',
            'ppdb_syarat' => 'Syarat & Alur Pendaftaran',
            'ppdb_kuota' => 'Jadwal & Kuota PPDB',
        ];

        return $titles[$key] ?? 'Profil Sekolah';
    }
}

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

        if (!array_key_exists($slug, $keyMap)) {
            abort(404);
        }

        $key = $keyMap[$slug];
        $section = Section::where('key', $key)->first();

        // Defaults if data not found
        $content = $section ? $section->content : [];
        $title = $this->getTitle($key);

        // Get guru data for direktori guru page
        $gurus = [];
        if ($key === 'direktori_guru') {
            $gurus = \App\Models\Guru::orderBy('nama')->get();
        }

        return Inertia::render('Profil/Show', [
            'slug' => $slug,
            'title' => $title,
            'content' => $content,
            'gurus' => $gurus,
        ]);
    }

    private function getTitle($key)
    {
        $titles = [
            'visi_misi' => 'Visi & Misi',
            'sejarah' => 'Sejarah Sekolah',
            'sambutan' => 'Sambutan Kepala Sekolah',
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

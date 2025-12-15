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
     * @param  string  $slug  (visi-misi, sejarah, sambutan, organisasi)
     * @return \Inertia\Response
     */
    public function show($slug)
    {
        // Map slug to section key
        $keyMap = [
            'visi-misi' => 'visi_misi',
            'sejarah' => 'sejarah',
            'sambutan' => 'sambutan',
            'struktur-organisasi' => 'organisasi'
        ];

        if (!array_key_exists($slug, $keyMap)) {
            abort(404);
        }

        $key = $keyMap[$slug];
        $section = Section::where('key', $key)->first();

        // Defaults if data not found
        $content = $section ? $section->content : [];
        $title = $this->getTitle($key);

        return Inertia::render('Profil/Show', [
            'slug' => $slug,
            'title' => $title,
            'content' => $content
        ]);
    }

    private function getTitle($key)
    {
        $titles = [
            'visi_misi' => 'Visi & Misi',
            'sejarah' => 'Sejarah Sekolah',
            'sambutan' => 'Sambutan Kepala Sekolah',
            'organisasi' => 'Struktur Organisasi',
        ];

        return $titles[$key] ?? 'Profil Sekolah';
    }
}

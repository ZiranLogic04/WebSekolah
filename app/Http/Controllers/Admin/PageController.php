<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\Section;
use Illuminate\Support\Facades\Storage;

class PageController extends Controller
{
    // Define the schema for each section (HALAMAN PROFIL ONLY - Beranda is now static)
    private function getSectionsConfig()
    {
        return [
            // === HALAMAN PROFIL ===
            'visi_misi' => [
                'title' => 'Visi & Misi',
                'description' => 'Kelola Visi dan Misi Sekolah.',
                'icon' => 'fas fa-bullseye',
                'color' => 'bg-primary',
                'fields' => [
                    ['name' => 'visi', 'type' => 'textarea', 'label' => 'Visi Sekolah', 'group_title' => 'Visi'],
                    ['name' => 'misi', 'type' => 'richtext', 'label' => 'Misi Sekolah', 'group_title' => 'Misi'],
                ]
            ],
            'sejarah' => [
                'title' => 'Sejarah Sekolah',
                'description' => 'Kelola Sejarah singkat sekolah.',
                'icon' => 'fas fa-history',
                'color' => 'bg-warning',
                'fields' => [
                    ['name' => 'image', 'type' => 'image', 'label' => 'Foto Gedung / Sejarah', 'group_title' => 'Konten Sejarah'],
                    ['name' => 'content', 'type' => 'richtext', 'label' => 'Isi Sejarah'],
                ]
            ],
            'sambutan' => [
                'title' => 'Sambutan Kepala Sekolah',
                'description' => 'Kata sambutan dari Kepala Sekolah.',
                'icon' => 'fas fa-user-tie',
                'color' => 'bg-success',
                'fields' => [
                    ['name' => 'kepsek_name', 'type' => 'text', 'label' => 'Nama Kepala Sekolah', 'group_title' => 'Biodata'],
                    ['name' => 'kepsek_image', 'type' => 'image', 'label' => 'Foto Kepala Sekolah'],
                    ['name' => 'content', 'type' => 'richtext', 'label' => 'Isi Sambutan', 'group_title' => 'Konten Sambutan'],
                ]
            ],
            'organisasi' => [
                'title' => 'Struktur Organisasi',
                'description' => 'Gambar bagan struktur organisasi.',
                'icon' => 'fas fa-sitemap',
                'color' => 'bg-info',
                'fields' => [
                    ['name' => 'image', 'type' => 'image', 'label' => 'Gambar Struktur Organisasi', 'group_title' => 'Bagan Organisasi'],
                    ['name' => 'description', 'type' => 'textarea', 'label' => 'Keterangan Tambahan (Opsional)'],
                ]
            ],
            
            // === HALAMAN BARU ===
            'sarpras' => [
                'title' => 'Sarana & Prasarana',
                'description' => 'Kelola informasi fasilitas sekolah.',
                'icon' => 'fas fa-building',
                'color' => 'bg-secondary',
                'fields' => [
                    ['name' => 'jumlah_rombel', 'type' => 'text', 'label' => 'Jumlah Rombel', 'group_title' => 'Ringkasan Statistik'],
                    ['name' => 'jumlah_siswa', 'type' => 'text', 'label' => 'Jumlah Siswa Aktif'],
                    ['name' => 'jumlah_guru', 'type' => 'text', 'label' => 'Jumlah Guru & Tendik'],
                    ['name' => 'jumlah_alumni', 'type' => 'text', 'label' => 'Jumlah Alumni'],
                    ['name' => 'content', 'type' => 'richtext', 'label' => 'Deskripsi Fasilitas', 'group_title' => 'Konten Sarana'],
                ]
            ],
            'tujuan' => [
                'title' => 'Tujuan Pendidikan',
                'description' => 'Kelola tujuan pendidikan madrasah.',
                'icon' => 'fas fa-bullseye',
                'color' => 'bg-primary',
                'fields' => [
                    ['name' => 'content', 'type' => 'richtext', 'label' => 'Daftar Tujuan Madrasah', 'group_title' => 'Tujuan'],
                    ['name' => 'program_pendukung', 'type' => 'richtext', 'label' => 'Program yang Mendukung', 'group_title' => 'Program Pendukung'],
                ]
            ],
            'kurikulum' => [
                'title' => 'Kurikulum & Mata Pelajaran',
                'description' => 'Kelola informasi kurikulum dan mata pelajaran.',
                'icon' => 'fas fa-book-open',
                'color' => 'bg-success',
                'fields' => [
                    ['name' => 'ringkasan', 'type' => 'richtext', 'label' => 'Ringkasan Kurikulum', 'group_title' => 'Kurikulum'],
                    ['name' => 'mapel_keagamaan', 'type' => 'richtext', 'label' => 'Mata Pelajaran Keagamaan', 'group_title' => 'Mata Pelajaran'],
                    ['name' => 'mapel_umum', 'type' => 'richtext', 'label' => 'Mata Pelajaran Umum'],
                    ['name' => 'penilaian', 'type' => 'richtext', 'label' => 'Sistem Penilaian', 'group_title' => 'Penilaian & Program'],
                    ['name' => 'program_penguat', 'type' => 'richtext', 'label' => 'Program Penguat'],
                ]
            ],
            'ppdb_syarat' => [
                'title' => 'Syarat & Alur PPDB',
                'description' => 'Kelola informasi persyaratan dan alur pendaftaran.',
                'icon' => 'fas fa-user-plus',
                'color' => 'bg-warning',
                'fields' => [
                    ['name' => 'syarat_admin', 'type' => 'richtext', 'label' => 'Syarat Administratif', 'group_title' => 'Syarat Pendaftaran'],
                    ['name' => 'syarat_usia', 'type' => 'richtext', 'label' => 'Ketentuan Usia & Akademik'],
                    ['name' => 'alur', 'type' => 'richtext', 'label' => 'Alur Pendaftaran', 'group_title' => 'Alur PPDB'],
                    ['name' => 'kontak_panitia', 'type' => 'textarea', 'label' => 'Kontak Panitia', 'group_title' => 'Kontak'],
                    ['name' => 'wa_panitia', 'type' => 'text', 'label' => 'Nomor WhatsApp Panitia'],
                    ['name' => 'email_panitia', 'type' => 'text', 'label' => 'Email Panitia'],
                ]
            ],
            'ppdb_kuota' => [
                'title' => 'Jadwal & Kuota PPDB',
                'description' => 'Kelola jadwal dan kuota penerimaan siswa baru.',
                'icon' => 'fas fa-calendar-check',
                'color' => 'bg-info',
                'fields' => [
                    ['name' => 'tahun_ajar', 'type' => 'text', 'label' => 'Tahun Ajaran', 'group_title' => 'Info Umum'],
                    ['name' => 'jadwal_daftar', 'type' => 'text', 'label' => 'Jadwal Pendaftaran', 'group_title' => 'Jadwal PPDB'],
                    ['name' => 'jadwal_verifikasi', 'type' => 'text', 'label' => 'Jadwal Verifikasi'],
                    ['name' => 'jadwal_observasi', 'type' => 'text', 'label' => 'Jadwal Observasi'],
                    ['name' => 'jadwal_pengumuman', 'type' => 'text', 'label' => 'Jadwal Pengumuman'],
                    ['name' => 'jadwal_daftar_ulang', 'type' => 'text', 'label' => 'Jadwal Daftar Ulang'],
                    ['name' => 'kuota_total', 'type' => 'text', 'label' => 'Total Kuota', 'group_title' => 'Kuota'],
                    ['name' => 'kuota_terisi', 'type' => 'text', 'label' => 'Kuota Terisi'],
                    ['name' => 'biaya_daftar', 'type' => 'text', 'label' => 'Biaya Pendaftaran', 'group_title' => 'Biaya'],
                    ['name' => 'biaya_spp', 'type' => 'text', 'label' => 'SPP Bulanan'],
                    ['name' => 'biaya_paket', 'type' => 'text', 'label' => 'Paket Seragam/Perlengkapan'],
                ]
            ],
        ];
    }

    public function index()
    {
        $config = $this->getSectionsConfig();
        
        // Semua halaman profil yang tersedia (Beranda sekarang statis)
        $orderedKeys = [
            // Profil Madrasah
            'visi_misi',
            'sejarah',
            'sambutan',
            'organisasi',
            // Halaman Baru
            'sarpras',
            'tujuan',
            'kurikulum',
            'ppdb_syarat',
            'ppdb_kuota',
        ];
        
        $sections = [];
        foreach ($orderedKeys as $key) {
            if (isset($config[$key])) {
                $sections[] = array_merge(['id' => $key], $config[$key]);
            }
        }

        return Inertia::render('Admin/Pages/Index', [
            'sections' => $sections
        ]);
    }

    public function edit($key)
    {
        $config = $this->getSectionsConfig();

        if (!isset($config[$key])) {
            abort(404);
        }

        $section = Section::where('key', $key)->first();
        $sectionConfig = $config[$key];
        $fields = $sectionConfig['fields'] ?? [];

        // Format section data to match what Edit.vue expects
        $sectionData = [
            'key' => $key,
            'content' => $section ? $section->content : [],
        ];

        return Inertia::render('Admin/Pages/Edit', [
            'section' => $sectionData,
            'schema' => $fields,
            'title' => $sectionConfig['title'] ?? 'Edit Halaman',
        ]);
    }

    public function update(Request $request, $key)
    {
        $config = $this->getSectionsConfig();
        
        if (!isset($config[$key])) {
            abort(404);
        }

        $section = Section::firstOrNew(['key' => $key]);
        $currentContent = $section->content ?? [];
        $fields = $config[$key]['fields'] ?? [];

        foreach ($fields as $field) {
            $name = $field['name'];
            $type = $field['type'];

            if ($type === 'image' && $request->hasFile($name)) {
                // Delete old image if exists
                if (isset($currentContent[$name]) && Storage::disk('public')->exists($currentContent[$name])) {
                    Storage::disk('public')->delete($currentContent[$name]);
                }
                // Store new image
                $path = $request->file($name)->store('sections/' . $key, 'public');
                $currentContent[$name] = $path;
            } elseif ($type !== 'image') {
                // Handle text/textarea/richtext/icon_picker
                if ($request->has($name)) {
                    $currentContent[$name] = $request->input($name);
                }
            }
        }

        $section->content = $currentContent;
        $section->title = $config[$key]['title']; // Fix: Ensure title is saved
        $section->save();

        return redirect()->route('pages.index')->with('success', 'Konten berhasil diperbarui!');
    }
}

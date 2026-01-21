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
                'custom_editor' => 'visi-misi',
                'fields' => []
            ],
            'sejarah' => [
                'title' => 'Sejarah Sekolah',
                'description' => 'Kelola Sejarah singkat sekolah.',
                'icon' => 'fas fa-history',
                'color' => 'bg-warning',
                'custom_editor' => 'sejarah',
                'fields' => []
            ],
            'sambutan' => [
                'title' => 'Kepala Madrasah',
                'description' => 'Profil lengkap, biodata, dan sambutan Kepala Madrasah.',
                'icon' => 'fas fa-user-tie',
                'color' => 'bg-success',
                'custom_editor' => 'kepala',
                'fields' => []
            ],
            'organisasi' => [
                'title' => 'Struktur Organisasi',
                'description' => 'Bagan struktur organisasi dan wali kelas.',
                'icon' => 'fas fa-sitemap',
                'color' => 'bg-info',
                'custom_editor' => 'organisasi',
                'fields' => []
            ],
            
            // === HALAMAN BARU ===
            'sarpras' => [
                'title' => 'Sarana & Prasarana',
                'description' => 'Kelola informasi fasilitas sekolah.',
                'icon' => 'fas fa-building',
                'color' => 'bg-secondary',
                'custom_editor' => 'sarpras', // Flag untuk pakai editor khusus
                'fields' => [
                    // Data facilities dan inventaris akan disimpan sebagai JSON
                    // dan diedit via komponen khusus SarprasEdit.vue
                ]
            ],
            'tujuan' => [
                'title' => 'Tujuan Pendidikan',
                'description' => 'Kelola tujuan pendidikan madrasah.',
                'icon' => 'fas fa-bullseye',
                'color' => 'bg-primary',
                'custom_editor' => 'tujuan', // Flag untuk pakai editor khusus
                'fields' => [
                    // Data tujuan dan program_pendukung akan disimpan sebagai JSON
                ]
            ],
            'kurikulum' => [
                'title' => 'Kurikulum & Mata Pelajaran',
                'description' => 'Kelola informasi kurikulum dan mata pelajaran.',
                'icon' => 'fas fa-book-open',
                'color' => 'bg-success',
                'custom_editor' => 'kurikulum',
                'fields' => []
            ],
            'ppdb_syarat' => [
                'title' => 'Syarat & Alur PPDB',
                'description' => 'Kelola informasi persyaratan, alur pendaftaran, dan kontak.',
                'icon' => 'fas fa-user-plus',
                'color' => 'bg-warning',
                'custom_editor' => 'ppdb-syarat',
                'fields' => []
            ],
            'ppdb_kuota' => [
                'title' => 'Jadwal & Kuota PPDB',
                'description' => 'Kelola jadwal, kuota, dan biaya pendidikan.',
                'icon' => 'fas fa-calendar-check',
                'color' => 'bg-info',
                'custom_editor' => 'ppdb-kuota',
                'fields' => []
            ],
        ];
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

        // Jika ada custom editor, gunakan komponen khusus
        if (isset($sectionConfig['custom_editor'])) {
            // Mapping custom_editor ke nama komponen Vue
            $editorMap = [
                'visi-misi' => 'Visi-misiEdit',
                'sejarah' => 'SejarahEdit',
                'kepala' => 'KepalaEdit',
                'organisasi' => 'OrganisasiEdit',
                'kurikulum' => 'KurikulumEdit',
                'sarpras' => 'SarprasEdit',
                'tujuan' => 'TujuanEdit',
                'ppdb-syarat' => 'PpdbSyaratEdit',
                'ppdb-kuota' => 'PpdbKuotaEdit',
            ];
            
            $editorKey = $sectionConfig['custom_editor'];
            $customEditor = $editorMap[$editorKey] ?? ucfirst($editorKey) . 'Edit';
            
            // Determine Back URL & Menu Context
            $menuItem = \App\Models\MenuItem::where('slug', $key)->first();
            $backUrl = route('halaman.index'); 
            $menuNavbar = null;
            
            if ($menuItem && $menuItem->menu_navbar_id) {
                $backUrl = route('menu-item.mega-menu', $menuItem->menu_navbar_id);
                $menuNavbar = \App\Models\MenuNavbar::find($menuItem->menu_navbar_id);
            } else {
                // Fallback: Try to map section keys to known Mega Menus
                $manualMap = [
                    'visi-misi' => 'profil',
                    'sejarah' => 'profil',
                    'struktur-organisasi' => 'profil',
                    'sarana-prasarana' => 'profil',
                    'tujuan' => 'profil',
                    'kepala-madrasah' => 'profil',
                    'prestasi' => 'profil',
                    'kemitraan' => 'profil',
                    
                    'kurikulum-mapel' => 'profil', 
                    'kalender-akademik' => 'profil', 
                ];
                
                // Better: Explicit Map
                $sectionToNavbarSlug = [
                     // Profil
                    'visi-misi' => 'akademik',
                    'sejarah' => 'akademik',
                    'struktur-organisasi' => 'akademik',
                    'sarana-prasarana' => 'akademik',
                    'tujuan' => 'akademik',
                    'kepala-madrasah' => 'akademik',
                    'prestasi' => 'akademik',
                    'kemitraan' => 'akademik',
                    // Akademik
                    'kurikulum-mapel' => 'akademik',
                ];

                $targetSlug = $sectionToNavbarSlug[$key] ?? 'akademik'; // Default to akademik if unknown
                $menuNavbar = \App\Models\MenuNavbar::where('slug', $targetSlug)->first();
                
                if ($menuNavbar) {
                    $backUrl = route('menu-item.mega-menu', $menuNavbar->id);
                }
            }
            
            return Inertia::render('Admin/Pages/' . $customEditor, [
                'section' => $sectionData,
                'title' => $sectionConfig['title'] ?? 'Edit Halaman',
                'backUrl' => $backUrl,
                'menuName' => $menuNavbar ? $menuNavbar->nama : 'Akademik',
                'menuId' => $menuNavbar ? $menuNavbar->id : null,
                'flash' => [
                    'success' => session('success'),
                    'error' => session('error'),
                ]
            ]);
        }

        return Inertia::render('Admin/Pages/Edit', [
            'section' => $sectionData,
            'schema' => $fields,
            'title' => $sectionConfig['title'] ?? 'Edit Halaman',
            'backUrl' => $backUrl,
            'flash' => [
                'success' => session('success'),
                'error' => session('error'),
            ]
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
        $sectionConfig = $config[$key];
        $fields = $sectionConfig['fields'] ?? [];

        // Handle custom editor (JSON data)
        if (isset($sectionConfig['custom_editor'])) {
            // Ambil semua data dari request dan simpan ke content
            $allowedFields = [
                // Sarpras
                'facilities', 'inventaris', 'content',
                // Tujuan
                'tujuan', 'program',
                // Visi Misi
                'visi', 'tagline', 'misi',
                // Sejarah
                'narasi', 'timeline', 'tahun_berdiri',
                'nsm', 'akreditasi', 'kepala_nama', 'alamat', 'jml_siswa', 'jml_guru', 'jml_rombel', // Manual Stats
                // Kepala
                'foto', 'nip', 'periode', 'email', 'telepon', 'quote', 'sambutan', 'riwayat',
                // Organisasi
                'pengawas', 'kepala', 'pimpinan', 'koordinator', 'administrasi', 'komite', 'wali_kelas', 'catatan',
                'mode_tampilan', 'org_chart_image', // Toggle & Image
                'pengawas_label', 'kepala_label', // Labels
                'pimpinan_title', 'koordinator_title', 'administrasi_title', 'komite_title', // Editable Titles
                // Kurikulum
                'ringkasan', 'highlight', 'mapel_keagamaan', 'mapel_umum', 'alokasi', 'penilaian', 'program_penguat', 'ekskul',
                // PPDB Syarat
                'syarat_admin', 'syarat_usia', 'alur_steps', 'kontak_info', 'kontak_wa', 'kontak_email', 'link_daftar',
                // PPDB Kuota
                'jadwal', 'kuota_total', 'kuota_terisi', 'biaya_daftar', 'biaya_spp', 'biaya_paket',
            ];
            
            foreach ($allowedFields as $field) {
                if ($request->has($field)) {
                    $value = $request->input($field);
                    
                    // Decode JSON string fields (from FormData)
                    if (is_string($value) && in_array($field, ['riwayat', 'timeline', 'wali_kelas', 'pimpinan', 'koordinator', 'misi', 'tujuan', 'program', 'highlight', 'mapel_keagamaan', 'mapel_umum', 'alokasi', 'penilaian', 'program_penguat', 'ekskul', 'facilities', 'inventaris', 'alur_steps', 'jadwal'])) {
                        $decoded = json_decode($value, true);
                        if (json_last_error() === JSON_ERROR_NONE) {
                            $value = $decoded;
                        }
                    }
                    
                    $currentContent[$field] = $value;
                }
            }
            
            // Handle file upload untuk foto kepala
            if ($request->hasFile('foto')) {
                if (isset($currentContent['foto']) && Storage::disk('public')->exists($currentContent['foto'])) {
                    Storage::disk('public')->delete($currentContent['foto']);
                }
                $path = $request->file('foto')->store('sections/kepala', 'public');
                $currentContent['foto'] = $path;
            }

            // Handle file upload untuk Bagan Struktur Organisasi
            if ($request->hasFile('org_chart_image')) {
                if (isset($currentContent['org_chart_image']) && Storage::disk('public')->exists($currentContent['org_chart_image'])) {
                    Storage::disk('public')->delete($currentContent['org_chart_image']);
                }
                $path = $request->file('org_chart_image')->store('sections/organisasi', 'public');
                $currentContent['org_chart_image'] = $path;
            }
        } else {
            // Handle regular fields
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
        }

        $section->content = $currentContent;
        $section->title = $sectionConfig['title']; // Fix: Ensure title is saved
        $section->save();

        // Redirect back to the same edit page
        return redirect()->back()->with('success', 'Konten berhasil diperbarui!');
    }
}

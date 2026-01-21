<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\MenuNavbar;
use App\Models\MenuItem;

class MenuItemSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Pastikan Menu Navbar 'Akademik' ada (ini adalah Master Mega Menu)
        $menuAkademik = MenuNavbar::firstOrCreate(
            ['slug' => 'akademik'],
            ['nama' => 'Akademik', 'is_active' => true, 'urutan' => 2]
        );

        $defaultGroups = [
            ['nama' => 'Tentang Kami', 'slug' => 'tentang-kami', 'urutan' => 1],
            ['nama' => 'Profil Madrasah', 'slug' => 'profil', 'urutan' => 2],
            ['nama' => 'Akademik & Program', 'slug' => 'akademik-program', 'urutan' => 3],
            ['nama' => 'Layanan & Sumber', 'slug' => 'layanan', 'urutan' => 4],
        ];

        // Static items untuk setiap grup
        $staticItems = [
            'profil' => [
                ['nama' => 'Visi & Misi', 'slug' => 'visi-misi', 'urutan' => 1],
                ['nama' => 'Sejarah Singkat', 'slug' => 'sejarah', 'urutan' => 2],
                ['nama' => 'Struktur Organisasi', 'slug' => 'struktur-organisasi', 'urutan' => 3],
                ['nama' => 'Sarana & Prasarana', 'slug' => 'sarana-prasarana', 'urutan' => 5],
                ['nama' => 'Tujuan', 'slug' => 'tujuan', 'urutan' => 6],
                ['nama' => 'Kepala Madrasah', 'slug' => 'kepala-madrasah', 'urutan' => 7],
                ['nama' => 'Prestasi', 'slug' => 'prestasi', 'urutan' => 8],
                ['nama' => 'Kemitraan', 'slug' => 'kemitraan', 'urutan' => 9],
            ],
            'akademik-program' => [
                ['nama' => 'Kurikulum & Mapel', 'slug' => 'kurikulum-mapel', 'urutan' => 1],
                ['nama' => 'Kalender Akademik', 'slug' => 'kalender-akademik', 'urutan' => 2],
                ['nama' => 'Program Tahsin/Tahfiz & BTQ', 'slug' => 'tahsin-tahfiz', 'urutan' => 3],
                ['nama' => 'Literasi & Klinik Numerasi', 'slug' => 'literasi-numerasi', 'urutan' => 4],
                ['nama' => 'Ekstrakurikuler', 'slug' => 'ekstrakurikuler', 'urutan' => 5],
                ['nama' => 'Tata Tertib Akademik', 'slug' => 'tata-tertib', 'urutan' => 6],
            ],
            'layanan' => [
                ['nama' => 'Berita & Pengumuman', 'slug' => 'berita-pengumuman', 'urutan' => 1],
                ['nama' => 'Gallery Foto/Video', 'slug' => 'gallery', 'urutan' => 2],
                ['nama' => 'Unduhan', 'slug' => 'unduhan', 'urutan' => 3],
                ['nama' => 'PPDB Online', 'slug' => 'ppdb-online-app', 'urutan' => 4],
                ['nama' => 'E-Books', 'slug' => 'e-books', 'urutan' => 5],
                ['nama' => 'Kontak & Lokasi', 'slug' => 'kontak-lokasi', 'urutan' => 6],
            ],
        ];

        foreach ($defaultGroups as $group) {
            $groupModel = MenuItem::firstOrCreate(
                [
                    'menu_navbar_id' => $menuAkademik->id,
                    'slug' => $group['slug'],
                    'parent_id' => null
                ],
                [
                    'nama' => $group['nama'],
                    'type' => 'group',
                    'is_active' => true,
                    'urutan' => $group['urutan']
                ]
            );

            // Seed static items untuk grup ini
            if (isset($staticItems[$group['slug']])) {
                foreach ($staticItems[$group['slug']] as $item) {
                    MenuItem::firstOrCreate(
                        [
                            'menu_navbar_id' => $menuAkademik->id,
                            'parent_id' => $groupModel->id,
                            'slug' => $item['slug']
                        ],
                        [
                            'nama' => $item['nama'],
                            'type' => 'page',
                            'is_active' => true,
                            'urutan' => $item['urutan']
                        ]
                    );
                }
            }
        }
    }
}

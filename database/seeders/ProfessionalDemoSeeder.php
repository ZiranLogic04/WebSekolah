<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Slider;
use App\Models\Section;
use App\Models\Feature;

class ProfessionalDemoSeeder extends Seeder
{
    /**
     * Seed demo data untuk website sekolah.
     * Menggunakan gambar dari public/assets/img/hope yang IKUT ke GitHub.
     */
    public function run(): void
    {
        // ========================================
        // SLIDERS (Menggunakan gambar dari assets)
        // ========================================
        Slider::query()->delete();
        
        $sliders = [
            [
                'title' => 'Selamat Datang di <span class="text-warning">Sekolah Kami</span>',
                'subtitle' => 'Membangun Generasi Unggul, Berakhlak Mulia, dan Berwawasan Global',
                'button_text' => 'Daftar Sekarang',
                'button_url' => '/ppdb',
                'image' => '/assets/img/hope/122.jpg', // Gambar dari public/assets (IKUT KE GITHUB)
                'order' => 1,
                'is_active' => true,
            ],
            [
                'title' => 'Fasilitas Pembelajaran <span class="text-warning">Modern</span>',
                'subtitle' => 'Kelas Digital, Laboratorium Lengkap, dan Perpustakaan',
                'button_text' => 'Lihat Fasilitas',
                'button_url' => '/profil/sarpras',
                'image' => '/assets/img/hope/123.jpg',
                'order' => 2,
                'is_active' => true,
            ],
            [
                'title' => 'Prestasi <span class="text-warning">Gemilang</span>',
                'subtitle' => 'Mencetak Lulusan Terbaik yang Berprestasi',
                'button_text' => 'Lihat Prestasi',
                'button_url' => '/akademik/prestasi',
                'image' => '/assets/img/hope/DUD.jpg',
                'order' => 3,
                'is_active' => true,
            ],
        ];

        foreach ($sliders as $slider) {
            Slider::create($slider);
        }

        // ========================================
        // FEATURES (4 Fitur Utama dengan icon .svg)
        // ========================================
        Feature::query()->delete();
        
        $features = [
            [
                'icon' => 'book-open.svg',
                'title' => 'Kurikulum Merdeka',
                'description' => 'Pembelajaran berbasis proyek dan karakter islami',
                'is_active' => true,
            ],
            [
                'icon' => 'user-check.svg',
                'title' => 'Guru Profesional',
                'description' => '90% tenaga pengajar bersertifikasi & berpengalaman',
                'is_active' => true,
            ],
            [
                'icon' => 'layers.svg',
                'title' => 'Fasilitas Lengkap',
                'description' => 'Laboratorium, perpustakaan digital, dan masjid',
                'is_active' => true,
            ],
            [
                'icon' => 'award.svg',
                'title' => 'Prestasi Unggul',
                'description' => 'Juara olimpiade tingkat nasional & internasional',
                'is_active' => true,
            ],
        ];

        foreach ($features as $feature) {
            Feature::create($feature);
        }

        // ========================================
        // SECTIONS (Semua section dengan gambar assets)
        // ========================================
        Section::query()->delete();
        
        // ABOUT SECTION
        Section::create([
            'key' => 'about',
            'title' => 'Tentang Kami',
            'content' => [
                'tagline' => 'TENTANG KAMI',
                'title' => 'Mencetak Generasi Islami yang <span>Cerdas</span> dan Berkarakter',
                'description' => 'Kami hadir sebagai lembaga pendidikan Islam modern yang memadukan keunggulan akademik dengan nilai-nilai keislaman yang kuat.',
                'image_1' => '/assets/img/hope/122.jpg',
                'image_2' => '/assets/img/hope/123.jpg',
                'image_3' => '/assets/img/hope/DUD.jpg',
                'item_1_icon' => 'open-book.svg',
                'item_1_title' => 'Kurikulum Integratif',
                'item_1_desc' => 'Memadukan kurikulum nasional dengan pendidikan agama Islam.',
                'item_2_icon' => 'global-education.svg',
                'item_2_title' => 'Akreditasi Unggul',
                'item_2_desc' => 'Terakreditasi A (Unggul) dengan standar manajemen mutu.',
                'quote' => 'Pendidikan adalah tiket ke masa depan.',
                'button_text' => 'Selengkapnya',
                'button_url' => '/profil',
            ],
        ]);

        // COUNTER SECTION
        Section::create([
            'key' => 'counter',
            'title' => 'Statistik',
            'content' => [
                'siswa_aktif' => '850',
                'guru' => '45',
                'rombel' => '24',
                'alumni' => '2500',
            ],
        ]);

        // PROGRAM SECTION
        Section::create([
            'key' => 'program',
            'title' => 'Program Unggulan',
            'content' => [
                'tagline' => 'PROGRAM UNGGULAN',
                'title' => 'Program & <span>Layanan</span> Akademik',
                'description' => 'Berbagai program unggulan untuk mengembangkan potensi siswa secara optimal',
                'items' => [
                    ['icon' => 'book.svg', 'title' => 'Tahfidz Al-Quran', 'desc' => 'Program intensif menghafal Al-Quran bersanad.', 'url' => '#'],
                    ['icon' => 'global-education.svg', 'title' => 'Bilingual Class', 'desc' => 'Pembelajaran Bahasa Inggris dan Arab.', 'url' => '#'],
                    ['icon' => 'monitor.svg', 'title' => 'Coding & Robotik', 'desc' => 'Ekstrakurikuler unggulan skill masa depan.', 'url' => '#'],
                    ['icon' => 'health.svg', 'title' => 'UKS Modern', 'desc' => 'Layanan kesehatan siswa proaktif.', 'url' => '#'],
                    ['icon' => 'art.svg', 'title' => 'Seni & Budaya', 'desc' => 'Ekstrakurikuler seni tari, lukis, musik.', 'url' => '#'],
                    ['icon' => 'teacher.svg', 'title' => 'Bimbingan Belajar', 'desc' => 'Les tambahan untuk persiapan ujian.', 'url' => '#'],
                ],
            ],
        ]);

        // VIDEO SECTION
        Section::create([
            'key' => 'video',
            'title' => 'Video Profil',
            'content' => [
                'tagline' => 'VIDEO PROFIL',
                'title' => 'Mengenal Lebih Dekat <span>Sekolah Kami</span>',
                'description' => 'Saksikan profil lengkap, fasilitas, dan suasana pembelajaran.',
                'button_text' => 'Profil Lengkap',
                'button_url' => '/profil',
                'youtube_url' => 'https://www.youtube.com/watch?v=dQw4w9WgXcQ',
                'poster_image' => '/assets/img/hope/DUD.jpg',
            ],
        ]);

        // TEAM SECTION
        Section::create([
            'key' => 'team',
            'title' => 'Tim Pengajar',
            'content' => [
                'tagline' => 'TIM PENGAJAR',
                'title' => 'Tenaga Pendidik <span>Profesional</span>',
                'description' => 'Dikelola oleh tenaga pendidik berpengalaman',
                'items' => [
                    ['nama' => 'Dr. H. Ahmad, M.Pd.', 'jabatan' => 'Kepala Sekolah', 'foto' => '/assets/img/default-m.svg', 'facebook' => '#', 'instagram' => '#', 'twitter' => '#'],
                    ['nama' => 'Hj. Siti Aminah, M.Pd.', 'jabatan' => 'Waka Kurikulum', 'foto' => '/assets/img/default-f.svg', 'facebook' => '#', 'instagram' => '#', 'twitter' => '#'],
                    ['nama' => 'Ust. Ridwan, Lc., M.A.', 'jabatan' => 'Waka Kesiswaan', 'foto' => '/assets/img/default-m.svg', 'facebook' => '#', 'instagram' => '#', 'twitter' => '#'],
                    ['nama' => 'Fatmawati, S.Pd.', 'jabatan' => 'Waka Humas', 'foto' => '/assets/img/default-f.svg', 'facebook' => '#', 'instagram' => '#', 'twitter' => '#'],
                ],
            ],
        ]);

        // CHOOSE SECTION
        Section::create([
            'key' => 'choose',
            'title' => 'Mengapa Memilih Kami',
            'content' => [
                'tagline' => 'KEUNGGULAN',
                'title' => 'Mengapa Memilih <span>Sekolah Kami?</span>',
                'description' => 'Alasan tepat untuk mempercayakan pendidikan putra-putri Anda',
                'image' => '/assets/img/hope/122.jpg',
                'items' => [
                    ['title' => 'Lingkungan Aman', 'desc' => 'Sekolah ramah anak dengan keamanan terjamin.', 'icon' => 'health.svg'],
                    ['title' => 'Biaya Terjangkau', 'desc' => 'Pendidikan berkualitas dengan biaya transparan.', 'icon' => 'money.svg'],
                    ['title' => 'Ekskul Beragam', 'desc' => 'Pilihan ekskul lengkap untuk semua bakat.', 'icon' => 'art.svg'],
                    ['title' => 'Lokasi Strategis', 'desc' => 'Mudah diakses dari berbagai penjuru.', 'icon' => 'building-2.svg'],
                ],
            ],
        ]);

        // PORTFOLIO SECTION
        Section::create([
            'key' => 'portfolio',
            'title' => 'Galeri',
            'content' => [
                'tagline' => 'GALERI',
                'title' => 'Dokumentasi <span>Kegiatan</span>',
                'items' => [
                    ['category' => 'Akademik', 'title' => 'Olimpiade Sains', 'url' => '#', 'image' => '/assets/img/hope/122.jpg'],
                    ['category' => 'Ekskul', 'title' => 'Latihan Pramuka', 'url' => '#', 'image' => '/assets/img/hope/123.jpg'],
                    ['category' => 'Religi', 'title' => 'Manasik Haji', 'url' => '#', 'image' => '/assets/img/hope/DUD.jpg'],
                    ['category' => 'Seni', 'title' => 'Pentas Seni', 'url' => '#', 'image' => '/assets/img/hope/DUDU_1.jpg'],
                    ['category' => 'Lingkungan', 'title' => 'Go Green', 'url' => '#', 'image' => '/assets/img/hope/43.jpg'],
                    ['category' => 'Kunjungan', 'title' => 'Study Tour', 'url' => '#', 'image' => '/assets/img/hope/15.jpg'],
                ],
            ],
        ]);

        // EVENT/AGENDA SECTION
        Section::create([
            'key' => 'event',
            'title' => 'Agenda',
            'content' => [
                'tagline' => 'AGENDA',
                'title' => 'Kegiatan <span>Terbaru</span>',
                'items' => [
                    ['location' => 'Aula Sekolah', 'date' => '15 Feb 2025', 'time' => '08.00 - 12.00', 'title' => 'Sosialisasi PPDB', 'description' => 'Pemaparan info pendaftaran siswa baru.', 'link' => '/ppdb', 'image' => '/assets/img/event/01.jpg'],
                    ['location' => 'Lapangan', 'date' => '17 Feb 2025', 'time' => '07.00 - 10.00', 'title' => 'Upacara HUT RI', 'description' => 'Peringatan hari kemerdekaan.', 'link' => '#', 'image' => '/assets/img/event/02.jpg'],
                    ['location' => 'Masjid', 'date' => '20 Feb 2025', 'time' => '08.00 - 11.00', 'title' => 'Wisuda Tahfidz', 'description' => 'Pelulusan penghafal Al-Quran.', 'link' => '#', 'image' => '/assets/img/event/03.jpg'],
                ],
            ],
        ]);

        // FOOTER LINKS SECTION
        Section::create([
            'key' => 'footer_links',
            'title' => 'Link Footer',
            'content' => [
                'col_1' => [
                    'title' => 'Tentang Kami',
                    'links' => [
                        ['label' => 'Profil', 'url' => '/profil'],
                        ['label' => 'Visi Misi', 'url' => '/visi-misi'],
                        ['label' => 'Sejarah', 'url' => '/sejarah'],
                    ]
                ],
                'col_2' => [
                    'title' => 'Akademik',
                    'links' => [
                        ['label' => 'Kurikulum', 'url' => '/akademik/kurikulum'],
                        ['label' => 'Kalender', 'url' => '/akademik/kalender'],
                        ['label' => 'Prestasi', 'url' => '/prestasi'],
                    ]
                ],
                'col_3' => [
                    'title' => 'Informasi',
                    'links' => [
                        ['label' => 'PPDB', 'url' => '/ppdb'],
                        ['label' => 'Berita', 'url' => '/berita'],
                        ['label' => 'Galeri', 'url' => '/galeri'],
                    ]
                ]
            ],
        ]);
    }
}

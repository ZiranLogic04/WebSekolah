<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\Section;
use Illuminate\Support\Facades\Storage;

class PageController extends Controller
{
    private function getIconOptions() {
        return [
            'fas fa-book' => 'Buku / Akademik',
            'fas fa-book-open' => 'Buku Terbuka',
            'fas fa-user-graduate' => 'Siswa / Lulusan',
            'fas fa-chalkboard-teacher' => 'Guru / Pengajar',
            'fas fa-school' => 'Sekolah',
            'fas fa-graduation-cap' => 'Kelulusan',
            'fas fa-trophy' => 'Piala / Prestasi',
            'fas fa-medal' => 'Medali',
            'fas fa-star' => 'Unggulan / Bintang',
            'fas fa-mosque' => 'Agama / Masjid',
            'fas fa-quran' => 'Al-Quran',
            'fas fa-flask' => 'Sains',
            'fas fa-calculator' => 'Matematika',
            'fas fa-globe' => 'Geografi',
            'fas fa-laptop' => 'Komputer / IT',
            'fas fa-desktop' => 'Lab Komputer',
            'fas fa-palette' => 'Seni',
            'fas fa-music' => 'Musik',
            'fas fa-futbol' => 'Olahraga',
            'fas fa-bus' => 'Transportasi',
            'fas fa-clock' => 'Waktu',
            'fas fa-clipboard-list' => 'Ujian',
            'fas fa-id-card' => 'Identitas',
            'fas fa-building' => 'Fasilitas',
            'fas fa-hands-helping' => 'Sosial / Peduli',
            'fas fa-heart' => 'Karakter / Hati',
            'fas fa-health' => 'Kesehatan / UKS',
            'fas fa-comments' => 'Diskusi / Bahasa',
            'fas fa-video' => 'Video',
            'fas fa-image' => 'Gambar',
            'fas fa-users' => 'Kelompok / Orang Banyak',
            'fas fa-child' => 'Anak',
        ];
    }

    // Define the schema for each section efficiently
    private function getSectionsConfig()
    {
        return [
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
                    ['name' => 'title', 'type' => 'text', 'label' => 'Judul Halaman', 'group_title' => 'Konten Sejarah'],
                    ['name' => 'image', 'type' => 'image', 'label' => 'Foto Gedung / Sejarah'],
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
            'hero' => [
                'title' => 'Hero Slider',
                'description' => 'Kelola 3 gambar slider utama.',
                'icon' => 'fas fa-images',
                'color' => 'bg-primary',
                'fields' => [
                    // Slide 1
                    [
                        'name' => 'slide_1_top_title', 
                        'type' => 'text', 
                        'label' => 'Judul Kecil (Atas)', 
                        'group_title' => 'Slide Utama #1',
                        'group_description' => 'Slide pertama yang muncul saat web dibuka. Gunakan gambar landscape kualitas tinggi.',
                        'group_image' => '/assets/admin_guide/hero_guide.png'
                    ],
                    ['name' => 'slide_1_image', 'type' => 'image', 'label' => 'Gambar Background Slide 1'],
                    ['name' => 'slide_1_title', 'type' => 'text', 'label' => 'Judul Utama Slide 1'],
                    ['name' => 'slide_1_subtitle', 'type' => 'textarea', 'label' => 'Deskripsi / Sub Judul Slide 1'],
                    ['name' => 'slide_1_button_text', 'type' => 'text', 'label' => 'Teks Tombol (Opsional)'],
                    ['name' => 'slide_1_button_link', 'type' => 'text', 'label' => 'Link Tombol (Opsional)'],
                    
                    
                    // Slide 2
                    [
                        'name' => 'slide_2_top_title', 
                        'type' => 'text', 
                        'label' => 'Judul Kecil (Atas)', 
                        'group_title' => 'Slide Utama #2',
                        'group_description' => 'Slide kedua. Pastikan kontras teks terjaga agar mudah dibaca.',
                        'group_image' => '/assets/admin_guide/hero_guide.png'
                    ],
                    ['name' => 'slide_2_image', 'type' => 'image', 'label' => 'Gambar Background Slide 2'],
                    ['name' => 'slide_2_title', 'type' => 'text', 'label' => 'Judul Utama Slide 2'],
                    ['name' => 'slide_2_subtitle', 'type' => 'textarea', 'label' => 'Deskripsi / Sub Judul Slide 2'],
                    ['name' => 'slide_2_button_text', 'type' => 'text', 'label' => 'Teks Tombol (Opsional)'],
                    ['name' => 'slide_2_button_link', 'type' => 'text', 'label' => 'Link Tombol (Opsional)'],
                    
                    
                    // Slide 3
                    [
                        'name' => 'slide_3_top_title', 
                        'type' => 'text', 
                        'label' => 'Judul Kecil (Atas)', 
                        'group_title' => 'Slide Utama #3',
                        'group_description' => 'Slide ketiga sebagai pelengkap informasi utama.',
                        'group_image' => '/assets/admin_guide/hero_guide.png'
                    ],
                    ['name' => 'slide_3_image', 'type' => 'image', 'label' => 'Gambar Background Slide 3'],
                    ['name' => 'slide_3_title', 'type' => 'text', 'label' => 'Judul Utama Slide 3'],
                    ['name' => 'slide_3_subtitle', 'type' => 'textarea', 'label' => 'Deskripsi / Sub Judul Slide 3'],
                    ['name' => 'slide_3_button_text', 'type' => 'text', 'label' => 'Teks Tombol (Opsional)'],
                    ['name' => 'slide_3_button_link', 'type' => 'text', 'label' => 'Link Tombol (Opsional)'],
                ]
            ],
            'feature' => [
                'title' => 'Keunggulan Sekolah',
                'description' => 'Kelola 4 poin keunggulan utama (tepat di bawah slider).',
                'icon' => 'fas fa-star',
                'color' => 'bg-warning',
                'fields' => [
                    // Item 1
                    [
                        'name' => 'feature_1_title', 
                        'type' => 'text', 
                        'label' => 'Judul Fitur 1', 
                        'group_title' => 'Fitur #1 (Pendidikan Bermutu)'
                    ],
                    ['name' => 'feature_1_desc', 'type' => 'textarea', 'label' => 'Deskripsi Fitur 1'],
                    [
                        'name' => 'feature_1_icon', 
                        'type' => 'icon_picker', 
                        'label' => 'Pilih Icon Fitur 1',
                        'options' => [
                            'fas fa-book' => 'Buku',
                            'fas fa-user-graduate' => 'Siswa',
                            'fas fa-chalkboard-teacher' => 'Guru',
                            'fas fa-school' => 'Sekolah',
                            'fas fa-graduation-cap' => 'Kelulusan',
                            'fas fa-trophy' => 'Piala',
                            'fas fa-medal' => 'Medali',
                            'fas fa-star' => 'Unggulan',
                            'fas fa-mosque' => 'Masjid',
                            'fas fa-quran' => 'Al-Quran',
                            'fas fa-flask' => 'Sains',
                            'fas fa-calculator' => 'Matematika',
                            'fas fa-globe' => 'Geografi',
                            'fas fa-laptop' => 'Komputer',
                            'fas fa-desktop' => 'Lab Komputer',
                            'fas fa-palette' => 'Seni',
                            'fas fa-music' => 'Musik',
                            'fas fa-futbol' => 'Olahraga',
                            'fas fa-bus' => 'Transportasi',
                            'fas fa-clock' => 'Waktu',
                            'fas fa-clipboard-list' => 'Ujian',
                            'fas fa-id-card' => 'Identitas',
                            'fas fa-building' => 'Fasilitas',
                            'fas fa-hands-helping' => 'Sosial',
                        ]
                    ],

                    // Item 2
                    [
                        'name' => 'feature_2_title', 
                        'type' => 'text', 
                        'label' => 'Judul Fitur 2', 
                        'group_title' => 'Fitur #2 (Guru)'
                    ],
                    ['name' => 'feature_2_desc', 'type' => 'textarea', 'label' => 'Deskripsi Fitur 2'],
                    [
                        'name' => 'feature_2_icon', 
                        'type' => 'icon_picker', 
                        'label' => 'Pilih Icon Fitur 2',
                        'options' => [
                            'fas fa-book' => 'Buku',
                            'fas fa-user-graduate' => 'Siswa',
                            'fas fa-chalkboard-teacher' => 'Guru',
                            'fas fa-school' => 'Sekolah',
                            'fas fa-graduation-cap' => 'Kelulusan',
                            'fas fa-trophy' => 'Piala',
                            'fas fa-medal' => 'Medali',
                            'fas fa-star' => 'Unggulan',
                            'fas fa-mosque' => 'Masjid',
                            'fas fa-quran' => 'Al-Quran',
                            'fas fa-flask' => 'Sains',
                            'fas fa-calculator' => 'Matematika',
                            'fas fa-globe' => 'Geografi',
                            'fas fa-laptop' => 'Komputer',
                            'fas fa-desktop' => 'Lab Komputer',
                            'fas fa-palette' => 'Seni',
                            'fas fa-music' => 'Musik',
                            'fas fa-futbol' => 'Olahraga',
                            'fas fa-bus' => 'Transportasi',
                            'fas fa-clock' => 'Waktu',
                            'fas fa-clipboard-list' => 'Ujian',
                            'fas fa-id-card' => 'Identitas',
                            'fas fa-building' => 'Fasilitas',
                            'fas fa-hands-helping' => 'Sosial',
                        ]
                    ],

                    // Item 3
                    [
                        'name' => 'feature_3_title', 
                        'type' => 'text', 
                        'label' => 'Judul Fitur 3', 
                        'group_title' => 'Fitur #3 (Kurikulum)'
                    ],
                    ['name' => 'feature_3_desc', 'type' => 'textarea', 'label' => 'Deskripsi Fitur 3'],
                    [
                        'name' => 'feature_3_icon', 
                        'type' => 'icon_picker', 
                        'label' => 'Pilih Icon Fitur 3',
                        'options' => [
                           'fas fa-book' => 'Buku / Akademik',
                            'fas fa-user-graduate' => 'Siswa / Lulusan',
                            'fas fa-chalkboard-teacher' => 'Guru / Pengajar',
                            'fas fa-mosque' => 'Agama / Masjid',
                            'fas fa-star' => 'Keunggulan / Bintang',
                            'fas fa-trophy' => 'Piala / Prestasi',
                            'fas fa-bus' => 'Transportasi',
                            'fas fa-building' => 'Gedung / Fasilitas',
                            'fas fa-laptop' => 'Komputer / IT',
                            'fas fa-globe' => 'Global / Bahasa',
                            'fas fa-heart' => 'Karakter / Hati',
                            'fas fa-hands-helping' => 'Sosial / Peduli',
                        ]
                    ],

                    // Item 4
                    [
                        'name' => 'feature_4_title', 
                        'type' => 'text', 
                        'label' => 'Judul Fitur 4', 
                        'group_title' => 'Fitur #4 (Fasilitas)'
                    ],
                    ['name' => 'feature_4_desc', 'type' => 'textarea', 'label' => 'Deskripsi Fitur 4'],
                    [
                        'name' => 'feature_4_icon', 
                        'type' => 'icon_picker', 
                        'label' => 'Pilih Icon Fitur 4',
                        'options' => [
                           'fas fa-book' => 'Buku / Akademik',
                            'fas fa-user-graduate' => 'Siswa / Lulusan',
                            'fas fa-chalkboard-teacher' => 'Guru / Pengajar',
                            'fas fa-mosque' => 'Agama / Masjid',
                            'fas fa-star' => 'Keunggulan / Bintang',
                            'fas fa-trophy' => 'Piala / Prestasi',
                            'fas fa-bus' => 'Transportasi',
                            'fas fa-building' => 'Gedung / Fasilitas',
                            'fas fa-laptop' => 'Komputer / IT',
                            'fas fa-globe' => 'Global / Bahasa',
                            'fas fa-heart' => 'Karakter / Hati',
                            'fas fa-hands-helping' => 'Sosial / Peduli',
                        ]
                    ],
                ]
            ],
            'about' => [
                'title' => 'Tentang Kami',
                'description' => 'Kelola konten profil sekolah, sejarah, dan keunggulan.',
                'icon' => 'fas fa-info-circle',
                'color' => 'bg-info',
                'fields' => [
                    // Group 1: Konten Utama
                    ['name' => 'title', 'type' => 'text', 'label' => 'Judul Header', 'group_title' => 'Informasi Utama', 'group_description' => 'Bagian ini mengatur judul utama dan teks deskripsi panjang mengenai sekolah.'],
                    ['name' => 'content', 'type' => 'richtext', 'label' => 'Deskripsi Lengkap'], 
                    
                    // Group 2: Galeri Foto
                    [
                        'name' => 'image', 
                        'type' => 'image', 
                        'label' => 'Foto Utama (Besar - Kiri)', 
                        'group_title' => 'Galeri Foto',
                        'group_description' => 'Upload foto kegiatan sekolah. Foto utama berukuran besar di sebelah kiri, dan dua foto kecil di sebelah kanan.'
                    ],
                    ['name' => 'image_2', 'type' => 'image', 'label' => 'Foto Tambahan 1 (Kecil - Kanan Atas)'],
                    ['name' => 'image_3', 'type' => 'image', 'label' => 'Foto Tambahan 2 (Kecil - Kanan Bawah)'],
                    
                    // Group 3: Statistik (REMOVED)
                    
                    // About Item 1
                    ['name' => 'item_1_title', 'type' => 'text', 'label' => 'Judul Poin 1', 'group_title' => 'Poin Keunggulan #1'],
                    ['name' => 'item_1_desc', 'type' => 'textarea', 'label' => 'Deskripsi Poin 1'],
                    [
                        'name' => 'item_1_icon', 
                        'type' => 'icon_picker', 
                        'label' => 'Pilih Ikon Poin 1',
                        'options' => [
                            'fas fa-book' => 'Buku',
                            'fas fa-user-graduate' => 'Siswa',
                            'fas fa-chalkboard-teacher' => 'Guru',
                            'fas fa-school' => 'Sekolah',
                            'fas fa-graduation-cap' => 'Kelulusan',
                            'fas fa-trophy' => 'Piala',
                            'fas fa-medal' => 'Medali',
                            'fas fa-star' => 'Unggulan',
                            'fas fa-mosque' => 'Masjid',
                            'fas fa-quran' => 'Al-Quran',
                            'fas fa-flask' => 'Sains',
                            'fas fa-calculator' => 'Matematika',
                            'fas fa-globe' => 'Geografi',
                            'fas fa-laptop' => 'Komputer',
                            'fas fa-desktop' => 'Lab Komputer',
                            'fas fa-palette' => 'Seni',
                            'fas fa-music' => 'Musik',
                            'fas fa-futbol' => 'Olahraga',
                            'fas fa-bus' => 'Transportasi',
                            'fas fa-clock' => 'Waktu',
                            'fas fa-clipboard-list' => 'Ujian',
                            'fas fa-id-card' => 'Identitas',
                            'fas fa-building' => 'Fasilitas',
                            'fas fa-hands-helping' => 'Sosial',
                        ]
                    ],

                    // About Item 2
                    ['name' => 'item_2_title', 'type' => 'text', 'label' => 'Judul Poin 2', 'group_title' => 'Poin Keunggulan #2'],
                    ['name' => 'item_2_desc', 'type' => 'textarea', 'label' => 'Deskripsi Poin 2'],
                    [
                        'name' => 'item_2_icon', 
                        'type' => 'icon_picker', 
                        'label' => 'Pilih Ikon Poin 2',
                        'options' => [
                            'fas fa-book' => 'Buku',
                            'fas fa-user-graduate' => 'Siswa',
                            'fas fa-chalkboard-teacher' => 'Guru',
                            'fas fa-school' => 'Sekolah',
                            'fas fa-graduation-cap' => 'Kelulusan',
                            'fas fa-trophy' => 'Piala',
                            'fas fa-medal' => 'Medali',
                            'fas fa-star' => 'Unggulan',
                            'fas fa-mosque' => 'Masjid',
                            'fas fa-quran' => 'Al-Quran',
                            'fas fa-flask' => 'Sains',
                            'fas fa-calculator' => 'Matematika',
                            'fas fa-globe' => 'Geografi',
                            'fas fa-laptop' => 'Komputer',
                            'fas fa-desktop' => 'Lab Komputer',
                            'fas fa-palette' => 'Seni',
                            'fas fa-music' => 'Musik',
                            'fas fa-futbol' => 'Olahraga',
                            'fas fa-bus' => 'Transportasi',
                            'fas fa-clock' => 'Waktu',
                            'fas fa-clipboard-list' => 'Ujian',
                            'fas fa-id-card' => 'Identitas',
                            'fas fa-building' => 'Fasilitas',
                            'fas fa-hands-helping' => 'Sosial',
                        ]
                    ],

                    // Quote
                    ['name' => 'quote_text', 'type' => 'textarea', 'label' => 'Kutipan Motivasi / Quote', 'group_title' => 'Kutipan & Kontak'],
                    ['name' => 'phone', 'type' => 'text', 'label' => 'Nomor Kontak / WhatsApp'],
                ]
            ],
             'counter' => [
                'title' => 'Statistik (Counter)',
                'description' => 'Bagian ini sudah otomatis terisi dari Database (Menu Siswa/Guru/Kelas).',
                'icon' => 'fas fa-chart-bar',
                'color' => 'bg-warning',
                'fields' => [
                    // Empty fields, effectively disabling editing but keeping the section listed or we can remove it entirely.
                    // Given the user said "hapus edit statistik", leaving it empty or with a message field is best.
                    ['name' => 'info_text', 'type' => 'text', 'label' => 'Info: Data diambil otomatis dari database.', 'readonly' => true],
                ]
            ],
            'video' => [
                'title' => 'Video Profil',
                'description' => 'Kelola link video profil Youtube dan deskripsinya.',
                'icon' => 'fas fa-video',
                'color' => 'bg-secondary',
                'fields' => [
                    ['name' => 'video_title', 'type' => 'text', 'label' => 'Judul Video', 'group_title' => 'Konten Video'],
                    ['name' => 'video_desc', 'type' => 'textarea', 'label' => 'Deskripsi Singkat'],
                    ['name' => 'video_btn_text', 'type' => 'text', 'label' => 'Teks Tombol Link'],
                    ['name' => 'video_btn_link', 'type' => 'text', 'label' => 'Link Tombol (ex: /profil)'],
                    ['name' => 'video_url', 'type' => 'text', 'label' => 'Link Youtube (Full URL)', 'group_title' => 'Media Video'],
                    ['name' => 'video_poster', 'type' => 'image', 'label' => 'Gambar Poster / Thumbnail Video'],
                ]
            ],
            'choose' => [
                'title' => 'Kenapa Memilih Kami',
                'description' => 'Alasan memilih sekolah.',
                'icon' => 'fas fa-check-circle',
                'color' => 'bg-dark',
                 'fields' => [
                    ['name' => 'title', 'type' => 'text', 'label' => 'Judul Header', 'group_title' => 'Header'],
                    ['name' => 'description', 'type' => 'textarea', 'label' => 'Deskripsi Singkat'],
                    ['name' => 'point_1', 'type' => 'text', 'label' => 'Poin Keunggulan 1', 'group_title' => 'Daftar Poin'],
                    ['name' => 'point_2', 'type' => 'text', 'label' => 'Poin Keunggulan 2'],
                    ['name' => 'point_3', 'type' => 'text', 'label' => 'Poin Keunggulan 3'],
                    ['name' => 'point_4', 'type' => 'text', 'label' => 'Poin Keunggulan 4'],
                ]
            ],
             'testimonial' => [
                'title' => 'Testimoni',
                'description' => 'Testimoni pilihan.',
                'icon' => 'fas fa-comments',
                'color' => 'bg-info',
                'fields' => [
                    ['name' => 'testi_1_name', 'type' => 'text', 'label' => 'Nama Testimoni 1', 'group_title' => 'Testimoni #1'],
                    ['name' => 'testi_1_role', 'type' => 'text', 'label' => 'Status Testimoni 1 (Wali Murid/Alumni)'],
                    ['name' => 'testi_1_content', 'type' => 'textarea', 'label' => 'Isi Testimoni 1'],
                     ['name' => 'testi_2_name', 'type' => 'text', 'label' => 'Nama Testimoni 2', 'group_title' => 'Testimoni #2'],
                    ['name' => 'testi_2_role', 'type' => 'text', 'label' => 'Status Testimoni 2'],
                    ['name' => 'testi_2_content', 'type' => 'textarea', 'label' => 'Isi Testimoni 2'],
                ]
            ],
            'program' => [
                'title' => 'Program & Layanan',
                'description' => 'Kelola daftar program unggulan dan layanan sekolah.',
                'icon' => 'fas fa-th-large',
                'color' => 'bg-success',
                'fields' => [
                    ['name' => 'title', 'type' => 'text', 'label' => 'Judul Header', 'group_title' => 'Header'],
                    ['name' => 'subtitle', 'type' => 'text', 'label' => 'Sub-Judul Header'],
                    ['name' => 'desc', 'type' => 'textarea', 'label' => 'Deskripsi Header'],

                    // Program 1
                    ['name' => 'prog_1_title', 'type' => 'text', 'label' => 'Judul Program 1', 'group_title' => 'Program #1'],
                    ['name' => 'prog_1_desc', 'type' => 'textarea', 'label' => 'Deskripsi Program 1'],
                    ['name' => 'prog_1_link', 'type' => 'text', 'label' => 'Link Tujuann 1'],
                    ['name' => 'prog_1_icon', 'type' => 'icon_picker', 'label' => 'Ikon Program 1', 'options' => $this->getIconOptions()],

                    // Program 2
                    ['name' => 'prog_2_title', 'type' => 'text', 'label' => 'Judul Program 2', 'group_title' => 'Program #2'],
                    ['name' => 'prog_2_desc', 'type' => 'textarea', 'label' => 'Deskripsi Program 2'],
                    ['name' => 'prog_2_link', 'type' => 'text', 'label' => 'Link Tujuan 2'],
                    ['name' => 'prog_2_icon', 'type' => 'icon_picker', 'label' => 'Ikon Program 2', 'options' => $this->getIconOptions()],

                    // Program 3
                    ['name' => 'prog_3_title', 'type' => 'text', 'label' => 'Judul Program 3', 'group_title' => 'Program #3'],
                    ['name' => 'prog_3_desc', 'type' => 'textarea', 'label' => 'Deskripsi Program 3'],
                    ['name' => 'prog_3_link', 'type' => 'text', 'label' => 'Link Tujuan 3'],
                    ['name' => 'prog_3_icon', 'type' => 'icon_picker', 'label' => 'Ikon Program 3', 'options' => $this->getIconOptions()],

                    // Program 4
                    ['name' => 'prog_4_title', 'type' => 'text', 'label' => 'Judul Program 4', 'group_title' => 'Program #4'],
                    ['name' => 'prog_4_desc', 'type' => 'textarea', 'label' => 'Deskripsi Program 4'],
                    ['name' => 'prog_4_link', 'type' => 'text', 'label' => 'Link Tujuan 4'],
                    ['name' => 'prog_4_icon', 'type' => 'icon_picker', 'label' => 'Ikon Program 4', 'options' => $this->getIconOptions()],

                    // Program 5
                    ['name' => 'prog_5_title', 'type' => 'text', 'label' => 'Judul Program 5', 'group_title' => 'Program #5'],
                    ['name' => 'prog_5_desc', 'type' => 'textarea', 'label' => 'Deskripsi Program 5'],
                    ['name' => 'prog_5_link', 'type' => 'text', 'label' => 'Link Tujuan 5'],
                    ['name' => 'prog_5_icon', 'type' => 'icon_picker', 'label' => 'Ikon Program 5', 'options' => $this->getIconOptions()],

                    // Program 6
                    ['name' => 'prog_6_title', 'type' => 'text', 'label' => 'Judul Program 6', 'group_title' => 'Program #6'],
                    ['name' => 'prog_6_desc', 'type' => 'textarea', 'label' => 'Deskripsi Program 6'],
                    ['name' => 'prog_6_link', 'type' => 'text', 'label' => 'Link Tujuan 6'],
                    ['name' => 'prog_6_icon', 'type' => 'icon_picker', 'label' => 'Ikon Program 6', 'options' => $this->getIconOptions()],
                ]
            ],
            'team' => [
                'title' => 'Guru & Tenaga Ahli',
                'description' => 'Kelola daftar guru dan staf (4 Orang Utama).',
                'icon' => 'fas fa-users',
                'color' => 'bg-danger',
                'fields' => [
                    // Team 1
                    ['name' => 'team_1_name', 'type' => 'text', 'label' => 'Nama Guru/Staf 1', 'group_title' => 'Guru/Staf #1'],
                    ['name' => 'team_1_job', 'type' => 'text', 'label' => 'Jabatan 1'],
                    ['name' => 'team_1_img', 'type' => 'image', 'label' => 'Foto 1 (Potrait)'],

                    // Team 2
                    ['name' => 'team_2_name', 'type' => 'text', 'label' => 'Nama Guru/Staf 2', 'group_title' => 'Guru/Staf #2'],
                    ['name' => 'team_2_job', 'type' => 'text', 'label' => 'Jabatan 2'],
                    ['name' => 'team_2_img', 'type' => 'image', 'label' => 'Foto 2 (Potrait)'],

                    // Team 3
                    ['name' => 'team_3_name', 'type' => 'text', 'label' => 'Nama Guru/Staf 3', 'group_title' => 'Guru/Staf #3'],
                    ['name' => 'team_3_job', 'type' => 'text', 'label' => 'Jabatan 3'],
                    ['name' => 'team_3_img', 'type' => 'image', 'label' => 'Foto 3 (Potrait)'],

                    // Team 4
                    ['name' => 'team_4_name', 'type' => 'text', 'label' => 'Nama Guru/Staf 4', 'group_title' => 'Guru/Staf #4'],
                    ['name' => 'team_4_job', 'type' => 'text', 'label' => 'Jabatan 4'],
                    ['name' => 'team_4_img', 'type' => 'image', 'label' => 'Foto 4 (Potrait)'],
                ]
            ],
            'choose' => [
                'title' => 'Alasan Memilih Kami',
                'description' => 'Bagian "Mengapa Memilih Kami" (4 Poin Utama & Gambar Kanan)',
                'icon' => 'fas fa-check-circle',
                'color' => 'bg-success',
                'fields' => [
                    ['name' => 'title', 'type' => 'text', 'label' => 'Judul Besar (misal: Pendidikan Berkualitas)'],
                    ['name' => 'subtitle', 'type' => 'text', 'label' => 'Sub-Judul (misal: Mengapa Memilih Kami)'],
                    ['name' => 'desc', 'type' => 'textarea', 'label' => 'Deskripsi Singkat'],
                    ['name' => 'image', 'type' => 'image', 'label' => 'Gambar Besar (Kanan)'],

                    // Item 1
                    ['name' => 'item_1_title', 'type' => 'text', 'label' => 'Judul Poin 1', 'group_title' => 'Poin Keunggulan 1'],
                    ['name' => 'item_1_desc', 'type' => 'textarea', 'label' => 'Deskripsi Poin 1'],
                    ['name' => 'item_1_icon', 'type' => 'icon_picker', 'label' => 'Ikon Poin 1', 'options' => $this->getIconOptions()],

                    // Item 2
                    ['name' => 'item_2_title', 'type' => 'text', 'label' => 'Judul Poin 2', 'group_title' => 'Poin Keunggulan 2'],
                    ['name' => 'item_2_desc', 'type' => 'textarea', 'label' => 'Deskripsi Poin 2'],
                    ['name' => 'item_2_icon', 'type' => 'icon_picker', 'label' => 'Ikon Poin 2', 'options' => $this->getIconOptions()],

                    // Item 3
                    ['name' => 'item_3_title', 'type' => 'text', 'label' => 'Judul Poin 3', 'group_title' => 'Poin Keunggulan 3'],
                    ['name' => 'item_3_desc', 'type' => 'textarea', 'label' => 'Deskripsi Poin 3'],
                    ['name' => 'item_3_icon', 'type' => 'icon_picker', 'label' => 'Ikon Poin 3', 'options' => $this->getIconOptions()],

                    // Item 4
                    ['name' => 'item_4_title', 'type' => 'text', 'label' => 'Judul Poin 4', 'group_title' => 'Poin Keunggulan 4'],
                    ['name' => 'item_4_desc', 'type' => 'textarea', 'label' => 'Deskripsi Poin 4'],
                    ['name' => 'item_4_icon', 'type' => 'icon_picker', 'label' => 'Ikon Poin 4', 'options' => $this->getIconOptions()],
                ]
            ],
            'event' => [
                'title' => 'Agenda & Kegiatan',
                'description' => 'Kelola daftar kegiatan sekolah (4 Item Terbaru).',
                'icon' => 'far fa-calendar-alt',
                'color' => 'bg-info',
                'fields' => [
                    // Event 1
                    ['name' => 'event_1_title', 'type' => 'text', 'label' => 'Judul Kegiatan 1', 'group_title' => 'Kegiatan #1'],
                    ['name' => 'event_1_date', 'type' => 'text', 'label' => 'Tanggal (contoh: 12 Okt 2025)'],
                    ['name' => 'event_1_time', 'type' => 'text', 'label' => 'Waktu (contoh: 08.00 - 10.00 WIB)'],
                    ['name' => 'event_1_loc', 'type' => 'text', 'label' => 'Lokasi'],
                    ['name' => 'event_1_desc', 'type' => 'textarea', 'label' => 'Deskripsi Singkat'],
                    ['name' => 'event_1_img', 'type' => 'image', 'label' => 'Gambar Kegiatan 1'],
                    ['name' => 'event_1_link', 'type' => 'text', 'label' => 'Link Detail (opsional)'],

                    // Event 2
                    ['name' => 'event_2_title', 'type' => 'text', 'label' => 'Judul Kegiatan 2', 'group_title' => 'Kegiatan #2'],
                    ['name' => 'event_2_date', 'type' => 'text', 'label' => 'Tanggal'],
                    ['name' => 'event_2_time', 'type' => 'text', 'label' => 'Waktu'],
                    ['name' => 'event_2_loc', 'type' => 'text', 'label' => 'Lokasi'],
                    ['name' => 'event_2_desc', 'type' => 'textarea', 'label' => 'Deskripsi Singkat'],
                    ['name' => 'event_2_img', 'type' => 'image', 'label' => 'Gambar Kegiatan 2'],
                    ['name' => 'event_2_link', 'type' => 'text', 'label' => 'Link Detail (opsional)'],

                    // Event 3
                    ['name' => 'event_3_title', 'type' => 'text', 'label' => 'Judul Kegiatan 3', 'group_title' => 'Kegiatan #3'],
                    ['name' => 'event_3_date', 'type' => 'text', 'label' => 'Tanggal'],
                    ['name' => 'event_3_time', 'type' => 'text', 'label' => 'Waktu'],
                    ['name' => 'event_3_loc', 'type' => 'text', 'label' => 'Lokasi'],
                    ['name' => 'event_3_desc', 'type' => 'textarea', 'label' => 'Deskripsi Singkat'],
                    ['name' => 'event_3_img', 'type' => 'image', 'label' => 'Gambar Kegiatan 3'],
                    ['name' => 'event_3_link', 'type' => 'text', 'label' => 'Link Detail (opsional)'],

                    // Event 4
                    ['name' => 'event_4_title', 'type' => 'text', 'label' => 'Judul Kegiatan 4', 'group_title' => 'Kegiatan #4'],
                    ['name' => 'event_4_date', 'type' => 'text', 'label' => 'Tanggal'],
                    ['name' => 'event_4_time', 'type' => 'text', 'label' => 'Waktu'],
                    ['name' => 'event_4_loc', 'type' => 'text', 'label' => 'Lokasi'],
                    ['name' => 'event_4_desc', 'type' => 'textarea', 'label' => 'Deskripsi Singkat'],
                    ['name' => 'event_4_img', 'type' => 'image', 'label' => 'Gambar Kegiatan 4'],
                    ['name' => 'event_4_link', 'type' => 'text', 'label' => 'Link Detail (opsional)'],
                ]
            ],
            'portfolio' => [
                'title' => 'Kegiatan Sekolah',
                'description' => 'Kelola 6 item kegiatan/galeri di homepage.',
                'icon' => 'fas fa-images',
                'color' => 'bg-purple',
                'fields' => [
                    // Item 1
                    ['name' => 'port_1_title', 'type' => 'text', 'label' => 'Judul Item 1', 'group_title' => 'Item #1'],
                    ['name' => 'port_1_cat', 'type' => 'text', 'label' => 'Kategori 1 (misal: // Literasi)'],
                    ['name' => 'port_1_img', 'type' => 'image', 'label' => 'Gambar 1'],
                    ['name' => 'port_1_link', 'type' => 'text', 'label' => 'Link Tuju 1'],

                    // Item 2
                    ['name' => 'port_2_title', 'type' => 'text', 'label' => 'Judul Item 2', 'group_title' => 'Item #2'],
                    ['name' => 'port_2_cat', 'type' => 'text', 'label' => 'Kategori 2'],
                    ['name' => 'port_2_img', 'type' => 'image', 'label' => 'Gambar 2'],
                    ['name' => 'port_2_link', 'type' => 'text', 'label' => 'Link Tuju 2'],

                    // Item 3
                    ['name' => 'port_3_title', 'type' => 'text', 'label' => 'Judul Item 3', 'group_title' => 'Item #3'],
                    ['name' => 'port_3_cat', 'type' => 'text', 'label' => 'Kategori 3'],
                    ['name' => 'port_3_img', 'type' => 'image', 'label' => 'Gambar 3'],
                    ['name' => 'port_3_link', 'type' => 'text', 'label' => 'Link Tuju 3'],

                    // Item 4
                    ['name' => 'port_4_title', 'type' => 'text', 'label' => 'Judul Item 4', 'group_title' => 'Item #4'],
                    ['name' => 'port_4_cat', 'type' => 'text', 'label' => 'Kategori 4'],
                    ['name' => 'port_4_img', 'type' => 'image', 'label' => 'Gambar 4'],
                    ['name' => 'port_4_link', 'type' => 'text', 'label' => 'Link Tuju 4'],

                    // Item 5
                    ['name' => 'port_5_title', 'type' => 'text', 'label' => 'Judul Item 5', 'group_title' => 'Item #5'],
                    ['name' => 'port_5_cat', 'type' => 'text', 'label' => 'Kategori 5'],
                    ['name' => 'port_5_img', 'type' => 'image', 'label' => 'Gambar 5'],
                    ['name' => 'port_5_link', 'type' => 'text', 'label' => 'Link Tuju 5'],

                    // Item 6
                    ['name' => 'port_6_title', 'type' => 'text', 'label' => 'Judul Item 6', 'group_title' => 'Item #6'],
                    ['name' => 'port_6_cat', 'type' => 'text', 'label' => 'Kategori 6'],
                    ['name' => 'port_6_img', 'type' => 'image', 'label' => 'Gambar 6'],
                    ['name' => 'port_6_link', 'type' => 'text', 'label' => 'Link Tuju 6'],
                ]
            ],
            'testimonial' => [
                'title' => 'Testimoni (Apa Kata Mereka)',
                'description' => 'Kelola 5 item testimoni di homepage.',
                'icon' => 'fas fa-comments',
                'color' => 'bg-success',
                'fields' => [
                    ['name' => 'title', 'type' => 'text', 'label' => 'Judul Utama (misal: Apa Kata Mereka)'],
                    ['name' => 'subtitle', 'type' => 'text', 'label' => 'Sub-Judul (misal: Testimoni)'],
                    ['name' => 'desc', 'type' => 'textarea', 'label' => 'Deskripsi Singkat'],
                    
                    // Item 1
                    ['name' => 'testi_1_name', 'type' => 'text', 'label' => 'Nama 1', 'group_title' => 'Testimoni #1'],
                    ['name' => 'testi_1_role', 'type' => 'text', 'label' => 'Status 1 (misal: Orang Tua)'],
                    ['name' => 'testi_1_text', 'type' => 'textarea', 'label' => 'Isi Testimoni 1'],
                    ['name' => 'testi_1_img', 'type' => 'image', 'label' => 'Foto 1'],

                    // Item 2
                    ['name' => 'testi_2_name', 'type' => 'text', 'label' => 'Nama 2', 'group_title' => 'Testimoni #2'],
                    ['name' => 'testi_2_role', 'type' => 'text', 'label' => 'Status 2'],
                    ['name' => 'testi_2_text', 'type' => 'textarea', 'label' => 'Isi Testimoni 2'],
                    ['name' => 'testi_2_img', 'type' => 'image', 'label' => 'Foto 2'],

                    // Item 3
                    ['name' => 'testi_3_name', 'type' => 'text', 'label' => 'Nama 3', 'group_title' => 'Testimoni #3'],
                    ['name' => 'testi_3_role', 'type' => 'text', 'label' => 'Status 3'],
                    ['name' => 'testi_3_text', 'type' => 'textarea', 'label' => 'Isi Testimoni 3'],
                    ['name' => 'testi_3_img', 'type' => 'image', 'label' => 'Foto 3'],

                    // Item 4
                    ['name' => 'testi_4_name', 'type' => 'text', 'label' => 'Nama 4', 'group_title' => 'Testimoni #4'],
                    ['name' => 'testi_4_role', 'type' => 'text', 'label' => 'Status 4'],
                    ['name' => 'testi_4_text', 'type' => 'textarea', 'label' => 'Isi Testimoni 4'],
                    ['name' => 'testi_4_img', 'type' => 'image', 'label' => 'Foto 4'],
                    
                     // Item 5
                    ['name' => 'testi_5_name', 'type' => 'text', 'label' => 'Nama 5', 'group_title' => 'Testimoni #5'],
                    ['name' => 'testi_5_role', 'type' => 'text', 'label' => 'Status 5'],
                    ['name' => 'testi_5_text', 'type' => 'textarea', 'label' => 'Isi Testimoni 5'],
                    ['name' => 'testi_5_img', 'type' => 'image', 'label' => 'Foto 5'],
                ]
            ],
            // Add other sections as needed with 'fields' array
        ];
    }

    public function index()
    {
        $config = $this->getSectionsConfig();
        
        // Urutan yang diinginkan (sesuai urutan di Beranda + Halaman Profil)
        $orderedKeys = [
            // === HOMEPAGE SECTIONS ===
            'hero',           // 1. Hero Slider
            'feature',        // 2. Keunggulan Sekolah
            'about',          // 3. Tentang Kami
            'counter',        // 4. Statistik (Otomatis)
            'program',        // 5. Program & Layanan
            'video',          // 6. Video Profil
            'team',           // 7. Guru & Tenaga Ahli
            'choose',         // 8. Mengapa Memilih Kami
            'portfolio',      // 9. Kegiatan Sekolah
            'event',          // 10. Agenda & Kegiatan
            'testimonial',    // 11. Testimoni
            // === HALAMAN PROFIL ===
            'visi_misi',      // 12. Visi & Misi
            'sejarah',        // 13. Sejarah
            'sambutan',       // 14. Sambutan Kepala Sekolah
            'organisasi',     // 15. Struktur Organisasi
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

        $sectionConfig = $config[$key];
        
        // Find or create the section in DB
        $section = Section::firstOrCreate(
            ['key' => $key],
            ['title' => $sectionConfig['title'], 'content' => []]
        );

        return Inertia::render('Admin/Pages/Edit', [
            'section' => $section,
            'schema' => $sectionConfig['fields'], // Pass the fields schema
            'title' => $sectionConfig['title']
        ]);
    }

    public function update(Request $request, $key)
    {
        $config = $this->getSectionsConfig();
        if (!isset($config[$key])) {
            abort(404);
        }

        $section = Section::where('key', $key)->firstOrFail();
        $content = $section->content ?? [];
        $rules = [];

        // Dynamic validation and processing
        foreach ($config[$key]['fields'] as $field) {
            $fieldName = $field['name'];
            
            if ($field['type'] === 'image') {
                $rules[$fieldName] = 'nullable|image|max:2048';
                
                if ($request->hasFile($fieldName)) {
                    // Delete old image if exists
                     if (isset($content[$fieldName]) && $content[$fieldName]) {
                        Storage::disk('public')->delete($content[$fieldName]);
                    }
                    $path = $request->file($fieldName)->store('pages', 'public');
                    $content[$fieldName] = $path;
                }
            } else {
                $content[$fieldName] = $request->input($fieldName);
            }
        }

        $section->update(['content' => $content]);

        return redirect()->route('pages.index')->with('success', 'Konten berhasil diperbarui!');
    }
}

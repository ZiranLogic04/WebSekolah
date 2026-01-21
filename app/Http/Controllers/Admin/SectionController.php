<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Section;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;

class SectionController extends Controller
{
    /**
     * Display About Section Editor
     */
    public function editAbout()
    {
        $section = Section::firstOrCreate(
            ['key' => 'about'],
            [
                'title' => 'Tentang Kami',
                'content' => [
                    'tagline' => 'Tentang Kami',
                    'title' => 'Sistem Pendidikan Madrasah Memberdayakan Potensi Sejak Dini',
                    'highlight_word' => 'Madrasah',
                    'description' => 'MI Al-Hikmah berkomitmen menghadirkan pendidikan dasar berkualitas yang menumbuhkan akhlak mulia, serta menguatkan literasi dan numerasi sejak dini.',
                    'image_1' => '/assets/img/hope/_U9A7700.jpg',
                    'image_2' => '/assets/img/hope/_U9A8033.jpg',
                    'image_3' => '/assets/img/hope/259A2654 copy.JPG',
                    'item_1_icon' => 'open-book.svg',
                    'item_1_title' => 'Tahfiz & BTQ Terstruktur',
                    'item_1_desc' => 'Tahsin bacaan, target juz 30 bertahap, setoran rutin, dan pendampingan harian.',
                    'item_2_icon' => 'global-education.svg',
                    'item_2_title' => 'Literasi & Klinik Numerasi',
                    'item_2_desc' => 'Membaca 15 menit setiap pagi dan pendampingan berhitung untuk fondasi akademik kuat.',
                    'quote' => 'Kami membangun lingkungan belajar yang aman, hangat, dan mendorong kemandirian serta rasa ingin tahu anak.',
                    'button_text' => 'Selengkapnya',
                    'button_url' => '/profil',
                ]
            ]
        );

        return Inertia::render('Admin/Tampilan/About', [
            'section' => $section,
            'availableIcons' => \App\Models\Feature::getAvailableIcons(),
        ]);
    }

    /**
     * Update About Section
     */
    public function updateAbout(Request $request)
    {
        $request->validate([
            'tagline' => 'required|string|max:100',
            'title' => 'required|string|max:255',
            'highlight_word' => 'nullable|string|max:50',
            'description' => 'required|string',
            'item_1_icon' => 'required|string',
            'item_1_title' => 'required|string|max:100',
            'item_1_desc' => 'required|string|max:255',
            'item_2_icon' => 'required|string',
            'item_2_title' => 'required|string|max:100',
            'item_2_desc' => 'required|string|max:255',
            'quote' => 'required|string',
            'button_text' => 'required|string|max:50',
            'button_url' => 'required|string|max:255',
            'image_1' => 'nullable|image|max:2048',
            'image_2' => 'nullable|image|max:2048',
            'image_3' => 'nullable|image|max:2048',
        ], [
            'tagline.required' => 'Tagline wajib diisi.',
            'title.required' => 'Judul wajib diisi.',
            'description.required' => 'Deskripsi wajib diisi.',
            'item_1_icon.required' => 'Icon highlight 1 wajib dipilih.',
            'item_1_title.required' => 'Judul highlight 1 wajib diisi.',
            'item_1_desc.required' => 'Deskripsi highlight 1 wajib diisi.',
            'item_2_icon.required' => 'Icon highlight 2 wajib dipilih.',
            'item_2_title.required' => 'Judul highlight 2 wajib diisi.',
            'item_2_desc.required' => 'Deskripsi highlight 2 wajib diisi.',
            'quote.required' => 'Kutipan/Quote wajib diisi.',
            'button_text.required' => 'Teks tombol wajib diisi.',
            'button_url.required' => 'Link tombol wajib diisi.',
            'image_*.image' => 'File harus berupa gambar.',
            'image_*.max' => 'Ukuran gambar maksimal 2MB.',
        ]);

        $section = Section::where('key', 'about')->first();
        $content = $section->content ?? [];

        // Handle image uploads
        foreach (['image_1', 'image_2', 'image_3'] as $imageField) {
            if ($request->hasFile($imageField)) {
                $file = $request->file($imageField);
                $filename = $imageField . '_' . time() . '.webp';
                $path = 'uploads/about/' . $filename;

                // Convert to WebP
                $manager = new ImageManager(new Driver());
                $image = $manager->read($file->getRealPath());
                $image->toWebp(80)->save(public_path($path));

                $content[$imageField] = '/' . $path;
            }
        }

        // Update text content
        $content['tagline'] = $request->tagline;
        $content['title'] = $request->title;
        $content['highlight_word'] = $request->highlight_word;
        $content['description'] = $request->description;
        $content['item_1_icon'] = $request->item_1_icon;
        $content['item_1_title'] = $request->item_1_title;
        $content['item_1_desc'] = $request->item_1_desc;
        $content['item_2_icon'] = $request->item_2_icon;
        $content['item_2_title'] = $request->item_2_title;
        $content['item_2_desc'] = $request->item_2_desc;
        $content['quote'] = $request->quote;
        $content['button_text'] = $request->button_text;
        $content['button_url'] = $request->button_url;

        $section->update(['content' => $content]);

        return redirect()->back()->with('success', 'Section Tentang Kami berhasil diperbarui!');
    }

    /**
     * Display Counter Section Editor
     */
    public function editCounter()
    {
        $section = Section::firstOrCreate(
            ['key' => 'counter'],
            [
                'title' => 'Counter/Statistik',
                'content' => [
                    'siswa_aktif' => '150',
                    'guru' => '12',
                    'rombel' => '6',
                    'alumni' => '500',
                ]
            ]
        );

        return Inertia::render('Admin/Tampilan/Counter', [
            'section' => $section,
        ]);
    }

    /**
     * Update Counter Section
     */
    public function updateCounter(Request $request)
    {
        $request->validate([
            'siswa_aktif' => 'required|max:10',
            'guru' => 'required|max:10',
            'rombel' => 'required|max:10',
            'alumni' => 'required|max:10',
        ], [
            'siswa_aktif.required' => 'Jumlah siswa aktif wajib diisi.',
            'siswa_aktif.max' => 'Jumlah siswa maksimal 10 karakter.',
            'guru.required' => 'Jumlah guru wajib diisi.',
            'guru.max' => 'Jumlah guru maksimal 10 karakter.',
            'rombel.required' => 'Jumlah rombel wajib diisi.',
            'rombel.max' => 'Jumlah rombel maksimal 10 karakter.',
            'alumni.required' => 'Jumlah alumni wajib diisi.',
            'alumni.max' => 'Jumlah alumni maksimal 10 karakter.',
        ]);

        $section = Section::where('key', 'counter')->first();
        
        $section->update([
            'content' => [
                'siswa_aktif' => $request->siswa_aktif,
                'guru' => $request->guru,
                'rombel' => $request->rombel,
                'alumni' => $request->alumni,
            ]
        ]);

        return redirect()->back()->with('success', 'Data Counter berhasil diperbarui!');
    }

    /**
     * Display Program Section Editor
     */
    public function editProgram()
    {
        $section = Section::firstOrCreate(
            ['key' => 'program'],
            [
                'title' => 'Program & Layanan',
                'content' => [
                    'tagline' => 'Program MI',
                    'title' => 'Jelajahi <span>Program & Layanan</span> Kami',
                    'description' => 'Program pembelajaran dan layanan siswa di MI Al-Hikmah yang menumbuhkan iman, ilmu, karakter, literasi, dan numerasi dalam lingkungan yang ramah anak.',
                    'items' => [
                        ['icon' => 'monitor.svg', 'title' => 'Kurikulum & Pembelajaran', 'desc' => 'Kurikulum Merdeka/K13, jadwal pelajaran, penilaian, pengayaan & remedial.', 'url' => '/akademik/kurikulum'],
                        ['icon' => 'art.svg', 'title' => 'Tahfiz & BTQ', 'desc' => 'Tahsin bacaan, target juz 30, setoran berkala, dan pembiasaan ibadah harian.', 'url' => '/program/tahfiz'],
                        ['icon' => 'data.svg', 'title' => 'Literasi 15 Menit & Perpustakaan', 'desc' => 'Membaca harian, pojok baca kelas, peminjaman buku, dan lomba literasi.', 'url' => '/program/literasi'],
                        ['icon' => 'monitor.svg', 'title' => 'Klinik Numerasi & Sains Dasar', 'desc' => 'Pendampingan berhitung, permainan numerasi, dan percobaan sains sederhana.', 'url' => '/program/numerasi'],
                        ['icon' => 'teacher-2.svg', 'title' => 'Kesiswaan & Ekstrakurikuler', 'desc' => 'Pramuka, olahraga, seni, MTQ, drumband, dan kegiatan penguatan karakter.', 'url' => '/kesiswaan/ekskul'],
                        ['icon' => 'health.svg', 'title' => 'Layanan Siswa (UKS & Anti-Bullying)', 'desc' => 'UKS, edukasi PHBS, Satgas Anti-Bullying, dan alur pengaduan yang aman.', 'url' => '/layanan/anak'],
                    ]
                ]
            ]
        );

        return Inertia::render('Admin/Tampilan/Program', [
            'section' => $section,
            'availableIcons' => \App\Models\Feature::getAvailableIcons(),
        ]);
    }

    /**
     * Update Program Section
     */
    public function updateProgram(Request $request)
    {
        $request->validate([
            'tagline' => 'required|string|max:100',
            'title' => 'required|string',
            'description' => 'required|string',
            'items' => 'required|array|min:1|max:10',
            'items.*.icon' => 'required|string',
            'items.*.title' => 'required|string|max:100',
            'items.*.desc' => 'required|string|max:255',
            'items.*.url' => 'nullable|string|max:255',
        ], [
            'tagline.required' => 'Tagline wajib diisi.',
            'title.required' => 'Judul wajib diisi.',
            'description.required' => 'Deskripsi wajib diisi.',
            'items.required' => 'Minimal harus ada 1 program.',
            'items.min' => 'Minimal harus ada 1 program.',
            'items.max' => 'Maksimal 10 program.',
            'items.*.title.required' => 'Judul program wajib diisi.',
            'items.*.desc.required' => 'Deskripsi program wajib diisi.',
        ]);

        $section = Section::where('key', 'program')->first();
        
        $section->update([
            'content' => [
                'tagline' => $request->tagline,
                'title' => $request->title,
                'description' => $request->description,
                'items' => $request->items,
            ]
        ]);

        return redirect()->back()->with('success', 'Section Program berhasil diperbarui!');
    }

    /**
     * Display Video Section Editor
     */
    public function editVideo()
    {
        $section = Section::firstOrCreate(
            ['key' => 'video'],
            [
                'title' => 'Video Profil',
                'content' => [
                    'tagline' => 'Video',
                    'title' => 'Profil <span>MI Al-Hikmah</span>',
                    'description' => 'Sekilas tentang MI Al-Hikmah: suasana belajar, pembiasaan ibadah, literasi & numerasi, serta fasilitas sekolah.',
                    'button_text' => 'Profil Madrasah',
                    'button_url' => '/profil',
                    'poster_image' => '/assets/img/hope/poster-profil.jpg',
                    'youtube_url' => 'https://www.youtube.com/watch?v=VIDEO_ID_PROFIL',
                ]
            ]
        );

        return Inertia::render('Admin/Tampilan/Video', [
            'section' => $section,
        ]);
    }

    /**
     * Update Video Section
     */
    public function updateVideo(Request $request)
    {
        $request->validate([
            'tagline' => 'required|string|max:100',
            'title' => 'required|string',
            'description' => 'required|string',
            'button_text' => 'required|string|max:50',
            'button_url' => 'required|string|max:255',
            'youtube_url' => 'required|string|max:500',
            'poster_image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ], [
            'tagline.required' => 'Tagline wajib diisi',
            'tagline.max' => 'Tagline maksimal 100 karakter',
            'title.required' => 'Judul wajib diisi',
            'description.required' => 'Deskripsi wajib diisi',
            'button_text.required' => 'Teks tombol wajib diisi',
            'button_text.max' => 'Teks tombol maksimal 50 karakter',
            'button_url.required' => 'Link tombol wajib diisi',
            'youtube_url.required' => 'URL YouTube wajib diisi',
            'poster_image.image' => 'File harus berupa gambar',
            'poster_image.mimes' => 'Format gambar harus JPG, PNG, atau WebP',
            'poster_image.max' => 'Ukuran gambar maksimal 2MB',
        ]);

        $section = Section::where('key', 'video')->first();
        $content = $section->content;

        // Handle poster image upload
        if ($request->hasFile('poster_image')) {
            $file = $request->file('poster_image');
            $filename = 'poster-video-' . time() . '.webp';
            $path = 'img/video/' . $filename;
            
            // Convert to WebP
            $manager = new ImageManager(new Driver());
            $image = $manager->read($file->getRealPath());
            $image->toWebp(85)->save(public_path('assets/' . $path));
            
            $content['poster_image'] = '/assets/' . $path;
        }

        $content['tagline'] = $request->tagline;
        $content['title'] = $request->title;
        $content['description'] = $request->description;
        $content['button_text'] = $request->button_text;
        $content['button_url'] = $request->button_url;
        $content['youtube_url'] = $request->youtube_url;

        $section->update(['content' => $content]);

        return redirect()->back()->with('success', 'Section Video berhasil diperbarui!');
    }

    /**
     * Display Team/Guru Section Editor
     */
    public function editTeam()
    {
        $section = Section::firstOrCreate(
            ['key' => 'team'],
            [
                'title' => 'Guru & Tendik',
                'content' => [
                    'tagline' => 'Guru & Tendik',
                    'title' => 'Kenali Tim <span>Pengajar & Tendik</span>',
                    'description' => 'Guru yang peduli dan tenaga kependidikan yang sigap—bersama menghadirkan pembelajaran yang aman, nyaman, dan bermakna.',
                    'items' => [
                        ['nama' => 'Nama Guru 1', 'jabatan' => 'Kepala Sekolah', 'foto' => '', 'facebook' => '', 'instagram' => '', 'twitter' => ''],
                        ['nama' => 'Nama Guru 2', 'jabatan' => 'Wakil Kepala Sekolah', 'foto' => '', 'facebook' => '', 'instagram' => '', 'twitter' => ''],
                        ['nama' => 'Nama Guru 3', 'jabatan' => 'Guru Kelas 1', 'foto' => '', 'facebook' => '', 'instagram' => '', 'twitter' => ''],
                        ['nama' => 'Nama Guru 4', 'jabatan' => 'Guru Kelas 2', 'foto' => '', 'facebook' => '', 'instagram' => '', 'twitter' => ''],
                    ]
                ]
            ]
        );

        return Inertia::render('Admin/Tampilan/Team', [
            'section' => $section,
        ]);
    }

    /**
     * Update Team/Guru Section
     */
    public function updateTeam(Request $request)
    {

        $request->validate([
            'tagline' => 'required|string|max:100',
            'title' => 'required|string',
            'description' => 'required|string',
            'items' => 'required|array|size:4',
            'items.*.nama' => 'required|string|max:100',
            'items.*.jabatan' => 'required|string|max:100',
            'items.*.foto' => 'nullable|string',
            'items.*.facebook' => 'nullable|string',
            'items.*.instagram' => 'nullable|string',
            'items.*.twitter' => 'nullable|string',
            'foto_0' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'foto_1' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'foto_2' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'foto_3' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ], [
            'tagline.required' => 'Tagline wajib diisi.',
            'title.required' => 'Judul wajib diisi.',
            'description.required' => 'Deskripsi wajib diisi.',
            'items.size' => 'Harus ada tepat 4 item guru.',
            'items.*.nama.required' => 'Nama guru wajib diisi.',
            'items.*.jabatan.required' => 'Jabatan guru wajib diisi.',
            'foto_*.image' => 'File harus berupa gambar.',
            'foto_*.max' => 'Ukuran foto maksimal 2MB.',
            'foto_*.mimes' => 'Format foto harus jpg, jpeg, png, atau webp.',
        ]);

        $section = Section::where('key', 'team')->first();
        $items = $request->items;

        // Handle photo uploads for each team member
        for ($i = 0; $i < 4; $i++) {
            if ($request->hasFile("foto_{$i}")) {
                $file = $request->file("foto_{$i}");
                $filename = 'team-' . $i . '-' . time() . '.webp';
                $path = 'img/team/' . $filename;
                
                // Convert to WebP
                $manager = new ImageManager(new Driver());
                $image = $manager->read($file->getRealPath());
                $image->toWebp(85)->save(public_path('assets/' . $path));
                
                $items[$i]['foto'] = '/assets/' . $path;
            }
        }

        $section->update([
            'content' => [
                'tagline' => $request->tagline,
                'title' => $request->title,
                'description' => $request->description,
                'items' => $items,
            ]
        ]);

        return redirect()->back()->with('success', 'Section Guru & Tendik berhasil diperbarui!');
    }

    /**
     * Display Choose Us Section Editor
     */
    public function editChoose()
    {
        $section = Section::firstOrCreate(
            ['key' => 'choose'],
            [
                'title' => 'Mengapa Memilih Kami',
                'content' => [
                    'tagline' => 'Mengapa Memilih Kami',
                    'title' => 'Pendidikan Dasar Berkualitas & <span>Ramah Anak</span>',
                    'description' => 'MI Al-Hikmah berkomitmen menghadirkan pembelajaran yang menumbuhkan iman, ilmu, dan karakter. Program terstruktur, guru kompeten, serta lingkungan aman–nyaman mendukung tumbuh kembang siswa setiap hari.',
                    'image' => '/assets/img/hope/_U9A7765.jpg',
                    'items' => [
                        ['title' => 'Guru Peduli & Kompeten', 'desc' => 'Pembelajaran aktif, pendampingan personal, dan evaluasi berkelanjutan di setiap kelas.', 'icon' => 'teacher-2.svg'],
                        ['title' => 'Tahfiz & BTQ Terstruktur', 'desc' => 'Tahsin bacaan, target juz 30 bertahap, setoran rutin, dan pembiasaan ibadah harian.', 'icon' => 'course-material.svg'],
                        ['title' => 'Literasi & Klinik Numerasi', 'desc' => 'Membaca 15 menit setiap hari dan pendampingan berhitung untuk menguatkan dasar akademik.', 'icon' => 'online-course.svg'],
                        ['title' => 'Sekolah Ramah Anak', 'desc' => 'Satgas Anti-Bullying, UKS, dan komunikasi orang tua–sekolah yang responsif & bersahabat.', 'icon' => 'health.svg'],
                    ]
                ]
            ]
        );

        return Inertia::render('Admin/Tampilan/Choose', [
            'section' => $section,
            'availableIcons' => \App\Models\Feature::getAvailableIcons(),
        ]);
    }

    /**
     * Update Choose Us Section
     */
    public function updateChoose(Request $request)
    {
        $request->validate([
            'tagline' => 'required|string|max:100',
            'title' => 'required|string',
            'description' => 'required|string',
            'image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'items' => 'required|array|size:4',
            'items.*.title' => 'required|string|max:100',
            'items.*.desc' => 'required|string',
            'items.*.icon' => 'required|string|max:255',
        ], [
            'tagline.required' => 'Tagline wajib diisi',
            'title.required' => 'Judul wajib diisi',
            'description.required' => 'Deskripsi wajib diisi',
            'items.*.title.required' => 'Judul poin wajib diisi',
            'items.*.desc.required' => 'Deskripsi poin wajib diisi',
            'items.*.icon.required' => 'Icon poin wajib dipilih',
            'image.image' => 'File utama harus gambar',
        ]);

        $section = Section::where('key', 'choose')->first();
        $content = $section->content;
        $items = $request->items;

        // Handle Main Image
        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $filename = 'choose-main-' . time() . '.webp';
            $path = 'img/choose/' . $filename;
            
            $manager = new ImageManager(new Driver());
            $image = $manager->read($file->getRealPath());
            $image->toWebp(90)->save(public_path('assets/' . $path));
            
            $content['image'] = '/assets/' . $path;
        }

        $content['tagline'] = $request->tagline;
        $content['title'] = $request->title;
        $content['description'] = $request->description;
        $content['items'] = $items;

        $section->update(['content' => $content]);

        return redirect()->back()->with('success', 'Section Mengapa Memilih Kami berhasil diperbarui!');
    }

    /**
     * Display Portfolio Section Editor
     */
    public function editPortfolio()
    {
        $section = Section::firstOrCreate(
            ['key' => 'portfolio'],
            [
                'title' => 'Portofolio',
                'content' => [
                    'tagline' => 'Kehidupan di MI',
                    'title' => 'Kegiatan di <span>MI Al-Hikmah</span>',
                    'items' => [
                        [
                            'category' => 'Literasi',
                            'title' => 'Budaya Literasi 15 Menit',
                            'url' => '/program/literasi',
                            'image' => '/assets/img/hope/stRe.jpg'
                        ],
                        [
                            'category' => 'Keagamaan',
                            'title' => 'Tahfiz & BTQ',
                            'url' => '/program/tahfiz',
                            'image' => '/assets/img/hope/aka465.jpg'
                        ],
                        [
                            'category' => 'Ekskul',
                            'title' => 'Olahraga & Pramuka',
                            'url' => '/kesiswaan/ekskul',
                            'image' => '/assets/img/hope/r456.jpg'
                        ],
                        [
                            'category' => 'Ramah Anak',
                            'title' => 'Sekolah Ramah Anak',
                            'url' => '/anti-bullying',
                            'image' => '/assets/img/hope/ra123k.jpg'
                        ],
                        [
                            'category' => 'Keagamaan',
                            'title' => 'Pembiasaan Ibadah Harian',
                            'url' => '/program/keagamaan',
                            'image' => '/assets/img/hope/REMISE UEA (29).jpg'
                        ],
                        [
                            'category' => 'Sarpras',
                            'title' => 'Fasilitas Sekolah',
                            'url' => '/sarpras',
                            'image' => '/assets/img/hope/IMG_1135 -BG (1).jpg'
                        ],
                    ]
                ]
            ]
        );

        return Inertia::render('Admin/Tampilan/Portfolio', [
            'section' => $section
        ]);
    }

    /**
     * Update Portfolio Section
     */
    public function updatePortfolio(Request $request)
    {
        $request->validate([
            'tagline' => 'required|string|max:100',
            'title' => 'required|string',
            'items' => 'required|array|size:6',
            'items.*.category' => 'required|string|max:50',
            'items.*.title' => 'required|string|max:100',
            'items.*.url' => 'required|string',
            'image_0' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'image_1' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'image_2' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'image_3' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'image_4' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'image_5' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ], [
            'tagline.required' => 'Tagline wajib diisi.',
            'title.required' => 'Judul utama wajib diisi.',
            'items.required' => 'Item tidak tersedia.',
            'items.*.category.required' => 'Kategori wajib diisi.',
            'items.*.title.required' => 'Judul kegiatan wajib diisi.',
            'items.*.url.required' => 'URL/Link wajib diisi (bisa diisi # jika tidak ada).',
            'image_*.image' => 'File harus berupa gambar.',
            'image_*.mimes' => 'Format gambar harus jpg, jpeg, png, atau webp.',
            'image_*.max' => 'Ukuran gambar maksimal 2MB.',
        ]);

        $section = Section::where('key', 'portfolio')->first();
        $items = $request->items;
        $oldItems = $section->content['items'] ?? [];

        // Handle photo uploads
        for ($i = 0; $i < 6; $i++) {
            if ($request->hasFile("image_{$i}")) {
                // Delete old image if it's not a default asset
                if (isset($oldItems[$i]['image'])) {
                    $oldPath = public_path($oldItems[$i]['image']);
                    // Only delete files in the 'activity' folder to protect defaults
                    if (file_exists($oldPath) && strpos($oldItems[$i]['image'], '/assets/img/activity/') !== false) {
                        @unlink($oldPath);
                    }
                }

                $file = $request->file("image_{$i}");
                $filename = 'activity-' . $i . '-' . time() . '.webp';
                $path = 'img/activity/' . $filename;
                
                // Ensure directory exists
                if (!file_exists(public_path('assets/img/activity'))) {
                    mkdir(public_path('assets/img/activity'), 0755, true);
                }

                // Convert to WebP with Resize
                $manager = new ImageManager(new Driver());
                $image = $manager->read($file->getRealPath());
                
                // Resize to 800x600 (4:3 ratio) to ensure consistency
                $image->cover(800, 600);
                
                $image->toWebp(80)->save(public_path('assets/' . $path));
                
                $items[$i]['image'] = '/assets/' . $path;
            } else {
                // Keep existing image if no new file uploaded
                // Since we added 'image' to form items, $request->items[$i]['image'] has it.
                // But let's be safe and ensure consistency with DB if needed.
                // Actually, if $request->items has it, we are good.
                // But if user messed with form data? 
                // We'll trust $request->items as validated array.
            }
        }

        $section->update(['content' => [
            'tagline' => $request->tagline,
            'title' => $request->title,
            'items' => $items,
        ]]);

        return redirect()->back()->with('success', 'Section Portofolio Kegiatan berhasil diperbarui!');
    }

    /**
     * Display Event Section Editor
     */
    public function editEvent()
    {
        $section = Section::firstOrCreate(
            ['key' => 'event'],
            [
                'title' => 'Agenda',
                'content' => [
                    'tagline' => 'Kegiatan',
                    'title' => 'Agenda <span>Terbaru</span>',
                    'items' => [
                        [
                            'location' => 'Aula MI Al-Hikmah',
                            'date' => '12 Okt 2025',
                            'time' => '08.00–10.30 WIB',
                            'title' => 'Sosialisasi PPDB 2026',
                            'description' => 'Pemaparan syarat, alur pendaftaran, kuota, dan biaya pendidikan kepada orang tua/wali.',
                            'link' => '/ppdb',
                            'image' => '/assets/img/hope/ogg.jpg'
                        ],
                        [
                            'location' => 'Perpustakaan',
                            'date' => '04 Okt 2025',
                            'time' => '09.00–11.00 WIB',
                            'title' => 'Launching Pojok Baca',
                            'description' => 'Gerakan membaca harian dan peminjaman buku untuk memperkuat budaya literasi siswa.',
                            'link' => '/program/literasi',
                            'image' => '/assets/img/hope/IMG_1135 -BG.jpg'
                        ],
                        [
                            'location' => 'Masjid Sekolah',
                            'date' => '28 Sep 2025',
                            'time' => '07.30–09.30 WIB',
                            'title' => 'Setoran Tahfiz Akbar',
                            'description' => 'Evaluasi capaian juz 30, muroja\'ah bersama, dan apresiasi untuk peserta terbaik.',
                            'link' => '/program/tahfiz',
                            'image' => '/assets/img/hope/REMISE UEA (29).jpg'
                        ],
                        [
                            'location' => 'Lapangan MI',
                            'date' => '21 Sep 2025',
                            'time' => '13.00–17.00 WIB',
                            'title' => 'Perkemahan Pramuka',
                            'description' => 'Penguatan kemandirian, kerja sama, dan disiplin melalui aktivitas kepramukaan.',
                            'link' => '/kesiswaan/ekskul',
                            'image' => '/assets/img/hope/h1a.jpg'
                        ],
                    ]
                ]
            ]
        );

        return Inertia::render('Admin/Tampilan/Event', [
            'section' => $section
        ]);
    }

    /**
     * Update Event Section
     */
    public function updateEvent(Request $request)
    {
        $request->validate([
            'tagline' => 'required|string|max:100',
            'title' => 'required|string',
            'items' => 'required|array',
            'items.*.location' => 'required|string|max:100',
            'items.*.date' => 'required|string|max:50',
            'items.*.time_start' => 'required|string|max:20',
            'items.*.time_end' => 'required|string|max:20',
            'items.*.title' => 'required|string|max:100',
            'items.*.description' => 'required|string|max:200',
            'items.*.link' => 'required|string',
            // Allow dynamic image keys validation? 
            // Better to skip explicit image_0..N validation here as keys are dynamic, 
            // check in loop or rely on basic max size from php.ini, or validate array manually?
            // We'll trust the loop check.
        ], [
            'tagline.required' => 'Tagline wajib diisi.',
            'title.required' => 'Judul utama wajib diisi.',
            'items.required' => 'Daftar agenda tidak boleh kosong.',
            'items.*.location.required' => 'Lokasi kegiatan wajib diisi.',
            'items.*.date.required' => 'Tanggal kegiatan wajib diisi.',
            'items.*.time_start.required' => 'Jam mulai wajib diisi.',
            'items.*.time_end.required' => 'Jam selesai wajib diisi.',
            'items.*.title.required' => 'Judul agenda wajib diisi.',
            'items.*.description.required' => 'Deskripsi agenda wajib diisi.',
            'items.*.link.required' => 'Link agenda wajib diisi.',
        ]);

        // Custom validation: time_end must be greater than time_start
        $items = $request->items;
        $errors = [];
        foreach ($items as $i => $item) {
            if (!empty($item['time_start']) && !empty($item['time_end'])) {
                if ($item['time_end'] <= $item['time_start']) {
                    $errors["items.{$i}.time_end"] = "Jam selesai harus lebih besar dari jam mulai.";
                }
            }
        }
        if (!empty($errors)) {
            return back()->withErrors($errors)->withInput();
        }

        $section = Section::where('key', 'event')->first();
        $oldItems = $section->content['items'] ?? [];
        $count = count($items);

        // Handle photo uploads for dynamic list
        for ($i = 0; $i < $count; $i++) {
            // Check for file in items array (nested)
            // Name in form: items[i][image_file]
            if ($request->hasFile("items.{$i}.image_file")) {
                $file = $request->file("items.{$i}.image_file");
                
                if (!$file->isValid()) continue;

                // Delete old image if exists at this index (only if it matches 'event' folder)
                if (isset($oldItems[$i]['image'])) {
                    $oldPath = public_path($oldItems[$i]['image']);
                    if (file_exists($oldPath) && strpos($oldItems[$i]['image'], '/assets/img/event/') !== false) {
                        @unlink($oldPath);
                    }
                }

                $filename = 'event-' . $i . '-' . time() . '.webp';
                $path = 'img/event/' . $filename;
                
                if (!file_exists(public_path('assets/img/event'))) {
                    mkdir(public_path('assets/img/event'), 0755, true);
                }

                $manager = new ImageManager(new Driver());
                $image = $manager->read($file->getRealPath());
                $image->cover(800, 600);
                $image->toWebp(80)->save(public_path('assets/' . $path));
                
                $items[$i]['image'] = '/assets/' . $path;
                
                // Remove the temporary file object from the items array to not save it in JSON
                unset($items[$i]['image_file']);
            } else {
                 // Ensure image key is preserved
            }
        }

        $section->update(['content' => [
            'tagline' => $request->tagline,
            'title' => $request->title,
            'items' => $items,
        ]]);

        return redirect()->back()->with('success', 'Section Agenda Kegiatan berhasil diperbarui!');
    }

    /**
     * Display Testimonial Section Editor
     */
    public function editTestimonial()
    {
        $section = Section::firstOrCreate(
            ['key' => 'testimonial'],
            [
                'title' => 'Testimoni',
                'content' => [
                    'tagline' => 'Testimoni',
                    'title' => 'Apa Kata <span>Mereka</span>',
                    'subtitle' => 'Cerita singkat dari orang tua, siswa, dan alumni tentang pengalaman belajar di MI Al-Hikmah.',
                    'items' => [
                        [
                            'name' => 'Ibu Rina',
                            'role' => 'Orang Tua/Wali',
                            'quote' => 'Guru-gurunya sangat peduli. Anak saya jadi semangat belajar, bacaan Qur\'annya juga jauh lebih baik.',
                            'rating' => 5,
                            'image' => '/assets/img/testimonial/01.jpg'
                        ],
                        [
                            'name' => 'Rafi',
                            'role' => 'Siswa',
                            'quote' => 'Belajarnya seru, ada literasi 15 menit tiap pagi dan banyak kegiatan pramuka serta olahraga.',
                            'rating' => 5,
                            'image' => '/assets/img/testimonial/02.jpg'
                        ],
                        [
                            'name' => 'Naila',
                            'role' => 'Alumni',
                            'quote' => 'Dasar literasi dan numerasi yang kuat di MI Al-Hikmah membantu saya cepat beradaptasi di SMP.',
                            'rating' => 5,
                            'image' => '/assets/img/testimonial/03.jpg'
                        ],
                        [
                            'name' => 'Pak Hendra',
                            'role' => 'Komite Sekolah',
                            'quote' => 'Komunikasi sekolah–orang tua sangat baik. Program tahfiz dan pembiasaan ibadah berjalan konsisten.',
                            'rating' => 5,
                            'image' => '/assets/img/testimonial/04.jpg'
                        ],
                        [
                            'name' => 'Ibu Desti',
                            'role' => 'Orang Tua/Wali',
                            'quote' => 'Sekolahnya ramah anak, ada UKS dan satgas anti-bullying. Kami merasa tenang menitipkan putra kami.',
                            'rating' => 5,
                            'image' => '/assets/img/testimonial/05.jpg'
                        ],
                    ]
                ]
            ]
        );

        return Inertia::render('Admin/Tampilan/Testimonial', [
            'section' => $section
        ]);
    }

    /**
     * Update Testimonial Section
     */
    public function updateTestimonial(Request $request)
    {
        $request->validate([
            'tagline' => 'required|string|max:100',
            'title' => 'required|string',
            'subtitle' => 'nullable|string|max:255',
            'items' => 'required|array',
            'items.*.name' => 'required|string|max:100',
            'items.*.role' => 'required|string|max:50',
            'items.*.quote' => 'required|string|max:300',
            'items.*.rating' => 'required|integer|min:1|max:5',
        ], [
            'tagline.required' => 'Tagline wajib diisi.',
            'title.required' => 'Judul utama wajib diisi.',
            'items.required' => 'Daftar testimoni tidak boleh kosong.',
            'items.*.name.required' => 'Nama wajib diisi.',
            'items.*.role.required' => 'Peran wajib diisi.',
            'items.*.quote.required' => 'Teks testimoni wajib diisi.',
            'items.*.rating.required' => 'Rating wajib diisi.',
            'items.*.rating.min' => 'Rating minimal 1 bintang.',
            'items.*.rating.max' => 'Rating maksimal 5 bintang.',
        ]);

        $section = Section::where('key', 'testimonial')->first();
        $items = $request->items;
        $oldItems = $section->content['items'] ?? [];
        $count = count($items);

        // Handle photo uploads for dynamic list
        for ($i = 0; $i < $count; $i++) {
            if ($request->hasFile("items.{$i}.image_file")) {
                $file = $request->file("items.{$i}.image_file");
                
                if (!$file->isValid()) continue;

                // Delete old image if exists
                if (isset($oldItems[$i]['image'])) {
                    $oldPath = public_path($oldItems[$i]['image']);
                    if (file_exists($oldPath) && strpos($oldItems[$i]['image'], '/assets/img/testimonial/') !== false) {
                        @unlink($oldPath);
                    }
                }

                $filename = 'testimonial-' . $i . '-' . time() . '.webp';
                $path = 'img/testimonial/' . $filename;
                
                if (!file_exists(public_path('assets/img/testimonial'))) {
                    mkdir(public_path('assets/img/testimonial'), 0755, true);
                }

                $manager = new ImageManager(new Driver());
                $image = $manager->read($file->getRealPath());
                $image->cover(150, 150); // Square photo for avatar
                $image->toWebp(80)->save(public_path('assets/' . $path));
                
                $items[$i]['image'] = '/assets/' . $path;
                unset($items[$i]['image_file']);
            }
        }

        $section->update(['content' => [
            'tagline' => $request->tagline,
            'title' => $request->title,
            'subtitle' => $request->subtitle,
            'items' => $items,
        ]]);

        return redirect()->back()->with('success', 'Section Testimoni berhasil diperbarui!');
    }

    /**
     * Display Partner Section Editor
     */
    public function editPartner()
    {
        $section = Section::firstOrCreate(
            ['key' => 'partner'],
            [
                'title' => 'Mitra',
                'content' => [
                    'items' => [
                        ['name' => 'Kementerian Agama', 'image' => 'https://upload.wikimedia.org/wikipedia/commons/9/9a/Kementerian_Agama_new_logo.png'],
                        ['name' => 'Kemendikbud', 'image' => 'https://upload.wikimedia.org/wikipedia/commons/thumb/9/9c/Logo_of_Ministry_of_Education_and_Culture_of_Republic_of_Indonesia.svg/1200px-Logo_of_Ministry_of_Education_and_Culture_of_Republic_of_Indonesia.svg.png'],
                        ['name' => 'RDM Kemenag', 'image' => 'https://blogger.googleusercontent.com/img/b/R29vZ2xl/AVvXsEjsiEi0VHQWUa-4k5SRiCP7Gw_9rQ97Qzcz2FfTleLkYo1-hvWVpLgf78tmi2gZAQfDW0ip_pe-Pda_xrG1yRJWOLOBnMF8t49yyYwI_F0gj5nWgjCMA3yl6_F8trjDkpcbt0loDmW8tqHIRJeFGXy1yZrV-e4PUaRoErmpZXxiUyzhSTiN6JnI0GUvVIk/s1600/rdmkemenag.id.png'],
                        ['name' => 'EMIS', 'image' => 'https://blogger.googleusercontent.com/img/b/R29vZ2xl/AVvXsEh734d0l2uQHOloBZvvEJrzNxOlVy2xtQAe2e1UUeyAOymJEqNTztdB1wjOoeJUuOMalaj2ebRRmWNLflCpidw5VBAn5JIeb93xO84xlB6kgHf_cBwdxwhlr1DSE_jHkeRgDXZXjamGJNWNBe80YpQMTCItxINQyVcrEX7_gkQiveBkSHJ1wb0J4r2_QHY/s744/emis-logo.55eeefa2.png'],
                        ['name' => 'Persis', 'image' => 'https://upload.wikimedia.org/wikipedia/commons/thumb/6/64/Logo_Persis.png/1024px-Logo_Persis.png'],
                    ]
                ]
            ]
        );

        return Inertia::render('Admin/Tampilan/Partner', [
            'section' => $section
        ]);
    }

    /**
     * Update Partner Section
     */
    public function updatePartner(Request $request)
    {
        $request->validate([
            'items' => 'required|array',
            'items.*.name' => 'required|string|max:100',
        ], [
            'items.required' => 'Daftar mitra tidak boleh kosong.',
            'items.*.name.required' => 'Nama mitra wajib diisi.',
        ]);

        $section = Section::where('key', 'partner')->first();
        $items = $request->items;
        $oldItems = $section->content['items'] ?? [];
        $count = count($items);

        // Handle photo uploads for dynamic list
        for ($i = 0; $i < $count; $i++) {
            if ($request->hasFile("items.{$i}.image_file")) {
                $file = $request->file("items.{$i}.image_file");
                
                if (!$file->isValid()) continue;

                // Delete old image if exists (only local files)
                if (isset($oldItems[$i]['image']) && strpos($oldItems[$i]['image'], '/assets/img/partner/') !== false) {
                    $oldPath = public_path($oldItems[$i]['image']);
                    if (file_exists($oldPath)) {
                        @unlink($oldPath);
                    }
                }

                $filename = 'partner-' . $i . '-' . time() . '.webp';
                $path = 'img/partner/' . $filename;
                
                if (!file_exists(public_path('assets/img/partner'))) {
                    mkdir(public_path('assets/img/partner'), 0755, true);
                }

                $manager = new ImageManager(new Driver());
                $image = $manager->read($file->getRealPath());
                $image->scaleDown(height: 80); // Scale to max height 80px
                $image->toWebp(90)->save(public_path('assets/' . $path));
                
                $items[$i]['image'] = '/assets/' . $path;
                unset($items[$i]['image_file']);
            }
        }

        $section->update(['content' => [
            'items' => $items,
        ]]);

        return redirect()->back()->with('success', 'Section Mitra berhasil diperbarui!');
    }

    /**
     * Display Footer Links Editor
     */
    public function editFooterLinks()
    {
        $section = Section::firstOrCreate(
            ['key' => 'footer_links'],
            [
                'title' => 'Footer Links',
                'content' => [
                    'quick_links_title' => 'Tautan Cepat',
                    'quick_links' => [
                        ['label' => 'Beranda', 'url' => '/'],
                        ['label' => 'Tentang', 'url' => '/profil'],
                        ['label' => 'Akademik', 'url' => '/akademik/kurikulum'],
                        ['label' => 'Program', 'url' => '/program'],
                        ['label' => 'PPDB', 'url' => '/ppdb'],
                        ['label' => 'Berita', 'url' => '/berita'],
                        ['label' => 'Galeri', 'url' => '/galeri'],
                        ['label' => 'Kontak', 'url' => '/kontak'],
                    ],
                    'academic_links_title' => 'Akademik & Layanan',
                    'academic_links' => [
                        ['label' => 'Kalender Akademik', 'url' => '/akademik/kalender'],
                        ['label' => 'Tahfiz & BTQ', 'url' => '/program/tahfiz'],
                        ['label' => 'Literasi 15 Menit', 'url' => '/program/literasi'],
                        ['label' => 'Klinik Numerasi', 'url' => '/program/numerasi'],
                        ['label' => 'Ekstrakurikuler', 'url' => '/kesiswaan/ekskul'],
                        ['label' => 'Unduhan', 'url' => '/unduhan'],
                        ['label' => 'Satgas Anti-Bullying', 'url' => '/anti-bullying'],
                        ['label' => 'Survei Kepuasan (SKM)', 'url' => '/skm'],
                    ]
                ]
            ]
        );

        return Inertia::render('Admin/Tampilan/FooterLinks', [
            'section' => $section
        ]);
    }

    /**
     * Update Footer Links Section
     */
    public function updateFooterLinks(Request $request)
    {
        $request->validate([
            'quick_links_title' => 'required|string|max:50',
            'quick_links' => 'required|array|min:1',
            'quick_links.*.label' => 'required|string|max:100',
            'quick_links.*.url' => 'required|string|max:255',
            'academic_links_title' => 'required|string|max:50',
            'academic_links' => 'required|array|min:1',
            'academic_links.*.label' => 'required|string|max:100',
            'academic_links.*.url' => 'required|string|max:255',
        ], [
            'quick_links_title.required' => 'Judul kolom 1 wajib diisi.',
            'quick_links.required' => 'Tautan kolom 1 tidak boleh kosong.',
            'quick_links.*.label.required' => 'Label tautan wajib diisi.',
            'quick_links.*.url.required' => 'URL tautan wajib diisi.',
            'academic_links_title.required' => 'Judul kolom 2 wajib diisi.',
            'academic_links.required' => 'Tautan kolom 2 tidak boleh kosong.',
            'academic_links.*.label.required' => 'Label tautan wajib diisi.',
            'academic_links.*.url.required' => 'URL tautan wajib diisi.',
        ]);

        $section = Section::where('key', 'footer_links')->first();

        $section->update(['content' => [
            'quick_links_title' => $request->quick_links_title,
            'quick_links' => $request->quick_links,
            'academic_links_title' => $request->academic_links_title,
            'academic_links' => $request->academic_links,
        ]]);

        return redirect()->back()->with('success', 'Footer Links berhasil diperbarui!');
    }
}

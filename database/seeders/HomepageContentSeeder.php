<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Section;

class HomepageContentSeeder extends Seeder
{
    public function run()
    {
        // 1. Hero / Slider
        $hero = Section::firstOrCreate(['key' => 'hero']);
        $hero->content = [
            'slide_1_top_title' => 'Selamat Datang',
            'slide_1_title' => 'Penerimaan Siswa Baru',
            'slide_1_subtitle' => 'Bergabunglah bersama kami untuk masa depan cerah.',
            // No image: fallback to default
            'slide_2_top_title' => 'Program Unggulan',
            'slide_2_title' => 'Tahfidz Al-Quran',
            'slide_2_subtitle' => 'Mencetak generasi penghafal Quran yang berakhlak mulia.',
            'slide_3_top_title' => 'Fasilitas',
            'slide_3_title' => 'Lingkungan Asri',
            'slide_3_subtitle' => 'Suasana belajar yang nyaman dan kondusif.'
        ];
        $hero->save();

        // 2. Feature / Keunggulan
        $feature = Section::firstOrCreate(['key' => 'feature']);
        $feature->content = [
            'feature_1_title' => 'Pendidikan Islami',
            'feature_1_desc' => 'Kurikulum berbasis Islam yang kuat dan berkarakter.',
            'feature_1_icon' => 'fas fa-mosque',

            'feature_2_title' => 'Guru Profesional',
            'feature_2_desc' => 'Dididik oleh tenaga pengajar berpengalaman dan tersertifikasi.',
            'feature_2_icon' => 'fas fa-chalkboard-teacher',

            'feature_3_title' => 'Fasilitas Lengkap',
            'feature_3_desc' => 'Lab Komputer, IPA, Perpustakaan, dan Masjid yang luas.',
            'feature_3_icon' => 'fas fa-building',

            'feature_4_title' => 'Ekstrakurikuler',
            'feature_4_desc' => 'Pengembangan bakat dan minat siswa di bidang olahraga dan seni.',
            'feature_4_icon' => 'fas fa-futbol'
        ];
        $feature->save();

        // 3. About / Tentang Kami
        $about = Section::firstOrCreate(['key' => 'about']);
        $about->content = [
            'title' => 'Sistem Pendidikan Madrasah Memberdayakan Potensi Sejak Dini',
            'content' => 'MI Al-Hikmah berkomitmen menghadirkan pendidikan dasar berkualitas yang menumbuhkan akhlak mulia, serta menguatkan literasi dan numerasi sejak dini. Berlandaskan nilai-nilai Islam dan karakter Pancasila.',
            'years' => 17,
            'years_text' => 'Tahun Pengalaman',
             // Images (null = use default theme assets)
            'image' => null,
            'image_2' => null,
            'image_3' => null,
            
            'item_1_title' => 'Tahfiz & BTQ Terstruktur',
            'item_1_desc' => 'Tahsin bacaan, target juz 30 bertahap, setoran rutin, dan pendampingan harian.',
            'item_1_icon' => 'fas fa-quran',

            'item_2_title' => 'Literasi & Klinik Numerasi',
            'item_2_desc' => 'Membaca 15 menit setiap pagi dan pendampingan berhitung untuk fondasi akademik kuat.',
            'item_2_icon' => 'fas fa-calculator', // OR book-open

            'quote_text' => 'Kami membangun lingkungan belajar yang aman, hangat, dan mendorong kemandirian serta rasa ingin tahu anak.',
            'phone' => '+62 812-3456-7890'
        ];
        $about->save();

        // 4. Video Profil
        $video = Section::firstOrCreate(['key' => 'video']);
        $video->content = [
            'video_title' => 'Profil <span>MI Al-Hikmah</span>',
            'video_desc' => 'Sekilas tentang MI Al-Hikmah: suasana belajar, pembiasaan ibadah, literasi & numerasi, serta fasilitas sekolah.',
            'video_btn_text' => 'Profil Madrasah',
            'video_btn_link' => '/profil',
            'video_url' => 'https://www.youtube.com/watch?v=VIDEO_ID_PROFIL',
            'video_poster' => null // Use default
        ];
        $video->save();

        // 5. Program & Layanan
        $program = Section::firstOrCreate(['key' => 'program']);
        $program->content = [
            'title' => 'Jelajahi <span>Program & Layanan</span> Kami',
            'subtitle' => 'Program MI',
            'desc' => 'Program pembelajaran dan layanan siswa di MI Al-Hikmah yang menumbuhkan iman, ilmu, karakter, literasi, dan numerasi dalam lingkungan yang ramah anak.',
            
            // 1. Kurikulum
            'prog_1_title' => 'Kurikulum & Pembelajaran',
            'prog_1_desc' => 'Kurikulum Merdeka/K13, jadwal pelajaran, penilaian, pengayaan & remedial.',
            'prog_1_link' => '/akademik/kurikulum',
            'prog_1_icon' => 'fas fa-desktop',

            // 2. Tahfiz
            'prog_2_title' => 'Tahfiz & BTQ',
            'prog_2_desc' => 'Tahsin bacaan, target juz 30, setoran berkala, dan pembiasaan ibadah harian.',
            'prog_2_link' => '/program/tahfiz',
            'prog_2_icon' => 'fas fa-quran',

            // 3. Literasi
            'prog_3_title' => 'Literasi 15 Menit & Perpustakaan',
            'prog_3_desc' => 'Membaca harian, pojok baca kelas, peminjaman buku, dan lomba literasi.',
            'prog_3_link' => '/program/literasi',
            'prog_3_icon' => 'fas fa-book-open',
            
            // 4. Numerasi
            'prog_4_title' => 'Klinik Numerasi & Sains Dasar',
            'prog_4_desc' => 'Pendampingan berhitung, permainan numerasi, dan percobaan sains sederhana.',
            'prog_4_link' => '/program/numerasi',
            'prog_4_icon' => 'fas fa-calculator',

            // 5. Kesiswaan
            'prog_5_title' => 'Kesiswaan & Ekstrakurikuler',
            'prog_5_desc' => 'Pramuka, olahraga, seni, MTQ, drumband, dan kegiatan penguatan karakter.',
            'prog_5_link' => '/kesiswaan/ekskul',
            'prog_5_icon' => 'fas fa-futbol',

            // 6. Layanan
            'prog_6_title' => 'Layanan Siswa (UKS & Anti-Bullying)',
            'prog_6_desc' => 'UKS, edukasi PHBS, Satgas Anti-Bullying, dan alur pengaduan yang aman.',
            'prog_6_link' => '/layanan/anak',
            'prog_6_icon' => 'fas fa-heart',
        ];
        $program->save();

        // 6. Team / Guru
        $team = Section::firstOrCreate(['key' => 'team']);
        $team->content = [
            // Team 1
            'team_1_name' => 'Nama Kepala Madrasah',
            'team_1_job' => 'Kepala Madrasah',
            // 'team_1_img' => null, // Use default

            // Team 2
            'team_2_name' => 'Nama Koordinator',
            'team_2_job' => 'Koordinator Kurikulum',
            // 'team_2_img' => null,

            // Team 3
            'team_3_name' => 'Nama Guru',
            'team_3_job' => 'Guru Kelas I',
            // 'team_3_img' => null,

            // Team 4
            'team_4_name' => 'Nama Staf',
            'team_4_job' => 'Operator & Tata Usaha',
            // 'team_4_img' => null,
        ];
        $team->save();

        // 7. Choose / Kenapa Memilih
        $choose = Section::firstOrCreate(['key' => 'choose']);
        $choose->content = [
            'title' => 'Pendidikan Dasar Berkualitas & <span>Ramah Anak</span>',
            'subtitle' => 'Mengapa Memilih Kami',
            'desc' => 'MI Al-Hikmah berkomitmen menghadirkan pembelajaran yang menumbuhkan iman, ilmu, dan karakter. Program terstruktur, guru kompeten, serta lingkungan aman–nyaman mendukung tumbuh kembang siswa setiap hari.',
            'image' => null, // Use default

            // Item 1
            'item_1_title' => 'Guru Peduli & Kompeten',
            'item_1_desc' => 'Pembelajaran aktif, pendampingan personal, dan evaluasi berkelanjutan di setiap kelas.',
            'item_1_icon' => 'fas fa-chalkboard-teacher', // SVG by default, but this is fallback

            // Item 2
            'item_2_title' => 'Tahfiz & BTQ Terstruktur',
            'item_2_desc' => 'Tahsin bacaan, target juz 30 bertahap, setoran rutin, dan pembiasaan ibadah harian.',
            'item_2_icon' => 'fas fa-book-open',

            // Item 3
            'item_3_title' => 'Literasi & Klinik Numerasi',
            'item_3_desc' => 'Membaca 15 menit setiap hari dan pendampingan berhitung untuk menguatkan dasar akademik.',
            'item_3_icon' => 'fas fa-calculator',

            // Item 4
            'item_4_title' => 'Sekolah Ramah Anak',
            'item_4_desc' => 'Satgas Anti-Bullying, UKS, dan komunikasi orang tua–sekolah yang responsif & bersahabat.',
            'item_4_icon' => 'fas fa-heart',
        ];
        $choose->save();

        // 8. Event / Agenda
        $event = Section::firstOrCreate(
            ['key' => 'event'],
            ['title' => 'Agenda & Kegiatan']
        );
        $event->content = [
            // Event 1
            'event_1_title' => 'Sosialisasi PPDB 2026',
            'event_1_date' => '12 Okt 2025',
            'event_1_time' => '08.00–10.30 WIB',
            'event_1_loc' => 'Aula MI Al-Hikmah',
            'event_1_desc' => 'Pemaparan syarat, alur pendaftaran, kuota, dan biaya pendidikan kepada orang tua/wali.',
            // image fallback

            // Event 2
            'event_2_title' => 'Launching Pojok Baca & Literasi',
            'event_2_date' => '04 Okt 2025',
            'event_2_time' => '09.00–11.00 WIB',
            'event_2_loc' => 'Perpustakaan',
            'event_2_desc' => 'Gerakan membaca harian dan peminjaman buku untuk memperkuat budaya literasi siswa.',
            // image fallback

            // Event 3
            'event_3_title' => 'Setoran Tahfiz Akbar & Khataman',
            'event_3_date' => '28 Sep 2025',
            'event_3_time' => '07.30–09.30 WIB',
            'event_3_loc' => 'Masjid Sekolah',
            'event_3_desc' => 'Evaluasi capaian juz 30, muroja\'ah bersama, dan apresiasi untuk peserta terbaik.',
            // image fallback

            // Event 4
            'event_4_title' => 'Perkemahan & Pelantikan Pramuka',
            'event_4_date' => '21 Sep 2025',
            'event_4_time' => '13.00–17.00 WIB',
            'event_4_loc' => 'Lapangan MI',
            'event_4_desc' => 'Penguatan kemandirian, kerja sama, dan disiplin melalui aktivitas kepramukaan.',
            // image fallback
        ];
        $event->save();

        // 9. Portfolio / Kegiatan
        $portfolio = Section::firstOrCreate(
            ['key' => 'portfolio'],
            ['title' => 'Kegiatan di MI Al-Hikmah']
        );
        $portfolio->content = [
            'title' => 'Kegiatan di <span>MI Al-Hikmah</span>',
            'subtitle' => 'Kehidupan di MI',

            // Item 1
            'port_1_title' => 'Budaya Literasi 15 Menit',
            'port_1_cat' => '// Literasi',
            'port_1_link' => '/program/literasi',
            // image fallback stRe.jpg

            // Item 2
            'port_2_title' => 'Tahfiz & BTQ',
            'port_2_cat' => '// Keagamaan',
            'port_2_link' => '/program/tahfiz',
            // image fallback aka465.jpg

            // Item 3
            'port_3_title' => 'Olahraga & Pramuka',
            'port_3_cat' => '// Ekskul',
            'port_3_link' => '/kesiswaan/ekskul',
            // image fallback r456.jpg

            // Item 4
            'port_4_title' => 'Sekolah Ramah Anak',
            'port_4_cat' => '// Ramah Anak',
            'port_4_link' => '/anti-bullying',
            // image fallback ra123k.jpg

            // Item 5
            'port_5_title' => 'Pembiasaan Ibadah Harian',
            'port_5_cat' => '// Keagamaan',
            'port_5_link' => '/program/keagamaan',
            // image fallback REMISE UEA (29).jpg

            // Item 6
            'port_6_title' => 'Fasilitas Sekolah',
            'port_6_cat' => '// Sarpras',
            'port_6_link' => '/sarpras',
            // image fallback IMG_1135 -BG (1).jpg
        ];
        $portfolio->save();

        // 10. Testimonial
        $testimonial = Section::firstOrCreate(
            ['key' => 'testimonial'],
            ['title' => 'Testimoni (Apa Kata Mereka)']
        );
        $testimonial->content = [
            'title' => 'Apa Kata <span>Mereka</span>',
            'subtitle' => 'Testimoni',
            'desc' => 'Cerita singkat dari orang tua, siswa, dan alumni tentang pengalaman belajar di MI Al-Hikmah.',

            // Item 1
            'testi_1_name' => 'Ibu Rina',
            'testi_1_role' => 'Orang Tua/Wali',
            'testi_1_text' => 'Guru-gurunya sangat peduli. Anak saya jadi semangat belajar, bacaan Qur\'anannya juga jauh lebih baik.',
            // image fallback 01.jpg

            // Item 2
            'testi_2_name' => 'Rafi',
            'testi_2_role' => 'Siswa',
            'testi_2_text' => 'Belajarnya seru, ada literasi 15 menit tiap pagi dan banyak kegiatan pramuka serta olahraga.',
            // image fallback 02.jpg

            // Item 3
            'testi_3_name' => 'Naila',
            'testi_3_role' => 'Alumni',
            'testi_3_text' => 'Dasar literasi dan numerasi yang kuat di MI Al-Hikmah membantu saya cepat beradaptasi di SMP.',
            // image fallback 03.jpg

            // Item 4
            'testi_4_name' => 'Pak Hendra',
            'testi_4_role' => 'Komite Sekolah',
            'testi_4_text' => 'Komunikasi sekolah–orang tua sangat baik. Program tahfiz dan pembiasaan ibadah berjalan konsisten.',
            // image fallback 04.jpg

            // Item 5
            'testi_5_name' => 'Ibu Desti',
            'testi_5_role' => 'Orang Tua/Wali',
            'testi_5_text' => 'Sekolahnya ramah anak, ada UKS dan satgas anti-bullying. Kami merasa tenang menitipkan putra kami.',
            // image fallback 05.jpg
        ];
        $testimonial->save();

         $this->command->info('Homepage Content Seeded Successfully!');
    }
}

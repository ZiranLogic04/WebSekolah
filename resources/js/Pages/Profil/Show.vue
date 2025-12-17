<template>
    <BerandaLayout>
        <!-- Breadcrumb Area -->
        <div class="breadcrumb-area" style="background-image: url(/assets/img/hope/breadcrumb-01.jpg);">
            <div class="container">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="breadcrumb-content">
                            <h2 class="title">{{ title }}</h2>
                            <ul class="breadcrumb-list">
                                <li><Link href="/">Beranda</Link></li>
                                <li class="active">{{ title }}</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Breadcrumb Area End -->

        <!-- Content Area -->
        <div class="py-120">
            <div class="container">
                
                <!-- Page: Visi & Misi -->
                <div v-if="slug === 'visi-misi'" class="visi-misi-wrapper">
                    <div class="row">
                        <div class="col-lg-8 mx-auto">
                            <div class="site-heading text-center">
                                <span class="site-title-tagline"><i class="fa fa-book-open-reader"></i> Visi & Misi</span>
                                <h2 class="site-title">Visi & <span>Misi</span> MI Al-Hikmah</h2>
                            </div>
                        </div>
                    </div>
                    <div class="row g-4">
                        <div class="col-md-12 mb-4 text-center">
                            <div class="p-4 bg-white rounded-3 shadow-sm">
                                <h5 class="mb-3">Visi</h5>
                                <p class="fs-5 fst-italic text-dark border-start border-5 border-success ps-4 text-start">
                                    "{{ content?.visi || 'Belum ada data Visi.' }}"
                                </p>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="p-4 bg-white rounded-3 shadow-sm">
                                <h5 class="mb-3 text-center">Misi</h5>
                                <div class="ck-content" v-html="content?.misi || '<p class=\'text-center\'>Belum ada data Misi.</p>'"></div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Page: Sejarah -->
                <div v-else-if="slug === 'sejarah'">
                    <div class="row">
                        <div class="col-lg-8 mx-auto">
                            <div class="site-heading text-center">
                                <span class="site-title-tagline"><i class="fa fa-book-open-reader"></i> Sejarah</span>
                                <h2 class="site-title">Sejarah <span>MI Al-Hikmah</span></h2>
                            </div>
                        </div>
                    </div>
                    <div class="row justify-content-center">
                        <div class="col-lg-10">
                            <div class="p-4 bg-white rounded-3 shadow-sm">
                                <img v-if="content?.image" :src="`/storage/${content.image}`" class="img-fluid rounded mb-4 w-100" style="max-height: 400px; object-fit: cover;" alt="Sejarah">
                                <div class="ck-content" v-html="content?.content || 'Belum ada konten sejarah.'"></div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Page: Sambutan / Kepala Madrasah -->
                <div v-else-if="slug === 'sambutan' || slug === 'kepala-madrasah'">
                    <div class="row">
                        <div class="col-lg-8 mx-auto">
                            <div class="site-heading text-center">
                                <span class="site-title-tagline"><i class="fa fa-user-tie"></i> Kepala Madrasah</span>
                                <h2 class="site-title">Sambutan <span>Kepala Madrasah</span></h2>
                            </div>
                        </div>
                    </div>
                    <div class="row g-4 align-items-start">
                        <div class="col-lg-4">
                            <div class="p-4 bg-white rounded-3 shadow-sm text-center">
                                <img v-if="content?.kepsek_image" :src="`/storage/${content.kepsek_image}`" class="img-fluid rounded shadow mb-3" alt="Kepala Madrasah">
                                <h5 class="fw-bold mb-0">{{ content?.kepsek_name || 'Kepala Madrasah' }}</h5>
                                <span class="text-muted small">Kepala Madrasah</span>
                            </div>
                        </div>
                        <div class="col-lg-8">
                            <div class="p-4 bg-white rounded-3 shadow-sm">
                                <div class="ck-content" v-html="content?.content || 'Belum ada sambutan.'"></div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Page: Struktur Organisasi -->
                <div v-else-if="slug === 'struktur-organisasi'">
                    <div class="row">
                        <div class="col-lg-8 mx-auto">
                            <div class="site-heading text-center">
                                <span class="site-title-tagline"><i class="fa fa-sitemap"></i> Organisasi</span>
                                <h2 class="site-title">Struktur <span>Organisasi</span></h2>
                            </div>
                        </div>
                    </div>
                    <div class="row justify-content-center">
                        <div class="col-lg-10">
                            <div class="p-4 bg-white rounded-3 shadow-sm text-center">
                                <img v-if="content?.image" :src="`/storage/${content.image}`" class="img-fluid" alt="Struktur Organisasi">
                                <p v-else class="text-muted py-5">Gambar bagan struktur organisasi belum diupload.</p>
                                <p v-if="content?.description" class="mt-4 text-muted">{{ content.description }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Page: Sarana & Prasarana -->
                <div v-else-if="slug === 'sarana-prasarana'">
                    <div class="row">
                        <div class="col-lg-8 mx-auto">
                            <div class="site-heading text-center">
                                <span class="site-title-tagline"><i class="fa fa-book-open-reader"></i> Sarana & Prasarana</span>
                                <h2 class="site-title">Fasilitas <span>MI Al-Hikmah</span></h2>
                                <p>Ringkasan fasilitas pembelajaran dan penunjang di MI Al-Hikmah untuk mendukung proses belajar yang aman, nyaman, dan bermakna.</p>
                            </div>
                        </div>
                    </div>
                    <!-- Static Facility Cards -->
                    <div class="row g-4">
                        <div class="col-sm-6 col-lg-4" v-for="(facility, index) in facilities" :key="index">
                            <div class="p-4 h-100 rounded-3 shadow-sm bg-white">
                                <div class="d-flex align-items-center mb-3">
                                    <div class="icon-circle me-3" style="width:50px; height:50px; display:flex; align-items:center; justify-content:center; background:#e0f2f1; border-radius:50%; flex-shrink:0;">
                                        <i :class="facility.icon" class="text-success fs-4"></i>
                                    </div>
                                    <h5 class="m-0">{{ facility.title }}</h5>
                                </div>
                                <p class="mb-0">{{ facility.desc }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Page: Tujuan -->
                <div v-else-if="slug === 'tujuan'">
                    <div class="row">
                        <div class="col-lg-8 mx-auto">
                            <div class="site-heading text-center">
                                <span class="site-title-tagline"><i class="fa fa-book-open-reader"></i> Tujuan</span>
                                <h2 class="site-title">Tujuan <span>Madrasah</span></h2>
                                <p>Landasan arah pengembangan MI Al-Hikmah untuk menumbuhkan iman, ilmu, dan akhlak mulia.</p>
                            </div>
                        </div>
                    </div>
                    <div class="row g-4 align-items-start">
                        <div class="col-lg-7">
                            <div class="p-4 rounded-3 shadow-sm bg-white">
                                <ol class="list-unstyled m-0" style="line-height:1.9;">
                                    <li v-for="(tujuan, i) in tujuanList" :key="i">
                                        <i class="far fa-check-circle me-2 text-success"></i>{{ tujuan }}
                                    </li>
                                </ol>
                            </div>
                        </div>
                        <div class="col-lg-5">
                            <div class="p-4 rounded-3 shadow-sm bg-white h-100">
                                <h5 class="mb-3">Program yang Mendukung</h5>
                                <ul class="m-0" style="line-height:1.9;">
                                    <li>Tahfidz & Tadarus Harian</li>
                                    <li>Gerakan Literasi Madrasah (GLM)</li>
                                    <li>Green & Clean School (GCC)</li>
                                    <li>Ekstrakurikuler: Pramuka, Kaligrafi, Hadroh, KIR, English Club, olahraga</li>
                                </ul>
                                <a href="/program" class="theme-btn mt-3">Lihat Program<i class="fas fa-arrow-right-long ms-2"></i></a>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Page: Kurikulum & Mapel -->
                <div v-else-if="slug === 'kurikulum-mapel'">
                    <div class="row">
                        <div class="col-lg-8 mx-auto">
                            <div class="site-heading text-center">
                                <span class="site-title-tagline"><i class="fa fa-book-open-reader"></i> Kurikulum</span>
                                <h2 class="site-title">Kurikulum & <span>Mata Pelajaran</span></h2>
                                <p>Struktur pembelajaran MI Al-Hikmah yang religius, ramah anak, dan menumbuhkan literasi–numerasi.</p>
                            </div>
                        </div>
                    </div>
                    <div class="row g-4">
                        <div class="col-lg-6">
                            <div class="p-4 rounded-3 shadow-sm bg-white h-100">
                                <h5 class="mb-3"><i class="far fa-mosque me-2"></i> Kelompok Keagamaan</h5>
                                <ul class="m-0" style="columns:2; column-gap:24px; line-height:1.9;">
                                    <li v-for="m in mapelKeagamaan" :key="m">{{ m }}</li>
                                </ul>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="p-4 rounded-3 shadow-sm bg-white h-100">
                                <h5 class="mb-3"><i class="far fa-books me-2"></i> Kelompok Umum & Keterampilan</h5>
                                <ul class="m-0" style="columns:2; column-gap:24px; line-height:1.9;">
                                    <li v-for="m in mapelUmum" :key="m">{{ m }}</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Page: Direktori Guru -->
                <div v-else-if="slug === 'direktori-guru'">
                    <div class="row">
                        <div class="col-lg-8 mx-auto">
                            <div class="site-heading text-center">
                                <span class="site-title-tagline"><i class="fa fa-chalkboard-teacher"></i> GTK</span>
                                <h2 class="site-title">Direktori <span>Guru & Tendik</span></h2>
                                <p>Temukan guru dan tenaga kependidikan MI Al-Hikmah beserta bidang ajarnya.</p>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 col-lg-3" v-for="guru in gurus" :key="guru.id">
                            <div class="team-item mb-4">
                                <div class="team-img">
                                    <img :src="guru.foto ? `/storage/${guru.foto}` : '/assets/img/team/placeholder.jpg'" :alt="guru.nama">
                                </div>
                                <div class="team-content">
                                    <div class="team-bio">
                                        <h5>{{ guru.nama }}</h5>
                                        <span>{{ guru.jabatan || 'Guru' }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div v-if="!gurus || gurus.length === 0" class="col-12">
                            <div class="alert alert-info">Data guru belum tersedia.</div>
                        </div>
                    </div>
                </div>

                <!-- Page: Syarat & Alur PPDB -->
                <div v-else-if="slug === 'syarat-dan-alur'">
                    <div class="row">
                        <div class="col-lg-8 mx-auto">
                            <div class="site-heading text-center">
                                <span class="site-title-tagline"><i class="fa fa-user-plus"></i> PPDB</span>
                                <h2 class="site-title">Syarat & <span>Alur Pendaftaran</span></h2>
                                <p>Informasi resmi penerimaan peserta didik baru MI Al-Hikmah: persyaratan, tahapan pendaftaran, jadwal, dan kontak panitia.</p>
                            </div>
                        </div>
                    </div>
                    <div class="row g-4 align-items-start">
                        <div class="col-lg-8">
                            <!-- Syarat -->
                            <div class="p-4 rounded-3 shadow-sm bg-white mb-3">
                                <h5 class="mb-3"><i class="fa fa-clipboard-check me-2 text-success"></i> Syarat Pendaftaran</h5>
                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <h6 class="mb-2">Administratif</h6>
                                        <ul class="m-0" style="line-height:1.9;">
                                            <li>Fotokopi <strong>Akte Kelahiran</strong> (1 lembar)</li>
                                            <li>Fotokopi <strong>Kartu Keluarga</strong> (1 lembar)</li>
                                            <li>Fotokopi <strong>KTP Orang Tua/Wali</strong> (1 lembar)</li>
                                            <li>Pas foto terbaru <strong>3×4</strong> (2 lembar)</li>
                                        </ul>
                                    </div>
                                    <div class="col-md-6">
                                        <h6 class="mb-2">Ketentuan Usia</h6>
                                        <ul class="m-0" style="line-height:1.9;">
                                            <li>Usia minimal <strong>6 tahun</strong> per <strong>1 Juli</strong> tahun berjalan</li>
                                            <li>Mengikuti observasi ringan kesiapan belajar</li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                            <!-- Alur -->
                            <div class="p-4 rounded-3 shadow-sm bg-white">
                                <h5 class="mb-3"><i class="fa fa-route me-2 text-success"></i> Alur Pendaftaran</h5>
                                <ol class="ppdb-steps list-unstyled m-0">
                                    <li class="step" v-for="(step, i) in ppdbSteps" :key="i">
                                        <div class="step-num">{{ i + 1 }}</div>
                                        <div class="step-body">
                                            <h6 class="mb-1">{{ step.title }}</h6>
                                            <p class="mb-0">{{ step.desc }}</p>
                                        </div>
                                    </li>
                                </ol>
                            </div>
                        </div>
                        <div class="col-lg-4">
                            <div class="p-4 rounded-3 shadow-sm bg-white position-sticky" style="top: 90px;">
                                <h5 class="mb-3">Info & Kontak Panitia</h5>
                                <ul class="list-unstyled mb-3" style="line-height:1.9;">
                                    <li><i class="fa fa-location-dot me-2 text-success"></i>Kp. Cihaneut RT 01/RW 11, Desa Drawati, Paseh, Bandung</li>
                                    <li><i class="far fa-envelope me-2 text-success"></i><a href="mailto:mialhikmahpaseh@gmail.com">mialhikmahpaseh@gmail.com</a></li>
                                    <li><i class="fab fa-whatsapp me-2 text-success"></i><a href="https://wa.me/6281214447944" target="_blank">0812-1444-7944</a></li>
                                </ul>
                                <a href="/ppdb/daftar" class="theme-btn d-block text-center">Daftar Online<i class="fas fa-arrow-right-long ms-2"></i></a>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Page: Jadwal & Kuota PPDB -->
                <div v-else-if="slug === 'jadwal-dan-kuota'">
                    <div class="row">
                        <div class="col-lg-9 mx-auto">
                            <div class="site-heading text-center">
                                <span class="site-title-tagline"><i class="fa fa-calendar-check"></i> PPDB</span>
                                <h2 class="site-title">Jadwal & <span>Kuota</span> PPDB</h2>
                                <p>Informasi resmi penerimaan peserta didik baru MI Al-Hikmah.</p>
                            </div>
                        </div>
                    </div>
                    <div class="row g-4 mb-4">
                        <div class="col-sm-6 col-lg-4" v-for="(j, i) in jadwalPpdb" :key="i">
                            <div class="p-4 rounded-3 shadow-sm bg-white h-100">
                                <div class="d-flex align-items-center gap-2 mb-1">
                                    <i class="fa fa-flag-checkered text-success"></i>
                                    <h6 class="m-0">{{ j.stage }}</h6>
                                </div>
                                <div class="small text-muted mb-2"><i class="fa fa-calendar me-1"></i>{{ j.range }}</div>
                                <p class="mb-0">{{ j.desc }}</p>
                            </div>
                        </div>
                    </div>
                    <div class="text-center mt-4">
                        <a href="/ppdb/daftar" class="theme-btn">Daftar Sekarang<i class="fas fa-arrow-right-long ms-2"></i></a>
                        <a href="/profil/syarat-dan-alur" class="theme-btn theme-btn2 ms-2">Lihat Syarat<i class="fas fa-arrow-right-long ms-2"></i></a>
                    </div>
                </div>

                <!-- Default Fallback -->
                <div v-else>
                    <div class="alert alert-info">Halaman tidak ditemukan kontennya.</div>
                </div>

            </div>
        </div>

    </BerandaLayout>
</template>

<script setup>
import { Link } from '@inertiajs/vue3';
import BerandaLayout from '@/Layouts/BerandaLayout.vue';

const props = defineProps({
    slug: String,
    title: String,
    content: Object,
    gurus: Array
});

// Static data for Sarana Prasarana
const facilities = [
    { icon: 'fas fa-chalkboard-teacher', title: 'Ruang Kelas', desc: 'Tersedia 6 ruang kelas yang nyaman dan terang.' },
    { icon: 'fas fa-book-reader', title: 'Perpustakaan & Pojok Baca', desc: 'Koleksi bacaan siswa & pojok baca di tiap kelas.' },
    { icon: 'fas fa-user-tie', title: 'Ruang Guru & Kepala', desc: 'Ruang kerja guru, kepala, serta TU/Operator.' },
    { icon: 'fas fa-first-aid', title: 'UKS & Keamanan Anak', desc: 'Layanan kesehatan dasar & Satgas Anti-Bullying.' },
    { icon: 'fas fa-desktop', title: 'Perangkat TIK', desc: 'Perangkat presentasi & akses TIK terjadwal.' },
    { icon: 'fas fa-volleyball-ball', title: 'Lapangan & Olahraga', desc: 'Kegiatan senam, scout, dan permainan edukatif.' },
    { icon: 'fas fa-mosque', title: 'Mushola / Pembiasaan Ibadah', desc: 'Kegiatan salat berjamaah & tadarus harian.' },
    { icon: 'fas fa-palette', title: 'Seni & Ekstrakurikuler', desc: 'Pramuka, hadroh, kaligrafi, dan kegiatan kreatif.' },
    { icon: 'fas fa-pump-soap', title: 'Sanitasi & Kebersihan', desc: 'Toilet terpisah, tempat cuci tangan, program GCC.' },
];

// Static data for Tujuan
const tujuanList = [
    'Menyiapkan peserta didik agar memiliki dasar keimanan yang kokoh, akhlak mulia, dan semangat ibadah dalam kehidupan sehari-hari.',
    'Menumbuhkembangkan karakter siswa yang mandiri, disiplin, tanggung jawab, dan cinta terhadap ilmu pengetahuan.',
    'Menanamkan pemahaman Islam yang benar dan moderat, serta mencintai Al-Qur\'an sebagai pedoman hidup.',
    'Membekali peserta didik dengan keterampilan dasar membaca, menulis, berhitung, dan berpikir kritis sesuai jenjang usianya.',
    'Mengembangkan potensi peserta didik secara optimal baik di bidang akademik maupun non-akademik.',
    'Membangun budaya sekolah yang sehat, harmonis, dan peduli lingkungan demi iklim belajar yang nyaman dan menyenangkan.',
    'Menanamkan semangat nasionalisme dan cinta tanah air dalam bingkai nilai-nilai Islami.',
];

// Static data for Kurikulum
const mapelKeagamaan = ["Al-Qur'an Hadits", "Akidah Akhlak", "Fikih", "SKI", "Bahasa Arab", "Tahfiz & BTQ"];
const mapelUmum = ["PPKn", "Bahasa Indonesia", "Matematika", "IPAS", "PJOK", "SBdP", "Muatan Lokal", "Informatika/TIK"];

// Static data for PPDB Steps
const ppdbSteps = [
    { title: 'Isi Formulir', desc: 'Calon peserta didik/ortu mengisi formulir online atau offline di sekolah.' },
    { title: 'Verifikasi Dokumen', desc: 'Panitia memeriksa berkas & menghubungi via WhatsApp/Email untuk konfirmasi.' },
    { title: 'Observasi/Wawancara', desc: 'Observasi kesiapan belajar & wawancara singkat orang tua.' },
    { title: 'Pengumuman & Daftar Ulang', desc: 'Peserta yang diterima melakukan daftar ulang (administrasi awal, seragam, dsb.).' },
];

// Static data for Jadwal PPDB
const jadwalPpdb = [
    { stage: 'Pendaftaran', range: '1 Feb – 31 Mar 2026', desc: 'Daftar online/offline & unggah berkas' },
    { stage: 'Verifikasi', range: 'H+1 s.d. H+3 (rolling)', desc: 'Validasi berkas & konfirmasi WA/Email' },
    { stage: 'Observasi', range: 'Setiap Sabtu, 09.00–11.00', desc: 'Observasi siswa & wawancara orang tua' },
    { stage: 'Pengumuman', range: 'Setiap Senin, 12.00', desc: 'Hasil seleksi & penempatan sementara' },
    { stage: 'Daftar Ulang', range: 'Maks. 7 hari setelah pengumuman', desc: 'Administrasi awal & pengukuran seragam' },
];
</script>

<style>
/* CKEditor Content Styles */
.ck-content h2 { margin-top: 1.5em; margin-bottom: 0.5em; font-weight: bold; }
.ck-content h3 { margin-top: 1.2em; margin-bottom: 0.5em; font-weight: bold; }
.ck-content ul, .ck-content ol { padding-left: 1.5em; margin-bottom: 1em; }
.ck-content ul li { list-style-type: disc; margin-bottom: 0.25em; }
.ck-content ol li { list-style-type: decimal; margin-bottom: 0.25em; }
.ck-content blockquote {
    border-left: 4px solid #ccc;
    margin-left: 0;
    padding-left: 1em;
    font-style: italic;
    color: #555;
}

/* Team Image */
.team-img img {
    width: 100%;
    height: 260px;
    object-fit: cover;
}

/* PPDB Steps */
.ppdb-steps .step {
    position: relative;
    display: flex;
    gap: 14px;
    padding: 14px 0;
}
.ppdb-steps .step + .step {
    border-top: 1px dashed rgba(0, 0, 0, .12);
}
.ppdb-steps .step-num {
    flex: 0 0 36px;
    height: 36px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    background: #16a34a;
    color: #fff;
    font-weight: 700;
    box-shadow: 0 6px 16px rgba(22, 163, 74, .25);
}
.ppdb-steps .step-body h6 {
    font-weight: 700;
}

.py-120 {
    padding-top: 120px;
    padding-bottom: 120px;
}
</style>

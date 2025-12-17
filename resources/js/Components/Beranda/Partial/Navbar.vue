<!-- components/Navbar.vue -->
<template>
  <div class="main-navigation">
    <nav class="navbar navbar-expand-lg">
      <div class="container position-relative">
        <a class="navbar-brand" href="/">
          <img 
            src="/assets/img/hope/header_logo1.png"
            style="height:56px; width:auto; max-width:300px; max-height:300px;" 
            alt="logo">
        </a>
        <span class="brand-name brand-name--green">MI ALHIKMAH</span>

        <div class="mobile-menu-right">
          <div class="search-btn">
            <button type="button" class="nav-right-link search-box-outer" @click="toggleSearch">
              <i class="feather-search"></i>
            </button>
          </div>
          <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#main_nav"
            aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-mobile-icon"><i class="feather-menu"></i></span>
          </button>
        </div>
        
        <div class="collapse navbar-collapse" id="main_nav">
          <ul class="navbar-nav">
            <li class="nav-item">
              <Link class="nav-link" :class="{ active: $page.url === '/' }" href="/">Beranda</Link>
            </li>

            <li class="nav-item mega-menu dropdown">
              <a class="nav-link dropdown-toggle" href="#" data-bs-toggle="dropdown">Akademik</a>
              <div class="dropdown-menu fade-down">
                <div class="mega-content">
                  <div class="container-fluid">
                    <div class="row">
                      <div class="col-12 col-sm-4 col-md-3">
                        <h5>Tentang Kami</h5>
                        <div class="menu-about">
                          <a href="#" class="menu-about-logo">
                            <img src="/assets/img/hope/header_logo1.png" style="background-color:white" alt="">
                          </a>
                          <p>MI Al-Hikmah adalah lembaga pendidikan ...</p>
                        </div>
                      </div>
                      <div class="col-12 col-sm-4 col-md-3">
                        <h5>Profil Madrasah</h5>
                        <ul class="mega-menu-item" style="max-height: 200px; overflow-y: auto;">
                          <li><Link class="dropdown-item" href="/profil/visi-misi">Visi & Misi</Link></li>
                          <li><Link class="dropdown-item" href="/profil/sejarah">Sejarah Singkat</Link></li>
                          <li><Link class="dropdown-item" href="/profil/struktur-organisasi">Struktur Organisasi</Link></li>
                          <li><Link class="dropdown-item" href="/profil/sambutan">Sambutan Kepala Sekolah</Link></li>
                          <li><Link class="dropdown-item" href="/sarana-prasarana">Sarana & Prasarana</Link></li>
                          <li><Link class="dropdown-item" href="/tujuan">Tujuan</Link></li>
                          <li><Link class="dropdown-item" href="/kepala-madrasah">Kepala Madrasah</Link></li>
                          <li><Link class="dropdown-item" href="">Prestasi</Link></li>
                          <li><Link class="dropdown-item" href="">Kemitraan</Link></li>
                        </ul>
                      </div>

                      <div class="col-12 col-sm-4 col-md-3">
                        <h5>Akademik & Program</h5>
                        <ul class="mega-menu-item" style="max-height: 200px; overflow-y: auto;">
                          <li><Link class="dropdown-item" href="/kurikulum-mapel">Kurikulum & Mapel</Link></li>
                          <li><Link class="dropdown-item" href="#">Kalender Akademik</Link></li>
                          <li><Link class="dropdown-item" href="#">Program Tahsin/Tahfiz & BTQ</Link></li>
                          <li><Link class="dropdown-item" href="#">Literasi 15 Menit & Klinik Numerasi</Link></li>
                          <li><a class="dropdown-item" href="/files/SYLLABUS_CURRICULUM_%282%29%5B1%5D.pdf" target="_blank">Ekstrakurikuler</a></li>
                          <li><Link class="dropdown-item" href="#">Tata Tertib Akademik</Link></li>
                        </ul>
                      </div>
                      <div class="col-12 col-sm-4 col-md-3">
                        <h5>Layanan & Sumber</h5>
                        <ul class="mega-menu-item">
                          <li><Link class="dropdown-item" href="#">Berita & Pengumuman</Link></li>
                          <li><Link class="dropdown-item" href="/gallery">Gallery Foto/Video</Link></li>
                          <li><Link class="dropdown-item" href="publications.html">Unduhan</Link></li>
                          <li class="dropdown-submenu">
                            <a class="dropdown-item dropdown-toggle" href="#">Aplikasi</a>
                            <ul class="dropdown-menu">
                              <li><a class="dropdown-item" href="">PPDB Online</a></li>
                              <li><a class="dropdown-item" href="">E-Books</a></li>
                            </ul>
                          </li>
                          <li><Link class="dropdown-item" href="">Kontak & Lokasi</Link></li>
                        </ul>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </li>

            <li class="nav-item dropdown">
              <a class="nav-link dropdown-toggle" href="#" data-bs-toggle="dropdown">Guru</a>
              <ul class="dropdown-menu fade-down">
                <li><Link class="dropdown-item" href="/direktori-guru">Direktori Guru</Link></li>
                <li><Link class="dropdown-item" href="">Silabus</Link></li>
                <li><Link class="dropdown-item" href="">Materi Ajar</Link></li>
                <li><Link class="dropdown-item" href="">Materi Uji</Link></li>
                <li><Link class="dropdown-item" href="">Prestasi Guru</Link></li>
                <li><Link class="dropdown-item" href="">Kalender Akademik</Link></li>
              </ul>
            </li>
            
            <li class="nav-item dropdown">
              <a class="nav-link dropdown-toggle" href="#" data-bs-toggle="dropdown">Siswa</a>
              <ul class="dropdown-menu fade-down">
                <li><Link class="dropdown-item" href="event-grid.html">Direktori Siswa</Link></li>
                <li><Link class="dropdown-item" href="Announcement.html">Osis</Link></li>
                <li><Link class="dropdown-item" href="Announcement.html">Prestasi Siswa</Link></li>
                <li><Link class="dropdown-item" href="Announcement.html">Beasiswa</Link></li>
              </ul>
            </li>
            
            <li class="nav-item dropdown">
              <a class="nav-link dropdown-toggle" href="#" data-bs-toggle="dropdown">Alumni</a>
              <ul class="dropdown-menu fade-down">
                <li><Link class="dropdown-item" href="alumnionline.html">Direktori Alumni</Link></li>
                <li><a class="dropdown-item" href="https://mis.hau.bi/student-alumni" target="_blank">Alumni Portal</a></li>
              </ul>
            </li>
            
            <li class="nav-item dropdown">
              <a class="nav-link dropdown-toggle" href="#" data-bs-toggle="dropdown">PPDB</a>
              <ul class="dropdown-menu fade-down">
                <li><Link class="dropdown-item" href="/syarat-dan-alur">Syarat & Alur Pendaftaran</Link></li>
                <li><Link class="dropdown-item" href="/jadwal-dan-kuota">Jadwal & Kuota</Link></li>
                <li><Link class="dropdown-item" href="">Biaya Pendidikan</Link></li>
                <li><Link class="dropdown-item" href="">Formulir Online / Cara Daftar</Link></li>
                <li><Link class="dropdown-item" href="">Brosur PPDB (unduhan)</Link></li>
                <li><Link class="dropdown-item" href="">FAQ PPDB / Kontak panitia</Link></li>
              </ul>
            </li>
            
            <li class="nav-item">
              <Link class="nav-link" :class="{ active: $page.url === '/berita' }" href="/berita">Berita</Link>
            </li>
          </ul>
          
          <div class="nav-right">
            <div class="search-btn">
              <button type="button" class="nav-right-link search-box-outer" @click="toggleSearch">
                <i class="feather-search"></i>
              </button>
            </div>
            <div class="nav-right-btn">
              <a href="/login" target="_blank" class="theme-btn">Masuk!</a>
            </div>
          </div>
        </div>
      </div>
    </nav>
  </div>
</template>

<script setup>
import { ref } from 'vue';
import { Link } from '@inertiajs/vue3';

const showSearch = ref(false);

const toggleSearch = () => {
  showSearch.value = !showSearch.value;
  // Implementasi logika pencarian di sini
    const searchPopup = document.querySelector('.search-popup');
    if (searchPopup) {
        searchPopup.classList.toggle('active', showSearch.value);
    }
};
</script>

<style scoped>
.main-navigation {
    position: relative;
    z-index: 999;
    background-color: #ffffff;
    box-shadow: 0 2px 10px rgba(0,0,0,0.1);
}
</style>
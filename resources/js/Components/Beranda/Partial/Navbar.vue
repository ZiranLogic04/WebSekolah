<!-- components/Navbar.vue -->
<template>
  <div class="main-navigation">
    <nav class="navbar navbar-expand-lg">
      <div class="container position-relative">
        <a class="navbar-brand d-flex align-items-center" href="/">
          <img 
            :src="lembaga.logo ? '/storage/' + lembaga.logo : '/assets/img/hope/header_logo1.png'"
            style="height:56px; width:auto; max-width:100px; object-fit: contain;" 
            alt="logo"
            class="me-2">
          <span class="brand-name">{{ lembaga.nama_sekolah || 'MI AL-HIKMAH' }}</span>
        </a>

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
            <!-- 1. Beranda -->
            <li class="nav-item" v-if="isActive('beranda')">
              <Link class="nav-link" :class="{ active: $page.url === '/' }" href="/">{{ getLabel('beranda', 'Beranda') }}</Link>
            </li>

            <!-- 2. Akademik (Mega Menu Dinamis) -->
            <li class="nav-item mega-menu dropdown" v-if="isActive('akademik')">
              <a class="nav-link dropdown-toggle" href="#" data-bs-toggle="dropdown">{{ getLabel('akademik', 'Akademik') }}</a>
              <div class="dropdown-menu fade-down">
                <div class="mega-content">
                  <div class="container-fluid">
                    <div class="row">
                      <!-- Kolom 1: Tentang Kami -->
                      <div class="col-12 col-sm-4 col-md-3">
                        <h5>{{ megaMenu?.['tentang-kami']?.nama || 'Tentang Kami' }}</h5>
                        <div class="menu-about">
                          <a href="#" class="menu-about-logo">
                            <img :src="lembaga.logo ? '/storage/' + lembaga.logo : '/assets/img/hope/header_logo1.png'" style="background-color:white" alt="Logo">
                          </a>
                          <p>{{ megaMenu?.['tentang-kami']?.content || 'Lembaga pendidikan Islam yang berkomitmen untuk membentuk generasi yang berakhlak mulia dan berprestasi.' }}</p>
                        </div>
                      </div>
                      
                      <!-- Kolom 2: Profil Madrasah -->
                      <div class="col-12 col-sm-4 col-md-3">
                        <h5>{{ megaMenu?.['profil']?.nama || 'Profil Madrasah' }}</h5>
                        <ul class="mega-menu-item" style="max-height: 200px; overflow-y: auto;">
                          <li v-for="item in (megaMenu?.['profil']?.items || [])" :key="item.id">
                            <Link class="dropdown-item" :href="getItemUrl(item)">{{ item.nama }}</Link>
                          </li>
                          <!-- Fallback jika kosong -->
                          <template v-if="!megaMenu?.['profil']?.items?.length">
                            <li><Link class="dropdown-item" href="/profil/visi-misi">Visi & Misi</Link></li>
                            <li><Link class="dropdown-item" href="/profil/sejarah">Sejarah Singkat</Link></li>
                            <li><Link class="dropdown-item" href="/profil/struktur-organisasi">Struktur Organisasi</Link></li>
                            <li><Link class="dropdown-item" href="/profil/sarana-prasarana">Sarana & Prasarana</Link></li>
                            <li><Link class="dropdown-item" href="/profil/visi-misi">Tujuan</Link></li>
                            <li><Link class="dropdown-item" href="/profil/kepala-madrasah">Kepala Madrasah</Link></li>
                            <li><Link class="dropdown-item" href="#">Prestasi</Link></li>
                            <li><Link class="dropdown-item" href="#">Kemitraan</Link></li>
                          </template>
                        </ul>
                      </div>
                      
                      <!-- Kolom 3: Akademik & Program -->
                      <div class="col-12 col-sm-4 col-md-3">
                        <h5>{{ megaMenu?.['akademik-program']?.nama || 'Akademik & Program' }}</h5>
                        <ul class="mega-menu-item" style="max-height: 200px; overflow-y: auto;">
                          <li v-for="item in (megaMenu?.['akademik-program']?.items || [])" :key="item.id">
                            <Link class="dropdown-item" :href="getItemUrl(item)">{{ item.nama }}</Link>
                          </li>
                          <!-- Fallback jika kosong -->
                          <template v-if="!megaMenu?.['akademik-program']?.items?.length">
                            <li><Link class="dropdown-item" href="/profil/kurikulum-mapel">Kurikulum & Mapel</Link></li>
                            <li><Link class="dropdown-item" href="#">Kalender Akademik</Link></li>
                            <li><Link class="dropdown-item" href="/profil/kurikulum-mapel">Program Tahsin/Tahfiz & BTQ</Link></li>
                            <li><Link class="dropdown-item" href="/profil/kurikulum-mapel">Literasi & Klinik Numerasi</Link></li>
                            <li><Link class="dropdown-item" href="/profil/kurikulum-mapel">Ekstrakurikuler</Link></li>
                            <li><Link class="dropdown-item" href="#">Tata Tertib Akademik</Link></li>
                          </template>
                        </ul>
                      </div>
                      
                      <!-- Kolom 4: Layanan & Sumber -->
                      <div class="col-12 col-sm-4 col-md-3">
                        <h5>{{ megaMenu?.['layanan']?.nama || 'Layanan & Sumber' }}</h5>
                        <ul class="mega-menu-item">
                          <li v-for="item in (megaMenu?.['layanan']?.items || [])" :key="item.id">
                            <Link class="dropdown-item" :href="getItemUrl(item)">{{ item.nama }}</Link>
                          </li>
                          <!-- Fallback jika kosong -->
                          <template v-if="!megaMenu?.['layanan']?.items?.length">
                            <li><Link class="dropdown-item" href="/berita">Berita & Pengumuman</Link></li>
                            <li><Link class="dropdown-item" href="/gallery">Gallery Foto/Video</Link></li>
                            <li><Link class="dropdown-item" href="#">Unduhan</Link></li>
                            <li><Link class="dropdown-item" href="#">PPDB Online</Link></li>
                            <li><Link class="dropdown-item" href="#">E-Books</Link></li>
                            <li><Link class="dropdown-item" href="#">Kontak & Lokasi</Link></li>
                          </template>
                        </ul>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </li>

            <!-- 3. Guru -->
            <li class="nav-item dropdown" v-if="isActive('guru')">
              <a class="nav-link dropdown-toggle" href="#" data-bs-toggle="dropdown">{{ getLabel('guru', 'Guru') }}</a>
              <ul class="dropdown-menu fade-down">
                <template v-if="guruMenu.length > 0">
                    <li v-for="item in guruMenu" :key="item.id"><Link class="dropdown-item" :href="getItemUrl(item)">{{ item.nama }}</Link></li>
                </template>
                <template v-else>
                    <li><Link class="dropdown-item" href="/direktori-guru">Direktori Guru</Link></li>
                </template>
              </ul>
            </li>
            
            <!-- 4. Siswa -->
            <li class="nav-item dropdown" v-if="isActive('siswa')">
              <a class="nav-link dropdown-toggle" href="#" data-bs-toggle="dropdown">{{ getLabel('siswa', 'Siswa') }}</a>
              <ul class="dropdown-menu fade-down">
                <template v-if="siswaMenu.length > 0">
                    <li v-for="item in siswaMenu" :key="item.id"><Link class="dropdown-item" :href="getItemUrl(item)">{{ item.nama }}</Link></li>
                </template>
                <template v-else>
                    <li><Link class="dropdown-item" href="/profil/direktori-siswa">Direktori Siswa</Link></li>
                    <li><Link class="dropdown-item" href="/profil/osis">Osis</Link></li>
                    <li><Link class="dropdown-item" href="/profil/prestasi-siswa">Prestasi Siswa</Link></li>
                    <li><Link class="dropdown-item" href="/profil/beasiswa">Beasiswa</Link></li>
                </template>
              </ul>
            </li>
            
            <!-- 5. Alumni -->
            <li class="nav-item dropdown" v-if="isActive('alumni')">
              <a class="nav-link dropdown-toggle" href="#" data-bs-toggle="dropdown">{{ getLabel('alumni', 'Alumni') }}</a>
              <ul class="dropdown-menu fade-down">
                <template v-if="alumniMenu.length > 0">
                    <li v-for="item in alumniMenu" :key="item.id"><Link class="dropdown-item" :href="getItemUrl(item)">{{ item.nama }}</Link></li>
                </template>
                <template v-else>
                    <li><Link class="dropdown-item" href="/profil/direktori-alumni">Direktori Alumni</Link></li>
                    <li><Link class="dropdown-item" href="/profil/alumni-portal">Alumni Portal</Link></li>
                </template>
              </ul>
            </li>
            
            <!-- 6. PPDB -->
            <li class="nav-item dropdown" v-if="isActive('ppdb')">
              <a class="nav-link dropdown-toggle" href="#" data-bs-toggle="dropdown">{{ getLabel('ppdb', 'PPDB') }}</a>
              <ul class="dropdown-menu fade-down">
                <template v-if="ppdbMenu.length > 0">
                    <li v-for="item in ppdbMenu" :key="item.id"><Link class="dropdown-item" :href="getItemUrl(item)">{{ item.nama }}</Link></li>
                </template>
                <template v-else>
                    <li><Link class="dropdown-item" href="/profil/syarat-alur">Syarat & Alur Pendaftaran</Link></li>
                    <li><Link class="dropdown-item" href="/profil/jadwal-kuota">Jadwal & Kuota</Link></li>
                </template>
              </ul>
            </li>
            
            <!-- 7. Berita -->
            <li class="nav-item" v-if="isActive('berita')">
              <Link class="nav-link" :class="{ active: $page.url === '/berita' }" href="/berita">{{ getLabel('berita', 'Berita') }}</Link>
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
import { ref, computed } from 'vue';
import { Link, usePage } from '@inertiajs/vue3';

const showSearch = ref(false);
const page = usePage();
const menus = computed(() => page.props.menus || {});
const lembaga = computed(() => page.props.lembaga || {}); // Ambil data lembaga
const megaMenu = computed(() => page.props.megaMenu || {}); // Fix: Ambil data megaMenu
const guruMenu = computed(() => page.props.guruMenu || []);
const siswaMenu = computed(() => page.props.siswaMenu || []);
const alumniMenu = computed(() => page.props.alumniMenu || []);
const ppdbMenu = computed(() => page.props.ppdbMenu || []);

const toggleSearch = () => {
    showSearch.value = !showSearch.value;
    const searchPopup = document.querySelector('.search-popup');
    if (searchPopup) {
        searchPopup.classList.toggle('active', showSearch.value);
    }
};

// Check if menu is active
const isActive = (slug) => {
    // Default true jika belum ada data (mencegah blank)
    if (!menus.value[slug]) return true;
    return menus.value[slug].is_active;
};

// Get menu label custom
const getLabel = (slug, defaultLabel) => {
    if (!menus.value[slug]) return defaultLabel;
    return menus.value[slug].nama;
};

// Get URL for menu item (use slug for profil pages)
const getItemUrl = (item) => {
    if (item.url) return item.url;
    
    // Mapping slug ke URL umum
    const urlMap = {
        'visi-misi': '/profil/visi-misi',
        'sejarah': '/profil/sejarah',
        'struktur-organisasi': '/profil/struktur-organisasi',
        'sambutan': '/profil/sambutan',
        'sarana-prasarana': '/profil/sarana-prasarana',
        'tujuan': '/profil/tujuan',
        'kepala-madrasah': '/profil/kepala-madrasah',
        'kurikulum-mapel': '/profil/kurikulum-mapel',
        'gallery': '/gallery',
        'berita-pengumuman': '/berita',
        'direktori-guru': '/direktori-guru',
        'direktori-siswa': '/profil/direktori-siswa',
        'direktori-alumni': '/profil/direktori-alumni',
        'alumni-portal': '/profil/alumni-portal'
    };
    
    return urlMap[item.slug] || `/profil/${item.slug}`;
};
</script>

<style scoped>
.main-navigation {
    position: relative;
    z-index: 999;
    background-color: #ffffff;
    box-shadow: 0 2px 10px rgba(0,0,0,0.1);
}

.brand-name {
    font-size: 1.5rem; /* 24px */
    font-weight: 800;
    color: #116E63;
    line-height: 1; /* Rapat untuk multi-line */
    text-transform: uppercase;
    font-family: 'Inter', sans-serif;
    margin-left: 10px;
    letter-spacing: 0.5px;
    /* Handling Long Text */
    max-width: 400px; /* Batasi lebar agar tidak menabrak menu */
    white-space: normal; /* Izinkan turun baris */
    display: inline-block; /* Agar wrap berfungsi */
    vertical-align: middle;
}

@media (max-width: 991px) { /* Tablet/Small Laptop */
    .brand-name {
        font-size: 1.2rem; /* Kecilkan sedikit di layar medium */
        max-width: 250px;
    }
}

@media (max-width: 768px) { /* Mobile */
    .brand-name {
        display: none; /* Tetap hide di mobile krena tidak muat */
    }
}
</style>
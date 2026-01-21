<template>
  <footer class="footer-area">
    <div class="footer-shape">
      <img :src="logoUrl" alt="Logo Sekolah">
    </div>

    <div class="footer-widget">
      <div class="container">
        <div class="row footer-widget-wrapper pt-100 pb-70">

          <!-- Kolom 1: Tentang & Kontak -->
          <div class="col-md-6 col-lg-4">
            <div class="footer-widget-box about-us">
              <a href="/" class="footer-logo">
                <img :src="logoUrl" alt="Logo Sekolah" style="width: 80px">
              </a>
              <p class="mb-3">
                {{ aboutSection?.description || lembaga?.nama_sekolah || 'MI Al-Hikmah adalah madrasah ibtidaiyah yang berkomitmen menghadirkan pendidikan dasar berkualitas.' }}
              </p>
              <ul class="footer-contact">
                <li v-if="lembaga?.alamat">
                  <a :href="'https://maps.google.com/?q=' + encodeURIComponent(lembaga.alamat)" target="_blank" rel="noopener">
                    <i class="fas fa-map-marker-alt"></i> {{ lembaga.alamat }}
                  </a>
                </li>
                <li v-if="lembaga?.email">
                  <a :href="'mailto:' + lembaga.email"><i class="fas fa-envelope"></i> {{ lembaga.email }}</a>
                </li>
                <li v-if="lembaga?.telepon">
                  <a :href="'tel:' + lembaga.telepon"><i class="fas fa-phone"></i> {{ lembaga.telepon }}</a>
                </li>
                <li v-if="lembaga?.whatsapp">
                  <a :href="'https://wa.me/' + lembaga.whatsapp.replace(/[^0-9]/g, '')" target="_blank" rel="noopener">
                    <i class="fab fa-whatsapp"></i> WhatsApp Layanan
                  </a>
                </li>
              </ul>
            </div>
          </div>

          <!-- Kolom 2: Tautan Cepat -->
          <div class="col-md-6 col-lg-2">
            <div class="footer-widget-box list">
              <h4 class="footer-widget-title">{{ quickLinksTitle }}</h4>
              <ul class="footer-list">
                <li v-for="(link, index) in quickLinks" :key="'quick-'+index">
                  <a :href="link.url"><i class="fas fa-caret-right"></i> {{ link.label }}</a>
                </li>
              </ul>
            </div>
          </div>

          <!-- Kolom 3: Akademik & Layanan -->
          <div class="col-md-6 col-lg-3">
            <div class="footer-widget-box list">
              <h4 class="footer-widget-title">{{ academicLinksTitle }}</h4>
              <ul class="footer-list">
                <li v-for="(link, index) in academicLinks" :key="'academic-'+index">
                  <a :href="link.url"><i class="fas fa-caret-right"></i> {{ link.label }}</a>
                </li>
              </ul>
            </div>
          </div>

          <!-- Kolom 4: Lokasi / Map -->
          <div class="col-md-6 col-lg-3">
            <div class="footer-widget-box list">
              <h4 class="footer-widget-title">Lokasi Kami</h4>
              <div class="ratio ratio-1x1" style="border-radius:12px; overflow:hidden;">
                <iframe :src="mapUrl"
                  style="border:0;" allowfullscreen="" loading="lazy"
                  referrerpolicy="no-referrer-when-downgrade"></iframe>
              </div>
            </div>
          </div>

        </div>
      </div>
    </div>

    <div class="copyright">
      <div class="container">
        <div class="copyright-wrapper">
          <div class="row">
            <div class="col-md-6 align-self-center">
              <p class="copyright-text mb-0">
                &copy; {{ new Date().getFullYear() }} {{ lembaga?.nama_sekolah || 'MI Al-Hikmah' }}. All rights reserved.
              </p>
            </div>
            <div class="col-md-6 align-self-center">
              <ul class="footer-social">
                <li v-if="lembaga?.facebook"><a :href="lembaga.facebook" target="_blank" rel="noopener"><i class="fab fa-facebook-f"></i></a></li>
                <li v-if="lembaga?.instagram"><a :href="lembaga.instagram" target="_blank" rel="noopener"><i class="fab fa-instagram"></i></a></li>
                <li v-if="lembaga?.whatsapp"><a :href="'https://wa.me/' + lembaga.whatsapp.replace(/[^0-9]/g, '')" target="_blank" rel="noopener"><i class="fab fa-whatsapp"></i></a></li>
                <li v-if="lembaga?.youtube"><a :href="lembaga.youtube" target="_blank" rel="noopener"><i class="fab fa-youtube"></i></a></li>
                <li v-if="lembaga?.twitter"><a :href="lembaga.twitter" target="_blank" rel="noopener"><i class="fab fa-twitter"></i></a></li>
              </ul>
            </div>
          </div>
        </div>
      </div>
    </div>
  </footer>
</template>

<script setup>
import { usePage } from '@inertiajs/vue3';
import { computed } from 'vue';

const page = usePage();
const lembaga = computed(() => page.props.lembaga);
const aboutSection = computed(() => page.props.aboutSection);

// Logo URL with /storage/ prefix
const logoUrl = computed(() => {
  if (lembaga.value?.logo) {
    return `/storage/${lembaga.value.logo}`;
  }
  return '/assets/img/hope/header_logo1.png';
});

// Footer Links Logic
const footerLinks = computed(() => page.props.footerLinks);

const quickLinksTitle = computed(() => footerLinks.value?.quick_links_title || 'Tautan Cepat');

const quickLinks = computed(() => {
  return footerLinks.value?.quick_links || [
    { label: 'Beranda', url: '/' },
    { label: 'Tentang', url: '/profil' },
    { label: 'Akademik', url: '/akademik/kurikulum' },
    { label: 'Program', url: '/program' },
    { label: 'PPDB', url: '/ppdb' },
    { label: 'Berita', url: '/berita' },
    { label: 'Galeri', url: '/galeri' },
    { label: 'Kontak', url: '/kontak' },
  ];
});

const academicLinksTitle = computed(() => footerLinks.value?.academic_links_title || 'Akademik & Layanan');

const academicLinks = computed(() => {
  return footerLinks.value?.academic_links || [
    { label: 'Kalender Akademik', url: '/akademik/kalender' },
    { label: 'Tahfiz & BTQ', 'url': '/program/tahfiz' },
    { label: 'Literasi 15 Menit', 'url': '/program/literasi' },
    { label: 'Klinik Numerasi', 'url': '/program/numerasi' },
    { label: 'Ekstrakurikuler', 'url': '/kesiswaan/ekskul' },
    { label: 'Unduhan', 'url': '/unduhan' },
    { label: 'Satgas Anti-Bullying', 'url': '/anti-bullying' },
    { label: 'Survei Kepuasan (SKM)', 'url': '/skm' },
  ];
});

// Auto-generate Google Maps URL from alamat if google_maps is not filled
const mapUrl = computed(() => {
  if (lembaga.value?.google_maps) {
    return lembaga.value.google_maps;
  }
  const query = lembaga.value?.alamat || lembaga.value?.nama_sekolah || 'MI Al-Hikmah';
  return `https://www.google.com/maps?q=${encodeURIComponent(query)}&output=embed`;
});
</script>

<style scoped>
.footer-area {
    overflow: hidden; /* Memotong objek yang keluar dari area footer */
    position: relative;
}
</style>
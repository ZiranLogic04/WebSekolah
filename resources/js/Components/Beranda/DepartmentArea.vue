<template>
  <div class="program-section bg pt-10">
    <div class="container">
      <div class="row">
        <div class="col-lg-6 mx-auto">
          <div class="site-heading text-center">
            <span class="site-title-tagline"><i class="fa fa-book-open"></i> Program MI</span>
            <h6 class="site-title">Jelajahi <span>Program & Layanan</span> Kami</h6>
            <p>Program pembelajaran dan layanan siswa di MI Al-Hikmah yang menumbuhkan iman, ilmu, karakter,
              literasi, dan numerasi dalam lingkungan yang ramah anak.</p>
          </div>
        </div>
      </div>

      <div class="program-slider owl-carousel owl-theme">
        <!-- 1. Kurikulum & Pembelajaran -->
        <div class="program-item">
          <div class="program-icon">
            <i class="fas fa-desktop"></i>
          </div>
          <div class="program-info">
            <h4 class="program-title"><a href="/akademik/kurikulum">Kurikulum & Pembelajaran</a></h4>
            <p>Kurikulum Merdeka/K13, jadwal pelajaran, penilaian, pengayaan & remedial.</p>
            <div class="program-btn">
              <a href="/akademik/kurikulum">Selengkapnya<i class="fas fa-arrow-right-long"></i></a>
            </div>
          </div>
        </div>

        <!-- 2. Tahfiz & BTQ -->
        <div class="program-item">
          <div class="program-icon">
            <i class="fas fa-star"></i>
          </div>
          <div class="program-info">
            <h4 class="program-title"><a href="/program/tahfiz">Tahfiz & BTQ</a></h4>
            <p>Tahsin bacaan, target juz 30, setoran berkala, dan pembiasaan ibadah harian.</p>
            <div class="program-btn">
              <a href="/program/tahfiz">Selengkapnya<i class="fas fa-arrow-right-long"></i></a>
            </div>
          </div>
        </div>

        <!-- 3. Literasi 15 Menit & Perpustakaan -->
        <div class="program-item">
          <div class="program-icon">
            <i class="fas fa-book"></i>
          </div>
          <div class="program-info">
            <h4 class="program-title"><a href="/program/literasi">Literasi 15 Menit & Perpustakaan</a></h4>
            <p>Membaca harian, pojok baca kelas, peminjaman buku, dan lomba literasi.</p>
            <div class="program-btn">
              <a href="/program/literasi">Selengkapnya<i class="fas fa-arrow-right-long"></i></a>
            </div>
          </div>
        </div>

        <!-- 4. Klinik Numerasi & Sains Dasar -->
        <div class="program-item">
          <div class="program-icon">
            <i class="fas fa-calculator"></i>
          </div>
          <div class="program-info">
            <h4 class="program-title"><a href="/program/numerasi">Klinik Numerasi & Sains Dasar</a></h4>
            <p>Pendampingan berhitung, permainan numerasi, dan percobaan sains sederhana.</p>
            <div class="program-btn">
              <a href="/program/numerasi">Selengkapnya<i class="fas fa-arrow-right-long"></i></a>
            </div>
          </div>
        </div>

        <!-- 5. Kesiswaan & Ekstrakurikuler -->
        <div class="program-item">
          <div class="program-icon">
            <i class="fas fa-users"></i>
          </div>
          <div class="program-info">
            <h4 class="program-title"><a href="/kesiswaan/ekskul">Kesiswaan & Ekstrakurikuler</a></h4>
            <p>Pramuka, olahraga, seni, MTQ, drumband, dan kegiatan penguatan karakter.</p>
            <div class="program-btn">
              <a href="/kesiswaan/ekskul">Selengkapnya<i class="fas fa-arrow-right-long"></i></a>
            </div>
          </div>
        </div>

        <!-- 6. Layanan Siswa (UKS & Anti-Bullying) -->
        <div class="program-item">
          <div class="program-icon">
            <i class="fas fa-heartbeat"></i>
          </div>
          <div class="program-info">
            <h4 class="program-title"><a href="/layanan/anak">Layanan Siswa (UKS & Anti-Bullying)</a></h4>
            <p>UKS, edukasi PHBS, Satgas Anti-Bullying, dan alur pengaduan yang aman.</p>
            <div class="program-btn">
              <a href="/layanan/anak">Selengkapnya<i class="fas fa-arrow-right-long"></i></a>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { onMounted } from 'vue'

onMounted(() => {
  if (window.$ && window.$.fn && window.$.fn.owlCarousel) {
    $('.program-slider').owlCarousel({
      items: 4,
      loop: true,
      nav: true,
      dots: false,
      autoplay: true,
      autoplayTimeout: 4000,
      margin: 30,
      responsive: {
        0: { items: 1 },
        576: { items: 2 },
        992: { items: 3 },
        1200: { items: 4 }
      }
    })
  }
})
</script>

<style scoped>
.pt-10 {
  padding-top: 10px;
}

.program-icon i {
  font-size: 50px;
  color: #FDA31B; /* var(--theme-color2) */
}
</style>
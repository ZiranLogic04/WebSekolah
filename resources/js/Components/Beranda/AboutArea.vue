<template>
  <div class="about-section py-90">
    <div class="container">
      <div class="row g-4 align-items-center">
        <div class="col-lg-6">
          <div class="about-left wow fadeInLeft" data-wow-delay=".25s">
            <div class="about-img">
              <div class="row g-4">
                <div class="col-md-6">
                  <img class="img-1" src="/assets/img/hope/_U9A7700.jpg" alt="Kegiatan belajar siswa MI Al-Hikmah">
                  <div class="about-experience mt-4">
                    <div class="about-experience-icon">
                      <i class="fas fa-award"></i>
                    </div>
                    <b class="text-start">17 Tahun <br> Telah Berdiri</b>
                  </div>
                </div>
                <div class="col-md-6">
                  <img class="img-2" src="/assets/img/hope/_U9A8033.jpg" alt="Suasana kelas ramah anak">
                  <img class="img-3 mt-4" src="/assets/img/hope/259A2654%20copy.JPG" alt="Kegiatan keagamaan dan literasi">
                </div>
              </div>
            </div>
          </div>
        </div>

        <div class="col-lg-6">
          <div class="about-right wow fadeInRight" data-wow-delay=".25s">
            <div class="site-heading mb-3">
              <span class="site-title-tagline"><i class="fa fa-book-open"></i> Tentang Kami</span>
              <h2 class="site-title">
                Sistem Pendidikan <span>Madrasah</span> Memberdayakan Potensi Sejak Dini
              </h2>
            </div>

            <p class="about-text">
              MI Al-Hikmah berkomitmen menghadirkan pendidikan dasar berkualitas yang menumbuhkan akhlak
              mulia, serta menguatkan literasi dan numerasi sejak dini. Berlandaskan nilai-nilai Islam dan karakter
              Pancasila, kami membekali siswa dengan dasar iman, ilmu, dan keterampilan untuk berkontribusi positif
              di lingkungan sekitarnya. Program unggulan meliputi tahsin/tahfiz Al-Qur'an, pembiasaan ibadah harian,
              pembelajaran aktif yang ramah anak, dan kegiatan kokurikuler kreatif—ditopang guru kompeten, biaya
              terjangkau, serta fasilitas yang nyaman.
            </p>

            <div class="about-content">
              <div class="row">
                <div class="col-md-7">
                  <!-- Item 1 -->
                  <div class="about-item">
                    <div class="about-item-icon">
                      <i class="fas fa-book-open"></i>
                    </div>
                    <div class="about-item-content">
                      <h5>Tahfiz & BTQ Terstruktur</h5>
                      <p>Tahsin bacaan, target juz 30 bertahap, setoran rutin, dan pendampingan harian.</p>
                    </div>
                  </div>
                  <!-- Item 2 -->
                  <div class="about-item">
                    <div class="about-item-icon">
                      <i class="fas fa-globe"></i>
                    </div>
                    <div class="about-item-content">
                      <h5>Literasi & Klinik Numerasi</h5>
                      <p>Membaca 15 menit setiap pagi dan pendampingan berhitung untuk fondasi akademik kuat.</p>
                    </div>
                  </div>
                </div>

                <div class="col-md-5">
                  <div class="about-quote">
                    <p>"Kami membangun lingkungan belajar yang aman, hangat, dan mendorong kemandirian serta rasa ingin tahu anak."</p>
                    <i class="fa fa-quote-right"></i>
                  </div>
                </div>
              </div>
            </div>

            <div class="about-bottom">
              <a href="/profil" class="theme-btn">Selengkapnya<i class="fas fa-arrow-right-long"></i></a>
              <div class="about-phone">
                <div class="icon"><i class="fa fa-headset"></i></div>
                <div class="number">
                  <span>Hubungi Kami</span>
                  <h6><a href="tel:+6281234567890">+62 812-3456-7890</a></h6>
                </div>
              </div>
            </div>

          </div>
        </div>

      </div>
    </div>
  </div>
</template>

<script setup>
// Static component - no props needed
</script>

<style scoped>
.py-90 {
  padding-top: 90px;
  padding-bottom: 90px;
}

.about-experience-icon i {
  font-size: 50px;
  color: #116E63; /* var(--theme-color) */
}

.about-item-icon i {
  font-size: 35px;
  color: #ffffff; /* var(--color-white) - but icon container is white? wait, let's check css */
  /* Re-checking CSS: 
     .about-item-icon has color: var(--theme-color); in style.css
     But for about-experience-icon it has color: var(--theme-color);
  */
}
/* enforcing colors based on inspection */
.about-experience-icon i {
    color: #116E63;
}
.about-item-icon i {
    color: #116E63; /* The icon itself should be teal because background is theme-color2 (orange) based on style.css but let's check style.css again to be sure */
}
</style>
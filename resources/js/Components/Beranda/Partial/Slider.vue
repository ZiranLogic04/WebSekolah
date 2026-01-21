<template>
  <div class="hero-section">
    <div class="hero-slider owl-carousel">
      
      <!-- Dinamis Slider dari Database -->
      <template v-if="sliders && sliders.length > 0">
        <div v-for="slider in sliders" :key="slider.id" class="hero-single" :style="{ background: `url(${slider.image})`, height: '500px', backgroundSize: 'cover', backgroundPosition: `center ${slider.image_position ?? 50}%` }">
          <div class="container">
            <div class="row align-items-center">
              <div class="col-md-12 col-lg-7">
                <div class="hero-content">
                  <h6 class="hero-sub-title" style="color: #F8B12D !important;" data-animation="fadeInDown" data-delay=".25s">
                    <i class="fa fa-book-open"></i> {{ slider.top_badge || $page.props.lembaga?.nama_sekolah || 'MI AL-HIKMAH' }}
                  </h6>
                  <h3 v-if="slider.title" class="hero-title" data-animation="fadeInRight" data-delay=".50s">
                    <span v-html="slider.title"></span>
                  </h3>
                  
                  <p v-if="slider.subtitle" data-animation="fadeInLeft" data-delay=".75s">
                    {{ slider.subtitle }}
                  </p>

                  <div class="hero-btn" data-animation="fadeInUp" data-delay="1s" v-if="slider.button_text && slider.button_url">
                    <a :href="slider.button_url" class="theme-btn">{{ slider.button_text }}<i class="fas fa-arrow-right-long"></i></a>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </template>

      <!-- Fallback Slider (Default Static) jika data kosong -->
      <template v-else>
          <!-- Slide 1: PPDB -->
          <div class="hero-single" style="background: url(/assets/img/hope/123.jpg); height: 500px; background-size: cover; background-position: center;">
            <div class="container">
              <div class="row align-items-center">
                <div class="col-md-12 col-lg-7">
                  <div class="hero-content">
                    <h6 class="hero-sub-title" data-animation="fadeInDown" data-delay=".25s">
                      <i class="fa fa-book-open"></i> Selamat Datang di MI Al-Hikmah
                    </h6>
                    <h3 class="hero-title" data-animation="fadeInRight" data-delay=".50s">
                      Penerimaan Siswa Baru <span>2025/2026</span>
                    </h3>
                    <p data-animation="fadeInLeft" data-delay=".75s">
                      Bergabunglah bersama sekolah dasar berbasis nilai Islam dan karakter
                      Pancasila—guru peduli, biaya terjangkau, pembelajaran aktif, fasilitas nyaman.
                    </p>
                    <div class="hero-btn" data-animation="fadeInUp" data-delay="1s">
                      <a href="#" class="theme-btn">Daftar Sekarang<i class="fas fa-arrow-right-long"></i></a>
                      <a href="#" class="theme-btn theme-btn2">Info PPDB<i class="fas fa-arrow-right-long"></i></a>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>

          <!-- Slide 2: Tahfiz & BTQ -->
          <div class="hero-single" style="background: url(/assets/img/hope/HOPEDREAM_31.jpg); height: 500px; background-size: cover; background-position: center;">
            <div class="container">
              <div class="row align-items-center">
                <div class="col-md-12 col-lg-7">
                  <div class="hero-content">
                    <h6 class="hero-sub-title" data-animation="fadeInDown" data-delay=".25s">
                      <i class="fa fa-book-open"></i> Program Unggulan
                    </h6>
                    <h3 class="hero-title" data-animation="fadeInRight" data-delay=".50s">
                      Program <span>Tahfiz & BTQ</span>
                    </h3>
                    <p data-animation="fadeInLeft" data-delay=".75s">
                      Target juz 30, tahsin harian, setoran mingguan, pembiasaan tilawah—membentuk
                      kebiasaan baik sejak dini.
                    </p>
                    <div class="hero-btn" data-animation="fadeInUp" data-delay="1s">
                      <a href="#" class="theme-btn">Lihat Program<i class="fas fa-arrow-right-long"></i></a>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
      </template>

    </div>
  </div>
</template>

<script setup>
import { onMounted, nextTick, watch } from 'vue'

const props = defineProps({
    sliders: Array
});

const initCarousel = () => {
    if (window.$ && window.$.fn && window.$.fn.owlCarousel) {
        // Destroy dulu jika sudah ada instance (penting saat update dinamis)
        $('.hero-slider').trigger('destroy.owl.carousel'); 
        
        $('.hero-slider').owlCarousel({
            items: 1,
            loop: true,
            nav: true,
            dots: true,
            autoplay: true,
            autoplayTimeout: 5000,
            animateOut: 'fadeOut',
            animateIn: 'fadeIn'
        });
    }
}

onMounted(() => {
    // Tunggu DOM update
    nextTick(() => {
        initCarousel();
    });
});

// Watch sliders prop changes untuk re-init (jika ada update real-time)
watch(() => props.sliders, () => {
    nextTick(() => {
        initCarousel();
    });
}, { deep: true });
</script>

<style scoped>
.hero-section {
    margin-top: 50px;
    padding-top: 20px;
    position: relative;
    z-index: 1;
}
.hero-single {
  background-size: cover;
  background-position: center;
  display: flex;
  align-items: center;
}
</style>
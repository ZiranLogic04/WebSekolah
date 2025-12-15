<!-- components/HeroSlider.vue -->
<template>
  <div class="hero-section">
    <div class="hero-slider owl-carousel">
      
      <!-- Slide 1 -->
      <div class="hero-single" :style="slideStyle('slide_1_image', '123.jpg')">
        <div class="container">
          <div class="row align-items-center">
            <div class="col-md-12 col-lg-7">
              <div class="hero-content">
                <h6 class="hero-sub-title" data-animation="fadeInDown" data-delay=".25s">
                  <i class="fa fa-book-open"></i> {{ getText('slide_1_top_title', 'Selamat Datang') }}
                </h6>
                <h3 class="hero-title" :class="getClass('slide_1_title')" data-animation="fadeInRight" data-delay=".50s">
                  {{ getText('slide_1_title', 'Penerimaan Siswa Baru 2025/2026') }}
                </h3>
                <p :class="getClass('slide_1_subtitle')" data-animation="fadeInLeft" data-delay=".75s">
                  {{ getText('slide_1_subtitle', 'Bergabunglah bersama sekolah dasar berbasis nilai Islam dan karakter Pancasila.') }}
                </p>
                <div class="hero-btn" data-animation="fadeInUp" data-delay="1s" v-if="getText('slide_1_button_text')">
                  <a :href="getText('slide_1_button_link', '#')" class="theme-btn">{{ getText('slide_1_button_text', 'Daftar Sekarang') }}<i class="fas fa-arrow-right-long"></i></a>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Slide 2 -->
      <div class="hero-single" :style="slideStyle('slide_2_image', 'HOPEDREAM_31.jpg')">
        <div class="container">
          <div class="row align-items-center">
            <div class="col-md-12 col-lg-7">
              <div class="hero-content">
                <h6 class="hero-sub-title" data-animation="fadeInDown" data-delay=".25s">
                   <i class="fa fa-book-open"></i> {{ getText('slide_2_top_title', 'Program Unggulan') }}
                </h6>
                <h3 class="hero-title" :class="getClass('slide_2_title')" data-animation="fadeInRight" data-delay=".50s">
                   {{ getText('slide_2_title', 'Program Tahfiz & BTQ') }}
                </h3>
                <p :class="getClass('slide_2_subtitle')" data-animation="fadeInLeft" data-delay=".75s">
                   {{ getText('slide_2_subtitle', 'Target juz 30, tahsin harian, setoran mingguan, pembiasaan tilawah.') }}
                </p>
                <div class="hero-btn" data-animation="fadeInUp" data-delay="1s" v-if="getText('slide_2_button_text')">
                  <a :href="getText('slide_2_button_link', '#')" class="theme-btn">{{ getText('slide_2_button_text', 'Lihat Program') }}<i class="fas fa-arrow-right-long"></i></a>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Slide 3 -->
      <div class="hero-single" :style="slideStyle('slide_3_image', '43.jpg')">
        <div class="container">
          <div class="row align-items-center">
            <div class="col-md-12 col-lg-7">
              <div class="hero-content">
                <h6 class="hero-sub-title" data-animation="fadeInDown" data-delay=".25s">
                   <i class="fa fa-book-open"></i> {{ getText('slide_3_top_title', 'Budaya Madrasah') }}
                </h6>
                <h3 class="hero-title" :class="getClass('slide_3_title')" data-animation="fadeInRight" data-delay=".50s">
                   {{ getText('slide_3_title', 'Literasi & Numerasi') }}
                </h3>
                <p :class="getClass('slide_3_subtitle')" data-animation="fadeInLeft" data-delay=".75s">
                   {{ getText('slide_3_subtitle', 'Membaca setiap hari dan pendampingan berhitung terstruktur.') }}
                </p>
                <div class="hero-btn" data-animation="fadeInUp" data-delay="1s" v-if="getText('slide_3_button_text')">
                  <a :href="getText('slide_3_button_link', '#')" class="theme-btn">{{ getText('slide_3_button_text', 'Selengkapnya') }}<i class="fas fa-arrow-right-long"></i></a>
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
import { onMounted } from 'vue'

const props = defineProps({
    data: {
        type: Object,
        default: () => ({})
    },
    activeField: {
        type: String,
        default: null
    }
});

const asset = (path) => `/${path}`;

const getImage = (key, defaultPath) => {
    if (props.data[key] instanceof File) {
        return URL.createObjectURL(props.data[key]);
    }
    if (props.data[key]) {
        return `/storage/${props.data[key]}`;
    }
    return asset(defaultPath);
}

const getText = (key, defaultText) => {
    return props.data[key] || defaultText;
}

const getClass = (key) => {
    return props.activeField === key ? 'highlight-active' : '';
}

const slideStyle = (key, defaultImage) => {
  return {
    background: `url(${getImage(key, `assets/img/hope/${defaultImage}`)})`,
    height: '500px',
    backgroundSize: 'cover',
    backgroundPosition: 'center'
  }
}
// Remove onMounted logic for now to prevent conflict with Vue reactivity if using v-for later? 
// Actually OwlCarousel might break if DOM changes.
// Keep it simple: Hardcode 3 slides that use dynamic data.

onMounted(() => {
  if (window.$ && window.$.fn && window.$.fn.owlCarousel) {
    $('.hero-slider').owlCarousel({
      items: 1,
      loop: true,
      nav: true,
      dots: true,
      autoplay: true,
      autoplayTimeout: 5000,
      animateOut: 'fadeOut',
      animateIn: 'fadeIn'
    })
  }
})
</script>

<style scoped>
.hero-section {
    margin-top: 50px; /* Adjust based on header height */
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
.highlight-active {
    outline: 5px solid #ffc107;
    z-index: 100;
}
</style>
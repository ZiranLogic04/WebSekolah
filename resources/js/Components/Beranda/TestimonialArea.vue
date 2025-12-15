<template>
  <div class="testimonial-section ts-bg pt-80 pb-80">
    <div class="container">
      <div class="row">
        <div class="col-lg-6 mx-auto">
          <div class="site-heading text-center">
            <span class="site-title-tagline"><i class="fa fa-book-open"></i> {{ getText('subtitle', 'Testimoni') }}</span>
            <h2 class="site-title text-white" v-html="getText('title', 'Apa Kata <span>Mereka</span>')"></h2>
            <p class="text-white">{{ getText('desc', 'Cerita singkat dari orang tua, siswa, dan alumni tentang pengalaman belajar di MI Al-Hikmah.') }}</p>
          </div>
        </div>
      </div>

      <div class="testimonial-slider owl-carousel owl-theme">
        <!-- 1. Testimoni 1 -->
        <div class="testimonial-item">
          <div class="testimonial-rate">
            <i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i>
          </div>
          <div class="testimonial-quote">
            <p>{{ getText('testi_1_text', 'Guru-gurunya sangat peduli. Anak saya jadi semangat belajar.') }}</p>
          </div>
          <div class="testimonial-content">
            <div class="testimonial-author-img">
              <img :src="getImage('testi_1_img', 'assets/img/testimonial/01.jpg')" alt="Testimonial 1">
            </div>
            <div class="testimonial-author-info">
              <h4>{{ getText('testi_1_name', 'Ibu Rina') }}</h4>
              <p>{{ getText('testi_1_role', 'Orang Tua/Wali') }}</p>
            </div>
          </div>
          <span class="testimonial-quote-icon"><i class="fa fa-quote-right"></i></span>
        </div>

        <!-- 2. Testimoni 2 -->
        <div class="testimonial-item">
          <div class="testimonial-rate">
            <i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i>
          </div>
          <div class="testimonial-quote">
            <p>{{ getText('testi_2_text', 'Belajarnya seru, ada literasi 15 menit tiap pagi.') }}</p>
          </div>
          <div class="testimonial-content">
            <div class="testimonial-author-img">
              <img :src="getImage('testi_2_img', 'assets/img/testimonial/02.jpg')" alt="Testimonial 2">
            </div>
            <div class="testimonial-author-info">
              <h4>{{ getText('testi_2_name', 'Rafi') }}</h4>
              <p>{{ getText('testi_2_role', 'Siswa') }}</p>
            </div>
          </div>
          <span class="testimonial-quote-icon"><i class="fa fa-quote-right"></i></span>
        </div>

        <!-- 3. Testimoni 3 -->
        <div class="testimonial-item">
          <div class="testimonial-rate">
            <i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i>
          </div>
          <div class="testimonial-quote">
            <p>{{ getText('testi_3_text', 'Dasar literasi dan numerasi yang kuat membantu saya di SMP.') }}</p>
          </div>
          <div class="testimonial-content">
            <div class="testimonial-author-img">
              <img :src="getImage('testi_3_img', 'assets/img/testimonial/03.jpg')" alt="Testimonial 3">
            </div>
            <div class="testimonial-author-info">
              <h4>{{ getText('testi_3_name', 'Naila') }}</h4>
              <p>{{ getText('testi_3_role', 'Alumni') }}</p>
            </div>
          </div>
          <span class="testimonial-quote-icon"><i class="fa fa-quote-right"></i></span>
        </div>

        <!-- 4. Testimoni 4 -->
        <div class="testimonial-item">
          <div class="testimonial-rate">
            <i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i>
          </div>
          <div class="testimonial-quote">
            <p>{{ getText('testi_4_text', 'Komunikasi sekolah–orang tua sangat baik.') }}</p>
          </div>
          <div class="testimonial-content">
            <div class="testimonial-author-img">
              <img :src="getImage('testi_4_img', 'assets/img/testimonial/04.jpg')" alt="Testimonial 4">
            </div>
            <div class="testimonial-author-info">
              <h4>{{ getText('testi_4_name', 'Pak Hendra') }}</h4>
              <p>{{ getText('testi_4_role', 'Komite Sekolah') }}</p>
            </div>
          </div>
          <span class="testimonial-quote-icon"><i class="fa fa-quote-right"></i></span>
        </div>

        <!-- 5. Testimoni 5 -->
        <div class="testimonial-item">
          <div class="testimonial-rate">
            <i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i>
          </div>
          <div class="testimonial-quote">
            <p>{{ getText('testi_5_text', 'Sekolahnya ramah anak, ada UKS dan satgas anti-bullying.') }}</p>
          </div>
          <div class="testimonial-content">
            <div class="testimonial-author-img">
              <img :src="getImage('testi_5_img', 'assets/img/testimonial/05.jpg')" alt="Testimonial 5">
            </div>
            <div class="testimonial-author-info">
              <h4>{{ getText('testi_5_name', 'Ibu Desti') }}</h4>
              <p>{{ getText('testi_5_role', 'Orang Tua') }}</p>
            </div>
          </div>
          <span class="testimonial-quote-icon"><i class="fa fa-quote-right"></i></span>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { onMounted } from 'vue'

const props = defineProps({
    data: Object
});

const asset = (path) => `/${path}`;
const getText = (key, defaultVal) => props.data?.[key] || defaultVal;
const getImage = (key, defaultPath) => {
    if (props.data?.[key]) {
        return `/storage/${props.data[key]}`;
    }
    return asset(defaultPath);
}

onMounted(() => {
    if ($('.testimonial-slider').length > 0) {
        $('.testimonial-slider').owlCarousel({
            loop: true,
            margin: 20,
            nav: false,
            dots: true,
            autoplay: true,
            responsive: {
                0: {
                    items: 1
                },
                600: {
                    items: 2
                },
                1000: {
                    items: 3
                }
            }
        });
    }
});
</script>
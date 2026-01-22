<template>
  <div class="hero-section">
    <div class="hero-slider owl-carousel">
      
      <!-- Dinamis Slider dari Database -->
      <div v-for="slider in sliders" :key="slider.id" class="hero-single" :style="{ background: `url(${getBgImage(slider.image)})`, height: '500px', backgroundSize: 'cover', backgroundPosition: `center ${slider.image_position ?? 50}%` }">
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

      <!-- Fallback Slider (Default Static) jika data kosong -->


    </div>
  </div>
</template>

<script setup>
import { onMounted, nextTick, watch } from 'vue'

const props = defineProps({
    sliders: Array
});

const getBgImage = (path) => {
    if (!path) return '';
    // If it starts with http or /, return as is (external or absolute path like /assets)
    if (path.startsWith('http') || path.startsWith('/')) {
        return path;
    }
    // Otherwise assume it's stored in storage link
    return `/storage/${path}`;
};

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
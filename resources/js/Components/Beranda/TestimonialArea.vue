<template>
  <div class="testimonial-section ts-bg pt-80 pb-80">
    <div class="container">
      <div class="row">
        <div class="col-lg-6 mx-auto">
          <div class="site-heading text-center">
            <span class="site-title-tagline"><i class="fa fa-book-open"></i> {{ content.tagline || '' }}</span>
            <h2 class="site-title text-white" v-html="content.title || ''"></h2>
            <p class="text-white">{{ content.subtitle || '' }}</p>
          </div>
        </div>
      </div>

      <div class="testimonial-slider owl-carousel owl-theme">
        <div v-for="(item, index) in items" :key="index" class="testimonial-item">
          <div class="testimonial-rate">
            <i v-for="star in item.rating" :key="'star-'+star" class="fas fa-star"></i>
          </div>
          <div class="testimonial-quote">
            <p>{{ item.quote }}</p>
          </div>
          <div class="testimonial-content">
            <div class="testimonial-author-img">
              <img :src="item.image || '/assets/img/testimonial/default.jpg'" :alt="item.name">
            </div>
            <div class="testimonial-author-info">
              <h4>{{ item.name }}</h4>
              <p>{{ item.role }}</p>
            </div>
          </div>
          <span class="testimonial-quote-icon"><i class="fa fa-quote-right"></i></span>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { computed, onMounted } from 'vue'

const props = defineProps({
  section: {
    type: Object,
    default: () => ({})
  }
});

const content = computed(() => props.section?.content || {});
const items = computed(() => content.value.items || []);

onMounted(() => {
  if (window.$ && window.$.fn && window.$.fn.owlCarousel) {
    setTimeout(() => {
      $('.testimonial-slider').owlCarousel({
        items: 3,
        loop: true,
        nav: true,
        dots: true,
        autoplay: true,
        autoplayTimeout: 5000,
        responsive: {
          0: { items: 1 },
          768: { items: 2 },
          992: { items: 3 }
        }
      })
    }, 100);
  }
})
</script>
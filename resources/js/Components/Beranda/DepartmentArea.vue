<template>
  <div class="program-section bg pt-10">
    <div class="container">
      <div class="row">
        <div class="col-lg-6 mx-auto">
          <div class="site-heading text-center">
            <span class="site-title-tagline"><i class="fa fa-book-open"></i> {{ headerData.tagline }}</span>
            <h6 class="site-title" v-html="headerData.title"></h6>
            <p>{{ headerData.description }}</p>
          </div>
        </div>
      </div>

      <div class="program-slider owl-carousel owl-theme">
        <div v-for="(item, index) in programItems" :key="index" class="program-item">
          <div class="program-icon">
            <img :src="`/assets/img/icon/${item.icon}`" :alt="item.title">
          </div>
          <div class="program-info">
            <h4 class="program-title">
              <a :href="item.url || '#'">{{ item.title }}</a>
            </h4>
            <p>{{ item.desc }}</p>
            <div v-if="item.url" class="program-btn">
              <a :href="item.url">Selengkapnya<i class="fas fa-arrow-right-long"></i></a>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { computed, onMounted, nextTick, watch } from 'vue'

const props = defineProps({
  program: {
    type: Object,
    default: () => ({})
  }
});

const headerData = computed(() => ({
  tagline: props.program?.tagline || '',
  title: props.program?.title || '',
  description: props.program?.description || '',
}));

const programItems = computed(() => {
  return props.program?.items || [];
});

const initOwl = () => {
    if (window.$ && window.$.fn && window.$.fn.owlCarousel) {
        $('.program-slider').trigger('destroy.owl.carousel');
        $('.program-slider').owlCarousel({
            items: 3,
            loop: true,
            nav: true,
            dots: true,
            autoplay: true,
            autoplayTimeout: 5000,
            margin: 25,
            responsive: {
                0: { items: 1 },
                576: { items: 2 },
                992: { items: 3 }
            }
        });
    }
};

onMounted(() => {
    nextTick(() => {
        initOwl();
    });
});

watch(programItems, () => {
    nextTick(() => {
        initOwl();
    });
});
</script>

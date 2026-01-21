<template>
  <div class="partner-section bg pt-60 pb-60">
    <div class="container">
      <!-- Partner Logos -->
      <div class="partner-wrapper partner-slider owl-carousel owl-theme">
        <div v-for="(item, index) in items" :key="index" class="partner-item">
          <img :src="item.image" :alt="item.name" :title="item.name">
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
      $('.partner-slider').owlCarousel({
        items: 5,
        loop: true,
        nav: false,
        dots: false,
        autoplay: true,
        autoplayTimeout: 3000,
        responsive: {
          0: { items: 2 },
          576: { items: 3 },
          768: { items: 4 },
          992: { items: 5 }
        }
      })
    }, 100);
  }
})
</script>

<style scoped>
.partner-section {
  background: #f8f9fa;
}

.partner-item {
  display: flex;
  align-items: center;
  justify-content: center;
  padding: 20px 30px;
  min-height: 150px;
}

.partner-item img {
  height: 150px;
  width: auto;
  object-fit: contain;
  transition: all 0.3s ease;
}

.partner-item img:hover {
  transform: scale(1.1);
}
</style>
<template>
  <div class="activities-section pt-20">
    <div class="container">
      <div class="row">
        <div class="col-lg-6 mx-auto">
          <div class="site-heading text-center">
            <span class="site-title-tagline"><i class="fa fa-book-open"></i> {{ tagline }}</span>
            <h2 class="site-title" v-html="title"></h2>
          </div>
        </div>
      </div>

      <div class="row">
        <div v-for="(item, index) in items" :key="index" class="col-md-4 mb-4">
          <div class="activities-item">
            <div class="activities-img">
              <img :src="item.image" style="height:300px; object-fit: cover; width: 100%;" :alt="item.title">
            </div>
            <div class="activities-content">
              <div class="activities-info">
                <div class="activities-title-info">
                  <h5 class="activities-subtitle"><span>//</span> {{ item.category }}</h5>
                  <a :href="item.url">
                    <h4 class="activities-title">{{ item.title }}</h4>
                  </a>
                </div>
                <a :href="item.url" class="activities-btn"><i class="fa fa-arrow-right"></i></a>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { computed } from 'vue';

const props = defineProps({
    portfolio: Object
});

const content = computed(() => props.portfolio || {});
const tagline = computed(() => content.value.tagline || 'Kehidupan di MI');
const title = computed(() => content.value.title || 'Kegiatan di <span>MI Al-Hikmah</span>');
const items = computed(() => content.value.items || [
        {
            category: 'Literasi',
            title: 'Budaya Literasi 15 Menit',
            url: '#',
            image: '/assets/img/hope/stRe.jpg'
        },
        {
            category: 'Keagamaan',
            title: 'Tahfiz & BTQ',
            url: '#',
            image: '/assets/img/hope/aka465.jpg'
        },
        {
            category: 'Ekskul',
            title: 'Olahraga & Pramuka',
            url: '#',
            image: '/assets/img/hope/r456.jpg'
        },
        {
            category: 'Ramah Anak',
            title: 'Sekolah Ramah Anak',
            url: '#',
            image: '/assets/img/hope/ra123k.jpg'
        },
        {
            category: 'Keagamaan',
            title: 'Pembiasaan Ibadah Harian',
            url: '#',
            image: '/assets/img/hope/REMISE UEA (29).jpg'
        },
        {
            category: 'Sarpras',
            title: 'Fasilitas Sekolah',
            url: '#',
            image: '/assets/img/hope/IMG_1135 -BG (1).jpg'
        },
]);
</script>
<template>
  <div class="highlight-section fa-negative">
    <div class="col-xl-9 ms-auto">
      <div class="highlight-wrapper">
        <div class="row g-4">
          <div v-for="(feature, index) in displayFeatures" :key="feature.id || index" class="col-md-6 col-lg-3">
            <div class="highlight-item">
              <span class="count">0{{ index + 1 }}</span>
              <div class="highlight-icon">
                <img :src="`/assets/img/icon/${feature.icon}`" alt="">
              </div>
              <div class="highlight-content">
                <h4 class="highlight-title">{{ feature.title }}</h4>
                <p>{{ feature.description }}</p>
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
  features: {
    type: Array,
    default: () => []
  }
});

// Default features jika data kosong
const defaultFeatures = [
  { icon: 'book.svg', title: 'Pendidikan Bermutu', description: 'Kualitas pembelajaran unggul dengan pendekatan islami dan lingkungan belajar yang inspiratif.' },
  { icon: 'teacher.svg', title: 'Guru', description: 'Tenaga pendidik kompeten, berdedikasi, dan menjadi teladan dalam ilmu serta akhlak.' },
  { icon: 'library.svg', title: 'Kurikulum Terpadu', description: 'Mengintegrasikan ilmu umum dan agama untuk membentuk siswa cerdas, beriman, dan berkarakter.' },
  { icon: 'building-2.svg', title: 'Fasilitas dan Kegiatan', description: 'Sarana lengkap dan kegiatan edukatif yang menumbuhkan potensi akademik, sosial, dan spiritual siswa.' },
];

const displayFeatures = computed(() => {
  return props.features && props.features.length > 0 ? props.features : defaultFeatures;
});
</script>
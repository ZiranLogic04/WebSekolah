<template>
  <div class="choose-section pt-80 pb-80">
    <div class="container">
      <div class="row align-items-center">
        <div class="col-lg-6">
          <div class="choose-content wow fadeInUp" data-wow-delay=".25s">
            <div class="choose-content-info">
              <div class="site-heading mb-0">
                <span class="site-title-tagline">
                  <i class="fa fa-book-open"></i> {{ chooseData.tagline }}
                </span>
                <h2 class="site-title text-white mb-10" v-html="chooseData.title"></h2>
                <p class="text-white">
                  {{ chooseData.description }}
                </p>
              </div>

              <div class="choose-content-wrap">
                <div class="row g-4">
                  <div class="col-md-6" v-for="(item, index) in chooseItems" :key="index">
                    <div class="choose-item">
                      <div class="choose-item-icon">
                        <img :src="getIconUrl(item.icon)" :alt="item.title">
                      </div>
                      <div class="choose-item-info">
                        <h4>{{ item.title }}</h4>
                        <p>{{ item.desc }}</p>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- Gambar kanan -->
        <div class="col-lg-6">
          <div class="choose-img wow fadeInRight" data-wow-delay=".25s">
            <img :src="chooseData.image" alt="Why Choose Us">
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { computed } from 'vue';

const props = defineProps({
    choose: {
        type: Object,
        default: () => ({})
    }
});

const defaultContent = {
    tagline: 'Mengapa Memilih Kami',
    title: 'Pendidikan Dasar Berkualitas & <span>Ramah Anak</span>',
    description: 'MI Al-Hikmah berkomitmen menghadirkan pembelajaran yang menumbuhkan iman, ilmu, dan karakter. Program terstruktur, guru kompeten, serta lingkungan aman–nyaman mendukung tumbuh kembang siswa setiap hari.',
    image: '/assets/img/hope/_U9A7765.jpg',
    items: [
        { title: 'Guru Peduli & Kompeten', desc: 'Pembelajaran aktif, pendampingan personal.', icon: 'teacher-2.svg' },
        { title: 'Tahfiz & BTQ Terstruktur', desc: 'Tahsin bacaan, target juz 30 bertahap.', icon: 'course-material.svg' },
        { title: 'Literasi & Klinik Numerasi', desc: 'Membaca 15 menit setiap hari.', icon: 'online-course.svg' },
        { title: 'Sekolah Ramah Anak', desc: 'Satgas Anti-Bullying, UKS, dan komunikasi.', icon: 'health.svg' },
    ]
};

const getIconUrl = (iconStr) => {
    if (!iconStr) return '/assets/img/icon/teacher-2.svg';
    if (iconStr.includes('/')) return iconStr; // Handle full path legacy
    return '/assets/img/icon/' + iconStr; // Handle filename only
};

const chooseData = computed(() => ({
    tagline: props.choose?.tagline || defaultContent.tagline,
    title: props.choose?.title || defaultContent.title,
    description: props.choose?.description || defaultContent.description,
    image: props.choose?.image || defaultContent.image,
}));

const chooseItems = computed(() => {
    return (props.choose?.items && props.choose.items.length > 0)
        ? props.choose.items
        : defaultContent.items;
});
</script>

<style scoped>
/* NOTE: User wants exact original image aspect - NO FIXED HEIGHT */
.choose-img {
    /* Ensure container lets image size naturally */
    height: auto;
}

.choose-img img {
    width: 100%;
    /* No fixed height, let it be natural aspect ratio like original */
    height: auto; /* Fix for distorted image */
    object-fit: contain; 
}
</style>

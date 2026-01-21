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

// Default header values
const defaultHeader = {
  tagline: 'Program MI',
  title: 'Jelajahi <span>Program & Layanan</span> Kami',
  description: 'Program pembelajaran dan layanan siswa di MI Al-Hikmah yang menumbuhkan iman, ilmu, karakter, literasi, dan numerasi dalam lingkungan yang ramah anak.',
};

// Default items
const defaultItems = [
  { icon: 'monitor.svg', title: 'Kurikulum & Pembelajaran', desc: 'Kurikulum Merdeka/K13, jadwal pelajaran, penilaian, pengayaan & remedial.', url: '/akademik/kurikulum' },
  { icon: 'art.svg', title: 'Tahfiz & BTQ', desc: 'Tahsin bacaan, target juz 30, setoran berkala, dan pembiasaan ibadah harian.', url: '/program/tahfiz' },
  { icon: 'data.svg', title: 'Literasi 15 Menit & Perpustakaan', desc: 'Membaca harian, pojok baca kelas, peminjaman buku, dan lomba literasi.', url: '/program/literasi' },
  { icon: 'monitor.svg', title: 'Klinik Numerasi & Sains Dasar', desc: 'Pendampingan berhitung, permainan numerasi, dan percobaan sains sederhana.', url: '/program/numerasi' },
  { icon: 'teacher-2.svg', title: 'Kesiswaan & Ekstrakurikuler', desc: 'Pramuka, olahraga, seni, MTQ, drumband, dan kegiatan penguatan karakter.', url: '/kesiswaan/ekskul' },
  { icon: 'health.svg', title: 'Layanan Siswa (UKS & Anti-Bullying)', desc: 'UKS, edukasi PHBS, Satgas Anti-Bullying, dan alur pengaduan yang aman.', url: '/layanan/anak' },
];

const headerData = computed(() => ({
  tagline: props.program?.tagline || defaultHeader.tagline,
  title: props.program?.title || defaultHeader.title,
  description: props.program?.description || defaultHeader.description,
}));

const programItems = computed(() => {
  return (props.program?.items && props.program.items.length > 0) 
    ? props.program.items 
    : defaultItems;
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

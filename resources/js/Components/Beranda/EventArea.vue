<template>
  <div class="event-section bg pt-20">
    <div class="container">
      <div class="row">
        <div class="col-lg-6 mx-auto">
          <div class="site-heading text-center">
            <span class="site-title-tagline"><i class="fa fa-book-open"></i> {{ tagline }}</span>
            <h2 class="site-title" v-html="title"></h2>
          </div>
        </div>
      </div>

      <div class="event-slider owl-carousel owl-theme">
        <div v-for="(item, index) in items" :key="index" class="event-item">
          <div class="event-location" v-if="item.location">
            <span><i class="fa fa-map-marker-alt me-1"></i>{{ item.location }}</span>
          </div>
          <div class="event-img">
            <img :src="item.image || '/assets/img/placeholder.jpg'" :alt="item.title" style="height:250px; width: 100%; object-fit: cover;">
          </div>
          <div class="event-info">
            <div class="event-meta">
              <span class="event-date" v-if="item.date"><i class="far fa-calendar-alt"></i>{{ formatDate(item.date) }}</span>
              <span class="event-time" v-if="item.time_start || item.time_end || item.time">
                <i class="far fa-clock"></i>{{ formatTime(item) }}
              </span>
            </div>
            <h4 class="event-title"><a :href="item.link">{{ item.title }}</a></h4>
            <p>{{ item.description }}</p>
            <div class="event-btn">
              <a :href="item.link" class="theme-btn">Selengkapnya<i class="fas fa-arrow-right-long"></i></a>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { onMounted, computed } from 'vue'

const props = defineProps({
    event: Object
});

const content = computed(() => props.event || {});

const tagline = computed(() => content.value.tagline || 'Kegiatan');
const title = computed(() => content.value.title || 'Agenda <span>Terbaru</span>');

const defaults = [
    {
        location: 'Aula MI Al-Hikmah',
        date: '12 Okt 2025',
        time: '08.00–10.30 WIB',
        title: 'Sosialisasi PPDB 2026',
        description: 'Pemaparan syarat, alur pendaftaran, kuota, dan biaya pendidikan kepada orang tua/wali.',
        link: '/ppdb',
        image: '/assets/img/hope/ogg.jpg'
    },
    {
        location: 'Perpustakaan',
        date: '04 Okt 2025',
        time: '09.00–11.00 WIB',
        title: 'Launching Pojok Baca & Literasi 15 Menit',
        description: 'Gerakan membaca harian dan peminjaman buku untuk memperkuat budaya literasi siswa.',
        link: '/program/literasi',
        image: '/assets/img/hope/IMG_1135 -BG.jpg'
    },
    {
        location: 'Masjid Sekolah',
        date: '28 Sep 2025',
        time: '07.30–09.30 WIB',
        title: 'Setoran Tahfiz Akbar & Khataman',
        description: 'Evaluasi capaian juz 30, muroja\'ah bersama, dan apresiasi untuk peserta terbaik.',
        link: '/program/tahfiz',
        image: '/assets/img/hope/REMISE UEA (29).jpg'
    },
    {
        location: 'Lapangan MI',
        date: '21 Sep 2025',
        time: '13.00–17.00 WIB',
        title: 'Perkemahan & Pelantikan Pramuka',
        description: 'Penguatan kemandirian, kerja sama, dan disiplin melalui aktivitas kepramukaan.',
        link: '/kesiswaan/ekskul',
        image: '/assets/img/hope/h1a.jpg'
    }
];

const items = computed(() => content.value.items || defaults);

const formatDate = (dateString) => {
    if (!dateString) return '';
    // If it looks like '12 Okt 2025' (legacy), return as is
    if (dateString.match(/[a-zA-Z]/)) return dateString;
    
    // Parse YYYY-MM-DD
    const date = new Date(dateString);
    if (isNaN(date.getTime())) return dateString;

    return new Intl.DateTimeFormat('id-ID', { day: '2-digit', month: 'short', year: 'numeric' }).format(date);
};

// Format time range for display (HH:MM to HH.MM format)
const formatTime = (item) => {
    // Convert HH:MM to HH.MM for display
    const formatSingleTime = (t) => t ? t.replace(':', '.') : '';
    
    if (item.time_start && item.time_end) {
        return `${formatSingleTime(item.time_start)} - ${formatSingleTime(item.time_end)} WIB`;
    } else if (item.time) {
        // Legacy format (already formatted like "08.00–10.30 WIB")
        return item.time;
    } else if (item.time_start) {
        return `${formatSingleTime(item.time_start)} WIB`;
    } else if (item.time_end) {
        return `${formatSingleTime(item.time_end)} WIB`;
    }
    return '';
};

onMounted(() => {
  if (window.$ && window.$.fn && window.$.fn.owlCarousel) {
    // Timeout ensuring vue render finishes
    setTimeout(() => {
        $('.event-slider').owlCarousel({
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
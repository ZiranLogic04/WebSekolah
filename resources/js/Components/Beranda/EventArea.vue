<template>
  <div class="event-section bg pt-20">
    <div class="container">
      <div class="row">
        <div class="col-lg-6 mx-auto">
          <div class="site-heading text-center">
            <span class="site-title-tagline"><i class="fa fa-book-open"></i> Kegiatan</span>
            <h2 class="site-title">Agenda <span>Terbaru</span></h2>
          </div>
        </div>
      </div>

      <div class="event-slider owl-carousel owl-theme">
        <!-- 1) Event 1 -->
        <div class="event-item">
          <div class="event-location">
            <span><i class="fa fa-map-marker-alt me-1"></i>{{ getText('event_1_loc', 'Aula MI Al-Hikmah') }}</span>
          </div>
          <div class="event-img">
            <img :src="getImage('event_1_img', 'assets/img/hope/ogg.jpg')" alt="Event 1" style="height:250px; object-fit: cover;">
          </div>
          <div class="event-info">
            <div class="event-meta">
              <span class="event-date"><i class="far fa-calendar-alt"></i>{{ getText('event_1_date', '12 Okt 2025') }}</span>
              <span class="event-time"><i class="far fa-clock"></i>{{ getText('event_1_time', '08.00–10.30 WIB') }}</span>
            </div>
            <h4 class="event-title">{{ getText('event_1_title', 'Sosialisasi PPDB 2026') }}</h4>
            <p>{{ getText('event_1_desc', 'Pemaparan syarat, alur pendaftaran, kuota, dan biaya pendidikan kepada orang tua/wali.') }}</p>
            <div class="event-btn">
              <Link :href="getText('event_1_link', '#')" class="theme-btn">Detail Kegiatan<i class="fas fa-arrow-right-long"></i></Link>
            </div>
          </div>
        </div>

        <!-- 2) Event 2 -->
        <div class="event-item">
          <div class="event-location">
            <span><i class="fa fa-map-marker-alt me-1"></i>{{ getText('event_2_loc', 'Perpustakaan') }}</span>
          </div>
          <div class="event-img">
            <img :src="getImage('event_2_img', 'assets/img/hope/IMG_1135%20-BG.jpg')" alt="Event 2" style="height:250px; object-fit: cover;">
          </div>
          <div class="event-info">
            <div class="event-meta">
              <span class="event-date"><i class="far fa-calendar-alt"></i>{{ getText('event_2_date', '04 Okt 2025') }}</span>
              <span class="event-time"><i class="far fa-clock"></i>{{ getText('event_2_time', '09.00–11.00 WIB') }}</span>
            </div>
            <h4 class="event-title"><Link :href="getText('event_2_link', '#')">{{ getText('event_2_title', 'Launching Pojok Baca') }}</Link></h4>
            <p>{{ getText('event_2_desc', 'Gerakan membaca harian dan peminjaman buku untuk memperkuat budaya literasi siswa.') }}</p>
            <div class="event-btn">
              <Link :href="getText('event_2_link', '#')" class="theme-btn">Selengkapnya<i class="fas fa-arrow-right-long"></i></Link>
            </div>
          </div>
        </div>

        <!-- 3) Event 3 -->
        <div class="event-item">
          <div class="event-location">
             <span><i class="fa fa-map-marker-alt me-1"></i>{{ getText('event_3_loc', 'Masjid Sekolah') }}</span>
          </div>
          <div class="event-img">
            <img :src="getImage('event_3_img', 'assets/img/hope/REMISE%20UEA%20%2829%29.jpg')" alt="Event 3" style="height:250px; object-fit: cover;">
          </div>
          <div class="event-info">
            <div class="event-meta">
              <span class="event-date"><i class="far fa-calendar-alt"></i>{{ getText('event_3_date', '28 Sep 2025') }}</span>
              <span class="event-time"><i class="far fa-clock"></i>{{ getText('event_3_time', '07.30–09.30 WIB') }}</span>
            </div>
            <h4 class="event-title"><Link :href="getText('event_3_link', '#')">{{ getText('event_3_title', 'Setoran Tahfiz Akbar') }}</Link></h4>
            <p>{{ getText('event_3_desc', 'Evaluasi capaian juz 30, muroja\'ah bersama, dan apresiasi untuk peserta terbaik.') }}</p>
            <div class="event-btn">
              <Link :href="getText('event_3_link', '#')" class="theme-btn">Selengkapnya<i class="fas fa-arrow-right-long"></i></Link>
            </div>
          </div>
        </div>

        <!-- 4) Event 4 -->
        <div class="event-item">
          <div class="event-location">
             <span><i class="fa fa-map-marker-alt me-1"></i>{{ getText('event_4_loc', 'Lapangan MI') }}</span>
          </div>
          <div class="event-img">
            <img :src="getImage('event_4_img', 'assets/img/hope/h1a.jpg')" alt="Event 4" style="height:250px; object-fit: cover;">
          </div>
          <div class="event-info">
            <div class="event-meta">
              <span class="event-date"><i class="far fa-calendar-alt"></i>{{ getText('event_4_date', '21 Sep 2025') }}</span>
              <span class="event-time"><i class="far fa-clock"></i>{{ getText('event_4_time', '13.00–17.00 WIB') }}</span>
            </div>
            <h4 class="event-title"><Link :href="getText('event_4_link', '#')">{{ getText('event_4_title', 'Perkemahan Pramuka') }}</Link></h4>
            <p>{{ getText('event_4_desc', 'Penguatan kemandirian, kerja sama, dan disiplin melalui aktivitas kepramukaan.') }}</p>
            <div class="event-btn">
              <Link :href="getText('event_4_link', '#')" class="theme-btn">Selengkapnya<i class="fas fa-arrow-right-long"></i></Link>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { Link } from '@inertiajs/vue3'
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
    if ($('.event-slider').length > 0) {
        $('.event-slider').owlCarousel({
            loop: true,
            margin: 20,
            nav: true,
            dots: true,
            autoplay: false,
            navText: [
                "<i class='fas fa-angle-left'></i>",
                "<i class='fas fa-angle-right'></i>"
            ],
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
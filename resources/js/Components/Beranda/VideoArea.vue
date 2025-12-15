<template>
  <div class="profile-section" style="padding-top: 100px; padding-bottom: 100px;">
    <div class="container">
      <div class="row g-4">
        <!-- Kiri: judul & deskripsi -->
        <div class="col-lg-4 wow fadeInLeft" data-wow-delay=".25s">
          <div class="site-heading mb-3">
            <span class="site-title-tagline"><i class="fa fa-book-open"></i> Video</span>
            <h2 class="site-title" v-html="getText('video_title', 'Profil <span>MI Al-Hikmah</span>')"></h2>
          </div>
          <p class="about-text">
            {{ getText('video_desc', 'Sekilas tentang MI Al-Hikmah: suasana belajar, pembiasaan ibadah, literasi & numerasi, serta fasilitas sekolah.') }}
          </p>
          <Link :href="getText('video_btn_link', '/profil')" class="theme-btn mt-30">
            {{ getText('video_btn_text', 'Profil Madrasah') }}<i class="fas fa-arrow-right-long"></i>
          </Link>
        </div>

        <!-- Kanan: poster + tombol play -->
        <div class="col-lg-8 wow fadeInRight" data-wow-delay=".25s">
          <div class="video-content has-overlay" :style="`background-image:url('${getImage('video_poster', 'assets/img/hope/poster-profil.jpg')}');`">
            <div class="row align-items-center">
              <div class="col-lg-12">
                <div class="video-wrapper">
                  <a class="play-btn popup-youtube" :href="getText('video_url', 'https://www.youtube.com/watch?v=VIDEO_ID_PROFIL')" aria-label="Putar video Profil MI Al-Hikmah">
                    <i class="fas fa-play" aria-hidden="true"></i>
                  </a>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { Link } from '@inertiajs/vue3'

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
</script>
<template>
  <div class="profile-section">
    <div class="container">
      <div class="row g-4">
        <!-- Kiri: judul & deskripsi -->
        <div class="col-lg-4 wow fadeInLeft" data-wow-delay=".25s">
          <div class="site-heading mb-3">
            <span class="site-title-tagline"><i class="fa fa-book-open"></i> {{ videoData.tagline }}</span>
            <h2 class="site-title" v-html="videoData.title"></h2>
          </div>
          <p class="about-text">
            {{ videoData.description }}
          </p>
          <a :href="videoData.button_url" class="theme-btn mt-30">{{ videoData.button_text }}<i class="fas fa-arrow-right-long"></i></a>
        </div>

        <!-- Kanan: poster + tombol play -->
        <div class="col-lg-8 wow fadeInRight" data-wow-delay=".25s">
          <div class="video-content has-overlay" :style="{ backgroundImage: `url('${videoData.poster_image}')` }">
            <div class="row align-items-center">
              <div class="col-lg-12">
                <div class="video-wrapper">
                  <a class="play-btn popup-youtube" :href="videoData.youtube_url" aria-label="Putar video">
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
import { computed } from 'vue'

const props = defineProps({
  video: {
    type: Object,
    default: () => ({})
  }
});

// Default values
const defaultData = {
  tagline: 'Video',
  title: 'Profil <span>MI Al-Hikmah</span>',
  description: 'Sekilas tentang MI Al-Hikmah: suasana belajar, pembiasaan ibadah, literasi & numerasi, serta fasilitas sekolah.',
  button_text: 'Profil Madrasah',
  button_url: '/profil',
  poster_image: '/assets/img/hope/poster-profil.jpg',
  youtube_url: 'https://www.youtube.com/watch?v=VIDEO_ID_PROFIL',
};

const videoData = computed(() => ({
  tagline: props.video?.tagline || defaultData.tagline,
  title: props.video?.title || defaultData.title,
  description: props.video?.description || defaultData.description,
  button_text: props.video?.button_text || defaultData.button_text,
  button_url: props.video?.button_url || defaultData.button_url,
  poster_image: props.video?.poster_image || defaultData.poster_image,
  youtube_url: props.video?.youtube_url || defaultData.youtube_url,
}));
</script>
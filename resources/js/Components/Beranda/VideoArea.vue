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
const videoData = computed(() => ({
  tagline: props.video?.tagline || '',
  title: props.video?.title || '',
  description: props.video?.description || '',
  button_text: props.video?.button_text || '',
  button_url: props.video?.button_url || '#',
  poster_image: props.video?.poster_image || '',
  youtube_url: props.video?.youtube_url || '#',
}));
</script>
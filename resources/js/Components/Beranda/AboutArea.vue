<template>
  <div class="about-section py-90">
    <div class="container">
      <div class="row g-4 align-items-center">
        <div class="col-lg-6">
          <div class="about-left wow fadeInLeft" data-wow-delay=".25s">
            <div class="about-img">
              <div class="row g-4">
                <div class="col-md-6">
                  <img class="img-1" :src="aboutData.image_1" :alt="aboutData.title">
                  <div class="about-experience mt-4">
                    <div class="about-experience-icon">
                      <img src="/assets/img/icon/exchange-idea.svg" alt="">
                    </div>
                    <b class="text-start">{{ lamaBerdiri }} Tahun <br> Telah Berdiri</b>
                  </div>
                </div>
                <div class="col-md-6">
                  <img class="img-2" :src="aboutData.image_2" :alt="aboutData.title">
                  <img class="img-3 mt-4" :src="aboutData.image_3" :alt="aboutData.title">
                </div>
              </div>
            </div>
          </div>
        </div>

        <div class="col-lg-6">
          <div class="about-right wow fadeInRight" data-wow-delay=".25s">
            <div class="site-heading mb-3">
              <span class="site-title-tagline"><i class="fa fa-book-open"></i> {{ aboutData.tagline }}</span>
              <h2 class="site-title" v-html="formattedTitle"></h2>
            </div>

            <p class="about-text">{{ aboutData.description }}</p>

            <div class="about-content">
              <div class="row">
                <div class="col-md-7">
                  <!-- Item 1 -->
                  <div class="about-item">
                    <div class="about-item-icon">
                      <img :src="`/assets/img/icon/${aboutData.item_1_icon}`" :alt="aboutData.item_1_title">
                    </div>
                    <div class="about-item-content">
                      <h5>{{ aboutData.item_1_title }}</h5>
                      <p>{{ aboutData.item_1_desc }}</p>
                    </div>
                  </div>
                  <!-- Item 2 -->
                  <div class="about-item">
                    <div class="about-item-icon">
                      <img :src="`/assets/img/icon/${aboutData.item_2_icon}`" :alt="aboutData.item_2_title">
                    </div>
                    <div class="about-item-content">
                      <h5>{{ aboutData.item_2_title }}</h5>
                      <p>{{ aboutData.item_2_desc }}</p>
                    </div>
                  </div>
                </div>

                <div class="col-md-5">
                  <div class="about-quote">
                    <p>"{{ aboutData.quote }}"</p>
                    <i class="fa fa-quote-right"></i>
                  </div>
                </div>
              </div>
            </div>

            <div class="about-bottom">
              <a :href="aboutData.button_url" class="theme-btn">{{ aboutData.button_text }}<i class="fas fa-arrow-right-long"></i></a>
              <div class="about-phone">
                <div class="icon"><i class="fa fa-headset"></i></div>
                <div class="number">
                  <span>Hubungi Kami</span>
                  <h6><a :href="'tel:' + (lembaga.telepon || '')">{{ lembaga.telepon || '+62 812-xxxx-xxxx' }}</a></h6>
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
import { computed } from 'vue';
import { usePage } from '@inertiajs/vue3';

const props = defineProps({
  about: {
    type: Object,
    default: () => ({})
  }
});

const page = usePage();
const lembaga = computed(() => page.props.lembaga || {});

// Default values
const aboutData = computed(() => {
  return props.about || {};
});

const formattedTitle = computed(() => {
  const title = aboutData.value.title;
  const highlight = aboutData.value.highlight_word;
  if (highlight && title.includes(highlight)) {
    return title.replace(highlight, `<span>${highlight}</span>`);
  }
  return title;
});

const lamaBerdiri = computed(() => {
    if (!lembaga.value.tahun_berdiri) return '0'; // Fallback 0
    const currentYear = new Date().getFullYear();
    const startYear = parseInt(lembaga.value.tahun_berdiri);
    return currentYear - startYear;
});
</script>
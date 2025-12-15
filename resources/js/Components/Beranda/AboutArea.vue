<template>
  <div class="about-section" style="padding-top: 90px; padding-bottom: 90px;">
    <div class="container">
      <div class="row g-4 align-items-center">
        <div class="col-lg-6">
          <div class="about-left wow fadeInLeft" data-wow-delay=".25s">
            <div class="about-img">
              <div class="row g-4">
                <div class="col-md-6">
                  <img class="img-1" :src="getImage('image', 'assets/img/hope/_U9A7700.jpg')" alt="Kegiatan belajar siswa MI Al-Hikmah">
                  <div class="about-experience mt-4">
                    <div class="about-experience-icon">
                      <i class="fas fa-certificate" style="font-size: 40px; color: var(--color-primary);"></i>
                    </div>
                    <b class="text-start">{{ yearsStanding }} Tahun <br> {{ getText('years_text', 'Telah Berdiri') }}</b>
                  </div>
                </div>
                <div class="col-md-6">
                  <img class="img-2" :src="getImage('image_2', 'assets/img/hope/_U9A8033.jpg')" alt="Suasana kelas ramah anak">
                  <img class="img-3 mt-4" :src="getImage('image_3', 'assets/img/hope/259A2654%20copy.JPG')" alt="Kegiatan keagamaan dan literasi">
                </div>
              </div>
            </div>
          </div>
        </div>

        <div class="col-lg-6">
          <div class="about-right wow fadeInRight" data-wow-delay=".25s">
            <div class="site-heading mb-3">
              <span class="site-title-tagline"><i class="fa fa-book-open"></i> Tentang Kami</span>
              <h2 class="site-title" :class="getClass('title')">
                {{ getText('title', 'Sistem Pendidikan Madrasah Memberdayakan Potensi Sejak Dini') }}
              </h2>
            </div>

            <p class="about-text" :class="getClass('content')" v-html="getHtml('content', `MI Al-Hikmah berkomitmen menghadirkan pendidikan dasar berkualitas yang menumbuhkan akhlak
              mulia, serta menguatkan literasi dan numerasi sejak dini. Berlandaskan nilai-nilai Islam dan karakter
              Pancasila, kami membekali siswa dengan dasar iman, ilmu, dan keterampilan untuk berkontribusi positif
              di lingkungan sekitarnya. Program unggulan meliputi tahsin/tahfiz Al-Qur'an, pembiasaan ibadah harian,
              pembelajaran aktif yang ramah anak, dan kegiatan kokurikuler kreatif—ditopang guru kompeten, biaya
              terjangkau, serta fasilitas yang nyaman.`)"></p>

            <div class="about-content">
              <div class="row">
                <div class="col-md-7">
                  <!-- Item 1 -->
                  <div class="about-item">
                    <div class="about-item-icon">
                         <i v-if="isIconClass(getText('item_1_icon'))" :class="getText('item_1_icon')" style="font-size: 30px; color: var(--color-primary);"></i>
                        <img v-else :src="asset('assets/img/icon/open-book.svg')" alt="Ikon Tahfiz & BTQ">
                    </div>
                    <div class="about-item-content">
                      <h5>{{ getText('item_1_title', 'Tahfiz & BTQ Terstruktur') }}</h5>
                      <p>{{ getText('item_1_desc', 'Tahsin bacaan, target juz 30 bertahap, setoran rutin, dan pendampingan harian.') }}</p>
                    </div>
                  </div>
                  <!-- Item 2 -->
                  <div class="about-item">
                    <div class="about-item-icon">
                        <i v-if="isIconClass(getText('item_2_icon'))" :class="getText('item_2_icon')" style="font-size: 30px; color: var(--color-primary);"></i>
                        <img v-else :src="asset('assets/img/icon/global-education.svg')" alt="Ikon Literasi & Numerasi">
                    </div>
                    <div class="about-item-content">
                      <h5>{{ getText('item_2_title', 'Literasi & Klinik Numerasi') }}</h5>
                      <p>{{ getText('item_2_desc', 'Membaca 15 menit setiap pagi dan pendampingan berhitung untuk fondasi akademik kuat.') }}</p>
                    </div>
                  </div>
                </div>

                <div class="col-md-5">
                  <div class="about-quote">
                    <p>"{{ getText('quote_text', 'Kami membangun lingkungan belajar yang aman, hangat, dan mendorong kemandirian serta rasa ingin tahu anak.') }}"</p>
                    <i class="fa fa-quote-right"></i>
                  </div>
                </div>
              </div>
            </div>

            <div class="about-bottom">
              <Link href="/profil" class="theme-btn">Selengkapnya<i class="fas fa-arrow-right-long"></i></Link>
              <div class="about-phone">
                <div class="icon"><i class="fa fa-headset"></i></div>
                <div class="number">
                  <span>Hubungi Kami</span>
                  <h6><a :href="'tel:' + getText('phone', '+6281234567890')">{{ getText('phone', '+62 812-3456-7890') }}</a></h6>
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
import { Link } from '@inertiajs/vue3'

const props = defineProps({
    data: {
        type: Object,
        default: () => ({})
    },
    activeField: {
        type: String,
        default: null
    }
});

import { usePage } from '@inertiajs/vue3'

const page = usePage();

const yearsStanding = computed(() => {
    // Priority: 1. Global Setup (Lembaga), 2. Fallback
    const foundedYear = page.props.lembaga?.tahun_berdiri;
    if (foundedYear) {
        return new Date().getFullYear() - parseInt(foundedYear);
    }
    return 15; // Fallback
})

const asset = (path) => {
  return `/${path}`
}

const getImage = (key, defaultPath) => {
    if (props.data[key] instanceof File) {
        return URL.createObjectURL(props.data[key]);
    }
    if (props.data[key]) {
        return `/storage/${props.data[key]}`;
    }
    return asset(defaultPath);
}

const getText = (key, defaultText) => {
    return props.data[key] || defaultText;
}

const getHtml = (key, defaultHtml) => {
    return props.data[key] || defaultHtml;
}

const getClass = (key) => {
    return props.activeField === key ? 'highlight-active' : '';
}

const isIconClass = (val) => {
    return val && typeof val === 'string' && (val.includes('fas ') || val.includes('far ') || val.includes('fab ') || val.includes('fa '));
}
</script>

<style scoped>
.highlight-active {
    outline: 3px solid #ffc107; /* Yellow highlight */
    background-color: rgba(255, 193, 7, 0.1);
    border-radius: 4px;
    transition: all 0.2s ease;
    box-shadow: 0 0 10px rgba(255, 193, 7, 0.5);
    position: relative;
    z-index: 10;
}
</style>
<template>
  <div class="highlight-section fa-negative">
    <div class="col-xl-9 ms-auto">
      <div class="highlight-wrapper">
        <div class="row g-4">
          <!-- Item 1 -->
          <div class="col-md-6 col-lg-3">
            <div class="highlight-item">
              <span class="count">01</span>
              <div class="highlight-icon">
                <i v-if="isIconClass(getText('feature_1_icon'))" :class="getText('feature_1_icon')" style="font-size: 30px; color: var(--color-primary);"></i>
                <img v-else :src="getImage('feature_1_icon', 'assets/img/icon/book.svg')" alt="">
              </div>
              <div class="highlight-content">
                <h4 class="highlight-title">{{ getText('feature_1_title', 'Pendidikan Bermutu') }}</h4>
                <p>{{ getText('feature_1_desc', 'Kualitas pembelajaran unggul dengan pendekatan islami dan lingkungan belajar yang inspiratif.') }}</p>
              </div>
            </div>
          </div>
          <!-- Item 2 -->
          <div class="col-md-6 col-lg-3">
            <div class="highlight-item">
              <span class="count">02</span>
              <div class="highlight-icon">
                <i v-if="isIconClass(getText('feature_2_icon'))" :class="getText('feature_2_icon')" style="font-size: 30px; color: var(--color-primary);"></i>
                <img v-else :src="getImage('feature_2_icon', 'assets/img/icon/teacher.svg')" alt="">
              </div>
              <div class="highlight-content">
                <h4 class="highlight-title">{{ getText('feature_2_title', 'Guru') }}</h4>
                <p>{{ getText('feature_2_desc', 'Tenaga pendidik kompeten, berdedikasi, dan menjadi teladan dalam ilmu serta akhlak.') }}</p>
              </div>
            </div>
          </div>
          <!-- Item 3 -->
          <div class="col-md-6 col-lg-3">
            <div class="highlight-item">
              <span class="count">03</span>
              <div class="highlight-icon">
                <i v-if="isIconClass(getText('feature_3_icon'))" :class="getText('feature_3_icon')" style="font-size: 30px; color: var(--color-primary);"></i>
                <img v-else :src="getImage('feature_3_icon', 'assets/img/icon/library.svg')" alt="">
              </div>
              <div class="highlight-content">
                <h4 class="highlight-title">{{ getText('feature_3_title', 'Kurikulum Terpadu') }}</h4>
                <p>{{ getText('feature_3_desc', 'Mengintegrasikan ilmu umum dan agama untuk membentuk siswa cerdas, beriman, dan berkarakter.') }}</p>
              </div>
            </div>
          </div>
          <!-- Item 4 -->
          <div class="col-md-6 col-lg-3">
            <div class="highlight-item">
              <span class="count">04</span>
              <div class="highlight-icon">
                <i v-if="isIconClass(getText('feature_4_icon'))" :class="getText('feature_4_icon')" style="font-size: 30px; color: var(--color-primary);"></i>
                <img v-else :src="getImage('feature_4_icon', 'assets/img/icon/building-2.svg')" alt="">
              </div>
              <div class="highlight-content">
                <h4 class="highlight-title">{{ getText('feature_4_title', 'Fasilitas dan Kegiatan') }}</h4>
                <p>{{ getText('feature_4_desc', 'Sarana lengkap dan kegiatan edukatif yang menumbuhkan potensi akademik, sosial, dan spiritual siswa.') }}</p>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
const props = defineProps({
    data: Object
});

const asset = (path) => {
  return `/${path}`
}

const getText = (key, defaultVal) => {
    return props.data?.[key] || defaultVal;
}

const getImage = (key, defaultPath) => {
    if (props.data?.[key] && !isIconClass(props.data[key])) {
        return `/storage/${props.data[key]}`;
    }
    return asset(defaultPath);
}

const isIconClass = (val) => {
    return val && typeof val === 'string' && (val.includes('fas ') || val.includes('far ') || val.includes('fab ') || val.includes('fa '));
}
</script>
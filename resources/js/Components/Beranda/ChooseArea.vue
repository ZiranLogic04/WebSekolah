<template>
  <div class="choose-section pt-80 pb-80">
    <div class="container">
      <div class="row align-items-center">
        <div class="col-lg-6">
          <div class="choose-content wow fadeInUp" data-wow-delay=".25s">
            <div class="choose-content-info">
              <div class="site-heading mb-0">
                <span class="site-title-tagline">
                  <i class="fa fa-book-open"></i> Mengapa Memilih Kami
                </span>
                <h2 class="site-title text-white mb-10" v-html="getText('title', 'Pendidikan Dasar Berkualitas & <span>Ramah Anak</span>')"></h2>
                <p class="text-white">
                  {{ getText('desc', 'MI Al-Hikmah berkomitmen menghadirkan pembelajaran yang menumbuhkan iman, ilmu, dan karakter.') }}
                </p>
              </div>

              <div class="choose-content-wrap">
                <div class="row g-4">
                  <!-- 1. Guru -->
                  <div class="col-md-6">
                    <div class="choose-item">
                      <div class="choose-item-icon">
                        <i :class="getText('item_1_icon', 'fas fa-chalkboard-teacher')"></i>
                      </div>
                      <div class="choose-item-info">
                        <h4>{{ getText('item_1_title', 'Guru Peduli & Kompeten') }}</h4>
                        <p>{{ getText('item_1_desc', 'Pembelajaran aktif, pendampingan personal, dan evaluasi berkelanjutan di setiap kelas.') }}</p>
                      </div>
                    </div>
                  </div>

                  <!-- 2. Tahfiz & BTQ -->
                  <div class="col-md-6">
                    <div class="choose-item">
                      <div class="choose-item-icon">
                        <i :class="getText('item_2_icon', 'fas fa-book-open')"></i>
                      </div>
                      <div class="choose-item-info">
                        <h4>{{ getText('item_2_title', 'Tahfiz & BTQ Terstruktur') }}</h4>
                        <p>{{ getText('item_2_desc', 'Tahsin bacaan, target juz 30 bertahap, dan setoran rutin.') }}</p>
                      </div>
                    </div>
                  </div>

                  <!-- 3. Literasi & Numerasi -->
                  <div class="col-md-6">
                    <div class="choose-item">
                      <div class="choose-item-icon">
                        <i :class="getText('item_3_icon', 'fas fa-calculator')"></i>
                      </div>
                      <div class="choose-item-info">
                        <h4>{{ getText('item_3_title', 'Literasi & Klinik Numerasi') }}</h4>
                        <p>{{ getText('item_3_desc', 'Membaca harian dan pendampingan berhitung untuk fondasi kuat.') }}</p>
                      </div>
                    </div>
                  </div>

                  <!-- 4. Ramah Anak & Aman -->
                  <div class="col-md-6">
                    <div class="choose-item">
                      <div class="choose-item-icon">
                         <i :class="getText('item_4_icon', 'fas fa-heart')"></i>
                      </div>
                      <div class="choose-item-info">
                        <h4>{{ getText('item_4_title', 'Sekolah Ramah Anak') }}</h4>
                        <p>{{ getText('item_4_desc', 'Satgas Anti-Bullying, UKS, dan komunikasi orang tua–sekolah yang responsif & bersahabat.') }}</p>
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
            <img :src="getImage('image', 'assets/img/hope/_U9A7765.jpg')" alt="Suasana belajar">
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

const asset = (path) => `/${path}`;
const getText = (key, defaultVal) => props.data?.[key] || defaultVal;
const getImage = (key, defaultPath) => {
    if (props.data?.[key]) {
        return `/storage/${props.data[key]}`;
    }
    return asset(defaultPath);
}
</script>

<style scoped>


/* Card Styling - Invisible structure for equal height */
.choose-item {
    height: 100%;
}

/* Icon Styling */
.choose-item-icon {
    font-size: 40px; /* Adjusted to 40px as requested */
    color: #ffbc34;
    display: inline-block;
    margin-bottom: 20px;
}
.choose-item-icon i {
    width: auto;
    height: auto;
}

/* Image Styling */
.choose-img, .choose-img img {
    height: 400px;
}
</style>
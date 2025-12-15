<template>
  <div class="program-section bg" style="padding: 120px 0;">
    <div class="container">
      <div class="row">
        <div class="col-lg-6 mx-auto">
          <div class="site-heading text-center">
            <span class="site-title-tagline"><i class="fa fa-book-open"></i> {{ getText('subtitle', 'Program MI') }}</span>
            <h6 class="site-title" v-html="getText('title', 'Jelajahi <span>Program & Layanan</span> Kami')"></h6>
            <p>{{ getText('desc', 'Program pembelajaran dan layanan siswa di MI Al-Hikmah yang menumbuhkan iman, ilmu, karakter, literasi, dan numerasi dalam lingkungan yang ramah anak.') }}</p>
          </div>
        </div>
      </div>

      <div class="program-slider owl-carousel owl-theme">
        
        <!-- Program 1 -->
        <div class="program-item">
          <div class="program-icon">
             <i :class="getText('prog_1_icon', 'fas fa-desktop')"></i>
          </div>
          <div class="program-info">
            <h4 class="program-title"><Link :href="getText('prog_1_link', '#')">{{ getText('prog_1_title', 'Program 1') }}</Link></h4>
            <p>{{ getText('prog_1_desc', 'Deskripsi program...') }}</p>
            <div class="program-btn">
              <Link :href="getText('prog_1_link', '#')">Selengkapnya<i class="fas fa-arrow-right-long"></i></Link>
            </div>
          </div>
        </div>

        <!-- Program 2 -->
        <div class="program-item">
          <div class="program-icon">
             <i :class="getText('prog_2_icon', 'fas fa-quran')"></i>
          </div>
          <div class="program-info">
            <h4 class="program-title"><Link :href="getText('prog_2_link', '#')">{{ getText('prog_2_title', 'Program 2') }}</Link></h4>
            <p>{{ getText('prog_2_desc', 'Deskripsi program...') }}</p>
            <div class="program-btn">
              <Link :href="getText('prog_2_link', '#')">Selengkapnya<i class="fas fa-arrow-right-long"></i></Link>
            </div>
          </div>
        </div>

        <!-- Program 3 -->
        <div class="program-item">
          <div class="program-icon">
             <i :class="getText('prog_3_icon', 'fas fa-book-open')"></i>
          </div>
          <div class="program-info">
            <h4 class="program-title"><Link :href="getText('prog_3_link', '#')">{{ getText('prog_3_title', 'Program 3') }}</Link></h4>
            <p>{{ getText('prog_3_desc', 'Deskripsi program...') }}</p>
            <div class="program-btn">
              <Link :href="getText('prog_3_link', '#')">Selengkapnya<i class="fas fa-arrow-right-long"></i></Link>
            </div>
          </div>
        </div>

        <!-- Program 4 -->
        <div class="program-item">
          <div class="program-icon">
             <i :class="getText('prog_4_icon', 'fas fa-calculator')"></i>
          </div>
          <div class="program-info">
            <h4 class="program-title"><Link :href="getText('prog_4_link', '#')">{{ getText('prog_4_title', 'Program 4') }}</Link></h4>
            <p>{{ getText('prog_4_desc', 'Deskripsi program...') }}</p>
            <div class="program-btn">
              <Link :href="getText('prog_4_link', '#')">Selengkapnya<i class="fas fa-arrow-right-long"></i></Link>
            </div>
          </div>
        </div>

         <!-- Program 5 -->
        <div class="program-item">
          <div class="program-icon">
             <i :class="getText('prog_5_icon', 'fas fa-futbol')"></i>
          </div>
          <div class="program-info">
            <h4 class="program-title"><Link :href="getText('prog_5_link', '#')">{{ getText('prog_5_title', 'Program 5') }}</Link></h4>
            <p>{{ getText('prog_5_desc', 'Deskripsi program...') }}</p>
            <div class="program-btn">
              <Link :href="getText('prog_5_link', '#')">Selengkapnya<i class="fas fa-arrow-right-long"></i></Link>
            </div>
          </div>
        </div>

         <!-- Program 6 -->
        <div class="program-item">
          <div class="program-icon">
             <i :class="getText('prog_6_icon', 'fas fa-heart')"></i>
          </div>
          <div class="program-info">
            <h4 class="program-title"><Link :href="getText('prog_6_link', '#')">{{ getText('prog_6_title', 'Program 6') }}</Link></h4>
            <p>{{ getText('prog_6_desc', 'Deskripsi program...') }}</p>
            <div class="program-btn">
              <Link :href="getText('prog_6_link', '#')">Selengkapnya<i class="fas fa-arrow-right-long"></i></Link>
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

onMounted(() => {
    // Manually initialize Owl Carousel
    if ($('.program-slider').length > 0) {
        $('.program-slider').owlCarousel({
            loop: true,
            margin: 25,
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
})
</script>

<style scoped>
.program-item {
    position: relative;
    /* overflow: hidden; Removed to ensure decorations aren't clipped if that was the issue, though 'mentok kanan' implies it was inside. */
}

.program-icon {
    display: flex;
    justify-content: center;
    align-items: center;
    width: 90px;
    height: 90px;
    background-color: rgba(255, 188, 52, 0.15); /* Light Orange/Yellow */
    border-radius: 50%;
    margin-bottom: 25px;
    transition: all 0.3s ease;
}

.program-icon i {
    font-size: 40px;
    color: #ffbc34; /* Orange/Yellow Theme Color */
    transition: all 0.3s ease;
}

.program-item:hover .program-icon {
    background-color: #ffbc34;
    transform: translateY(-5px);
}

.program-item:hover .program-icon i {
    color: #fff;
}

.program-btn a {
    color: #1b2559;
    font-weight: 600;
    transition: all 0.3s ease;
}

.program-btn a:hover {
    color: #ffbc34; /* Match the orange theme */
    padding-left: 5px; /* Slight movement effect */
}

.program-btn a i {
    transition: margin-left 0.3s ease;
}

.program-btn a:hover i {
    margin-left: 8px;
}

/* Owl Carousel Nav Styling */
:deep(.owl-nav) {
    text-align: center;
    margin-top: 20px;
}
:deep(.owl-nav button.owl-prev),
:deep(.owl-nav button.owl-next) {
    width: 40px;
    height: 40px;
    line-height: 40px;
    background-color: #fff;
    border: 1px solid #e6e6e6 !important;
    border-radius: 50%;
    color: #1b2559;
    font-size: 16px;
    margin: 0 5px;
    transition: all 0.3s ease;
}
:deep(.owl-nav button.owl-prev:hover),
:deep(.owl-nav button.owl-next:hover) {
    background-color: #ffbc34 !important;
    border-color: #ffbc34 !important;
    color: #fff;
}
</style>
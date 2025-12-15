<template>
  <div class="activities-section pt-20">
    <div class="container">
      <div class="row">
        <div class="col-lg-6 mx-auto">
          <div class="site-heading text-center">
            <span class="site-title-tagline"><i class="fa fa-book-open"></i> Galeri Kegiatan</span>
            <h2 class="site-title">Kegiatan di <span>{{ page.props.lembaga?.nama_sekolah || 'Sekolah' }}</span></h2>
          </div>
        </div>
      </div>

      <div class="row">
        <!-- Item 1 -->
        <div class="col-md-4">
          <div class="activities-item">
            <div class="activities-img">
              <img :src="getImage('port_1_img', 'assets/img/hope/stRe.jpg')" style="height:300px" alt="Portfolio 1">
            </div>
            <div class="activities-content">
              <div class="activities-info">
                <div class="activities-title-info">
                  <h5 class="activities-subtitle"><span>//</span> {{ getText('port_1_cat', 'Literasi') }}</h5>
                  <Link :href="getText('port_1_link', '#')">
                    <h4 class="activities-title">{{ getText('port_1_title', 'Budaya Literasi 15 Menit') }}</h4>
                  </Link>
                </div>
                <Link :href="getText('port_1_link', '#')" class="activities-btn"><i class="fa fa-arrow-right"></i></Link>
              </div>
            </div>
          </div>
        </div>

        <!-- Item 2 -->
        <div class="col-md-4">
          <div class="activities-item">
            <div class="activities-img">
              <img :src="getImage('port_2_img', 'assets/img/hope/aka465.jpg')" style="height:300px" alt="Portfolio 2">
            </div>
            <div class="activities-content">
              <div class="activities-info">
                <div class="activities-title-info">
                  <h5 class="activities-subtitle"><span>//</span> {{ getText('port_2_cat', 'Keagamaan') }}</h5>
                  <Link :href="getText('port_2_link', '#')">
                    <h4 class="activities-title">{{ getText('port_2_title', 'Tahfiz & BTQ') }}</h4>
                  </Link>
                </div>
                <Link :href="getText('port_2_link', '#')" class="activities-btn"><i class="fa fa-arrow-right"></i></Link>
              </div>
            </div>
          </div>
        </div>

        <!-- Item 3 -->
        <div class="col-md-4">
          <div class="activities-item">
            <div class="activities-img">
              <img :src="getImage('port_3_img', 'assets/img/hope/r456.jpg')" style="height:300px" alt="Portfolio 3">
            </div>
            <div class="activities-content">
              <div class="activities-info">
                <div class="activities-title-info">
                  <h5 class="activities-subtitle"><span>//</span> {{ getText('port_3_cat', 'Ekskul') }}</h5>
                  <Link :href="getText('port_3_link', '#')">
                    <h4 class="activities-title">{{ getText('port_3_title', 'Olahraga & Pramuka') }}</h4>
                  </Link>
                </div>
                <Link :href="getText('port_3_link', '#')" class="activities-btn"><i class="fa fa-arrow-right"></i></Link>
              </div>
            </div>
          </div>
        </div>

        <!-- Item 4 -->
        <div class="col-md-4">
          <div class="activities-item">
            <div class="activities-img">
              <img :src="getImage('port_4_img', 'assets/img/hope/ra123k.jpg')" style="height:300px" alt="Portfolio 4">
            </div>
            <div class="activities-content">
              <div class="activities-info">
                <div class="activities-title-info">
                  <h5 class="activities-subtitle"><span>//</span> {{ getText('port_4_cat', 'Ramah Anak') }}</h5>
                  <Link :href="getText('port_4_link', '#')">
                    <h4 class="activities-title">{{ getText('port_4_title', 'Sekolah Ramah Anak') }}</h4>
                  </Link>
                </div>
                <Link :href="getText('port_4_link', '#')" class="activities-btn"><i class="fa fa-arrow-right"></i></Link>
              </div>
            </div>
          </div>
        </div>

        <!-- Item 5 -->
        <div class="col-md-4">
          <div class="activities-item">
            <div class="activities-img">
              <img :src="getImage('port_5_img', 'assets/img/hope/REMISE%20UEA%20%2829%29.jpg')" style="height:300px" alt="Portfolio 5">
            </div>
            <div class="activities-content">
              <div class="activities-info">
                <div class="activities-title-info">
                  <h5 class="activities-subtitle"><span>//</span> {{ getText('port_5_cat', 'Keagamaan') }}</h5>
                  <Link :href="getText('port_5_link', '#')">
                    <h4 class="activities-title">{{ getText('port_5_title', 'Pembiasaan Ibadah Harian') }}</h4>
                  </Link>
                </div>
                <Link :href="getText('port_5_link', '#')" class="activities-btn"><i class="fa fa-arrow-right"></i></Link>
              </div>
            </div>
          </div>
        </div>

        <!-- Item 6 -->
        <div class="col-md-4">
          <div class="activities-item">
            <div class="activities-img">
              <img :src="getImage('port_6_img', 'assets/img/hope/IMG_1135%20-BG%20%281%29.jpg')" style="height:300px" alt="Portfolio 6">
            </div>
            <div class="activities-content">
              <div class="activities-info">
                <div class="activities-title-info">
                  <h5 class="activities-subtitle"><span>//</span> {{ getText('port_6_cat', 'Sarpras') }}</h5>
                  <Link :href="getText('port_6_link', '#')">
                    <h4 class="activities-title">{{ getText('port_6_title', 'Fasilitas Sekolah') }}</h4>
                  </Link>
                </div>
                <Link :href="getText('port_6_link', '#')" class="activities-btn"><i class="far fa-arrow-right"></i></Link>
              </div>
            </div>
          </div>
        </div>

      </div>
    </div>
  </div>
</template>

<script setup>
import { Link, usePage } from '@inertiajs/vue3'

const props = defineProps({
    data: Object
});

const page = usePage();

const asset = (path) => `/${path}`;
const getText = (key, defaultVal) => props.data?.[key] || defaultVal;
const getImage = (key, defaultPath) => {
    if (props.data?.[key]) {
        return `/storage/${props.data[key]}`;
    }
    return asset(defaultPath);
}
</script>
<!-- components/HeaderTop.vue -->
<template>
  <div class="header-top">
    <div class="container">
      <div class="header-top-wrap">
        <div class="header-top-left">
          <div class="header-top-social">
            <span>Follow Us: </span>
            <a v-if="lembaga?.facebook" :href="lembaga.facebook" target="_blank" aria-label="Facebook">
              <i class="fab fa-facebook-f"></i>
            </a>
            <a v-if="lembaga?.instagram" :href="lembaga.instagram" target="_blank" aria-label="Instagram">
              <i class="fab fa-instagram"></i>
            </a>
            <a v-if="lembaga?.twitter" :href="lembaga.twitter" target="_blank" aria-label="Twitter">
              <i class="fab fa-twitter"></i>
            </a>
            <a v-if="lembaga?.youtube" :href="lembaga.youtube" target="_blank" aria-label="YouTube">
              <i class="fab fa-youtube"></i>
            </a>
            <!-- Fallback jika belum ada yang diisi, tampilkan icon default agar tidak kosong melompong (opsional) -->
            <span v-if="!hasSosmed" class="text-white small ms-2 fst-italic">Media sosial belum diatur</span>
          </div>
        </div>

        <div class="header-top-right">
          <div class="header-top-contact">
            <ul class="ht-contact">
              <li class="ht-item ht-item--loc" v-if="lembaga?.alamat">
                <i class="fa fa-location-dot"></i>
                <a 
                  :href="'https://maps.google.com/?q=' + lembaga.alamat"
                  target="_blank"
                  class="truncate" 
                  :title="lembaga.alamat">
                  {{ lembaga.alamat }}
                </a>
              </li>

              <li class="ht-item" v-if="lembaga?.email">
                <i class="far fa-envelope"></i>
                <a :href="'mailto:' + lembaga.email" class="truncate" :title="lembaga.email">
                  {{ lembaga.email }}
                </a>
              </li>

              <li class="ht-item" v-if="lembaga?.telepon">
                <i class="fa fa-phone-volume"></i>
                <a :href="'tel:' + lembaga.telepon" class="truncate" :title="lembaga.telepon">{{ lembaga.telepon }}</a>
              </li>
            </ul>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { computed } from 'vue';
import { usePage } from '@inertiajs/vue3';

const page = usePage();
const lembaga = computed(() => page.props.lembaga || {});

const hasSosmed = computed(() => {
    return lembaga.value.facebook || lembaga.value.instagram || lembaga.value.twitter || lembaga.value.youtube;
});
</script>

<style>
/* Force styling for social icons to ensure visibility */
.header-top-social span {
    color: #ffffff;
    font-weight: 500;
}

.header-top-social a {
    width: 35px;
    height: 35px;
    line-height: 35px;
    color: #ffffff;
    font-size: 16px;
    background: #116E63; /* var(--theme-color) */
    text-align: center;
    margin-left: 5px;
    border-radius: 50%;
    transition: all .5s ease-in-out;
    display: inline-block;
}

.header-top-social a:hover {
    color: #FDA31B; /* var(--theme-color2) */
}
</style>
<template>
  <div class="counter-area" style="padding-top: 60px; padding-bottom: 60px;">
    <div class="container">
      <div class="row">
        <div class="col-lg-3 col-sm-6">
          <div class="counter-box">
            <div class="icon"><i class="fas fa-user-graduate text-white custom-icon"></i></div>
            <div>
              <span class="counter" :data-from="0" :data-to="stats?.siswa_aktif || 0" data-speed="1500">{{ stats?.siswa_aktif || 0 }}</span>
              <h6 class="title">+ Siswa Aktif</h6>
            </div>
          </div>
        </div>

        <div class="col-lg-3 col-sm-6">
          <div class="counter-box">
            <div class="icon"><i class="fas fa-chalkboard-teacher text-white custom-icon"></i></div>
            <div>
              <span class="counter" :data-from="0" :data-to="stats?.guru || 0" data-speed="1500">{{ stats?.guru || 0 }}</span>
              <h6 class="title">+ Guru & Tendik</h6>
            </div>
          </div>
        </div>

        <div class="col-lg-3 col-sm-6">
          <div class="counter-box">
            <div class="icon"><i class="fas fa-school text-white custom-icon"></i></div>
            <div>
              <span class="counter" :data-from="0" :data-to="stats?.rombel || 0" data-speed="1500">{{ stats?.rombel || 0 }}</span>
              <h6 class="title">+ Rombel</h6>
            </div>
          </div>
        </div>

        <div class="col-lg-3 col-sm-6">
          <div class="counter-box">
            <div class="icon"><i class="fas fa-graduation-cap text-white custom-icon"></i></div>
            <div>
              <span class="counter" :data-from="0" :data-to="stats?.alumni || 0" data-speed="2000">{{ stats?.alumni || 0 }}</span>
              <h6 class="title">+ Siswa Lulus</h6>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { onMounted } from 'vue'

defineProps({
    stats: Object 
})

const asset = (path) => {
  return `/${path}`
}

// Counter animation logic should be here or in main script.js
</script>

<style scoped>
.custom-icon {
    font-size: 35px;
}
</style>
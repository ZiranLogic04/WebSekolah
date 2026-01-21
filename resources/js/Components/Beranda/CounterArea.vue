<template>
  <div class="counter-area pt-60 pb-60">
    <div class="container">
      <div class="row">

        <div class="col-lg-3 col-sm-6">
          <div class="counter-box">
            <div class="icon"><img src="/assets/img/icon/course.svg" alt=""></div>
            <div>
              <span class="counter" data-from="0" :data-to="stats.siswa_aktif" data-speed="1500">{{ stats.siswa_aktif }}</span>
              <h6 class="title">+ Siswa Aktif</h6>
            </div>
          </div>
        </div>

        <div class="col-lg-3 col-sm-6">
          <div class="counter-box">
            <div class="icon"><img src="/assets/img/icon/graduation.svg" alt=""></div>
            <div>
              <span class="counter" data-from="0" :data-to="stats.guru" data-speed="1500">{{ stats.guru }}</span>
              <h6 class="title">+ Guru & Tendik</h6>
            </div>
          </div>
        </div>

        <div class="col-lg-3 col-sm-6">
          <div class="counter-box">
            <div class="icon"><img src="/assets/img/icon/teacher-2.svg" alt=""></div>
            <div>
              <span class="counter" data-from="0" :data-to="stats.rombel" data-speed="1500">{{ stats.rombel }}</span>
              <h6 class="title">+ Rombel</h6>
            </div>
          </div>
        </div>

        <div class="col-lg-3 col-sm-6">
          <div class="counter-box">
            <div class="icon"><img src="/assets/img/icon/graduation.svg" alt=""></div>
            <div>
              <span class="counter" data-from="0" :data-to="stats.alumni" data-speed="2000">{{ stats.alumni }}</span>
              <h6 class="title">+ Siswa Lulus</h6>
            </div>
          </div>
        </div>

      </div>
    </div>
  </div>
</template>

<script setup>
defineProps({
    stats: {
        type: Object,
        default: () => ({
            siswa_aktif: 0,
            guru: 0,
            rombel: 0,
            alumni: 0
        })
    }
})
</script>
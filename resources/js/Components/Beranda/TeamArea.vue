<template>
  <div class="team-section" style="padding: 120px 0;">
    <div class="container">
      <div class="row">
        <div class="col-lg-6 mx-auto">
          <div class="site-heading text-center">
            <span class="site-title-tagline"><i class="fa fa-book-open"></i> Guru & Tendik</span>
            <h2 class="site-title">Kenali Tim <span>Pengajar & Tendik</span></h2>
            <p>Guru yang peduli dan tenaga kependidikan yang sigap—bersama menghadirkan pembelajaran yang aman, nyaman, dan bermakna.</p>
          </div>
        </div>
      </div>

      <div class="row">
        <!-- 1 -->
        <div class="col-md-6 col-lg-3">
          <div class="team-item wow fadeInUp" data-wow-delay=".25s">
            <div class="team-img">
              <img :src="getImage('team_1_img', 'assets/img/team/01.jpg')" alt="Kepala Madrasah">
            </div>
            <div class="team-social">
               <a href="#"><i class="fab fa-facebook-f"></i></a>
               <a href="#"><i class="fab fa-whatsapp"></i></a>
            </div>
            <div class="team-content">
              <div class="team-bio">
                <h5><a href="#">{{ getText('team_1_name', 'Nama Kepala Madrasah') }}</a></h5>
                <span>{{ getText('team_1_job', 'Kepala Madrasah') }}</span>
              </div>
            </div>
             <!-- Social Share Btn Removed for simplicity/redundancy in static template -->
          </div>
        </div>

        <!-- 2 -->
        <div class="col-md-6 col-lg-3">
          <div class="team-item wow fadeInUp" data-wow-delay=".50s">
            <div class="team-img">
              <img :src="getImage('team_2_img', 'assets/img/team/02.jpg')" alt="Koordinator Kurikulum">
            </div>
            <div class="team-social">
               <a href="#"><i class="fab fa-facebook-f"></i></a>
               <a href="#"><i class="fab fa-whatsapp"></i></a>
            </div>
            <div class="team-content">
              <div class="team-bio">
                <h5><a href="#">{{ getText('team_2_name', 'Nama Koordinator') }}</a></h5>
                <span>{{ getText('team_2_job', 'Koordinator Kurikulum') }}</span>
              </div>
            </div>
          </div>
        </div>

        <!-- 3 -->
        <div class="col-md-6 col-lg-3">
          <div class="team-item wow fadeInUp" data-wow-delay=".75s">
            <div class="team-img">
              <img :src="getImage('team_3_img', 'assets/img/team/03.jpg')" alt="Guru Kelas I">
            </div>
             <div class="team-social">
               <a href="#"><i class="fab fa-facebook-f"></i></a>
               <a href="#"><i class="fab fa-whatsapp"></i></a>
            </div>
            <div class="team-content">
              <div class="team-bio">
                <h5><a href="#">{{ getText('team_3_name', 'Nama Guru') }}</a></h5>
                <span>{{ getText('team_3_job', 'Guru Kelas I') }}</span>
              </div>
            </div>
          </div>
        </div>

        <!-- 4 -->
        <div class="col-md-6 col-lg-3">
          <div class="team-item wow fadeInUp" data-wow-delay="1s">
            <div class="team-img">
              <img :src="getImage('team_4_img', 'assets/img/team/04.jpg')" alt="Operator & TU">
            </div>
            <div class="team-social">
               <a href="#"><i class="fab fa-facebook-f"></i></a>
               <a href="#"><i class="fab fa-whatsapp"></i></a>
            </div>
            <div class="team-content">
              <div class="team-bio">
                <h5><a href="#">{{ getText('team_4_name', 'Nama Staf') }}</a></h5>
                <span>{{ getText('team_4_job', 'Operator & Tata Usaha') }}</span>
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


.team-img img {
    width: 100%;
    height: 280px; /* Reduced from 350px */
    object-fit: cover;
    border-radius: 10px;
}
</style>
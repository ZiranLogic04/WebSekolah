<template>
  <div class="team-section pt-20">
    <div class="container">
      <div class="row">
        <div class="col-lg-6 mx-auto">
          <div class="site-heading text-center">
            <span class="site-title-tagline"><i class="fa fa-book-open"></i> {{ teamData.tagline }}</span>
            <h2 class="site-title" v-html="teamData.title"></h2>
            <p>{{ teamData.description }}</p>
          </div>
        </div>
      </div>

      <div class="row">
        <div v-if="teamItems.length === 0" class="col-12 text-center text-muted">
            <p>Data tim belum tersedia.</p>
        </div>

        <div v-else class="col-md-6 col-lg-3" v-for="(item, index) in teamItems" :key="index">
          <div class="team-item wow fadeInUp" :data-wow-delay="(0.25 * (index + 1)) + 's'">
            <div class="team-img">
              <img :src="item.foto || '/assets/img/team/01.jpg'" :alt="item.nama">
            </div>
            <div class="team-social">
              <a v-if="item.facebook" :href="item.facebook" target="_blank" rel="noopener"><i class="fab fa-facebook-f"></i></a>
              <a v-if="item.instagram" :href="item.instagram" target="_blank" rel="noopener"><i class="fab fa-instagram"></i></a>
              <a v-if="item.twitter" :href="item.twitter" target="_blank" rel="noopener"><i class="fab fa-twitter"></i></a>
            </div>
            <div class="team-content">
              <div class="team-bio">
                <h5><a href="#">{{ item.nama }}</a></h5>
                <span>{{ item.jabatan }}</span>
              </div>
            </div>
            <span class="team-social-btn"><i class="fa fa-share-nodes"></i></span>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { computed } from 'vue'

const props = defineProps({
    team: {
        type: Object,
        default: () => ({})
    }
});

const teamData = computed(() => ({
    tagline: props.team?.tagline || '',
    title: props.team?.title || '',
    description: props.team?.description || '',
}));

const teamItems = computed(() => {
    return props.team?.items || [];
});
</script>

<style scoped>
.team-img {
    height: 280px;
    overflow: hidden;
}
.team-img img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    object-position: center top;
}
</style>
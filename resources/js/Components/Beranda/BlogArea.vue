<template>
  <div class="blog-section pt-20">
    <div class="container">
      <div class="row">
        <div class="col-lg-6 mx-auto">
          <div class="site-heading text-center">
            <span class="site-title-tagline"><i class="fa fa-book-open"></i> Berita</span>
            <h2 class="site-title">Berita & <span>Pengumuman</span></h2>
            <p>Update terbaru kegiatan, prestasi, dan informasi penting dari MI Al-Hikmah.</p>
          </div>
        </div>
      </div>

      <div class="row">
        <div v-if="posts.length === 0" class="col-12 text-center text-muted">
            <p>Belum ada berita terbaru.</p>
        </div>

        <!-- Dynamic Posts -->
        <div v-else class="col-md-6 col-lg-4" v-for="(post, index) in posts" :key="post.id">
          <div class="blog-item wow fadeInUp" :data-wow-delay="0.25 * (index + 1) + 's'">
            <div class="blog-date"><i class="fa fa-calendar-alt"></i> {{ formatDate(post.published_at) }}</div>
            <div class="blog-item-img">
              <img :src="post.image ? '/storage/' + post.image : '/assets/img/blog/default.jpg'" :alt="post.title" style="height: 250px; object-fit: cover;">
            </div>
            <div class="blog-item-info">
              <div class="blog-item-meta">
                <ul>
                  <li><a href="#"><i class="far fa-user-circle"></i> By {{ post.author?.name || 'Admin' }}</a></li>
                  <!-- <li><a href="#"><i class="far fa-comments"></i> 0 Komentar</a></li> -->
                </ul>
              </div>
              <h4 class="blog-title">
                <Link :href="`/berita/${post.slug}`">{{ post.title }}</Link>
              </h4>
              <Link class="theme-btn" :href="`/berita/${post.slug}`">Baca Selengkapnya<i class="fas fa-arrow-right-long"></i></Link>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { Link } from '@inertiajs/vue3';

defineProps({
    posts: {
        type: Array,
        default: () => []
    }
})

const formatDate = (dateString) => {
    if (!dateString) return '';
    const date = new Date(dateString);
    return date.toLocaleDateString('id-ID', { day: 'numeric', month: 'short', year: 'numeric' });
}
</script>
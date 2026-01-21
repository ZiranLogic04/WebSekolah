<template>
    <BerandaLayout>
        <!-- Breadcrumb Area -->
        <div class="breadcrumb-area">
            <div class="container">
                <div class="row align-items-center justify-content-center">
                    <div class="col-12 text-center">
                        <div class="breadcrumb-content">
                            <h2 class="breadcrumb-title">Berita & Pengumuman</h2>
                            <ul class="breadcrumb-list">
                                <li><Link href="/">Beranda</Link></li>
                                <li class="active">Berita</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Blog Area -->
        <div class="blog-section pt-50 pb-50">
            <div class="container">
                <div class="row" v-if="posts.data && posts.data.length > 0">
                    <div class="col-md-6 col-lg-4" v-for="post in posts.data" :key="post.id">
                        <div class="blog-item wow fadeInUp" data-wow-delay=".25s">
                            <div class="blog-date"><i class="fa fa-calendar-alt"></i> {{ formatDate(post.published_at) }}</div>
                            <div class="blog-item-img">
                                <img :src="post.image ? `/storage/${post.image}` : '/assets/img/hope/literasi.jpg'" :alt="post.title" style="height: 250px; object-fit: cover;">
                            </div>
                            <div class="blog-item-info">
                                <div class="blog-item-meta">
                                    <ul>
                                        <li><a href="#"><i class="far fa-user-circle"></i> {{ post.author?.name || 'Admin' }}</a></li>
                                        <!-- <li><a href="#"><i class="far fa-eye"></i> {{ post.views || 0 }} Views</a></li> -->
                                    </ul>
                                </div>
                                <h4 class="blog-title">
                                    <Link :href="`/berita/${post.slug}`">{{ post.title }}</Link>
                                </h4>
                                <p>{{ truncate(stripHtml(post.content), 120) }}</p>
                                <Link class="theme-btn" :href="`/berita/${post.slug}`">Baca Selengkapnya<i class="fas fa-arrow-right-long"></i></Link>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row" v-else>
                     <div class="col-12 text-center">
                        <h3>Belum ada berita yang dipublikasikan.</h3>
                     </div>
                </div>

                <!-- Pagination -->
                <div class="row" v-if="posts.links.length > 3">
                    <div class="col-md-12">
                         <div class="pagination-tab d-flex justify-content-center mt-5">
                            <ul class="pagination mb-0">
                                <li class="page-item" :class="{ disabled: !link.url, active: link.active }" v-for="(link, index) in posts.links" :key="index">
                                    <Link class="page-link" :href="link.url || '#'" v-html="link.label"></Link>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </BerandaLayout>
</template>

<script setup>
import { Link } from '@inertiajs/vue3';
import BerandaLayout from '@/Layouts/BerandaLayout.vue';

defineProps({
    posts: Object
});

const formatDate = (dateString) => {
    if (!dateString) return '';
    const date = new Date(dateString);
    return date.toLocaleDateString('id-ID', {
        day: 'numeric',
        month: 'long',
        year: 'numeric'
    });
};

const stripHtml = (html) => {
   let tmp = document.createElement("DIV");
   tmp.innerHTML = html;
   return tmp.textContent || tmp.innerText || "";
};

const truncate = (str, n) => {
  return (str.length > n) ? str.substr(0, n-1) + '&hellip;' : str;
};
</script>

<style scoped>
.breadcrumb-area {
    background: linear-gradient(rgba(0, 0, 0, 0.5), rgba(0, 0, 0, 0.5)), url('/assets/img/hope/slider-1.jpg');
    background-size: cover;
    background-position: center;
    padding: 100px 0;
    color: #fff;
    margin-top: 80px; /* Adjust for fixed header */
}
.breadcrumb-title {
    color: #fff;
    font-size: 40px;
    font-weight: 700;
    margin-bottom: 10px;
}
.breadcrumb-list {
    margin: 0;
    padding: 0;
    list-style: none;
}
.breadcrumb-list li {
    display: inline-block;
    color: #fff;
    font-size: 16px;
    font-weight: 500;
}
.breadcrumb-list li + li::before {
    content: "\f105";
    font-family: "Font Awesome 5 Pro";
    margin: 0 10px;
    color: #fff;
}
.breadcrumb-list li a {
    color: #fff;
}
.blog-date {
    background: var(--theme-color);
    color: #fff;
    padding: 5px 15px;
    border-radius: 5px;
    position: absolute;
    top: 20px;
    left: 20px;
    font-size: 14px;
    z-index: 1;
}
</style>

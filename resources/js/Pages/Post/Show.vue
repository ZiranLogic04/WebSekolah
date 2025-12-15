<template>
    <BerandaLayout>
        <!-- Breadcrumb Area -->
        <div class="breadcrumb-area" :style="post.image ? `background: linear-gradient(rgba(0, 0, 0, 0.7), rgba(0, 0, 0, 0.7)), url('/storage/${post.image}')` : ''">
            <div class="container">
                <div class="row align-items-center justify-content-center">
                    <div class="col-12 text-center">
                        <div class="breadcrumb-content">
                            <h2 class="breadcrumb-title" style="max-width: 800px; margin: 0 auto; line-height: 1.4;">{{ post.title }}</h2>
                            <ul class="breadcrumb-list mt-3">
                                <li><Link href="/">Beranda</Link></li>
                                <li><Link href="/berita">Berita</Link></li>
                                <li class="active">Detail</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="blog-details-area pt-50 pb-50">
            <div class="container">
                <div class="row">
                    <!-- Main Content -->
                    <div class="col-lg-8">
                        <div class="blog-single-post">
                            <Link href="/berita" class="back-btn mb-3 d-inline-block text-muted">
                                <i class="fas fa-chevron-left me-1"></i> Kembali ke Berita
                            </Link>
                            
                            <div class="blog-image mb-4">
                                <img :src="post.image ? `/storage/${post.image}` : '/assets/img/hope/literasi.jpg'" alt="Post Image" class="img-fluid rounded w-100">
                            </div>
                            
                            <div class="blog-info mb-4">
                                <ul class="d-flex flex-wrap gap-4 list-unstyled text-muted border-bottom pb-3">
                                    <li><i class="far fa-user me-2"></i>{{ post.author?.name || 'Admin' }}</li>
                                    <li><i class="far fa-calendar-alt me-2"></i>{{ formatDate(post.published_at) }}</li>
                                </ul>
                            </div>

                            <div class="blog-content ql-editor" v-html="post.content"></div>
                            
                            <!-- Share -->
                            <div class="card blog-share mt-5">
                                <div class="card-header bg-white">
                                    <h5 class="card-title mb-0">Bagikan Postingan Ini</h5>
                                </div>
                                <div class="card-body">
                                    <ul class="social-share list-unstyled d-flex gap-2 mb-0">
                                        <li><a href="#" class="btn btn-outline-primary btn-sm"><i class="fab fa-facebook-f"></i></a></li>
                                        <li><a href="#" class="btn btn-outline-info btn-sm"><i class="fab fa-twitter"></i></a></li>
                                        <li><a href="#" class="btn btn-outline-success btn-sm"><i class="fab fa-whatsapp"></i></a></li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Sidebar -->
                    <div class="col-lg-4">
                        <div class="sidebar-widget card mb-4">
                            <div class="card-header bg-white">
                                <h5 class="card-title mb-0">Berita Terbaru</h5>
                            </div>
                            <div class="card-body">
                                <div class="recent-posts">
                                    <div class="recent-post-item d-flex mb-3" v-for="recent in recentPosts" :key="recent.id">
                                        <div class="recent-post-img me-3" style="width: 80px; flex-shrink: 0;">
                                            <Link :href="`/berita/${recent.slug}`">
                                                <img :src="recent.image ? `/storage/${recent.image}` : '/assets/img/hope/literasi.jpg'" class="img-fluid rounded" style="height: 60px; object-fit: cover; width: 100%;">
                                            </Link>
                                        </div>
                                        <div class="recent-post-info">
                                            <h6 class="mb-1" style="font-size: 14px; line-height: 1.4;">
                                                <Link :href="`/berita/${recent.slug}`" class="text-dark">{{ recent.title }}</Link>
                                            </h6>
                                            <small class="text-muted"><i class="far fa-clock me-1"></i>{{ formatDate(recent.published_at) }}</small>
                                        </div>
                                    </div>
                                    <div v-if="recentPosts.length === 0" class="text-center text-muted">
                                        <small>Tidak ada berita lain.</small>
                                    </div>
                                </div>
                            </div>
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
import '@vueup/vue-quill/dist/vue-quill.snow.css'; // Import minimal styles for content display

defineProps({
    post: Object,
    recentPosts: Array
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
</script>

<style scoped>
.breadcrumb-area {
    background: linear-gradient(rgba(0, 0, 0, 0.5), rgba(0, 0, 0, 0.5)), url('/assets/img/hope/slider-1.jpg');
    background-size: cover;
    background-position: center;
    padding: 100px 0;
    color: #fff;
    margin-top: 80px;
}
.breadcrumb-title {
    color: #fff;
    font-size: 32px;
    font-weight: 700;
}
.breadcrumb-list {
    margin: 0;
    padding: 0;
    list-style: none;
}
.breadcrumb-list li {
    display: inline-block;
    color: #fff;
    font-size: 14px;
}
.breadcrumb-list li + li::before {
    content: "/";
    margin: 0 10px;
    color: #eee;
}
.breadcrumb-list li a {
    color: #fff;
    text-decoration: none;
}

/* Content Styles */
.blog-content {
    font-size: 16px;
    line-height: 1.8;
    color: #444;
}
.blog-content :deep(p) {
    margin-bottom: 20px;
}
.blog-content :deep(img) {
    max-width: 100%;
    height: auto;
    border-radius: 5px;
    margin: 20px 0;
}
</style>

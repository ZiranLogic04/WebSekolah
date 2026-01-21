<template>
    <aside class="blog-sidebar">

        <!-- Recent Post Widget -->
        <div class="widget recent-post mb-4">
            <h5 class="widget-title">Terbaru</h5>
            <div v-if="recentPosts && recentPosts.length > 0">
                <div class="recent-post-single d-flex align-items-center mb-3" v-for="(post, i) in recentPosts" :key="i">
                    <a class="recent-post-img flex-shrink-0 me-3" :href="'/berita/'+post.slug" style="width: 80px; height: 80px; display: block; overflow: hidden; border-radius: 8px;">
                        <img :src="post.image" :alt="post.title" loading="lazy" class="w-100 h-100" style="object-fit: cover;">
                    </a>
                    <div class="recent-post-bio">
                        <h6 class="mb-1" style="font-size: 0.95rem; line-height: 1.4;">
                            <a :href="'/berita/'+post.slug" class="text-dark text-decoration-none">{{ post.title }}</a>
                        </h6>
                        <span class="small text-muted"><i class="far fa-clock me-1"></i>{{ post.date }}</span>
                    </div>
                </div>
            </div>
            <div v-else class="text-muted text-center py-3">
                <small>Belum ada berita terbaru.</small>
            </div>
        </div>

        <!-- Category Widget (Only if exists) -->
        <div class="widget category mb-4" v-if="categories && categories.length > 0">
            <h5 class="widget-title">Kategori</h5>
            <div class="category-list">
                <a v-for="(cat, i) in categories" :key="i" :href="'/berita?kategori='+cat.slug" class="d-flex justify-content-between align-items-center">
                    <span><i class="fa fa-arrow-right me-2"></i>{{ cat.name }}</span>
                    <span>({{ cat.count }})</span>
                </a>
            </div>
        </div>

        <!-- Social Share Widget -->
        <div class="widget social-share">
            <h5 class="widget-title">Ikuti Kami</h5>
            <div class="social-share-link d-flex gap-2">
                <a href="#" class="social-btn facebook"><i class="fab fa-facebook-f"></i></a>
                <a href="#" class="social-btn instagram"><i class="fab fa-instagram"></i></a>
                <a href="#" class="social-btn whatsapp"><i class="fab fa-whatsapp"></i></a>
                <a href="#" class="social-btn youtube"><i class="fab fa-youtube"></i></a>
            </div>
        </div>
    </aside>
</template>

<script setup>
import { Link } from '@inertiajs/vue3';

const props = defineProps({
    recentPosts: {
        type: Array,
        default: () => []
    },
    categories: {
        type: Array,
        default: () => []
    }
});
</script>

<style scoped>
/* Widget Sidebar Styles from websekolahku */
.blog-sidebar .widget {
    background: var(--theme-bg-light, #f9f9f9); /* Fallback color */
    padding: 30px;
    margin-bottom: 30px;
    border-radius: 20px;
    border: 1px solid #eee; /* Add border for better visibility */
}

.blog-sidebar .widget .widget-title {
    padding-bottom: 10px;
    margin-bottom: 30px;
    position: relative;
    font-size: 22px;
    color: var(--color-dark, #333);
}

.blog-sidebar .widget .widget-title::before {
    position: absolute;
    content: '';
    width: 15px;
    border-bottom: 3px solid var(--theme-color2, #ffbc34);
    bottom: 0;
    left: 0;
}

.blog-sidebar .widget .widget-title::after {
    position: absolute;
    content: '';
    width: 30px;
    border-bottom: 3px solid var(--theme-color2, #ffbc34);
    bottom: 0;
    left: 22px;
}

.blog-sidebar .widget .search-form {
    position: relative;
}

.blog-sidebar .widget .search-form .form-control {
    padding: 12px 15px 12px 15px;
    border-radius: 8px;
    box-shadow: none;
    border: 1px solid #ddd;
    height: auto;
}

.blog-sidebar .widget .search-form .form-control:focus {
    border-color: var(--theme-color, #3d5ee1);
}

.blog-sidebar .widget .search-form button {
    position: absolute;
    right: 0;
    top: 0;
    font-size: 18px;
    padding: 8px 18px 6px 18px;
    background: transparent;
    border: none;
    color: var(--theme-color2, #ffbc34);
}

.blog-sidebar .widget .category-list a {
    display: block;
    padding: 10px 0;
    font-weight: 500;
    border-bottom: 1px solid #ced4da;
    transition: 0.3s;
    color: #555;
    text-decoration: none;
}

.blog-sidebar .widget .category-list a:last-child {
    margin-bottom: 0px;
    border-bottom: none;
}

.blog-sidebar .widget .category-list a:hover {
    padding-left: 10px;
    color: var(--theme-color2, #ffbc34);
    background-color: transparent; /* Override bootstrap if needed */
}

.blog-sidebar .widget .category-list a i {
    margin-right: 5px;
    color: var(--theme-color2, #ffbc34);
}

.blog-sidebar .widget .category-list a span {
    float: right;
}

.blog-sidebar .widget .recent-post-single {
    display: flex;
    justify-content: start;
    align-items: center;
    margin-bottom: 20px;
}

.blog-sidebar .widget .recent-post-img {
    margin-right: 20px;
    flex-shrink: 0; /* Prevent image shrinking */
}

.blog-sidebar .widget .recent-post-img img {
    width: 80px;
    height: 80px;
    border-radius: 10px;
    object-fit: cover;
}

.blog-sidebar .widget .recent-post-bio h6 {
    font-size: 16px;
    font-weight: 600;
    text-transform: capitalize;
    margin-bottom: 5px;
    line-height: 1.4;
}

.blog-sidebar .widget .recent-post-bio h6 a {
    color: #333;
    text-decoration: none;
}

.blog-sidebar .widget .recent-post-bio h6 a:hover {
    color: var(--theme-color2, #ffbc34);
}

.blog-sidebar .widget .recent-post-bio span {
    font-size: 13px;
    color: var(--theme-color2, #ffbc34);
    font-weight: 500;
}

.blog-sidebar .widget .recent-post-bio span i {
    margin-right: 5px;
}

.blog-sidebar .widget .social-share-link {
    display: flex;
    gap: 10px;
    flex-wrap: wrap;
}

.blog-sidebar .widget .social-share-link a {
    width: 40px;
    height: 40px;
    line-height: 40px; /* Center icon vertically */
    display: flex;
    align-items: center;
    justify-content: center;
    border: 2px solid var(--theme-color, #3d5ee1);
    color: var(--theme-color, #3d5ee1);
    text-align: center;
    border-radius: 50px;
    transition: 0.3s;
    text-decoration: none;
}

.blog-sidebar .widget .social-share-link a:hover {
    background: var(--theme-color, #3d5ee1);
    color: #fff;
}
</style>

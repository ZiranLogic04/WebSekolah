<template>
    <BerandaLayout>
        <!-- Breadcrumb Area -->
        <div class="breadcrumb-area">
            <div class="container">
                <div class="row align-items-center justify-content-center">
                    <div class="col-12 text-center">
                        <div class="breadcrumb-content">
                            <h2 class="breadcrumb-title">Gallery Foto</h2>
                            <ul class="breadcrumb-list">
                                <li><Link href="/">Beranda</Link></li>
                                <li class="active">Gallery</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Gallery Area -->
        <div class="gallery-section pt-50 pb-50">
            <div class="container">
                <!-- Filters -->
                <div class="row mb-5">
                    <div class="col-12 text-center">
                        <div class="portfolio-filter">
                            <button 
                                :class="{ active: !selectedCategory }" 
                                @click="filterCategory('')"
                            >
                                Semua
                            </button>
                            <button 
                                v-for="cat in categories" 
                                :key="cat"
                                :class="{ active: selectedCategory === cat }"
                                @click="filterCategory(cat)"
                            >
                                {{ cat }}
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Grid -->
                <div class="row g-4" v-if="images && images.length > 0">
                    <div 
                        class="col-md-6 col-lg-4" 
                        v-for="item in images" 
                        :key="item.id"
                    >
                        <div class="gallery-item" @click="openLightbox(item)">
                            <div class="gallery-img">
                                <img :src="item.image" :alt="item.title" loading="lazy">
                                <div class="gallery-overlay">
                                    <div class="gallery-content">
                                        <h4>{{ item.title }}</h4>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row" v-else>
                     <div class="col-12 text-center py-5">
                        <div class="text-muted">
                            <i class="fas fa-images fa-3x mb-3 text-secondary"></i>
                            <h4>Belum ada foto di kategori ini.</h4>
                        </div>
                     </div>
                </div>
            </div>
        </div>

        <!-- Lightbox Modal -->
        <div class="lightbox-modal" v-if="showLightbox" @click.self="closeLightbox">
            <div class="lightbox-content">
                <button class="lightbox-close" @click="closeLightbox">
                    <i class="fas fa-times"></i>
                </button>
                <div class="lightbox-body">
                    <img :src="activeImage.image" :alt="activeImage.title" class="lightbox-img">
                    <div class="lightbox-caption text-center">
                        <h4>{{ activeImage.title }}</h4>
                    </div>
                </div>
            </div>
        </div>
    </BerandaLayout>
</template>

<script setup>
import { ref } from 'vue';
import { Link, router } from '@inertiajs/vue3';
import BerandaLayout from '@/Layouts/BerandaLayout.vue';

const props = defineProps({
    images: Array,
    categories: Array,
    selectedCategory: String
});

const showLightbox = ref(false);
const activeImage = ref(null);

const filterCategory = (category) => {
    router.visit(route('gallery.index'), {
        data: { category },
        preserveScroll: true,
        only: ['images', 'selectedCategory'],
        preserveState: true
    });
};

const openLightbox = (item) => {
    activeImage.value = item;
    showLightbox.value = true;
    document.body.style.overflow = 'hidden'; // Prevent scrolling
};

const closeLightbox = () => {
    showLightbox.value = false;
    activeImage.value = null;
    document.body.style.overflow = ''; // Restore scrolling
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
    font-family: "Font Awesome 5 Pro", sans-serif;
    margin: 0 10px;
    color: #fff;
}

.breadcrumb-list li a {
    color: #fff;
}

/* Filter styles */
.portfolio-filter button {
    background: transparent;
    border: none;
    cursor: pointer;
    font-size: 16px;
    font-weight: 600;
    color: #666;
    margin: 0 15px;
    padding: 5px 0;
    position: relative;
    transition: all 0.3s;
}

.portfolio-filter button.active,
.portfolio-filter button:hover {
    color: var(--theme-color);
}

.portfolio-filter button::after {
    content: '';
    position: absolute;
    bottom: 0;
    left: 0;
    width: 0;
    height: 2px;
    background: var(--theme-color);
    transition: all 0.3s;
}

.portfolio-filter button.active::after,
.portfolio-filter button:hover::after {
    width: 100%;
}

/* Gallery styles */
.gallery-item {
    position: relative;
    overflow: hidden;
    border-radius: 10px;
    cursor: pointer;
    box-shadow: 0 5px 15px rgba(0,0,0,0.1);
}

.gallery-img {
    position: relative;
    height: 280px;
    width: 100%;
}

.gallery-img img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: transform 0.5s;
}

.gallery-item:hover .gallery-img img {
    transform: scale(1.1);
}

.gallery-overlay {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(0, 0, 0, 0.6);
    display: flex;
    justify-content: center;
    align-items: center;
    opacity: 0;
    transition: all 0.3s;
}

.gallery-item:hover .gallery-overlay {
    opacity: 1;
}

.gallery-content {
    text-align: center;
    color: #fff;
    padding: 20px;
    transform: translateY(20px);
    transition: all 0.3s;
}

.gallery-item:hover .gallery-content {
    transform: translateY(0);
}

.gallery-content h4 {
    color: #fff;
    font-size: 20px;
    margin-bottom: 5px;
}

.gallery-content p {
    margin-bottom: 15px;
    font-size: 14px;
    color: #ddd;
}

.gallery-icon {
    width: 40px;
    height: 40px;
    line-height: 40px;
    background: var(--theme-color);
    color: #fff;
    border-radius: 50%;
    margin: 0 auto;
}

/* Lightbox styles */
.lightbox-modal {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(0, 0, 0, 0.9);
    z-index: 9999;
    display: flex;
    justify-content: center;
    align-items: center;
    padding: 30px;
}

.lightbox-content {
    position: relative;
    max-width: 900px;
    width: 100%;
    max-height: 90vh;
    border-radius: 8px;
    background: #000;
    box-shadow: 0 0 20px rgba(0,0,0,0.5);
    overflow: hidden;
}

.lightbox-close {
    position: absolute;
    top: 15px;
    right: 15px;
    background: rgba(0,0,0,0.5);
    color: #fff;
    border: none;
    width: 40px;
    height: 40px;
    border-radius: 50%;
    cursor: pointer;
    z-index: 10;
    transition: background 0.3s;
}

.lightbox-close:hover {
    background: rgba(255,0,0,0.7);
}

.lightbox-body {
    display: flex;
    flex-direction: column;
}

.lightbox-img {
    max-height: 80vh;
    width: 100%;
    object-fit: contain;
}

.lightbox-caption {
    padding: 15px 20px;
    background: rgba(0,0,0,0.8);
    color: #fff;
}
</style>

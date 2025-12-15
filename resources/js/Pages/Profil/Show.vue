<template>
    <BerandaLayout>
        <!-- Breadcrumb Area -->
        <div class="breadcrumb-area" style="background-image: url(/assets/img/hope/breadcrumb-01.jpg);">
            <div class="container">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="breadcrumb-content">
                            <h2 class="title">{{ title }}</h2>
                            <ul class="breadcrumb-list">
                                <li><Link href="/">Beranda</Link></li>
                                <li class="active">{{ title }}</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Breadcrumb Area End -->

        <!-- Content Area -->
        <div class="section-padding">
            <div class="container">
                <div class="row justify-content-center">
                    <div class="col-lg-10">
                        
                        <!-- Page: Visi & Misi -->
                        <div v-if="slug === 'visi-misi'" class="visi-misi-wrapper">
                            <div class="row">
                                <div class="col-md-12 mb-5 text-center">
                                    <h3 class="modern-title">Visi</h3>
                                    <div class="visi-box p-4 bg-light rounded shadow-sm">
                                        <p class="fs-4 fst-italic text-dark border-start border-5 border-primary ps-4">
                                            "{{ content?.visi || 'Belum ada data Visi.' }}"
                                        </p>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <h3 class="modern-title text-center mb-4">Misi</h3>
                                    <div class="misi-content ck-content" v-html="content?.misi || '<p class=\'text-center\'>Belum ada data Misi.</p>'"></div>
                                </div>
                            </div>
                        </div>

                        <!-- Page: Sejarah -->
                        <div v-else-if="slug === 'sejarah'">
                            <div class="card border-0 shadow-sm">
                                <div class="card-body p-4 p-md-5">
                                    <img v-if="content?.image" :src="`/storage/${content.image}`" class="img-fluid rounded mb-4 w-100 object-fit-cover" style="max-height: 400px;" alt="Sejarah">
                                    <div class="ck-content" v-html="content?.content || 'Belum ada konten sejarah.'"></div>
                                </div>
                            </div>
                        </div>

                        <!-- Page: Sambutan -->
                        <div v-else-if="slug === 'sambutan'">
                            <div class="row align-items-center">
                                <div class="col-md-4 mb-4 mb-md-0 text-center">
                                    <img v-if="content?.kepsek_image" :src="`/storage/${content.kepsek_image}`" class="img-fluid rounded shadow-lg" alt="Kepala Sekolah">
                                    <div class="mt-3">
                                        <h5 class="fw-bold mb-0">{{ content?.kepsek_name || 'Kepala Sekolah' }}</h5>
                                        <span class="text-muted small">Kepala Madrasah</span>
                                    </div>
                                </div>
                                <div class="col-md-8">
                                    <div class="sambutan-text ps-md-4">
                                        <h3 class="mb-4">Sambutan Kepala Madrasah</h3>
                                        <div class="ck-content" v-html="content?.content || 'Belum ada sambutan.'"></div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Page: Organisasi -->
                        <div v-else-if="slug === 'struktur-organisasi'" class="text-center">
                             <div class="card border-0 shadow-sm">
                                <div class="card-body">
                                    <img v-if="content?.image" :src="`/storage/${content.image}`" class="img-fluid" alt="Struktur Organisasi">
                                    <p v-else class="text-muted py-5">Gambar bagan struktur organisasi belum diupload.</p>
                                    
                                    <p v-if="content?.description" class="mt-4 text-muted">{{ content.description }}</p>
                                </div>
                            </div>
                        </div>

                        <!-- Default Fallback -->
                        <div v-else>
                            <div class="alert alert-info">Halaman tidak ditemukan kontennya.</div>
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
    slug: String,
    title: String,
    content: Object
});
</script>

<style>
/* CKEditor Content Styles */
.ck-content h2 { margin-top: 1.5em; margin-bottom: 0.5em; font-weight: bold; }
.ck-content h3 { margin-top: 1.2em; margin-bottom: 0.5em; font-weight: bold; }
.ck-content ul, .ck-content ol { padding-left: 1.5em; margin-bottom: 1em; }
.ck-content ul li { list-style-type: disc; margin-bottom: 0.25em; }
.ck-content ol li { list-style-type: decimal; margin-bottom: 0.25em; }
.ck-content blockquote {
    border-left: 4px solid #ccc;
    margin-left: 0;
    padding-left: 1em;
    font-style: italic;
    color: #555;
}

.modern-title {
    position: relative;
    display: inline-block;
    padding-bottom: 10px;
    margin-bottom: 20px;
}
.modern-title:after {
    content: '';
    position: absolute;
    left: 50%;
    bottom: 0;
    transform: translateX(-50%);
    width: 50px;
    height: 3px;
    background-color: var(--theme-color-1, #0d6efd);
}
</style>

<template>
    <HomeLayout>
        <Head title="Direktori Guru & Tenaga Kependidikan" />

        <!-- Hero Section -->
        <div class="page-title-area">
            <div class="d-table">
                <div class="d-table-cell">
                    <div class="container">
                        <div class="title-content">
                            <h2>Direktori Guru & Tendik</h2>
                            <ul>
                                <li>
                                    <Link href="/">Beranda</Link>
                                </li>
                                <li>
                                    <i class="icofont-simple-right"></i>
                                </li>
                                <li>Guru & Tendik</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- End Hero Section -->

        <!-- Team Area -->
        <section class="team-area pt-100 pb-70">
            <div class="container">
                <div class="section-title">
                    <span>Tenaga Pendidik & Kependidikan</span>
                    <h2>Daftar Guru & Staff Kami</h2>
                    <p>
                        Berkenalan dengan para pengajar dan staff yang berdedikasi tinggi dalam membimbing putra-putri Anda.
                    </p>
                </div>

                <!-- Filters -->
                <div class="row mb-5">
                    <div class="col-lg-12">
                        <div class="filter-box p-4 rounded bg-light shadow-sm">
                            <form @submit.prevent="filter" class="row g-3">
                                <div class="col-md-5">
                                    <input 
                                        type="text" 
                                        class="form-control" 
                                        placeholder="Cari Nama Guru..." 
                                        v-model="form.search"
                                        @input="debouncedSearch"
                                    >
                                </div>
                                <div class="col-md-3">
                                    <select class="form-select" v-model="form.subject" @change="filter">
                                        <option value="">Semua Mapel</option>
                                        <option v-for="s in subjects" :key="s" :value="s">{{ s }}</option>
                                    </select>
                                </div>
                                <div class="col-md-3">
                                    <select class="form-select" v-model="form.role" @change="filter">
                                        <option value="">Semua Jabatan</option>
                                        <option v-for="r in roles" :key="r" :value="r">{{ r }}</option>
                                    </select>
                                </div>
                                <div class="col-md-1">
                                    <button type="button" @click="resetFilter" class="btn btn-outline-danger w-100">
                                        <i class="fas fa-undo"></i>
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                <!-- Content Grid -->
                <div class="row">
                    <div v-if="teachers.data.length === 0" class="col-12 text-center py-5">
                        <div class="alert alert-info">
                            <i class="fas fa-info-circle me-2"></i> Belum ada data guru yang sesuai dengan pencarian Anda.
                        </div>
                    </div>

                    <div 
                        v-for="teacher in teachers.data" 
                        :key="teacher.id" 
                        class="col-lg-3 col-md-6 mb-4"
                    >
                        <div class="single-team-item">
                            <div class="team-img">
                                <img 
                                    :src="getAvatar(teacher)" 
                                    :alt="teacher.nama"
                                    class="img-fluid rounded-top"
                                    style="width: 100%; height: 300px; object-fit: cover;"
                                    @error="handleImageError($event, teacher)"
                                >
                                <div class="team-social">
                                    <a v-if="teacher.facebook" :href="teacher.facebook" target="_blank"><i class="icofont-facebook"></i></a>
                                    <a v-if="teacher.twitter" :href="teacher.twitter" target="_blank"><i class="icofont-twitter"></i></a>
                                    <a v-if="teacher.linkedin" :href="teacher.linkedin" target="_blank"><i class="icofont-linkedin"></i></a>
                                    <a v-if="teacher.instagram" :href="teacher.instagram" target="_blank"><i class="icofont-instagram"></i></a>
                                </div>
                            </div>
                            <div class="team-content">
                                <h3>{{ teacher.nama }}</h3>
                                <span>{{ teacher.jabatan || 'Guru' }}</span>
                                <div v-if="teacher.bidang_studi" class="mt-2 text-muted small">
                                    <i class="fas fa-book me-1"></i> {{ teacher.bidang_studi }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Pagination -->
                <div class="row mt-4" v-if="teachers.data.length > 0">
                    <div class="col-lg-12 col-md-12 text-center">
                        <div class="pagination-area">
                             <!-- Simple Pagination Links -->
                            <template v-for="(link, key) in teachers.links" :key="key">
                                <Link 
                                    v-if="link.url" 
                                    :href="link.url" 
                                    class="prev page-numbers"
                                    :class="{ 'current': link.active }"
                                    v-html="link.label"
                                ></Link>
                            </template>
                        </div>
                    </div>
                </div>

            </div>
        </section>
        <!-- End Team Area -->

    </HomeLayout>
</template>

<script setup>
import { Head, Link, useForm, router } from '@inertiajs/vue3';
import HomeLayout from '@/Layouts/BerandaLayout.vue';
import { debounce } from 'lodash';

const props = defineProps({
    teachers: Object,
    subjects: Array,
    roles: Array,
    filters: Object
});

const form = useForm({
    search: props.filters.search || '',
    subject: props.filters.subject || '',
    role: props.filters.role || ''
});

const filter = () => {
    form.get(route('guru.public.index'), {
        preserveState: true,
        preserveScroll: true,
        replace: true
    });
};

const debouncedSearch = debounce(() => {
    filter();
}, 500);

const resetFilter = () => {
    form.search = '';
    form.subject = '';
    form.role = '';
    filter();
};
const getAvatar = (teacher) => {
    if (teacher.foto) {
        return `/storage/${teacher.foto}`;
    }
    
    // Gender based avatar (Local SVG)
    if (teacher.jenis_kelamin === 'P') {
        return '/assets/img/default-f.svg';
    }
    return '/assets/img/default-m.svg';
};

const handleImageError = (event, teacher) => {
    // If external/storage image fails, fallback to gendered SVG
    if (teacher.jenis_kelamin === 'P') {
        event.target.src = '/assets/img/default-f.svg';
    } else {
        event.target.src = '/assets/img/default-m.svg';
    }
};
</script>

<style scoped>
.page-title-area {
    background-image: url(/assets/img/page-title-bg.jpg);
    background-position: center center;
    background-size: cover;
    background-repeat: no-repeat;
    padding-top: 150px;
    padding-bottom: 70px;
}

.title-content h2 {
    font-size: 45px;
    color: #fff;
    margin-bottom: 0;
    font-weight: 700;
}

.title-content ul {
    padding-left: 0;
    margin-bottom: 0;
    margin-top: 15px;
    list-style-type: none;
}

.title-content ul li {
    display: inline-block;
    color: #fff;
    font-size: 16px;
    font-weight: 500;
}

.title-content ul li a {
    color: #fff;
}

.title-content ul li i {
    margin-left: 5px;
    margin-right: 5px;
    font-size: 12px;
}

.single-team-item {
    margin-bottom: 30px;
    background-color: #ffffff;
    box-shadow: 0 0 15px rgba(0, 0, 0, 0.05);
    border-radius: 10px;
    overflow: hidden;
    transition: transform .3s, box-shadow .3s;
    backface-visibility: hidden;
    will-change: transform;
}

.single-team-item:hover {
    transform: translateY(-5px);
    box-shadow: 0 10px 20px rgba(0,0,0,0.1);
}

.team-img {
    position: relative;
    overflow: hidden;
}

.team-img img {
    transition: transform .3s;
}

.team-social {
    position: absolute;
    bottom: -100px;
    left: 0;
    right: 0;
    background-color: rgba(255, 255, 255, 0.9);
    text-align: center;
    padding: 10px 0;
    transition: bottom .3s;
}

.single-team-item:hover .team-social {
    bottom: 0;
}

.team-social a {
    display: inline-block;
    width: 35px;
    height: 35px;
    line-height: 35px;
    background-color: #007bff;
    color: #fff;
    border-radius: 50%;
    margin: 0 5px;
    transition: background-color .3s;
}

.team-social a:hover {
    background-color: #0056b3;
}

.team-content {
    padding: 20px;
    text-align: center;
}

.team-content h3 {
    font-size: 20px;
    margin-bottom: 8px;
    font-weight: 600;
}

.team-content span {
    color: #007bff;
    font-size: 15px;
    font-weight: 500;
}

/* Pagination */
.pagination-area {
    margin-top: 30px;
}

.pagination-area .page-numbers {
    width: 40px;
    height: 40px;
    line-height: 40px;
    border-radius: 50%;
    background-color: #fff;
    box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
    color: #111;
    display: inline-block;
    margin: 0 5px;
    font-weight: 600;
    transition: .5s;
}

.pagination-area .page-numbers.current,
.pagination-area .page-numbers:hover {
    background-color: #007bff;
    color: #fff;
}
</style>

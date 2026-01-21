<script setup>
import { Head, Link, useForm } from '@inertiajs/vue3';
import AdminLayout from '@/Layouts/AdminLayout.vue';
import Swal from 'sweetalert2';

const props = defineProps({
    section: Object,
});

const content = props.section?.content || {};

const form = useForm({
    siswa_aktif: content.siswa_aktif || '0',
    guru: content.guru || '0',
    rombel: content.rombel || '0',
    alumni: content.alumni || '0',
});

const Toast = Swal.mixin({
    toast: true,
    position: 'top-end',
    showConfirmButton: false,
    timer: 3000,
    timerProgressBar: true,
    didOpen: (toast) => {
        toast.addEventListener('mouseenter', Swal.stopTimer)
        toast.addEventListener('mouseleave', Swal.resumeTimer)
    }
});

const submit = () => {
    form.post('/admin/counter', {
        onSuccess: () => {
            Toast.fire({
                icon: 'success',
                title: 'Berhasil',
                text: 'Data Counter berhasil diperbarui'
            });
        },
        onError: () => {
            Toast.fire({
                icon: 'error',
                title: 'Gagal memperbarui data',
                text: 'Periksa kembali inputan anda.'
            });
        },
    });
};

const counterItems = [
    { key: 'siswa_aktif', label: 'Siswa Aktif', icon: '/assets/img/icon/course.svg', color: '#116E63' },
    { key: 'guru', label: 'Guru & Tendik', icon: '/assets/img/icon/graduation.svg', color: '#116E63' },
    { key: 'rombel', label: 'Rombel', icon: '/assets/img/icon/teacher-2.svg', color: '#116E63' },
    { key: 'alumni', label: 'Siswa Lulus', icon: '/assets/img/icon/graduation.svg', color: '#116E63' },
];
</script>

<template>
    <Head title="Edit Counter/Statistik" />
    
    <AdminLayout>
        <div class="content container-fluid">
            <!-- Radiant Header -->
            <div class="card border-0 rounded-4 mb-4 overflow-hidden" style="background: linear-gradient(135deg, #4361ee 0%, #3a0ca3 100%);">
                <div class="card-body p-4 position-relative">
                    <div class="d-flex align-items-center gap-4 position-relative z-1">
                        <div class="icon-box bg-white bg-opacity-25 rounded-4 p-3 d-flex align-items-center justify-content-center backdrop-blur" style="width: 64px; height: 64px;">
                            <i class="fas fa-chart-pie fa-2x text-white"></i>
                        </div>
                        <div>
                            <h3 class="fw-bold mb-1 text-white">Statistik & Angka</h3>
                            <p class="mb-0 text-white-50">Kelola angka statistik penting (Siswa, Guru, Alumni)</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Breadcrumb & Action -->
            <div class="row align-items-center mb-4">
                <div class="col">
                    <nav aria-label="breadcrumb">
                         <ol class="breadcrumb alert alert-primary border-0 rounded-4 shadow-sm py-3 px-4 mb-0 align-items-center justify-content-between">
                            <div class="d-flex align-items-center">
                                <li class="breadcrumb-item"><a href="/admin/halaman" class="text-decoration-none fw-bold"><i class="fas fa-layer-group me-1"></i> Halaman</a></li>
                                <li class="breadcrumb-item"><a href="/admin/halaman/beranda" class="text-decoration-none text-muted">Beranda</a></li>
                                <li class="breadcrumb-item active text-primary fw-bold" aria-current="page">Counter</li>
                            </div>

                            <button class="btn btn-primary rounded-pill shadow-sm px-4 fw-bold" @click="submit" :disabled="form.processing">
                                <i class="fas fa-save me-2"></i> {{ form.processing ? 'Menyimpan...' : 'Simpan Perubahan' }}
                            </button>
                        </ol>
                    </nav>
                </div>
            </div>

            <!-- Counter Cards -->
            <div class="row g-4">
                <div v-for="item in counterItems" :key="item.key" class="col-md-6 col-lg-3">
                    <div class="card border-0 shadow-sm h-100 rounded-4 section-card">
                        <div class="card-body text-center p-4">
                            <div 
                                class="icon-circle mx-auto mb-4 shadow-sm"
                                :style="{ background: 'linear-gradient(135deg, #FDA31B 0%, #d9880f 100%)' }"
                            >
                                <img :src="item.icon" alt="" style="width: 32px; height: 32px; filter: brightness(0) invert(1);">
                            </div>
                            <h6 class="fw-bold text-dark mb-2">{{ item.label }}</h6>
                            <p class="text-secondary small mb-3">Masukkan total angka</p>
                            
                            <div class="input-group shadow-sm rounded-4 overflow-hidden">
                                <span class="input-group-text bg-light border-0 ps-3 text-primary fw-bold">#</span>
                                <input 
                                    type="number" 
                                    class="form-control form-control-lg text-center border-0 bg-light fw-bold fs-3 text-primary"
                                    :class="{'is-invalid': form.errors[item.key]}"
                                    v-model="form[item.key]"
                                    :placeholder="item.label"
                                >
                            </div>
                            <div v-if="form.errors[item.key]" class="invalid-feedback d-block fw-bold mt-2 text-center">{{ form.errors[item.key] }}</div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Info -->
            <div class="alert bg-white border-0 shadow-sm rounded-4 mt-4 d-flex align-items-center p-4">
                <div class="bg-primary rounded-circle p-2 me-3 d-flex align-items-center justify-content-center" style="width: 48px; height: 48px;">
                    <i class="fas fa-info-circle fs-4 text-white"></i>
                </div>
                <div class="text-secondary">
                    <strong>Info:</strong> Data ini akan ditampilkan di section Counter **Beranda** (animasi angka bergerak) dan halaman **Sarana & Prasarana**.
                </div>
            </div>
        </div>
    </AdminLayout>
</template>

<style scoped>
.icon-circle {
    width: 70px;
    height: 70px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: transform 0.3s;
}
.section-card:hover .icon-circle {
    transform: scale(1.1);
}
.section-card {
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
}
.section-card:hover {
    transform: translateY(-5px);
}
.backdrop-blur {
    backdrop-filter: blur(8px);
    -webkit-backdrop-filter: blur(8px);
}
.transform-rotate-12 {
    transform: rotate(12deg);
}
.text-white-50 {
    color: rgba(255, 255, 255, 0.75) !important;
}
</style>



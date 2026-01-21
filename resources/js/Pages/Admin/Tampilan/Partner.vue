<script setup>
import { Head, useForm } from '@inertiajs/vue3';
import AdminLayout from '@/Layouts/AdminLayout.vue';
import Swal from 'sweetalert2';
import { ref } from 'vue';

const props = defineProps({
    section: Object,
});

const content = props.section?.content || {};
const items = content.items || [];

const defaultItems = items.length > 0 ? items : [
    { name: '', image: '' }
];

const form = useForm({
    items: defaultItems.map(item => ({
        name: item.name,
        image: item.image,
        image_file: null
    })),
});

const previews = ref(form.items.map(item => item.image));

// Placeholder logo
const placeholderImage = "data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='200' height='80' viewBox='0 0 200 80'%3E%3Crect fill='%23f8f9fa' width='200' height='80' rx='8'/%3E%3Ctext x='50%25' y='45%25' dominant-baseline='middle' text-anchor='middle' font-family='Arial,sans-serif' font-size='24' fill='%23adb5bd'%3E🏢%3C/text%3E%3Ctext x='50%25' y='70%25' dominant-baseline='middle' text-anchor='middle' font-family='Arial,sans-serif' font-size='10' fill='%23adb5bd'%3EUpload Logo%3C/text%3E%3C/svg%3E";

const getPreview = (index) => {
    return previews.value[index] || placeholderImage;
};

const hasItemError = (index) => {
    return form.errors[`items.${index}.name`];
};

const handleImageChange = (e, index) => {
    const file = e.target.files[0];
    if (file) {
        form.items[index].image_file = file;
        previews.value[index] = URL.createObjectURL(file);
    }
};

const handleUrlInput = (index) => {
    // When user pastes URL, update preview
    previews.value[index] = form.items[index].image;
};

const addItem = () => {
    form.items.push({
        name: '',
        image: '',
        image_file: null
    });
    previews.value.push(null);
};

const removeItem = (index) => {
    if (form.items.length <= 1) {
        Swal.fire('Tidak Bisa', 'Minimal harus ada 1 mitra.', 'warning');
        return;
    }
    Swal.fire({
        title: 'Hapus Mitra?',
        text: "Item ini akan dihapus dari daftar.",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'Ya, Hapus!'
    }).then((result) => {
        if (result.isConfirmed) {
            form.items.splice(index, 1);
            previews.value.splice(index, 1);
        }
    });
};

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
    form.post('/admin/partner', {
        forceFormData: true,
        preserveScroll: true,
        onSuccess: () => {
             Toast.fire({
                icon: 'success',
                title: 'Berhasil',
                text: 'Daftar mitra berhasil diperbarui'
            });
        },
        onError: () => {
            Toast.fire({
                icon: 'error',
                title: 'Gagal',
                text: 'Mohon periksa isian yang ditandai merah'
            });
        }
    });
};
</script>

<template>
    <Head title="Edit Mitra" />

    <AdminLayout>
        <div class="content container-fluid">
            <!-- Radiant Header -->
            <div class="card border-0 rounded-4 mb-4 overflow-hidden" style="background: linear-gradient(135deg, #4361ee 0%, #3a0ca3 100%);">
                <div class="card-body p-4 position-relative">
                    <div class="d-flex align-items-center gap-4 position-relative z-1">
                        <div class="icon-box bg-white bg-opacity-25 rounded-4 p-3 d-flex align-items-center justify-content-center backdrop-blur" style="width: 64px; height: 64px;">
                            <i class="fas fa-handshake fa-2x text-white"></i>
                        </div>
                        <div>
                            <h3 class="fw-bold mb-1 text-white">Mitra/Partner</h3>
                            <p class="mb-0 text-white-50">Kelola logo mitra kerja sama</p>
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
                                <li class="breadcrumb-item active text-primary fw-bold" aria-current="page">Mitra</li>
                            </div>
                            <!-- Save Button Moved Here -->
                            <button class="btn btn-primary rounded-pill shadow-sm px-4 fw-bold" @click="submit" :disabled="form.processing">
                                <i class="fas fa-save me-2"></i> {{ form.processing ? 'Menyimpan...' : 'Simpan Perubahan' }}
                            </button>
                        </ol>
                    </nav>
                </div>
            </div>

            <!-- Add Button -->
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h5 class="fw-bold text-secondary mb-0"><i class="fas fa-handshake me-2"></i>Daftar Mitra ({{ form.items.length }})</h5>
                <button class="btn btn-primary text-white fw-bold shadow-sm rounded-pill" @click="addItem">
                    <i class="fas fa-plus me-2"></i>Tambah Mitra
                </button>
            </div>

            <!-- Grid Items -->
            <div class="row g-4">
                <div v-for="(item, index) in form.items" :key="index" class="col-xl-4 col-lg-6">
                    <div class="card h-100 border-0 shadow rounded-4 item-card" :class="{'border-danger border-2': hasItemError(index)}">
                        <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center border-bottom">
                            <span class="badge rounded-pill px-3 py-2 border fs-6" :class="hasItemError(index) ? 'bg-danger-subtle text-danger border-danger-subtle' : 'bg-primary-subtle text-primary border-primary-subtle'">
                                <i class="fas fa-building me-1"></i> Mitra #{{ index + 1 }}
                                <span v-if="hasItemError(index)" class="ms-1">⚠️</span>
                            </span>
                            <button class="btn btn-outline-danger btn-sm rounded-circle px-2" @click="removeItem(index)" title="Hapus Mitra">
                                <i class="fas fa-trash"></i>
                            </button>
                        </div>
                        <div class="card-body p-4">
                            <!-- Logo Preview -->
                            <div class="text-center mb-3">
                                <div class="position-relative mx-auto group-image-upload shadow-sm rounded-3 overflow-hidden bg-light p-3" style="max-width: 200px; min-height: 80px;">
                                    <img :src="getPreview(index)" class="img-fluid transition-scale" style="max-height: 60px;" @error="e => e.target.src = placeholderImage">
                                    <div class="hover-overlay position-absolute top-0 start-0 w-100 h-100 d-flex align-items-center justify-content-center" style="background: rgba(0,0,0,0.5); opacity: 0; transition: opacity 0.2s;">
                                        <label class="btn btn-light btn-sm shadow-sm fw-bold text-dark">
                                            <i class="fas fa-upload text-primary"></i> Upload
                                            <input type="file" class="d-none" accept="image/*" @change="e => handleImageChange(e, index)">
                                        </label>
                                    </div>
                                </div>
                            </div>

                            <!-- Form Fields -->
                            <div class="mb-3">
                                <label class="form-label fw-bold text-secondary small text-uppercase">Nama Mitra</label>
                                <div class="input-group shadow-sm rounded-4 overflow-hidden">
                                    <span class="input-group-text bg-white border-0 ps-3 text-primary"><i class="fas fa-font"></i></span>
                                    <input type="text" class="form-control border-0 bg-white fw-bold text-dark" :class="{'is-invalid': form.errors[`items.${index}.name`]}" v-model="item.name" placeholder="Contoh: Kementerian Agama">
                                </div>
                                <div v-if="form.errors[`items.${index}.name`]" class="invalid-feedback d-block fw-bold mt-1">{{ form.errors[`items.${index}.name`] }}</div>
                            </div>
                            
                            <div class="mb-0">
                                <label class="form-label fw-bold text-secondary small text-uppercase">URL Logo (Opsional)</label>
                                <div class="input-group shadow-sm rounded-4 overflow-hidden">
                                    <span class="input-group-text bg-white border-0 ps-3 text-primary"><i class="fas fa-link"></i></span>
                                    <input type="text" class="form-control border-0 bg-white text-primary small" v-model="item.image" @input="handleUrlInput(index)" placeholder="https://... atau upload di atas">
                                </div>
                                <small class="text-muted mt-1 d-block">Bisa pakai URL eksternal atau upload file lokal</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </AdminLayout>
</template>

<style scoped>
.header-card {
    background: linear-gradient(135deg, #4361ee 0%, #3a0ca3 100%);
}
.icon-box-header {
    width: 48px;
    height: 48px;
    background: rgba(255,255,255,0.2);
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.5rem;
}
.item-card {
    transition: transform 0.2s, box-shadow 0.2s;
}
.item-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 10px 20px rgba(0,0,0,0.1) !important;
}
.group-image-upload:hover .hover-overlay {
    opacity: 1 !important;
}
.group-image-upload:hover .transition-scale {
    transform: scale(1.05);
}
.transition-scale {
    transition: transform 0.3s ease;
}
</style>

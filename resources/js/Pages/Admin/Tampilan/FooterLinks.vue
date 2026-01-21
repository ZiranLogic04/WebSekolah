<script setup>
import { Head, useForm } from '@inertiajs/vue3';
import AdminLayout from '@/Layouts/AdminLayout.vue';
import Swal from 'sweetalert2';

const props = defineProps({
    section: Object,
});

const content = props.section?.content || {};

const form = useForm({
    quick_links_title: content.quick_links_title || 'Tautan Cepat',
    quick_links: content.quick_links || [{ label: '', url: '' }],
    academic_links_title: content.academic_links_title || 'Akademik & Layanan',
    academic_links: content.academic_links || [{ label: '', url: '' }],
});

const addQuickLink = () => {
    form.quick_links.push({ label: '', url: '' });
};

const removeQuickLink = (index) => {
    if (form.quick_links.length <= 1) {
        Swal.fire('Tidak Bisa', 'Minimal harus ada 1 tautan.', 'warning');
        return;
    }
    form.quick_links.splice(index, 1);
};

const addAcademicLink = () => {
    form.academic_links.push({ label: '', url: '' });
};

const removeAcademicLink = (index) => {
    if (form.academic_links.length <= 1) {
        Swal.fire('Tidak Bisa', 'Minimal harus ada 1 tautan.', 'warning');
        return;
    }
    form.academic_links.splice(index, 1);
};

const hasError = (type, index, field) => {
    return form.errors[`${type}.${index}.${field}`];
};

// SanitizeUrl removed as per user request

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
    form.post('/admin/footer-links', {
        preserveScroll: true,
        onSuccess: () => {
             Toast.fire({
                icon: 'success',
                title: 'Berhasil',
                text: 'Footer links berhasil diperbarui'
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
    <Head title="Edit Footer Links" />

    <AdminLayout>
        <div class="content container-fluid">
            <!-- Radiant Header -->
            <div class="card border-0 rounded-4 mb-4 overflow-hidden" style="background: linear-gradient(135deg, #4361ee 0%, #3a0ca3 100%);">
                <div class="card-body p-4 position-relative">
                    <div class="d-flex align-items-center gap-4 position-relative z-1">
                        <div class="icon-box bg-white bg-opacity-25 rounded-4 p-3 d-flex align-items-center justify-content-center backdrop-blur" style="width: 64px; height: 64px;">
                            <i class="fas fa-sitemap fa-2x text-white"></i>
                        </div>
                        <div>
                            <h3 class="fw-bold mb-1 text-white">Kelola Footer Links</h3>
                            <p class="mb-0 text-white-50">Atur navigasi di bagian bawah website</p>
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
                                <li class="breadcrumb-item active text-primary fw-bold" aria-current="page">Footer Links</li>
                            </div>
                            <!-- Save Button Moved Here -->
                            <button class="btn btn-primary rounded-pill shadow-sm px-4 fw-bold" @click="submit" :disabled="form.processing">
                                <i class="fas fa-save me-2"></i> {{ form.processing ? 'Menyimpan...' : 'Simpan Perubahan' }}
                            </button>
                        </ol>
                    </nav>
                </div>
            </div>

            <!-- Helper Card -->
            <div class="alert alert-info border-0 shadow-sm d-flex align-items-center mb-4" role="alert">
                <i class="fas fa-info-circle text-primary fs-4 me-3"></i>
                <div>
                    <h6 class="alert-heading fw-bold mb-1">Pengaturan Navigasi Footer</h6>
                    <p class="mb-0 text-muted small">Atur link navigasi yang muncul di bagian bawah website (Footer). Anda bisa mengubah judul kolom dan daftar link yang tersedia.</p>
                </div>
            </div>



            <div class="row g-4">
                <!-- Kolom 1 -->
                <div class="col-lg-6">
                    <div class="card border-0 shadow-sm rounded-4 h-100">
                        <div class="card-header bg-white py-3 border-bottom rounded-top-4">
                            <h5 class="fw-bold mb-0 text-primary"><i class="fas fa-folder-open me-2"></i>Kolom Navigasi 1</h5>
                        </div>
                        <div class="card-body p-4">
                            <!-- Judul Kolom Input -->
                            <div class="mb-4">
                                <label class="form-label fw-bold small text-uppercase text-muted">Judul Kolom</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light border-end-0"><i class="fas fa-heading text-secondary"></i></span>
                                    <input type="text" class="form-control bg-light border-start-0 ps-0 fw-bold text-primary" v-model="form.quick_links_title" placeholder="Contoh: Tautan Cepat">
                                </div>
                                <div v-if="form.errors.quick_links_title" class="invalid-feedback d-block fw-bold mt-1">{{ form.errors.quick_links_title }}</div>
                            </div>

                            <hr class="border-secondary opacity-10 my-4">

                            <!-- List Links -->
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <label class="form-label fw-bold small text-uppercase text-muted mb-0">Daftar Link</label>
                                <button class="btn btn-primary btn-sm rounded-pill px-3" @click="addQuickLink">
                                    <i class="fas fa-plus me-1"></i> Tambah Link
                                </button>
                            </div>

                            <div class="link-list">
                                <div v-for="(link, index) in form.quick_links" :key="'quick-'+index" class="link-item card border-0 bg-light mb-2 p-2 rounded-3 animate-fade">
                                    <div class="d-flex gap-2 align-items-start">
                                        <div class="handle text-muted align-self-center ps-2 pe-1" style="cursor: move;">
                                            <i class="fas fa-grip-vertical"></i>
                                        </div>
                                        <div class="flex-grow-1">
                                            <div class="row g-2">
                                                <div class="col-md-5">
                                                    <div class="input-group input-group-sm">
                                                        <span class="input-group-text bg-white border-end-0"><i class="fas fa-tag text-muted font-small"></i></span>
                                                        <input type="text" class="form-control bg-white border-start-0 form-control-sm" 
                                                            :class="{'is-invalid': hasError('quick_links', index, 'label')}" 
                                                            v-model="link.label" placeholder="Label">
                                                    </div>
                                                </div>
                                                <div class="col-md-7">
                                                    <div class="input-group input-group-sm">
                                                        <span class="input-group-text bg-white border-end-0"><i class="fas fa-link text-muted font-small"></i></span>
                                                        <input type="text" class="form-control bg-white border-start-0 form-control-sm" 
                                                            :class="{'is-invalid': hasError('quick_links', index, 'url')}" 
                                                            v-model="link.url" placeholder="URL (/path)">
                                                    </div>
                                                    <div v-if="hasError('quick_links', index, 'url')" class="invalid-feedback d-block">{{ form.errors[`quick_links.${index}.url`] }}</div>
                                                </div>
                                            </div>
                                        </div>
                                        <button class="btn btn-link text-danger btn-sm p-1 align-self-center hover-delete" @click="removeQuickLink(index)" title="Hapus">
                                            <i class="fas fa-trash-alt"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Kolom 2 -->
                <div class="col-lg-6">
                    <div class="card border-0 shadow-sm rounded-4 h-100">
                        <div class="card-header bg-white py-3 border-bottom rounded-top-4">
                            <h5 class="fw-bold mb-0 text-success"><i class="fas fa-folder-open me-2"></i>Kolom Navigasi 2</h5>
                        </div>
                        <div class="card-body p-4">
                            <!-- Judul Kolom Input -->
                            <div class="mb-4">
                                <label class="form-label fw-bold small text-uppercase text-muted">Judul Kolom</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light border-end-0"><i class="fas fa-heading text-secondary"></i></span>
                                    <input type="text" class="form-control bg-light border-start-0 ps-0 fw-bold text-success" v-model="form.academic_links_title" placeholder="Contoh: Akademik & Layanan">
                                </div>
                                <div v-if="form.errors.academic_links_title" class="invalid-feedback d-block fw-bold mt-1">{{ form.errors.academic_links_title }}</div>
                            </div>

                            <hr class="border-secondary opacity-10 my-4">

                            <!-- List Links -->
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <label class="form-label fw-bold small text-uppercase text-muted mb-0">Daftar Link</label>
                                <button class="btn btn-success btn-sm rounded-pill px-3" @click="addAcademicLink">
                                    <i class="fas fa-plus me-1"></i> Tambah Link
                                </button>
                            </div>

                            <div class="link-list">
                                <div v-for="(link, index) in form.academic_links" :key="'academic-'+index" class="link-item card border-0 bg-light mb-2 p-2 rounded-3 animate-fade">
                                    <div class="d-flex gap-2 align-items-start">
                                        <div class="handle text-muted align-self-center ps-2 pe-1" style="cursor: move;">
                                            <i class="fas fa-grip-vertical"></i>
                                        </div>
                                        <div class="flex-grow-1">
                                            <div class="row g-2">
                                                <div class="col-md-5">
                                                    <div class="input-group input-group-sm">
                                                        <span class="input-group-text bg-white border-end-0"><i class="fas fa-tag text-muted font-small"></i></span>
                                                        <input type="text" class="form-control bg-white border-start-0 form-control-sm" 
                                                            :class="{'is-invalid': hasError('academic_links', index, 'label')}" 
                                                            v-model="link.label" placeholder="Label">
                                                    </div>
                                                </div>
                                                <div class="col-md-7">
                                                    <div class="input-group input-group-sm">
                                                        <span class="input-group-text bg-white border-end-0"><i class="fas fa-link text-muted font-small"></i></span>
                                                        <input type="text" class="form-control bg-white border-start-0 form-control-sm" 
                                                            :class="{'is-invalid': hasError('academic_links', index, 'url')}" 
                                                            v-model="link.url" placeholder="URL (/path)">
                                                    </div>
                                                    <div v-if="hasError('academic_links', index, 'url')" class="invalid-feedback d-block">{{ form.errors[`academic_links.${index}.url`] }}</div>
                                                </div>
                                            </div>
                                        </div>
                                        <button class="btn btn-link text-danger btn-sm p-1 align-self-center hover-delete" @click="removeAcademicLink(index)" title="Hapus">
                                            <i class="fas fa-trash-alt"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Live Preview -->
            <div class="card border-0 shadow-sm rounded-4 mt-5 overflow-hidden" style="background: linear-gradient(135deg, #4361ee 0%, #3a0ca3 100%);">
                <div class="card-header bg-transparent py-3 border-bottom border-light border-opacity-10 d-flex justify-content-between align-items-center">
                    <h6 class="mb-0 text-white"><i class="fas fa-eye me-2 text-white"></i>Preview Tampilan Footer</h6>
                    <span class="badge bg-white bg-opacity-25 text-white">Live Preview</span>
                </div>
                <div class="card-body py-4">
                    <div class="container-fluid px-0">
                        <div class="row">
                            <div class="col-md-6 mb-4 mb-md-0">
                                <h6 class="text-white fw-bold border-bottom border-light border-opacity-10 pb-2 mb-3">{{ form.quick_links_title || 'Judul Kolom 1' }}</h6>
                                <ul class="list-unstyled">
                                    <li v-for="(link, index) in form.quick_links" :key="'pq-'+index" class="mb-2 text-white-50 small">
                                        <i class="fas fa-caret-right me-2 text-warning"></i> <span class="text-light">{{ link.label || 'Nama Link' }}</span>
                                    </li>
                                </ul>
                            </div>
                            <div class="col-md-6">
                                <h6 class="text-white fw-bold border-bottom border-light border-opacity-10 pb-2 mb-3">{{ form.academic_links_title || 'Judul Kolom 2' }}</h6>
                                <ul class="list-unstyled">
                                    <li v-for="(link, index) in form.academic_links" :key="'pa-'+index" class="mb-2 text-white-50 small">
                                        <i class="fas fa-caret-right me-2 text-warning"></i> <span class="text-light">{{ link.label || 'Nama Link' }}</span>
                                    </li>
                                </ul>
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
    background: linear-gradient(135deg, #1e293b 0%, #0f172a 100%);
}
.icon-box-header {
    width: 48px;
    height: 48px;
    background: rgba(255,255,255,0.1);
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.5rem;
    backdrop-filter: blur(5px);
}
.hover-scale {
    transition: transform 0.2s;
}
.hover-scale:hover {
    transform: translateY(-2px);
}
.font-small {
    font-size: 0.8rem;
}
.link-item {
    transition: all 0.2s ease;
    border: 1px solid transparent !important;
}
.link-item:hover {
    background-color: #fff !important;
    border-color: #e2e8f0 !important;
    box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05), 0 2px 4px -1px rgba(0, 0, 0, 0.03);
}
.hover-delete:hover {
    background-color: #fee2e2;
    border-radius: 50%;
}
.animate-fade {
    animation: fadeIn 0.3s ease-in-out;
}
@keyframes fadeIn {
    from { opacity: 0; transform: translateY(10px); }
    to { opacity: 1; transform: translateY(0); }
}
</style>

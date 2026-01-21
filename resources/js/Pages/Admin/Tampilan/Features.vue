<script setup>
import { Head, useForm } from '@inertiajs/vue3';
import AdminLayout from '@/Layouts/AdminLayout.vue';
import Swal from 'sweetalert2';
import { ref } from 'vue';

const props = defineProps({
    features: Array,
    availableIcons: Object,
});

const form = useForm({
    features: props.features.map(f => ({
        id: f.id,
        icon: f.icon,
        title: f.title,
        description: f.description,
        is_active: f.is_active,
    })),
});

const selectedFeatureIndex = ref(null);
const showIconPicker = ref(false);

const selectIcon = (iconFile) => {
    if (selectedFeatureIndex.value !== null) {
        form.features[selectedFeatureIndex.value].icon = iconFile;
    }
    showIconPicker.value = false;
    selectedFeatureIndex.value = null;
};

const openIconPicker = (index) => {
    selectedFeatureIndex.value = index;
    showIconPicker.value = true;
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
    form.put('/admin/features', {
        onSuccess: () => {
             Toast.fire({
                icon: 'success',
                title: 'Berhasil',
                text: 'Fitur unggulan berhasil diperbarui'
            });
        },
        onError: () => {
            Toast.fire({
                icon: 'error',
                title: 'Gagal',
                text: 'Mohon periksa isian yang ditandai merah'
            });
        },
    });
};
</script>

<template>
    <Head title="Kelola Feature" />
    
    <AdminLayout>
        <div class="content container-fluid">
            <!-- Radiant Header -->
            <div class="card border-0 rounded-4 mb-4 overflow-hidden" style="background: linear-gradient(135deg, #4361ee 0%, #3a0ca3 100%);">
                <div class="card-body p-4 position-relative">
                    <div class="d-flex align-items-center gap-4 position-relative z-1">
                        <div class="icon-box bg-white bg-opacity-25 rounded-4 p-3 d-flex align-items-center justify-content-center backdrop-blur" style="width: 64px; height: 64px;">
                            <i class="fas fa-star fa-2x text-white"></i>
                        </div>
                        <div>
                            <h3 class="fw-bold mb-1 text-white">Kelola Fitur Unggulan</h3>
                            <p class="mb-0 text-white-50">Atur 4 poin utama yang tampil di bawah slider</p>
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
                                <li class="breadcrumb-item active text-primary fw-bold" aria-current="page">Features</li>
                            </div>
                            
                            <!-- Save Button Moved Here -->
                            <button class="btn btn-primary rounded-pill shadow-sm px-4 fw-bold" @click="submit" :disabled="form.processing">
                                <i class="fas fa-save me-2"></i> {{ form.processing ? 'Menyimpan...' : 'Simpan Perubahan' }}
                            </button>
                        </ol>
                    </nav>
                </div>
            </div>

            <!-- Feature Cards Grid -->
            <div class="row g-4">
                <div v-for="(feature, index) in form.features" :key="feature.id" class="col-lg-6">
                    <div class="card h-100 border-0 rounded-4 shadow-sm section-card hover-elevate">
                        <div class="card-header border-0 bg-white pt-4 pb-0 px-4 d-flex justify-content-between align-items-center">
                            <div class="d-flex align-items-center gap-3">
                                <span class="badge bg-primary text-white rounded-pill px-3 py-2 fw-bold">#{{ index + 1 }}</span>
                            </div>
                            <div class="d-flex align-items-center gap-4 px-3" style="min-width: 140px;">
                                <span class="fw-bold" :class="feature.is_active ? 'text-success' : 'text-danger'">
                                    {{ feature.is_active ? 'Aktif' : 'Nonaktif' }}
                                </span>
                                <div class="form-check form-switch mb-0">
                                    <input class="form-check-input" type="checkbox" v-model="feature.is_active" :id="'active-' + index" style="width: 3em; height: 1.5em; cursor: pointer;">
                                </div>
                            </div>
                        </div>
                        
                        <div class="card-body p-4">
                            <!-- Icon Selector -->
                            <div class="mb-4">
                                <label class="form-label fw-bold text-dark mb-2">Icon</label>
                                <div 
                                    class="d-flex align-items-center gap-3 p-3 border-2 rounded-4 bg-white hover-bg-light-darker transition-all border-dashed"
                                    :class="{'border-danger': form.errors[`features.${index}.icon`]}"
                                    style="cursor: pointer; border-style: dashed !important; border-color: #dee2e6;"
                                    @click="openIconPicker(index)"
                                >
                                    <div class="icon-box rounded-circle d-flex align-items-center justify-content-center shadow-sm" style="width: 50px; height: 50px; background: linear-gradient(135deg, #198754 0%, #157347 100%);">
                                        <img :src="`/assets/img/icon/${feature.icon}`" alt="Icon" style="width: 24px; height: 24px; filter: brightness(0) invert(1);">
                                    </div>
                                    <div class="flex-grow-1">
                                        <span class="fw-bold text-dark d-block text-truncate">{{ availableIcons[feature.icon] }}</span>
                                        <span class="text-muted small">Klik untuk ganti icon</span>
                                    </div>
                                    <div>
                                        <i class="fas fa-chevron-right text-muted small"></i>
                                    </div>
                                </div>
                                <div v-if="form.errors[`features.${index}.icon`]" class="invalid-feedback d-block fw-bold mt-1">{{ form.errors[`features.${index}.icon`] }}</div>
                            </div>
                             
                            <!-- Title Input (Group + Icon) -->
                            <div class="mb-3">
                                <label class="form-label fw-bold text-dark">Judul Feature</label>
                                <div class="input-group shadow-sm rounded-4 overflow-hidden">
                                    <span class="input-group-text bg-white border-0 ps-3 text-primary"><i class="fas fa-heading"></i></span>
                                    <input type="text" class="form-control border-0 bg-white" :class="{'is-invalid': form.errors[`features.${index}.title`]}" v-model="feature.title" placeholder="Contoh: Kurikulum Merdeka">
                                </div>
                                <div v-if="form.errors[`features.${index}.title`]" class="invalid-feedback d-block fw-bold mt-1">{{ form.errors[`features.${index}.title`] }}</div>
                            </div>
                            
                            <!-- Description Input (Group + Icon) -->
                            <div>
                                <label class="form-label fw-bold text-dark">Deskripsi Singkat</label>
                                <div class="input-group shadow-sm rounded-4 overflow-hidden">
                                    <span class="input-group-text bg-white border-0 ps-3 pt-3 align-items-start text-secondary"><i class="fas fa-align-left"></i></span>
                                    <textarea class="form-control border-0 bg-white" :class="{'is-invalid': form.errors[`features.${index}.description`]}" v-model="feature.description" rows="3" placeholder="Jelaskan keunggulan ini..."></textarea>
                                </div>
                                <div v-if="form.errors[`features.${index}.description`]" class="invalid-feedback d-block fw-bold mt-1">{{ form.errors[`features.${index}.description`] }}</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Icon Picker Modal -->
        <div v-if="showIconPicker" class="modal-backdrop fade show"></div>
        <div v-if="showIconPicker" class="modal fade show d-block" tabindex="-1" @click.self="showIconPicker = false">
            <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
                <div class="modal-content border-0 rounded-4 shadow-lg overflow-hidden">
                    <div class="modal-header border-bottom">
                         <h5 class="modal-title fw-bold text-dark"><i class="fas fa-icons me-2 text-primary"></i>Pilih Icon</h5>
                        <button type="button" class="btn-close" @click="showIconPicker = false"></button>
                    </div>
                    <div class="modal-body p-4 bg-light">
                        <div class="row g-3">
                            <div v-for="(label, iconFile) in availableIcons" :key="iconFile" class="col-4 col-md-3">
                                <div 
                                    class="icon-option text-center p-3 rounded-3 bg-white border shadow-sm h-100 d-flex flex-column align-items-center justify-content-center"
                                    :class="{ 'selected': form.features[selectedFeatureIndex]?.icon === iconFile }"
                                    @click="selectIcon(iconFile)"
                                >
                                    <div class="mb-2 d-flex align-items-center justify-content-center" style="height: 40px; width: 40px;">
                                        <img :src="`/assets/img/icon/${iconFile}`" class="icon-img" style="max-width: 100%; max-height: 100%;">
                                    </div>
                                    <p class="small mb-0 text-dark fw-bold text-truncate w-100">{{ label }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AdminLayout>
</template>

<style scoped>
.section-card {
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
}
.section-card:hover {
    transform: translateY(-5px);
}
.hover-bg-light-darker:hover {
    background-color: #e9ecef !important;
}
.hover-shadow:hover {
    box-shadow: 0 .5rem 1rem rgba(0,0,0,.15)!important;
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

/* Icon Picker Styles */

/* Icon Picker Styles */
/* Icon Picker Styles */
/* Icon Picker Styles */
.icon-option {
    cursor: pointer;
    transition: all 0.2s;
    border: 2px solid transparent;
}
.icon-option:hover {
    transform: translateY(-3px);
    border-color: #198754;
}
.icon-option.selected {
    background-color: #198754 !important;
    border-color: #198754;
}
.icon-option.selected p {
    color: white !important;
}
.icon-option.selected img {
    filter: brightness(0) invert(1);
}
.icon-img {
    width: 48px;
    height: 48px;
    object-fit: contain;
    /* Green Filter for #198754 */
    filter: invert(34%) sepia(77%) saturate(452%) hue-rotate(108deg) brightness(93%) contrast(89%);
}
</style>

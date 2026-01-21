<script setup>
import { Head, Link, useForm } from '@inertiajs/vue3';
import AdminLayout from '@/Layouts/AdminLayout.vue';
import Swal from 'sweetalert2';
import { ref, onMounted } from 'vue';

const props = defineProps({
    section: Object,
});

const content = props.section?.content || {};
const items = content.items || [];

const defaultItems = Array(6).fill().map(() => ({
    category: '',
    title: '',
    url: '',
    image: null
}));

const mergedItems = items.length === 6 ? items : defaultItems;

const form = useForm({
    tagline: content.tagline || 'Portofolio',
    title: content.title || 'Karya <span>Terbaru</span>',
    items: mergedItems.map(item => ({
        category: item.category,
        title: item.title,
        url: item.url,
        image: item.image, // Keep existing image path
    })),
    // Validation placeholders for file uploads
    image_0: null,
    image_1: null,
    image_2: null,
    image_3: null,
    image_4: null,
    image_5: null,
});

const previews = ref(mergedItems.map(item => item.image));
const titleEditor = ref(null);

// SVG placeholder as data URI (no external file needed)
const placeholderImage = "data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='400' height='300' viewBox='0 0 400 300'%3E%3Crect fill='%23e9ecef' width='400' height='300'/%3E%3Ctext x='50%25' y='45%25' dominant-baseline='middle' text-anchor='middle' font-family='Arial,sans-serif' font-size='48' fill='%23adb5bd'%3E📷%3C/text%3E%3Ctext x='50%25' y='60%25' dominant-baseline='middle' text-anchor='middle' font-family='Arial,sans-serif' font-size='14' fill='%23adb5bd'%3EKlik untuk upload gambar%3C/text%3E%3C/svg%3E";

const getPreview = (index) => {
    return previews.value[index] || placeholderImage;
};

// Check if any field in an item has an error
const hasItemError = (index) => {
    const fields = ['category', 'title', 'url'];
    return fields.some(field => form.errors[`items.${index}.${field}`]);
};

onMounted(() => {
    if (titleEditor.value) {
        titleEditor.value.innerHTML = form.title;
    }
});

const handleImageChange = (e, index) => {
    const file = e.target.files[0];
    if (file) {
        form[`image_${index}`] = file;
        previews.value[index] = URL.createObjectURL(file);
    }
};

const updateTitle = () => {
    if (titleEditor.value) {
        form.title = titleEditor.value.innerHTML;
    }
};

const warnaiTeks = () => {
    const selection = window.getSelection();
    if (selection.rangeCount > 0 && selection.toString().trim() !== '') {
        const range = selection.getRangeAt(0);
        const span = document.createElement('span');
        span.appendChild(range.extractContents());
        range.insertNode(span);
        selection.removeAllRanges();
        updateTitle();
    } else {
        Swal.fire('Info', 'Block/select teks dulu yang mau diwarnai', 'info');
    }
};

const hapusWarna = () => {
    if (titleEditor.value) {
        titleEditor.value.innerHTML = titleEditor.value.innerText;
        updateTitle();
    }
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
    updateTitle();
    form.post('/admin/portfolio', {
        forceFormData: true,
        preserveScroll: true,
        onSuccess: () => {
             Toast.fire({
                icon: 'success',
                title: 'Berhasil',
                text: 'Portofolio berhasil diperbarui'
            });
        },
        onError: () => {
            Toast.fire({
                icon: 'error',
                title: 'Gagal memperbarui portofolio',
                text: 'Mohon periksa isian yang ditandai merah'
            });
        }
    });
};
</script>

<template>
    <Head title="Edit Portofolio" />

    <AdminLayout>
       <div class="content container-fluid">
            <!-- Radiant Header -->
            <div class="card border-0 rounded-4 mb-4 overflow-hidden" style="background: linear-gradient(135deg, #4361ee 0%, #3a0ca3 100%);">
                <div class="card-body p-4 position-relative">
                    <div class="d-flex align-items-center gap-4 position-relative z-1">
                        <div class="icon-box bg-white bg-opacity-25 rounded-4 p-3 d-flex align-items-center justify-content-center backdrop-blur" style="width: 64px; height: 64px;">
                            <i class="fas fa-camera fa-2x text-white"></i>
                        </div>
                        <div>
                            <h3 class="fw-bold mb-1 text-white">Portofolio Kegiatan</h3>
                            <p class="mb-0 text-white-50">Kelola 6 foto kegiatan unggulan sekolah</p>
                        </div>
                    </div>
                    <!-- Decorative Circle -->
                    <div class="position-absolute top-0 end-0 p-3 opacity-10 pointer-events-none">
                        <i class="fas fa-camera fa-10x text-white transform-rotate-12" style="margin-right: -40px; margin-top: -40px;"></i>
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
                                <li class="breadcrumb-item active text-primary fw-bold" aria-current="page">Portofolio</li>
                            </div>
                            <!-- Save Button Moved Here -->
                            <button class="btn btn-primary rounded-pill shadow-sm px-4 fw-bold" @click="submit" :disabled="form.processing">
                                <i class="fas fa-save me-2"></i> {{ form.processing ? 'Menyimpan...' : 'Simpan Perubahan' }}
                            </button>
                        </ol>
                    </nav>
                </div>
            </div>

            <!-- Header Section Form -->
            <div class="card border-0 shadow-sm rounded-4 mb-4 section-card">
                <div class="card-header bg-white border-0 py-4 px-4">
                    <h5 class="fw-bold text-dark mb-0"><i class="fas fa-heading text-primary me-2"></i>Header Section</h5>
                </div>
                <div class="card-body p-4 pt-0">
                    <div class="row g-4">
                        <div class="col-md-6">
                            <label class="form-label fw-bold text-dark">Tagline</label>
                            <div class="input-group shadow-sm rounded-4 overflow-hidden">
                                <span class="input-group-text bg-light border-0 ps-3 text-primary"><i class="fas fa-tag"></i></span>
                                <input type="text" class="form-control border-0 bg-light" :class="{'is-invalid': form.errors.tagline}" v-model="form.tagline" placeholder="Contoh: Portofolio">
                            </div>
                            <div v-if="form.errors.tagline" class="invalid-feedback d-block fw-bold mt-1">{{ form.errors.tagline }}</div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold text-dark">Judul Utama</label>
                            <div class="mb-2">
                                <button type="button" class="btn btn-warning btn-sm me-2 text-white rounded-pill shadow-sm fw-bold" @click="warnaiTeks">
                                    <i class="fas fa-highlighter me-1"></i> Highlight
                                </button>
                                <button type="button" class="btn btn-outline-secondary btn-sm rounded-pill" @click="hapusWarna">
                                    <i class="fas fa-eraser me-1"></i> Reset
                                </button>
                            </div>
                            <div 
                                ref="titleEditor"
                                contenteditable="true"
                                class="form-control title-editor bg-light border-0 shadow-sm rounded-4 p-3"
                                :class="{'is-invalid': form.errors.title}"
                                style="min-height: 48px;"
                                @input="updateTitle"
                                @blur="updateTitle"
                            ></div>
                            <div v-if="form.errors.title" class="invalid-feedback d-block fw-bold mt-1">{{ form.errors.title }}</div>
                            <small class="text-muted mt-1 d-block"><i class="fas fa-info-circle me-1"></i>Blok teks lalu klik tombol "Highlight" untuk memberi warna orange.</small>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Grid Items -->
            <div class="row g-4">
                <div v-for="(item, index) in form.items" :key="index" class="col-xl-6">
                    <div class="card h-100 border-0 shadow-sm rounded-4 section-card position-relative overflow-hidden" :class="{'border-danger border-2': hasItemError(index)}">
                         <div class="card-header bg-white border-0 py-4 px-4 d-flex justify-content-between align-items-center">
                            <span class="badge rounded-pill px-3 py-2 border shadow-sm" :class="hasItemError(index) ? 'bg-danger-subtle text-danger border-danger-subtle' : 'bg-primary-subtle text-primary border-primary-subtle'">
                                <i class="fas fa-image me-1"></i> Portfolio #{{ index + 1 }}
                                <span v-if="hasItemError(index)" class="ms-1">⚠️</span>
                            </span>
                        </div>
                        <div class="card-body p-4 pt-0">
                            <div class="row g-4">
                                <!-- Image Column -->
                                <div class="col-md-5">
                                    <div class="mb-3">
                                        <label class="form-label fw-bold text-dark mb-2">Foto Kegiatan</label>
                                        <div class="position-relative group-image-upload shadow-sm rounded-4 overflow-hidden">
                                            <div class="ratio ratio-4x3 bg-light">
                                                <img :src="getPreview(index)" class="object-fit-cover w-100 h-100 transition-scale">
                                            </div>
                                            <div class="hover-overlay position-absolute top-0 start-0 w-100 h-100 d-flex align-items-center justify-content-center" style="background: rgba(0,0,0,0.4); opacity: 0; transition: opacity 0.2s;">
                                                <label class="btn btn-light shadow-sm fw-bold rounded-pill">
                                                    <i class="fas fa-camera text-primary me-2"></i> Ganti Foto
                                                    <input type="file" class="d-none" accept="image/*" @change="e => handleImageChange(e, index)">
                                                </label>
                                            </div>
                                        </div>
                                        <div class="text-center mt-2">
                                            <span class="badge bg-secondary-subtle text-dark border"><i class="fas fa-expand me-1"></i>Rasio 4:3</span>
                                        </div>
                                    </div>
                                </div>
                                
                                <!-- Details Column -->
                                <div class="col-md-7">
                                    <div class="bg-light p-4 rounded-4 border-0 h-100">
                                        <h6 class="fw-bold text-primary mb-3 border-bottom pb-2"><i class="fas fa-info-circle me-2"></i>Detail Item</h6>
                                        <div class="mb-3">
                                            <label class="form-label small fw-bold text-dark mb-1">Judul Kegiatan</label>
                                             <div class="input-group input-group-sm bg-white shadow-sm rounded-3 overflow-hidden">
                                                <span class="input-group-text bg-white border-0 ps-3 text-secondary"><i class="fas fa-heading"></i></span>
                                                <input type="text" class="form-control border-0 fw-bold" :class="{'is-invalid': form.errors[`items.${index}.title`]}" v-model="item.title" placeholder="Contoh: Upacara Bendera">
                                            </div>
                                            <div v-if="form.errors[`items.${index}.title`]" class="invalid-feedback d-block fw-bold mt-1">{{ form.errors[`items.${index}.title`] }}</div>
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label small fw-bold text-dark mb-1">Kategori</label>
                                            <div class="input-group input-group-sm bg-white shadow-sm rounded-3 overflow-hidden">
                                                <span class="input-group-text bg-white border-0 ps-3 text-secondary"><i class="fas fa-folder"></i></span>
                                                <input type="text" class="form-control border-0" :class="{'is-invalid': form.errors[`items.${index}.category`]}" v-model="item.category" placeholder="Contoh: Ekstrakurikuler">
                                            </div>
                                            <div v-if="form.errors[`items.${index}.category`]" class="invalid-feedback d-block fw-bold mt-1">{{ form.errors[`items.${index}.category`] }}</div>
                                        </div>
                                        <div class="mb-0">
                                            <label class="form-label small fw-bold text-dark mb-1">URL / Link (Opsional)</label>
                                            <div class="input-group input-group-sm bg-white shadow-sm rounded-3 overflow-hidden">
                                                <span class="input-group-text bg-white border-0 ps-3 text-secondary"><i class="fas fa-link"></i></span>
                                                <input type="text" class="form-control border-0 text-primary" :class="{'is-invalid': form.errors[`items.${index}.url`]}" v-model="item.url" placeholder="#">
                                            </div>
                                            <div v-if="form.errors[`items.${index}.url`]" class="invalid-feedback d-block fw-bold mt-1">{{ form.errors[`items.${index}.url`] }}</div>
                                        </div>
                                    </div>
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
.title-editor {
    min-height: 48px;
    font-size: 1.1rem;
    line-height: 1.5;
}
.title-editor:focus {
    outline: none;
    background: #fff;
    border-color: #fda31b;
}
.title-editor :deep(span) {
    color: #FDA31B;
    font-weight: 700;
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
.section-card {
    transition: transform 0.3s;
}
.section-card:hover {
    transform: translateY(-5px);
}
.transition-scale {
    transition: transform 0.3s ease;
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



<script setup>
import { Head, Link, useForm } from '@inertiajs/vue3';
import AdminLayout from '@/Layouts/AdminLayout.vue';
import Swal from 'sweetalert2';
import { ref, onMounted } from 'vue';

const props = defineProps({
    section: Object,
});

const content = props.section?.content || {};

const form = useForm({
    tagline: content.tagline || 'Video',
    title: content.title || 'Profil <span>MI Al-Hikmah</span>',
    description: content.description || '',
    button_text: content.button_text || 'Profil Madrasah',
    button_url: content.button_url || '/profil',
    youtube_url: content.youtube_url || '',
    poster_image: null,
});

const posterPreview = ref(content.poster_image || '/assets/img/hope/poster-profil.jpg');
const titleEditor = ref(null);

onMounted(() => {
    if (titleEditor.value) {
        titleEditor.value.innerHTML = form.title;
    }
});

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

const handlePosterChange = (e) => {
    const file = e.target.files[0];
    if (file) {
        form.poster_image = file;
        posterPreview.value = URL.createObjectURL(file);
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
    form.post('/admin/video', {
        forceFormData: true,
        onSuccess: () => {
            Toast.fire({
                icon: 'success',
                title: 'Berhasil',
                text: 'Section Video berhasil diperbarui'
            });
        },
        onError: () => {
            Toast.fire({
                icon: 'error',
                title: 'Gagal memperbarui section',
                text: 'Periksa kembali inputan anda.'
            });
        },
    });
};
</script>

<template>
    <Head title="Edit Video" />
    
    <AdminLayout>
        <div class="content container-fluid">
            <!-- Radiant Header -->
            <div class="card border-0 rounded-4 mb-4 overflow-hidden" style="background: linear-gradient(135deg, #4361ee 0%, #3a0ca3 100%);">
                <div class="card-body p-4 position-relative">
                    <div class="d-flex align-items-center gap-4 position-relative z-1">
                        <div class="icon-box bg-white bg-opacity-25 rounded-4 p-3 d-flex align-items-center justify-content-center backdrop-blur" style="width: 64px; height: 64px;">
                            <i class="fas fa-video fa-2x text-white"></i>
                        </div>
                        <div>
                            <h3 class="fw-bold mb-1 text-white">Video Profil</h3>
                            <p class="mb-0 text-white-50">Kelola video profil sekolah yang ditampilkan di beranda</p>
                        </div>
                    </div>
                    <!-- Decorative Circle -->
                    <div class="position-absolute top-0 end-0 p-3 opacity-10 pointer-events-none">
                        <i class="fas fa-video fa-10x text-white transform-rotate-12" style="margin-right: -40px; margin-top: -40px;"></i>
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
                                <li class="breadcrumb-item active text-primary fw-bold" aria-current="page">Video</li>
                            </div>

                            <button class="btn btn-primary rounded-pill shadow-sm px-4 fw-bold" @click="submit" :disabled="form.processing">
                                <i class="fas fa-save me-2"></i> {{ form.processing ? 'Menyimpan...' : 'Simpan Perubahan' }}
                            </button>
                        </ol>
                    </nav>
                </div>
            </div>

            <div class="row g-4">
                <!-- Left: Form -->
                <div class="col-lg-7">
                    <div class="card border-0 shadow-sm rounded-4 section-card h-100">
                        <div class="card-header bg-white border-0 py-4 px-4">
                             <h5 class="fw-bold text-dark mb-0"><i class="fas fa-edit text-primary me-2"></i>Konten Video</h5>
                        </div>
                        <div class="card-body p-4 pt-0">
                            <!-- Tagline -->
                            <div class="mb-3">
                                <label class="form-label fw-bold text-dark">Tagline</label>
                                <div class="input-group shadow-sm rounded-4 overflow-hidden">
                                     <span class="input-group-text bg-light border-0 ps-3 text-primary"><i class="fas fa-tag"></i></span>
                                    <input type="text" class="form-control border-0 bg-light" :class="{'is-invalid': form.errors.tagline}" v-model="form.tagline" placeholder="Video">
                                </div>
                                <div v-if="form.errors.tagline" class="invalid-feedback d-block fw-bold mt-1">{{ form.errors.tagline }}</div>
                            </div>
                            
                            <!-- Title with Color -->
                            <div class="mb-3">
                                <label class="form-label fw-bold text-dark">Judul Section</label>
                                <div class="mb-2">
                                    <button type="button" class="btn btn-warning btn-sm me-2 text-white rounded-pill shadow-sm fw-bold" style="background: #FDA31B; border-color: #FDA31B;" @click="warnaiTeks">
                                        <i class="fas fa-highlighter me-1"></i>Highlight
                                    </button>
                                    <button type="button" class="btn btn-outline-secondary btn-sm rounded-pill" @click="hapusWarna">
                                        <i class="fas fa-eraser me-1"></i>Reset
                                    </button>
                                </div>
                                <div 
                                    ref="titleEditor"
                                    contenteditable="true"
                                    class="form-control title-editor bg-light border-0 shadow-sm rounded-4 p-3"
                                    :class="{'is-invalid': form.errors.title}"
                                    @input="updateTitle"
                                    @blur="updateTitle"
                                ></div>
                                <small class="text-muted ms-2 mt-1 d-block"><i class="fas fa-info-circle me-1"></i>Block teks lalu klik "Warnai Teks"</small>
                                <div v-if="form.errors.title" class="invalid-feedback d-block fw-bold mt-1">{{ form.errors.title }}</div>
                            </div>
                            
                            <!-- Description -->
                            <div class="mb-3">
                                <label class="form-label fw-bold text-dark">Deskripsi</label>
                                <div class="input-group shadow-sm rounded-4 overflow-hidden">
                                    <span class="input-group-text bg-light border-0 ps-3 pt-3 align-items-start text-secondary"><i class="fas fa-align-left"></i></span>
                                    <textarea class="form-control border-0 bg-light" :class="{'is-invalid': form.errors.description}" v-model="form.description" rows="3" placeholder="Deskripsi singkat tentang video..."></textarea>
                                </div>
                                <div v-if="form.errors.description" class="invalid-feedback d-block fw-bold mt-1">{{ form.errors.description }}</div>
                            </div>

                            <!-- Button Settings -->
                            <div class="row g-3 mb-3">
                                <div class="col-md-6">
                                    <label class="form-label fw-bold text-dark">Teks Tombol</label>
                                    <div class="input-group shadow-sm rounded-4 overflow-hidden">
                                        <span class="input-group-text bg-light border-0 ps-3 text-info"><i class="fas fa-mouse-pointer"></i></span>
                                        <input type="text" class="form-control border-0 bg-light" :class="{'is-invalid': form.errors.button_text}" v-model="form.button_text" placeholder="Profil Madrasah">
                                    </div>
                                    <div v-if="form.errors.button_text" class="invalid-feedback d-block fw-bold mt-1">{{ form.errors.button_text }}</div>
                                </div>
                                <div class="col-md-6">
                                     <label class="form-label fw-bold text-dark">Link Tombol</label>
                                     <div class="input-group shadow-sm rounded-4 overflow-hidden">
                                        <span class="input-group-text bg-light border-0 ps-3 text-info"><i class="fas fa-link"></i></span>
                                        <input type="text" class="form-control border-0 bg-light" :class="{'is-invalid': form.errors.button_url}" v-model="form.button_url" placeholder="/profil">
                                    </div>
                                    <div v-if="form.errors.button_url" class="invalid-feedback d-block fw-bold mt-1">{{ form.errors.button_url }}</div>
                                </div>
                            </div>

                            <!-- YouTube URL -->
                            <div class="youtube-input-wrapper p-3 rounded-4 mt-3 bg-light border border-secondary-subtle">
                                <label class="form-label fw-bold text-dark">URL Video YouTube</label>
                                <div class="input-group shadow-sm rounded-4 overflow-hidden">
                                    <span class="input-group-text bg-primary text-white border-0 ps-3">
                                        <i class="fab fa-youtube"></i>
                                    </span>
                                    <input type="text" class="form-control border-0 bg-white" :class="{'is-invalid': form.errors.youtube_url}" v-model="form.youtube_url" placeholder="https://www.youtube.com/watch?v=...">
                                </div>
                                <div v-if="form.errors.youtube_url" class="invalid-feedback d-block fw-bold mt-1">{{ form.errors.youtube_url }}</div>
                                <small class="text-secondary mt-1 d-block">Paste link YouTube lengkap dari browser</small>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Right: Poster Upload -->
                <div class="col-lg-5">
                    <div class="card border-0 shadow-sm rounded-4 section-card h-100">
                         <div class="card-header bg-white border-0 py-4 px-4">
                            <h5 class="fw-bold text-dark mb-0"><i class="fas fa-image text-primary me-2"></i>Poster Video</h5>
                        </div>
                        <div class="card-body p-4 pt-0">
                            <!-- Poster Preview -->
                            <div class="poster-container mb-4 rounded-4 overflow-hidden shadow-sm position-relative">
                                <img :src="posterPreview" class="poster-image" alt="Poster Preview">
                                <div class="play-button">
                                    <i class="fas fa-play"></i>
                                </div>
                                <div class="poster-overlay">
                                    <span class="badge bg-primary shadow-sm">Preview</span>
                                </div>
                            </div>

                            <!-- Upload Button -->
                            <div class="upload-area p-4 text-center rounded-4 border-2 border-dashed border-secondary-subtle">
                                <div class="icon-box bg-white shadow-sm rounded-circle mx-auto mb-3 d-flex align-items-center justify-content-center" style="width: 60px; height: 60px;">
                                    <i class="fas fa-cloud-upload-alt fa-2x text-primary"></i>
                                </div>
                                <h6 class="fw-bold text-dark mb-1">Ganti Poster Video</h6>
                                <p class="text-muted small mb-3">Ukuran: 1280x720px (16:9). Max 2MB.</p>
                                <label class="btn btn-outline-primary btn-sm rounded-pill px-4 hover-shadow transition-all">
                                    <i class="fas fa-upload me-1"></i>Pilih Gambar
                                    <input type="file" class="d-none" accept="image/*" @change="handlePosterChange">
                                </label>
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
    min-height: 70px;
    font-size: 1.2rem;
    line-height: 1.6;
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
.poster-container {
    height: 220px;
    background: #000;
}
.poster-image {
    width: 100%;
    height: 100%;
    object-fit: cover;
    opacity: 0.8;
}
.play-button {
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    width: 60px;
    height: 60px;
    background: rgba(255, 255, 255, 0.9);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    color: #dc3545;
    font-size: 20px;
    box-shadow: 0 4px 15px rgba(0,0,0,0.3);
    transition: transform 0.2s;
}
.poster-container:hover .play-button {
    transform: translate(-50%, -50%) scale(1.1);
    background: #fff;
}
.poster-overlay {
    position: absolute;
    top: 10px;
    right: 10px;
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
.transition-all {
    transition: all 0.3s ease;
}
.hover-shadow:hover {
    box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15) !important;
    transform: translateY(-2px);
}
</style>



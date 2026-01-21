<script setup>
import { Head, Link, useForm } from '@inertiajs/vue3';
import AdminLayout from '@/Layouts/AdminLayout.vue';
import Swal from 'sweetalert2';
import { ref } from 'vue';

const props = defineProps({
    section: Object,
    availableIcons: Object,
});

const content = props.section?.content || {};

const form = useForm({
    tagline: content.tagline || 'Tentang Kami',
    title: content.title || '',
    highlight_word: content.highlight_word || '',
    description: content.description || '',
    item_1_icon: content.item_1_icon || 'open-book.svg',
    item_1_title: content.item_1_title || '',
    item_1_desc: content.item_1_desc || '',
    item_2_icon: content.item_2_icon || 'global-education.svg',
    item_2_title: content.item_2_title || '',
    item_2_desc: content.item_2_desc || '',
    quote: content.quote || '',
    button_text: content.button_text || 'Selengkapnya',
    button_url: content.button_url || '/profil',
    image_1: null,
    image_2: null,
    image_3: null,
});

const imagePreviews = ref({
    image_1: content.image_1 || null,
    image_2: content.image_2 || null,
    image_3: content.image_3 || null,
});

const dragging = ref({ image_1: false, image_2: false, image_3: false });
const selectedIconField = ref(null);
const showIconPicker = ref(false);

const handleImageUpload = (event, field) => {
    const file = event.target.files?.[0] || event.dataTransfer?.files?.[0];
    if (file) {
        form[field] = file;
        imagePreviews.value[field] = URL.createObjectURL(file);
    }
    dragging.value[field] = false;
};

const handleDrop = (event, field) => {
    event.preventDefault();
    handleImageUpload(event, field);
};

const openIconPicker = (field) => {
    selectedIconField.value = field;
    showIconPicker.value = true;
};

const selectIcon = (iconFile) => {
    if (selectedIconField.value) {
        form[selectedIconField.value] = iconFile;
    }
    showIconPicker.value = false;
    selectedIconField.value = null;
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

const titleEditor = ref(null);

const warnaiTeks = () => {
    const selection = window.getSelection();
    if (!selection.rangeCount) return;

    const range = selection.getRangeAt(0);
    const span = document.createElement('span');
    span.style.color = '#FDA31B';
    span.style.fontWeight = '700';
    
    try {
        range.surroundContents(span);
        updateTitle();
    } catch (e) {
        Swal.fire('Gagal', 'Blok teks dengan rapi (jangan separuh tag lain)', 'error');
    }
};

const hapusWarna = () => {
    if (titleEditor.value) {
        titleEditor.value.innerText = titleEditor.value.innerText;
        updateTitle();
    }
};

const updateTitle = () => {
    if (titleEditor.value) {
        form.title = titleEditor.value.innerHTML;
    }
};

// Initialize editor content on mount
import { onMounted } from 'vue';
onMounted(() => {
    if (titleEditor.value) {
        // If title doesn't have HTML but highlight_word exists, try to merge (optional, but good UX)
        // Or just load title.
        titleEditor.value.innerHTML = form.title || '';
    }
});

const submit = () => {
    updateTitle(); // Sync before submit
    form.post('/admin/about', {
        forceFormData: true,
        onSuccess: () => {
             Toast.fire({
                icon: 'success',
                title: 'Berhasil',
                text: 'Section Tentang Kami diperbarui'
            });
        },
        onError: (errors) => {
            Toast.fire({
                icon: 'error',
                title: 'Gagal',
                text: 'Mohon periksa isian yang ditandai merah'
            });
        },
    });
};

const imageLabels = {
    image_1: { label: 'Gambar Utama', desc: 'Gambar besar di kiri' },
    image_2: { label: 'Gambar Atas', desc: 'Gambar kanan atas' },
    image_3: { label: 'Gambar Bawah', desc: 'Gambar kanan bawah' },
};
</script>

<template>
    <Head title="Edit Tentang Kami" />
    
    <AdminLayout>
        <div class="content container-fluid">
            <!-- Radiant Header -->
            <div class="card border-0 rounded-4 mb-4 overflow-hidden" style="background: linear-gradient(135deg, #4361ee 0%, #3a0ca3 100%);">
                <div class="card-body p-4 position-relative">
                    <div class="d-flex align-items-center gap-4 position-relative z-1">
                        <div class="icon-box bg-white bg-opacity-25 rounded-4 p-3 d-flex align-items-center justify-content-center backdrop-blur" style="width: 64px; height: 64px;">
                            <i class="fas fa-info-circle fa-2x text-white"></i>
                        </div>
                        <div>
                            <h3 class="fw-bold mb-1 text-white">Kelola Tentang Kami</h3>
                            <p class="mb-0 text-white-50">Atur konten profil sekolah, visi singkat, dan gambar highlight</p>
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
                                <li class="breadcrumb-item active text-primary fw-bold" aria-current="page">Tentang Kami</li>
                            </div>

                            <!-- Save Button Moved Here -->
                            <button class="btn btn-primary rounded-pill shadow-sm px-4 fw-bold" @click="submit" :disabled="form.processing">
                                <i class="fas fa-save me-2"></i> {{ form.processing ? 'Menyimpan...' : 'Simpan Perubahan' }}
                            </button>
                        </ol>
                    </nav>
                </div>
            </div>

            <div class="row g-4">
                <!-- Kolom Kiri - Gambar -->
                <div class="col-lg-5">
                    <div class="card border-0 shadow-sm rounded-4 h-100 section-card">
                        <div class="card-header bg-white border-0 py-4 px-4">
                            <h5 class="fw-bold text-dark mb-0"><i class="fas fa-images text-primary me-2"></i>Galeri Gambar</h5>
                        </div>
                        <div class="card-body p-4 pt-0">
                            <div class="row g-4">
                                <div v-for="(info, field) in imageLabels" :key="field" class="col-12">
                                    <div class="d-flex justify-content-between align-items-center mb-2">
                                        <label class="form-label fw-bold small text-uppercase text-muted mb-0">{{ info.label }}</label>
                                        <span class="badge bg-light text-secondary rounded-pill">{{ info.desc }}</span>
                                    </div>
                                    <div 
                                        class="upload-area rounded-4 text-center position-relative overflow-hidden bg-light"
                                        :class="{ 'dragging': dragging[field], 'has-image': imagePreviews[field] }"
                                        @dragover.prevent="dragging[field] = true"
                                        @dragleave.prevent="dragging[field] = false"
                                        @drop="handleDrop($event, field)"
                                        @click="$refs[field][0].click()"
                                    >
                                        <input 
                                            type="file" 
                                            :ref="field" 
                                            class="d-none" 
                                            accept="image/*"
                                            @change="handleImageUpload($event, field)"
                                        >
                                        <template v-if="imagePreviews[field]">
                                            <img :src="imagePreviews[field]" class="preview-img">
                                            <div class="overlay backdrop-blur">
                                                <div class="bg-white rounded-circle p-3 shadow-sm mb-2">
                                                    <i class="fas fa-camera fa-lg text-primary"></i>
                                                </div>
                                                <span class="text-white fw-bold small">Klik untuk ganti</span>
                                            </div>
                                        </template>
                                        <template v-else>
                                            <div class="placeholder-content py-4">
                                                <div class="icon-box bg-white rounded-circle shadow-sm mx-auto mb-3 d-flex align-items-center justify-content-center" style="width: 50px; height: 50px;">
                                                    <i class="fas fa-cloud-upload-alt fa-lg text-primary"></i>
                                                </div>
                                                <p class="mb-1 fw-bold text-dark small">Upload</p>
                                            </div>
                                        </template>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Kolom Kanan - Konten -->
                <div class="col-lg-7">
                    <!-- Section Header -->
                    <div class="card mb-4 border-0 shadow-sm rounded-4 section-card">
                        <div class="card-header bg-white border-0 py-4 px-4">
                            <h5 class="fw-bold text-dark mb-0"><i class="fas fa-heading text-primary me-2"></i>Header Section</h5>
                        </div>
                        <div class="card-body p-4 pt-0">
                            <div class="mb-3">
                                <label class="form-label fw-bold text-dark">Judul Utama</label>
                                <div class="mb-2">
                                    <button type="button" class="btn btn-warning btn-sm me-2 text-white rounded-pill shadow-sm fw-bold" style="background: #FDA31B; border-color: #FDA31B;" @click="warnaiTeks">
                                        <i class="fas fa-highlighter me-1"></i> Highlight
                                    </button>
                                    <button type="button" class="btn btn-outline-secondary btn-sm rounded-pill" @click="hapusWarna">
                                        <i class="fas fa-eraser me-1"></i> Reset
                                    </button>
                                </div>
                                <div 
                                    ref="titleEditor"
                                    contenteditable="true"
                                    class="form-control title-editor bg-white border-0 shadow-sm rounded-4 p-3"
                                    @input="updateTitle"
                                    @blur="updateTitle"
                                ></div>
                                <div v-if="form.errors.title" class="invalid-feedback d-block fw-bold mt-1">{{ form.errors.title }}</div>
                                <small class="text-muted ms-2 mt-1 d-block"><i class="fas fa-info-circle me-1"></i>Block teks lalu klik "Highlight" untuk mewarnai.</small>
                            </div>

                            <div>
                                <label class="form-label fw-bold text-dark">Deskripsi</label>
                                <div class="input-group shadow-sm rounded-4 overflow-hidden">
                                    <span class="input-group-text bg-white border-0 ps-3 pt-3 align-items-start text-secondary"><i class="fas fa-align-left"></i></span>
                                    <textarea class="form-control border-0 bg-white" :class="{'is-invalid': form.errors.description}" v-model="form.description" rows="4" placeholder="Deskripsi tentang sekolah..."></textarea>
                                </div>
                                <div v-if="form.errors.description" class="invalid-feedback d-block fw-bold mt-1">{{ form.errors.description }}</div>
                            </div>
                        </div>
                    </div>

                    <!-- About Items -->
                    <div class="card mb-4 border-0 shadow-sm rounded-4 section-card">
                         <div class="card-header bg-white border-0 py-4 px-4">
                            <h5 class="fw-bold text-dark mb-0"><i class="fas fa-star text-primary me-2"></i>Poin Highlight</h5>
                        </div>
                        <div class="card-body p-4 pt-0">
                            <div v-for="n in 2" :key="n" class="item-box mb-4" :class="{ 'pb-4 border-bottom border-dashed': n === 1 }">
                                <div class="d-flex align-items-center gap-2 mb-3">
                                    <span class="badge bg-primary text-white rounded-pill px-3 py-2 fw-bold">#{{ n }}</span>
                                    <span class="fw-bold text-dark">Highlight Item {{ n }}</span>
                                </div>
                                
                                <div class="mb-3">
                                    <label class="form-label fw-bold text-dark">Icon</label>
                                    <div 
                                        class="d-flex align-items-center gap-3 p-3 border-2 rounded-4 bg-light hover-bg-light-darker transition-all"
                                        :class="{'border-danger': form.errors['item_' + n + '_icon']}"
                                        style="cursor: pointer; border-style: dashed !important; border-color: #dee2e6;"
                                        @click="openIconPicker('item_' + n + '_icon')"
                                    >
                                        <div class="icon-box rounded-circle d-flex align-items-center justify-content-center shadow-sm" style="width: 45px; height: 45px; background: linear-gradient(135deg, #FDA31B 0%, #d9880f 100%);">
                                            <img :src="`/assets/img/icon/${form['item_' + n + '_icon']}`" alt="Icon" style="width: 20px; height: 20px; filter: brightness(0) invert(1);">
                                        </div>
                                        <div class="flex-grow-1">
                                            <span class="fw-bold text-dark d-block">{{ availableIcons[form['item_' + n + '_icon']] }}</span>
                                            <span class="text-muted small">Klik ganti icon</span>
                                        </div>
                                        <i class="fas fa-chevron-right text-muted"></i>
                                    </div>
                                    <div v-if="form.errors['item_' + n + '_icon']" class="invalid-feedback d-block fw-bold mt-1">{{ form.errors['item_' + n + '_icon'] }}</div>
                                </div>
                                
                                <div class="mb-3">
                                    <label class="form-label fw-bold text-dark">Judul Item</label>
                                    <div class="input-group shadow-sm rounded-4 overflow-hidden">
                                        <span class="input-group-text bg-white border-0 ps-3 text-primary"><i class="fas fa-bookmark"></i></span>
                                        <input type="text" class="form-control border-0 bg-white" :class="{'is-invalid': form.errors['item_' + n + '_title']}" v-model="form['item_' + n + '_title']" placeholder="Judul item...">
                                    </div>
                                    <div v-if="form.errors['item_' + n + '_title']" class="invalid-feedback d-block fw-bold mt-1">{{ form.errors['item_' + n + '_title'] }}</div>
                                </div>
                                
                                <div>
                                    <label class="form-label fw-bold text-dark">Deskripsi Item</label>
                                    <div class="input-group shadow-sm rounded-4 overflow-hidden">
                                        <span class="input-group-text bg-white border-0 ps-3 pt-3 align-items-start text-secondary"><i class="fas fa-align-left"></i></span>
                                        <textarea class="form-control border-0 bg-white" :class="{'is-invalid': form.errors['item_' + n + '_desc']}" v-model="form['item_' + n + '_desc']" rows="2" placeholder="Deskripsi singkat..."></textarea>
                                    </div>
                                    <div v-if="form.errors['item_' + n + '_desc']" class="invalid-feedback d-block fw-bold mt-1">{{ form.errors['item_' + n + '_desc'] }}</div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Quote & Button -->
                    <div class="card border-0 shadow-sm rounded-4 section-card">
                        <div class="card-header bg-white border-0 py-4 px-4">
                            <h5 class="fw-bold text-dark mb-0"><i class="fas fa-quote-right text-primary me-2"></i>Quote & Tombol</h5>
                        </div>
                        <div class="card-body p-4 pt-0">
                            <div class="mb-4">
                                <label class="form-label fw-bold text-dark">Quote / Kutipan</label>
                                <div class="input-group shadow-sm rounded-4 overflow-hidden">
                                    <span class="input-group-text bg-white border-0 ps-3 pt-3 align-items-start text-primary"><i class="fas fa-quote-left"></i></span>
                                    <textarea class="form-control border-0 bg-white fst-italic" :class="{'is-invalid': form.errors.quote}" v-model="form.quote" rows="2" placeholder="Kutipan inspiratif..."></textarea>
                                </div>
                                <div v-if="form.errors.quote" class="invalid-feedback d-block fw-bold mt-1">{{ form.errors.quote }}</div>
                            </div>
                            <div class="row">
                                <div class="col-md-6 mb-3 mb-md-0">
                                    <label class="form-label fw-bold text-dark">Teks Tombol</label>
                                    <div class="input-group shadow-sm rounded-4 overflow-hidden">
                                        <span class="input-group-text bg-white border-0 ps-3 text-info"><i class="fas fa-mouse-pointer"></i></span>
                                        <input type="text" class="form-control border-0 bg-white" v-model="form.button_text" placeholder="Selengkapnya">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-bold text-dark">URL Tombol</label>
                                    <div class="input-group shadow-sm rounded-4 overflow-hidden">
                                        <span class="input-group-text bg-white border-0 ps-3 text-info"><i class="fas fa-link"></i></span>
                                        <input type="text" class="form-control border-0 bg-white" v-model="form.button_url" placeholder="/profil">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Icon Picker Modal -->
        <!-- Icon Picker Modal -->
        <div v-if="showIconPicker" class="modal-backdrop fade show"></div>
        <div v-if="showIconPicker" class="modal fade show d-block" tabindex="-1" @click.self="showIconPicker = false">
            <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
                <div class="modal-content border-0 rounded-4 shadow-lg overflow-hidden">
                    <div class="modal-header border-bottom">
                         <h5 class="modal-title fw-bold text-dark"><i class="fas fa-icons me-2 text-primary"></i>Pilih Icon Highlight</h5>
                        <button type="button" class="btn-close" @click="showIconPicker = false"></button>
                    </div>
                    <div class="modal-body p-4 bg-light">
                        <div class="row g-3">
                            <div v-for="(label, iconFile) in availableIcons" :key="iconFile" class="col-4 col-md-3">
                                <div 
                                    class="icon-option text-center p-3 rounded-3 bg-white border shadow-sm h-100 d-flex flex-column align-items-center justify-content-center"
                                    :class="{ 'selected': form[selectedIconField] === iconFile }"
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
.upload-area {
    border: 2px dashed #dee2e6;
    background: #f8f9fa;
    cursor: pointer;
    transition: all 0.3s ease;
    min-height: 180px;
    display: flex;
    align-items: center;
    justify-content: center;
}
.upload-area:hover, .upload-area.dragging {
    border-color: #4361ee;
    background: #f0f2ff;
}
.upload-area.has-image {
    border-style: solid;
    border-color: #dee2e6;
    padding: 0;
}
.upload-area .preview-img {
    width: 100%;
    height: 180px;
    object-fit: cover;
}
.upload-area .overlay {
    position: absolute;
    inset: 0;
    background: rgba(0,0,0,0.3);
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    opacity: 0;
    transition: opacity 0.3s;
}
.upload-area:hover .overlay {
    opacity: 1;
}
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

/* Icon Picker Styles (Matches Features.vue but Orange #fd7e14) */
.icon-option {
    cursor: pointer;
    transition: all 0.2s;
    border: 2px solid transparent;
}
.icon-option:hover {
    transform: translateY(-3px);
    border-color: #fd7e14;
}
.icon-option.selected {
    background-color: #fd7e14 !important;
    border-color: #fd7e14;
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
    /* Orange Filter for #fd7e14 */
    filter: invert(56%) sepia(68%) saturate(2250%) hue-rotate(345deg) brightness(101%) contrast(97%);
}
</style>



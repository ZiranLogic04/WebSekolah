<script setup>
import { Head, useForm } from '@inertiajs/vue3';
import AdminLayout from '@/Layouts/AdminLayout.vue';
import Swal from 'sweetalert2';
import { ref, onMounted } from 'vue';

const props = defineProps({
    section: Object,
    availableIcons: Object,
});

const content = props.section?.content || {};

// Ensure items have full path handling or fallback
const form = useForm({
    tagline: content.tagline || 'Mengapa Memilih Kami',
    title: content.title || 'Pendidikan Dasar Berkualitas & <span>Ramah Anak</span>',
    description: content.description || '',
    image: null,
    items: content.items?.length === 4 ? content.items : [
        { title: '', desc: '', icon: 'teacher-2.svg' },
        { title: '', desc: '', icon: 'course-material.svg' },
        { title: '', desc: '', icon: 'online-course.svg' },
        { title: '', desc: '', icon: 'health.svg' },
    ],
});

const mainImagePreview = ref(content.image || '/assets/img/hope/_U9A7765.jpg');
const titleEditor = ref(null);
const showIconPicker = ref(false);
const selectedItemIndex = ref(null);


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

const handleMainImageChange = (e) => {
    const file = e.target.files[0];
    if (file) {
        form.image = file;
        mainImagePreview.value = URL.createObjectURL(file);
    }
};

// Helper: check if icon is full path or filename
const getIconUrl = (iconStr) => {
    if (iconStr && iconStr.includes('/')) return iconStr;
    return '/assets/img/icon/' + iconStr;
};

// Open Modal
const openIconPicker = (index) => {
    selectedItemIndex.value = index;
    showIconPicker.value = true;
};

// Select Icon from Modal
const selectIcon = (iconName) => {
    if (selectedItemIndex.value !== null) {
        form.items[selectedItemIndex.value].icon = iconName;
    }
    showIconPicker.value = false;
    selectedItemIndex.value = null;
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
    form.post('/admin/choose', {
        forceFormData: true,
        onSuccess: () => {
            Toast.fire({
                icon: 'success',
                title: 'Berhasil',
                text: 'Section Mengapa Memilih Kami berhasil diperbarui'
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
    <Head title="Edit Mengapa Memilih Kami" />
    
    <AdminLayout>
        <div class="content container-fluid">
            <!-- Radiant Header -->
            <div class="card border-0 rounded-4 mb-4 overflow-hidden" style="background: linear-gradient(135deg, #4361ee 0%, #3a0ca3 100%);">
                <div class="card-body p-4 position-relative">
                    <div class="d-flex align-items-center gap-4 position-relative z-1">
                        <div class="icon-box bg-white bg-opacity-25 rounded-4 p-3 d-flex align-items-center justify-content-center backdrop-blur" style="width: 64px; height: 64px;">
                            <i class="fas fa-check-circle fa-2x text-white"></i>
                        </div>
                        <div>
                            <h3 class="fw-bold mb-1 text-white">Mengapa Memilih Kami</h3>
                            <p class="mb-0 text-white-50">Kelola poin keunggulan sekolah</p>
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
                                <li class="breadcrumb-item active text-primary fw-bold" aria-current="page">Mengapa Kami</li>
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
                <!-- Left: Header & Main Image -->
                <div class="col-lg-5">
                    <!-- Header Section -->
                    <div class="card border-0 shadow-sm rounded-4 mb-4">
                        <div class="card-header bg-white py-3 border-bottom rounded-top-4">
                            <h5 class="fw-bold mb-0"><i class="fas fa-edit text-success me-2"></i>Header Section</h5>
                        </div>
                        <div class="card-body p-4">
                            <div class="mb-3">
                                <label class="form-label fw-bold text-secondary">Tagline</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light border-end-0" style="color: #FDA31B;"><i class="fas fa-tag"></i></span>
                                    <input type="text" class="form-control border-start-0 ps-0 fw-bold text-dark" :class="{'is-invalid': form.errors.tagline}" v-model="form.tagline" placeholder="Tagline...">
                                </div>
                                <div v-if="form.errors.tagline" class="invalid-feedback d-block fw-bold mt-1">{{ form.errors.tagline }}</div>
                            </div>
                            <div class="mb-3">
                                <label class="form-label fw-bold text-secondary">Judul Section</label>
                                <div class="mb-2">
                                    <button type="button" class="btn btn-warning btn-sm me-2 text-white px-3 fw-bold shadow-sm" style="background: #FDA31B; border-color: #FDA31B;" @click="warnaiTeks">
                                        <i class="fas fa-palette me-1"></i> Warnai (Emas)
                                    </button>
                                    <button type="button" class="btn btn-outline-secondary btn-sm px-3 fw-bold" @click="hapusWarna">
                                        <i class="fas fa-eraser me-1"></i> Reset
                                    </button>
                                </div>
                                <div 
                                    ref="titleEditor"
                                    contenteditable="true"
                                    class="form-control title-editor rounded-3"
                                    :class="{'is-invalid': form.errors.title}"
                                    @input="updateTitle"
                                    @blur="updateTitle"
                                ></div>
                                <div v-if="form.errors.title" class="invalid-feedback d-block fw-bold mt-1">{{ form.errors.title }}</div>
                                <small class="text-muted mt-1 d-block"><i class="fas fa-info-circle me-1"></i>Blok teks lalu klik tombol "Warnai" untuk memberi warna emas.</small>
                            </div>
                            <div class="mb-0">
                                <label class="form-label fw-bold text-secondary">Deskripsi</label>
                                <textarea class="form-control border-2" :class="{'is-invalid': form.errors.description}" v-model="form.description" rows="3" placeholder="Deskripsi..."></textarea>
                                <div v-if="form.errors.description" class="invalid-feedback d-block fw-bold mt-1">{{ form.errors.description }}</div>
                            </div>
                        </div>
                    </div>

                    <!-- Main Image -->
                    <div class="card border-0 shadow-sm rounded-4">
                        <div class="card-header bg-white py-3 border-bottom rounded-top-4">
                            <h5 class="fw-bold mb-0"><i class="fas fa-image me-2" style="color: #FDA31B;"></i>Gambar Utama</h5>
                        </div>
                        <div class="card-body p-4">
                            <div class="main-image-preview mb-3 rounded-3 overflow-hidden shadow-sm">
                                <img :src="mainImagePreview" class="w-100 object-fit-cover" style="height: 300px;">
                            </div>
                            <div class="text-center">
                                <div class="mb-3 text-muted small">
                                    <p class="mb-1"><i class="fas fa-info-circle me-1"></i>Saran Ukuran: <strong>800x1200 px</strong> (Rasio Portrait 2:3).</p>
                                    <p class="mb-0">Format: JPG/PNG/WEBP. Max: 2MB.</p>
                                </div>
                                <label class="btn btn-warning w-100 fw-bold shadow-sm text-white" style="background: #FDA31B; border-color: #FDA31B;">
                                    <i class="fas fa-upload me-2"></i>Ganti Gambar
                                    <input type="file" class="d-none" accept="image/*" @change="handleMainImageChange">
                                </label>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Right: Items Grid -->
                <div class="col-lg-7">
                    <div class="card border-0 shadow-sm rounded-4 h-100">
                        <div class="card-header bg-white py-3 border-bottom rounded-top-4">
                            <h5 class="fw-bold mb-0"><i class="fas fa-list-ul me-2" style="color: #FDA31B;"></i>4 Poin Keunggulan</h5>
                        </div>
                        <div class="card-body p-4">
                            <div class="row g-4">
                                <div v-for="(item, index) in form.items" :key="index" class="col-md-6">
                                    <div class="item-card border rounded-4 p-4 bg-white h-100 position-relative shadow-sm">
                                        
                                        <!-- Header Item -->
                                        <div class="d-flex align-items-center justify-content-between mb-3">
                                            <span class="badge bg-primary text-white px-3 py-2 rounded-pill fw-bold">Poin #{{ index + 1 }}</span>
                                        </div>

                                        <!-- Icon Selector (Orange Style) -->
                                        <div class="mb-3">
                                            <label class="form-label fw-bold small text-secondary">Icon</label>
                                            <div 
                                                class="icon-selector d-flex align-items-center gap-3 p-3 rounded-3 shadow-sm"
                                                @click="openIconPicker(index)"
                                            >
                                                <div class="icon-box" style="background: linear-gradient(135deg, #FDA31B 0%, #d9880f 100%); width: 48px; height: 48px; border-radius: 10px; display: flex; align-items: center; justify-content: center;">
                                                    <img :src="getIconUrl(item.icon)" class="icon-img" style="filter: brightness(0) invert(1); width: 28px; height: 28px;">
                                                </div>
                                                <div class="flex-grow-1">
                                                    <span class="fw-bold text-white d-block text-truncate">
                                                        {{ availableIcons[item.icon] || 'Pilih Icon' }}
                                                    </span>
                                                    <p class="x-small mb-0 text-white-50">Klik untuk ganti</p>
                                                </div>
                                                <i class="fas fa-chevron-right text-white small"></i>
                                            </div>
                                        </div>

                                        <div class="mb-3">
                                            <label class="form-label small fw-bold mb-1 text-secondary">Judul Poin</label>
                                            <div class="input-group input-group-sm">
                                                <span class="input-group-text bg-light border-end-0" style="color: #FDA31B;"><i class="fas fa-heading"></i></span>
                                                <input type="text" class="form-control border-start-0 ps-0 fw-bold" :class="{'is-invalid': form.errors[`items.${index}.title`]}" v-model="item.title" placeholder="Judul...">
                                            </div>
                                            <div v-if="form.errors[`items.${index}.title`]" class="invalid-feedback d-block fw-bold mt-1">{{ form.errors[`items.${index}.title`] }}</div>
                                        </div>
                                        <div class="mb-0">
                                            <label class="form-label small fw-bold mb-1 text-secondary">Deskripsi</label>
                                            <textarea class="form-control form-control-sm border-2" :class="{'is-invalid': form.errors[`items.${index}.desc`]}" v-model="item.desc" rows="3" placeholder="Penjelasan singkat..."></textarea>
                                            <div v-if="form.errors[`items.${index}.desc`]" class="invalid-feedback d-block fw-bold mt-1">{{ form.errors[`items.${index}.desc`] }}</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Icon Picker Modal (Standard About.vue Style) -->
        <div v-if="showIconPicker" class="modal-backdrop fade show"></div>
        <div v-if="showIconPicker" class="modal fade show d-block" tabindex="-1" @click.self="showIconPicker = false">
            <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
                <div class="modal-content border-0 rounded-4 shadow-lg overflow-hidden">
                    <div class="modal-header border-bottom">
                         <h5 class="modal-title fw-bold text-dark"><i class="fas fa-icons me-2 text-primary"></i>Pilih Icon Poin</h5>
                        <button type="button" class="btn-close" @click="showIconPicker = false"></button>
                    </div>
                    <div class="modal-body p-4 bg-light">
                        <div class="row g-3">
                            <div v-for="(label, iconFile) in availableIcons" :key="iconFile" class="col-4 col-md-3">
                                <div 
                                    class="icon-option text-center p-3 rounded-3 bg-white border shadow-sm h-100 d-flex flex-column align-items-center justify-content-center"
                                    :class="{ 'selected': form.items[selectedItemIndex]?.icon === iconFile }"
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
.header-card {
    background: linear-gradient(135deg, #FDA31B 0%, #d9880f 100%);
}
.icon-box-header {
    width: 50px;
    height: 50px;
    background: rgba(255,255,255,0.2);
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.5rem;
    color: white;
}
.title-editor {
    min-height: 60px;
    font-size: 1.1rem;
    line-height: 1.5;
    border: 2px solid #dee2e6;
    background: #f8f9fa;
}
.title-editor:focus {
    outline: none;
    border-color: #FDA31B;
    box-shadow: 0 0 0 0.2rem rgba(253, 163, 27, 0.15);
    background: #fff;
}
.title-editor :deep(span) {
    color: #FDA31B;
}

/* Icon Selector Styles (Orange - Radiant) */
.icon-selector {
    background: #FDA31B;
    cursor: pointer;
    transition: all 0.2s;
}
.icon-selector:hover {
    background: #d9880f;
    transform: translateY(-2px);
}
.icon-box {
    width: 48px;
    height: 48px;
    background: rgba(255,255,255,0.15);
    border-radius: 10px;
    display: flex;
    align-items: center;
    justify-content: center;
}

/* Modal Icon Option Styles (Matches About.vue EXACTLY) */
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
    /* Orange Filter for #fd7e14 from About.vue */
    filter: invert(56%) sepia(68%) saturate(2250%) hue-rotate(345deg) brightness(101%) contrast(97%);
}
.x-small { font-size: 0.75rem; }

.item-card {
    transition: transform 0.2s;
}
.item-card:hover {
    box-shadow: 0 4px 12px rgba(0,0,0,0.08); 
}
.rounded-4 {
    border-radius: 1rem !important;
}
.rounded-top-4 {
    border-top-left-radius: 1rem !important;
    border-top-right-radius: 1rem !important;
}
</style>

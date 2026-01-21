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

const defaultItems = items.length > 0 ? items : [
    { name: '', role: '', quote: '', rating: 5, image: null }
];

const form = useForm({
    tagline: content.tagline || 'Testimoni',
    title: content.title || 'Apa Kata <span>Mereka</span>',
    subtitle: content.subtitle || 'Cerita singkat dari orang tua, siswa, dan alumni tentang pengalaman belajar.',
    items: defaultItems.map(item => ({
        name: item.name,
        role: item.role,
        quote: item.quote,
        rating: item.rating || 5,
        image: item.image,
        image_file: null
    })),
});

const previews = ref(form.items.map(item => item.image));
const titleEditor = ref(null);

// Placeholder avatar
const placeholderImage = "data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='150' height='150' viewBox='0 0 150 150'%3E%3Crect fill='%23e9ecef' width='150' height='150'/%3E%3Ctext x='50%25' y='45%25' dominant-baseline='middle' text-anchor='middle' font-family='Arial,sans-serif' font-size='40' fill='%23adb5bd'%3E👤%3C/text%3E%3Ctext x='50%25' y='70%25' dominant-baseline='middle' text-anchor='middle' font-family='Arial,sans-serif' font-size='10' fill='%23adb5bd'%3EUpload Foto%3C/text%3E%3C/svg%3E";

const getPreview = (index) => {
    return previews.value[index] || placeholderImage;
};

const hasItemError = (index) => {
    const fields = ['name', 'role', 'quote', 'rating'];
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
        form.items[index].image_file = file;
        previews.value[index] = URL.createObjectURL(file);
    }
};

const addItem = () => {
    form.items.push({
        name: '',
        role: '',
        quote: '',
        rating: 5,
        image: null,
        image_file: null
    });
    previews.value.push(null);
};

const removeItem = (index) => {
    if (form.items.length <= 1) {
        Swal.fire('Tidak Bisa', 'Minimal harus ada 1 testimoni.', 'warning');
        return;
    }
    Swal.fire({
        title: 'Hapus Testimoni?',
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
    form.post('/admin/testimonial', {
        forceFormData: true,
        preserveScroll: true,
        onSuccess: () => {
             Toast.fire({
                icon: 'success',
                title: 'Berhasil',
                text: 'Testimoni berhasil diperbarui'
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
    <Head title="Edit Testimoni" />

    <AdminLayout>
        <div class="content container-fluid">
             <!-- Radiant Header -->
            <div class="card border-0 rounded-4 mb-4 overflow-hidden" style="background: linear-gradient(135deg, #4361ee 0%, #3a0ca3 100%);">
                <div class="card-body p-4 position-relative">
                    <div class="d-flex align-items-center gap-4 position-relative z-1">
                        <div class="icon-box bg-white bg-opacity-25 rounded-4 p-3 d-flex align-items-center justify-content-center backdrop-blur" style="width: 64px; height: 64px;">
                            <i class="fas fa-quote-right fa-2x text-white"></i>
                        </div>
                        <div>
                            <h3 class="fw-bold mb-1 text-white">Testimoni</h3>
                            <p class="mb-0 text-white-50">Kelola testimoni dari orang tua, siswa, dan alumni</p>
                        </div>
                    </div>
                    <!-- Decorative Circle -->
                    <div class="position-absolute top-0 end-0 p-3 opacity-10 pointer-events-none">
                        <i class="fas fa-quote-right fa-10x text-white transform-rotate-12" style="margin-right: -40px; margin-top: -40px;"></i>
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
                                <li class="breadcrumb-item active text-primary fw-bold" aria-current="page">Testimoni</li>
                            </div>
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
                        <div class="col-md-4">
                            <label class="form-label fw-bold text-dark">Tagline</label>
                            <div class="input-group shadow-sm rounded-4 overflow-hidden">
                                <span class="input-group-text bg-light border-0 ps-3 text-primary"><i class="fas fa-tag"></i></span>
                                <input type="text" class="form-control border-0 bg-light" :class="{'is-invalid': form.errors.tagline}" v-model="form.tagline" placeholder="Contoh: Testimoni">
                            </div>
                            <div v-if="form.errors.tagline" class="invalid-feedback d-block fw-bold mt-1">{{ form.errors.tagline }}</div>
                        </div>
                        <div class="col-md-4">
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
                        </div>
                        <div class="col-md-4">
                             <label class="form-label fw-bold text-dark">Subtitle</label>
                             <div class="input-group shadow-sm rounded-4 overflow-hidden">
                                <span class="input-group-text bg-light border-0 ps-3 pt-3 align-items-start text-secondary"><i class="fas fa-align-left"></i></span>
                                <textarea class="form-control border-0 bg-light" :class="{'is-invalid': form.errors.subtitle}" rows="3" v-model="form.subtitle" placeholder="Deskripsi singkat..."></textarea>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Add Button -->
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h5 class="fw-bold text-dark mb-0"><i class="fas fa-comments me-2"></i>Daftar Testimoni ({{ form.items.length }})</h5>
                <button class="btn btn-primary rounded-pill shadow-sm px-4 fw-bold" @click="addItem">
                    <i class="fas fa-plus me-2"></i>Tambah Testimoni
                </button>
            </div>

            <!-- Grid Items -->
            <div class="row g-4">
                <div v-for="(item, index) in form.items" :key="index" class="col-xl-6">
                    <div class="card h-100 border-0 shadow-sm rounded-4 section-card position-relative overflow-hidden" :class="{'border-danger border-2': hasItemError(index)}">
                         <div class="card-header bg-white border-0 py-4 px-4 d-flex justify-content-between align-items-center">
                            <span class="badge rounded-pill px-3 py-2 border shadow-sm" :class="hasItemError(index) ? 'bg-danger-subtle text-danger border-danger-subtle' : 'bg-primary-subtle text-primary border-primary-subtle'">
                                <i class="fas fa-quote-left me-1"></i> Testimoni #{{ index + 1 }}
                                <span v-if="hasItemError(index)" class="ms-1">⚠️</span>
                            </span>
                            <button class="btn btn-outline-danger btn-sm rounded-circle px-2" @click="removeItem(index)" title="Hapus Testimoni">
                                <i class="fas fa-trash"></i>
                            </button>
                        </div>
                        <div class="card-body p-4 pt-0">
                            <div class="row g-4">
                                <!-- Avatar Column -->
                                <div class="col-md-4 text-center">
                                    <label class="form-label fw-bold text-dark mb-2">Foto</label>
                                    <div class="position-relative mx-auto group-image-upload shadow-sm rounded-circle overflow-hidden mb-3" style="width: 120px; height: 120px;">
                                        <img :src="getPreview(index)" class="w-100 h-100 object-fit-cover transition-scale">
                                        <div class="hover-overlay position-absolute top-0 start-0 w-100 h-100 d-flex align-items-center justify-content-center" style="background: rgba(0,0,0,0.5); opacity: 0; transition: opacity 0.2s;">
                                            <label class="btn btn-light btn-sm shadow-sm fw-bold rounded-pill">
                                                <i class="fas fa-camera text-primary"></i>
                                                <input type="file" class="d-none" accept="image/*" @change="e => handleImageChange(e, index)">
                                            </label>
                                        </div>
                                    </div>
                                    
                                    <!-- Rating Stars -->
                                    <div class="mt-3">
                                        <label class="form-label fw-bold text-secondary small mb-1">Rating</label>
                                        <div class="rating-stars">
                                            <i v-for="star in 5" :key="star" 
                                               class="fas fa-star fs-5 cursor-pointer"
                                               :class="star <= item.rating ? 'text-warning' : 'text-muted'"
                                               @click="item.rating = star"
                                               style="cursor: pointer;"></i>
                                        </div>
                                        <div v-if="form.errors[`items.${index}.rating`]" class="text-danger small mt-1">{{ form.errors[`items.${index}.rating`] }}</div>
                                    </div>
                                </div>
                                
                                <!-- Details Column -->
                                <div class="col-md-8">
                                    <div class="bg-light p-4 rounded-4 border-0 h-100">
                                        <h6 class="fw-bold text-primary mb-3 border-bottom pb-2"><i class="fas fa-user me-2"></i>Detail Pemberi Testimoni</h6>
                                        <div class="mb-3">
                                            <label class="form-label small fw-bold text-dark mb-1">Nama</label>
                                            <div class="input-group input-group-sm bg-white shadow-sm rounded-3 overflow-hidden">
                                                <span class="input-group-text bg-white border-0 ps-3 text-secondary"><i class="fas fa-id-card"></i></span>
                                                <input type="text" class="form-control border-0 fw-bold" :class="{'is-invalid': form.errors[`items.${index}.name`]}" v-model="item.name" placeholder="Contoh: Ibu Rina">
                                            </div>
                                            <div v-if="form.errors[`items.${index}.name`]" class="invalid-feedback d-block fw-bold mt-1">{{ form.errors[`items.${index}.name`] }}</div>
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label small fw-bold text-dark mb-1">Peran</label>
                                            <div class="input-group input-group-sm bg-white shadow-sm rounded-3 overflow-hidden">
                                                 <span class="input-group-text bg-white border-0 ps-3 text-secondary"><i class="fas fa-briefcase"></i></span>
                                                <input type="text" class="form-control border-0" :class="{'is-invalid': form.errors[`items.${index}.role`]}" v-model="item.role" :list="'role-list-'+index" placeholder="Ketik atau pilih peran...">
                                            </div>
                                            <datalist :id="'role-list-'+index">
                                                <option value="Orang Tua/Wali"></option>
                                                <option value="Siswa"></option>
                                                <option value="Alumni"></option>
                                                <option value="Komite Sekolah"></option>
                                                <option value="Guru"></option>
                                                <option value="Mitra Kerja Sama"></option>
                                            </datalist>
                                            <div v-if="form.errors[`items.${index}.role`]" class="invalid-feedback d-block fw-bold mt-1">{{ form.errors[`items.${index}.role`] }}</div>
                                        </div>
                                        <div class="mb-0">
                                            <label class="form-label small fw-bold text-dark mb-1">Teks Testimoni</label>
                                            <div class="input-group input-group-sm bg-white shadow-sm rounded-3 overflow-hidden">
                                                <span class="input-group-text bg-white border-0 ps-3 pt-2 align-items-start text-secondary"><i class="fas fa-comment-dots"></i></span>
                                                <textarea class="form-control border-0" :class="{'is-invalid': form.errors[`items.${index}.quote`]}" rows="3" v-model="item.quote" placeholder="Tuliskan testimoni..."></textarea>
                                            </div>
                                            <div v-if="form.errors[`items.${index}.quote`]" class="invalid-feedback d-block fw-bold mt-1">{{ form.errors[`items.${index}.quote`] }}</div>
                                            <small class="text-muted d-block text-end mt-1" style="font-size: 0.75rem;">Maks 300 karakter</small>
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
.rating-stars i {
    transition: color 0.15s, transform 0.15s;
}
.rating-stars i:hover {
    transform: scale(1.2);
}
.transition-scale {
    transition: transform 0.3s ease;
}
</style>



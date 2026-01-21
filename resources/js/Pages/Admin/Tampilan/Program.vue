<script setup>
import { Head, Link, useForm } from '@inertiajs/vue3';
import AdminLayout from '@/Layouts/AdminLayout.vue';
import Swal from 'sweetalert2';
import { ref, onMounted } from 'vue';

const props = defineProps({
    section: Object,
    availableIcons: Object,
});

const content = props.section?.content || {};

const form = useForm({
    tagline: content.tagline || 'Program MI',
    title: content.title || 'Jelajahi <span>Program & Layanan</span> Kami',
    description: content.description || '',
    items: content.items || [],
});

const titleEditor = ref(null);
const showIconModal = ref(false);
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

const addItem = () => {
    if (form.items.length >= 10) {
        Swal.fire('Batas Limit', 'Maksimal 10 program items', 'warning');
        return;
    }
    form.items.push({ icon: 'monitor.svg', title: '', desc: '', url: '' });
};

const removeItem = (index) => {
    Swal.fire({
        title: 'Hapus Item?',
        text: "Item yang dihapus tidak bisa dikembalikan",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'Ya, Hapus!'
    }).then((result) => {
        if (result.isConfirmed) {
            form.items.splice(index, 1);
        }
    });
};

const openIconModal = (index) => {
    selectedItemIndex.value = index;
    showIconModal.value = true;
};

const selectIcon = (iconFilename) => {
    if (selectedItemIndex.value !== null) {
        form.items[selectedItemIndex.value].icon = iconFilename;
        showIconModal.value = false;
        selectedItemIndex.value = null;
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
    form.post('/admin/program', {
        onSuccess: () => {
             Toast.fire({
                icon: 'success',
                title: 'Berhasil',
                text: 'Data Program & Layanan berhasil disimpan'
            });
        },
        onError: () => {
             Toast.fire({
                icon: 'error',
                title: 'Gagal',
                text: 'Gagal memperbarui section. Periksa inputan anda.'
            });
        },
    });
};
</script>

<template>
    <Head title="Edit Program" />
    
    <AdminLayout>
        <div class="content container-fluid">
            <!-- Radiant Header -->
            <div class="card border-0 rounded-4 mb-4 overflow-hidden" style="background: linear-gradient(135deg, #4361ee 0%, #3a0ca3 100%);">
                <div class="card-body p-4 position-relative">
                    <div class="d-flex align-items-center gap-4 position-relative z-1">
                        <div class="icon-box bg-white bg-opacity-25 rounded-4 p-3 d-flex align-items-center justify-content-center backdrop-blur" style="width: 64px; height: 64px;">
                            <i class="fas fa-th-large fa-2x text-white"></i>
                        </div>
                        <div>
                            <h3 class="fw-bold mb-1 text-white">Program & Layanan</h3>
                            <p class="mb-0 text-white-50">Kelola tampilan section program unggulan dan layanan sekolah</p>
                        </div>
                    </div>
                    <!-- Decorative Circle -->
                    <div class="position-absolute top-0 end-0 p-3 opacity-10 pointer-events-none">
                        <i class="fas fa-th-large fa-10x text-white transform-rotate-12" style="margin-right: -40px; margin-top: -40px;"></i>
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
                                <li class="breadcrumb-item active text-primary fw-bold" aria-current="page">Program</li>
                            </div>

                            <button class="btn btn-primary rounded-pill shadow-sm px-4 fw-bold" @click="submit" :disabled="form.processing">
                                <i class="fas fa-save me-2"></i> {{ form.processing ? 'Menyimpan...' : 'Simpan Perubahan' }}
                            </button>
                        </ol>
                    </nav>
                </div>
            </div>

            <div class="row g-4">
                <!-- Header Section Editor -->
                <div class="col-lg-4">
                    <div class="card border-0 shadow-sm h-100 rounded-4 section-card">
                        <div class="card-header bg-white border-0 py-4 px-4">
                            <h5 class="fw-bold text-dark mb-0"><i class="fas fa-heading text-primary me-2"></i>Header Section</h5>
                        </div>
                        <div class="card-body p-4 pt-0">
                            <div class="mb-3">
                                <label class="form-label fw-bold text-dark">Tagline</label>
                                <div class="input-group shadow-sm rounded-4 overflow-hidden">
                                    <span class="input-group-text bg-light border-0 ps-3 text-primary"><i class="fas fa-tag"></i></span>
                                    <input type="text" class="form-control border-0 bg-light" :class="{'is-invalid': form.errors.tagline}" v-model="form.tagline" placeholder="Program MI">
                                </div>
                                <div v-if="form.errors.tagline" class="invalid-feedback d-block fw-bold mt-1">{{ form.errors.tagline }}</div>
                            </div>
                            
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
                                    @input="updateTitle"
                                    @blur="updateTitle"
                                ></div>
                                <small class="text-muted ms-2 mt-1 d-block"><i class="fas fa-info-circle me-1"></i>Block teks lalu klik "Highlight"</small>
                            </div>
                            
                            <div class="mb-0">
                                <label class="form-label fw-bold text-dark">Deskripsi</label>
                                <div class="input-group shadow-sm rounded-4 overflow-hidden">
                                     <span class="input-group-text bg-light border-0 ps-3 pt-3 align-items-start text-secondary"><i class="fas fa-align-left"></i></span>
                                    <textarea class="form-control border-0 bg-light" :class="{'is-invalid': form.errors.description}" v-model="form.description" rows="5" placeholder="Deskripsi section program..."></textarea>
                                </div>
                                <div v-if="form.errors.description" class="invalid-feedback d-block fw-bold mt-1">{{ form.errors.description }}</div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Program Items Editor -->
                <div class="col-lg-8">
                    <div class="card border-0 shadow-sm rounded-4 section-card">
                        <div class="card-header bg-white border-0 py-4 px-4 d-flex justify-content-between align-items-center">
                            <h5 class="fw-bold text-dark mb-0"><i class="fas fa-list text-primary me-2"></i>Daftar Program ({{ form.items.length }})</h5>
                            <button class="btn btn-success rounded-circle shadow-sm btn-icon" @click="addItem" :disabled="form.items.length >= 10" title="Tambah Program">
                                <i class="fas fa-plus"></i>
                            </button>
                        </div>
                        <div class="card-body p-4 pt-0">
                            <div v-if="form.items.length === 0" class="text-center py-5">
                                <div class="icon-box bg-light rounded-circle mx-auto mb-3 d-flex align-items-center justify-content-center" style="width: 80px; height: 80px;">
                                    <i class="fas fa-inbox fa-3x text-secondary opacity-50"></i>
                                </div>
                                <h6 class="fw-bold text-secondary">Belum ada program</h6>
                                <p class="text-muted small">Klik tombol (+) di atas untuk menambahkan program baru</p>
                            </div>

                            <div v-for="(item, index) in form.items" :key="index" class="card border-0 shadow-sm mb-3 rounded-4 overflow-hidden bg-white hover-shadow transition-all">
                                <div class="card-body p-3">
                                    <div class="d-flex align-items-start gap-3">
                                        <!-- Left Side: Icon -->
                                        <div class="text-center">
                                            <div 
                                                class="icon-preview mb-2 d-flex align-items-center justify-content-center shadow-sm cursor-pointer rounded-4" 
                                                @click="openIconModal(index)"
                                                style="width: 70px; height: 70px; background: linear-gradient(135deg, #FDA31B 0%, #d9880f 100%);"
                                            >
                                                <img :src="`/assets/img/icon/${item.icon}`" alt="icon" style="width: 32px; height: 32px; filter: brightness(0) invert(1);">
                                            </div>
                                            <button class="btn btn-outline-primary btn-sm rounded-pill w-100" style="font-size: 0.7rem;" @click="openIconModal(index)">
                                                Ganti
                                            </button>
                                        </div>

                                        <!-- Right Side: Forms -->
                                        <div class="flex-grow-1">
                                            <div class="row g-2">
                                                <div class="col-md-7">
                                                    <label class="form-label small fw-bold text-dark mb-1">Judul Program</label>
                                                    <div class="input-group input-group-sm shadow-sm rounded-3 overflow-hidden">
                                                        <span class="input-group-text bg-light border-0 ps-3 text-primary"><i class="fas fa-bookmark"></i></span>
                                                        <input type="text" class="form-control border-0 bg-light" :class="{'is-invalid': form.errors[`items.${index}.title`]}" v-model="item.title" placeholder="Contoh: Kurikulum">
                                                    </div>
                                                    <div v-if="form.errors[`items.${index}.title`]" class="invalid-feedback d-block fw-bold mt-1">{{ form.errors[`items.${index}.title`] }}</div>
                                                </div>
                                                <div class="col-md-5">
                                                    <label class="form-label small fw-bold text-dark mb-1">Link URL</label>
                                                    <div class="input-group input-group-sm shadow-sm rounded-3 overflow-hidden">
                                                        <span class="input-group-text bg-light border-0 ps-3 text-info"><i class="fas fa-link"></i></span>
                                                        <input type="text" class="form-control border-0 bg-light" v-model="item.url" placeholder="/program/contoh">
                                                    </div>
                                                </div>
                                                <div class="col-12">
                                                    <label class="form-label small fw-bold text-dark mb-1">Deskripsi Singkat</label>
                                                    <div class="input-group input-group-sm shadow-sm rounded-3 overflow-hidden">
                                                        <span class="input-group-text bg-light border-0 ps-3 pt-2 align-items-start text-secondary"><i class="fas fa-align-left"></i></span>
                                                        <textarea class="form-control border-0 bg-light" :class="{'is-invalid': form.errors[`items.${index}.desc`]}" v-model="item.desc" rows="2" placeholder="Penjelasan singkat program..."></textarea>
                                                    </div>
                                                    <div v-if="form.errors[`items.${index}.desc`]" class="invalid-feedback d-block fw-bold mt-1">{{ form.errors[`items.${index}.desc`] }}</div>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Delete Action -->
                                        <div>
                                            <button class="btn btn-light text-danger btn-sm p-2 rounded-circle shadow-sm hover-danger" @click="removeItem(index)">
                                                <i class="fas fa-trash-alt"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Icon Picker Modal -->
        <div v-if="showIconModal" class="modal-backdrop fade show"></div>
        <div v-if="showIconModal" class="modal fade show d-block" tabindex="-1" @click.self="showIconModal = false">
            <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
                <div class="modal-content border-0 rounded-4 shadow-lg overflow-hidden">
                    <div class="modal-header border-bottom">
                         <h5 class="modal-title fw-bold text-dark"><i class="fas fa-icons me-2 text-primary"></i>Pilih Icon Program</h5>
                        <button type="button" class="btn-close" @click="showIconModal = false"></button>
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
.title-editor {
    min-height: 80px;
    font-size: 1.1rem;
    line-height: 1.5;
}
.title-editor:focus {
    outline: none;
    background-color: #fff !important;
}
.title-editor :deep(span) {
    color: #FDA31B;
    font-weight: 700;
}
.hover-danger:hover {
    background-color: #dc3545 !important;
    color: white !important;
}
.hover-shadow:hover {
    box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15) !important;
    transform: translateY(-3px);
}
.transition-all {
    transition: all 0.3s ease;
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
.btn-icon {
    width: 40px; 
    height: 40px; 
    display: flex; 
    align-items: center; 
    justify-content: center;
}
.section-card {
    transition: transform 0.3s;
}
.section-card:hover {
    transform: translateY(-5px);
}

/* Icon Picker Styles (Matches About.vue EXACTLY) */
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
</style>



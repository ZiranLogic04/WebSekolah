<script setup>

import { Head, Link, useForm, router } from '@inertiajs/vue3';
import AdminLayout from '@/Layouts/AdminLayout.vue';
import Swal from 'sweetalert2';
import { ref, onMounted } from 'vue';

const props = defineProps({
    sliders: Array,
});

const isEditing = ref(false);
const editingId = ref(null);
const imagePreview = ref(null);
const modalTitle = ref('Tambah Slider Baru');
const dragging = ref(false);
const fileInput = ref(null);

const form = useForm({
    image: null,
    image_position: 50,
    top_badge: '',
    title: '',
    subtitle: '',
    button_text: '',
    button_url: '',
    order: 0,
    is_active: true,
});

const resetForm = () => {
    form.reset();
    form.clearErrors(); // Clear validation errors
    form.transform((data) => data); // Reset transform to default
    isEditing.value = false;
    editingId.value = null;
    imagePreview.value = null;
    modalTitle.value = 'Tambah Slider Baru';
    // Reset file input value manually
    const fileInput = document.getElementById('imageInput');
    if (fileInput) fileInput.value = '';
};

const handleImageUpload = (event) => {
    const file = event.target.files[0];
    if (file) {
        form.image = file;
        imagePreview.value = URL.createObjectURL(file);
    }
};

const handleFileDrop = (e) => {
    dragging.value = false;
    const file = e.dataTransfer.files[0];
    if (file && file.type.startsWith('image/')) {
        form.image = file;
        imagePreview.value = URL.createObjectURL(file);
    }
};

const triggerFileInput = () => {
    fileInput.value.click();
};

const editSlider = (slider) => {
    form.clearErrors(); // Clear errors when opening edit
    isEditing.value = true;
    editingId.value = slider.id;
    modalTitle.value = 'Edit Slider';
    
    form.top_badge = slider.top_badge;
    form.image_position = slider.image_position ?? 50;
    form.title = slider.title;
    form.subtitle = slider.subtitle;
    form.button_text = slider.button_text;
    form.button_url = slider.button_url;
    form.order = slider.order;
    form.is_active = Boolean(slider.is_active);
    form.image = null; // Reset image input (only upload if changing)
    
    if (slider.image) {
        imagePreview.value = `/storage/${slider.image}`;
    } else {
        imagePreview.value = null;
    }
    
    // Show modal
    const modalElement = document.getElementById('sliderModal');
    const modal = new bootstrap.Modal(modalElement);
    modal.show();
};

const openAddModal = () => {
    resetForm();
    const modalElement = document.getElementById('sliderModal');
    const modal = new bootstrap.Modal(modalElement);
    modal.show();
};

// Ensure modal reset on close
onMounted(() => {
    const modalElement = document.getElementById('sliderModal');
    if (modalElement) {
        modalElement.addEventListener('hidden.bs.modal', () => {
            resetForm();
        });
    }
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
    if (isEditing.value) {
        // Use post with _method: 'PUT' for file uploads in Laravel/Inertia
        form.transform((data) => ({
            ...data,
            _method: 'PUT',
        })).post(`/admin/slider/${editingId.value}`, {
            onSuccess: () => {
                const modalElement = document.getElementById('sliderModal');
                const modal = bootstrap.Modal.getInstance(modalElement);
                modal.hide();
                Toast.fire({
                    icon: 'success',
                    title: 'Berhasil',
                    text: 'Slider berhasil diperbarui'
                });
                resetForm();
            },
            onError: (errors) => {
                Toast.fire({
                    icon: 'error',
                    title: 'Gagal',
                    text: 'Periksa kembali inputan anda.'
                });
            }
        });
    } else {
        form.post('/admin/slider', {
            forceFormData: true,
            onSuccess: () => {
                const modalElement = document.getElementById('sliderModal');
                const modal = bootstrap.Modal.getInstance(modalElement);
                modal.hide();
                Toast.fire({
                    icon: 'success',
                    title: 'Berhasil',
                    text: 'Slider berhasil ditambahkan'
                });
                resetForm();
            },
            onError: () => {
                Toast.fire({
                    icon: 'error',
                    title: 'Gagal',
                    text: 'Periksa kembali inputan anda.'
                });
            }
        });
    }
};

const deleteSlider = (id) => {
    Swal.fire({
        title: 'Hapus Slider?',
        text: "Data yang dihapus tidak dapat dikembalikan!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'Ya, Hapus!'
    }).then((result) => {
        if (result.isConfirmed) {
            router.delete(`/admin/slider/${id}`, {
                onSuccess: () => {
                    Toast.fire({
                        icon: 'success',
                        title: 'Berhasil',
                        text: 'Slider berhasil dihapus'
                    });
                }
            });
        }
    });
};
</script>

<template>
    <AdminLayout>
        <Head title="Kelola Slider Beranda" />

        <div class="content container-fluid">
            <!-- Page Header -->
            <!-- Radiant Header -->
            <div class="card border-0 rounded-4 mb-4 overflow-hidden" style="background: linear-gradient(135deg, #4361ee 0%, #3a0ca3 100%);">
                <div class="card-body p-4 position-relative">
                    <div class="d-flex align-items-center gap-4 position-relative z-1">
                        <div class="icon-box bg-white bg-opacity-25 rounded-4 p-3 d-flex align-items-center justify-content-center backdrop-blur" style="width: 64px; height: 64px;">
                            <i class="fas fa-images fa-2x text-white"></i>
                        </div>
                        <div>
                            <h3 class="fw-bold mb-1 text-white">Kelola Slider Beranda</h3>
                            <p class="mb-0 text-white-50">Atur gambar slide show banner di halaman utama</p>
                        </div>
                    </div>
                    <!-- Decorative Circle -->
                    <div class="position-absolute top-0 end-0 p-3 opacity-10 pointer-events-none">
                        <i class="fas fa-images fa-10x text-white transform-rotate-12" style="margin-right: -40px; margin-top: -40px;"></i>
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
                                <li class="breadcrumb-item active text-primary fw-bold" aria-current="page">Slider</li>
                            </div>
                            
                             <button class="btn btn-primary rounded-pill shadow-sm px-4 fw-bold" @click="openAddModal">
                                <i class="fas fa-plus me-2"></i> Tambah Slider
                            </button>
                        </ol>
                    </nav>
                </div>
            </div>

            <div class="row">
                <div class="col-md-12">
                    <div class="card border-0 rounded-4 shadow-sm">
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-hover table-center mb-0">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Gambar</th>
                                            <th>Judul & Subjudul</th>
                                            <th>Tombol</th>
                                            <th>Urutan</th>
                                            <th>Status</th>
                                            <th class="text-end">Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr v-for="(slider, index) in sliders" :key="slider.id">
                                            <td>{{ index + 1 }}</td>
                                            <td>
                                                <div class="rounded-3 overflow-hidden shadow-sm" style="width: 120px; height: 60px;">
                                                    <img :src="`/storage/${slider.image}`" alt="Slider Image" class="w-100 h-100 object-fit-cover">
                                                </div>
                                            </td>
                                            <td>
                                                <div v-if="slider.top_badge" class="badge bg-warning text-dark mb-1">{{ slider.top_badge }}</div>
                                                <div class="fw-bold">{{ slider.title || '-' }} <span class="text-muted fw-normal" v-if="!slider.title"></span></div>
                                                <div class="text-muted small">{{ slider.subtitle || '-' }}</div>
                                            </td>
                                            <td>
                                                <div v-if="slider.button_text || slider.button_url">
                                                    <span v-if="slider.button_text" class="badge bg-light text-dark border mb-1 d-block w-auto">
                                                        <i class="fas fa-mouse-pointer me-1 text-muted"></i> {{ slider.button_text }}
                                                    </span>
                                                    <div v-if="slider.button_url" class="small text-muted text-truncate" style="max-width: 150px;">
                                                        <i class="fas fa-link me-1"></i> {{ slider.button_url }}
                                                    </div>
                                                </div>
                                                <span v-else class="text-muted small">Tidak ada tombol</span>
                                            </td>
                                            <td><span class="badge bg-light text-dark border">{{ slider.order }}</span></td>
                                            <td>
                                                <span v-if="slider.is_active" class="badge bg-success">Aktif</span>
                                                <span v-else class="badge bg-danger">Nonaktif</span>
                                            </td>
                                            <td class="text-end">
                                                <button class="btn btn-sm btn-primary rounded-circle shadow-sm me-2" style="width: 32px; height: 32px; padding: 0;" @click="editSlider(slider)">
                                                    <i class="fas fa-edit"></i>
                                                </button>
                                                <button class="btn btn-sm btn-danger rounded-circle shadow-sm" style="width: 32px; height: 32px; padding: 0;" @click="deleteSlider(slider.id)">
                                                    <i class="feather-trash-2"></i>
                                                </button>
                                            </td>
                                        </tr>
                                        <tr v-if="sliders.length === 0">
                                            <td colspan="7" class="text-center py-4">
                                                <div class="mb-3">
                                                    <i class="fas fa-images text-muted" style="font-size: 3rem; opacity: 0.5;"></i>
                                                </div>
                                                <p class="text-muted mt-2">Belum ada slider yang ditambahkan.</p>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal Slider -->
        <div class="modal fade" id="sliderModal" tabindex="-1" aria-hidden="true" data-bs-backdrop="static">
            <div class="modal-dialog modal-lg modal-dialog-centered">
                <div class="modal-content border-0 rounded-4 shadow-lg overflow-hidden">
                    <div class="modal-header border-0 p-4 text-white" style="background: linear-gradient(135deg, #4361ee 0%, #3a0ca3 100%);">
                        <div class="d-flex align-items-center gap-3">
                            <div class="bg-white bg-opacity-25 rounded-circle p-2 d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                                <i class="fas fa-edit text-white"></i>
                            </div>
                            <h5 class="modal-title fw-bold mb-0 text-white">{{ modalTitle }}</h5>
                        </div>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <form @submit.prevent="submit">
                        <div class="modal-body p-4">
                            <div class="row">
                                <!-- Gambar -->
                                <div class="col-12 mb-4">
                                    <label class="form-label fw-bold text-dark mb-2">Gambar Slider <span class="text-danger">*</span></label>
                                    
                                    <!-- Upload Area -->
                                    <div
                                        class="upload-area text-center p-4 border-2 rounded-4 position-relative overflow-hidden bg-white"
                                        :class="{ 'border-primary bg-soft-primary': dragging, 'border-dashed border-secondary-subtle': !dragging, 'is-invalid border-danger': form.errors.image }"
                                        style="border-style: dashed !important; cursor: pointer; transition: all 0.3s ease;"
                                        @dragover.prevent="dragging = true"
                                        @dragleave.prevent="dragging = false"
                                        @drop.prevent="handleFileDrop"
                                        @click="triggerFileInput"
                                    >
                                        <input
                                            type="file"
                                            class="d-none"
                                            ref="fileInput"
                                            accept="image/*"
                                            @change="handleImageUpload"
                                        />
                                        
                                        <div v-if="!imagePreview" class="py-3">
                                            <div class="icon-box bg-light rounded-circle shadow-sm mx-auto mb-3 d-flex align-items-center justify-content-center" style="width: 60px; height: 60px;">
                                                <i class="fas fa-cloud-upload-alt fa-2x text-primary"></i>
                                            </div>
                                            <h6 class="fw-bold text-dark mb-1">Klik untuk Upload</h6>
                                            <p class="text-muted small mb-0">atau drag & drop file gambar di sini</p>
                                        </div>
                                        
                                        <div v-else class="position-relative">
                                            <img :src="imagePreview" alt="Preview" class="img-fluid rounded-3 shadow-sm" style="max-height: 200px; object-fit: cover;">
                                            <div class="position-absolute top-0 end-0 p-2">
                                                <button type="button" class="btn btn-sm btn-danger rounded-circle shadow-sm" @click.stop="imagePreview = null; form.image = null">
                                                    <i class="fas fa-times"></i>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div v-if="form.errors.image" class="invalid-feedback d-block fw-bold mt-2"><i class="fas fa-exclamation-circle me-1"></i> {{ form.errors.image }}</div>
                                </div>

                                <!-- Badge -->
                                <div class="col-12 mb-3">
                                    <label class="form-label fw-bold text-dark">Teks Badge (Kuning)</label>
                                    <div class="input-group shadow-sm rounded-4 overflow-hidden">
                                        <span class="input-group-text bg-warning text-dark border-0 px-3"><i class="fas fa-tag"></i></span>
                                        <input type="text" class="form-control border-0 form-control-lg bg-light" :class="{'is-invalid': form.errors.top_badge}" v-model="form.top_badge" placeholder="Contoh: Program Unggulan">
                                    </div>
                                    <div v-if="form.errors.top_badge" class="invalid-feedback d-block">{{ form.errors.top_badge }}</div>
                                    <small class="text-muted ms-1">Badge kecil di atas judul (Opsional)</small>
                                </div>
                                
                                <!-- Judul -->
                                <div class="col-12 mb-3">
                                    <label class="form-label fw-bold text-dark">Judul Utama <span class="text-danger">*</span></label>
                                    <div class="input-group shadow-sm rounded-4 overflow-hidden">
                                        <span class="input-group-text bg-primary text-white border-0 px-3"><i class="fas fa-heading"></i></span>
                                        <input type="text" class="form-control border-0 form-control-lg bg-light fw-bold" :class="{'is-invalid': form.errors.title}" v-model="form.title" placeholder="Contoh: Selamat Datang">
                                    </div>
                                    <div v-if="form.errors.title" class="invalid-feedback d-block">{{ form.errors.title }}</div>
                                </div>
                                
                                <!-- Subtitle -->
                                <div class="col-12 mb-3">
                                    <label class="form-label fw-bold text-dark">Deskripsi Singkat <span class="text-danger">*</span></label>
                                    <div class="input-group shadow-sm rounded-4 overflow-hidden">
                                        <span class="input-group-text bg-secondary text-white border-0 px-3"><i class="fas fa-align-left"></i></span>
                                        <textarea class="form-control border-0 bg-light" :class="{'is-invalid': form.errors.subtitle}" v-model="form.subtitle" rows="2" placeholder="Deskripsi singkat slider..."></textarea>
                                    </div>
                                    <div v-if="form.errors.subtitle" class="invalid-feedback d-block">{{ form.errors.subtitle }}</div>
                                </div>
                                
                                <!-- Tombol & URL -->
                                <div class="col-md-6 mb-3">
                                    <label class="form-label fw-bold text-dark">Teks Tombol <small class="text-muted fw-normal">(Opsional)</small></label>
                                    <div class="input-group shadow-sm rounded-4 overflow-hidden">
                                        <span class="input-group-text bg-info text-white border-0 px-3"><i class="fas fa-mouse-pointer"></i></span>
                                        <input type="text" class="form-control border-0 bg-light" :class="{'is-invalid': form.errors.button_text}" v-model="form.button_text" placeholder="Contoh: Daftar Sekarang">
                                    </div>
                                    <div v-if="form.errors.button_text" class="invalid-feedback d-block">{{ form.errors.button_text }}</div>
                                </div>
                                
                                <div class="col-md-6 mb-3">
                                    <label class="form-label fw-bold text-dark">URL Tombol <small class="text-muted fw-normal">(Opsional)</small></label>
                                    <div class="input-group shadow-sm rounded-4 overflow-hidden">
                                        <span class="input-group-text bg-info text-white border-0 px-3"><i class="fas fa-link"></i></span>
                                        <input type="text" class="form-control border-0 bg-light" :class="{'is-invalid': form.errors.button_url}" v-model="form.button_url" placeholder="Contoh: /ppdb">
                                    </div>
                                    <div v-if="form.errors.button_url" class="invalid-feedback d-block">{{ form.errors.button_url }}</div>
                                </div>
                                
                                <!-- Urutan & Status -->
                                <div class="col-md-6 mb-3">
                                    <label class="form-label fw-bold text-dark">Urutan Tampil</label>
                                    <div class="input-group shadow-sm rounded-4 overflow-hidden">
                                        <span class="input-group-text bg-dark text-white border-0 px-3"><i class="fas fa-sort-numeric-up"></i></span>
                                        <input type="number" class="form-control border-0 bg-light" :class="{'is-invalid': form.errors.order}" v-model="form.order" min="0" placeholder="0">
                                    </div>
                                    <div v-if="form.errors.order" class="invalid-feedback d-block">{{ form.errors.order }}</div>
                                </div>
                                
                                <div class="col-md-6 mb-3">
                                    <label class="form-label fw-bold text-dark">Status</label>
                                    <div class="p-2 border rounded-4 bg-light shadow-sm d-flex align-items-center justify-content-between px-3" style="min-height: 48px;">
                                        <span class="fw-bold" :class="form.is_active ? 'text-success' : 'text-danger'">
                                            {{ form.is_active ? 'Aktif' : 'Nonaktif' }}
                                        </span>
                                        <div class="form-check form-switch mb-0">
                                            <input class="form-check-input" type="checkbox" v-model="form.is_active" id="statusSwitch" style="width: 3em; height: 1.5em; cursor: pointer;">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer border-top-0 p-4 bg-light">
                            <button type="button" class="btn btn-light rounded-pill px-4" data-bs-dismiss="modal">Batal</button>
                            <button type="submit" class="btn btn-primary rounded-pill px-4 shadow-sm" :disabled="form.processing">
                                <i class="fas fa-save me-2"></i> {{ form.processing ? 'Menyimpan...' : 'Simpan' }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </AdminLayout>
</template>

<style scoped>
.backdrop-blur {
    backdrop-filter: blur(8px);
    -webkit-backdrop-filter: blur(8px);
}
.transform-rotate-12 {
    transform: rotate(12deg);
}
</style>


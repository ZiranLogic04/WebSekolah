<script setup>
import { ref } from "vue";
import { Head, useForm, router, Link } from "@inertiajs/vue3";
import AdminLayout from "@/Layouts/AdminLayout.vue";
import { showSuccess, showError } from "@/Utils/notifications";
import { confirmDelete } from "@/Utils/swal";

const props = defineProps({
    galleries: Object,
    categories: Array,
    filters: Object,
});

const searchQuery = ref(props.filters.search || "");
const selectedCategory = ref(props.filters.category || "");
const showUploadModal = ref(false);
const showEditModal = ref(false);
const dragging = ref(false);
const fileInput = ref(null);
const perPage = ref(10); 

const form = useForm({
    title: "",
    image: null,
    category: "",
    description: "",
    is_active: true,
});

const editForm = useForm({
    id: null,
    title: "",
    image: null,
    category: "",
    description: "",
    is_active: true,
    _method: "POST", 
});

const handleSearch = () => {
    router.get(
        '/admin/gallery',
        { search: searchQuery.value, category: selectedCategory.value },
        { preserveState: true, preserveScroll: true }
    );
};

const triggerFileInput = () => {
    fileInput.value.click();
};

const handleFileDrop = (e) => {
    dragging.value = false;
    const file = e.dataTransfer.files[0];
    if (file && file.type.startsWith("image/")) {
        form.image = file;
    }
};

const handleFileSelect = (e) => {
    const file = e.target.files[0];
    if (file) {
        form.image = file;
    }
};

const handleEditFileSelect = (e) => {
    const file = e.target.files[0];
    if (file) {
        editForm.image = file;
    }
};

const uploadImage = () => {
    form.post('/admin/gallery', {
        onSuccess: () => {
            showUploadModal.value = false;
            form.reset();
            showSuccess('Berhasil', 'Gambar berhasil diunggah');
        },
        onError: () => {
            showError('Gagal', 'Gagal mengunggah gambar');
        },
    });
};

const editGallery = (item) => {
    editForm.id = item.id;
    editForm.title = item.title;
    editForm.category = item.category;
    editForm.description = item.description;
    editForm.is_active = !!item.is_active; // Ensure boolean
    editForm.image = null; // Reset image input
    showEditModal.value = true;
};

const updateGallery = () => {
    editForm.post(`/admin/gallery/${editForm.id}`, {
        onSuccess: () => {
            showEditModal.value = false;
            editForm.reset();
            showSuccess('Berhasil', 'Gambar berhasil diperbarui');
        },
        onError: (err) => {
            console.error(err);
            showError('Gagal', 'Gagal memperbarui gambar');
        },
    });
};

const deleteGallery = (id) => {
    confirmDelete("Hapus Gambar?", "Gambar yang dihapus tidak dapat dikembalikan!").then((result) => {
        if (result.isConfirmed) {
            router.delete(`/admin/gallery/${id}`, {
                onSuccess: () => {
                    showSuccess('Berhasil', 'Gambar berhasil dihapus');
                },
                onError: () => {
                    showError('Gagal', 'Gagal menghapus gambar');
                }
            });
        }
    });
};
</script>

<template>
    <Head title="Manajemen Gallery" />

    <AdminLayout>
        <div class="container-fluid">
            <!-- Radiant Header -->
            <div class="card border-0 shadow-lg rounded-4 mb-4 overflow-hidden position-relative bg-primary">
                <div class="card-body p-4 p-lg-5 position-relative z-1">
                    <div class="row align-items-center">
                        <div class="col-lg-8">
                            <div class="d-flex align-items-center gap-3 mb-2">
                                <div class="icon-box bg-white rounded-3 text-primary p-2">
                                    <i class="fas fa-images fs-3"></i>
                                </div>
                                <h2 class="fw-bold text-white mb-0">Manajemen Gallery</h2>
                            </div>
                            <p class="text-white-50 mb-0 fs-5">Kelola koleksi foto dan gambar sekolah.</p>
                        </div>
                        <div class="col-lg-4 text-lg-end mt-3 mt-lg-0">
                            <button @click="showUploadModal = true" class="btn btn-light btn-lg rounded-pill shadow-sm px-4 fw-bold text-primary hover-elevate">
                                <i class="fas fa-plus me-2"></i>Upload Gambar
                            </button>
                        </div>
                    </div>
                </div>
                <!-- Decorative Background -->
                <div class="position-absolute bottom-0 end-0 opacity-10 me-n5 mb-n5">
                    <i class="fas fa-camera" style="font-size: 10rem; color: white;"></i>
                </div>
            </div>

            <!-- Filters Card -->
            <div class="card border-0 shadow-sm rounded-4 mb-4">
                <div class="card-body p-4">
                    <div class="row g-3 align-items-end">
                        <div class="col-md-5">
                            <label class="form-label fw-bold text-muted small">Cari Gambar</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-end-0 rounded-start-pill ps-3"><i class="fas fa-search text-muted"></i></span>
                                <input type="text" class="form-control bg-light border-start-0 rounded-end-pill" placeholder="Cari judul gambar..." v-model="searchQuery" @keyup.enter="handleSearch">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-bold text-muted small">Kategori</label>
                            <select class="form-select rounded-pill bg-light border-0" v-model="selectedCategory" @change="handleSearch">
                                <option value="">Semua Kategori</option>
                                <option v-for="cat in categories" :key="cat" :value="cat">{{ cat }}</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <button class="btn btn-primary w-100 rounded-pill" @click="handleSearch">
                                <i class="fas fa-filter me-2"></i>Filter
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Gallery Grid -->
            <div class="row g-4">
                <div v-for="item in galleries.data" :key="item.id" class="col-lg-3 col-md-4 col-sm-6">
                    <div class="card h-100 border-0 shadow-sm rounded-4 gallery-card overflow-hidden">
                        <div class="position-relative">
                            <img :src="'/storage/' + item.image" class="card-img-top gallery-img" :alt="item.title">
                            <div class="gallery-overlay d-flex align-items-end p-3">
                                <span class="badge bg-primary rounded-pill px-3 py-2" v-if="item.category">
                                    <i class="fas fa-folder me-1"></i>{{ item.category }}
                                </span>
                            </div>
                            <!-- Status Badge -->
                            <div class="position-absolute top-0 end-0 p-2">
                                <span class="badge rounded-pill" :class="item.is_active ? 'bg-success' : 'bg-secondary'">
                                    {{ item.is_active ? 'Aktif' : 'Draft' }}
                                </span>
                            </div>
                        </div>
                        <div class="card-body p-3">
                            <h6 class="card-title fw-bold text-truncate mb-1" :title="item.title">{{ item.title }}</h6>
                            <p class="card-text small text-muted text-truncate mb-3" v-if="item.description">{{ item.description }}</p>
                            <div class="d-flex gap-2">
                                <button class="btn btn-sm btn-outline-primary rounded-pill flex-fill" @click="editGallery(item)">
                                    <i class="fas fa-edit me-1"></i>Edit
                                </button>
                                <button class="btn btn-sm btn-outline-danger rounded-pill" @click="deleteGallery(item.id)">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Empty State -->
            <div v-if="galleries.data.length === 0" class="text-center py-5">
                <div class="text-muted">
                    <i class="fas fa-images fa-3x mb-3"></i>
                    <p>Belum ada gambar di gallery.</p>
                </div>
            </div>

            <!-- Pagination (Modern) -->
            <div class="p-3 border-top mt-4">
                <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
                    <div class="d-flex align-items-center gap-3">
                        <div class="d-flex align-items-center gap-2">
                            <span class="text-muted small">Tampilkan</span>
                            <select class="form-select form-select-sm" style="width: 70px;" v-model="perPage" disabled title="Fitur ini akan segera tersedia">
                                <option :value="10">10</option>
                                <option :value="25">25</option>
                                <option :value="50">50</option>
                            </select>
                            <span class="text-muted small">data</span>
                        </div>
                        <small class="text-muted" v-if="galleries.total">
                            Menampilkan {{ galleries.from || 0 }} - {{ galleries.to || 0 }} dari {{ galleries.total || 0 }}
                        </small>
                    </div>
                    
                    <div v-if="galleries.links && galleries.links.length > 3">
                        <nav>
                             <ul class="pagination pagination-sm mb-0">
                                <li class="page-item" :class="{ disabled: !galleries.prev_page_url }">
                                    <Link class="page-link" :href="galleries.prev_page_url || '#'" preserve-state preserve-scroll>
                                        <i class="fas fa-chevron-left"></i>
                                    </Link>
                                </li>
                                <template v-for="(link, index) in galleries.links.slice(1, -1)" :key="index">
                                    <li class="page-item" :class="{ active: link.active, disabled: !link.url }">
                                        <Link class="page-link" :href="link.url || '#'" v-html="link.label" preserve-state preserve-scroll></Link>
                                    </li>
                                </template>
                                <li class="page-item" :class="{ disabled: !galleries.next_page_url }">
                                    <Link class="page-link" :href="galleries.next_page_url || '#'" preserve-state preserve-scroll>
                                        <i class="fas fa-chevron-right"></i>
                                    </Link>
                                </li>
                            </ul>
                        </nav>
                    </div>
                </div>
            </div>
        </div>

        <!-- Upload Modal -->
        <div v-if="showUploadModal" class="modal-backdrop fade show" style="z-index: 1050;"></div>
        <div v-if="showUploadModal" class="modal fade show d-block" tabindex="-1" style="z-index: 1055;">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content border-0 shadow-lg rounded-4">
                    <div class="modal-header bg-primary text-white rounded-top-4">
                        <div class="d-flex align-items-center gap-3">
                            <div class="icon-box bg-white rounded-3 text-primary p-2">
                                <i class="fas fa-cloud-upload-alt"></i>
                            </div>
                            <div>
                                <h5 class="modal-title fw-bold text-white mb-0">Upload Gambar Baru</h5>
                                <small class="text-white opacity-75">Tambah foto ke koleksi gallery</small>
                            </div>
                        </div>
                        <button type="button" class="btn-close btn-close-white" @click="showUploadModal = false"></button>
                    </div>
                    <div class="modal-body">
                        <form @submit.prevent="uploadImage">
                            <div class="mb-3">
                                <label class="form-label">Judul Gambar</label>
                                <input
                                    type="text"
                                    class="form-control"
                                    v-model="form.title"
                                    :class="{ 'is-invalid': form.errors.title }"
                                />
                                <div v-if="form.errors.title" class="invalid-feedback">
                                    {{ form.errors.title }}
                                </div>
                            </div>
                            
                            <div class="mb-3">
                                <label class="form-label">File Gambar</label>
                                <div
                                    class="upload-area text-center p-4 border rounded dashed"
                                    :class="{ 'bg-light': dragging }"
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
                                        @change="handleFileSelect"
                                    />
                                    <div v-if="!form.image">
                                        <i class="fas fa-cloud-upload-alt fa-2x text-muted mb-2"></i>
                                        <p class="text-muted mb-0">Klik atau drag gambar ke sini</p>
                                    </div>
                                    <div v-else>
                                        <p class="text-success mb-0">
                                            <i class="fas fa-check-circle me-1"></i>
                                            {{ form.image.name }}
                                        </p>
                                    </div>
                                </div>
                                <div v-if="form.errors.image" class="text-danger small mt-1">
                                    <i class="fas fa-exclamation-circle me-1"></i>
                                    {{ form.errors.image }}
                                </div>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Kategori</label>
                                 <input
                                    type="text"
                                    class="form-control"
                                    v-model="form.category"
                                    list="categoryList"
                                    placeholder="Contoh: Kegiatan, Fasilitas, dll (Ketik baru atau pilih)"
                                />
                                <datalist id="categoryList">
                                    <option v-for="cat in categories" :key="cat" :value="cat"></option>
                                    <option value="Kegiatan"></option>
                                    <option value="Fasilitas"></option>
                                    <option value="Prestasi"></option>
                                    <option value="Gedung"></option>
                                </datalist>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Deskripsi (Opsional)</label>
                                <textarea
                                    class="form-control"
                                    rows="3"
                                    v-model="form.description"
                                ></textarea>
                            </div>

                            <div class="d-flex justify-content-end gap-2">
                                <button
                                    type="button"
                                    class="btn btn-secondary"
                                    @click="showUploadModal = false"
                                >
                                    Batal
                                </button>
                                <button
                                    type="submit"
                                    class="btn btn-primary"
                                    :disabled="form.processing"
                                >
                                    <span
                                        v-if="form.processing"
                                        class="spinner-border spinner-border-sm me-1"
                                    ></span>
                                    Upload
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- Edit Modal -->
        <div v-if="showEditModal" class="modal-backdrop fade show" style="z-index: 1050;"></div>
        <div v-if="showEditModal" class="modal fade show d-block" tabindex="-1" style="z-index: 1055;">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content border-0 shadow-lg rounded-4">
                    <div class="modal-header bg-primary text-white rounded-top-4">
                        <div class="d-flex align-items-center gap-3">
                            <div class="icon-box bg-white rounded-3 text-primary p-2">
                                <i class="fas fa-edit"></i>
                            </div>
                            <div>
                                <h5 class="modal-title fw-bold text-white mb-0">Edit Gallery</h5>
                                <small class="text-white opacity-75">Perbarui informasi gambar</small>
                            </div>
                        </div>
                        <button type="button" class="btn-close btn-close-white" @click="showEditModal = false"></button>
                    </div>
                    <div class="modal-body">
                         <form @submit.prevent="updateGallery">
                            <div class="mb-3">
                                <label class="form-label">Judul Gambar</label>
                                <input
                                    type="text"
                                    class="form-control"
                                    v-model="editForm.title"
                                    :class="{ 'is-invalid': editForm.errors.title }"
                                />
                                <div v-if="editForm.errors.title" class="invalid-feedback">
                                    {{ editForm.errors.title }}
                                </div>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Ganti Gambar (Opsional)</label>
                                <input
                                    type="file"
                                    class="form-control"
                                    accept="image/*"
                                    @change="handleEditFileSelect"
                                />
                                <div v-if="editForm.errors.image" class="text-danger small mt-1">
                                    <i class="fas fa-exclamation-circle me-1"></i>
                                    {{ editForm.errors.image }}
                                </div>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Kategori</label>
                                <input
                                    type="text"
                                    class="form-control"
                                    v-model="editForm.category"
                                    list="editCategoryList"
                                />
                                <datalist id="editCategoryList">
                                    <option v-for="cat in categories" :key="cat" :value="cat"></option>
                                    <option value="Kegiatan"></option>
                                    <option value="Fasilitas"></option>
                                    <option value="Prestasi"></option>
                                </datalist>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Deskripsi</label>
                                <textarea
                                    class="form-control"
                                    rows="3"
                                    v-model="editForm.description"
                                ></textarea>
                            </div>
                            
                            <div class="mb-3">
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" id="isActiveSwitch" v-model="editForm.is_active">
                                    <label class="form-check-label" for="isActiveSwitch">Tampilkan di Publik</label>
                                </div>
                            </div>

                            <div class="d-flex justify-content-end gap-2">
                                <button
                                    type="button"
                                    class="btn btn-secondary"
                                    @click="showEditModal = false"
                                >
                                    Batal
                                </button>
                                <button
                                    type="submit"
                                    class="btn btn-primary"
                                    :disabled="editForm.processing"
                                >
                                    <span
                                        v-if="editForm.processing"
                                        class="spinner-border spinner-border-sm me-1"
                                    ></span>
                                    Simpan Perubahan
                                </button>
                            </div>
                         </form>
                    </div>
                </div>
            </div>
        </div>

    </AdminLayout>
</template>

<style scoped>
/* Icon Box */
.icon-box {
    width: 48px;
    height: 48px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.25rem;
}

/* Hover Elevate Effect */
.hover-elevate {
    transition: transform 0.2s ease, box-shadow 0.2s ease;
}
.hover-elevate:hover {
    transform: translateY(-3px);
    box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05) !important;
}

/* Gallery Card */
.gallery-card {
    transition: all 0.3s ease;
}
.gallery-card:hover {
    transform: translateY(-8px);
    box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04) !important;
}

/* Gallery Image */
.gallery-img {
    height: 200px;
    object-fit: cover;
    width: 100%;
    transition: transform 0.3s ease;
}
.gallery-card:hover .gallery-img {
    transform: scale(1.05);
}

/* Gallery Overlay */
.gallery-overlay {
    position: absolute;
    bottom: 0;
    left: 0;
    right: 0;
    background: linear-gradient(transparent, rgba(0,0,0,0.7));
    opacity: 0;
    transition: opacity 0.3s ease;
}
.gallery-card:hover .gallery-overlay {
    opacity: 1;
}

/* Upload Area */
.dashed {
    border: 2px dashed #dee2e6 !important;
    border-radius: 1rem !important;
    cursor: pointer;
    transition: all 0.2s ease;
    background-color: #f8f9fa;
}
.dashed:hover {
    border-color: #3d5ee1 !important;
    background-color: #eef2ff;
}

/* Form Controls */
.form-control, .form-select {
    border-radius: 0.5rem;
}
</style>

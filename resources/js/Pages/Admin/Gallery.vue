<script setup>
import { ref } from "vue";
import { Head, useForm, router, Link } from "@inertiajs/vue3";
import AdminLayout from "@/Layouts/AdminLayout.vue";
import Swal from "sweetalert2";

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
const perPage = ref(10); // Added for UI consistency

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
    _method: "POST", // Will be handled as POST with _method PUT/POST logic
});

const handleSearch = () => {
    router.get(
        route("admin.gallery.index"),
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
    form.post(route("admin.gallery.store"), {
        onSuccess: () => {
            showUploadModal.value = false;
            form.reset();
            Swal.fire("Sukses", "Gambar berhasil diupload", "success");
        },
        onError: () => {
            Swal.fire("Gagal", "Terjadi kesalahan saat upload", "error");
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
    editForm.post(route("admin.gallery.update", editForm.id), {
        onSuccess: () => {
            showEditModal.value = false;
            editForm.reset();
            Swal.fire("Sukses", "Gambar berhasil diupdate", "success");
        },
        onError: (err) => {
            console.error(err);
            Swal.fire("Gagal", "Terjadi kesalahan saat update", "error");
        },
    });
};

const deleteGallery = (id) => {
    Swal.fire({
        title: "Apakah anda yakin?",
        text: "Gambar yang dihapus tidak dapat dikembalikan!",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#d33",
        cancelButtonColor: "#3085d6",
        confirmButtonText: "Ya, Hapus!",
        cancelButtonText: "Batal",
    }).then((result) => {
        if (result.isConfirmed) {
            router.delete(route("admin.gallery.destroy", id), {
                onSuccess: () => {
                    Swal.fire("Terhapus!", "Gambar berhasil dihapus.", "success");
                },
            });
        }
    });
};
</script>

<template>
    <Head title="Manajemen Gallery" />

    <AdminLayout>
        <div class="container-fluid">
            <!-- Header -->
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2 class="h4 mb-0 text-gray-800">Manajemen Gallery</h2>
                <button
                    @click="showUploadModal = true"
                    class="btn btn-primary btn-sm"
                >
                    <i class="fas fa-plus me-2"></i>Upload Gambar
                </button>
            </div>

            <!-- Filters -->
            <div class="card shadow mb-4">
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-4">
                            <input
                                type="text"
                                class="form-control"
                                placeholder="Cari judul..."
                                v-model="searchQuery"
                                @keyup.enter="handleSearch"
                            />
                        </div>
                        <div class="col-md-3">
                            <select
                                class="form-select"
                                v-model="selectedCategory"
                                @change="handleSearch"
                            >
                                <option value="">Semua Kategori</option>
                                <option
                                    v-for="cat in categories"
                                    :key="cat"
                                    :value="cat"
                                >
                                    {{ cat }}
                                </option>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <button
                                class="btn btn-secondary w-100"
                                @click="handleSearch"
                            >
                                Cari
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Gallery Grid -->
            <div class="row">
                <div
                    v-for="item in galleries.data"
                    :key="item.id"
                    class="col-md-3 col-sm-6 mb-4"
                >
                    <div class="card h-100 shadow-sm gallery-card">
                        <div class="position-relative overflow-hidden">
                            <img
                                :src="'/storage/' + item.image"
                                class="card-img-top gallery-img"
                                :alt="item.title"
                            />
                            <div class="gallery-overlay">
                                <span
                                    class="badge bg-primary mb-2"
                                    v-if="item.category"
                                >
                                    {{ item.category }}
                                </span>
                            </div>
                        </div>
                        <div class="card-body">
                            <h6 class="card-title text-truncate" :title="item.title">
                                {{ item.title }}
                            </h6>
                            <p
                                class="card-text small text-muted text-truncate"
                                v-if="item.description"
                            >
                                {{ item.description }}
                            </p>
                            <div class="d-flex justify-content-between mt-3">
                                <button
                                    class="btn btn-sm btn-outline-info"
                                    @click="editGallery(item)"
                                >
                                    <i class="fas fa-edit"></i>
                                </button>
                                <button
                                    class="btn btn-sm btn-outline-danger"
                                    @click="deleteGallery(item.id)"
                                >
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
        <div v-if="showUploadModal" class="modal-backdrop fade show"></div>
        <div
            v-if="showUploadModal"
            class="modal fade show d-block"
            tabindex="-1"
        >
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Upload Gambar Baru</h5>
                        <button
                            type="button"
                            class="btn-close"
                            @click="showUploadModal = false"
                        ></button>
                    </div>
                    <div class="modal-body">
                        <form @submit.prevent="uploadImage">
                            <div class="mb-3">
                                <label class="form-label">Judul Gambar</label>
                                <input
                                    type="text"
                                    class="form-control"
                                    v-model="form.title"
                                    required
                                />
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
        <div v-if="showEditModal" class="modal-backdrop fade show"></div>
        <div v-if="showEditModal" class="modal fade show d-block" tabindex="-1">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Edit Gallery</h5>
                        <button
                            type="button"
                            class="btn-close"
                            @click="showEditModal = false"
                        ></button>
                    </div>
                    <div class="modal-body">
                         <form @submit.prevent="updateGallery">
                            <div class="mb-3">
                                <label class="form-label">Judul Gambar</label>
                                <input
                                    type="text"
                                    class="form-control"
                                    v-model="editForm.title"
                                    required
                                />
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Ganti Gambar (Opsional)</label>
                                <input
                                    type="file"
                                    class="form-control"
                                    accept="image/*"
                                    @change="handleEditFileSelect"
                                />
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
.gallery-card {
    transition: transform 0.2s;
}
.gallery-card:hover {
    transform: translateY(-5px);
}
.gallery-img {
    height: 180px;
    object-fit: cover;
    width: 100%;
}
.dashed {
    border-style: dashed !important;
    border-width: 2px !important;
    cursor: pointer;
}
</style>

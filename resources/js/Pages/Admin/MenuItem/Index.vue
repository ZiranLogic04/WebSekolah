<template>
    <AdminLayout>
        <div class="content container-fluid">
            <!-- Breadcrumb -->
            <nav aria-label="breadcrumb" class="mb-4">
                <ol class="breadcrumb alert alert-light border-0 rounded-4 shadow-sm py-3 px-4 mb-0 align-items-center">
                    <li class="breadcrumb-item"><Link href="/admin/dashboard" class="text-decoration-none fw-bold"><i class="fas fa-home me-1"></i> Dashboard</Link></li>
                    <li class="breadcrumb-item text-muted fw-bold">Halaman</li>
                    <li class="breadcrumb-item" v-if="level === 3">
                        <Link :href="`/admin/halaman/${menu.id}/items`" class="text-decoration-none fw-bold">{{ menu.nama }}</Link>
                    </li>
                    <li class="breadcrumb-item active text-primary fw-bold" aria-current="page">{{ level === 2 ? menu.nama : parent.nama }}</li>
                </ol>
            </nav>

            <!-- Radiant Header Card -->
            <div class="card border-0 shadow-lg rounded-4 mb-4 overflow-hidden position-relative" style="background: linear-gradient(135deg, #4361ee 0%, #3a0ca3 100%);">
                <div class="card-body p-4 p-lg-5 position-relative z-1">
                    <div class="row align-items-center">
                        <div class="col-lg-8">
                            <div class="d-flex align-items-center gap-3 mb-2">
                                <div class="icon-box bg-white bg-opacity-25 rounded-3 text-white p-2 backdrop-blur">
                                    <i class="fas fa-sitemap fs-3"></i>
                                </div>
                                <h2 class="fw-bold text-white mb-0 ls-tight">{{ level === 2 ? menu.nama : parent.nama }}</h2>
                            </div>
                            <p class="text-white-50 mb-0 fs-5">Kelola item menu dan halaman untuk bagian ini.</p>
                        </div>
                    </div>
                </div>
                <!-- Decorative Background -->
                <div class="position-absolute bottom-0 end-0 opacity-10 me-n5 mb-n5">
                    <i class="fas fa-sitemap" style="font-size: 10rem; color: white;"></i>
                </div>
            </div>

             <!-- Card Grid View -->
            <div class="row">
                <!-- Add New Card -->
                <div class="col-12 col-md-6 col-lg-4 mb-4" @click="showAddModal" style="cursor: pointer;">
                    <div class="card h-100 border-2 border-dashed border-primary bg-light hover-shadow transition-all d-flex align-items-center justify-content-center text-center p-4">
                        <div class="text-primary">
                            <div class="mb-3">
                                <i class="fas fa-plus-circle fa-3x"></i>
                            </div>
                            <h5 class="fw-bold">Tambah Halaman Baru</h5>
                            <p class="text-muted small mb-0">Klik untuk menambahkan halaman atau link</p>
                        </div>
                    </div>
                </div>

                <!-- Item Cards -->
                <div v-for="item in items" :key="item.id" class="col-12 col-md-6 col-lg-4 mb-4">
                    <div class="card h-100 border-0 shadow-sm hover-up transition-all">
                        <div class="card-body d-flex flex-column">
                            <div class="d-flex justify-content-between align-items-start mb-3">
                                <span class="badge rounded-pill" :class="getBadgeClass(item.type)">
                                    <i :class="getTypeIcon(item.type)" class="me-1"></i> {{ getTypeName(item.type) }}
                                </span>
                                <div class="form-check form-switch m-0 pt-1">
                                    <input 
                                        class="form-check-input" 
                                        type="checkbox" 
                                        :checked="item.is_active"
                                        @change="toggleStatus(item)"
                                        title="Aktifkan/Nonaktifkan"
                                    >
                                </div>
                            </div>
                            
                            <h5 class="card-title fw-bold text-dark mb-2">{{ item.nama }}</h5>
                            
                            <div v-if="item.type === 'url'" class="text-muted small mb-3 fst-italic text-truncate">
                                <i class="fas fa-link me-1"></i> {{ item.url }}
                            </div>
                            <div v-else class="text-muted small mb-3">
                                <i class="fas fa-file-alt me-1"></i> Konten Halaman
                            </div>

                            <div class="mt-auto d-flex gap-2">
                                <Link 
                                    v-if="item.type === 'group'" 
                                    :href="`/admin/halaman/items/${item.id}`" 
                                    class="btn btn-outline-primary w-100 btn-sm"
                                >
                                    <i class="fas fa-folder-open me-1"></i> Buka
                                </Link>
                                
                                <Link 
                                    v-if="item.type === 'page'" 
                                    :href="`/admin/halaman/items/${item.id}/content`" 
                                    class="btn btn-outline-success w-100 btn-sm"
                                >
                                    <i class="fas fa-pen me-1"></i> Edit Konten
                                </Link>

                                <button 
                                    class="btn btn-outline-danger btn-sm" 
                                    title="Hapus"
                                    @click="deleteItem(item)"
                                >
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Add Modal (Simple implementation) -->
            <div class="modal fade" id="addModal" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header text-white border-0" style="background: linear-gradient(135deg, #4361ee 0%, #3a0ca3 100%);">
                            <h5 class="modal-title fw-bold"><i class="fas fa-plus-circle me-2"></i>Tambah Item Baru</h5>
                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <form @submit.prevent="submitAdd">
                            <div class="modal-body">
                                <div class="alert alert-light border mb-3 small">
                                    <i class="fas fa-info-circle me-1"></i>
                                    Tambahkan item baru ke dalam menu <strong>{{ level === 2 ? menu.nama : parent.nama }}</strong>.
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">Nama Item</label>
                                    <input type="text" class="form-control" v-model="form.nama" placeholder="Contoh: Sejarah, Visi Misi..." required>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Tipe Konten</label>
                                    <select class="form-select" v-model="form.type" required>
                                        <option value="page">Halaman Konten (Editor Teks)</option>
                                        <option value="group">Group (Memiliki Sub-Menu Lagi)</option>
                                        <option value="url">Link Eksternal (URL)</option>
                                    </select>
                                </div>
                                <div class="mb-3" v-if="form.type === 'url'">
                                    <label class="form-label">URL Tujuan</label>
                                    <input type="text" class="form-control" v-model="form.url" placeholder="https://google.com atau /halaman-lain" required>
                                </div>
                            </div>
                            <div class="modal-footer border-top-0">
                                <button type="button" class="btn btn-light" data-bs-dismiss="modal">Batal</button>
                                <button type="submit" class="btn btn-primary" :disabled="form.processing">Simpan Item</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </AdminLayout>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import AdminLayout from '@/Layouts/AdminLayout.vue';
import { Link, useForm, router } from '@inertiajs/vue3';
import { showSuccess, showError } from '@/Utils/notifications';
import Swal from 'sweetalert2';

const props = defineProps({
    menu: Object,
    items: Array,
    level: Number,
    parent: Object
});

const form = useForm({
    menu_navbar_id: props.menu.id,
    parent_id: props.parent ? props.parent.id : null,
    nama: '',
    type: 'page',
    url: ''
});

let modal = null;

onMounted(() => {
    // Initialize bootstrap modal
    if (typeof bootstrap !== 'undefined') {
        const modalEl = document.getElementById('addModal');
        modal = new bootstrap.Modal(modalEl);
    }
});

const showAddModal = () => {
    form.reset();
    form.menu_navbar_id = props.menu.id;
    form.parent_id = props.parent ? props.parent.id : null;
    modal?.show();
};

const submitAdd = () => {
    form.post('/admin/halaman/items', {
        onSuccess: () => {
            modal?.hide();
            showSuccess('Item baru berhasil ditambahkan!');
            form.reset();
            // Re-initialize modal to handle potential DOM updates
            const modalEl = document.getElementById('addModal');
            if (modalEl) {
               modal = new bootstrap.Modal(modalEl);
            }
        },
        onError: () => {
            showError('Gagal menambahkan item. Periksa inputan Anda.');
        }
    });
};

const toggleStatus = (item) => {
    router.put(`/admin/halaman/items/${item.id}`, {
        nama: item.nama,
        is_active: !item.is_active
    }, { 
        preserveScroll: true,
        onSuccess: () => {
            // Optional: Feedback on toggle if needed
        }
    });
};

const deleteItem = (item) => {
    Swal.fire({
        title: 'Hapus Item?',
        text: `Anda akan menghapus "${item.nama}". Data konten di dalamnya juga akan terhapus.`,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'Ya, Hapus!',
        cancelButtonText: 'Batal'
    }).then((result) => {
        if (result.isConfirmed) {
            router.delete(`/admin/halaman/items/${item.id}`, {
                onSuccess: () => {
                    showSuccess('Item berhasil dihapus.');
                },
                onError: () => {
                    showError('Gagal menghapus item.');
                }
            });
        }
    });
};

const getBadgeClass = (type) => {
    switch (type) {
        case 'group': return 'bg-primary';
        case 'page': return 'bg-success';
        case 'url': return 'bg-info text-dark';
        default: return 'bg-secondary';
    }
};

const getTypeName = (type) => {
    switch (type) {
        case 'group': return 'Group';
        case 'page': return 'Halaman';
        case 'url': return 'Link';
        default: return type;
    }
};

const getTypeIcon = (type) => {
    switch (type) {
        case 'group': return 'fas fa-folder';
        case 'page': return 'fas fa-file-alt';
        case 'url': return 'fas fa-link';
        default: return 'fas fa-question';
    }
};
</script>

<style scoped>
.hover-bg-light:hover {
    background-color: #f8f9fa;
}
.list-group-item {
    transition: background-color 0.2s;
}

.transition-all {
    transition: all 0.3s ease;
}

.hover-up:hover {
    transform: translateY(-5px);
}

.hover-shadow:hover {
    box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15) !important;
}

.card {
    border-radius: 12px;
}
</style>

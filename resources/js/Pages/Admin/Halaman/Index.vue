<template>
    <AdminLayout>
        <div class="content container-fluid">
            <!-- Radiant Header -->
            <div class="card border-0 rounded-4 mb-4 overflow-hidden" style="background: linear-gradient(135deg, #4361ee 0%, #3a0ca3 100%);">
                <div class="card-body p-4 position-relative">
                    <div class="d-flex align-items-center gap-4 position-relative z-1">
                        <div class="icon-box bg-white bg-opacity-25 rounded-4 p-3 d-flex align-items-center justify-content-center backdrop-blur" style="width: 64px; height: 64px;">
                            <i class="fas fa-sitemap fa-2x text-white"></i>
                        </div>
                        <div>
                            <h3 class="fw-bold mb-1 text-white">Kelola Menu Navbar</h3>
                            <p class="mb-0 text-white-50">Atur struktur menu utama website dengan mudah</p>
                        </div>
                    </div>
                    <!-- Decorative Circle -->
                    <div class="position-absolute top-0 end-0 p-3 opacity-10 pointer-events-none">
                        <i class="fas fa-layer-group fa-10x text-white transform-rotate-12" style="margin-right: -40px; margin-top: -40px;"></i>
                    </div>
                </div>
            </div>

            <!-- Page Header (Breadcrumb only) -->
            <div class="row align-items-center mb-4">
                <div class="col">
                     <nav aria-label="breadcrumb">
                        <ol class="breadcrumb alert alert-primary border-0 rounded-4 shadow-sm py-3 px-4 mb-0 align-items-center">
                            <li class="breadcrumb-item active text-primary fw-bold" aria-current="page">
                                <i class="fas fa-layer-group me-2"></i> Halaman 
                                <i class="fas fa-chevron-right ms-2 small opacity-50"></i>
                            </li>
                        </ol>
                    </nav>
                </div>
            </div>

            <div class="row g-4">
                <div class="col-lg-4 col-md-6" v-for="menu in menus" :key="menu.id">
                    <div class="card h-100 border-0 shadow-sm rounded-4 menu-card hover-elevate">
                        <div class="card-body p-4 position-relative">
                            <!-- Status Badge -->
                            <div class="position-absolute top-0 end-0 m-3">
                                <div class="form-check form-switch" v-if="menu.slug !== 'beranda'">
                                    <input 
                                        class="form-check-input shadow-none" 
                                        type="checkbox" 
                                        :checked="menu.is_active" 
                                        @change="toggleStatus(menu)"
                                        style="cursor: pointer; width: 3em; height: 1.5em;"
                                        title="Aktifkan/Nonaktifkan Menu"
                                    >
                                </div>
                                <div v-else class="text-muted small fw-bold">
                                    <i class="fas fa-lock me-1"></i> Locked
                                </div>
                            </div>

                            <div class="d-flex align-items-center mb-4">
                                <div class="icon-box bg-soft-primary rounded-circle d-flex align-items-center justify-content-center text-primary me-3" style="width: 56px; height: 56px;">
                                    <i class="fas fa-cube fa-2x"></i>
                                </div>
                                <div>
                                    <h5 class="fw-bold mb-1 text-dark text-truncate" style="max-width: 150px;">{{ menu.nama }}</h5>
                                </div>
                            </div>
                           
                            
                            <div class="d-flex justify-content-between align-items-center pt-3 border-top mt-auto">
                                <button v-if="!['beranda', 'berita'].includes(menu.slug)" class="btn btn-outline-secondary btn-sm rounded-pill px-3" @click="openEditModal(menu)">
                                    <i class="fas fa-pencil-alt me-1"></i> Rename
                                </button>
                                <div v-else></div>

                                <div v-if="!['berita'].includes(menu.slug)">
                                    <Link 
                                        :href="getManageUrl(menu)" 
                                        class="btn btn-primary btn-sm rounded-pill px-3 shadow-sm d-flex align-items-center"
                                    >
                                        <span>{{ getManageLabel(menu) }}</span>
                                        <i class="fas fa-arrow-right ms-2 small"></i>
                                    </Link>
                                </div>
                                <div v-else>
                                     <span class="text-muted small"><i class="fas fa-link me-1"></i> Direct Link</span>
                                </div>
                            </div>
                        </div>
                        <!-- Color accent bar -->
                         <div class="card-footer p-0 border-0 overflow-hidden rounded-bottom-4">
                            <div class="progress" style="height: 4px;">
                                <div class="progress-bar bg-primary" :style="{ width: menu.is_active ? '100%' : '0%' }"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>

        <!-- Edit Menu Name Modal -->
        <div class="modal fade" id="editMenuModal" tabindex="-1" ref="editModalRef">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content border-0 shadow-lg rounded-4">
                    <div class="modal-header bg-primary text-white rounded-top-4">
                         <div class="d-flex align-items-center gap-3">
                            <div class="icon-box bg-white rounded-3 text-primary p-2">
                                <i class="fas fa-edit"></i>
                            </div>
                            <div>
                                <h5 class="modal-title fw-bold text-white mb-0">Edit Nama Menu</h5>
                                <small class="text-white opacity-75">Sesuaikan label menu navigasi</small>
                            </div>
                        </div>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">Nama Menu</label>
                            <input type="text" class="form-control" v-model="editForm.nama" placeholder="Masukkan nama menu" @keyup.enter="saveMenuName">
                        </div>
                        <p class="text-muted small mb-0"><i class="fas fa-info-circle me-1"></i> Nama ini akan tampil di navigasi website.</p>
                    </div>
                    <div class="modal-footer border-0 pt-0">
                        <button type="button" class="btn btn-light rounded-pill px-4" data-bs-dismiss="modal">Batal</button>
                        <button type="button" class="btn btn-primary rounded-pill px-4" @click="saveMenuName" :disabled="saving">
                            <span v-if="saving"><i class="fas fa-spinner fa-spin me-1"></i> Menyimpan...</span>
                            <span v-else><i class="fas fa-save me-1"></i> Simpan</span>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </AdminLayout>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import { Link, router } from '@inertiajs/vue3';
import AdminLayout from '@/Layouts/AdminLayout.vue';
import { showSuccess, showError } from '@/Utils/notifications';

const props = defineProps({
    menus: Array
});

const editModalRef = ref(null);
const editForm = ref({ id: null, nama: '' });
const saving = ref(false);
let editModal = null;

onMounted(() => {
    if (typeof window !== 'undefined' && window.bootstrap) {
        editModal = new window.bootstrap.Modal(editModalRef.value);
    }
});

const openEditModal = (menu) => {
    editForm.value = { id: menu.id, nama: menu.nama };
    if (editModal) {
        editModal.show();
    }
};

const saveMenuName = () => {
    if (!editForm.value.nama.trim()) {
        showError('Gagal', 'Nama menu tidak boleh kosong!');
        return;
    }
    saving.value = true;
    router.put(`/admin/halaman/${editForm.value.id}`, {
        nama: editForm.value.nama,
        is_active: props.menus.find(m => m.id === editForm.value.id)?.is_active ?? true
    }, {
        preserveScroll: true,
        onSuccess: () => {
            showSuccess('Berhasil', 'Nama menu berhasil diperbarui!');
            if (editModal) editModal.hide();
            saving.value = false;
        },
        onError: () => {
            showError('Gagal', 'Gagal memperbarui nama menu.');
            saving.value = false;
        }
    });
};

const MEGA_MENU_SLUGS = ['akademik', 'profil', 'guru', 'siswa', 'alumni', 'ppdb'];

const getManageUrl = (menu) => {
    if (menu.slug === 'beranda') return '/admin/halaman/beranda';
    if (MEGA_MENU_SLUGS.includes(menu.slug)) return `/admin/halaman/${menu.id}/mega-menu`;
    return `/admin/halaman/${menu.id}/items`;
};

const getManageLabel = (menu) => {
    if (menu.slug === 'beranda') return 'Kelola Konten';
    if (MEGA_MENU_SLUGS.includes(menu.slug)) return 'Kelola Menu';
    return 'Kelola Sub-Menu';
};

const toggleStatus = (menu) => {
    router.put(`/admin/halaman/${menu.id}`, {
        nama: menu.nama,
        is_active: !menu.is_active
    }, {
        preserveScroll: true,
        onSuccess: () => {
            showSuccess('Berhasil', `Status menu ${menu.nama} diperbarui!`);
        },
        onError: () => showError('Gagal', 'Gagal memperbarui status menu.')
    });
};
</script>

<style scoped>
.menu-card {
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
}

.hover-elevate:hover {
    transform: translateY(-5px);
    box-shadow: 0 10px 30px rgba(0,0,0,0.1) !important;
}

.icon-box {
    display: flex;
    align-items: center;
    justify-content: center;
}

.backdrop-blur {
    backdrop-filter: blur(8px);
    -webkit-backdrop-filter: blur(8px);
}

.transform-rotate-12 {
    transform: rotate(12deg);
}
</style>


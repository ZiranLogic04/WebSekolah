<script setup>
import { ref, computed, watch } from 'vue';
import { Head, useForm, router, usePage } from '@inertiajs/vue3';
import AdminLayout from '@/Layouts/AdminLayout.vue';
import { showToast, confirmDelete } from '@/Utils/swal';

const props = defineProps({
    users: Array,
});

const search = ref('');
const page = usePage();

// Watch for flash messages
watch(() => page.props.flash, (flash) => {
    if (flash?.success) showToast('success', 'Berhasil', flash.success);
    if (flash?.error) showToast('error', 'Gagal', flash.error);
}, { deep: true });

const items = computed(() => {
    return props.users.map((user, index) => ({
        ...user,
        no: index + 1,
        initials: getInitials(user.name),
        role_display: capitalize((user.role || 'user').replace(/_/g, ' '))
    }));
});

// Filter items berdasarkan search
const filteredItems = computed(() => {
    if (!search.value) return items.value;
    const keyword = search.value.toLowerCase();
    return items.value.filter(item => 
        item.name?.toLowerCase().includes(keyword) || 
        item.email?.toLowerCase().includes(keyword) ||
        item.role?.toLowerCase().includes(keyword)
    );
});

function capitalize(s) {
    return s && s[0].toUpperCase() + s.slice(1);
}

function getInitials(name) {
    if (!name) return '';
    return name.split(' ').map(n => n[0]).join('').substring(0, 2).toUpperCase();
}

const form = useForm({
    id: null,
    name: '',
    email: '',
    role: 'siswa',
    password: '',
    password_confirmation: '',
});

const isEditing = ref(false);
const showModal = ref(false);

const openModal = (user = null) => {
    form.reset();
    form.clearErrors();
    if (user) {
        isEditing.value = true;
        form.id = user.id;
        form.name = user.name;
        form.email = user.email;
        form.role = user.role || 'siswa';
    } else {
        isEditing.value = false;
        form.role = 'siswa';
    }
    showModal.value = true;
};

const closeModal = () => {
    showModal.value = false;
    form.reset();
};

const submit = () => {
    const options = {
        onSuccess: () => {
            closeModal();
            // Toast handled by flash watcher or default Inertia behavior
        },
        onError: () => {
            showToast('error', 'Gagal', 'Terjadi kesalahan saat menyimpan data.');
        }
    };

    if (isEditing.value) {
        form.put(`/admin/akun/${form.id}`, options);
    } else {
        form.post('/admin/akun', options);
    }
};

const handleDelete = (id) => {
    confirmDelete(
        'Hapus Akun?',
        'Data akun ini akan dihapus permanen.'
    ).then((result) => {
        if (result.isConfirmed) {
            router.delete(`/admin/akun/${id}`, {
                onSuccess: () => {
                    showToast('success', 'Berhasil', 'Akun berhasil dihapus.');
                },
                onError: () => {
                    showToast('error', 'Gagal', 'Gagal menghapus akun.');
                }
            });
        }
    });
};

</script>

<template>
    <AdminLayout>
        <Head title="Kelola Akun" />
        
        <div class="content container-fluid pb-5">
            <!-- Radiant Header Card -->
            <div class="card border-0 shadow-lg rounded-4 mb-4 overflow-hidden position-relative" style="background: linear-gradient(135deg, #4f46e5 0%, #3b82f6 100%);">
                <div class="card-body p-4 p-lg-5 position-relative z-1">
                    <div class="row align-items-center">
                        <div class="col-lg-8">
                            <div class="d-flex align-items-center gap-3 mb-2">
                                <div class="icon-box bg-white rounded-3 text-primary p-2">
                                    <i class="fas fa-users-cog fs-3"></i>
                                </div>
                                <h2 class="fw-bold text-white mb-0 ls-tight">Data Akun Pengguna</h2>
                            </div>
                            <p class="text-white-50 mb-0 fs-5">Manajemen akses dan peran pengguna sistem.</p>
                        </div>
                        <div class="col-lg-4 text-lg-end mt-3 mt-lg-0">
                            <button class="btn btn-white text-primary rounded-pill fw-bold shadow-sm px-4 py-2 hover-up" @click="openModal()">
                                <i class="fas fa-plus me-2"></i> Tambah Akun
                            </button>
                        </div>
                    </div>
                </div>
                <!-- Decorative Background -->
                <div class="position-absolute bottom-0 end-0 opacity-10 me-n5 mb-n5">
                    <i class="fas fa-user-shield" style="font-size: 10rem; color: white;"></i>
                </div>
            </div>

            <!-- Modern Table Card -->
            <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
                <div class="card-header bg-white p-4 border-bottom">
                    <div class="row align-items-center">
                        <div class="col-md-6">
                            <h5 class="fw-bold mb-0 text-dark">Daftar Pengguna</h5>
                            <small class="text-muted">Total {{ users.length }} akun terdaftar</small>
                        </div>
                        <div class="col-md-6 mt-3 mt-md-0">
                            <div class="input-group">
                                <span class="input-group-text bg-white border-end-0 ps-3 rounded-start-pill text-primary">
                                    <i class="fas fa-search"></i>
                                </span>
                                <input type="text" class="form-control bg-white border-start-0 rounded-end-pill ps-0" placeholder="Cari data..." v-model="search">
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="bg-primary text-white">
                                <tr>
                                    <th class="px-4 py-3 border-0 rounded-start-2" width="60">No</th>
                                    <th class="px-4 py-3 border-0">Pengguna</th>
                                    <th class="px-4 py-3 border-0">Email</th>
                                    <th class="px-4 py-3 border-0 text-center">Role</th>
                                    <th class="px-4 py-3 border-0 text-center">Status</th>
                                    <th class="px-4 py-3 border-0 rounded-end-2 text-end" width="120">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-if="filteredItems.length === 0">
                                    <td colspan="6" class="text-center py-5">
                                        <div class="empty-state text-center py-4">
                                            <div class="mb-3">
                                                <i class="fas fa-users-slash fa-3x text-muted opacity-25"></i>
                                            </div>
                                            <h6 class="fw-bold text-dark">Tidak ada data ditemukan</h6>
                                            <p class="text-muted small mb-0">Coba kata kunci pencarian lain atau tambahkan akun baru.</p>
                                        </div>
                                    </td>
                                </tr>
                                <tr v-for="(item, index) in filteredItems" :key="item.id">
                                    <td class="px-4 py-3 text-muted fw-bold">{{ index + 1 }}</td>
                                    <td class="px-4 py-3">
                                        <div class="d-flex align-items-center">
                                            <div class="avatar-circle me-3">
                                                {{ item.initials }}
                                            </div>
                                            <div>
                                                <h6 class="mb-0 fw-bold text-dark">{{ item.name }}</h6>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-4 py-3 text-muted">{{ item.email }}</td>
                                    <td class="px-4 py-3 text-center">
                                        <span class="badge rounded-pill px-3 py-2 fw-bold" :class="{
                                            'bg-primary-subtle': item.role === 'admin',
                                            'bg-info-subtle': item.role === 'guru',
                                            'bg-warning-subtle': item.role === 'siswa',
                                            'bg-success-subtle': item.role === 'staf_keuangan',
                                            'bg-secondary-subtle': item.role === 'staf_administrasi'
                                        }">
                                            {{ item.role_display }}
                                        </span>
                                    </td>
                                    <td class="px-4 py-3 text-center">
                                        <span class="badge bg-success-subtle border-success-subtle rounded-pill px-3">Aktif</span>
                                    </td>
                                    <td class="px-4 py-3 text-end">
                                        <div class="d-flex justify-content-end gap-2">
                                            <button class="btn btn-sm btn-light text-primary hover-shadow rounded-circle" style="width: 32px; height: 32px;" @click="openModal(item)" title="Edit">
                                                <i class="fas fa-pen fa-xs"></i>
                                            </button>
                                            <button class="btn btn-sm btn-light text-danger hover-shadow rounded-circle" style="width: 32px; height: 32px;" @click="handleDelete(item.id)" title="Hapus">
                                                <i class="fas fa-trash fa-xs"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Add/Edit Modal (Standard Bootstrap with Radiant Header) -->
        <div class="modal fade" :class="{ 'show': showModal }" style="display: block;" v-if="showModal" role="dialog" aria-modal="true" @click.self="closeModal">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content border-0 rounded-4 shadow-lg overflow-hidden">
                    <div class="modal-header border-0 text-white p-4" style="background: linear-gradient(135deg, #4f46e5 0%, #3b82f6 100%);">
                        <div class="d-flex align-items-center gap-3">
                            <div class="bg-white bg-opacity-25 p-2 rounded-circle">
                                <i class="fas" :class="isEditing ? 'fa-user-edit' : 'fa-user-plus'"></i>
                            </div>
                            <div>
                                <h5 class="modal-title fw-bold mb-0 text-white">{{ isEditing ? 'Edit Akun Pengguna' : 'Tambah Akun Baru' }}</h5>
                                <p class="mb-0 small text-white-50">Isi detail informasi pengguna untuk akses sistem.</p>
                            </div>
                        </div>
                        <button type="button" class="btn-close btn-close-white" @click="closeModal"></button>
                    </div>
                    <div class="modal-body p-4">
                        <form @submit.prevent="submit">
                            <div class="mb-4">
                                <label class="form-label fw-bold small text-muted text-uppercase">Nama Lengkap <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light border-end-0 text-muted ps-3 rounded-start-3"><i class="fas fa-user"></i></span>
                                    <input type="text" class="form-control bg-light border-start-0 py-2 rounded-end-3" v-model="form.name" placeholder="Contoh: Budi Santoso" :class="{'is-invalid': form.errors.name}">
                                </div>
                                <div v-if="form.errors.name" class="text-danger small mt-1">{{ form.errors.name }}</div>
                            </div>

                            <div class="mb-4">
                                <label class="form-label fw-bold small text-muted text-uppercase">Alamat Email <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light border-end-0 text-muted ps-3 rounded-start-3"><i class="fas fa-envelope"></i></span>
                                    <input type="email" class="form-control bg-light border-start-0 py-2 rounded-end-3" v-model="form.email" placeholder="contoh@sekolah.sch.id" :class="{'is-invalid': form.errors.email}">
                                </div>
                                <div v-if="form.errors.email" class="text-danger small mt-1">{{ form.errors.email }}</div>
                            </div>
                            
                            <div class="mb-4">
                                <label class="form-label fw-bold small text-muted text-uppercase">Role / Peran <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light border-end-0 text-muted ps-3 rounded-start-3"><i class="fas fa-user-tag"></i></span>
                                    <select class="form-select bg-light border-start-0 py-2 rounded-end-3" v-model="form.role" :class="{'is-invalid': form.errors.role}">
                                        <option value="admin">Admin</option>
                                        <option value="guru">Guru</option>
                                        <option value="staf_keuangan">Staf Keuangan</option>
                                        <option value="staf_administrasi">Staf Administrasi</option>
                                        <option value="siswa">Siswa</option>
                                    </select>
                                </div>
                                <div v-if="form.errors.role" class="text-danger small mt-1">{{ form.errors.role }}</div>
                            </div>

                            <hr class="border-dashed my-4 opacity-50">

                            <div class="alert alert-light border-start border-warning border-4 text-muted small mb-3" v-if="isEditing">
                                <i class="fas fa-info-circle text-warning me-1"></i> Kosongkan password jika tidak ingin mengubahnya.
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label fw-bold small text-muted text-uppercase">Password <span class="text-danger" v-if="!isEditing">*</span></label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-light border-end-0 text-muted ps-3 rounded-start-3"><i class="fas fa-lock"></i></span>
                                        <input type="password" class="form-control bg-light border-start-0 py-2 rounded-end-3" v-model="form.password" placeholder="******" :class="{'is-invalid': form.errors.password}">
                                    </div>
                                    <div v-if="form.errors.password" class="text-danger small mt-1">{{ form.errors.password }}</div>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label fw-bold small text-muted text-uppercase">Konfirmasi Password</label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-light border-end-0 text-muted ps-3 rounded-start-3"><i class="fas fa-check-circle"></i></span>
                                        <input type="password" class="form-control bg-light border-start-0 py-2 rounded-end-3" v-model="form.password_confirmation" placeholder="******">
                                    </div>
                                </div>
                            </div>

                            <div class="d-flex justify-content-end gap-2 mt-4">
                                <button type="button" class="btn btn-light rounded-pill px-4 fw-bold" @click="closeModal">Batal</button>
                                <button type="submit" class="btn btn-primary rounded-pill px-4 fw-bold shadow-sm hover-up" :disabled="form.processing">
                                    <i class="fas me-2" :class="form.processing ? 'fa-spinner fa-spin' : 'fa-save'"></i>
                                    {{ form.processing ? 'Menyimpan...' : 'Simpan Data' }}
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <div v-if="showModal" class="modal-backdrop fade show"></div>
    </AdminLayout>
</template>

<style scoped>


/* Override/Specific refinements based on user audio feedback */
.avatar-circle {
    width: 40px;
    height: 40px;
    background: linear-gradient(135deg, #4f46e5 0%, #3b82f6 100%);
    color: white;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: 700;
    font-size: 14px;
    box-shadow: 0 3px 6px rgba(79, 70, 229, 0.2);
}

.table thead th {
    background-color: #3d5ee1 !important; /* Blue header requested */
    color: white !important;
    font-weight: 600;
    text-transform: uppercase;
    font-size: 0.85rem;
    padding: 1rem 1.5rem;
    border: none;
}

.table tbody td {
    padding: 1rem 1.5rem;
    vertical-align: middle;
}

/* Badge Styling Refinement - Solid colors instead of transparent backgrounds */
.badge.bg-primary-subtle { background-color: #e0e7ff !important; color: #4f46e5 !important; }
.badge.bg-info-subtle { background-color: #cffafe !important; color: #0891b2 !important; }
.badge.bg-warning-subtle { background-color: #fef3c7 !important; color: #d97706 !important; }
.badge.bg-success-subtle { background-color: #dcfce7 !important; color: #16a34a !important; }
.badge.bg-secondary-subtle { background-color: #f3f4f6 !important; color: #4b5563 !important; }

/* Status Badge - Ensure no white background */
.badge.bg-success-subtle.border-success-subtle {
    background-color: #d1fae5 !important; 
    border-color: #10b981 !important;
    color: #047857 !important;
}

.modal-header .modal-title {
    color: white !important;
}
.modal-header .text-white-50 {
    color: rgba(255, 255, 255, 0.8) !important;
}

.hover-up:hover {
    transform: translateY(-2px);
    transition: all 0.2s ease;
}

.hover-shadow:hover {
    box-shadow: 0 .5rem 1rem rgba(0,0,0,.15)!important;
    transition: all 0.2s ease;
}

/* Modal Transition */
.modal.fade .modal-dialog {
    transform: scale(0.95);
    transition: transform 0.3s ease-out;
}
.modal.show .modal-dialog {
    transform: scale(1);
}
</style>

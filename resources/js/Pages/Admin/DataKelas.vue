<template>
    <AdminLayout>
        <Head title="Data Kelas" />

        <div class="content container-fluid">
            <!-- Radiant Header Card -->
            <div class="card border-0 shadow-lg rounded-4 mb-4 overflow-hidden position-relative" style="background: linear-gradient(135deg, #4f46e5 0%, #3b82f6 100%);">
                <div class="card-body p-4 p-lg-5 position-relative z-1">
                    <div class="row align-items-center">
                        <div class="col-lg-8">
                            <div class="d-flex align-items-center gap-3 mb-2">
                                <div class="icon-box bg-white rounded-3 text-primary p-2">
                                    <i class="fas fa-chalkboard-teacher fs-3"></i>
                                </div>
                                <h2 class="fw-bold text-white mb-0 ls-tight">Data Kelas</h2>
                            </div>
                            <p class="text-white-50 mb-0 fs-5">Kelola data kelas dan wali kelas.</p>
                        </div>
                        <div class="col-lg-4 text-lg-end mt-3 mt-lg-0">
                            <button @click="openAddModal" class="btn btn-light btn-lg rounded-pill shadow-sm px-4 fw-bold text-primary hover-elevate">
                                <i class="fas fa-plus-circle me-2"></i>Tambah Kelas
                            </button>
                        </div>
                    </div>
                </div>
                <!-- Decorative Background -->
                <div class="position-absolute bottom-0 end-0 opacity-10 me-n5 mb-n5">
                    <i class="fas fa-school" style="font-size: 10rem; color: white;"></i>
                </div>
            </div>

            <!-- Table Card -->
            <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0 modern-table">
                            <thead class="bg-light text-secondary">
                                <tr>
                                    <th width="60" class="text-center py-3">No</th>
                                    <th class="py-3 text-center">Nama Kelas</th>
                                    <th class="py-3">Wali Kelas</th>
                                    <th class="text-center py-3">Jumlah Siswa</th>
                                    <th width="150" class="text-center py-3">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-if="kelasData.length === 0">
                                    <td colspan="5" class="text-center py-5">
                                        <div class="empty-state">
                                            <div class="empty-icon bg-light rounded-circle p-4 d-inline-block mb-3">
                                                <i class="fas fa-chalkboard fa-3x text-muted opacity-50"></i>
                                            </div>
                                            <h5 class="mb-2 text-dark fw-semibold">Tidak ada data kelas</h5>
                                            <p class="text-muted mb-0">Silakan tambah kelas baru untuk memulai</p>
                                        </div>
                                    </td>
                                </tr>
                                <tr v-for="(kelas, index) in kelasData" :key="kelas.id" class="align-middle">
                                    <td class="text-center text-muted fw-medium">{{ index + 1 }}</td>
                                    <td class="text-center">
                                        <span class="badge bg-indigo-subtle text-indigo px-3 py-2 rounded-pill fs-7 fw-bold border border-indigo-subtle">
                                            {{ kelas.nama }}
                                        </span>
                                    </td>
                                    <td>
                                        <div v-if="kelas.wali_kelas" class="d-flex align-items-center p-1">
                                            <div class="avatar-circle me-3 shadow-sm gradient-avatar text-white fw-bold">
                                                {{ getInitials(kelas.wali_kelas.nama) }}
                                            </div>
                                            <div>
                                                <div class="fw-bold text-dark">{{ kelas.wali_kelas.nama }}</div>
                                                <small class="text-muted d-flex align-items-center gap-1">
                                                    <i class="far fa-id-card fa-xs"></i>
                                                    {{ kelas.wali_kelas.nip || '-' }}
                                                </small>
                                            </div>
                                        </div>
                                        <div v-else class="text-muted fst-italic ps-2 opacity-75">
                                            <i class="fas fa-minus-circle me-1"></i> Belum ditentukan
                                        </div>
                                    </td>
                                    <td class="text-center">
                                        <div class="d-inline-flex align-items-center gap-2 px-3 py-1 bg-light rounded-pill border">
                                            <i class="fas fa-user-graduate text-secondary fa-sm"></i>
                                            <span class="fw-bold text-dark">{{ kelas.siswa_count ?? 0 }}</span>
                                        </div>
                                    </td>
                                    <td class="text-center">
                                        <div class="d-flex justify-content-center gap-2">
                                            <button
                                                @click="editKelas(kelas)"
                                                class="btn btn-icon btn-light-primary"
                                                title="Edit"
                                            >
                                                <i class="fas fa-pen"></i>
                                            </button>
                                            <button
                                                @click="confirmDelete(kelas)"
                                                class="btn btn-icon btn-light-danger"
                                                title="Hapus"
                                            >
                                                <i class="fas fa-trash-alt"></i>
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

        <!-- Add/Edit Modal with Premium Design -->
        <div v-if="showModal" class="modal fade show d-block" style="background: rgba(15, 23, 42, 0.6); backdrop-filter: blur(4px); z-index: 1050;">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content border-0 shadow-lg rounded-4 overflow-hidden">
                    <div class="modal-header bg-gradient-to-r from-blue-600 to-indigo-700 border-0 p-4">
                        <div class="d-flex align-items-center gap-3">
                            <div class="modal-icon-box bg-white text-primary rounded-circle p-2 d-flex align-items-center justify-content-center shadow-sm">
                                <i :class="isEditing ? 'fas fa-edit' : 'fas fa-plus'" class="fa-lg"></i>
                            </div>
                            <div>
                                <h5 class="modal-title fw-bold mb-0 text-white">
                                    {{ isEditing ? 'Edit Kelas' : 'Tambah Kelas Baru' }}
                                </h5>
                                <p class="mb-0 text-white-50 small">
                                    {{ isEditing ? 'Perbarui informasi kelas' : 'Isi form untuk membuat kelas' }}
                                </p>
                            </div>
                        </div>
                        <button type="button" class="btn-close btn-close-white opacity-100" @click="closeModal"></button>
                    </div>
                    
                    <form @submit.prevent="submitForm">
                        <div class="modal-body p-4 bg-white">
                            <div class="modern-form">
                                <div class="mb-4">
                                    <label class="form-label fw-bold text-secondary small text-uppercase ls-1">Nama Kelas</label>
                                    <div class="input-group input-group-lg shadow-sm">
                                        <span class="input-group-text bg-light border-end-0 text-muted ps-3">
                                            <i class="fas fa-chalkboard"></i>
                                        </span>
                                        <input
                                            v-model="form.nama"
                                            type="text"
                                            class="form-control border-start-0 ps-0 fs-6 bg-light text-dark shadow-none"
                                            placeholder="Contoh: X IPA 1"
                                            :class="{ 'is-invalid': form.errors.nama }"
                                        />
                                    </div>
                                    <div v-if="form.errors.nama" class="invalid-feedback d-block mt-1">
                                        <i class="fas fa-exclamation-circle me-1"></i> {{ form.errors.nama }}
                                    </div>
                                </div>
                                
                                <div class="mb-2">
                                    <label class="form-label fw-bold text-secondary small text-uppercase ls-1">Wali Kelas</label>
                                    <div class="input-group input-group-lg shadow-sm">
                                        <span class="input-group-text bg-light border-end-0 text-muted ps-3">
                                            <i class="fas fa-user-tie"></i>
                                        </span>
                                        <select 
                                            v-model="form.wali_kelas_id" 
                                            class="form-select border-start-0 ps-0 fs-6 bg-light text-dark shadow-none"
                                            :class="{ 'is-invalid': form.errors.wali_kelas_id }"
                                            style="cursor: pointer;"
                                        >
                                            <option value="">-- Pilih Wali Kelas --</option>
                                            <option v-for="guru in guruList" :key="guru.id" :value="guru.id">
                                                {{ guru.nama }}
                                            </option>
                                        </select>
                                    </div>
                                    <div v-if="form.errors.wali_kelas_id" class="invalid-feedback d-block mt-1">
                                        <i class="fas fa-exclamation-circle me-1"></i> {{ form.errors.wali_kelas_id }}
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer bg-light border-0 p-3 justify-content-between">
                             <button type="button" class="btn btn-light px-4 fw-medium text-secondary" @click="closeModal">
                                Batal
                            </button>
                            <button type="submit" class="btn btn-primary px-5 shadow-sm btn-gradient" :disabled="form.processing">
                                <span v-if="form.processing" class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span>
                                <i v-else class="fas fa-save me-2"></i> 
                                {{ isEditing ? 'Simpan Perubahan' : 'Simpan Kelas' }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </AdminLayout>
</template>

<script setup>
import { ref, watch } from 'vue';
import { Head, Link, useForm, router, usePage } from '@inertiajs/vue3';
import AdminLayout from '@/Layouts/AdminLayout.vue';
import Swal from 'sweetalert2';
import { showToast } from '@/Utils/swal';

const props = defineProps({
    kelasData: Array,
    guruList: Array
});

const page = usePage();

// Watch Flash Messages
watch(() => page.props.flash, (flash) => {
    if (flash?.success) {
        showToast('success', 'Sukses', flash.success);
    }
    if (flash?.error) {
        showToast('error', 'Gagal', flash.error);
    }
}, { deep: true, immediate: true });

// State
const showModal = ref(false);
const isEditing = ref(false);
const editingId = ref(null);

const form = useForm({
    nama: '',
    wali_kelas_id: ''
});

// Actions
const openAddModal = () => {
    isEditing.value = false;
    form.reset();
    form.clearErrors();
    form.wali_kelas_id = ""; 
    showModal.value = true;
};

const editKelas = (kelas) => {
    isEditing.value = true;
    editingId.value = kelas.id;
    form.nama = kelas.nama;
    form.wali_kelas_id = kelas.wali_kelas_id || "";
    form.clearErrors();
    showModal.value = true;
};

const closeModal = () => {
    showModal.value = false;
    form.reset();
};

const submitForm = () => {
    const options = {
        onSuccess: () => closeModal(),
        onError: () => {
             // Generic error toast, specific errors are inline
             showToast('error', 'Gagal', 'Periksa inputan anda.');
        }
    };

    if (isEditing.value) {
        form.put(`/admin/kelas/${editingId.value}`, options);
    } else {
        form.post('/admin/kelas', options);
    }
};

const confirmDelete = (kelas) => {
    const studentCount = kelas.siswa_count || 0;
    
    let title = 'Hapus Kelas?';
    let html = `Anda yakin ingin menghapus kelas <br/><strong class="text-dark">"${kelas.nama}"</strong>?`;
    let icon = 'warning';
    let confirmBtnColor = '#ef4444';

    // Warning validation if class has students
    if (studentCount > 0) {
        title = '⚠️ Hapus Kelas Berisi Siswa?';
        html = `Kelas <strong class="text-dark">"${kelas.nama}"</strong> masih memiliki <strong class="text-danger">${studentCount} Siswa</strong>.<br/>Siswa di kelas ini akan menjadi <strong>Tanpa Kelas</strong>!`;
        icon = 'warning'; 
    }

    Swal.fire({
        title: title,
        html: html,
        icon: icon,
        showCancelButton: true,
        confirmButtonColor: confirmBtnColor,
        cancelButtonColor: '#94a3b8',
        confirmButtonText: '<i class="fas fa-trash-alt me-1"></i> Ya, Hapus!',
        cancelButtonText: 'Batal',
        customClass: {
            popup: 'rounded-4 shadow-lg border-0',
            title: 'fs-5 fw-bold text-dark',
            htmlContainer: 'text-secondary',
            confirmButton: 'btn btn-danger px-4 py-2 rounded-3 shadow-sm',
            cancelButton: 'btn btn-secondary px-4 py-2 rounded-3 shadow-sm'
        },
        buttonsStyling: false
    }).then((result) => {
        if (result.isConfirmed) {
            router.delete(`/admin/kelas/${kelas.id}`, {
                preserveScroll: true,
                onSuccess: () => {
                    // Success toast handled by watcher
                },
                onError: (errors) => {
                     showToast('error', 'Gagal', 'Terjadi kesalahan saat menghapus kelas.');
                }
            });
        }
    });
};

// Utils
const getInitials = (name) => {
    if (!name) return "??";
    const parts = name.split(" ");
    if (parts.length === 1) return parts[0].charAt(0).toUpperCase();
    return (parts[0].charAt(0) + parts[parts.length - 1].charAt(0)).toUpperCase();
};
</script>

<style scoped>
/* Gradient Header */
.gradient-header {
    background: linear-gradient(135deg, #4f46e5 0%, #3b82f6 100%);
}

.header-icon-box {
    width: 42px;
    height: 42px;
    display: flex;
    align-items: center;
    justify-content: center;
}

/* Modern Table */
.modern-table thead th {
    font-weight: 600;
    text-transform: uppercase;
    font-size: 0.75rem;
    letter-spacing: 0.5px;
    border-bottom: 2px solid #e2e8f0;
    background-color: #f8f9fa !important;
    color: #334155 !important;
}

.avatar-circle {
    width: 38px;
    height: 38px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 14px;
}

.gradient-avatar {
    background: linear-gradient(135deg, #6366f1 0%, #4f46e5 100%);
}

.bg-indigo-subtle {
    background-color: #e0e7ff;
}
.text-indigo {
    color: #4338ca;
}
.border-indigo-subtle {
    border-color: #c7d2fe !important;
}

.btn-icon {
    width: 32px;
    height: 32px;
    padding: 0;
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 8px;
    transition: all 0.2s;
}

.btn-light-primary {
    background-color: #eff6ff;
    color: #3b82f6;
    border: none;
}
.btn-light-primary:hover {
    background-color: #3b82f6;
    color: white;
    transform: translateY(-2px);
    box-shadow: 0 4px 6px rgba(59, 130, 246, 0.2);
}

.btn-light-danger {
    background-color: #fef2f2;
    color: #ef4444;
    border: none;
}
.btn-light-danger:hover {
    background-color: #ef4444;
    color: white;
    transform: translateY(-2px);
    box-shadow: 0 4px 6px rgba(239, 68, 68, 0.2);
}

/* Modal Styling */
.bg-gradient-to-r {
    background: linear-gradient(to right, #2563eb, #4338ca);
}

.modal-icon-box {
    width: 44px;
    height: 44px;
}

.modern-form .input-group-text {
    border-color: #e2e8f0;
}

.modern-form .form-control, .modern-form .form-select {
    border-color: #e2e8f0;
    padding: 0.75rem 1rem;
    color: #1e293b; 
}
.modern-form .form-control:focus, .modern-form .form-select:focus {
    border-color: #6366f1;
    box-shadow: none;
    background-color: #fff;
}

.ls-1 {
    letter-spacing: 0.5px;
}

.btn-gradient {
    background: linear-gradient(135deg, #4f46e5 0%, #3b82f6 100%);
    border: none;
    transition: all 0.3s ease;
}
.btn-gradient:hover {
    background: linear-gradient(135deg, #4338ca 0%, #2563eb 100%);
    transform: translateY(-1px);
    box-shadow: 0 4px 12px rgba(67, 56, 202, 0.3);
}

/* SweetAlert Toast Customization */
body.swal2-toast-shown .swal2-container.swal2-top-end, 
body.swal2-toast-shown .swal2-container.swal2-top-right {
    top: 1rem !important;
    right: 1rem !important;
}

:deep(.small-toast) {
    font-size: 0.85rem !important;
    width: auto !important;
    min-width: 350px !important;
    max-width: 800px !important;
    padding: 0.75rem 1rem !important;
    white-space: nowrap !important;
    box-shadow: 0 4px 12px rgba(0,0,0,0.1) !important;
    border-radius: 12px !important;
}

:deep(.small-toast-title) {
    font-weight: 600 !important;
    width: 100%;
}
</style>

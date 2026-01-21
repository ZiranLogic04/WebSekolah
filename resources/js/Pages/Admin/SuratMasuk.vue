<script setup>
import { ref, computed, watch } from 'vue';
import { Head, Link, useForm, router, usePage } from '@inertiajs/vue3';
import AdminLayout from '@/Layouts/AdminLayout.vue';
import Swal from 'sweetalert2';
import { showToast } from '@/Utils/swal';

const props = defineProps({
    suratMasuk: Array,
});

const page = usePage();

// Watch for flash messages from Inertia (sama dengan DataSiswa)
watch(
    () => page.props.flash,
    (flash) => {
        if (flash?.success) {
            showToast("success", "Sukses!", flash.success);
        }
        if (flash?.error) {
            showToast("error", "Error!", flash.error);
        }
    },
    { deep: true, immediate: true }
);

const showModal = ref(false);
const isEditing = ref(false);

// Form
const form = useForm({
    id: null,
    nomor_agenda: '',
    nomor_surat: '',
    pengirim: '',
    perihal: '',
    tgl_surat: new Date().toISOString().substr(0, 10),
    tgl_diterima: new Date().toISOString().substr(0, 10),
    file_scan: null,
    keterangan: '',
});

const tableData = computed(() => {
    return props.suratMasuk.map((item, index) => ({
        ...item,
        no: index + 1
    }));
});

// Actions
const openAddModal = () => {
    form.reset();
    form.id = null;
    form.tgl_surat = new Date().toISOString().substr(0, 10);
    form.tgl_diterima = new Date().toISOString().substr(0, 10);
    isEditing.value = false;
    showModal.value = true;
};

const openEditModal = (item) => {
    form.id = item.id;
    form.nomor_agenda = item.nomor_agenda || '';
    form.nomor_surat = item.nomor_surat;
    form.pengirim = item.pengirim;
    form.perihal = item.perihal;
    form.tgl_surat = item.tgl_surat;
    form.tgl_diterima = item.tgl_diterima;
    form.keterangan = item.keterangan;
    form.file_scan = null;
    
    isEditing.value = true;
    showModal.value = true;
};

const closeModal = () => {
    showModal.value = false;
    form.reset();
};

const submitForm = () => {
    const url = isEditing.value ? `/admin/surat-masuk/${form.id}` : '/admin/surat-masuk';
    
    const options = {
        preserveScroll: true,
        onSuccess: () => {
            showToast(
                'success',
                'Sukses!',
                isEditing.value ? 'Data surat masuk berhasil diperbarui' : 'Surat masuk berhasil dicatat'
            );
            closeModal();
        },
        onError: (errors) => {
            showToast('error', 'Gagal!', 'Periksa inputan anda. Pastikan semua kolom wajib diisi dengan benar.');
        },
    };
    
    if (isEditing.value) {
        form.post(url, { ...options, data: { _method: 'PUT' } });
    } else {
        form.post(url, options);
    }
};

const confirmDelete = async (item) => {
    const result = await Swal.fire({
        title: 'Hapus Surat Masuk?',
        text: `Surat dari "${item.pengirim}" akan dihapus permanen.`,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#dc3545',
        cancelButtonColor: '#6c757d',
        confirmButtonText: 'Ya, Hapus',
        cancelButtonText: 'Batal'
    });

    if (result.isConfirmed) {
        router.delete(`/admin/surat-masuk/${item.id}`);
    }
};

const viewFile = (id) => {
    window.open(`/admin/surat-masuk/${id}/view`, '_blank');
};

const formatDate = (dateStr) => {
    if (!dateStr) return '-';
    return new Date(dateStr).toLocaleDateString('id-ID', { day: 'numeric', month: 'short', year: 'numeric' });
};


const handleFileSelect = (event) => {
    const file = event.target.files[0];
    if (file) {
        form.file_scan = file;
    }
};
</script>

<template>
    <AdminLayout>
        <Head title="Surat Masuk" />
        
        <div class="content container-fluid">
            <!-- Radiant Header Card (sama dengan DataSiswa) -->
            <div class="card border-0 shadow-lg rounded-4 mb-4 overflow-hidden position-relative" style="background: linear-gradient(135deg, #4f46e5 0%, #3b82f6 100%);">
                <div class="card-body p-4 p-lg-5 position-relative z-1">
                    <div class="row align-items-center">
                        <div class="col-lg-8">
                            <div class="d-flex align-items-center gap-3 mb-2">
                                <div class="icon-box bg-white rounded-3 text-primary p-2">
                                    <i class="fas fa-envelope-open-text fs-3"></i>
                                </div>
                                <h2 class="fw-bold text-white mb-0 ls-tight">Surat Masuk</h2>
                            </div>
                            <p class="text-white-50 mb-0 fs-5">Kelola dan arsipkan surat masuk dari instansi lain.</p>
                        </div>
                        <div class="col-lg-4 text-lg-end mt-3 mt-lg-0">
                            <button @click="openAddModal" class="btn btn-light btn-lg rounded-pill shadow-sm px-4 fw-bold text-primary hover-elevate">
                                <i class="fas fa-plus-circle me-2"></i>Catat Surat Masuk
                            </button>
                        </div>
                    </div>
                </div>
                <!-- Decorative Background -->
                <div class="position-absolute bottom-0 end-0 opacity-10 me-n5 mb-n5">
                    <i class="fas fa-envelope-open-text" style="font-size: 10rem; color: white;"></i>
                </div>
            </div>

            <!-- Modern Table Card -->
            <div class="card modern-table-card">
                <div class="card-header">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h5 class="card-title mb-0">Daftar Surat Masuk</h5>
                            <p class="card-subtitle text-muted mb-0">{{ tableData.length }} surat tercatat</p>
                        </div>
                    </div>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-modern mb-0">
                            <thead>
                                <tr>
                                    <th width="60" class="text-center">No</th>
                                    <th class="text-start">No. Agenda</th>
                                    <th class="text-start">No. Surat</th>
                                    <th class="text-start">Pengirim</th>
                                    <th class="text-start">Perihal</th>
                                    <th class="text-center">Tgl Surat</th>
                                    <th class="text-center">Tgl Diterima</th>

                                    <th width="120" class="text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-if="tableData.length === 0">
                                    <td colspan="8" class="text-center py-5">
                                        <div class="empty-state">
                                            <div class="empty-icon mb-3">
                                                <i class="fas fa-envelope-open fa-3x text-muted"></i>
                                            </div>
                                            <h5 class="mb-2">Belum ada surat masuk</h5>
                                            <p class="text-muted mb-4">Klik tombol "Catat Surat Masuk" untuk menambahkan data</p>
                                            <button @click="openAddModal" class="btn btn-primary">
                                                <i class="fas fa-plus me-1"></i> Catat Surat Masuk
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                                <tr v-for="item in tableData" :key="item.id">
                                    <td class="text-center fw-medium text-muted">{{ item.no }}</td>
                                    <td class="text-start">
                                        <span class="badge bg-light text-dark">{{ item.nomor_agenda || '-' }}</span>
                                    </td>
                                    <td class="text-start fw-semibold">{{ item.nomor_surat }}</td>
                                    <td class="text-start">
                                        <div class="fw-medium">{{ item.pengirim }}</div>
                                    </td>
                                    <td class="text-start">
                                        <div class="text-truncate" style="max-width: 200px;" :title="item.perihal">
                                            {{ item.perihal }}
                                        </div>
                                    </td>
                                    <td class="text-center">
                                        <small class="text-muted">{{ formatDate(item.tgl_surat) }}</small>
                                    </td>
                                    <td class="text-center">
                                        <span class="badge bg-success-soft text-success">{{ formatDate(item.tgl_diterima) }}</span>
                                    </td>

                                    <td class="text-center">
                                        <div class="d-flex gap-1 justify-content-center">
                                            <button 
                                                v-if="item.file_path"
                                                class="btn-icon-modern btn-icon-view" 
                                                @click="viewFile(item.id)" 
                                                title="Lihat File"
                                            >
                                                <i class="fas fa-download"></i>
                                            </button>
                                            <button class="btn-icon-modern btn-icon-edit" @click="openEditModal(item)" title="Edit">
                                                <i class="fas fa-edit"></i>
                                            </button>
                                            <button class="btn-icon-modern btn-icon-delete" @click="confirmDelete(item)" title="Hapus">
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

        <!-- Modern Modal Form -->
        <div v-if="showModal" class="modal fade show d-block" tabindex="-1" style="background: rgba(0, 0, 0, 0.5); z-index: 1050">
            <div class="modal-dialog modal-lg modal-dialog-centered" style="z-index: 1060">
                <div class="modal-content modern-modal">
                    <div class="modal-header">
                        <div class="modal-title">
                            <i class="modal-icon" :class="isEditing ? 'fas fa-edit' : 'fas fa-envelope-open-text'"></i>
                            <div>
                                <h5>{{ isEditing ? 'Edit Surat Masuk' : 'Catat Surat Masuk' }}</h5>
                                <p class="modal-subtitle text-muted">
                                    {{ isEditing ? 'Perbarui data surat yang sudah ada' : 'Tambahkan catatan surat masuk baru' }}
                                </p>
                            </div>
                        </div>
                        <button type="button" class="btn-close" @click="closeModal"></button>
                    </div>
                    <form @submit.prevent="submitForm">
                        <div class="modal-body">
                            <!-- Section: Identitas Surat -->
                            <div class="mb-4">
                                <h6 class="section-title text-primary border-bottom pb-2 mb-3">
                                    <i class="fas fa-file-alt me-2"></i>Identitas Surat
                                </h6>
                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <label class="form-label">Nomor Agenda</label>
                                        <div class="input-group">
                                            <span class="input-group-text"><i class="fas fa-bookmark"></i></span>
                                            <input type="text" class="form-control" v-model="form.nomor_agenda" placeholder="Contoh: 001/I/2024">
                                        </div>
                                        <small class="text-muted">Opsional - Penomoran internal</small>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label required">Nomor Surat</label>
                                        <div class="input-group">
                                            <span class="input-group-text"><i class="fas fa-hashtag"></i></span>
                                            <input type="text" class="form-control" v-model="form.nomor_surat" placeholder="Nomor surat dari pengirim">
                                        </div>
                                        <div v-if="form.errors.nomor_surat" class="text-danger small mt-1">{{ form.errors.nomor_surat }}</div>
                                    </div>
                                </div>
                            </div>

                            <!-- Section: Asal & Perihal -->
                            <div class="mb-4">
                                <h6 class="section-title text-primary border-bottom pb-2 mb-3">
                                    <i class="fas fa-info-circle me-2"></i>Asal & Perihal
                                </h6>
                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <label class="form-label required">Pengirim</label>
                                        <div class="input-group">
                                            <span class="input-group-text"><i class="fas fa-building"></i></span>
                                            <input type="text" class="form-control" v-model="form.pengirim" placeholder="Instansi / Perorangan">
                                        </div>
                                        <div v-if="form.errors.pengirim" class="text-danger small mt-1">{{ form.errors.pengirim }}</div>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label required">Perihal</label>
                                        <div class="input-group">
                                            <span class="input-group-text"><i class="fas fa-comment-alt"></i></span>
                                            <input type="text" class="form-control" v-model="form.perihal" placeholder="Inti/ringkasan surat">
                                        </div>
                                        <div v-if="form.errors.perihal" class="text-danger small mt-1">{{ form.errors.perihal }}</div>
                                    </div>
                                </div>
                            </div>

                            <!-- Section: Tanggal -->
                            <div class="mb-4">
                                <h6 class="section-title text-primary border-bottom pb-2 mb-3">
                                    <i class="fas fa-calendar-alt me-2"></i>Tanggal
                                </h6>
                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <label class="form-label required">Tanggal Surat</label>
                                        <div class="input-group">
                                            <span class="input-group-text"><i class="fas fa-calendar"></i></span>
                                            <input type="date" class="form-control" v-model="form.tgl_surat">
                                        </div>
                                        <div v-if="form.errors.tgl_surat" class="text-danger small mt-1">{{ form.errors.tgl_surat }}</div>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label required">Tanggal Diterima</label>
                                        <div class="input-group">
                                            <span class="input-group-text"><i class="fas fa-calendar-check"></i></span>
                                            <input type="date" class="form-control" v-model="form.tgl_diterima">
                                        </div>
                                        <div v-if="form.errors.tgl_diterima" class="text-danger small mt-1">{{ form.errors.tgl_diterima }}</div>
                                    </div>
                                </div>
                            </div>

                            <!-- Section: Lampiran & Keterangan -->
                            <div class="mb-3">
                                <h6 class="section-title text-primary border-bottom pb-2 mb-3">
                                    <i class="fas fa-paperclip me-2"></i>Lampiran & Keterangan
                                </h6>
                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <label class="form-label">File Scan Surat</label>
                                        <div class="upload-card" @click="$refs.fileScanInput.click()">
                                            <input type="file" ref="fileScanInput" class="d-none" @change="handleFileSelect($event)" accept=".pdf,.jpg,.jpeg,.png">
                                            <div class="upload-card-content" :class="{ 'has-file': form.file_scan }">
                                                <i class="fas fa-file-pdf upload-icon"></i>
                                                <span class="upload-label">{{ form.file_scan ? form.file_scan.name : 'File Scan Surat' }}</span>
                                                <span v-if="form.file_scan" class="upload-status text-success">
                                                    <i class="fas fa-check-circle me-1"></i>File siap diupload
                                                </span>
                                                <span v-else class="upload-hint">Klik untuk pilih file (PDF, JPG, PNG)</span>
                                            </div>
                                        </div>
                                        <small class="text-muted d-block mt-1" v-if="isEditing">
                                            <i class="fas fa-info-circle me-1"></i>Biarkan kosong jika tidak ingin mengubah file.
                                        </small>
                                        <div v-if="form.errors.file_scan" class="text-danger small mt-1">{{ form.errors.file_scan }}</div>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label">Keterangan Tambahan</label>
                                        <textarea class="form-control" rows="4" v-model="form.keterangan" placeholder="Catatan tambahan..."></textarea>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-light" @click="closeModal">
                                <i class="fas fa-times me-1"></i> Batal
                            </button>
                            <button type="submit" class="btn btn-primary" :disabled="form.processing">
                                <i class="fas fa-save me-1"></i> 
                                {{ form.processing ? 'Menyimpan...' : 'Simpan Data' }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </AdminLayout>
</template>

<style scoped>
.bg-success-soft {
    background-color: rgba(25, 135, 84, 0.1) !important;
}

/* Table Header Styling */
.table-modern thead th {
    background-color: #3d5ee1 !important;
    font-weight: 600;
    color: #ffffff;
    padding: 12px 15px;
    border-bottom: 2px solid #3351c7;
    font-size: 13px;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

/* Upload Card Styling */
.upload-card {
    border: 2px dashed #dee2e6;
    border-radius: 12px;
    padding: 1.5rem;
    cursor: pointer;
    transition: all 0.3s ease;
    background: #f8f9fa;
}

.upload-card:hover {
    border-color: #3d5ee1;
    background: rgba(61, 94, 225, 0.05);
    transform: translateY(-2px);
}

.upload-card-content {
    display: flex;
    flex-direction: column;
    align-items: center;
    text-align: center;
    gap: 0.5rem;
}

.upload-card-content.has-file {
    border-color: #198754;
    background: rgba(25, 135, 84, 0.05);
}

.upload-icon {
    font-size: 2rem;
    color: #6c757d;
    margin-bottom: 0.25rem;
}

.upload-card:hover .upload-icon {
    color: #3d5ee1;
}

.upload-card-content.has-file .upload-icon {
    color: #198754;
}

.upload-label {
    font-weight: 600;
    color: #495057;
    font-size: 0.95rem;
}

.upload-hint {
    font-size: 0.8rem;
    color: #6c757d;
}

.upload-status {
    font-size: 0.85rem;
    font-weight: 500;
}
</style>

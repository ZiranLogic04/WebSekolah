<template>
    <AdminLayout>
        <div class="content container-fluid">
            <!-- Radiant Header Card -->
            <div class="card border-0 shadow-lg rounded-4 mb-4 overflow-hidden position-relative" style="background: linear-gradient(135deg, #4f46e5 0%, #3b82f6 100%);">
                <div class="card-body p-4 p-lg-5 position-relative z-1">
                    <div class="row align-items-center">
                        <div class="col-lg-7">
                            <div class="d-flex align-items-center gap-3 mb-2">
                                <div class="icon-box bg-white rounded-3 text-primary p-2">
                                    <i class="fas fa-tags fs-3"></i>
                                </div>
                                <h2 class="fw-bold text-white mb-0 ls-tight">Jenis Tagihan</h2>
                            </div>
                            <p class="text-white-50 mb-0 fs-5">Kelola kategori/jenis tagihan sekolah.</p>
                        </div>
                        <div class="col-lg-5 text-lg-end mt-3 mt-lg-0">
                            <Link href="/admin/tagihan" class="btn btn-light rounded-pill px-4 me-2 fw-medium">
                                <i class="fas fa-arrow-left me-2 text-secondary"></i>Kembali
                            </Link>
                            <button class="btn btn-light btn-lg rounded-pill shadow-sm px-4 fw-bold text-primary hover-elevate" @click="openModal()">
                                <i class="fas fa-plus-circle me-2 text-primary"></i>Tambah Jenis
                            </button>
                        </div>
                    </div>
                </div>
                <!-- Decorative Background -->
                <div class="position-absolute bottom-0 end-0 opacity-10 me-n5 mb-n5">
                    <i class="fas fa-tags" style="font-size: 10rem; color: white;"></i>
                </div>
            </div>

            <!-- Table Card -->
            <div class="row">
                <div class="col-12">
                    <div class="card border-0 shadow-sm rounded-4">
                        <div class="card-body p-4">
                            <div class="table-responsive">
                                <table class="table table-hover table-center mb-0">
                                    <thead>
                                        <tr>
                                            <th>Nama Jenis Tagihan</th>
                                            <th>Kode</th>
                                            <th>Keterangan</th>
                                            <th class="text-end">Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr v-for="item in filteredData" :key="item.id">
                                            <td class="fw-bold">{{ item.nama }}</td>
                                            <td><span class="badge bg-secondary">{{ item.kode || '-' }}</span></td>
                                            <td>{{ item.keterangan || '-' }}</td>
                                            <td class="text-end">
                                                <div class="btn-group">
                                                    <button class="btn btn-sm btn-outline-success" @click="openModal(item)" title="Edit">
                                                        <i class="fas fa-edit"></i>
                                                    </button>
                                                    <button class="btn btn-sm btn-outline-danger" @click="confirmDelete(item.id)" title="Hapus">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </div>
                                            </td>
                                        </tr>
                                        <tr v-if="filteredData.length === 0">
                                            <td colspan="4" class="text-center text-muted py-5">
                                                <i class="fas fa-inbox fa-3x mb-3 d-block opacity-25"></i>
                                                Belum ada data jenis tagihan.
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Modal -->
            <div class="modal fade" id="modalJenis" tabindex="-1" role="dialog" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered" role="document">
                    <div class="modal-content border-0 shadow-lg rounded-4">
                        <div class="modal-header bg-primary text-white rounded-top-4">
                            <div class="modal-title d-flex align-items-center">
                                <div class="icon-box bg-white rounded-3 text-primary p-2 me-3">
                                    <i class="fas fa-tags"></i>
                                </div>
                                <div>
                                    <h5 class="mb-0 text-white">{{ isEdit ? 'Edit Jenis Tagihan' : 'Tambah Jenis Tagihan' }}</h5>
                                    <small class="text-white opacity-75">{{ isEdit ? 'Perbarui data jenis tagihan' : 'Buat kategori tagihan baru' }}</small>
                                </div>
                            </div>
                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body p-4">
                            <form @submit.prevent="submit">
                                <div class="mb-4">
                                    <label class="form-label fw-bold">Nama Jenis Tagihan <span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="fas fa-tag"></i></span>
                                        <input class="form-control" type="text" v-model="form.nama" placeholder="Contoh: SPP, ATK, Listrik">
                                    </div>
                                    <div class="invalid-feedback d-block" v-if="form.errors.nama">{{ form.errors.nama }}</div>
                                </div>
                                <div class="mb-4">
                                    <label class="form-label fw-bold">Kode <span class="text-muted small">(Opsional)</span></label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="fas fa-barcode"></i></span>
                                        <input class="form-control" type="text" v-model="form.kode" placeholder="Contoh: SPP-2024">
                                    </div>
                                </div>
                                <div class="mb-4">
                                    <label class="form-label fw-bold">Keterangan <span class="text-muted small">(Opsional)</span></label>
                                    <textarea class="form-control" v-model="form.keterangan" rows="2" placeholder="Deskripsi jenis tagihan..."></textarea>
                                </div>
                                <div class="d-flex gap-2">
                                    <button type="button" class="btn btn-light flex-fill rounded-pill" data-bs-dismiss="modal">
                                        <i class="fas fa-times me-1"></i> Batal
                                    </button>
                                    <button type="submit" class="btn btn-primary flex-fill rounded-pill" :disabled="form.processing">
                                        <span v-if="form.processing"><i class="fas fa-spinner fa-spin me-1"></i> Menyimpan...</span>
                                        <span v-else><i class="fas fa-save me-1"></i> Simpan</span>
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </AdminLayout>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue';
import { Link, useForm, router } from '@inertiajs/vue3';
import AdminLayout from '@/Layouts/AdminLayout.vue';
import Swal from 'sweetalert2';
import { showToast } from '@/Utils/swal';

const props = defineProps({
    jenisTagihans: Array,
});

const isEdit = ref(false);
const modalInstance = ref(null);

const form = useForm({
    id: null,
    nama: '',
    kode: '',
    keterangan: ''
});

// Use data directly (Controller already filters latest)
const filteredData = computed(() => {
    return props.jenisTagihans;
});

onMounted(() => {
    // Initialize bootstrap modal
    const modalEl = document.getElementById('modalJenis');
    if (window.bootstrap) {
        modalInstance.value = new window.bootstrap.Modal(modalEl);
    }
    
    // Fix sidebar z-index when modal opens
    const sidebar = document.getElementById('sidebar');
    if (modalEl && sidebar) {
        modalEl.addEventListener('show.bs.modal', () => {
            sidebar.style.zIndex = '999';
        });
        modalEl.addEventListener('hidden.bs.modal', () => {
            sidebar.style.zIndex = '';
        });
    }
});

const openModal = (item = null) => {
    form.clearErrors();
    form.reset();

    if (item) {
        isEdit.value = true;
        form.id = item.id;
        form.nama = item.nama;
        form.kode = item.kode;
        form.keterangan = item.keterangan;
    } else {
        isEdit.value = false;
    }
    
    modalInstance.value?.show();
};

const hideModal = () => {
    modalInstance.value?.hide();
};

const submit = () => {
    if (isEdit.value) {
        form.put(`/admin/jenis-tagihan/${form.id}`, {
            onSuccess: () => {
                hideModal();
                showToast('success', 'Berhasil!', 'Jenis tagihan berhasil diperbarui.');
            },
            onError: () => {
                showToast('error', 'Gagal!', 'Periksa inputan Anda. Pastikan field yang wajib sudah diisi.');
            }
        });
    } else {
        form.post('/admin/jenis-tagihan', {
            onSuccess: () => {
                hideModal();
                showToast('success', 'Berhasil!', 'Jenis tagihan baru berhasil ditambahkan.');
            },
            onError: () => {
                showToast('error', 'Gagal!', 'Periksa inputan Anda. Pastikan field yang wajib sudah diisi.');
            }
        });
    }
};

const confirmDelete = (id) => {
    Swal.fire({
        title: 'Apakah anda yakin?',
        text: "Data yang dihapus tidak dapat dikembalikan!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Ya, Hapus!'
    }).then((result) => {
        if (result.isConfirmed) {
            router.delete(`/admin/jenis-tagihan/${id}`, {
                onSuccess: () => Swal.fire('Terhapus!', 'Jenis tagihan telah dihapus.', 'success'),
                onError: (errors) => Swal.fire('Gagal!', errors.message || 'Terjadi kesalahan.', 'error')
            });
        }
    });
};
</script>

<style>
/* Global style - fix sidebar appearing above modal backdrop */
.modal.show ~ .sidebar,
body.modal-open .sidebar {
    z-index: 999 !important;
}
</style>

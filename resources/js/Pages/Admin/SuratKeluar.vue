<script setup>
import { ref, computed, watch } from 'vue';
import { Head, Link, router, usePage } from '@inertiajs/vue3';
import AdminLayout from '@/Layouts/AdminLayout.vue';
import Swal from 'sweetalert2';
import { showToast } from '@/Utils/swal';

const props = defineProps({
    surats: Array,
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

// Computed
const computedSurats = computed(() => {
    return props.surats.map((item, index) => ({
        ...item,
        no: index + 1
    }));
});

// Actions
const deleteSurat = async (id) => {
    const result = await Swal.fire({
        title: 'Hapus Arsip Surat?',
        text: 'Surat ini akan dihapus secara permanen.',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#6c757d',
        confirmButtonText: 'Ya, Hapus',
        cancelButtonText: 'Batal'
    });

    if (result.isConfirmed) {
        router.delete(`/admin/surat/${id}`, {
            onSuccess: () => {
                // Flash message watcher will handle the success toast
            }
        });
    }
};

const printSurat = (id) => {
    window.open(`/admin/surat/${id}/print`, '_blank');
};

const formatDate = (dateString) => {
    if (!dateString) return '-';
    return new Date(dateString).toLocaleDateString('id-ID', { 
        year: 'numeric', 
        month: 'long', 
        day: 'numeric'
    });
};

// Flash message watcher
watch(() => page.props.flash, (flash) => {
    if (flash?.success) showSuccess('Sukses', flash.success);
    if (flash?.error) showError('Error', flash.error);
}, { deep: true });
</script>

<template>
    <AdminLayout>
        <Head title="Surat Keluar" />
        


        <div class="content container-fluid">
            <!-- Radiant Header Card (sama dengan DataSiswa) -->
            <div class="card border-0 shadow-lg rounded-4 mb-4 overflow-hidden position-relative" style="background: linear-gradient(135deg, #4f46e5 0%, #3b82f6 100%);">
                <div class="card-body p-4 p-lg-5 position-relative z-1">
                    <div class="row align-items-center">
                        <div class="col-lg-8">
                            <div class="d-flex align-items-center gap-3 mb-2">
                                <div class="icon-box bg-white rounded-3 text-primary p-2">
                                    <i class="fas fa-paper-plane fs-3"></i>
                                </div>
                                <h2 class="fw-bold text-white mb-0 ls-tight">Surat Keluar</h2>
                            </div>
                            <p class="text-white-50 mb-0 fs-5">Arsip surat yang sudah dicetak dan dikirim.</p>
                        </div>
                        <div class="col-lg-4 text-lg-end mt-3 mt-lg-0">
                            <Link href="/admin/surat/create" class="btn btn-light btn-lg rounded-pill shadow-sm px-4 fw-bold text-primary hover-elevate">
                                <i class="fas fa-plus-circle me-2"></i>Buat Surat Baru
                            </Link>
                        </div>
                    </div>
                </div>
                <!-- Decorative Background -->
                <div class="position-absolute bottom-0 end-0 opacity-10 me-n5 mb-n5">
                    <i class="fas fa-paper-plane" style="font-size: 10rem; color: white;"></i>
                </div>
            </div>
            <div class="card modern-table-card">
                <div class="card-header">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h5 class="card-title mb-0">Daftar Surat Keluar</h5>
                            <p class="card-subtitle text-muted mb-0">{{ computedSurats.length }} surat ditemukan</p>
                        </div>
                    </div>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-modern mb-0">
                            <thead>
                                <tr>
                                    <th width="60" class="text-center">No</th>
                                    <th class="text-start">Jenis Surat</th>
                                    <th class="text-start">Kode/Nomor</th>
                                    <th class="text-center">Tanggal Arsip</th>
                                    <th width="140" class="text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-if="computedSurats.length === 0">
                                    <td colspan="5" class="text-center py-5">
                                        <div class="empty-state">
                                            <div class="empty-icon mb-3">
                                                <i class="fas fa-envelope-open fa-3x text-muted"></i>
                                            </div>
                                            <h5 class="mb-2">Belum Ada Surat Keluar</h5>
                                            <p class="text-muted mb-4">Buat dan cetak surat untuk melihatnya di sini</p>
                                            <Link href="/admin/surat/create" class="btn btn-primary">
                                                <i class="fas fa-plus me-1"></i>Buat Surat
                                            </Link>
                                        </div>
                                    </td>
                                </tr>
                                <tr v-for="item in computedSurats" :key="item.id">
                                    <td class="text-center fw-medium text-muted">{{ item.no }}</td>
                                    <td class="text-start fw-semibold">{{ item.jenis_surat }}</td>
                                    <td class="text-start"><span class="badge bg-light text-dark">{{ item.kode_surat || '-' }}</span></td>
                                    <td class="text-center text-muted">{{ formatDate(item.updated_at) }}</td>
                                    <td class="text-center">
                                        <div class="d-flex gap-1 justify-content-center">
                                            <button class="btn-icon-modern btn-icon-view" @click="printSurat(item.id)" title="Cetak PDF">
                                                <i class="fas fa-print"></i>
                                            </button>
                                            <button class="btn-icon-modern btn-icon-delete" @click="deleteSurat(item.id)" title="Hapus">
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
    </AdminLayout>
</template>

<style scoped>
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

.table-modern tbody td {
    padding: 12px 15px;
    vertical-align: middle;
    border-bottom: 1px solid #f1f3f5;
}

.table-modern tbody tr:hover {
    background-color: #f8f9fa;
}

.empty-state {
    padding: 2rem;
    text-align: center;
}

.empty-icon {
    opacity: 0.5;
}
</style>

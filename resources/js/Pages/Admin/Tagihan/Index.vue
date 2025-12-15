<template>
    <AdminLayout>
        <div class="content container-fluid">
            <div class="page-header">
                <div class="row align-items-center">
                    <div class="col">
                        <h3 class="page-title">Daftar Tagihan</h3>
                        <ul class="breadcrumb">
                            <li class="breadcrumb-item"><Link href="/dashboard">Dashboard</Link></li>
                            <li class="breadcrumb-item active">Tagihan</li>
                        </ul>
                    </div>
                    <div class="col-auto">
                        <Link href="/admin/jenis-tagihan" class="btn btn-secondary me-2"><i class="fas fa-list"></i> Jenis Tagihan</Link>
                        <Link href="/admin/tagihan/create" class="btn btn-primary"><i class="fas fa-plus"></i> Buat Tagihan Baru</Link>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-12">
                    <div class="card card-table">
                        <div class="card-body">
                            
                            <!-- Filter & Search -->
                            <div class="row mb-4 p-3">
                                <div class="col-md-3">
                                    <select class="form-control form-select" v-model="filters.jenis_tagihan_id" @change="handleSearch">
                                        <option value="">-- Semua Jenis Tagihan --</option>
                                        <option v-for="j in jenisTagihans" :key="j.id" :value="j.id">{{ j.nama }}</option>
                                    </select>
                                </div>
                                <div class="col-md-3">
                                    <input type="text" class="form-control" placeholder="Target Kelas (Contoh: 10A)" v-model="filters.kelas" @keyup.enter="handleSearch">
                                </div>
                                <div class="col-md-6">
                                    <div class="input-group">
                                        <input type="text" class="form-control" placeholder="Cari Nama Batch..." v-model="filters.search" @keyup.enter="handleSearch">
                                        <button class="btn btn-outline-secondary" type="button" @click="handleSearch"><i class="fas fa-search"></i></button>
                                    </div>
                                </div>
                            </div>

                            <div class="table-responsive">
                                <table class="table table-hover table-center mb-0 datatable">
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>Nama Batch</th>
                                            <th>Jenis Tagihan</th>
                                            <th>Target Kelas</th>
                                            <th>Target Gender</th>
                                            <th>Jumlah Tagihan</th>
                                            <th>Total Siswa</th>
                                            <th>Tenggat Waktu</th>
                                            <th class="text-end">Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr v-for="(batch, index) in batches.data" :key="batch.id">
                                            <td>
                                                {{ (batches.current_page - 1) * batches.per_page + index + 1 }}
                                            </td>
                                            <td>
                                                <h2 class="table-avatar">
                                                    <a href="#">{{ batch.nama_tagihan }}</a>
                                                </h2>
                                            </td>
                                            <td>{{ batch.jenis_tagihan?.nama || '-' }}</td>
                                            <td>{{ batch.target_kelas || 'Semua' }}</td>
                                            <td>{{ batch.target_gender || 'Semua' }}</td>
                                            <td>{{ formatRupiah(batch.jumlah) }}</td>
                                            <td>
                                                <span class="badge bg-info">{{ batch.tagihans_count }} Siswa</span>
                                            </td>
                                            <td>
                                                {{ formatDate(batch.tenggat_waktu) }}
                                            </td>
                                            <td class="text-end">
                                                <div class="actions">
                                                    <!-- Currently only delete -->
                                                    <!-- Can add detail view later to list students in this batch -->
                                                    <button class="btn btn-sm bg-danger-light" @click="confirmDelete(batch.id)">
                                                        <i class="feather-trash-2"></i> Hapus
                                                    </button>
                                                </div>
                                            </td>
                                        </tr>
                                        <tr v-if="batches.data.length === 0">
                                            <td colspan="9" class="text-center">Tidak ada data tagihan batch ditemukan.</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>

                             <!-- Pagination -->
                             <div class="row mt-4" v-if="batches.links.length > 3">
                                <div class="col-md-12">
                                    <div class="pagination-tab d-flex justify-content-end">
                                        <ul class="pagination mb-0">
                                            <li class="page-item" :class="{ disabled: !link.url, active: link.active }" v-for="(link, index) in batches.links" :key="index">
                                                <Link class="page-link" :href="link.url || '#'" v-html="link.label"></Link>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AdminLayout>
</template>

<script setup>
import { ref } from 'vue';
import { Link, router } from '@inertiajs/vue3';
import AdminLayout from '@/Layouts/AdminLayout.vue';
import Swal from 'sweetalert2';

const props = defineProps({
    batches: Object,
    filters: Object,
    kelasList: Array, // Usually unused for batches if we just show strings, but kept for consistency
    jenisTagihans: Array
});

const filters = ref({
    search: props.filters?.search || '',
    kelas: props.filters?.kelas || '',
    jenis_tagihan_id: props.filters?.jenis_tagihan_id || ''
});

const handleSearch = () => {
    router.get('/admin/tagihan', filters.value, { preserveState: true, replace: true });
};

const formatRupiah = (value) => {
    return new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR', minimumFractionDigits: 0 }).format(value);
};

const formatDate = (dateString) => {
    if (!dateString) return '-';
    return new Date(dateString).toLocaleDateString('id-ID');
};

const confirmDelete = (id) => {
    Swal.fire({
        title: 'Hapus Batch Tagihan?',
        text: "Semua tagihan siswa dalam batch ini akan ikut terhapus! Aksi ini tidak dapat dibatalkan.",
        icon: 'item',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'Ya, Hapus Semua!'
    }).then((result) => {
        if (result.isConfirmed) {
            router.delete(`/admin/tagihan/${id}`, {
                onSuccess: () => Swal.fire('Terhapus!', 'Batch tagihan dan seluruh itemnya berhasil dihapus.', 'success'),
                onError: () => Swal.fire('Gagal!', 'Terjadi kesalahan saat menghapus data.', 'error')
            });
        }
    });
};
</script>

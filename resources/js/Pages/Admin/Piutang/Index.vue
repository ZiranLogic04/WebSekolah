<template>
    <AdminLayout>
        <div class="content container-fluid">
            <div class="page-header">
                <div class="row align-items-center">
                    <div class="col">
                        <h3 class="page-title">Daftar Piutang Siswa</h3>
                        <ul class="breadcrumb">
                            <li class="breadcrumb-item"><Link href="/dashboard">Dashboard</Link></li>
                            <li class="breadcrumb-item active">Piutang</li>
                        </ul>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-12">
                    <div class="card card-table">
                        <div class="card-body">
                            
                            <!-- Filter & Search -->
                            <div class="row mb-4 p-3">
                                <div class="col-md-2">
                                    <select class="form-control form-select" v-model="filters.kelas" @change="handleSearch">
                                        <option value="">-- Semua Kelas --</option>
                                        <option v-for="k in kelasList" :key="k" :value="k">{{ k }}</option>
                                    </select>
                                </div>
                                <div class="col-md-3">
                                    <select class="form-control form-select" v-model="filters.jenis_tagihan_id" @change="handleSearch">
                                        <option value="">-- Semua Jenis Tagihan --</option>
                                        <option v-for="j in jenisTagihans" :key="j.id" :value="j.id">{{ j.nama }}</option>
                                    </select>
                                </div>
                                <div class="col-md-5">
                                    <div class="input-group">
                                        <input type="text" class="form-control" placeholder="Cari Nama / NIS..." v-model="filters.search" @keyup.enter="handleSearch">
                                        <button class="btn btn-outline-secondary" type="button" @click="handleSearch"><i class="fas fa-search"></i></button>
                                    </div>
                                </div>
                            </div>

                            <div class="table-responsive">
                                <table class="table table-hover table-center mb-0 datatable">
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>Siswa</th>
                                            <th>NIS</th>
                                            <th>Kelas</th>
                                            <th>Jenis Tagihan</th>
                                            <th>Jumlah</th>
                                            <th>Status</th>
                                            <th>Jatuh Tempo</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr v-for="(tagihan, index) in tagihans.data" :key="tagihan.id">
                                            <td>
                                                {{ (tagihans.current_page - 1) * tagihans.per_page + index + 1 }}
                                            </td>
                                            <td>
                                                <h2 class="table-avatar">
                                                    <a href="#">{{ tagihan.siswa?.nama || 'Hapus Siswa' }}</a>
                                                </h2>
                                            </td>
                                            <td>{{ tagihan.siswa?.nis || '-' }}</td>
                                            <td>{{ tagihan.siswa?.kelas || '-' }}</td>
                                            <td>{{ tagihan.nama_tagihan }}</td>
                                            <td>{{ formatRupiah(tagihan.jumlah) }}</td>
                                            <td>
                                                <span class="badge" :class="tagihan.status === 'Lunas' ? 'bg-success' : 'bg-danger'">
                                                    {{ tagihan.status }}
                                                </span>
                                            </td>
                                            <td>
                                                <span :class="{'text-danger fw-bold': isOverdue(tagihan.tenggat_waktu) && tagihan.status !== 'Lunas'}">
                                                    {{ formatDate(tagihan.tenggat_waktu) }}
                                                </span>
                                            </td>
                                        </tr>
                                        <tr v-if="tagihans.data.length === 0">
                                            <td colspan="8" class="text-center">Tidak ada data piutang ditemukan.</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>

                             <!-- Pagination -->
                             <div class="row mt-4" v-if="tagihans.links.length > 3">
                                <div class="col-md-12">
                                    <div class="pagination-tab d-flex justify-content-end">
                                        <ul class="pagination mb-0">
                                            <li class="page-item" :class="{ disabled: !link.url, active: link.active }" v-for="(link, index) in tagihans.links" :key="index">
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

const props = defineProps({
    tagihans: Object,
    filters: Object,
    kelasList: Array,
    jenisTagihans: Array
});

const filters = ref({
    search: props.filters?.search || '',
    kelas: props.filters?.kelas || '',
    jenis_tagihan_id: props.filters?.jenis_tagihan_id || ''
});

const handleSearch = () => {
    router.get('/admin/piutang', filters.value, { preserveState: true, replace: true });
};

const formatRupiah = (value) => {
    return new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR', minimumFractionDigits: 0 }).format(value);
};

const formatDate = (dateString) => {
    if (!dateString) return '-';
    return new Date(dateString).toLocaleDateString('id-ID');
};

const isOverdue = (dateString) => {
    if (!dateString) return false;
    return new Date(dateString) < new Date();
};
</script>

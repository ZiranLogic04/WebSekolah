<template>
    <AdminLayout>
        <div class="content container-fluid">
            <!-- Header -->
            <div class="page-header">
                <div class="row align-items-center">
                    <div class="col">
                        <h3 class="page-title">Kartu Keuangan Siswa</h3>
                        <ul class="breadcrumb">
                            <li class="breadcrumb-item"><Link href="/admin/siswa">Data Siswa</Link></li>
                            <li class="breadcrumb-item active">Kartu Keuangan</li>
                        </ul>
                    </div>
                    <div class="col-auto">
                        <Link href="/admin/siswa" class="btn btn-secondary">
                            <i class="fas fa-arrow-left me-1"></i> Kembali
                        </Link>
                    </div>
                </div>
            </div>

            <div class="row">
                <!-- Bio Card -->
                <div class="col-md-4">
                    <div class="card">
                        <div class="card-body text-center">
                            <div class="mb-3">
                                <div class="avatar avatar-xl">
                                    <span class="avatar-title rounded-circle bg-primary text-white fs-2">
                                        {{ siswa.nama.charAt(0) }}
                                    </span>
                                </div>
                            </div>
                            <h4 class="mb-1">{{ siswa.nama }}</h4>
                            <p class="text-muted mb-1">{{ siswa.nis }}</p>
                            <span class="badge bg-soft-primary text-primary mb-3">{{ siswa.kelas }}</span>
                            
                            <hr>
                            <div class="text-start">
                                <div class="d-flex justify-content-between mb-2">
                                    <span>Status:</span>
                                    <span class="badge" :class="getStatusClass(siswa.status)">{{ siswa.status }}</span>
                                </div>
                                <div class="d-flex justify-content-between mb-2">
                                    <span>Gender:</span>
                                    <span>{{ siswa.jenis_kelamin === 'L' ? 'Laki-Laki' : 'Perempuan' }}</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Summary Card -->
                    <div class="card bg-primary text-white">
                        <div class="card-body">
                            <h5 class="card-title text-white mb-4">Ringkasan Keuangan</h5>
                            <div class="row text-center">
                                <div class="col-6 mb-3">
                                    <small class="text-light opacity-75">Total Tagihan</small>
                                    <h5 class="mb-0 text-white">{{ formatRupiah(summary.total) }}</h5>
                                </div>
                                <div class="col-6 mb-3">
                                    <small class="text-light opacity-75">Terbayar</small>
                                    <h5 class="mb-0 text-white">{{ formatRupiah(summary.paid) }}</h5>
                                </div>
                                <div class="col-12 mt-2 pt-2 border-top border-white-50">
                                    <small class="text-light opacity-75">Sisa Tunggakan</small>
                                    <h3 class="mb-0 text-white fw-bold">{{ formatRupiah(summary.debt) }}</h3>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Ledger Table -->
                <div class="col-md-8">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="card-title mb-0">Riwayat Tagihan & Pembayaran (Semua Periode)</h5>
                        </div>
                        <div class="card-body p-0">
                            <div class="table-responsive">
                                <table class="table table-hover table-center mb-0">
                                    <thead class="table-light">
                                        <tr>
                                            <th>Tanggal Tagihan</th>
                                            <th>Keterangan</th>
                                            <th class="text-end">Jumlah</th>
                                            <th class="text-end">Sisa</th>
                                            <th class="text-center">Status</th>
                                            <th width="50"></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <template v-for="item in tagihan" :key="item.id">
                                            <tr class="cursor-pointer" @click="toggleRow(item.id)">
                                                <td>{{ formatDate(item.created_at) }}</td>
                                                <td>
                                                    <div class="fw-bold">{{ item.nama_tagihan }}</div>
                                                    <small class="text-muted">{{ item.jenis_tagihan?.nama }}</small>
                                                </td>
                                                <td class="text-end">{{ formatRupiah(item.jumlah) }}</td>
                                                <td class="text-end fw-bold" :class="item.sisa > 0 ? 'text-danger' : 'text-success'">
                                                    {{ formatRupiah(item.sisa) }}
                                                </td>
                                                <td class="text-center">
                                                    <span class="badge" :class="item.sisa <= 0 ? 'bg-success' : 'bg-warning'">
                                                        {{ item.sisa <= 0 ? 'Lunas' : 'Belum Lunas' }}
                                                    </span>
                                                </td>
                                                <td>
                                                    <i class="fas" :class="expandedRows.includes(item.id) ? 'fa-chevron-up' : 'fa-chevron-down'"></i>
                                                </td>
                                            </tr>
                                            <!-- Expanded Detail (Payment History) -->
                                            <tr v-if="expandedRows.includes(item.id)" class="bg-light">
                                                <td colspan="6" class="p-3">
                                                    <div v-if="item.transaksis && item.transaksis.length > 0">
                                                        <h6 class="text-muted mb-2 ps-2 border-start border-3 border-primary">Riwayat Pembayaran</h6>
                                                        <table class="table table-sm table-bordered mb-0 bg-white">
                                                            <thead>
                                                                <tr>
                                                                    <th>Tgl Bayar</th>
                                                                    <th>Kode Transaksi</th>
                                                                    <th class="text-end">Nominal Bayar</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody>
                                                                <tr v-for="trx in item.transaksis" :key="trx.id">
                                                                    <td>{{ formatDate(trx.tanggal_bayar) }}</td>
                                                                    <td>{{ trx.kode_transaksi }}</td>
                                                                    <td class="text-end text-success fw-bold">{{ formatRupiah(trx.jumlah_bayar) }}</td>
                                                                </tr>
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                    <div v-else class="text-center text-muted py-2">
                                                        <em>Belum ada pembayaran untuk tagihan ini.</em>
                                                    </div>
                                                </td>
                                            </tr>
                                        </template>
                                        <tr v-if="tagihan.length === 0">
                                            <td colspan="6" class="text-center py-5">
                                                <i class="fas fa-file-invoice-dollar fa-3x text-muted mb-3"></i>
                                                <p>Tidak ada riwayat tagihan.</p>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AdminLayout>
</template>

<script setup>
import { ref, computed } from 'vue';
import { Link } from '@inertiajs/vue3';
import AdminLayout from '@/Layouts/AdminLayout.vue';

const props = defineProps({
    siswa: Object,
    tagihan: Array
});

const expandedRows = ref([]);

const toggleRow = (id) => {
    if (expandedRows.value.includes(id)) {
        expandedRows.value = expandedRows.value.filter(rowId => rowId !== id);
    } else {
        expandedRows.value.push(id);
    }
};

const summary = computed(() => {
    let total = 0;
    let paid = 0;
    let debt = 0;
    
    props.tagihan.forEach(item => {
        total += parseFloat(item.jumlah);
        paid += parseFloat(item.terbayar);
        debt += parseFloat(item.sisa);
    });

    return { total, paid, debt };
});

const formatRupiah = (val) => {
    return new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR', minimumFractionDigits: 0 }).format(val);
};

const formatDate = (date) => {
    if (!date) return '-';
    return new Date(date).toLocaleDateString('id-ID', { day: 'numeric', month: 'short', year: 'numeric' });
};

const getStatusClass = (status) => {
    switch(status) {
        case 'Aktif': return 'bg-success';
        case 'Non-Aktif': return 'bg-danger';
        case 'Pindah': return 'bg-warning';
        case 'Lulus': return 'bg-info';
        default: return 'bg-secondary';
    }
};
</script>

<style scoped>
.cursor-pointer {
    cursor: pointer;
}
</style>

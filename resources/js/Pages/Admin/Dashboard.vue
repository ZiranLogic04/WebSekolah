<template>
    <AdminLayout>
        <div class="content container-fluid">
            <div class="page-header">
                <div class="row">
                    <div class="col-sm-12">
                        <div class="page-sub-header">
                            <h3 class="page-title">Selamat Datang, {{ $page.props.auth?.user?.name || 'Admin' }}!</h3>
                            <ul class="breadcrumb">
                                <li class="breadcrumb-item">
                                    <Link href="/admin/dashboard">Beranda</Link>
                                </li>
                                <li class="breadcrumb-item active">Dashboard</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Statistik Utama -->
            <div class="row">
                <div class="col-xl-3 col-sm-6 col-12 d-flex">
                    <div class="card bg-comman w-100">
                        <div class="card-body">
                            <div class="db-widgets d-flex justify-content-between align-items-center">
                                <div class="db-info">
                                    <h6>Total Siswa</h6>
                                    <h3>{{ formatNumber(stats.totalSiswa) }}</h3>
                                </div>
                                <div class="db-icon">
                                    <img src="/assets/img/icons/dash-icon-01.svg" alt="Ikon Siswa" />
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-3 col-sm-6 col-12 d-flex">
                    <div class="card bg-comman w-100">
                        <div class="card-body">
                            <div class="db-widgets d-flex justify-content-between align-items-center">
                                <div class="db-info">
                                    <h6>Total Guru</h6>
                                    <h3>{{ formatNumber(stats.totalGuru) }}</h3>
                                </div>
                                <div class="db-icon">
                                    <img src="/assets/img/icons/dash-icon-02.svg" alt="Ikon Guru" />
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-3 col-sm-6 col-12 d-flex">
                    <div class="card bg-comman w-100">
                        <div class="card-body">
                            <div class="db-widgets d-flex justify-content-between align-items-center">
                                <div class="db-info">
                                    <h6>Total Berita</h6>
                                    <h3>{{ formatNumber(stats.totalPost) }}</h3>
                                </div>
                                <div class="db-icon">
                                    <img src="/assets/img/icons/dash-icon-03.svg" alt="Ikon Berita" />
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-3 col-sm-6 col-12 d-flex">
                    <div class="card bg-comman w-100">
                        <div class="card-body">
                            <div class="db-widgets d-flex justify-content-between align-items-center">
                                <div class="db-info">
                                    <h6>Saldo Keuangan</h6>
                                    <h3>{{ formatRupiah(stats.saldoKeuangan) }}</h3>
                                </div>
                                <div class="db-icon">
                                    <img src="/assets/img/icons/dash-icon-04.svg" alt="Ikon Keuangan" />
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Statistik Keuangan & Surat -->
            <div class="row">
                <div class="col-xl-3 col-sm-6 col-12 d-flex">
                    <div class="card bg-comman w-100" style="border-left: 4px solid #28a745;">
                        <div class="card-body">
                            <div class="db-widgets d-flex justify-content-between align-items-center">
                                <div class="db-info">
                                    <h6>Total Pemasukan</h6>
                                    <h3 class="text-success">{{ formatRupiah(stats.totalPemasukan) }}</h3>
                                </div>
                                <div class="db-icon">
                                    <i class="fas fa-arrow-down fa-2x text-success"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-3 col-sm-6 col-12 d-flex">
                    <div class="card bg-comman w-100" style="border-left: 4px solid #dc3545;">
                        <div class="card-body">
                            <div class="db-widgets d-flex justify-content-between align-items-center">
                                <div class="db-info">
                                    <h6>Total Pengeluaran</h6>
                                    <h3 class="text-danger">{{ formatRupiah(stats.totalPengeluaran) }}</h3>
                                </div>
                                <div class="db-icon">
                                    <i class="fas fa-arrow-up fa-2x text-danger"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-3 col-sm-6 col-12 d-flex">
                    <div class="card bg-comman w-100" style="border-left: 4px solid #17a2b8;">
                        <div class="card-body">
                            <div class="db-widgets d-flex justify-content-between align-items-center">
                                <div class="db-info">
                                    <h6>Tagihan Lunas</h6>
                                    <h3 class="text-info">{{ formatNumber(stats.tagihanLunas) }}</h3>
                                </div>
                                <div class="db-icon">
                                    <i class="fas fa-check-circle fa-2x text-info"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-3 col-sm-6 col-12 d-flex">
                    <div class="card bg-comman w-100" style="border-left: 4px solid #ffc107;">
                        <div class="card-body">
                            <div class="db-widgets d-flex justify-content-between align-items-center">
                                <div class="db-info">
                                    <h6>Tagihan Belum Lunas</h6>
                                    <h3 class="text-warning">{{ formatNumber(stats.tagihanBelumLunas) }}</h3>
                                </div>
                                <div class="db-icon">
                                    <i class="fas fa-exclamation-triangle fa-2x text-warning"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Statistik Surat -->
            <div class="row">
                <div class="col-xl-6 col-sm-6 col-12 d-flex">
                    <div class="card bg-comman w-100">
                        <div class="card-body">
                            <div class="db-widgets d-flex justify-content-between align-items-center">
                                <div class="db-info">
                                    <h6>Total Surat Masuk</h6>
                                    <h3>{{ formatNumber(stats.totalSuratMasuk) }}</h3>
                                </div>
                                <div class="db-icon">
                                    <i class="fas fa-envelope-open fa-2x text-primary"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-6 col-sm-6 col-12 d-flex">
                    <div class="card bg-comman w-100">
                        <div class="card-body">
                            <div class="db-widgets d-flex justify-content-between align-items-center">
                                <div class="db-info">
                                    <h6>Total Surat Keluar</h6>
                                    <h3>{{ formatNumber(stats.totalSuratKeluar) }}</h3>
                                </div>
                                <div class="db-icon">
                                    <i class="fas fa-paper-plane fa-2x text-secondary"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Tabel Data Terbaru -->
            <div class="row">
                <!-- Berita Terbaru -->
                <div class="col-xl-6 d-flex">
                    <div class="card flex-fill student-space comman-shadow">
                        <div class="card-header d-flex align-items-center justify-content-between">
                            <h5 class="card-title mb-0">Berita Terbaru</h5>
                            <Link href="/admin/posts" class="btn btn-sm btn-outline-primary">
                                Lihat Semua
                            </Link>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table star-student table-hover table-center table-borderless table-striped">
                                    <thead class="thead-light">
                                        <tr>
                                            <th>Judul</th>
                                            <th class="text-end">Tanggal</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr v-for="post in postTerbaru" :key="post.id">
                                            <td class="text-nowrap">
                                                <Link :href="`/berita/${post.slug}`" target="_blank">
                                                    {{ truncate(post.title, 40) }}
                                                </Link>
                                            </td>
                                            <td class="text-end">
                                                {{ formatDate(post.created_at) }}
                                            </td>
                                        </tr>
                                        <tr v-if="postTerbaru.length === 0">
                                            <td colspan="2" class="text-center text-muted">
                                                Belum ada berita
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Surat Masuk Terbaru -->
                <div class="col-xl-6 d-flex">
                    <div class="card flex-fill comman-shadow">
                        <div class="card-header d-flex align-items-center justify-content-between">
                            <h5 class="card-title mb-0">Surat Masuk Terbaru</h5>
                            <Link href="/admin/surat-masuk" class="btn btn-sm btn-outline-primary">
                                Lihat Semua
                            </Link>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-hover table-center table-borderless table-striped">
                                    <thead class="thead-light">
                                        <tr>
                                            <th>No. Surat</th>
                                            <th>Perihal</th>
                                            <th class="text-end">Tanggal</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr v-for="surat in suratMasukTerbaru" :key="surat.id">
                                            <td class="text-nowrap">{{ surat.nomor_surat }}</td>
                                            <td>{{ truncate(surat.perihal, 30) }}</td>
                                            <td class="text-end">{{ formatDate(surat.tgl_surat) }}</td>
                                        </tr>
                                        <tr v-if="suratMasukTerbaru.length === 0">
                                            <td colspan="3" class="text-center text-muted">
                                                Belum ada surat masuk
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Transaksi Terbaru -->
            <div class="row">
                <div class="col-12">
                    <div class="card flex-fill comman-shadow">
                        <div class="card-header d-flex align-items-center justify-content-between">
                            <h5 class="card-title mb-0">Transaksi Terbaru</h5>
                            <Link href="/admin/transaksi" class="btn btn-sm btn-outline-primary">
                                Lihat Semua
                            </Link>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-hover table-center table-borderless table-striped">
                                    <thead class="thead-light">
                                        <tr>
                                            <th>Kode Transaksi</th>
                                            <th>Siswa</th>
                                            <th>Jumlah Bayar</th>
                                            <th>Metode</th>
                                            <th class="text-end">Tanggal</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr v-for="trx in transaksiTerbaru" :key="trx.id">
                                            <td>{{ trx.kode_transaksi || '-' }}</td>
                                            <td>{{ trx.tagihan?.siswa?.nama || '-' }}</td>
                                            <td>
                                                <span class="badge bg-success">
                                                    {{ formatRupiah(trx.jumlah_bayar) }}
                                                </span>
                                            </td>
                                            <td>{{ trx.metode_pembayaran || '-' }}</td>
                                            <td class="text-end">{{ formatDate(trx.tanggal_bayar || trx.created_at) }}</td>
                                        </tr>
                                        <tr v-if="transaksiTerbaru.length === 0">
                                            <td colspan="5" class="text-center text-muted">
                                                Belum ada transaksi
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
import { Link, usePage } from "@inertiajs/vue3";
import AdminLayout from "@/Layouts/AdminLayout.vue";

const props = defineProps({
    stats: {
        type: Object,
        default: () => ({
            totalSiswa: 0,
            totalGuru: 0,
            totalUser: 0,
            totalPost: 0,
            totalPemasukan: 0,
            totalPengeluaran: 0,
            saldoKeuangan: 0,
            tagihanLunas: 0,
            tagihanBelumLunas: 0,
            totalSuratMasuk: 0,
            totalSuratKeluar: 0,
        })
    },
    postTerbaru: {
        type: Array,
        default: () => []
    },
    suratMasukTerbaru: {
        type: Array,
        default: () => []
    },
    transaksiTerbaru: {
        type: Array,
        default: () => []
    }
});

// Format angka dengan pemisah ribuan
const formatNumber = (num) => {
    if (!num) return '0';
    return num.toLocaleString('id-ID');
};

// Format ke Rupiah
const formatRupiah = (num) => {
    if (num === undefined || num === null) return 'Rp 0';
    return 'Rp ' + num.toLocaleString('id-ID');
};

// Format tanggal
const formatDate = (dateStr) => {
    if (!dateStr) return '-';
    const date = new Date(dateStr);
    return date.toLocaleDateString('id-ID', {
        day: 'numeric',
        month: 'short',
        year: 'numeric'
    });
};

// Truncate text
const truncate = (text, length) => {
    if (!text) return '';
    if (text.length <= length) return text;
    return text.substring(0, length) + '...';
};
</script>

<style scoped>
.card {
    transition: transform 0.2s ease, box-shadow 0.2s ease;
}

.card:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
}

.db-info h3 {
    font-size: 1.5rem;
    font-weight: 700;
    margin-bottom: 0;
}

.db-info h6 {
    color: #6c757d;
    margin-bottom: 0.5rem;
    font-size: 0.85rem;
}

.badge {
    padding: 0.4em 0.8em;
    font-size: 0.75rem;
}

.table td {
    vertical-align: middle;
}

.card-header {
    justify-content: space-between;
}
</style>

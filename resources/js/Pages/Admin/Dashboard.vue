<template>
    <AdminLayout>
        <div class="content container-fluid">
            <!-- Header Section -->
            <div class="page-header mb-5">
                <div class="row align-items-center justify-content-between p-4 bg-white rounded-4 shadow-sm">
                    <div class="col-auto">
                        <div class="d-flex align-items-center gap-3">
                            <div class="bg-primary rounded-3 d-flex align-items-center justify-content-center" style="width: 48px; height: 48px;">
                                <i class="fas fa-home fa-lg text-white"></i>
                            </div>
                            <div>
                                <h3 class="page-title fw-bold text-dark mb-0" style="font-size: 1.5rem;">
                                     Dashboard
                                </h3>
                                <p class="text-muted mb-0 small">Ringkasan statistik & akses cepat</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-auto">
                        <span class="badge bg-light text-dark px-3 py-2 border rounded-pill d-flex align-items-center gap-2">
                            <i class="far fa-calendar-alt text-primary"></i> 
                            <span class="fw-bold">{{ currentDate }}</span>
                        </span>
                    </div>
                </div>
            </div>

            <!-- Shortcut Akses Cepat -->
            <div class="mb-4">
                 <div class="d-flex align-items-center border-bottom pb-2 mb-3">
                    <div class="bg-primary rounded-pill me-3" style="width: 5px; height: 24px;"></div>
                    <h5 class="fw-bold text-dark mb-0">Akses Cepat</h5>
                 </div>
            
                <div class="row">
                    <!-- Tambah Siswa -->
                    <div class="col-xl-3 col-sm-6 col-12 mb-3">
                        <Link href="/admin/siswa/create" class="shortcut-card card h-100 border-0 shadow hover-elevate overflow-hidden" style="background: linear-gradient(120deg, #4f46e5, #3b82f6);">
                            <div class="card-body p-4 position-relative">
                                <div class="d-flex justify-content-between align-items-start position-relative z-1">
                                    <div>
                                        <h6 class="text-white-50 mb-1 text-uppercase small ls-1">Siswa</h6>
                                        <h4 class="fw-bold text-white mb-0">Tambah Baru</h4>
                                    </div>
                                    <div class="icon-circle bg-white text-primary rounded-circle d-flex align-items-center justify-content-center" style="width: 45px; height: 45px;">
                                        <i class="fas fa-user-plus fa-lg"></i>
                                    </div>
                                </div>
                                <div class="position-absolute bottom-0 end-0 opacity-10" style="transform: translate(20%, 20%);">
                                    <i class="fas fa-user-plus fa-8x text-white"></i>
                                </div>
                            </div>
                        </Link>
                    </div>
                    <!-- Buat Berita -->
                    <div class="col-xl-3 col-sm-6 col-12 mb-3">
                        <Link href="/admin/posts/create" class="shortcut-card card h-100 border-0 shadow hover-elevate overflow-hidden" style="background: linear-gradient(120deg, #059669, #10b981);">
                            <div class="card-body p-4 position-relative">
                                <div class="d-flex justify-content-between align-items-start position-relative z-1">
                                    <div>
                                        <h6 class="text-white-50 mb-1 text-uppercase small ls-1">Berita</h6>
                                        <h4 class="fw-bold text-white mb-0">Tulis Artikel</h4>
                                    </div>
                                    <div class="icon-circle bg-white text-success rounded-circle d-flex align-items-center justify-content-center" style="width: 45px; height: 45px;">
                                        <i class="fas fa-pen-nib fa-lg"></i>
                                    </div>
                                </div>
                                <div class="position-absolute bottom-0 end-0 opacity-10" style="transform: translate(20%, 20%);">
                                    <i class="fas fa-pen-nib fa-8x text-white"></i>
                                </div>
                            </div>
                        </Link>
                    </div>
                    <!-- Catat Transaksi -->
                    <div class="col-xl-3 col-sm-6 col-12 mb-3">
                        <Link href="/admin/transaksi/create" class="shortcut-card card h-100 border-0 shadow hover-elevate overflow-hidden" style="background: linear-gradient(120deg, #d97706, #f59e0b);">
                            <div class="card-body p-4 position-relative">
                                <div class="d-flex justify-content-between align-items-start position-relative z-1">
                                    <div>
                                        <h6 class="text-white-50 mb-1 text-uppercase small ls-1">Keuangan</h6>
                                        <h4 class="fw-bold text-white mb-0">Input Bayar</h4>
                                    </div>
                                    <div class="icon-circle bg-white text-warning rounded-circle d-flex align-items-center justify-content-center" style="width: 45px; height: 45px;">
                                        <i class="fas fa-cash-register fa-lg"></i>
                                    </div>
                                </div>
                                <div class="position-absolute bottom-0 end-0 opacity-10" style="transform: translate(20%, 20%);">
                                    <i class="fas fa-cash-register fa-8x text-white"></i>
                                </div>
                            </div>
                        </Link>
                    </div>
                    <!-- Buat Surat Masuk -->
                    <div class="col-xl-3 col-sm-6 col-12 mb-3">
                        <Link href="/admin/surat-masuk/create" class="shortcut-card card h-100 border-0 shadow hover-elevate overflow-hidden" style="background: linear-gradient(120deg, #0284c7, #0ea5e9);">
                            <div class="card-body p-4 position-relative">
                                <div class="d-flex justify-content-between align-items-start position-relative z-1">
                                    <div>
                                        <h6 class="text-white-50 mb-1 text-uppercase small ls-1">Administrasi</h6>
                                        <h4 class="fw-bold text-white mb-0">Surat Masuk</h4>
                                    </div>
                                    <div class="icon-circle bg-white text-info rounded-circle d-flex align-items-center justify-content-center" style="width: 45px; height: 45px;">
                                        <i class="fas fa-envelope-open-text fa-lg"></i>
                                    </div>
                                </div>
                                <div class="position-absolute bottom-0 end-0 opacity-10" style="transform: translate(20%, 20%);">
                                    <i class="fas fa-envelope-open-text fa-8x text-white"></i>
                                </div>
                            </div>
                        </Link>
                    </div>
                </div>
            </div>

            <!-- Statistik Utama -->
            <div class="mb-4">
                 <div class="d-flex align-items-center border-bottom pb-2 mb-3">
                    <div class="bg-primary rounded-pill me-3" style="width: 5px; height: 24px;"></div>
                    <h5 class="fw-bold text-dark mb-0">Ringkasan Data</h5>
                 </div>

                <div class="row g-4">
                    <!-- Siswa -->
                    <div class="col-xl-3 col-sm-6 col-12">
                        <div class="stat-card card h-100 border-0 shadow-sm">
                            <div class="card-body p-4">
                                <div class="d-flex justify-content-between align-items-start">
                                    <div>
                                        <p class="text-muted mb-1 small fw-bold text-uppercase">Total Siswa</p>
                                        <h3 class="fw-bold text-dark mb-0">{{ formatNumber(stats.totalSiswa) }}</h3>
                                    </div>
                                    <div class="stat-icon bg-light-primary text-primary">
                                        <i class="fas fa-user-graduate"></i>
                                    </div>
                                </div>
                                <div class="mt-3 pt-3 border-top border-light">
                                    <span class="badge bg-light-success text-success rounded-pill px-2">Aktif</span>
                                    <span class="text-muted small ms-2">Terdaftar di sistem</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- Guru -->
                    <div class="col-xl-3 col-sm-6 col-12">
                        <div class="stat-card card h-100 border-0 shadow-sm">
                            <div class="card-body p-4">
                                <div class="d-flex justify-content-between align-items-start">
                                    <div>
                                        <p class="text-muted mb-1 small fw-bold text-uppercase">Tenaga Pendidik</p>
                                        <h3 class="fw-bold text-dark mb-0">{{ formatNumber(stats.totalGuru) }}</h3>
                                    </div>
                                    <div class="stat-icon bg-light-danger text-danger">
                                        <i class="fas fa-chalkboard-teacher"></i>
                                    </div>
                                </div>
                                <div class="mt-3 pt-3 border-top border-light">
                                    <Link href="/admin/guru" class="text-decoration-none text-muted small hover-link">
                                        Lihat Data Guru <i class="fas fa-arrow-right ms-1 small"></i>
                                    </Link>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- Berita -->
                    <div class="col-xl-3 col-sm-6 col-12">
                        <div class="stat-card card h-100 border-0 shadow-sm">
                            <div class="card-body p-4">
                                <div class="d-flex justify-content-between align-items-start">
                                    <div>
                                        <p class="text-muted mb-1 small fw-bold text-uppercase">Artikel & Berita</p>
                                        <h3 class="fw-bold text-dark mb-0">{{ formatNumber(stats.totalPost) }}</h3>
                                    </div>
                                    <div class="stat-icon bg-light-info text-info">
                                        <i class="far fa-newspaper"></i>
                                    </div>
                                </div>
                                <div class="mt-3 pt-3 border-top border-light">
                                    <span class="badge bg-light-success text-success rounded-pill px-2">Published</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- Saldo -->
                    <div class="col-xl-3 col-sm-6 col-12">
                        <div class="stat-card card h-100 border-0 shadow-sm">
                            <div class="card-body p-4">
                                <div class="d-flex justify-content-between align-items-start">
                                    <div>
                                        <p class="text-muted mb-1 small fw-bold text-uppercase">Saldo Keuangan</p>
                                        <h4 class="fw-bold text-success mb-0">{{ formatRupiah(stats.saldoKeuangan) }}</h4>
                                    </div>
                                    <div class="stat-icon bg-light-success text-success">
                                        <i class="fas fa-sack-dollar"></i>
                                    </div>
                                </div>
                                <div class="mt-3 pt-3 border-top border-light">
                                    <small class="text-muted">Total Pemasukan Bersih</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Keuangan Lanjutan & Transaksi -->
            <div class="mb-4">
                 <div class="d-flex align-items-center border-bottom pb-2 mb-3">
                    <div class="bg-primary rounded-pill me-3" style="width: 5px; height: 24px;"></div>
                    <h5 class="fw-bold text-dark mb-0">Detail Keuangan & Transaksi</h5>
                 </div>

                <div class="row g-4 mb-5">
                    <div class="col-xl-6 col-12">
                        <div class="card border-0 shadow-sm h-100 rounded-4 overflow-hidden">
                            <div class="card-header bg-white border-0 py-3 px-4 d-flex justify-content-between align-items-center">
                                <h6 class="fw-bold mb-0 text-dark"><i class="fas fa-wallet me-2 text-warning"></i> Ringkasan Keuangan</h6>
                            </div>
                            <div class="card-body p-4 pt-2">
                               <div class="row g-3">
                                    <div class="col-md-6">
                                        <div class="p-3 bg-light rounded-3 border border-light">
                                            <div class="d-flex align-items-center mb-2">
                                                <div class="badge rounded-circle bg-success p-1 me-2"><i class="fas fa-arrow-down small text-white"></i></div>
                                                <small class="text-muted fw-bold">Pemasukan</small>
                                            </div>
                                            <h5 class="fw-bold text-dark mb-0">{{ formatRupiah(stats.totalPemasukan) }}</h5>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="p-3 bg-light rounded-3 border border-light">
                                            <div class="d-flex align-items-center mb-2">
                                                <div class="badge rounded-circle bg-danger p-1 me-2"><i class="fas fa-arrow-up small text-white"></i></div>
                                                <small class="text-muted fw-bold">Pengeluaran</small>
                                            </div>
                                            <h5 class="fw-bold text-dark mb-0">{{ formatRupiah(stats.totalPengeluaran) }}</h5>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="p-3 bg-light rounded-3 border border-light">
                                            <div class="d-flex align-items-center mb-2">
                                                <i class="fas fa-check-circle text-info me-2"></i>
                                                <small class="text-muted fw-bold">Tagihan Lunas</small>
                                            </div>
                                            <h5 class="fw-bold text-dark mb-0">{{ formatNumber(stats.tagihanLunas) }}</h5>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="p-3 bg-light rounded-3 border border-light">
                                            <div class="d-flex align-items-center mb-2">
                                                <i class="fas fa-exclamation-circle text-warning me-2"></i>
                                                <small class="text-muted fw-bold">Belum Lunas</small>
                                            </div>
                                            <h5 class="fw-bold text-dark mb-0">{{ formatNumber(stats.tagihanBelumLunas) }}</h5>
                                        </div>
                                    </div>
                               </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-xl-6 col-12">
                        <div class="card border-0 shadow-sm h-100 rounded-4 overflow-hidden">
                            <div class="card-header bg-white border-0 py-3 px-4 d-flex justify-content-between align-items-center">
                                <h6 class="fw-bold mb-0 text-dark"><i class="fas fa-exchange-alt me-2 text-primary"></i> Transaksi Terakhir</h6>
                                <Link href="/admin/transaksi" class="btn btn-sm btn-light text-muted small rounded-pill px-3">Lihat Semua</Link>
                            </div>
                            <div class="card-body p-0">
                                <div class="table-responsive">
                                    <table class="table table-hover align-middle mb-0">
                                        <thead class="bg-light">
                                            <tr>
                                                <th class="border-0 px-4 py-3 small text-muted text-uppercase fw-bold">Siswa</th>
                                                <th class="border-0 px-4 py-3 small text-muted text-uppercase fw-bold">Jumlah</th>
                                                <th class="border-0 px-4 py-3 small text-muted text-uppercase fw-bold text-end">Tanggal</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr v-for="trx in transaksiTerbaru" :key="trx.id">
                                                <td class="px-4 py-3">
                                                    <div class="d-flex align-items-center">
                                                        <div class="avatar avatar-sm bg-light-primary text-primary rounded-circle me-2 d-flex align-items-center justify-content-center fw-bold">
                                                            {{ trx.tagihan?.siswa?.nama?.charAt(0) || '?' }}
                                                        </div>
                                                        <div>
                                                            <span class="d-block fw-bold text-dark small">{{ truncate(trx.tagihan?.siswa?.nama, 15) }}</span>
                                                            <span class="small text-muted" style="font-size: 11px;">{{ trx.metode_pembayaran }}</span>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td class="px-4 py-3">
                                                     <span class="badge bg-light-success text-success border border-success border-opacity-25 rounded-pill">{{ formatRupiah(trx.jumlah_bayar) }}</span>
                                                </td>
                                                <td class="px-4 py-3 text-end text-muted small fw-bold">{{ formatDate(trx.tanggal_bayar || trx.created_at) }}</td>
                                            </tr>
                                            <tr v-if="transaksiTerbaru.length === 0">
                                                <td colspan="3" class="text-center py-4 text-muted small">Belum ada transaksi</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Tabel Data Terbaru (Grid) -->
            <div class="row g-4">
                <!-- Berita Terbaru -->
                <div class="col-xl-6 d-flex">
                    <div class="card flex-fill border-0 shadow-sm rounded-4 overflow-hidden">
                        <div class="card-header bg-white border-0 py-3 px-4 d-flex align-items-center justify-content-between">
                            <h6 class="fw-bold mb-0 text-dark"><i class="far fa-newspaper me-2 text-info"></i> Berita Terbaru</h6>
                            <Link href="/admin/posts" class="btn btn-sm btn-light text-muted small rounded-pill px-3">Lihat Semua</Link>
                        </div>
                        <div class="card-body p-0">
                            <div class="table-responsive">
                                <table class="table table-hover align-middle mb-0">
                                    <thead class="bg-light">
                                        <tr>
                                            <th class="border-0 px-4 py-3 small text-muted text-uppercase fw-bold">Judul Artikel</th>
                                            <th class="border-0 px-4 py-3 small text-muted text-uppercase fw-bold text-end">Tanggal</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr v-for="post in postTerbaru" :key="post.id">
                                            <td class="px-4 py-3">
                                                <Link :href="`/berita/${post.slug}`" target="_blank" class="text-dark fw-bold text-decoration-none hover-primary d-block">
                                                    {{ truncate(post.title, 45) }}
                                                </Link>
                                            </td>
                                            <td class="px-4 py-3 text-end text-muted small">{{ formatDate(post.created_at) }}</td>
                                        </tr>
                                        <tr v-if="postTerbaru.length === 0">
                                            <td colspan="2" class="text-center py-4 text-muted small">Belum ada berita</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Surat Masuk Terbaru -->
                <div class="col-xl-6 d-flex">
                    <div class="card flex-fill border-0 shadow-sm rounded-4 overflow-hidden">
                        <div class="card-header bg-white border-0 py-3 px-4 d-flex align-items-center justify-content-between">
                            <h6 class="fw-bold mb-0 text-dark"><i class="fas fa-envelope me-2 text-warning"></i> Surat Masuk</h6>
                            <Link href="/admin/surat-masuk" class="btn btn-sm btn-light text-muted small rounded-pill px-3">Lihat Semua</Link>
                        </div>
                        <div class="card-body p-0">
                            <div class="table-responsive">
                                <table class="table table-hover align-middle mb-0">
                                    <thead class="bg-light">
                                        <tr>
                                            <th class="border-0 px-4 py-3 small text-muted text-uppercase fw-bold">Perihal</th>
                                            <th class="border-0 px-4 py-3 small text-muted text-uppercase fw-bold text-end">Tanggal</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr v-for="surat in suratMasukTerbaru" :key="surat.id">
                                            <td class="px-4 py-3">
                                                <span class="d-block fw-bold text-dark small">{{ truncate(surat.perihal, 35) }}</span>
                                                <span class="small text-muted" style="font-size: 11px;"><i class="fas fa-hashtag me-1"></i>{{ surat.nomor_surat }}</span>
                                            </td>
                                            <td class="px-4 py-3 text-end text-muted small">{{ formatDate(surat.tgl_surat) }}</td>
                                        </tr>
                                        <tr v-if="suratMasukTerbaru.length === 0">
                                            <td colspan="3" class="text-center py-4 text-muted small">Belum ada surat masuk</td>
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
import { computed } from 'vue';
import { Link, usePage } from "@inertiajs/vue3";
import AdminLayout from "@/Layouts/AdminLayout.vue";

const props = defineProps({
    stats: { type: Object, default: () => ({}) },
    postTerbaru: { type: Array, default: () => [] },
    suratMasukTerbaru: { type: Array, default: () => [] },
    transaksiTerbaru: { type: Array, default: () => [] }
});

const currentDate = computed(() => {
    return new Date().toLocaleDateString('id-ID', { weekday: 'long', day: 'numeric', month: 'long', year: 'numeric' });
});

const formatNumber = (num) => num ? Number(num).toLocaleString('id-ID') : '0';
const formatRupiah = (num) => {
    if (num === undefined || num === null) return 'Rp 0';
    return 'Rp ' + Number(num).toLocaleString('id-ID');
};
const formatDate = (dateStr) => {
    if (!dateStr) return '-';
    return new Date(dateStr).toLocaleDateString('id-ID', { day: 'numeric', month: 'short', year: 'numeric' });
};
const truncate = (text, length) => {
    if (!text) return '';
    return text.length <= length ? text : text.substring(0, length) + '...';
};
</script>

<style scoped>
/* Typography */
.ls-1 { letter-spacing: 1px; }

/* Shortcut Cards */
.shortcut-card {
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    cursor: pointer;
}
.shortcut-card:hover { transform: translateY(-5px); box-shadow: 0 15px 30px -5px rgba(0,0,0,0.2) !important; }

/* Background Icon Styling */
.shortcut-card .position-absolute i {
    font-size: 6rem !important; /* Reduced from 8x (approx 8em) to 6rem */
    opacity: 0.15 !important;   /* Reduced opacity */
}

/* Stat Cards */
.stat-card { transition: all 0.2s ease; }
.stat-card:hover { transform: translateY(-2px); box-shadow: 0 .5rem 1rem rgba(0,0,0,.08) !important; }
.stat-icon {
    width: 42px; height: 42px;
    border-radius: 12px;
    display: flex; align-items: center; justify-content: center;
    font-size: 1.25rem;
}
.bg-light-primary { background-color: rgba(61, 90, 241, 0.1); }
.bg-light-danger { background-color: rgba(220, 53, 69, 0.1); }
.bg-light-success { background-color: rgba(16, 185, 129, 0.1); }
.bg-light-info { background-color: rgba(14, 165, 233, 0.1); }

/* Table Improvements */
.table thead th {
    font-size: 0.75rem;
    letter-spacing: 0.5px;
    background-color: #f8fafc;
}
.hover-link:hover { color: #3d5af1 !important; text-decoration: underline !important; }
.hover-primary:hover { color: #3d5af1 !important; }

/* Avatar */
.avatar-sm { width: 32px; height: 32px; font-size: 14px; }
</style>

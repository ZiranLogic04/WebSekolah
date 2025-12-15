<template>
    <AdminLayout>
        <div class="content container-fluid">
            <!-- Page Header -->
            <div class="page-header">
                <div class="row align-items-center">
                    <div class="col">
                        <h3 class="page-title">Rekap Keuangan</h3>
                        <ul class="breadcrumb">
                            <li class="breadcrumb-item"><Link href="/dashboard">Dashboard</Link></li>
                            <li class="breadcrumb-item active">Keuangan</li>
                        </ul>
                    </div>
                    <div class="col-auto">
                        <button type="button" @click="openPrintModal" class="btn btn-primary">
                            <i class="fas fa-print"></i> Cetak Laporan
                        </button>
                    </div>
                </div>
            </div>

            <!-- Dashboard Info -->
            <div class="row">
                <div class="col-md-12 mb-3">
                     <div class="card bg-white shadow-sm border-0">
                        <div class="card-body">
                            <h5 class="card-title text-muted mb-3">Ringkasan Keuangan</h5>
                            <div class="row">
                                <div class="col-md-4 text-center border-end">
                                    <h6 class="text-primary"><i class="fas fa-wallet mb-2"></i> Total Saldo Sekolah (Saat Ini)</h6>
                                    <h3>{{ formatRupiah(summary.saldo_sekolah) }}</h3>
                                    <small class="text-muted">Total kas tersedia</small>
                                </div>
                                <div class="col-md-4 text-center border-end">
                                    <h6 class="text-success"><i class="fas fa-arrow-down mb-2"></i> Pemasukan {{ summary.label_periode }}</h6>
                                    <h3>{{ formatRupiah(summary.pemasukan_periode) }}</h3>
                                </div>
                                <div class="col-md-4 text-center">
                                    <h6 class="text-danger"><i class="fas fa-arrow-up mb-2"></i> Pengeluaran {{ summary.label_periode }}</h6>
                                    <h3>{{ formatRupiah(summary.pengeluaran_periode) }}</h3>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Laporan Arus Kas Detail -->
            <div class="row mb-4">
                <!-- Rincian Pemasukan -->
                <div class="col-md-6 d-flex">
                    <div class="card flex-fill">
                        <div class="card-header">
                            <h5 class="card-title text-success">Rincian Pemasukan {{ summary.label_periode }}</h5>
                        </div>
                        <div class="card-body p-0">
                            <div class="table-responsive">
                                <table class="table table-striped table-hover mb-0">
                                    <thead>
                                        <tr>
                                            <th>Kategori</th>
                                            <th class="text-end">Jumlah</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr v-if="laporan.pemasukan.length === 0">
                                            <td colspan="2" class="text-center">Tidak ada data pemasukan.</td>
                                        </tr>
                                        <tr v-for="(item, index) in laporan.pemasukan" :key="index">
                                            <td>{{ item.kategori }} <span class="badge bg-light text-dark rounded-pill ms-1">{{ item.count }}x</span></td>
                                            <td class="text-end fw-bold text-success">{{ formatRupiah(item.total) }}</td>
                                        </tr>
                                    </tbody>
                                    <tfoot>
                                        <tr class="fw-bold bg-light">
                                            <td>Total</td>
                                            <td class="text-end text-success">{{ formatRupiah(summary.pemasukan_periode) }}</td>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Rincian Pengeluaran -->
                <div class="col-md-6 d-flex">
                    <div class="card flex-fill">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <h5 class="card-title text-danger mb-0">Rincian Pengeluaran</h5>
                            <span class="badge bg-danger-light">{{ summary.label_periode }}</span>
                        </div>
                        <div class="card-body p-0">
                            <div class="table-responsive">
                                <table class="table table-striped table-hover mb-0">
                                    <thead>
                                        <tr>
                                            <th>Kategori</th>
                                            <th class="text-end">Jumlah</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr v-if="laporan.pengeluaran.length === 0">
                                            <td colspan="2" class="text-center text-muted py-3">Tidak ada data pengeluaran.</td>
                                        </tr>
                                        <tr v-for="(item, index) in laporan.pengeluaran" :key="index">
                                            <td>{{ item.kategori }} <span class="badge bg-light text-dark rounded-pill ms-1">{{ item.count }}x</span></td>
                                            <td class="text-end fw-bold text-danger">{{ formatRupiah(item.total) }}</td>
                                        </tr>
                                    </tbody>
                                    <tfoot>
                                        <tr class="fw-bold bg-light">
                                            <td>Total</td>
                                            <td class="text-end text-danger">{{ formatRupiah(summary.pengeluaran_periode) }}</td>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- RIWAYAT TRANSAKSI DETAIL -->
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="card-title">Log Transaksi Lengkap</h5>
                        </div>
                        <div class="card-body">
                            <!-- Simple Search Filter within Table Card -->
                            <div class="row mb-3">
                                <div class="col-md-8"></div>
                                <div class="col-md-4">
                                    <div class="input-group">
                                        <input type="text" class="form-control" placeholder="Cari transaksi..." v-model="filterForm.search" @keyup.enter="handleSearch">
                                        <button class="btn btn-outline-secondary" type="button" @click="handleSearch"><i class="fas fa-search"></i></button>
                                    </div>
                                </div>
                            </div>

                            <div class="table-responsive">
                                <table class="table table-hover table-center mb-0 datatable">
                                    <thead class="thead-light">
                                        <tr>
                                            <th>Tanggal</th>
                                            <th>Kategori / Sumber</th>
                                            <th>Tipe</th>
                                            <th>Jumlah</th>
                                            <th class="text-end">Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr v-if="transaksi.data.length === 0">
                                            <td colspan="5" class="text-center p-5">Belum ada data transaksi.</td>
                                        </tr>
                                        <tr v-for="item in transaksi.data" :key="item.id">
                                            <td>{{ new Date(item.tanggal).toLocaleDateString('id-ID') }}</td>
                                            <td>
                                                <div class="fw-bold">{{ item.kategori ? item.kategori.nama : 'Lainnya' }}</div>
                                                <small class="text-muted">{{ item.sumber }} - {{ item.keterangan }}</small>
                                            </td>
                                            <td>
                                                <span class="badge" :class="item.tipe === 'Masuk' ? 'bg-success' : 'bg-danger'">
                                                    {{ item.tipe }}
                                                </span>
                                            </td>
                                            <td class="fw-bold" :class="item.tipe === 'Masuk' ? 'text-success' : 'text-danger'">
                                                {{ item.tipe === 'Masuk' ? '+' : '-' }} {{ formatRupiah(item.jumlah) }}
                                            </td>
                                            <td class="text-end">
                                                <button v-if="!item.transaksi_id" class="btn btn-sm btn-danger" @click="confirmDelete(item.id)">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                                <span v-else class="text-muted"><i class="fas fa-lock"></i></span>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>

                            <!-- Pagination -->
                            <div class="row mt-3" v-if="transaksi.links.length > 3">
                                <div class="col-md-12">
                                    <nav aria-label="Page navigation">
                                        <ul class="pagination justify-content-center">
                                            <li class="page-item" :class="{ disabled: !link.url, active: link.active }" v-for="(link, index) in transaksi.links" :key="index">
                                                <Link class="page-link" :href="link.url || '#'" v-html="link.label" preserve-state preserve-scroll />
                                            </li>
                                        </ul>
                                    </nav>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Modal Form (Hidden but accessible if needed via URL param or future feature) -->
            <div class="modal fade" id="keuanganModal" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Catat Transaksi Manual</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <form @submit.prevent="submitForm">
                            <div class="modal-body">
                                <div class="mb-3">
                                    <label class="form-label">Tipe Transaksi</label>
                                    <div class="d-flex gap-3">
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" id="tipeMasuk" value="Masuk" v-model="form.tipe">
                                            <label class="form-check-label" for="tipeMasuk">
                                                <i class="fas fa-arrow-down text-success me-1"></i> Pemasukan
                                            </label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" id="tipeKeluar" value="Keluar" v-model="form.tipe">
                                            <label class="form-check-label" for="tipeKeluar">
                                                <i class="fas fa-arrow-up text-danger me-1"></i> Pengeluaran
                                            </label>
                                        </div>
                                    </div>
                                </div>
                                <div class="mb-3" v-if="currentKategori.length > 0">
                                    <label class="form-label">Kategori</label>
                                    <select class="form-select" v-model="form.kategori_id">
                                        <option value="">-- Pilih Kategori (Opsional) --</option>
                                        <option v-for="kat in currentKategori" :key="kat.id" :value="kat.id">
                                            {{ kat.nama }} {{ kat.kode ? `(${kat.kode})` : '' }}
                                        </option>
                                    </select>
                                    <small class="text-muted">Opsional. <Link href="/admin/jenis-tagihan" class="text-primary">Kelola Kategori</Link></small>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Sumber / Keterangan Singkat</label>
                                    <input type="text" class="form-control" v-model="form.sumber" placeholder="Contoh: Beli Spidol, Dana BOS, dll" required>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Jumlah (Rp)</label>
                                    <input type="text" class="form-control" v-model="displayAmount" @input="handleAmountInput" required placeholder="0">
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Tanggal</label>
                                    <input type="date" class="form-control" v-model="form.tanggal" required>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Keterangan Tambahan (Opsional)</label>
                                    <textarea class="form-control" v-model="form.keterangan" rows="2" placeholder="Detail tambahan..."></textarea>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                <button type="submit" class="btn btn-primary" :disabled="form.processing">Simpan</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Modal Print Filter -->
            <div class="modal fade" id="printModal" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Cetak Laporan Keuangan</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <form @submit.prevent="handlePrintSubmit">
                                <div class="mb-3">
                                    <label class="form-label fw-bold">Pilih Periode Laporan</label>
                                    <select class="form-select" v-model="printFilter.period">
                                        <option value="week">Minggu Ini</option>
                                        <option value="month">Bulan Ini</option>
                                        <option value="month_range">Pilih Rentang Bulan</option>
                                        <option value="year">Tahun Ini</option>
                                        <option value="custom">Pilih Tanggal Manual</option>
                                    </select>
                                </div>

                                <!-- Custom Date Range -->
                                <div class="row" v-if="printFilter.period === 'custom'">
                                    <div class="col-6 mb-3">
                                        <label class="form-label">Dari Tanggal</label>
                                        <input type="date" class="form-control" v-model="printFilter.start_date">
                                    </div>
                                    <div class="col-6 mb-3">
                                    <label class="form-label">Sampai Tanggal</label>
                                        <input type="date" class="form-control" v-model="printFilter.end_date">
                                    </div>
                                </div>

                                <!-- Month Range -->
                                <div class="row" v-if="printFilter.period === 'month_range'">
                                    <div class="col-6 mb-3">
                                        <label class="form-label">Dari Bulan</label>
                                        <input type="month" class="form-control" v-model="printFilter.start_month">
                                    </div>
                                    <div class="col-6 mb-3">
                                    <label class="form-label">Sampai Bulan</label>
                                        <input type="month" class="form-control" v-model="printFilter.end_month">
                                    </div>
                                </div>

                                <div class="d-grid">
                                    <button type="submit" class="btn btn-primary btn-lg"><i class="fas fa-print me-2"></i> Cetak Sekarang</button>
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
import { Link, router, usePage, useForm } from '@inertiajs/vue3';
import AdminLayout from '@/Layouts/AdminLayout.vue';
import Swal from 'sweetalert2';

const props = defineProps({
    transaksi: Object,
    summary: Object,
    laporan: Object,
    filters: Object,
    kategoriPemasukan: Array,
    kategoriPengeluaran: Array
});

const filterForm = ref({
    search: props.filters?.search || '',
    tipe: props.filters?.tipe || '',
    start_date: props.filters?.start_date || '',
    end_date: props.filters?.end_date || '',
    period: props.filters?.period || 'month'
});

const form = useForm({
    tipe: 'Keluar',
    kategori_id: '',
    sumber: '',
    jumlah: '',
    tanggal: new Date().toISOString().substr(0, 10),
    keterangan: ''
});

const displayAmount = ref('');

const formatCurrencyInput = (value) => {
    if (!value) return '';
    let number_string = value.replace(/[^,\d]/g, '').toString();
    let split = number_string.split(',');
    let sisa = split[0].length % 3;
    let rupiah = split[0].substr(0, sisa);
    let ribuan = split[0].substr(sisa).match(/\d{3}/gi);

    if (ribuan) {
        let separator = sisa ? '.' : '';
        rupiah += separator + ribuan.join('.');
    }

    rupiah = split[1] !== undefined ? rupiah + ',' + split[1] : rupiah;
    return rupiah;
};

const handleAmountInput = (e) => {
    let formatted = formatCurrencyInput(e.target.value);
    displayAmount.value = formatted;
    // Remove dots for parsing
    let numericValue = formatted.replace(/\./g, '');
    form.jumlah = numericValue;
};

// Computed: kategori berdasarkan tipe yang dipilih
const currentKategori = computed(() => {
    if (form.tipe === 'Masuk') {
        return props.kategoriPemasukan || [];
    } else {
        return props.kategoriPengeluaran || [];
    }
});

// Print Modal Logic
const printFilter = ref({
    period: 'month',
    start_date: '',
    end_date: '',
    start_month: '',
    end_month: ''
});

let modalInstance = null;
let printModalInstance = null;

onMounted(() => {
    // Initialize Transaction Modal
    const el = document.getElementById('keuanganModal');
    if (window.bootstrap) {
        modalInstance = new window.bootstrap.Modal(el);
    }
    
    // Initialize Print Modal
    const printEl = document.getElementById('printModal');
    if (window.bootstrap && printEl) {
        printModalInstance = new window.bootstrap.Modal(printEl);
    }
    
    // Auto open modal if ?action=create
    const urlParams = new URLSearchParams(window.location.search);
    if (urlParams.get('action') === 'create') {
        openModal();
    }
});

const openPrintModal = () => {
    printFilter.value.period = 'month'; // Default
    printFilter.value.start_date = '';
    printFilter.value.end_date = '';
    printFilter.value.start_month = '';
    printFilter.value.end_month = '';
    printModalInstance.show();
};

const handlePrintSubmit = () => {
    const params = new URLSearchParams();
    
    if (printFilter.value.period === 'month_range') {
        // Logic for Month Range: Convert "YYYY-MM" to Start/End Dates
        if (printFilter.value.start_month && printFilter.value.end_month) {
            const startStr = printFilter.value.start_month; // "2024-01"
            const endStr = printFilter.value.end_month;     // "2024-03"
            
            // Start Date: 1st of start month
            params.append('start_date', startStr + '-01');
            
            // End Date: Last day of end month
            const [year, month] = endStr.split('-').map(Number);
            const lastDay = new Date(year, month, 0).getDate(); // month is 1-indexed here effectively for next month 0-day trick? NO.
            // new Date(year, monthIndex, 0). monthIndex 1 = Feb, day 0 = last day of Jan.
            // But split gives '01' -> 1. 
            // new Date(2024, 1, 0) -> Jan 31 2024? No, Month is 0-indexed in JS Date?
            // "2024-03". split -> 2024, 3. 
            // new Date(2024, 3, 0) -> April 0th -> March 31st. Correct.
            
            params.append('end_date', endStr + '-' + lastDay);
            
            // Send as custom period so controller uses the dates
            params.append('period', 'custom'); 
        }
    } else {
        if (printFilter.value.period) params.append('period', printFilter.value.period);
        if (printFilter.value.start_date) params.append('start_date', printFilter.value.start_date);
        if (printFilter.value.end_date) params.append('end_date', printFilter.value.end_date);
    }

    const url = `/admin/uang-sekolah/print?${params.toString()}`;
    window.open(url, '_blank');
    printModalInstance.hide();
};

// Construct Print URL based on current filters
const printUrl = computed(() => {
    const params = new URLSearchParams();
    if (filterForm.value.period) params.append('period', filterForm.value.period);
    if (filterForm.value.start_date) params.append('start_date', filterForm.value.start_date);
    if (filterForm.value.end_date) params.append('end_date', filterForm.value.end_date);
    // Print logic usually doesn't need 'search' or 'tipe' unless we want to filter the report too. 
    // Assuming report is FULL report for the period.
    return `/admin/uang-sekolah/print?${params.toString()}`;
});

const handleSearch = () => {
    if (filterForm.value.start_date || filterForm.value.end_date) {
        filterForm.value.period = '';
    }
    router.get('/admin/uang-sekolah', filterForm.value, { preserveState: true, replace: true });
};

const setPeriod = (period) => {
    filterForm.value.period = period;
    filterForm.value.start_date = '';
    filterForm.value.end_date = '';
    router.get('/admin/uang-sekolah', { ...filterForm.value, start_date: null, end_date: null }, { preserveState: true, replace: true });
};


const openModal = () => {
    form.reset();
    displayAmount.value = '';
    form.tanggal = new Date().toISOString().substr(0, 10);
    modalInstance.show();
};

const submitForm = () => {
    form.post('/admin/uang-sekolah', {
        onSuccess: () => {
            modalInstance.hide();
            Swal.fire('Berhasil', 'Data transaksi berhasil disimpan', 'success');
        },
        onError: () => {
            Swal.fire('Gagal', 'Terjadi kesalahan validasi', 'error');
        }
    });
};

const confirmDelete = (id) => {
    Swal.fire({
        title: 'Hapus Data?',
        text: "Data yang dihapus tidak dapat dikembalikan!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'Ya, Hapus!'
    }).then((result) => {
        if (result.isConfirmed) {
            router.delete(`/admin/uang-sekolah/${id}`, {
                onSuccess: () => Swal.fire('Terhapus!', 'Data berhasil dihapus.', 'success')
            });
        }
    });
};

const formatRupiah = (value) => {
    return new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR', minimumFractionDigits: 0 }).format(value);
};
</script>

<style scoped>
/* Scoped styles for Dashboard view */
</style>

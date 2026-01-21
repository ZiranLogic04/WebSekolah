<template>
    <AdminLayout>
        <div class="content container-fluid">
            <!-- Radiant Header (Solid Color Variant) -->
            <div class="card border-0 shadow-lg rounded-4 mb-4 overflow-hidden position-relative bg-primary">
                <div class="card-body p-4 p-lg-5 position-relative z-1">
                    <div class="row align-items-center">
                        <div class="col-lg-8">
                            <div class="d-flex align-items-center gap-3 mb-2">
                                <div class="icon-box bg-white rounded-3 text-primary p-2">
                                    <i class="fas fa-chart-line fs-3"></i>
                                </div>
                                <h2 class="fw-bold text-white mb-0 ls-tight">Rekap Keuangan</h2>
                            </div>
                            <p class="text-white-50 mb-0 fs-5">Ringkasan arus kas, pemasukan, dan pengeluaran sekolah.</p>
                        </div>
                        <div class="col-lg-4 text-lg-end mt-3 mt-lg-0">
                            <button class="btn btn-light btn-lg rounded-pill shadow-sm px-4 fw-bold text-primary hover-elevate" @click="openPrintModal">
                                <i class="fas fa-print me-2"></i>Cetak Laporan
                            </button>
                        </div>
                    </div>
                </div>
                <!-- Decorative Background -->
                <div class="position-absolute bottom-0 end-0 opacity-10 me-n5 mb-n5">
                    <i class="fas fa-chart-pie" style="font-size: 10rem; color: white;"></i>
                </div>
            </div>

            <!-- Dashboard Info -->
            <div class="row">
                <div class="col-md-12 mb-4">
                     <div class="card border-0 shadow-sm rounded-4">
                        <div class="card-body p-4">
                            <h5 class="card-title fw-bold text-muted mb-4"><i class="fas fa-wallet me-2"></i>Ringkasan Keuangan</h5>
                            <div class="row g-4">
                                <div class="col-md-4 text-center border-end">
                                    <div class="p-2">
                                        <h6 class="text-primary fw-bold mb-3">Total Saldo Sekolah</h6>
                                        <h2 class="fw-bold mb-1">{{ formatRupiah(summary.saldo_sekolah) }}</h2>
                                        <small class="text-muted"><i class="fas fa-check-circle text-success me-1"></i>Kas tersedia saat ini</small>
                                    </div>
                                </div>
                                <div class="col-md-4 text-center border-end">
                                    <div class="p-2">
                                        <h6 class="text-success fw-bold mb-3">Pemasukan {{ summary.label_periode }}</h6>
                                        <h3 class="fw-bold text-success mb-0"><i class="fas fa-arrow-down me-2"></i>{{ formatRupiah(summary.pemasukan_periode) }}</h3>
                                    </div>
                                </div>
                                <div class="col-md-4 text-center">
                                    <div class="p-2">
                                        <h6 class="text-danger fw-bold mb-3">Pengeluaran {{ summary.label_periode }}</h6>
                                        <h3 class="fw-bold text-danger mb-0"><i class="fas fa-arrow-up me-2"></i>{{ formatRupiah(summary.pengeluaran_periode) }}</h3>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Laporan Arus Kas Detail -->
            <div class="row mb-4">
                <!-- Rincian Pemasukan -->
                <div class="col-md-6 d-flex mb-4 mb-md-0">
                    <div class="card border-0 shadow-sm rounded-4 flex-fill overflow-hidden">
                        <div class="card-header bg-white border-0 pt-4 px-4 pb-0">
                            <h5 class="card-title fw-bold text-success mb-0"><i class="fas fa-arrow-down me-2"></i>Rincian Pemasukan</h5>
                            <small class="text-muted">{{ summary.label_periode }}</small>
                        </div>
                        <div class="card-body p-0">
                            <div class="table-responsive p-3">
                                <table class="table table-hover table-center mb-0">
                                    <thead class="table-light rounded-3">
                                        <tr>
                                            <th class="rounded-start-3 ps-4">Kategori</th>
                                            <th class="text-end rounded-end-3 pe-4">Jumlah</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr v-if="laporan.pemasukan.length === 0">
                                            <td colspan="2" class="text-center py-4 text-muted">Tidak ada data pemasukan.</td>
                                        </tr>
                                        <tr v-for="(item, index) in laporan.pemasukan" :key="index">
                                            <td class="ps-4">{{ item.kategori }} <span class="badge bg-success-subtle text-success rounded-pill ms-2">{{ item.count }}x</span></td>
                                            <td class="text-end fw-bold text-success pe-4">{{ formatRupiah(item.total) }}</td>
                                        </tr>
                                    </tbody>
                                    <tfoot>
                                        <tr class="fw-bold bg-light">
                                            <td class="ps-4 rounded-start-3">Total</td>
                                            <td class="text-end text-success pe-4 rounded-end-3">{{ formatRupiah(summary.pemasukan_periode) }}</td>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Rincian Pengeluaran -->
                <div class="col-md-6 d-flex">
                    <div class="card border-0 shadow-sm rounded-4 flex-fill overflow-hidden">
                        <div class="card-header bg-white border-0 pt-4 px-4 pb-0 d-flex justify-content-between align-items-center">
                            <div>
                                <h5 class="card-title fw-bold text-danger mb-0"><i class="fas fa-arrow-up me-2"></i>Rincian Pengeluaran</h5>
                                <small class="text-muted">{{ summary.label_periode }}</small>
                            </div>
                        </div>
                        <div class="card-body p-0">
                            <div class="table-responsive p-3">
                                <table class="table table-hover table-center mb-0">
                                    <thead class="table-light rounded-3">
                                        <tr>
                                            <th class="rounded-start-3 ps-4">Kategori</th>
                                            <th class="text-end rounded-end-3 pe-4">Jumlah</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr v-if="laporan.pengeluaran.length === 0">
                                            <td colspan="2" class="text-center text-muted py-4">Tidak ada data pengeluaran.</td>
                                        </tr>
                                        <tr v-for="(item, index) in laporan.pengeluaran" :key="index">
                                            <td class="ps-4">
                                                {{ item.kategori }}
                                                <span class="badge bg-danger-subtle text-danger rounded-pill ms-2">{{ item.count }}x</span>
                                            </td>
                                            <td class="text-end fw-bold text-danger pe-4">{{ formatRupiah(item.total) }}</td>
                                        </tr>
                                    </tbody>
                                    <tfoot>
                                        <tr class="fw-bold bg-light">
                                            <td class="ps-4 rounded-start-3">Total</td>
                                            <td class="text-end text-danger pe-4 rounded-end-3">{{ formatRupiah(summary.pengeluaran_periode) }}</td>
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
                    <div class="card border-0 shadow-sm rounded-4">
                        <div class="card-header bg-white border-0 pt-4 px-4 pb-0 d-flex justify-content-between align-items-center">
                            <h5 class="card-title fw-bold text-dark mb-0"><i class="fas fa-history me-2 text-primary"></i>Riwayat Transaksi</h5>
                        </div>
                        <div class="card-body p-4">
                            <!-- Simple Search Filter within Table Card -->
                            <div class="row mb-4">
                                <div class="col-md-8"></div>
                                <div class="col-md-4">
                                    <div class="input-group">
                                        <span class="input-group-text bg-light border-end-0 rounded-start-pill ps-3"><i class="fas fa-search text-muted"></i></span>
                                        <input type="text" class="form-control bg-light border-start-0 border-end-0" placeholder="Cari transaksi..." v-model="filterForm.search" @keyup.enter="handleSearch">
                                        <button class="btn btn-primary rounded-end-pill px-4" type="button" @click="handleSearch">
                                            Cari
                                        </button>
                                    </div>
                                </div>
                            </div>

                            <div class="table-responsive">
                                <table class="table table-hover align-middle mb-0">
                                    <thead class="table-light rounded-3">
                                        <tr>
                                            <th class="rounded-start-3 ps-4 py-3" style="width: 15%;">Tanggal</th>
                                            <th class="py-3" style="width: 20%;">Kategori</th>
                                            <th class="py-3" style="width: 35%;">Keterangan</th>
                                            <th class="text-center py-3" style="width: 10%;">Tipe</th>
                                            <th class="text-end rounded-end-3 pe-4 py-3" style="width: 20%;">Jumlah</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr v-if="transaksi.data.length === 0">
                                            <td colspan="5" class="text-center p-5 text-muted">Belum ada data transaksi.</td>
                                        </tr>
                                        <tr v-for="item in transaksi.data" :key="item.id">
                                            <td class="ps-4">{{ new Date(item.tanggal).toLocaleDateString('id-ID') }}</td>
                                            <td>
                                                <!-- Priority: 1. Relation (jenisKategori), 2. Manual string (kategori), 3. Sumber, 4. Fallback -->
                                                <div class="fw-bold text-dark">
                                                    {{ item.jenis_kategori?.nama || item.kategori || item.sumber || 'Lainnya' }}
                                                </div>
                                            </td>
                                            <td>
                                                <small class="text-muted d-block text-truncate" style="max-width: 300px;">{{ item.keterangan || item.sumber || '-' }}</small>
                                            </td>
                                            <td class="text-center">
                                                <div class="d-inline-flex align-items-center gap-2 px-3 py-1 rounded-pill border" 
                                                     :class="item.tipe === 'Masuk' ? 'border-success-subtle bg-success-subtle text-success' : 'border-danger-subtle bg-danger-subtle text-danger'">
                                                    <i class="fas" :class="item.tipe === 'Masuk' ? 'fa-arrow-down' : 'fa-arrow-up'"></i>
                                                    <span class="fw-bold small">{{ item.tipe === 'Masuk' ? 'Masuk' : 'Keluar' }}</span>
                                                </div>
                                            </td>
                                            <td class="text-end fw-bold pe-4" :class="item.tipe === 'Masuk' ? 'text-success' : 'text-danger'">
                                                {{ item.tipe === 'Masuk' ? '+' : '-' }} {{ formatRupiah(item.jumlah) }}
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>

                            <!-- Pagination -->
                            <div class="row mt-4" v-if="transaksi.links.length > 3">
                                <div class="col-md-12">
                                    <nav aria-label="Page navigation">
                                        <ul class="pagination justify-content-center">
                                            <li class="page-item" :class="{ disabled: !link.url, active: link.active }" v-for="(link, index) in transaksi.links" :key="index">
                                                <Link class="page-link rounded-circle mx-1 border-0 shadow-sm d-flex align-items-center justify-content-center" 
                                                      style="width: 32px; height: 32px;"
                                                      :class="link.active ? 'bg-primary text-white' : 'bg-white text-dark'"
                                                      :href="link.url || '#'" 
                                                      v-html="link.label" 
                                                      preserve-state 
                                                      preserve-scroll />
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
            <div class="modal fade" id="keuanganModal" tabindex="-1" aria-hidden="true" style="z-index: 1055;">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content border-0 shadow-lg rounded-4">
                        <div class="modal-header bg-primary text-white rounded-top-4">
                            <div class="d-flex align-items-center gap-3">
                                <div class="icon-box bg-white rounded-3 text-primary p-2">
                                    <i class="fas fa-edit"></i>
                                </div>
                                <div>
                                    <h5 class="modal-title fw-bold text-white mb-0">Catat Transaksi</h5>
                                    <small class="text-white opacity-75">Input pemasukan atau pengeluaran baru</small>
                                </div>
                            </div>
                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <form @submit.prevent="submitForm">
                            <div class="modal-body p-4">
                                <div class="mb-4">
                                    <label class="form-label fw-bold mb-3">Tipe Transaksi</label>
                                    <div class="d-flex gap-3">
                                        <div class="form-check card-radio flex-fill">
                                            <input class="form-check-input visually-hidden" type="radio" id="tipeMasuk" value="Masuk" v-model="form.tipe">
                                            <label class="form-check-label card p-3 text-center border cursor-pointer h-100" :class="form.tipe === 'Masuk' ? 'border-success bg-success-subtle text-success fw-bold' : ''" for="tipeMasuk">
                                                <i class="fas fa-arrow-down mb-2 fs-4 d-block"></i>
                                                Pemasukan
                                            </label>
                                        </div>
                                        <div class="form-check card-radio flex-fill">
                                            <input class="form-check-input visually-hidden" type="radio" id="tipeKeluar" value="Keluar" v-model="form.tipe">
                                            <label class="form-check-label card p-3 text-center border cursor-pointer h-100" :class="form.tipe === 'Keluar' ? 'border-danger bg-danger-subtle text-danger fw-bold' : ''" for="tipeKeluar">
                                                <i class="fas fa-arrow-up mb-2 fs-4 d-block"></i>
                                                Pengeluaran
                                            </label>
                                        </div>
                                    </div>
                                </div>
                                <div class="mb-3" v-if="currentKategori.length > 0">
                                    <label class="form-label fw-bold">Kategori</label>
                                    <select class="form-select rounded-pill bg-light border-0 ps-3" v-model="form.kategori_id">
                                        <option value="">-- Pilih Kategori (Opsional) --</option>
                                        <option v-for="kat in currentKategori" :key="kat.id" :value="kat.id">
                                            {{ kat.nama }} {{ kat.kode ? `(${kat.kode})` : '' }}
                                        </option>
                                    </select>
                                    <small class="text-muted ms-2 mt-1 d-block">Opsional. <Link href="/admin/jenis-tagihan" class="text-primary text-decoration-none">Kelola Kategori</Link></small>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label fw-bold">Sumber / Keterangan Singkat</label>
                                    <input type="text" class="form-control rounded-pill bg-light border-0 ps-3" v-model="form.sumber" placeholder="Contoh: Beli Spidol, Dana BOS, dll" required>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label fw-bold">Jumlah (Rp)</label>
                                    <div class="input-group">
                                        <span class="input-group-text rounded-start-pill bg-light border-0 fw-bold">Rp</span>
                                        <input type="text" class="form-control rounded-end-pill bg-light border-0 ps-2 fw-bold" v-model="displayAmount" @input="handleAmountInput" required placeholder="0">
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label fw-bold">Tanggal</label>
                                    <input type="date" class="form-control rounded-pill bg-light border-0 ps-3" v-model="form.tanggal" required>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label fw-bold">Keterangan Tambahan (Opsional)</label>
                                    <textarea class="form-control rounded-4 bg-light border-0 p-3" v-model="form.keterangan" rows="2" placeholder="Detail tambahan..."></textarea>
                                </div>
                            </div>
                            <div class="modal-footer border-0 px-4 pb-4">
                                <button type="button" class="btn btn-light rounded-pill px-4" data-bs-dismiss="modal">Batal</button>
                                <button type="submit" class="btn btn-primary rounded-pill px-4 shadow-sm" :disabled="form.processing">Simpan Transaksi</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Modal Print Filter - Modern Design -->
            <div class="modal fade" id="printModal" tabindex="-1" aria-hidden="true" style="z-index: 1055;">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content border-0 shadow-lg rounded-4">
                        <div class="modal-header bg-primary text-white rounded-top-4">
                            <div class="d-flex align-items-center gap-3">
                                <div class="icon-box bg-white rounded-3 text-primary p-2">
                                    <i class="fas fa-print"></i>
                                </div>
                                <div>
                                    <h5 class="modal-title fw-bold text-white mb-0">Cetak Laporan</h5>
                                    <small class="text-white opacity-75">Ekspor laporan keuangan ke PDF</small>
                                </div>
                            </div>
                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body p-4">
                            <form @submit.prevent="handlePrintSubmit">
                                <!-- Period Selection Cards -->
                                <div class="row g-3 mb-4">
                                    <div class="col-6">
                                        <div class="period-card" 
                                            :class="{ 'active': printFilter.period === 'week' }"
                                            @click="printFilter.period = 'week'">
                                            <i class="fas fa-calendar-week"></i>
                                            <span>Minggu Ini</span>
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div class="period-card" 
                                            :class="{ 'active': printFilter.period === 'month' }"
                                            @click="printFilter.period = 'month'">
                                            <i class="fas fa-calendar-alt"></i>
                                            <span>Bulan Ini</span>
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div class="period-card" 
                                            :class="{ 'active': printFilter.period === 'year' }"
                                            @click="printFilter.period = 'year'">
                                            <i class="fas fa-calendar"></i>
                                            <span>Tahun Ini</span>
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div class="period-card" 
                                            :class="{ 'active': printFilter.period === 'custom' }"
                                            @click="printFilter.period = 'custom'">
                                            <i class="fas fa-calendar-day"></i>
                                            <span>Pilih Tanggal</span>
                                        </div>
                                    </div>
                                </div>

                                <!-- Custom Date Range -->
                                <div class="custom-date-section rounded-3 p-3 bg-light animated fadeIn" v-if="printFilter.period === 'custom'">
                                    <div class="row g-3">
                                        <div class="col-6">
                                            <label class="form-label fw-bold small text-muted mb-1">Dari Tanggal</label>
                                            <input type="date" class="form-control rounded-pill border-0 shadow-sm" v-model="printFilter.start_date">
                                        </div>
                                        <div class="col-6">
                                            <label class="form-label fw-bold small text-muted mb-1">Sampai Tanggal</label>
                                            <input type="date" class="form-control rounded-pill border-0 shadow-sm" v-model="printFilter.end_date">
                                        </div>
                                    </div>
                                </div>

                                <div class="d-grid mt-4">
                                    <button type="submit" class="btn btn-primary btn-lg rounded-pill shadow-sm">
                                        <i class="fas fa-download me-2"></i> Unduh Laporan PDF
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
    if (window.bootstrap && el) {
        modalInstance = new window.bootstrap.Modal(el);
        
        // Z-Index Fix for Sidebar when modal opens
        el.addEventListener('show.bs.modal', () => {
             const sidebar = document.querySelector('.sidebar');
             if(sidebar) sidebar.style.zIndex = '1000';
        });
        el.addEventListener('hidden.bs.modal', () => {
             const sidebar = document.querySelector('.sidebar');
             if(sidebar) sidebar.style.zIndex = '';
        });
    }
    
    // Initialize Print Modal
    const printEl = document.getElementById('printModal');
    if (window.bootstrap && printEl) {
        printModalInstance = new window.bootstrap.Modal(printEl);
        
        // Z-Index Fix for Sidebar when modal opens
        printEl.addEventListener('show.bs.modal', () => {
             const sidebar = document.querySelector('.sidebar');
             if(sidebar) sidebar.style.zIndex = '1000';
        });
        printEl.addEventListener('hidden.bs.modal', () => {
             const sidebar = document.querySelector('.sidebar');
             if(sidebar) sidebar.style.zIndex = '';
        });
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
/* Table Header Styling */
.table thead th {
    background-color: #3d5ee1;
    color: #fff;
    font-weight: 600;
    font-size: 0.85rem;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    padding: 1rem 0.75rem;
    border: none;
}
.table tbody td {
    padding: 1rem 0.75rem;
    vertical-align: middle;
    font-size: 0.95rem;
}
.table-hover tbody tr:hover {
    background-color: #f8f9fa;
}

/* Card Styling */
.card {
    border: none;
    border-radius: 1rem;
    box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
    transition: all 0.3s ease;
}

/* Icon Box */
.icon-box {
    width: 48px;
    height: 48px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.25rem;
    transition: all 0.3s ease;
}

/* Hover Elevate */
.hover-elevate {
    transition: transform 0.2s ease, box-shadow 0.2s ease;
}
.hover-elevate:hover {
    transform: translateY(-3px);
    box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05) !important;
}

/* Card Radio (for Tipe Transaksi) */
.card-radio label {
    transition: all 0.2s ease;
}
.card-radio label:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 6px rgba(0,0,0,0.05);
}

/* Period Card (for Print Modal) */
.period-card {
    border: 2px solid #e9ecef;
    border-radius: 1rem;
    padding: 1.5rem;
    cursor: pointer;
    transition: all 0.2s ease;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    height: 100%;
    color: #495057;
    background-color: #fff;
}

.period-card i {
    font-size: 2rem;
    margin-bottom: 0.75rem;
    color: #6c757d;
    transition: color 0.2s ease;
}

.period-card span {
    font-weight: 600;
    font-size: 0.9rem;
}

.period-card:hover {
    border-color: #3b82f6;
    background-color: #eff6ff;
    transform: translateY(-3px);
}

.period-card.active {
    border-color: #3b82f6;
    background-color: #eff6ff;
    color: #3b82f6;
}

.period-card.active i {
    color: #3b82f6;
}

/* Animations */
.animated {
    animation-duration: 0.3s;
    animation-fill-mode: both;
}
.fadeIn {
    animation-name: fadeIn;
}
@keyframes fadeIn {
    from {
        opacity: 0;
        transform: translateY(10px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}
</style>

<template>
    <AdminLayout>
        <div class="content container-fluid">
            <!-- Radiant Header -->
            <div class="card border-0 shadow-lg rounded-4 mb-4 overflow-hidden position-relative" style="background: linear-gradient(135deg, #4f46e5 0%, #3b82f6 100%);">
                <div class="card-body p-4 p-lg-5 position-relative z-1">
                    <div class="row align-items-center">
                        <div class="col-lg-8">
                            <div class="d-flex align-items-center gap-3 mb-2">
                                <div class="icon-box bg-white rounded-3 text-primary p-2">
                                    <i class="fas fa-hand-holding-usd fs-3"></i>
                                </div>
                                <h2 class="fw-bold text-white mb-0 ls-tight">Daftar Piutang</h2>
                            </div>
                            <p class="text-white-50 mb-0 fs-5">Pantau dan kelola tunggakan pembayaran siswa.</p>
                        </div>
                        <div class="col-lg-4 text-lg-end mt-3 mt-lg-0">
                            <button class="btn btn-light btn-lg rounded-pill shadow-sm px-4 fw-bold text-primary hover-elevate" @click="showPrintModal = true">
                                <i class="fas fa-print me-2"></i>Cetak Laporan
                            </button>
                        </div>
                    </div>
                </div>
                <!-- Decorative Background -->
                <div class="position-absolute bottom-0 end-0 opacity-10 me-n5 mb-n5">
                    <i class="fas fa-hand-holding-usd" style="font-size: 10rem; color: white;"></i>
                </div>
            </div>

            <div class="row">
                <div class="col-12">
                    <div class="card border-0 shadow-sm rounded-4">
                        <div class="card-body p-4">
                            
                            <!-- Filter & Search -->
                            <div class="row g-3 mb-4">
                                <div class="col-md-2">
                                    <div class="input-group">
                                        <span class="input-group-text bg-light border-end-0 rounded-start-pill"><i class="fas fa-layer-group text-muted"></i></span>
                                        <select class="form-select border-start-0 rounded-end-pill bg-light" v-model="filters.kelas" @change="handleSearch">
                                            <option value="">Semua Kelas</option>
                                            <option v-for="k in kelasList" :key="k" :value="k">{{ k }}</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="input-group">
                                        <span class="input-group-text bg-light border-end-0 rounded-start-pill"><i class="fas fa-tags text-muted"></i></span>
                                        <select class="form-select border-start-0 rounded-end-pill bg-light" v-model="filters.jenis_tagihan_id" @change="handleSearch">
                                            <option value="">Semua Jenis</option>
                                            <option v-for="j in jenisTagihans" :key="j.id" :value="j.id">{{ j.nama }}</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="input-group">
                                        <span class="input-group-text bg-light border-end-0 rounded-start-pill"><i class="fas fa-calendar-alt text-muted"></i></span>
                                        <select class="form-select border-start-0 rounded-end-pill bg-light" v-model="filters.tahun_ajaran_id" @change="handleSearch">
                                            <option value="">Semua Tahun</option>
                                            <option v-for="t in tahunAjarans" :key="t.id" :value="t.id">{{ t.tahun }} {{ t.is_active ? '(Aktif)' : '' }}</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="input-group">
                                        <input type="text" class="form-control rounded-start-pill ps-4" placeholder="Cari Nama / NIS..." v-model="filters.search" @keyup.enter="handleSearch">
                                        <button class="btn btn-primary rounded-end-pill px-4" type="button" @click="handleSearch">
                                            <i class="fas fa-search"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>

                            <div class="table-responsive">
                                <table class="table table-hover table-bordered align-middle mb-0">
                                    <thead>
                                        <tr>
                                            <th class="text-center" style="width: 50px;">No</th>
                                            <th>Nama Siswa</th>
                                            <th class="text-center">NIS</th>
                                            <th class="text-center">Kelas</th>
                                            <th>Jenis Tagihan</th>
                                            <th class="text-end">Sisa Tagihan</th>
                                            <th class="text-center">Jatuh Tempo</th>
                                            <th class="text-center">Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr v-for="(tagihan, index) in tagihans.data" :key="tagihan.id">
                                            <td class="text-center">
                                                {{ (tagihans.current_page - 1) * tagihans.per_page + index + 1 }}
                                            </td>
                                            <td>{{ tagihan.siswa?.nama || '-' }}</td>
                                            <td class="text-center">{{ tagihan.siswa?.nis || '-' }}</td>
                                            <td class="text-center">{{ tagihan.siswa?.kelas || '-' }}</td>
                                            <td>{{ tagihan.nama_tagihan }}</td>
                                            <td class="text-end fw-semibold text-danger">{{ formatRupiah(tagihan.sisa || tagihan.jumlah) }}</td>
                                            <td class="text-center" :class="{'text-danger': isOverdue(tagihan.tenggat_waktu)}">
                                                {{ formatDate(tagihan.tenggat_waktu) }}
                                            </td>
                                            <td class="text-center">
                                                <Link :href="`/admin/transaksi?student_id=${tagihan.siswa_id}`" class="btn btn-primary btn-sm rounded-pill px-3 shadow-sm">
                                                    <i class="fas fa-money-bill-wave me-1"></i> Bayar
                                                </Link>
                                            </td>
                                        </tr>
                                        <tr v-if="tagihans.data.length === 0">
                                            <td colspan="8" class="text-center py-4 text-muted">
                                                Tidak ada data piutang ditemukan.
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>

                             <!-- Pagination -->
                             <div class="row mt-4 align-items-center" v-if="tagihans.data.length > 0">
                                <div class="col-md-6">
                                    <p class="text-muted mb-0">
                                        Menampilkan <strong>{{ tagihans.from }}</strong> - <strong>{{ tagihans.to }}</strong> dari <strong>{{ tagihans.total }}</strong> data piutang
                                    </p>
                                </div>
                                <div class="col-md-6" v-if="tagihans.links.length > 3">
                                    <div class="d-flex justify-content-end">
                                        <nav>
                                            <ul class="pagination mb-0">
                                                <li class="page-item" :class="{ disabled: !tagihans.prev_page_url }">
                                                    <Link class="page-link" :href="tagihans.prev_page_url || '#'">
                                                        <i class="fas fa-chevron-left me-1"></i> Sebelumnya
                                                    </Link>
                                                </li>
                                                <li class="page-item" v-for="(link, index) in paginationNumbers" :key="index" :class="{ active: link.active }">
                                                    <Link class="page-link" :href="link.url || '#'">{{ link.label }}</Link>
                                                </li>
                                                <li class="page-item" :class="{ disabled: !tagihans.next_page_url }">
                                                    <Link class="page-link" :href="tagihans.next_page_url || '#'">
                                                        Selanjutnya <i class="fas fa-chevron-right ms-1"></i>
                                                    </Link>
                                                </li>
                                            </ul>
                                        </nav>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal Cetak -->
        <div v-if="showPrintModal" class="modal fade show d-block" tabindex="-1" style="background: rgba(0,0,0,0.5); z-index: 1055;">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content border-0 shadow-lg rounded-4">
                    <div class="modal-header bg-primary text-white rounded-top-4">
                        <div class="d-flex align-items-center gap-3">
                            <div class="icon-box bg-white rounded-3 text-primary p-2">
                                <i class="fas fa-print"></i>
                            </div>
                            <div>
                                <h5 class="modal-title fw-bold text-white mb-0">Cetak Laporan</h5>
                                <small class="text-white opacity-75">Ekspor data piutang ke PDF</small>
                            </div>
                        </div>
                        <button type="button" class="btn-close btn-close-white" @click="showPrintModal = false"></button>
                    </div>
                    <div class="modal-body p-4">
                        <!-- Pilihan Tipe -->
                        <div class="mb-4">
                            <label class="form-label fw-bold mb-3">Pilih Tipe Periode</label>
                            <div class="d-flex gap-3">
                                <div class="form-check card-radio flex-fill">
                                    <input class="form-check-input visually-hidden" type="radio" id="typeYear" value="year" v-model="printType">
                                    <label class="form-check-label card p-3 text-center border cursor-pointer h-100" :class="printType === 'year' ? 'border-primary bg-primary-subtle text-primary fw-bold' : ''" for="typeYear">
                                        <i class="fas fa-calendar-alt mb-2 fs-4 d-block"></i>
                                        Per Tahun
                                    </label>
                                </div>
                                <div class="form-check card-radio flex-fill">
                                    <input class="form-check-input visually-hidden" type="radio" id="typeMonth" value="month" v-model="printType">
                                    <label class="form-check-label card p-3 text-center border cursor-pointer h-100" :class="printType === 'month' ? 'border-primary bg-primary-subtle text-primary fw-bold' : ''" for="typeMonth">
                                        <i class="fas fa-calendar-week mb-2 fs-4 d-block"></i>
                                        Rentang Bulan
                                    </label>
                                </div>
                            </div>
                        </div>

                        <!-- Per Tahun -->
                        <div v-if="printType === 'year'" class="mb-3 animated fadeIn">
                            <label class="form-label fw-bold">Pilih Tahun</label>
                            <select class="form-select" v-model="printFilters.tahun">
                                <option v-for="y in yearOptions" :key="y" :value="y">{{ y }}</option>
                            </select>
                        </div>

                        <!-- Rentang Bulan -->
                        <div v-else class="row g-3 animated fadeIn">
                            <div class="col-6">
                                <label class="form-label fw-bold">Dari Bulan</label>
                                <input type="month" class="form-control" v-model="printFilters.start_month">
                            </div>
                            <div class="col-6">
                                <label class="form-label fw-bold">Sampai Bulan</label>
                                <input type="month" class="form-control" v-model="printFilters.end_month">
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer border-0 pt-0 px-4 pb-4">
                        <button class="btn btn-light rounded-pill px-4" @click="showPrintModal = false">Batal</button>
                        <button class="btn btn-primary rounded-pill px-4 shadow-sm" @click="doPrint">
                            <i class="fas fa-print me-2"></i>Cetak PDF
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </AdminLayout>
</template>

<script setup>
import { ref, computed } from 'vue';
import { Link, router } from '@inertiajs/vue3';
import AdminLayout from '@/Layouts/AdminLayout.vue';

const props = defineProps({
    tagihans: Object,
    filters: Object,
    kelasList: Array,
    jenisTagihans: Array,
    tahunAjarans: Array
});

// Filter hanya nomor halaman (tanpa Previous/Next)
const paginationNumbers = computed(() => {
    return props.tagihans.links.filter((link, index) => {
        // Skip first (Previous) and last (Next)
        return index !== 0 && index !== props.tagihans.links.length - 1;
    });
});

const filters = ref({
    search: props.filters?.search || '',
    kelas: props.filters?.kelas || '',
    jenis_tagihan_id: props.filters?.jenis_tagihan_id || '',
    tahun_ajaran_id: props.filters?.tahun_ajaran_id || ''
});

const showPrintModal = ref(false);
const printType = ref('year'); // 'year' atau 'month'

// Options
const currentYear = new Date().getFullYear();
const yearOptions = [currentYear - 2, currentYear - 1, currentYear, currentYear + 1];
const currentMonth = new Date().toISOString().slice(0, 7);

const printFilters = ref({
    tahun: currentYear,
    start_month: currentMonth,
    end_month: currentMonth
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

const doPrint = () => {
    let url;
    if (printType.value === 'year') {
        url = `/admin/piutang/print?type=year&tahun=${printFilters.value.tahun}`;
    } else {
        url = `/admin/piutang/print?type=month&start_month=${printFilters.value.start_month}&end_month=${printFilters.value.end_month}`;
    }
    window.open(url, '_blank');
    showPrintModal.value = false;
};
</script>

<style scoped>
/* Table Header Styling - Sama seperti Tagihan */
.table thead th {
    background-color: #3d5ee1;
    color: #fff;
    font-weight: 600;
    font-size: 0.8rem;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    padding: 0.75rem;
    border: none;
}
.table tbody td {
    padding: 0.75rem;
    vertical-align: middle;
    font-size: 0.9rem;
}
.table-hover tbody tr:hover {
    background-color: #f8f9fa;
}

/* Card */
.card {
    border: none;
    border-radius: 10px;
    box-shadow: 0 2px 10px rgba(0,0,0,0.08);
}

/* Icon Box */
.icon-box {
    width: 48px;
    height: 48px;
    display: flex;
    align-items: center;
    justify-content: center;
}

/* Hover Elevate */
.hover-elevate {
    transition: all 0.3s ease;
}
.hover-elevate:hover {
    transform: translateY(-3px);
    box-shadow: 0 10px 20px rgba(0,0,0,0.1) !important;
}

/* Card Radio */
.card-radio .card {
    transition: all 0.2s ease;
}
.card-radio input:checked + .card {
    background-color: #ebf5ff;
    border-color: #3d5ee1 !important;
    color: #3d5ee1;
}

/* Animation */
@keyframes fadeIn {
    from { opacity: 0; transform: translateY(10px); }
    to { opacity: 1; transform: translateY(0); }
}
.animated.fadeIn {
    animation: fadeIn 0.3s ease-out forwards;
}
</style>

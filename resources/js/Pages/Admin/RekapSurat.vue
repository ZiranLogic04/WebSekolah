<script setup>
import { ref, computed } from 'vue';
import { Head, Link, router } from '@inertiajs/vue3';
import AdminLayout from '@/Layouts/AdminLayout.vue';

const props = defineProps({
    data: Array,
    filters: Object
});

const form = ref({
    start_date: props.filters?.start_date || '',
    end_date: props.filters?.end_date || '',
    type: props.filters?.type || 'all'
});

// Print Modal
const showPrintModal = ref(false);
const printMode = ref('tahunan'); // 'tahunan' or 'bulanan'
const printForm = ref({
    type: 'all'
});

// Generate options
const currentYear = new Date().getFullYear();
const currentMonth = new Date().getMonth() + 1;
const years = Array.from({ length: 20 }, (_, i) => currentYear - 10 + i);
const months = [
    { value: 1, label: 'Januari' },
    { value: 2, label: 'Februari' },
    { value: 3, label: 'Maret' },
    { value: 4, label: 'April' },
    { value: 5, label: 'Mei' },
    { value: 6, label: 'Juni' },
    { value: 7, label: 'Juli' },
    { value: 8, label: 'Agustus' },
    { value: 9, label: 'September' },
    { value: 10, label: 'Oktober' },
    { value: 11, label: 'November' },
    { value: 12, label: 'Desember' },
];

// Tahunan mode
const selectedYear = ref(currentYear);

// Bulanan mode - rentang lintas tahun
const startMonth = ref(currentMonth);
const startYear = ref(currentYear);
const endMonth = ref(currentMonth);
const endYear = ref(currentYear);

const computedItems = computed(() => {
    return props.data.map((item, index) => ({
        ...item,
        no: index + 1
    }));
});

const applyFilter = () => {
    router.get('/admin/rekap-surat', form.value, { preserveState: true, replace: true });
};

const openPrintModal = () => {
    printMode.value = 'tahunan';
    selectedYear.value = currentYear;
    startMonth.value = 1;
    startYear.value = currentYear;
    endMonth.value = currentMonth;
    endYear.value = currentYear;
    showPrintModal.value = true;
};

const closePrintModal = () => {
    showPrintModal.value = false;
};

const confirmPrint = () => {
    let startDate, endDate;
    
    if (printMode.value === 'tahunan') {
        startDate = `${selectedYear.value}-01-01`;
        endDate = `${selectedYear.value}-12-31`;
    } else {
        // Bulanan - rentang lintas tahun
        const startMonthPad = String(startMonth.value).padStart(2, '0');
        const endMonthPad = String(endMonth.value).padStart(2, '0');
        const lastDay = new Date(endYear.value, endMonth.value, 0).getDate();
        
        startDate = `${startYear.value}-${startMonthPad}-01`;
        endDate = `${endYear.value}-${endMonthPad}-${lastDay}`;
    }
    
    const query = new URLSearchParams({
        start_date: startDate,
        end_date: endDate,
        type: printForm.value.type
    }).toString();
    
    window.open(`/admin/rekap-surat/print?${query}`, '_blank');
    closePrintModal();
};

const formatDate = (dateString) => {
    if (!dateString) return '-';
    return new Date(dateString).toLocaleDateString('id-ID', {
        day: 'numeric',
        month: 'short',
        year: 'numeric'
    });
};

const getMonthLabel = (value) => {
    return months.find(m => m.value === value)?.label || '';
};
</script>

<template>
    <AdminLayout>
        <Head title="Rekap Surat" />
        
        <div class="content container-fluid">
            <!-- Radiant Header Card (sama dengan DataSiswa) -->
            <div class="card border-0 shadow-lg rounded-4 mb-4 overflow-hidden position-relative" style="background: linear-gradient(135deg, #4f46e5 0%, #3b82f6 100%);">
                <div class="card-body p-4 p-lg-5 position-relative z-1">
                    <div class="row align-items-center">
                        <div class="col-lg-8">
                            <div class="d-flex align-items-center gap-3 mb-2">
                                <div class="icon-box bg-white rounded-3 text-primary p-2">
                                    <i class="fas fa-book-open fs-3"></i>
                                </div>
                                <h2 class="fw-bold text-white mb-0 ls-tight">Rekapitulasi Surat</h2>
                            </div>
                            <p class="text-white-50 mb-0 fs-5">Buku agenda dan rekap seluruh surat masuk & keluar.</p>
                        </div>
                        <div class="col-lg-4 text-lg-end mt-3 mt-lg-0">
                            <button @click="openPrintModal" class="btn btn-light btn-lg rounded-pill shadow-sm px-4 fw-bold text-primary hover-elevate">
                                <i class="fas fa-print me-2"></i>Cetak Laporan
                            </button>
                        </div>
                    </div>
                </div>
                <!-- Decorative Background -->
                <div class="position-absolute bottom-0 end-0 opacity-10 me-n5 mb-n5">
                    <i class="fas fa-book-open" style="font-size: 10rem; color: white;"></i>
                </div>
            </div>

            <!-- Modern Filter Card -->
            <div class="card border-0 shadow-sm rounded-4 mb-4">
                <div class="card-body p-4">
                    <form @submit.prevent="applyFilter" class="row g-3">
                        <div class="col-lg-3 col-md-6">
                            <label class="form-label small text-muted">Dari Tanggal</label>
                            <input type="date" class="form-control rounded-pill" v-model="form.start_date">
                        </div>
                        <div class="col-lg-3 col-md-6">
                            <label class="form-label small text-muted">Sampai Tanggal</label>
                            <input type="date" class="form-control rounded-pill" v-model="form.end_date">
                        </div>
                        <div class="col-lg-3 col-md-6">
                            <label class="form-label small text-muted">Jenis Surat</label>
                            <select class="form-select rounded-pill" v-model="form.type">
                                <option value="all">Semua Surat</option>
                                <option value="masuk">Surat Masuk</option>
                                <option value="keluar">Surat Keluar</option>
                            </select>
                        </div>
                        <div class="col-lg-3 col-md-6 d-flex align-items-end">
                            <button type="submit" class="btn btn-primary rounded-pill w-100">
                                <i class="fas fa-search me-2"></i> Tampilkan
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Table Card -->
            <div class="card modern-table-card">
                <div class="card-header">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h5 class="card-title mb-0">Buku Agenda Surat</h5>
                            <p class="card-subtitle text-muted mb-0">{{ computedItems.length }} surat ditemukan</p>
                        </div>
                    </div>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-modern mb-0">
                            <thead>
                                <tr>
                                    <th width="60" class="text-center">No</th>
                                    <th class="text-center">Tanggal</th>
                                    <th class="text-start">No. Surat</th>
                                    <th class="text-start">Jenis / Asal</th>
                                    <th class="text-start">Perihal</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-if="computedItems.length === 0">
                                    <td colspan="5" class="text-center py-5">
                                        <div class="empty-state">
                                            <div class="empty-icon mb-3">
                                                <i class="fas fa-file-alt fa-3x text-muted"></i>
                                            </div>
                                            <h5 class="mb-2">Tidak ada data surat</h5>
                                            <p class="text-muted mb-0">Ubah filter untuk mencari data</p>
                                        </div>
                                    </td>
                                </tr>
                                <tr v-for="item in computedItems" :key="item.no">
                                    <td class="text-center fw-medium text-muted">{{ item.no }}</td>
                                    <td class="text-center text-muted">{{ formatDate(item.date) }}</td>
                                    <td class="text-start fw-semibold">{{ item.no_surat }}</td>
                                    <td class="text-start">
                                        <span class="badge" :class="item.jenis === 'Surat Masuk' ? 'bg-info' : 'bg-success'">
                                            {{ item.jenis }}
                                        </span>
                                        <div class="small text-muted mt-1">{{ item.pengirim_penerima }}</div>
                                    </td>
                                    <td class="text-start">
                                        <div class="text-truncate" style="max-width: 250px;" :title="item.perihal">
                                            {{ item.perihal }}
                                        </div>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Print Settings Modal -->
        <div v-if="showPrintModal" class="modal fade show d-block" tabindex="-1" style="background: rgba(0,0,0,0.5); z-index: 1050">
            <div class="modal-dialog modal-dialog-centered" style="z-index: 1060">
                <div class="modal-content modern-modal">
                    <div class="modal-header">
                        <div class="modal-title">
                            <i class="modal-icon fas fa-print"></i>
                            <div>
                                <h5>Cetak Laporan Surat</h5>
                                <p class="modal-subtitle text-muted">Pilih periode dan jenis surat untuk dicetak</p>
                            </div>
                        </div>
                        <button type="button" class="btn-close" @click="closePrintModal"></button>
                    </div>
                    <div class="modal-body">
                        <!-- Mode Selection -->
                        <div class="mb-4">
                            <h6 class="section-title text-primary border-bottom pb-2 mb-3">
                                <i class="fas fa-calendar-alt me-2"></i>Periode Laporan
                            </h6>
                            <div class="btn-group w-100" role="group">
                                <input type="radio" class="btn-check" id="modeYear" value="tahunan" v-model="printMode">
                                <label class="btn btn-outline-primary" for="modeYear">
                                    <i class="fas fa-calendar me-2"></i>Tahunan
                                </label>
                                <input type="radio" class="btn-check" id="modeMonth" value="bulanan" v-model="printMode">
                                <label class="btn btn-outline-primary" for="modeMonth">
                                    <i class="fas fa-calendar-week me-2"></i>Rentang Bulan
                                </label>
                            </div>
                        </div>

                        <!-- Tahunan Mode -->
                        <div v-if="printMode === 'tahunan'" class="mb-4">
                            <label class="form-label fw-bold">Pilih Tahun</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="fas fa-calendar-alt"></i></span>
                                <select class="form-select" v-model="selectedYear">
                                    <option v-for="year in years" :key="year" :value="year">{{ year }}</option>
                                </select>
                            </div>
                        </div>

                        <!-- Bulanan Mode - Rentang -->
                        <div v-if="printMode === 'bulanan'" class="mb-4">
                            <div class="row g-3">
                                <div class="col-12">
                                    <label class="form-label fw-bold"><i class="fas fa-play me-1 text-success"></i> Dari</label>
                                </div>
                                <div class="col-6">
                                    <select class="form-select" v-model="startMonth">
                                        <option v-for="month in months" :key="month.value" :value="month.value">
                                            {{ month.label }}
                                        </option>
                                    </select>
                                </div>
                                <div class="col-6">
                                    <select class="form-select" v-model="startYear">
                                        <option v-for="year in years" :key="year" :value="year">{{ year }}</option>
                                    </select>
                                </div>
                            </div>
                            <div class="row g-3 mt-2">
                                <div class="col-12">
                                    <label class="form-label fw-bold"><i class="fas fa-stop me-1 text-danger"></i> Sampai</label>
                                </div>
                                <div class="col-6">
                                    <select class="form-select" v-model="endMonth">
                                        <option v-for="month in months" :key="month.value" :value="month.value">
                                            {{ month.label }}
                                        </option>
                                    </select>
                                </div>
                                <div class="col-6">
                                    <select class="form-select" v-model="endYear">
                                        <option v-for="year in years" :key="year" :value="year">{{ year }}</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <!-- Type Selection -->
                        <div class="mb-4">
                            <h6 class="section-title text-primary border-bottom pb-2 mb-3">
                                <i class="fas fa-filter me-2"></i>Jenis Surat
                            </h6>
                            <div class="input-group">
                                <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                                <select class="form-select" v-model="printForm.type">
                                    <option value="all">Semua Surat (Masuk & Keluar)</option>
                                    <option value="masuk">Surat Masuk Saja</option>
                                    <option value="keluar">Surat Keluar Saja</option>
                                </select>
                            </div>
                        </div>

                        <!-- Preview Info -->
                        <div class="alert alert-primary border-0 shadow-sm mb-0">
                            <div class="d-flex align-items-center">
                                <i class="fas fa-file-pdf fa-2x me-3 text-primary"></i>
                                <div>
                                    <strong>Periode Cetak:</strong>
                                    <div v-if="printMode === 'tahunan'">
                                        Januari - Desember <strong>{{ selectedYear }}</strong>
                                    </div>
                                    <div v-else>
                                        {{ getMonthLabel(startMonth) }} {{ startYear }} - {{ getMonthLabel(endMonth) }} {{ endYear }}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-light" @click="closePrintModal">
                            <i class="fas fa-times me-1"></i> Batal
                        </button>
                        <button type="button" class="btn btn-primary" @click="confirmPrint">
                            <i class="fas fa-print me-1"></i> Cetak Laporan
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </AdminLayout>
</template>

<style scoped>
.filter-card {
    border-radius: 10px;
    box-shadow: 0 2px 10px rgba(0,0,0,0.08);
    border: none;
}

.card-header {
    background: #fff;
    border-bottom: 1px solid #e9ecef;
    padding: 1rem 1.25rem;
}

.empty-state {
    padding: 2rem;
}

.empty-icon {
    opacity: 0.5;
}

.modal-content {
    border-radius: 12px;
}

.form-check-input:checked {
    background-color: #3d5ee1;
    border-color: #3d5ee1;
}
</style>

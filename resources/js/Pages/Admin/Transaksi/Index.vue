<template>
    <AdminLayout>
        <div class="content container-fluid">
            <!-- Radiant Header Card -->
            <div class="card border-0 shadow-lg rounded-4 mb-4 overflow-hidden position-relative" style="background: linear-gradient(135deg, #4f46e5 0%, #3b82f6 100%);">
                <div class="card-body p-4 p-lg-5 position-relative z-1">
                    <div class="row align-items-center">
                        <div class="col-lg-8">
                            <div class="d-flex align-items-center gap-3 mb-2">
                                <div class="icon-box bg-white rounded-3 text-primary p-2">
                                    <i class="fas fa-cash-register fs-3"></i>
                                </div>
                                <h2 class="fw-bold text-white mb-0 ls-tight">Transaksi Pemasukan</h2>
                            </div>
                            <p class="text-white-50 mb-0 fs-5">Kelola pembayaran tagihan siswa dengan mudah dan cepat.</p>
                        </div>
                    </div>
                </div>
                <!-- Decorative Background -->
                <div class="position-absolute bottom-0 end-0 opacity-10 me-n5 mb-n5">
                    <i class="fas fa-cash-register" style="font-size: 10rem; color: white;"></i>
                </div>
            </div>

            <div class="row">
                <!-- Student Search Section -->
                <div class="col-lg-4 mb-4">
                    <div class="card border-0 shadow-sm rounded-4">
                        <div class="card-body p-4">
                            <h5 class="card-title mb-3 fw-bold"><i class="fas fa-search me-2 text-primary"></i>Cari Siswa</h5>
                            <div class="form-group relative">
                                <input type="text" class="form-control rounded-pill ps-4" placeholder="Ketik Nama / NIS..." v-model="searchQuery" @focus="showDropdown = true">
                                <i class="fas fa-search position-absolute top-50 end-0 translate-middle-y me-3 text-muted"></i>
                                
                                <!-- Dropdown Results -->
                                <div class="search-dropdown shadow-sm" v-if="showDropdown && filteredStudents.length > 0">
                                    <ul class="list-group list-group-flush">
                                        <li class="list-group-item list-group-item-action cursor-pointer" 
                                            v-for="student in filteredStudents" 
                                            :key="student.id"
                                            @click="selectStudent(student)">
                                            <div class="fw-bold">{{ student.nama }}</div>
                                            <small class="text-muted">{{ student.nis }} - Kelas {{ student.kelas }}</small>
                                        </li>
                                    </ul>
                                </div>
                                <div class="search-dropdown shadow-sm p-3 text-center text-muted" v-if="showDropdown && searchQuery && filteredStudents.length === 0">
                                    Siswa tidak ditemukan.
                                </div>
                            </div>

                            <div v-if="selectedStudent" class="mt-4 animated fadeIn">
                                <div class="student-info-card bg-light p-3 rounded">
                                    <div class="d-flex align-items-center mb-3">
                                        <div class="avatar-circle me-3">{{ selectedStudent.nama.charAt(0) }}</div>
                                        <div>
                                            <h6 class="mb-0 fw-bold">{{ selectedStudent.nama }}</h6>
                                            <small class="text-muted">{{ selectedStudent.kelas }}</small>
                                        </div>
                                    </div>
                                    <hr>
                                    <div class="d-flex justify-content-between mb-1">
                                        <span>NIS:</span>
                                        <span class="fw-medium">{{ selectedStudent.nis }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Bills Section -->


                <!-- Transaction History & Bills -->
                <div class="col-md-8">
                    <!-- Tabs -->
                    <ul class="nav nav-tabs nav-tabs-bottom mb-3" id="transaksiTabs" role="tablist">
                        <li class="nav-item" role="presentation">
                            <button class="nav-link active" id="bills-tab" data-bs-toggle="tab" data-bs-target="#bills" type="button" role="tab" aria-selected="true">Tagihan Aktif</button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="history-tab" data-bs-toggle="tab" data-bs-target="#history" type="button" role="tab" aria-selected="false">Riwayat Pembayaran</button>
                        </li>
                    </ul>

                    <div class="tab-content">
                        <!-- Bills Tab -->
                        <div class="tab-pane fade show active" id="bills" role="tabpanel">
                            <div class="card">
                                <div class="card-body">
                                    <div v-if="!selectedStudent" class="text-center py-5 text-muted">
                                        <i class="fas fa-user-graduate fa-3x mb-3 opacity-50"></i>
                                        <p>Silakan pilih siswa terlebih dahulu untuk melihat tagihan.</p>
                                    </div>

                                    <div v-else-if="isLoadingBills" class="text-center py-5">
                                        <div class="spinner-border text-primary" role="status">
                                            <span class="visually-hidden">Loading...</span>
                                        </div>
                                    </div>

                                    <div v-else-if="bills.length === 0" class="text-center py-5">
                                        <i class="fas fa-check-circle fa-3x mb-3 text-success"></i>
                                        <p>Belum ada tagihan untuk siswa ini.</p>
                                    </div>

                                    <div v-else class="table-responsive">
                                        <table class="table table-hover table-center mb-0">
                                            <thead class="table-light">
                                                <tr>
                                                    <th>Tagihan</th>
                                                    <th>Sisa Bayar</th>
                                                    <th>Jatuh Tempo</th>
                                                    <th class="text-end">Aksi</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr v-for="bill in bills" :key="bill.id">
                                                    <td>
                                                        <div class="fw-bold">{{ bill.nama_tagihan }}</div>
                                                        <small class="text-muted">Total: {{ formatRupiah(bill.jumlah) }}</small>
                                                    </td>
                                                    <td class="fw-bold" :class="bill.sisa > 0 ? 'text-danger' : 'text-success'">
                                                        {{ bill.sisa > 0 ? formatRupiah(bill.sisa) : 'Lunas' }}
                                                    </td>
                                                    <td>
                                                        <span class="badge" :class="isOverdue(bill.tenggat_waktu) ? 'bg-danger' : 'bg-info'">
                                                            {{ bill.tenggat_waktu }}
                                                        </span>
                                                    </td>
                                                    <td class="text-end">
                                                        <button class="btn btn-sm" :class="bill.sisa > 0 ? 'btn-primary' : 'btn-secondary'" 
                                                            @click="openPaymentModal(bill)" :disabled="bill.sisa <= 0">
                                                            <i class="fas" :class="bill.sisa > 0 ? 'fa-money-bill-wave' : 'fa-check'"></i>
                                                            {{ bill.sisa > 0 ? 'Bayar' : 'Lunas' }}
                                                        </button>
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- History Tab -->
                        <div class="tab-pane fade" id="history" role="tabpanel">
                             <div class="card">
                                <div class="card-body">
                                    <div v-if="!selectedStudent">
                                        <div v-if="latestTransactions.data.length === 0" class="text-center py-5 text-muted">
                                            <p>Belum ada riwayat transaksi terbaru.</p>
                                        </div>
                                        <div v-else>
                                            <h6 class="mb-3 text-primary"><i class="fas fa-history me-1"></i> Riwayat Transaksi Terbaru (Semua Siswa)</h6>
                                            <div class="table-responsive">
                                                <table class="table table-hover table-center mb-0">
                                                    <thead class="table-light">
                                                        <tr>
                                                            <th>Kode</th>
                                                            <th>Siswa</th>
                                                            <th>Tagihan</th>
                                                            <th>Jumlah Bayar</th>
                                                            <th>Metode</th>
                                                            <th>Tanggal</th>
                                                            <th class="text-end">Aksi</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <tr v-for="trx in latestTransactions.data" :key="trx.id">
                                                            <td>{{ trx.kode_transaksi }}</td>
                                                            <td>
                                                                <div class="fw-bold">{{ trx.tagihan?.siswa?.nama || 'Siswa Dihapus' }}</div>
                                                                <small class="text-muted">{{ trx.tagihan?.siswa?.kelas || '-' }}</small>
                                                            </td>
                                                            <td>{{ trx.tagihan?.nama_tagihan || '-' }}</td>
                                                            <td class="text-success fw-bold">{{ formatRupiah(trx.jumlah_bayar) }}</td>
                                                            <td>
                                                                <span class="badge" :class="trx.metode_pembayaran === 'Transfer' ? 'bg-info' : 'bg-success'">
                                                                    {{ trx.metode_pembayaran || 'Cash' }}
                                                                </span>
                                                            </td>
                                                            <td>{{ new Date(trx.tanggal_bayar).toLocaleDateString('id-ID') }}</td>
                                                            <td class="text-end">
                                                                <button class="btn btn-outline-secondary btn-sm" @click="reprintReceipt(trx)">
                                                                    <i class="fas fa-print"></i>
                                                                </button>
                                                            </td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </div>
                                            <div class="mt-4 d-flex justify-content-end">
                                                <Pagination :links="latestTransactions.links" />
                                            </div>
                                        </div>
                                    </div>
                                    <div v-else-if="isLoadingHistory" class="text-center py-5">
                                        <div class="spinner-border text-primary" role="status">
                                            <span class="visually-hidden">Loading...</span>
                                        </div>
                                    </div>
                                    <div v-else-if="transactions.length === 0" class="text-center py-5 text-muted">
                                        <p>Belum ada riwayat transaksi.</p>
                                    </div>
                                    <div v-else class="table-responsive">
                                        <table class="table table-hover table-center mb-0">
                                            <thead class="table-light">
                                                <tr>
                                                    <th>Kode</th>
                                                    <th>Tagihan</th>
                                                    <th>Jumlah Bayar</th>
                                                    <th>Metode</th>
                                                    <th>Tanggal</th>
                                                    <th class="text-end">Aksi</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr v-for="trx in transactions" :key="trx.id">
                                                    <td>{{ trx.kode_transaksi }}</td>
                                                    <td>{{ trx.tagihan?.nama_tagihan || '-' }}</td>
                                                    <td class="text-success fw-bold">{{ formatRupiah(trx.jumlah_bayar) }}</td>
                                                    <td>
                                                        <span class="badge" :class="trx.metode_pembayaran === 'Transfer' ? 'bg-info' : 'bg-success'">
                                                            {{ trx.metode_pembayaran || 'Cash' }}
                                                        </span>
                                                    </td>
                                                    <td>{{ new Date(trx.tanggal_bayar).toLocaleDateString('id-ID') }}</td>
                                                    <td class="text-end">
                                                        <button class="btn btn-outline-secondary btn-sm" @click="reprintReceipt(trx)">
                                                            <i class="fas fa-print"></i>
                                                        </button>
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
            </div>

            <!-- Payment Modal -->
            <div class="modal fade" id="paymentModal" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content border-0 shadow-lg payment-modal">
                        <div class="modal-header border-0 pb-0">
                            <h5 class="modal-title fw-bold">
                                <i class="fas fa-wallet text-primary me-2"></i>Konfirmasi Pembayaran
                            </h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body p-4" v-if="selectedBill">
                            <!-- Bill Info Card -->
                            <div class="bill-info-card bg-light p-3 rounded-3 mb-4 text-center">
                                <span class="d-block text-muted small mb-1">Pembayaran Tagihan</span>
                                <h6 class="fw-bold mb-2 text-dark">{{ selectedBill.nama_tagihan }}</h6>
                                <div class="amount-display text-primary fw-bold display-6">
                                    {{ formatRupiah(selectedBill.sisa) }}
                                </div>
                                <span class="badge bg-warning text-dark mt-2">Sisa Tagihan</span>
                            </div>

                            <form @submit.prevent="submitPayment">

                                <div class="form-group mb-4">
                                    <label class="form-label fw-semibold">Nominal Pembayaran</label>
                                    <div class="input-group input-group-lg payment-input-group">
                                        <span class="input-group-text bg-white text-muted border-end-0 pe-2">Rp</span>
                                        <input type="text" class="form-control border-start-0 ps-1 fw-bold text-dark fs-4" v-model="displayAmount" @input="formatInputAmount" required placeholder="0">
                                    </div>
                                    <div class="d-flex justify-content-between align-items-center mt-2">
                                        <small class="text-muted">Maksimal: {{ formatRupiah(selectedBill.sisa) }}</small>
                                        <button type="button" class="btn btn-sm btn-outline-primary rounded-pill px-3" @click="setFullAmount">
                                            Bayar Full
                                        </button>
                                    </div>
                                </div>
                                
                                <div class="form-group mb-4">
                                    <label class="form-label fw-semibold mb-2">Metode Pembayaran</label>
                                    <div class="d-flex gap-3">
                                        <label class="payment-method-radio flex-fill">
                                            <input type="radio" v-model="form.metode_pembayaran" value="Cash" class="visually-hidden">
                                            <div class="payment-card p-3 rounded-3 border text-center cursor-pointer h-100 d-flex flex-column align-items-center justify-content-center" :class="{ 'active': form.metode_pembayaran === 'Cash' }">
                                                <i class="fas fa-money-bill-wave d-block mb-2 fa-2x"></i>
                                                <span class="fw-medium">Tunai</span>
                                            </div>
                                        </label>
                                        <label class="payment-method-radio flex-fill">
                                            <input type="radio" v-model="form.metode_pembayaran" value="Transfer" class="visually-hidden">
                                            <div class="payment-card p-3 rounded-3 border text-center cursor-pointer h-100 d-flex flex-column align-items-center justify-content-center" :class="{ 'active': form.metode_pembayaran === 'Transfer' }">
                                                <i class="fas fa-university d-block mb-2 fa-2x"></i>
                                                <span class="fw-medium">Transfer</span>
                                            </div>
                                        </label>
                                    </div>
                                </div>

                                <div class="d-grid gap-2">
                                    <button type="submit" class="btn btn-primary btn-lg btn-process shadow-sm" :disabled="form.processing">
                                        <span v-if="form.processing"><i class="fas fa-spinner fa-spin me-2"></i>Memproses...</span>
                                        <span v-else><i class="fas fa-check-circle me-2"></i>Bayar Sekarang</span>
                                    </button>
                                    <button type="button" class="btn btn-light btn-lg text-muted" data-bs-dismiss="modal">Batal</button>
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
import { Link, useForm } from '@inertiajs/vue3';
import AdminLayout from '@/Layouts/AdminLayout.vue';
import axios from 'axios';
import Swal from 'sweetalert2';
import Pagination from '@/Components/Pagination.vue';

const props = defineProps({
    students: Array,
    sekolah: Object,
    students: Array,
    sekolah: Object,
    latestTransactions: Object
});

const searchQuery = ref('');
const showDropdown = ref(false);
const selectedStudent = ref(null);
const bills = ref([]);
const transactions = ref([]);
const isLoadingBills = ref(false);
const isLoadingHistory = ref(false);
const selectedBill = ref(null);
const paymentModal = ref(null);
const displayAmount = ref('');

const form = useForm({
    tagihan_id: '',
    jumlah_bayar: '',
    metode_pembayaran: 'Cash',
    keterangan: ''
});

// Format input currency
const formatInputAmount = (event) => {
    let value = event.target.value.replace(/\D/g, '');
    if (value) {
        form.jumlah_bayar = value;
        displayAmount.value = new Intl.NumberFormat('id-ID').format(value);
    } else {
        form.jumlah_bayar = '';
        displayAmount.value = '';
    }
};

// Filter students for autocomplete
const filteredStudents = computed(() => {
    if (!searchQuery.value) return [];
    const query = searchQuery.value.toLowerCase();
    // Return max 10 results to not lag UI
    return props.students.filter(s => 
        s.nama.toLowerCase().includes(query) || 
        s.nis.toLowerCase().includes(query)
    ).slice(0, 10);
});

onMounted(() => {
    const modalEl = document.getElementById('paymentModal');
    if (window.bootstrap) {
        paymentModal.value = new window.bootstrap.Modal(modalEl);
    }

    // Check URL params for page, if exists switch to history tab
    const urlParams = new URLSearchParams(window.location.search);
    if (urlParams.has('page')) {
        const historyTabBtn = document.getElementById('history-tab');
        if (historyTabBtn) {
            const tab = new window.bootstrap.Tab(historyTabBtn);
            tab.show();
        }
    }

    // Check for student_id to auto-select student
    if (urlParams.has('student_id')) {
        const studentId = parseInt(urlParams.get('student_id'));
        const student = props.students.find(s => s.id === studentId);
        if (student) {
            selectStudent(student);
        }
    }

    // Close dropdown when clicking outside
    document.addEventListener('click', (e) => {
        if (!e.target.closest('.search-dropdown') && !e.target.closest('.form-group.relative')) {
            showDropdown.value = false;
        }
    });
});

const selectStudent = (student) => {
    selectedStudent.value = student;
    searchQuery.value = student.nama;
    showDropdown.value = false;
    fetchBills(student.id);
    fetchTransactions(student.id);
};

const fetchBills = async (siswaId) => {
    isLoadingBills.value = true;
    try {
        const response = await axios.get(`/admin/api/siswa/${siswaId}/tagihan`);
        bills.value = response.data;
    } catch (error) {
        console.error('Error fetching bills:', error);
        Swal.fire('Error', 'Gagal memuat tagihan siswa', 'error');
    } finally {
        isLoadingBills.value = false;
    }
};

const fetchTransactions = async (siswaId) => {
    isLoadingHistory.value = true;
    try {
        const response = await axios.get(`/admin/api/siswa/${siswaId}/transaksi`);
        transactions.value = response.data;
        console.log('Transactions updated:', transactions.value);
    } catch (error) {
        console.error('Error fetching transactions:', error);
    } finally {
        isLoadingHistory.value = false;
    }
};

const openPaymentModal = (bill) => {
    selectedBill.value = bill;
    form.tagihan_id = bill.id;
    form.jumlah_bayar = bill.sisa; // Default to full amount
    displayAmount.value = new Intl.NumberFormat('id-ID').format(bill.sisa);
    paymentModal.value.show();
};

const setFullAmount = () => {
    if (!selectedBill.value) return;
    form.jumlah_bayar = selectedBill.value.sisa;
    displayAmount.value = formatRupiah(selectedBill.value.sisa).replace('Rp', '').trim();
};

const submitPayment = () => {
    form.post('/admin/transaksi', {
        preserveState: true,
        preserveScroll: true,
        onSuccess: (page) => {
            paymentModal.value.hide();
            fetchBills(selectedStudent.value.id); // Refresh bills
            fetchTransactions(selectedStudent.value.id); // Refresh history
            
            // Switch to History Tab to show the new transaction
            const historyTabBtn = document.getElementById('history-tab');
            if (historyTabBtn) {
                const tab = new window.bootstrap.Tab(historyTabBtn);
                tab.show();
            }

            form.reset();
            displayAmount.value = '';
            
            // Show success and Print Receipt Option
            const flash = page.props.flash;
            const printData = flash.print_data;
            console.log('DEBUG Print Data:', printData);
            console.log('DEBUG Saldo Sekolah:', printData?.saldo_sekolah);
            
            if (printData) {
                Swal.fire({
                    title: 'Pembayaran Berhasil!',
                    html: `
                        <p>Kode Transaksi: <b>${printData.transaksi.kode_transaksi}</b></p>
                        <div class="text-start p-3 bg-light rounded">
                            <div class="d-flex justify-content-between mb-1">
                                <span>Saldo Awal:</span>
                                <span>${formatRupiah(printData.saldo_awal)}</span>
                            </div>
                            <div class="d-flex justify-content-between mb-2 text-success fw-bold">
                                <span>+ Pembayaran:</span>
                                <span>${formatRupiah(printData.transaksi.jumlah_bayar)}</span>
                            </div>
                            <div class="border-top pt-2 d-flex justify-content-between fw-bold">
                                <span>Saldo Saat Ini:</span>
                                <span class="text-primary">${formatRupiah(printData.saldo_sekolah)}</span>
                            </div>
                        </div>
                    `,
                    icon: 'success',
                    showCancelButton: true,
                    confirmButtonText: '<i class="fas fa-print"></i> Cetak Struk',
                    cancelButtonText: 'Tutup'
                }).then((result) => {
                    if (result.isConfirmed) {
                        printReceipt(printData);
                    }
                });
            }
        },
        onError: () => {
            Swal.fire('Gagal!', 'Transaksi gagal diproses.', 'error');
        }
    });
};

const reprintReceipt = (trx) => {
    // Construct printData object based on trx history
    const printData = {
        transaksi: trx,
        tagihan: trx.tagihan,
        siswa: selectedStudent.value || trx.tagihan?.siswa, // Handle global history where selectedStudent is null
        sekolah: props.sekolah // Use props data for history reprint
    };
    printReceipt(printData);
};

    const printReceipt = (data) => {
    // Generate receipt HTML
    const printWindow = window.open('', '_blank', 'width=800,height=600');
    
    // Sekolah Info (Fallback)
    const sekolahNama = data.sekolah?.nama_sekolah || 'SEKOLAH INDONESIA';

    printWindow.document.write(`
        <html>
        <head>
            <title>Struk Pembayaran - ${sekolahNama}</title>
            <style>
                @page { size: auto; margin: 0mm; }
                body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; font-size: 14px; margin: 20px; color: #333; }
                .receipt-container { max-width: 400px; margin: 0 auto; border: 1px solid #ddd; padding: 20px; box-shadow: 0 0 10px rgba(0,0,0,0.05); }
                .header { text-align: center; margin-bottom: 20px; }
                .header h3 { margin: 0; color: #3d5ee1; font-size: 18px; text-transform: uppercase; }
                .header p { margin: 5px 0 0; color: #666; font-size: 12px; }
                .divider { border-top: 2px dashed #eee; margin: 15px 0; }
                .info-row { display: flex; justify-content: space-between; margin-bottom: 8px; }
                .label { color: #666; font-size: 13px; }
                .value { font-weight: 600; text-align: right; }
                .total-section { background: #f8f9fa; padding: 10px; border-radius: 8px; margin-top: 15px; }
                .total-row { display: flex; justify-content: space-between; align-items: center; font-weight: bold; font-size: 16px; color: #3d5ee1; }
                .footer { text-align: center; margin-top: 25px; font-size: 11px; color: #999; }
                
                /* Print specific styles */
                @media print {
                    .receipt-container { border: none; box-shadow: none; padding: 0; }
                    body { margin: 0; padding: 10px; }
                }
            </style>
        </head>
        <body>
            <div class="receipt-container">
                <div class="header">
                    <h3>${sekolahNama}</h3>
                    <p>BUKTI PEMBAYARAN TAGIHAN</p>
                    <p style="font-size: 11px; margin-top: 2px;">${new Date(data.transaksi.tanggal_bayar).toLocaleString('id-ID')}</p>
                </div>
                
                <div class="divider"></div>
                
                <div class="info-row">
                    <span class="label">No. Transaksi</span>
                    <span class="value">${data.transaksi.kode_transaksi}</span>
                </div>
                <div class="info-row">
                    <span class="label">Siswa</span>
                    <span class="value">${data.siswa.nama}<br><span style="font-weight:normal; font-size:12px">Kelas ${data.siswa.kelas}</span></span>
                </div>
                <div class="info-row">
                    <span class="label">Pembayaran</span>
                    <span class="value">${data.tagihan.nama_tagihan}</span>
                </div>
                
                <div class="divider"></div>
                
                <div class="info-row">
                    <span class="label">Total Tagihan</span>
                    <span class="value">${formatRupiah(data.tagihan.jumlah)}</span>
                </div>
                <div class="info-row">
                    <span class="label">Sisa Sebelumnya</span>
                    <span class="value">${formatRupiah(data.tagihan.sisa + data.transaksi.jumlah_bayar)}</span>
                </div>
                
                <div class="total-section">
                    <div class="total-row">
                        <span>DIBAYAR</span>
                        <span>${formatRupiah(data.transaksi.jumlah_bayar)}</span>
                    </div>
                    <div class="info-row mt-2" style="margin-bottom:0">
                        <span class="label">Metode</span>
                        <span class="value">${data.transaksi.metode_pembayaran || 'Cash'}</span>
                    </div>
                    <div class="info-row mt-2" style="margin-bottom:0">
                        <span class="label">Terbilang</span>
                        <span class="value" style="font-style:italic; font-size:12px; font-weight:normal; text-transform:capitalize">${terbilang(data.transaksi.jumlah_bayar)} Rupiah</span>
                    </div>
                </div>

                <div class="info-row mt-2" style="justify-content: flex-end; color: #dc3545; font-weight: bold;">
                    <span style="margin-right: 10px">Sisa Tagihan:</span>
                    <span>${formatRupiah(data.tagihan.sisa)}</span>
                </div>
                
                <div class="footer">
                    <p>Terima Kasih atas pembayaran Anda.</p>
                </div>
            </div>
        </body>
        </html>
    `);

    printWindow.onload = function() {
        setTimeout(() => {
            printWindow.print();
        }, 500);
    };
    printWindow.document.close();
};

/* Helper untuk terbilang (Sederhana) */
const terbilang = (angka) => {
    const bil = ["", "satu", "dua", "tiga", "empat", "lima", "enam", "tujuh", "delapan", "sembilan", "sepuluh", "sebelas"];
    let terbilang = "";
    if (angka < 12) {
        terbilang = " " + bil[angka];
    } else if (angka < 20) {
        terbilang = terbilangCalc(angka - 10) + " belas";
    } else if (angka < 100) {
        terbilang = terbilangCalc(Math.floor(angka / 10)) + " puluh" + terbilangCalc(angka % 10);
    } else if (angka < 200) {
        terbilang = " seratus" + terbilangCalc(angka - 100);
    } else if (angka < 1000) {
        terbilang = terbilangCalc(Math.floor(angka / 100)) + " ratus" + terbilangCalc(angka % 100);
    } else if (angka < 2000) {
        terbilang = " seribu" + terbilangCalc(angka - 1000);
    } else if (angka < 1000000) {
        terbilang = terbilangCalc(Math.floor(angka / 1000)) + " ribu" + terbilangCalc(angka % 1000);
    } else if (angka < 1000000000) {
        terbilang = terbilangCalc(Math.floor(angka / 1000000)) + " juta" + terbilangCalc(angka % 1000000);
    }
    return terbilang;
};

// Recursive helper for terbilang to avoid defining var inside
const terbilangCalc = (angka) => {
    const bil = ["", "satu", "dua", "tiga", "empat", "lima", "enam", "tujuh", "delapan", "sembilan", "sepuluh", "sebelas"];
    if (angka < 12) return " " + bil[angka];
    if (angka < 20) return terbilangCalc(angka - 10) + " belas";
    if (angka < 100) return terbilangCalc(Math.floor(angka / 10)) + " puluh" + terbilangCalc(angka % 10);
    if (angka < 200) return " seratus" + terbilangCalc(angka - 100);
    if (angka < 1000) return terbilangCalc(Math.floor(angka / 100)) + " ratus" + terbilangCalc(angka % 100);
    if (angka < 2000) return " seribu" + terbilangCalc(angka - 1000);
    if (angka < 1000000) return terbilangCalc(Math.floor(angka / 1000)) + " ribu" + terbilangCalc(angka % 1000);
    if (angka < 1000000000) return terbilangCalc(Math.floor(angka / 1000000)) + " juta" + terbilangCalc(angka % 1000000);
    return "";
};

const formatRupiah = (value) => {
    return new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR', minimumFractionDigits: 0 }).format(value);
};

const isOverdue = (dateString) => {
    if (!dateString) return false;
    return new Date(dateString) < new Date();
};
</script>

<style scoped>
.search-dropdown {
    position: absolute;
    top: 100%;
    left: 0;
    width: 100%;
    background: white;
    z-index: 1000;
    border: 1px solid rgba(0,0,0,.1);
    border-radius: 0 0 .5rem .5rem;
    max-height: 300px;
    overflow-y: auto;
}
.cursor-pointer { cursor: pointer; }
.avatar-circle {
    width: 45px;
    height: 45px;
    background: #4f46e5;
    color: white;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: bold;
    font-size: 1.2rem;
    transform: translateX(5px);
}

/* Payment Modal Styles */
.payment-modal {
    border-radius: 16px;
    overflow: hidden;
}
.bill-info-card {
    border: 1px solid #eef2f7;
}
.payment-method-radio input:checked + .payment-card {
    background-color: #f0f7ff;
    border-color: #3d5ee1 !important;
    color: #3d5ee1;
}
.payment-card {
    transition: all 0.2s ease;
    border: 1px solid #eef2f7;
}
.payment-card:hover {
    border-color: #3d5ee1;
    background-color: #f8f9fa;
}
.btn-process {
    background: linear-gradient(135deg, #3d5ee1 0%, #5b7df5 100%);
    border: none;
    transition: all 0.3s ease;
}
.btn-process:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(61, 94, 225, 0.3);
}
</style>

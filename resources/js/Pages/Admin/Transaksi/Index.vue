<template>
    <AdminLayout>
        <div class="content container-fluid">
            <div class="page-header">
                <div class="row align-items-center">
                    <div class="col">
                        <h3 class="page-title">Transaksi Pemasukan</h3>
                        <ul class="breadcrumb">
                            <li class="breadcrumb-item"><Link href="/dashboard">Dashboard</Link></li>
                            <li class="breadcrumb-item active">Transaksi</li>
                        </ul>
                    </div>
                </div>
            </div>

            <div class="row">
                <!-- Student Search Section -->
                <div class="col-md-4">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title mb-3">Cari Siswa</h5>
                            <div class="form-group relative">
                                <input type="text" class="form-control" placeholder="Ketik Nama / NIS..." v-model="searchQuery" @focus="showDropdown = true">
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
                                        <div v-if="latestTransactions.length === 0" class="text-center py-5 text-muted">
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
                                                            <th>Tanggal</th>
                                                            <th class="text-end">Aksi</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <tr v-for="trx in latestTransactions" :key="trx.id">
                                                            <td>{{ trx.kode_transaksi }}</td>
                                                            <td>
                                                                <div class="fw-bold">{{ trx.tagihan?.siswa?.nama || 'Siswa Dihapus' }}</div>
                                                                <small class="text-muted">{{ trx.tagihan?.siswa?.kelas || '-' }}</small>
                                                            </td>
                                                            <td>{{ trx.tagihan?.nama_tagihan || '-' }}</td>
                                                            <td class="text-success fw-bold">{{ formatRupiah(trx.jumlah_bayar) }}</td>
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
                                                    <th>Tanggal</th>
                                                    <th class="text-end">Aksi</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr v-for="trx in transactions" :key="trx.id">
                                                    <td>{{ trx.kode_transaksi }}</td>
                                                    <td>{{ trx.tagihan?.nama_tagihan || '-' }}</td>
                                                    <td class="text-success fw-bold">{{ formatRupiah(trx.jumlah_bayar) }}</td>
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
                    <div class="modal-content">
                        <div class="modal-header bg-primary text-white">
                            <h5 class="modal-title">Konfirmasi Pembayaran</h5>
                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body" v-if="selectedBill">
                            <div class="text-center mb-4">
                                <h6 class="text-muted mb-1">{{ selectedBill.nama_tagihan }}</h6>
                                <h3 class="fw-bold text-primary">{{ formatRupiah(selectedBill.sisa) }}</h3>
                                <small class="text-muted">Sisa Tagihan</small>
                            </div>

                            <form @submit.prevent="submitPayment">
                                <div class="form-group mb-3">
                                    <label class="form-label">Jumlah Bayar</label>
                                    <div class="input-group">
                                        <span class="input-group-text">Rp</span>
                                        <input type="text" class="form-control form-control-lg fw-bold" v-model="displayAmount" @input="formatInputAmount" required placeholder="0">
                                    </div>
                                    <small class="text-muted">Maksimal: {{ formatRupiah(selectedBill.sisa) }}</small>
                                </div>
                                
                                <div class="form-group mb-4">
                                    <label class="form-label">Metode Pembayaran</label>
                                    <select class="form-select" v-model="form.metode_pembayaran">
                                        <option value="Cash">Tunai (Cash)</option>
                                        <option value="Transfer">Transfer Bank</option>
                                    </select>
                                </div>

                                <div class="d-grid">
                                    <button type="submit" class="btn btn-primary btn-lg" :disabled="form.processing">
                                        <i class="fas fa-check-circle me-2"></i> Proses Pembayaran
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
import { Link, useForm } from '@inertiajs/vue3';
import AdminLayout from '@/Layouts/AdminLayout.vue';
import axios from 'axios';
import Swal from 'sweetalert2';

const props = defineProps({
    students: Array,
    sekolah: Object,
    latestTransactions: Array
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
    // Generate simple receipt HTML
    const printWindow = window.open('', '_blank', 'width=400,height=600');
    printWindow.document.write(`
        <html>
        <head>
            <title>Struk Pembayaran - Web Sekolah</title>
            <style>
                body { font-family: 'Courier New', Courier, monospace; font-size: 12px; padding: 20px; }
                .text-center { text-align: center; }
                .divider { border-top: 1px dashed #000; margin: 10px 0; }
                .bold { font-weight: bold; }
                .flex-between { display: flex; justify-content: space-between; }
                .mt-2 { margin-top: 10px; }
            </style>
        </head>
        <body>
            <div class="text-center">
                <h3 style="margin:0;">${data.sekolah?.nama_sekolah || 'WEB SEKOLAH'}</h3>
                <p style="margin:0;">Bukti Pembayaran</p>
            </div>
            <div class="divider"></div>
            <div>
                <p>No. Transaksi : ${data.transaksi.kode_transaksi}</p>
                <p>Tanggal : ${new Date(data.transaksi.tanggal_bayar).toLocaleString('id-ID')}</p>
                <p>Siswa : ${data.siswa.nama} (${data.siswa.kelas})</p>
                <p>Pembayaran : ${data.tagihan.nama_tagihan}</p>
            </div>
            <div class="divider"></div>
            <div class="flex-between">
                <span>Total Tagihan:</span>
                <span>${formatRupiah(data.tagihan.jumlah)}</span>
            </div>
            <div class="flex-between">
                <span>Pembayaran Ini:</span>
                <span>${formatRupiah(data.transaksi.jumlah_bayar)}</span>
            </div>
             <div class="flex-between mt-2">
                <span>Sisa Tagihan:</span>
                <span>${formatRupiah(data.tagihan.sisa)}</span>
            </div>
            <div class="divider"></div>
            <div class="flex-between bold">
                <span>TOTAL BAYAR</span>
                <span>${formatRupiah(data.transaksi.jumlah_bayar)}</span>
            </div>
            <div class="divider"></div>
            <div class="text-center mt-2">
                <p>Terima Kasih</p>
                <p>Simpan struk ini sebagai bukti pembayaran yang sah.</p>
            </div>
            <script>
                window.print();
                window.onafterprint = function() { window.close(); }
            <\/script>
        </body>
        </html>
    `);
    printWindow.document.close();
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
}
</style>

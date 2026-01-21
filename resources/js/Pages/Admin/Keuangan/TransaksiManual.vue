<template>
    <AdminLayout>
        <div class="content container-fluid">
            <!-- Radiant Header Card (sama dengan DataSiswa) -->
            <div class="card border-0 shadow-lg rounded-4 mb-4 overflow-hidden position-relative" style="background: linear-gradient(135deg, #4f46e5 0%, #3b82f6 100%);">
                <div class="card-body p-4 p-lg-5 position-relative z-1">
                    <div class="row align-items-center">
                        <div class="col-lg-8">
                            <div class="d-flex align-items-center gap-3 mb-2">
                                <div class="icon-box bg-white rounded-3 text-primary p-2">
                                    <i class="fas fa-exchange-alt fs-3"></i>
                                </div>
                                <h2 class="fw-bold text-white mb-0 ls-tight">Transaksi Manual</h2>
                            </div>
                            <p class="text-white-50 mb-0 fs-5">Catat pemasukan dan pengeluaran non-SPP secara manual.</p>
                        </div>
                        <div class="col-lg-4 text-lg-end mt-3 mt-lg-0">
                            <div class="d-inline-flex align-items-center bg-white bg-opacity-25 rounded-pill px-4 py-2">
                                <i class="fas fa-wallet me-2 text-white"></i>
                                <span class="text-white fw-bold">Saldo: {{ formatRupiah(saldoSekolah) }}</span>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Decorative Background -->
                <div class="position-absolute bottom-0 end-0 opacity-10 me-n5 mb-n5">
                    <i class="fas fa-exchange-alt" style="font-size: 10rem; color: white;"></i>
                </div>
            </div>

            <div class="row">
                <!-- Form Transaksi -->
                <div class="col-lg-5 mb-4">
                    <div class="card border-0 shadow-sm rounded-4">
                        <div class="card-header bg-white border-bottom p-4">
                            <h5 class="card-title mb-0 fw-bold">
                                <i class="fas fa-plus-circle me-2 text-primary"></i>Catat Transaksi Baru
                            </h5>
                        </div>
                        <div class="card-body p-4">
                            <form @submit.prevent="submitTransaksi">
                                <!-- Tipe Transaksi - Modern Button Group -->
                                <div class="mb-4">
                                    <label class="form-label fw-bold">Tipe Transaksi <span class="text-danger">*</span></label>
                                    <div class="btn-group w-100" role="group">
                                        <input type="radio" class="btn-check" id="tipeMasuk" value="Masuk" v-model="form.tipe">
                                        <label class="btn btn-outline-success" for="tipeMasuk">
                                            <i class="fas fa-arrow-down me-2"></i>Pemasukan
                                        </label>
                                        <input type="radio" class="btn-check" id="tipeKeluar" value="Keluar" v-model="form.tipe">
                                        <label class="btn btn-outline-danger" for="tipeKeluar">
                                            <i class="fas fa-arrow-up me-2"></i>Pengeluaran
                                        </label>
                                    </div>
                                </div>

                                <!-- Kategori (Input Text) -->
                                <div class="mb-3">
                                    <label class="form-label">Kategori <span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="fas fa-tag"></i></span>
                                        <input type="text" class="form-control" v-model="form.kategori" placeholder="Contoh: Sumbangan Wali Murid, Pembelian ATK">
                                    </div>
                                </div>

                                <!-- Jumlah (dengan format ribuan) -->
                                <div class="mb-3">
                                    <label class="form-label">Jumlah <span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <span class="input-group-text">Rp</span>
                                        <input 
                                            type="text" 
                                            class="form-control" 
                                            :value="formatInputNumber(form.jumlah)"
                                            @input="handleJumlahInput"
                                            placeholder="1.000.000"
                                        >
                                    </div>
                                </div>

                                <!-- Tanggal -->
                                <div class="mb-3">
                                    <label class="form-label">Tanggal <span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="fas fa-calendar"></i></span>
                                        <input type="date" class="form-control" v-model="form.tanggal">
                                    </div>
                                </div>

                                <!-- Keterangan -->
                                <div class="mb-4">
                                    <label class="form-label">Keterangan <span class="text-muted small">(Opsional)</span></label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="fas fa-comment-alt"></i></span>
                                        <textarea class="form-control" v-model="form.keterangan" rows="2" placeholder="Keterangan transaksi..."></textarea>
                                    </div>
                                </div>

                                <button type="submit" class="btn btn-primary w-100 rounded-pill py-2" :disabled="form.processing">
                                    <i class="fas fa-save me-2"></i>
                                    <span v-if="form.processing">Menyimpan...</span>
                                    <span v-else>Simpan Transaksi</span>
                                </button>
                            </form>
                        </div>
                    </div>
                </div>

                <!-- Riwayat Transaksi Manual -->
                <div class="col-lg-7 mb-4">
                    <div class="card border-0 shadow-sm rounded-4">
                        <div class="card-header bg-white border-bottom p-4 d-flex justify-content-between align-items-center">
                            <h5 class="card-title mb-0 fw-bold">
                                <i class="fas fa-history me-2 text-primary"></i>Riwayat Transaksi
                            </h5>
                            <Link href="/admin/uang-sekolah" class="btn btn-sm btn-outline-primary">
                                Lihat Semua <i class="fas fa-arrow-right ms-1"></i>
                            </Link>
                        </div>
                        <div class="card-body p-0">
                            <div class="table-responsive">
                                <table class="table table-hover mb-0">
                                    <thead class="table-light">
                                        <tr>
                                            <th>Tanggal</th>
                                            <th>Tipe</th>
                                            <th>Kategori</th>
                                            <th>Keterangan</th>
                                            <th class="text-end">Jumlah</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr v-for="item in riwayat.data" :key="item.id">
                                            <td>{{ formatTanggal(item.tanggal) }}</td>
                                            <td>
                                                <span :class="item.tipe === 'Masuk' ? 'badge bg-success' : 'badge bg-danger'">
                                                    <i :class="item.tipe === 'Masuk' ? 'fas fa-arrow-down' : 'fas fa-arrow-up'" class="me-1"></i>
                                                    {{ item.tipe === 'Masuk' ? 'Pemasukan' : 'Pengeluaran' }}
                                                </span>
                                            </td>
                                            <td>{{ item.kategori || '-' }}</td>
                                            <td>{{ item.keterangan || '-' }}</td>
                                            <td class="text-end fw-bold" :class="item.tipe === 'Masuk' ? 'text-success' : 'text-danger'">
                                                {{ item.tipe === 'Masuk' ? '+' : '-' }} {{ formatRupiah(item.jumlah) }}
                                            </td>
                                        </tr>
                                        <tr v-if="riwayat.data.length === 0">
                                            <td colspan="5" class="text-center text-muted py-4">
                                                <i class="fas fa-inbox fa-2x mb-2 d-block"></i>
                                                Belum ada transaksi manual
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                            <!-- Pagination -->
                            <div v-if="riwayat.last_page > 1" class="card-footer d-flex justify-content-between align-items-center">
                                <small class="text-muted">Halaman {{ riwayat.current_page }} dari {{ riwayat.last_page }}</small>
                                <nav>
                                    <ul class="pagination pagination-sm mb-0">
                                        <li class="page-item" :class="{ disabled: !riwayat.prev_page_url }">
                                            <Link :href="riwayat.prev_page_url || '#'" class="page-link">&laquo;</Link>
                                        </li>
                                        <li class="page-item" :class="{ disabled: !riwayat.next_page_url }">
                                            <Link :href="riwayat.next_page_url || '#'" class="page-link">&raquo;</Link>
                                        </li>
                                    </ul>
                                </nav>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AdminLayout>
</template>

<script setup>
import AdminLayout from '@/Layouts/AdminLayout.vue';
import { Link, useForm } from '@inertiajs/vue3';
import Swal from 'sweetalert2';

const props = defineProps({
    riwayat: Object, // Paginated object
    saldoSekolah: Number,
});

const form = useForm({
    tipe: 'Masuk',
    kategori: '',
    jumlah: '',
    tanggal: new Date().toISOString().split('T')[0],
    keterangan: '',
});

// Format angka untuk display di input (dengan separator ribuan)
const formatInputNumber = (value) => {
    if (!value) return '';
    const numValue = String(value).replace(/\D/g, '');
    return new Intl.NumberFormat('id-ID').format(numValue);
};

// Handle input jumlah - simpan angka tanpa format
const handleJumlahInput = (e) => {
    const rawValue = e.target.value.replace(/\D/g, '');
    form.jumlah = rawValue;
};

const formatRupiah = (value) => {
    return new Intl.NumberFormat('id-ID', {
        style: 'currency',
        currency: 'IDR',
        minimumFractionDigits: 0
    }).format(value);
};

const formatTanggal = (tanggal) => {
    return new Date(tanggal).toLocaleDateString('id-ID', {
        day: '2-digit',
        month: 'short',
        year: 'numeric'
    });
};

const submitTransaksi = () => {
    if (!form.jumlah || parseInt(form.jumlah) < 1) {
        Swal.fire({
            icon: 'error',
            title: 'Gagal!',
            text: 'Jumlah harus lebih dari 0'
        });
        return;
    }
    
    // Simpan nilai sebelum submit untuk ditampilkan di modal
    const isIncome = form.tipe === 'Masuk';
    const amount = parseInt(form.jumlah);
    const amountFormatted = formatRupiah(amount);
    
    // Hitung saldo sekolah baru
    const newSaldoSekolah = isIncome 
        ? (props.saldoSekolah + amount) 
        : (props.saldoSekolah - amount);

    form.post('/admin/transaksi-manual', {
        preserveScroll: true,
        onSuccess: () => {
            Swal.fire({
                icon: 'success',
                title: 'Transaksi Berhasil!',
                html: `
                    <div style="text-align: center; margin: 10px 0;">
                        <div style="font-size: 1.5rem; font-weight: bold; color: ${isIncome ? '#28a745' : '#dc3545'};">
                            ${isIncome ? '+' : '-'} ${amountFormatted}
                        </div>
                        <div style="margin-top: 10px; font-size: 0.9rem; color: #666;">
                            Saldo Sekolah: <strong style="color: #333;">${formatRupiah(newSaldoSekolah)}</strong>
                        </div>
                    </div>
                `,
                timer: 2500,
                showConfirmButton: false
            });
            form.reset();
            form.tipe = 'Masuk';
            form.tanggal = new Date().toISOString().split('T')[0];
        },
        onError: (errors) => {
            Swal.fire({
                icon: 'error',
                title: 'Gagal!',
                text: Object.values(errors).flat().join('\n')
            });
        }
    });
};
</script>

<style scoped>
/* Card Styling */
.card {
    border: none;
    border-radius: 10px;
    box-shadow: 0 2px 10px rgba(0,0,0,0.08);
}
.card-header {
    background-color: #fff;
    border-bottom: 1px solid #f0f0f0;
    padding: 1rem 1.25rem;
}
.card-title {
    font-weight: 600;
    color: #333;
}

/* Form Styling */
.form-label {
    font-weight: 600;
    font-size: 0.875rem;
    color: #444;
    margin-bottom: 0.5rem;
}
.form-control, .form-select {
    border-radius: 6px;
    border: 1px solid #ddd;
    padding: 0.625rem 0.875rem;
    font-size: 0.9rem;
}
.form-control:focus {
    border-color: #3d5ee1;
    box-shadow: 0 0 0 3px rgba(61, 94, 225, 0.15);
}

/* Input Group - Alignment Fix */
.input-group {
    align-items: stretch;
}
.input-group-text {
    display: flex;
    align-items: center;
    justify-content: center;
    background-color: #f8f9fa;
    border: 1px solid #ddd;
    border-radius: 6px 0 0 6px;
    font-weight: 600;
    color: #555;
    padding: 0.625rem 0.875rem;
    min-width: 50px;
}
.input-group .form-control {
    border-radius: 0 6px 6px 0;
}
.input-group textarea.form-control {
    border-radius: 0 6px 6px 0;
}
.input-group-text i {
    width: 14px;
    text-align: center;
}

/* Custom Radio Buttons */
.radio-group {
    display: flex;
    gap: 1.5rem;
}
.radio-item {
    display: flex;
    align-items: center;
    cursor: pointer;
    padding: 0.5rem 1rem;
    border-radius: 8px;
    border: 1px solid #e0e0e0;
    transition: all 0.2s ease;
    background: #fff;
}
.radio-item:hover {
    border-color: #3d5ee1;
    background: #f8f9ff;
}
.radio-item.active {
    border-color: #3d5ee1;
    background: #f0f4ff;
}
.radio-item input[type="radio"] {
    display: none;
}
.radio-circle {
    width: 18px;
    height: 18px;
    border: 2px solid #ccc;
    border-radius: 50%;
    margin-right: 8px;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
    transition: all 0.2s ease;
}
.radio-item.active .radio-circle {
    border-color: #3d5ee1;
}
.radio-item.active .radio-circle::after {
    content: '';
    width: 10px;
    height: 10px;
    background: #3d5ee1;
    border-radius: 50%;
}
.radio-label {
    display: flex;
    align-items: center;
    font-weight: 500;
    font-size: 0.9rem;
}
.radio-label i {
    margin-right: 6px;
}

/* Submit Button */
.btn-primary {
    background-color: #3d5ee1;
    border-color: #3d5ee1;
    padding: 0.75rem;
    font-weight: 600;
    border-radius: 6px;
}
.btn-primary:hover {
    background-color: #2c4fd1;
    border-color: #2c4fd1;
}

/* Table Styling */
.table-hover tbody tr:hover {
    background-color: #f8f9fa;
}
.table thead.table-light th {
    background-color: #3d5ee1 !important;
    color: #fff;
    font-weight: 600;
    font-size: 0.85rem;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    padding: 0.875rem;
    border: none;
}
.table tbody td {
    padding: 0.875rem;
    vertical-align: middle;
    font-size: 0.9rem;
}

/* Badge */
.badge {
    font-weight: 500;
    padding: 0.4em 0.65em;
    font-size: 0.8rem;
}

/* Simple Saldo Inline Display */
.saldo-inline {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    flex-wrap: wrap;
    font-size: 0.9rem;
    padding: 0.75rem 1rem;
    background: #f8f9fa;
    border-radius: 8px;
    border: 1px solid #e9ecef;
}
.saldo-item {
    display: inline-flex;
    align-items: center;
    gap: 0.35rem;
}
.saldo-item i {
    font-size: 0.8rem;
}
.saldo-separator {
    color: #ccc;
    margin: 0 0.5rem;
}
</style>

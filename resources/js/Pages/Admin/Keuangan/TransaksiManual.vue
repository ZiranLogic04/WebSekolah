<template>
    <AdminLayout>
        <div class="content container-fluid">
            <!-- Page Header -->
            <div class="page-header">
                <div class="row align-items-center">
                    <div class="col">
                        <h3 class="page-title">Transaksi Manual</h3>
                        <ul class="breadcrumb">
                            <li class="breadcrumb-item"><Link href="/admin/dashboard">Dashboard</Link></li>
                            <li class="breadcrumb-item"><Link href="/admin/uang-sekolah">Keuangan</Link></li>
                            <li class="breadcrumb-item active">Transaksi Manual</li>
                        </ul>
                    </div>
                </div>
            </div>

            <div class="row">
                <!-- Form Transaksi -->
                <div class="col-md-5">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="card-title mb-0">
                                <i class="fas fa-plus-circle me-2"></i>Catat Transaksi Baru
                            </h5>
                        </div>
                        <div class="card-body">
                            <form @submit.prevent="submitTransaksi">
                                <!-- Tipe Transaksi -->
                                <div class="mb-3">
                                    <label class="form-label">Tipe Transaksi <span class="text-danger">*</span></label>
                                    <div class="d-flex gap-3">
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" v-model="form.tipe" value="Masuk" id="tipeMasuk">
                                            <label class="form-check-label text-success" for="tipeMasuk">
                                                <i class="fas fa-arrow-down me-1"></i> Pemasukan
                                            </label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" v-model="form.tipe" value="Keluar" id="tipeKeluar">
                                            <label class="form-check-label text-danger" for="tipeKeluar">
                                                <i class="fas fa-arrow-up me-1"></i> Pengeluaran
                                            </label>
                                        </div>
                                    </div>
                                </div>

                                <!-- Kategori (Input Text) -->
                                <div class="mb-3">
                                    <label class="form-label">Kategori <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" v-model="form.kategori" required placeholder="Contoh: Sumbangan Wali Murid, Pembelian ATK, dll">
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
                                            required 
                                            placeholder="1.000.000"
                                        >
                                    </div>
                                </div>

                                <!-- Tanggal -->
                                <div class="mb-3">
                                    <label class="form-label">Tanggal <span class="text-danger">*</span></label>
                                    <input type="date" class="form-control" v-model="form.tanggal" required>
                                </div>

                                <!-- Keterangan -->
                                <div class="mb-4">
                                    <label class="form-label">Keterangan</label>
                                    <textarea class="form-control" v-model="form.keterangan" rows="3" placeholder="Keterangan transaksi (opsional)"></textarea>
                                </div>

                                <button type="submit" class="btn btn-primary w-100" :disabled="form.processing">
                                    <i class="fas fa-save me-1"></i>
                                    <span v-if="form.processing">Menyimpan...</span>
                                    <span v-else>Simpan Transaksi</span>
                                </button>
                            </form>
                        </div>
                    </div>
                </div>

                <!-- Riwayat Transaksi Manual -->
                <div class="col-md-7">
                    <div class="card">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <h5 class="card-title mb-0">
                                <i class="fas fa-history me-2"></i>Riwayat Transaksi Manual
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
                                        <tr v-for="item in riwayat" :key="item.id">
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
                                        <tr v-if="riwayat.length === 0">
                                            <td colspan="5" class="text-center text-muted py-4">
                                                <i class="fas fa-inbox fa-2x mb-2 d-block"></i>
                                                Belum ada transaksi manual
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
import AdminLayout from '@/Layouts/AdminLayout.vue';
import { Link, useForm } from '@inertiajs/vue3';
import Swal from 'sweetalert2';

const props = defineProps({
    riwayat: Array,
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
            title: 'Error!',
            text: 'Jumlah harus lebih dari 0'
        });
        return;
    }

    form.post('/admin/transaksi-manual', {
        preserveScroll: true,
        onSuccess: () => {
            Swal.fire({
                icon: 'success',
                title: 'Berhasil!',
                text: 'Transaksi berhasil dicatat.',
                timer: 1500,
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
.form-check-input:checked {
    background-color: #3d5ee1;
    border-color: #3d5ee1;
}
</style>

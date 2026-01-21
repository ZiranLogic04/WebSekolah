<template>
    <AdminLayout>
        <div class="content container-fluid">
            <div class="page-header">
                <div class="row align-items-center">
                    <div class="col">
                        <h3 class="page-title">Transaksi Pengeluaran</h3>
                        <ul class="breadcrumb">
                            <li class="breadcrumb-item"><Link href="/admin/uang-sekolah">Keuangan</Link></li>
                            <li class="breadcrumb-item active">Transaksi Pengeluaran</li>
                        </ul>
                    </div>
                </div>
            </div>

            <div class="row">
                <!-- Form Input Pengeluaran -->
                <div class="col-md-5">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="card-title mb-0">
                                <i class="fas fa-plus-circle text-danger me-2"></i>Catat Pengeluaran Baru
                            </h5>
                        </div>
                        <div class="card-body">
                            <form @submit.prevent="submitForm">
                                <div class="mb-3">
                                    <label class="form-label">Kategori Pengeluaran</label>
                                    <select class="form-select" v-model="form.kategori_id">
                                        <option value="">-- Pilih Kategori --</option>
                                        <option v-for="kat in kategoriPengeluaran" :key="kat.id" :value="kat.id">
                                            {{ kat.nama }}
                                        </option>
                                    </select>
                                    <small class="text-muted">
                                        <Link href="/admin/jenis-tagihan" class="text-primary">+ Tambah Jenis Tagihan Baru</Link>
                                    </small>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Jumlah (Rp) <span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <span class="input-group-text">Rp</span>
                                        <input type="number" class="form-control" v-model="form.jumlah" required min="1" placeholder="0">
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Tanggal <span class="text-danger">*</span></label>
                                    <input type="date" class="form-control" v-model="form.tanggal" required>
                                </div>
                                <div class="mb-4">
                                    <label class="form-label">Keterangan (Opsional)</label>
                                    <textarea class="form-control" v-model="form.sumber" rows="2" placeholder="Contoh: Beli spidol, Konsumsi rapat, dll"></textarea>
                                </div>
                                <div class="d-grid">
                                    <button type="submit" class="btn btn-danger btn-lg" :disabled="form.processing">
                                        <i class="fas fa-save me-2"></i>
                                        <span v-if="form.processing">Menyimpan...</span>
                                        <span v-else>Simpan Pengeluaran</span>
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                <!-- Riwayat Pengeluaran Terbaru -->
                <div class="col-md-7">
                    <div class="card">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <h5 class="card-title mb-0">
                                <i class="fas fa-history me-2"></i>Riwayat Pengeluaran Terbaru
                            </h5>
                            <Link href="/admin/uang-sekolah?tipe=Keluar" class="btn btn-sm btn-outline-primary">
                                Lihat Semua
                            </Link>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-hover">
                                    <thead>
                                        <tr>
                                            <th>Tanggal</th>
                                            <th>Kategori</th>
                                            <th>Keterangan</th>
                                            <th class="text-end">Jumlah</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr v-for="item in riwayat" :key="item.id">
                                            <td>{{ formatDate(item.tanggal) }}</td>
                                            <td>
                                                <span class="badge bg-danger" v-if="item.kategori">{{ item.kategori.nama }}</span>
                                                <span class="text-muted" v-else>-</span>
                                            </td>
                                            <td>{{ item.sumber || '-' }}</td>
                                            <td class="text-end text-danger fw-bold">-{{ formatRupiah(item.jumlah) }}</td>
                                        </tr>
                                        <tr v-if="riwayat.length === 0">
                                            <td colspan="4" class="text-center text-muted py-4">
                                                Belum ada data pengeluaran.
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                    <!-- Summary Card -->
                    <div class="card bg-danger text-white">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h6 class="text-white-50 mb-1">Total Pengeluaran Bulan Ini</h6>
                                    <h3 class="text-white mb-0">{{ formatRupiah(totalBulanIni) }}</h3>
                                </div>
                                <div>
                                    <i class="fas fa-arrow-up fa-2x opacity-50"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AdminLayout>
</template>

<script setup>
import { ref } from 'vue';
import { Link, useForm } from '@inertiajs/vue3';
import AdminLayout from '@/Layouts/AdminLayout.vue';
import Swal from 'sweetalert2';

const props = defineProps({
    kategoriPengeluaran: Array,
    riwayat: Array,
    totalBulanIni: Number
});

const form = useForm({
    kategori_id: '',
    sumber: '',
    jumlah: '',
    tanggal: new Date().toISOString().substr(0, 10),
});

const submitForm = () => {
    form.post('/admin/pengeluaran', {
        onSuccess: (page) => {
            const popupData = page.props.flash.popup_data;
            if (popupData) {
                Swal.fire({
                    title: 'Pengeluaran Berhasil Dicatat!',
                    html: `
                        <div class="text-start p-3 bg-light rounded">
                            <div class="d-flex justify-content-between mb-1">
                                <span>Saldo Awal:</span>
                                <span>${formatRupiah(popupData.saldo_awal)}</span>
                            </div>
                            <div class="d-flex justify-content-between mb-2 text-danger fw-bold">
                                <span>- Pengeluaran:</span>
                                <span>${formatRupiah(popupData.jumlah)}</span>
                            </div>
                            <div class="border-top pt-2 d-flex justify-content-between fw-bold">
                                <span>Saldo Saat Ini:</span>
                                <span class="text-primary">${formatRupiah(popupData.saldo_sekolah)}</span>
                            </div>
                        </div>
                    `,
                    icon: 'success'
                });
            } else {
                 Swal.fire('Berhasil!', 'Pengeluaran berhasil dicatat.', 'success');
            }
            
            form.reset();
            form.tanggal = new Date().toISOString().substr(0, 10);
        },
        onError: () => {
            Swal.fire('Gagal!', 'Terjadi kesalahan.', 'error');
        }
    });
};

const formatRupiah = (value) => {
    return new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR', minimumFractionDigits: 0 }).format(value);
};

const formatDate = (dateString) => {
    if (!dateString) return '-';
    return new Date(dateString).toLocaleDateString('id-ID');
};
</script>

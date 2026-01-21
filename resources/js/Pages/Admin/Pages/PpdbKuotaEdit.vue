<template>
    <AdminLayout>
        <div class="content container-fluid">
            <!-- Page Header -->
            <div class="page-header">
                <div class="row align-items-center">
                    <div class="col">
                        <h3 class="page-title">Edit Jadwal & Kuota PPDB</h3>
                        <ul class="breadcrumb">
                            <li class="breadcrumb-item"><Link href="/admin/pages">Halaman Khusus</Link></li>
                            <li class="breadcrumb-item active">PPDB Kuota</li>
                        </ul>
                    </div>
                    <div class="col-auto">
                        <Link href="/admin/pages" class="btn btn-outline-secondary me-2">
                            <i class="fas fa-arrow-left me-1"></i> Kembali
                        </Link>
                        <button class="btn btn-primary" @click="submit" :disabled="form.processing">
                            <i class="fas fa-save me-1"></i> 
                            <span v-if="form.processing">Menyimpan...</span>
                            <span v-else>Simpan Perubahan</span>
                        </button>
                    </div>
                </div>
            </div>

            <!-- Content -->
            <div class="row">
                <div class="col-lg-12">
                     <!-- Info Alert -->
                    <div class="alert alert-info border-0 shadow-sm mb-4">
                        <div class="d-flex align-items-center">
                            <div class="fs-1 me-3 text-info">
                                <i class="fas fa-calendar-alt"></i>
                            </div>
                            <div>
                                <h5 class="alert-heading mb-1">Informasi PPDB</h5>
                                <p class="mb-0 text-muted">Kelola jadwal pelaksanaan PPDB, kuota penerimaan siswa, serta estimasi biaya masuk.</p>
                            </div>
                        </div>
                    </div>

                    <form @submit.prevent="submit">
                        
                        <!-- Jadwal Repeater -->
                        <div class="card border-0 shadow-sm mb-4">
                            <div class="card-header bg-transparent border-bottom py-3 d-flex justify-content-between align-items-center">
                                <h5 class="mb-0 fw-bold text-primary"><i class="fas fa-calendar-alt me-2"></i>Jadwal Kegiatan</h5>
                                <button type="button" class="btn btn-sm btn-primary" @click="addJadwal">
                                    <i class="fas fa-plus me-1"></i> Tambah Jadwal
                                </button>
                            </div>
                            <div class="card-body">
                                <div v-if="form.jadwal.length === 0" class="text-center py-5 text-muted bg-light rounded">
                                     <i class="fas fa-calendar-times fa-3x mb-3 text-secondary opacity-50"></i>
                                    <p class="mb-0">Belum ada jadwal yang ditambahkan.</p>
                                </div>

                                <div class="row g-3">
                                    <div v-for="(item, index) in form.jadwal" :key="'jadwal-'+index" class="col-md-6 col-lg-4">
                                        <div class="card h-100 border shadow-sm">
                                            <div class="card-body p-3">
                                                <div class="d-flex justify-content-between mb-3 border-bottom pb-2">
                                                    <span class="badge bg-secondary">Tahap {{ index + 1 }}</span>
                                                    <button type="button" class="btn btn-sm text-danger p-0" @click="removeJadwal(index)" title="Hapus">
                                                        <i class="fas fa-times"></i>
                                                    </button>
                                                </div>
                                                <div class="mb-2">
                                                    <label class="form-label small text-muted">Nama Tahapan</label>
                                                    <input type="text" v-model="item.stage" class="form-control form-control-sm fw-bold" placeholder="Contoh: Pendaftaran">
                                                </div>
                                                <div class="mb-2">
                                                    <label class="form-label small text-muted">Waktu / Tanggal</label>
                                                    <div class="input-group input-group-sm">
                                                        <span class="input-group-text bg-white"><i class="fas fa-clock text-muted"></i></span>
                                                        <input type="text" v-model="item.range" class="form-control form-control-sm" placeholder="1 Feb - 31 Mar 2026">
                                                    </div>
                                                </div>
                                                <div>
                                                    <label class="form-label small text-muted">Keterangan Singkat</label>
                                                    <textarea v-model="item.desc" class="form-control form-control-sm" rows="2" placeholder="Keterangan..."></textarea>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Kuota & Biaya -->
                        <div class="row g-4 mb-4">
                            <div class="col-lg-6">
                                <div class="card border-0 shadow-sm h-100">
                                    <div class="card-header bg-transparent border-bottom py-3">
                                        <h5 class="mb-0 fw-bold text-success"><i class="fas fa-users me-2"></i>Kuota Siswa</h5>
                                    </div>
                                    <div class="card-body">
                                        <div class="mb-3">
                                            <label class="form-label">Total Kuota Penerimaan</label>
                                            <input type="text" v-model="form.kuota_total" class="form-control" placeholder="Contoh: 60 Siswa (2 Rombel)">
                                            <small class="text-muted">Jumlah total siswa yang akan diterima.</small>
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label">Status / Kuota Terisi</label>
                                            <input type="text" v-model="form.kuota_terisi" class="form-control" placeholder="Contoh: 15 Pendaftar / Pendaftaran dibuka...">
                                            <small class="text-muted">Informasi progress pendaftaran saat ini.</small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="card border-0 shadow-sm h-100">
                                    <div class="card-header bg-transparent border-bottom py-3">
                                        <h5 class="mb-0 fw-bold text-info"><i class="fas fa-money-bill-wave me-2"></i>Estimasi Biaya</h5>
                                    </div>
                                    <div class="card-body">
                                        <div class="mb-3">
                                            <label class="form-label">Biaya Pendaftaran</label>
                                            <div class="input-group">
                                                <span class="input-group-text bg-light">Rp</span>
                                                <input type="text" v-model="form.biaya_daftar" class="form-control" placeholder="100.000">
                                            </div>
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label">SPP Bulanan</label>
                                             <div class="input-group">
                                                <span class="input-group-text bg-light">Rp</span>
                                                <input type="text" v-model="form.biaya_spp" class="form-control" placeholder="0 (Gratis)">
                                            </div>
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label">Paket Seragam & Buku</label>
                                            <input type="text" v-model="form.biaya_paket" class="form-control" placeholder="Menyesuaikan koperasi sekolah">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="d-flex justify-content-end gap-2 pb-4">
                            <Link href="/admin/pages" class="btn btn-secondary">
                                <i class="fas fa-arrow-left me-1"></i> Kembali
                            </Link>
                            <button type="submit" class="btn btn-primary" :disabled="form.processing">
                                <i class="fas fa-save me-1"></i> Simpan
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </AdminLayout>
</template>

<script setup>
import { reactive } from 'vue';
import AdminLayout from '@/Layouts/AdminLayout.vue';
import { Link, router } from '@inertiajs/vue3';
import { showSuccess, showError } from '@/Utils/notifications';
import Swal from 'sweetalert2';

const props = defineProps({
    section: Object,
});

const defaultJadwal = [
    { stage: 'Pendaftaran', range: '1 Feb – 31 Mar 2026', desc: 'Daftar online/offline & unggah berkas' },
    { stage: 'Verifikasi', range: 'H+1 s.d. H+3 (rolling)', desc: 'Validasi berkas & konfirmasi WA/Email' },
    { stage: 'Observasi', range: 'Setiap Sabtu, 09.00–11.00', desc: 'Observasi siswa & wawancara orang tua' },
    { stage: 'Pengumuman', range: 'Setiap Senin, 12.00', desc: 'Hasil seleksi & penempatan sementara' },
    { stage: 'Daftar Ulang', range: 'Maks. 7 hari setelah pengumuman', desc: 'Administrasi awal & pengukuran seragam' },
];

const form = reactive({
    jadwal: props.section?.content?.jadwal || defaultJadwal,
    kuota_total: props.section?.content?.kuota_total || '60 Siswa (2 Rombel)',
    kuota_terisi: props.section?.content?.kuota_terisi || 'Pendaftaran dibuka Feb 2026',
    biaya_daftar: props.section?.content?.biaya_daftar || 'Gratis',
    biaya_spp: props.section?.content?.biaya_spp || 'Gratis (BOS)',
    biaya_paket: props.section?.content?.biaya_paket || 'Menyesuaikan Koperasi',
    processing: false
});

const addJadwal = () => {
    form.jadwal.push({ stage: '', range: '', desc: '' });
};

const removeJadwal = (index) => {
    form.jadwal.splice(index, 1);
};

const submit = () => {
    form.processing = true;
    router.put('/admin/pages/ppdb_kuota', {
        jadwal: JSON.stringify(form.jadwal),
        kuota_total: form.kuota_total,
        kuota_terisi: form.kuota_terisi,
        biaya_daftar: form.biaya_daftar,
        biaya_spp: form.biaya_spp,
        biaya_paket: form.biaya_paket
    }, {
        onSuccess: () => {
            Swal.fire({
                icon: 'success',
                title: 'Berhasil!',
                text: 'Data PPDB berhasil diperbarui!',
                confirmButtonColor: '#4361ee',
                timer: 2000,
                timerProgressBar: true
            });
        },
        onError: () => {
            showError('Gagal menyimpan data PPDB.');
        },
        onFinish: () => {
            form.processing = false;
        }
    });
};
</script>

<style scoped>
.form-control-sm {
    font-size: 13px;
}
</style>

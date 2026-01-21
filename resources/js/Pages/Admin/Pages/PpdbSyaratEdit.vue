<template>
    <AdminLayout>
        <div class="content container-fluid">
            <!-- Page Header -->
            <div class="page-header">
                <div class="row align-items-center">
                    <div class="col">
                        <h3 class="page-title">Edit Syarat & Alur PPDB</h3>
                        <ul class="breadcrumb">
                            <li class="breadcrumb-item"><Link href="/admin/pages">Halaman Khusus</Link></li>
                            <li class="breadcrumb-item active">PPDB Syarat</li>
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
                                <i class="fas fa-clipboard-list"></i>
                            </div>
                            <div>
                                <h5 class="alert-heading mb-1">Ketentuan PPDB</h5>
                                <p class="mb-0 text-muted">Atur syarat pendaftaran, alur penerimaan siswa baru, dan informasi kontak panitia PPDB.</p>
                            </div>
                        </div>
                    </div>

                    <form @submit.prevent="submit">
                        
                        <!-- Syarat Pendaftaran -->
                        <div class="row g-4 mb-4">
                            <div class="col-lg-6">
                                <div class="card border-0 shadow-sm h-100">
                                    <div class="card-header bg-transparent border-bottom py-3">
                                        <h5 class="mb-0 fw-bold text-primary"><i class="fas fa-file-contract me-2"></i>Syarat Administratif</h5>
                                    </div>
                                    <div class="card-body">
                                        <textarea v-model="form.syarat_admin" class="form-control" rows="8" 
                                            placeholder="Isi daftar syarat administratif..."></textarea>
                                        <small class="text-muted d-block mt-2"><i class="fas fa-info-circle me-1"></i> Gunakan format HTML sederhana seperti &lt;ul&gt;, &lt;li&gt;, &lt;strong&gt; untuk membuat daftar.</small>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="card border-0 shadow-sm h-100">
                                    <div class="card-header bg-transparent border-bottom py-3">
                                        <h5 class="mb-0 fw-bold text-primary"><i class="fas fa-child me-2"></i>Ketentuan Usia/Akademik</h5>
                                    </div>
                                    <div class="card-body">
                                        <textarea v-model="form.syarat_usia" class="form-control" rows="8" 
                                            placeholder="Isi ketentuan usia..."></textarea>
                                         <small class="text-muted d-block mt-2"><i class="fas fa-info-circle me-1"></i> Gunakan format HTML sederhana untuk tampilan yang lebih rapi.</small>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Alur Pendaftaran (Repeater) -->
                        <div class="card border-0 shadow-sm mb-4">
                            <div class="card-header bg-transparent border-bottom py-3 d-flex justify-content-between align-items-center">
                                <h5 class="mb-0 fw-bold text-warning text-dark"><i class="fas fa-route me-2"></i>Alur Pendaftaran</h5>
                                <button type="button" class="btn btn-sm btn-warning text-dark" @click="addStep">
                                    <i class="fas fa-plus me-1"></i> Tambah Langkah
                                </button>
                            </div>
                            <div class="card-body">
                                <div v-if="form.alur_steps.length === 0" class="text-center py-5 text-muted bg-light rounded">
                                    <i class="fas fa-random fa-3x mb-3 text-secondary opacity-50"></i>
                                    <p class="mb-0">Belum ada langkah alur yang ditambahkan.</p>
                                </div>

                                <div class="row g-3">
                                    <div v-for="(step, index) in form.alur_steps" :key="'step-'+index" class="col-md-6">
                                        <div class="card h-100 border shadow-sm">
                                            <div class="card-body p-3">
                                                <div class="d-flex justify-content-between mb-3 border-bottom pb-2">
                                                    <span class="badge bg-secondary">Langkah {{ index + 1 }}</span>
                                                    <button type="button" class="btn btn-sm text-danger p-0" @click="removeStep(index)" title="Hapus">
                                                        <i class="fas fa-times"></i>
                                                    </button>
                                                </div>
                                                <div class="row g-2">
                                                    <div class="col-12">
                                                        <label class="form-label small text-muted">Judul Langkah</label>
                                                        <input type="text" v-model="step.title" class="form-control form-control-sm fw-bold" placeholder="Contoh: Isi Formulir">
                                                    </div>
                                                    <div class="col-12">
                                                        <label class="form-label small text-muted">Deskripsi</label>
                                                        <textarea v-model="step.desc" class="form-control form-control-sm" rows="2" placeholder="Penjelasan singkat..."></textarea>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Kontak Panitia -->
                        <div class="card border-0 shadow-sm mb-4">
                            <div class="card-header bg-transparent border-bottom py-3">
                                <h5 class="mb-0 fw-bold text-info"><i class="fas fa-headset me-2"></i>Kontak Panitia</h5>
                            </div>
                            <div class="card-body">
                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <label class="form-label">Alamat Sekretariat</label>
                                        <div class="input-group">
                                            <span class="input-group-text bg-light"><i class="fas fa-map-marker-alt text-muted"></i></span>
                                            <input type="text" v-model="form.kontak_info" class="form-control" placeholder="Lokasi pendaftaran...">
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <label class="form-label">WhatsApp</label>
                                        <div class="input-group">
                                            <span class="input-group-text bg-light"><i class="fab fa-whatsapp text-success"></i></span>
                                            <input type="text" v-model="form.kontak_wa" class="form-control" placeholder="0812...">
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <label class="form-label">Email</label>
                                        <div class="input-group">
                                            <span class="input-group-text bg-light"><i class="fas fa-envelope text-muted"></i></span>
                                            <input type="text" v-model="form.kontak_email" class="form-control" placeholder="email@sekolah.com">
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <label class="form-label">Link Pendaftaran Online</label>
                                        <div class="input-group">
                                            <span class="input-group-text bg-light"><i class="fas fa-globe text-muted"></i></span>
                                            <input type="text" v-model="form.link_daftar" class="form-control" placeholder="/ppdb/daftar">
                                        </div>
                                        <small class="text-muted">Jika kosong, tombol pendaftaran mungkin tidak akan muncul atau tidak aktif.</small>
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

const defaultSteps = [
    { title: 'Isi Formulir', desc: 'Calon peserta didik/ortu mengisi formulir online atau offline di sekolah.' },
    { title: 'Verifikasi Dokumen', desc: 'Panitia memeriksa berkas & menghubungi via WhatsApp/Email untuk konfirmasi.' },
    { title: 'Observasi/Wawancara', desc: 'Observasi kesiapan belajar & wawancara singkat orang tua.' },
    { title: 'Pengumuman & Daftar Ulang', desc: 'Peserta yang diterima melakukan daftar ulang (administrasi awal, seragam, dsb.).' },
];

const defaultSyaratAdmin = `<ul>
<li>Fotokopi <strong>Akte Kelahiran</strong> (1 lembar)</li>
<li>Fotokopi <strong>Kartu Keluarga</strong> (1 lembar)</li>
<li>Fotokopi <strong>KTP Orang Tua/Wali</strong> (1 lembar)</li>
<li>Pas foto terbaru <strong>3×4</strong> (2 lembar)</li>
</ul>`;

const defaultSyaratUsia = `<ul>
<li>Usia minimal <strong>6 tahun</strong> per <strong>1 Juli</strong> tahun berjalan</li>
<li>Mengikuti observasi ringan kesiapan belajar</li>
</ul>`;

const form = reactive({
    syarat_admin: props.section?.content?.syarat_admin || defaultSyaratAdmin,
    syarat_usia: props.section?.content?.syarat_usia || defaultSyaratUsia,
    alur_steps: props.section?.content?.alur_steps || defaultSteps,
    kontak_info: props.section?.content?.kontak_info || 'Kp. Cihaneut RT 01/RW 11, Desa Drawati, Paseh, Bandung',
    kontak_wa: props.section?.content?.kontak_wa || '0812-1444-7944',
    kontak_email: props.section?.content?.kontak_email || 'mialhikmahpaseh@gmail.com',
    link_daftar: props.section?.content?.link_daftar || '/ppdb/daftar',
    processing: false
});

const addStep = () => {
    form.alur_steps.push({ title: '', desc: '' });
};

const removeStep = (index) => {
    form.alur_steps.splice(index, 1);
};

const submit = () => {
    form.processing = true;
    router.put('/admin/pages/ppdb_syarat', {
        syarat_admin: form.syarat_admin,
        syarat_usia: form.syarat_usia,
        alur_steps: JSON.stringify(form.alur_steps),
        kontak_info: form.kontak_info,
        kontak_wa: form.kontak_wa,
        kontak_email: form.kontak_email,
        link_daftar: form.link_daftar
    }, {
        onSuccess: () => {
            Swal.fire({
                icon: 'success',
                title: 'Berhasil!',
                text: 'Data Syarat & Alur PPDB berhasil disimpan!',
                confirmButtonColor: '#4361ee',
                timer: 2000,
                timerProgressBar: true
            });
        },
        onError: () => {
            showError('Gagal menyimpan data.');
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

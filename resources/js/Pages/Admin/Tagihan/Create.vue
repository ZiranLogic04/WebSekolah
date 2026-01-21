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
                                    <i class="fas fa-file-invoice-dollar fs-3"></i>
                                </div>
                                <h2 class="fw-bold text-white mb-0 ls-tight">Buat Tagihan Siswa</h2>
                            </div>
                            <p class="text-white-50 mb-0 fs-5">Buat tagihan baru untuk siswa berdasarkan filter kelas dan jenis kelamin.</p>
                        </div>
                    </div>
                </div>
                <!-- Decorative Background -->
                <div class="position-absolute bottom-0 end-0 opacity-10 me-n5 mb-n5">
                    <i class="fas fa-file-invoice-dollar" style="font-size: 10rem; color: white;"></i>
                </div>
            </div>

            <div class="row justify-content-center">
                <div class="col-lg-8 col-md-10">
                    <form @submit.prevent="submit">
                        
                        <!-- Info Tahun Ajaran -->
                        <div class="alert alert-info border-0 shadow-sm d-flex align-items-center mb-4" role="alert" v-if="tahunAjaran">
                            <i class="fas fa-calendar-check fa-2x me-3"></i>
                            <div>
                                <div class="fw-bold">Tahun Ajaran Aktif</div>
                                <div class="small">Tagihan yang Anda buat akan tercatat untuk tahun ajaran <span class="fw-bold text-decoration-underline">{{ tahunAjaran.tahun }}</span>.</div>
                            </div>
                        </div>

                        <!-- Section 1: Informasi Tagihan -->
                        <div class="card mb-4">
                            <div class="card-header">
                                <h5 class="card-title mb-0">
                                    <i class="fas fa-file-invoice-dollar me-2 text-primary"></i>
                                    Informasi Tagihan
                                </h5>
                            </div>
                            <div class="card-body">
                                <!-- Jenis Tagihan -->
                                <div class="mb-4">
                                    <label class="form-label fw-semibold">
                                        <i class="fas fa-tag me-1 text-muted"></i>
                                        Jenis Tagihan <span class="text-danger">*</span>
                                    </label>
                                    <select class="form-control form-select form-select-lg" v-model="form.jenis_tagihan_id">
                                        <option value="">-- Pilih Jenis Tagihan --</option>
                                        <option v-for="jenis in jenisTagihans" :key="jenis.id" :value="jenis.id">
                                            {{ jenis.nama }} {{ jenis.kode ? `(${jenis.kode})` : '' }}
                                        </option>
                                    </select>
                                    <div class="invalid-feedback d-block" v-if="form.errors.jenis_tagihan_id">{{ form.errors.jenis_tagihan_id }}</div>
                                </div>

                                <!-- Nominal -->
                                <div class="mb-4">
                                    <label class="form-label fw-semibold">
                                        <i class="fas fa-money-bill-wave me-1 text-muted"></i>
                                        Nominal Tagihan <span class="text-danger">*</span>
                                    </label>
                                    <div class="input-group input-group-lg">
                                        <span class="input-group-text">Rp</span>
                                        <input type="text" class="form-control" v-model="displayJumlah" @input="formatInputJumlah" placeholder="Contoh: 500.000">
                                    </div>
                                    <div class="invalid-feedback d-block" v-if="form.errors.jumlah">{{ form.errors.jumlah }}</div>
                                </div>

                                <!-- Tenggat Waktu -->
                                <div class="mb-3">
                                    <label class="form-label fw-semibold">
                                        <i class="fas fa-calendar-alt me-1 text-muted"></i>
                                        Tenggat Waktu <span class="text-danger">*</span>
                                    </label>
                                    <input type="date" class="form-control form-control-lg" v-model="form.tenggat_waktu">
                                    <div class="invalid-feedback d-block" v-if="form.errors.tenggat_waktu">{{ form.errors.tenggat_waktu }}</div>
                                </div>
                            </div>
                        </div>

                        <!-- Section 2: Target Siswa -->
                        <div class="card mb-4">
                            <div class="card-header">
                                <h5 class="card-title mb-0">
                                    <i class="fas fa-users me-2 text-success"></i>
                                    Target Siswa
                                </h5>
                            </div>
                            <div class="card-body">
                                <p class="text-muted mb-4">
                                    <i class="fas fa-info-circle me-1"></i>
                                    Pilih filter untuk menentukan siswa mana yang akan ditagih. Biarkan "Semua" jika ingin menagih seluruh siswa aktif.
                                </p>

                                <div class="row">
                                    <div class="col-md-6 mb-3 mb-md-0">
                                        <label class="form-label fw-semibold">
                                            <i class="fas fa-school me-1 text-muted"></i>
                                            Filter Kelas
                                        </label>
                                        <select class="form-control form-select form-select-lg" v-model="form.filter_kelas">
                                            <option value="Semua">Semua Kelas</option>
                                            <option v-for="k in kelasList" :key="k" :value="k">Kelas {{ k }}</option>
                                        </select>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label fw-semibold">
                                            <i class="fas fa-venus-mars me-1 text-muted"></i>
                                            Filter Gender
                                        </label>
                                        <select class="form-control form-select form-select-lg" v-model="form.filter_gender">
                                            <option value="Semua">Semua Gender</option>
                                            <option value="L">Laki-Laki</option>
                                            <option value="P">Perempuan</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Action Buttons -->
                        <div class="card">
                            <div class="card-body d-flex justify-content-between align-items-center">
                                <Link href="/admin/tagihan" class="btn btn-outline-secondary btn-lg">
                                    <i class="fas fa-arrow-left me-2"></i>Kembali
                                </Link>
                                <button type="submit" class="btn btn-primary btn-lg" :disabled="form.processing">
                                    <i class="fas fa-paper-plane me-2"></i>
                                    <span v-if="form.processing">Memproses...</span>
                                    <span v-else>Generate Tagihan</span>
                                </button>
                            </div>
                        </div>

                    </form>
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
    jenisTagihans: Array,
    kelasList: Array,
    tahunAjaran: Object
});

const displayJumlah = ref('');

const form = useForm({
    jenis_tagihan_id: '',
    jumlah: '',
    tenggat_waktu: '',
    filter_kelas: 'Semua', 
    filter_gender: 'Semua' 
});

// Format input currency
const formatInputJumlah = (event) => {
    let value = event.target.value.replace(/\D/g, '');
    if (value) {
        form.jumlah = value;
        displayJumlah.value = new Intl.NumberFormat('id-ID').format(value);
    } else {
        form.jumlah = '';
        displayJumlah.value = '';
    }
};

// Preset Logic
const setPreset = (type) => {
    const date = new Date();
    
    if (type === '7days') {
        date.setDate(date.getDate() + 7);
    } else if (type === '1month') {
        date.setMonth(date.getMonth() + 1);
    } else if (type === 'end_ganjil') {
        date.setMonth(11, 31); 
    } else if (type === 'end_genap') {
        date.setMonth(5, 30);
        if (new Date() > date) {
             date.setFullYear(date.getFullYear() + 1);
        }
    }
    
    const offset = date.getTimezoneOffset() * 60000;
    const localDate = new Date(date.getTime() - offset);
    form.tenggat_waktu = localDate.toISOString().split('T')[0];
};

const submit = () => {
    form.post('/admin/tagihan', {
        onSuccess: () => {
            Swal.fire({
                icon: 'success',
                title: 'Berhasil!',
                text: 'Tagihan berhasil dibuat untuk siswa terpilih.',
                timer: 2000,
                showConfirmButton: false
            });
        },
        onError: () => {
            Swal.fire({
                icon: 'error',
                title: 'Gagal!',
                text: 'Terjadi kesalahan, periksa inputan Anda.'
            });
        }
    });
};
</script>

<style scoped>
/* Card Styling */
.card {
    border: none;
    border-radius: 12px;
    box-shadow: 0 2px 12px rgba(0,0,0,0.08);
}
.card-header {
    background: #f8f9fa;
    border-bottom: 1px solid #eee;
    padding: 1.25rem 1.5rem;
    border-radius: 12px 12px 0 0 !important;
}
.card-body {
    padding: 1.5rem;
}
.card-title {
    font-size: 1.1rem;
    font-weight: 600;
    color: #333;
}

/* Form Controls */
.form-label {
    margin-bottom: 0.5rem;
    color: #555;
}
.form-control, .form-select {
    border-radius: 8px;
    border: 1px solid #ddd;
    transition: all 0.2s ease;
}
.form-control:focus, .form-select:focus {
    border-color: #3d5ee1;
    box-shadow: 0 0 0 3px rgba(61, 94, 225, 0.15);
}
.form-control-lg, .form-select-lg {
    padding: 0.75rem 1rem;
    font-size: 1rem;
}

/* Input Group */
.input-group-text {
    background-color: #f8f9fa;
    border: 1px solid #ddd;
    border-radius: 8px 0 0 8px;
    font-weight: 600;
    color: #555;
    padding: 0.75rem 1rem;
}
.input-group .form-control {
    border-radius: 0 8px 8px 0;
}

/* Preset Buttons */
.preset-buttons {
    display: flex;
    align-items: center;
    flex-wrap: wrap;
    gap: 0.5rem;
}
.preset-buttons .btn {
    border-radius: 20px;
    font-size: 0.8rem;
    padding: 0.35rem 0.75rem;
}

/* Action Buttons */
.btn-lg {
    padding: 0.75rem 1.5rem;
    font-size: 1rem;
    border-radius: 8px;
}
.btn-primary {
    background-color: #3d5ee1;
    border-color: #3d5ee1;
}
.btn-primary:hover {
    background-color: #2c4fd1;
    border-color: #2c4fd1;
}
</style>

<template>
    <AdminLayout>
        <div class="content container-fluid">
            <div class="page-header">
                <div class="row align-items-center">
                    <div class="col">
                        <h3 class="page-title">Buat Tagihan Siswa</h3>
                        <ul class="breadcrumb">
                            <li class="breadcrumb-item"><Link href="/dashboard">Dashboard</Link></li>
                            <li class="breadcrumb-item"><Link href="/admin/tagihan">Tagihan</Link></li>
                            <li class="breadcrumb-item active">Buat Tagihan</li>
                        </ul>
                    </div>
                </div>
            </div>

            <div class="row justify-content-center">
                <div class="col-md-8">
                    <div class="card">
                        <div class="card-body">
                            <form @submit.prevent="submit">
                                
                                <h5 class="card-title">Informasi Tagihan</h5>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Jenis Tagihan <span class="text-danger">*</span></label>
                                            <select class="form-control form-select" v-model="form.jenis_tagihan_id">
                                                <option value="">-- Pilih Jenis Tagihan --</option>
                                                <option v-for="jenis in jenisTagihans" :key="jenis.id" :value="jenis.id">
                                                    {{ jenis.nama }} {{ jenis.kode ? `(${jenis.kode})` : '' }}
                                                </option>
                                            </select>
                                            <div class="invalid-feedback d-block" v-if="form.errors.jenis_tagihan_id">{{ form.errors.jenis_tagihan_id }}</div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Nominal (Rp) <span class="text-danger">*</span></label>
                                            <input type="text" class="form-control" v-model="displayJumlah" @input="formatInputJumlah" placeholder="Contoh: 100.000">
                                            <div class="invalid-feedback d-block" v-if="form.errors.jumlah">{{ form.errors.jumlah }}</div>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label>Tenggat Waktu / Jatuh Tempo <span class="text-danger">*</span></label>
                                    <div class="row g-2">
                                        <div class="col-md-4">
                                            <div class="input-group">
                                                <input type="number" class="form-control" v-model="durationValue" placeholder="Lama" min="1">
                                                <span class="input-group-text bg-white border-start-0">
                                                    <i class="fas fa-clock text-muted"></i>
                                                </span>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <select class="form-control form-select" v-model="durationUnit">
                                                <option value="month">Bulan</option>
                                                <option value="year">Tahun</option>
                                            </select>
                                        </div>
                                        <div class="col-md-4">
                                            <input type="date" class="form-control" v-model="form.tenggat_waktu">
                                        </div>
                                    </div>
                                    <div class="invalid-feedback d-block" v-if="form.errors.tenggat_waktu">{{ form.errors.tenggat_waktu }}</div>
                                </div>

                                <hr>
                                <hr>
                                <h5 class="card-title">Target Siswa</h5>
                                
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Filter Kelas</label>
                                            <select class="form-control form-select" v-model="form.filter_kelas">
                                                <option value="Semua">-- Semua Kelas --</option>
                                                <option v-for="k in kelasList" :key="k" :value="k">Kelas {{ k }}</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Filter Gender</label>
                                            <select class="form-control form-select" v-model="form.filter_gender">
                                                <option value="Semua">-- Semua Gender --</option>
                                                <option value="L">Laki-Laki</option>
                                                <option value="P">Perempuan</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>

                                <div class="mt-4 text-end">
                                    <Link href="/admin/tagihan" class="btn btn-secondary me-2">Batal</Link>
                                    <button type="submit" class="btn btn-primary" :disabled="form.processing">
                                        <i class="fas fa-save me-1"></i> Generate Tagihan
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
import { ref, watch, computed } from 'vue';
import { Link, useForm } from '@inertiajs/vue3';
import AdminLayout from '@/Layouts/AdminLayout.vue';
import Swal from 'sweetalert2';

const props = defineProps({
    jenisTagihans: Array,
    kelasList: Array
});

const displayJumlah = ref('');
const durationValue = ref(6);
const durationUnit = ref('month');

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

// Calculate Date
const calculateDate = () => {
    if (durationValue.value) {
       const date = new Date();
       if (durationUnit.value === 'month') {
           date.setMonth(date.getMonth() + parseInt(durationValue.value));
       } else {
           date.setFullYear(date.getFullYear() + parseInt(durationValue.value));
       }
       form.tenggat_waktu = date.toISOString().split('T')[0];
   }
};

watch([durationValue, durationUnit], calculateDate, { immediate: true });



const submit = () => {
    // Validate custom logic if needed before submit
    form.post('/admin/tagihan', {
        onSuccess: () => {
            Swal.fire('Berhasil!', 'Tagihan berhasil dibuat untuk siswa terpilih.', 'success');
        },
        onError: () => {
            Swal.fire('Gagal!', 'Terjadi kesalahan, periksa inputan anda.', 'error');
        }
    });
};
</script>

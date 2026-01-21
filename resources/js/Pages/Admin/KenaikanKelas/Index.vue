<template>
    <AdminLayout>
        <Head title="Kenaikan Kelas" />
        
        <div class="content container-fluid pb-5">
            <!-- Radiant Header Card -->
            <div class="card border-0 shadow-lg rounded-4 mb-4 overflow-hidden position-relative" style="background: linear-gradient(135deg, #4f46e5 0%, #3b82f6 100%);">
                <div class="card-body p-4 p-lg-5 position-relative z-1">
                    <div class="row align-items-center">
                        <div class="col-lg-8">
                            <div class="d-flex align-items-center gap-3 mb-2">
                                <div class="icon-box bg-white rounded-3 text-primary p-2">
                                    <i class="fas fa-level-up-alt fs-3"></i>
                                </div>
                                <h2 class="fw-bold text-white mb-0 ls-tight">Manajemen Kenaikan Kelas</h2>
                            </div>
                            <p class="text-white-50 mb-0 fs-5">Proses kenaikan kelas, tinggal kelas, atau kelulusan siswa secara massal.</p>
                        </div>
                    </div>
                </div>
                <!-- Decorative Background -->
                <div class="position-absolute bottom-0 end-0 opacity-10 me-n5 mb-n5">
                    <i class="fas fa-graduation-cap" style="font-size: 10rem; color: white;"></i>
                </div>
            </div>

            <div class="row">
                <div class="col-md-12">
                    <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
                        <div class="card-header bg-white p-4 border-bottom">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h5 class="fw-bold mb-0 text-dark">Proses Kenaikan Kelas</h5>
                                    <p class="text-muted small mb-0">Pilih kelas asal dan tentukan aksi untuk memulai proses.</p>
                                </div>
                            </div>
                        </div>
                        <div class="card-body p-4">
                            <!-- Step 1: Pilih Kelas Asal -->
                            <div class="bg-light p-4 rounded-4 mb-4 border border-dashed">
                                <div class="row g-3">
                                    <div class="col-md-3">
                                        <label class="form-label fw-bold text-muted small text-uppercase">Kelas Asal</label>
                                        <div class="input-group">
                                            <span class="input-group-text bg-white border-end-0 text-muted ps-3 rounded-start-3"><i class="fas fa-chalkboard-teacher"></i></span>
                                            <select class="form-select bg-white border-start-0 py-2 rounded-end-3" v-model="form.kelas_asal" @change="fetchSiswa">
                                                <option value="">-- Pilih Kelas --</option>
                                                <option v-for="kelas in kelasList" :key="kelas.id" :value="kelas.nama">{{ kelas.nama }}</option>
                                            </select>
                                        </div>
                                    </div>
                                    
                                    <div class="col-md-3">
                                        <label class="form-label fw-bold text-muted small text-uppercase">Aksi</label>
                                        <div class="input-group">
                                            <span class="input-group-text bg-white border-end-0 text-muted ps-3 rounded-start-3"><i class="fas fa-cogs"></i></span>
                                            <select class="form-select bg-white border-start-0 py-2 rounded-end-3" v-model="form.aksi">
                                                <option value="naik">Naik ke Kelas...</option>
                                                <option value="tinggal">Tinggal di Kelas</option>
                                                <option value="lulus">Lulus / Tamat</option>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-md-3" v-if="form.aksi === 'naik'">
                                        <label class="form-label fw-bold text-muted small text-uppercase">Kelas Tujuan</label>
                                        <div class="input-group">
                                            <span class="input-group-text bg-white border-end-0 text-muted ps-3 rounded-start-3"><i class="fas fa-share"></i></span>
                                            <select class="form-select bg-white border-start-0 py-2 rounded-end-3" v-model="form.kelas_tujuan">
                                                <option value="">-- Pilih Kelas Tujuan --</option>
                                                <option v-for="kelas in kelasList" :key="kelas.id" :value="kelas.nama">{{ kelas.nama }}</option>
                                            </select>
                                        </div>
                                    </div>
                                    
                                    <div class="col-md-3" v-if="form.aksi === 'lulus'">
                                        <label class="form-label fw-bold text-muted small text-uppercase">Tahun Lulus</label>
                                        <div class="input-group">
                                            <span class="input-group-text bg-white border-end-0 text-muted ps-3 rounded-start-3"><i class="fas fa-calendar-alt"></i></span>
                                            <select class="form-select bg-white border-start-0 py-2 rounded-end-3" v-model="form.tahun_lulus">
                                                <option v-for="year in availableYears" :key="year" :value="year">{{ year }}</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Step 2: Pilih Siswa -->
                            <div v-if="siswaList.length > 0" class="card border border-light shadow-sm rounded-4 overflow-hidden">
                                <div class="card-header bg-white p-3 border-bottom d-flex justify-content-between align-items-center">
                                    <div>
                                        <h6 class="fw-bold mb-0 text-dark">Daftar Siswa Kelas {{ form.kelas_asal }}</h6>
                                        <small class="text-muted">{{ siswaList.length }} siswa ditemukan</small>
                                    </div>
                                    <div class="d-flex gap-2">
                                        <button class="btn btn-sm btn-light text-primary hover-up fw-bold" @click="toggleSelectAll">
                                            <i class="fas fa-check-double me-1"></i>
                                            {{ isAllSelected ? 'Batalkan' : 'Pilih Semua' }}
                                        </button>
                                    </div>
                                </div>
                                <div class="card-body p-0">
                                    <div class="table-responsive">
                                        <table class="table table-hover align-middle mb-0">
                                            <thead class="bg-primary text-white">
                                                <tr>
                                                    <th class="px-4 py-3 border-0 bg-primary text-white" width="50">
                                                        <input class="form-check-input" type="checkbox" @change="selectAll" :checked="isAllSelected">
                                                    </th>
                                                    <th class="px-4 py-3 border-0 bg-primary text-white" width="60">No</th>
                                                    <th class="px-4 py-3 border-0 bg-primary text-white">NIS</th>
                                                    <th class="px-4 py-3 border-0 bg-primary text-white">Nama Siswa</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr v-for="(siswa, index) in siswaList" :key="siswa.id" :class="{'bg-primary-subtle': form.siswa_ids.includes(siswa.id)}">
                                                    <td class="px-4 py-2">
                                                        <input class="form-check-input" type="checkbox" :value="siswa.id" v-model="form.siswa_ids">
                                                    </td>
                                                    <td class="px-4 py-2 text-center fw-medium text-muted">{{ index + 1 }}</td>
                                                    <td class="px-4 py-2 fw-medium text-primary">{{ siswa.nis }}</td>
                                                    <td class="px-4 py-2 fw-semibold text-dark">{{ siswa.nama }}</td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                    
                                    <!-- Action Bar -->
                                    <div class="d-flex justify-content-between align-items-center p-4 border-top bg-light">
                                        <div class="d-flex align-items-center gap-2">
                                            <div class="bg-white p-2 rounded-circle shadow-sm text-primary">
                                                <i class="fas fa-users-cog"></i>
                                            </div>
                                            <span class="text-muted fw-medium">Terpilih: <b class="text-primary fs-5">{{ form.siswa_ids.length }}</b> siswa</span>
                                        </div>
                                        <button class="btn btn-primary rounded-pill px-5 py-2 fw-bold shadow-sm hover-up" @click="submit" :disabled="form.processing || form.siswa_ids.length === 0">
                                            <span v-if="form.processing" class="spinner-border spinner-border-sm me-2"></span>
                                            <i class="fas fa-check-circle me-2" v-else></i>
                                            Proses {{ 
                                                form.aksi === 'naik' ? 'Kenaikan' : 
                                                (form.aksi === 'lulus' ? 'Kelulusan' : 'Tinggal Kelas')
                                            }}
                                        </button>
                                    </div>
                                </div>
                            </div>

                            <div v-else-if="form.kelas_asal && !loading" class="text-center py-5">
                                <div class="empty-state py-4">
                                    <div class="mb-3">
                                        <i class="fas fa-user-slash fa-3x text-muted opacity-25"></i>
                                    </div>
                                    <h6 class="fw-bold text-dark mb-1">Tidak ada siswa aktif</h6>
                                    <p class="text-muted small mb-0">Kelas ini tidak memiliki siswa aktif saat ini.</p>
                                </div>
                            </div>
                            
                            <div v-if="loading" class="text-center py-5">
                                <div class="spinner-border text-primary" role="status"></div>
                                <p class="mt-3 fw-medium text-muted">Memuat data siswa...</p>
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
import { Link, useForm, Head } from '@inertiajs/vue3';
import { ref, computed } from 'vue';
import axios from 'axios';
import { showToast, confirmDelete } from '@/Utils/swal';

const props = defineProps({
    kelasList: Array,
    tahunAjaranList: Array,
    activeTahunAjaranId: Number
});

const siswaList = ref([]);
const loading = ref(false);

// Generate available years (current year and 5 years back)
const availableYears = computed(() => {
    const currentYear = new Date().getFullYear();
    const years = [];
    for (let i = 0; i <= 5; i++) {
        years.push((currentYear - i).toString());
    }
    return years;
});

const form = useForm({
    kelas_asal: '',
    aksi: 'naik',
    kelas_tujuan: '',
    siswa_ids: [],
    tahun_lulus: new Date().getFullYear().toString()
});

const fetchSiswa = async () => {
    if (!form.kelas_asal) {
        siswaList.value = [];
        return;
    }

    loading.value = true;
    try {
        const response = await axios.get(`/admin/api/siswa-by-kelas?kelas=${form.kelas_asal}`);
        siswaList.value = response.data;
        form.siswa_ids = []; 
    } catch (error) {
        console.error("Error fetching siswa", error);
        showToast('error', 'Gagal', 'Gagal memuat data siswa.');
    } finally {
        loading.value = false;
    }
};

const isAllSelected = computed(() => {
    return siswaList.value.length > 0 && form.siswa_ids.length === siswaList.value.length;
});

const toggleSelectAll = () => {
    if (isAllSelected.value) {
        form.siswa_ids = [];
    } else {
        form.siswa_ids = siswaList.value.map(s => s.id);
    }
};

const selectAll = (e) => {
    if (e.target.checked) {
        form.siswa_ids = siswaList.value.map(s => s.id);
    } else {
        form.siswa_ids = [];
    }
};

const submit = () => {
    if (form.aksi === 'naik' && !form.kelas_tujuan) {
        showToast('warning', 'Peringatan', 'Silakan pilih kelas tujuan terlebih dahulu.');
        return;
    }

    confirmDelete(
        'Konfirmasi Proses',
        `Anda akan memproses ${form.siswa_ids.length} siswa. Lanjutkan?`,
        'question',
        'Ya, Proses'
    ).then((result) => {
        if (result.isConfirmed) {
            form.post('/admin/kenaikan-kelas', {
                onSuccess: () => {
                    showToast('success', 'Berhasil', 'Proses kenaikan kelas berhasil disimpan.');
                    fetchSiswa(); // Refresh list
                    form.siswa_ids = [];
                },
                onError: () => {
                   showToast('error', 'Gagal', 'Terjadi kesalahan sistem.');
                }
            });
        }
    });
};
</script>

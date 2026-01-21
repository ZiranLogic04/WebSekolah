<template>
    <AdminLayout>
        <div class="content container-fluid">
            <!-- Radiant Header -->
            <div class="card border-0 rounded-4 mb-4 overflow-hidden" style="background: linear-gradient(135deg, #4361ee 0%, #3a0ca3 100%);">
                <div class="card-body p-4 position-relative">
                    <div class="d-flex align-items-center gap-4 position-relative z-1">
                        <div class="icon-box bg-white bg-opacity-25 rounded-4 p-3 d-flex align-items-center justify-content-center backdrop-blur" style="width: 64px; height: 64px;">
                            <i class="fas fa-book-open fa-2x text-white"></i>
                        </div>
                        <div>
                            <h3 class="fw-bold mb-1 text-white">Edit Kurikulum & Mapel</h3>
                            <p class="mb-0 text-white-50">Kelola data kurikulum, mata pelajaran, dan alokasi waktu.</p>
                        </div>
                    </div>
                    <!-- Decorative Circle -->
                    <div class="position-absolute top-0 end-0 p-3 opacity-10 pointer-events-none">
                        <i class="fas fa-layer-group fa-10x text-white transform-rotate-12" style="margin-right: -40px; margin-top: -40px;"></i>
                    </div>
                </div>
            </div>

            <!-- Breadcrumb & Save Action -->
            <div class="bg-white border-0 rounded-4 shadow-sm py-3 px-4 mb-4 d-flex justify-content-between align-items-center w-100">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><Link href="/admin/halaman" class="text-decoration-none fw-bold"><i class="fas fa-layer-group me-1"></i> Halaman</Link></li>
                    <li class="breadcrumb-item">
                        <Link v-if="props.menuId" :href="`/admin/halaman/${props.menuId}/mega-menu`" class="text-decoration-none fw-bold text-muted hover-primary">{{ props.menuName || 'Akademik' }}</Link>
                        <span v-else class="text-muted fw-bold">{{ props.menuName || 'Akademik' }}</span>
                    </li>
                    <li class="breadcrumb-item active text-primary fw-bold" aria-current="page">Kurikulum</li>
                </ol>
                <button class="btn btn-primary rounded-pill px-4 fw-bold shadow-sm" @click="submit" :disabled="form.processing">
                    <span v-if="form.processing"><i class="fas fa-spinner fa-spin me-1"></i> Menyimpan...</span>
                    <span v-else><i class="fas fa-save me-1"></i> Simpan Perubahan</span>
                </button>
            </div>

            <!-- Content -->
            <div class="row">
                <div class="col-lg-12">
                    <form @submit.prevent="submit">
                        <!-- Ringkasan -->
                        <div class="card border-0 shadow-sm rounded-4 mb-4">
                            <div class="card-header bg-transparent border-bottom py-3">
                                <h5 class="mb-0 fw-bold text-primary"><i class="fas fa-info-circle me-2"></i>Ringkasan & Highlight</h5>
                            </div>
                            <div class="card-body">
                                <div class="mb-4">
                                    <label class="form-label fw-bold text-muted small">Gambaran Umum Kurikulum</label>
                                    <textarea v-model="form.ringkasan" class="form-control border-0 bg-light shadow-sm" rows="3" 
                                        placeholder="MI Al-Hikmah menerapkan kurikulum madrasah yang terintegrasi..."></textarea>
                                </div>
                                
                                <label class="form-label fw-bold text-muted small">Poin Unggulan (Highlight)</label>
                                <div v-for="(item, index) in form.highlight" :key="'h'+index" class="input-group mb-2 shadow-sm rounded-3 overflow-hidden">
                                    <span class="input-group-text bg-light text-success border-0"><i class="fas fa-check"></i></span>
                                    <input type="text" v-model="form.highlight[index]" class="form-control border-0" placeholder="Highlight...">
                                    <button type="button" class="btn btn-light text-danger border-start-0" @click="form.highlight.splice(index, 1)">
                                        <i class="fas fa-times"></i>
                                    </button>
                                </div>
                                <button type="button" class="btn btn-sm btn-outline-primary rounded-pill mt-2" @click="form.highlight.push('')">
                                    <i class="fas fa-plus me-1"></i> Tambah Highlight
                                </button>
                            </div>
                        </div>

                        <!-- Mata Pelajaran -->
                        <div class="row g-4 mb-4">
                            <!-- Keagamaan -->
                            <div class="col-lg-6">
                                <div class="card border-0 shadow-sm rounded-4 h-100">
                                    <div class="card-header bg-transparent border-bottom py-3 d-flex justify-content-between align-items-center">
                                        <h5 class="mb-0 fw-bold text-success"><i class="fas fa-quran me-2"></i>Kelompok Keagamaan</h5>
                                        <button type="button" class="btn btn-sm btn-success text-white rounded-pill" @click="form.mapel_keagamaan.push('')">
                                            <i class="fas fa-plus"></i>
                                        </button>
                                    </div>
                                    <div class="card-body">
                                        <div v-for="(item, index) in form.mapel_keagamaan" :key="'mk'+index" class="input-group mb-2 shadow-sm rounded-3 overflow-hidden">
                                            <span class="input-group-text bg-light border-0"><i class="fas fa-book-reader text-success"></i></span>
                                            <input type="text" v-model="form.mapel_keagamaan[index]" class="form-control border-0 fw-medium">
                                            <button type="button" class="btn btn-light text-danger border-start-0" @click="form.mapel_keagamaan.splice(index, 1)">
                                                <i class="fas fa-times"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- Umum -->
                            <div class="col-lg-6">
                                <div class="card border-0 shadow-sm rounded-4 h-100">
                                    <div class="card-header bg-transparent border-bottom py-3 d-flex justify-content-between align-items-center">
                                        <h5 class="mb-0 fw-bold text-info"><i class="fas fa-book me-2"></i>Kelompok Umum</h5>
                                        <button type="button" class="btn btn-sm btn-info text-white rounded-pill" @click="form.mapel_umum.push('')">
                                            <i class="fas fa-plus"></i>
                                        </button>
                                    </div>
                                    <div class="card-body">
                                        <div v-for="(item, index) in form.mapel_umum" :key="'mu'+index" class="input-group mb-2 shadow-sm rounded-3 overflow-hidden">
                                            <span class="input-group-text bg-light border-0"><i class="fas fa-book-open text-info"></i></span>
                                            <input type="text" v-model="form.mapel_umum[index]" class="form-control border-0 fw-medium">
                                            <button type="button" class="btn btn-light text-danger border-start-0" @click="form.mapel_umum.splice(index, 1)">
                                                <i class="fas fa-times"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Penilaian & Program Penguat -->
                        <div class="row g-4 mb-4">
                            <!-- Penilaian -->
                            <div class="col-lg-6">
                                <div class="card border-0 shadow-sm rounded-4 h-100">
                                    <div class="card-header bg-transparent border-bottom py-3 d-flex justify-content-between align-items-center">
                                        <h5 class="mb-0 fw-bold text-warning"><i class="fas fa-clipboard-check me-2"></i>Sistem Penilaian</h5>
                                        <button type="button" class="btn btn-sm btn-warning text-white rounded-pill" @click="form.penilaian.push('')">
                                            <i class="fas fa-plus"></i>
                                        </button>
                                    </div>
                                    <div class="card-body">
                                        <div v-for="(item, index) in form.penilaian" :key="'p'+index" class="input-group mb-2 shadow-sm rounded-3 overflow-hidden">
                                            <span class="input-group-text bg-light border-0"><i class="fas fa-star text-warning"></i></span>
                                            <input type="text" v-model="form.penilaian[index]" class="form-control border-0">
                                            <button type="button" class="btn btn-light text-danger border-start-0" @click="form.penilaian.splice(index, 1)">
                                                <i class="fas fa-times"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- Program Penguat -->
                            <div class="col-lg-6">
                                <div class="card border-0 shadow-sm rounded-4 h-100">
                                    <div class="card-header bg-transparent border-bottom py-3 d-flex justify-content-between align-items-center">
                                        <h5 class="mb-0 fw-bold text-danger"><i class="fas fa-rocket me-2"></i>Program Penguat</h5>
                                        <button type="button" class="btn btn-sm btn-danger text-white rounded-pill" @click="form.program_penguat.push('')">
                                            <i class="fas fa-plus"></i>
                                        </button>
                                    </div>
                                    <div class="card-body">
                                        <div v-for="(item, index) in form.program_penguat" :key="'pp'+index" class="input-group mb-2 shadow-sm rounded-3 overflow-hidden">
                                            <span class="input-group-text bg-light border-0"><i class="fas fa-heart text-danger"></i></span>
                                            <input type="text" v-model="form.program_penguat[index]" class="form-control border-0">
                                            <button type="button" class="btn btn-light text-danger border-start-0" @click="form.program_penguat.splice(index, 1)">
                                                <i class="fas fa-times"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Alokasi Waktu -->
                        <div class="card border-0 shadow-sm rounded-4 mb-4">
                            <div class="card-header bg-transparent border-bottom py-3 d-flex justify-content-between align-items-center">
                                <h5 class="mb-0 fw-bold text-secondary"><i class="fas fa-clock me-2"></i>Alokasi Waktu (JP)</h5>
                                <button type="button" class="btn btn-sm btn-secondary rounded-pill" @click="addAlokasiRow">
                                    <i class="fas fa-plus me-1"></i> Tambah Baris
                                </button>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive rounded-4 border">
                                    <table class="table table-hover align-middle mb-0">
                                        <thead class="bg-light text-secondary">
                                            <tr>
                                                <th rowspan="2" class="align-middle border-bottom-0 ps-4" style="width: 30%;">Mata Pelajaran</th>
                                                <th colspan="6" class="text-center py-2 border-bottom-0">Kelas</th>
                                                <th rowspan="2" class="align-middle border-bottom-0" style="width: 50px;"></th>
                                            </tr>
                                            <tr>
                                                <th class="text-center">1</th><th class="text-center">2</th><th class="text-center">3</th>
                                                <th class="text-center">4</th><th class="text-center">5</th><th class="text-center">6</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr v-for="(row, idx) in form.alokasi" :key="'al'+idx">
                                                <td class="text-start ps-4">
                                                    <input type="text" v-model="row.mapel" class="form-control form-control-sm border-0 fw-bold bg-transparent text-primary" placeholder="Nama Mapel">
                                                </td>
                                                <td v-for="cls in ['1','2','3','4','5','6']" :key="cls" style="min-width: 50px;" class="p-1">
                                                    <input type="number" v-model="row[cls]" class="form-control form-control-sm text-center border-0 bg-light rounded-3">
                                                </td>
                                                <td class="text-center">
                                                    <button type="button" class="btn btn-sm text-danger" @click="form.alokasi.splice(idx, 1)">
                                                        <i class="fas fa-times"></i>
                                                    </button>
                                                </td>
                                            </tr>
                                        </tbody>
                                        <tfoot class="bg-light">
                                            <tr>
                                                <td colspan="8" class="text-start text-muted small fst-italic px-4 py-2">
                                                    <i class="fas fa-info-circle me-1"></i> Isi dengan angka Jam Pelajaran (JP) per minggu.
                                                </td>
                                            </tr>
                                        </tfoot>
                                    </table>
                                </div>
                            </div>
                        </div>

                        <!-- Ekstrakurikuler -->
                        <div class="card border-0 shadow-sm rounded-4 mb-4">
                            <div class="card-header bg-transparent border-bottom py-3 d-flex justify-content-between align-items-center">
                                <h5 class="mb-0 fw-bold text-primary"><i class="fas fa-futbol me-2"></i>Ekstrakurikuler</h5>
                                <button type="button" class="btn btn-sm btn-primary rounded-pill" @click="form.ekskul.push('')">
                                    <i class="fas fa-plus me-1"></i> Tambah
                                </button>
                            </div>
                            <div class="card-body">
                                <div class="row g-3">
                                    <div v-for="(item, index) in form.ekskul" :key="'ek'+index" class="col-md-3 col-sm-6">
                                        <div class="input-group shadow-sm rounded-3 overflow-hidden">
                                            <span class="input-group-text bg-light border-0"><i class="fas fa-running text-primary"></i></span>
                                            <input type="text" v-model="form.ekskul[index]" class="form-control border-0" placeholder="Nama Ekskul">
                                            <button type="button" class="btn btn-light text-danger border-start-0" @click="form.ekskul.splice(index, 1)">
                                                <i class="fas fa-times"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
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
    menuName: String,
    groupName: String,
    menuId: [String, Number],
});

const defaultAlokasi = [
    { mapel: 'PPKn', '1': 4, '2': 4, '3': 4, '4': 4, '5': 4, '6': 4 },
    { mapel: 'B. Indonesia', '1': 7, '2': 7, '3': 7, '4': 6, '5': 6, '6': 6 },
    { mapel: 'Matematika', '1': 6, '2': 6, '3': 6, '4': 6, '5': 6, '6': 6 },
    { mapel: 'IPAS', '1': 3, '2': 3, '3': 4, '4': 5, '5': 5, '6': 5 },
    { mapel: 'SBdP', '1': 3, '2': 3, '3': 3, '4': 3, '5': 3, '6': 3 },
    { mapel: 'PJOK', '1': 4, '2': 4, '3': 4, '4': 4, '5': 4, '6': 4 },
    { mapel: 'PAI (QH, AA, Fikih, SKI)', '1': 6, '2': 6, '3': 6, '4': 6, '5': 6, '6': 6 },
    { mapel: 'Bahasa Arab', '1': 2, '2': 2, '3': 2, '4': 2, '5': 2, '6': 2 },
    { mapel: 'BTQ/Tahfiz', '1': 3, '2': 3, '3': 3, '4': 3, '5': 3, '6': 3 },
];

const form = reactive({
    ringkasan: props.section?.content?.ringkasan || 'MI Al-Hikmah menerapkan kurikulum madrasah yang terintegrasi dengan penguatan karakter, tahfiz/BTQ, serta pembiasaan ibadah harian.',
    highlight: props.section?.content?.highlight || [
        'Integrasi intrakurikuler, kokurikuler (projek/pendalaman), dan ekstrakurikuler.',
        'Penguatan kekhasan madrasah: Tahfiz & BTQ, pembiasaan ibadah, akhlak mulia.',
        'Budaya sekolah: Gerakan Literasi & Clinic Numerasi, sekolah ramah anak.',
    ],
    mapel_keagamaan: props.section?.content?.mapel_keagamaan || ["Al-Qur'an Hadits", "Akidah Akhlak", "Fikih", "SKI", "Bahasa Arab", "Tahfiz & BTQ"],
    mapel_umum: props.section?.content?.mapel_umum || ["PPKn", "Bahasa Indonesia", "Matematika", "IPAS", "PJOK", "SBdP", "Muatan Lokal", "Informatika/TIK"],
    alokasi: props.section?.content?.alokasi || defaultAlokasi,
    penilaian: props.section?.content?.penilaian || [
        'Asesmen diagnostik awal untuk pemetaan kemampuan.',
        'Penilaian formatif (harian/tugas/projek) & sumatif (PTS/PAS).',
        'Penilaian sikap/karakter terintegrasi dalam pembiasaan ibadah & adab.',
        'Portofolio karya & jurnal belajar untuk refleksi kemajuan siswa.',
    ],
    program_penguat: props.section?.content?.program_penguat || [
        'Tahfiz & BTQ (tahsin bacaan, setoran berkala, target juz bertahap).',
        'Gerakan Literasi (15 menit membaca) & Klinik Numerasi.',
        'Projek karakter & kepemimpinan melalui kegiatan kepramukaan/ekskul.',
        'Green & Clean School (GCC) dan Sekolah Ramah Anak.',
    ],
    ekskul: props.section?.content?.ekskul || ['Pramuka', 'Hadroh', 'Kaligrafi', 'KIR / Sains', 'Olahraga', 'English Club'],
    processing: false
});

const addAlokasiRow = () => {
    form.alokasi.push({ mapel: '', '1': 0, '2': 0, '3': 0, '4': 0, '5': 0, '6': 0 });
};

const submit = () => {
    form.processing = true;
    router.put('/admin/pages/kurikulum', {
        ringkasan: form.ringkasan,
        highlight: form.highlight.filter(h => h.trim() !== ''),
        mapel_keagamaan: form.mapel_keagamaan.filter(m => m.trim() !== ''),
        mapel_umum: form.mapel_umum.filter(m => m.trim() !== ''),
        alokasi: form.alokasi,
        penilaian: form.penilaian.filter(p => p.trim() !== ''),
        program_penguat: form.program_penguat.filter(p => p.trim() !== ''),
        ekskul: form.ekskul.filter(e => e.trim() !== '')
    }, {
        onSuccess: () => {
            Swal.fire({
                icon: 'success',
                title: 'Berhasil!',
                text: 'Kurikulum berhasil diperbarui!',
                confirmButtonColor: '#4361ee',
                timer: 2000,
                timerProgressBar: true
            });
        },
        onError: () => {
            showError('Gagal', 'Gagal menyimpan data.');
        },
        onFinish: () => {
            form.processing = false;
        }
    });
};
</script>

<style scoped>
.backdrop-blur {
    backdrop-filter: blur(8px);
    -webkit-backdrop-filter: blur(8px);
}
.text-white-50 {
    color: rgba(255, 255, 255, 0.75) !important;
}
.hover-primary:hover {
    color: var(--bs-primary) !important;
}
.form-control-sm {
    font-size: 13px;
}
</style>

<template>
    <AdminLayout>
        <div class="content container-fluid">
            <!-- Radiant Header -->
            <div class="card border-0 rounded-4 mb-4 overflow-hidden" style="background: linear-gradient(135deg, #4361ee 0%, #3a0ca3 100%);">
                <div class="card-body p-4 position-relative">
                    <div class="d-flex align-items-center gap-4 position-relative z-1">
                        <div class="icon-box bg-white bg-opacity-25 rounded-4 p-3 d-flex align-items-center justify-content-center backdrop-blur" style="width: 64px; height: 64px;">
                            <i class="fas fa-bullseye fa-2x text-white"></i>
                        </div>
                        <div>
                            <h3 class="fw-bold mb-1 text-white">Edit Tujuan Pendidikan</h3>
                            <p class="mb-0 text-white-50">Definisikan tujuan strategis dan program pendukung sekolah.</p>
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
                        <Link v-if="props.menuId" :href="`/admin/halaman/${props.menuId}/mega-menu`" class="text-decoration-none fw-bold text-muted hover-primary">{{ props.menuName || 'Tentang Kami' }}</Link>
                        <span v-else class="text-muted fw-bold">{{ props.menuName || 'Tentang Kami' }}</span>
                    </li>
                    <li class="breadcrumb-item active text-primary fw-bold" aria-current="page">Tujuan Pendidikan</li>
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
                        <!-- Daftar Tujuan -->
                        <div class="card border-0 shadow-sm rounded-4 mb-4">
                            <div class="card-header bg-transparent border-bottom py-3 d-flex justify-content-between align-items-center">
                                <h5 class="mb-0 fw-bold text-primary"><i class="fas fa-bullseye me-2"></i>Daftar Tujuan Madrasah</h5>
                                <button type="button" class="btn btn-sm btn-primary rounded-pill" @click="addTujuan">
                                    <i class="fas fa-plus me-1"></i> Tambah Tujuan
                                </button>
                            </div>
                            <div class="card-body">
                                <p class="text-muted small mb-3 fst-italic">Daftar tujuan pendidikan yang ditampilkan di halaman publik.</p>
                                
                                <div v-if="form.tujuan.length === 0" class="text-center py-5 text-muted bg-light rounded-4">
                                    <i class="fas fa-list-ol fa-3x mb-3 text-secondary opacity-50"></i>
                                    <p class="mb-0">Belum ada tujuan yang ditambahkan</p>
                                </div>

                                <div v-else class="d-flex flex-column gap-3">
                                    <div v-for="(item, index) in form.tujuan" :key="'tujuan-'+index" class="w-100">
                                        <div class="input-group shadow-sm rounded-3 overflow-hidden">
                                            <span class="input-group-text bg-light fw-bold text-primary border-0" style="min-width: 45px; justify-content: center;">{{ index + 1 }}</span>
                                            <input type="text" v-model="form.tujuan[index]" class="form-control border-0" placeholder="Isi tujuan pendidikan..." required>
                                            <button type="button" class="btn btn-light text-danger border-start-0" @click="removeTujuan(index)" title="Hapus">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Program Pendukung -->
                        <div class="card border-0 shadow-sm rounded-4 mb-4">
                            <div class="card-header bg-transparent border-bottom py-3 d-flex justify-content-between align-items-center">
                                <h5 class="mb-0 fw-bold text-success"><i class="fas fa-tasks me-2"></i>Program Pendukung</h5>
                                <button type="button" class="btn btn-sm btn-success text-white rounded-pill" @click="addProgram">
                                    <i class="fas fa-plus me-1"></i> Tambah Program
                                </button>
                            </div>
                            <div class="card-body">
                                <p class="text-muted small mb-3 fst-italic">Program-program unggulan yang mendukung tercapainya tujuan sekolah.</p>

                                <div v-if="form.program.length === 0" class="text-center py-5 text-muted bg-light rounded-4">
                                    <i class="fas fa-clipboard-check fa-3x mb-3 text-secondary opacity-50"></i>
                                    <p class="mb-0">Belum ada program pendukung</p>
                                </div>

                                <div v-else class="d-flex flex-column gap-3">
                                    <div v-for="(item, index) in form.program" :key="'prog-'+index" class="w-100">
                                        <div class="input-group shadow-sm rounded-3 overflow-hidden">
                                            <span class="input-group-text bg-light border-0"><i class="fas fa-check-circle text-success"></i></span>
                                            <input type="text" v-model="form.program[index]" class="form-control border-0" placeholder="Nama program..." required>
                                            <button type="button" class="btn btn-light text-danger border-start-0" @click="removeProgram(index)" title="Hapus">
                                                <i class="fas fa-trash"></i>
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
    menuName: String, // Dynamic Prop
    groupName: String,
    menuId: [String, Number], // Dynamic Prop
});

// Default tujuan jika belum ada data
const defaultTujuan = [
    'Menyiapkan peserta didik agar memiliki dasar keimanan yang kokoh, akhlak mulia, dan semangat ibadah dalam kehidupan sehari-hari.',
    'Menumbuhkembangkan karakter siswa yang mandiri, disiplin, tanggung jawab, dan cinta terhadap ilmu pengetahuan.',
    'Menanamkan pemahaman Islam yang benar dan moderat, serta mencintai Al-Qur\'an sebagai pedoman hidup.',
    'Membekali peserta didik dengan keterampilan dasar membaca, menulis, berhitung, dan berpikir kritis sesuai jenjang usianya.',
    'Mengembangkan potensi peserta didik secara optimal baik di bidang akademik maupun non-akademik.',
    'Membangun budaya sekolah yang sehat, harmonis, dan peduli lingkungan demi iklim belajar yang nyaman dan menyenangkan.',
    'Menanamkan semangat nasionalisme dan cinta tanah air dalam bingkai nilai-nilai Islami.',
];

// Default program pendukung jika belum ada data
const defaultProgram = [
    'Tahfidz & Tadarus Harian',
    'Gerakan Literasi Madrasah (GLM)',
    'Green & Clean School (GCC)',
    'Ekstrakurikuler: Pramuka, Kaligrafi, Hadroh, KIR, English Club, olahraga',
];

// Initialize form with existing data or defaults
const form = reactive({
    tujuan: props.section?.content?.tujuan || [...defaultTujuan],
    program: props.section?.content?.program || [...defaultProgram],
    processing: false
});

const addTujuan = () => {
    form.tujuan.push('');
};

const removeTujuan = (index) => {
    form.tujuan.splice(index, 1);
};

const addProgram = () => {
    form.program.push('');
};

const removeProgram = (index) => {
    form.program.splice(index, 1);
};

const submit = () => {
    form.processing = true;
    router.put('/admin/pages/tujuan', {
        tujuan: form.tujuan.filter(t => t.trim() !== ''),
        program: form.program.filter(p => p.trim() !== '')
    }, {
        onSuccess: () => {
             Swal.fire({
                icon: 'success',
                title: 'Berhasil!',
                text: 'Data Tujuan & Program berhasil disimpan!',
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
</style>

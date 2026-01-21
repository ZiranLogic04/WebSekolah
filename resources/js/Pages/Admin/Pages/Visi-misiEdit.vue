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
                            <h3 class="fw-bold mb-1 text-white">Edit Visi & Misi</h3>
                            <p class="mb-0 text-white-50">Kelola visi, misi, dan tagline sekolah.</p>
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
                        <Link v-if="menuId" :href="`/admin/halaman/${menuId}/mega-menu`" class="text-decoration-none fw-bold text-muted hover-primary">{{ menuName || 'Akademik' }}</Link>
                        <span v-else class="text-muted fw-bold">{{ menuName || 'Akademik' }}</span>
                    </li>
                    <li class="breadcrumb-item active text-primary fw-bold" aria-current="page">Visi & Misi</li>
                </ol>
                <button class="btn btn-primary rounded-pill px-4 fw-bold shadow-sm" @click="submit" :disabled="form.processing">
                    <span v-if="form.processing"><i class="fas fa-spinner fa-spin me-1"></i></span>
                    <span v-else><i class="fas fa-save me-1"></i> Simpan</span>
                </button>
            </div>

            <!-- Content -->
            <div class="row">
                <div class="col-lg-12">
                    


                    <form @submit.prevent="submit">
                        <div class="row g-4">
                            <!-- Visi -->
                            <div class="col-lg-5">
                                <div class="card border-0 shadow-sm h-100">
                                    <div class="card-header bg-transparent border-bottom py-3">
                                        <h5 class="mb-0 fw-bold text-primary"><i class="fas fa-eye me-2"></i>Visi Sekolah</h5>
                                    </div>
                                    <div class="card-body">
                                        <div class="mb-3">
                                            <label class="form-label fw-bold">Tagline Visi</label>
                                            <input type="text" v-model="form.tagline" class="form-control" placeholder="TAMANKU">
                                            <small class="text-muted">Singkatan atau akronim visi (Opsional)</small>
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label fw-bold">Isi Visi</label>
                                            <textarea v-model="form.visi" class="form-control" rows="6" 
                                                placeholder='Terwujudnya Insan yang Tafaqquh fid-din, Mandiri, dan Berakhlakul Karimah.'></textarea>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Misi -->
                            <div class="col-lg-7">
                                <div class="card border-0 shadow-sm h-100">
                                    <div class="card-header bg-transparent border-bottom py-3 d-flex justify-content-between align-items-center">
                                        <h5 class="mb-0 fw-bold text-primary"><i class="fas fa-list-check me-2"></i>Misi Sekolah</h5>
                                        <button type="button" class="btn btn-sm btn-primary" @click="addMisi">
                                            <i class="fas fa-plus me-1"></i> Tambah Misi
                                        </button>
                                    </div>
                                    <div class="card-body">
                                        <div v-if="form.misi.length === 0" class="text-center py-5 text-muted bg-light rounded-3">
                                            <i class="fas fa-clipboard-list fs-3 mb-3 d-block opacity-50"></i>
                                            Belum ada misi yang ditambahkan.
                                        </div>

                                        <div v-for="(item, index) in form.misi" :key="item.id" class="input-group mb-3">
                                            <span class="input-group-text bg-light fw-bold text-muted" style="width: 40px; justify-content: center;">{{ index + 1 }}</span>
                                            <input type="text" v-model="item.text" class="form-control" placeholder="Tulis poin misi di sini...">
                                            <button type="button" class="btn btn-outline-danger" @click="removeMisi(index)" title="Hapus">
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
import { reactive, ref } from 'vue';
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

const defaultMisi = [
    'Mewujudkan generasi yang rajin dan khusyuk beribadah melalui kegiatan keagamaan.',
    'Menumbuhkan perilaku santun melalui pembiasaan 5S dan agen karakter.',
    'Meningkatkan kualitas dan efektivitas proses belajar mengajar.',
    'Mendorong prestasi akademik dan non-akademik melalui kegiatan ekstrakurikuler.',
    'Membiasakan budaya disiplin bagi seluruh warga madrasah melalui program bahasa.',
    'Menciptakan lingkungan Aman, Bersih, Indah, Rindang, Asri melalui kegiatan "5 menit bersih" sebelum pulang.',
    'Menjaga dan mengelola lingkungan hidup melalui komunitas Green and Clean School (GCC).',
    'Meningkatkan kemampuan literasi melalui Gerakan Literasi Madrasah (GLM).',
];

// Convert existing misi to objects with unique IDs
let idCounter = ref(0);
const createMisiItem = (text) => ({ id: idCounter.value++, text });

const initMisi = () => {
    const existingMisi = props.section?.content?.misi;
    if (existingMisi && Array.isArray(existingMisi) && existingMisi.length > 0) {
        return existingMisi.map(m => createMisiItem(typeof m === 'string' ? m : m.text || ''));
    }
    return defaultMisi.map(m => createMisiItem(m));
};

const form = reactive({
    visi: props.section?.content?.visi || 'Terwujudnya Insan yang Tafaqquh fid-din, Mandiri, dan Berakhlakul Karimah (TAMANKU).',
    tagline: props.section?.content?.tagline || 'TAMANKU',
    misi: initMisi(),
    processing: false
});

const goBack = () => {
    window.history.back();
};

const addMisi = () => {
    form.misi.push(createMisiItem(''));
};

const removeMisi = (index) => {
    form.misi.splice(index, 1);
};

const submit = () => {
    form.processing = true;
    router.put(`/admin/pages/${props.section.key}`, {
        visi: form.visi,
        tagline: form.tagline,
        misi: form.misi.map(m => m.text).filter(m => m.trim() !== '')
    }, {
        onSuccess: () => {
            Swal.fire({
                icon: 'success',
                title: 'Berhasil!',
                text: 'Data Visi & Misi berhasil diperbarui!',
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

<template>
    <AdminLayout>
        <div class="content container-fluid">
            <!-- Radiant Header -->
            <div class="card border-0 rounded-4 mb-4 overflow-hidden" style="background: linear-gradient(135deg, #4361ee 0%, #3a0ca3 100%);">
                <div class="card-body p-4 position-relative">
                    <div class="d-flex align-items-center gap-4 position-relative z-1">
                        <div class="icon-box bg-white bg-opacity-25 rounded-4 p-3 d-flex align-items-center justify-content-center backdrop-blur" style="width: 64px; height: 64px;">
                            <i class="fas fa-user-tie fa-2x text-white"></i>
                        </div>
                        <div>
                            <h3 class="fw-bold mb-1 text-white">Edit Kepala Madrasah</h3>
                            <p class="mb-0 text-white-50">Kelola profil, foto, dan sambutan Kepala Madrasah.</p>
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
                    <li class="breadcrumb-item active text-primary fw-bold" aria-current="page">Kepala Madrasah</li>
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
                        <div class="row g-4">
                            <!-- Biodata -->
                            <div class="col-lg-4">
                                <div class="card border-0 shadow-sm rounded-4 h-100">
                                    <div class="card-header bg-transparent border-bottom py-3">
                                        <h5 class="mb-0 fw-bold text-primary"><i class="fas fa-id-card me-2"></i>Biodata</h5>
                                    </div>
                                    <div class="card-body text-center">
                                        <div class="mb-4">
                                            <div class="d-inline-block position-relative">
                                                <img :src="previewFoto || (form.foto ? `/storage/${form.foto}` : '/assets/img/team/placeholder.jpg')" 
                                                     class="img-fluid rounded-circle shadow-sm p-1 bg-white" 
                                                     style="width: 160px; height: 160px; object-fit: cover;">
                                                <label class="btn btn-light border position-absolute bottom-0 end-0 rounded-circle shadow" style="width: 45px; height: 45px; display: flex; align-items: center; justify-content: center; cursor: pointer;">
                                                    <i class="fas fa-camera text-primary"></i>
                                                    <input type="file" @change="handleFoto" accept="image/*" class="d-none">
                                                </label>
                                            </div>
                                            <div class="small text-muted mt-2">Format: JPG/PNG (Max 2MB)</div>
                                        </div>
                                        <div class="text-start">
                                            <div class="mb-3">
                                                <label class="form-label fw-bold small text-muted">Nama Lengkap</label>
                                                <input type="text" class="form-control bg-light border-0 fw-bold text-dark" :value="$page.props.lembaga?.kepala_sekolah" disabled>
                                                <small class="text-muted d-block mt-1 fst-italic">* Diambil dari Data Lembaga</small>
                                            </div>
                                            <div class="mb-3">
                                                <label class="form-label fw-bold small text-muted">NIP</label>
                                                <input type="text" v-model="form.nip" class="form-control border-0 bg-light" placeholder="—">
                                            </div>
                                            <div class="mb-3">
                                                <label class="form-label fw-bold small text-muted">Masa Jabatan</label>
                                                <input type="text" v-model="form.periode" class="form-control border-0 bg-light" placeholder="Periode 2023–sekarang">
                                            </div>
                                            <div class="mb-3">
                                                <label class="form-label fw-bold small text-muted">Email</label>
                                                <input type="email" v-model="form.email" class="form-control border-0 bg-light" placeholder="email@example.com">
                                            </div>
                                            <div class="mb-3">
                                                <label class="form-label fw-bold small text-muted">Telepon/HP</label>
                                                <input type="text" v-model="form.telepon" class="form-control border-0 bg-light" placeholder="0812-xxxx-xxxx">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Sambutan -->
                            <div class="col-lg-8">
                                <div class="card border-0 shadow-sm rounded-4 mb-4">
                                    <div class="card-header bg-transparent border-bottom py-3">
                                        <h5 class="mb-0 fw-bold text-success"><i class="fas fa-quote-left me-2"></i>Quote & Sambutan</h5>
                                    </div>
                                    <div class="card-body">
                                        <div class="mb-3">
                                            <label class="form-label fw-bold text-muted small">Quote / Motto</label>
                                            <textarea v-model="form.quote" class="form-control border-0 bg-light shadow-sm" rows="2" 
                                                placeholder='"Membentuk generasi yang Tafaqquh fid-din..."'></textarea>
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label fw-bold text-muted small">Isi Sambutan</label>
                                            <textarea v-model="form.sambutan" class="form-control border-0 bg-light shadow-sm" rows="8" 
                                                placeholder="Assalamu'alaikum warahmatullahi wabarakatuh..."></textarea>
                                        </div>
                                    </div>
                                </div>

                                <!-- Riwayat Singkat -->
                                <div class="card border-0 shadow-sm rounded-4">
                                    <div class="card-header bg-transparent border-bottom py-3 d-flex justify-content-between align-items-center">
                                        <h5 class="mb-0 fw-bold text-info"><i class="fas fa-list-ul me-2"></i>Riwayat Singkat</h5>
                                        <button type="button" class="btn btn-sm btn-info text-white rounded-pill" @click="addRiwayat">
                                            <i class="fas fa-plus me-1"></i> Tambah
                                        </button>
                                    </div>
                                    <div class="card-body">
                                        <div v-if="form.riwayat.length === 0" class="text-center py-4 text-muted bg-light rounded-4">
                                            Belum ada riwayat
                                        </div>
                                        <div v-else class="d-flex flex-column gap-2">
                                            <div v-for="(item, index) in form.riwayat" :key="index" class="w-100">
                                                <div class="input-group shadow-sm rounded-3 overflow-hidden">
                                                    <span class="input-group-text bg-light text-primary border-0"><i class="fas fa-check"></i></span>
                                                    <input type="text" v-model="form.riwayat[index]" class="form-control border-0" placeholder="Riwayat...">
                                                    <button type="button" class="btn btn-light text-danger border-start-0" @click="removeRiwayat(index)">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </div>
                                            </div>
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

const previewFoto = ref(null);
const fotoFile = ref(null);

const defaultRiwayat = [
    'Pendidikan terakhir: (isi dari data resmi)',
    'Pengalaman mengajar & kepemimpinan madrasah',
    'Fokus pengembangan: Tahfiz/BTQ, GLM, GCC, layanan ramah anak',
    'Kolaborasi aktif dengan komite & orang tua/wali',
];

const form = reactive({
    foto: props.section?.content?.foto || '',
    nip: props.section?.content?.nip || '—',
    periode: props.section?.content?.periode || 'Periode 2023–sekarang',
    email: props.section?.content?.email || '',
    telepon: props.section?.content?.telepon || '',
    quote: props.section?.content?.quote || '"Membentuk generasi yang Tafaqquh fid-din, Mandiri, dan Berakhlakul Karimah (TAMANKU)."',
    sambutan: props.section?.content?.sambutan || 'Assalamu\'alaikum warahmatullahi wabarakatuh.\n\nSelamat datang di MI Al-Hikmah. Kami berkomitmen menghadirkan layanan pendidikan dasar yang berkualitas, religius, dan ramah anak.\n\nWassalamu\'alaikum warahmatullahi wabarakatuh.',
    riwayat: props.section?.content?.riwayat || [...defaultRiwayat],
    processing: false
});

const handleFoto = (e) => {
    const file = e.target.files[0];
    if (file) {
        fotoFile.value = file;
        previewFoto.value = URL.createObjectURL(file);
    }
};

const addRiwayat = () => {
    form.riwayat.push('');
};

const removeRiwayat = (index) => {
    form.riwayat.splice(index, 1);
};

const submit = () => {
    form.processing = true;
    
    const formData = new FormData();
    formData.append('_method', 'POST'); // or PUT if handled by resource endpoint, but usually FormData uses POST with _method override or simple POST. PageController uses POST/PUT. 
    // Checking previous implementation: `router.post('/admin/pages/sambutan', formData, ...)`
    // Usually Inertia handles PUT via `_method: PUT` in FormData.
    // Line 205 in old file: `router.post(...)`.
    // I'll stick to POST without explicit `_method` unless required.
    // Actually, `PageController` update method is commonly PUT.
    // But `sambutan` route might be special?
    // Let's assume generic PageController update. Line 169 web.php: `Route::post('/pages/{key}'...)`.
    // So POST is correct.

    formData.append('nip', form.nip);
    formData.append('periode', form.periode);
    formData.append('email', form.email);
    formData.append('telepon', form.telepon);
    formData.append('quote', form.quote);
    formData.append('sambutan', form.sambutan);
    formData.append('riwayat', JSON.stringify(form.riwayat.filter(r => r.trim() !== '')));
    
    if (fotoFile.value) {
        formData.append('foto', fotoFile.value);
    }
    
    router.post('/admin/pages/sambutan', formData, {
        forceFormData: true,
        onSuccess: () => {
            Swal.fire({
                icon: 'success',
                title: 'Berhasil!',
                text: 'Profil Kepala Madrasah diperbarui!',
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

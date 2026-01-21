<template>
    <AdminLayout>
        <div class="content container-fluid">
            <!-- Radiant Header -->
            <div class="card border-0 rounded-4 mb-4 overflow-hidden" style="background: linear-gradient(135deg, #4361ee 0%, #3a0ca3 100%);">
                <div class="card-body p-4 position-relative">
                    <div class="d-flex align-items-center gap-4 position-relative z-1">
                        <div class="icon-box bg-white bg-opacity-25 rounded-4 p-3 d-flex align-items-center justify-content-center backdrop-blur" style="width: 64px; height: 64px;">
                            <i class="fas fa-landmark fa-2x text-white"></i>
                        </div>
                        <div>
                            <h3 class="fw-bold mb-1 text-white">Sejarah Singkat Sekolah</h3>
                            <p class="mb-0 text-white-50">Kelola narasi perjalanan dan milestone penting sekolah.</p>
                        </div>
                    </div>
                    <!-- Decorative Circle -->
                    <div class="position-absolute top-0 end-0 p-3 opacity-10 pointer-events-none">
                        <i class="fas fa-history fa-10x text-white transform-rotate-12" style="margin-right: -40px; margin-top: -40px;"></i>
                    </div>
                </div>
            </div>

            <!-- Breadcrumb & Save Action -->
            <div class="bg-white border-0 rounded-4 shadow-sm py-3 px-4 mb-4 d-flex justify-content-between align-items-center w-100">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><Link href="/admin/halaman" class="text-decoration-none fw-bold"><i class="fas fa-layer-group me-1"></i> Halaman</Link></li>
                    <li class="breadcrumb-item">
                        <Link v-if="menuId" :href="`/admin/halaman/${menuId}/mega-menu`" class="text-decoration-none fw-bold text-muted hover-primary">{{ menuName || 'Profil' }}</Link>
                        <span v-else class="text-muted fw-bold">{{ menuName || 'Profil' }}</span>
                    </li>
                    <li class="breadcrumb-item active text-primary fw-bold" aria-current="page">Sejarah Singkat</li>
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
                            <!-- Narasi Sejarah -->
                            <div class="col-12">
                                <div class="card border-0 shadow-sm h-100">
                                    <div class="card-header bg-transparent border-bottom py-3">
                                        <h5 class="mb-0 fw-bold text-primary"><i class="fas fa-book-open me-2"></i>Narasi Perjalanan</h5>
                                    </div>
                                    <div class="card-body">
                                        <div class="mb-3">
                                            <label class="form-label fw-bold">Cerita Sejarah</label>
                                            <textarea v-model="form.narasi" class="form-control" rows="8" 
                                                placeholder="Tuliskan narasi sejarah berdirinya sekolah..."></textarea>
                                            <small class="text-muted">Gunakan paragraf yang menarik untuk menceritakan awal mula hingga perkembangan sekolah saat ini.</small>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Timeline Repeater -->
                            <div class="col-12">
                                <div class="card border-0 shadow-sm h-100">
                                    <div class="card-header bg-transparent border-bottom py-3 d-flex justify-content-between align-items-center">
                                        <h5 class="mb-0 fw-bold text-primary"><i class="fas fa-stream me-2"></i>Timeline / Milestones</h5>
                                        <button type="button" class="btn btn-sm btn-primary" @click="addTimeline">
                                            <i class="fas fa-plus me-1"></i> Tambah Momen
                                        </button>
                                    </div>
                                    <div class="card-body bg-light">
                                        <div v-if="form.timeline.length === 0" class="text-center py-5 text-muted bg-white rounded-3">
                                            <i class="fas fa-history fs-1 mb-3 d-block opacity-50"></i>
                                            <p>Belum ada data timeline.</p>
                                            <button type="button" class="btn btn-sm btn-outline-primary" @click="addTimeline">
                                                Tambah Timeline Pertama
                                            </button>
                                        </div>

                                        <div v-for="(item, index) in form.timeline" :key="index" class="card mb-3 border shadow-sm">
                                            <div class="card-body p-3">
                                                <div class="d-flex justify-content-between mb-2">
                                                    <span class="badge bg-primary">#{{ index + 1 }}</span>
                                                    <button type="button" class="btn btn-sm text-danger p-0" @click="removeTimeline(index)" title="Hapus">
                                                        <i class="fas fa-times"></i>
                                                    </button>
                                                </div>
                                                <div class="row g-2">
                                                    <div class="col-md-3">
                                                        <label class="form-label small text-muted">Tahun</label>
                                                        <input type="text" v-model="item.tahun" class="form-control form-control-sm" placeholder="2010">
                                                    </div>
                                                    <div class="col-md-9">
                                                        <label class="form-label small text-muted">Judul Peristiwa</label>
                                                        <input type="text" v-model="item.judul" class="form-control form-control-sm" placeholder="Pendirian Sekolah">
                                                    </div>
                                                    <div class="col-12">
                                                        <label class="form-label small text-muted">Deskripsi Singkat</label>
                                                        <textarea v-model="item.desc" class="form-control form-control-sm" rows="2" placeholder="Deskripsi singkat momen ini..."></textarea>
                                                    </div>
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
import { reactive } from 'vue';
import AdminLayout from '@/Layouts/AdminLayout.vue';
import { Link, router } from '@inertiajs/vue3';
import { showSuccess, showError } from '@/Utils/notifications';
import Swal from 'sweetalert2';

const props = defineProps({
    section: Object,
    backUrl: String,
    menuName: String,
    menuId: [String, Number],
});

const defaultTimeline = [
    { tahun: '2008', judul: 'Pendirian MI Al-Hikmah', desc: 'Awal mula layanan pendidikan dasar berbasis keislaman untuk masyarakat sekitar.' },
    { tahun: '2014', judul: 'Penguatan Tahfiz & BTQ', desc: 'Program tahsin, setoran berkala, dan pembiasaan ibadah harian.' },
    { tahun: '2022', judul: 'Akreditasi Madrasah', desc: 'Pencapaian akreditasi B sebagai wujud komitmen mutu.' },
];

const form = reactive({
    narasi: props.section?.content?.narasi || '',
    timeline: props.section?.content?.timeline || defaultTimeline,
    processing: false
});

const addTimeline = () => {
    form.timeline.push({ tahun: '', judul: '', desc: '' });
};

const removeTimeline = (index) => {
    form.timeline.splice(index, 1);
};

const submit = () => {
    // Validasi: Pastikan tidak ada timeline yang kosong
    for (const item of form.timeline) {
        if (!item.tahun || String(item.tahun).trim() === '' || !item.judul || String(item.judul).trim() === '') {
            showError('Gagal', 'Mohon lengkapi Tahun dan Judul Peristiwa untuk semua timeline!');
            return;
        }
    }

    form.processing = true;
    router.put(`/admin/pages/${props.section.key}`, {
        narasi: form.narasi,
        timeline: JSON.stringify(form.timeline)
    }, {
        preserveScroll: true,
        preserveState: true,
        onSuccess: () => {
            Swal.fire({
                icon: 'success',
                title: 'Berhasil!',
                text: 'Data Sejarah & Timeline berhasil disimpan!',
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

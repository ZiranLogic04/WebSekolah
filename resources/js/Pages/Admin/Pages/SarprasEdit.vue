<template>
    <AdminLayout>
        <div class="content container-fluid">
            <!-- Radiant Header -->
            <div class="card border-0 rounded-4 mb-4 overflow-hidden" style="background: linear-gradient(135deg, #4361ee 0%, #3a0ca3 100%);">
                <div class="card-body p-4 position-relative">
                    <div class="d-flex align-items-center gap-4 position-relative z-1">
                        <div class="icon-box bg-white bg-opacity-25 rounded-4 p-3 d-flex align-items-center justify-content-center backdrop-blur" style="width: 64px; height: 64px;">
                            <i class="fas fa-building fa-2x text-white"></i>
                        </div>
                        <div>
                            <h3 class="fw-bold mb-1 text-white">Edit Sarana & Prasarana</h3>
                            <p class="mb-0 text-white-50">Kelola data fasilitas dan inventaris sekolah.</p>
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
                        <Link v-if="props.menuId" :href="`/admin/halaman/${props.menuId}/mega-menu`" class="text-decoration-none fw-bold text-muted hover-primary">{{ props.menuName || 'Fasilitas' }}</Link>
                        <span v-else class="text-muted fw-bold">{{ props.menuName || 'Fasilitas' }}</span>
                    </li>
                    <li class="breadcrumb-item active text-primary fw-bold" aria-current="page">Sarana & Prasarana</li>
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
                        <!-- Kartu Fasilitas -->
                        <div class="card border-0 shadow-sm rounded-4 mb-4">
                            <div class="card-header bg-transparent border-bottom py-3 d-flex justify-content-between align-items-center">
                                <h5 class="mb-0 fw-bold text-primary"><i class="fas fa-th-large me-2"></i>Kartu Fasilitas Utama</h5>
                                <button type="button" class="btn btn-sm btn-primary rounded-pill" @click="addFacility">
                                    <i class="fas fa-plus me-1"></i> Tambah Kartu
                                </button>
                            </div>
                            <div class="card-body">
                                <p class="text-muted small mb-3 fst-italic">Kartu fasilitas yang ditampilkan di halaman publik. Drag untuk mengubah urutan (Coming Soon).</p>
                                
                                <div v-if="form.facilities.length === 0" class="text-center py-5 text-muted bg-light rounded-4">
                                    <i class="fas fa-box-open fa-3x mb-3 text-secondary opacity-50"></i>
                                    <p class="mb-0">Belum ada kartu fasilitas yang ditambahkan</p>
                                </div>

                                <div v-else class="row g-3">
                                    <div v-for="(facility, index) in form.facilities" :key="'f'+index" class="col-12">
                                        <div class="card bg-light border-0 rounded-3">
                                            <div class="card-body p-3">
                                                <div class="row g-3 align-items-center">
                                                    <div class="col-md-2 text-center text-md-start">
                                                        <label class="form-label small text-muted d-block text-start">Icon</label>
                                                        <div class="input-group input-group-sm">
                                                            <span class="input-group-text bg-white border-0"><i :class="facility.icon"></i></span>
                                                            <select v-model="facility.icon" class="form-select form-select-sm border-0">
                                                                <option v-for="ic in iconOptions" :key="ic.value" :value="ic.value">{{ ic.label }}</option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-3">
                                                        <label class="form-label small text-muted">Judul Fasilitas</label>
                                                        <input type="text" v-model="facility.title" class="form-control form-control-sm fw-bold border-0 shadow-sm" placeholder="Nama fasilitas">
                                                    </div>
                                                    <div class="col-md-6">
                                                        <label class="form-label small text-muted">Deskripsi Singkat</label>
                                                        <input type="text" v-model="facility.desc" class="form-control form-control-sm border-0 shadow-sm" placeholder="Deskripsi singkat untuk kartu">
                                                    </div>
                                                    <div class="col-md-1 text-end">
                                                        <label class="d-none d-md-block form-label small">&nbsp;</label>
                                                        <button type="button" class="btn btn-sm text-danger" @click="removeFacility(index)" title="Hapus Fasilitas">
                                                            <i class="fas fa-trash-alt"></i>
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Tabel Inventaris -->
                        <div class="card border-0 shadow-sm rounded-4 mb-4">
                            <div class="card-header bg-transparent border-bottom py-3 d-flex justify-content-between align-items-center">
                                <h5 class="mb-0 fw-bold text-success"><i class="fas fa-table me-2"></i>Data Inventaris Lengkap</h5>
                                <button type="button" class="btn btn-sm btn-success text-white rounded-pill" @click="addInventaris">
                                    <i class="fas fa-plus me-1"></i> Tambah Baris
                                </button>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive rounded-4 border">
                                    <table class="table table-hover align-middle mb-0">
                                        <thead class="bg-light">
                                            <tr>
                                                <th class="ps-4 border-bottom-0 py-3" style="width: 30%;">Nama Fasilitas/Barang</th>
                                                <th class="border-bottom-0 py-3" style="width: 15%;">Jumlah</th>
                                                <th class="border-bottom-0 py-3" style="width: 15%;">Kondisi</th>
                                                <th class="border-bottom-0 py-3" style="width: 30%;">Keterangan</th>
                                                <th class="border-bottom-0 py-3" style="width: 10%;"></th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr v-if="form.inventaris.length === 0">
                                                <td colspan="5" class="text-center py-4 text-muted">Belum ada data inventaris</td>
                                            </tr>
                                            <tr v-for="(item, index) in form.inventaris" :key="'inv'+index">
                                                <td class="ps-4"><input type="text" v-model="item.name" class="form-control form-control-sm border-0 bg-transparent fw-bold text-primary" placeholder="Nama Item"></td>
                                                <td><input type="text" v-model="item.qty" class="form-control form-control-sm border-0 bg-light" placeholder="Jml"></td>
                                                <td><input type="text" v-model="item.cond" class="form-control form-control-sm border-0 bg-light" placeholder="Kondisi"></td>
                                                <td><input type="text" v-model="item.note" class="form-control form-control-sm border-0 bg-light" placeholder="Keterangan"></td>
                                                <td class="text-center">
                                                    <button type="button" class="btn btn-sm text-danger" @click="removeInventaris(index)">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </td>
                                            </tr>
                                        </tbody>
                                        <tfoot class="bg-light">
                                            <tr>
                                                <td colspan="5" class="small text-muted px-4 py-2">
                                                    <i class="fas fa-info-circle me-1"></i> Data ini akan ditampilkan dalam bentuk tabel horizontal di halaman detail.
                                                </td>
                                            </tr>
                                        </tfoot>
                                    </table>
                                </div>
                            </div>
                        </div>

                        <!-- Deskripsi Tambahan -->
                        <div class="card border-0 shadow-sm rounded-4 mb-4">
                            <div class="card-header bg-transparent border-bottom py-3">
                                <h5 class="mb-0 fw-bold text-info"><i class="fas fa-align-left me-2"></i>Deskripsi Tambahan</h5>
                            </div>
                            <div class="card-body">
                                <textarea v-model="form.content" class="form-control border-0 bg-light shadow-sm" rows="6" placeholder="Tulis deskripsi tambahan, tata tertib, atau informasi penggunaan fasilitas di sini..."></textarea>
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

// Icon options untuk dropdown
const iconOptions = [
    { value: 'fas fa-chalkboard-teacher', label: '📚 Ruang Kelas' },
    { value: 'fas fa-book-reader', label: '📖 Perpustakaan' },
    { value: 'fas fa-user-tie', label: '👔 Ruang Guru' },
    { value: 'fas fa-first-aid', label: '🏥 UKS' },
    { value: 'fas fa-desktop', label: '💻 Perangkat TIK' },
    { value: 'fas fa-volleyball-ball', label: '⚽ Lapangan' },
    { value: 'fas fa-mosque', label: '🕌 Mushola' },
    { value: 'fas fa-palette', label: '🎨 Seni/Ekskul' },
    { value: 'fas fa-pump-soap', label: '🧼 Sanitasi' },
    { value: 'fas fa-door-open', label: '🚪 Ruangan' },
    { value: 'fas fa-tools', label: '🔧 Peralatan' },
    { value: 'fas fa-utensils', label: '🍽️ Kantin' },
];

// Default facilities
const defaultFacilities = [
    { icon: 'fas fa-chalkboard-teacher', title: 'Ruang Kelas', desc: 'Tersedia 6 ruang kelas yang nyaman dan terang.' },
    { icon: 'fas fa-book-reader', title: 'Perpustakaan & Pojok Baca', desc: 'Koleksi bacaan siswa & pojok baca di tiap kelas.' },
    { icon: 'fas fa-user-tie', title: 'Ruang Guru & Kepala', desc: 'Ruang kerja guru, kepala, serta TU/Operator.' },
    // Truncated defaults for brevity, preserved logic
    { icon: 'fas fa-first-aid', title: 'UKS & Keamanan Anak', desc: 'Layanan kesehatan dasar & Satgas Anti-Bullying.' },
    { icon: 'fas fa-desktop', title: 'Perangkat TIK', desc: 'Perangkat presentasi & akses TIK terjadwal.' },
    { icon: 'fas fa-volleyball-ball', title: 'Lapangan & Olahraga', desc: 'Kegiatan senam, scout, dan permainan edukatif.' },
    { icon: 'fas fa-mosque', title: 'Mushola / Pembiasaan Ibadah', desc: 'Kegiatan salat berjamaah & tadarus harian.' },
];

const defaultInventaris = [
    { name: 'Ruang Kelas', qty: '6', cond: 'Baik', note: 'Meja-kursi proporsional' },
    { name: 'Perpustakaan', qty: '—', cond: '—', note: 'Koleksi bacaan siswa' },
    { name: 'Ruang Guru', qty: '1', cond: '—', note: '—' },
];

const form = reactive({
    facilities: props.section?.content?.facilities || [...defaultFacilities],
    inventaris: props.section?.content?.inventaris || [...defaultInventaris],
    content: props.section?.content?.content || '',
    processing: false
});

const addFacility = () => {
    form.facilities.push({ icon: 'fas fa-door-open', title: '', desc: '' });
};

const removeFacility = (index) => {
    form.facilities.splice(index, 1);
};

const addInventaris = () => {
    form.inventaris.push({ name: '', qty: '', cond: '', note: '' });
};

const removeInventaris = (index) => {
    form.inventaris.splice(index, 1);
};

const submit = () => {
    form.processing = true;
    router.put('/admin/pages/sarpras', {
        facilities: form.facilities,
        inventaris: form.inventaris,
        content: form.content
    }, {
        onSuccess: () => {
            Swal.fire({
                icon: 'success',
                title: 'Berhasil!',
                text: 'Data Sarana & Prasarana berhasil disimpan!',
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

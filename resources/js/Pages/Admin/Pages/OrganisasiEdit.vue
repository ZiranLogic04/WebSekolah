<template>
    <AdminLayout>
        <div class="content container-fluid">
            <!-- Page Header -->
            <!-- Radiant Header -->
            <div class="card border-0 rounded-4 mb-4 overflow-hidden" style="background: linear-gradient(135deg, #4361ee 0%, #3a0ca3 100%);">
                <div class="card-body p-4 position-relative">
                    <div class="d-flex align-items-center gap-4 position-relative z-1">
                        <div class="icon-box bg-white bg-opacity-25 rounded-4 p-3 d-flex align-items-center justify-content-center backdrop-blur" style="width: 64px; height: 64px;">
                            <i class="fas fa-sitemap fa-2x text-white"></i>
                        </div>
                        <div>
                            <h3 class="fw-bold mb-1 text-white">Edit Struktur Organisasi</h3>
                            <p class="mb-0 text-white-50">Kelola tampilan bagan struktur madrasah (Mode Gambar / Teks)</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Breadcrumb & Action -->
            <div class="row align-items-center mb-4">
                <div class="col">
                    <nav aria-label="breadcrumb">
                         <ol class="breadcrumb alert alert-primary border-0 rounded-4 shadow-sm py-3 px-4 mb-0 align-items-center justify-content-between">
                            <div class="d-flex align-items-center">
                                <li class="breadcrumb-item"><Link href="/admin/halaman" class="text-decoration-none fw-bold"><i class="fas fa-layer-group me-1"></i> Halaman</Link></li>
                                <li class="breadcrumb-item">
                                    <Link v-if="props.menuId" :href="`/admin/halaman/${props.menuId}/mega-menu`" class="text-decoration-none text-muted hover-primary">{{ props.menuName || 'Akademik' }}</Link>
                                    <span v-else class="text-muted">{{ props.menuName || 'Akademik' }}</span>
                                </li>
                                <li class="breadcrumb-item active text-primary fw-bold ps-2" aria-current="page">Organisasi</li>
                            </div>
                            
                            <div class="d-flex align-items-center gap-2">
                                <button class="btn btn-primary rounded-pill shadow-sm px-4 fw-bold" @click="submit" :disabled="form.processing">
                                    <i class="fas fa-save me-2"></i> 
                                    <span v-if="form.processing">Menyimpan...</span>
                                    <span v-else>Simpan Perubahan</span>
                                </button>
                            </div>
                        </ol>
                    </nav>
                </div>
            </div>

            <!-- Content -->
            <div class="row">
                <div class="col-lg-12">
                    
                    <!-- Info Alert -->


                    <form @submit.prevent="submit">
                        <!-- Mode Selector -->
                        <!-- Mode Selector Grid -->
                        <div class="row g-4 mb-4">
                            <div class="col-md-6">
                                <label class="card h-100 border-2 shadow-sm cursor-pointer transition-all hover-transform" 
                                    :class="form.mode_tampilan === 'text' ? 'border-primary bg-primary text-white' : 'border-light bg-white'">
                                    <div class="card-body p-4 d-flex align-items-center gap-3">
                                        <input type="radio" value="text" v-model="form.mode_tampilan" class="d-none">
                                        <div class="icon-box rounded-circle d-flex align-items-center justify-content-center shadow-sm" 
                                            :class="form.mode_tampilan === 'text' ? 'bg-white text-primary' : 'bg-light text-muted'"
                                            style="width: 50px; height: 50px;">
                                            <i class="fas fa-list-ul fa-lg"></i>
                                        </div>
                                        <div>
                                            <h5 class="fw-bold mb-1" :class="form.mode_tampilan === 'text' ? 'text-white' : 'text-dark'">Daftar Teks Dinamis</h5>
                                            <p class="mb-0 small" :class="form.mode_tampilan === 'text' ? 'text-white-50' : 'text-muted'">Input manual jabatan & nama (Pohon Otomatis)</p>
                                        </div>
                                        <div class="ms-auto" v-if="form.mode_tampilan === 'text'">
                                            <i class="fas fa-check-circle text-white fa-2x"></i>
                                        </div>
                                    </div>
                                </label>
                            </div>
                            <div class="col-md-6">
                                <label class="card h-100 border-2 shadow-sm cursor-pointer transition-all hover-transform"
                                    :class="form.mode_tampilan === 'image' ? 'border-primary bg-primary text-white' : 'border-light bg-white'">
                                    <div class="card-body p-4 d-flex align-items-center gap-3">
                                        <input type="radio" value="image" v-model="form.mode_tampilan" class="d-none">
                                        <div class="icon-box rounded-circle d-flex align-items-center justify-content-center shadow-sm"
                                            :class="form.mode_tampilan === 'image' ? 'bg-white text-primary' : 'bg-light text-muted'"
                                            style="width: 50px; height: 50px;">
                                            <i class="fas fa-image fa-lg"></i>
                                        </div>
                                        <div>
                                            <h5 class="fw-bold mb-1" :class="form.mode_tampilan === 'image' ? 'text-white' : 'text-dark'">Upload Gambar</h5>
                                            <p class="mb-0 small" :class="form.mode_tampilan === 'image' ? 'text-white-50' : 'text-muted'">Upload file gambar struktur utuh (JPG/PNG)</p>
                                        </div>
                                        <div class="ms-auto" v-if="form.mode_tampilan === 'image'">
                                            <i class="fas fa-check-circle text-white fa-2x"></i>
                                        </div>
                                    </div>
                                </label>
                            </div>
                        </div>

                        <div class="row g-4">
                            <!-- Struktur Jabatan -->
                            <div class="col-lg-6">
                                <div class="card border-0 shadow-sm h-100">
                                    <div class="card-header bg-transparent border-bottom py-3">
                                        <h5 class="mb-0 fw-bold text-primary"><i class="fas fa-user-friends me-2"></i>Struktur Utama</h5>
                                    </div>
                                    <div class="card-body">


                                        <!-- MODE: IMAGE -->
                                        <div v-if="form.mode_tampilan === 'image'" class="mb-4 pt-3 border-top">
                                            <label class="d-block p-5 border-2 border-dashed rounded-4 text-center bg-light hover-bg-light-darker position-relative overflow-hidden mb-3" style="cursor: pointer; border-color: #dee2e6; border-style: dashed;">
                                                <input type="file" @change="handleImageUpload" class="d-none" accept="image/*">
                                                <div class="position-relative z-1">
                                                    <div class="mb-3">
                                                        <i class="fas fa-cloud-upload-alt fa-3x text-primary opacity-75"></i>
                                                    </div>
                                                    <h5 class="fw-bold text-dark">Klik untuk Pilih Gambar</h5>
                                                    <p class="text-muted small mb-0">Format: JPG, PNG, JPEG (Max 2MB)</p>
                                                </div>
                                            </label>
                                            
                                            <div v-if="form.org_chart_image_url" class="p-2 border rounded bg-light text-center">
                                                <img :src="form.org_chart_image_url" alt="Preview" class="img-fluid rounded shadow-sm" style="max-height: 300px;">
                                                <div class="small text-muted mt-2">Preview Gambar Saat Ini</div>
                                            </div>
                                            <div v-else class="text-center py-5 border border-dashed rounded text-muted">
                                                <i class="fas fa-image fa-3x mb-2 opacity-50"></i>
                                                <p>Belum ada gambar.</p>
                                            </div>
                                        </div>

                                        <!-- MODE: TEXT -->
                                        <div v-if="form.mode_tampilan === 'text'">
                                            <!-- Pengawas -->
                                            <div class="mb-3">
                                                <div class="d-flex align-items-center mb-1">
                                                    <template v-if="!editing.pengawas">
                                                        <span class="fw-bold small text-muted me-2">{{ form.pengawas_label }}</span>
                                                        <button type="button" class="btn btn-link p-0 text-muted shadow-none" @click="startEdit('pengawas')" title="Edit Label">
                                                            <i class="fas fa-pen fa-xs"></i>
                                                        </button>
                                                    </template>
                                                    <input 
                                                        v-else
                                                        :ref="el => inputRefs.pengawas = el"
                                                        type="text" 
                                                        v-model="form.pengawas_label" 
                                                        class="form-control form-control-sm border-primary fw-bold p-1 text-muted shadow-sm" 
                                                        placeholder="Label Pengawas"
                                                        @blur="stopEdit('pengawas')"
                                                        @keyup.enter="stopEdit('pengawas')"
                                                    >
                                                </div>
                                                <div class="input-group">
                                                    <span class="input-group-text bg-light"><i class="fas fa-user-shield"></i></span>
                                                    <input type="text" v-model="form.pengawas" class="form-control" placeholder="Nama Pejabat">
                                                </div>
                                            </div>

                                            <!-- Kepala -->
                                            <div class="mb-4">
                                                <div class="d-flex align-items-center mb-1">
                                                    <template v-if="!editing.kepala">
                                                        <span class="fw-bold small text-muted me-2">{{ form.kepala_label }}</span>
                                                        <button type="button" class="btn btn-link p-0 text-muted shadow-none" @click="startEdit('kepala')" title="Edit Label">
                                                            <i class="fas fa-pen fa-xs"></i>
                                                        </button>
                                                    </template>
                                                    <input 
                                                        v-else
                                                        :ref="el => inputRefs.kepala = el"
                                                        type="text" 
                                                        v-model="form.kepala_label" 
                                                        class="form-control form-control-sm border-primary fw-bold p-1 text-muted shadow-sm" 
                                                        placeholder="Label Kepala"
                                                        @blur="stopEdit('kepala')"
                                                        @keyup.enter="stopEdit('kepala')"
                                                    >
                                                </div>
                                                <div class="input-group">
                                                    <span class="input-group-text bg-light"><i class="fas fa-user-tie"></i></span>
                                                    <input type="text" class="form-control bg-light" :value="$page.props.lembaga?.kepala_sekolah" disabled>
                                                </div>
                                                <small class="text-muted d-block mt-1 fst-italic">* Otomatis dari Data Lembaga</small>
                                            </div>
                                            <!-- Pimpinan -->
                                            <div class="mb-4 pt-3 border-top">
                                                <div v-if="!editing.pimpinan" class="d-flex align-items-center w-100 px-2 py-1 bg-light rounded mb-2">
                                                    <span class="fw-bold text-primary flex-grow-1">{{ form.pimpinan_title }}</span>
                                                    <button type="button" class="btn btn-sm btn-link text-muted p-0" @click="startEdit('pimpinan')">
                                                        <i class="fas fa-pen fa-xs"></i>
                                                    </button>
                                                </div>
                                                <div v-else class="input-group input-group-sm mb-3">
                                                    <span class="input-group-text bg-white border-end-0"><i class="fas fa-pen text-muted"></i></span>
                                                    <input 
                                                        :ref="el => inputRefs.pimpinan = el"
                                                        type="text" 
                                                        v-model="form.pimpinan_title" 
                                                        class="form-control fw-bold border-start-0 text-primary fs-6 shadow-sm" 
                                                        placeholder="Judul Bagian"
                                                        @blur="stopEdit('pimpinan')"
                                                        @keyup.enter="stopEdit('pimpinan')"
                                                    >
                                                </div>
                                                <div v-for="(item, index) in form.pimpinan" :key="'p'+index" class="input-group mb-2">
                                                    <input type="text" v-model="item.jabatan" class="form-control bg-light" style="max-width: 45%;" placeholder="Jabatan">
                                                    <input type="text" v-model="item.nama" class="form-control border-start-0" placeholder="Nama Pejabat">
                                                    <button type="button" class="btn btn-outline-danger" @click="form.pimpinan.splice(index, 1)">
                                                        <i class="fas fa-times"></i>
                                                    </button>
                                                </div>
                                                <button type="button" class="btn btn-sm btn-light border-primary text-primary mt-2" @click="addItem(form.pimpinan, 'Wakil Kepala')">
                                                    <i class="fas fa-plus me-1"></i> Tambah Item
                                                </button>
                                            </div>

                                            <!-- Koordinator -->
                                            <div class="mb-4 pt-3 border-top">
                                                <div v-if="!editing.koordinator" class="d-flex align-items-center w-100 px-2 py-1 bg-light rounded mb-2">
                                                    <span class="fw-bold text-primary flex-grow-1">{{ form.koordinator_title }}</span>
                                                    <button type="button" class="btn btn-sm btn-link text-muted p-0" @click="startEdit('koordinator')">
                                                        <i class="fas fa-pen fa-xs"></i>
                                                    </button>
                                                </div>
                                                <div v-else class="input-group input-group-sm mb-3">
                                                    <span class="input-group-text bg-white border-end-0"><i class="fas fa-pen text-muted"></i></span>
                                                    <input 
                                                        :ref="el => inputRefs.koordinator = el"
                                                        type="text" 
                                                        v-model="form.koordinator_title" 
                                                        class="form-control fw-bold border-start-0 text-primary fs-6 shadow-sm" 
                                                        placeholder="Judul Bagian"
                                                        @blur="stopEdit('koordinator')"
                                                        @keyup.enter="stopEdit('koordinator')"
                                                    >
                                                </div>
                                                <div v-for="(item, index) in form.koordinator" :key="'k'+index" class="input-group mb-2">
                                                    <input type="text" v-model="item.jabatan" class="form-control bg-light" style="max-width: 45%;" placeholder="Jabatan">
                                                    <input type="text" v-model="item.nama" class="form-control border-start-0" placeholder="Nama Pejabat">
                                                    <button type="button" class="btn btn-outline-danger" @click="form.koordinator.splice(index, 1)">
                                                        <i class="fas fa-times"></i>
                                                    </button>
                                                </div>
                                                <button type="button" class="btn btn-sm btn-light border-primary text-primary mt-2" @click="addItem(form.koordinator, 'Koordinator')">
                                                    <i class="fas fa-plus me-1"></i> Tambah Item
                                                </button>
                                            </div>

                                            <!-- Administrasi -->
                                            <div class="mb-4 pt-3 border-top">
                                                <div v-if="!editing.administrasi" class="d-flex align-items-center w-100 px-2 py-1 bg-light rounded mb-2">
                                                    <span class="fw-bold text-primary flex-grow-1">{{ form.administrasi_title }}</span>
                                                    <button type="button" class="btn btn-sm btn-link text-muted p-0" @click="startEdit('administrasi')">
                                                        <i class="fas fa-pen fa-xs"></i>
                                                    </button>
                                                </div>
                                                <div v-else class="input-group input-group-sm mb-3">
                                                    <span class="input-group-text bg-white border-end-0"><i class="fas fa-pen text-muted"></i></span>
                                                    <input 
                                                        :ref="el => inputRefs.administrasi = el"
                                                        type="text" 
                                                        v-model="form.administrasi_title" 
                                                        class="form-control fw-bold border-start-0 text-primary fs-6 shadow-sm" 
                                                        placeholder="Judul Bagian"
                                                        @blur="stopEdit('administrasi')"
                                                        @keyup.enter="stopEdit('administrasi')"
                                                    >
                                                </div>
                                                <div v-for="(item, index) in form.administrasi" :key="'adm'+index" class="input-group mb-2">
                                                    <input type="text" v-model="item.jabatan" class="form-control bg-light" style="max-width: 45%;" placeholder="Jabatan">
                                                    <input type="text" v-model="item.nama" class="form-control border-start-0" placeholder="Nama Pejabat">
                                                    <button type="button" class="btn btn-outline-danger" @click="form.administrasi.splice(index, 1)">
                                                        <i class="fas fa-times"></i>
                                                    </button>
                                                </div>
                                                <button type="button" class="btn btn-sm btn-light border-primary text-primary mt-2" @click="addItem(form.administrasi, 'Staf TU')">
                                                    <i class="fas fa-plus me-1"></i> Tambah Item
                                                </button>
                                            </div>

                                            <!-- Komite -->
                                            <div class="mb-3 pt-3 border-top">
                                                <div v-if="!editing.komite" class="d-flex align-items-center w-100 px-2 py-1 bg-light rounded mb-2">
                                                    <span class="fw-bold text-primary flex-grow-1">{{ form.komite_title }}</span>
                                                    <button type="button" class="btn btn-sm btn-link text-muted p-0" @click="startEdit('komite')">
                                                        <i class="fas fa-pen fa-xs"></i>
                                                    </button>
                                                </div>
                                                <div v-else class="input-group input-group-sm mb-3">
                                                    <span class="input-group-text bg-white border-end-0"><i class="fas fa-pen text-muted"></i></span>
                                                    <input 
                                                        :ref="el => inputRefs.komite = el"
                                                        type="text" 
                                                        v-model="form.komite_title" 
                                                        class="form-control fw-bold border-start-0 text-primary fs-6 shadow-sm" 
                                                        placeholder="Judul Bagian"
                                                        @blur="stopEdit('komite')"
                                                        @keyup.enter="stopEdit('komite')"
                                                    >
                                                </div>
                                                <div v-for="(item, index) in form.komite" :key="'kom'+index" class="input-group mb-2">
                                                    <input type="text" v-model="item.jabatan" class="form-control bg-light" style="max-width: 45%;" placeholder="Jabatan">
                                                    <input type="text" v-model="item.nama" class="form-control border-start-0" placeholder="Nama Pejabat">
                                                    <button type="button" class="btn btn-outline-danger" @click="form.komite.splice(index, 1)">
                                                        <i class="fas fa-times"></i>
                                                    </button>
                                                </div>
                                                <button type="button" class="btn btn-sm btn-light border-primary text-primary mt-2" @click="addItem(form.komite, 'Ketua Komite')">
                                                    <i class="fas fa-plus me-1"></i> Tambah Item
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Wali Kelas (Right) -->
                            <div class="col-lg-6">
                                <div class="card border-0 shadow-sm mb-4">
                                    <div class="card-header bg-transparent border-bottom py-3 d-flex justify-content-between align-items-center">
                                        <h5 class="mb-0 fw-bold text-success"><i class="fas fa-chalkboard-teacher me-2"></i>Wali Kelas</h5>
                                        <button type="button" class="btn btn-sm btn-success text-white" @click="addWali">
                                            <i class="fas fa-plus me-1"></i> Tambah
                                        </button>
                                    </div>
                                    <div class="card-body p-0">
                                        <div class="table-responsive">
                                            <table class="table table-hover mb-0">
                                                <thead class="bg-light">
                                                    <tr>
                                                        <th class="ps-4 fw-bold" style="width: 40%;">Kelas</th>
                                                        <th class="fw-bold">Nama Wali</th>
                                                        <th style="width: 50px;"></th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <tr v-for="(item, index) in form.wali_kelas" :key="index">
                                                        <td class="ps-4">
                                                            <input type="text" v-model="item.kelas" class="form-control form-control-sm border-0 bg-transparent fw-bold" placeholder="Kelas">
                                                        </td>
                                                        <td>
                                                            <input type="text" v-model="item.nama" class="form-control form-control-sm" placeholder="Nama Guru">
                                                        </td>
                                                        <td class="pe-3">
                                                            <button type="button" class="btn btn-sm text-danger" @click="form.wali_kelas.splice(index, 1)">
                                                                <i class="fas fa-trash-alt"></i>
                                                            </button>
                                                        </td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>

                                <div class="card border-0 shadow-sm">
                                    <div class="card-header bg-transparent border-bottom py-3">
                                        <h5 class="mb-0 fw-bold text-warning"><i class="fas fa-sticky-note me-2"></i>Catatan Tambahan</h5>
                                    </div>
                                    <div class="card-body">
                                        <textarea v-model="form.catatan" class="form-control" rows="4" 
                                            placeholder="Catatan tentang struktur organisasi..."></textarea>
                                        <p class="text-muted small mt-2 mb-0">Catatan ini akan ditampilkan di bawah bagan jika ada.</p>
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
import { reactive, onMounted, nextTick } from 'vue';
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

const defaultWali = [
    { kelas: 'Kelas I', nama: '—' },
    { kelas: 'Kelas II', nama: '—' },
    { kelas: 'Kelas III', nama: '—' },
    { kelas: 'Kelas IV', nama: '—' },
    { kelas: 'Kelas V', nama: '—' },
    { kelas: 'Kelas VI', nama: '—' },
];

const form = reactive({
    // Mode & Image
    mode_tampilan: props.section?.content?.mode_tampilan || 'text',
    org_chart_image: null,
    org_chart_image_url: props.section?.content?.org_chart_image || null,

    // Editable Titles (Labels)
    pengawas_label: props.section?.content?.pengawas_label || 'Pengawas Madrasah',
    kepala_label: props.section?.content?.kepala_label || 'Kepala Madrasah',
    pimpinan_title: props.section?.content?.pimpinan_title || 'Pimpinan/Wakil Kepala',
    koordinator_title: props.section?.content?.koordinator_title || 'Koordinator Program',
    administrasi_title: props.section?.content?.administrasi_title || 'Administrasi/TU',
    komite_title: props.section?.content?.komite_title || 'Komite Madrasah',

    // Data Content
    pengawas: props.section?.content?.pengawas || 'Ayi Ridwan',
    pimpinan: [], // Migrated onMounted
    koordinator: [], // Migrated onMounted
    administrasi: [], // Migrated onMounted
    komite: [], // Migrated onMounted
    wali_kelas: props.section?.content?.wali_kelas || [...defaultWali],
    catatan: props.section?.content?.catatan || 'Bagan dapat diperbarui sewaktu-waktu sesuai SK terbaru.',
    
    processing: false
});

// Helper: Migrate Data (String/Array String -> Array Object)
const migrateToList = (data, defaultJabatan = 'Jabatan') => {
    if (!data) return [];
    
    // Case 1: Array of Objects (Already migrated)
    if (Array.isArray(data) && data.length > 0 && typeof data[0] === 'object') {
        return data.map(item => ({ ...item }));
    }
    
    // Case 2: Array of Strings
    if (Array.isArray(data)) {
        return data.map(str => ({ jabatan: defaultJabatan, nama: str }));
    }

    // Case 3: Single String
    if (typeof data === 'string' && data.trim() !== '') {
        return [{ jabatan: defaultJabatan, nama: data }];
    }

    return [];
};

onMounted(() => {
    form.pimpinan = migrateToList(props.section?.content?.pimpinan, 'Wakil Kepala');
    form.koordinator = migrateToList(props.section?.content?.koordinator, 'Koordinator');
    form.administrasi = migrateToList(props.section?.content?.administrasi, 'Staf TU/Operator');
    form.komite = migrateToList(props.section?.content?.komite, 'Ketua Komite');
});

const addWali = () => {
    form.wali_kelas.push({ kelas: '', nama: '' });
};

const addItem = (list, defaultJabatan = '') => {
    list.push({ jabatan: defaultJabatan, nama: '' });
};

const handleImageUpload = (event) => {
    const file = event.target.files[0];
    if (file) {
        form.org_chart_image = file;
        form.org_chart_image_url = URL.createObjectURL(file);
    }
};

const editing = reactive({
    pengawas: false,
    kepala: false,
    pimpinan: false,
    koordinator: false,
    administrasi: false,
    komite: false
});

const inputRefs = reactive({});

const startEdit = (field) => {
    editing[field] = true;
    nextTick(() => {
        if (inputRefs[field]) inputRefs[field].focus();
    });
};

const stopEdit = (field) => {
    editing[field] = false;
};

const submit = () => {
    form.processing = true;

    // Filter empty items
    const filterList = (list) => list.filter(item => item.nama && item.nama.trim() !== '');

    // Prepare JSON lists
    // Note: We send arrays of objects. Backend should handle this if configured correctly.
    // However, if the backend expects JSON strings for these fields (as per my Controller change),
    // we should stringify them. Check PageController logic line 253: `json_decode`.
    // Yes, for FormData, objects inside keys might need stringify if sent as text fields, 
    // or sent as array keys `pimpinan[0][jabatan]`.
    // My Controller logic `is_string($value) && in_array(...)` implies it attempts to decode JSON strings.
    // BUT Inertia with `forceFormData` handles nested objects by flattening them (pimpinan[0][jabatan]).
    // Wait, `json_decode` only works if the value IS A STRING.
    // If Inertia sends them as `pimpinan[0][jabatan]`, `$request->input('pimpinan')` will be an ARRAY.
    // My Controller lines 253 checks `is_string($value)`.
    // So if it comes as Array, it bypasses decode and goes to `currentContent[$field] = $value`.
    // This is CORRECT and SAFE.

    router.post(`/admin/pages/${props.section?.key || 'organisasi'}`, {
        _method: 'PUT',
        
        // Mode & Image
        mode_tampilan: form.mode_tampilan,
        org_chart_image: form.org_chart_image,

        // Titles
        pengawas_label: form.pengawas_label,
        kepala_label: form.kepala_label,
        pimpinan_title: form.pimpinan_title,
        koordinator_title: form.koordinator_title,
        administrasi_title: form.administrasi_title,
        komite_title: form.komite_title,

        // Data Content (Filtered)
        pengawas: form.pengawas,
        pimpinan: JSON.stringify(filterList(form.pimpinan)), // Force JSON string for specific controller handling if needed?
        // Actually, if I send as Array, Inertia FormData sends `pimpinan[0][nama]: ...`.
        // Controller `$request->input('pimpinan')` returns Array.
        // My Controller: `if (is_string($value) ... decode ...`.
        // So sending Array is fine.
        // WAIT. If I use `JSON.stringify` here, it sends a STRING. Simple and safe. 
        // Let's use stringify to match the `is_string` check explicitly or ensures it's stored as JSON in DB if DB column is text.
        // DB content column is JSON type usually (Laravel cast attribute).
        // If I send Array, Laravel casts it to JSON automatically on save?
        // Let's use JSON.stringify to be absolutely sure it passes as a single field value.

        pimpinan: JSON.stringify(filterList(form.pimpinan)),
        koordinator: JSON.stringify(filterList(form.koordinator)),
        administrasi: JSON.stringify(filterList(form.administrasi)),
        komite: JSON.stringify(filterList(form.komite)),
        wali_kelas: JSON.stringify(form.wali_kelas.filter(w => w.kelas || w.nama)),
        catatan: form.catatan
    }, {
        forceFormData: true,
        preserveScroll: true,
        onSuccess: () => {
            Swal.fire({
                icon: 'success',
                title: 'Berhasil!',
                text: 'Struktur Organisasi diperbarui!',
                confirmButtonColor: '#4361ee',
                timer: 2000,
                timerProgressBar: true
            });
            // Sync local data
            form.pimpinan = filterList(form.pimpinan);
            form.koordinator = filterList(form.koordinator);
            form.administrasi = filterList(form.administrasi);
            form.komite = filterList(form.komite);
            form.org_chart_image = null; 
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
.section-card {
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
}
.hover-transform:hover {
    transform: translateY(-5px);
}
.hover-bg-light-darker:hover {
    background-color: #e9ecef !important;
}
.cursor-pointer {
    cursor: pointer;
}
.border-dashed {
    border-style: dashed !important;
}
</style>

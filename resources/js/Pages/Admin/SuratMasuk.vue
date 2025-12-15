<script setup>
import { ref, reactive, computed } from 'vue';
import { Head, useForm, router } from '@inertiajs/vue3';
import AdminLayout from '@/Layouts/AdminLayout.vue';
import ToastNotification from '@/Components/ToastNotification.vue';
import VueEasyDataTable from 'vue3-easy-data-table';
import 'vue3-easy-data-table/dist/style.css';

const props = defineProps({
    suratMasuk: Array,
});

const toast = ref(null);
const showModal = ref(false);
const isEditing = ref(false);
const fileInput = ref(null);

// Form
const form = useForm({
    id: null,
    nomor_agenda: '',
    nomor_surat: '',
    pengirim: '',
    perihal: '',
    tgl_surat: new Date().toISOString().substr(0, 10),
    tgl_diterima: new Date().toISOString().substr(0, 10),
    file_scan: null, // File Object
    keterangan: '',
});

const computedItems = computed(() => {
    return props.suratMasuk.map((item, index) => ({
        ...item,
        no: index + 1
    }));
});

const headers = [
    { text: "No", value: "no", width: 50 },
    { text: "No. Surat", value: "nomor_surat" },
    { text: "Pengirim", value: "pengirim" },
    { text: "Perihal", value: "perihal" },
    { text: "Tgl Terima", value: "tgl_diterima" },
    { text: "File", value: "file_info" },
    { text: "Aksi", value: "actions", width: 150 },
];

// Actions
const openAddModal = () => {
    form.reset();
    form.id = null;
    isEditing.value = false;
    showModal.value = true;
};

const openEditModal = (item) => {
    form.id = item.id;
    form.nomor_agenda = item.nomor_agenda || '';
    form.nomor_surat = item.nomor_surat;
    form.pengirim = item.pengirim;
    form.perihal = item.perihal;
    form.tgl_surat = item.tgl_surat;
    form.tgl_diterima = item.tgl_diterima;
    form.keterangan = item.keterangan;
    form.file_scan = null; // Don't prefill file
    
    isEditing.value = true;
    showModal.value = true;
};

const submitForm = () => {
    const url = isEditing.value ? `/admin/surat-masuk/${form.id}` : '/admin/surat-masuk';
    // Use POST for both, but spoof PUT for Update if needed (Inertia handles FormData automatically now for simple post?)
    // Actually, for file upload in Laravel with PUT/PATCH, we often need _method: PUT and post.
    
    if (isEditing.value) {
        form.post(url, {
            data: { _method: 'PUT' },
            onSuccess: () => {
                showModal.value = false;
                toast.value?.showSuccessToast('Berhasil', 'Data Surat Masuk diperbarui');
            }
        });
    } else {
        form.post(url, {
            onSuccess: () => {
                showModal.value = false;
                toast.value?.showSuccessToast('Berhasil', 'Surat Masuk dicatat');
            }
        });
    }
};

const deleteSurat = (id) => {
    if(confirm('Hapus surat masuk ini? File scan juga akan dihapus.')) {
        router.delete(`/admin/surat-masuk/${id}`, {
             onSuccess: () => toast.value?.showSuccessToast('Berhasil', 'Data dihapus')
        });
    }
};

const viewFile = (path) => {
    if (path) window.open(`/storage/${path}`, '_blank');
};
</script>

<template>
    <AdminLayout>
        <Head title="Surat Masuk" />
        
        <div class="content container-fluid">
            <!-- Header -->
            <div class="page-header mb-4">
                <div class="row align-items-center">
                    <div class="col">
                        <h3 class="page-title">Surat Masuk</h3>
                        <ul class="breadcrumb">
                            <li class="breadcrumb-item"><a href="/dashboard">Dashboard</a></li>
                            <li class="breadcrumb-item active">Surat Masuk</li>
                        </ul>
                    </div>
                    <div class="col-auto">
                        <button class="btn btn-primary" @click="openAddModal">
                            <i class="fas fa-plus me-2"></i> CATAT SURAT MASUK
                        </button>
                    </div>
                </div>
            </div>

            <!-- Table -->
            <div class="card modern-table-card animate__animated animate__fadeIn">
                <div class="card-header bg-white border-bottom py-3">
                    <h5 class="m-0 fw-bold text-success">Daftar Surat Masuk</h5>
                </div>
                <div class="card-body p-0">
                    <VueEasyDataTable :headers="headers" :items="computedItems" table-class-name="modern-data-table" border-cell alternating>
                        <template #item-no="{ no }">
                            <span class="fw-bold text-secondary">{{ no }}</span>
                        </template>
                        <template #item-tgl_diterima="item">
                            {{ new Date(item.tgl_diterima).toLocaleDateString('id-ID', {day: 'numeric', month: 'short', year: 'numeric'}) }}
                        </template>
                        <template #item-file_info="item">
                            <button v-if="item.file_path" class="btn btn-sm btn-outline-primary" @click="viewFile(item.file_path)">
                                <i class="fas fa-paperclip me-1"></i> Buka File
                            </button>
                            <span v-else class="text-muted small">-</span>
                        </template>
                        <template #item-actions="item">
                            <div class="action-buttons">
                                <button class="btn btn-action btn-edit bg-warning text-dark me-2" @click="openEditModal(item)"><i class="fas fa-edit"></i></button>
                                <button class="btn btn-action btn-delete" @click="deleteSurat(item.id)"><i class="fas fa-trash"></i></button>
                            </div>
                        </template>
                    </VueEasyDataTable>
                </div>
            </div>
        </div>

        <!-- MODAL FORM -->
        <div v-if="showModal" class="modal-backdrop fade show"></div>
        <div v-if="showModal" class="modal fade show d-block" tabindex="-1">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title fw-bold">{{ isEditing ? 'Edit Surat Masuk' : 'Catat Surat Masuk' }}</h5>
                        <button type="button" class="btn-close" @click="showModal = false"></button>
                    </div>
                    <div class="modal-body">
                         <form @submit.prevent="submitForm">
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Nomor Agenda (Opsional)</label>
                                    <input type="text" class="form-control" v-model="form.nomor_agenda" placeholder="Contoh: 001/XII/2024">
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Nomor Surat <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" v-model="form.nomor_surat" required placeholder="Nomor dari Pengirim">
                                </div>
                            </div>
                            
                            <div class="mb-3">
                                <label class="form-label">Pengirim <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" v-model="form.pengirim" required placeholder="Instansi/Perorangan">
                            </div>
                            
                            <div class="mb-3">
                                <label class="form-label">Perihal <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" v-model="form.perihal" required placeholder="Inti surat...">
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Tanggal Surat <span class="text-danger">*</span></label>
                                    <input type="date" class="form-control" v-model="form.tgl_surat" required>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Tanggal Diterima <span class="text-danger">*</span></label>
                                    <input type="date" class="form-control" v-model="form.tgl_diterima" required>
                                </div>
                            </div>

                             <div class="mb-3">
                                <label class="form-label">File Scan (PDF/Gambar)</label>
                                <input type="file" class="form-control" @input="form.file_scan = $event.target.files[0]">
                                <small class="text-muted" v-if="isEditing">Biarkan kosong jika tidak ingin mengubah file.</small>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Keterangan Tambahan</label>
                                <textarea class="form-control" rows="3" v-model="form.keterangan"></textarea>
                            </div>

                            <div class="modal-footer px-0 pb-0">
                                <button type="button" class="btn btn-light border" @click="showModal = false">Batal</button>
                                <button type="submit" class="btn btn-primary" :disabled="form.processing">Simpan</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <ToastNotification ref="toast" />
    </AdminLayout>
</template>

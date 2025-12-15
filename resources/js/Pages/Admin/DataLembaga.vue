<script setup>
import { ref, onMounted } from 'vue';
import { Head, useForm, usePage } from '@inertiajs/vue3';
import AdminLayout from '@/Layouts/AdminLayout.vue';
import ToastNotification from '@/Components/ToastNotification.vue';

const props = defineProps({
    lembaga: Object,
});

const form = useForm({
    nama_sekolah: props.lembaga?.nama_sekolah || '',
    npsn: props.lembaga?.npsn || '',
    status: props.lembaga?.status || 'Negeri',
    akreditasi: props.lembaga?.akreditasi || '',
    akreditasi: props.lembaga?.akreditasi || '',
    kepala_sekolah: props.lembaga?.kepala_sekolah || '',
    tahun_berdiri: props.lembaga?.tahun_berdiri || '',
    email: props.lembaga?.email || '',
    telepon: props.lembaga?.telepon || '',
    alamat: props.lembaga?.alamat || '',
    logo: null,
});

import { watch } from 'vue';

const logoPreview = ref(props.lembaga?.logo ? `/storage/${props.lembaga.logo}` : null);
const toast = ref(null);
const page = usePage();

const handleLogoUpload = (event) => {
    const file = event.target.files[0];
    if (file) {
        form.logo = file;
        logoPreview.value = URL.createObjectURL(file);
    }
};

const submit = () => {
    form.post('/admin/lembaga', {
        preserveScroll: true,
        onSuccess: () => {
             // Toast handled by watcher
        }
    });
};

watch(() => props.lembaga, (newLembaga) => {
    if (newLembaga?.logo) {
        logoPreview.value = `/storage/${newLembaga.logo}`;
    } else {
        logoPreview.value = null;
    }
}, { deep: true });

watch(() => page.props.flash, (flash) => {
    if (flash?.success) toast.value?.showSuccessToast('Sukses', flash.success);
    if (flash?.error) toast.value?.showErrorToast('Error', flash.error);
}, { deep: true });

</script>

<template>
    <AdminLayout>
        <Head title="Data Lembaga" />

        <div class="content container-fluid">
            <!-- Page Header -->
            <div class="page-header">
                <div class="row">
                    <div class="col">
                        <h3 class="page-title">Profil Lembaga</h3>
                        <ul class="breadcrumb">
                            <li class="breadcrumb-item"><a href="/dashboard">Dashboard</a></li>
                            <li class="breadcrumb-item active">Lembaga</li>
                        </ul>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="card-title">Informasi Sekolah</h5>
                        </div>
                        <div class="card-body">
                            <form @submit.prevent="submit">
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="d-flex align-items-center mb-4">
                                            <div class="me-4">
                                                <div class="avatar-upload">
                                                    <div class="avatar-preview">
                                                        <div v-if="logoPreview" 
                                                            :style="{ 'background-image': `url(${logoPreview})` }" 
                                                            class="preview-image">
                                                        </div>
                                                        <div v-else class="preview-placeholder">
                                                            <i class="feather-image"></i>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div>
                                                <label class="btn btn-primary btn-sm mb-2">
                                                    <i class="feather-upload me-1"></i> Upload Logo
                                                    <input type="file" class="d-none" @change="handleLogoUpload" accept="image/*">
                                                </label>
                                                <p class="text-muted small mb-0">Rekomendasi: PNG, JPG, max 2MB</p>
                                                <div v-if="form.errors.logo" class="text-danger small">{{ form.errors.logo }}</div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group mb-3">
                                            <label class="form-label">Nama Sekolah <span class="text-danger">*</span></label>
                                            <input type="text" class="form-control" v-model="form.nama_sekolah">
                                            <div v-if="form.errors.nama_sekolah" class="text-danger small">{{ form.errors.nama_sekolah }}</div>
                                        </div>
                                        
                                        <div class="form-group mb-3">
                                            <label class="form-label">NPSN</label>
                                            <input type="text" class="form-control" v-model="form.npsn">
                                            <div v-if="form.errors.npsn" class="text-danger small">{{ form.errors.npsn }}</div>
                                        </div>

                                        <div class="form-group mb-3">
                                            <label class="form-label">Status Sekolah <span class="text-danger">*</span></label>
                                            <select class="form-select" v-model="form.status">
                                                <option value="Negeri">Negeri</option>
                                                <option value="Swasta">Swasta</option>
                                            </select>
                                            <div v-if="form.errors.status" class="text-danger small">{{ form.errors.status }}</div>
                                        </div>

                                        <div class="form-group mb-3">
                                            <label class="form-label">Akreditasi</label>
                                            <select class="form-select" v-model="form.akreditasi">
                                                <option value="">Pilih Akreditasi</option>
                                                <option value="A">A</option>
                                                <option value="B">B</option>
                                                <option value="C">C</option>
                                                <option value="Belum Terakreditasi">Belum Terakreditasi</option>
                                            </select>
                                            <div v-if="form.errors.akreditasi" class="text-danger small">{{ form.errors.akreditasi }}</div>
                                        </div>

                                        <div class="form-group mb-3">
                                            <label class="form-label">Tahun Berdiri (Contoh: 1990)</label>
                                            <input type="number" class="form-control" v-model="form.tahun_berdiri" min="1900">
                                             <div v-if="form.errors.tahun_berdiri" class="text-danger small">{{ form.errors.tahun_berdiri }}</div>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group mb-3">
                                            <label class="form-label">Kepala Sekolah</label>
                                            <input type="text" class="form-control" v-model="form.kepala_sekolah">
                                            <div v-if="form.errors.kepala_sekolah" class="text-danger small">{{ form.errors.kepala_sekolah }}</div>
                                        </div>

                                        <div class="form-group mb-3">
                                            <label class="form-label">Email</label>
                                            <input type="email" class="form-control" v-model="form.email">
                                            <div v-if="form.errors.email" class="text-danger small">{{ form.errors.email }}</div>
                                        </div>

                                        <div class="form-group mb-3">
                                            <label class="form-label">Nomor Telepon</label>
                                            <input type="text" class="form-control" v-model="form.telepon">
                                            <div v-if="form.errors.telepon" class="text-danger small">{{ form.errors.telepon }}</div>
                                        </div>
                                    </div>

                                    <div class="col-md-12">
                                        <div class="form-group mb-3">
                                            <label class="form-label">Alamat Lengkap</label>
                                            <textarea class="form-control" rows="3" v-model="form.alamat"></textarea>
                                            <div v-if="form.errors.alamat" class="text-danger small">{{ form.errors.alamat }}</div>
                                        </div>
                                    </div>
                                </div>

                                <div class="text-end pt-3 border-top">
                                    <button type="submit" class="btn btn-primary" :disabled="form.processing">
                                        <i class="feather-save me-1"></i> {{ form.processing ? 'Menyimpan...' : 'Simpan Perubahan' }}
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <ToastNotification ref="toast" />
    </AdminLayout>
</template>

<style scoped>
.avatar-preview {
    width: 100px;
    height: 100px;
    border-radius: 10px;
    overflow: hidden;
    background-color: #f3f4f6;
    border: 2px dashed #d1d5db;
    display: flex;
    align-items: center;
    justify-content: center;
}

.preview-image {
    width: 100%;
    height: 100%;
    background-size: cover;
    background-position: center;
}

.preview-placeholder {
    font-size: 2rem;
    color: #9ca3af;
}
</style>

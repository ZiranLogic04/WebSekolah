<script setup>
import { ref, watch } from 'vue';
import { Head, useForm, usePage } from '@inertiajs/vue3';
import AdminLayout from '@/Layouts/AdminLayout.vue';
import { showToast } from '@/Utils/swal';

const props = defineProps({
    lembaga: Object,
    tahunAjarans: Array,
});

// Local state untuk list tahun ajaran (agar bisa diupdate tanpa refresh)
const localTahunAjarans = ref(props.tahunAjarans || []);
const showTahunAjaranModal = ref(false);

// Form untuk tambah tahun ajaran baru
const tahunAjaranForm = useForm({
    tahun: '',
});

const submitTahunAjaran = () => {
    tahunAjaranForm.post('/admin/tahun-ajaran', {
        preserveScroll: true,
        onSuccess: (page) => {
            showTahunAjaranModal.value = false;
            tahunAjaranForm.reset();
            // Update local list
            if (page.props.tahunAjarans) {
                localTahunAjarans.value = page.props.tahunAjarans;
            }
             showToast('success', 'Sukses', 'Tahun ajaran baru berhasil ditambahkan.');
        },
        onError: () => {
             showToast('error', 'Gagal', 'Gagal menambahkan tahun ajaran.');
        }
    });
};

const form = useForm({
    nama_sekolah: props.lembaga?.nama_sekolah || '',
    npsn: props.lembaga?.npsn || '',
    status: props.lembaga?.status || 'Negeri',
    akreditasi: props.lembaga?.akreditasi || '',
    kepala_sekolah: props.lembaga?.kepala_sekolah || '',
    tahun_berdiri: props.lembaga?.tahun_berdiri || '',
    tahun_ajaran_aktif_id: props.lembaga?.tahun_ajaran_aktif_id || null,
    semester_aktif: props.lembaga?.semester_aktif || 'Ganjil',
    email: props.lembaga?.email || '',
    telepon: props.lembaga?.telepon || '',
    alamat: props.lembaga?.alamat || '',
    facebook: props.lembaga?.facebook || '',
    instagram: props.lembaga?.instagram || '',
    twitter: props.lembaga?.twitter || '',
    youtube: props.lembaga?.youtube || '',
    whatsapp: props.lembaga?.whatsapp || '',
    google_maps: props.lembaga?.google_maps || '',
    logo: null,
});


const logoPreview = ref(props.lembaga?.logo ? `/storage/${props.lembaga.logo}` : null);
const page = usePage();

const handleLogoUpload = (event) => {
    const file = event.target.files[0];
    if (file) {
        form.logo = file;
        logoPreview.value = URL.createObjectURL(file);
    }
};

const submit = () => {
    form.transform(data => ({
        ...data,
        tahun_ajaran_aktif_id: data.tahun_ajaran_aktif_id || null,
    })).post('/admin/lembaga', {
        preserveScroll: true,
        forceFormData: true,
        onSuccess: () => {
             // Toast handled by watcher
        },
        onError: () => {
            showToast('error', 'Gagal', 'Gagal menyimpan perubahan. Periksa kembali inputan anda.');
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
    if (flash?.success) showToast('success', 'Sukses', flash.success);
    if (flash?.error) showToast('error', 'Error', flash.error);
}, { deep: true, immediate: true });

</script>

<template>
    <AdminLayout>
        <Head title="Data Lembaga" />

        <div class="content container-fluid">
            <!-- Radiant Header Card -->
            <div class="card border-0 shadow-lg rounded-4 mb-4 overflow-hidden position-relative" style="background: linear-gradient(135deg, #4f46e5 0%, #3b82f6 100%);">
                <div class="card-body p-4 p-lg-5 position-relative z-1">
                    <div class="row align-items-center">
                        <div class="col-lg-8">
                            <div class="d-flex align-items-center gap-3 mb-2">
                                <div class="icon-box bg-white rounded-3 text-primary p-2">
                                    <i class="fas fa-university fs-3"></i>
                                </div>
                                <h2 class="fw-bold text-white mb-0 ls-tight">Data Lembaga</h2>
                            </div>
                            <p class="text-white-50 mb-0 fs-5">Kelola identitas sekolah, tahun ajaran, dan media sosial.</p>
                        </div>
                    </div>
                </div>
                <!-- Decorative Background -->
                <div class="position-absolute bottom-0 end-0 opacity-10 me-n5 mb-n5">
                    <i class="fas fa-school" style="font-size: 10rem; color: white;"></i>
                </div>
            </div>

            <div class="row">
                <div class="col-md-12">
                    <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
                        <div class="card-header bg-white border-bottom-0 py-4 px-4">
                            <h5 class="fw-bold mb-0 text-dark">Informasi Sekolah</h5>
                        </div>
                        <div class="card-body p-4">
                            <form @submit.prevent="submit">
                                <!-- Logo Section -->
                                <div class="row mb-5 justify-content-center">
                                    <div class="col-md-8 text-center">
                                        <div class="logo-upload-container position-relative d-inline-block mb-3">
                                            <div class="avatar-upload shadow-sm border border-2 border-light rounded-4 overflow-hidden position-relative">
                                                <div class="avatar-preview bg-light d-flex align-items-center justify-content-center" style="width: 150px; height: 150px;">
                                                    <div v-if="logoPreview" 
                                                        :style="{ backgroundImage: `url(${logoPreview})` }" 
                                                        class="preview-image w-100 h-100" style="background-size: cover; background-position: center;">
                                                    </div>
                                                    <div v-else class="preview-placeholder text-muted opacity-50">
                                                        <i class="fas fa-image fa-3x"></i>
                                                    </div>
                                                </div>
                                                <div class="upload-overlay position-absolute top-0 start-0 w-100 h-100 d-flex align-items-center justify-content-center bg-dark bg-opacity-50 opacity-0 transition-opacity" style="cursor: pointer;" @click="$refs.logoInput.click()">
                                                    <i class="fas fa-camera text-white fs-3"></i>
                                                </div>
                                            </div>
                                            <button type="button" class="btn btn-sm btn-light position-absolute bottom-0 end-0 rounded-circle shadow-sm border" style="width: 32px; height: 32px;" @click="$refs.logoInput.click()">
                                                <i class="fas fa-pen text-primary small"></i>
                                            </button>
                                        </div>
                                        <div>
                                            <h6 class="fw-bold text-dark mb-1">Logo Sekolah</h6>
                                            <p class="text-muted small mb-0">Format: PNG, JPG, WebP (Max 2MB)</p>
                                            <input type="file" ref="logoInput" class="d-none" @change="handleLogoUpload" accept="image/*">
                                            <div v-if="form.errors.logo" class="text-danger small mt-1 fw-bold">{{ form.errors.logo }}</div>
                                        </div>
                                    </div>
                                </div>

                                <div class="row g-4">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="form-label fw-medium text-secondary small text-uppercase ls-1">Nama Sekolah <span class="text-danger">*</span></label>
                                            <div class="input-group">
                                                <span class="input-group-text bg-light border-end-0"><i class="fas fa-school text-muted"></i></span>
                                                <input type="text" class="form-control border-start-0 ps-0 bg-light" v-model="form.nama_sekolah" :class="{ 'is-invalid': form.errors.nama_sekolah }" placeholder="Masukkan nama sekolah">
                                                <div v-if="form.errors.nama_sekolah" class="invalid-feedback">{{ form.errors.nama_sekolah }}</div>
                                            </div>
                                        </div>
                                        
                                        <div class="form-group mt-3">
                                            <label class="form-label fw-medium text-secondary small text-uppercase ls-1">NPSN</label>
                                            <div class="input-group">
                                                <span class="input-group-text bg-light border-end-0"><i class="fas fa-id-card text-muted"></i></span>
                                                <input type="text" class="form-control border-start-0 ps-0 bg-light" v-model="form.npsn" :class="{ 'is-invalid': form.errors.npsn }" placeholder="Nomor Pokok Sekolah Nasional">
                                                <div v-if="form.errors.npsn" class="invalid-feedback">{{ form.errors.npsn }}</div>
                                            </div>
                                        </div>

                                        <div class="form-group mt-3">
                                            <label class="form-label fw-medium text-secondary small text-uppercase ls-1">Status Sekolah <span class="text-danger">*</span></label>
                                            <select class="form-select bg-light" v-model="form.status" :class="{ 'is-invalid': form.errors.status }">
                                                <option value="Negeri">Negeri</option>
                                                <option value="Swasta">Swasta</option>
                                            </select>
                                            <div v-if="form.errors.status" class="invalid-feedback">{{ form.errors.status }}</div>
                                        </div>

                                        <div class="form-group mt-3">
                                            <label class="form-label fw-medium text-secondary small text-uppercase ls-1">Akreditasi</label>
                                            <select class="form-select bg-light" v-model="form.akreditasi" :class="{ 'is-invalid': form.errors.akreditasi }">
                                                <option value="">Pilih Akreditasi</option>
                                                <option value="A">A</option>
                                                <option value="B">B</option>
                                                <option value="C">C</option>
                                                <option value="Belum Terakreditasi">Belum Terakreditasi</option>
                                            </select>
                                            <div v-if="form.errors.akreditasi" class="invalid-feedback">{{ form.errors.akreditasi }}</div>
                                        </div>

                                        <div class="form-group mt-3">
                                            <label class="form-label fw-medium text-secondary small text-uppercase ls-1">Tahun Berdiri</label>
                                            <div class="input-group">
                                                <span class="input-group-text bg-light border-end-0"><i class="fas fa-calendar-alt text-muted"></i></span>
                                                <input type="number" class="form-control border-start-0 ps-0 bg-light" v-model="form.tahun_berdiri" min="1900" :class="{ 'is-invalid': form.errors.tahun_berdiri }" placeholder="Contoh: 1990">
                                                <div v-if="form.errors.tahun_berdiri" class="invalid-feedback">{{ form.errors.tahun_berdiri }}</div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="form-label fw-medium text-secondary small text-uppercase ls-1">Kepala Madrasah</label>
                                            <div class="input-group">
                                                <span class="input-group-text bg-light border-end-0"><i class="fas fa-user-tie text-muted"></i></span>
                                                <input type="text" class="form-control border-start-0 ps-0 bg-light" v-model="form.kepala_sekolah" :class="{ 'is-invalid': form.errors.kepala_sekolah }" placeholder="Nama Kepala Sekolah">
                                                <div v-if="form.errors.kepala_sekolah" class="invalid-feedback">{{ form.errors.kepala_sekolah }}</div>
                                            </div>
                                        </div>

                                        <div class="form-group mt-3">
                                            <label class="form-label fw-medium text-secondary small text-uppercase ls-1">Tahun Ajaran Aktif System <span class="text-danger">*</span></label>
                                            <div class="input-group">
                                                <select class="form-select bg-light" v-model="form.tahun_ajaran_aktif_id" :class="{ 'is-invalid': form.errors.tahun_ajaran_aktif_id }">
                                                    <option :value="null">Pilih Tahun Ajaran</option>
                                                    <option v-for="ta in localTahunAjarans" :key="ta.id" :value="ta.id">
                                                        {{ ta.tahun }}
                                                    </option>
                                                </select>
                                                <button type="button" class="btn btn-primary" @click="showTahunAjaranModal = true" title="Tambah Tahun Ajaran">
                                                    <i class="fas fa-plus"></i>
                                                </button>
                                                <div v-if="form.errors.tahun_ajaran_aktif_id" class="invalid-feedback">{{ form.errors.tahun_ajaran_aktif_id }}</div>
                                            </div>
                                            <small class="text-muted d-block mt-1"><i class="fas fa-info-circle me-1"></i> Default saat login pertama kali.</small>
                                        </div>

                                        <div class="form-group mt-3">
                                            <label class="form-label fw-medium text-secondary small text-uppercase ls-1">Semester Aktif System <span class="text-danger">*</span></label>
                                            <select class="form-select bg-light" v-model="form.semester_aktif" :class="{ 'is-invalid': form.errors.semester_aktif }">
                                                <option value="Ganjil">Ganjil</option>
                                                <option value="Genap">Genap</option>
                                            </select>
                                            <div v-if="form.errors.semester_aktif" class="invalid-feedback">{{ form.errors.semester_aktif }}</div>
                                        </div>

                                        <div class="form-group mt-3">
                                            <label class="form-label fw-medium text-secondary small text-uppercase ls-1">Email Sekolah</label>
                                            <div class="input-group">
                                                <span class="input-group-text bg-light border-end-0"><i class="fas fa-envelope text-muted"></i></span>
                                                <input type="email" class="form-control border-start-0 ps-0 bg-light" v-model="form.email" :class="{ 'is-invalid': form.errors.email }" placeholder="email@sekolah.sch.id">
                                                <div v-if="form.errors.email" class="invalid-feedback">{{ form.errors.email }}</div>
                                            </div>
                                        </div>

                                        <div class="form-group mt-3">
                                            <label class="form-label fw-medium text-secondary small text-uppercase ls-1">Nomor Telepon</label>
                                            <div class="input-group">
                                                <span class="input-group-text bg-light border-end-0"><i class="fas fa-phone text-muted"></i></span>
                                                <input type="text" class="form-control border-start-0 ps-0 bg-light" v-model="form.telepon" :class="{ 'is-invalid': form.errors.telepon }" placeholder="021-xxxxxxx">
                                                <div v-if="form.errors.telepon" class="invalid-feedback">{{ form.errors.telepon }}</div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label class="form-label fw-medium text-secondary small text-uppercase ls-1">Alamat Lengkap</label>
                                            <textarea class="form-control bg-light" rows="3" v-model="form.alamat" :class="{ 'is-invalid': form.errors.alamat }" placeholder="Alamat lengkap sekolah..."></textarea>
                                            <div v-if="form.errors.alamat" class="invalid-feedback">{{ form.errors.alamat }}</div>
                                        </div>
                                    </div>

                                    <!-- Media Sosial Section -->
                                    <div class="col-12 mt-4 mb-3">
                                        <div class="d-flex align-items-center gap-2 border-bottom pb-2">
                                            <i class="fas fa-share-alt text-primary fs-5"></i>
                                            <h6 class="fw-bold text-dark mb-0 ls-tight">Media Sosial</h6>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="form-label fw-medium text-secondary small text-uppercase ls-1">Facebook URL</label>
                                            <div class="input-group">
                                                <span class="input-group-text bg-light border-end-0"><i class="fab fa-facebook text-primary"></i></span>
                                                <input type="url" class="form-control border-start-0 ps-0 bg-light" v-model="form.facebook" :class="{ 'is-invalid': form.errors.facebook }" placeholder="https://facebook.com/namasekolah">
                                                <div v-if="form.errors.facebook" class="invalid-feedback">{{ form.errors.facebook }}</div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="form-label fw-medium text-secondary small text-uppercase ls-1">Instagram URL</label>
                                            <div class="input-group">
                                                <span class="input-group-text bg-light border-end-0"><i class="fab fa-instagram text-danger"></i></span>
                                                <input type="url" class="form-control border-start-0 ps-0 bg-light" v-model="form.instagram" :class="{ 'is-invalid': form.errors.instagram }" placeholder="https://instagram.com/namasekolah">
                                                <div v-if="form.errors.instagram" class="invalid-feedback">{{ form.errors.instagram }}</div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group mt-3">
                                            <label class="form-label fw-medium text-secondary small text-uppercase ls-1">Twitter/X URL</label>
                                            <div class="input-group">
                                                <span class="input-group-text bg-light border-end-0"><i class="fab fa-twitter text-info"></i></span>
                                                <input type="url" class="form-control border-start-0 ps-0 bg-light" v-model="form.twitter" :class="{ 'is-invalid': form.errors.twitter }" placeholder="https://twitter.com/namasekolah">
                                                <div v-if="form.errors.twitter" class="invalid-feedback">{{ form.errors.twitter }}</div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group mt-3">
                                            <label class="form-label fw-medium text-secondary small text-uppercase ls-1">YouTube URL</label>
                                            <div class="input-group">
                                                <span class="input-group-text bg-light border-end-0"><i class="fab fa-youtube text-danger"></i></span>
                                                <input type="url" class="form-control border-start-0 ps-0 bg-light" v-model="form.youtube" :class="{ 'is-invalid': form.errors.youtube }" placeholder="https://youtube.com/c/namasekolah">
                                                <div v-if="form.errors.youtube" class="invalid-feedback">{{ form.errors.youtube }}</div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group mt-3">
                                            <label class="form-label fw-medium text-secondary small text-uppercase ls-1">Nomor WhatsApp</label>
                                            <div class="input-group">
                                                <span class="input-group-text bg-light border-end-0"><i class="fab fa-whatsapp text-success"></i></span>
                                                <input type="text" class="form-control border-start-0 ps-0 bg-light" v-model="form.whatsapp" :class="{ 'is-invalid': form.errors.whatsapp }" placeholder="6281234567890">
                                                <div v-if="form.errors.whatsapp" class="invalid-feedback">{{ form.errors.whatsapp }}</div>
                                            </div>
                                            <small class="text-muted d-block mt-1">Format: 62xxx (tanpa + atau spasi)</small>
                                        </div>
                                    </div>

                                    <!-- Google Maps Section -->
                                    <div class="col-12 mt-4 mb-3">
                                        <div class="d-flex align-items-center gap-2 border-bottom pb-2">
                                            <i class="fas fa-map-marked-alt text-primary fs-5"></i>
                                            <h6 class="fw-bold text-dark mb-0 ls-tight">Lokasi (Google Maps)</h6>
                                        </div>
                                    </div>

                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label class="form-label fw-medium text-secondary small text-uppercase ls-1">Google Maps Embed URL</label>
                                            <div class="input-group">
                                                <span class="input-group-text bg-light border-end-0"><i class="fas fa-map-pin text-danger"></i></span>
                                                <input type="text" class="form-control border-start-0 ps-0 bg-light" v-model="form.google_maps" :class="{ 'is-invalid': form.errors.google_maps }" placeholder="https://www.google.com/maps?q=Nama Sekolah&output=embed">
                                                <div v-if="form.errors.google_maps" class="invalid-feedback">{{ form.errors.google_maps }}</div>
                                            </div>
                                            <small class="text-muted d-block mt-1">Kosongkan jika ingin auto-generate dari alamat.</small>
                                        </div>
                                    </div>
                                </div>

                                <div class="text-end pt-4 mt-4 border-top">
                                    <button type="submit" class="btn btn-primary px-5 btn-lg shadow-sm btn-gradient" :disabled="form.processing">
                                        <span v-if="form.processing" class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span>
                                        <i v-else class="fas fa-save me-2"></i> 
                                        {{ form.processing ? 'Menyimpan...' : 'Simpan Perubahan' }}
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Modal Tambah Tahun Ajaran -->
        <div v-if="showTahunAjaranModal" class="modal fade show d-block" tabindex="-1" style="background-color: rgba(0,0,0,0.5); z-index: 1055;">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content border-0 shadow-lg rounded-4 overflow-hidden">
                    <div class="modal-header border-0 p-4" style="background: linear-gradient(135deg, #4f46e5 0%, #3b82f6 100%);">
                        <h5 class="modal-title text-white fw-bold">Tambah Tahun Ajaran Baru</h5>
                        <button type="button" class="btn-close btn-close-white" @click="showTahunAjaranModal = false"></button>
                    </div>
                    <form @submit.prevent="submitTahunAjaran">
                        <div class="modal-body p-4 bg-white">
                            <div class="form-group">
                                <label class="form-label fw-bold small text-uppercase ls-1 text-secondary">Tahun Ajaran <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light border-end-0"><i class="fas fa-calendar-check text-muted"></i></span>
                                    <input type="text" class="form-control border-start-0 ps-0 bg-light" v-model="tahunAjaranForm.tahun" placeholder="Contoh: 2025/2026" required>
                                </div>
                                <small class="text-muted d-block mt-1">Format: YYYY/YYYY (contoh: 2024/2025)</small>
                                <div v-if="tahunAjaranForm.errors.tahun" class="text-danger small mt-1 fw-bold">{{ tahunAjaranForm.errors.tahun }}</div>
                            </div>
                        </div>
                        <div class="modal-footer bg-light border-0 p-3">
                            <button type="button" class="btn btn-light text-secondary fw-medium" @click="showTahunAjaranModal = false">Batal</button>
                            <button type="submit" class="btn btn-primary px-4 shadow-sm btn-gradient" :disabled="tahunAjaranForm.processing">
                                {{ tahunAjaranForm.processing ? 'Menyimpan...' : 'Simpan' }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        
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

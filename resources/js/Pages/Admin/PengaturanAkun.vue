<script setup>
import { useForm, usePage } from '@inertiajs/vue3';
import { ref, computed, watch } from 'vue';
import AdminLayout from '@/Layouts/AdminLayout.vue';
import { showToast } from '@/Utils/swal';

const page = usePage();
const props = defineProps({
    user: Object
});

const activeTab = ref('profile'); // 'profile', 'password'

const currentUser = computed(() => {
    return props.user || page.props.auth?.user || {};
});

const profileForm = useForm({
    name: currentUser.value.name || '',
    email: currentUser.value.email || '',
});

const passwordForm = useForm({
    current_password: '',
    password: '',
    password_confirmation: '',
});

const submitProfile = () => {
    profileForm.patch('/admin/profile', {
        preserveScroll: true,
        onSuccess: () => {
            // Toast handled by watcher
        },
        onError: () => {
            showToast('error', 'Gagal', 'Terjadi kesalahan saat menyimpan profil.');
        }
    });
};

const submitPassword = () => {
    passwordForm.put('/admin/password', {
        preserveScroll: true,
        onSuccess: () => {
            passwordForm.reset();
        },
        onError: () => {
            showToast('error', 'Gagal', 'Terjadi kesalahan saat mengubah password.');
        }
    });
};

// Watch for flash messages
watch(() => page.props.flash, (flash) => {
    if (flash?.success) showToast('success', 'Berhasil', flash.success);
    if (flash?.error) showToast('error', 'Gagal', flash.error);
}, { deep: true });

</script>

<template>
    <AdminLayout>
        <Head title="Pengaturan Akun" />
        
        <div class="content container-fluid pb-5">
            <!-- Radiant Header Card -->
            <div class="card border-0 shadow-lg rounded-4 mb-4 overflow-hidden position-relative" style="background: linear-gradient(135deg, #4f46e5 0%, #3b82f6 100%);">
                <div class="card-body p-4 p-lg-5 position-relative z-1">
                    <div class="row align-items-center">
                        <div class="col-lg-8">
                            <div class="d-flex align-items-center gap-3 mb-2">
                                <div class="icon-box bg-white rounded-3 text-primary p-2">
                                    <i class="fas fa-user-cog fs-3"></i>
                                </div>
                                <h2 class="fw-bold text-white mb-0 ls-tight">Pengaturan Akun</h2>
                            </div>
                            <p class="text-white-50 mb-0 fs-5">Kelola Profil Pribadi dan Keamanan Akun Anda.</p>
                        </div>
                    </div>
                </div>
                <!-- Decorative Background -->
                <div class="position-absolute bottom-0 end-0 opacity-10 me-n5 mb-n5">
                    <i class="fas fa-shield-alt" style="font-size: 10rem; color: white;"></i>
                </div>
            </div>

            <div class="row">
                <!-- Sidebar Profil Ringkas -->
                <div class="col-lg-4 mb-4">
                    <div class="card border-0 shadow-sm rounded-4 overflow-hidden h-100">
                        <div class="card-body text-center p-5">
                            <div class="mb-4 position-relative d-inline-block">
                                <div class="avatar-circle bg-light rounded-circle d-flex align-items-center justify-content-center mx-auto" style="width: 120px; height: 120px; border: 4px solid #fff; box-shadow: 0 5px 15px rgba(0,0,0,0.1);">
                                    <i class="fas fa-user text-primary" style="font-size: 3rem;"></i>
                                </div>
                                <div class="position-absolute bottom-0 end-0 bg-success rounded-circle border border-white p-2" title="Active"></div>
                            </div>
                            <h4 class="fw-bold mb-1">{{ currentUser.name }}</h4>
                            <p class="text-muted mb-3">{{ currentUser.email }}</p>
                            <span class="badge bg-primary-subtle text-primary rounded-pill px-3 py-2">
                                <i class="fas fa-user-shield me-1"></i> Administrator
                            </span>
                        </div>
                        <div class="bg-light p-3 text-center border-top">
                            <span class="text-muted small"><i class="far fa-clock me-1"></i> Terakhir login: {{ new Date().toLocaleDateString('id-ID') }}</span>
                        </div>
                    </div>
                </div>

                <!-- Form Area -->
                <div class="col-lg-8">
                    <div class="card border-0 shadow-sm rounded-4 overflow-hidden h-100">
                        <div class="card-header bg-white border-bottom p-0">
                            <ul class="nav nav-pills p-3 gap-2">
                                <li class="nav-item">
                                    <button 
                                        class="nav-link fw-bold px-4 py-2 rounded-pill transition-all"
                                        :class="activeTab === 'profile' ? 'active shadow-sm' : 'text-muted'"
                                        @click="activeTab = 'profile'"
                                    >
                                        <i class="fas fa-id-card me-2"></i> Detail Profil
                                    </button>
                                </li>
                                <li class="nav-item">
                                    <button 
                                        class="nav-link fw-bold px-4 py-2 rounded-pill transition-all"
                                        :class="activeTab === 'password' ? 'btn-danger active shadow-sm text-white' : 'text-muted'"
                                        @click="activeTab = 'password'"
                                        style="background-color: activeTab === 'password' ? '#dc3545' : ''"
                                    >
                                        <i class="fas fa-lock me-2"></i> Ganti Password
                                    </button>
                                </li>
                            </ul>
                        </div>

                        <div class="card-body p-4 p-lg-5">
                            <!-- TAB 1: PROFIL -->
                            <Transition name="fade" mode="out-in">
                                <div v-if="activeTab === 'profile'">
                                    <h5 class="fw-bold text-dark mb-4">Informasi Pribadi</h5>
                                    
                                    <form @submit.prevent="submitProfile">
                                        <div class="mb-4">
                                            <label class="form-label fw-bold small text-muted text-uppercase">Nama Lengkap</label>
                                            <div class="input-group">
                                                <span class="input-group-text bg-light border-end-0 text-muted ps-3 rounded-start-3">
                                                    <i class="fas fa-user"></i>
                                                </span>
                                                <input 
                                                    v-model="profileForm.name" 
                                                    type="text" 
                                                    class="form-control bg-light border-start-0 py-3 rounded-end-3"
                                                    :class="{'is-invalid': profileForm.errors.name}"
                                                    placeholder="Nama Lengkap Anda"
                                                >
                                                <div class="invalid-feedback ms-2" v-if="profileForm.errors.name">{{ profileForm.errors.name }}</div>
                                            </div>
                                        </div>

                                        <div class="mb-4">
                                            <label class="form-label fw-bold small text-muted text-uppercase">Alamat Email</label>
                                            <div class="input-group">
                                                <span class="input-group-text bg-light border-end-0 text-muted ps-3 rounded-start-3">
                                                    <i class="fas fa-envelope"></i>
                                                </span>
                                                <input 
                                                    v-model="profileForm.email" 
                                                    type="email" 
                                                    class="form-control bg-light border-start-0 py-3 rounded-end-3"
                                                    :class="{'is-invalid': profileForm.errors.email}"
                                                    placeholder="email@sekolah.sch.id"
                                                >
                                                <div class="invalid-feedback ms-2" v-if="profileForm.errors.email">{{ profileForm.errors.email }}</div>
                                            </div>
                                        </div>

                                        <div class="d-flex justify-content-end mt-5">
                                            <button type="submit" class="btn btn-primary px-5 py-3 rounded-pill fw-bold shadow hover-up" :disabled="profileForm.processing">
                                                <i class="fas fa-save me-2" v-if="!profileForm.processing"></i>
                                                <i class="fas fa-spinner fa-spin me-2" v-if="profileForm.processing"></i>
                                                {{ profileForm.processing ? 'Menyimpan...' : 'Simpan Perubahan' }}
                                            </button>
                                        </div>
                                    </form>
                                </div>
                            
                                <!-- TAB 2: PASSWORD -->
                                <div v-else key="password">
                                    <h5 class="fw-bold text-danger mb-4">Keamanan Akun</h5>
                                    <div class="alert alert-light border-start border-danger border-4 text-muted small mb-4">
                                        <i class="fas fa-info-circle me-2 text-danger"></i>
                                        Pastikan password baru Anda kuat (minimal 8 karakter, kombinasi huruf dan angka).
                                    </div>

                                    <form @submit.prevent="submitPassword">
                                        <div class="mb-4">
                                            <label class="form-label fw-bold small text-muted text-uppercase">Password Saat Ini</label>
                                            <div class="input-group">
                                                <span class="input-group-text bg-light border-end-0 text-muted ps-3 rounded-start-3">
                                                    <i class="fas fa-key"></i>
                                                </span>
                                                <input 
                                                    v-model="passwordForm.current_password" 
                                                    type="password" 
                                                    class="form-control bg-light border-start-0 py-3 rounded-end-3"
                                                    :class="{'is-invalid': passwordForm.errors.current_password}"
                                                    placeholder="Masukkan password lama"
                                                >
                                                <div class="invalid-feedback ms-2" v-if="passwordForm.errors.current_password">{{ passwordForm.errors.current_password }}</div>
                                            </div>
                                        </div>

                                        <hr class="my-4 border-dashed opacity-50">

                                        <div class="row">
                                            <div class="col-md-6 mb-4">
                                                <label class="form-label fw-bold small text-muted text-uppercase">Password Baru</label>
                                                <div class="input-group">
                                                    <span class="input-group-text bg-light border-end-0 text-muted ps-3 rounded-start-3">
                                                        <i class="fas fa-lock"></i>
                                                    </span>
                                                    <input 
                                                        v-model="passwordForm.password" 
                                                        type="password" 
                                                        class="form-control bg-light border-start-0 py-3 rounded-end-3"
                                                        :class="{'is-invalid': passwordForm.errors.password}"
                                                        placeholder="Password baru"
                                                    >
                                                    <div class="invalid-feedback ms-2" v-if="passwordForm.errors.password">{{ passwordForm.errors.password }}</div>
                                                </div>
                                            </div>
                                            <div class="col-md-6 mb-4">
                                                <label class="form-label fw-bold small text-muted text-uppercase">Konfirmasi Password</label>
                                                <div class="input-group">
                                                    <span class="input-group-text bg-light border-end-0 text-muted ps-3 rounded-start-3">
                                                        <i class="fas fa-check-circle"></i>
                                                    </span>
                                                    <input 
                                                        v-model="passwordForm.password_confirmation" 
                                                        type="password" 
                                                        class="form-control bg-light border-start-0 py-3 rounded-end-3"
                                                        :class="{'is-invalid': passwordForm.errors.password_confirmation}"
                                                        placeholder="Ulangi password baru"
                                                    >
                                                    <div class="invalid-feedback ms-2" v-if="passwordForm.errors.password_confirmation">{{ passwordForm.errors.password_confirmation }}</div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="d-flex justify-content-end mt-3">
                                            <button type="submit" class="btn btn-danger px-5 py-3 rounded-pill fw-bold shadow hover-up" :disabled="passwordForm.processing">
                                                <i class="fas fa-shield-alt me-2" v-if="!passwordForm.processing"></i>
                                                <i class="fas fa-spinner fa-spin me-2" v-if="passwordForm.processing"></i>
                                                {{ passwordForm.processing ? 'Memproses...' : 'Update Password' }}
                                            </button>
                                        </div>
                                    </form>
                                </div>
                            </Transition>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AdminLayout>
</template>

<style scoped>
.transition-all { transition: all 0.3s ease; }
.hover-up:hover { transform: translateY(-3px); }

/* Custom Tab Styling */
.nav-pills .nav-link.active {
    background-color: #4f46e5; /* Primary */
}

/* Transitions */
.fade-enter-active,
.fade-leave-active {
  transition: opacity 0.3s ease;
}

.fade-enter-from,
.fade-leave-to {
  opacity: 0;
}
</style>

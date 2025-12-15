<script setup>
import { useForm, usePage } from '@inertiajs/vue3';
import { ref, computed } from 'vue';
import AdminLayout from '@/Layouts/AdminLayout.vue';
import ToastNotification from '@/Components/ToastNotification.vue';

const page = usePage();
const props = defineProps({
    user: Object
});

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
        }
    });
};

const submitPassword = () => {
    passwordForm.put('/admin/password', {
        preserveScroll: true,
        onSuccess: () => {
            passwordForm.reset();
        }
    });
};

const toast = ref(null);

// Watch for flash messages
import { watch } from 'vue';
watch(() => page.props.flash, (flash) => {
    if (flash?.success) {
        toast.value?.show('success', flash.success);
    }
    if (flash?.error) {
        toast.value?.show('error', flash.error);
    }
}, { deep: true });

</script>

<template>
    <AdminLayout>
        <div class="content container-fluid">
            <!-- Page Header -->
            <div class="page-header">
                <div class="row">
                    <div class="col">
                        <h3 class="page-title">Profil Saya</h3>
                        <ul class="breadcrumb">
                            <li class="breadcrumb-item"><a href="#">Dashboard</a></li>
                            <li class="breadcrumb-item active">Profil</li>
                        </ul>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-12">
                    <div class="profile-header">
                        <div class="row align-items-center">
                            <div class="col-auto profile-image">
                                <a href="#">
                                    <img class="rounded-circle" alt="User Image" src="/assets/img/profiles/avatar-01.jpg">
                                </a>
                            </div>
                            <div class="col ms-md-n2 profile-user-info">
                                <h4 class="user-name mb-0">{{ currentUser.name }}</h4>
                                <h6 class="text-muted">{{ currentUser.email }}</h6>
                                <div class="user-Location"><i class="fas fa-user-shield"></i> Administrator</div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="profile-menu">
                        <ul class="nav nav-tabs nav-tabs-solid">
                            <li class="nav-item">
                                <a class="nav-link active" data-bs-toggle="tab" href="#per_details_tab">Tentang Saya</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" data-bs-toggle="tab" href="#password_tab">Ganti Password</a>
                            </li>
                        </ul>
                    </div>
                    
                    <div class="tab-content profile-tab-cont">
                        <!-- Personal Details Tab -->
                        <div class="tab-pane fade show active" id="per_details_tab">
                            <div class="row">
                                <div class="col-lg-12">
                                    <div class="card">
                                        <div class="card-body">
                                            <h5 class="card-title d-flex justify-content-between">
                                                <span>Detail Pribadi</span>
                                            </h5>
                                            <form @submit.prevent="submitProfile">
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label>Nama Lengkap</label>
                                                            <input v-model="profileForm.name" type="text" class="form-control">
                                                            <div v-if="profileForm.errors.name" class="text-danger small">{{ profileForm.errors.name }}</div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label>Email</label>
                                                            <input v-model="profileForm.email" type="email" class="form-control">
                                                            <div v-if="profileForm.errors.email" class="text-danger small">{{ profileForm.errors.email }}</div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <button class="btn btn-primary" type="submit" :disabled="profileForm.processing">Simpan Perubahan</button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Password Tab -->
                        <div id="password_tab" class="tab-pane fade">
                            <div class="card">
                                <div class="card-body">
                                    <h5 class="card-title">Ganti Password</h5>
                                    <div class="row">
                                        <div class="col-md-10 col-lg-6">
                                            <form @submit.prevent="submitPassword">
                                                <div class="form-group">
                                                    <label>Password Saat Ini</label>
                                                    <input v-model="passwordForm.current_password" type="password" class="form-control">
                                                    <div v-if="passwordForm.errors.current_password" class="text-danger small">{{ passwordForm.errors.current_password }}</div>
                                                </div>
                                                <div class="form-group">
                                                    <label>Password Baru</label>
                                                    <input v-model="passwordForm.password" type="password" class="form-control">
                                                    <div v-if="passwordForm.errors.password" class="text-danger small">{{ passwordForm.errors.password }}</div>
                                                </div>
                                                <div class="form-group">
                                                    <label>Konfirmasi Password</label>
                                                    <input v-model="passwordForm.password_confirmation" type="password" class="form-control">
                                                    <div v-if="passwordForm.errors.password_confirmation" class="text-danger small">{{ passwordForm.errors.password_confirmation }}</div>
                                                </div>
                                                <button class="btn btn-primary" type="submit" :disabled="passwordForm.processing">Simpan Password</button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                    </div>
                </div>
            </div>
        </div>
        <ToastNotification ref="toast" />
    </AdminLayout>
</template>

<style scoped>
.profile-header {
    background-color: #fff;
    border: 1px solid #e3e3e3;
    padding: 1.5rem;
    margin-bottom: 20px;
    border-radius: 10px;
}
.profile-image img {
    width: 100px;
    height: 100px;
    border-radius: 50%;
}
.profile-user-info h4 {
    color: #333;
    font-weight: 600;
}
.profile-menu {
    background-color: #fff;
    padding: 10px;
    border-radius: 10px;
    border: 1px solid #e3e3e3;
    margin-bottom: 20px;
}
.nav-tabs-solid {
    border-bottom: none;
}
.nav-tabs-solid .nav-link {
    border: none;
    color: #333;
    font-weight: 500;
    border-radius: 5px;
    padding: 10px 20px;
}
.nav-tabs-solid .nav-link.active {
    background-color: #3d5ee1;
    color: #fff;
}
.nav-tabs-solid .nav-link:hover {
    background-color: #f7f7f7;
}
.nav-tabs-solid .nav-link.active:hover {
    background-color: #3d5ee1;
    color: #fff;
}
</style>

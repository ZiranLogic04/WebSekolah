<script setup>
import { ref, computed, watch, onMounted } from 'vue';
import { Head, useForm, router, usePage } from '@inertiajs/vue3';
import AdminLayout from '@/Layouts/AdminLayout.vue';
import ToastNotification from '@/Components/ToastNotification.vue';
import VueEasyDataTable from 'vue3-easy-data-table';
import 'vue3-easy-data-table/dist/style.css';

const props = defineProps({
    users: Array,
});

const search = ref('');
const headers = [
    { text: "No", value: "no", width: 50 },
    { text: "Pengguna", value: "user_info", sortable: true },
    { text: "Email", value: "email", sortable: true },
    { text: "Role", value: "role_display", sortable: true },
    { text: "Status", value: "status", sortable: true },
    { text: "Aksi", value: "actions", width: 100 },
];

const items = computed(() => {
    return props.users.map((user, index) => ({
        ...user,
        no: index + 1,
        initials: getInitials(user.name),
        status: 'Aktif',
        role_display: capitalize(user.role || 'user')
    }));
});

function capitalize(s) {
    return s && s[0].toUpperCase() + s.slice(1);
}

function getInitials(name) {
    if (!name) return '';
    return name.split(' ').map(n => n[0]).join('').substring(0, 2).toUpperCase();
}

const form = useForm({
    id: null,
    name: '',
    email: '',
    role: 'siswa',
    password: '',
    password_confirmation: '',
});

const isEditing = ref(false);

// Custom Modal State
const showModal = ref(false);
const showDeleteVal = ref(false);
const deleteId = ref(null);

const openModal = (user = null) => {
    form.reset();
    form.clearErrors();
    if (user) {
        isEditing.value = true;
        form.id = user.id;
        form.name = user.name;
        form.email = user.email;
        form.role = user.role || 'siswa';
    } else {
        isEditing.value = false;
        form.role = 'siswa';
    }
    showModal.value = true;
};

const closeModal = () => {
    showModal.value = false;
    form.reset();
};

const confirmDelete = (id) => {
    deleteId.value = id;
    showDeleteVal.value = true;
};

const closeDelete = () => {
    showDeleteVal.value = false;
    deleteId.value = null;
};

const submit = () => {
    if (isEditing.value) {
        form.put(`/admin/akun/${form.id}`, {
            onSuccess: () => closeModal(),
        });
    } else {
        form.post('/admin/akun', {
            onSuccess: () => closeModal(),
        });
    }
};

const deleteUser = () => {
    if (deleteId.value) {
        router.delete(`/admin/akun/${deleteId.value}`, {
            onSuccess: () => closeDelete(),
        });
    }
};

const toast = ref(null);
const page = usePage();
watch(() => page.props.flash, (flash) => {
    if (flash?.success) toast.value?.showSuccessToast('Sukses', flash.success);
    if (flash?.error) toast.value?.showErrorToast('Error', flash.error);
}, { deep: true });

</script>

<template>
    <AdminLayout>
        <Head title="Kelola Akun" />
        
        <div class="content container-fluid">
            <!-- Page Header -->
            <div class="page-header">
                <div class="row align-items-center">
                    <div class="col">
                        <h3 class="page-title">Data Pengguna</h3>
                        <ul class="breadcrumb">
                            <li class="breadcrumb-item"><a href="/dashboard">Dashboard</a></li>
                            <li class="breadcrumb-item active">Akun</li>
                        </ul>
                    </div>
                    <div class="col-auto text-end float-end ms-auto">
                        <button class="btn btn-primary" @click="openModal()">
                            <i class="fas fa-plus"></i> Tambah Akun
                        </button>
                    </div>
                </div>
            </div>

            <!-- Modern Table Card -->
            <div class="row">
                <div class="col-sm-12">
                    <div class="card modern-table-card">
                        <div class="card-header">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h5 class="card-title mb-0">Daftar Akun</h5>
                                    <p class="card-subtitle text-muted mb-0">Manage user access and roles</p>
                                </div>
                                <div class="col-auto">
                                    <div class="input-group input-group-modern">
                                        <span class="input-group-text bg-white border-end-0">
                                            <i class="fas fa-search text-muted"></i>
                                        </span>
                                        <input type="text" class="form-control border-start-0 ps-0" placeholder="Cari..." v-model="search">
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="card-body p-0">
                            <VueEasyDataTable
                                :headers="headers"
                                :items="items"
                                :search-value="search"
                                table-class-name="modern-data-table"
                                header-text-direction="left"
                                body-text-direction="left"
                                border-cell
                                alternating
                                :rows-per-page="10"
                            >
                                <template #item-user_info="item">
                                    <div class="d-flex align-items-center py-2">
                                        <div class="avatar-circle me-3">{{ item.initials }}</div>
                                        <div>
                                            <h6 class="mb-0 fw-bold text-dark">{{ item.name }}</h6>
                                            <small class="text-muted">Part of Admin System</small>
                                        </div>
                                    </div>
                                </template>

                                <template #item-email="item">
                                    <span class="text-muted">{{ item.email }}</span>
                                </template>

                                <template #item-role_display="item">
                                    <span :class="{
                                        'status-badge bg-primary': item.role === 'admin',
                                        'status-badge bg-info': item.role === 'guru',
                                        'status-badge bg-warning': item.role === 'siswa'
                                    }">{{ item.role_display }}</span>
                                </template>

                                <template #item-status="item">
                                    <span class="status-badge bg-success">Active</span>
                                </template>

                                <template #item-actions="item">
                                    <div class="action-buttons">
                                        <button class="btn btn-action btn-edit" @click="openModal(item)" title="Edit">
                                            <i class="fas fa-pen"></i>
                                        </button>
                                        <button class="btn btn-action btn-delete" @click="confirmDelete(item.id)" title="Hapus">
                                            <i class="fas fa-trash-alt"></i>
                                        </button>
                                    </div>
                                </template>
                            </VueEasyDataTable>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Add/Edit Modal (Standard Bootstrap) -->
        <div class="modal fade" :class="{ 'show': showModal }" style="display: block;" v-if="showModal" role="dialog" aria-modal="true">
            <div class="modal-dialog modal-dialog-centered modern-modal">
                <div class="modal-content border-0">
                    <div class="modal-header">
                        <div class="modal-title">
                            <div class="modal-icon">
                                <i class="fas" :class="isEditing ? 'fa-user-edit' : 'fa-user-plus'"></i>
                            </div>
                            <div>
                                <h5>{{ isEditing ? 'Edit Akun' : 'Tambah Akun' }}</h5>
                                <p class="modal-subtitle text-muted">Isi form berikut untuk {{ isEditing ? 'mengubah' : 'menambah' }} data user.</p>
                            </div>
                        </div>
                        <button type="button" class="btn-close" @click="closeModal"></button>
                    </div>
                    <div class="modal-body">
                        <form @submit.prevent="submit">
                            <div class="mb-3">
                                <label class="form-label required">Nama Lengkap</label>
                                <input type="text" class="form-control" v-model="form.name" placeholder="Nama Lengkap">
                                <div v-if="form.errors.name" class="text-danger small mt-1">{{ form.errors.name }}</div>
                            </div>
                            <div class="mb-3">
                                <label class="form-label required">Email</label>
                                <input type="email" class="form-control" v-model="form.email" placeholder="example@email.com">
                                <div v-if="form.errors.email" class="text-danger small mt-1">{{ form.errors.email }}</div>
                            </div>
                            <div class="mb-3">
                                <label class="form-label required">Role</label>
                                <select class="form-select" v-model="form.role">
                                    <option value="admin">Admin</option>
                                    <option value="guru">Guru</option>
                                    <option value="siswa">Siswa</option>
                                </select>
                                <div v-if="form.errors.role" class="text-danger small mt-1">{{ form.errors.role }}</div>
                            </div>
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label" :class="{ 'required': !isEditing }">Password</label>
                                    <input type="password" class="form-control" v-model="form.password" placeholder="******">
                                    <div v-if="form.errors.password" class="text-danger small mt-1">{{ form.errors.password }}</div>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Konfirmasi Password</label>
                                    <input type="password" class="form-control" v-model="form.password_confirmation" placeholder="******">
                                </div>
                            </div>
                            <div class="d-grid pt-3">
                                <button type="submit" class="btn btn-primary" :disabled="form.processing">
                                    <i class="fas fa-save me-2"></i> {{ form.processing ? 'Menyimpan...' : 'Simpan Data' }}
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <div v-if="showModal" class="modal-backdrop fade show"></div>

        <!-- Delete Modal -->
        <div class="modal fade" :class="{ 'show': showDeleteVal }" style="display: block;" v-if="showDeleteVal" role="dialog" aria-modal="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content border-0 shadow">
                    <div class="modal-body text-center p-4">
                        <div class="text-danger mb-3">
                            <i class="fas fa-trash-alt fa-3x"></i>
                        </div>
                        <h4 class="mb-2 fw-bold">Hapus Akun?</h4>
                        <p class="text-muted">Data yang dihapus tidak dapat dikembalikan.</p>
                        <div class="mt-4 d-flex justify-content-center gap-2">
                            <button class="btn btn-light px-4" @click="closeDelete">Batal</button>
                            <button class="btn btn-danger px-4" @click="deleteUser">Hapus</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div v-if="showDeleteVal" class="modal-backdrop fade show"></div>

        <ToastNotification ref="toast" />
    </AdminLayout>
</template>

<style scoped>
.avatar-circle {
    width: 35px;
    height: 35px;
    background-color: #3d5ee1;
    color: white;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: 600;
    font-size: 14px;
}
/* No modern-data-table overrides here, relying on admin-theme.css */
</style>

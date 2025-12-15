<template>
    <AdminLayout>
        <div class="content container-fluid">
            <div class="page-header">
                <div class="row align-items-center">
                    <div class="col">
                        <h3 class="page-title">Daftar Jenis Tagihan</h3>
                        <ul class="breadcrumb">
                            <li class="breadcrumb-item"><Link href="/admin/dashboard">Dashboard</Link></li>
                            <li class="breadcrumb-item"><Link href="/admin/uang-sekolah">Keuangan</Link></li>
                            <li class="breadcrumb-item active">Jenis Tagihan</li>
                        </ul>
                    </div>
                    <div class="col-auto">
                        <button class="btn btn-primary" @click="openModal()"><i class="fas fa-plus"></i> Tambah Jenis Tagihan</button>
                    </div>
                </div>
            </div>

            <!-- Tab Filter -->
            <ul class="nav nav-tabs nav-tabs-bottom mb-4">
                <li class="nav-item">
                    <a class="nav-link" :class="{ active: activeFilter === '' }" href="#" @click.prevent="setFilter('')">
                        <i class="fas fa-list me-1"></i> Semua
                        <span class="badge bg-secondary ms-1">{{ countAll }}</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" :class="{ active: activeFilter === 'Pemasukan' }" href="#" @click.prevent="setFilter('Pemasukan')">
                        <i class="fas fa-arrow-down text-success me-1"></i> Pemasukan
                        <span class="badge bg-success ms-1">{{ countPemasukan }}</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" :class="{ active: activeFilter === 'Pengeluaran' }" href="#" @click.prevent="setFilter('Pengeluaran')">
                        <i class="fas fa-arrow-up text-danger me-1"></i> Pengeluaran
                        <span class="badge bg-danger ms-1">{{ countPengeluaran }}</span>
                    </a>
                </li>
            </ul>

            <div class="row">
                <div class="col-md-12">
                    <div class="card card-table">
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-hover table-center mb-0 datatable">
                                    <thead>
                                        <tr>
                                            <th>Nama Jenis Tagihan</th>
                                            <th>Tipe</th>
                                            <th>Kode</th>
                                            <th>Keterangan</th>
                                            <th class="text-end">Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr v-for="item in filteredData" :key="item.id">
                                            <td class="fw-bold">{{ item.nama }}</td>
                                            <td>
                                                <span class="badge" :class="item.tipe === 'Pemasukan' ? 'bg-success' : 'bg-danger'">
                                                    <i :class="item.tipe === 'Pemasukan' ? 'fas fa-arrow-down' : 'fas fa-arrow-up'" class="me-1"></i>
                                                    {{ item.tipe }}
                                                </span>
                                            </td>
                                            <td>{{ item.kode || '-' }}</td>
                                            <td>{{ item.keterangan || '-' }}</td>
                                            <td class="text-end">
                                                <div class="actions">
                                                    <button class="btn btn-sm bg-success-light me-2" @click="openModal(item)">
                                                        <i class="feather-edit"></i>
                                                    </button>
                                                    <button class="btn btn-sm bg-danger-light" @click="confirmDelete(item.id)">
                                                        <i class="feather-trash-2"></i>
                                                    </button>
                                                </div>
                                            </td>
                                        </tr>
                                        <tr v-if="filteredData.length === 0">
                                            <td colspan="5" class="text-center text-muted py-4">
                                                <i class="fas fa-inbox fa-2x mb-2 d-block opacity-50"></i>
                                                Belum ada data jenis tagihan {{ activeFilter ? activeFilter.toLowerCase() : '' }}.
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Modal -->
            <div class="modal fade" id="modalJenis" tabindex="-1" role="dialog" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">{{ isEdit ? 'Edit Jenis Tagihan' : 'Tambah Jenis Tagihan Baru' }}</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <form @submit.prevent="submit">
                                <div class="form-group mb-3">
                                    <label class="form-label">Tipe Transaksi <span class="text-danger">*</span></label>
                                    <div class="d-flex gap-3">
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" id="tipePemasukan" value="Pemasukan" v-model="form.tipe">
                                            <label class="form-check-label" for="tipePemasukan">
                                                <i class="fas fa-arrow-down text-success me-1"></i> Pemasukan
                                            </label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" id="tipePengeluaran" value="Pengeluaran" v-model="form.tipe">
                                            <label class="form-check-label" for="tipePengeluaran">
                                                <i class="fas fa-arrow-up text-danger me-1"></i> Pengeluaran
                                            </label>
                                        </div>
                                    </div>
                                    <div class="invalid-feedback d-block" v-if="form.errors.tipe">{{ form.errors.tipe }}</div>
                                </div>
                                <div class="form-group mb-3">
                                    <label class="form-label">Nama Jenis Tagihan <span class="text-danger">*</span></label>
                                    <input class="form-control" type="text" v-model="form.nama" placeholder="Contoh: SPP, ATK, Listrik">
                                    <div class="invalid-feedback d-block" v-if="form.errors.nama">{{ form.errors.nama }}</div>
                                </div>
                                <div class="form-group mb-3">
                                    <label class="form-label">Kode (Opsional)</label>
                                    <input class="form-control" type="text" v-model="form.kode" placeholder="Contoh: SPP-2024">
                                </div>
                                <div class="form-group mb-3">
                                    <label class="form-label">Keterangan</label>
                                    <textarea class="form-control" v-model="form.keterangan" rows="2" placeholder="Deskripsi jenis tagihan (opsional)"></textarea>
                                </div>
                                <div class="mt-4 d-flex gap-2">
                                    <button type="button" class="btn btn-secondary flex-fill" data-bs-dismiss="modal">Batal</button>
                                    <button type="submit" class="btn btn-primary flex-fill" :disabled="form.processing">
                                        <span v-if="form.processing"><i class="fas fa-spinner fa-spin me-1"></i> Menyimpan...</span>
                                        <span v-else>Simpan</span>
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </AdminLayout>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue';
import { Link, useForm, router } from '@inertiajs/vue3';
import AdminLayout from '@/Layouts/AdminLayout.vue';
import Swal from 'sweetalert2';

const props = defineProps({
    jenisTagihans: Array,
    filters: Object
});

const isEdit = ref(false);
const modalInstance = ref(null);
const activeFilter = ref(props.filters?.tipe || '');

const form = useForm({
    id: null,
    nama: '',
    tipe: 'Pemasukan',
    kode: '',
    keterangan: ''
});

// Computed counts
const countAll = computed(() => props.jenisTagihans.length);
const countPemasukan = computed(() => props.jenisTagihans.filter(i => i.tipe === 'Pemasukan').length);
const countPengeluaran = computed(() => props.jenisTagihans.filter(i => i.tipe === 'Pengeluaran').length);

// Filtered data based on active tab (client-side for speed)
const filteredData = computed(() => {
    if (!activeFilter.value) return props.jenisTagihans;
    return props.jenisTagihans.filter(i => i.tipe === activeFilter.value);
});

onMounted(() => {
    // Initialize bootstrap modal
    const modalEl = document.getElementById('modalJenis');
    if (window.bootstrap) {
        modalInstance.value = new window.bootstrap.Modal(modalEl);
    }
});

const setFilter = (tipe) => {
    activeFilter.value = tipe;
    // Optional: could also do server-side filter
    // router.get('/admin/jenis-tagihan', { tipe }, { preserveState: true, replace: true });
};

const openModal = (item = null) => {
    form.clearErrors();
    form.reset();
    form.tipe = 'Pemasukan'; // Default

    if (item) {
        isEdit.value = true;
        form.id = item.id;
        form.nama = item.nama;
        form.tipe = item.tipe;
        form.kode = item.kode;
        form.keterangan = item.keterangan;
    } else {
        isEdit.value = false;
    }
    
    modalInstance.value?.show();
};

const hideModal = () => {
    modalInstance.value?.hide();
};

const submit = () => {
    if (isEdit.value) {
        form.put(`/admin/jenis-tagihan/${form.id}`, {
            onSuccess: () => {
                hideModal();
                Swal.fire('Berhasil!', 'Jenis tagihan berhasil diperbarui.', 'success');
            }
        });
    } else {
        form.post('/admin/jenis-tagihan', {
            onSuccess: () => {
                hideModal();
                Swal.fire('Berhasil!', 'Jenis tagihan baru berhasil ditambahkan.', 'success');
            }
        });
    }
};

const confirmDelete = (id) => {
    Swal.fire({
        title: 'Apakah anda yakin?',
        text: "Data yang dihapus tidak dapat dikembalikan!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Ya, Hapus!'
    }).then((result) => {
        if (result.isConfirmed) {
            router.delete(`/admin/jenis-tagihan/${id}`, {
                onSuccess: () => Swal.fire('Terhapus!', 'Jenis tagihan telah dihapus.', 'success'),
                onError: (errors) => Swal.fire('Gagal!', errors.message || 'Terjadi kesalahan.', 'error')
            });
        }
    });
};
</script>

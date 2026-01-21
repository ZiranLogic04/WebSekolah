<template>
    <AdminLayout>
        <div class="content container-fluid">
            <!-- Radiant Header Card (sama dengan DataSiswa) -->
            <div class="card border-0 shadow-lg rounded-4 mb-4 overflow-hidden position-relative" style="background: linear-gradient(135deg, #4f46e5 0%, #3b82f6 100%);">
                <div class="card-body p-4 p-lg-5 position-relative z-1">
                    <div class="row align-items-center">
                        <div class="col-lg-7">
                            <div class="d-flex align-items-center gap-3 mb-2">
                                <div class="icon-box bg-white rounded-3 text-primary p-2">
                                    <i class="fas fa-file-invoice-dollar fs-3"></i>
                                </div>
                                <h2 class="fw-bold text-white mb-0 ls-tight">Daftar Tagihan</h2>
                            </div>
                            <p class="text-white-50 mb-0 fs-5">Kelola tagihan siswa per batch dengan filter dan pencarian.</p>
                        </div>
                        <div class="col-lg-5 text-lg-end mt-3 mt-lg-0">
                            <Link href="/admin/jenis-tagihan" class="btn btn-light rounded-pill px-4 me-2 fw-medium">
                                <i class="fas fa-cog me-2 text-secondary"></i>Kelola Jenis
                            </Link>
                            <Link href="/admin/tagihan/create" class="btn btn-light btn-lg rounded-pill shadow-sm px-4 fw-bold text-primary hover-elevate">
                                <i class="fas fa-plus-circle me-2"></i>Buat Tagihan
                            </Link>
                        </div>
                    </div>
                </div>
                <!-- Decorative Background -->
                <div class="position-absolute bottom-0 end-0 opacity-10 me-n5 mb-n5">
                    <i class="fas fa-file-invoice-dollar" style="font-size: 10rem; color: white;"></i>
                </div>
            </div>

            <div class="row">
                <div class="col-12">
                    <div class="card border-0 shadow-sm rounded-4">
                        <div class="card-body p-4">
                            
                            <!-- Modern Filter & Search -->
                            <div class="row g-3 mb-4">
                                <div class="col-lg-3 col-md-6">
                                    <label class="form-label small text-muted">Jenis Tagihan</label>
                                    <select class="form-select rounded-pill" v-model="filters.jenis_tagihan_id" @change="handleSearch">
                                        <option value="">Semua Jenis Tagihan</option>
                                        <option v-for="j in jenisTagihans" :key="j.id" :value="j.id">{{ j.nama }}</option>
                                    </select>
                                </div>
                                <div class="col-lg-3 col-md-6">
                                    <label class="form-label small text-muted">Kelas</label>
                                    <select class="form-select rounded-pill" v-model="filters.kelas" @change="handleSearch">
                                        <option value="">Semua Kelas</option>
                                        <option v-for="k in kelasList" :key="k" :value="k">{{ k }}</option>
                                    </select>
                                </div>
                                <div class="col-lg-6">
                                    <label class="form-label small text-muted">Pencarian</label>
                                    <div class="input-group">
                                        <input type="text" class="form-control rounded-pill rounded-end" placeholder="Cari nama siswa, jenis, dll..." v-model="filters.search" @keyup.enter="handleSearch">
                                        <button class="btn btn-primary rounded-pill rounded-start px-4" type="button" @click="handleSearch">
                                            <i class="fas fa-search me-1"></i> Cari
                                        </button>
                                    </div>
                                </div>
                            </div>

                            <div class="table-responsive">
                                <table class="table table-hover table-center mb-0">
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>Tanggal Dibuat</th>
                                            <th>Jenis Tagihan</th>
                                            <th>Target</th>
                                            <th>Nominal</th>
                                            <th>Siswa</th>
                                            <th>Tenggat</th>
                                            <th class="text-end">Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr v-for="(batch, index) in batches.data" :key="batch.id">
                                            <td>{{ (batches.current_page - 1) * batches.per_page + index + 1 }}</td>
                                            <td>
                                                <strong>{{ formatDateFull(batch.created_at) }}</strong>
                                            </td>
                                            <td>
                                                <span class="badge bg-primary">{{ batch.jenis_tagihan?.nama || '-' }}</span>
                                            </td>
                                            <td>
                                                <small>
                                                    Kelas: {{ batch.target_kelas || 'Semua' }}<br>
                                                    Gender: {{ batch.target_gender || 'Semua' }}
                                                </small>
                                            </td>
                                            <td><strong>{{ formatRupiah(batch.jumlah) }}</strong></td>
                                            <td>
                                                <span class="badge bg-info cursor-pointer" @click="showDetail(batch)">
                                                    <i class="fas fa-users me-1"></i>{{ batch.total_siswa }} Siswa
                                                </span>
                                            </td>
                                            <td>{{ formatDate(batch.tenggat_waktu) }}</td>
                                            <td class="text-end">
                                                <div class="btn-group">
                                                    <button class="btn btn-sm btn-outline-info" @click="showDetail(batch)" title="Lihat Detail">
                                                        <i class="fas fa-eye"></i>
                                                    </button>
                                                    <button class="btn btn-sm btn-outline-danger" @click="confirmDelete(batch.id)" title="Hapus">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </div>
                                            </td>
                                        </tr>
                                        <tr v-if="batches.data.length === 0">
                                            <td colspan="8" class="text-center py-4">
                                                <i class="fas fa-inbox fa-2x text-muted mb-2 d-block"></i>
                                                Tidak ada data tagihan ditemukan.
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>

                            <!-- Pagination -->
                            <div class="row mt-4" v-if="batches.links.length > 3">
                                <div class="col-md-12">
                                    <div class="pagination-tab d-flex justify-content-end">
                                        <ul class="pagination mb-0">
                                            <li class="page-item" :class="{ disabled: !link.url, active: link.active }" v-for="(link, index) in batches.links" :key="index">
                                                <Link class="page-link" :href="link.url || '#'" v-html="link.label"></Link>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal Detail Siswa -->
        <div class="modal fade" id="detailModal" tabindex="-1" ref="detailModalRef">
            <div class="modal-dialog modal-lg modal-dialog-centered">
                <div class="modal-content border-0 shadow-lg rounded-4">
                    <div class="modal-header bg-primary text-white rounded-top-4">
                        <div class="modal-title d-flex align-items-center">
                            <div class="icon-box bg-white rounded-3 text-primary p-2 me-3">
                                <i class="fas fa-users fs-5"></i>
                            </div>
                            <div>
                                <h5 class="mb-0">Daftar Siswa</h5>
                                <small class="opacity-75">{{ selectedBatch?.jenis_tagihan?.nama }}</small>
                            </div>
                        </div>
                        <button type="button" class="btn-close btn-close-white" @click="closeModal"></button>
                    </div>
                    <div class="modal-body p-4">
                        <div class="row g-3 mb-4">
                            <div class="col-md-4">
                                <div class="bg-light rounded-3 p-3 text-center">
                                    <i class="fas fa-calendar text-primary mb-2"></i>
                                    <small class="d-block text-muted">Tanggal Dibuat</small>
                                    <strong>{{ formatDateFull(selectedBatch?.created_at) }}</strong>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="bg-light rounded-3 p-3 text-center">
                                    <i class="fas fa-money-bill-wave text-success mb-2"></i>
                                    <small class="d-block text-muted">Nominal</small>
                                    <strong class="text-success">{{ formatRupiah(selectedBatch?.jumlah) }}</strong>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="bg-light rounded-3 p-3 text-center">
                                    <i class="fas fa-clock text-warning mb-2"></i>
                                    <small class="d-block text-muted">Tenggat Waktu</small>
                                    <strong>{{ formatDate(selectedBatch?.tenggat_waktu) }}</strong>
                                </div>
                            </div>
                        </div>

                        <div v-if="loadingDetail" class="text-center py-4">
                            <i class="fas fa-spinner fa-spin fa-2x"></i>
                            <p class="mt-2">Memuat data...</p>
                        </div>

                        <div v-else class="table-scroll-container">
                            <table class="table table-sm table-hover mb-0">
                                <thead class="table-light sticky-header">
                                    <tr>
                                        <th>No</th>
                                        <th>Nama Siswa</th>
                                        <th>Kelas</th>
                                        <th>Status</th>
                                        <th class="text-end">Terbayar</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="(tagihan, idx) in detailTagihans" :key="tagihan.id">
                                        <td>{{ (detailPagination.current_page - 1) * detailPagination.per_page + idx + 1 }}</td>
                                        <td>{{ tagihan.nama_siswa || tagihan.siswa?.nama || '-' }}</td>
                                        <td>{{ tagihan.kelas_siswa || tagihan.siswa?.kelas || '-' }}</td>
                                        <td>
                                            <span class="badge" :class="tagihan.status === 'Lunas' ? 'bg-success' : 'bg-warning'">
                                                {{ tagihan.status }}
                                            </span>
                                        </td>
                                        <td class="text-end">{{ formatRupiah(tagihan.terbayar) }}</td>
                                    </tr>
                                    <tr v-if="detailTagihans.length === 0">
                                        <td colspan="5" class="text-center">Tidak ada data</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        
                        <!-- Pagination Info & Controls -->
                        <div v-if="detailPagination.total > 0" class="d-flex justify-content-between align-items-center mt-3">
                            <small class="text-muted">
                                Menampilkan {{ (detailPagination.current_page - 1) * detailPagination.per_page + 1 }} - 
                                {{ Math.min(detailPagination.current_page * detailPagination.per_page, detailPagination.total) }} 
                                dari {{ detailPagination.total }} siswa
                            </small>
                            <nav v-if="detailPagination.last_page > 1">
                                <ul class="pagination pagination-sm mb-0">
                                    <li class="page-item" :class="{ disabled: detailPagination.current_page === 1 }">
                                        <button class="page-link" @click="loadDetailPage(detailPagination.current_page - 1)">&laquo;</button>
                                    </li>
                                    <li class="page-item active">
                                        <span class="page-link">{{ detailPagination.current_page }} / {{ detailPagination.last_page }}</span>
                                    </li>
                                    <li class="page-item" :class="{ disabled: detailPagination.current_page === detailPagination.last_page }">
                                        <button class="page-link" @click="loadDetailPage(detailPagination.current_page + 1)">&raquo;</button>
                                    </li>
                                </ul>
                            </nav>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" @click="closeModal">Tutup</button>
                    </div>
                </div>
            </div>
        </div>

    </AdminLayout>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import { Link, router } from '@inertiajs/vue3';
import AdminLayout from '@/Layouts/AdminLayout.vue';
import Swal from 'sweetalert2';

const props = defineProps({
    batches: Object,
    filters: Object,
    kelasList: Array,
    jenisTagihans: Array
});

const filters = ref({
    search: props.filters?.search || '',
    kelas: props.filters?.kelas || '',
    jenis_tagihan_id: props.filters?.jenis_tagihan_id || ''
});

const selectedBatch = ref(null);
const detailTagihans = ref([]);
const loadingDetail = ref(false);
const detailModalRef = ref(null);
let modalInstance = null;

onMounted(() => {
    // Initialize Bootstrap modal
    if (typeof window !== 'undefined' && window.bootstrap) {
        modalInstance = new window.bootstrap.Modal(document.getElementById('detailModal'));
    }
});

const handleSearch = () => {
    router.get('/admin/tagihan', filters.value, { preserveState: true, replace: true });
};

const formatRupiah = (value) => {
    if (!value) return 'Rp 0';
    return new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR', minimumFractionDigits: 0 }).format(value);
};

const formatDate = (dateString) => {
    if (!dateString) return '-';
    return new Date(dateString).toLocaleDateString('id-ID');
};

const formatDateFull = (dateString) => {
    if (!dateString) return '-';
    return new Date(dateString).toLocaleDateString('id-ID', {
        day: 'numeric',
        month: 'short',
        year: 'numeric'
    });
};


// Pagination state
const detailPagination = ref({
    current_page: 1,
    last_page: 1,
    per_page: 20,
    total: 0
});

const loadDetailPage = async (page) => {
    if (!selectedBatch.value || page < 1 || (detailPagination.value.last_page > 0 && page > detailPagination.value.last_page)) return;
    
    loadingDetail.value = true;
    try {
        const response = await fetch(`/admin/tagihan/${selectedBatch.value.id}/detail?page=${page}`);
        const data = await response.json();
        detailTagihans.value = data.tagihans;
        detailPagination.value = data.pagination;
    } catch (error) {
        console.error("Error fetching detail:", error);
        detailTagihans.value = [];
    } finally {
        loadingDetail.value = false;
    }
};

const showDetail = async (batch) => {
    selectedBatch.value = batch;
    loadingDetail.value = true;
    detailTagihans.value = [];
    
    if (modalInstance) {
        modalInstance.show();
    }
    
    // Reset pagination to page 1
    detailPagination.value = {
        current_page: 1,
        last_page: 1,
        per_page: 20,
        total: 0
    };

    await loadDetailPage(1);
    
    // Backup fetching logic inside showDetail is removed since loadDetailPage handles it
};

const closeModal = () => {
    if (modalInstance) {
        modalInstance.hide();
    } else {
        document.getElementById('detailModal').classList.remove('show');
        document.getElementById('detailModal').style.display = 'none';
        document.body.classList.remove('modal-open');
    }
};

const confirmDelete = (id) => {
    Swal.fire({
        title: 'Hapus Batch Tagihan?',
        text: "Semua tagihan siswa dalam batch ini akan ikut terhapus!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'Ya, Hapus!',
        cancelButtonText: 'Batal'
    }).then((result) => {
        if (result.isConfirmed) {
            router.delete(`/admin/tagihan/${id}`, {
                onSuccess: () => Swal.fire('Terhapus!', 'Batch tagihan berhasil dihapus.', 'success'),
                onError: () => Swal.fire('Gagal!', 'Terjadi kesalahan.', 'error')
            });
        }
    });
};
</script>

<style scoped>
/* Table Header Styling */
.table thead th {
    background-color: #3d5ee1;
    color: #fff;
    font-weight: 600;
    font-size: 0.8rem;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    padding: 0.75rem;
    border: none;
}
.table tbody td {
    padding: 0.75rem;
    vertical-align: middle;
    font-size: 0.9rem;
}
.table-hover tbody tr:hover {
    background-color: #f8f9fa;
}

/* Card Styling */
.card {
    border: none;
    border-radius: 10px;
    box-shadow: 0 2px 10px rgba(0,0,0,0.08);
}

/* Badge Clickable */
.cursor-pointer {
    cursor: pointer;
}
.cursor-pointer:hover {
    opacity: 0.85;
}

/* Modal */
.modal-header {
    background: #f8f9fa;
    border-bottom: 1px solid #eee;
}
.modal-content {
    border-radius: 12px;
    border: none;
}

/* Button Group */
.btn-group .btn {
    padding: 0.4rem 0.6rem;
}

/* Table Scroll Container */
.table-scroll-container {
    max-height: 350px;
    overflow-y: auto;
    border: 1px solid #eee;
    border-radius: 8px;
}
.table-scroll-container .sticky-header th {
    position: sticky;
    top: 0;
    background: #fff; /* Ensure it's opaque */
    z-index: 10; /* Higher z-index */
    border-bottom: 2px solid #dee2e6;
    color: #333;
    font-weight: 600;
    box-shadow: 0 1px 2px rgba(0,0,0,0.05); /* Subtle shadow to separate */
}

/* Action Buttons */
.btn-action {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    padding: 10px 20px;
    font-size: 0.9rem;
    font-weight: 600;
    border-radius: 8px;
    text-decoration: none;
    transition: all 0.2s ease;
    cursor: pointer;
}
.btn-action i {
    font-size: 0.85rem;
}
.btn-action-primary {
    background: linear-gradient(135deg, #3d5ee1, #5b7df5);
    color: #fff;
    border: none;
    box-shadow: 0 4px 12px rgba(61, 94, 225, 0.3);
}
.btn-action-primary:hover {
    background: linear-gradient(135deg, #2c4fd1, #4a6ce4);
    transform: translateY(-2px);
    box-shadow: 0 6px 16px rgba(61, 94, 225, 0.4);
    color: #fff;
}
.btn-action-secondary {
    background: #fff;
    color: #555;
    border: 2px solid #ddd;
}
.btn-action-secondary:hover {
    background: #f8f9fa;
    border-color: #3d5ee1;
    color: #3d5ee1;
}

/* Header Buttons Container */
.header-buttons {
    display: flex;
    flex-wrap: nowrap;
    gap: 10px;
}
.header-buttons .btn-action span {
    white-space: nowrap;
}
</style>

<script setup>
import { computed } from 'vue';
import { Head, Link, router } from '@inertiajs/vue3';
import AdminLayout from '@/Layouts/AdminLayout.vue';
import VueEasyDataTable from 'vue3-easy-data-table';
import 'vue3-easy-data-table/dist/style.css';
import Swal from 'sweetalert2';

const props = defineProps({
    surats: Array,
});

const headers = [
    { text: "No", value: "no", width: 50 },
    { text: "Jenis Surat", value: "jenis_surat" },
    { text: "Nomor Surat", value: "kode_surat" },
    { text: "Tanggal Buat", value: "created_at" },
    { text: "Status", value: "status" },
    { text: "Aksi", value: "actions", width: 250 },
];

const items = computed(() => {
    return props.surats.map((item, index) => ({
        ...item,
        no: index + 1
    }));
});

const deleteSurat = async (id) => {
    const result = await Swal.fire({
        title: 'Hapus Draft Surat?',
        text: 'Draft surat ini akan dihapus permanen.',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#6c757d',
        confirmButtonText: 'Ya, Hapus',
        cancelButtonText: 'Batal'
    });
    if (result.isConfirmed) {
        router.delete(`/admin/surat/${id}`);
    }
};

const archiveSurat = async (id) => {
    const result = await Swal.fire({
        title: 'Arsipkan Surat?',
        text: 'Surat akan dipindahkan ke Surat Keluar (Final).',
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#198754',
        cancelButtonColor: '#6c757d',
        confirmButtonText: 'Ya, Arsipkan',
        cancelButtonText: 'Batal'
    });
    if (result.isConfirmed) {
        router.patch(`/admin/surat/${id}/status`);
    }
};

const printSurat = (id) => {
    window.open(`/admin/surat/${id}/print`, '_blank');
};
</script>

<template>
    <AdminLayout>
        <Head title="Buat Surat" />
        
        <div class="content container-fluid">
            <div class="page-header mb-4">
                <div class="row align-items-center">
                    <div class="col">
                        <h3 class="page-title">Buat Surat</h3>
                        <ul class="breadcrumb">
                            <li class="breadcrumb-item"><a href="#">Surat</a></li>
                            <li class="breadcrumb-item active">Draft Surat</li>
                        </ul>
                    </div>
                    <div class="col-auto">
                        <Link href="/admin/surat/create" class="btn btn-primary">
                            <i class="fas fa-plus me-2"></i> Buat Surat Baru
                        </Link>
                    </div>
                </div>
            </div>

            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white py-3">
                    <h5 class="mb-0 fw-bold text-secondary">Daftar Draft Surat</h5>
                </div>
                <div class="card-body p-0">
                    <VueEasyDataTable :headers="headers" :items="items" table-class-name="modern-data-table" border-cell alternating>
                        <template #item-created_at="item">
                            {{ new Date(item.created_at).toLocaleDateString('id-ID', { day: 'numeric', month: 'long', year: 'numeric'}) }}
                        </template>
                        <template #item-status="item">
                            <span class="badge bg-secondary">{{ item.status }}</span>
                        </template>
                        <template #item-actions="item">
                            <div class="action-buttons">
                                <button class="btn btn-info btn-sm text-white me-2" @click="printSurat(item.id)" title="Cetak PDF"><i class="fas fa-print"></i></button>
                                <Link :href="`/admin/surat/${item.id}/edit`" class="btn btn-warning btn-sm text-white me-2" title="Edit"><i class="fas fa-edit"></i></Link>
                                <button class="btn btn-success btn-sm text-white me-2" @click="archiveSurat(item.id)" title="Arsipkan (Final)"><i class="fas fa-check"></i></button>
                                <button class="btn btn-danger btn-sm" @click="deleteSurat(item.id)" title="Hapus"><i class="fas fa-trash"></i></button>
                            </div>
                        </template>
                    </VueEasyDataTable>
                    <div v-if="items.length === 0" class="p-4 text-center text-muted">
                        Belum ada draft surat. Klik "Buat Surat Baru" untuk memulai.
                    </div>
                </div>
            </div>
        </div>
    </AdminLayout>
</template>

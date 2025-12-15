<script setup>
import { computed } from 'vue';
import { Head, router } from '@inertiajs/vue3';
import AdminLayout from '@/Layouts/AdminLayout.vue';
import VueEasyDataTable from 'vue3-easy-data-table';
import 'vue3-easy-data-table/dist/style.css';

const props = defineProps({
    surats: Array,
});

const computedSurats = computed(() => {
    return props.surats.map((item, index) => ({
        ...item,
        no: index + 1
    }));
});

const headers = [
    { text: "No", value: "no", width: 50 },
    { text: "Jenis Surat", value: "jenis_surat" },
    { text: "Kode/Nomor", value: "kode_surat" },
    { text: "Tanggal Arsip", value: "updated_at" }, // Use updated_at for archive time
    { text: "Aksi", value: "actions", width: 150 },
];

const deleteSurat = (id) => {
    if(confirm('Hapus arsip surat ini secara permanen?')) router.delete(`/admin/surat/${id}`);
};
const printSurat = (id) => {
    window.open(`/admin/surat/${id}/cetak`, '_blank');
};
</script>

<template>
    <AdminLayout>
        <Head title="Surat Keluar" />
        
        <div class="content container-fluid">
            <!-- Header -->
            <div class="page-header mb-4">
                <div class="row align-items-center">
                    <div class="col">
                        <h3 class="page-title">Surat Keluar</h3>
                        <ul class="breadcrumb">
                            <li class="breadcrumb-item"><a href="/dashboard">Dashboard</a></li>
                            <li class="breadcrumb-item active">Surat Keluar</li>
                        </ul>
                    </div>
                </div>
            </div>

            <!-- Content -->
            <div class="animate__animated animate__fadeIn">
                <div class="alert alert-success border shadow-sm mb-4">
                    <h5 class="fw-bold"><i class="fas fa-archive me-2"></i> Arsip Surat Keluar</h5>
                    <p class="mb-0 small">Halaman ini menampilkan surat-surat yang telah selesai dibuat dan diarsipkan (Final). Anda dapat mencetak kembali surat dari sini.</p>
                </div>

                <!-- The Table -->
                <div class="card modern-table-card">
                    <div class="card-header bg-white border-bottom py-3">
                        <h5 class="m-0 fw-bold text-success">Daftar Surat Terarsip</h5>
                    </div>
                    <div class="card-body p-0">
                        <VueEasyDataTable :headers="headers" :items="computedSurats" table-class-name="modern-data-table" border-cell alternating>
                            <template #item-no="{ no }">
                                <span class="fw-bold text-secondary">{{ no }}</span>
                            </template>
                            <template #item-updated_at="item">{{ new Date(item.updated_at).toLocaleDateString('id-ID', { year: 'numeric', month: 'long', day: 'numeric'}) }}</template>
                            <template #item-actions="item">
                                <div class="action-buttons">
                                    <button class="btn btn-action btn-edit bg-info text-white me-2" @click="printSurat(item.id)" title="Cetak/PDF"><i class="fas fa-print"></i></button>
                                    <button class="btn btn-action btn-delete" @click="deleteSurat(item.id)" title="Hapus"><i class="fas fa-trash"></i></button>
                                </div>
                            </template>
                        </VueEasyDataTable>
                    </div>
                </div>
            </div>

        </div>
    </AdminLayout>
</template>

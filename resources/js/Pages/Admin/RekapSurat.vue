<script setup>
import { ref, watch, computed } from 'vue';
import { Head, router } from '@inertiajs/vue3';
import AdminLayout from '@/Layouts/AdminLayout.vue';
import VueEasyDataTable from 'vue3-easy-data-table';
import 'vue3-easy-data-table/dist/style.css';

const props = defineProps({
    data: Array,
    filters: Object
});

const form = ref({
    start_date: props.filters.start_date,
    end_date: props.filters.end_date,
    type: props.filters.type // all, masuk, keluar
});

const headers = [
    { text: "No", value: "no", width: 50 },
    { text: "Tanggal", value: "date", sortable: true },
    { text: "No. Surat", value: "no_surat" },
    { text: "Jenis / Asal", value: "jenis" },
    { text: "Perihal", value: "perihal" },
];

const computedItems = computed(() => {
    return props.data.map((item, index) => ({
        ...item,
        no: index + 1
    }));
});

const applyFilter = () => {
    router.get('/admin/rekap-surat', form.value, { preserveState: true, replace: true });
};

const printRekap = () => {
    // Build query string manually
    const query = new URLSearchParams(form.value).toString();
    window.open(`/admin/rekap-surat/print?${query}`, '_blank');
};

</script>

<template>
    <AdminLayout>
        <Head title="Rekap Surat" />
        
        <div class="content container-fluid">
            <!-- Header -->
            <div class="page-header mb-4">
                <div class="row align-items-center">
                    <div class="col">
                        <h3 class="page-title">Rekapitulasi Surat</h3>
                        <ul class="breadcrumb">
                            <li class="breadcrumb-item"><a href="/dashboard">Dashboard</a></li>
                            <li class="breadcrumb-item active">Buku Agenda</li>
                        </ul>
                    </div>
                    <div class="col-auto">
                        <button class="btn btn-outline-primary" @click="printRekap">
                            <i class="fas fa-print me-2"></i> CETAK LAPORAN
                        </button>
                    </div>
                </div>
            </div>

            <!-- FILTERS -->
            <div class="card mb-4 border-0 shadow-sm animate__animated animate__fadeInDown">
                <div class="card-body">
                    <div class="row g-3 align-items-end">
                        <div class="col-md-3">
                            <label class="form-label fw-bold small text-muted">Dari Tanggal</label>
                            <input type="date" class="form-control" v-model="form.start_date">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label fw-bold small text-muted">Sampai Tanggal</label>
                            <input type="date" class="form-control" v-model="form.end_date">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label fw-bold small text-muted">Jenis Surat</label>
                            <select class="form-select" v-model="form.type">
                                <option value="all">Semua Surat</option>
                                <option value="masuk">Surat Masuk</option>
                                <option value="keluar">Surat Keluar</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <div class="d-grid">
                                <button class="btn btn-primary" @click="applyFilter">
                                    <i class="fas fa-filter me-2"></i> Tampilkan Data
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Table -->
            <div class="card modern-table-card animate__animated animate__fadeInUp">
                <div class="card-header bg-white border-bottom py-3">
                    <h5 class="m-0 fw-bold text-secondary">Buku Agenda Surat</h5>
                </div>
                <div class="card-body p-0">
                    <VueEasyDataTable :headers="headers" :items="computedItems" table-class-name="modern-data-table" border-cell alternating>
                        <template #item-no="{ no }">
                            <span class="fw-bold text-secondary">{{ no }}</span>
                        </template>
                        <template #item-date="item">
                            {{ new Date(item.date).toLocaleDateString('id-ID', {day: 'numeric', month: 'short', year: 'numeric'}) }}
                        </template>
                        <template #item-jenis="item">
                            <div>
                                <span class="badge" :class="item.jenis === 'Surat Masuk' ? 'bg-info' : 'bg-success'">{{ item.jenis }}</span>
                                <div class="small text-muted mt-1">{{ item.pengirim_penerima }}</div>
                            </div>
                        </template>
                    </VueEasyDataTable>
                </div>
            </div>
        </div>
    </AdminLayout>
</template>

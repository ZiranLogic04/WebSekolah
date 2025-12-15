<template>
    <AdminLayout>
        <div class="content container-fluid">
            <!-- Page Header -->
            <div class="page-header">
                <div class="row align-items-center">
                    <div class="col">
                        <h3 class="page-title">Halaman Beranda</h3>
                        <ul class="breadcrumb">
                            <li class="breadcrumb-item"><Link href="/dashboard">Dashboard</Link></li>
                            <li class="breadcrumb-item active">Halaman</li>
                        </ul>
                    </div>
                </div>
            </div>
            <!-- /Page Header -->

            <div class="row">
                <div class="col-xl-3 col-sm-6 col-12 d-flex" v-for="section in sections" :key="section.id">
                    <div class="card bg-white flex-fill w-100">
                        <div class="card-body">
                            <div class="db-widgets d-flex justify-content-between align-items-center">
                                <div class="db-info">
                                    <h6>{{ section.title }}</h6>
                                    <h3 class="mt-2 text-muted fw-normal" style="font-size: 14px; line-height: 1.5;">{{ section.description }}</h3>
                                </div>
                                <div class="db-icon text-white" :class="section.color || 'bg-primary'">
                                    <i :class="section.icon"></i>
                                </div>
                            </div>
                            <div class="mt-3">
                                <Link :href="`/admin/pages/${section.id}/edit`" class="btn btn-sm btn-outline-primary w-100">
                                    <i class="fas fa-edit me-1"></i> Edit Konten
                                </Link>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AdminLayout>
</template>

<script setup>
import { Link } from '@inertiajs/vue3';
import AdminLayout from '@/Layouts/AdminLayout.vue';

defineProps({
    sections: Array
});
</script>

<style scoped>
.db-widgets .db-icon {
    width: 50px;
    height: 50px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 20px;
    flex-shrink: 0;
}
.db-widgets .db-info h6 {
    font-weight: 600;
    font-size: 16px;
    margin-bottom: 5px;
}
.card {
    border: 1px solid #e3e3e3;
    border-radius: 10px;
    box-shadow: 0 0 10px rgba(0,0,0,0.05);
    transition: all 0.3s ease;
}
.card:hover {
    transform: translateY(-5px);
    box-shadow: 0 5px 15px rgba(0,0,0,0.1);
}
</style>

<template>
    <AdminLayout>
        <div class="content container-fluid">
            <!-- Page Header -->
            <div class="page-header">
                <div class="row align-items-center">
                    <div class="col">
                        <h3 class="page-title">Kelola Halaman</h3>
                        <ul class="breadcrumb">
                            <li class="breadcrumb-item"><Link href="/admin/dashboard">Dashboard</Link></li>
                            <li class="breadcrumb-item active">Halaman</li>
                        </ul>
                    </div>
                </div>
            </div>
            <!-- /Page Header -->

            <!-- Section Cards -->
            <div class="row g-4">
                <div class="col-lg-4 col-md-6" v-for="section in sections || []" :key="section.id">
                    <div class="page-card">
                        <div class="page-card-header" :class="section.color || 'bg-primary'">
                            <div class="page-icon">
                                <i :class="section.icon"></i>
                            </div>
                        </div>
                        <div class="page-card-body">
                            <h5 class="page-title">{{ section.title }}</h5>
                            <p class="page-desc">{{ section.description }}</p>
                            <Link :href="`/admin/pages/${section.id}/edit`" class="edit-btn">
                                <i class="fas fa-pen me-2"></i>Edit Konten
                            </Link>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Info Box -->
            <div class="info-box mt-4">
                <div class="info-icon">
                    <i class="fas fa-info-circle"></i>
                </div>
                <div class="info-content">
                    <strong>Catatan:</strong> Perubahan yang Anda simpan akan langsung tampil di halaman publik website.
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
.page-card {
    background: #fff;
    border-radius: 16px;
    overflow: hidden;
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
    transition: all 0.3s ease;
    height: 100%;
}

.page-card:hover {
    transform: translateY(-8px);
    box-shadow: 0 12px 40px rgba(0, 0, 0, 0.15);
}

.page-card-header {
    height: 100px;
    display: flex;
    align-items: center;
    justify-content: center;
    position: relative;
    overflow: hidden;
}

.page-card-header::before {
    content: '';
    position: absolute;
    top: -50%;
    left: -50%;
    width: 200%;
    height: 200%;
    background: radial-gradient(circle, rgba(255,255,255,0.2) 0%, transparent 70%);
    animation: shimmer 3s infinite;
}

@keyframes shimmer {
    0%, 100% { transform: rotate(0deg); }
    50% { transform: rotate(180deg); }
}

.page-icon {
    width: 60px;
    height: 60px;
    background: rgba(255, 255, 255, 0.2);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    position: relative;
    z-index: 1;
}

.page-icon i {
    font-size: 24px;
    color: #fff;
}

.page-card-body {
    padding: 24px;
    text-align: center;
}

.page-card-body .page-title {
    font-size: 18px;
    font-weight: 700;
    color: #1a1a2e;
    margin-bottom: 8px;
}

.page-card-body .page-desc {
    font-size: 13px;
    color: #6c757d;
    line-height: 1.5;
    margin-bottom: 20px;
    min-height: 40px;
}

.edit-btn {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    padding: 10px 24px;
    background: linear-gradient(135deg, #3d5ee1 0%, #5a7cf7 100%);
    color: #fff;
    border-radius: 50px;
    font-size: 14px;
    font-weight: 600;
    text-decoration: none;
    transition: all 0.3s ease;
    box-shadow: 0 4px 15px rgba(61, 94, 225, 0.3);
}

.edit-btn:hover {
    background: linear-gradient(135deg, #2a4bc9 0%, #4a6ce7 100%);
    transform: scale(1.05);
    box-shadow: 0 6px 20px rgba(61, 94, 225, 0.4);
    color: #fff;
}

.info-box {
    display: flex;
    align-items: center;
    gap: 16px;
    padding: 16px 20px;
    background: linear-gradient(135deg, #e8f4fd 0%, #f0f7ff 100%);
    border-radius: 12px;
    border-left: 4px solid #3d5ee1;
}

.info-icon {
    width: 40px;
    height: 40px;
    background: #3d5ee1;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
}

.info-icon i {
    color: #fff;
    font-size: 18px;
}

.info-content {
    font-size: 14px;
    color: #4a5568;
}

/* Color variations */
.bg-primary { background: linear-gradient(135deg, #3d5ee1 0%, #5a7cf7 100%); }
.bg-success { background: linear-gradient(135deg, #28a745 0%, #48c76a 100%); }
.bg-warning { background: linear-gradient(135deg, #ffc107 0%, #ffda44 100%); }
.bg-info { background: linear-gradient(135deg, #17a2b8 0%, #3cc0d6 100%); }
.bg-secondary { background: linear-gradient(135deg, #6c757d 0%, #8e99a4 100%); }
.bg-danger { background: linear-gradient(135deg, #dc3545 0%, #f06270 100%); }
</style>

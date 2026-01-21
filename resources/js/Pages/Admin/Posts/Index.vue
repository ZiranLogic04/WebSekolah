<template>
    <AdminLayout>
        <div class="content container-fluid p-4">
            
            <!-- Radiant Header Card -->
            <div class="card border-0 shadow-lg rounded-4 mb-4 overflow-hidden position-relative" style="background: linear-gradient(135deg, #4f46e5 0%, #3b82f6 100%);">
                <div class="card-body p-4 p-lg-5 position-relative z-1">
                    <div class="row align-items-center">
                        <div class="col-lg-8">
                            <div class="d-flex align-items-center gap-3 mb-2">
                                <div class="icon-box bg-white rounded-3 text-primary p-2">
                                    <i class="feather-file-text fs-3"></i>
                                </div>
                                <h2 class="fw-bold text-white mb-0 ls-tight">Manajemen Berita</h2>
                            </div>
                            <p class="text-white-50 mb-0 fs-5">Kelola artikel, berita, dan pengumuman sekolah dengan sentuhan profesional.</p>
                        </div>
                        <div class="col-lg-4 text-lg-end mt-3 mt-lg-0">
                            <Link href="/admin/posts/create" class="btn btn-light btn-lg rounded-pill shadow-sm px-4 fw-bold text-primary hover-elevate">
                                <i class="feather-plus-circle me-2"></i>Tulis Berita Baru
                            </Link>
                        </div>
                    </div>
                </div>
                <!-- Decorative Background -->
                <div class="position-absolute bottom-0 end-0 opacity-10 me-n5 mb-n5">
                    <i class="feather-file-text" style="font-size: 15rem; color: white;"></i>
                </div>
            </div>

            <!-- Modern Search & Filter Toolbar -->
            <div class="card border-0 shadow-sm rounded-4 mb-5 backdrop-blur-md bg-white-80">
                <div class="card-body p-2">
                    <div class="row g-2 align-items-center">
                        <div class="col-md-7">
                            <div class="nav nav-pills nav-fill bg-light p-1 rounded-pill p-1 gap-1">
                                <Link 
                                    href="/admin/posts" 
                                    class="nav-link rounded-pill text-sm fw-bold transition-all"
                                    :class="!filters.status ? 'active bg-white shadow-sm text-primary scale-100' : 'text-muted hover-bg-white-50'"
                                >
                                    <i class="feather-grid me-1"></i> Semua
                                </Link>
                                <Link 
                                    href="/admin/posts?status=published" 
                                    class="nav-link rounded-pill text-sm fw-bold transition-all"
                                    :class="filters.status === 'published' ? 'active bg-white shadow-sm text-success scale-100' : 'text-muted hover-bg-white-50'"
                                >
                                    <i class="feather-check-circle me-1"></i> Publish
                                </Link>
                                <Link 
                                    href="/admin/posts?status=draft" 
                                    class="nav-link rounded-pill text-sm fw-bold transition-all"
                                    :class="filters.status === 'draft' ? 'active bg-white shadow-sm text-secondary scale-100' : 'text-muted hover-bg-white-50'"
                                >
                                    <i class="feather-edit-2 me-1"></i> Draft
                                </Link>
                            </div>
                        </div>
                        <div class="col-md-5">
                            <div class="input-group input-group-lg border-0 bg-light rounded-pill overflow-hidden focus-ring-primary">
                                <span class="input-group-text bg-transparent border-0 ps-4 text-muted">
                                    <i class="feather-search"></i>
                                </span>
                                <input 
                                    type="text" 
                                    class="form-control border-0 bg-transparent shadow-none" 
                                    placeholder="Cari judul berita..." 
                                    v-model="search" 
                                    @keyup.enter="handleSearch"
                                    style="font-size: 0.95rem;"
                                >
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Elegant Card List Content -->
            <div class="d-flex flex-column gap-4" v-if="posts.data.length > 0">
                <div v-for="post in posts.data" :key="post.id" class="card border-0 shadow-hover rounded-4 overflow-hidden group-hover-effect">
                    <div class="card-body p-0">
                        <div class="row g-0 h-100">
                            <!-- Image Section -->
                            <div class="col-lg-4 position-relative overflow-hidden">
                                <div class="h-100 min-h-250 position-relative">
                                     <img 
                                        :src="post.image ? `/storage/${post.image}` : '/assets/img/category/blog-6.jpg'" 
                                        class="w-100 h-100 object-fit-cover transition-transform duration-700 group-hover-scale" 
                                        alt="Cover"
                                        @error="$event.target.src = '/assets/img/category/blog-6.jpg'"
                                    >
                                    <div class="position-absolute inset-0 bg-gradient-to-t from-black-50 to-transparent"></div>
                                    <div class="position-absolute top-0 start-0 m-3">
                                        <div class="badge rounded-pill px-3 py-2 shadow-sm border border-white border-opacity-25 backdrop-blur-md fw-bold letter-spacing-1" 
                                            :class="post.status === 'published' ? 'bg-success text-white' : 'bg-dark text-white'">
                                            {{ post.status === 'published' ? 'PUBLISH' : 'DRAFT' }}
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Content Section -->
                            <div class="col-lg-8 p-4 p-xl-5 d-flex flex-column justify-content-between bg-white">
                                <div>
                                    <div class="d-flex flex-wrap justify-content-between align-items-start mb-3 gap-2">
                                        <div class="d-flex align-items-center gap-3 text-secondary small fw-semibold">
                                            <div class="d-flex align-items-center gap-2 bg-light px-3 py-1 rounded-pill">
                                                <i class="feather-calendar text-primary"></i> 
                                                <span class="text-dark">{{ formatDate(post.created_at) }}</span>
                                            </div>
                                            <div class="d-flex align-items-center gap-2">
                                                <i class="feather-user text-secondary"></i> 
                                                <span class="text-dark">{{ post.author?.name || 'Admin' }}</span>
                                            </div>
                                        </div>
                                        
                                        <!-- User Friendly Status Switch -->
                                        <div class="d-flex align-items-center px-1 py-1 rounded-pill border transition-all hover-border-primary" :class="post.status === 'published' ? 'bg-success-subtle border-success-subtle' : 'bg-light border-light-subtle'">
                                            <div class="form-check form-switch m-0 d-flex align-items-center gap-2 ps-1 pe-2">
                                                <input 
                                                    class="custom-toggle-input shadow-none cursor-pointer" 
                                                    type="checkbox" 
                                                    role="switch" 
                                                    :checked="post.status === 'published'"
                                                    @change="toggleStatus(post)"
                                                >
                                                <span class="fw-bold small user-select-none" :class="post.status === 'published' ? 'text-success' : 'text-secondary'">
                                                    {{ post.status === 'published' ? 'Online' : 'Offline' }}
                                                </span>
                                            </div>
                                        </div>
                                    </div>

                                    <Link :href="`/admin/posts/${post.id}/edit`" class="text-dark text-decoration-none group">
                                        <h3 class="fw-bold mb-3 hover-text-primary transition-colors display-6 fs-4 lh-sm text-dark">
                                            {{ post.title }}
                                        </h3>
                                    </Link>
                                    
                                    <p class="text-muted mb-0 line-clamp-2 fs-6">
                                        {{ truncate(stripHtml(post.content), 200) }}
                                    </p>
                                </div>

                                <!-- Modern Icon Actions -->
                                <div class="d-flex align-items-center justify-content-between mt-4 pt-4 border-top border-light">
                                    <div class="d-flex gap-2">
                                        <Link 
                                            :href="`/admin/posts/${post.id}/edit`" 
                                            class="btn btn-light rounded-circle p-0 d-flex align-items-center justify-content-center text-secondary hover-bg-warning hover-text-white transition-all shadow-sm-hover"
                                            style="width: 42px; height: 42px;"
                                            title="Edit Berita"
                                        >
                                            <i class="feather-edit-3 fs-5"></i>
                                        </Link>
                                        <button 
                                            @click="deletePost(post.id)" 
                                            class="btn btn-light rounded-circle p-0 d-flex align-items-center justify-content-center text-secondary hover-bg-danger hover-text-white transition-all shadow-sm-hover"
                                            style="width: 42px; height: 42px;"
                                            title="Hapus Berita"
                                        >
                                            <i class="feather-trash-2 fs-5"></i>
                                        </button>
                                    </div>
                                    
                                    <a 
                                        :href="post.status === 'published' ? `/berita/${post.slug}` : '#'" 
                                        target="_blank" 
                                        class="btn btn-outline-primary rounded-pill px-4 fw-bold d-flex align-items-center gap-2 transition-all hover-elevate"
                                        :class="{'disabled opacity-50': post.status !== 'published'}"
                                    >
                                        <span class="small">Lihat Artikel</span>
                                        <i class="feather-arrow-right-circle"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Empty State -->
            <div v-else class="text-center py-5 my-5 bg-white rounded-5 shadow-sm border border-dashed border-2">
                <div class="mb-4">
                    <div class="bg-primary-subtle text-primary rounded-circle d-inline-flex align-items-center justify-content-center animate-bounce-slow" style="width: 100px; height: 100px;">
                        <i class="feather-edit-3 fs-1"></i>
                    </div>
                </div>
                <h3 class="fw-bold text-dark mb-2">Belum Ada Artikel</h3>
                <p class="text-muted mb-4 max-w-md mx-auto fs-5">Mulai bangun kehadiran digital sekolah Anda dengan menulis artikel pertama.</p>
                <Link href="/admin/posts/create" class="btn btn-primary btn-lg px-5 py-3 rounded-pill shadow-lg fw-bold hover-elevate">
                    <i class="feather-plus me-2"></i>Tulis Berita Sekarang
                </Link>
            </div>

            <!-- Pagination -->
            <div class="mt-5 d-flex justify-content-center" v-if="posts.links.length > 3">
                 <ul class="pagination mb-0 gap-2 p-2 bg-white rounded-pill shadow-sm">
                    <li class="page-item" :class="{ disabled: !link.url, active: link.active }" v-for="(link, index) in posts.links" :key="index">
                        <Link 
                            class="page-link border-0 rounded-circle w-40px h-40px d-flex align-items-center justify-content-center fw-bold text-sm transition-all" 
                            :class="link.active ? 'bg-primary text-white shadow-md scale-110' : 'bg-transparent text-secondary hover-bg-light'"
                            :href="link.url || '#'" 
                            v-html="link.label"
                        ></Link>
                    </li>
                </ul>
            </div>
        </div>
    </AdminLayout>
</template>

<script setup>
import { ref, watch } from 'vue';
import { Link, router, usePage } from '@inertiajs/vue3';
import AdminLayout from '@/Layouts/AdminLayout.vue';
import { confirmDelete, showToast } from '@/Utils/swal';

const props = defineProps({
    posts: Object,
    filters: Object,
});

const search = ref(props.filters.search || '');

const handleSearch = () => {
    router.get('/admin/posts', { search: search.value }, { preserveState: true, replace: true });
};

watch(search, (value) => {
    if (value === '') {
        handleSearch();
    }
});

const deletePost = (id) => {
    confirmDelete().then((result) => {
        if (result.isConfirmed) {
            router.delete(`/admin/posts/${id}`, {
                onSuccess: () => {
                    showToast('success', 'Berhasil', 'Artikel telah dihapus');
                },
                onError: () => {
                    showToast('error', 'Gagal', 'Terjadi kesalahan saat menghapus artikel');
                }
            });
        }
    });
};

const toggleStatus = (post) => {
    router.patch(`/admin/posts/${post.id}/toggle-status`, {}, {
        preserveScroll: true,
        onSuccess: () => {
            // Toast handled by flash message watcher
        },
        onError: () => {
             showToast('error', 'Gagal', 'Terjadi kesalahan saat mengubah status artikel');
        }
    });
};

// Check for flash messages
const page = usePage();
watch(() => page.props.flash, (flash) => {
    if (flash?.success) {
        showToast('success', 'Sukses', flash.success);
    }
    if (flash?.error) {
        showToast('error', 'Error', flash.error);
    }
}, { deep: true, immediate: true });

const formatDate = (dateString) => {
    const options = { year: 'numeric', month: 'long', day: 'numeric' };
    return new Date(dateString).toLocaleDateString('id-ID', options);
};

const stripHtml = (html) => {
    if (!html) return "";
    return html.replace(/<[^>]*>?/gm, '');
};

const truncate = (str, n) => {
  if (!str) return "";
  return (str.length > n) ? str.substring(0, n-1) + '...' : str;
};
</script>



<style scoped>
/* Modern Icon Actions - Forced Update */
.btn-icon-modern {
    width: 42px;
    height: 42px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    border: none;
    background-color: #f3f4f6;
    color: #6b7280;
    font-size: 1.1rem;
    transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
    cursor: pointer;
}

.btn-icon-modern:hover {
    transform: translateY(-2px);
    color: white;
}

.btn-icon-edit:hover {
    background-color: #f59e0b; /* Amber */
    box-shadow: 0 4px 12px rgba(245, 158, 11, 0.3);
}

.btn-icon-delete:hover {
    background-color: #ef4444; /* Red */
    box-shadow: 0 4px 12px rgba(239, 68, 68, 0.3);
}

.btn-view-modern {
    text-decoration: none;
    display: flex;
    align-items: center;
    transition: all 0.2s;
    padding: 8px 16px;
    border-radius: 50px;
    background-color: transparent;
}
.btn-view-modern:hover:not(.disabled) {
    background-color: #eff6ff;
    color: #2563eb !important;
    transform: translateX(3px);
}

.hover-scale:hover {
    transform: scale(1.02);
}

/* Custom Toggle Switch - Unique Class */
.custom-toggle-input {
    appearance: none;
    width: 3.2em !important;
    height: 1.6em !important;
    background-color: #e5e7eb !important;
    border-radius: 2em;
    position: relative;
    cursor: pointer;
    transition: background-color 0.3s ease;
    box-shadow: inset 0 2px 4px rgba(0,0,0,0.1);
}

.custom-toggle-input::after {
    content: '';
    position: absolute;
    top: 0.15em;
    left: 0.15em;
    width: 1.3em;
    height: 1.3em;
    background-color: white;
    border-radius: 50%;
    transition: transform 0.3s cubic-bezier(0.4, 0.0, 0.2, 1);
    box-shadow: 0 2px 4px rgba(0,0,0,0.2);
}

.custom-toggle-input:checked {
    background-color: #10b981 !important;
}

.custom-toggle-input:checked::after {
    transform: translateX(1.6em);
}


.image-section {
    flex: 0 0 320px;
}
@media (max-width: 992px) {
    .image-section {
        flex: 0 0 100%;
        height: 200px !important;
        min-height: auto !important;
    }
}
.card-hover-effect {
    transition: transform 0.3s ease, box-shadow 0.3s ease;
}
.card-hover-effect:hover {
    transform: translateY(-4px);
    box-shadow: 0 1rem 3rem rgba(0,0,0,.1) !important;
}
.display-title {
    font-size: 1.25rem;
    line-height: 1.4;
}
.hover-up:hover {
    transform: translateY(-2px);
}
.w-40px {
    width: 40px;
}
.h-40px {
    height: 40px;
}
.backdrop-blur {
    backdrop-filter: blur(8px);
    background-color: rgba(255, 255, 255, 0.2);
}
.object-fit-cover {
    object-fit: cover;
}
.hover-text-primary:hover {
    color: #3d5ee1 !important;
}
.transition-colors {
    transition: color 0.2s ease;
}
.line-clamp-2 {
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
}
</style>

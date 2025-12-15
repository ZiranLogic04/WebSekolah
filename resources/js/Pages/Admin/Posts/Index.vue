<template>
    <AdminLayout>
        <div class="content container-fluid">
            <div class="page-header">
                <div class="row align-items-center">
                    <div class="col">
                        <h3 class="page-title">{{ filters.status === 'draft' ? 'Daftar Draft' : 'Daftar Postingan' }}</h3>
                        <ul class="breadcrumb">
                            <li class="breadcrumb-item"><Link href="/dashboard">Dashboard</Link></li>
                            <li class="breadcrumb-item active">Posts</li>
                        </ul>
                    </div>
                    <div class="col-auto">
                        <Link href="/admin/posts/create" class="btn btn-primary"><i class="fas fa-plus"></i> Tambah Baru</Link>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-12">
                    <div class="card card-table">
                        <div class="card-body">
                            <!-- Filter Tabs -->
                            <div class="row mb-4">
                                <div class="col-md-12">
                                    <ul class="nav nav-tabs nav-tabs-solid">
                                        <li class="nav-item">
                                            <Link class="nav-link" :class="{ active: !filters.status }" href="/admin/posts">Semua</Link>
                                        </li>
                                        <li class="nav-item">
                                            <Link class="nav-link" :class="{ active: filters.status === 'published' }" href="/admin/posts?status=published">Published</Link>
                                        </li>
                                        <li class="nav-item">
                                            <Link class="nav-link" :class="{ active: filters.status === 'draft' }" href="/admin/posts?status=draft">Draft</Link>
                                        </li>
                                    </ul>
                                </div>
                            </div>

                            <!-- Search Filter -->
                            <div class="row mb-4">
                                <div class="col-md-4">
                                    <input type="text" class="form-control" placeholder="Cari Judul..." v-model="search" @keyup.enter="handleSearch">
                                </div>
                            </div>

                            <!-- Grid View -->
                            <div class="row">
                                <div class="col-md-6 col-xl-4 col-sm-12 d-flex" v-for="post in posts.data" :key="post.id">
                                    <div class="blog grid-blog flex-fill">
                                        <div class="blog-image">
                                            <Link :href="`/admin/posts/${post.id}/edit`">
                                                <img class="img-fluid" :src="post.image ? `/storage/${post.image}` : '/assets/img/category/blog-6.jpg'" alt="Post Image" style="height: 200px; object-fit: cover; width: 100%;">
                                            </Link>
                                            <div class="blog-views">
                                                <i class="fas fa-eye me-1"></i> {{ post.views || 0 }}
                                            </div>
                                        </div>
                                        <div class="blog-content">
                                            <ul class="entry-meta meta-item">
                                                <li>
                                                    <div class="post-author">
                                                        <a href="#">
                                                            <img src="/assets/img/profiles/avatar-01.jpg" alt="Post Author">
                                                            <span>
                                                                <span class="post-title">{{ post.author?.name || 'Admin' }}</span>
                                                                <span class="post-date"><i class="far fa-clock"></i> {{ formatDate(post.created_at) }}</span>
                                                            </span>
                                                        </a>
                                                    </div>
                                                </li>
                                            </ul>
                                            <h3 class="blog-title"><Link :href="`/admin/posts/${post.id}/edit`">{{ post.title }}</Link></h3>
                                            <p>{{ truncate(stripHtml(post.content), 100) }}</p>
                                        </div>
                                        <div class="row">
                                            <div class="edit-options">
                                                <div class="edit-delete-btn">
                                                    <Link :href="`/admin/posts/${post.id}/edit`" class="text-success"><i class="feather-edit-3 me-1"></i> Edit</Link>
                                                    <a href="#" @click.prevent="deletePost(post.id)" class="text-danger"><i class="feather-trash-2 me-1"></i> Delete</a>
                                                </div>
                                                <div class="text-end inactive-style">
                                                    <span :class="post.status === 'published' ? 'text-success' : 'text-danger'">
                                                        <i :class="post.status === 'published' ? 'feather-eye' : 'feather-eye-off'" class="me-1"></i> 
                                                        {{ post.status === 'published' ? 'Published' : 'Draft' }}
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Pagination -->
                            <div class="row" v-if="posts.links.length > 3">
                                <div class="col-md-12">
                                    <div class="pagination-tab d-flex justify-content-center">
                                        <ul class="pagination mb-0">
                                            <li class="page-item" :class="{ disabled: !link.url, active: link.active }" v-for="(link, index) in posts.links" :key="index">
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
    </AdminLayout>
</template>

<style scoped>
@import '@/../css/admin-blog.css';
</style>

<script setup>
import { ref, watch } from 'vue';
import { Link, router } from '@inertiajs/vue3';
import AdminLayout from '@/Layouts/AdminLayout.vue';
import Swal from 'sweetalert2';

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
            router.delete(`/admin/posts/${id}`);
        }
    });
};

const formatDate = (dateString) => {
    const options = { year: 'numeric', month: 'short', day: 'numeric' };
    return new Date(dateString).toLocaleDateString('id-ID', options);
};

const stripHtml = (html) => {
   let tmp = document.createElement("DIV");
   tmp.innerHTML = html;
   return tmp.textContent || tmp.innerText || "";
};

const truncate = (str, n) => {
  return (str.length > n) ? str.substr(0, n-1) + '&hellip;' : str;
};
</script>

<style scoped>
.blog-image img {
    border-radius: 4px 4px 0 0;
}
.blog-content p {
    color: #6c757d;
    font-size: 14px;
    margin-bottom: 0;
    min-height: 42px;
}
.blog.grid-blog {
    border: 1px solid #e3e3e3;
    border-radius: 4px;
    margin-bottom: 30px;
    background: #fff;
    transition: all 0.3s ease;
}
.blog.grid-blog:hover {
    box-shadow: 0 0 15px rgba(0, 0, 0, 0.1);
}
</style>

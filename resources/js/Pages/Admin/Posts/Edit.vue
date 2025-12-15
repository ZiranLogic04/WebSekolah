<template>
    <AdminLayout>
        <div class="page-header">
            <div class="row align-items-center">
                <div class="col">
                    <h3 class="page-title">Edit Post</h3>
                    <ul class="breadcrumb">
                        <li class="breadcrumb-item"><Link href="/dashboard">Dashboard</Link></li>
                        <li class="breadcrumb-item"><Link href="/admin/posts">Posts</Link></li>
                        <li class="breadcrumb-item active">Edit</li>
                    </ul>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-xl-12">
                <div class="card">
                    <div class="card-body">
                        <form @submit.prevent="submit">
                            <div class="bank-inner-details">
                                <div class="row">
                                    <div class="col-lg-12 col-md-12">
                                        <div class="form-group">
                                            <label>Judul<span class="text-danger">*</span></label>
                                            <input type="text" class="form-control" v-model="form.title" :class="{'is-invalid': form.errors.title}">
                                            <div class="invalid-feedback" v-if="form.errors.title">{{ form.errors.title }}</div>
                                        </div>
                                    </div>
                                    
                                    <div class="col-lg-12 col-md-12">
                                        <div class="form-group">
                                            <label>Status</label>
                                            <select class="form-control form-select" v-model="form.status">
                                                <option value="draft">Draft</option>
                                                <option value="published">Published</option>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-lg-12 col-md-12">
                                        <div class="form-group">
                                            <label>Gambar Utama</label>
                                            <div class="change-photo-btn">
                                                <div v-if="!imagePreview">
                                                    <p>Tambah Gambar</p>
                                                </div>
                                                <div v-else>
                                                    <img :src="imagePreview" alt="Preview" style="max-height: 200px; width: auto; display: block; margin-bottom: 10px;">
                                                    <p>Ganti Gambar</p>
                                                </div>
                                                <input type="file" class="upload" @change="handleImageUpload" accept="image/*">
                                            </div>
                                            <small class="text-danger" v-if="form.errors.image">{{ form.errors.image }}</small>
                                        </div>
                                    </div>

                                    <div class="col-lg-12 col-md-12">
                                        <div class="form-group">
                                            <label>Konten</label>
                                            <div style="min-height: 200px;">
                                                <QuillEditor theme="snow" v-model:content="form.content" contentType="html" toolbar="full" />
                                            </div>
                                            <small class="text-danger" v-if="form.errors.content">{{ form.errors.content }}</small>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="blog-categories-btn pt-0">
                                <div class="bank-details-btn">
                                    <button type="submit" class="btn btn-primary me-2" :disabled="form.processing">Simpan Perubahan</button>
                                    <Link href="/admin/posts" class="btn bank-cancel-btn">Kembali</Link>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </AdminLayout>
</template>

<script setup>
import { ref } from 'vue';
import { Link, useForm } from '@inertiajs/vue3';
import AdminLayout from '@/Layouts/AdminLayout.vue';
import { QuillEditor } from '@vueup/vue-quill';
import '@vueup/vue-quill/dist/vue-quill.snow.css';

const props = defineProps({
    post: Object,
});

const form = useForm({
    _method: 'PUT',
    title: props.post.title,
    content: props.post.content,
    status: props.post.status,
    image: null,
});

const imagePreview = ref(props.post.image ? `/storage/${props.post.image}` : null);

const handleImageUpload = (event) => {
    const file = event.target.files[0];
    if (file) {
        form.image = file;
        
        const reader = new FileReader();
        reader.onload = (e) => {
            imagePreview.value = e.target.result;
        };
        reader.readAsDataURL(file);
    }
};

const submit = () => {
    form.post(`/admin/posts/${props.post.id}`);
};
</script>

<style>
/* Override Quill height */
.ql-editor {
    min-height: 200px;
}
.change-photo-btn {
    background-color: #f7f7f7;
    border: 2px dashed #e5e5e5;
    border-radius: 6px;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    padding: 20px;
    position: relative;
    color: #979797;
    font-weight: 500;
    font-size: 16px;
    text-align: center;
    width: 100%;
}
.change-photo-btn input.upload {
    bottom: 0;
    cursor: pointer;
    filter: alpha(opacity=0);
    height: 100%;
    left: 0;
    margin: 0;
    opacity: 0;
    padding: 0;
    position: absolute;
    right: 0;
    top: 0;
    width: 100%;
    z-index: 20;
}
</style>

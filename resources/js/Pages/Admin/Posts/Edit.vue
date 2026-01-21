<template>
    <AdminLayout>
        <div class="content container-fluid p-4">
            
            <form @submit.prevent="submit">
                <!-- Sticky Action Header -->
                <div class="card shadow-lg mb-4 border-0 sticky-top header-card" style="top: 10px; z-index: 990;">
                    <div class="card-body py-3">
                        <div class="d-flex justify-content-between align-items-center">
                            <div class="d-flex align-items-center gap-3">
                                <Link href="/admin/posts" class="btn btn-outline-light btn-sm rounded-circle p-2" title="Kembali">
                                    <i class="fas fa-arrow-left"></i>
                                </Link>
                                <div>
                                    <h4 class="mb-0 fw-bold text-white">Edit Berita</h4>
                                    <p class="text-white-50 mb-0 small">Perbarui artikel dan informasi berita</p>
                                </div>
                            </div>
                            <div class="d-flex gap-2">
                                <Link href="/admin/posts" class="btn btn-light text-muted px-4 py-2 fw-semibold shadow-sm">
                                    Batal
                                </Link>
                                <button type="submit" class="btn btn-warning px-4 py-2 fw-bold shadow-sm text-dark" :disabled="form.processing">
                                    <i class="fas fa-save me-2"></i>
                                    {{ form.status === 'published' ? 'Perbarui & Terbit' : 'Simpan Draft' }}
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row g-4">
                    <!-- Column Left: Main Content -->
                    <div class="col-lg-8">
                        <div class="card shadow-sm border-0 rounded-4 mb-4">
                            <div class="card-body p-4">
                                <div class="form-group mb-4">
                                    <label class="form-label fw-bold text-secondary mb-2">JUDUL BERITA</label>
                                    <input 
                                        type="text" 
                                        class="form-control form-control-lg fs-3 fw-bold border-0 border-bottom rounded-0 px-0 py-2 focus-ring-none" 
                                        placeholder="Ketik judul berita..." 
                                        v-model="form.title" 
                                        :class="{'is-invalid': form.errors.title}"
                                        style="box-shadow: none !important;"
                                    >
                                    <div class="invalid-feedback fs-6" v-if="form.errors.title">{{ form.errors.title }}</div>
                                </div>

                                <div class="form-group mb-0">
                                    <label class="form-label fw-bold text-secondary mb-3 d-flex justify-content-between">
                                        <span>KONTEN ARTIKEL</span>
                                        <span class="badge bg-light text-muted fw-normal">Editor Aktif</span>
                                    </label>
                                    <div class="quill-wrapper rounded-3 overflow-hidden border shadow-sm" :class="{'is-invalid': form.errors.content}">
                                        <QuillEditor ref="quill" theme="snow" v-model:content="form.content" contentType="html" toolbar="full" />
                                    </div>
                                    <div class="text-danger small mt-1" v-if="form.errors.content">{{ form.errors.content }}</div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Column Right: Sidebar Actions -->
                    <div class="col-lg-4">
                        <div class="card shadow-sm border-0 rounded-4 mb-4">
                            <div class="card-header bg-white py-3 border-bottom rounded-top-4">
                                <h6 class="card-title mb-0 fw-bold text-primary"><i class="fas fa-cog me-2"></i>Pengaturan Publikasi</h6>
                            </div>
                            <div class="card-body">
                                <div class="mb-3">
                                    <label class="form-label fw-medium small text-muted">STATUS DOKUMEN</label>
                                    <div class="d-flex gap-2">
                                        <div class="form-check custom-radio-card flex-fill">
                                            <input class="form-check-input d-none" type="radio" name="status" id="statusDraft" value="draft" v-model="form.status">
                                            <label class="form-check-label w-100 p-2 border rounded text-center cursor-pointer transition-all" for="statusDraft">
                                                <i class="fas fa-file-alt d-block mb-1 fs-5"></i>
                                                Draft
                                            </label>
                                        </div>
                                        <div class="form-check custom-radio-card flex-fill">
                                            <input class="form-check-input d-none" type="radio" name="status" id="statusPub" value="published" v-model="form.status">
                                            <label class="form-check-label w-100 p-2 border rounded text-center cursor-pointer transition-all" for="statusPub">
                                                <i class="fas fa-rocket d-block mb-1 fs-5"></i>
                                                Publish
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Image Card -->
                        <div class="card shadow-sm border-0 rounded-4 mb-4">
                            <div class="card-header bg-white py-3 border-bottom rounded-top-4">
                                <h6 class="card-title mb-0 fw-bold text-primary"><i class="fas fa-image me-2"></i>Gambar Sampul</h6>
                            </div>
                            <div class="card-body">
                                <div class="image-upload-area rounded-3" @click="$refs.fileInput.click()">
                                    <div v-if="!imagePreview" class="text-center p-5">
                                        <div class="mb-3">
                                            <span class="icon-circle bg-light text-primary rounded-circle" style="width: 60px; height: 60px; display: inline-flex; align-items: center; justify-content: center;">
                                                <i class="fas fa-cloud-upload-alt fs-3"></i>
                                            </span>
                                        </div>
                                        <h6 class="fw-bold mb-1">Upload Gambar</h6>
                                        <p class="mb-0 text-muted small">Klik untuk ubah gambar cover</p>
                                    </div>
                                    <div v-else class="preview-container">
                                        <img :src="imagePreview" alt="Preview" class="img-fluid rounded-3 w-100 object-fit-cover" style="height: 250px;">
                                        <div class="overlay rounded-3">
                                            <span class="btn btn-light btn-sm fw-bold shadow-sm"><i class="fas fa-camera me-1"></i> Ganti Gambar</span>
                                        </div>
                                    </div>
                                </div>
                                <input ref="fileInput" type="file" class="d-none" @change="handleImageUpload" accept="image/*">
                                <div class="text-danger small mt-2 text-center fw-bold" v-if="form.errors.image">{{ form.errors.image }}</div>
                                <div class="alert alert-light border mt-3 mb-0 p-2 small text-muted d-flex align-items-start gap-2">
                                    <i class="fas fa-info-circle mt-1 text-info"></i>
                                    <div>
                                        Gambar saat ini akan diganti jika Anda mengupload baru.
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </AdminLayout>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import { Link, useForm } from '@inertiajs/vue3';
import AdminLayout from '@/Layouts/AdminLayout.vue';
import { QuillEditor } from '@vueup/vue-quill';
import '@vueup/vue-quill/dist/vue-quill.snow.css';
import { showToast } from '@/Utils/swal';
import axios from 'axios';

const props = defineProps({
    post: Object,
});

const quill = ref(null);

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
    form.post(`/admin/posts/${props.post.id}`, {
        onSuccess: () => {
            showToast('success', 'Post berhasil diperbarui!');
        },
        onError: () => {
             showToast('error', 'Gagal memperbarui post. Periksa inputan anda.');
        }
    });
};

// Custom Image Handler for Quill
const imageHandler = () => {
    const input = document.createElement('input');
    input.setAttribute('type', 'file');
    input.setAttribute('accept', 'image/*');
    input.click();

    input.onchange = async () => {
        const file = input.files[0];
        const formData = new FormData();
        formData.append('image', file);

        try {
            const res = await axios.post('/admin/posts/upload-image', formData, {
                headers: { 'Content-Type': 'multipart/form-data' }
            });
            const url = res.data.url;
            const editor = quill.value.getQuill();
            const range = editor.getSelection();
            editor.insertEmbed(range.index, 'image', url);
        } catch (err) {
            console.error(err);
            showToast('error', 'Gagal mengupload gambar ke editor');
        }
    };
};

onMounted(() => {
    if (quill.value) {
        const editor = quill.value.getQuill();
        editor.getModule('toolbar').addHandler('image', imageHandler);
    }
});
</script>

<style>
/* Override Quill height */
.quill-wrapper .ql-editor {
    min-height: 400px;
    font-size: 1.05rem;
    line-height: 1.6;
    color: #374151;
}
.quill-wrapper .ql-container {
    border-bottom-left-radius: 0.5rem;
    border-bottom-right-radius: 0.5rem;
    border: none;
}
.quill-wrapper .ql-toolbar {
    border-top-left-radius: 0.5rem;
    border-top-right-radius: 0.5rem;
    border: none;
    background-color: #f8fafc;
    border-bottom: 1px solid #e5e7eb;
}

/* Header Card Gradient */
.header-card {
    background: linear-gradient(135deg, #4f46e5 0%, #3b82f6 100%);
    border-radius: 1rem;
}

/* Custom Radio Card */
.custom-radio-card .form-check-input:checked + .form-check-label {
    background-color: #eff6ff;
    border-color: #3b82f6 !important;
    color: #1d4ed8;
}
.custom-radio-card .form-check-label:hover {
    background-color: #f9fafb;
}

/* Image Upload Area */
.image-upload-area {
    border: 2px dashed #cbd5e1;
    background: #f8fafc;
    cursor: pointer;
    transition: all 0.3s ease;
    overflow: hidden;
    position: relative;
    min-height: 250px;
    display: flex;
    flex-direction: column;
    justify-content: center;
}
.image-upload-area:hover {
    border-color: #3b82f6;
    background: #eff6ff;
}
.preview-container {
    position: relative;
    width: 100%;
    height: 100%;
}
.preview-container .overlay {
    position: absolute;
    top: 0; left: 0; right: 0; bottom: 0;
    background: rgba(0,0,0,0.4);
    display: flex;
    align-items: center;
    justify-content: center;
    opacity: 0;
    transition: opacity 0.3s;
    backdrop-filter: blur(2px);
}
.preview-container:hover .overlay {
    opacity: 1;
}
.focus-ring-none:focus {
    box-shadow: none;
    border-color: #e5e7eb;
}
</style>

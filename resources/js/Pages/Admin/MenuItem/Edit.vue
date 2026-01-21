<template>
    <AdminLayout>
        <div class="content container-fluid">
            <!-- Radiant Header -->
            <div class="card border-0 rounded-4 mb-4 overflow-hidden" style="background: linear-gradient(135deg, #4361ee 0%, #3a0ca3 100%);">
                <div class="card-body p-4 position-relative">
                    <div class="d-flex align-items-center gap-4 position-relative z-1">
                        <div class="icon-box bg-white bg-opacity-25 rounded-4 p-3 d-flex align-items-center justify-content-center backdrop-blur" style="width: 64px; height: 64px;">
                            <i class="fas fa-file-alt fa-2x text-white"></i>
                        </div>
                        <div>
                            <h3 class="fw-bold mb-1 text-white">Edit Konten Halaman</h3>
                            <p class="mb-0 text-white-50">Kelola konten, teks, gambar, dan layout halaman ini.</p>
                        </div>
                    </div>
                    <!-- Decorative Circle -->
                    <div class="position-absolute top-0 end-0 p-3 opacity-10 pointer-events-none">
                        <i class="fas fa-layer-group fa-10x text-white transform-rotate-12" style="margin-right: -40px; margin-top: -40px;"></i>
                    </div>
                </div>
            </div>

            <!-- Breadcrumb & Save Action -->
            <div class="bg-white border-0 rounded-4 shadow-sm py-3 px-4 mb-4 d-flex justify-content-between align-items-center w-100">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><Link href="/admin/halaman" class="text-decoration-none fw-bold"><i class="fas fa-layer-group me-1"></i> Halaman</Link></li>
                    <li class="breadcrumb-item">
                        <Link :href="`/admin/halaman/${props.item.menu_navbar.id}/mega-menu`" class="text-decoration-none fw-bold text-muted hover-primary">{{ props.item.menu_navbar.nama }}</Link>
                    </li>
                    <li class="breadcrumb-item active text-primary fw-bold" aria-current="page">{{ form.nama || 'Untitled' }}</li>
                </ol>
                <div class="d-flex gap-2">
                    <button class="btn btn-primary rounded-pill px-4 fw-bold shadow-sm" @click="saveContent" :disabled="form.processing">
                        <span v-if="form.processing"><i class="fas fa-spinner fa-spin me-1"></i> Menyimpan...</span>
                        <span v-else><i class="fas fa-save me-1"></i> Simpan Konten</span>
                    </button>
                </div>
            </div>

            <!-- Content -->
            <div class="row justify-content-center">
                <div class="col-lg-12">
                    <div class="card border-0 shadow-sm rounded-4">
                        <div class="card-body p-4">
                            <!-- Judul Menu -->
                            <div class="mb-4">
                                <label class="form-label fw-bold text-muted small text-uppercase">Judul Halaman / Nama Menu</label>
                                <div class="input-group shadow-sm rounded-3 overflow-hidden">
                                    <span class="input-group-text bg-light border-0"><i class="fas fa-heading text-primary"></i></span>
                                    <input 
                                        type="text" 
                                        class="form-control border-0 fw-bold text-dark fs-5" 
                                        v-model="form.nama" 
                                        placeholder="Contoh: Sejarah Singkat"
                                    >
                                </div>
                                <div class="form-text text-muted mt-2 d-flex align-items-center">
                                    <i class="fas fa-link me-1"></i> Slug: 
                                    <span v-if="form.nama" class="ms-1 badge bg-light text-secondary border">
                                        /profil/{{ generateSlug(form.nama) }}
                                    </span>
                                </div>
                            </div>

                            <!-- Editor -->
                            <div class="mb-3">
                                <label class="form-label fw-bold text-muted small text-uppercase d-flex justify-content-between align-items-center">
                                    <span>Konten Utama</span>
                                    <span class="badge bg-white text-primary border shadow-sm fw-bold small px-2 py-1 rounded-pill"><i class="fas fa-pen-nib me-1"></i> CKEditor Active</span>
                                </label>
                                <div class="editor-wrapper border-0 shadow-sm rounded-4 overflow-hidden">
                                    <ckeditor :editor="editor" v-model="form.content" :config="editorConfig"></ckeditor>
                                </div>
                            </div>

                        </div>
                    </div>

                </div>
            </div>
        </div>
    </AdminLayout>
</template>

<script setup>
import { useForm, Link } from '@inertiajs/vue3';
import AdminLayout from '@/Layouts/AdminLayout.vue';
import ClassicEditor from '@ckeditor/ckeditor5-build-classic';
import { showSuccess, showError } from '@/Utils/notifications';
import { getCurrentInstance } from 'vue';

const props = defineProps({
    item: Object
});

const form = useForm({
    nama: props.item.nama || '',
    content: props.item.content || ''
});

// Helper untuk preview slug sederhana (client side only visualization)
const generateSlug = (text) => {
    if (!text) return '';
    return text.toString().toLowerCase()
        .replace(/\s+/g, '-')           // Replace spaces with -
        .replace(/[^\w\-]+/g, '')       // Remove all non-word chars
        .replace(/\-\-+/g, '-')         // Replace multiple - with single -
        .replace(/^-+/, '')             // Trim - from start of text
        .replace(/-+$/, '');            // Trim - from end of text
};

const editor = ClassicEditor;
const editorConfig = {
    toolbar: [
        'heading', '|', 
        'bold', 'italic', 'underline', 'strikethrough', '|',
        'link', 'bulletedList', 'numberedList', 'todoList', '|',
        'outdent', 'indent', '|',
        'imageUpload', 'blockQuote', 'insertTable', 'mediaEmbed', '|',
        'undo', 'redo'
    ],
    placeholder: 'Mulai menulis konten di sini...\nAnda menyisipkan gambar dengan Copy-Paste (Ctrl+V) langsung ke editor.',
    extraPlugins: [MyCustomUploadAdapterPlugin],
    language: 'id' // Optional if supported
};

// --- Custom Upload Adapter (Base64) ---
class MyUploadAdapter {
    constructor(loader) {
        this.loader = loader;
    }
    upload() {
        return this.loader.file
            .then(file => new Promise((resolve, reject) => {
                const reader = new FileReader();
                reader.onload = () => {
                    resolve({ default: reader.result });
                };
                reader.onerror = error => reject(error);
                reader.readAsDataURL(file);
            }));
    }
    abort() {}
}

function MyCustomUploadAdapterPlugin(editor) {
    editor.plugins.get('FileRepository').createUploadAdapter = (loader) => {
        return new MyUploadAdapter(loader);
    };
}
// --------------------------------------

const saveContent = () => {
    form.put(`/admin/halaman/items/${props.item.id}/content`, {
        preserveScroll: true,
        onSuccess: () => {
            showSuccess('Konten berhasil disimpan!');
        },
        onError: () => {
            showError('Gagal menyimpan konten. Periksa inputan Anda.');
        }
    });
};
</script>

<style>
/* CKEditor Custom Styling */
.ck-editor__editable {
    min-height: 500px;
    padding: 2rem !important;
    font-size: 1rem;
    line-height: 1.7;
    background-color: #ffffff;
    border-bottom-left-radius: 1rem !important;
    border-bottom-right-radius: 1rem !important;
}
.ck-toolbar {
    border-top-left-radius: 1rem !important;
    border-top-right-radius: 1rem !important;
    background-color: #f8f9fa !important;
    border: none !important;
    border-bottom: 1px solid #e9ecef !important;
    padding: 0.75rem !important;
}

.backdrop-blur {
    backdrop-filter: blur(8px);
    -webkit-backdrop-filter: blur(8px);
}
.text-white-50 {
    color: rgba(255, 255, 255, 0.75) !important;
}
.hover-primary:hover {
    color: var(--bs-primary) !important;
}
</style>

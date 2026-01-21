<script setup>
import { Head, Link, useForm } from '@inertiajs/vue3';
import AdminLayout from '@/Layouts/AdminLayout.vue';
import Swal from 'sweetalert2';
import { ref, onMounted } from 'vue';

const props = defineProps({
    section: Object,
});

const content = props.section?.content || {};

const form = useForm({
    tagline: content.tagline || 'Guru & Tendik',
    title: content.title || 'Kenali Tim <span>Pengajar & Tendik</span>',
    description: content.description || '',
    items: content.items?.length === 4 ? content.items : [
        { nama: '', jabatan: '', foto: '', facebook: '', instagram: '', twitter: '' },
        { nama: '', jabatan: '', foto: '', facebook: '', instagram: '', twitter: '' },
        { nama: '', jabatan: '', foto: '', facebook: '', instagram: '', twitter: '' },
        { nama: '', jabatan: '', foto: '', facebook: '', instagram: '', twitter: '' },
    ],
    foto_0: null,
    foto_1: null,
    foto_2: null,
    foto_3: null,
});

const photoPreviews = ref([
    content.items?.[0]?.foto || '/assets/img/team/01.jpg',
    content.items?.[1]?.foto || '/assets/img/team/01.jpg',
    content.items?.[2]?.foto || '/assets/img/team/01.jpg',
    content.items?.[3]?.foto || '/assets/img/team/01.jpg',
]);

const titleEditor = ref(null);

onMounted(() => {
    if (titleEditor.value) {
        titleEditor.value.innerHTML = form.title;
    }
});

const updateTitle = () => {
    if (titleEditor.value) {
        form.title = titleEditor.value.innerHTML;
    }
};

const warnaiTeks = () => {
    const selection = window.getSelection();
    if (selection.rangeCount > 0 && selection.toString().trim() !== '') {
        const range = selection.getRangeAt(0);
        const span = document.createElement('span');
        span.appendChild(range.extractContents());
        range.insertNode(span);
        selection.removeAllRanges();
        updateTitle();
    } else {
        Swal.fire('Info', 'Block/select teks dulu yang mau diwarnai', 'info');
    }
};

const hapusWarna = () => {
    if (titleEditor.value) {
        titleEditor.value.innerHTML = titleEditor.value.innerText;
        updateTitle();
    }
};

const handlePhotoChange = (index, event) => {
    const file = event.target.files[0];
    if (file) {
        form[`foto_${index}`] = file;
        photoPreviews.value[index] = URL.createObjectURL(file);
    }
};

const Toast = Swal.mixin({
    toast: true,
    position: 'top-end',
    showConfirmButton: false,
    timer: 3000,
    timerProgressBar: true,
    didOpen: (toast) => {
        toast.addEventListener('mouseenter', Swal.stopTimer)
        toast.addEventListener('mouseleave', Swal.resumeTimer)
    }
});

const submit = () => {
    updateTitle();
    form.post('/admin/team', {
        forceFormData: true,
        onSuccess: () => {
            Toast.fire({
                icon: 'success',
                title: 'Berhasil',
                text: 'Section Guru & Tendik berhasil diperbarui'
            });
        },
        onError: () => {
            Toast.fire({
                icon: 'error',
                title: 'Gagal',
                text: 'Periksa kembali inputan anda.'
            });
        },
    });
};
</script>

<template>
    <Head title="Edit Guru & Tendik" />
    
    <AdminLayout>
        <div class="content container-fluid">
            <!-- Radiant Header -->
            <div class="card border-0 rounded-4 mb-4 overflow-hidden" style="background: linear-gradient(135deg, #4361ee 0%, #3a0ca3 100%);">
                <div class="card-body p-4 position-relative">
                    <div class="d-flex align-items-center gap-4 position-relative z-1">
                        <div class="icon-box bg-white bg-opacity-25 rounded-4 p-3 d-flex align-items-center justify-content-center backdrop-blur" style="width: 64px; height: 64px;">
                            <i class="fas fa-users fa-2x text-white"></i>
                        </div>
                        <div>
                            <h3 class="fw-bold mb-1 text-white">Guru & Tendik</h3>
                            <p class="mb-0 text-white-50">Kelola tampilan tim pengajar dan tenaga kependidikan</p>
                        </div>
                    </div>
                    <!-- Decorative Circle -->
                    <div class="position-absolute top-0 end-0 p-3 opacity-10 pointer-events-none">
                        <i class="fas fa-users fa-10x text-white transform-rotate-12" style="margin-right: -40px; margin-top: -40px;"></i>
                    </div>
                </div>
            </div>

            <!-- Breadcrumb & Action -->
            <div class="row align-items-center mb-4">
                <div class="col">
                     <nav aria-label="breadcrumb">
                        <ol class="breadcrumb alert alert-primary border-0 rounded-4 shadow-sm py-3 px-4 mb-0 align-items-center justify-content-between">
                            <div class="d-flex align-items-center">
                                <li class="breadcrumb-item"><a href="/admin/halaman" class="text-decoration-none fw-bold"><i class="fas fa-layer-group me-1"></i> Halaman</a></li>
                                <li class="breadcrumb-item"><a href="/admin/halaman/beranda" class="text-decoration-none text-muted">Beranda</a></li>
                                <li class="breadcrumb-item active text-primary fw-bold" aria-current="page">Guru & Tendik</li>
                            </div>

                            <button class="btn btn-primary rounded-pill shadow-sm px-4 fw-bold" @click="submit" :disabled="form.processing">
                                <i class="fas fa-save me-2"></i> {{ form.processing ? 'Menyimpan...' : 'Simpan Perubahan' }}
                            </button>
                        </ol>
                    </nav>
                </div>
            </div>

            <!-- Header Section Card -->
            <div class="card border-0 shadow-sm rounded-4 mb-4 section-card">
                 <div class="card-header bg-white border-0 py-4 px-4">
                    <h5 class="fw-bold text-dark mb-0"><i class="fas fa-heading text-primary me-2"></i>Header Section</h5>
                </div>
                <div class="card-body p-4 pt-0">
                    <div class="row g-3">
                        <div class="col-md-4">
                            <label class="form-label fw-bold text-dark">Tagline</label>
                            <div class="input-group shadow-sm rounded-4 overflow-hidden">
                                <span class="input-group-text bg-light border-0 ps-3 text-primary"><i class="fas fa-tag"></i></span>
                                <input type="text" class="form-control border-0 bg-light" :class="{'is-invalid': form.errors.tagline}" v-model="form.tagline" placeholder="Guru & Tendik">
                            </div>
                            <div v-if="form.errors.tagline" class="invalid-feedback d-block fw-bold mt-1">{{ form.errors.tagline }}</div>
                        </div>
                        <div class="col-md-8">
                            <label class="form-label fw-bold text-dark">Judul Section</label>
                            <div class="d-flex gap-2 mb-2">
                                <button type="button" class="btn btn-warning btn-sm text-white rounded-pill shadow-sm fw-bold" style="background: #FDA31B; border-color: #FDA31B;" @click="warnaiTeks">
                                    <i class="fas fa-highlighter me-1"></i>Highlight
                                </button>
                                <button type="button" class="btn btn-outline-secondary btn-sm rounded-pill" @click="hapusWarna">
                                    <i class="fas fa-eraser me-1"></i>Reset
                                </button>
                            </div>
                            <div 
                                ref="titleEditor"
                                contenteditable="true"
                                class="form-control title-editor bg-light border-0 shadow-sm rounded-4 p-3"
                                :class="{'is-invalid': form.errors.title}"
                                @input="updateTitle"
                                @blur="updateTitle"
                            ></div>
                            <div v-if="form.errors.title" class="invalid-feedback d-block fw-bold mt-1">{{ form.errors.title }}</div>
                        </div>
                        <div class="col-12">
                            <label class="form-label fw-bold text-dark">Deskripsi</label>
                             <div class="input-group shadow-sm rounded-4 overflow-hidden">
                                <span class="input-group-text bg-light border-0 ps-3 pt-3 align-items-start text-secondary"><i class="fas fa-align-left"></i></span>
                                <textarea class="form-control border-0 bg-light" :class="{'is-invalid': form.errors.description}" v-model="form.description" rows="2" placeholder="Deskripsi..."></textarea>
                            </div>
                            <div v-if="form.errors.description" class="invalid-feedback d-block fw-bold mt-1">{{ form.errors.description }}</div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Team Members - 2 per row -->
            <div class="row g-4">
                <div v-for="(item, index) in form.items" :key="index" class="col-md-6">
                    <div class="card border-0 shadow-sm rounded-4 h-100 section-card">
                        <div class="card-header bg-white border-0 py-4 px-4 d-flex align-items-center gap-3">
                            <div class="team-number rounded-circle d-flex align-items-center justify-content-center text-white fw-bold shadow-sm" style="width: 32px; height: 32px; background: linear-gradient(135deg, #4361ee, #3a0ca3);">
                                {{ index + 1 }}
                            </div>
                            <h6 class="fw-bold text-dark mb-0">Anggota Tim {{ index + 1 }}</h6>
                        </div>
                        <div class="card-body p-4 pt-0">
                            <div class="row g-4">
                                <!-- Left: Photo Upload -->
                                <div class="col-4">
                                    <div class="team-photo-wrapper position-relative rounded-4 overflow-hidden shadow-sm aspect-ratio-1" style="aspect-ratio: 1;">
                                        <img :src="photoPreviews[index]" alt="Foto" class="team-photo w-100 h-100 object-fit-cover">
                                        <label class="upload-overlay position-absolute bottom-0 start-0 w-100 p-2 text-white text-center cursor-pointer transition-all" style="background: rgba(0,0,0,0.6);" :for="`foto_${index}`">
                                            <i class="fas fa-camera mb-1"></i>
                                            <span class="d-block small">Ganti</span>
                                        </label>
                                        <input 
                                            type="file" 
                                            :id="`foto_${index}`" 
                                            class="d-none" 
                                            accept="image/*" 
                                            @change="handlePhotoChange(index, $event)"
                                        >
                                    </div>
                                </div>
                                <!-- Right: Info -->
                                <div class="col-8">
                                    <div class="mb-3">
                                        <label class="form-label small fw-bold text-dark mb-1">Nama Lengkap</label>
                                        <div class="input-group input-group-sm shadow-sm rounded-3 overflow-hidden">
                                            <span class="input-group-text bg-light border-0 ps-3 text-primary"><i class="fas fa-user"></i></span>
                                            <input type="text" class="form-control border-0 bg-light" :class="{'is-invalid': form.errors[`items.${index}.nama`]}" v-model="item.nama" placeholder="Nama...">
                                        </div>
                                        <div v-if="form.errors[`items.${index}.nama`]" class="invalid-feedback d-block fw-bold mt-1">{{ form.errors[`items.${index}.nama`] }}</div>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label small fw-bold text-dark mb-1">Jabatan</label>
                                        <div class="input-group input-group-sm shadow-sm rounded-3 overflow-hidden">
                                            <span class="input-group-text bg-light border-0 ps-3 text-info"><i class="fas fa-briefcase"></i></span>
                                            <input type="text" class="form-control border-0 bg-light" :class="{'is-invalid': form.errors[`items.${index}.jabatan`]}" v-model="item.jabatan" placeholder="Jabatan...">
                                        </div>
                                        <div v-if="form.errors[`items.${index}.jabatan`]" class="invalid-feedback d-block fw-bold mt-1">{{ form.errors[`items.${index}.jabatan`] }}</div>
                                    </div>
                                </div>
                                <!-- Social Media - Full Width -->
                                <div class="col-12">
                                    <label class="form-label small fw-bold text-dark mb-2">Sosial Media (Opsional)</label>
                                    <div class="row g-2">
                                        <div class="col-4">
                                            <div class="input-group input-group-sm shadow-sm rounded-3 overflow-hidden">
                                                <span class="input-group-text bg-light border-0 ps-2 text-primary" style="width: 32px; justify-content: center;"><i class="fab fa-facebook-f"></i></span>
                                                <input type="text" class="form-control border-0 bg-light px-2" v-model="item.facebook" placeholder="URL">
                                            </div>
                                        </div>
                                        <div class="col-4">
                                            <div class="input-group input-group-sm shadow-sm rounded-3 overflow-hidden">
                                                <span class="input-group-text bg-light border-0 ps-2 text-danger" style="width: 32px; justify-content: center;"><i class="fab fa-instagram"></i></span>
                                                <input type="text" class="form-control border-0 bg-light px-2" v-model="item.instagram" placeholder="URL">
                                            </div>
                                        </div>
                                        <div class="col-4">
                                            <div class="input-group input-group-sm shadow-sm rounded-3 overflow-hidden">
                                                <span class="input-group-text bg-light border-0 ps-2 text-info" style="width: 32px; justify-content: center;"><i class="fab fa-twitter"></i></span>
                                                <input type="text" class="form-control border-0 bg-light px-2" v-model="item.twitter" placeholder="URL">
                                            </div>
                                        </div>
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
.title-editor {
    min-height: 50px;
    font-size: 1.1rem;
    line-height: 1.5;
}
.title-editor:focus {
    outline: none;
    background: #fff;
    border-color: #fda31b;
}
.title-editor :deep(span) {
    color: #FDA31B;
    font-weight: 700;
}
.upload-overlay:hover {
    background: rgba(67, 97, 238, 0.9) !important; /* Primary color hover */
}
.backdrop-blur {
    backdrop-filter: blur(8px);
    -webkit-backdrop-filter: blur(8px);
}
.transform-rotate-12 {
    transform: rotate(12deg);
}
.text-white-50 {
    color: rgba(255, 255, 255, 0.75) !important;
}
.section-card {
    transition: transform 0.3s;
}
.section-card:hover {
    transform: translateY(-5px);
}
.cursor-pointer {
    cursor: pointer;
}
.transition-all {
    transition: all 0.2s ease;
}
</style>



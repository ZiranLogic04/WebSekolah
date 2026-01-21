<template>
    <AdminLayout>
        <div class="content container-fluid">
            <!-- Page Header -->
            <div class="page-header">
                <div class="row align-items-center">
                    <div class="col">
                        <h3 class="page-title">{{ title }}</h3>
                        <ul class="breadcrumb">
                            <li class="breadcrumb-item"><Link href="/admin/pages">Halaman Khusus</Link></li>
                            <li class="breadcrumb-item active">Edit</li>
                        </ul>
                    </div>
                    <div class="col-auto">
                        <Link href="/admin/pages" class="btn btn-outline-secondary me-2">
                            <i class="fas fa-arrow-left me-1"></i> Kembali
                        </Link>
                        <button class="btn btn-primary" @click="submit" :disabled="form.processing">
                            <i class="fas fa-save me-1"></i> 
                            <span v-if="form.processing">Menyimpan...</span>
                            <span v-else>Simpan Perubahan</span>
                        </button>
                    </div>
                </div>
            </div>

            <!-- Content -->
            <div class="row justify-content-center">
                <div class="col-lg-12">
                    
                    <!-- Info Alert -->
                    <div class="alert alert-info border-0 shadow-sm mb-4">
                        <div class="d-flex align-items-center">
                            <div class="fs-1 me-3 text-info">
                                <i class="fas fa-info-circle"></i>
                            </div>
                            <div>
                                <h5 class="alert-heading mb-1">Panduan Pengisian</h5>
                                <p class="mb-0 text-muted">Isi formulir di bawah ini untuk memperbarui konten halaman <strong>{{ title }}</strong>. Pastikan data yang dimasukkan sudah benar sebelum menyimpan.</p>
                            </div>
                        </div>
                    </div>

                    <form @submit.prevent="submit">
                        
                        <!-- Form Groups -->
                        <div class="form-groups-container">
                            <div v-for="(group, gIndex) in fieldGroups" :key="gIndex" class="card border-0 shadow-sm mb-4">
                                <div class="card-header bg-transparent border-bottom py-3">
                                    <h5 class="mb-0 fw-bold text-primary"><i class="fas fa-layer-group me-2"></i>{{ group.title || 'Informasi Umum' }}</h5>
                                    <p v-if="group.description" class="mb-0 text-muted small mt-1">{{ group.description }}</p>
                                </div>
                                
                                <div class="card-body">
                                    
                                    <!-- Layout with Image -->
                                    <div class="row" v-if="group.fields.some(f => f.type === 'image')">
                                        <!-- Image Column -->
                                        <div class="col-md-4">
                                            <div v-for="(field, fIndex) in group.fields.filter(f => f.type === 'image')" :key="'img'+fIndex" class="image-upload-box mb-3">
                                                <label class="form-label fw-bold small text-uppercase text-muted">{{ field.label }}</label>
                                                <div class="image-preview border rounded-3 p-1 bg-light text-center position-relative">
                                                    <img v-if="previews[field.name] || (section.content && section.content[field.name])" 
                                                         :src="previews[field.name] || `/storage/${section.content[field.name]}`"
                                                         class="img-fluid rounded shadow-sm" style="max-height: 200px; object-fit: cover;">
                                                    <div v-else class="py-5 text-muted opacity-50">
                                                        <i class="fas fa-cloud-upload-alt fs-1 mb-2"></i>
                                                        <div class="small">Upload Gambar</div>
                                                    </div>
                                                </div>
                                                <input type="file" class="form-control mt-2" @change="handleImageUpload($event, field.name)" accept="image/*">
                                            </div>
                                        </div>

                                        <!-- Text Fields Column -->
                                        <div class="col-md-8">
                                            <div v-for="(field, fIndex) in group.fields.filter(f => f.type !== 'image')" :key="'txt'+fIndex">
                                                <FieldRenderer :field="field" :form="form" />
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Standard Layout (No Image) -->
                                    <div v-else>
                                        <div v-for="(field, fIndex) in group.fields" :key="fIndex">
                                            <FieldRenderer :field="field" :form="form" />
                                        </div>
                                    </div>

                                </div>
                            </div>
                        </div>
                        
                    </form>
                </div>
            </div>
        </div>
    </AdminLayout>
</template>

<script setup>
import { ref, computed } from 'vue';
import { Link, useForm } from '@inertiajs/vue3';
import AdminLayout from '@/Layouts/AdminLayout.vue';
import { QuillEditor } from '@vueup/vue-quill';
import '@vueup/vue-quill/dist/vue-quill.snow.css';
import { showSuccess, showError } from '@/Utils/notifications';
import Swal from 'sweetalert2';

// -- Sub-component for rendering fields to clean up template --
import { defineComponent, h } from 'vue';

const FieldRenderer = defineComponent({
    props: ['field', 'form'],
    components: { QuillEditor },
    setup(props) {
        return () => {
            const { field, form } = props;
            
            // Input Text / Number
            if (['text', 'number'].includes(field.type)) {
                return h('div', { class: 'mb-4' }, [
                    h('label', { class: 'form-label fw-bold' }, field.label),
                    h('input', {
                        type: field.type,
                        class: 'form-control',
                        placeholder: field.label,
                        value: form[field.name],
                        onInput: (e) => form[field.name] = e.target.value
                    })
                ]);
            }

            // Textarea
            if (field.type === 'textarea') {
                return h('div', { class: 'mb-4' }, [
                    h('label', { class: 'form-label fw-bold' }, field.label),
                    h('textarea', {
                        class: 'form-control',
                        rows: 3,
                        placeholder: field.label,
                        value: form[field.name],
                        onInput: (e) => form[field.name] = e.target.value
                    })
                ]);
            }

            // Rich Text
            if (field.type === 'richtext') {
                return h('div', { class: 'mb-4' }, [
                    h('label', { class: 'form-label fw-bold' }, field.label),
                    h('div', { class: 'editor-wrapper border rounded overflow-hidden', style: 'background: white;' }, [
                        h(QuillEditor, {
                            theme: 'snow',
                            contentType: 'html',
                            toolbar: 'minimal',
                            content: form[field.name],
                            'onUpdate:content': (val) => form[field.name] = val
                        })
                    ])
                ]);
            }

            return null;
        }
    }
});
// -----------------------------------------------------------

const props = defineProps({
    section: Object,
    schema: Array,
    title: String
});

const fieldGroups = computed(() => {
    const groups = [];
    let currentGroup = { title: null, description: null, fields: [] };

    props.schema.forEach(field => {
        if (field.group_title) {
            if (currentGroup.fields.length > 0) {
                groups.push(currentGroup);
            }
            currentGroup = { 
                title: field.group_title, 
                description: field.group_description || null,
                fields: [] 
            };
        }
        currentGroup.fields.push(field);
    });
    
    if (currentGroup.fields.length > 0) {
        groups.push(currentGroup);
    }

    if (groups.length === 0 && props.schema.length > 0) {
        groups.push({ title: 'Konten Halaman', description: 'Silakan edit konten di bawah ini.', fields: props.schema });
    }

    return groups;
});

const initialData = {};
props.schema.forEach(field => {
    initialData[field.name] = props.section.content?.[field.name] || '';
});
initialData['_method'] = 'PUT';

const form = useForm(initialData);
const previews = ref({});

const handleImageUpload = (event, fieldName) => {
    const file = event.target.files[0];
    if (file) {
        form[fieldName] = file;
        const reader = new FileReader();
        reader.onload = (e) => {
            previews.value[fieldName] = e.target.result;
        };
        reader.readAsDataURL(file);
    }
};

const submit = () => {
    form.post(`/admin/pages/${props.section.key}`, {
        preserveScroll: true,
        forceFormData: true,
        onSuccess: () => {
            Swal.fire({
                icon: 'success',
                title: 'Berhasil!',
                text: 'Data berhasil disimpan!',
                confirmButtonColor: '#4361ee',
                timer: 2000,
                timerProgressBar: true
            });
        },
        onError: () => {
            showError('Gagal menyimpan data.');
        }
    });
};
</script>

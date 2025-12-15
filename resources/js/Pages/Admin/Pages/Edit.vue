<template>
    <AdminLayout>
        <div class="content container-fluid">
            <!-- Page Header -->
            <div class="page-header">
                <div class="row align-items-center">
                    <div class="col">
                        <h3 class="page-title">Edit: {{ title }}</h3>
                        <ul class="breadcrumb">
                            <li class="breadcrumb-item"><Link href="/dashboard">Dashboard</Link></li>
                            <li class="breadcrumb-item"><Link href="/admin/pages">Halaman</Link></li>
                            <li class="breadcrumb-item active">Edit</li>
                        </ul>
                    </div>
                </div>
            </div>

            <div class="row justify-content-center">
                <div class="col-md-9">
                    <form @submit.prevent="submit">
                        
                        <div class="form-groups-container">
                            <div v-for="(group, gIndex) in fieldGroups" :key="gIndex" class="card border-0 shadow-sm mb-4">
                                <div class="card-header bg-white py-3 border-bottom-0">
                                    <div class="d-flex align-items-center">
                                        <i class="fas fa-layer-group me-3 text-primary fs-5"></i>
                                        <div>
                                            <h5 class="mb-0 fw-bold text-dark">{{ group.title || 'Informasi Umum' }}</h5>
                                            <p v-if="group.description" class="text-muted small mb-0 mt-1">{{ group.description }}</p>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="card-body p-4 pt-0">
                                    <hr class="mt-0 mb-4 text-muted opacity-25">
                                    
                                    <!-- Auto Split Layout (If Image Exists) -->
                                    <div class="row" v-if="group.fields.some(f => f.type === 'image')">
                                        <!-- Col Left: Image -->
                                        <div class="col-md-4 mb-4">
                                            <div v-for="(field, fIndex) in group.fields.filter(f => f.type === 'image')" :key="'img'+fIndex" class="mb-3">
                                                <div class="card border-0 bg-light h-100">
                                                    <div class="card-body p-3 text-center d-flex flex-column justify-content-center">
                                                        <label class="small text-uppercase fw-bold text-muted mb-3">{{ field.label }}</label>
                                                        <!-- Preview Larger -->
                                                        <div class="mb-3 bg-white shadow-sm rounded-3 overflow-hidden position-relative mx-auto" style="width: 100%; padding-top: 56.25%;">
                                                            <div class="position-absolute top-0 start-0 w-100 h-100">
                                                                <img v-if="previews[field.name] || (section.content && section.content[field.name])" 
                                                                     :src="previews[field.name] || `/storage/${section.content[field.name]}`" 
                                                                     class="w-100 h-100 object-fit-cover">
                                                                <div v-else class="w-100 h-100 d-flex align-items-center justify-content-center text-muted bg-secondary bg-opacity-10">
                                                                    <div class="text-center">
                                                                        <i class="fas fa-cloud-upload-alt fa-2x mb-2 opacity-50"></i>
                                                                        <p class="small mb-0 fst-italic">No Image</p>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <!-- Input -->
                                                        <input type="file" class="form-control form-control-sm" @change="handleImageUpload($event, field.name)" accept="image/*">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Col Right: Text Inputs -->
                                        <div class="col-md-8">
                                            <div class="row">
                                                <div class="col-12" v-for="(field, fIndex) in group.fields.filter(f => f.type !== 'image')" :key="'txt'+fIndex">
                                                    
                                                    <!-- Modern Floating Input -->
                                                    <div class="form-floating mb-3" v-if="['text', 'number'].includes(field.type)">
                                                        <input :type="field.type" class="form-control" :id="field.name" :placeholder="field.label" v-model="form[field.name]">
                                                        <label :for="field.name">{{ field.label }}</label>
                                                    </div>

                                                    <!-- Modern Floating Textarea -->
                                                    <div class="form-floating mb-3" v-if="field.type === 'textarea'">
                                                        <textarea class="form-control" :id="field.name" :placeholder="field.label" 
                                                                  v-model="form[field.name]" style="height: 100px"></textarea>
                                                        <label :for="field.name">{{ field.label }}</label>
                                                    </div>

                                                    <!-- Visual Icon Picker (Compact) -->
                                                    <div class="mb-4" v-if="field.type === 'icon_picker'">
                                                        <label class="form-label small text-uppercase fw-bold text-muted mb-2">{{ field.label }}</label>
                                                        <div class="row g-1">
                                                            <div class="col-3 col-lg-2" v-for="(label, value) in field.options" :key="value">
                                                                <div 
                                                                    class="card h-100 cursor-pointer icon-card border-1" 
                                                                    :class="{'border-primary bg-primary bg-opacity-10 shadow-sm': form[field.name] === value}"
                                                                    @click="form[field.name] = value"
                                                                    style="cursor: pointer; transition: all 0.2s; min-height: 60px;"
                                                                    :title="label"
                                                                >
                                                                    <div class="card-body text-center p-1 d-flex flex-column align-items-center justify-content-center">
                                                                        <i :class="value" class="mb-1" :style="{ color: form[field.name] === value ? '#0d6efd' : '#6c757d', fontSize: '24px' }"></i>
                                                                        <div style="font-size: 8px; line-height: 1; overflow: hidden; white-space: nowrap; text-overflow: ellipsis; max-width: 100%;" class="text-muted">{{ label }}</div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>

                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Standard Stacked Layout (No Image) -->
                                    <div class="row" v-else>
                                        <div class="col-12 mb-4" v-for="(field, fIndex) in group.fields" :key="fIndex">
                                            
                                            <!-- Modern Floating Input -->
                                            <div class="form-floating mb-3" v-if="['text', 'number'].includes(field.type)">
                                                <input :type="field.type" class="form-control" :id="field.name" :placeholder="field.label" v-model="form[field.name]">
                                                <label :for="field.name">{{ field.label }}</label>
                                            </div>

                                            <!-- Modern Floating Textarea -->
                                            <div class="form-floating mb-3" v-if="field.type === 'textarea'">
                                                <textarea class="form-control" :id="field.name" :placeholder="field.label" 
                                                          v-model="form[field.name]" style="height: 100px"></textarea>
                                                <label :for="field.name">{{ field.label }}</label>
                                            </div>

                                            <!-- Visual Icon Picker (Compact) -->
                                            <div class="mb-4" v-if="field.type === 'icon_picker'">
                                                <label class="form-label small text-uppercase fw-bold text-muted mb-2">{{ field.label }}</label>
                                                <div class="row g-1">
                                                    <div class="col-3 col-sm-2 col-xl-1" v-for="(label, value) in field.options" :key="value">
                                                        <div 
                                                            class="card h-100 cursor-pointer icon-card border-1" 
                                                            :class="{'border-primary bg-primary bg-opacity-10 shadow-sm': form[field.name] === value}"
                                                            @click="form[field.name] = value"
                                                            style="cursor: pointer; transition: all 0.2s; min-height: 60px;"
                                                            :title="label"
                                                        >
                                                            <div class="card-body text-center p-1 d-flex flex-column align-items-center justify-content-center">
                                                                <i :class="value" class="mb-1" :style="{ color: form[field.name] === value ? '#0d6efd' : '#6c757d', fontSize: '24px' }"></i>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- Rich Text (Standard) -->
                                            <div class="mb-4" v-if="field.type === 'richtext'">
                                                <label class="form-label small text-uppercase fw-bold text-muted mb-2">{{ field.label }}</label>
                                                <div class="bg-white rounded-3 shadow-sm border overflow-hidden">
                                                    <QuillEditor theme="snow" v-model:content="form[field.name]" contentType="html" toolbar="minimal" />
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                </div>
                            </div>
                        </div>

                        <!-- Action Bar -->
                        <div class="d-flex justify-content-end mt-4 p-3 bg-white shadow-sm rounded">
                            <Link href="/admin/pages" class="btn btn-light text-muted me-3">Batal</Link>
                            <button type="submit" class="btn btn-primary px-5 fw-bold" :disabled="form.processing">
                                <i class="fas fa-save me-2"></i> Simpan Data
                            </button>
                        </div>
                        
                        <div style="height: 50px;"></div>

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

const props = defineProps({
    section: Object, // Database record
    schema: Array,   // Field definitions
    title: String
});

    // Pre-process schema into groups
const fieldGroups = computed(() => {
    const groups = [];
    let currentGroup = { title: null, description: null, fields: [] };

    props.schema.forEach(field => {
        if (field.group_title) {
            // Push previous group if it has fields
            if (currentGroup.fields.length > 0) {
                groups.push(currentGroup);
            }
            // Start new group
            currentGroup = { 
                title: field.group_title, 
                description: field.group_description || null,
                image: field.group_image || null,
                fields: [] 
            };
        }
        currentGroup.fields.push(field);
    });
    
    // Push the last group
    if (currentGroup.fields.length > 0) {
        groups.push(currentGroup);
    }

    // Specila case: if no groups defined, wrap all in one
    if (groups.length === 0 && props.schema.length > 0) {
        groups.push({ title: 'Konten Halaman', description: 'Silakan edit konten di bawah ini.', fields: props.schema });
    }

    return groups;
});

// Initialize form data from existing content
const initialData = {};
props.schema.forEach(field => {
    initialData[field.name] = props.section.content?.[field.name] || '';
});

// Add method spoofing for PUT
initialData['_method'] = 'PUT';

const form = useForm(initialData);
const previews = ref({});

const handleImageUpload = (event, fieldName) => {
    const file = event.target.files[0];
    if (file) {
        form[fieldName] = file;
        
        // Create local preview
        const reader = new FileReader();
        reader.onload = (e) => {
            previews.value[fieldName] = e.target.result;
        };
        reader.readAsDataURL(file);
    }
};

const submit = () => {
    // Determine route parameter based on section key
    form.post(`/admin/pages/${props.section.key}`, {
        preserveScroll: true,
        forceFormData: true, // Crucial for file uploads with PUT/POST spoofing
    });
};
</script>

<style scoped>
.form-control:focus {
    border-color: #86b7fe;
    box-shadow: 0 0 0 0.25rem rgba(13, 110, 253, 0.25);
}
</style>

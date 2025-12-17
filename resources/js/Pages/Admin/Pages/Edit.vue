<template>
    <AdminLayout>
        <div class="content container-fluid">
            <!-- Page Header -->
            <div class="page-header">
                <div class="row align-items-center">
                    <div class="col">
                        <div class="d-flex align-items-center gap-3">
                            <Link href="/admin/pages" class="back-btn">
                                <i class="fas fa-arrow-left"></i>
                            </Link>
                            <div>
                                <h3 class="page-title mb-1">{{ title }}</h3>
                                <ul class="breadcrumb mb-0">
                                    <li class="breadcrumb-item"><Link href="/admin/dashboard">Dashboard</Link></li>
                                    <li class="breadcrumb-item"><Link href="/admin/pages">Halaman</Link></li>
                                    <li class="breadcrumb-item active">Edit</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row justify-content-center">
                <div class="col-lg-10">
                    <form @submit.prevent="submit">
                        
                        <!-- Form Groups -->
                        <div class="form-groups-container">
                            <div v-for="(group, gIndex) in fieldGroups" :key="gIndex" class="form-card">
                                <div class="form-card-header">
                                    <div class="header-icon">
                                        <i class="fas fa-layer-group"></i>
                                    </div>
                                    <div class="header-text">
                                        <h5>{{ group.title || 'Informasi Umum' }}</h5>
                                        <p v-if="group.description">{{ group.description }}</p>
                                    </div>
                                </div>
                                
                                <div class="form-card-body">
                                    
                                    <!-- Layout with Image -->
                                    <div class="row" v-if="group.fields.some(f => f.type === 'image')">
                                        <!-- Image Column -->
                                        <div class="col-md-4">
                                            <div v-for="(field, fIndex) in group.fields.filter(f => f.type === 'image')" :key="'img'+fIndex" class="image-upload-box">
                                                <label class="field-label">{{ field.label }}</label>
                                                <div class="image-preview">
                                                    <img v-if="previews[field.name] || (section.content && section.content[field.name])" 
                                                         :src="previews[field.name] || `/storage/${section.content[field.name]}`">
                                                    <div v-else class="no-image">
                                                        <i class="fas fa-cloud-upload-alt"></i>
                                                        <span>Upload Gambar</span>
                                                    </div>
                                                </div>
                                                <input type="file" class="file-input" @change="handleImageUpload($event, field.name)" accept="image/*">
                                            </div>
                                        </div>

                                        <!-- Text Fields Column -->
                                        <div class="col-md-8">
                                            <div v-for="(field, fIndex) in group.fields.filter(f => f.type !== 'image')" :key="'txt'+fIndex">
                                                
                                                <!-- Text Input -->
                                                <div class="modern-input" v-if="['text', 'number'].includes(field.type)">
                                                    <input :type="field.type" :id="field.name" placeholder=" " v-model="form[field.name]">
                                                    <label :for="field.name">{{ field.label }}</label>
                                                </div>

                                                <!-- Textarea -->
                                                <div class="modern-input" v-if="field.type === 'textarea'">
                                                    <textarea :id="field.name" placeholder=" " v-model="form[field.name]" rows="3"></textarea>
                                                    <label :for="field.name">{{ field.label }}</label>
                                                </div>

                                                <!-- Icon Picker -->
                                                <div class="icon-picker-box" v-if="field.type === 'icon_picker'">
                                                    <label class="field-label">{{ field.label }}</label>
                                                    <div class="icon-grid">
                                                        <div v-for="(label, value) in field.options" :key="value"
                                                             class="icon-item"
                                                             :class="{ active: form[field.name] === value }"
                                                             @click="form[field.name] = value"
                                                             :title="label">
                                                            <i :class="value"></i>
                                                        </div>
                                                    </div>
                                                </div>

                                                <!-- Rich Text Editor -->
                                                <div class="richtext-box" v-if="field.type === 'richtext'">
                                                    <label class="field-label">{{ field.label }}</label>
                                                    <div class="editor-wrapper">
                                                        <QuillEditor theme="snow" v-model:content="form[field.name]" contentType="html" toolbar="minimal" />
                                                    </div>
                                                </div>

                                            </div>
                                        </div>
                                    </div>

                                    <!-- Standard Layout (No Image) -->
                                    <div v-else>
                                        <div v-for="(field, fIndex) in group.fields" :key="fIndex">
                                            
                                            <!-- Text Input -->
                                            <div class="modern-input" v-if="['text', 'number'].includes(field.type)">
                                                <input :type="field.type" :id="field.name" placeholder=" " v-model="form[field.name]">
                                                <label :for="field.name">{{ field.label }}</label>
                                            </div>

                                            <!-- Textarea -->
                                            <div class="modern-input" v-if="field.type === 'textarea'">
                                                <textarea :id="field.name" placeholder=" " v-model="form[field.name]" rows="3"></textarea>
                                                <label :for="field.name">{{ field.label }}</label>
                                            </div>

                                            <!-- Icon Picker -->
                                            <div class="icon-picker-box" v-if="field.type === 'icon_picker'">
                                                <label class="field-label">{{ field.label }}</label>
                                                <div class="icon-grid">
                                                    <div v-for="(label, value) in field.options" :key="value"
                                                         class="icon-item"
                                                         :class="{ active: form[field.name] === value }"
                                                         @click="form[field.name] = value"
                                                         :title="label">
                                                        <i :class="value"></i>
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- Rich Text Editor -->
                                            <div class="richtext-box" v-if="field.type === 'richtext'">
                                                <label class="field-label">{{ field.label }}</label>
                                                <div class="editor-wrapper">
                                                    <QuillEditor theme="snow" v-model:content="form[field.name]" contentType="html" toolbar="minimal" />
                                                </div>
                                            </div>

                                        </div>
                                    </div>

                                </div>
                            </div>
                        </div>

                        <!-- Action Bar -->
                        <div class="action-bar">
                            <Link href="/admin/pages" class="cancel-btn">
                                <i class="fas fa-times me-2"></i>Batal
                            </Link>
                            <button type="submit" class="save-btn" :disabled="form.processing">
                                <i class="fas fa-save me-2"></i>
                                <span v-if="form.processing">Menyimpan...</span>
                                <span v-else>Simpan Perubahan</span>
                            </button>
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
    });
};
</script>

<style scoped>
/* Back Button */
.back-btn {
    width: 40px;
    height: 40px;
    background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: #495057;
    text-decoration: none;
    transition: all 0.3s ease;
}

.back-btn:hover {
    background: linear-gradient(135deg, #3d5ee1 0%, #5a7cf7 100%);
    color: #fff;
    transform: scale(1.05);
}

/* Form Card */
.form-card {
    background: #fff;
    border-radius: 20px;
    box-shadow: 0 4px 25px rgba(0, 0, 0, 0.08);
    margin-bottom: 24px;
    overflow: hidden;
}

.form-card-header {
    display: flex;
    align-items: center;
    gap: 16px;
    padding: 24px;
    background: linear-gradient(135deg, #f8f9fa 0%, #fff 100%);
    border-bottom: 1px solid #eef2f7;
}

.header-icon {
    width: 50px;
    height: 50px;
    background: linear-gradient(135deg, #3d5ee1 0%, #5a7cf7 100%);
    border-radius: 14px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: #fff;
    font-size: 20px;
}

.header-text h5 {
    font-size: 18px;
    font-weight: 700;
    color: #1a1a2e;
    margin: 0;
}

.header-text p {
    font-size: 13px;
    color: #6c757d;
    margin: 4px 0 0 0;
}

.form-card-body {
    padding: 24px;
}

/* Modern Input */
.modern-input {
    position: relative;
    margin-bottom: 24px;
}

.modern-input input,
.modern-input textarea {
    width: 100%;
    padding: 18px 16px 8px;
    font-size: 15px;
    border: 2px solid #e9ecef;
    border-radius: 12px;
    background: #f8f9fa;
    transition: all 0.3s ease;
    outline: none;
}

.modern-input textarea {
    resize: none;
    min-height: 100px;
}

.modern-input input:focus,
.modern-input textarea:focus {
    border-color: #3d5ee1;
    background: #fff;
    box-shadow: 0 0 0 4px rgba(61, 94, 225, 0.1);
}

.modern-input label {
    position: absolute;
    left: 16px;
    top: 50%;
    transform: translateY(-50%);
    font-size: 15px;
    color: #6c757d;
    pointer-events: none;
    transition: all 0.3s ease;
    background: transparent;
    padding: 0 4px;
}

.modern-input textarea + label {
    top: 20px;
    transform: none;
}

.modern-input input:focus + label,
.modern-input input:not(:placeholder-shown) + label,
.modern-input textarea:focus + label,
.modern-input textarea:not(:placeholder-shown) + label {
    top: 8px;
    transform: none;
    font-size: 11px;
    font-weight: 600;
    color: #3d5ee1;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

/* Field Label */
.field-label {
    display: block;
    font-size: 12px;
    font-weight: 700;
    color: #495057;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    margin-bottom: 12px;
}

/* Image Upload */
.image-upload-box {
    margin-bottom: 24px;
}

.image-preview {
    width: 100%;
    aspect-ratio: 16/9;
    border-radius: 16px;
    overflow: hidden;
    background: linear-gradient(135deg, #f1f3f9 0%, #e9ecef 100%);
    margin-bottom: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    border: 2px dashed #dee2e6;
    transition: all 0.3s ease;
}

.image-preview:hover {
    border-color: #3d5ee1;
}

.image-preview img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.no-image {
    text-align: center;
    color: #adb5bd;
}

.no-image i {
    font-size: 40px;
    margin-bottom: 8px;
    display: block;
}

.no-image span {
    font-size: 13px;
    font-weight: 500;
}

.file-input {
    width: 100%;
    padding: 10px;
    border: 2px solid #e9ecef;
    border-radius: 10px;
    font-size: 13px;
    background: #f8f9fa;
    transition: all 0.3s ease;
}

.file-input:hover {
    border-color: #3d5ee1;
}

/* Icon Picker */
.icon-picker-box {
    margin-bottom: 24px;
}

.icon-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(50px, 1fr));
    gap: 8px;
}

.icon-item {
    aspect-ratio: 1;
    background: #f8f9fa;
    border: 2px solid #e9ecef;
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    transition: all 0.3s ease;
}

.icon-item i {
    font-size: 20px;
    color: #6c757d;
}

.icon-item:hover {
    border-color: #3d5ee1;
    transform: scale(1.05);
}

.icon-item.active {
    background: linear-gradient(135deg, #3d5ee1 0%, #5a7cf7 100%);
    border-color: #3d5ee1;
}

.icon-item.active i {
    color: #fff;
}

/* Rich Text Editor */
.richtext-box {
    margin-bottom: 24px;
}

.editor-wrapper {
    border: 2px solid #e9ecef;
    border-radius: 12px;
    overflow: hidden;
    background: #fff;
    transition: all 0.3s ease;
}

.editor-wrapper:focus-within {
    border-color: #3d5ee1;
    box-shadow: 0 0 0 4px rgba(61, 94, 225, 0.1);
}

/* Action Bar */
.action-bar {
    display: flex;
    justify-content: flex-end;
    gap: 16px;
    padding: 20px 24px;
    background: #fff;
    border-radius: 16px;
    box-shadow: 0 4px 25px rgba(0, 0, 0, 0.08);
    margin-bottom: 40px;
}

.cancel-btn {
    padding: 12px 24px;
    background: #f8f9fa;
    color: #6c757d;
    border-radius: 12px;
    font-size: 14px;
    font-weight: 600;
    text-decoration: none;
    transition: all 0.3s ease;
    display: inline-flex;
    align-items: center;
}

.cancel-btn:hover {
    background: #e9ecef;
    color: #495057;
}

.save-btn {
    padding: 12px 32px;
    background: linear-gradient(135deg, #3d5ee1 0%, #5a7cf7 100%);
    color: #fff;
    border: none;
    border-radius: 12px;
    font-size: 14px;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.3s ease;
    display: inline-flex;
    align-items: center;
    box-shadow: 0 4px 15px rgba(61, 94, 225, 0.3);
}

.save-btn:hover:not(:disabled) {
    background: linear-gradient(135deg, #2a4bc9 0%, #4a6ce7 100%);
    transform: translateY(-2px);
    box-shadow: 0 6px 20px rgba(61, 94, 225, 0.4);
}

.save-btn:disabled {
    opacity: 0.7;
    cursor: not-allowed;
}
</style>

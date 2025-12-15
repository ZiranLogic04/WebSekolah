<script setup>
import { ref, reactive, watch, computed, nextTick, onMounted } from 'vue';
import { Head, useForm, usePage, router } from '@inertiajs/vue3';
import AdminLayout from '@/Layouts/AdminLayout.vue';
import ToastNotification from '@/Components/ToastNotification.vue';
import { QuillEditor } from '@vueup/vue-quill';
import '@vueup/vue-quill/dist/vue-quill.snow.css';
import VueEasyDataTable from 'vue3-easy-data-table';
import 'vue3-easy-data-table/dist/style.css';

const props = defineProps({
    surats: Array,
    templates: Array,
    lembaga: Object,
});

const activeTab = ref('archive'); // 'archive', 'create', 'templates'
const toast = ref(null);
const page = usePage();

const computedSurats = computed(() => {
    return props.surats.map((item, index) => ({
        ...item,
        no: index + 1
    }));
});

// ----------------------------------------------------------------------
// TAB 1: ARSIP SURAT (Letter Archive)
// ----------------------------------------------------------------------
// ----------------------------------------------------------------------
// TAB 1: ARSIP SURAT (Letter Archive)
// ----------------------------------------------------------------------
const headersArchive = [
    { text: "No", value: "no", width: 50 },
    { text: "Jenis Surat", value: "jenis_surat" },
    { text: "Kode/Nomor", value: "kode_surat" },
    { text: "Tanggal", value: "created_at" },
    { text: "Status", value: "status" },
    { text: "Aksi", value: "actions", width: 200 },
];

const deleteSurat = (id) => {
    if(confirm('Hapus arsip surat ini?')) router.delete(`/admin/surat/${id}`);
};
const printSurat = (id) => {
    window.open(`/admin/surat/${id}/cetak`, '_blank');
};
const archiveSurat = (id) => {
    if(confirm('Arsipkan surat ini? Status akan berubah menjadi Final.')) {
        router.patch(`/admin/surat/${id}/status`, {}, {
            onSuccess: () => toast.value?.showSuccessToast('Berhasil', 'Surat telah diarsipkan.')
        });
    }
};

// ----------------------------------------------------------------------
// TAB 2: BUAT SURAT (The Generator)
// ----------------------------------------------------------------------
const dynamicFields = ref([]);
const letterForm = useForm({
    id: null,
    template_id: '',
    jenis_surat: '', // Required for backend
    kode_surat: '',
    data_json: {}, // Dynamic data
    isi_surat: '', // Valid HTML from template
    use_system_header: true,
    // Kop defaults (taken directly from props)
    kop_baris1: 'PEMERINTAH PROVINSI JAWA TIMUR',
    kop_baris2: props.lembaga?.nama_sekolah ? props.lembaga.nama_sekolah.toUpperCase() : '',
    kop_baris3: props.lembaga?.alamat || '',
    kop_baris4: [
        props.lembaga?.email ? `Email: ${props.lembaga.email}` : '',
        props.lembaga?.telepon ? `Telp: ${props.lembaga.telepon}` : ''
    ].filter(Boolean).join(' | '),
    kop_baris5: '',
    kop_baris6: '',
    processing: false
});

const previewContent = ref('');
const previewEditorRef = ref(null);

// Helper: Update Preview Content manually
const updatePreview = () => {
    let content = letterForm.isi_surat || '';
    
    // Replace [NOMOR_SURAT]
    if (letterForm.kode_surat) {
        content = content.replace(/\[NOMOR_SURAT\]/g, letterForm.kode_surat);
    }

    // Replace dynamic {{variables}}
    if (dynamicFields.value.length > 0) {
        dynamicFields.value.forEach(field => {
            const regex = new RegExp(`{{${field}}}`, 'g');
            const replacement = letterForm.data_json[field] 
                ? `<span class="fw-bold text-primary">${letterForm.data_json[field]}</span>` 
                : `<span class="bg-warning text-dark opacity-50 px-1">{{${field}}}</span>`;
            content = content.replace(regex, replacement);
        });
    }
    previewContent.value = content;
};

// Watch for ALL changes in letterForm to update text real-time
watch(() => letterForm, () => {
    updatePreview();
}, { deep: true });

// Watch Template Selection Specifics
watch(() => letterForm.template_id, (newId) => {
    const template = props.templates.find(t => t.id === newId);
    if (template) {
        letterForm.jenis_surat = template.name; // Set name for backend
        letterForm.isi_surat = template.content;
        letterForm.use_system_header = Boolean(template.use_system_kop);
        
        // Extract Placeholders
        const matches = template.content.match(/{{(.*?)}}/g);
        if (matches) {
            dynamicFields.value = [...new Set(matches.map(m => m.replace(/{{|}}/g, '')))];
            // Init data_json keys if missing
            dynamicFields.value.forEach(key => {
                if (!letterForm.data_json[key]) letterForm.data_json[key] = '';
            });
        } else {
            dynamicFields.value = [];
        }
        // Immediate update
        updatePreview();
    }
});

const saveLetter = () => {
    letterForm.post('/admin/surat', {
        onSuccess: () => {
            activeTab.value = 'archive';
            toast.value?.showSuccessToast('Berhasil', 'Surat berhasil dibuat dan diarsipkan.');
            letterForm.reset();
        }
    });
};

// ----------------------------------------------------------------------
// TAB 3: MASTER TEMPLATE (Designer)
// ----------------------------------------------------------------------
const isEditingTemplate = ref(false);
const showSource = ref(false); // Toggle raw HTML
const editorRef = ref(null); // Reference to Quill Instance

const templateForm = useForm({
    id: null,
    name: '',
    content: '', // Will be synced with Editor
    use_system_kop: true,
});

const headersTemplate = [
    { text: "Nama Template", value: "name" },
    { text: "Header Default?", value: "use_system_kop" },
    { text: "Aksi", value: "actions", width: 150 },
];

const createTemplate = () => {
    templateForm.reset();
    isEditingTemplate.value = true;
};
const editTemplate = async (item) => {
    templateForm.id = item.id;
    templateForm.name = item.name;
    templateForm.content = item.content; // Normal bind
    templateForm.use_system_kop = Boolean(item.use_system_kop);
    showSource.value = false; 
    
    isEditingTemplate.value = true;
    
    // MANUAL INJECTION FIX: Force Quill to accept the HTML
    await nextTick();
    if (editorRef.value && item.content) {
        editorRef.value.setHTML(item.content);
    }
};
const cancelTemplateEdit = () => {
    isEditingTemplate.value = false;
    templateForm.reset();
};
const saveTemplate = () => {
    const url = templateForm.id ? `/admin/letter-templates/${templateForm.id}` : '/admin/letter-templates';
    const method = templateForm.id ? 'put' : 'post';
    
    templateForm[method](url, {
        onSuccess: () => {
            isEditingTemplate.value = false;
            toast.value?.showSuccessToast('Berhasil', 'Template disimpan');
        }
    });
};
const deleteTemplate = (id) => {
    if(confirm('Hapus template ini?')) router.delete(`/admin/letter-templates/${id}`);
};

// Watch for Flash
watch(() => page.props.flash, (flash) => {
    if (flash?.success) toast.value?.showSuccessToast('Sukses', flash.success);
}, { deep: true });
</script>

<template>
    <AdminLayout>
        <Head title="Pengaturan Surat" />
        
        <div class="content container-fluid">
            <!-- Header -->
            <div class="page-header mb-4">
                <div class="row align-items-center">
                    <div class="col">
                        <h3 class="page-title">Manajemen Persuratan</h3>
                        <ul class="breadcrumb">
                            <li class="breadcrumb-item"><a href="/dashboard">Dashboard</a></li>
                            <li class="breadcrumb-item active">Surat</li>
                        </ul>
                    </div>
                </div>
            </div>

            <!-- MAIN CONTENT SWITCHER -->
            
            <!-- VIEW 1: ARSIP SURAT (LIST, DEFAULT) -->
            <div v-if="activeTab === 'archive'" class="animate__animated animate__fadeIn">
                
                <!-- Action Buttons & Info -->
                <div class="row mb-4">
                    <div class="col-md-8">
                        <div class="alert alert-light border shadow-sm">
                            <h5 class="fw-bold"><i class="fas fa-info-circle text-primary me-2"></i> Sistem Manajemen Surat</h5>
                            <p class="mb-0 text-muted small">Kelola arsip surat sekolah, buat surat baru dari template, atau atur master template surat.</p>
                        </div>
                    </div>
                    <div class="col-md-4 d-flex flex-column gap-2">
                        <button class="btn btn-primary py-2 fw-bold shadow-sm" @click="activeTab = 'create'">
                            <i class="fas fa-plus-circle me-2"></i> BUAT SURAT BARU
                        </button>
                        <button class="btn btn-outline-secondary py-2 fw-bold" @click="activeTab = 'templates'">
                            <i class="fas fa-pencil-ruler me-2"></i> KELOLA MASTER TEMPLATE
                        </button>
                    </div>
                </div>

                <!-- The Table -->
                <div class="card modern-table-card">
                    <div class="card-header bg-white border-bottom py-3">
                        <h5 class="m-0 fw-bold text-secondary">Arsip Surat Keluar</h5>
                    </div>
                    <div class="card-body p-0">
                        <VueEasyDataTable :headers="headersArchive" :items="computedSurats" table-class-name="modern-data-table" border-cell alternating>
                            <template #item-no="{ no }">
                                <span class="fw-bold text-secondary">{{ no }}</span>
                            </template>
                            <template #item-created_at="item">{{ new Date(item.created_at).toLocaleDateString('id-ID', { year: 'numeric', month: 'long', day: 'numeric'}) }}</template>
                            <template #item-actions="item">
                                <div class="action-buttons">
                                    <button class="btn btn-action btn-edit bg-info text-white me-2" @click="printSurat(item.id)" title="Cetak/PDF"><i class="fas fa-print"></i></button>
                                    
                                    <!-- Show Archive Button only for Drafts -->
                                    <button v-if="item.status !== 'archived'" class="btn btn-action btn-success text-white me-2" @click="archiveSurat(item.id)" title="Arsipkan"><i class="fas fa-archive"></i></button>
                                    <button class="btn btn-action btn-delete" @click="deleteSurat(item.id)" title="Hapus"><i class="fas fa-trash"></i></button>
                                </div>
                            </template>
                        </VueEasyDataTable>
                    </div>
                </div>
            </div>

            <!-- VIEW 2: GENERATOR (BUAT SURAT) -->
            <div v-if="activeTab === 'create'" class="animate__animated animate__fadeIn">
                <!-- Back Button -->
                <div class="mb-3">
                    <button class="btn btn-secondary px-4" @click="activeTab = 'archive'">
                        <i class="fas fa-arrow-left me-2"></i> Kembali ke Arsip
                    </button>
                </div>

                <div class="row">
                    <!-- Config Sidebar -->
                    <div class="col-lg-4 mb-4">
                        <div class="card h-100 border-primary border-top border-3">
                            <div class="card-body">
                                <h5 class="fw-bold mb-3"><i class="fas fa-cog me-2"></i> 1. Konfigurasi Surat</h5>
                                
                                <div class="mb-3">
                                    <label class="form-label fw-bold">Pilih Template</label>
                                    <select class="form-select" v-model="letterForm.template_id">
                                        <option value="" disabled>-- Pilih Jenis Surat --</option>
                                        <option v-for="t in props.templates" :key="t.id" :value="t.id">{{ t.name }}</option>
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label fw-bold">Nomor Surat</label>
                                    <input type="text" class="form-control" v-model="letterForm.kode_surat" placeholder="No. 123/SK/2024">
                                </div>

                                <div v-if="letterForm.template_id" class="mt-4 animate__animated animate__fadeInUp">
                                    <h5 class="fw-bold mb-3"><i class="fas fa-pencil-alt me-2"></i> 2. Isi Data</h5>
                                    <div class="alert alert-info small" v-if="dynamicFields.length === 0">
                                        Template ini tidak memiliki variabel dinamis/placeholder.
                                    </div>
                                    <div v-for="field in dynamicFields" :key="field" class="mb-3">
                                        <label class="form-label text-capitalize small">{{ field.replace(/_/g, ' ') }}</label>
                                        <input type="text" class="form-control" v-model="letterForm.data_json[field]" :placeholder="'Isi ' + field">
                                    </div>
                                    </div>
                                
                                <!-- EDIT KOP SURAT (ACCORDION) -->
                                <div class="accordion mt-3" id="accordionKop">
                                    <div class="accordion-item">
                                        <h2 class="accordion-header" id="headingKop">
                                            <button class="accordion-button collapsed py-2" type="button" data-bs-toggle="collapse" data-bs-target="#collapseKop" aria-expanded="false" aria-controls="collapseKop">
                                                <i class="fas fa-heading me-2"></i> Edit Kop Surat
                                            </button>
                                        </h2>
                                        <div id="collapseKop" class="accordion-collapse collapse" aria-labelledby="headingKop">
                                            <div class="accordion-body p-2 small">
                                                <div class="mb-2">
                                                    <label class="form-label mb-0 fw-bold">Baris 1 (Pemerintah/Yayasan)</label>
                                                    <input type="text" class="form-control form-control-sm" v-model="letterForm.kop_baris1">
                                                </div>
                                                <div class="mb-2">
                                                    <label class="form-label mb-0 fw-bold">Baris 2 (Nama Sekolah)</label>
                                                    <input type="text" class="form-control form-control-sm" v-model="letterForm.kop_baris2">
                                                </div>
                                                <div class="mb-2">
                                                    <label class="form-label mb-0 fw-bold">Baris 3 (Alamat)</label>
                                                    <input type="text" class="form-control form-control-sm" v-model="letterForm.kop_baris3">
                                                </div>
                                                <div class="mb-2">
                                                    <label class="form-label mb-0 fw-bold">Baris 4 (Kontak)</label>
                                                    <input type="text" class="form-control form-control-sm" v-model="letterForm.kop_baris4">
                                                </div>
                                                <div class="mb-2">
                                                    <label class="form-label mb-0 fw-bold">Baris 5 (Opsional: Website/Lainnya)</label>
                                                    <input type="text" class="form-control form-control-sm" v-model="letterForm.kop_baris5" placeholder="Contoh: Website: www.sekolah.sch.id">
                                                </div>
                                                <div class="mb-2">
                                                    <label class="form-label mb-0 fw-bold">Baris 6 (Opsional)</label>
                                                    <input type="text" class="form-control form-control-sm" v-model="letterForm.kop_baris6" placeholder="Tambahan informasi...">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="d-grid mt-4">
                                    <button class="btn btn-primary btn-lg" @click="saveLetter" :disabled="!letterForm.template_id || letterForm.processing">
                                        <i class="fas fa-save me-2"></i> SIMPAN DRAFT
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Live Preview -->
                    <div class="col-lg-8">
                        <div class="card h-100 bg-secondary bg-opacity-10 border-0">
                            <div class="card-header bg-transparent border-0 d-flex justify-content-between align-items-center">
                                <h5 class="card-title fw-bold m-0"><i class="fas fa-eye me-2"></i> Preview Hasil</h5>
                                <span class="badge bg-warning text-dark">A4 Preview</span>
                            </div>
                            
                            <!-- DEBUG LEMBAGA -->
                            <div class="px-3" v-if="!props.lembaga">
                                <div class="alert alert-danger mb-0">
                                    <strong>ERROR:</strong> Data Lembaga tidak ditemukan (NULL). Silakan isi data di menu Data Lembaga.
                                </div>
                            </div>
                            <!-- END DEBUG -->

                            <div class="card-body d-flex justify-content-center bg-light">
                                <div class="a4-paper shadow">
                                    <!-- HEADER SYSTEM -->
                                    <div class="kop-surat d-flex align-items-center border-bottom pb-3 mb-4" 
                                         style="border-bottom: 3px double black !important;"
                                         v-if="letterForm.use_system_header">
                                        <div class="logo-section me-3" v-if="props.lembaga?.logo">
                                            <!-- Fix Image Path: Ensure /storage/ prefix is only added if needed -->
                                            <img :src="props.lembaga.logo.startsWith('http') ? props.lembaga.logo : `/storage/${props.lembaga.logo}`" alt="Logo" style="height: 100px; width: auto; object-fit: contain;">
                                        </div>
                                        <div class="text-center w-100">
                                            <h5 class="mb-0 text-uppercase fw-bold">{{ letterForm.kop_baris1 }}</h5>
                                            <h4 class="mb-0 text-uppercase fw-bold">{{ letterForm.kop_baris2 }}</h4>
                                            <p class="mb-0 small">{{ letterForm.kop_baris3 }}</p>
                                            <p class="mb-0 small">{{ letterForm.kop_baris4 }}</p>
                                            <p class="mb-0 small">{{ letterForm.kop_baris5 }}</p>
                                            <p class="mb-0 small">{{ letterForm.kop_baris6 }}</p>
                                        </div>
                                    </div>

                                    <!-- CONTENT -->
                                    <div class="surat-content ql-editor p-0" v-html="previewContent"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- VIEW 3: TEMPLATES (MASTER) -->
            <div v-if="activeTab === 'templates'" class="animate__animated animate__fadeIn">
                <!-- Back Button -->
                <div class="mb-3 d-flex justify-content-between align-items-center">
                    <button class="btn btn-secondary px-4" @click="activeTab = 'archive'">
                        <i class="fas fa-arrow-left me-2"></i> Kembali ke Arsip
                    </button>
                    <!-- Show 'Add' button only in list mode of templates -->
                    <button v-if="!isEditingTemplate" class="btn btn-primary" @click="createTemplate">
                        <i class="fas fa-plus me-2"></i> Tambah Template
                    </button>
                </div>

                <!-- List Mode -->
                <div v-if="!isEditingTemplate">
                    <div class="card modern-table-card">
                        <div class="card-header bg-white py-3">
                            <h5 class="m-0 fw-bold text-primary">Daftar Template Surat ({{ props.templates ? props.templates.length : 0 }})</h5>
                        </div>
                        <div class="card-body p-0">
                            <VueEasyDataTable :headers="headersTemplate" :items="props.templates" table-class-name="modern-data-table" border-cell alternating>
                                <template #item-use_system_kop="item">
                                    <span class="badge" :class="item.use_system_kop ? 'bg-success' : 'bg-secondary'">{{ item.use_system_kop ? 'Ya' : 'Tidak' }}</span>
                                </template>
                                <template #item-actions="item">
                                    <div class="action-buttons">
                                        <button class="btn btn-action btn-edit bg-primary text-white me-2" @click="editTemplate(item)"><i class="fas fa-edit"></i></button>
                                        <button class="btn btn-action btn-delete" @click="deleteTemplate(item.id)"><i class="fas fa-trash"></i></button>
                                    </div>
                                </template>
                            </VueEasyDataTable>
                        </div>
                    </div>
                </div>

                <!-- Edit Mode -->
                <div v-else>
                    <div class="card h-100 shadow-sm border-0">
                        <div class="card-header bg-white border-bottom py-3 d-flex justify-content-between align-items-center">
                            <h5 class="fw-bold m-0"><i class="fas fa-edit me-2"></i> Editor Template</h5>
                            <div>
                                <button class="btn btn-light border me-2" @click="cancelTemplateEdit">Batal</button>
                                <button class="btn btn-primary" @click="saveTemplate"><i class="fas fa-save me-1"></i> Simpan Template</button>
                            </div>
                        </div>
                        <div class="card-body bg-light">
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label class="form-label fw-bold">Nama Template</label>
                                    <input type="text" class="form-control" v-model="templateForm.name" placeholder="Misal: Surat Keterangan Lulus">
                                </div>
                                <div class="col-md-6 d-flex align-items-end">
                                    <div class="form-check form-switch mb-2">
                                        <input class="form-check-input" type="checkbox" id="tmplSysHeader" v-model="templateForm.use_system_kop">
                                        <label class="form-check-label fw-bold" for="tmplSysHeader">Gunakan Header Sistem (Kop Sekolah)</label>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Wide Editor (Visual) -->
                            <!-- Dynamic Height Class based on KOP usage -->
                            <div class="bg-white p-4 justify-content-center d-flex shadow-sm rounded">
                                <div class="a4-paper vector-page" :class="{ 'reduced-height': templateForm.use_system_kop }">
                                    <!-- NO VISUAL KOP HERE (As requested) -->
                                    <QuillEditor ref="editorRef" theme="snow" v-model:content="templateForm.content" :key="templateForm.id" contentType="html" toolbar="essential" placeholder="Desain format surat di sini..." />
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
        <ToastNotification ref="toast" />
    </AdminLayout>
</template>

<style scoped>
.a4-paper {
    width: 210mm;
    height: 297mm; /* Default Full A4 */
    display: flex;
    flex-direction: column;
    background: white;
    padding: 1cm 2cm; 
    color: black;
    font-family: 'Times New Roman', serif;
    font-size: 12pt;
    line-height: 1.35; 
    margin-bottom: 3rem; 
    box-shadow: 0 4px 15px rgba(0,0,0,0.2); 
    overflow: hidden; /* Hide overflow to show LIMIT */
    transition: height 0.3s ease; 
    flex-shrink: 0; 
}

/* Reduced Height when Kop is active (Simulating 80cm of 100cm) */
.a4-paper.reduced-height {
    height: 267mm; /* 297mm - 30mm (Relaxed Limit) */
    border-top: 4px solid #ddd; 
}

/* Copy visual style to indicate "Top is reserved"? Maybe not needed. User wants "80cm doang" */


/* Ensure Kop doesn't shrink */
.kop-surat {
    flex-shrink: 0;
}

/* Quill Overrides */
:deep(.ql-container) {
    flex: 1; /* occupy remaining space */
    display: flex;
    flex-direction: column;
    font-family: inherit;
    font-size: inherit; 
    border: none !important;
    overflow: visible; 
}
:deep(.ql-editor) {
    flex: 1;
    overflow-y: visible; 
    padding: 0;
}
/* Revert specific P margin to default or remove if originally standard */
:deep(.ql-editor p) {
    margin-bottom: 0.5rem; /* Standard spacing */
}
:deep(.ql-indent-1) { padding-left: 1.25cm; }
:deep(.ql-indent-2) { padding-left: 2.50cm; }
:deep(.ql-indent-3) { padding-left: 3.75cm; }
:deep(.ql-indent-4) { padding-left: 5.00cm; }
:deep(.ql-indent-5) { padding-left: 6.25cm; }
:deep(.ql-indent-6) { padding-left: 7.50cm; }
:deep(.ql-indent-7) { padding-left: 8.75cm; }
:deep(.ql-indent-8) { padding-left: 10.00cm; }
:deep(.ql-indent-9) { padding-left: 11.25cm; }

:deep(.ql-toolbar) {
    border: none !important;
    border-bottom: 1px solid #ddd !important;
    position: sticky;
    top: 0;
    z-index: 50;
    background: white;
}
</style>

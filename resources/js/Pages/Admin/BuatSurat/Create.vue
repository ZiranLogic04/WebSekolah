<script setup>
import { ref, watch, computed, onMounted } from 'vue';
import { Head, Link, useForm, usePage } from '@inertiajs/vue3';
import AdminLayout from '@/Layouts/AdminLayout.vue';
import { showToast } from '@/Utils/swal';

const page = usePage();

const props = defineProps({
    surat: Object,
    templates: Array,
    lembaga: Object,
    isEditing: Boolean,
});

// Helper for consistency
const showSuccess = (title, text) => showToast('success', title, text);
const showError = (title, text) => showToast('error', title, text);

// Check flash message on mount/update
watch(() => page.props.flash, (flash) => {
    if (flash?.success) {
        showSuccess('Berhasil', flash.success);
    }
    if (flash?.error) {
        showError('Gagal', flash.error);
    }
}, { deep: true, immediate: true });

const dynamicFields = ref([]);

const form = useForm({
    id: props.surat?.id || null,
    template_id: props.surat?.template_id || '',
    jenis_surat: props.surat?.jenis_surat || '',
    kode_surat: props.surat?.kode_surat || '',
    isi_surat: props.surat?.isi_surat || '',
    data_json: props.surat?.data_json || {},
    ukuran_kertas: props.surat?.ukuran_kertas || 'A4',
    use_system_header: props.surat?.use_system_header !== undefined ? Boolean(props.surat.use_system_header) : true,
});

// Watch Template Selection - Auto-fill content AND paper size
watch(() => form.template_id, (newId) => {
    if (newId) {
        const t = props.templates?.find(x => x.id == newId);
        if (t) {
            form.jenis_surat = t.name;
            form.isi_surat = t.content;
            form.use_system_header = Boolean(t.use_system_kop);
            form.ukuran_kertas = t.paper_size || 'A4'; // Auto-fill from template
            form.data_json = {}; // Reset data
        }
    }
});

// Extract Placeholders from content
const extractPlaceholders = (content) => {
    if (!content) return [];
    const matches = content.match(/{{(.*?)}}/g);
    if (matches) {
        return [...new Set(matches.map(m => m.replace(/{{|}}/g, '').trim()))];
    }
    return [];
};

// Watch content to update dynamic fields
watch(() => form.isi_surat, (newVal) => {
    const fields = extractPlaceholders(newVal);
    dynamicFields.value = fields;
    
    // Ensure keys exist in data_json
    fields.forEach(f => {
        if (form.data_json[f] === undefined) form.data_json[f] = '';
    });
}, { immediate: true });

// Preview Content with variables replaced
const previewContent = computed(() => {
    let content = form.isi_surat || '';
    
    // Replace [NOMOR_SURAT]
    if (form.kode_surat) {
        content = content.replace(/\[NOMOR_SURAT\]/g, `<strong>${form.kode_surat}</strong>`);
    }

    // Replace {{ variable }} with actual values
    content = content.replace(/{{(.*?)}}/g, (match, p1) => {
        const key = p1.trim();
        const val = form.data_json[key];
        
        if (val) return `<strong>${val}</strong>`;
        return `<span class="text-danger">[${key}]</span>`;
    });
    
    return content;
});

// Kop Surat Helper
const getLogoPath = (path) => path ? '/storage/' + path : null;

// Paper Style Logic - FIXED HEIGHT like real paper
// Margin mPDF diterapkan sebagai padding di content area, bukan di paper container
const paperStyle = computed(() => {
    let w = '210mm';
    let h = '297mm';

    if (form.ukuran_kertas === 'F4') {
        w = '215mm';
        h = '330mm';
    } else if (form.ukuran_kertas === 'Legal') {
        w = '216mm';
        h = '356mm';
    }

    return {
        width: w,
        height: h,
        // Padding untuk simulasi margin mPDF
        paddingTop: '15mm',
        paddingBottom: '10mm',
        paddingLeft: '15mm',
        paddingRight: '15mm'
    };
});

// Submit (Save Only)
const submit = (options = {}) => {
    const url = props.isEditing ? `/admin/surat/${form.id}` : '/admin/surat';
    const method = props.isEditing ? 'put' : 'post';

    form[method](url, {
        onSuccess: (page) => {
            // Flash message watcher handles success notification
            if (options.onSuccess) options.onSuccess(page);
        },
        onError: (errors) => {
            let msg = 'Periksa kembali data yang diinput.';
            if (errors.kode_surat) msg = errors.kode_surat;
            else if (errors.template_id) msg = 'Silakan pilih template surat.';
            showError('Gagal Menyimpan', msg);
        }
    });
};

// Save and Print
const saveAndPrint = () => {
    submit({
        onSuccess: (page) => {
            // Check if we have an ID from props (updated by redirect)
            const id = page.props.surat?.id;
            if (id) {
                 window.open(`/admin/surat/${id}/print`, '_blank');
                 // Notifikasi setelah cetak
                 setTimeout(() => {
                     showSuccess('Surat Tercetak', 'Surat berhasil dicetak dan masuk ke arsip Surat Keluar.');
                 }, 500);
            }
        }
    });
};
</script>

<template>
    <AdminLayout>
        <Head title="Buat Surat" />
        <div class="content container-fluid">
            <!-- Radiant Header Card -->
            <div class="card border-0 shadow-lg rounded-4 mb-4 overflow-hidden position-relative" style="background: linear-gradient(135deg, #4f46e5 0%, #3b82f6 100%);">
                <div class="card-body p-4 p-lg-5 position-relative z-1">
                    <div class="row align-items-center">
                        <div class="col-lg-8">
                            <div class="d-flex align-items-center gap-3 mb-2">
                                <div class="icon-box bg-white rounded-3 text-primary p-2">
                                    <i class="fas fa-pen-fancy fs-3"></i>
                                </div>
                                <h2 class="fw-bold text-white mb-0 ls-tight">{{ isEditing ? 'Edit Surat' : 'Buat Surat Baru' }}</h2>
                            </div>
                            <p class="text-white-50 mb-0 fs-5">Buat dan cetak surat resmi dengan mudah menggunakan template yang tersedia.</p>
                        </div>
                        </div>
                </div>
                <!-- Decorative Background -->
                <div class="position-absolute bottom-0 end-0 opacity-10 me-n5 mb-n5">
                    <i class="fas fa-envelope-open-text" style="font-size: 10rem; color: white;"></i>
                </div>
            </div>

            <div class="row">
                <!-- FORM SIDEBAR -->
                <div class="col-lg-4 mb-4">
                    <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
                        <div class="card-header bg-white p-4 border-bottom">
                            <h5 class="fw-bold mb-0 text-dark"><i class="fas fa-sliders-h me-2 text-primary"></i> Pengaturan Surat</h5>
                        </div>
                        <div class="card-body p-4">
                            
                            <!-- TEMPLATE -->
                            <div class="mb-4">
                                <label class="form-label fw-bold text-muted small text-uppercase">Template Surat <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light border-end-0 text-muted ps-3 rounded-start-3"><i class="fas fa-file-alt"></i></span>
                                    <select class="form-select border-start-0 py-2 rounded-end-3" v-model="form.template_id">
                                        <option value="">-- Pilih Template --</option>
                                        <option v-for="t in templates" :key="t.id" :value="t.id">{{ t.name }}</option>
                                    </select>
                                </div>
                            </div>

                            <!-- NOMOR SURAT -->
                            <div class="mb-4">
                                <label class="form-label fw-bold text-muted small text-uppercase">Nomor Surat <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light border-end-0 text-muted ps-3 rounded-start-3"><i class="fas fa-barcode"></i></span>
                                    <input type="text" class="form-control border-start-0 py-2 rounded-end-3" :class="{'is-invalid': !form.kode_surat && form.template_id}" v-model="form.kode_surat" placeholder="No. Surat (e.g. 421/100/SMK)">
                                </div>
                                <div v-if="!form.kode_surat && form.template_id" class="text-danger small mt-1">Nomor surat wajib diisi</div>
                            </div>
                            
                            <!-- KERTAS -->
                            <div class="mb-4">
                                <label class="form-label fw-bold text-muted small text-uppercase">Ukuran Kertas</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light border-end-0 text-muted ps-3 rounded-start-3"><i class="fas fa-print"></i></span>
                                    <select class="form-select border-start-0 py-2 rounded-end-3" v-model="form.ukuran_kertas">
                                        <option value="A4">A4 (Standar)</option>
                                        <option value="F4">F4 / Folio</option>
                                        <option value="Legal">Legal</option>
                                    </select>
                                </div>
                            </div>

                            <hr class="border-secondary-subtle">
                            
                            <!-- DYNAMIC FIELDS -->
                            <div v-if="dynamicFields.length > 0">
                                <h6 class="fw-bold mb-3 text-primary"><i class="fas fa-pen-nib me-2"></i>Isi Data Variabel</h6>
                                <div class="p-3 bg-light rounded-4 border border-dashed">
                                    <div v-for="field in dynamicFields" :key="field" class="mb-3">
                                        <label class="form-label small fw-bold text-muted text-capitalize">{{ field.replace(/_/g, ' ') }}</label>
                                        <input type="text" class="form-control form-control-sm rounded-3" v-model="form.data_json[field]" :placeholder="'Isi ' + field">
                                    </div>
                                </div>
                            </div>

                            <div class="d-grid gap-2 mt-4">
                                <button class="btn btn-primary rounded-pill py-2 fw-bold shadow-sm hover-up" @click="saveAndPrint" :disabled="form.processing || !form.template_id || !form.kode_surat">
                                    <span v-if="form.processing" class="spinner-border spinner-border-sm me-2"></span>
                                    <i class="fas fa-print me-2" v-else></i> Simpan & Cetak
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- PREVIEW -->
                <div class="col-lg-8">
                    <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
                        <div class="card-header bg-white p-4 border-bottom d-flex justify-content-between align-items-center">
                            <h5 class="fw-bold mb-0 text-dark"><i class="fas fa-file-alt me-2 text-primary"></i> Live Preview</h5>
                            <span class="badge bg-primary rounded-pill px-3">{{ form.ukuran_kertas }}</span>
                        </div>
                        
                        <div class="card-body p-4 bg-secondary-subtle">
                            
                            <!-- Paper Preview Container - Scrollable -->
                            <div class="paper-preview-wrapper d-flex justify-content-center">
                                <!-- A4/F4/Legal Paper - Fixed size -->
                                <div class="paper-a4 shadow bg-white" :style="paperStyle">
                                    
                                    <!-- KOP SURAT (MATCHES PDF STRUCTURE) -->
                                    <div v-if="form.use_system_header && lembaga" class="kop-surat">
                                        <table class="kop-table">
                                            <tr>
                                                <!-- Logo Kiri -->
                                                <td class="logo-cell">
                                                    <img v-if="lembaga.logo_kiri" :src="getLogoPath(lembaga.logo_kiri)" class="logo-img">
                                                </td>
                                                <!-- Teks Tengah -->
                                                <td class="text-cell">
                                                    <h3 v-if="lembaga.kop_baris1" class="kop-header1">{{ lembaga.kop_baris1 }}</h3>
                                                    <h2 v-if="lembaga.kop_baris2 || lembaga.nama_sekolah" class="kop-header2">{{ lembaga.kop_baris2 || lembaga.nama_sekolah }}</h2>
                                                    <h3 v-if="lembaga.kop_baris3 || lembaga.alamat" class="kop-header3">{{ lembaga.kop_baris3 || lembaga.alamat }}</h3>
                                                    <p v-if="lembaga.kop_baris4 || (lembaga.telepon + ' | ' + lembaga.email)" class="kop-text">{{ lembaga.kop_baris4 || (lembaga.telepon + ' | ' + lembaga.email) }}</p>
                                                    <p v-if="lembaga.kop_baris5" class="kop-text">{{ lembaga.kop_baris5 }}</p>
                                                </td>
                                                <!-- Logo Kanan -->
                                                <td class="logo-cell">
                                                    <img v-if="lembaga.logo_kanan" :src="getLogoPath(lembaga.logo_kanan)" class="logo-img">
                                                </td>
                                            </tr>
                                        </table>
                                    </div>
                                    
                                    <!-- EMPTY STATE -->
                                    <div v-if="!form.isi_surat" class="text-center text-muted py-5">
                                        <i class="fas fa-file-alt fa-3x mb-3 opacity-25"></i>
                                        <p>Pilih template di sebelah kiri untuk melihat preview surat.</p>
                                    </div>
                                    
                                    <!-- CONTENT -->
                                    <div v-else v-html="previewContent" class="preview-content ql-editor"></div>
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
.hover-up {
    transition: all 0.2s ease;
}
.hover-up:hover {
    transform: translateY(-2px);
    box-shadow: 0 .5rem 1rem rgba(0,0,0,.15)!important;
}
.border-dashed {
    border-style: dashed !important;
}

.paper-a4 {
    font-family: 'Times New Roman', Times, serif;
    font-size: 11pt;
    line-height: 1.15;
    letter-spacing: 0.5px; /* Reduced: Preview needs less spacing than Editor */
    color: #000;
    box-sizing: border-box;
}

/* Reset CSS untuk elements di dalam paper - hilangkan default browser */
.paper-a4 h2,
.paper-a4 h3,
.paper-a4 h4,
.paper-a4 p {
    margin: 0;
    padding: 0;
}

/* Kop Surat Table Style (EXACTLY Matches PDF surat.blade.php) */
.kop-surat {
    width: 100%;
    border-bottom: 3px double #000;
    margin-bottom: 15px;
    padding-bottom: 8px;
}
.kop-table {
    width: 100%;
    border-collapse: collapse;
}
.kop-table td {
    vertical-align: middle;
}
.logo-cell {
    width: 15%;
    text-align: center;
}
.logo-img {
    max-height: 70px;
    width: auto;
}
.text-cell {
    width: 70%;
    text-align: center;
}
.kop-header1 {
    font-family: 'Times New Roman', Times, serif;
    font-size: 12pt;
    font-weight: bold;
    text-transform: uppercase;
    margin: 0;
}
.kop-header2 {
    font-family: 'Times New Roman', Times, serif;
    font-size: 14pt;
    font-weight: bold;
    text-transform: uppercase;
    margin: 0;
}
.kop-header3 {
    font-family: 'Times New Roman', Times, serif;
    font-size: 12pt;
    font-weight: bold;
    margin: 0;
}
.kop-text {
    font-family: 'Times New Roman', Times, serif;
    font-size: 10pt;
    margin: 0;
}

.preview-content {
    font-size: 12pt;
    line-height: 1.15; /* Sama dengan .paper-a4 */
    /* text-align follows Quill classes */
}

.preview-content :deep(p) {
    margin: 0 0 10px 0;
    text-indent: 0;
}

/* Quill Indentation Classes */
:deep(.ql-indent-1) { padding-left: 3em; }
:deep(.ql-indent-2) { padding-left: 6em; }
:deep(.ql-indent-3) { padding-left: 9em; }
:deep(.ql-indent-4) { padding-left: 12em; }
:deep(.ql-indent-5) { padding-left: 15em; }
:deep(.ql-indent-6) { padding-left: 18em; }
:deep(.ql-indent-7) { padding-left: 21em; }
:deep(.ql-indent-8) { padding-left: 24em; }

/* Force Quill Alignments for Preview */
:deep(.ql-align-right) {
    text-align: right;
}
:deep(.ql-align-center) {
    text-align: center;
}
:deep(.ql-align-justify) {
    text-align: justify;
}
</style>

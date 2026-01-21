<script setup>
import { ref, watch, computed } from 'vue';
import { Head, useForm, router, usePage } from '@inertiajs/vue3';
import AdminLayout from '@/Layouts/AdminLayout.vue';
import { showToast, confirmDelete } from '@/Utils/swal';
import { QuillEditor } from '@vueup/vue-quill';
import '@vueup/vue-quill/dist/vue-quill.snow.css';

const props = defineProps({
    lembaga: Object, // Contains logos, kop_lines
    templates: Array,
});

const activeTab = ref('kop'); // 'kop', 'templates'
const page = usePage();

// Watch Flash Messages
watch(() => page.props.flash, (flash) => {
    if (flash?.success) showToast('success', 'Berhasil', flash.success);
    if (flash?.error) showToast('error', 'Gagal', flash.error);
}, { deep: true });

// ----------------------------------------------------------------------
// TAB 1: KOP SURAT
// ----------------------------------------------------------------------
const kopForm = useForm({
    logo_kiri: null,
    logo_kanan: null,
    // Default Fallbacks if Kop data is empty
    kop_baris1: props.lembaga?.kop_baris1 || 'YAYASAN PENDIDIKAN / DINAS PENDIDIKAN',
    kop_baris2: props.lembaga?.kop_baris2 || (props.lembaga?.nama_sekolah ? props.lembaga.nama_sekolah.toUpperCase() : ''),
    kop_baris3: props.lembaga?.kop_baris3 || props.lembaga?.alamat || '',
    kop_baris4: props.lembaga?.kop_baris4 || [
        props.lembaga?.telepon ? `Telp: ${props.lembaga.telepon}` : '',
        props.lembaga?.email ? `Email: ${props.lembaga.email}` : ''
    ].filter(Boolean).join(' | '),
    kop_baris5: props.lembaga?.kop_baris5 || '',
});

// Previews
// Helper to fix path if needed
const getLogoPath = (path) => path ? (path.startsWith('http') ? path : '/storage/' + path) : null;

const previewLogoKiri = ref(getLogoPath(props.lembaga?.logo_kiri));
const previewLogoKanan = ref(getLogoPath(props.lembaga?.logo_kanan));

const handleFile = (e, field) => {
    const file = e.target.files[0];
    if (file) {
        if (file.size > 2 * 1024 * 1024) {
            showToast('warning', 'File Terlalu Besar', 'Maksimal 2MB! Silakan pilih file lain.');
            e.target.value = '';
            return;
        }
        kopForm[field] = file;
        const reader = new FileReader();
        reader.onload = (e) => {
            if (field === 'logo_kiri') previewLogoKiri.value = e.target.result;
            if (field === 'logo_kanan') previewLogoKanan.value = e.target.result;
        };
        reader.readAsDataURL(file);
    }
};

const saveKop = () => {
    const url = '/admin/pengaturan-surat/kop';
    
    kopForm.post(url, {
        forceFormData: true,
        preserveScroll: true,
        onSuccess: () => {
           // Toast handled by watcher
        },
        onError: (errors) => {
            console.error('Save Kop Error:', errors);
            showToast('error', 'Gagal Simpan', 'Terjadi kesalahan validasi. Cek ukuran file (max 2MB).');
        }
    });
};



// ----------------------------------------------------------------------
// TAB 2: TEMPLATES
// ----------------------------------------------------------------------
const isEditingTemplate = ref(false);
const templateForm = useForm({
    id: null,
    name: '',
    content: '',
    use_system_kop: true,
    paper_size: 'A4',
});



const createTemplate = () => {
    templateForm.reset();
    templateForm.content = '<p>Ketik draf surat di sini...</p>'; // Default content
    templateForm.use_system_kop = true;
    templateForm.paper_size = 'A4';
    isEditingTemplate.value = true;
};

const editTemplate = (item) => {
    templateForm.id = item.id;
    templateForm.name = item.name;
    templateForm.content = item.content;
    templateForm.use_system_kop = Boolean(item.use_system_kop);
    templateForm.paper_size = item.paper_size || 'A4';
    isEditingTemplate.value = true;
};

const cancelTemplateEdit = () => {
    isEditingTemplate.value = false;
    templateForm.reset();
};

const saveTemplate = () => {
    if (!templateForm.name) return showToast('error', 'Error', 'Nama template harus diisi.');
    
    const url = templateForm.id 
        ? `/admin/letter-templates/${templateForm.id}` 
        : '/admin/letter-templates';
        
    const method = templateForm.id ? 'put' : 'post';
    
    templateForm[method](url, {
        onSuccess: () => {
            isEditingTemplate.value = false;
            // Toast handled by watcher
        },
        onError: (errors) => {
            showToast('error', 'Gagal', 'Gagal menyimpan template. Periksa isian.');
        }
    });
};

const deleteTemplate = (id) => {
    confirmDelete('Hapus Template?', 'Template yang dihapus tidak dapat dikembalikan!').then((result) => {
        if (result.isConfirmed) {
            router.delete(`/admin/letter-templates/${id}`, {
                onSuccess: () => showToast('success', 'Terhapus', 'Template berhasil dihapus.')
            });
        }
    });
};

// Computed: Editor size based on paper size
// Height = kertas + toolbar (45px) + kop jika aktif (~100px)
const editorStyle = computed(() => {
    const widths = {
        'A4': '210mm',
        'F4': '215mm', 
        'Legal': '216mm'
    };
    const heights = {
        'A4': '297mm',
        'F4': '330mm',
        'Legal': '356mm'
    };
    
    const baseHeight = heights[templateForm.paper_size] || heights['A4'];
    
    return {
        width: widths[templateForm.paper_size] || widths['A4'],
        minHeight: baseHeight,
        position: 'relative'
    };
});

// Page boundary position (for visual marker)
const paperBoundaryStyle = computed(() => {
    // Adjusted to line 40 (user requested)
    const heights = {
        'A4': '305mm',  // ~40 lines
        'F4': '345mm',
        'Legal': '370mm'
    };
    return {
        top: heights[templateForm.paper_size] || heights['A4']
    };
});



</script>

<template>
    <AdminLayout>
        <Head title="Pengaturan Surat" />
        
        <div class="content container-fluid pb-5">
            <!-- Radiant Header Card -->
            <div class="card border-0 shadow-lg rounded-4 mb-4 overflow-hidden position-relative" style="background: linear-gradient(135deg, #4f46e5 0%, #3b82f6 100%);">
                <div class="card-body p-4 p-lg-5 position-relative z-1">
                    <div class="row align-items-center">
                        <div class="col-lg-8">
                            <div class="d-flex align-items-center gap-3 mb-2">
                                <div class="icon-box bg-white rounded-3 text-primary p-2">
                                    <i class="fas fa-envelopes-bulk fs-3"></i>
                                </div>
                                <h2 class="fw-bold text-white mb-0 ls-tight">Pengaturan Surat</h2>
                            </div>
                            <p class="text-white-50 mb-0 fs-5">Kelola Kop Surat, Template, dan Format Dokumen Sekolah.</p>
                        </div>
                    </div>
                </div>
                <!-- Decorative Background -->
                <div class="position-absolute bottom-0 end-0 opacity-10 me-n5 mb-n5">
                    <i class="fas fa-file-contract" style="font-size: 10rem; color: white;"></i>
                </div>
            </div>

            <!-- Custom Tabs -->
            <div class="d-flex justify-content-center mb-4">
                <div class="bg-white p-1 rounded-pill shadow-sm d-inline-flex">
                    <button 
                        class="btn rounded-pill px-4 fw-bold transition-all"
                        :class="activeTab === 'kop' ? 'btn-primary shadow' : 'btn-light bg-transparent text-muted'"
                        @click="activeTab = 'kop'"
                    >
                        <i class="fas fa-heading me-2"></i> Kop Surat
                    </button>
                    <button 
                        class="btn rounded-pill px-4 fw-bold transition-all ms-2"
                        :class="activeTab === 'templates' ? 'btn-primary shadow' : 'btn-light bg-transparent text-muted'"
                        @click="activeTab = 'templates'"
                    >
                        <i class="fas fa-layer-group me-2"></i> Template Surat
                    </button>
                </div>
            </div>

            <!-- TAB 1: KOP SURAT -->
            <Transition name="fade" mode="out-in">
                <div v-if="activeTab === 'kop'" key="kop">
                    <div class="row g-4">
                        <!-- EDITOR KIRI -->
                        <div class="col-lg-4">
                            <div class="card border-0 shadow-sm h-100 rounded-4 overflow-hidden">
                                <div class="card-header bg-white border-0 pt-4 px-4 pb-0">
                                    <h5 class="fw-bold mb-1 text-primary">Editor Data</h5>
                                    <p class="text-muted small">Atur logo dan teks kop di sini.</p>
                                </div>
                                <div class="card-body p-4">
                                    
                                    <!-- Upload Logos -->
                                    <div class="mb-4">
                                        <label class="form-label fw-bold text-dark small text-uppercase">Logo Instansi</label>
                                        <div class="d-flex gap-3">
                                            <div class="w-50">
                                                <div class="logo-uploader border rounded p-2 text-center bg-light cursor-pointer position-relative mb-2">
                                                    <img v-if="previewLogoKiri" :src="previewLogoKiri" class="object-fit-contain" style="height: 50px;">
                                                    <i v-else class="fas fa-image text-muted fs-2 opacity-50"></i>
                                                    <input type="file" class="position-absolute top-0 start-0 w-100 h-100 opacity-0 cursor-pointer" accept="image/*" @change="e => handleFile(e, 'logo_kiri')">
                                                </div>
                                                <div class="text-center small text-muted">Kiri</div>
                                            </div>
                                            <div class="w-50">
                                                <div class="logo-uploader border rounded p-2 text-center bg-light cursor-pointer position-relative mb-2">
                                                    <img v-if="previewLogoKanan" :src="previewLogoKanan" class="object-fit-contain" style="height: 50px;">
                                                    <i v-else class="fas fa-image text-muted fs-2 opacity-50"></i>
                                                    <input type="file" class="position-absolute top-0 start-0 w-100 h-100 opacity-0 cursor-pointer" accept="image/*" @change="e => handleFile(e, 'logo_kanan')">
                                                </div>
                                                <div class="text-center small text-muted">Kanan</div>
                                            </div>
                                        </div>
                                    </div>

                                    <hr class="border-dashed my-4 opacity-50">

                                    <!-- Text Inputs -->
                                    <div class="mb-3">
                                        <label class="form-label fw-bold small text-muted">Baris 1 (Header Utama)</label>
                                        <input type="text" class="form-control" v-model="kopForm.kop_baris1" placeholder="PEMERINTAH KABUPATEN...">
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label fw-bold small text-muted">Baris 2 (Nama Instansi)</label>
                                        <input type="text" class="form-control fw-bold" v-model="kopForm.kop_baris2" placeholder="DINAS PENDIDIKAN...">
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label fw-bold small text-muted">Baris 3 (Sub Header/Alamat)</label>
                                        <input type="text" class="form-control" v-model="kopForm.kop_baris3">
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label fw-bold small text-muted">Baris 4 (Detail Kontak)</label>
                                        <textarea class="form-control" v-model="kopForm.kop_baris4" rows="2"></textarea>
                                    </div>
                                    <div class="mb-4">
                                        <label class="form-label fw-bold small text-muted">Baris 5 (Lainnya)</label>
                                        <input type="text" class="form-control" v-model="kopForm.kop_baris5">
                                    </div>

                                    <button type="button" class="btn btn-primary w-100 py-3 rounded-3 fw-bold shadow-sm hover-up" @click="saveKop" :disabled="kopForm.processing">
                                        <span v-if="kopForm.processing"><i class="fas fa-spinner fa-spin me-2"></i> Menyimpan...</span>
                                        <span v-else><i class="fas fa-check-circle me-2"></i> SIMPAN PERUBAHAN</span>
                                    </button>
                                </div>
                            </div>
                        </div>

                        <!-- LIVE PREVIEW KANAN -->
                        <div class="col-lg-8">
                            <div class="card border-0 shadow-sm h-100 rounded-4 bg-light">
                                <div class="card-header bg-transparent border-0 pt-4 px-4 text-center">
                                    <h5 class="fw-bold mb-1 text-secondary">Live Preview</h5>
                                    <p class="text-muted small">Tampilan Kop Surat akan terlihat seperti ini di kertas A4.</p>
                                </div>
                                <div class="card-body d-flex justify-content-center align-items-center p-0 pb-5">
                                    
                                    <!-- A4 PAPER SIMULATION -->
                                    <div class="paper-a4 bg-white shadow p-5 animate__animated animate__zoomIn">
                                        <div class="preview-kop d-flex align-items-center border-bottom border-dark border-3 py-3 px-4 position-relative" style="border-bottom-style: double !important;">
                                            
                                            <!-- Logo Kiri -->
                                            <div class="logo-slot text-center" style="width: 70px;">
                                                <img v-if="previewLogoKiri" :src="previewLogoKiri" class="object-fit-contain" style="max-height: 70px; max-width: 100%;">
                                            </div>

                                            <!-- Teks Tengah -->
                                            <div class="flex-grow-1 text-center px-2">
                                                <h5 class="fw-bold mb-0 text-uppercase font-serif" style="font-size: 12pt; letter-spacing: 0.5px; color: #000;" v-if="kopForm.kop_baris1">{{ kopForm.kop_baris1 }}</h5>
                                                <h4 class="fw-bold mb-0 text-uppercase font-serif" style="font-size: 14pt; letter-spacing: 1px; color: #000;" v-if="kopForm.kop_baris2">{{ kopForm.kop_baris2 }}</h4>
                                                <p class="fw-bold mb-0 font-serif" style="font-size: 12pt; color: #000;" v-if="kopForm.kop_baris3">{{ kopForm.kop_baris3 }}</p>
                                                <p class="mb-0 font-serif small" style="font-size: 10pt; color: #000;" v-if="kopForm.kop_baris4">{{ kopForm.kop_baris4 }}</p>
                                                <p class="mb-0 font-serif fst-italic small" style="font-size: 10pt; color: #000;" v-if="kopForm.kop_baris5">{{ kopForm.kop_baris5 }}</p>
                                            </div>

                                            <!-- Logo Kanan -->
                                            <div class="logo-slot text-center" style="width: 70px;">
                                                <img v-if="previewLogoKanan" :src="previewLogoKanan" class="object-fit-contain" style="max-height: 70px; max-width: 100%;">
                                            </div>

                                        </div>

                                        <!-- Dummy Content Lines -->
                                        <div class="mt-5 opacity-25">
                                            <div class="bg-dark rounded mb-3" style="height: 10px; width: 30%;"></div>
                                            <div class="bg-dark rounded mb-2" style="height: 8px; width: 100%;"></div>
                                            <div class="bg-dark rounded mb-2" style="height: 8px; width: 95%;"></div>
                                            <div class="bg-dark rounded mb-2" style="height: 8px; width: 90%;"></div>
                                            <div class="bg-dark rounded mb-2" style="height: 8px; width: 98%;"></div>
                                        </div>
                                    </div>
                                    <!-- End Paper -->
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </Transition>

            <!-- TAB 2: TEMPLATES -->
            <Transition name="fade" mode="out-in">
                <div v-if="activeTab === 'templates'" key="templates">
                    
                    <!-- View Mode -->
                    <div v-if="!isEditingTemplate" class="row">
                        <div class="col-12">
                            <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
                                <div class="card-header bg-white border-0 py-4 px-4 d-flex justify-content-between align-items-center">
                                    <div>
                                        <h5 class="fw-bold mb-1 text-dark">Daftar Template</h5>
                                        <p class="text-muted small mb-0">Kelola format surat yang sering digunakan.</p>
                                    </div>
                                    <button class="btn btn-primary rounded-pill px-4 fw-bold shadow-sm hover-up" @click="createTemplate">
                                        <i class="fas fa-plus me-2"></i> Buat Template
                                    </button>
                                </div>
                                <div class="card-body p-0">
                                    <div class="table-responsive">
                                        <table class="table table-hover align-middle mb-0">
                                            <thead class="bg-light">
                                                <tr>
                                                    <th class="ps-4" style="width: 50px;">No</th>
                                                    <th>Nama Template</th>
                                                    <th class="text-center" style="width: 150px;">Status Kop</th>
                                                    <th class="text-center" style="width: 150px;">Aksi</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr v-if="!templates || templates.length === 0">
                                                    <td colspan="4" class="text-center py-5">
                                                        <div class="empty-state">
                                                            <i class="fas fa-file-alt fa-3x text-muted opacity-50 mb-3"></i>
                                                            <h6 class="text-muted">Belum ada template surat</h6>
                                                            <p class="text-muted small">Klik tombol "Buat Template" untuk membuat template baru</p>
                                                        </div>
                                                    </td>
                                                </tr>
                                                <tr v-for="(item, index) in templates" :key="item.id">
                                                    <td class="ps-4 text-muted fw-medium">{{ index + 1 }}</td>
                                                    <td>
                                                        <div class="d-flex align-items-center">
                                                            <div class="template-icon me-3">
                                                                <i class="fas fa-file-alt"></i>
                                                            </div>
                                                            <div class="fw-semibold">{{ item.name }}</div>
                                                        </div>
                                                    </td>
                                                    <td class="text-center">
                                                        <span 
                                                            class="status-badge" 
                                                            :class="item.use_system_kop ? 'status-success' : 'status-secondary'"
                                                        >
                                                            <i class="fas me-1" :class="item.use_system_kop ? 'fa-check-circle' : 'fa-times-circle'"></i>
                                                            {{ item.use_system_kop ? 'Pakai Kop' : 'Tanpa Kop' }}
                                                        </span>
                                                    </td>
                                                    <td class="text-center">
                                                        <div class="d-flex gap-2 justify-content-center">
                                                            <button 
                                                                class="btn-icon-modern btn-icon-edit" 
                                                                @click="editTemplate(item)" 
                                                                title="Edit Template"
                                                            >
                                                                <i class="fas fa-edit"></i>
                                                            </button>
                                                            <button 
                                                                class="btn-icon-modern btn-icon-delete" 
                                                                @click="deleteTemplate(item.id)" 
                                                                title="Hapus Template"
                                                            >
                                                                <i class="fas fa-trash-alt"></i>
                                                            </button>
                                                        </div>
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Editor Mode -->
                    <div v-else class="row justify-content-center">
                        <div class="col-lg-10">
                            <div class="card border-0 shadow-lg rounded-4 overflow-hidden">
                                <div class="card-header bg-white border-bottom py-3 px-4 d-flex justify-content-between align-items-center">
                                    <h5 class="fw-bold mb-0 text-dark"><i class="fas fa-edit me-2 text-primary"></i> Editor Template</h5>
                                    <div>
                                        <button class="btn btn-primary fw-bold btn-sm me-2 rounded-pill px-4 shadow-sm" @click="saveTemplate">
                                            <i class="fas fa-save me-1"></i> Simpan
                                        </button>
                                        <button class="btn btn-light text-muted btn-sm rounded-pill px-3 border" @click="cancelTemplateEdit">
                                            <i class="fas fa-times me-1"></i> Tutup
                                        </button>
                                    </div>
                                </div>
                                <div class="card-body p-4 bg-white">
                                    <div class="row g-3 mb-4">
                                        <div class="col-md-5">
                                            <label class="form-label fw-bold small text-muted">Nama Template</label>
                                            <input type="text" class="form-control form-control-lg border bg-light" v-model="templateForm.name" placeholder="Contoh: Surat Keterangan Lulus">
                                        </div>
                                        <div class="col-md-3">
                                            <label class="form-label fw-bold small text-muted">Ukuran Kertas</label>
                                            <select class="form-select form-select-lg" v-model="templateForm.paper_size">
                                                <option value="A4">A4 (210 x 297 mm)</option>
                                                <option value="F4">F4/Folio (215 x 330 mm)</option>
                                                <option value="Legal">Legal (216 x 356 mm)</option>
                                            </select>
                                        </div>
                                        <div class="col-md-4">
                                            <label class="form-label fw-bold small text-muted">Opsi Kop</label>
                                            <div class="bg-white p-2 rounded shadow-sm border-0 d-flex align-items-center" style="height: 48px;">
                                                <div class="form-check form-switch ms-2">
                                                    <input class="form-check-input" type="checkbox" id="useKop" v-model="templateForm.use_system_kop">
                                                    <label class="form-check-label small fw-bold" for="useKop">Gunakan Kop Sistem</label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Tips -->
                                    <div class="mb-3 p-3 bg-blue-50 text-primary rounded-3 small">
                                        <i class="fas fa-lightbulb me-2 text-warning"></i> <strong>Tips:</strong> Gunakan tanda kurung kurawal ganda <code>&#123;&#123; nama_variabel &#125;&#125;</code> untuk menyisipkan data dinamis (Contoh: <code>&#123;&#123; nama_siswa &#125;&#125;</code>).
                                    </div>

                                    <!-- Editor Container - Ukuran kertas + toolbar + kop -->
                                    <div class="editor-paper-container bg-light p-4 d-flex justify-content-center">
                                        <div class="bg-white shadow-sm" :class="{'has-kop': templateForm.use_system_kop}" :style="editorStyle">
                                            <!-- PAGE BOUNDARY MARKER (Batas Halaman) -->
                                            <div class="page-boundary" :style="paperBoundaryStyle">
                                                <span>BATAS HALAMAN 1</span>
                                            </div>
                                            
                                            <!-- KOP SURAT PREVIEW (jika use_system_kop aktif) -->
                                            <div v-if="templateForm.use_system_kop && lembaga" class="kop-surat-preview">
                                                <table class="kop-table">
                                                    <tr>
                                                        <td class="logo-cell">
                                                            <img v-if="lembaga.logo_kiri" :src="getLogoPath(lembaga.logo_kiri)" class="logo-img">
                                                        </td>
                                                        <td class="text-cell">
                                                            <h3 v-if="lembaga.kop_baris1" class="kop-header1">{{ lembaga.kop_baris1 }}</h3>
                                                            <h2 v-if="lembaga.kop_baris2 || lembaga.nama_sekolah" class="kop-header2">{{ lembaga.kop_baris2 || lembaga.nama_sekolah }}</h2>
                                                            <h3 v-if="lembaga.kop_baris3 || lembaga.alamat" class="kop-header3">{{ lembaga.kop_baris3 || lembaga.alamat }}</h3>
                                                            <p v-if="lembaga.kop_baris4" class="kop-text">{{ lembaga.kop_baris4 }}</p>
                                                            <p v-if="lembaga.kop_baris5" class="kop-text">{{ lembaga.kop_baris5 }}</p>
                                                        </td>
                                                        <td class="logo-cell">
                                                            <img v-if="lembaga.logo_kanan" :src="getLogoPath(lembaga.logo_kanan)" class="logo-img">
                                                        </td>
                                                    </tr>
                                                </table>
                                            </div>
                                            
                                            <!-- Quill Editor dengan toolbar -->
                                            <QuillEditor 
                                                theme="snow" 
                                                v-model:content="templateForm.content" 
                                                contentType="html" 
                                                toolbar="full"
                                                style="flex: 1;"
                                                placeholder="Mulai desain surat di sini... Gunakan {{ variabel }} untuk data dinamis." 
                                            />
                                        </div>
                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </Transition>
        </div>
    </AdminLayout>
</template>

<style scoped>
.transition-all { transition: all 0.3s ease; }
.hover-up:hover { transform: translateY(-2px); }

.paper-a4 {
    width: 210mm;
    min-height: 297mm;
    background: white;
    transform: scale(0.7); /* Scale down for preview */
    transform-origin: top center;
    margin-bottom: -80px; /* Counteract empty space due to scaling */
}

/* Kop Surat Preview in Editor - MATCHES PDF */
.kop-surat-preview {
    width: 100%;
    border-bottom: 3px double #000;
    margin-bottom: 15px;
    width: 100%;
    border-bottom: 3px double #000;
    margin-bottom: 15px;
    padding: 15mm 15mm 8px 15mm;
    box-sizing: border-box;
    box-sizing: border-box;
    font-family: 'Times New Roman', serif;
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
    font-size: 13pt; /* Increased from 12pt */
    font-weight: bold;
    text-transform: uppercase;
    margin: 0;
}
.kop-header2 {
    font-size: 16pt; /* Increased from 14pt - Nama Sekolah */
    font-weight: bold;
    text-transform: uppercase;
    margin: 0;
}
.kop-header3 {
    font-size: 13pt; /* Increased from 12pt */
    font-weight: bold;
    margin: 0;
}
.kop-text {
    font-size: 11pt; /* Increased from 10pt */
    margin: 0;
}

/* Quill Editor Styling - MATCHES PDF OUTPUT */
:deep(.ql-container) {
    height: calc(100% - 45px) !important; /* Fixed height minus toolbar */
    overflow: auto !important;
}
:deep(.ql-editor) {
    font-family: 'Times New Roman', serif;
    font-size: 11pt;
    line-height: 1.15;
    letter-spacing: 1.5px; /* Calibrated to match mPDF character width (~414 chars per line) */
    padding: 15mm 15mm !important;
    min-height: 100%;
    overflow-y: visible !important;
}
/* If Kop is present, remove top padding from Editor because Kop already adds 20mm top spacing */
.has-kop :deep(.ql-editor) {
    padding-top: 0 !important;
}
:deep(.ql-editor p) {
    margin: 0 0 10px 0; /* Proper spacing for letters */
}
:deep(.ql-toolbar) {
    border: none !important;
    border-bottom: 1px solid #eee !important;
    background: #f8f9fa;
}
:deep(.ql-container) {
    border: none !important;
}

/* Quill Alignment Classes - MUST MATCH PDF */
:deep(.ql-align-right) {
    text-align: right;
}
:deep(.ql-align-center) {
    text-align: center;
}
:deep(.ql-align-justify) {
    text-align: justify;
}

/* Quill Indentation Classes - MUST MATCH PDF */
:deep(.ql-indent-1) { padding-left: 3em; }
:deep(.ql-indent-2) { padding-left: 6em; }
:deep(.ql-indent-3) { padding-left: 9em; }
:deep(.ql-indent-4) { padding-left: 12em; }
:deep(.ql-indent-5) { padding-left: 15em; }
:deep(.ql-indent-6) { padding-left: 18em; }
:deep(.ql-indent-7) { padding-left: 21em; }
:deep(.ql-indent-8) { padding-left: 24em; }

/* Page Boundary Marker (Garis Pembatas Halaman) */
.page-boundary {
    position: absolute;
    left: 0;
    right: 0;
    border-top: 3px dashed #dc3545; /* Garis merah putus-putus */
    pointer-events: none;
    z-index: 100;
}
.page-boundary span {
    position: absolute;
    right: 10px;
    top: -22px;
    background: #dc3545;
    color: white;
    font-size: 10px;
    padding: 2px 8px;
    border-radius: 4px;
    font-weight: bold;
}

/* Transitions */
.fade-enter-active,
.fade-leave-active {
  transition: opacity 0.3s ease;
}

.fade-enter-from,
.fade-leave-to {
  opacity: 0;
}

.font-serif {
    font-family: 'Times New Roman', Times, serif;
}

/* Template Table Styles */
.template-icon {
    width: 40px;
    height: 40px;
    border-radius: 10px;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 1rem;
}

/* Action Buttons - Modern Style */
.btn-icon-modern {
    width: 36px;
    height: 36px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    border: none;
    background-color: #f3f4f6;
    color: #6b7280;
    font-size: 0.9rem;
    transition: all 0.2s ease;
    cursor: pointer;
}

.btn-icon-modern:hover {
    transform: translateY(-2px);
    color: white;
}

.btn-icon-edit:hover {
    background-color: #f59e0b;
    box-shadow: 0 4px 12px rgba(245, 158, 11, 0.3);
}

.btn-icon-delete:hover {
    background-color: #ef4444;
    box-shadow: 0 4px 12px rgba(239, 68, 68, 0.3);
}

.empty-state {
    padding: 2rem;
}

/* Status Badge */
.status-badge {
    display: inline-flex;
    align-items: center;
    padding: 0.5rem 1rem;
    border-radius: 50px;
    font-size: 0.8rem;
    font-weight: 500;
}

.status-success {
    background-color: #d1fae5;
    color: #065f46;
}

.status-secondary {
    background-color: #e5e7eb;
    color: #4b5563;
    color: #4b5563;
}

/* Page Boundary Line */
.page-boundary {
    position: absolute;
    left: 0;
    width: 100%;
    border-bottom: 2px dashed #ef4444; /* Red Dashed Line */
    pointer-events: none; /* Ignore clicks */
    z-index: 10;
}
.page-boundary span {
    position: absolute;
    right: 0;
    top: -20px;
    background: #ef4444;
    color: white;
    font-size: 10px;
    padding: 2px 8px;
    border-radius: 4px 0 0 0;
    font-weight: bold;
}
</style>

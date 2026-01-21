<script setup>
import { Head, useForm } from '@inertiajs/vue3';
import AdminLayout from '@/Layouts/AdminLayout.vue';
import Swal from 'sweetalert2';
import { ref, onMounted } from 'vue';

const props = defineProps({
    section: Object,
});

const content = props.section?.content || {};
const items = content.items || [];

// Parse old date format "12 Okt 2025" to "2025-10-12" for date picker
const parseDate = (dateStr) => {
    if (!dateStr) return '';
    // If already in YYYY-MM-DD format, return as is
    if (/^\d{4}-\d{2}-\d{2}$/.test(dateStr)) return dateStr;
    
    const months = {
        'Jan': '01', 'Feb': '02', 'Mar': '03', 'Apr': '04', 'Mei': '05', 'Jun': '06',
        'Jul': '07', 'Agu': '08', 'Sep': '09', 'Okt': '10', 'Nov': '11', 'Des': '12'
    };
    
    const match = dateStr.match(/(\d{1,2})\s+(\w+)\s+(\d{4})/);
    if (match) {
        const day = match[1].padStart(2, '0');
        const month = months[match[2]] || '01';
        const year = match[3];
        return `${year}-${month}-${day}`;
    }
    return ''; // Can't parse, user needs to re-enter
};

// Parse old time format "08.00–10.30 WIB" or "08:00" to HH:MM
const parseTime = (timeStr) => {
    if (!timeStr) return '';
    // Already in HH:MM format
    if (/^\d{2}:\d{2}$/.test(timeStr)) return timeStr;
    // Format 08.00
    const match = timeStr.match(/(\d{1,2})[.:,](\d{2})/);
    if (match) {
        return `${match[1].padStart(2, '0')}:${match[2]}`;
    }
    return '';
};

// Extract start/end times from old combined format like "08.00–10.30 WIB"
const extractTimes = (timeStr) => {
    if (!timeStr) return { start: '', end: '' };
    const match = timeStr.match(/(\d{1,2}[.:,]\d{2})\s*[-–—]\s*(\d{1,2}[.:,]\d{2})/);
    if (match) {
        return {
            start: parseTime(match[1]),
            end: parseTime(match[2])
        };
    }
    return { start: parseTime(timeStr), end: '' };
};

const defaultItems = items.length > 0 ? items : [
    { location: '', date: '', time_start: '', time_end: '', title: '', description: '', link: '', image: null }
];

const form = useForm({
    tagline: content.tagline || 'Kegiatan',
    title: content.title || 'Agenda <span>Terbaru</span>',
    items: defaultItems.map(item => {
        // Handle legacy 'time' field
        const times = item.time ? extractTimes(item.time) : { start: '', end: '' };
        return {
            location: item.location,
            date: parseDate(item.date),
            time_start: item.time_start ? parseTime(item.time_start) : times.start,
            time_end: item.time_end ? parseTime(item.time_end) : times.end,
            title: item.title,
            description: item.description,
            link: item.link,
            image: item.image,
            image_file: null
        };
    }),
});

const previews = ref(form.items.map(item => item.image));
const titleEditor = ref(null);

// SVG placeholder as data URI (no external file needed)
const placeholderImage = "data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='400' height='300' viewBox='0 0 400 300'%3E%3Crect fill='%23e9ecef' width='400' height='300'/%3E%3Ctext x='50%25' y='45%25' dominant-baseline='middle' text-anchor='middle' font-family='Arial,sans-serif' font-size='48' fill='%23adb5bd'%3E📷%3C/text%3E%3Ctext x='50%25' y='60%25' dominant-baseline='middle' text-anchor='middle' font-family='Arial,sans-serif' font-size='14' fill='%23adb5bd'%3EKlik untuk upload gambar%3C/text%3E%3C/svg%3E";

const getPreview = (index) => {
    return previews.value[index] || placeholderImage;
};

// Check if any field in an item has an error
const hasItemError = (index) => {
    const fields = ['location', 'date', 'time_start', 'time_end', 'title', 'description', 'link'];
    return fields.some(field => form.errors[`items.${index}.${field}`]);
};

onMounted(() => {
    if (titleEditor.value) {
        titleEditor.value.innerHTML = form.title;
    }
});

const handleImageChange = (e, index) => {
    const file = e.target.files[0];
    if (file) {
        form.items[index].image_file = file; // Nested file upload
        previews.value[index] = URL.createObjectURL(file);
    }
};

const addItem = () => {
    form.items.push({
        location: '',
        date: '',
        time_start: '',
        time_end: '',
        title: '',
        description: '',
        link: '',
        image: null,
        image_file: null
    });
    previews.value.push(null);
};

const removeItem = (index) => {
    Swal.fire({
        title: 'Hapus Agenda?',
        text: "Item ini akan dihapus dari daftar.",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'Ya, Hapus!'
    }).then((result) => {
        if (result.isConfirmed) {
            form.items.splice(index, 1);
            previews.value.splice(index, 1);
        }
    });
};

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
    form.post('/admin/event', {
        forceFormData: true,
        preserveScroll: true,
        onSuccess: () => {
            Toast.fire({
                icon: 'success',
                title: 'Berhasil',
                text: 'Agenda berhasil diperbarui'
            });
        },
        onError: () => {
            Toast.fire({
                icon: 'error',
                title: 'Gagal',
                text: 'Mohon periksa isian yang ditandai merah'
            });
        }
    });
};
</script>

<template>
    <Head title="Edit Agenda Kegiatan" />

    <AdminLayout>
        <div class="content container-fluid">
            <!-- Radiant Header -->
            <div class="card border-0 rounded-4 mb-4 overflow-hidden" style="background: linear-gradient(135deg, #4361ee 0%, #3a0ca3 100%);">
                <div class="card-body p-4 position-relative">
                    <div class="d-flex align-items-center gap-4 position-relative z-1">
                        <div class="icon-box bg-white bg-opacity-25 rounded-4 p-3 d-flex align-items-center justify-content-center backdrop-blur" style="width: 64px; height: 64px;">
                            <i class="fas fa-calendar-alt fa-2x text-white"></i>
                        </div>
                        <div>
                            <h3 class="fw-bold mb-1 text-white">Agenda Kegiatan</h3>
                            <p class="mb-0 text-white-50">Kelola agenda terbaru di beranda</p>
                        </div>
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
                                <li class="breadcrumb-item active text-primary fw-bold" aria-current="page">Agenda</li>
                            </div>
                            <!-- Save Button Moved Here -->
                            <button class="btn btn-primary rounded-pill shadow-sm px-4 fw-bold" @click="submit" :disabled="form.processing">
                                <i class="fas fa-save me-2"></i> {{ form.processing ? 'Menyimpan...' : 'Simpan Perubahan' }}
                            </button>
                        </ol>
                    </nav>
                </div>
            </div>



            <!-- Header Section Form -->
            <div class="card border-0 shadow-sm rounded-4 mb-4">
                <div class="card-header bg-white py-3 border-bottom rounded-top-4">
                    <h5 class="fw-bold mb-0 text-dark"><i class="fas fa-heading me-2 text-primary"></i>Header Section</h5>
                </div>
                <div class="card-body p-4">
                    <div class="row g-4">
                        <div class="col-md-6">
                            <label class="form-label fw-bold text-secondary">Tagline</label>
                            <div class="input-group shadow-sm rounded-4 overflow-hidden">
                                <span class="input-group-text bg-white border-0 text-primary"><i class="fas fa-tag"></i></span>
                                <input type="text" class="form-control form-control-lg border-0 ps-0 text-dark fw-bold bg-white" :class="{'is-invalid': form.errors.tagline}" v-model="form.tagline" placeholder="Contoh: Kegiatan">
                            </div>
                            <div v-if="form.errors.tagline" class="invalid-feedback d-block fw-bold mt-1">{{ form.errors.tagline }}</div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold text-secondary">Judul Utama</label>
                            <div class="mb-2">
                                <button type="button" class="btn btn-warning btn-sm me-2 text-white px-3 fw-bold shadow-sm rounded-pill" @click="warnaiTeks">
                                    <i class="fas fa-highlighter me-1"></i> Highlight
                                </button>
                                <button type="button" class="btn btn-outline-secondary btn-sm px-3 fw-bold rounded-pill" @click="hapusWarna">
                                    <i class="fas fa-eraser me-1"></i> Reset
                                </button>
                            </div>
                            <div 
                                ref="titleEditor"
                                contenteditable="true"
                                class="form-control form-control-lg border-0 shadow-sm rounded-4 title-editor bg-white"
                                :class="{'is-invalid': form.errors.title}"
                                style="min-height: 48px;"
                                @input="updateTitle"
                                @blur="updateTitle"
                            ></div>
                            <div v-if="form.errors.title" class="invalid-feedback d-block fw-bold mt-1">{{ form.errors.title }}</div>
                            <small class="text-muted mt-1 d-block"><i class="fas fa-info-circle me-1"></i>Blok teks lalu klik tombol "Highlight" untuk memberi warna orange.</small>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Add Button -->
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h5 class="fw-bold text-secondary mb-0"><i class="fas fa-list me-2"></i>Daftar Agenda ({{ form.items.length }})</h5>
                <button class="btn btn-primary text-white fw-bold shadow-sm rounded-pill" @click="addItem">
                    <i class="fas fa-plus me-2"></i>Tambah Agenda
                </button>
            </div>

            <!-- Grid Items -->
            <div class="row g-4">
                <div v-for="(item, index) in form.items" :key="index" class="col-xl-6">
                    <div class="card h-100 border-0 shadow rounded-4 item-card" :class="{'border-danger border-2': hasItemError(index)}">
                        <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center border-bottom">
                            <span class="badge rounded-pill px-3 py-2 border fs-6" :class="hasItemError(index) ? 'bg-danger-subtle text-danger border-danger-subtle' : 'bg-primary-subtle text-primary border-primary-subtle'">
                                <i class="fas fa-calendar-day me-1"></i> Agenda #{{ index + 1 }}
                                <span v-if="hasItemError(index)" class="ms-1">⚠️</span>
                            </span>
                            <button class="btn btn-outline-danger btn-sm rounded-circle px-2" @click="removeItem(index)" title="Hapus Agenda">
                                <i class="fas fa-trash"></i>
                            </button>
                        </div>
                        <div class="card-body p-4">
                            <div class="row g-4">
                                <!-- Image & Main Info -->
                                <div class="col-md-5">
                                    <div class="mb-3">
                                        <label class="form-label fw-bold text-secondary mb-2">Foto Kegiatan</label>
                                        <div class="position-relative group-image-upload shadow-sm rounded-3 overflow-hidden">
                                            <div class="ratio ratio-4x3 bg-light">
                                                <img :src="getPreview(index)" class="object-fit-cover w-100 h-100 transition-scale">
                                            </div>
                                            <div class="hover-overlay position-absolute top-0 start-0 w-100 h-100 d-flex align-items-center justify-content-center" style="background: rgba(0,0,0,0.4); opacity: 0; transition: opacity 0.2s;">
                                                <label class="btn btn-light shadow-sm fw-bold rounded-pill">
                                                    <i class="fas fa-camera text-primary me-2"></i> Ganti Foto
                                                    <input type="file" class="d-none" accept="image/*" @change="e => handleImageChange(e, index)">
                                                </label>
                                            </div>
                                        </div>
                                        <div class="text-center mt-2">
                                            <span class="badge bg-secondary-subtle text-dark border"><i class="fas fa-expand me-1"></i>Rasio 4:3 (Landscape)</span>
                                        </div>
                                    </div>
                                </div>
                                
                                <!-- Inputs -->
                                <div class="col-md-7">
                                    <!-- Date & Time Group -->
                                    <div class="bg-light p-3 rounded-3 mb-3 border-0 shadow-sm">
                                        <h6 class="fw-bold text-dark mb-3 border-bottom pb-2"><i class="fas fa-clock me-2 text-primary"></i>Waktu & Tempat</h6>
                                        <div class="mb-2">
                                            <label class="form-label small text-secondary mb-1">Lokasi</label>
                                            <div class="input-group input-group-sm bg-white rounded-3 shadow-sm overflow-hidden">
                                                <span class="input-group-text bg-white text-primary border-0"><i class="fas fa-map-marker-alt"></i></span>
                                                <input type="text" class="form-control border-0 ps-0" :class="{'is-invalid': form.errors[`items.${index}.location`]}" v-model="item.location" placeholder="Contoh: Aula Sekolah">
                                            </div>
                                            <div v-if="form.errors[`items.${index}.location`]" class="invalid-feedback d-block fw-bold mt-1">{{ form.errors[`items.${index}.location`] }}</div>
                                        </div>
                                        <div class="mb-2">
                                            <label class="form-label small text-secondary mb-1">Tanggal</label>
                                            <div class="input-group input-group-sm bg-white rounded-3 shadow-sm overflow-hidden">
                                                <span class="input-group-text bg-white text-primary border-0"><i class="fas fa-calendar"></i></span>
                                                <input type="date" class="form-control border-0 ps-0" :class="{'is-invalid': form.errors[`items.${index}.date`]}" v-model="item.date">
                                            </div>
                                            <div v-if="form.errors[`items.${index}.date`]" class="invalid-feedback d-block fw-bold mt-1">{{ form.errors[`items.${index}.date`] }}</div>
                                        </div>
                                        <div class="row g-2">
                                            <div class="col-6">
                                                <label class="form-label small text-secondary mb-1">Dari Jam</label>
                                                <div class="input-group input-group-sm bg-white rounded-3 shadow-sm overflow-hidden">
                                                    <span class="input-group-text bg-white text-primary border-0"><i class="fas fa-hourglass-start"></i></span>
                                                    <input type="time" class="form-control border-0 ps-0" :class="{'is-invalid': form.errors[`items.${index}.time_start`]}" v-model="item.time_start">
                                                </div>
                                                <div v-if="form.errors[`items.${index}.time_start`]" class="invalid-feedback d-block fw-bold mt-1">{{ form.errors[`items.${index}.time_start`] }}</div>
                                            </div>
                                            <div class="col-6">
                                                <label class="form-label small text-secondary mb-1">Sampai Jam</label>
                                                <div class="input-group input-group-sm bg-white rounded-3 shadow-sm overflow-hidden">
                                                    <span class="input-group-text bg-white text-primary border-0"><i class="fas fa-hourglass-end"></i></span>
                                                    <input type="time" class="form-control border-0 ps-0" :class="{'is-invalid': form.errors[`items.${index}.time_end`]}" v-model="item.time_end">
                                                </div>
                                                <div v-if="form.errors[`items.${index}.time_end`]" class="invalid-feedback d-block fw-bold mt-1">{{ form.errors[`items.${index}.time_end`] }}</div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Details Group -->
                                    <div>
                                        <h6 class="fw-bold text-dark mb-3 border-bottom pb-2"><i class="fas fa-info-circle me-2 text-primary"></i>Detail Acara</h6>
                                        <div class="mb-2">
                                            <label class="form-label small text-secondary mb-1">Judul Agenda</label>
                                            <div class="input-group input-group-sm bg-white rounded-3 shadow-sm overflow-hidden">
                                                <span class="input-group-text bg-white text-primary border-0"><i class="fas fa-heading"></i></span>
                                                <input type="text" class="form-control fw-bold border-0 ps-0" :class="{'is-invalid': form.errors[`items.${index}.title`]}" v-model="item.title" placeholder="Judul...">
                                            </div>
                                            <div v-if="form.errors[`items.${index}.title`]" class="invalid-feedback d-block fw-bold mt-1">{{ form.errors[`items.${index}.title`] }}</div>
                                        </div>
                                        <div class="mb-2">
                                            <label class="form-label small text-secondary mb-1">Deskripsi Singkat</label>
                                            <div class="input-group input-group-sm bg-white rounded-3 shadow-sm overflow-hidden">
                                                <span class="input-group-text bg-white text-primary border-0"><i class="fas fa-align-left"></i></span>
                                                <textarea class="form-control border-0 ps-0" :class="{'is-invalid': form.errors[`items.${index}.description`]}" rows="2" v-model="item.description" placeholder="Deskripsi..."></textarea>
                                            </div>
                                            <div v-if="form.errors[`items.${index}.description`]" class="invalid-feedback d-block fw-bold mt-1">{{ form.errors[`items.${index}.description`] }}</div>
                                        </div>
                                        <div class="mb-0">
                                            <label class="form-label small text-secondary mb-1">Link / Tautan</label>
                                            <div class="input-group input-group-sm bg-white rounded-3 shadow-sm overflow-hidden">
                                                <span class="input-group-text bg-white text-primary border-0"><i class="fas fa-link"></i></span>
                                                <input type="text" class="form-control text-primary border-0 ps-0" :class="{'is-invalid': form.errors[`items.${index}.link`]}" v-model="item.link" placeholder="/agenda/...">
                                            </div>
                                            <div v-if="form.errors[`items.${index}.link`]" class="invalid-feedback d-block fw-bold mt-1">{{ form.errors[`items.${index}.link`] }}</div>
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
.header-card {
    background: linear-gradient(135deg, #4361ee 0%, #3a0ca3 100%);
}
.icon-box-header {
    width: 48px;
    height: 48px;
    background: rgba(255,255,255,0.2);
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.5rem;
}
.item-card {
    transition: transform 0.2s, box-shadow 0.2s;
}
.item-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 10px 20px rgba(0,0,0,0.1) !important;
}
.group-image-upload:hover .hover-overlay {
    opacity: 1 !important;
}
.group-image-upload:hover .transition-scale {
    transform: scale(1.05);
}
.transition-scale {
    transition: transform 0.3s ease;
}
.form-control:focus {
    box-shadow: 0 0 0 0.2rem rgba(67, 97, 238, 0.15);
}
.title-editor :deep(span) {
    color: #FDA31B;
    font-weight: 700;
}
</style>

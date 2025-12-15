<script setup>
import { onMounted, computed } from 'vue';
import { Head } from '@inertiajs/vue3';


const props = defineProps({
    surat: Object,
    lembaga: Object,
});

const processedContent = computed(() => {
    let content = props.surat.isi_surat || '';
    
    // Replace [NOMOR_SURAT]
    if (props.surat.kode_surat) {
        content = content.replace(/\[NOMOR_SURAT\]/g, props.surat.kode_surat);
    }

    // Replace dynamic {{variables}} using data_json
    const dynamicData = props.surat.data_json || {};
    // Match all {{variable}} patterns
    const regex = /{{(.*?)}}/g;
    
    content = content.replace(regex, (match, p1) => {
        const key = p1.trim(); // "nama" from "{{ nama }}" or "{{nama}}"
        return dynamicData[key] || match; // Return value or keep placeholder if missing
    });

    return content;
});

// Format Date correctly (e.g., "10 Desember 2024") from surat.data_json.tanggal_surat OR current date
const formattedDate = computed(() => {
    // Priority: 1. data_json.tanggal_surat, 2. surat.created_at, 3. Today
    let dateStr = props.surat.data_json?.tanggal_surat || props.surat.created_at || new Date();
    
    const d = new Date(dateStr);
    if (isNaN(d.getTime())) return dateStr; // Return raw if invalid date

    return new Intl.DateTimeFormat('id-ID', {
        day: 'numeric',
        month: 'long', 
        year: 'numeric'
    }).format(d);
});

// Get Place (Tempat Surat)
const tempatSurat = computed(() => {
    return props.surat.data_json?.tempat_surat || 'Tempat';
});

// (Removed Duplicate Code)

onMounted(() => {
    setTimeout(() => {
        window.print();
    }, 500);
});
</script>

<template>
    <Head :title="surat.jenis_surat" />
    
    <div class="print-container">
        <div class="a4-page">
             <!-- Header / Kop -->
            <div class="kop-surat d-flex align-items-center border-bottom pb-3 mb-4" 
                 style="border-bottom: 3px double black !important;"
                 v-if="surat.use_system_header">
                <div class="logo-section me-3" v-if="lembaga?.logo">
                    <img :src="`/storage/${lembaga.logo}`" alt="Logo" style="height: 100px; width: auto;">
                </div>
                <div class="text-center w-100">
                    <h5 class="mb-0 text-uppercase fw-bold" v-if="surat.kop_baris1">{{ surat.kop_baris1 }}</h5>
                    <h4 class="mb-0 text-uppercase fw-bold" v-if="surat.kop_baris2">{{ surat.kop_baris2 }}</h4>
                    <p class="mb-0 small" v-if="surat.kop_baris3">{{ surat.kop_baris3 }}</p>
                    <p class="mb-0 small" v-if="surat.kop_baris4">{{ surat.kop_baris4 }}</p>
                    <p class="mb-0 small" v-if="surat.kop_baris5">{{ surat.kop_baris5 }}</p>
                </div>
            </div>
            
            <!-- Content -->
            <div class="surat-content" v-html="processedContent"></div>
        </div>
    </div>
</template>

<style>
@media print {
    @page {
        size: A4;
        margin: 0; 
    }
    body {
        margin: 0;
        background: white;
        -webkit-print-color-adjust: exact;
        width: 210mm;
        height: auto; /* Allow multi-page */
        overflow: visible; /* Show all content */
    }
    .print-container {
        display: block; 
        padding: 0 !important;
        margin: 0 !important;
        width: 210mm;
        overflow: visible; 
    }
    .a4-page {
        width: 100%;
        margin: 0;
        /* Even tighter padding: 1cm top/bottom, 2cm sides */
        padding: 1cm 2cm; 
        box-sizing: border-box; 
        height: auto; /* Allow growth */
        overflow: visible; 
        position: relative; 
    }

    /* COMPACT CONTENT (Alternative to Zoom for stability) */
    /* No Zoom - Use Font/Line Height metrics to fit page */
    .kop-surat, .surat-content, .signature-section {
        /* zoom: 1; Reset */
    }

    /* COMPACT HEADER (Standardized) */
    .kop-surat {
        margin-bottom: 1rem !important; 
        padding-bottom: 0.5rem !important;
        border-bottom: 3px double black !important;
    }
    .logo-section img {
        height: 70px !important; 
        width: auto;
    }
    /* Standard Headings */
    .kop-surat h5 { font-size: 14pt !important; margin-bottom: 0; }
    .kop-surat h4 { font-size: 16pt !important; margin-bottom: 0; }
    .kop-surat p { font-size: 11pt !important; margin-bottom: 0; }

    /* CONTENT (Standard 12pt) */
    .surat-content p {
        margin-bottom: 0.5rem; 
        font-size: 12pt; 
        line-height: 1.35; 
    }
    
    /* SIGNATURE */
    .signature-section {
        margin-top: 1.5rem !important; 
        page-break-inside: avoid; 
    }
    .signature-spacer {
        height: 40px !important; /* Smaller spacer */
    }
    
    br { display: block; } 
    p:empty { display: none; } 
    
    /* QUILL ALIGNMENT SUPPORT */
    .ql-align-center { text-align: center; }
    .ql-align-right { text-align: right; }
    .ql-align-justify { text-align: justify; }

    /* QUILL INDENT SUPPORT (Standard CM Units - Unaffected by Font Size) */
    .ql-indent-1 { padding-left: 1.25cm; }
    .ql-indent-2 { padding-left: 2.50cm; }
    .ql-indent-3 { padding-left: 3.75cm; }
    .ql-indent-4 { padding-left: 5.00cm; }
    .ql-indent-5 { padding-left: 6.25cm; }
    .ql-indent-6 { padding-left: 7.50cm; }
    .ql-indent-7 { padding-left: 8.75cm; }
    .ql-indent-8 { padding-left: 10.00cm; }
    .ql-indent-9 { padding-left: 11.25cm; }
}

/* Screen styles - Mirroring Print Styles for WYSIWYG */
/* ... (Keep existing screen styles, just adding Quill support below) */
.a4-page .ql-align-center { text-align: center; }
.a4-page .ql-align-right { text-align: right; }
.a4-page .ql-align-justify { text-align: justify; }

.a4-page .ql-indent-1 { padding-left: 1.25cm; }
.a4-page .ql-indent-2 { padding-left: 2.50cm; }
.a4-page .ql-indent-3 { padding-left: 3.75cm; }
.a4-page .ql-indent-4 { padding-left: 5.00cm; }
.a4-page .ql-indent-5 { padding-left: 6.25cm; }
.a4-page .ql-indent-6 { padding-left: 7.50cm; }
.a4-page .ql-indent-7 { padding-left: 8.75cm; }
.a4-page .ql-indent-8 { padding-left: 10.00cm; }
.a4-page .ql-indent-9 { padding-left: 11.25cm; }

/* Force content not to scroll locally */
.surat-content, .surat-content p, .surat-content div {
    overflow: visible !important;
    white-space: pre-wrap !important; /* Preserve manual spaces/tabs */
    tab-size: 4; /* Standard tab size */
}

.print-container {
    background: #525659; /* Acrobat reader gray */
    min-height: 100vh;
    padding: 20px;
    display: flex;
    justify-content: center;
}
.a4-page {
    background: white;
    width: 210mm;
    height: 297mm; /* Match print height */
    padding: 1cm 2cm; /* Match print padding */
    box-shadow: 0 0 10px rgba(0,0,0,0.5);
    font-family: 'Times New Roman', Times, serif;
    color: black;
    box-sizing: border-box;
    overflow: hidden; /* Hide overflow just like print */
}

/* Simulate Print Scaling on Screen (Disabled for 1:1 fidelity) */
.a4-page .kop-surat, 
.a4-page .surat-content, 
.a4-page .signature-section {
    /* zoom: 0.92; REMOVED */
}

/* Match Screen Content Styles to Print */
.a4-page .kop-surat {
    margin-bottom: 1rem !important;
    padding-bottom: 0.5rem !important;
    border-bottom: 3px double black !important;
}
.a4-page .logo-section img {
    height: 70px !important; 
    width: auto;
}
.a4-page .kop-surat h5 { font-size: 14pt !important; margin-bottom: 0; }
.a4-page .kop-surat h4 { font-size: 16pt !important; margin-bottom: 0; }
.a4-page .kop-surat p { font-size: 11pt !important; margin-bottom: 0; }

.a4-page .surat-content p {
    margin-bottom: 0.5rem; 
    font-size: 12pt;
    line-height: 1.35; /* Match print line-height */
}

.a4-page .signature-section {
    margin-top: 2rem !important; 
}
.a4-page .signature-spacer {
    height: 50px !important; 
}

/* Hide empty P tags on screen too */
.a4-page p:empty { display: none; }
</style>

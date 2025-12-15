<template>
    <Head title="Cetak Laporan Keuangan" />
    <div class="print-container">
        <!-- Opsional: Tombol aksi hanya tampil di layar -->
        <div class="no-print mb-4 text-center">
            <button @click="printPage" class="btn btn-primary me-2"><i class="fas fa-print"></i> Cetak Dokumen</button>
            <button @click="closePage" class="btn btn-secondary"><i class="fas fa-times"></i> Tutup</button>
        </div>

        <!-- A. KOP LAPORAN -->
        <div class="header-section mb-0">
            <div class="row align-items-center mb-2">
                <div class="col-2 text-center">
                    <img :src="logoSrc" @error="handleImageError" alt="Logo" style="width: 80px; height: auto;">
                </div>
                <div class="col-8 text-center">
                    <h5 class="mb-0 text-uppercase fw-bold">YAYASAN PENDIDIKAN / DINAS PENDIDIKAN</h5>
                    <h3 class="fw-bold text-uppercase mb-1">{{ lembaga?.nama_sekolah || 'NAMA SEKOLAH' }}</h3>
                    <p class="mb-0 small">{{ lembaga?.alamat || 'Alamat Sekolah Belum Diisi' }}</p>
                    <p class="mb-0 small">Telp: {{ lembaga?.telepon || '-' }}</p>
                </div>
                <!-- Empty col to balance center if needed, or just col-10? Usually col-2 logo, col-10 text. Let's try col-2 and col-10 for better width usage -->
            </div>
            <div class="border-top border-dark border-3 mb-1"></div>
            <div class="border-top border-dark border-1 mb-4"></div>
        </div>

        <!-- B. JUDUL LAPORAN -->
        <div class="text-center mb-4">
            <h4 class="fw-bold text-uppercase mb-0">LAPORAN KEUANGAN SEKOLAH</h4>
            <h5 class="text-muted">Periode: {{ summary.label_periode }}</h5>
        </div>

        <!-- C. INFORMASI IDENTITAS -->
        <div class="mb-4">
            <table class="table-borderless">
                <tr>
                    <td style="width: 120px;">Nama Sekolah</td>
                    <td>: <strong>{{ lembaga?.nama_sekolah || '-' }}</strong></td>
                </tr>
                <tr>
                    <td>Tahun Ajaran</td>
                    <td>: {{ calculateTahunAjaran(summary.start_date) }}</td>
                </tr>
                <tr>
                    <td>Tanggal Cetak</td>
                    <td>: {{ new Date().toLocaleDateString('id-ID', { day: 'numeric', month: 'long', year: 'numeric'}) }}</td>
                </tr>
            </table>
        </div>

        <!-- D. TABEL LAPORAN KEUANGAN (BUKU KAS UMUM) -->
        <div class="section mb-2">
            <table class="table table-sm table-bordered border-dark text-sm w-100" style="border-collapse: collapse; border: 1px solid black;">
                <thead class="bg-light">
                    <tr class="text-center align-middle">
                        <th width="5%" style="border: 1px solid black;">No</th>
                        <th width="12%" style="border: 1px solid black;">Tanggal</th>
                        <th style="border: 1px solid black;">Uraian</th>
                        <th width="15%" style="border: 1px solid black;">Pemasukan</th>
                        <th width="15%" style="border: 1px solid black;">Pengeluaran</th>
                        <th width="15%" style="border: 1px solid black;">Saldo</th>
                    </tr>
                </thead>
                <tbody>
                    <!-- Row 1: Saldo Awal -->
                    <tr>
                        <td class="text-center" style="border: 1px solid black;">-</td>
                        <td class="text-center" style="border: 1px solid black;">{{ formatDate(summary.start_date) }}</td>
                        <td style="border: 1px solid black;" class="fw-bold">Saldo Awal</td>
                        <td class="text-end" style="border: 1px solid black;">-</td>
                        <td class="text-end" style="border: 1px solid black;">-</td>
                        <td class="text-end fw-bold" style="border: 1px solid black;">{{ formatRupiah(summary.saldo_awal) }}</td>
                    </tr>

                    <!-- Transaction Rows -->
                    <tr v-for="(row, index) in standardReport" :key="index">
                        <td class="text-center" style="border: 1px solid black;">{{ index + 1 }}</td>
                        <td class="text-center" style="border: 1px solid black;">{{ formatDate(row.tanggal) }}</td>
                        <td style="border: 1px solid black;">
                            <div>{{ row.sumber }}</div>
                            <div class="text-muted fst-italic small" v-if="row.kategori && row.kategori !== 'Lainnya'">({{ row.kategori }})</div>
                        </td>
                        <td class="text-end" style="border: 1px solid black;">
                            {{ row.masuk > 0 ? formatRupiah(row.masuk) : '-' }}
                        </td>
                        <td class="text-end" style="border: 1px solid black;">
                            {{ row.keluar > 0 ? formatRupiah(row.keluar) : '-' }}
                        </td>
                        <td class="text-end" style="border: 1px solid black;">
                            {{ formatRupiah(row.saldo) }}
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        <!-- E. REKAPITULASI (Simple List Style) -->
        <div class="row mb-4 brake-inside-avoid">
             <!-- Spacer to push to right or keep left? Diagram implies left or just below. I'll align it left-ish or full width container with aligned colons -->
             <div class="col-6">
                <!-- Empty or Note -->
             </div>
             <div class="col-6">
                 <table class="table-borderless w-100">
                    <tr>
                        <td class="py-1">Total Pemasukan</td>
                        <td class="py-1" style="width: 10px;">:</td>
                        <td class="text-end py-1">{{ formatRupiah(summary.pemasukan_periode) }}</td>
                    </tr>
                    <tr>
                        <td class="py-1">Total Pengeluaran</td>
                        <td class="py-1">:</td>
                        <td class="text-end py-1">{{ formatRupiah(summary.pengeluaran_periode) }}</td>
                    </tr>
                    <tr class="fw-bold border-top border-dark">
                        <td class="py-1">Saldo Akhir</td>
                        <td class="py-1">:</td>
                        <td class="text-end py-1">{{ formatRupiah(summary.saldo_akhir_periode) }}</td>
                    </tr>
                 </table>
             </div>
        </div>

        <!-- F. CATATAN -->
        <div class="mb-5 fst-italic text-muted" style="font-size: 10px;">
            * Laporan ini dihasilkan dari sistem keuangan sekolah dan digunakan sebagai dokumen administrasi resmi.
        </div>

        <!-- G. AREA TANDA TANGAN -->
        <div class="signatures mt-4 brake-inside-avoid">
            <div class="row text-center">
                <div class="col-6">
                    <p class="mb-5">Mengetahui,<br>Kepala Sekolah</p>
                    <br><br>
                    <p class="fw-bold text-decoration-underline mb-0 text-uppercase">{{ lembaga?.kepala_sekolah || '(....................................)' }}</p>
                    <small>NIP. ....................................</small>
                </div>
                <div class="col-6">
                    <p class="mb-5">Bendahara Sekolah</p>
                    <br><br>
                    <p class="fw-bold text-decoration-underline mb-0 text-uppercase">{{ $page.props.auth.user.name || '(....................................)' }}</p>
                    <small>NIP. ....................................</small>
                </div>
            </div>
        </div>

    </div>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue';
import { Head } from '@inertiajs/vue3';

const props = defineProps({
    summary: Object,
    laporan: Object,
    transaksi: Array,
    lembaga: Object
});

const logoSrc = computed(() => {
    return props.lembaga?.logo ? '/storage/' + props.lembaga.logo : '/assets/img/logo.png';
});

const handleImageError = (e) => {
    e.target.src = '/assets/img/logo.png';
};

const calculateTahunAjaran = (dateString) => {
    if (!dateString) return '-';
    const date = new Date(dateString);
    const month = date.getMonth() + 1; // 1-12
    const year = date.getFullYear();
    
    // Jika bulan >= 7 (Juli) maka tahun ajaran = Tahun/Tahun+1
    // Jika bulan < 7 (Januari-Juni) maka tahun ajaran = Tahun-1/Tahun
    
    if (month >= 7) {
        return `${year}/${year + 1}`;
    } else {
        return `${year - 1}/${year}`;
    }
};

const formatDate = (dateString) => {
    if (!dateString) return '-';
    // Format DD/MM/YYYY for strict table layout
    return new Date(dateString).toLocaleDateString('id-ID', { day: '2-digit', month: '2-digit', year: 'numeric' });
};

const standardReport = computed(() => {
    let currentBalance = Number(props.summary.saldo_awal || 0);
    
    // Sort transactions by date just in case, though controller does it too
    // Assuming backend sort is reliable.
    
    return props.transaksi.map(item => {
        let masuk = 0;
        let keluar = 0;
        let amount = Number(item.jumlah);

        if (item.tipe === 'Masuk') {
            masuk = amount;
            currentBalance += amount;
        } else {
            keluar = amount;
            currentBalance -= amount;
        }

        return {
            tanggal: item.tanggal,
            sumber: item.sumber,
            keterangan: item.keterangan,
            kategori: item.kategori ? item.kategori.nama : '',
            masuk: masuk,
            keluar: keluar,
            saldo: currentBalance
        };
    });
});

const formatRupiah = (value) => {
    return new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR', minimumFractionDigits: 0 }).format(value);
};

const printPage = () => {
    window.print();
};

const closePage = () => {
    window.close();
};

onMounted(() => {
    // Auto print when component loads
    window.print();
});
</script>

<style>
/* Reset standard body for print */
body {
    background-color: #eee;
    font-family: 'Times New Roman', Times, serif; /* Formal Font */
    color: #000;
}

/* A4 Page Container */
.print-container {
    background: white;
    width: 210mm;
    min-height: 297mm;
    margin: 20px auto;
    padding: 2cm; /* Margin 2-3cm */
    box-shadow: 0 0 10px rgba(0,0,0,0.1);
    position: relative;
    font-size: 11pt; /* Isi tabel 11-12pt */
}

h3, h4, h5 {
    color: #000 !important;
}

table {
    border-color: #000 !important;
}

@media print {
    @page {
        size: A4 portrait;
        margin: 2cm; /* Margin Setting */
    }
    
    body {
        margin: 0;
        background: white;
        font-family: 'Times New Roman', Times, serif;
    }

    .print-container {
        width: 100%;
        margin: 0;
        box-shadow: none;
        padding: 0;
        border: none;
    }

    .no-print {
        display: none !important;
    }
    
    .brake-inside-avoid {
        page-break-inside: avoid;
    }

    /* Force Black & White */
    * {
        -webkit-print-color-adjust: exact !important; 
        print-color-adjust: exact !important;
        color: #000 !important; /* Force text black */
    }
    
    .bg-light {
        background-color: #f0f0f0 !important; /* Keep slight grey for headers if needed, or remove for pure BW */
    }
    
    .text-success, .text-danger, .text-primary {
        color: #000 !important; /* Eliminate colors */
    }
    
    a {
        text-decoration: none;
        color: #000;
    }
}
</style>

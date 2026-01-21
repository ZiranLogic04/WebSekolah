<template>
    <AdminLayout>
        <div class="content container-fluid">
            <!-- Radiant Header -->
            <div class="card border-0 rounded-4 mb-4 overflow-hidden" style="background: linear-gradient(135deg, #4361ee 0%, #3a0ca3 100%);">
                <div class="card-body p-4 position-relative">
                    <div class="d-flex align-items-center gap-4 position-relative z-1">
                        <div class="icon-box bg-white bg-opacity-25 rounded-4 p-3 d-flex align-items-center justify-content-center backdrop-blur" style="width: 64px; height: 64px;">
                            <i class="fas fa-home fa-2x text-white"></i>
                        </div>
                        <div>
                            <h3 class="fw-bold mb-1 text-white">Kelola Konten Beranda</h3>
                            <p class="mb-0 text-white-50">Atur semua elemen yang tampil di halaman depan website</p>
                        </div>
                    </div>
                    <!-- Decorative Circle -->
                    <div class="position-absolute top-0 end-0 p-3 opacity-10 pointer-events-none">
                        <i class="fas fa-th-large fa-10x text-white transform-rotate-12" style="margin-right: -40px; margin-top: -40px;"></i>
                    </div>
                </div>
            </div>

            <!-- Breadcrumb -->
            <div class="row align-items-center mb-4">
                <div class="col">
                     <nav aria-label="breadcrumb">
                        <ol class="breadcrumb alert alert-primary border-0 rounded-4 shadow-sm py-3 px-4 mb-0 align-items-center">
                            <li class="breadcrumb-item"><a href="/admin/halaman" class="text-decoration-none fw-bold"><i class="fas fa-layer-group me-1"></i> Halaman</a></li>
                            <li class="breadcrumb-item active text-primary fw-bold" aria-current="page">Beranda</li>
                        </ol>
                    </nav>
                </div>
            </div>

            <!-- Content Grid -->
            <div class="row">
                <!-- Card 1: Slider -->
                <!-- Card 1: Slider -->
                <div class="col-xl-3 col-sm-6 col-12 d-flex">
                    <div class="card bg-white w-100 border-0 shadow-sm rounded-4 section-card hover-elevate">
                        <div class="card-body p-4">
                            <div class="d-flex align-items-center mb-3">
                                <div class="icon-box bg-soft-primary rounded-circle d-flex align-items-center justify-content-center text-primary me-3" style="width: 56px; height: 56px;">
                                    <i class="fas fa-images fa-2x"></i>
                                </div>
                            </div>
                            <h5 class="fw-bold mb-2 text-dark">Slider Utama</h5>
                            <p class="text-muted small mb-4">Kelola gambar slider banner</p>
                            <Link href="/admin/slider" class="btn btn-primary w-100 rounded-pill shadow-sm">
                                Kelola Slider <i class="fas fa-arrow-right ms-2 small"></i>
                            </Link>
                        </div>
                    </div>
                </div>
                
                <!-- Card 2: Feature Points -->
                <!-- Card 2: Feature Points -->
                <div class="col-xl-3 col-sm-6 col-12 d-flex">
                    <div class="card bg-white w-100 border-0 shadow-sm rounded-4 section-card hover-elevate">
                        <div class="card-body p-4">
                            <div class="d-flex align-items-center mb-3">
                                <div class="icon-box bg-soft-primary rounded-circle d-flex align-items-center justify-content-center text-primary me-3" style="width: 56px; height: 56px;">
                                    <i class="fas fa-star fa-2x"></i>
                                </div>
                            </div>
                            <h5 class="fw-bold mb-2 text-dark">4 Poin Unggulan</h5>
                            <p class="text-muted small mb-4">Kelola highlight di bawah slider</p>
                            <Link href="/admin/features" class="btn btn-primary w-100 rounded-pill shadow-sm">
                                Kelola Feature <i class="fas fa-arrow-right ms-2 small"></i>
                            </Link>
                        </div>
                    </div>
                </div>
                
                <!-- Card 3: Tentang Kami -->
                <!-- Card 3: Tentang Kami -->
                <div class="col-xl-3 col-sm-6 col-12 d-flex">
                    <div class="card bg-white w-100 border-0 shadow-sm rounded-4 section-card hover-elevate">
                        <div class="card-body p-4">
                            <div class="d-flex align-items-center mb-3">
                                <div class="icon-box bg-soft-primary rounded-circle d-flex align-items-center justify-content-center text-primary me-3" style="width: 56px; height: 56px;">
                                    <i class="fas fa-info-circle fa-2x"></i>
                                </div>
                            </div>
                            <h5 class="fw-bold mb-2 text-dark">Tentang Kami</h5>
                            <p class="text-muted small mb-4">Kelola section about beranda</p>
                            <Link href="/admin/about" class="btn btn-primary w-100 rounded-pill shadow-sm">
                                Kelola About <i class="fas fa-arrow-right ms-2 small"></i>
                            </Link>
                        </div>
                    </div>
                </div>
                
                <!-- Card 4: Counter/Statistik -->
                <!-- Card 4: Counter/Statistik -->
                <div class="col-xl-3 col-sm-6 col-12 d-flex">
                    <div class="card bg-white w-100 border-0 shadow-sm rounded-4 section-card hover-elevate">
                        <div class="card-body p-4">
                            <div class="d-flex align-items-center mb-3">
                                <div class="icon-box bg-soft-primary rounded-circle d-flex align-items-center justify-content-center text-primary me-3" style="width: 56px; height: 56px;">
                                    <i class="fas fa-chart-bar fa-2x"></i>
                                </div>
                            </div>
                            <h5 class="fw-bold mb-2 text-dark">Counter/Statistik</h5>
                            <p class="text-muted small mb-4">Angka siswa, guru, rombel, dll</p>
                            <Link href="/admin/counter" class="btn btn-primary w-100 rounded-pill shadow-sm">
                                Kelola Counter <i class="fas fa-arrow-right ms-2 small"></i>
                            </Link>
                        </div>
                    </div>
                </div>
                
                <!-- Card 5: Program & Layanan -->
                <div class="col-xl-3 col-sm-6 col-12 d-flex">
                    <div class="card bg-white w-100 border-0 shadow-sm rounded-4 section-card hover-elevate">
                        <div class="card-body p-4">
                            <div class="d-flex align-items-center mb-3">
                                <div class="icon-box bg-soft-primary rounded-circle d-flex align-items-center justify-content-center text-primary me-3" style="width: 56px; height: 56px;">
                                    <i class="fas fa-th-large fa-2x"></i>
                                </div>
                            </div>
                            <h5 class="fw-bold mb-2 text-dark">Program & Layanan</h5>
                            <p class="text-muted small mb-4">Header section program</p>
                            <Link href="/admin/program" class="btn btn-primary w-100 rounded-pill shadow-sm">
                                Kelola Program <i class="fas fa-arrow-right ms-2 small"></i>
                            </Link>
                        </div>
                    </div>
                </div>
                
                <!-- Card 6: Video Profil -->
                <div class="col-xl-3 col-sm-6 col-12 d-flex">
                    <div class="card bg-white w-100 border-0 shadow-sm rounded-4 section-card hover-elevate">
                        <div class="card-body p-4">
                            <div class="d-flex align-items-center mb-3">
                                <div class="icon-box bg-soft-primary rounded-circle d-flex align-items-center justify-content-center text-primary me-3" style="width: 56px; height: 56px;">
                                    <i class="fas fa-video fa-2x"></i>
                                </div>
                            </div>
                            <h5 class="fw-bold mb-2 text-dark">Video Profil</h5>
                            <p class="text-muted small mb-4">Video YouTube di beranda</p>
                            <Link href="/admin/video" class="btn btn-primary w-100 rounded-pill shadow-sm">
                                Kelola Video <i class="fas fa-arrow-right ms-2 small"></i>
                            </Link>
                        </div>
                    </div>
                </div>
                
                <!-- Card 7: Guru & Tendik -->
                <div class="col-xl-3 col-sm-6 col-12 d-flex">
                    <div class="card bg-white w-100 border-0 shadow-sm rounded-4 section-card hover-elevate">
                        <div class="card-body p-4">
                            <div class="d-flex align-items-center mb-3">
                                <div class="icon-box bg-soft-primary rounded-circle d-flex align-items-center justify-content-center text-primary me-3" style="width: 56px; height: 56px;">
                                    <i class="fas fa-users fa-2x"></i>
                                </div>
                            </div>
                            <h5 class="fw-bold mb-2 text-dark">Guru & Tendik</h5>
                            <p class="text-muted small mb-4">Tim pengajar di beranda</p>
                            <Link href="/admin/team" class="btn btn-primary w-100 rounded-pill shadow-sm text-white">
                                Kelola Team <i class="fas fa-arrow-right ms-2 small"></i>
                            </Link>
                        </div>
                    </div>
                </div>

                <!-- Card 8: Mengapa Memilih Kami -->
                <div class="col-xl-3 col-sm-6 col-12 d-flex">
                    <div class="card bg-white w-100 border-0 shadow-sm rounded-4 section-card hover-elevate">
                        <div class="card-body p-4">
                            <div class="d-flex align-items-center mb-3">
                                <div class="icon-box bg-soft-primary rounded-circle d-flex align-items-center justify-content-center text-primary me-3" style="width: 56px; height: 56px;">
                                    <i class="fas fa-check-circle fa-2x"></i>
                                </div>
                            </div>
                            <h5 class="fw-bold mb-2 text-dark">Mengapa Memilih Kami</h5>
                            <p class="text-muted small mb-4">Poin keunggulan sekolah</p>
                            <Link href="/admin/choose" class="btn btn-primary w-100 rounded-pill shadow-sm">
                                Kelola Section <i class="fas fa-arrow-right ms-2 small"></i>
                            </Link>
                        </div>
                    </div>
                </div>

                <!-- Card 9: Portofolio Kegiatan -->
                <div class="col-xl-3 col-sm-6 col-12 d-flex">
                    <div class="card bg-white w-100 border-0 shadow-sm rounded-4 section-card hover-elevate">
                        <div class="card-body p-4">
                            <div class="d-flex align-items-center mb-3">
                                <div class="icon-box bg-soft-primary rounded-circle d-flex align-items-center justify-content-center text-primary me-3" style="width: 56px; height: 56px;">
                                    <i class="fas fa-camera fa-2x"></i>
                                </div>
                            </div>
                            <h5 class="fw-bold mb-2 text-dark">Portofolio Kegiatan</h5>
                            <p class="text-muted small mb-4">6 Foto kegiatan unggulan</p>
                            <Link href="/admin/portfolio" class="btn btn-primary w-100 rounded-pill shadow-sm text-white">
                                Kelola Portofolio <i class="fas fa-arrow-right ms-2 small"></i>
                            </Link>
                        </div>
                    </div>
                </div>

                <!-- Card 10: Agenda Kegiatan -->
                <div class="col-xl-3 col-sm-6 col-12 d-flex">
                    <div class="card bg-white w-100 border-0 shadow-sm rounded-4 section-card hover-elevate">
                        <div class="card-body p-4">
                            <div class="d-flex align-items-center mb-3">
                                <div class="icon-box bg-soft-primary rounded-circle d-flex align-items-center justify-content-center text-primary me-3" style="width: 56px; height: 56px;">
                                    <i class="fas fa-calendar-alt fa-2x"></i>
                                </div>
                            </div>
                            <h5 class="fw-bold mb-2 text-dark">Agenda Kegiatan</h5>
                            <p class="text-muted small mb-4">Jadwal 4 event terbaru</p>
                            <Link href="/admin/event" class="btn btn-primary w-100 rounded-pill shadow-sm">
                                Kelola Agenda <i class="fas fa-arrow-right ms-2 small"></i>
                            </Link>
                        </div>
                    </div>
                </div>

                <!-- Card 11: Testimoni -->
                <div class="col-xl-3 col-sm-6 col-12 d-flex">
                    <div class="card bg-white w-100 border-0 shadow-sm rounded-4 section-card hover-elevate">
                        <div class="card-body p-4">
                            <div class="d-flex align-items-center mb-3">
                                <div class="icon-box bg-soft-primary rounded-circle d-flex align-items-center justify-content-center text-primary me-3" style="width: 56px; height: 56px;">
                                    <i class="fas fa-quote-right fa-2x"></i>
                                </div>
                            </div>
                            <h5 class="fw-bold mb-2 text-dark">Testimoni</h5>
                            <p class="text-muted small mb-4">Kata mereka tentang sekolah</p>
                            <Link href="/admin/testimonial" class="btn btn-primary w-100 rounded-pill shadow-sm">
                                Kelola Testimoni <i class="fas fa-arrow-right ms-2 small"></i>
                            </Link>
                        </div>
                    </div>
                </div>

                <!-- Card 12: Partner/Mitra -->
                <div class="col-xl-3 col-sm-6 col-12 d-flex">
                    <div class="card bg-white w-100 border-0 shadow-sm rounded-4 section-card hover-elevate">
                        <div class="card-body p-4">
                            <div class="d-flex align-items-center mb-3">
                                <div class="icon-box bg-soft-primary rounded-circle d-flex align-items-center justify-content-center text-primary me-3" style="width: 56px; height: 56px;">
                                    <i class="fas fa-handshake fa-2x"></i>
                                </div>
                            </div>
                            <h5 class="fw-bold mb-2 text-dark">Mitra/Partner</h5>
                            <p class="text-muted small mb-4">Logo kerja sama di beranda</p>
                            <Link href="/admin/partner" class="btn btn-primary w-100 rounded-pill shadow-sm">
                                Kelola Mitra <i class="fas fa-arrow-right ms-2 small"></i>
                            </Link>
                        </div>
                    </div>
                </div>

                <!-- Card 13: Footer Links -->
                <div class="col-xl-3 col-sm-6 col-12 d-flex">
                    <div class="card bg-white w-100 border-0 shadow-sm rounded-4 section-card hover-elevate">
                        <div class="card-body p-4">
                            <div class="d-flex align-items-center mb-3">
                                <div class="icon-box bg-soft-primary rounded-circle d-flex align-items-center justify-content-center text-primary me-3" style="width: 56px; height: 56px;">
                                    <i class="fas fa-link fa-2x"></i>
                                </div>
                            </div>
                            <h5 class="fw-bold mb-2 text-dark">Footer Links</h5>
                            <p class="text-muted small mb-4">Navigasi bawah website</p>
                            <Link href="/admin/footer-links" class="btn btn-primary w-100 rounded-pill shadow-sm">
                                Kelola Footer <i class="fas fa-arrow-right ms-2 small"></i>
                            </Link>
                        </div>
                    </div>
                </div>
                
                <!-- More cards can be added here later -->
            </div>
        </div>
    </AdminLayout>
</template>

<script setup>
import { Link } from '@inertiajs/vue3';
import AdminLayout from '@/Layouts/AdminLayout.vue';
</script>

<style scoped>
.section-card {
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
}

.hover-elevate:hover {
    transform: translateY(-5px);
    box-shadow: 0 10px 30px rgba(0,0,0,0.1) !important;
}

.icon-box {
    display: flex;
    align-items: center;
    justify-content: center;
}

.backdrop-blur {
    backdrop-filter: blur(8px);
    -webkit-backdrop-filter: blur(8px);
}

.transform-rotate-12 {
    transform: rotate(12deg);
}

/* Soft colors for icon boxes */
.bg-soft-primary { background-color: rgba(67, 97, 238, 0.1) !important; }
.text-primary { color: #4361ee !important; }
.btn-primary { 
    background-color: #4361ee; 
    border-color: #4361ee;
}
.btn-primary:hover { 
    background-color: #0b5ed7; 
    border-color: #0a58ca;
}

</style>

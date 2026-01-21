<template>
    <AdminLayout>
        <Head title="Data Alumni" />
        
        <div class="content container-fluid pb-5">
            <!-- Radiant Header Card -->
            <div class="card border-0 shadow-lg rounded-4 mb-4 overflow-hidden position-relative" style="background: linear-gradient(135deg, #4f46e5 0%, #3b82f6 100%);">
                <div class="card-body p-4 p-lg-5 position-relative z-1">
                    <div class="row align-items-center">
                        <div class="col-lg-8">
                            <div class="d-flex align-items-center gap-3 mb-2">
                                <div class="icon-box bg-white rounded-3 text-primary p-2">
                                    <i class="fas fa-user-graduate fs-3"></i>
                                </div>
                                <h2 class="fw-bold text-white mb-0 ls-tight">Data Alumni</h2>
                            </div>
                            <p class="text-white-50 mb-0 fs-5">Arsip data siswa yang telah lulus atau mutasi.</p>
                        </div>
                    </div>
                </div>
                <!-- Decorative Background -->
                <div class="position-absolute bottom-0 end-0 opacity-10 me-n5 mb-n5">
                    <i class="fas fa-graduation-cap" style="font-size: 10rem; color: white;"></i>
                </div>
            </div>

        <div class="row">
            <!-- Modern Table Card -->
            <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
                <div class="card-header bg-white p-4 border-bottom">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h5 class="fw-bold mb-0 text-dark">Daftar Alumni</h5>
                            <small class="text-muted">Total {{ alumni.total }} alumni terdata</small>
                        </div>
                        <!-- Filter Logic Inline -->
                         <div class="d-flex gap-2">
                             <div class="input-group">
                                <span class="input-group-text bg-white border-end-0 ps-3 rounded-start-pill text-primary">
                                    <i class="fas fa-search"></i>
                                </span>
                                <input type="text" class="form-control bg-white border-start-0 rounded-end-pill ps-0" placeholder="Cari Nama/NIS..." v-model="params.search" @input="filter">
                            </div>
                         </div>
                    </div>
                    
                    <!-- Advanced Filters Row -->
                    <div class="row mt-3 g-2">
                         <div class="col-md-3">
                            <select class="form-select bg-light border-0 rounded-3" v-model="params.tahun_lulus" @change="filter">
                                <option value="">Semua Tahun Lulus</option>
                                <option v-for="tahun in tahunLulusList" :key="tahun" :value="tahun">{{ tahun }}</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <select class="form-select bg-light border-0 rounded-3" v-model="params.status" @change="filter">
                                <option value="">Semua Status</option>
                                <option value="Lulus">Lulus</option>
                                <option value="Pindah">Pindah</option>
                                <option value="Keluar">Keluar</option>
                            </select>
                        </div>
                    </div>
                </div>

                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="bg-primary text-white">
                                <tr>
                                    <th class="px-4 py-3 border-0 rounded-start-2" width="60">No</th>
                                    <th class="px-4 py-3 border-0">NIS</th>
                                    <th class="px-4 py-3 border-0">Nama</th>
                                    <th class="px-4 py-3 border-0 text-center">Kelas Terakhir</th>
                                    <th class="px-4 py-3 border-0 text-center">Tahun Lulus</th>
                                    <th class="px-4 py-3 border-0 text-center">Status</th>
                                    <th class="px-4 py-3 border-0 rounded-end-2 text-center" width="100">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-if="alumni.data.length === 0">
                                    <td colspan="7" class="text-center py-5">
                                        <div class="empty-state">
                                            <div class="mb-3">
                                                <i class="fas fa-user-graduate fa-3x text-muted opacity-25"></i>
                                            </div>
                                            <h6 class="fw-bold text-dark mb-1">Tidak ada data alumni</h6>
                                            <p class="text-muted small mb-0">Coba ubah filter pencarian Anda.</p>
                                        </div>
                                    </td>
                                </tr>
                                <tr v-for="(siswa, index) in alumni.data" :key="siswa.id">
                                    <td class="px-4 py-3 text-center fw-medium text-muted">{{ alumni.from + index }}</td>
                                    <td class="px-4 py-3 fw-medium text-primary">{{ siswa.nis }}</td>
                                    <td class="px-4 py-3 fw-semibold text-dark">{{ siswa.nama }}</td>
                                    <td class="px-4 py-3 text-center">
                                        <span class="badge bg-light text-dark border">{{ siswa.kelas }}</span>
                                    </td>
                                    <td class="px-4 py-3 text-center fw-medium">{{ siswa.tahun_lulus || '-' }}</td>
                                    <td class="px-4 py-3 text-center">
                                        <span class="badge rounded-pill px-3" :class="{
                                            'bg-success-subtle text-success border border-success-subtle': siswa.status === 'Lulus', 
                                            'bg-warning-subtle text-warning border border-warning-subtle': siswa.status === 'Pindah',
                                            'bg-danger-subtle text-danger border border-danger-subtle': siswa.status === 'Keluar' || siswa.status === 'Dikeluarkan',
                                            'bg-secondary-subtle text-secondary border border-secondary-subtle': siswa.status === 'Mengundurkan Diri'
                                        }">
                                            <i class="fas me-1" :class="{
                                                'fa-graduation-cap': siswa.status === 'Lulus',
                                                'fa-school': siswa.status === 'Pindah',
                                                'fa-user-slash': siswa.status === 'Keluar' || siswa.status === 'Dikeluarkan',
                                                'fa-hand-paper': siswa.status === 'Mengundurkan Diri'
                                            }"></i>{{ siswa.status }}
                                        </span>
                                    </td>
                                    <td class="px-4 py-3 text-center">
                                        <button type="button" class="btn btn-sm btn-light text-primary hover-shadow rounded-circle" style="width: 32px; height: 32px;" @click="viewDetail(siswa)" title="Detail">
                                            <i class="fas fa-eye fa-xs"></i>
                                        </button>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    
                    <!-- Pagination -->
                    <div class="d-flex justify-content-between align-items-center p-4 border-top bg-light" v-if="alumni.links.length > 3">
                            <div class="text-muted small">
                            Menampilkan <span class="fw-bold">{{ alumni.from }}</span> sampai <span class="fw-bold">{{ alumni.to }}</span> dari <span class="fw-bold">{{ alumni.total }}</span> data
                        </div>
                        <nav aria-label="Page navigation">
                            <ul class="pagination pagination-sm mb-0 gap-1">
                                <li v-for="(link, index) in alumni.links" :key="index" class="page-item" :class="{'active': link.active, 'disabled': !link.url}">
                                    <Link v-if="link.url" class="page-link rounded-2 border-0" :href="link.url" v-html="link.label" preserve-state :class="{'bg-primary text-white': link.active, 'bg-white text-muted': !link.active}"></Link>
                                    <span v-else class="page-link rounded-2 border-0 bg-transparent text-muted" v-html="link.label"></span>
                                </li>
                            </ul>
                        </nav>
                    </div>
                </div>
            </div>
        </div>
    </div>

        <!-- Modern Detail Modal -->
        <div
            v-if="showDetailModal"
            class="modal fade show d-block"
            tabindex="-1"
            style="background: rgba(0, 0, 0, 0.5); z-index: 1050"
        >
            <div
                class="modal-dialog modal-lg modal-dialog-centered"
                style="z-index: 1060"
            >
                <div class="modal-content modern-modal">
                    <div class="modal-header">
                        <div class="modal-title">
                            <i class="modal-icon fas fa-user-graduate"></i>
                            <div>
                                <h5>Detail Data Alumni</h5>
                                <p class="modal-subtitle text-muted">
                                    Informasi lengkap data alumni
                                </p>
                            </div>
                        </div>
                        <button
                            type="button"
                            class="btn-close"
                            @click="showDetailModal = false"
                        ></button>
                    </div>
                    <div class="modal-body p-0 bg-light">
                        <!-- Profile Header -->
                        <div class="bg-white px-4 pb-4 pt-2 mb-3">
                            <div class="d-flex align-items-center gap-4">
                                <div class="flex-shrink-0 position-relative">
                                    <div class="avatar-circle shadow" style="width: 85px; height: 85px; font-size: 2.2rem; background: linear-gradient(135deg, #4f46e5 0%, #3b82f6 100%); border: 3px solid #fff;">
                                        {{ selectedAlumni ? getInitials(selectedAlumni.nama) : '' }}
                                    </div>
                                    <div 
                                        class="position-absolute bottom-0 end-0 bg-white rounded-circle p-1 shadow-sm border"
                                        style="width: 30px; height: 30px; display: flex; align-items: center; justify-content: center;"
                                    >
                                        <i :class="selectedAlumni?.jenis_kelamin === 'L' ? 'fas fa-mars text-primary small' : 'fas fa-venus text-danger small'"></i>
                                    </div>
                                </div>
                                <div class="flex-grow-1">
                                    <h4 class="fw-bold text-dark mb-1">{{ selectedAlumni?.nama }}</h4>
                                    <div class="d-flex flex-wrap align-items-center gap-3 text-muted mb-2">
                                        <span class="d-flex align-items-center gap-2 small bg-light px-3 py-1 rounded-pill"><i class="fas fa-id-card text-secondary"></i> {{ selectedAlumni?.nis }}</span>
                                        <span class="d-flex align-items-center gap-2 small bg-light px-3 py-1 rounded-pill"><i class="fas fa-layer-group text-secondary"></i> {{ selectedAlumni?.kelas }}</span>
                                    </div>
                                    <span class="badge rounded-pill px-3 py-1" :class="getStatusClass(selectedAlumni?.status)">
                                        {{ selectedAlumni?.status }}
                                    </span>
                                </div>
                            </div>
                        </div>

                        <div class="px-4 pb-4">
                            <!-- Modern Soft Tabs -->
                            <div class="p-1 bg-white rounded-pill border mb-4 d-inline-block w-100 shadow-sm">
                                <ul class="nav nav-pills nav-fill" role="tablist">
                                    <li class="nav-item user-select-none">
                                        <a class="nav-link active rounded-pill fw-medium" data-bs-toggle="tab" href="#detailPribadi">Data Diri</a>
                                    </li>
                                    <li class="nav-item user-select-none">
                                        <a class="nav-link rounded-pill fw-medium" data-bs-toggle="tab" href="#detailAlamat">Alamat</a>
                                    </li>
                                    <li class="nav-item user-select-none">
                                        <a class="nav-link rounded-pill fw-medium" data-bs-toggle="tab" href="#detailOrtu">Keluarga</a>
                                    </li>
                                    <!-- Use 'Sekolah' tab for generic purposes or rename logic if needed, but keeping consistent with Siswa -->
                                    <li class="nav-item user-select-none">
                                        <a class="nav-link rounded-pill fw-medium" data-bs-toggle="tab" href="#detailSekolah">Akademik</a>
                                    </li>
                                    <li class="nav-item user-select-none">
                                        <a class="nav-link rounded-pill fw-medium" data-bs-toggle="tab" href="#detailDokumen">Dokumen</a>
                                    </li>
                                </ul>
                            </div>

                            <!-- Content Area -->
                            <div class="tab-content bg-white p-4 rounded-4 shadow-sm border" style="min-height: 350px;">
                                <!-- Tab: Data Diri -->
                                <div class="tab-pane fade show active" id="detailPribadi">
                                    <h6 class="text-uppercase text-secondary fw-bold small mb-4 tracking-wide">Informasi Pribadi</h6>
                                    <div class="row g-4 mb-5">
                                        <div class="col-md-6">
                                            <div class="d-flex gap-3">
                                                <div class="text-primary mt-1"><i class="fas fa-venus-mars"></i></div>
                                                <div>
                                                    <div class="small text-muted mb-1">Jenis Kelamin</div>
                                                    <div class="fw-semibold text-dark">{{ selectedAlumni?.jenis_kelamin === 'L' ? 'Laki-laki' : 'Perempuan' }}</div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="d-flex gap-3">
                                                <div class="text-primary mt-1"><i class="fas fa-pray"></i></div>
                                                <div>
                                                    <div class="small text-muted mb-1">Agama</div>
                                                    <div class="fw-semibold text-dark">{{ selectedAlumni?.agama || '-' }}</div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="d-flex gap-3">
                                                <div class="text-primary mt-1"><i class="fas fa-calendar-alt"></i></div>
                                                <div>
                                                    <div class="small text-muted mb-1">Tempat, Tanggal Lahir</div>
                                                    <div class="fw-semibold text-dark">{{ selectedAlumni?.tempat_lahir || '-' }}, {{ formatDate(selectedAlumni?.tanggal_lahir) }}</div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="d-flex gap-3">
                                                <div class="text-primary mt-1"><i class="fas fa-id-badge"></i></div>
                                                <div>
                                                    <div class="small text-muted mb-1">NIK</div>
                                                    <div class="fw-semibold text-dark">{{ selectedAlumni?.nik || '-' }}</div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="d-flex gap-3">
                                                <div class="text-primary mt-1"><i class="fas fa-file-invoice"></i></div>
                                                <div>
                                                    <div class="small text-muted mb-1">No. KK (NKK)</div>
                                                    <div class="fw-semibold text-dark">{{ selectedAlumni?.nkk || '-' }}</div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="d-flex gap-3">
                                                <div class="text-primary mt-1"><i class="fas fa-file-alt"></i></div>
                                                <div>
                                                    <div class="small text-muted mb-1">No. Akte</div>
                                                    <div class="fw-semibold text-dark">{{ selectedAlumni?.no_akte || '-' }}</div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="d-flex gap-3">
                                                <div class="text-primary mt-1"><i class="fas fa-child"></i></div>
                                                <div>
                                                    <div class="small text-muted mb-1">Anak Ke-</div>
                                                    <div class="fw-semibold text-dark">{{ selectedAlumni?.anak_ke || '-' }}</div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="d-flex gap-3">
                                                <div class="text-primary mt-1"><i class="fas fa-users"></i></div>
                                                <div>
                                                    <div class="small text-muted mb-1">Jumlah Saudara</div>
                                                    <div class="fw-semibold text-dark">{{ selectedAlumni?.jumlah_saudara || '-' }}</div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <h6 class="text-uppercase text-secondary fw-bold small mb-4 tracking-wide border-top pt-4">Kesehatan & Lainnya</h6>
                                    <div class="row g-4">
                                        <div class="col-md-4">
                                            <div class="d-flex gap-3">
                                                <div class="text-danger mt-1"><i class="fas fa-heartbeat"></i></div>
                                                <div>
                                                    <div class="small text-muted mb-1">Golongan Darah</div>
                                                    <div class="fw-semibold text-dark">{{ selectedAlumni?.golongan_darah || '-' }}</div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="d-flex gap-3">
                                                <div class="text-danger mt-1"><i class="fas fa-ruler-vertical"></i></div>
                                                <div>
                                                    <div class="small text-muted mb-1">Tinggi Badan</div>
                                                    <div class="fw-semibold text-dark">{{ selectedAlumni?.tinggi_badan ? selectedAlumni.tinggi_badan + ' cm' : '-' }}</div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="d-flex gap-3">
                                                <div class="text-danger mt-1"><i class="fas fa-weight"></i></div>
                                                <div>
                                                    <div class="small text-muted mb-1">Berat Badan</div>
                                                    <div class="fw-semibold text-dark">{{ selectedAlumni?.berat_badan ? selectedAlumni.berat_badan + ' kg' : '-' }}</div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-12">
                                            <div class="d-flex gap-3">
                                                <div class="text-danger mt-1"><i class="fas fa-notes-medical"></i></div>
                                                <div>
                                                    <div class="small text-muted mb-1">Riwayat Penyakit</div>
                                                    <div class="fw-semibold text-dark">{{ selectedAlumni?.riwayat_penyakit || '-' }}</div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="d-flex gap-3">
                                                <div class="text-warning mt-1"><i class="fas fa-star"></i></div>
                                                <div>
                                                    <div class="small text-muted mb-1">Cita-cita</div>
                                                    <div class="fw-semibold text-dark">{{ selectedAlumni?.cita_cita || '-' }}</div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="d-flex gap-3">
                                                <div class="text-info mt-1"><i class="fas fa-palette"></i></div>
                                                <div>
                                                    <div class="small text-muted mb-1">Hobi</div>
                                                    <div class="fw-semibold text-dark">{{ selectedAlumni?.hobi || '-' }}</div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Tab: Alamat -->
                                <div class="tab-pane fade" id="detailAlamat">
                                    <h6 class="text-uppercase text-secondary fw-bold small mb-4 tracking-wide">Alamat & Kontak</h6>
                                    <div class="row g-4">
                                        <div class="col-12">
                                            <div class="d-flex gap-3">
                                                <div class="text-success mt-1"><i class="fas fa-map-marker-alt"></i></div>
                                                <div>
                                                    <div class="small text-muted mb-1">Alamat Lengkap</div>
                                                    <div class="fw-semibold text-dark">{{ selectedAlumni?.alamat || '-' }}</div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="d-flex gap-3">
                                                <div class="text-success mt-1"><i class="fas fa-sign"></i></div>
                                                <div>
                                                    <div class="small text-muted mb-1">RT</div>
                                                    <div class="fw-semibold text-dark">{{ selectedAlumni?.rt || '-' }}</div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="d-flex gap-3">
                                                <div class="text-success mt-1"><i class="fas fa-sign"></i></div>
                                                <div>
                                                    <div class="small text-muted mb-1">RW</div>
                                                    <div class="fw-semibold text-dark">{{ selectedAlumni?.rw || '-' }}</div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="d-flex gap-3">
                                                <div class="text-success mt-1"><i class="fas fa-home"></i></div>
                                                <div>
                                                    <div class="small text-muted mb-1">Tinggal Bersama</div>
                                                    <div class="fw-semibold text-dark">{{ selectedAlumni?.tinggal_bersama || '-' }}</div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="d-flex gap-3">
                                                <div class="text-success mt-1"><i class="fas fa-phone-alt"></i></div>
                                                <div>
                                                    <div class="small text-muted mb-1">No. Telepon / HP</div>
                                                    <div class="fw-semibold text-dark">{{ selectedAlumni?.no_telepon || '-' }}</div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Tab: Keluarga -->
                                <div class="tab-pane fade" id="detailOrtu">
                                    <div class="row g-4">
                                        <!-- Ayah -->
                                        <div class="col-md-6">
                                            <div class="p-3 bg-light rounded-3 h-100">
                                                <h6 class="text-primary fw-bold mb-3 border-bottom pb-2"><i class="fas fa-male me-2"></i>Data Ayah</h6>
                                                <div class="d-flex flex-column gap-3">
                                                    <div><small class="text-muted d-block">Nama</small><span class="fw-medium text-dark">{{ selectedAlumni?.nama_ayah || '-' }}</span></div>
                                                    <div><small class="text-muted d-block">NIK</small><span class="fw-medium text-dark">{{ selectedAlumni?.nik_ayah || '-' }}</span></div>
                                                    <div><small class="text-muted d-block">TTL</small><span class="fw-medium text-dark">{{ selectedAlumni?.tempat_lahir_ayah || '-' }}, {{ formatDate(selectedAlumni?.tanggal_lahir_ayah) }}</span></div>
                                                    <div><small class="text-muted d-block">Pekerjaan</small><span class="fw-medium text-dark">{{ selectedAlumni?.pekerjaan_ayah || '-' }}</span></div>
                                                    <div><small class="text-muted d-block">Pendidikan</small><span class="fw-medium text-dark">{{ selectedAlumni?.pendidikan_ayah || '-' }}</span></div>
                                                    <div><small class="text-muted d-block">Penghasilan</small><span class="text-success fw-medium">{{ selectedAlumni?.penghasilan_ayah || '-' }}</span></div>
                                                </div>
                                            </div>
                                        </div>
                                        <!-- Ibu -->
                                        <div class="col-md-6">
                                            <div class="p-3 bg-light rounded-3 h-100">
                                                <h6 class="text-danger fw-bold mb-3 border-bottom pb-2"><i class="fas fa-female me-2"></i>Data Ibu</h6>
                                                <div class="d-flex flex-column gap-3">
                                                    <div><small class="text-muted d-block">Nama</small><span class="fw-medium text-dark">{{ selectedAlumni?.nama_ibu || '-' }}</span></div>
                                                    <div><small class="text-muted d-block">NIK</small><span class="fw-medium text-dark">{{ selectedAlumni?.nik_ibu || '-' }}</span></div>
                                                    <div><small class="text-muted d-block">TTL</small><span class="fw-medium text-dark">{{ selectedAlumni?.tempat_lahir_ibu || '-' }}, {{ formatDate(selectedAlumni?.tanggal_lahir_ibu) }}</span></div>
                                                    <div><small class="text-muted d-block">Pekerjaan</small><span class="fw-medium text-dark">{{ selectedAlumni?.pekerjaan_ibu || '-' }}</span></div>
                                                    <div><small class="text-muted d-block">Pendidikan</small><span class="fw-medium text-dark">{{ selectedAlumni?.pendidikan_ibu || '-' }}</span></div>
                                                </div>
                                            </div>
                                        </div>
                                        <!-- Wali -->
                                        <div class="col-md-12" v-if="selectedAlumni?.nama_wali">
                                            <div class="p-3 bg-light rounded-3 h-100">
                                                <h6 class="text-secondary fw-bold mb-3 border-bottom pb-2"><i class="fas fa-user-shield me-2"></i>Data Wali</h6>
                                                <div class="row g-4">
                                                    <div class="col-md-6">
                                                        <div class="d-flex flex-column gap-3">
                                                            <div><small class="text-muted d-block">Nama Wali</small><span class="fw-medium text-dark">{{ selectedAlumni?.nama_wali || '-' }}</span></div>
                                                            <div><small class="text-muted d-block">NIK Wali</small><span class="fw-medium text-dark">{{ selectedAlumni?.nik_wali || '-' }}</span></div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="d-flex flex-column gap-3">
                                                            <div><small class="text-muted d-block">Pekerjaan</small><span class="fw-medium text-dark">{{ selectedAlumni?.pekerjaan_wali || '-' }}</span></div>
                                                            <div><small class="text-muted d-block">Pendidikan</small><span class="fw-medium text-dark">{{ selectedAlumni?.pendidikan_wali || '-' }}</span></div>
                                                            <div><small class="text-muted d-block">Hubungan</small><span class="fw-medium text-dark">{{ selectedAlumni?.hubungan_wali || '-' }}</span></div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Tab: Akademik -->
                                <div class="tab-pane fade" id="detailSekolah">
                                    <h6 class="text-uppercase text-secondary fw-bold small mb-4 tracking-wide">Riwayat Akademik</h6>
                                    <div class="row g-4">
                                        <div class="col-md-6">
                                            <div class="d-flex gap-3">
                                                <div class="text-info mt-1"><i class="fas fa-school"></i></div>
                                                <div>
                                                    <div class="small text-muted mb-1">Sekolah Asal</div>
                                                    <div class="fw-semibold text-dark">{{ selectedAlumni?.sekolah_asal || '-' }}</div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="d-flex gap-3">
                                                <div class="text-info mt-1"><i class="fas fa-chalkboard-teacher"></i></div>
                                                <div>
                                                    <div class="small text-muted mb-1">Kelas Terakhir</div>
                                                    <div class="fw-semibold text-dark">{{ selectedAlumni?.kelas || '-' }}</div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="d-flex gap-3">
                                                <div class="text-info mt-1"><i class="fas fa-sign-in-alt"></i></div>
                                                <div>
                                                    <div class="small text-muted mb-1">Status Masuk</div>
                                                    <div class="fw-semibold text-dark">{{ selectedAlumni?.status_masuk || '-' }}</div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="d-flex gap-3">
                                                <div class="text-info mt-1"><i class="fas fa-calendar-check"></i></div>
                                                <div>
                                                    <div class="small text-muted mb-1">Tanggal Masuk</div>
                                                    <div class="fw-semibold text-dark">{{ formatDate(selectedAlumni?.tanggal_masuk) }}</div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="d-flex gap-3">
                                                <div class="text-info mt-1"><i class="fas fa-user-check"></i></div>
                                                <div>
                                                    <div class="small text-muted mb-1">Status Keaktifan/Kelulusan</div>
                                                    <div class="fw-semibold"><span class="badge" :class="getStatusClass(selectedAlumni?.status)">{{ selectedAlumni?.status }}</span></div>
                                                </div>
                                            </div>
                                        </div>
                                        <!-- Tahun Lulus (Alumni Specific) -->
                                        <div class="col-md-6" v-if="selectedAlumni?.tahun_lulus">
                                            <div class="d-flex gap-3">
                                                <div class="text-info mt-1"><i class="fas fa-graduation-cap"></i></div>
                                                <div>
                                                    <div class="small text-muted mb-1">Tahun Lulus</div>
                                                    <div class="fw-semibold text-dark">{{ selectedAlumni?.tahun_lulus }}</div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!-- Tab: Dokumen -->
                                <div class="tab-pane fade" id="detailDokumen">
                                    <h6 class="text-uppercase text-secondary fw-bold small mb-4 tracking-wide">Berkas Digital</h6>
                                    <div class="row g-3">
                                        <div class="col-md-6" v-for="(label, key) in {
                                            file_foto: 'Foto Siswa',
                                            file_akte: 'Akte Kelahiran',
                                            file_kk: 'Kartu Keluarga',
                                            file_kip: 'Kartu Indonesia Pintar',
                                            file_ktp_ortu: 'KTP Orang Tua'
                                        }" :key="key">
                                            <div class="p-3 border rounded-3 bg-light d-flex align-items-center justify-content-between hover-bg-light-dark transition-all">
                                                <div class="d-flex align-items-center gap-3">
                                                    <i class="fas fa-file-alt text-primary fs-4"></i>
                                                    <span class="fw-medium text-dark">{{ label }}</span>
                                                </div>
                                                <a v-if="selectedAlumni && selectedAlumni[key]" :href="`/admin/siswa/${selectedAlumni.id}/document/${key}`" target="_blank" class="btn btn-sm btn-outline-primary rounded-pill px-3">
                                                    Buka
                                                </a>
                                                <span v-else class="badge bg-secondary bg-opacity-25 text-secondary rounded-pill fw-normal">Kosong</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer border-top px-4 py-3 bg-white">
                        <button
                            type="button"
                            class="btn btn-light border"
                            @click="showDetailModal = false"
                        >
                            Tutup
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </AdminLayout>
</template>

<script setup>
import AdminLayout from '@/Layouts/AdminLayout.vue';
import { Link, router } from '@inertiajs/vue3';
import { ref, reactive } from 'vue';
import { debounce } from 'lodash';

const props = defineProps({
    alumni: Object,
    tahunLulusList: Array,
    filters: Object
});

const params = reactive({
    search: props.filters?.search || '',
    tahun_lulus: props.filters?.tahun_lulus || '',
    status: props.filters?.status || ''
});

const filter = debounce(() => {
    router.get('/admin/alumni', params, {
        preserveState: true,
        replace: true
    });
}, 500);

// Detail Modal
const showDetailModal = ref(false);
const selectedAlumni = ref(null);

const viewDetail = (siswa) => {
    selectedAlumni.value = siswa;
    showDetailModal.value = true;
};

const closeDetailModal = () => {
    showDetailModal.value = false;
    selectedAlumni.value = null;
};

const formatDate = (dateString) => {
    if (!dateString) return '-';
    try {
        const date = new Date(dateString);
        if (isNaN(date.getTime())) return '-';
        return date.toLocaleDateString('id-ID', { day: 'numeric', month: 'long', year: 'numeric' });
    } catch (e) {
        return '-';
    }
};

const getInitials = (name) => {
    if (!name) return '??';
    const parts = name.trim().split(' ');
    if (parts.length === 1) return parts[0].substring(0, 2).toUpperCase();
    return (parts[0][0] + parts[1][0]).toUpperCase();
};

const getStatusClass = (status) => {
    switch(status) {
        case 'Lulus': return 'bg-success';
        case 'Pindah': return 'bg-warning';
        case 'Keluar': 
        case 'Dikeluarkan': return 'bg-danger';
        default: return 'bg-secondary';
    }
};
</script>

<style scoped>
/* Detail Modal Info Items - Clean Layout */
.info-item {
    background: #f8f9fa;
    border-radius: 8px;
    padding: 12px 16px;
    display: flex;
    flex-direction: column;
    gap: 4px;
    border-left: 3px solid #e9ecef;
    transition: all 0.2s ease;
}

.info-item:hover {
    border-left-color: #0d6efd;
    background: #f0f4ff;
}

.info-label {
    font-size: 0.75rem;
    font-weight: 600;
    color: #6c757d;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.info-value {
    font-size: 0.95rem;
    font-weight: 500;
    color: #212529;
    word-break: break-word;
}

/* Detail Modal Tab Content */
.tab-content .row {
    margin-top: 0.5rem;
}

.tab-pane h6 {
    margin-top: 0.5rem;
}

/* Avatar styling */
.avatar-circle {
    width: 60px;
    height: 60px;
    border-radius: 50%;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.5rem;
    font-weight: 600;
}

/* Badge Styling Refinement */
.badge.bg-primary-subtle { background-color: #e0e7ff !important; color: #4f46e5 !important; }
.badge.bg-success-subtle { background-color: #dcfce7 !important; color: #16a34a !important; }
.badge.bg-warning-subtle { background-color: #fef3c7 !important; color: #d97706 !important; }
.badge.bg-danger-subtle { background-color: #fee2e2 !important; color: #dc2626 !important; }
.badge.bg-secondary-subtle { background-color: #f3f4f6 !important; color: #4b5563 !important; }

.hover-up:hover {
    transform: translateY(-2px);
    transition: all 0.2s ease;
}

.hover-shadow:hover {
    box-shadow: 0 .5rem 1rem rgba(0,0,0,.15)!important;
    transition: all 0.2s ease;
}



</style>

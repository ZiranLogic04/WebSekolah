<template>
    <AdminLayout>
        <Head title="Data Siswa" />

        <!-- Toast Notification Component -->


        <div class="content container-fluid">
            <!-- Breadcrumb -->
            <nav aria-label="breadcrumb" class="mb-4">
                <ol class="breadcrumb alert alert-light border-0 rounded-4 shadow-sm py-3 px-4 mb-0 align-items-center">
                    <li class="breadcrumb-item"><Link href="/admin/dashboard" class="text-decoration-none fw-bold"><i class="fas fa-home me-1"></i> Dashboard</Link></li>
                    <li class="breadcrumb-item text-muted fw-bold">Master Induk</li>
                    <li class="breadcrumb-item active text-primary fw-bold" aria-current="page">Data Siswa</li>
                </ol>
            </nav>

            <!-- Radiant Header Card -->
            <div class="card border-0 shadow-lg rounded-4 mb-4 overflow-hidden position-relative" style="background: linear-gradient(135deg, #4361ee 0%, #3a0ca3 100%);">
                <div class="card-body p-4 p-lg-5 position-relative z-1">
                    <div class="row align-items-center">
                        <div class="col-lg-8">
                            <div class="d-flex align-items-center gap-3 mb-2">
                                <div class="icon-box bg-white rounded-3 text-primary p-2">
                                    <i class="fas fa-user-graduate fs-3"></i>
                                </div>
                                <h2 class="fw-bold text-white mb-0 ls-tight">Data Siswa</h2>
                            </div>
                            <p class="text-white-50 mb-0 fs-5">Kelola data induk siswa, import data, dan manajemen akademik.</p>
                        </div>
                        <div class="col-lg-4 text-lg-end mt-3 mt-lg-0">
                            <button @click="showAddModal = true" class="btn btn-light btn-lg rounded-pill shadow-sm px-4 fw-bold text-primary hover-elevate">
                                <i class="fas fa-plus-circle me-2"></i>Tambah Siswa
                            </button>
                        </div>
                    </div>
                </div>
                <!-- Decorative Background -->
                <div class="position-absolute bottom-0 end-0 opacity-10 me-n5 mb-n5">
                    <i class="fas fa-user-graduate" style="font-size: 10rem; color: white;"></i>
                </div>
            </div>

            <!-- Modern Filter Card -->
            <div class="card border-0 shadow-sm rounded-4 mb-4">
                <div class="card-body p-4">
                    <form @submit.prevent="filter" class="row g-3">
                        <div class="col-lg-3 col-md-6">
                            <div class="input-group">
                                <span class="input-group-text bg-white border-end-0 rounded-start-pill ps-3">
                                    <i class="fas fa-search text-muted"></i>
                                </span>
                                <input
                                    type="text"
                                    class="form-control border-start-0 rounded-end-pill ps-0"
                                    placeholder="Cari NIS atau Nama..."
                                    v-model="filters.search"
                                    @input="onFilterChange"
                                />
                            </div>
                        </div>
                        <div class="col-lg-2 col-md-6">
                            <select
                                class="form-select rounded-pill"
                                v-model="filters.kelas"
                                @change="filter"
                            >
                                <option value="">Semua Kelas</option>
                                <option
                                    v-for="kelas in kelasList"
                                    :value="kelas"
                                    :key="kelas"
                                >
                                    {{ kelas }}
                                </option>
                            </select>
                        </div>

                        <div class="col-lg-2 col-md-6">
                            <select
                                class="form-select rounded-pill"
                                v-model="filters.jenis_kelamin"
                                @change="filter"
                            >
                                <option value="">Semua Gender</option>
                                <option value="L">Laki-laki</option>
                                <option value="P">Perempuan</option>
                            </select>
                        </div>
                        <div class="col-lg-3 col-md-6">
                            <div class="d-flex gap-2">
                                <button
                                    type="button"
                                    @click="resetFilters"
                                    class="btn btn-light rounded-pill w-50"
                                >
                                    <i class="fas fa-redo me-1"></i>Reset
                                </button>
                                <!-- Tombol Bulk Delete -->
                                <button
                                    type="button"
                                    @click="confirmBulkDelete"
                                    class="btn btn-outline-danger rounded-pill w-50"
                                    :disabled="selectedStudents.length === 0"
                                >
                                    <i class="fas fa-trash me-1"></i>Hapus ({{
                                        selectedStudents.length
                                    }})
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Modern Table Card -->
            <div class="card border-0 shadow-sm rounded-4 overflow-hidden mb-4">
                <div class="card-header bg-white border-bottom-0 py-4 px-4">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <p class="text-muted fw-bold mb-0 small">
                                <span class="text-primary">{{ siswa.total }}</span> siswa terdaftar
                            </p>
                        </div>
                        <div class="d-flex gap-2">
                            <button
                                @click="toggleSelectAll"
                                class="btn btn-sm btn-light border rounded-pill px-3 fw-medium text-secondary"
                            >
                                <i class="fas fa-check-double me-1"></i>{{ isAllSelected ? 'Batal' : 'Pilih Semua' }}
                            </button>
                        </div>
                    </div>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-modern mb-0">
                            <thead>
                                <tr>
                                    <th width="30">
                                        <input type="checkbox" class="form-check-input" @change="toggleSelectAll" :checked="isAllSelected">
                                    </th>
                                    <th width="40" class="text-center">No</th>
                                    <th width="100" class="text-start">NIS</th>
                                    <th width="100" class="text-start">NISN</th>
                                    <th class="text-start" style="min-width: 200px;">Nama</th>
                                    <th width="150" class="text-start">TTL</th>
                                    <th class="text-center">Kelas</th>
                                    <th class="text-center">L/P</th>

                                    <th width="120" class="text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-if="tableData.length === 0">
                                    <td colspan="10" class="text-center py-5">
                                        <div class="empty-state">
                                            <div class="empty-icon mb-3">
                                                <i class="fas fa-users fa-3x text-muted"></i>
                                            </div>
                                            <h5 class="mb-2">Tidak ada data siswa</h5>
                                            <p class="text-muted mb-4">Belum ada data siswa yang tersedia</p>
                                            <button @click="showAddModal = true" class="btn btn-primary">
                                                <i class="fas fa-plus me-1"></i>Tambah Siswa
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                                <tr v-for="(item, index) in tableData" :key="item.id" :class="{ 'row-selected': selectedStudents.includes(item.id) }">
                                    <td>
                                        <input type="checkbox" class="form-check-input" :value="item.id" v-model="selectedStudents">
                                    </td>
                                    <td class="text-center fw-medium text-muted">{{ siswa.from + index }}</td>
                                    <td class="fw-medium text-wrap">{{ item.nis }}</td>
                                    <td class="fw-medium text-wrap">{{ item.nisn || '-' }}</td>
                                    <td class="fw-semibold text-wrap">{{ item.nama }}</td>
                                    <td class="text-wrap">
                                        <div class="fw-bold small lh-1">{{ item.tempat_lahir || '-' }}</div>
                                        <div class="text-muted small">{{ formatShortDate(item.tanggal_lahir) }}</div>
                                    </td>
                                    <td class="text-center">
                                        <span class="badge kelas-badge">{{ item.kelas }}</span>
                                    </td>
                                    <td class="text-center">
                                        <div class="gender-badge" :class="item.jenis_kelamin === 'L' ? 'male' : 'female'">
                                            <i :class="item.jenis_kelamin === 'L' ? 'fas fa-mars' : 'fas fa-venus'"></i>
                                            {{ item.jenis_kelamin }}
                                        </div>
                                    </td>

                                    <td class="text-nowrap text-center">
                                        <div class="d-flex gap-2 justify-content-center">
                                            <!-- View Detail -->
                                            <button 
                                                class="btn-icon-modern btn-icon-view" 
                                                @click="viewStudentDetail(item)" 
                                                title="Detail Siswa"
                                            >
                                                <i class="fas fa-eye"></i>
                                            </button>

                                            <!-- Edit -->
                                            <button 
                                                class="btn-icon-modern btn-icon-edit" 
                                                @click="editStudent(item)" 
                                                title="Edit Data"
                                            >
                                                <i class="fas fa-edit"></i>
                                            </button>

                                            <!-- Change Status (Mutasi) -->
                                            <button 
                                                v-if="item.status === 'Aktif'" 
                                                class="btn-icon-modern btn-icon-warning" 
                                                @click="showStatusChangeModal(item)" 
                                                title="Mutasi Siswa" 
                                                type="button"
                                            >
                                                <i class="fas fa-exchange-alt"></i>
                                            </button>

                                            <!-- Delete -->
                                            <button 
                                                class="btn-icon-modern btn-icon-delete" 
                                                @click.stop.prevent="confirmDelete(item)" 
                                                title="Hapus Permanen" 
                                                type="button"
                                            >
                                                <i class="fas fa-trash-alt"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    <div class="p-3 border-top">
                        <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
                            <div class="d-flex align-items-center gap-3">
                                <div class="d-flex align-items-center gap-2">
                                    <span class="text-muted small">Tampilkan</span>
                                    <select class="form-select form-select-sm" style="width: 70px;" v-model="perPage" @change="changePerPage">
                                        <option :value="10">10</option>
                                        <option :value="25">25</option>
                                        <option :value="50">50</option>
                                        <option :value="100">100</option>
                                    </select>
                                    <span class="text-muted small">data</span>
                                </div>
                                <small class="text-muted">
                                    Menampilkan {{ siswa.from || 0 }} - {{ siswa.to || 0 }} dari {{ siswa.total || 0 }}
                                </small>
                            </div>
                            <div>
                                <nav>
                                    <ul class="pagination pagination-sm mb-0">
                                        <!-- PREV -->
                                        <li
                                            class="page-item"
                                            :class="{
                                                disabled: !siswa.prev_page_url,
                                            }"
                                        >
                                            <Link
                                                class="page-link"
                                                :href="
                                                    siswa.prev_page_url || ''
                                                "
                                                preserve-state
                                                preserve-scroll
                                            >
                                                <i
                                                    class="fas fa-chevron-left"
                                                ></i>
                                            </Link>
                                        </li>

                                        <!-- NUMBER LINKS -->
                                        <li
                                            v-for="page in siswa.links"
                                            :key="page.label"
                                            v-show="!page.label.includes('Previous') && !page.label.includes('Next')"
                                            class="page-item"
                                            :class="{
                                                active: page.active,
                                                disabled: page.url === null,
                                            }"
                                        >
                                            <Link
                                                class="page-link"
                                                :href="page.url || ''"
                                                v-html="page.label"
                                                preserve-state
                                                preserve-scroll
                                            ></Link>
                                        </li>

                                        <!-- NEXT -->
                                        <li
                                            class="page-item"
                                            :class="{
                                                disabled: !siswa.next_page_url,
                                            }"
                                        >
                                            <Link
                                                class="page-link"
                                                :href="
                                                    siswa.next_page_url || ''
                                                "
                                                preserve-state
                                                preserve-scroll
                                            >
                                                <i
                                                    class="fas fa-chevron-right"
                                                ></i>
                                            </Link>
                                        </li>
                                    </ul>
                                </nav>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modern Add/Edit Modal -->
        <div
            v-if="showAddModal"
            class="modal fade show d-block"
            tabindex="-1"
            style="background: rgba(0, 0, 0, 0.5); z-index: 1050"
        >
            <div
                class="modal-dialog modal-lg modal-dialog-centered"
                style="z-index: 1060"
            >
                <div class="modal-content modern-modal">
                    <div class="modal-header text-white" style="background: linear-gradient(135deg, #4361ee 0%, #3a0ca3 100%);">
                        <div class="modal-title">
                            <i class="modal-icon text-white" :class="isEditing ? 'fas fa-edit' : 'fas fa-user-plus'"></i>
                            <div>
                                <h5 class="text-white mb-0">{{ isEditing ? "Edit Data Siswa" : "Tambah Data Siswa" }}</h5>
                                <p class="modal-subtitle text-white-50 mb-0">
                                    {{ isEditing ? "Perbarui data siswa yang sudah ada" : "Tambahkan data siswa baru ke sistem" }}
                                </p>
                            </div>
                        </div>
                        <button type="button" class="btn-close btn-close-white" @click="closeModal"></button>
                    </div>
                    <form @submit.prevent="saveStudent">
                        <div class="modal-body">
                            <!-- Tabs Navigation -->
                            <div class="modal-tabs mb-4">
                                <div class="nav-tabs">
                                    <button
                                        type="button"
                                        class="nav-tab"
                                        :class="{
                                            active: activeTab === 'manual',
                                        }"
                                        @click="activeTab = 'manual'"
                                    >
                                        <i class="fas fa-keyboard me-2"></i
                                        >Input Manual
                                    </button>
                                    <button
                                        v-if="!isEditing"
                                        type="button"
                                        class="nav-tab"
                                        :class="{
                                            active: activeTab === 'import',
                                        }"
                                        @click="activeTab = 'import'"
                                    >
                                        <i class="fas fa-file-import me-2"></i
                                        >Import Excel
                                    </button>
                                </div>
                            </div>

                            <!-- Manual Input Form with Tabs -->
                            <div v-if="activeTab === 'manual'">
                                <!-- Form Tab Navigation -->
                                <div class="form-tabs mb-4">
                                    <nav class="nav nav-pills nav-fill">
                                        <button type="button" class="nav-link" :class="{ active: formTab === 'identity' }" @click="formTab = 'identity'">
                                            <i class="fas fa-user me-1"></i> Data Diri
                                        </button>
                                        <button type="button" class="nav-link" :class="{ active: formTab === 'address' }" @click="formTab = 'address'">
                                            <i class="fas fa-home me-1"></i> Alamat
                                        </button>
                                        <button type="button" class="nav-link" :class="{ active: formTab === 'parents' }" @click="formTab = 'parents'">
                                            <i class="fas fa-users me-1"></i> Orang Tua
                                        </button>
                                        <button type="button" class="nav-link" :class="{ active: formTab === 'guardian' }" @click="formTab = 'guardian'">
                                            <i class="fas fa-user-shield me-1"></i> Wali
                                        </button>
                                        <button type="button" class="nav-link" :class="{ active: formTab === 'school' }" @click="formTab = 'school'">
                                            <i class="fas fa-school me-1"></i> Sekolah
                                        </button>
                                        <button type="button" class="nav-link" :class="{ active: formTab === 'other' }" @click="formTab = 'other'">
                                            <i class="fas fa-info-circle me-1"></i> Lainnya
                                        </button>
                                        <button type="button" class="nav-link" :class="{ active: formTab === 'documents' }" @click="formTab = 'documents'">
                                            <i class="fas fa-folder-open me-1"></i> Dokumen
                                        </button>
                                    </nav>
                                </div>

                                <!-- Tab: Data Diri -->
                                <div v-show="formTab === 'identity'" class="row g-3">
                                    <div class="col-md-6">
                                        <label class="form-label required">NIS</label>
                                        <input type="text" class="form-control" v-model="form.nis" :class="{ 'is-invalid': form.errors.nis }" placeholder="Nomor Induk Siswa" />
                                        <div v-if="form.errors.nis" class="invalid-feedback">{{ form.errors.nis }}</div>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label">NISN</label>
                                        <input type="text" class="form-control" v-model="form.nisn" placeholder="Nomor Induk Siswa Nasional" />
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label required">Nama Lengkap</label>
                                        <input type="text" class="form-control" v-model="form.nama" :class="{ 'is-invalid': form.errors.nama }" placeholder="Nama lengkap siswa" />
                                        <div v-if="form.errors.nama" class="invalid-feedback">{{ form.errors.nama }}</div>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label required">Jenis Kelamin</label>
                                        <select class="form-select" v-model="form.jenis_kelamin" :class="{ 'is-invalid': form.errors.jenis_kelamin }">
                                            <option value="">Pilih</option>
                                            <option value="L">Laki-laki</option>
                                            <option value="P">Perempuan</option>
                                        </select>
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label">Tempat Lahir</label>
                                        <input type="text" class="form-control" v-model="form.tempat_lahir" placeholder="Kota kelahiran" />
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label">Tanggal Lahir</label>
                                        <input type="date" class="form-control" v-model="form.tanggal_lahir" />
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label">Agama</label>
                                        <select class="form-select" v-model="form.agama">
                                            <option value="">Pilih Agama</option>
                                            <option value="Islam">Islam</option>
                                            <option value="Kristen">Kristen</option>
                                            <option value="Katolik">Katolik</option>
                                            <option value="Hindu">Hindu</option>
                                            <option value="Buddha">Buddha</option>
                                            <option value="Konghucu">Konghucu</option>
                                        </select>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label">NIK</label>
                                        <input type="text" class="form-control" v-model="form.nik" placeholder="Nomor Induk Kependudukan" maxlength="16" />
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label">No. KK (NKK)</label>
                                        <input type="text" class="form-control" v-model="form.nkk" placeholder="Nomor Kartu Keluarga" maxlength="16" />
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label">Anak Ke-</label>
                                        <input type="number" class="form-control" v-model="form.anak_ke" placeholder="1" min="1" />
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label">Jumlah Saudara</label>
                                        <input type="number" class="form-control" v-model="form.jumlah_saudara" placeholder="0" min="0" />
                                    </div>
                                </div>

                                <!-- Tab: Alamat -->
                                <div v-show="formTab === 'address'" class="row g-3">
                                    <div class="col-12">
                                        <label class="form-label">Alamat Lengkap</label>
                                        <textarea class="form-control" rows="2" v-model="form.alamat" placeholder="Alamat lengkap siswa"></textarea>
                                    </div>
                                    <div class="col-md-3">
                                        <label class="form-label">RT</label>
                                        <input type="text" class="form-control" v-model="form.rt" placeholder="001" maxlength="3" />
                                    </div>
                                    <div class="col-md-3">
                                        <label class="form-label">RW</label>
                                        <input type="text" class="form-control" v-model="form.rw" placeholder="002" maxlength="3" />
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label">Tinggal Bersama</label>
                                        <select class="form-select" v-model="form.tinggal_bersama">
                                            <option value="">Pilih</option>
                                            <option value="Orang Tua">Orang Tua</option>
                                            <option value="Wali">Wali</option>
                                            <option value="Saudara">Saudara</option>
                                            <option value="Kos">Kos</option>
                                            <option value="Lainnya">Lainnya</option>
                                        </select>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label">No. Telepon / HP</label>
                                        <input type="text" class="form-control" v-model="form.no_telepon" placeholder="08xxxxxxxxxx" />
                                    </div>
                                     <div class="col-md-6">
                                        <label class="form-label">No. Akte Kelahiran</label>
                                        <input type="text" class="form-control" v-model="form.no_akte" placeholder="Nomor Akte" />
                                    </div>
                                </div>

                                <!-- Tab: Orang Tua -->
                                <div v-show="formTab === 'parents'" class="row g-3">
                                    <div class="col-12"><h6 class="text-primary fw-bold border-bottom pb-2"><i class="fas fa-male me-1"></i> Data Ayah</h6></div>
                                    <div class="col-md-6">
                                        <label class="form-label">Nama Ayah</label>
                                        <input type="text" class="form-control" v-model="form.nama_ayah" placeholder="Nama lengkap ayah" />
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label">NIK Ayah</label>
                                        <input type="text" class="form-control" v-model="form.nik_ayah" placeholder="NIK Ayah" maxlength="16" />
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label">Tempat Lahir Ayah</label>
                                        <input type="text" class="form-control" v-model="form.tempat_lahir_ayah" placeholder="Kota" />
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label">Tanggal Lahir Ayah</label>
                                        <input type="date" class="form-control" v-model="form.tanggal_lahir_ayah" />
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label">Pekerjaan Ayah</label>
                                        <input type="text" class="form-control" v-model="form.pekerjaan_ayah" placeholder="Pekerjaan" />
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label">Pendidikan Terakhir Ayah</label>
                                        <select class="form-select" v-model="form.pendidikan_ayah">
                                            <option value="">Pilih</option>
                                            <option value="SD">SD/Sederajat</option>
                                            <option value="SMP">SMP/Sederajat</option>
                                            <option value="SMA">SMA/Sederajat</option>
                                            <option value="D3">D3</option>
                                            <option value="S1">S1</option>
                                            <option value="S2">S2</option>
                                            <option value="S3">S3</option>
                                        </select>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label">Penghasilan Ayah / Bulan</label>
                                        <select class="form-select" v-model="form.penghasilan_ayah">
                                            <option value="">Pilih</option>
                                            <option value="< 1 Juta">Kurang dari 1 Juta</option>
                                            <option value="1 - 3 Juta">1 - 3 Juta</option>
                                            <option value="3 - 5 Juta">3 - 5 Juta</option>
                                            <option value="> 5 Juta">Lebih dari 5 Juta</option>
                                        </select>
                                    </div>

                                    <div class="col-12 mt-4"><h6 class="text-danger fw-bold border-bottom pb-2"><i class="fas fa-female me-1"></i> Data Ibu</h6></div>
                                    <div class="col-md-6">
                                        <label class="form-label">Nama Ibu</label>
                                        <input type="text" class="form-control" v-model="form.nama_ibu" placeholder="Nama lengkap ibu" />
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label">NIK Ibu</label>
                                        <input type="text" class="form-control" v-model="form.nik_ibu" placeholder="NIK Ibu" maxlength="16" />
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label">Tempat Lahir Ibu</label>
                                        <input type="text" class="form-control" v-model="form.tempat_lahir_ibu" placeholder="Kota" />
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label">Tanggal Lahir Ibu</label>
                                        <input type="date" class="form-control" v-model="form.tanggal_lahir_ibu" />
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label">Pekerjaan Ibu</label>
                                        <input type="text" class="form-control" v-model="form.pekerjaan_ibu" placeholder="Pekerjaan" />
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label">Pendidikan Terakhir Ibu</label>
                                        <select class="form-select" v-model="form.pendidikan_ibu">
                                            <option value="">Pilih</option>
                                            <option value="SD">SD/Sederajat</option>
                                            <option value="SMP">SMP/Sederajat</option>
                                            <option value="SMA">SMA/Sederajat</option>
                                            <option value="D3">D3</option>
                                            <option value="S1">S1</option>
                                            <option value="S2">S2</option>
                                            <option value="S3">S3</option>
                                        </select>
                                    </div>
                                </div>

                                <!-- Tab: Wali -->
                                <div v-show="formTab === 'guardian'" class="row g-3">
                                    <div class="col-12">
                                        <p class="text-muted small">Isi bagian ini jika siswa tinggal bersama wali (bukan orang tua kandung).</p>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label">Nama Wali</label>
                                        <input type="text" class="form-control" v-model="form.nama_wali" placeholder="Nama lengkap wali" />
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label">NIK Wali</label>
                                        <input type="text" class="form-control" v-model="form.nik_wali" placeholder="NIK Wali" maxlength="16" />
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label">Pekerjaan Wali</label>
                                        <input type="text" class="form-control" v-model="form.pekerjaan_wali" placeholder="Pekerjaan" />
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label">Pendidikan Wali</label>
                                        <select class="form-select" v-model="form.pendidikan_wali">
                                            <option value="">Pilih</option>
                                            <option value="SD">SD/Sederajat</option>
                                            <option value="SMP">SMP/Sederajat</option>
                                            <option value="SMA">SMA/Sederajat</option>
                                            <option value="D3">D3</option>
                                            <option value="S1">S1</option>
                                            <option value="S2">S2</option>
                                            <option value="S3">S3</option>
                                        </select>
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label">Hubungan dengan Siswa</label>
                                        <select class="form-select" v-model="form.hubungan_wali">
                                            <option value="">Pilih</option>
                                            <option value="Kakek/Nenek">Kakek/Nenek</option>
                                            <option value="Paman/Bibi">Paman/Bibi</option>
                                            <option value="Kakak/Adik">Kakak/Adik</option>
                                            <option value="Lainnya">Lainnya</option>
                                        </select>
                                    </div>
                                </div>

                                <!-- Tab: Sekolah -->
                                <div v-show="formTab === 'school'" class="row g-3">
                                    <div class="col-md-6">
                                        <label class="form-label required">Kelas Saat Ini</label>
                                        <select class="form-select" v-model="form.kelas" :class="{ 'is-invalid': form.errors.kelas }">
                                            <option value="">Pilih Kelas</option>
                                            <option v-for="kelas in kelasList" :value="kelas" :key="kelas">{{ kelas }}</option>
                                        </select>
                                        <div v-if="form.errors.kelas" class="invalid-feedback">{{ form.errors.kelas }}</div>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label">Status Masuk</label>
                                        <select class="form-select" v-model="form.status_masuk">
                                            <option value="">Pilih</option>
                                            <option value="Siswa Baru">Siswa Baru</option>
                                            <option value="Pindahan">Pindahan</option>
                                        </select>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label">Diterima di Kelas</label>
                                        <input type="text" class="form-control" v-model="form.diterima_di_kelas" placeholder="Contoh: 1A" />
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label">Tanggal Masuk</label>
                                        <input type="date" class="form-control" v-model="form.tanggal_masuk" />
                                    </div>
                                    <div class="col-12">
                                        <label class="form-label">Sekolah Asal</label>
                                        <input type="text" class="form-control" v-model="form.sekolah_asal" placeholder="Nama sekolah sebelumnya (jika pindahan atau naik jenjang)" />
                                    </div>
                                </div>

                                <!-- Tab: Lainnya -->
                                <div v-show="formTab === 'other'" class="row g-3">
                                    <div class="col-12"><h6 class="text-secondary fw-bold border-bottom pb-2"><i class="fas fa-heartbeat me-1"></i> Kesehatan &amp; Fisik</h6></div>
                                    <div class="col-12">
                                        <label class="form-label">Riwayat Penyakit Berat</label>
                                        <textarea class="form-control" rows="2" v-model="form.riwayat_penyakit" placeholder="Tuliskan jika ada riwayat penyakit berat"></textarea>
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label">Berat Badan (Kg)</label>
                                        <input type="number" class="form-control" v-model="form.berat_badan" placeholder="30" />
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label">Tinggi Badan (Cm)</label>
                                        <input type="number" class="form-control" v-model="form.tinggi_badan" placeholder="120" />
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label">Golongan Darah</label>
                                        <select class="form-select" v-model="form.golongan_darah">
                                            <option value="">Pilih</option>
                                            <option value="A">A</option>
                                            <option value="B">B</option>
                                            <option value="AB">AB</option>
                                            <option value="O">O</option>
                                        </select>
                                    </div>

                                    <div class="col-12 mt-3"><h6 class="text-secondary fw-bold border-bottom pb-2"><i class="fas fa-star me-1"></i> Minat & Bakat</h6></div>
                                    <div class="col-md-6">
                                        <label class="form-label">Cita-cita</label>
                                        <input type="text" class="form-control" v-model="form.cita_cita" placeholder="Cita-cita siswa" />
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label">Hobi</label>
                                        <input type="text" class="form-control" v-model="form.hobi" placeholder="Hobi siswa" />
                                    </div>
                                </div>

                                <!-- Tab: Dokumen -->
                                <div v-show="formTab === 'documents'" class="row g-3">
                                    <div class="col-12">
                                        <p class="text-muted small mb-3"><i class="fas fa-info-circle me-1"></i> Upload dokumen pendukung dalam format gambar (JPG, PNG) atau PDF. Maksimal 2MB per file.</p>
                                    </div>
                                    
                                    <!-- Foto Siswa -->
                                    <div class="col-md-6">
                                        <div class="upload-card" @click="$refs.file_foto.click()">
                                            <input type="file" ref="file_foto" class="d-none" @change="handleFileUpload($event, 'file_foto')" accept="image/*" />
                                            <div class="upload-card-content" :class="{ 'has-file': form.file_foto }">
                                                <i class="fas fa-camera upload-icon"></i>
                                                <span class="upload-label">Foto Siswa</span>
                                                <span v-if="form.file_foto" class="upload-status text-success"><i class="fas fa-check-circle me-1"></i>File siap</span>
                                                <span v-else class="upload-hint">Klik untuk pilih file</span>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <!-- Akte Kelahiran -->
                                    <div class="col-md-6">
                                        <div class="upload-card" @click="$refs.file_akte.click()">
                                            <input type="file" ref="file_akte" class="d-none" @change="handleFileUpload($event, 'file_akte')" accept="image/*,.pdf" />
                                            <div class="upload-card-content" :class="{ 'has-file': form.file_akte }">
                                                <i class="fas fa-file-alt upload-icon"></i>
                                                <span class="upload-label">Akte Kelahiran</span>
                                                <span v-if="form.file_akte" class="upload-status text-success"><i class="fas fa-check-circle me-1"></i>File siap</span>
                                                <span v-else class="upload-hint">Klik untuk pilih file</span>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <!-- Kartu Keluarga -->
                                    <div class="col-md-6">
                                        <div class="upload-card" @click="$refs.file_kk.click()">
                                            <input type="file" ref="file_kk" class="d-none" @change="handleFileUpload($event, 'file_kk')" accept="image/*,.pdf" />
                                            <div class="upload-card-content" :class="{ 'has-file': form.file_kk }">
                                                <i class="fas fa-users upload-icon"></i>
                                                <span class="upload-label">Kartu Keluarga (KK)</span>
                                                <span v-if="form.file_kk" class="upload-status text-success"><i class="fas fa-check-circle me-1"></i>File siap</span>
                                                <span v-else class="upload-hint">Klik untuk pilih file</span>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <!-- KIP -->
                                    <div class="col-md-6">
                                        <div class="upload-card" @click="$refs.file_kip.click()">
                                            <input type="file" ref="file_kip" class="d-none" @change="handleFileUpload($event, 'file_kip')" accept="image/*,.pdf" />
                                            <div class="upload-card-content" :class="{ 'has-file': form.file_kip }">
                                                <i class="fas fa-id-card upload-icon"></i>
                                                <span class="upload-label">Kartu Indonesia Pintar</span>
                                                <span v-if="form.file_kip" class="upload-status text-success"><i class="fas fa-check-circle me-1"></i>File siap</span>
                                                <span v-else class="upload-hint">Klik untuk pilih file</span>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <!-- KTP Ortu -->
                                    <div class="col-md-6">
                                        <div class="upload-card" @click="$refs.file_ktp_ortu.click()">
                                            <input type="file" ref="file_ktp_ortu" class="d-none" @change="handleFileUpload($event, 'file_ktp_ortu')" accept="image/*,.pdf" />
                                            <div class="upload-card-content" :class="{ 'has-file': form.file_ktp_ortu }">
                                                <i class="fas fa-address-card upload-icon"></i>
                                                <span class="upload-label">KTP Orang Tua</span>
                                                <span v-if="form.file_ktp_ortu" class="upload-status text-success"><i class="fas fa-check-circle me-1"></i>File siap</span>
                                                <span v-else class="upload-hint">Klik untuk pilih file</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Import Excel Tab -->
                            <div v-if="activeTab === 'import'">
                                <div class="import-container">
                                    <div class="text-center mb-4">
                                        <div class="import-icon mb-3">
                                            <i
                                                class="fas fa-file-excel fa-3x text-success"
                                            ></i>
                                        </div>
                                        <h4>Import Data Excel</h4>
                                        <p class="text-muted mb-2">
                                            Upload file Excel untuk menambahkan
                                            banyak data sekaligus
                                        </p>
                                        <a href="/admin/siswa/template" class="btn btn-sm btn-outline-success">
                                            <i class="fas fa-download me-1"></i> Download Template
                                        </a>
                                    </div>

                                    <!-- Step 1: Upload File -->
                                    <div v-if="importStep === 1">
                                        <!-- Petunjuk Import (Moved to Top) -->
                                        <div class="alert alert-info border-0 bg-opacity-10 mb-4">
                                            <h6 class="alert-heading fw-bold mb-2"><i class="fas fa-info-circle me-2"></i>Petunjuk Import</h6>
                                            <ul class="mb-0 ps-3 small">
                                                <li>Gunakan template Excel yang disediakan.</li>
                                                <li>Kolom wajib: <b>NIS, Nama, Kelas, Jenis Kelamin (L/P)</b>.</li>
                                                <li>Pastikan tidak ada NIS duplikat di file maupun database.</li>
                                            </ul>
                                        </div>

                                        <div
                                            class="upload-zone p-5 border-2 border-dashed rounded-3 text-center position-relative overflow-hidden"
                                            :class="{
                                                'bg-light': !importFile, 
                                                'bg-white border-success': importFile && !previewLoading,
                                                'bg-light border-primary': previewLoading
                                            }"
                                            style="cursor: pointer; transition: all 0.2s; min-height: 250px;"
                                            @click="triggerFileInput"
                                            @dragover.prevent
                                            @dragenter.prevent
                                            @dragleave.prevent
                                            @drop.prevent="handleFileDrop"
                                        >
                                            <div v-if="previewLoading" class="position-absolute top-0 start-0 w-100 h-100 d-flex flex-column align-items-center justify-content-center bg-white bg-opacity-75" style="z-index: 10; backdrop-filter: blur(2px);">
                                                <div class="spinner-border text-primary mb-3" style="width: 3rem; height: 3rem;" role="status"></div>
                                                <h5 class="text-primary fw-bold mb-1">Sedang Memproses...</h5>
                                                <p class="text-muted small">Mohon tunggu, membaca data Excel</p>
                                            </div>

                                            <div :class="{'opacity-0': previewLoading}">
                                                <input
                                                    type="file"
                                                    ref="fileInput"
                                                    @change="handleFileSelect"
                                                    accept=".xlsx,.xls,.csv,.xlsm"
                                                    hidden
                                                />

                                                <div class="upload-content text-center">
                                                    <div v-if="!importFile">
                                                        <i class="fas fa-cloud-upload-alt fa-3x text-primary mb-3"></i>
                                                        <h5>Klik atau drag file Excel ke sini</h5>
                                                        <p class="text-muted mb-0 small">Format: .xlsx, .xls, .xlsm, .csv (Maks. 5MB)</p>
                                                    </div>
                                                    <div v-else>
                                                        <i class="fas fa-file-excel fa-3x text-success mb-3 animate__animated animate__bounceIn"></i>
                                                        <h5 class="text-dark mb-1">{{ importFile.name }}</h5>
                                                        <p class="text-muted small mb-0">{{ formatFileSize(importFile.size) }}</p>
                                                        <p class="text-success small fw-bold mt-2"><i class="fas fa-check-circle me-1"></i>File Terpilih</p>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <!-- Step 2: Preview Data (No Changes needed here in this block replacement) -->
                                    <div v-else-if="importStep === 2">
                                        <div class="d-flex justify-content-between align-items-center mb-3">
                                            <div>
                                                <h6 class="mb-1 fw-bold">Preview Data Import</h6>
                                                <div class="d-flex gap-3 text-sm">
                                                    <span class="text-muted">Total: <b>{{ previewStats.total }}</b></span>
                                                    <span class="text-success">Valid: <b>{{ previewStats.valid }}</b></span>
                                                    <span class="text-danger">Invalid: <b>{{ previewStats.invalid }}</b></span>
                                                </div>
                                            </div>
                                            <div class="d-flex gap-2">
                                                <button type="button" class="btn btn-outline-secondary btn-sm" @click="resetImportState">
                                                    <i class="fas fa-arrow-left me-1"></i> Pilih File Lain
                                                </button>
                                                <button 
                                                    type="button" 
                                                    class="btn btn-success btn-sm" 
                                                    @click="submitImport"
                                                    :disabled="importing || previewStats.valid === 0"
                                                >
                                                    <i class="fas" :class="importing ? 'fa-spinner fa-spin' : 'fa-check'"></i>
                                                    {{ importing ? 'Mengimport...' : 'Import Sekarang' }}
                                                </button>
                                            </div>
                                        </div>

                                        <div class="alert alert-info py-2 mb-3 small border-0 bg-opacity-10">
                                            <i class="fas fa-info-circle me-1"></i>
                                            <b>Catatan:</b> Tabel ini hanya menampilkan preview data utama (Wajib). Data lengkap lainnya tetap akan diproses saat import.
                                        </div>

                                        <div v-if="previewStats.invalid > 0" class="alert alert-warning py-2 mb-3">
                                            <i class="fas fa-exclamation-triangle me-2"></i>
                                            Ada {{ previewStats.invalid }} baris data <b>Tidak Valid</b>. Data tersebut tidak akan diimport.
                                        </div>

                                        <div class="table-responsive border rounded" style="max-height: 400px;">
                                            <table class="table table-sm table-hover mb-0 sticky-header text-nowrap">
                                                <thead class="table-light">
                                                    <tr>
                                                        <th class="text-center" width="50">#</th>
                                                        <th>NIS</th>
                                                        <th>NISN</th>
                                                        <th>Nama</th>
                                                        <th>Kelas</th>
                                                        <th>L/P</th>
                                                        <th>Tempat Lahir</th>
                                                        <th>Tgl Lahir</th>
                                                        <th>Nama Ayah</th>
                                                        <th class="text-center" width="100">Status</th>
                                                        <th>Keterangan</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <tr v-for="(row, idx) in previewData" :key="idx" :class="{'table-danger bg-opacity-10': row.__status === 'Invalid'}">
                                                        <td class="text-center">{{ row.__row }}</td>
                                                        <td>{{ row.nis || '-' }}</td>
                                                        <td>{{ row.nisn || '-' }}</td>
                                                        <td>{{ row.nama || '-' }}</td>
                                                        <td>{{ row.kelas || '-' }}</td>
                                                        <td>{{ row.jenis_kelamin || '-' }}</td>
                                                        <td>{{ row.tempat_lahir || '-' }}</td>
                                                        <td>{{ row.tanggal_lahir ? formatDate(row.tanggal_lahir) : '-' }}</td>
                                                        <td>{{ row.nama_ayah || '-' }}</td>
                                                        <td class="text-center">
                                                            <span class="badge" :class="row.__status === 'Valid' ? 'bg-success' : 'bg-danger'">
                                                                {{ row.__status === 'Valid' ? 'Valid' : 'Tidak Valid' }}
                                                            </span>
                                                        </td>
                                                        <td class="text-xs" style="min-width: 200px;">
                                                            <div v-if="row.__errors && row.__errors.length">
                                                                <ul class="mb-0 ps-3 text-danger">
                                                                    <li v-for="(err, i) in row.__errors" :key="i">{{ err }}</li>
                                                                </ul>
                                                            </div>
                                                            <span v-else class="text-success fw-bold"><i class="fas fa-check-circle me-1"></i>Data Valid (Siap Import)</span>
                                                        </td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                        <p class="text-muted text-xs mt-2">* Menampilkan total {{ previewStats.total }} data</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button
                                type="button"
                                class="btn btn-light"
                                @click="closeModal"
                            >
                                Batal
                            </button>
                            <button
                                v-if="activeTab === 'manual'"
                                type="submit"
                                class="btn btn-primary"
                                :disabled="form.processing"
                            >
                                <i
                                    class="fas"
                                    :class="isEditing ? 'fa-save' : 'fa-check'"
                                ></i>
                                {{
                                    isEditing
                                        ? "Simpan Perubahan"
                                        : "Simpan Data"
                                }}
                            </button>
                        </div>
                    </form>
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
                <div class="modal-content border-0 shadow-lg rounded-4 overflow-hidden">
                    <div class="modal-header border-0 py-3 px-4 align-items-center bg-white sticky-top shadow-sm" style="z-index: 10;">
                        <h5 class="modal-title fw-bold text-dark d-flex align-items-center gap-3">
                             <div class="rounded-circle d-flex align-items-center justify-content-center bg-primary text-white shadow-sm" style="width: 40px; height: 40px;">
                                <i class="fas fa-user-graduate fs-6"></i>
                             </div>
                             <span>Detail Siswa</span>
                        </h5>
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
                                        {{ selectedStudent ? getInitials(selectedStudent.nama) : '' }}
                                    </div>
                                    <div 
                                        class="position-absolute bottom-0 end-0 bg-white rounded-circle p-1 shadow-sm border"
                                        style="width: 30px; height: 30px; display: flex; align-items: center; justify-content: center;"
                                    >
                                        <i :class="selectedStudent?.jenis_kelamin === 'L' ? 'fas fa-mars text-primary small' : 'fas fa-venus text-danger small'"></i>
                                    </div>
                                </div>
                                <div class="flex-grow-1">
                                    <h4 class="fw-bold text-dark mb-1">{{ selectedStudent?.nama }}</h4>
                                    <div class="d-flex flex-wrap align-items-center gap-3 text-muted mb-2">
                                        <span class="d-flex align-items-center gap-2 small bg-light px-3 py-1 rounded-pill"><i class="fas fa-id-card text-secondary"></i> {{ selectedStudent?.nis }}</span>
                                        <span class="d-flex align-items-center gap-2 small bg-light px-3 py-1 rounded-pill"><i class="fas fa-layer-group text-secondary"></i> {{ selectedStudent?.kelas }}</span>
                                    </div>
                                    <span class="badge rounded-pill px-3 py-1" :class="getStatusClass(selectedStudent?.status)">
                                        {{ selectedStudent?.status }}
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
                                                    <div class="fw-semibold text-dark">{{ selectedStudent.jenis_kelamin === 'L' ? 'Laki-laki' : 'Perempuan' }}</div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="d-flex gap-3">
                                                <div class="text-primary mt-1"><i class="fas fa-pray"></i></div>
                                                <div>
                                                    <div class="small text-muted mb-1">Agama</div>
                                                    <div class="fw-semibold text-dark">{{ selectedStudent.agama || '-' }}</div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="d-flex gap-3">
                                                <div class="text-primary mt-1"><i class="fas fa-calendar-alt"></i></div>
                                                <div>
                                                    <div class="small text-muted mb-1">Tempat, Tanggal Lahir</div>
                                                    <div class="fw-semibold text-dark">{{ selectedStudent.tempat_lahir || '-' }}, {{ formatShortDate(selectedStudent.tanggal_lahir) }}</div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="d-flex gap-3">
                                                <div class="text-primary mt-1"><i class="fas fa-id-badge"></i></div>
                                                <div>
                                                    <div class="small text-muted mb-1">NIK</div>
                                                    <div class="fw-semibold text-dark">{{ selectedStudent.nik || '-' }}</div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="d-flex gap-3">
                                                <div class="text-primary mt-1"><i class="fas fa-file-invoice"></i></div>
                                                <div>
                                                    <div class="small text-muted mb-1">No. KK (NKK)</div>
                                                    <div class="fw-semibold text-dark">{{ selectedStudent.nkk || '-' }}</div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="d-flex gap-3">
                                                <div class="text-primary mt-1"><i class="fas fa-file-alt"></i></div>
                                                <div>
                                                    <div class="small text-muted mb-1">No. Akte</div>
                                                    <div class="fw-semibold text-dark">{{ selectedStudent.no_akte || '-' }}</div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="d-flex gap-3">
                                                <div class="text-primary mt-1"><i class="fas fa-child"></i></div>
                                                <div>
                                                    <div class="small text-muted mb-1">Anak Ke-</div>
                                                    <div class="fw-semibold text-dark">{{ selectedStudent.anak_ke || '-' }}</div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="d-flex gap-3">
                                                <div class="text-primary mt-1"><i class="fas fa-users"></i></div>
                                                <div>
                                                    <div class="small text-muted mb-1">Jumlah Saudara</div>
                                                    <div class="fw-semibold text-dark">{{ selectedStudent.jumlah_saudara || '-' }}</div>
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
                                                    <div class="fw-semibold text-dark">{{ selectedStudent.golongan_darah || '-' }}</div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="d-flex gap-3">
                                                <div class="text-danger mt-1"><i class="fas fa-ruler-vertical"></i></div>
                                                <div>
                                                    <div class="small text-muted mb-1">Tinggi Badan</div>
                                                    <div class="fw-semibold text-dark">{{ selectedStudent.tinggi_badan ? selectedStudent.tinggi_badan + ' cm' : '-' }}</div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="d-flex gap-3">
                                                <div class="text-danger mt-1"><i class="fas fa-weight"></i></div>
                                                <div>
                                                    <div class="small text-muted mb-1">Berat Badan</div>
                                                    <div class="fw-semibold text-dark">{{ selectedStudent.berat_badan ? selectedStudent.berat_badan + ' kg' : '-' }}</div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-12">
                                            <div class="d-flex gap-3">
                                                <div class="text-danger mt-1"><i class="fas fa-notes-medical"></i></div>
                                                <div>
                                                    <div class="small text-muted mb-1">Riwayat Penyakit</div>
                                                    <div class="fw-semibold text-dark">{{ selectedStudent.riwayat_penyakit || '-' }}</div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="d-flex gap-3">
                                                <div class="text-warning mt-1"><i class="fas fa-star"></i></div>
                                                <div>
                                                    <div class="small text-muted mb-1">Cita-cita</div>
                                                    <div class="fw-semibold text-dark">{{ selectedStudent.cita_cita || '-' }}</div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="d-flex gap-3">
                                                <div class="text-info mt-1"><i class="fas fa-palette"></i></div>
                                                <div>
                                                    <div class="small text-muted mb-1">Hobi</div>
                                                    <div class="fw-semibold text-dark">{{ selectedStudent.hobi || '-' }}</div>
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
                                                    <div class="fw-semibold text-dark">{{ selectedStudent.alamat || '-' }}</div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="d-flex gap-3">
                                                <div class="text-success mt-1"><i class="fas fa-sign"></i></div>
                                                <div>
                                                    <div class="small text-muted mb-1">RT</div>
                                                    <div class="fw-semibold text-dark">{{ selectedStudent.rt || '-' }}</div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="d-flex gap-3">
                                                <div class="text-success mt-1"><i class="fas fa-sign"></i></div>
                                                <div>
                                                    <div class="small text-muted mb-1">RW</div>
                                                    <div class="fw-semibold text-dark">{{ selectedStudent.rw || '-' }}</div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="d-flex gap-3">
                                                <div class="text-success mt-1"><i class="fas fa-home"></i></div>
                                                <div>
                                                    <div class="small text-muted mb-1">Tinggal Bersama</div>
                                                    <div class="fw-semibold text-dark">{{ selectedStudent.tinggal_bersama || '-' }}</div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="d-flex gap-3">
                                                <div class="text-success mt-1"><i class="fas fa-phone-alt"></i></div>
                                                <div>
                                                    <div class="small text-muted mb-1">No. Telepon / HP</div>
                                                    <div class="fw-semibold text-dark">{{ selectedStudent.no_telepon || '-' }}</div>
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
                                                    <div><small class="text-muted d-block">Nama</small><span class="fw-medium text-dark">{{ selectedStudent.nama_ayah || '-' }}</span></div>
                                                    <div><small class="text-muted d-block">NIK</small><span class="fw-medium text-dark">{{ selectedStudent.nik_ayah || '-' }}</span></div>
                                                    <div><small class="text-muted d-block">TTL</small><span class="fw-medium text-dark">{{ selectedStudent.tempat_lahir_ayah || '-' }}, {{ formatShortDate(selectedStudent.tanggal_lahir_ayah) }}</span></div>
                                                    <div><small class="text-muted d-block">Pekerjaan</small><span class="fw-medium text-dark">{{ selectedStudent.pekerjaan_ayah || '-' }}</span></div>
                                                    <div><small class="text-muted d-block">Pendidikan</small><span class="fw-medium text-dark">{{ selectedStudent.pendidikan_ayah || '-' }}</span></div>
                                                    <div><small class="text-muted d-block">Penghasilan</small><span class="text-success fw-medium">{{ selectedStudent.penghasilan_ayah || '-' }}</span></div>
                                                </div>
                                            </div>
                                        </div>
                                        <!-- Ibu -->
                                        <div class="col-md-6">
                                            <div class="p-3 bg-light rounded-3 h-100">
                                                <h6 class="text-danger fw-bold mb-3 border-bottom pb-2"><i class="fas fa-female me-2"></i>Data Ibu</h6>
                                                <div class="d-flex flex-column gap-3">
                                                    <div><small class="text-muted d-block">Nama</small><span class="fw-medium text-dark">{{ selectedStudent.nama_ibu || '-' }}</span></div>
                                                    <div><small class="text-muted d-block">NIK</small><span class="fw-medium text-dark">{{ selectedStudent.nik_ibu || '-' }}</span></div>
                                                    <div><small class="text-muted d-block">TTL</small><span class="fw-medium text-dark">{{ selectedStudent.tempat_lahir_ibu || '-' }}, {{ formatShortDate(selectedStudent.tanggal_lahir_ibu) }}</span></div>
                                                    <div><small class="text-muted d-block">Pekerjaan</small><span class="fw-medium text-dark">{{ selectedStudent.pekerjaan_ibu || '-' }}</span></div>
                                                    <div><small class="text-muted d-block">Pendidikan</small><span class="fw-medium text-dark">{{ selectedStudent.pendidikan_ibu || '-' }}</span></div>
                                                </div>
                                            </div>
                                        </div>
                                        <!-- Wali -->
                                        <div class="col-md-12" v-if="selectedStudent.nama_wali">
                                            <div class="p-3 bg-light rounded-3 h-100">
                                                <h6 class="text-secondary fw-bold mb-3 border-bottom pb-2"><i class="fas fa-user-shield me-2"></i>Data Wali</h6>
                                                <div class="row g-4">
                                                    <div class="col-md-6">
                                                        <div class="d-flex flex-column gap-3">
                                                            <div><small class="text-muted d-block">Nama Wali</small><span class="fw-medium text-dark">{{ selectedStudent.nama_wali || '-' }}</span></div>
                                                            <div><small class="text-muted d-block">NIK Wali</small><span class="fw-medium text-dark">{{ selectedStudent.nik_wali || '-' }}</span></div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="d-flex flex-column gap-3">
                                                            <div><small class="text-muted d-block">Pekerjaan</small><span class="fw-medium text-dark">{{ selectedStudent.pekerjaan_wali || '-' }}</span></div>
                                                            <div><small class="text-muted d-block">Pendidikan</small><span class="fw-medium text-dark">{{ selectedStudent.pendidikan_wali || '-' }}</span></div>
                                                            <div><small class="text-muted d-block">Hubungan</small><span class="fw-medium text-dark">{{ selectedStudent.hubungan_wali || '-' }}</span></div>
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
                                                    <div class="fw-semibold text-dark">{{ selectedStudent.sekolah_asal || '-' }}</div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="d-flex gap-3">
                                                <div class="text-info mt-1"><i class="fas fa-chalkboard-teacher"></i></div>
                                                <div>
                                                    <div class="small text-muted mb-1">Kelas Saat Ini</div>
                                                    <div class="fw-semibold text-dark">{{ selectedStudent.kelas || '-' }}</div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="d-flex gap-3">
                                                <div class="text-info mt-1"><i class="fas fa-sign-in-alt"></i></div>
                                                <div>
                                                    <div class="small text-muted mb-1">Status Masuk</div>
                                                    <div class="fw-semibold text-dark">{{ selectedStudent.status_masuk || '-' }}</div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="d-flex gap-3">
                                                <div class="text-info mt-1"><i class="fas fa-calendar-check"></i></div>
                                                <div>
                                                    <div class="small text-muted mb-1">Tanggal Masuk</div>
                                                    <div class="fw-semibold text-dark">{{ formatShortDate(selectedStudent.tanggal_masuk) }}</div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="d-flex gap-3">
                                                <div class="text-info mt-1"><i class="fas fa-user-check"></i></div>
                                                <div>
                                                    <div class="small text-muted mb-1">Status Keaktifan</div>
                                                    <div class="fw-semibold"><span class="badge bg-success bg-opacity-10 text-success">{{ selectedStudent.status }}</span></div>
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
                                                <a v-if="selectedStudent[key]" :href="`/admin/siswa/${selectedStudent.id}/document/${key}`" target="_blank" class="btn btn-sm btn-outline-primary rounded-pill px-3">
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
                        <button
                            type="button"
                            class="btn btn-primary"
                            @click="editStudent(selectedStudent)"
                        >
                            <i class="fas fa-edit me-1"></i> Edit Data
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Status Change Modal -->
        <div
            v-if="showStatusModal"
            class="modal fade show d-block"
            tabindex="-1"
            style="background: rgba(0, 0, 0, 0.6); z-index: 1050; backdrop-filter: blur(4px);"
            @click.self="showStatusModal = false"
        >
            <div
                class="modal-dialog modal-dialog-centered"
                style="z-index: 1060; max-width: 650px;"
            >
                <div class="modal-content" style="border: none; border-radius: 20px; box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);">
                    <!-- Header dengan gradient -->
                    <div class="text-center" style="background: linear-gradient(135deg, #3b82f6 0%, #1d4ed8 100%); padding: 30px 20px; border-radius: 20px 20px 0 0;">
                        <div class="mb-3" style="width: 70px; height: 70px; background: rgba(255,255,255,0.2); border-radius: 50%; display: inline-flex; align-items: center; justify-content: center; backdrop-filter: blur(10px);">
                            <i class="fas fa-user-minus fa-2x text-white"></i>
                        </div>
                        <h4 class="mb-1 fw-bold text-white">Ubah Status Siswa</h4>
                        <p class="mb-0 text-white" style="opacity: 0.9;">{{ studentToChangeStatus?.nama }}</p>
                        <button
                            type="button"
                            class="btn-close btn-close-white position-absolute"
                            style="top: 20px; right: 20px;"
                            @click="showStatusModal = false"
                        ></button>
                    </div>
                    
                    <div class="modal-body p-4">
                        <p class="text-center text-muted mb-4">Pilih alasan siswa tidak lagi aktif:</p>
                        
                        <!-- Year Picker (Moved to Top) -->
                        <div class="mb-4 text-center">
                            <label class="form-label fw-medium text-muted mb-2">Tahun Keluar/Pindah:</label>
                            <select 
                                v-model="selectedTahunKeluar" 
                                class="form-select mx-auto" 
                                style="max-width: 200px; border-radius: 10px; padding: 10px 15px;"
                            >
                                <option v-for="year in availableYears" :key="year" :value="year">{{ year }}</option>
                            </select>
                        </div>

                        <div class="row g-4 justify-content-center">
                            <!-- Dikeluarkan -->
                            <div class="col-md-4">
                                <div 
                                    class="status-card h-100 p-4 rounded-4 text-center"
                                    @click="changeStudentStatus('Dikeluarkan')"
                                    style="cursor: pointer; transition: all 0.3s ease; border: 2px solid #fee2e2; background: linear-gradient(180deg, #fff 0%, #fef2f2 100%);"
                                    @mouseover="$event.currentTarget.style.transform = 'translateY(-8px)'; $event.currentTarget.style.boxShadow = '0 20px 40px rgba(239, 68, 68, 0.25)'; $event.currentTarget.style.borderColor = '#ef4444';"
                                    @mouseleave="$event.currentTarget.style.transform = 'translateY(0)'; $event.currentTarget.style.boxShadow = 'none'; $event.currentTarget.style.borderColor = '#fee2e2';"
                                >
                                    <div class="mb-3 mx-auto" style="width: 80px; height: 80px; background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%); border-radius: 20px; display: flex; align-items: center; justify-content: center; box-shadow: 0 12px 24px rgba(239, 68, 68, 0.35);">
                                        <i class="fas fa-user-slash text-white fa-2x"></i>
                                    </div>
                                    <h5 class="mb-0 fw-bold text-danger">Dikeluarkan</h5>
                                </div>
                            </div>
                            
                            <!-- Mengundurkan Diri -->
                            <div class="col-md-4">
                                <div 
                                    class="status-card h-100 p-4 rounded-4 text-center"
                                    @click="changeStudentStatus('Mengundurkan Diri')"
                                    style="cursor: pointer; transition: all 0.3s ease; border: 2px solid #e2e8f0; background: linear-gradient(180deg, #fff 0%, #f8fafc 100%);"
                                    @mouseover="$event.currentTarget.style.transform = 'translateY(-8px)'; $event.currentTarget.style.boxShadow = '0 20px 40px rgba(100, 116, 139, 0.25)'; $event.currentTarget.style.borderColor = '#64748b';"
                                    @mouseleave="$event.currentTarget.style.transform = 'translateY(0)'; $event.currentTarget.style.boxShadow = 'none'; $event.currentTarget.style.borderColor = '#e2e8f0';"
                                >
                                    <div class="mb-3 mx-auto" style="width: 80px; height: 80px; background: linear-gradient(135deg, #64748b 0%, #475569 100%); border-radius: 20px; display: flex; align-items: center; justify-content: center; box-shadow: 0 12px 24px rgba(100, 116, 139, 0.35);">
                                        <i class="fas fa-hand-paper text-white fa-2x"></i>
                                    </div>
                                    <h5 class="mb-0 fw-bold text-dark">Mengundurkan Diri</h5>
                                </div>
                            </div>
                            
                            <!-- Pindah Sekolah -->
                            <div class="col-md-4">
                                <div 
                                    class="status-card h-100 p-4 rounded-4 text-center"
                                    @click="changeStudentStatus('Pindah')"
                                    style="cursor: pointer; transition: all 0.3s ease; border: 2px solid #fef3c7; background: linear-gradient(180deg, #fff 0%, #fffbeb 100%);"
                                    @mouseover="$event.currentTarget.style.transform = 'translateY(-8px)'; $event.currentTarget.style.boxShadow = '0 20px 40px rgba(245, 158, 11, 0.25)'; $event.currentTarget.style.borderColor = '#f59e0b';"
                                    @mouseleave="$event.currentTarget.style.transform = 'translateY(0)'; $event.currentTarget.style.boxShadow = 'none'; $event.currentTarget.style.borderColor = '#fef3c7';"
                                >
                                    <div class="mb-3 mx-auto" style="width: 80px; height: 80px; background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%); border-radius: 20px; display: flex; align-items: center; justify-content: center; box-shadow: 0 12px 24px rgba(245, 158, 11, 0.35);">
                                        <i class="fas fa-school text-white fa-2x"></i>
                                    </div>
                                    <h5 class="mb-0 fw-bold" style="color: #d97706;">Pindah Sekolah</h5>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="modal-footer border-0 justify-content-center pb-4">
                        <button
                            type="button"
                            class="btn px-5 py-2"
                            style="background: #f1f5f9; color: #64748b; border-radius: 10px; font-weight: 500;"
                            @click="showStatusModal = false"
                        >
                            Batal
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Bulk Actions Modal -->
        <div
            v-if="showBulkModal"
            class="modal fade show d-block"
            tabindex="-1"
            style="background: rgba(0, 0, 0, 0.5); z-index: 1050"
        >
            <div
                class="modal-dialog modal-dialog-centered modal-md"
                style="z-index: 1060"
            >
                <div class="modal-content modern-modal">
                    <div class="modal-header">
                        <div class="modal-title">
                            <i class="modal-icon fas fa-cogs"></i>
                            <div>
                                <h5>Aksi Massal</h5>
                                <p class="modal-subtitle text-muted">
                                    Kelola {{ selectedStudents.length }} siswa
                                    terpilih
                                </p>
                            </div>
                        </div>
                        <button
                            type="button"
                            class="btn-close"
                            @click="showBulkModal = false"
                        ></button>
                    </div>
                    <form @submit.prevent="applyBulkActions">
                        <div class="modal-body">
                            <div class="row g-3">
                                <div class="col-12">
                                    <div class="form-group">
                                        <label class="form-label"
                                            >Ubah Status</label
                                        >
                                        <select
                                            class="form-select"
                                            v-model="bulkAction.status"
                                        >
                                            <option value="">
                                                Pilih Status
                                            </option>
                                            <option value="Aktif">Aktif</option>
                                            <option value="Non-Aktif">
                                                Non-Aktif
                                            </option>
                                            <option value="Pindah">
                                                Pindah
                                            </option>
                                            <option value="Lulus">Lulus</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="form-group">
                                        <label class="form-label"
                                            >Pindah Kelas</label
                                        >
                                        <select
                                            class="form-select"
                                            v-model="bulkAction.kelas"
                                        >
                                            <option value="">
                                                Pilih Kelas
                                            </option>
                                            <option
                                                v-for="kelas in kelasList"
                                                :value="kelas"
                                                :key="kelas"
                                            >
                                                {{ kelas }}
                                            </option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="text-center">
                                        <p class="text-muted mb-3">Atau</p>
                                        <button
                                            type="button"
                                            @click="confirmBulkDelete"
                                            class="btn btn-outline-danger w-100"
                                        >
                                            <i class="fas fa-trash me-2"></i
                                            >Hapus
                                            {{ selectedStudents.length }} Siswa
                                            Terpilih
                                        </button>
                                        <small class="text-muted mt-2 d-block"
                                            >Aksi ini akan menghapus permanen
                                            semua siswa terpilih</small
                                        >
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button
                                type="button"
                                class="btn btn-light"
                                @click="showBulkModal = false"
                            >
                                Batal
                            </button>
                            <button
                                type="submit"
                                class="btn btn-primary"
                                :disabled="bulkForm.processing"
                            >
                                <i class="fas fa-check me-1"></i>
                                Terapkan Perubahan
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Bulk Delete Confirmation Modal -->
        <div
            v-if="showBulkDeleteModal"
            class="modal fade show d-block"
            tabindex="-1"
            style="background: rgba(0, 0, 0, 0.5); z-index: 1050"
        >
            <div
                class="modal-dialog modal-dialog-centered modal-sm"
                style="z-index: 1060"
            >
                <div class="modal-content modern-modal">
                    <div class="modal-body text-center py-4">
                        <div class="confirmation-icon mb-3">
                            <i
                                class="fas fa-exclamation-triangle fa-3x text-warning"
                            ></i>
                        </div>
                        <h5 class="mb-3">
                            Hapus {{ selectedStudents.length }} Siswa?
                        </h5>
                        <p class="text-muted mb-4">
                            <strong>{{ selectedStudents.length }} siswa</strong>
                            akan dihapus permanen. Tindakan ini tidak dapat
                            dibatalkan.
                        </p>
                    </div>
                    <div class="modal-footer justify-content-center">
                        <button
                            type="button"
                            class="btn btn-light"
                            @click="showBulkDeleteModal = false"
                        >
                            Batal
                        </button>
                        <button
                            type="button"
                            class="btn btn-danger"
                            @click="deleteBulkStudents"
                            :disabled="deletingBulk"
                        >
                            <i
                                class="fas"
                                :class="
                                    deletingBulk
                                        ? 'fa-spinner fa-spin'
                                        : 'fa-trash'
                                "
                            ></i>
                            Hapus Semua
                        </button>
                    </div>
                </div>
            </div>
        </div>
        <!-- Import Result Modal -->

        <!-- Document Preview Modal -->

    </AdminLayout>
</template>

<script setup>
import { ref, reactive, computed, watch, onMounted } from "vue";
import { Link, useForm, router, Head, usePage } from "@inertiajs/vue3";
import AdminLayout from "@/Layouts/AdminLayout.vue";
import axios from "axios";
import Swal from 'sweetalert2';

import { showToast } from '@/Utils/swal';

// Import EasyDataTable
import VueEasyDataTable from "vue3-easy-data-table";
import "vue3-easy-data-table/dist/style.css";

const EasyDataTable = VueEasyDataTable;
const page = usePage();


// Props dengan default values
const props = defineProps({
    siswa: {
        type: Object,
        default: () => ({
            data: [],
            links: [],
            current_page: 1,
            total: 0,
            from: 0,
            to: 0,
            per_page: 10,
        }),
    },
    filters: {
        type: Object,
        default: () => ({}),
    },
    kelasList: {
        type: Array,
        default: () => [],
    }
});

// State
const showAddModal = ref(false);
const showDetailModal = ref(false);
const showBulkModal = ref(false);
const showBulkDeleteModal = ref(false);
const showStatusModal = ref(false);
const isEditing = ref(false);
const loading = ref(false);
const deletingBulk = ref(false);
const importing = ref(false);
const changingStatus = ref(false);
const activeTab = ref("manual");
const formTab = ref("identity"); // New State for Form Tabs: identity, address, parents, guardian, school, other
const studentToChangeStatus = ref(null);
const selectedTahunKeluar = ref(new Date().getFullYear().toString());

// Generate available years (current year and 5 years back)
const currentYear = new Date().getFullYear();
const tahunKeluarOptions = Array.from({ length: 6 }, (_, i) => (currentYear - i).toString());

// Import Preview State
const importStep = ref(1);
const previewData = ref([]);
const previewColumns = ref([]);
const previewLoading = ref(false);
const previewStats = ref({ total: 0, valid: 0, invalid: 0 });
const importFile = ref(null); 
const fileInput = ref(null);

// Document Preview State (Removed as per request)

const resetImportState = () => {
    importStep.value = 1;
    previewData.value = [];
    previewColumns.value = [];
    previewStats.value = { valid: 0, invalid: 0, total: 0 };
    importFile.value = null;
    if (fileInput.value) fileInput.value.value = '';
};

// ... existing code ...



const availableYears = computed(() => {
    const currentYear = new Date().getFullYear();
    const years = [];
    for (let i = 0; i <= 5; i++) {
        years.push((currentYear - i).toString());
    }
    return years;
});

// Filters untuk SERVER-SIDE
const filters = reactive({
    search: props.filters?.search || "",
    kelas: props.filters?.kelas || "",
    jenis_kelamin: props.filters?.jenis_kelamin || "",
});

// Per page untuk pagination
const perPage = ref(props.siswa?.per_page || 10);

const changePerPage = () => {
    router.get("/admin/siswa", { ...filters, per_page: perPage.value }, {
        preserveState: true,
        preserveScroll: true,
        replace: true,
    });
};

// Student data untuk operasi
const selectedStudent = ref(null);

const selectedStudents = ref([]);

// Bulk actions
const bulkAction = reactive({
    status: "",
    kelas: "",
});

// Forms
const form = useForm({
    id: null,
    // Identity
    nis: "",
    nisn: "",
    nama: "",
    jenis_kelamin: "",
    tempat_lahir: "",
    tanggal_lahir: "",
    agama: "",
    nik: "",
    nkk: "",
    anak_ke: "",
    jumlah_saudara: "",
    
    // Contact & Address
    no_telepon: "",
    alamat: "",
    rt: "",
    rw: "",
    tinggal_bersama: "",
    
    // Ayah
    nama_ayah: "",
    nik_ayah: "",
    tempat_lahir_ayah: "",
    tanggal_lahir_ayah: "",
    pekerjaan_ayah: "",
    pendidikan_ayah: "",
    penghasilan_ayah: "",
    
    // Ibu
    nama_ibu: "",
    nik_ibu: "",
    tempat_lahir_ibu: "",
    tanggal_lahir_ibu: "",
    pekerjaan_ibu: "",
    pendidikan_ibu: "",
    
    // Wali
    nama_wali: "",
    nik_wali: "",
    pekerjaan_wali: "",
    pendidikan_wali: "",
    hubungan_wali: "",
    
    // School
    kelas: "",
    status: "Aktif",
    status_masuk: "",
    diterima_di_kelas: "",
    sekolah_asal: "",
    tanggal_masuk: "",
    
    // Physical & Misc
    riwayat_penyakit: "",
    berat_badan: "",
    tinggi_badan: "",
    golongan_darah: "",
    cita_cita: "",
    hobi: "",
    no_akte: "",
    
    // Document Files
    file_foto: null,
    file_akte: null,
    file_kk: null,
    file_kip: null,
    file_ktp_ortu: null,
    

});

const bulkForm = useForm({
    ids: [],
    status: "",
    kelas: "",
});

// Toast Notification Ref




// Watch for flash messages from Inertia
watch(
    () => page.props.flash,
    (flash) => {
        if (flash?.success) {
            showToast("success", "Sukses!", flash.success);
        }
        if (flash?.error) {
            showToast("error", "Error!", flash.error);
        }
        if (flash?.warning) {
            showToast("warning", "Peringatan!", flash.warning);
        }
        if (flash?.info) {
            showToast("info", "Info!", flash.info);
        }
    },
    { deep: true, immediate: true }
);

// Helper function untuk format tanggal
const formatDate = (dateString) => {
    if (!dateString) return "-";
    try {
        const date = new Date(dateString);
        if (isNaN(date.getTime())) return dateString; // Return original string if invalid date
        return date.toLocaleDateString("id-ID", {
            day: "2-digit",
            month: "short",
            year: "numeric",
        });
    } catch (e) {
        return dateString;
    }
};

// Table Headers
const headers = [
    { text: "No", value: "no", sortable: false},
    { text: "", value: "select", sortable: false  },
    { text: "NIS", value: "nis", sortable: true},
    { text: "Nama Siswa", value: "nama", sortable: true },
    { text: "Kelas", value: "kelas", sortable: true },
    { text: "JK", value: "jenis_kelamin", sortable: true },
    { text: "Tanggal Lahir", value: "tanggal_lahir", sortable: true },
    { text: "No. Telp", value: "no_telepon", sortable: true },
    { text: "Status", value: "status", sortable: true },
    { text: "Aksi", value: "actions", sortable: false },
];

// Computed property untuk data tabel
const tableData = computed(() => {
    if (!props.siswa?.data || !Array.isArray(props.siswa.data)) {
        return [];
    }
    
    const currentPage = props.siswa.current_page || 1;
    const itemsPerPage = props.siswa.per_page || 10;
    const start = (currentPage - 1) * itemsPerPage;
    
    return props.siswa.data.map((item, index) => ({
        ...item,
        no: start + index + 1
    }));
});

// Computed untuk check apakah semua terpilih
const isAllSelected = computed(() => {
    return props.siswa.data.length > 0 && selectedStudents.value.length === props.siswa.data.length;
});

// Toggle select all
const toggleSelectAll = () => {
    if (isAllSelected.value) {
        selectedStudents.value = [];
    } else {
        selectedStudents.value = props.siswa.data.map(item => item.id);
    }
};

// Debounce untuk search
let searchTimeout = null;
const onFilterChange = () => {
    if (searchTimeout) clearTimeout(searchTimeout);

    searchTimeout = setTimeout(() => {
        filter();
    }, 500);
};

// SERVER-SIDE Filter Function
const filter = () => {
    router.get("/admin/siswa", filters, {
        preserveState: true,
        preserveScroll: true,
        replace: true,
        onBefore: () => {
            loading.value = true;
        },
        onFinish: () => {
            loading.value = false;
        },
    });
};

const resetFilters = () => {
    Object.keys(filters).forEach((key) => {
        filters[key] = "";
    });
    filter();
    selectedStudents.value = [];
};

// Helper Functions
const getStatusClass = (status) => {
    switch (status) {
        case "Aktif": return "bg-success";
        case "Non-Aktif": return "bg-danger";
        case "Pindah": return "bg-warning";
        case "Lulus": return "bg-info";
        default: return "bg-secondary";
    }
};

const getStatusIcon = (status) => {
    switch (status) {
        case "Aktif": return "fas fa-check-circle";
        case "Non-Aktif": return "fas fa-times-circle";
        case "Pindah": return "fas fa-exchange-alt";
        case "Lulus": return "fas fa-graduation-cap";
        default: return "fas fa-question-circle";
    }
};

const formatShortDate = (dateString) => {
    if (!dateString) return "-";
    const date = new Date(dateString);
    return date.toLocaleDateString("id-ID", {
        day: "2-digit",
        month: "short",
        year: "numeric",
    });
};

const formatFileSize = (bytes) => {
    if (bytes === 0) return "0 Bytes";
    const k = 1024;
    const sizes = ["Bytes", "KB", "MB", "GB"];
    const i = Math.floor(Math.log(bytes) / Math.log(k));
    return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + " " + sizes[i];
};

const getInitials = (name) => {
    if (!name) return "??";
    const parts = name.split(" ");
    if (parts.length === 1) return parts[0].charAt(0).toUpperCase();
    return (parts[0].charAt(0) + parts[parts.length - 1].charAt(0)).toUpperCase();
};

// Confirm Bulk Delete
const confirmBulkDelete = () => {
    if (selectedStudents.value.length === 0) {
        showToast("warning", "Peringatan!", "Pilih siswa terlebih dahulu!");
        return;
    }
    
    Swal.fire({
        title: 'Hapus Data Siswa?',
        html: `Anda akan menghapus <strong>${selectedStudents.value.length}</strong> data siswa.<br>Tindakan ini tidak dapat dibatalkan.`,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#dc3545',
        cancelButtonColor: '#6c757d',
        confirmButtonText: '<i class="fas fa-trash me-1"></i>Ya, Hapus!',
        cancelButtonText: 'Batal'
    }).then((result) => {
        if (result.isConfirmed) {
            executeBulkDelete();
        }
    });
};

const executeBulkDelete = () => {
    deletingBulk.value = true;
    
    router.post('/admin/siswa/bulk-destroy', {
        ids: selectedStudents.value
    }, {
        preserveScroll: true,
        onSuccess: () => {
            Swal.fire({
                icon: 'success',
                title: 'Berhasil!',
                text: `${selectedStudents.value.length} data siswa berhasil dihapus!`,
                confirmButtonColor: '#4361ee',
                timer: 2000,
                timerProgressBar: true
            });
            selectedStudents.value = [];
        },
        onError: (errors) => {
            console.log("Bulk delete errors:", errors);
            showToast("error", "Gagal!", "Terjadi kesalahan saat menghapus data");
        },
        onFinish: () => {
            deletingBulk.value = false;
        }
    });
};

const applyBulkActions = () => {
    if (!bulkAction.status && !bulkAction.kelas) {
        showToast("warning", "Peringatan!", "Pilih setidaknya satu aksi (status atau kelas)!");
        return;
    }

    bulkForm.ids = selectedStudents.value;
    bulkForm.status = bulkAction.status;
    bulkForm.kelas = bulkAction.kelas;

    bulkForm.post("/admin/siswa/bulk-update", {
        preserveScroll: true,
        onSuccess: () => {
            Swal.fire({
                icon: 'success',
                title: 'Berhasil!',
                text: `Berhasil mengupdate ${selectedStudents.value.length} data siswa!`,
                confirmButtonColor: '#4361ee',
                timer: 2000,
                timerProgressBar: true
            });
            showBulkModal.value = false;
            selectedStudents.value = [];
            bulkAction.status = "";
            bulkAction.kelas = "";
            bulkForm.reset();
        },
        onError: (errors) => {
            console.log("Bulk update errors:", errors);
            showToast("error", "Gagal!", "Terjadi kesalahan saat mengupdate data");
        },
    });
};

const deleteBulkStudents = () => {
    deletingBulk.value = true;

    router.post("/admin/siswa/bulk-destroy", { ids: selectedStudents.value }, {
        preserveScroll: true,
        onSuccess: () => {
            showToast("success", "Sukses!", `Berhasil menghapus ${selectedStudents.value.length} data siswa`);
            deletingBulk.value = false;
            showBulkDeleteModal.value = false;
            selectedStudents.value = [];
        },
        onError: (errors) => {
            deletingBulk.value = false;
            console.log("Bulk delete errors:", errors);
            showToast("error", "Gagal!", "Terjadi kesalahan saat menghapus data");
        },
    });
};

// Student CRUD Operations
const viewStudentDetail = (student) => {
    selectedStudent.value = student;
    showDetailModal.value = true;
};

// Helper to safely format date YYYY-MM-DD for input
const formatDateForInput = (dateString) => {
    if (!dateString) return "";
    // If it's already YYYY-MM-DD
    if (dateString.match(/^\d{4}-\d{2}-\d{2}$/)) return dateString;
    // If it's ISO like 2024-05-20T00:00:00...
    try {
        const date = new Date(dateString);
        if (isNaN(date.getTime())) return "";
        return date.toISOString().split('T')[0];
    } catch (e) {
        return "";
    }
}

const editStudent = (student) => {
    isEditing.value = true;
    form.id = student.id;
    
    // Map all fields
    form.nis = student.nis;
    form.nisn = student.nisn;
    form.nama = student.nama;
    form.jenis_kelamin = student.jenis_kelamin;
    form.tempat_lahir = student.tempat_lahir;
    form.tanggal_lahir = formatDateForInput(student.tanggal_lahir);
    form.agama = student.agama;
    form.nik = student.nik;
    form.nkk = student.nkk;
    form.anak_ke = student.anak_ke;
    form.jumlah_saudara = student.jumlah_saudara;
    
    form.no_telepon = student.no_telepon;
    form.alamat = student.alamat;
    form.rt = student.rt;
    form.rw = student.rw;
    form.tinggal_bersama = student.tinggal_bersama;
    
    form.nama_ayah = student.nama_ayah;
    form.nik_ayah = student.nik_ayah;
    form.tempat_lahir_ayah = student.tempat_lahir_ayah;
    form.tanggal_lahir_ayah = formatDateForInput(student.tanggal_lahir_ayah);
    form.pekerjaan_ayah = student.pekerjaan_ayah;
    form.pendidikan_ayah = student.pendidikan_ayah;
    form.penghasilan_ayah = student.penghasilan_ayah;
    
    form.nama_ibu = student.nama_ibu;
    form.nik_ibu = student.nik_ibu;
    form.tempat_lahir_ibu = student.tempat_lahir_ibu;
    form.tanggal_lahir_ibu = formatDateForInput(student.tanggal_lahir_ibu);
    form.pekerjaan_ibu = student.pekerjaan_ibu;
    form.pendidikan_ibu = student.pendidikan_ibu;
    
    form.nama_wali = student.nama_wali;
    form.nik_wali = student.nik_wali;
    form.pekerjaan_wali = student.pekerjaan_wali;
    form.pendidikan_wali = student.pendidikan_wali;
    form.hubungan_wali = student.hubungan_wali;
    
    form.kelas = student.kelas;
    form.status = student.status;
    form.status_masuk = student.status_masuk;
    form.diterima_di_kelas = student.diterima_di_kelas;
    form.sekolah_asal = student.sekolah_asal;
    form.tanggal_masuk = formatDateForInput(student.tanggal_masuk);
    
    form.riwayat_penyakit = student.riwayat_penyakit;
    form.berat_badan = student.berat_badan;
    form.tinggi_badan = student.tinggi_badan;
    form.golongan_darah = student.golongan_darah;
    form.cita_cita = student.cita_cita;
    form.hobi = student.hobi;
    form.no_akte = student.no_akte;



    showAddModal.value = true;
    showDetailModal.value = false;
    activeTab.value = "manual";
    formTab.value = "identity"; // Reset to first tab
};

// Handle file upload for document fields
const handleFileUpload = (event, fieldName) => {
    const file = event.target.files[0];
    if (file) {
        form[fieldName] = file;
    }
};

// Validation helper with toast feedback
const validateForm = () => {
    const errors = [];
    
    if (!form.nis || form.nis.trim() === '') {
        errors.push('NIS wajib diisi');
    }
    if (!form.nama || form.nama.trim() === '') {
        errors.push('Nama Lengkap wajib diisi');
    }
    if (!form.jenis_kelamin) {
        errors.push('Jenis Kelamin wajib dipilih');
    }
    if (!form.kelas) {
        errors.push('Kelas wajib dipilih');
    }
    
    return errors;
};

// Save student with validation feedback
const saveStudent = () => {
    // Validate required fields
    const validationErrors = validateForm();
    
    if (validationErrors.length > 0) {
        showToast('error', 'Validasi Gagal', validationErrors.join(', '));
        
        // Navigate to the tab with the first error
        if (!form.nis || !form.nama || !form.jenis_kelamin) {
            formTab.value = 'identity';
        } else if (!form.kelas) {
            formTab.value = 'school';
        }
        return;
    }
    
    // Check if any files are being uploaded
    const hasFiles = form.file_foto || form.file_akte || form.file_kk || form.file_kip || form.file_ktp_ortu;
    
    const options = {
        preserveScroll: true,
        forceFormData: hasFiles, // Only use FormData if files present
        onSuccess: () => {
            Swal.fire({
                icon: 'success',
                title: 'Berhasil!',
                text: isEditing.value ? 'Data siswa berhasil diperbarui!' : 'Data siswa baru berhasil ditambahkan!',
                confirmButtonColor: '#4361ee',
                timer: 2000,
                timerProgressBar: true
            });
            closeModal();
        },
        onError: (errors) => {
            // Generic message only, fields have inline validation
            showToast('error', 'Gagal Menyimpan', 'Periksa inputan anda. Pastikan semua kolom wajib diisi dengan benar.');
        },
    };
    
    if (isEditing.value) {
        if (hasFiles) {
            form.post(`/admin/siswa/${form.id}?_method=PUT`, options);
        } else {
            form.put(`/admin/siswa/${form.id}`, options);
        }
    } else {
        form.post('/admin/siswa', options);
    }
};

const confirmDelete = async (student) => {
    // First check if student has pending tagihan
    try {
        const response = await fetch(`/admin/siswa/${student.id}/check-tagihan`);
        const data = await response.json();
        
        let title = 'Hapus Siswa?';
        let text = `Anda yakin ingin menghapus data "${student.nama}"?`;
        let icon = 'warning';
        
        // If student has pending tagihan, show warning
        if (data.has_tagihan) {
            const formattedAmount = new Intl.NumberFormat('id-ID', { 
                style: 'currency', 
                currency: 'IDR', 
                minimumFractionDigits: 0 
            }).format(data.total_piutang);
            
            title = '⚠️ Siswa Memiliki Piutang!';
            text = `${student.nama} masih memiliki ${data.jumlah_tagihan} tagihan belum lunas sebesar ${formattedAmount}. Data tagihan akan ikut terhapus. Lanjutkan hapus?`;
            icon = 'error';
        }
        
        Swal.fire({
            title: title,
            text: text,
            icon: icon,
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: data.has_tagihan ? 'Ya, Hapus Semua!' : 'Ya, Hapus!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                router.delete(`/admin/siswa/${student.id}`, {
                    onSuccess: () => {
                        // Toast handled by watcher
                    },
                    onError: () => {
                       showToast('error', 'Gagal', 'Terjadi kesalahan saat menghapus data');
                    }
                });
            }
        });
    } catch (error) {
        console.error('Error checking tagihan:', error);
        // Fallback to simple delete confirmation
        Swal.fire({
            title: 'Hapus Siswa?',
            text: `Anda yakin ingin menghapus data "${student.nama}"?`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Ya, Hapus!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                router.delete(`/admin/siswa/${student.id}`);
            }
        });
    }
};


// Status Change Functions
const showStatusChangeModal = (student) => {
    studentToChangeStatus.value = student;
    showStatusModal.value = true;
};

const changeStudentStatus = (newStatus) => {
    if (!studentToChangeStatus.value || changingStatus.value) return;

    changingStatus.value = true;

    // Use router.put with data directly
    router.put(`/admin/siswa/${studentToChangeStatus.value.id}`, {
        nis: studentToChangeStatus.value.nis,
        nama: studentToChangeStatus.value.nama,
        kelas: studentToChangeStatus.value.kelas,
        jenis_kelamin: studentToChangeStatus.value.jenis_kelamin,
        status: newStatus,
        tahun_lulus: selectedTahunKeluar.value
    }, {
        preserveScroll: true,
        onSuccess: () => {
            Swal.fire({
                icon: 'success',
                title: 'Berhasil!',
                text: `Status ${studentToChangeStatus.value.nama} diubah menjadi ${newStatus}!`,
                confirmButtonColor: '#4361ee',
                timer: 2000,
                timerProgressBar: true
            });
            showStatusModal.value = false;
            studentToChangeStatus.value = null;
            // Reset to current year for next use
            selectedTahunKeluar.value = new Date().getFullYear().toString();
        },
        onError: (errors) => {
            console.log("Status change errors:", errors);
            showToast("error", "Gagal!", "Terjadi kesalahan saat mengubah status");
        },
        onFinish: () => {
            changingStatus.value = false;
        },
    });
};

// Import Operations
const triggerFileInput = () => {
    document.querySelector('input[type="file"]').click();
};

const handleFileSelect = (event) => {
    const file = event.target.files[0];
    if (file) {
        if (file.size > 5 * 1024 * 1024) {
            showToast("error", "Error!", "Ukuran file terlalu besar! Maksimal 5MB.");
            return;
        }

        // Validasi ekstensi file
        const validExtensions = [".xlsx", ".xls", ".csv", ".xlsm"];
        const fileExtension = "." + file.name.split(".").pop().toLowerCase();

        if (!validExtensions.includes(fileExtension)) {
            showToast(
                "error",
                "Error!",
                "Format file tidak didukung! Hanya file Excel (.xlsx, .xls, .xlsm) atau CSV yang diperbolehkan."
            );
            return;
        }

        // Reset input value agar bisa pilih file yang sama jika gagal
        if (event.target.value) {
            event.target.value = '';
        }

        importFile.value = file;
        fetchPreview(); // Auto preview after select
    }
};



const handleFileDrop = (event) => {
    event.preventDefault();
    const file = event.dataTransfer.files[0];
    if (file) {
        handleFileSelect({ target: { files: [file] } });
    }
};

const removeImportFile = () => {
    importFile.value = null;
};

const fetchPreview = async () => {
    if (!importFile.value) {
        showWarning("Peringatan!", "Pilih file terlebih dahulu!");
        return;
    }
    
    previewLoading.value = true;
    const formData = new FormData();
    formData.append("file", importFile.value);
    
    try {
        const response = await axios.post("/admin/siswa/import/preview", formData, {
            headers: { "Content-Type": "multipart/form-data" }
        });
        
        previewData.value = response.data.preview;
        previewStats.value = {
            total: response.data.total_rows,
            valid: response.data.valid_count,
            invalid: response.data.invalid_count
        };
        
        importStep.value = 2; // Move to preview step
        
        if (response.data.invalid_count > 0) {
            showWarning("Perhatian", `${response.data.invalid_count} baris data tidak valid. Silakan cek tabel preview.`);
        }
        
    } catch (error) {
        console.error("Preview error:", error);
        showError("Gagal Preview", error.response?.data?.message || "Terjadi kesalahan saat memproses file");
    } finally {
        previewLoading.value = false;
    }
};

const submitImport = async () => {
    if (!importFile.value) return;

    importing.value = true;

    const formData = new FormData();
    formData.append("file", importFile.value);

    // Gunakan axios langsung agar tidak ada redirect/flash message confusion
    try {
        const response = await axios.post("/admin/siswa/import", formData, {
            headers: { "Content-Type": "multipart/form-data" }
        });

        if (response.data.success) {
            // User Feedback: Simple Toast (No Report Modal)
            const { successCount, errorCount } = response.data.importResult;
            
            if (errorCount > 0) {
                showToast(
                    "warning",
                    "Import Selesai", 
                    `${successCount} berhasil, ${errorCount} dilewati (invalid)`
                );
            } else {
                Swal.fire({
                    icon: 'success',
                    title: 'Import Berhasil!',
                    text: `Sukses mengimport ${successCount} data siswa!`,
                    confirmButtonColor: '#4361ee',
                    timer: 2000,
                    timerProgressBar: true
                });
            }

            // Tutup Modal Import/Upload
            closeModal();
            
            // Reload data tabel
            router.reload({ only: ["siswa"] });
        }
    } catch (error) {
        console.error("Import error:", error);
        const msg = error.response?.data?.message || "Terjadi kesalahan saat mengimport data";
        showToast("error", "Gagal Import", msg);
    } finally {
        importing.value = false;
    }
};

// Modal Operations
const closeModal = () => {
    showAddModal.value = false;
    isEditing.value = false;
    activeTab.value = "manual";
    formTab.value = "identity"; // Reset form tab
    
    resetImportState(); // Reset import state
    
    form.reset();
    form.clearErrors();
};

// Watch untuk perubahan filters
watch(
    filters,
    () => {
        // Reset selected students ketika filter berubah
        selectedStudents.value = [];
    },
    { deep: true }
);

// Watch untuk perubahan jumlah data yang dipilih
watch(
    selectedStudents,
    (newValue) => {
        // Update counter atau logika lainnya
    },
    { deep: true }
);

// Handle data siswa dari props
onMounted(() => {
    // Pastikan data selalu ada
    if (!props.siswa.data || !Array.isArray(props.siswa.data)) {
        console.warn("Data siswa tidak valid atau tidak ada");
    }
});
</script>

<style scoped>
/* Table Modern Premium */
.table-modern thead th {
    background-color: #f8fafc;
    color: #64748b;
    font-weight: 600;
    font-size: 0.8rem;
    text-transform: uppercase;
    letter-spacing: 0.05em;
    padding: 1rem 1.5rem;
    border-bottom: 2px solid #e2e8f0;
}

.table-modern tbody td {
    padding: 1rem 1.5rem;
    vertical-align: middle;
    color: #334155;
    font-size: 0.95rem;
    border-bottom: 1px solid #f1f5f9;
}

.table-modern tbody tr:last-child td {
    border-bottom: none;
}

.table-modern tbody tr:hover {
    background-color: #f8fafc;
}

/* Row Selected */
.row-selected td {
    background-color: #eff6ff !important;
}

/* Badge Tweaks */
.kelas-badge {
    background: #e0e7ff;
    color: #4338ca;
    padding: 0.35rem 0.75rem;
    border-radius: 9999px;
    font-weight: 600;
    font-size: 0.8rem;
}

.gender-badge {
    padding: 0.25rem 0.6rem;
    border-radius: 6px;
    font-size: 0.8rem;
    font-weight: 600;
    display: inline-flex;
    align-items: center;
    gap: 6px;
}
.gender-badge.male {
    background: #eff6ff;
    color: #2563eb;
}
.gender-badge.female {
    background: #fdf2f8;
    color: #db2777;
}

/* Modern Icon Actions - Premium Style */
.btn-icon-modern {
    width: 38px;
    height: 38px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    border: none;
    background-color: #f3f4f6;
    color: #6b7280;
    font-size: 1rem;
    transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
    cursor: pointer;
    box-shadow: 0 2px 4px rgba(0,0,0,0.02);
}

.btn-icon-modern:hover {
    transform: translateY(-2px);
    color: white;
}

/* View: Info Blue */
.btn-icon-view:hover {
    background-color: #3b82f6;
    box-shadow: 0 4px 12px rgba(59, 130, 246, 0.3);
}

/* Edit: Amber */
.btn-icon-edit:hover {
    background-color: #f59e0b; 
    box-shadow: 0 4px 12px rgba(245, 158, 11, 0.3);
}

/* Warning/Status: Orange */
.btn-icon-warning:hover {
    background-color: #f97316;
    box-shadow: 0 4px 12px rgba(249, 115, 22, 0.3);
}

/* Delete: Red */
.btn-icon-delete:hover {
    background-color: #ef4444; 
    box-shadow: 0 4px 12px rgba(239, 68, 68, 0.3);
}

/* Badge Styling Refinement */
.status-badge {
    padding: 0.35em 0.8em;
    font-size: 0.85em;
    font-weight: 600;
    border-radius: 50px;
    letter-spacing: 0.3px;
    display: inline-flex;
    align-items: center;
}

/* Form Tabs Navigation */
.form-tabs {
    background: #f8f9fa;
    border-radius: 0.5rem;
    padding: 0.5rem;
}

.form-tabs .nav-link {
    color: #6c757d;
    font-size: 0.8rem;
    font-weight: 500;
    padding: 0.5rem 0.75rem;
    border-radius: 0.375rem;
    transition: all 0.2s ease;
    border: none;
    background: transparent;
}

.form-tabs .nav-link:hover {
    color: #0d6efd;
    background: rgba(13, 110, 253, 0.1);
}

.form-tabs .nav-link.active {
    background: #0d6efd;
    color: white;
    box-shadow: 0 2px 8px rgba(13, 110, 253, 0.3);
}

.form-tabs .nav-link i {
    font-size: 0.9rem;
}

/* Required field indicator */
.form-label.required::after {
    content: " *";
    color: #dc3545;
}

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

/* Kelas badge large */
.kelas-badge-lg {
    background: #e9ecef;
    color: #495057;
}

/* Upload Card Styling */
.upload-card {
    border: 2px dashed #dee2e6;
    border-radius: 12px;
    padding: 20px;
    cursor: pointer;
    transition: all 0.3s ease;
    background: #f8f9fa;
}

.upload-card:hover {
    border-color: #0d6efd;
    background: #f0f4ff;
    transform: translateY(-2px);
}

.upload-card-content {
    display: flex;
    flex-direction: column;
    align-items: center;
    text-align: center;
    gap: 8px;
}

.upload-card-content.has-file {
    border-color: #198754;
    background: #f0fff4;
}

.upload-icon {
    font-size: 2rem;
    color: #6c757d;
    transition: color 0.3s ease;
}

.upload-card:hover .upload-icon {
    color: #0d6efd;
}

.upload-card-content.has-file .upload-icon {
    color: #198754;
}

.upload-label {
    font-weight: 600;
    font-size: 0.9rem;
    color: #495057;
}

.upload-hint {
    font-size: 0.75rem;
    color: #adb5bd;
}

.upload-status {
    font-size: 0.8rem;
    font-weight: 500;
}

/* SweetAlert Toast Customization */
body.swal2-toast-shown .swal2-container.swal2-top-end, 
body.swal2-toast-shown .swal2-container.swal2-top-right {
    top: 1rem !important;
    right: 1rem !important;
}

.small-toast {
    font-size: 0.85rem !important;
    width: auto !important; /* Allow auto width */
    min-width: 350px !important;
    max-width: 800px !important; /* Allow wide messages */
    padding: 0.75rem 1rem !important;
    white-space: nowrap !important; /* User requested no wrap */
    box-shadow: 0 4px 12px rgba(0,0,0,0.1) !important;
}

.small-toast-title {
    font-weight: 500 !important;
    margin: 0 !important;
    letter-spacing: 0.3px;
    color: #333;
}
</style>


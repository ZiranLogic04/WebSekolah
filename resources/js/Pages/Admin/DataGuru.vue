<template>
    <AdminLayout>
        <Head title="Data Guru" />

        <div class="content container-fluid">
            <!-- Modern Page Header -->
            <!-- Radiant Header Card -->
            <div class="card border-0 shadow-lg rounded-4 mb-4 overflow-hidden position-relative" style="background: linear-gradient(135deg, #4f46e5 0%, #3b82f6 100%);">
                <div class="card-body p-4 p-lg-5 position-relative z-1">
                    <div class="row align-items-center">
                        <div class="col-lg-8">
                            <div class="d-flex align-items-center gap-3 mb-2">
                                <div class="icon-box bg-white rounded-3 text-primary p-2">
                                    <i class="fas fa-chalkboard-teacher fs-3"></i>
                                </div>
                                <h2 class="fw-bold text-white mb-0 ls-tight">Data Guru</h2>
                            </div>
                            <p class="text-white-50 mb-0 fs-5">Manajemen data tenaga pengajar dan staf akademik.</p>
                        </div>
                        <div class="col-lg-4 text-lg-end mt-3 mt-lg-0">
                            <button @click="showAddModal = true" class="btn btn-light btn-lg rounded-pill shadow-sm px-4 fw-bold text-primary hover-elevate">
                                <i class="fas fa-plus-circle me-2"></i>Tambah Guru
                            </button>
                        </div>
                    </div>
                </div>
                <!-- Decorative Background -->
                <div class="position-absolute bottom-0 end-0 opacity-10 me-n5 mb-n5">
                    <i class="fas fa-chalkboard-teacher" style="font-size: 10rem; color: white;"></i>
                </div>
            </div>

            <!-- Modern Filter Card -->
            <div class="card filter-card mb-4">
                <div class="card-body">
                    <form @submit.prevent="filter" class="row g-3">
                        <div class="col-lg-3 col-md-6">
                            <div class="input-group input-group-modern">
                                <span class="input-group-text bg-white border-end-0 rounded-start-pill ps-3">
                                    <i class="fas fa-search text-muted"></i>
                                </span>
                                <input
                                    type="text"
                                    class="form-control border-start-0 rounded-end-pill ps-0"
                                    placeholder="Cari NIP atau Nama..."
                                    v-model="filters.search"
                                    @input="onFilterChange"
                                />
                            </div>
                        </div>
                        <div class="col-lg-2 col-md-6">
                            <select
                                class="form-select rounded-pill"
                                v-model="filters.status_kepegawaian"
                                @change="filter"
                            >
                                <option value="">Semua Status</option>
                                <option value="PNS">PNS</option>
                                <option value="Honorer">Honorer</option>
                                <option value="Kontrak">Kontrak</option>
                                <option value="Tetap">Tetap</option>
                            </select>
                        </div>
                        <div class="col-lg-2 col-md-6">
                            <select
                                class="form-select rounded-pill"
                                v-model="filters.status_aktif"
                                @change="filter"
                            >
                                <option value="">Semua Keaktifan</option>
                                <option value="Aktif">Aktif</option>
                                <option value="Non-Aktif">Non-Aktif</option>
                                <option value="Cuti">Cuti</option>
                                <option value="Pensiun">Pensiun</option>
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
                                <!-- Tombol Bulk Actions -->
                                <button
                                    type="button"
                                    @click="showBulkActions"
                                    class="btn btn-outline-primary w-50"
                                    :disabled="selectedTeachers.length === 0"
                                >
                                    <i class="fas fa-cog me-1"></i>Aksi ({{
                                        selectedTeachers.length
                                    }})
                                </button>
                            </div>
                        </div>
                    </form>
                    <!-- Info selected teachers -->
                    <div
                        v-if="selectedTeachers.length > 0"
                        class="mt-3 p-2 bg-light rounded"
                    >
                        <div
                            class="d-flex justify-content-between align-items-center"
                        >
                            <small>
                                <i
                                    class="fas fa-check-circle text-success me-1"
                                ></i>
                                {{ selectedTeachers.length }} guru terpilih
                            </small>
                            <button
                                @click="deselectAllTeachers"
                                class="btn btn-sm btn-link text-danger"
                            >
                                <i class="fas fa-times"></i> Batalkan
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Modern Table Card -->
            <div class="card modern-table-card">
                <div class="card-header">
                    <div
                        class="d-flex justify-content-between align-items-center"
                    >
                        <div>
                            <h5 class="card-title mb-0">Daftar Guru</h5>
                            <p class="card-subtitle text-muted mb-0">
                                {{ guru.total }} guru ditemukan
                            </p>
                        </div>
                        <div class="d-flex align-items-center gap-2">
                            <button
                                @click="selectAllTeachers"
                                class="btn btn-sm btn-outline-secondary"
                            >
                                <i class="fas fa-check-double me-1"></i>Pilih
                                Semua
                            </button>
                            <button
                                @click="deselectAllTeachers"
                                class="btn btn-sm btn-outline-secondary"
                            >
                                <i class="fas fa-times me-1"></i>Batalkan
                            </button>
                        </div>
                    </div>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-modern mb-0">
                            <thead>
                                <tr>
                                    <th width="50">
                                        <input type="checkbox" class="form-check-input" @change="toggleSelectAll" :checked="isAllSelected">
                                    </th>
                                    <th width="60" class="text-center">No</th>
                                    <th class="text-start">NIP</th>
                                    <th class="text-start">Nama</th>
                                    <th class="text-center">Bidang Studi</th>
                                    <th class="text-center">Status</th>
                                    <th class="text-center">Keaktifan</th>
                                    <th width="140" class="text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-if="tableData.length === 0">
                                    <td colspan="8" class="text-center py-5">
                                        <div class="empty-state">
                                            <div class="empty-icon mb-3">
                                                <i class="fas fa-chalkboard-teacher fa-3x text-muted"></i>
                                            </div>
                                            <h5 class="mb-2">Tidak ada data guru</h5>
                                            <p class="text-muted mb-4">Belum ada data guru yang tersedia</p>
                                            <button @click="showAddModal = true" class="btn btn-primary">
                                                <i class="fas fa-plus me-1"></i>Tambah Guru
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                                <tr v-for="(item, index) in tableData" :key="item.id" :class="{ 'row-selected': selectedTeachers.includes(item.id) }">
                                    <td>
                                        <input type="checkbox" class="form-check-input" :value="item.id" v-model="selectedTeachers">
                                    </td>
                                    <td class="text-center fw-medium text-muted">{{ guru.from + index }}</td>
                                    <td class="fw-medium">{{ item.nip }}</td>
                                    <td class="fw-semibold text-wrap" style="min-width: 200px; max-width: 300px;">{{ item.nama }}</td>
                                    <td class="text-center">
                                        <span class="badge bidang-badge text-wrap">{{ item.bidang_studi || '-' }}</span>
                                    </td>
                                    <td class="text-center">
                                        <span class="status-badge" :class="getKepegawaianClass(item.status_kepegawaian)">
                                            {{ item.status_kepegawaian || '-' }}
                                        </span>
                                    </td>
                                    <td class="text-center">
                                        <span class="status-badge" :class="getAktifClass(item.status_aktif)">
                                            <i :class="getAktifIcon(item.status_aktif) + ' me-1'"></i>
                                            {{ item.status_aktif }}
                                        </span>
                                    </td>
                                    <td class="text-nowrap text-center">
                                        <div class="d-flex gap-2 justify-content-center">
                                            <!-- View Detail -->
                                            <button 
                                                class="btn-icon-modern btn-icon-view" 
                                                @click="viewTeacherDetail(item)" 
                                                title="Detail Guru"
                                            >
                                                <i class="fas fa-eye"></i>
                                            </button>

                                            <!-- Edit -->
                                            <button 
                                                class="btn-icon-modern btn-icon-edit" 
                                                @click="editTeacher(item)" 
                                                title="Edit Data"
                                            >
                                                <i class="fas fa-edit"></i>
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
                                    Menampilkan {{ guru.from || 0 }} - {{ guru.to || 0 }} dari {{ guru.total || 0 }}
                                </small>
                            </div>
                            <div>
                                <nav>
                                    <ul class="pagination pagination-sm mb-0">
                                        <li class="page-item" :class="{ disabled: !guru.prev_page_url }">
                                            <Link class="page-link" :href="guru.prev_page_url || ''" preserve-state preserve-scroll>
                                                <i class="fas fa-chevron-left"></i>
                                            </Link>
                                        </li>
                                        <li v-for="page in guru.links" :key="page.label" class="page-item" :class="{ active: page.active, disabled: page.url === null }">
                                            <Link class="page-link" :href="page.url || ''" v-html="page.label" preserve-state preserve-scroll></Link>
                                        </li>
                                        <li class="page-item" :class="{ disabled: !guru.next_page_url }">
                                            <Link class="page-link" :href="guru.next_page_url || ''" preserve-state preserve-scroll>
                                                <i class="fas fa-chevron-right"></i>
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
                            <i
                                class="modal-icon text-white"
                                :class="
                                    isEditing
                                        ? 'fas fa-edit'
                                        : 'fas fa-user-plus'
                                "
                            ></i>
                            <div>
                                <h5 class="text-white mb-0">
                                    {{
                                        isEditing
                                            ? "Edit Data Guru"
                                            : "Tambah Data Guru"
                                    }}
                                </h5>
                                <p class="modal-subtitle text-white-50 mb-0">
                                    {{
                                        isEditing
                                            ? "Perbarui data guru yang sudah ada"
                                            : "Tambahkan data guru baru ke sistem"
                                    }}
                                </p>
                            </div>
                        </div>
                        <button
                            type="button"
                            class="btn-close btn-close-white"
                            @click="closeModal"
                        ></button>
                    </div>
                    <form @submit.prevent="saveTeacher">
                        <div class="modal-body">
                             <!-- Tabs Navigation -->
                            <div class="modal-tabs mb-4" v-if="!isEditing">
                                <div class="nav-tabs">
                                    <button
                                        type="button"
                                        class="nav-tab"
                                        :class="{ active: activeTab === 'manual' }"
                                        @click="activeTab = 'manual'"
                                    >
                                        <i class="fas fa-keyboard me-2"></i>Input Manual
                                    </button>
                                    <button
                                        type="button"
                                        class="nav-tab"
                                        :class="{ active: activeTab === 'import' }"
                                        @click="activeTab = 'import'"
                                    >
                                        <i class="fas fa-file-import me-2"></i>Import Excel
                                    </button>
                                </div>
                            </div>

                            <!-- Manual Input Form with Tabs -->
                            <div v-if="activeTab === 'manual'">
                                <!-- Form Tab Navigation -->
                                <div class="form-tabs mb-4">
                                    <nav class="nav nav-pills nav-fill">
                                        <button type="button" class="nav-link" :class="{ active: formTab === 'identity' }" @click="formTab = 'identity'">
                                            <i class="fas fa-user-tie me-1"></i> Identitas
                                        </button>
                                        <button type="button" class="nav-link" :class="{ active: formTab === 'employment' }" @click="formTab = 'employment'">
                                            <i class="fas fa-briefcase me-1"></i> Kepegawaian
                                        </button>
                                        <button type="button" class="nav-link" :class="{ active: formTab === 'contact' }" @click="formTab = 'contact'">
                                            <i class="fas fa-address-book me-1"></i> Kontak
                                        </button>
                                        <button type="button" class="nav-link" :class="{ active: formTab === 'social' }" @click="formTab = 'social'">
                                            <i class="fas fa-share-alt me-1"></i> Medsos
                                        </button>
                                    </nav>
                                </div>

                                <!-- Tab: Identitas -->
                                <div v-show="formTab === 'identity'" class="row g-3">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="form-label required">NIP</label>
                                            <div class="input-group">
                                                <span class="input-group-text"><i class="fas fa-id-card"></i></span>
                                                <input type="text" class="form-control" v-model="form.nip" :class="{ 'is-invalid': form.errors.nip }" placeholder="Masukkan NIP (Opsional)">
                                                <div v-if="form.errors.nip" class="invalid-feedback">{{ form.errors.nip }}</div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="form-label required">Nama Lengkap</label>
                                            <div class="input-group">
                                                <span class="input-group-text"><i class="fas fa-user"></i></span>
                                                <input type="text" class="form-control" v-model="form.nama" :class="{ 'is-invalid': form.errors.nama }" placeholder="Nama lengkap">
                                                <div v-if="form.errors.nama" class="invalid-feedback">{{ form.errors.nama }}</div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="form-label required">Jenis Kelamin</label>
                                            <select class="form-select" v-model="form.jenis_kelamin" :class="{ 'is-invalid': form.errors.jenis_kelamin }">
                                                <option value="">Pilih Jenis Kelamin</option>
                                                <option value="L">Laki-laki</option>
                                                <option value="P">Perempuan</option>
                                            </select>
                                            <div v-if="form.errors.jenis_kelamin" class="invalid-feedback">{{ form.errors.jenis_kelamin }}</div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
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
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="form-label">Tempat Lahir</label>
                                            <div class="input-group">
                                                <span class="input-group-text"><i class="fas fa-map-marker-alt"></i></span>
                                                <input type="text" class="form-control" v-model="form.tempat_lahir" placeholder="Kota kelahiran">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="form-label">Tanggal Lahir</label>
                                            <div class="input-group">
                                                <span class="input-group-text"><i class="fas fa-calendar"></i></span>
                                                <input type="date" class="form-control" v-model="form.tanggal_lahir">
                                            </div>
                                        </div>
                                    </div>
                                     <div class="col-12">
                                        <div class="form-group">
                                            <label class="form-label">Foto Guru</label>
                                            <div class="upload-card" @click="$refs.fotoInput.click()">
                                                <input type="file" ref="fotoInput" class="d-none" @change="handleFileUpload" accept="image/jpeg,image/png,image/jpg,image/webp">
                                                <div class="upload-card-content" :class="{ 'has-file': form.foto_preview, 'border-danger': form.errors.foto }">
                                                    <div v-if="form.foto_preview" class="text-center">
                                                        <img :src="form.foto_preview" class="img-thumbnail mb-2 shadow-sm" style="height: 100px; width: 100px; object-fit: cover; border-radius: 50%;">
                                                        <div class="upload-status text-success fw-bold"><i class="fas fa-check-circle me-1"></i>Foto dipilih</div>
                                                    </div>
                                                    <div v-else class="text-center">
                                                        <div class="upload-icon-circle bg-light rounded-circle p-3 d-inline-block mb-2">
                                                            <i class="fas fa-camera text-primary fs-3"></i>
                                                        </div>
                                                        <div class="upload-label fw-medium text-dark">Klik untuk upload foto</div>
                                                        <div class="upload-hint text-muted small">JPG, PNG, WebP (Maks 2MB)</div>
                                                    </div>
                                                </div>
                                                <div v-if="form.errors.foto" class="text-danger small mt-1 text-center font-weight-bold">{{ form.errors.foto }}</div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Tab: Kepegawaian -->
                                <div v-show="formTab === 'employment'" class="row g-3">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="form-label">Bidang Studi</label>
                                            <div class="input-group">
                                                <span class="input-group-text"><i class="fas fa-book"></i></span>
                                                <input type="text" class="form-control" v-model="form.bidang_studi" :class="{ 'is-invalid': form.errors.bidang_studi }" placeholder="Contoh: Matematika">
                                                <div class="invalid-feedback" v-if="form.errors.bidang_studi">{{ form.errors.bidang_studi }}</div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="form-label">Jabatan</label>
                                            <div class="input-group">
                                                <span class="input-group-text"><i class="fas fa-briefcase"></i></span>
                                                <input type="text" class="form-control" v-model="form.jabatan" :class="{ 'is-invalid': form.errors.jabatan }" placeholder="Contoh: Guru Kelas">
                                                <div class="invalid-feedback" v-if="form.errors.jabatan">{{ form.errors.jabatan }}</div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="form-label">Status Kepegawaian</label>
                                            <select class="form-select" v-model="form.status_kepegawaian" :class="{ 'is-invalid': form.errors.status_kepegawaian }">
                                                <option value="">Pilih Status</option>
                                                <option value="PNS">PNS</option>
                                                <option value="Honorer">Honorer</option>
                                                <option value="Kontrak">Kontrak</option>
                                                <option value="Tetap">Tetap</option>
                                            </select>
                                            <div class="invalid-feedback" v-if="form.errors.status_kepegawaian">{{ form.errors.status_kepegawaian }}</div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="form-label">Status Aktif</label>
                                            <select class="form-select" v-model="form.status_aktif" :class="{ 'is-invalid': form.errors.status_aktif }">
                                                <option value="Aktif">Aktif</option>
                                                <option value="Non-Aktif">Non-Aktif</option>
                                                <option value="Cuti">Cuti</option>
                                                <option value="Pensiun">Pensiun</option>
                                            </select>
                                            <div class="invalid-feedback" v-if="form.errors.status_aktif">{{ form.errors.status_aktif }}</div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="form-label">Pendidikan Terakhir</label>
                                            <div class="input-group">
                                                <span class="input-group-text"><i class="fas fa-graduation-cap"></i></span>
                                                <input type="text" class="form-control" v-model="form.pendidikan_terakhir" :class="{ 'is-invalid': form.errors.pendidikan_terakhir }" placeholder="Contoh: S1 Pendidikan">
                                                <div class="invalid-feedback" v-if="form.errors.pendidikan_terakhir">{{ form.errors.pendidikan_terakhir }}</div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="form-label">Tanggal Mulai Bekerja</label>
                                            <div class="input-group">
                                                <span class="input-group-text"><i class="fas fa-calendar-check"></i></span>
                                                <input type="date" class="form-control" v-model="form.tanggal_mulai_bekerja" :class="{ 'is-invalid': form.errors.tanggal_mulai_bekerja }">
                                                <div class="invalid-feedback" v-if="form.errors.tanggal_mulai_bekerja">{{ form.errors.tanggal_mulai_bekerja }}</div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Tab: Kontak -->
                                <div v-show="formTab === 'contact'" class="row g-3">
                                    <div class="col-12">
                                        <div class="form-group">
                                            <label class="form-label">Alamat Lengkap</label>
                                            <div class="input-group">
                                                <span class="input-group-text"><i class="fas fa-home"></i></span>
                                                <textarea class="form-control" rows="3" v-model="form.alamat" :class="{ 'is-invalid': form.errors.alamat }" placeholder="Alamat lengkap domisili"></textarea>
                                                <div v-if="form.errors.alamat" class="invalid-feedback">{{ form.errors.alamat }}</div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="form-label">Email</label>
                                            <div class="input-group">
                                                <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                                                <input type="email" class="form-control" v-model="form.email" :class="{ 'is-invalid': form.errors.email }" placeholder="nama@email.com">
                                                <div v-if="form.errors.email" class="invalid-feedback">{{ form.errors.email }}</div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="form-label">No. Telepon / HP</label>
                                            <div class="input-group">
                                                <span class="input-group-text"><i class="fas fa-phone"></i></span>
                                                <input type="text" class="form-control" v-model="form.no_telepon" :class="{ 'is-invalid': form.errors.no_telepon }" placeholder="08xxxxxxxxxx">
                                                <div v-if="form.errors.no_telepon" class="invalid-feedback">{{ form.errors.no_telepon }}</div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Tab: Medsos -->
                                <div v-show="formTab === 'social'" class="row g-3">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="form-label">Facebook</label>
                                            <div class="input-group">
                                                <span class="input-group-text"><i class="fab fa-facebook text-primary"></i></span>
                                                <input type="text" class="form-control" v-model="form.facebook" :class="{ 'is-invalid': form.errors.facebook }" placeholder="URL Profil Facebook">
                                                <div v-if="form.errors.facebook" class="invalid-feedback">{{ form.errors.facebook }}</div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="form-label">Instagram</label>
                                            <div class="input-group">
                                                <span class="input-group-text"><i class="fab fa-instagram text-danger"></i></span>
                                                <input type="text" class="form-control" v-model="form.instagram" :class="{ 'is-invalid': form.errors.instagram }" placeholder="URL Profil Instagram">
                                                <div v-if="form.errors.instagram" class="invalid-feedback">{{ form.errors.instagram }}</div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="form-label">Twitter / X</label>
                                            <div class="input-group">
                                                <span class="input-group-text"><i class="fab fa-twitter text-info"></i></span>
                                                <input type="text" class="form-control" v-model="form.twitter" :class="{ 'is-invalid': form.errors.twitter }" placeholder="URL Profil Twitter">
                                                <div v-if="form.errors.twitter" class="invalid-feedback">{{ form.errors.twitter }}</div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="form-label">LinkedIn</label>
                                            <div class="input-group">
                                                <span class="input-group-text"><i class="fab fa-linkedin text-primary"></i></span>
                                                <input type="text" class="form-control" v-model="form.linkedin" :class="{ 'is-invalid': form.errors.linkedin }" placeholder="URL Profil LinkedIn">
                                                <div v-if="form.errors.linkedin" class="invalid-feedback">{{ form.errors.linkedin }}</div>
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
                                            <i class="fas fa-file-excel fa-3x text-success"></i>
                                        </div>
                                        <h4>Import Data Excel</h4>
                                        <p class="text-muted mb-2">
                                            Upload file Excel untuk menambahkan banyak data sekaligus
                                        </p>
                                        <button type="button" @click="downloadTemplate" class="btn btn-sm btn-outline-success">
                                            <i class="fas fa-download me-1"></i> Download Template
                                        </button>
                                    </div>

                                    <div class="import-guide mb-4">
                                        <div class="card bg-light border-0 p-3">
                                            <h6 class="mb-2">
                                                <i class="fas fa-info-circle me-2 text-primary"></i>Petunjuk Import:
                                            </h6>
                                            <ul class="small text-muted mb-0 ps-3">
                                                <li>Gunakan template Excel yang disediakan</li>
                                                <li>Pastikan format kolom sesuai (NIP, Nama, dll)</li>
                                                <li>NIP harus unik untuk setiap guru</li>
                                            </ul>
                                        </div>
                                    </div>

                                    <div
                                        class="upload-zone"
                                        @click="triggerFileInput"
                                        @dragover.prevent
                                        @dragenter.prevent
                                        @dragleave.prevent
                                        @drop.prevent="handleFileDrop"
                                    >
                                        <input
                                            type="file"
                                            ref="fileInputImport"
                                            @change="handleFileSelect"
                                            accept=".xlsx,.xls,.csv"
                                            hidden
                                        />

                                        <div class="upload-content text-center">
                                            <i class="fas fa-cloud-upload-alt fa-2x text-primary mb-3"></i>
                                            <h5>Klik atau drag file Excel ke sini</h5>
                                            <p class="text-muted mb-3">Format: .xlsx, .xls, .csv (Maks. 5MB)</p>
                                            <button type="button" class="btn btn-outline-primary">
                                                <i class="fas fa-folder-open me-2"></i>Pilih File
                                            </button>
                                        </div>
                                    </div>

                                    <div v-if="importFile" class="mt-4">
                                        <div class="file-preview">
                                            <div class="file-info">
                                                <i class="fas fa-file-excel text-success me-3"></i>
                                                <div class="flex-grow-1">
                                                    <h6 class="mb-1">{{ importFile.name }}</h6>
                                                    <p class="text-muted mb-0">{{ formatFileSize(importFile.size) }}</p>
                                                </div>
                                                <button type="button" @click="removeImportFile" class="btn btn-link text-danger">
                                                    <i class="fas fa-times"></i>
                                                </button>
                                            </div>
                                        </div>

                                        <div class="text-center mt-3">
                                            <button
                                                type="button"
                                                @click="processImport"
                                                class="btn btn-success"
                                                :disabled="importing"
                                            >
                                                <i class="fas fa-upload me-2"></i>
                                                {{ importing ? 'Mengimport...' : 'Import Data' }}
                                            </button>
                                        </div>
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
                                    :class="
                                        form.processing ? 'fa-spinner fa-spin' : 'fa-save'
                                    "
                                ></i>
                                {{
                                    form.processing
                                        ? "Menyimpan..."
                                        : isEditing
                                        ? "Update Data"
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
                <div class="modal-content modern-modal">
                    <div class="modal-header text-white" style="background: linear-gradient(135deg, #4361ee 0%, #3a0ca3 100%);">
                        <div class="modal-title">
                            <i class="modal-icon fas fa-chalkboard-teacher text-white"></i>
                            <div>
                                <h5 class="text-white mb-0">Detail Data Guru</h5>
                                <p class="modal-subtitle text-white-50 mb-0">
                                    Informasi lengkap data guru
                                </p>
                            </div>
                        </div>
                        <button
                            type="button"
                            class="btn-close btn-close-white"
                            @click="showDetailModal = false"
                        ></button>
                    </div>
                    <div class="modal-body">
                        <div v-if="selectedTeacher" class="teacher-detail">
                            <!-- Teacher Header -->
                            <div class="teacher-header mb-4">
                                <div class="d-flex align-items-center">
                                    <div class="teacher-avatar me-3">
                                        <div class="avatar-circle">
                                            {{
                                                getInitials(
                                                    selectedTeacher.nama
                                                )
                                            }}
                                        </div>
                                    </div>
                                    <div class="teacher-info">
                                        <h4 class="mb-1">
                                            {{ selectedTeacher.nama }}
                                        </h4>
                                        <div class="d-flex flex-wrap gap-2">
                                            <span class="badge bg-primary">{{
                                                selectedTeacher.nip
                                            }}</span>
                                            <span
                                                class="badge"
                                                :class="
                                                    getKepegawaianClass(
                                                        selectedTeacher.status_kepegawaian
                                                    )
                                                "
                                            >
                                                {{
                                                    selectedTeacher.status_kepegawaian ||
                                                    "-"
                                                }}
                                            </span>
                                            <span
                                                class="badge"
                                                :class="
                                                    getAktifClass(
                                                        selectedTeacher.status_aktif
                                                    )
                                                "
                                            >
                                                {{ selectedTeacher.status_aktif }}
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Teacher Information Grid -->
                            <div class="row g-4">
                                <div class="col-md-6">
                                    <div class="info-card">
                                        <div class="info-card-header">
                                            <i
                                                class="fas fa-user-circle me-2"
                                            ></i>
                                            <span>Informasi Pribadi</span>
                                        </div>
                                        <div class="info-card-body">
                                            <div class="info-item">
                                                <span class="info-label"
                                                    >Jenis Kelamin</span
                                                >
                                                <span class="info-value">
                                                    <span
                                                        class="gender-badge small"
                                                        :class="
                                                            selectedTeacher.jenis_kelamin ===
                                                            'L'
                                                                ? 'male'
                                                                : 'female'
                                                        "
                                                    >
                                                        {{
                                                            selectedTeacher.jenis_kelamin ===
                                                            "L"
                                                                ? "Laki-laki"
                                                                : "Perempuan"
                                                        }}
                                                    </span>
                                                </span>
                                            </div>
                                            <div class="info-item">
                                                <span class="info-label"
                                                    >TTL</span
                                                >
                                                <span class="info-value">
                                                    {{
                                                        selectedTeacher.tempat_lahir ||
                                                        "-"
                                                    }},
                                                    {{
                                                        formatShortDate(
                                                            selectedTeacher.tanggal_lahir
                                                        )
                                                    }}
                                                </span>
                                            </div>
                                            <div class="info-item">
                                                <span class="info-label"
                                                    >Alamat</span
                                                >
                                                <span class="info-value">{{
                                                    selectedTeacher.alamat ||
                                                    "-"
                                                }}</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="info-card">
                                        <div class="info-card-header">
                                            <i
                                                class="fas fa-briefcase me-2"
                                            ></i>
                                            <span>Informasi Profesional</span>
                                        </div>
                                        <div class="info-card-body">
                                            <div class="info-item">
                                                <span class="info-label"
                                                    >Bidang Studi</span
                                                >
                                                <span class="info-value">{{
                                                    selectedTeacher.bidang_studi ||
                                                    "-"
                                                }}</span>
                                            </div>
                                            <div class="info-item">
                                                <span class="info-label"
                                                    >Pendidikan Terakhir</span
                                                >
                                                <span class="info-value">{{
                                                    selectedTeacher.pendidikan_terakhir ||
                                                    "-"
                                                }}</span>
                                            </div>
                                            <div class="info-item">
                                                <span class="info-label"
                                                    >Mulai Bekerja</span
                                                >
                                                <span class="info-value">
                                                    {{
                                                        formatShortDate(
                                                            selectedTeacher.tanggal_mulai_bekerja
                                                        ) || "-"
                                                    }}
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="info-card">
                                        <div class="info-card-header">
                                            <i
                                                class="fas fa-address-book me-2"
                                            ></i>
                                            <span>Kontak</span>
                                        </div>
                                        <div class="info-card-body">
                                            <div class="info-item">
                                                <span class="info-label"
                                                    >Email</span
                                                >
                                                <span class="info-value">
                                                    <i
                                                        class="fas fa-envelope text-muted me-1"
                                                    ></i>
                                                    {{
                                                        selectedTeacher.email ||
                                                        "-"
                                                    }}
                                                </span>
                                            </div>
                                            <div class="info-item">
                                                <span class="info-label"
                                                    >No. Telepon</span
                                                >
                                                <span class="info-value">
                                                    <i
                                                        class="fas fa-phone text-muted me-1"
                                                    ></i>
                                                    {{
                                                        selectedTeacher.no_telepon ||
                                                        "-"
                                                    }}
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="info-card">
                                        <div class="info-card-header">
                                            <i
                                                class="fas fa-info-circle me-2"
                                            ></i>
                                            <span>Informasi Tambahan</span>
                                        </div>
                                        <div class="info-card-body">
                                            <div class="info-item">
                                                <span class="info-label"
                                                    >Terdaftar</span
                                                >
                                                <span class="info-value">{{
                                                    formatShortDate(
                                                        selectedTeacher.created_at
                                                    )
                                                }}</span>
                                            </div>
                                            <div class="info-item">
                                                <span class="info-label"
                                                    >Status</span
                                                >
                                                <span class="info-value">
                                                    <span
                                                        class="status-badge"
                                                        :class="
                                                            getAktifClass(
                                                                selectedTeacher.status_aktif
                                                            )
                                                        "
                                                    >
                                                        {{
                                                            selectedTeacher.status_aktif
                                                        }}
                                                    </span>
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button
                            type="button"
                            class="btn btn-secondary"
                            @click="showDetailModal = false"
                        >
                            Tutup
                        </button>
                        <button
                            type="button"
                            class="btn btn-warning"
                            @click="editTeacher(selectedTeacher)"
                        >
                            <i class="fas fa-edit me-1"></i>Edit Data
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modern Confirmation Modal -->
        <div
            v-if="showConfirmModal"
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
                        <h5 class="mb-3">Hapus Data?</h5>
                        <p class="text-muted mb-4">
                            Data
                            <strong>{{ teacherToDelete?.nama }}</strong> akan
                            dihapus permanen. Tindakan ini tidak dapat
                            dibatalkan.
                        </p>
                    </div>
                    <div class="modal-footer justify-content-center">
                        <button
                            type="button"
                            class="btn btn-light"
                            @click="showConfirmModal = false"
                        >
                            Batal
                        </button>
                        <button
                            type="button"
                            class="btn btn-danger"
                            @click.prevent="deleteTeacher"
                            :disabled="deleting"
                        >
                            <i
                                class="fas"
                                :class="
                                    deleting ? 'fa-spinner fa-spin' : 'fa-trash'
                                "
                            ></i>
                            Hapus
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
                                    Kelola {{ selectedTeachers.length }} guru
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
                                            >Ubah Status Aktif</label
                                        >
                                        <select
                                            class="form-select"
                                            v-model="bulkAction.status_aktif"
                                        >
                                            <option value="">
                                                Pilih Status
                                            </option>
                                            <option value="Aktif">Aktif</option>
                                            <option value="Non-Aktif">
                                                Non-Aktif
                                            </option>
                                            <option value="Cuti">Cuti</option>
                                            <option value="Pensiun">
                                                Pensiun
                                            </option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="form-group">
                                        <label class="form-label"
                                            >Ubah Status Kepegawaian</label
                                        >
                                        <select
                                            class="form-select"
                                            v-model="bulkAction.status_kepegawaian"
                                        >
                                            <option value="">
                                                Pilih Status
                                            </option>
                                            <option value="PNS">PNS</option>
                                            <option value="Honorer">
                                                Honorer
                                            </option>
                                            <option value="Kontrak">
                                                Kontrak
                                            </option>
                                            <option value="Tetap">Tetap</option>
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
                                            {{ selectedTeachers.length }} Guru
                                            Terpilih
                                        </button>
                                        <small class="text-muted mt-2 d-block"
                                            >Aksi ini akan menghapus permanen
                                            semua guru terpilih</small
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
                            Hapus {{ selectedTeachers.length }} Guru?
                        </h5>
                        <p class="text-muted mb-4">
                            <strong>{{ selectedTeachers.length }} guru</strong>
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
                            @click="deleteBulkTeachers"
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

                        <!-- Detail Guru Modal -->
        <!-- Detail Guru Modal -->
        <div
            v-if="showDetailModal"
            class="modal fade show d-block"
            tabindex="-1"
            role="dialog"
            aria-modal="true"
            style="background: rgba(0, 0, 0, 0.5); z-index: 9999; display: block;"
        >
            <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable" style="z-index: 10000">
                <div class="modal-content modern-modal border-0 overflow-hidden" v-if="selectedTeacher">
                    <div class="detail-header text-center p-3 position-relative">
                        <button 
                            type="button" 
                            class="btn-close btn-close-white position-absolute top-0 end-0 m-3" 
                            @click="showDetailModal = false"
                        ></button>
                        
                        <div class="detail-avatar-large shadow-sm mb-2 mx-auto">
                            <img v-if="selectedTeacher.foto" :src="`/storage/${selectedTeacher.foto}`" alt="Foto Guru">
                            <span v-else>{{ getInitials(selectedTeacher.nama) }}</span>
                        </div>
                        
                        <h5 class="text-white fw-bold mb-2">{{ selectedTeacher.nama }}</h5>
                        <div class="d-flex justify-content-center flex-wrap gap-2">
                            <span class="badge bg-white text-primary border border-white text-uppercase fw-bold px-3 py-2 rounded-pill shadow-sm" style="opacity: 0.95;">
                                <i class="fas fa-id-badge me-1"></i> {{ selectedTeacher.nip }}
                            </span>
                            <span class="badge bg-dark text-warning border border-dark text-uppercase fw-bold px-3 py-2 rounded-pill shadow-sm" style="opacity: 0.9;">
                                <i class="fas fa-briefcase me-1"></i> {{ selectedTeacher.status_kepegawaian }}
                            </span>
                            <span class="badge bg-white text-success border border-white text-uppercase fw-bold px-3 py-2 rounded-pill shadow-sm" style="opacity: 0.95;">
                                <i class="fas fa-check-circle me-1"></i> {{ selectedTeacher.status_aktif }}
                            </span>
                        </div>
                    </div>

                    <div class="modal-body bg-light p-3">
                        <!-- Tab Navigation -->
                        <div class="d-flex justify-content-center mb-3">
                            <div class="bg-white p-1 rounded-pill shadow-sm d-inline-flex">
                                <button 
                                    class="btn btn-sm rounded-pill px-3 fw-bold transition-all"
                                    :class="detailTab === 'kepegawaian' ? 'btn-primary shadow-sm' : 'btn-light text-muted bg-transparent'"
                                    @click="detailTab = 'kepegawaian'"
                                >
                                    <i class="fas fa-id-card me-1"></i>Kepegawaian
                                </button>
                                <button 
                                    class="btn btn-sm rounded-pill px-3 fw-bold transition-all"
                                    :class="detailTab === 'pribadi' ? 'btn-primary shadow-sm' : 'btn-light text-muted bg-transparent'"
                                    @click="detailTab = 'pribadi'"
                                >
                                    <i class="fas fa-user me-1"></i>Pribadi
                                </button>
                                <button 
                                    class="btn btn-sm rounded-pill px-3 fw-bold transition-all"
                                    :class="detailTab === 'kontak' ? 'btn-primary shadow-sm' : 'btn-light text-muted bg-transparent'"
                                    @click="detailTab = 'kontak'"
                                >
                                    <i class="fas fa-address-book me-1"></i>Kontak
                                </button>
                            </div>
                        </div>

                        <!-- Tab Content -->
                        <div class="tab-content">
                            <!-- Data Kepegawaian -->
                            <div v-show="detailTab === 'kepegawaian'" class="animate__animated animate__fadeIn">
                                <div class="info-card p-3">
                                    <h6 class="text-primary fw-bold mb-3 border-bottom pb-2">
                                        <i class="fas fa-id-card me-2"></i>Data Kepegawaian
                                    </h6>
                                    <div class="row g-3">
                                        <div class="col-md-6">
                                            <div class="info-item">
                                                <span class="info-label">NIP</span>
                                                <span class="info-value">{{ selectedTeacher.nip || '-' }}</span>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="info-item">
                                                <span class="info-label">Bidang Studi</span>
                                                <span class="info-value">{{ selectedTeacher.bidang_studi || '-' }}</span>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="info-item">
                                                <span class="info-label">Status Kepegawaian</span>
                                                <span class="badge bg-warning text-dark border border-dark px-3 py-2 rounded-pill shadow-sm" style="opacity: 0.9;">
                                                     <i class="fas fa-briefcase me-1"></i> {{ selectedTeacher.status_kepegawaian }}
                                                </span>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="info-item">
                                                <span class="info-label">Status Aktif</span>
                                                <span class="badge bg-success text-white border border-white px-3 py-2 rounded-pill shadow-sm" style="opacity: 0.9;">
                                                     <i class="fas fa-check-circle me-1"></i> {{ selectedTeacher.status_aktif }}
                                                </span>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="info-item">
                                                <span class="info-label">Mulai Tugas</span>
                                                <span class="info-value">{{ selectedTeacher.tmt_kerja ? formatShortDate(selectedTeacher.tmt_kerja) : '-' }}</span>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="info-item">
                                                <span class="info-label">Pendidikan Terakhir</span>
                                                <span class="info-value">{{ selectedTeacher.pendidikan_terakhir || '-' }}</span>
                                            </div>
                                        </div>
                                        <div class="col-12">
                                            <div class="info-item">
                                                <span class="info-label">Tempat Tugas</span>
                                                <span class="info-value">{{ selectedTeacher.tempat_tugas || '-' }}</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Data Pribadi -->
                            <div v-show="detailTab === 'pribadi'" class="animate__animated animate__fadeIn">
                                <div class="info-card p-3">
                                    <h6 class="text-primary fw-bold mb-3 border-bottom pb-2">
                                        <i class="fas fa-user me-2"></i>Data Pribadi
                                    </h6>
                                    <div class="row g-3">
                                        <div class="col-md-6">
                                            <div class="info-item">
                                                <span class="info-label">Nama Lengkap</span>
                                                <span class="info-value">{{ selectedTeacher.nama }}</span>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="info-item">
                                                <span class="info-label">Jenis Kelamin</span>
                                                <span class="info-value">
                                                    <i :class="selectedTeacher.jenis_kelamin === 'L' ? 'fas fa-mars text-primary' : 'fas fa-venus text-danger'" class="me-1"></i>
                                                    {{ selectedTeacher.jenis_kelamin === 'L' ? 'Laki-laki' : 'Perempuan' }}
                                                </span>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="info-item">
                                                <span class="info-label">Tempat Lahir</span>
                                                <span class="info-value">{{ selectedTeacher.tempat_lahir || '-' }}</span>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="info-item">
                                                <span class="info-label">Tanggal Lahir</span>
                                                <span class="info-value">
                                                    {{ selectedTeacher.tanggal_lahir ? formatShortDate(selectedTeacher.tanggal_lahir) : '-' }}
                                                </span>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="info-item">
                                                <span class="info-label">Agama</span>
                                                <span class="info-value">{{ selectedTeacher.agama || '-' }}</span>
                                            </div>
                                        </div>
                                        <div class="col-12">
                                            <div class="info-item">
                                                <span class="info-label">Alamat Lengkap</span>
                                                <span class="info-value">{{ selectedTeacher.alamat || '-' }}</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Kontak & Medsos -->
                            <div v-show="detailTab === 'kontak'" class="animate__animated animate__fadeIn">
                                <div class="info-card p-3">
                                    <h6 class="text-primary fw-bold mb-3 border-bottom pb-2">
                                        <i class="fas fa-address-book me-2"></i>Kontak & Media Sosial
                                    </h6>
                                    <div class="row g-3">
                                        <div class="col-md-6">
                                            <div class="info-item border-0 p-0 mb-2">
                                                <span class="info-label">Email</span>
                                                <div class="d-flex align-items-center mt-1">
                                                    <div class="icon-box-sm bg-light text-primary rounded-circle me-2" style="width: 32px; height: 32px; display: flex; align-items: center; justify-content: center;">
                                                        <i class="fas fa-envelope"></i>
                                                    </div>
                                                    <span class="info-value">{{ selectedTeacher.email || '-' }}</span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="info-item border-0 p-0 mb-2">
                                                <span class="info-label">No. Telepon</span>
                                                <div class="d-flex align-items-center mt-1">
                                                    <div class="icon-box-sm bg-light text-success rounded-circle me-2" style="width: 32px; height: 32px; display: flex; align-items: center; justify-content: center;">
                                                        <i class="fas fa-phone"></i>
                                                    </div>
                                                    <span class="info-value">{{ selectedTeacher.no_telepon || '-' }}</span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-12 mt-4" v-if="selectedTeacher.facebook || selectedTeacher.instagram || selectedTeacher.twitter || selectedTeacher.linkedin">
                                            <span class="info-label mb-3 d-block">Media Sosial</span>
                                            <div class="d-flex gap-3">
                                                <a v-if="selectedTeacher.facebook" :href="selectedTeacher.facebook" target="_blank" class="btn btn-outline-primary rounded-pill d-flex align-items-center gap-2">
                                                    <i class="fab fa-facebook-f"></i> Facebook
                                                </a>
                                                <a v-if="selectedTeacher.instagram" :href="selectedTeacher.instagram" target="_blank" class="btn btn-outline-danger rounded-pill d-flex align-items-center gap-2">
                                                    <i class="fab fa-instagram"></i> Instagram
                                                </a>
                                                <a v-if="selectedTeacher.twitter" :href="selectedTeacher.twitter" target="_blank" class="btn btn-outline-info rounded-pill d-flex align-items-center gap-2">
                                                    <i class="fab fa-twitter"></i> Twitter
                                                </a>
                                                <a v-if="selectedTeacher.linkedin" :href="selectedTeacher.linkedin" target="_blank" class="btn btn-outline-primary rounded-pill d-flex align-items-center gap-2">
                                                    <i class="fab fa-linkedin-in"></i> LinkedIn
                                                </a>
                                            </div>
                                        </div>
                                        <div class="col-12" v-else>
                                            <div class="text-center text-muted py-3">
                                                <i class="fas fa-link mb-2"></i>
                                                <p class="mb-0">Tidak ada media sosial terhubung</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div v-else class="modal-content modern-modal border-0 p-5 text-center">
                     <div class="spinner-border text-primary" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                </div>
            </div>
        </div>


    </AdminLayout>
</template>

<script setup>
import { ref, reactive, computed, watch, onMounted } from "vue";
import { Link, useForm, router, Head, usePage } from "@inertiajs/vue3";
import AdminLayout from "@/Layouts/AdminLayout.vue";
import Swal from 'sweetalert2';
import { showToast } from '@/Utils/swal';

// Import EasyDataTable
import VueEasyDataTable from "vue3-easy-data-table";
import "vue3-easy-data-table/dist/style.css";

const EasyDataTable = VueEasyDataTable;
const page = usePage();

// Props dengan default values
const props = defineProps({
    guru: {
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
});

// State
const showAddModal = ref(false);
const showDetailModal = ref(false);
const showBulkModal = ref(false);
const showBulkDeleteModal = ref(false);
const isEditing = ref(false);
const activeTab = ref('manual');
const detailTab = ref('kepegawaian'); // Tab state for Detail Modal
const loading = ref(false);
const deleting = ref(false);
const deletingBulk = ref(false);
const importing = ref(false);
const formTab = ref('identity'); // UI State for Form Tabs

// Filters untuk SERVER-SIDE
const filters = reactive({
    search: props.filters?.search || "",
    status_kepegawaian: props.filters?.status_kepegawaian || "",
    status_aktif: props.filters?.status_aktif || "",
    jenis_kelamin: props.filters?.jenis_kelamin || "",
});

// Teacher data untuk operasi
const selectedTeacher = ref(null);
const teacherToDelete = ref(null);
const selectedTeachers = ref([]);

// Bulk actions
const bulkAction = reactive({
    status_aktif: "",
    status_kepegawaian: "",
});

// Forms
const form = useForm({
    id: null,
    nip: "",
    nama: "",
    bidang_studi: "",
    jenis_kelamin: "",
    tempat_lahir: "",
    tanggal_lahir: "",
    alamat: "",
    email: "",
    no_telepon: "",
    status_kepegawaian: "",
    status_aktif: "Aktif",
    pendidikan_terakhir: "",
    tanggal_mulai_bekerja: "",
    jabatan: "",
    agama: "",
    foto: null,
    foto_preview: null,
    facebook: "",
    instagram: "",
    twitter: "",
    linkedin: "",
});

const bulkForm = useForm({
    ids: [],
    status_aktif: "",
    status_kepegawaian: "",
});



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

// Table Headers
const headers = [
    { text: "No", value: "no", sortable: false },
    { text: "", value: "select", sortable: false },
    { text: "NIP", value: "nip", sortable: true },
    { text: "Nama Guru", value: "nama", sortable: true },
    { text: "Bidang Studi", value: "bidang_studi", sortable: true },
    { text: "JK", value: "jenis_kelamin", sortable: true },
    {
        text: "Tanggal Lahir",
        value: "tanggal_lahir",
        sortable: true,
    },
    { text: "No. Telp", value: "no_telepon", sortable: true },
    { text: "Status Kepeg.", value: "status_kepegawaian", sortable: true },
    { text: "Status Aktif", value: "status_aktif", sortable: true },
    { text: "Aksi", value: "actions", sortable: false },
];

// Computed property untuk data tabel
const tableData = computed(() => {
    if (!props.guru?.data || !Array.isArray(props.guru.data)) {
        return [];
    }
    
    const page = props.guru.current_page || 1;
    const perPageVal = props.guru.per_page || 10;
    const start = (page - 1) * perPageVal;
    
    return props.guru.data.map((item, index) => ({
        ...item,
        no: start + index + 1
    }));
});

// Per page untuk pagination
const perPage = ref(props.guru?.per_page || 10);

const changePerPage = () => {
    router.get("/admin/guru", { ...filters, per_page: perPage.value }, {
        preserveState: true,
        preserveScroll: true,
        replace: true,
    });
};

// Computed untuk check apakah semua terpilih
const isAllSelected = computed(() => {
    if (tableData.value.length === 0) return false;
    return tableData.value.every(item => selectedTeachers.value.includes(item.id));
});

// Toggle select all
const toggleSelectAll = (event) => {
    if (event.target.checked) {
        selectedTeachers.value = tableData.value.map(item => item.id);
    } else {
        selectedTeachers.value = [];
    }
};

// Debounce untuk search
let searchTimeout = null;
const onFilterChange = () => {
    if (searchTimeout) clearTimeout(searchTimeout);

    searchTimeout = setTimeout(() => {
        // Reset ke halaman pertama ketika filter berubah
        filter();
    }, 500);
};

// SERVER-SIDE Filter Function
const filter = () => {
    router.get("/admin/guru", filters, {
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
    filter(); // Trigger server-side filtering
    selectedTeachers.value = []; // Reset selection
};

// Helper Functions
const getKepegawaianClass = (status) => {
    switch (status) {
        case "PNS":
            return "bg-primary";
        case "Honorer":
            return "bg-warning";
        case "Kontrak":
            return "bg-info";
        case "Tetap":
            return "bg-success";
        default:
            return "bg-secondary";
    }
};

const getAktifClass = (status) => {
    switch (status) {
        case "Aktif":
            return "bg-success";
        case "Non-Aktif":
            return "bg-danger";
        case "Cuti":
            return "bg-warning";
        case "Pensiun":
            return "bg-info";
        default:
            return "bg-secondary";
    }
};

const getAktifIcon = (status) => {
    switch (status) {
        case "Aktif":
            return "fas fa-check-circle";
        case "Non-Aktif":
            return "fas fa-times-circle";
        case "Cuti":
            return "fas fa-bed";
        case "Pensiun":
            return "fas fa-umbrella-beach";
        default:
            return "fas fa-question-circle";
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

const getInitials = (name) => {
    if (!name) return "??";
    const parts = name.split(" ");
    if (parts.length === 1) return parts[0].charAt(0).toUpperCase();
    return (
        parts[0].charAt(0) + parts[parts.length - 1].charAt(0)
    ).toUpperCase();
};

// Multi Select Methods
const selectAllTeachers = () => {
    selectedTeachers.value = props.guru.data.map((teacher) => teacher.id);
};

const deselectAllTeachers = () => {
    selectedTeachers.value = [];
};

const showBulkActions = () => {
    if (selectedTeachers.value.length === 0) {
        showToast("warning", "Peringatan!", "Pilih guru terlebih dahulu!");
        return;
    }
    showBulkModal.value = true;
};

const applyBulkActions = () => {
    if (!bulkAction.status_aktif && !bulkAction.status_kepegawaian) {
        showToast(
            "warning",
            "Peringatan!",
            "Pilih setidaknya satu aksi (status aktif atau kepegawaian)!"
        );
        return;
    }

    bulkForm.ids = selectedTeachers.value;
    bulkForm.status_aktif = bulkAction.status_aktif;
    bulkForm.status_kepegawaian = bulkAction.status_kepegawaian;

    bulkForm.post("/admin/guru/bulk-update", {
        preserveScroll: true,
        onSuccess: () => {
            showToast(
                "success",
                "Sukses!",
                `Berhasil mengupdate ${selectedTeachers.value.length} data guru`
            );
            showBulkModal.value = false;
            selectedTeachers.value = [];
            bulkAction.status_aktif = "";
            bulkAction.status_kepegawaian = "";
            bulkForm.reset();
        },
        onError: (errors) => {
            console.log("Bulk update errors:", errors);
            showToast("error", "Gagal!", "Terjadi kesalahan saat mengupdate data");
        },
    });
};

const confirmBulkDelete = () => {
    showBulkModal.value = false;
    showBulkDeleteModal.value = true;
};

const deleteBulkTeachers = () => {
    deletingBulk.value = true;

    router.post(
        "/admin/guru/bulk-destroy",
        {
            ids: selectedTeachers.value,
        },
        {
            preserveScroll: true,
            onSuccess: () => {
                showToast(
                    "success",
                    "Sukses!",
                    `Berhasil menghapus ${selectedTeachers.value.length} data guru`
                );
                deletingBulk.value = false;
                showBulkDeleteModal.value = false;
                selectedTeachers.value = [];
            },
            onError: (errors) => {
                deletingBulk.value = false;
                console.log("Bulk delete errors:", errors);
                showToast("error", "Gagal!", "Terjadi kesalahan saat menghapus data");
            },
        }
    );
};

const importForm = useForm({
    file: null,
});

const handleFileChange = (e) => {
    importForm.file = e.target.files[0];
};

const importGuru = () => {
    if (!importForm.file) {
        showToast("warning", "Peringatan!", "Silakan pilih file Excel terlebih dahulu");
        return;
    }

    importing.value = true;
    importForm.post("/admin/guru/import", {
        preserveScroll: true,
        onSuccess: (page) => {
            importing.value = false;
            showAddModal.value = false;
            importForm.reset();
            activeTab.value = 'manual';
            
            // Check import result from flash session if available
            // Note: success message is handled by flash watcher, 
            // but we can show detailed result if passed 
            if (page.props.flash.importResult) {
                const result = page.props.flash.importResult;
                 showToast(
                    "success",
                    "Import Selesai!", 
                    `Sukses: ${result.successCount}, Gagal: ${result.errorCount}`
                );
            } else {
                 showToast("success", "Sukses!", "Data guru berhasil diimport");
            }
        },
        onError: (errors) => {
            importing.value = false;
            console.log("Import errors:", errors);
            showToast("error", "Gagal!", "Terjadi kesalahan saat import data");
        },
    });
};

const downloadTemplate = () => {
    window.location.href = "/admin/guru/download-template";
};

// Import file ref
const importFile = ref(null);
const fileInputImport = ref(null);

// Import functions untuk UI baru
const triggerFileInput = () => {
    if (fileInputImport.value) {
        fileInputImport.value.click();
    }
};

const handleFileSelect = (event) => {
    const files = event.target.files;
    if (files.length > 0) {
        importFile.value = files[0];
    }
};

const handleFileDrop = (event) => {
    const files = event.dataTransfer.files;
    if (files.length > 0) {
        importFile.value = files[0];
    }
};

const formatFileSize = (bytes) => {
    if (bytes === 0) return '0 Bytes';
    const k = 1024;
    const sizes = ['Bytes', 'KB', 'MB', 'GB'];
    const i = Math.floor(Math.log(bytes) / Math.log(k));
    return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + ' ' + sizes[i];
};

const removeImportFile = () => {
    importFile.value = null;
    if (fileInputImport.value) {
        fileInputImport.value.value = '';
    }
};

const processImport = () => {
    if (!importFile.value) {
        showToast("warning", "Peringatan!", "Pilih file terlebih dahulu!");
        return;
    }
    
    importing.value = true;
    
    const formData = new FormData();
    formData.append('file', importFile.value);
    
    router.post('/admin/guru/import', formData, {
        forceFormData: true,
        preserveScroll: true,
        onSuccess: (page) => {
            importing.value = false;
            importFile.value = null;
            if (fileInputImport.value) {
                fileInputImport.value.value = '';
            }
            activeTab.value = 'manual';
            showAddModal.value = false;
            
            // Cek hasil import dari flash session
            const importResult = page.props.flash?.importResult;
            if (importResult) {
                const { successCount, errorCount, errors } = importResult;
                
                if (successCount > 0 && errorCount === 0) {
                    showToast("success", "Import Berhasil!", `${successCount} data guru berhasil diimport`);
                } else if (successCount > 0 && errorCount > 0) {
                    showToast("warning", "Import Selesai", `${successCount} berhasil, ${errorCount} gagal`);
                    if (errors && errors.length > 0) {
                        console.log("Detail error:", errors);
                    }
                } else if (successCount === 0 && errorCount > 0) {
                    showToast("error", "Import Gagal!", `Semua ${errorCount} data gagal diimport. Periksa format file.`);
                } else {
                    showToast("warning", "Tidak Ada Data", "Tidak ada data yang diimport");
                }
            } else if (page.props.flash?.success) {
                // Flash success message handled by watcher
            }
            
            // Reload data
            router.reload({ only: ['guru'] });
        },
        onError: (errors) => {
            importing.value = false;
            console.log("Import errors:", errors);
            
            // Tampilkan error message yang lebih spesifik
            if (errors.file) {
                showToast("error", "Gagal!", errors.file);
            } else {
                showToast("error", "Gagal!", "Format file tidak valid. Gunakan template yang disediakan.");
            }
        },
    });
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
};

// Teacher CRUD Operations
// Teacher CRUD Operations
const viewTeacherDetail = (teacher) => {
    selectedTeacher.value = { ...teacher }; // Clone object to ensure reactivity
    showDetailModal.value = true;
};

const editTeacher = (teacher) => {
    isEditing.value = true;
    form.id = teacher.id;
    form.nip = teacher.nip;
    form.nama = teacher.nama;
    form.jabatan = teacher.jabatan;
    form.bidang_studi = teacher.bidang_studi;
    form.jenis_kelamin = teacher.jenis_kelamin;
    form.tempat_lahir = teacher.tempat_lahir;
    form.tanggal_lahir = formatDateForInput(teacher.tanggal_lahir);
    form.alamat = teacher.alamat;
    form.email = teacher.email;
    form.email = teacher.email;
    form.no_telepon = teacher.no_telepon;
    form.agama = teacher.agama || "";
    form.status_kepegawaian = teacher.status_kepegawaian;
    form.status_aktif = teacher.status_aktif;
    form.pendidikan_terakhir = teacher.pendidikan_terakhir;
    form.tanggal_mulai_bekerja = formatDateForInput(teacher.tanggal_mulai_bekerja);
    form.facebook = teacher.facebook;
    form.instagram = teacher.instagram;
    form.twitter = teacher.twitter;
    form.linkedin = teacher.linkedin;
    
    // Set foto preview if exists
    if (teacher.foto) {
        form.foto_preview = `/storage/${teacher.foto}`;
    }

    showAddModal.value = true;
    showDetailModal.value = false;
    activeTab.value = 'manual';

    // Show existing photo preview if available
    form.foto_preview = teacher.foto ? `/storage/${teacher.foto}` : null;
    form.foto = null; // Reset file input
};

const confirmDelete = (teacher) => {
    Swal.fire({
        title: 'Hapus Data Guru?',
        html: `Anda yakin ingin menghapus data guru <br/><strong class="text-dark">"${teacher.nama}"</strong>?`,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: '<i class="fas fa-trash-alt me-1"></i> Ya, Hapus!',
        cancelButtonText: 'Batal',
        customClass: {
            popup: 'rounded-4 shadow-lg border-0',
            title: 'fs-5 fw-bold text-dark',
            htmlContainer: 'text-secondary',
            confirmButton: 'btn btn-danger px-4 py-2 rounded-3 shadow-sm',
            cancelButton: 'btn btn-secondary px-4 py-2 rounded-3 shadow-sm'
        },
        buttonsStyling: false
    }).then((result) => {
        if (result.isConfirmed) {
            router.delete(`/admin/guru/${teacher.id}`, {
                preserveScroll: true,
                onSuccess: () => {
                    // Toast handled by watcher
                },
                onError: (errors) => {
                    showToast('error', 'Gagal', 'Terjadi kesalahan saat menghapus data');
                }
            });
        }
    });
};

const handleFileUpload = (event) => {
    const file = event.target.files[0];
    if (file) {
        form.foto = file;
        // Create preview
        const reader = new FileReader();
        reader.onload = (e) => {
            form.foto_preview = e.target.result;
        };
        reader.readAsDataURL(file);
    }
};

const saveTeacher = () => {
    form.processing = true;

    if (isEditing.value) {
        // MANUAL FORMDATA CONSTRUCTION TO ENSURE FILE UPLOAD WORKS
        // This avoids issues with Inertia's transform wrapper
        const formData = new FormData();
        
        // Append all form fields
        formData.append('_method', 'PUT'); // Simulate PUT
        formData.append('id', form.id);
        formData.append('nip', form.nip || '');
        formData.append('nama', form.nama || '');
        formData.append('jabatan', form.jabatan || '');
        formData.append('bidang_studi', form.bidang_studi || '');
        formData.append('jenis_kelamin', form.jenis_kelamin || '');
        formData.append('tempat_lahir', form.tempat_lahir || '');
        formData.append('tanggal_lahir', form.tanggal_lahir || '');
        formData.append('agama', form.agama || '');
        formData.append('alamat', form.alamat || '');
        formData.append('email', form.email || '');
        formData.append('no_telepon', form.no_telepon || '');
        formData.append('status_kepegawaian', form.status_kepegawaian || '');
        formData.append('status_aktif', form.status_aktif || '');
        formData.append('pendidikan_terakhir', form.pendidikan_terakhir || '');
        formData.append('tanggal_mulai_bekerja', form.tanggal_mulai_bekerja || '');
        formData.append('facebook', form.facebook || '');
        formData.append('instagram', form.instagram || '');
        formData.append('twitter', form.twitter || '');
        formData.append('linkedin', form.linkedin || '');

        // Append file if exists
        if (form.foto) {
            formData.append('foto', form.foto);
        }

        router.post(`/admin/guru/${form.id}`, formData, {
            preserveScroll: true,
            forceFormData: true,
            onSuccess: () => {
                showToast("success", "Sukses!", "Data guru berhasil diperbarui");
                closeModal();
                form.processing = false;
            },
            onError: (errors) => {
                console.log("Update errors:", errors);
                form.errors = errors; // Map errors back to form
                // Generic error toast only, detailed errors are inline
                showToast("error", "Gagal!", "Gagal menyimpan data. Periksa inputan anda.");
                form.processing = false;
            },
        });
    } else {
        form.post("/admin/guru", {
            preserveScroll: true,
            onSuccess: () => {
                showToast("success", "Sukses!", "Data guru berhasil ditambahkan");
                closeModal();
                form.processing = false;
            },
            onError: (errors) => {
                console.log("Create errors:", errors);
                showToast("error", "Gagal!", "Terjadi kesalahan saat menambah data");
                form.processing = false;
            },
            forceFormData: true,
        });
    }
};

// Modal Operations
const closeModal = () => {
    showAddModal.value = false;
    isEditing.value = false;
    activeTab.value = 'manual';
    form.reset();
    form.clearErrors();
    importForm.reset();
};

// Watch untuk perubahan filters
watch(
    filters,
    () => {
        // Reset selected teachers ketika filter berubah
        selectedTeachers.value = [];
    },
    { deep: true }
);

// Watch untuk perubahan jumlah data yang dipilih
watch(
    selectedTeachers,
    (newValue) => {
        // Update counter atau logika lainnya
    },
    { deep: true }
);

// Handle data guru dari props
onMounted(() => {
    // Pastikan data selalu ada
    if (!props.guru.data || !Array.isArray(props.guru.data)) {
        console.warn("Data guru tidak valid atau tidak ada");
    }
});
</script>

<style scoped>
/* ============================================================
   DATA GURU - PREMIUM STYLES (Matching DataSiswa)
   ============================================================ */

/* Gradient Header */
.gradient-header {
    background: linear-gradient(135deg, #4f46e5 0%, #3b82f6 100%);
}

.header-icon-box {
    width: 48px;
    height: 48px;
    display: flex;
    align-items: center;
    justify-content: center;
}

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
.bidang-badge {
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

/* Status Badge */
.status-badge {
    padding: 0.35em 0.8em;
    font-size: 0.85em;
    font-weight: 600;
    border-radius: 50px;
    letter-spacing: 0.3px;
    display: inline-flex;
    align-items: center;
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

.btn-icon-view:hover {
    background-color: #3b82f6;
    box-shadow: 0 4px 12px rgba(59, 130, 246, 0.3);
}

.btn-icon-edit:hover {
    background-color: #f59e0b; 
    box-shadow: 0 4px 12px rgba(245, 158, 11, 0.3);
}

.btn-icon-delete:hover {
    background-color: #ef4444; 
    box-shadow: 0 4px 12px rgba(239, 68, 68, 0.3);
}

/* Filter Card */
.filter-card {
    border: none;
    border-radius: 12px;
    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
}

.input-group-modern {
    box-shadow: 0 1px 2px rgba(0, 0, 0, 0.05);
    border-radius: 8px;
    overflow: hidden;
}
.input-group-modern .input-group-text {
    background-color: #fff;
    border: 1px solid #e2e8f0;
    border-right: none;
    color: #94a3b8;
}
.input-group-modern .form-control {
    border: 1px solid #e2e8f0;
    border-left: none;
}
.input-group-modern .form-control:focus {
    box-shadow: none;
    border-color: #e2e8f0;
}

/* Modern Table Card */
.modern-table-card {
    border: none;
    border-radius: 12px;
    box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
    overflow: hidden;
}

/* Modern Modal */
.modern-modal {
    border: none;
    border-radius: 16px;
    overflow: hidden;
    box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
}
.modern-modal .modal-header {
    background: linear-gradient(135deg, #4f46e5 0%, #3b82f6 100%);
    padding: 1.5rem;
    border-bottom: none;
    color: white;
}
.modern-modal .modal-title {
    display: flex;
    align-items: center;
    gap: 1rem;
}
.modal-icon {
    width: 40px;
    height: 40px;
    background: rgba(255, 255, 255, 0.2);
    border-radius: 10px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.25rem;
}
.modal-subtitle {
    font-size: 0.875rem;
    opacity: 0.9;
    margin-bottom: 0;
    font-weight: 400;
}
.modern-modal .btn-close {
    filter: brightness(0) invert(1);
    opacity: 0.8;
}

/* Tabs */
.modal-tabs {
    border-bottom: 1px solid #e2e8f0;
}
.nav-tab {
    background: none;
    border: none;
    padding: 1rem 1.5rem;
    font-weight: 500;
    color: #64748b;
    border-bottom: 2px solid transparent;
    transition: all 0.2s;
}
.nav-tab:hover { color: #3b82f6; }
.nav-tab.active {
    color: #3b82f6;
    border-bottom-color: #3b82f6;
}

/* Form Styles */
.form-label {
    font-size: 0.875rem;
    font-weight: 600;
    color: #334155;
    margin-bottom: 0.5rem;
}
.required::after {
    content: "*";
    color: #dc2626;
    margin-left: 2px;
}
.section-title {
    color: #3b82f6;
    font-weight: 600;
    letter-spacing: 0.5px;
    text-transform: uppercase;
    font-size: 0.8rem;
}

/* Detail Modal Info Items */
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

.detail-header {
    background: linear-gradient(135deg, #4f46e5 0%, #3b82f6 100%);
    padding: 2rem;
    border-radius: 12px 12px 0 0;
    color: white;
    position: relative;
    text-align: center;
}

.detail-avatar-large {
    width: 80px;
    height: 80px;
    border-radius: 50%;
    border: 4px solid rgba(255,255,255,0.3);
    background: rgba(255,255,255,0.2);
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 2rem;
    font-weight: 700;
    color: white;
    overflow: hidden;
    margin: 0 auto 1rem;
}

.detail-avatar-large img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.info-card {
    background: white;
    border-radius: 1rem;
    padding: 1.5rem;
    box-shadow: 0 2px 12px rgba(0,0,0,0.03);
    height: 100%;
    border: 1px solid #f1f5f9;
}

/* Avatar Circle */
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

/* Upload Card */
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

/* Import Wizard Styles */
.import-container {
    padding: 1rem;
}
.upload-zone {
    border: 2px dashed #ddd;
    border-radius: 8px;
    padding: 3rem;
    text-align: center;
    cursor: pointer;
    transition: all 0.3s ease;
}
.upload-zone:hover {
    border-color: #3b82f6;
    background-color: #f8faff;
}

/* SweetAlert Toast Customization */
body.swal2-toast-shown .swal2-container.swal2-top-end, 
body.swal2-toast-shown .swal2-container.swal2-top-right {
    top: 1rem !important;
    right: 1rem !important;
}

.small-toast {
    font-size: 0.85rem !important;
    width: auto !important;
    min-width: 350px !important;
    max-width: 800px !important;
    padding: 0.75rem 1rem !important;
    white-space: nowrap !important;
    box-shadow: 0 4px 12px rgba(0,0,0,0.1) !important;
}

.small-toast-title {
    font-weight: 500 !important;
    margin: 0 !important;
    letter-spacing: 0.3px;
    color: #333;
}
</style>

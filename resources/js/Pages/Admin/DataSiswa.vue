<template>
    <AdminLayout>
        <Head title="Data Siswa" />

        <!-- Toast Notification Component -->
        <ToastNotification ref="toast" />

        <div class="content container-fluid">
            <!-- Modern Page Header -->
            <div class="page-header">
                <div class="row align-items-center">
                    <div class="col">
                        <h3 class="page-title">Data Siswa</h3>
                        <ul class="breadcrumb">
                            <li class="breadcrumb-item">
                                <Link href="/dashboard">Dashboard</Link>
                            </li>
                            <li class="breadcrumb-item active">Data Siswa</li>
                        </ul>
                    </div>
                    <div class="col-auto">
                         <button
                            @click="openManageClassModal"
                            class="btn btn-secondary me-2"
                        >
                            <i class="fas fa-cog me-1"></i> Kelola Kelas
                        </button>
                        <button
                            @click="showAddModal = true"
                            class="btn btn-primary"
                        >
                            <i class="fas fa-plus me-1"></i> Tambah Siswa
                        </button>
                    </div>
                </div>
            </div>

            <!-- Modern Filter Card -->
            <div class="card filter-card mb-4">
                <div class="card-body">
                    <form @submit.prevent="filter" class="row g-3">
                        <div class="col-lg-3 col-md-6">
                            <div class="input-group input-group-modern">
                                <span class="input-group-text">
                                    <i class="fas fa-search"></i>
                                </span>
                                <input
                                    type="text"
                                    class="form-control"
                                    placeholder="Cari NIS atau Nama..."
                                    v-model="filters.search"
                                    @input="onFilterChange"
                                />
                            </div>
                        </div>
                        <div class="col-lg-2 col-md-6">
                            <select
                                class="form-select"
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
                                class="form-select"
                                v-model="filters.status"
                                @change="filter"
                            >
                                <option value="">Semua Status</option>
                                <option value="Aktif">Aktif</option>
                                <option value="Non-Aktif">Non-Aktif</option>
                                <option value="Pindah">Pindah</option>
                                <option value="Lulus">Lulus</option>
                            </select>
                        </div>
                        <div class="col-lg-2 col-md-6">
                            <select
                                class="form-select"
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
                                    class="btn btn-outline-secondary w-50"
                                >
                                    <i class="fas fa-redo me-1"></i>Reset
                                </button>
                                <!-- Tombol Bulk Actions -->
                                <button
                                    type="button"
                                    @click="showBulkActions"
                                    class="btn btn-outline-primary w-50"
                                    :disabled="selectedStudents.length === 0"
                                >
                                    <i class="fas fa-cog me-1"></i>Aksi ({{
                                        selectedStudents.length
                                    }})
                                </button>
                            </div>
                        </div>
                    </form>
                    <!-- Info selected students -->
                    <div
                        v-if="selectedStudents.length > 0"
                        class="mt-3 p-2 bg-light rounded"
                    >
                        <div
                            class="d-flex justify-content-between align-items-center"
                        >
                            <small>
                                <i
                                    class="fas fa-check-circle text-success me-1"
                                ></i>
                                {{ selectedStudents.length }} siswa terpilih
                            </small>
                            <button
                                @click="deselectAllStudents"
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
                            <h5 class="card-title mb-0">Daftar Siswa</h5>
                            <p class="card-subtitle text-muted mb-0">
                                {{ siswa.total }} siswa ditemukan
                            </p>
                        </div>
                        <div class="d-flex align-items-center gap-2">
                            <button
                                @click="selectAllStudents"
                                class="btn btn-sm btn-outline-secondary"
                            >
                                <i class="fas fa-check-double me-1"></i>Pilih
                                Semua
                            </button>
                            <button
                                @click="deselectAllStudents"
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
                                    <th width="60">No</th>
                                    <th>NIS</th>
                                    <th>Nama</th>
                                    <th>Kelas</th>
                                    <th>L/P</th>
                                    <th>Tgl Lahir</th>
                                    <th>No. Telp</th>
                                    <th>Status</th>
                                    <th width="140">Aksi</th>
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
                                    <td class="fw-medium">{{ item.nis }}</td>
                                    <td class="fw-semibold">{{ item.nama }}</td>
                                    <td class="text-center">
                                        <span class="badge kelas-badge">{{ item.kelas }}</span>
                                    </td>
                                    <td class="text-center">
                                        <div class="gender-badge" :class="item.jenis_kelamin === 'L' ? 'male' : 'female'">
                                            <i :class="item.jenis_kelamin === 'L' ? 'fas fa-mars' : 'fas fa-venus'"></i>
                                            {{ item.jenis_kelamin }}
                                        </div>
                                    </td>
                                    <td class="text-center">{{ formatShortDate(item.tanggal_lahir) }}</td>
                                    <td class="text-center">
                                        <span class="phone-number">{{ item.no_telepon || '-' }}</span>
                                    </td>
                                    <td class="text-center">
                                        <span class="status-badge" :class="getStatusClass(item.status)">
                                            <i :class="getStatusIcon(item.status) + ' me-1'"></i>
                                            {{ item.status }}
                                        </span>
                                    </td>
                                    <td>
                                        <div class="action-buttons">
                                            <button class="btn btn-action btn-view" @click="viewStudentDetail(item)" title="Detail">
                                                <i class="fas fa-eye"></i>
                                            </button>
                                            <button class="btn btn-action btn-edit" @click="editStudent(item)" title="Edit">
                                                <i class="fas fa-edit"></i>
                                            </button>
                                            <button class="btn btn-action btn-delete" @click.stop.prevent="confirmDelete(item)" title="Hapus" type="button">
                                                <i class="fas fa-trash"></i>
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
                    <div class="modal-header">
                        <div class="modal-title">
                            <i
                                class="modal-icon"
                                :class="
                                    isEditing
                                        ? 'fas fa-edit'
                                        : 'fas fa-user-plus'
                                "
                            ></i>
                            <div>
                                <h5>
                                    {{
                                        isEditing
                                            ? "Edit Data Siswa"
                                            : "Tambah Data Siswa"
                                    }}
                                </h5>
                                <p class="modal-subtitle text-muted">
                                    {{
                                        isEditing
                                            ? "Perbarui data siswa yang sudah ada"
                                            : "Tambahkan data siswa baru ke sistem"
                                    }}
                                </p>
                            </div>
                        </div>
                        <button
                            type="button"
                            class="btn-close"
                            @click="closeModal"
                        ></button>
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

                            <!-- Manual Input Form -->
                            <div v-if="activeTab === 'manual'">
                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="form-label required"
                                                >NIS</label
                                            >
                                            <div class="input-group">
                                                <span class="input-group-text">
                                                    <i
                                                        class="fas fa-id-card"
                                                    ></i>
                                                </span>
                                                <input
                                                    type="text"
                                                    class="form-control"
                                                    v-model="form.nis"
                                                    :class="{
                                                        'is-invalid':
                                                            form.errors.nis,
                                                    }"
                                                    placeholder="Masukkan NIS"
                                                    required
                                                />
                                                <div
                                                    v-if="form.errors.nis"
                                                    class="invalid-feedback"
                                                >
                                                    {{ form.errors.nis }}
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="form-label required"
                                                >Nama Lengkap</label
                                            >
                                            <div class="input-group">
                                                <span class="input-group-text">
                                                    <i class="fas fa-user"></i>
                                                </span>
                                                <input
                                                    type="text"
                                                    class="form-control"
                                                    v-model="form.nama"
                                                    :class="{
                                                        'is-invalid':
                                                            form.errors.nama,
                                                    }"
                                                    placeholder="Masukkan nama lengkap"
                                                    required
                                                />
                                                <div
                                                    v-if="form.errors.nama"
                                                    class="invalid-feedback"
                                                >
                                                    {{ form.errors.nama }}
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="form-label required"
                                                >Kelas</label
                                            >
                                            <div class="input-group">
                                                <span class="input-group-text">
                                                    <i
                                                        class="fas fa-graduation-cap"
                                                    ></i>
                                                </span>
                                                <select
                                                    class="form-select"
                                                    v-model="form.kelas"
                                                    :class="{
                                                        'is-invalid':
                                                            form.errors.kelas,
                                                    }"
                                                    required
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
                                                <div
                                                    v-if="form.errors.kelas"
                                                    class="invalid-feedback"
                                                >
                                                    {{ form.errors.kelas }}
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="form-label required"
                                                >Jenis Kelamin</label
                                            >
                                            <div class="input-group">
                                                <span class="input-group-text">
                                                    <i
                                                        class="fas fa-venus-mars"
                                                    ></i>
                                                </span>
                                                <select
                                                    class="form-select"
                                                    v-model="form.jenis_kelamin"
                                                    :class="{
                                                        'is-invalid':
                                                            form.errors
                                                                .jenis_kelamin,
                                                    }"
                                                    required
                                                >
                                                    <option value="">
                                                        Pilih Jenis Kelamin
                                                    </option>
                                                    <option value="L">
                                                        Laki-laki
                                                    </option>
                                                    <option value="P">
                                                        Perempuan
                                                    </option>
                                                </select>
                                                <div
                                                    v-if="
                                                        form.errors
                                                            .jenis_kelamin
                                                    "
                                                    class="invalid-feedback"
                                                >
                                                    {{
                                                        form.errors
                                                            .jenis_kelamin
                                                    }}
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="form-label"
                                                >Tempat Lahir</label
                                            >
                                            <div class="input-group">
                                                <span class="input-group-text">
                                                    <i
                                                        class="fas fa-map-marker-alt"
                                                    ></i>
                                                </span>
                                                <input
                                                    type="text"
                                                    class="form-control"
                                                    v-model="form.tempat_lahir"
                                                    placeholder="Kota kelahiran"
                                                />
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="form-label"
                                                >Tanggal Lahir</label
                                            >
                                            <div class="input-group">
                                                <span class="input-group-text">
                                                    <i
                                                        class="fas fa-calendar"
                                                    ></i>
                                                </span>
                                                <input
                                                    type="date"
                                                    class="form-control"
                                                    v-model="form.tanggal_lahir"
                                                />
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-12">
                                        <div class="form-group">
                                            <label class="form-label"
                                                >Alamat</label
                                            >
                                            <div class="input-group">
                                                <span class="input-group-text">
                                                    <i class="fas fa-home"></i>
                                                </span>
                                                <textarea
                                                    class="form-control"
                                                    rows="2"
                                                    v-model="form.alamat"
                                                    placeholder="Alamat lengkap"
                                                ></textarea>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="form-label"
                                                >Nama Ayah</label
                                            >
                                            <input
                                                type="text"
                                                class="form-control"
                                                v-model="form.nama_ayah"
                                                placeholder="Nama ayah"
                                            />
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="form-label"
                                                >Nama Ibu</label
                                            >
                                            <input
                                                type="text"
                                                class="form-control"
                                                v-model="form.nama_ibu"
                                                placeholder="Nama ibu"
                                            />
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="form-label"
                                                >No. Telepon Orang Tua</label
                                            >
                                            <div class="input-group">
                                                <span class="input-group-text">
                                                    <i class="fas fa-phone"></i>
                                                </span>
                                                <input
                                                    type="text"
                                                    class="form-control"
                                                    v-model="form.no_telepon"
                                                    placeholder="08xxxxxxxxxx"
                                                />
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="form-label"
                                                >Status</label
                                            >
                                            <select
                                                class="form-select"
                                                v-model="form.status"
                                            >
                                                <option value="Aktif">
                                                    Aktif
                                                </option>
                                                <option value="Non-Aktif">
                                                    Non-Aktif
                                                </option>
                                                <option value="Pindah">
                                                    Pindah
                                                </option>
                                                <option value="Lulus">
                                                    Lulus
                                                </option>
                                            </select>
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
                                            ref="fileInput"
                                            @change="handleFileSelect"
                                            accept=".xlsx,.xls,.csv"
                                            hidden
                                        />

                                        <div class="upload-content text-center">
                                            <i
                                                class="fas fa-cloud-upload-alt fa-2x text-primary mb-3"
                                            ></i>
                                            <h5>
                                                Klik atau drag file Excel ke
                                                sini
                                            </h5>
                                            <p class="text-muted mb-3">
                                                Format: .xlsx, .xls, .csv (Maks.
                                                5MB)
                                            </p>
                                            <button
                                                type="button"
                                                class="btn btn-outline-primary"
                                            >
                                                <i
                                                    class="fas fa-folder-open me-2"
                                                ></i
                                                >Pilih File
                                            </button>
                                        </div>
                                    </div>

                                    <div v-if="importFile" class="mt-4">
                                        <div class="file-preview">
                                            <div class="file-info">
                                                <i
                                                    class="fas fa-file-excel text-success me-3"
                                                ></i>
                                                <div class="flex-grow-1">
                                                    <h6 class="mb-1">
                                                        {{ importFile.name }}
                                                    </h6>
                                                    <p class="text-muted mb-0">
                                                        {{
                                                            formatFileSize(
                                                                importFile.size
                                                            )
                                                        }}
                                                    </p>
                                                </div>
                                                <button
                                                    type="button"
                                                    @click="removeImportFile"
                                                    class="btn btn-link text-danger"
                                                >
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
                                                <i
                                                    class="fas fa-upload me-2"
                                                ></i>
                                                {{
                                                    importing
                                                        ? "Mengimport..."
                                                        : "Import Data"
                                                }}
                                            </button>
                                            <a
                                                href="/admin/siswa/download-template"
                                                class="btn btn-outline-secondary ms-2"
                                                >Template
                                            </a>
                                        </div>
                                    </div>

                                    <div class="import-guide mt-4">
                                        <div class="card">
                                            <div class="card-body">
                                                <h6>
                                                    <i
                                                        class="fas fa-info-circle me-2"
                                                    ></i
                                                    >Petunjuk Import
                                                </h6>
                                                <ul class="mb-0">
                                                    <li>
                                                        Format file harus sesuai
                                                        template
                                                    </li>
                                                    <li>
                                                        Kolom wajib: NIS, Nama,
                                                        Kelas, Jenis Kelamin
                                                        (L/P)
                                                    </li>
                                                    <li>
                                                        Maksimal 1000 data per
                                                        import
                                                    </li>
                                                </ul>
                                            </div>
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
                <div class="modal-content modern-modal">
                    <div class="modal-header">
                        <div class="modal-title">
                            <i class="modal-icon fas fa-user-graduate"></i>
                            <div>
                                <h5>Detail Data Siswa</h5>
                                <p class="modal-subtitle text-muted">
                                    Informasi lengkap data siswa
                                </p>
                            </div>
                        </div>
                        <button
                            type="button"
                            class="btn-close"
                            @click="showDetailModal = false"
                        ></button>
                    </div>
                    <div class="modal-body">
                        <div v-if="selectedStudent" class="student-detail">
                            <!-- Student Header -->
                            <div class="student-header mb-4">
                                <div class="d-flex align-items-center">
                                    <div class="student-avatar me-3">
                                        <div class="avatar-circle">
                                            {{
                                                getInitials(
                                                    selectedStudent.nama
                                                )
                                            }}
                                        </div>
                                    </div>
                                    <div class="student-info">
                                        <h4 class="mb-1">
                                            {{ selectedStudent.nama }}
                                        </h4>
                                        <div class="d-flex flex-wrap gap-2">
                                            <span class="badge bg-primary">{{
                                                selectedStudent.nis
                                            }}</span>
                                            <span
                                                class="badge"
                                                :class="
                                                    getStatusClass(
                                                        selectedStudent.status
                                                    )
                                                "
                                            >
                                                {{ selectedStudent.status }}
                                            </span>
                                            <span
                                                class="badge kelas-badge-lg"
                                                >{{
                                                    selectedStudent.kelas
                                                }}</span
                                            >
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Student Information Grid -->
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
                                                            selectedStudent.jenis_kelamin ===
                                                            'L'
                                                                ? 'male'
                                                                : 'female'
                                                        "
                                                    >
                                                        {{
                                                            selectedStudent.jenis_kelamin ===
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
                                                        selectedStudent.tempat_lahir ||
                                                        "-"
                                                    }},
                                                    {{
                                                        formatShortDate(
                                                            selectedStudent.tanggal_lahir
                                                        )
                                                    }}
                                                </span>
                                            </div>
                                            <div class="info-item">
                                                <span class="info-label"
                                                    >Alamat</span
                                                >
                                                <span class="info-value">{{
                                                    selectedStudent.alamat ||
                                                    "-"
                                                }}</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="info-card">
                                        <div class="info-card-header">
                                            <i class="fas fa-users me-2"></i>
                                            <span>Data Orang Tua</span>
                                        </div>
                                        <div class="info-card-body">
                                            <div class="info-item">
                                                <span class="info-label"
                                                    >Nama Ayah</span
                                                >
                                                <span class="info-value">{{
                                                    selectedStudent.nama_ayah ||
                                                    "-"
                                                }}</span>
                                            </div>
                                            <div class="info-item">
                                                <span class="info-label"
                                                    >Nama Ibu</span
                                                >
                                                <span class="info-value">{{
                                                    selectedStudent.nama_ibu ||
                                                    "-"
                                                }}</span>
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
                                                        selectedStudent.no_telepon ||
                                                        "-"
                                                    }}
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-12">
                                    <div class="info-card">
                                        <div class="info-card-header">
                                            <i
                                                class="fas fa-info-circle me-2"
                                            ></i>
                                            <span>Informasi Tambahan</span>
                                        </div>
                                        <div class="info-card-body">
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="info-item">
                                                        <span class="info-label"
                                                            >Terdaftar</span
                                                        >
                                                        <span
                                                            class="info-value"
                                                            >{{
                                                                formatShortDate(
                                                                    selectedStudent.created_at
                                                                )
                                                            }}</span
                                                        >
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="info-item">
                                                        <span class="info-label"
                                                            >Status</span
                                                        >
                                                        <span
                                                            class="info-value"
                                                        >
                                                            <span
                                                                class="status-badge"
                                                                :class="
                                                                    getStatusClass(
                                                                        selectedStudent.status
                                                                    )
                                                                "
                                                            >
                                                                {{
                                                                    selectedStudent.status
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
                            @click="editStudent(selectedStudent)"
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
                            <strong>{{ studentToDelete?.nama }}</strong> akan
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
                            @click.prevent="deleteStudent"
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
        <!-- Manage Classes Modal -->
        <div
            v-if="showManageClassModal"
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
                            <i class="modal-icon fas fa-graduation-cap"></i>
                            <div>
                                <h5>Kelola Data Kelas</h5>
                                <p class="modal-subtitle text-muted">
                                    Tambah, edit, atau hapus kelas
                                </p>
                            </div>
                        </div>
                        <button
                            type="button"
                            class="btn-close"
                            @click="closeManageClassModal"
                        ></button>
                    </div>
                    <div class="modal-body">
                        <!-- Add/Edit Form -->
                        <div class="card mb-3 bg-light border-0">
                            <div class="card-body">
                                <form @submit.prevent="saveKelas" class="d-flex gap-2">
                                    <input
                                        type="text"
                                        class="form-control"
                                        placeholder="Nama Kelas Baru..."
                                        v-model="classForm.nama"
                                        required
                                    />
                                    <button
                                        type="submit"
                                        class="btn btn-primary"
                                        :disabled="classForm.processing"
                                    >
                                        <i
                                            class="fas"
                                            :class="classForm.processing ? 'fa-spinner fa-spin' : (editingClassId ? 'fa-save' : 'fa-plus')"
                                        ></i>
                                        {{ editingClassId ? 'Update' : 'Tambah' }}
                                    </button>
                                     <button
                                        v-if="editingClassId"
                                        type="button"
                                        class="btn btn-secondary"
                                        @click="cancelEditClass"
                                    >
                                        Batal
                                    </button>
                                </form>
                            </div>
                        </div>

                        <!-- Class List -->
                        <div class="table-responsive" style="max-height: 400px; overflow-y: auto;">
                            <table class="table table-hover mb-0">
                                <thead class="table-light sticky-top">
                                    <tr>
                                        <th>Nama Kelas</th>
                                        <th class="text-end">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="kelas in manageKelasList" :key="kelas.id">
                                        <td>{{ kelas.nama }}</td>
                                        <td class="text-end">
                                            <button
                                                class="btn btn-sm btn-outline-warning me-1"
                                                @click="editClass(kelas)"
                                                title="Edit"
                                            >
                                                <i class="fas fa-edit"></i>
                                            </button>
                                            <button
                                                class="btn btn-sm btn-outline-danger"
                                                @click="deleteClass(kelas)"
                                                title="Hapus"
                                            >
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </td>
                                    </tr>
                                    <tr v-if="manageKelasList.length === 0">
                                        <td colspan="2" class="text-center text-muted">
                                            Belum ada data kelas.
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="modal-footer">
                         <button
                            type="button"
                            class="btn btn-light"
                            @click="closeManageClassModal"
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
import { ref, reactive, computed, watch, onMounted } from "vue";
import { Link, useForm, router, Head, usePage } from "@inertiajs/vue3";
import AdminLayout from "@/Layouts/AdminLayout.vue";
import ToastNotification from "@/Components/ToastNotification.vue";
import axios from "axios";

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
const showConfirmModal = ref(false);
const showBulkModal = ref(false);
const showBulkDeleteModal = ref(false);
const isEditing = ref(false);
const loading = ref(false);
const deleting = ref(false);
const deletingBulk = ref(false);
const importing = ref(false);
const activeTab = ref("manual");

// Filters untuk SERVER-SIDE
const filters = reactive({
    search: props.filters?.search || "",
    kelas: props.filters?.kelas || "",
    status: props.filters?.status || "",
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
const studentToDelete = ref(null);
const importFile = ref(null);
const selectedStudents = ref([]);

// Bulk actions
const bulkAction = reactive({
    status: "",
    kelas: "",
});

// Forms
const form = useForm({
    id: null,
    nis: "",
    nama: "",
    no_telepon: "",
    kelas: "",
    jenis_kelamin: "",
    tempat_lahir: "",
    tanggal_lahir: "",
    alamat: "",
    nama_ayah: "",
    nama_ibu: "",
    pekerjaan_orang_tua: "",
    status: "Aktif",
});

const bulkForm = useForm({
    ids: [],
    status: "",
    kelas: "",
});

// Toast Notification Ref
const toast = ref(null);

// Helper untuk show toast
const showSuccess = (title, message = "") => {
    if (toast.value) {
        toast.value.showSuccessToast(title, message);
    }
};

const showError = (title, message = "") => {
    if (toast.value) {
        toast.value.showErrorToast(title, message);
    }
};

const showWarning = (title, message = "") => {
    if (toast.value) {
        toast.value.showWarningToast(title, message);
    }
};

// Watch for flash messages from Inertia
watch(
    () => page.props.flash,
    (flash) => {
        if (flash?.success) {
            showSuccess("Sukses!", flash.success);
        }
        if (flash?.error) {
            showError("Error!", flash.error);
        }
        if (flash?.warning) {
            showWarning("Peringatan!", flash.warning);
        }
        if (flash?.info) {
            showSuccess("Info!", flash.info);
        }
    },
    { deep: true, immediate: true }
);

// Table Headers
const headers = [
    { text: "No", value: "no", sortable: false},
    { text: "", value: "select", sortable: false  },
    { text: "NIS", value: "nis", sortable: true},
    { text: "Nama Siswa", value: "nama", sortable: true },
    { text: "Kelas", value: "kelas", sortable: true },
    { text: "JK", value: "jenis_kelamin", sortable: true },
    {
        text: "Tanggal Lahir",
        value: "tanggal_lahir",
        sortable: true
        
    },
    { text: "No. Telp", value: "no_telepon", sortable: true },
    { text: "Status", value: "status", sortable: true },
    { text: "Aksi", value: "actions", sortable: false },
];

// Computed property untuk data tabel - FIXED
const tableData = computed(() => {
    if (!props.siswa?.data || !Array.isArray(props.siswa.data)) {
        return [];
    }
    
    const page = props.siswa.current_page || 1;
    const perPage = props.siswa.per_page || 10;
    const start = (page - 1) * perPage;
    
    return props.siswa.data.map((item, index) => ({
        ...item,
        no: start + index + 1
    }));
});

// Computed untuk check apakah semua terpilih
const isAllSelected = computed(() => {
    if (tableData.value.length === 0) return false;
    return tableData.value.every(item => selectedStudents.value.includes(item.id));
});

// Toggle select all
const toggleSelectAll = (event) => {
    if (event.target.checked) {
        selectedStudents.value = tableData.value.map(item => item.id);
    } else {
        selectedStudents.value = [];
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
    filter(); // Trigger server-side filtering
    selectedStudents.value = []; // Reset selection
};

// Helper Functions
const getStatusClass = (status) => {
    switch (status) {
        case "Aktif":
            return "bg-success";
        case "Non-Aktif":
            return "bg-danger";
        case "Pindah":
            return "bg-warning";
        case "Lulus":
            return "bg-info";
        default:
            return "bg-secondary";
    }
};

const getStatusIcon = (status) => {
    switch (status) {
        case "Aktif":
            return "fas fa-check-circle";
        case "Non-Aktif":
            return "fas fa-times-circle";
        case "Pindah":
            return "fas fa-exchange-alt";
        case "Lulus":
            return "fas fa-graduation-cap";
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
    return (
        parts[0].charAt(0) + parts[parts.length - 1].charAt(0)
    ).toUpperCase();
};

// Multi Select Methods
const selectAllStudents = () => {
    selectedStudents.value = props.siswa.data.map((student) => student.id);
};

const deselectAllStudents = () => {
    selectedStudents.value = [];
};

const showBulkActions = () => {
    if (selectedStudents.value.length === 0) {
        showWarning("Peringatan!", "Pilih siswa terlebih dahulu!");
        return;
    }
    showBulkModal.value = true;
};

const applyBulkActions = () => {
    if (!bulkAction.status && !bulkAction.kelas) {
        showWarning(
            "Peringatan!",
            "Pilih setidaknya satu aksi (status atau kelas)!"
        );
        return;
    }

    bulkForm.ids = selectedStudents.value;
    bulkForm.status = bulkAction.status;
    bulkForm.kelas = bulkAction.kelas;

    bulkForm.post("/admin/siswa/bulk-update", {
        preserveScroll: true,
        onSuccess: () => {
            showSuccess(
                "Sukses!",
                `Berhasil mengupdate ${selectedStudents.value.length} data siswa`
            );
            showBulkModal.value = false;
            selectedStudents.value = [];
            bulkAction.status = "";
            bulkAction.kelas = "";
            bulkForm.reset();
        },
        onError: (errors) => {
            console.log("Bulk update errors:", errors);
            showError("Gagal!", "Terjadi kesalahan saat mengupdate data");
        },
    });
};

const confirmBulkDelete = () => {
    showBulkModal.value = false;
    showBulkDeleteModal.value = true;
};

const deleteBulkStudents = () => {
    deletingBulk.value = true;

    router.post(
        "/admin/siswa/bulk-destroy",
        {
            ids: selectedStudents.value,
        },
        {
            preserveScroll: true,
            onSuccess: () => {
                showSuccess(
                    "Sukses!",
                    `Berhasil menghapus ${selectedStudents.value.length} data siswa`
                );
                deletingBulk.value = false;
                showBulkDeleteModal.value = false;
                selectedStudents.value = [];
            },
            onError: (errors) => {
                deletingBulk.value = false;
                console.log("Bulk delete errors:", errors);
                showError("Gagal!", "Terjadi kesalahan saat menghapus data");
            },
        }
    );
};

// Student CRUD Operations
const viewStudentDetail = (student) => {
    selectedStudent.value = student;
    showDetailModal.value = true;
};

const editStudent = (student) => {
    isEditing.value = true;
    form.id = student.id;
    form.nis = student.nis;
    form.nama = student.nama;
    form.no_telepon = student.no_telepon;
    form.kelas = student.kelas;
    form.jenis_kelamin = student.jenis_kelamin;
    form.tempat_lahir = student.tempat_lahir;
    form.tanggal_lahir = student.tanggal_lahir;
    form.alamat = student.alamat;
    form.nama_ayah = student.nama_ayah;
    form.nama_ibu = student.nama_ibu;
    form.pekerjaan_orang_tua = student.pekerjaan_orang_tua;
    form.status = student.status;

    showAddModal.value = true;
    showDetailModal.value = false;
    activeTab.value = "manual";
};

const confirmDelete = (student) => {
    studentToDelete.value = student;
    showConfirmModal.value = true;
};
const deleteStudent = () => {
    if (!studentToDelete.value || deleting.value) return;

    deleting.value = true;

    form.delete(`/admin/siswa/${studentToDelete.value.id}`, {
        preserveScroll: true,
        onSuccess: () => {
            showSuccess(
                "Sukses!",
                `Berhasil menghapus data siswa: ${studentToDelete.value.nama}`
            );
            showConfirmModal.value = false;
            studentToDelete.value = null;
        },
        onError: (errors) => {
            console.log("Delete errors:", errors);
            showError("Gagal!", "Terjadi kesalahan saat menghapus data");
        },
        onFinish: () => {
            deleting.value = false;
        },
    });
};


const saveStudent = () => {
    if (!form.nis || !form.nama || !form.kelas || !form.jenis_kelamin) {
        showWarning("Peringatan!", "Harap isi semua field yang wajib diisi!");
        return;
    }

    if (isEditing.value) {
        form.put(`/admin/siswa/${form.id}`, {
            preserveScroll: true,
            onSuccess: () => {
                showSuccess("Sukses!", "Data siswa berhasil diperbarui");
                closeModal();
            },
            onError: (errors) => {
                console.log("Update errors:", errors);
                showError("Gagal!", "Terjadi kesalahan saat menyimpan data");
            },
        });
    } else {
        form.post("/admin/siswa", {
            preserveScroll: true,
            onSuccess: () => {
                showSuccess("Sukses!", "Data siswa berhasil ditambahkan");
                closeModal();
            },
            onError: (errors) => {
                console.log("Create errors:", errors);
                showError("Gagal!", "Terjadi kesalahan saat menambah data");
            },
        });
    }
};

// Import Operations
const triggerFileInput = () => {
    document.querySelector('input[type="file"]').click();
};

const handleFileSelect = (event) => {
    const file = event.target.files[0];
    if (file) {
        if (file.size > 5 * 1024 * 1024) {
            showError("Error!", "Ukuran file terlalu besar! Maksimal 5MB.");
            return;
        }

        // Validasi ekstensi file
        const validExtensions = [".xlsx", ".xls", ".csv"];
        const fileExtension = "." + file.name.split(".").pop().toLowerCase();

        if (!validExtensions.includes(fileExtension)) {
            showError(
                "Error!",
                "Format file tidak didukung! Hanya file Excel (.xlsx, .xls) atau CSV yang diperbolehkan."
            );
            return;
        }

        importFile.value = file;
    }
};

// Manage Class State
const showManageClassModal = ref(false);
const manageKelasList = ref([]);
const editingClassId = ref(null);
const classForm = useForm({
    nama: ''
});

const openManageClassModal = async () => {
    showManageClassModal.value = true;
    await fetchClassList();
};

const closeManageClassModal = () => {
    showManageClassModal.value = false;
    cancelEditClass();
};

const fetchClassList = async () => {
    try {
        const response = await axios.get('/admin/kelas');
        manageKelasList.value = response.data;
    } catch (error) {
        console.error("Error fetching classes:", error);
    }
};

const saveKelas = () => {
    if (editingClassId.value) {
        classForm.put(`/admin/kelas/${editingClassId.value}`, {
            onSuccess: () => {
                showSuccess("Sukses!", "Kelas berhasil diupdate");
                fetchClassList();
                cancelEditClass();
                // Reload main page to update dropdowns if necessary (or update locally if we had a proper store)
                router.reload({ only: ['kelasList'] }); 
            },
            onError: (err) => {
                 // Check if it's a validation error (e.g. unique name)
                 if(err.nama){
                    showError("Gagal!", err.nama);
                 } else {
                    showError("Gagal!", "Gagal mengupdate kelas");
                 }
            }
        });
    } else {
        classForm.post('/admin/kelas', {
            onSuccess: () => {
                showSuccess("Sukses!", "Kelas berhasil ditambahkan");
                fetchClassList();
                classForm.reset();
                router.reload({ only: ['kelasList'] });
            },
            onError: (err) => {
                 if(err.nama){
                    showError("Gagal!", err.nama);
                 } else {
                    showError("Gagal!", "Gagal menambahkan kelas");
                 }
            }
        });
    }
};

const editClass = (kelas) => {
    editingClassId.value = kelas.id;
    classForm.nama = kelas.nama;
};

const cancelEditClass = () => {
    editingClassId.value = null;
    classForm.reset();
    classForm.clearErrors();
};

const deleteClass = (kelas) => {
    Swal.fire({
        title: 'Hapus Kelas?',
        text: `Anda yakin ingin menghapus kelas "${kelas.nama}"?`,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Ya, Hapus!',
        cancelButtonText: 'Batal'
    }).then((result) => {
        if (result.isConfirmed) {
            router.delete(`/admin/kelas/${kelas.id}`, {
                onSuccess: () => {
                    showSuccess("Sukses!", "Kelas berhasil dihapus");
                    fetchClassList();
                    router.reload({ only: ['kelasList'] });
                },
                onError: (errors) => {
                    // Errors are usually in page.props.errors or flash, handled by our global watcher? 
                    // Actually delete returns back with errors bag if we used ->withErrors logic in Controller.
                    // But router delete error handling in catch/onError is tricky with redirects.
                    // Since we used redirect()->back()->withErrors() in controller, inertia should pick it up in flash or props.errors
                    // Let's rely on the global watcher for flash messages if implemented there, 
                    // but we might need to check usePage().props.errors.
                    
                    // The global watcher handles flash.error. The controller returned withErrors(['message' => ...])
                    // This puts it in page.props.errors.message usually or similar.
                    if (page.props.errors?.message) {
                        showError("Gagal!", page.props.errors.message);
                    } else {
                         showError("Gagal!", "Gagal menghapus kelas (sedang digunakan?)");
                    }
                }
            });
        }
    });
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

const processImport = () => {
    if (!importFile.value) {
        showWarning("Peringatan!", "Pilih file terlebih dahulu!");
        return;
    }

    importing.value = true;

    const formData = new FormData();
    formData.append("file", importFile.value);

    router.post("/admin/siswa/import", formData, {
        preserveScroll: true,
        onSuccess: (response) => {
            importing.value = false;
            importFile.value = null;
            showAddModal.value = false;

            // Cek jika ada data sukses dan error dari response
            if (response.props?.importResult) {
                const { successCount, errorCount, errors } =
                    response.props.importResult;

                if (errorCount > 0) {
                    showWarning(
                        "Import Selesai dengan Peringatan",
                        `Berhasil mengimport ${successCount} data, ${errorCount} data gagal`
                    );

                    // Tampilkan detail error jika ada
                    if (errors && errors.length > 0) {
                        console.log("Import errors:", errors);
                    }
                } else {
                    showSuccess(
                        "Import Berhasil!",
                        `Berhasil mengimport ${successCount} data siswa`
                    );
                }
            } else {
                showSuccess("Sukses!", "Data berhasil diimport");
            }

            router.reload({ only: ["siswa"] });
        },
        onError: (errors) => {
            importing.value = false;
            console.log("Import errors:", errors);
            showError("Gagal!", "Terjadi kesalahan saat mengimport data");
        },
    });
};

// Modal Operations
const closeModal = () => {
    showAddModal.value = false;
    isEditing.value = false;
    activeTab.value = "manual";
    importFile.value = null;
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
/* All styles have been moved to admin-theme.css */
</style>

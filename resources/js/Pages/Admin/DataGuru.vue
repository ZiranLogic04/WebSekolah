<template>
    <AdminLayout>
        <Head title="Data Guru" />

        <!-- Toast Notification Component -->
        <ToastNotification ref="toast" />

        <div class="content container-fluid">
            <!-- Modern Page Header -->
            <div class="page-header">
                <div class="row align-items-center">
                    <div class="col">
                        <h3 class="page-title">Data Guru</h3>
                        <ul class="breadcrumb">
                            <li class="breadcrumb-item">
                                <Link href="/dashboard">Dashboard</Link>
                            </li>
                            <li class="breadcrumb-item active">Data Guru</li>
                        </ul>
                    </div>
                    <div class="col-auto">
                        <button
                            @click="showAddModal = true"
                            class="btn btn-primary"
                        >
                            <i class="fas fa-plus me-1"></i> Tambah Guru
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
                                    placeholder="Cari NIP atau Nama..."
                                    v-model="filters.search"
                                    @input="onFilterChange"
                                />
                            </div>
                        </div>
                        <div class="col-lg-2 col-md-6">
                            <select
                                class="form-select"
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
                                class="form-select"
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
                    <EasyDataTable
                        :headers="headers"
                        :items="tableData"
                        :loading="loading"
                        buttons-pagination
                        :rows-per-page="guru.per_page || 10"
                        alternating
                        border-cell
                        table-class-name="modern-data-table"
                        header-text-direction="center"
                        body-text-direction="center"
                    >
                        <!-- No Column -->
                        <template #item-no="{ no }">
                            <div class="table-index">
                                {{ no }}
                            </div>
                        </template>

                        <!-- Checkbox Column untuk multi select -->
                        <template #item-select="item">
                            <div class="text-center">
                                <input
                                    type="checkbox"
                                    class="form-check-input"
                                    :value="item.id"
                                    v-model="selectedTeachers"
                                    :id="'teacher-' + item.id"
                                />
                            </div>
                        </template>

                        <!-- Nama Column -->
                        <template #item-nama="item">
                            <div class="teacher-name">
                                <div class="fw-semibold">{{ item.nama }}</div>
                                <small class="text-muted">{{ item.nip }}</small>
                            </div>
                        </template>

                        <!-- Bidang Studi Column -->
                        <template #item-bidang_studi="item">
                            <span class="badge bidang-badge">{{
                                item.bidang_studi || "-"
                            }}</span>
                        </template>

                        <!-- Jenis Kelamin Column -->
                        <template #item-jenis_kelamin="item">
                            <div
                                class="gender-badge"
                                :class="
                                    item.jenis_kelamin === 'L'
                                        ? 'male'
                                        : 'female'
                                "
                            >
                                <i
                                    :class="
                                        item.jenis_kelamin === 'L'
                                            ? 'fas fa-mars'
                                            : 'fas fa-venus'
                                    "
                                ></i>
                                <span>{{
                                    item.jenis_kelamin === "L" ? "L" : "P"
                                }}</span>
                            </div>
                        </template>

                        <!-- Tanggal Lahir Column -->
                        <template #item-tanggal_lahir="item">
                            <div class="fw-medium">
                                {{ formatShortDate(item.tanggal_lahir) }}
                            </div>
                        </template>

                        <!-- No. Telp Column -->
                        <template #item-no_telepon="item">
                            <div class="phone-number">
                                <i class="fas fa-phone me-1 text-muted"></i>
                                {{ item.no_telepon || "-" }}
                            </div>
                        </template>

                        <!-- Status Kepegawaian Column -->
                        <template #item-status_kepegawaian="item">
                            <span
                                class="status-badge"
                                :class="
                                    getKepegawaianClass(
                                        item.status_kepegawaian
                                    )
                                "
                            >
                                {{ item.status_kepegawaian || "-" }}
                            </span>
                        </template>

                        <!-- Status Aktif Column -->
                        <template #item-status_aktif="item">
                            <span
                                class="status-badge"
                                :class="getAktifClass(item.status_aktif)"
                            >
                                <i
                                    :class="
                                        getAktifIcon(item.status_aktif) + ' me-1'
                                    "
                                ></i>
                                {{ item.status_aktif }}
                            </span>
                        </template>

                        <!-- Actions Column -->
                        <template #item-actions="item">
                            <div class="action-buttons">
                                <button
                                    class="btn btn-action btn-view"
                                    @click="viewTeacherDetail(item)"
                                    title="Detail"
                                >
                                    <i class="fas fa-eye"></i>
                                </button>
                                <button
                                    class="btn btn-action btn-edit"
                                    @click="editTeacher(item)"
                                    title="Edit"
                                >
                                    <i class="fas fa-edit"></i>
                                </button>
                                <button
                                    class="btn btn-action btn-delete"
                                    @click.stop.prevent="confirmDelete(item)"
                                    title="Hapus"
                                    type="button"
                                >
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>
                        </template>

                        <!-- Empty State -->
                        <template #empty-message>
                            <div class="empty-state text-center py-5">
                                <div class="empty-icon mb-3">
                                    <i
                                        class="fas fa-chalkboard-teacher fa-4x text-light"
                                    ></i>
                                </div>
                                <h5 class="mb-2">Tidak ada data guru</h5>
                                <p class="text-muted mb-4">
                                    Belum ada data guru yang tersedia
                                </p>
                                <button
                                    @click="showAddModal = true"
                                    class="btn btn-primary"
                                >
                                    <i class="fas fa-plus me-1"></i>Tambah Guru
                                </button>
                            </div>
                        </template>
                    </EasyDataTable>

                    <!-- Pagination -->
                    <div class="p-3 border-top">
                        <div
                            class="d-flex justify-content-between align-items-center"
                        >
                            <div>
                                <small class="text-muted">
                                    Menampilkan {{ guru.from }} sampai
                                    {{ guru.to }} dari {{ guru.total }} data
                                </small>
                            </div>
                            <div>
                                <nav>
                                    <ul class="pagination pagination-sm mb-0">
                                        <!-- PREV -->
                                        <li
                                            class="page-item"
                                            :class="{
                                                disabled: !guru.prev_page_url,
                                            }"
                                        >
                                            <Link
                                                class="page-link"
                                                :href="guru.prev_page_url || ''"
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
                                            v-for="page in guru.links"
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
                                                disabled: !guru.next_page_url,
                                            }"
                                        >
                                            <Link
                                                class="page-link"
                                                :href="guru.next_page_url || ''"
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
                                            ? "Edit Data Guru"
                                            : "Tambah Data Guru"
                                    }}
                                </h5>
                                <p class="modal-subtitle text-muted">
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
                            class="btn-close"
                            @click="closeModal"
                        ></button>
                    </div>
                    <form @submit.prevent="saveTeacher">
                        <div class="modal-body">
                             <!-- Tabs Navigation -->
                            <div class="modal-tabs mb-4">
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
                                        v-if="!isEditing"
                                        type="button"
                                        class="nav-tab"
                                        :class="{ active: activeTab === 'import' }"
                                        @click="activeTab = 'import'"
                                    >
                                        <i class="fas fa-file-import me-2"></i>Import Excel
                                    </button>
                                </div>
                            </div>

                            <!-- Manual Input Form -->
                            <div v-if="activeTab === 'manual'">
                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="form-label required">NIP</label>
                                            <div class="input-group">
                                                <span class="input-group-text">
                                                    <i class="fas fa-id-card"></i>
                                                </span>
                                                <input
                                                    type="text"
                                                    class="form-control"
                                                    v-model="form.nip"
                                                    :class="{
                                                        'is-invalid': form.errors.nip,
                                                    }"
                                                    placeholder="Masukkan NIP"
                                                />
                                                <div
                                                    v-if="form.errors.nip"
                                                    class="invalid-feedback"
                                                >
                                                    {{ form.errors.nip }}
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- Sisa form manual tetap sama, hanya dibungkus div -->
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
                                                        'is-invalid': form.errors.nama,
                                                    }"
                                                    placeholder="Masukkan nama lengkap"
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
                                            <label class="form-label">Bidang Studi</label>
                                            <div class="input-group">
                                                <span class="input-group-text">
                                                    <i class="fas fa-book"></i>
                                                </span>
                                                <input
                                                    type="text"
                                                    class="form-control"
                                                    v-model="form.bidang_studi"
                                                    placeholder="Contoh: Matematika"
                                                />
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
                                                    <i class="fas fa-venus-mars"></i>
                                                </span>
                                                <select
                                                    class="form-select"
                                                    v-model="form.jenis_kelamin"
                                                    :class="{
                                                        'is-invalid':
                                                            form.errors.jenis_kelamin,
                                                    }"
                                                >
                                                    <option value="">
                                                        Pilih Jenis Kelamin
                                                    </option>
                                                    <option value="L">Laki-laki</option>
                                                    <option value="P">Perempuan</option>
                                                </select>
                                                <div
                                                    v-if="form.errors.jenis_kelamin"
                                                    class="invalid-feedback"
                                                >
                                                    {{ form.errors.jenis_kelamin }}
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
                                                <i class="fas fa-calendar"></i>
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
                                        <label class="form-label">Alamat</label>
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
                                            >Email</label
                                        >
                                        <div class="input-group">
                                            <span class="input-group-text">
                                                <i class="fas fa-envelope"></i>
                                            </span>
                                            <input
                                                type="email"
                                                class="form-control"
                                                v-model="form.email"
                                                :class="{
                                                    'is-invalid': form.errors.email,
                                                }"
                                                placeholder="nama@email.com"
                                            />
                                            <div
                                                v-if="form.errors.email"
                                                class="invalid-feedback"
                                            >
                                                {{ form.errors.email }}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="form-label"
                                            >No. Telepon</label
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
                                            >Status Kepegawaian</label
                                        >
                                        <select
                                            class="form-select"
                                            v-model="form.status_kepegawaian"
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
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="form-label"
                                            >Status Aktif</label
                                        >
                                        <select
                                            class="form-select"
                                            v-model="form.status_aktif"
                                        >
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
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="form-label"
                                            >Pendidikan Terakhir</label
                                        >
                                        <div class="input-group">
                                            <span class="input-group-text">
                                                <i class="fas fa-graduation-cap"></i>
                                            </span>
                                            <input
                                                type="text"
                                                class="form-control"
                                                v-model="form.pendidikan_terakhir"
                                                placeholder="Contoh: S1 Pendidikan Matematika"
                                            />
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="form-label"
                                            >Tanggal Mulai Bekerja</label
                                        >
                                        <div class="input-group">
                                            <span class="input-group-text">
                                                <i class="fas fa-calendar-check"></i>
                                            </span>
                                            <input
                                                type="date"
                                                class="form-control"
                                                v-model="form.tanggal_mulai_bekerja"
                                            />
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
                                        <p class="text-muted">
                                            Upload file Excel untuk menambahkan banyak data sekaligus
                                        </p>
                                    </div>

                                    <div class="import-guide mb-4">
                                        <div class="card bg-light border-0 p-3">
                                            <h6 class="mb-2">
                                                <i class="fas fa-info-circle me-2"></i>Petunjuk Import:
                                            </h6>
                                            <ul class="small text-muted mb-0 ps-3">
                                                <li>Gunakan template Excel yang disediakan</li>
                                                <li>Pastikan format kolom sesuai (NIP, Nama, dll)</li>
                                                <li>NIP harus unik untuk setiap guru</li>
                                            </ul>
                                            <div class="mt-3">
                                                <button
                                                    type="button"
                                                    @click="downloadTemplate"
                                                    class="btn btn-sm btn-outline-primary"
                                                >
                                                    <i class="fas fa-download me-1"></i>Download Template
                                                </button>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <div class="upload-zone text-center p-4 border rounded dashed">
                                            <input
                                                type="file"
                                                class="d-none"
                                                ref="fileInput"
                                                accept=".xlsx, .xls"
                                                @change="handleFileChange"
                                            />
                                            <div v-if="!importForm.file">
                                                <i class="fas fa-cloud-upload-alt fa-2x text-muted mb-2"></i>
                                                <p class="text-muted mb-2">Drag file here or click to upload</p>
                                                <button
                                                    type="button"
                                                    class="btn btn-sm btn-outline-primary"
                                                    @click="$refs.fileInput.click()"
                                                >
                                                    Pilih File
                                                </button>
                                            </div>
                                            <div v-else class="file-preview">
                                                <div class="d-flex align-items-center justify-content-between">
                                                    <div class="d-flex align-items-center">
                                                        <i class="fas fa-file-excel text-success fa-2x me-3"></i>
                                                        <div class="text-start">
                                                            <h6 class="mb-0">{{ importForm.file.name }}</h6>
                                                            <small class="text-muted">
                                                                {{ (importForm.file.size / 1024).toFixed(2) }} KB
                                                            </small>
                                                        </div>
                                                    </div>
                                                    <button
                                                        type="button"
                                                        class="btn btn-sm btn-link text-danger"
                                                        @click="importForm.file = null"
                                                    >
                                                        <i class="fas fa-times"></i>
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                        <div v-if="importForm.errors.file" class="text-danger small mt-1">
                                            {{ importForm.errors.file }}
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
                            <button
                                v-if="activeTab === 'import'"
                                type="button"
                                class="btn btn-primary"
                                @click="importGuru"
                                :disabled="importing || !importForm.file"
                            >
                                <i
                                    class="fas"
                                    :class="importing ? 'fa-spinner fa-spin' : 'fa-upload'"
                                ></i>
                                {{ importing ? 'Mengimport...' : 'Import Data' }}
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
                            <i class="modal-icon fas fa-chalkboard-teacher"></i>
                            <div>
                                <h5>Detail Data Guru</h5>
                                <p class="modal-subtitle text-muted">
                                    Informasi lengkap data guru
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

    </AdminLayout>
</template>

<script setup>
import { ref, reactive, computed, watch, onMounted } from "vue";
import { Link, useForm, router, Head, usePage } from "@inertiajs/vue3";
import AdminLayout from "@/Layouts/AdminLayout.vue";
import ToastNotification from "@/Components/ToastNotification.vue";

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
const showConfirmModal = ref(false);
const showBulkModal = ref(false);
const showBulkDeleteModal = ref(false);
const isEditing = ref(false);
const activeTab = ref('manual');
const loading = ref(false);
const deleting = ref(false);
const deletingBulk = ref(false);
const importing = ref(false);

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
});

const bulkForm = useForm({
    ids: [],
    status_aktif: "",
    status_kepegawaian: "",
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
    const perPage = props.guru.per_page || 10;
    const start = (page - 1) * perPage;
    
    return props.guru.data.map((item, index) => ({
        ...item,
        no: start + index + 1
    }));
});

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
        showWarning("Peringatan!", "Pilih guru terlebih dahulu!");
        return;
    }
    showBulkModal.value = true;
};

const applyBulkActions = () => {
    if (!bulkAction.status_aktif && !bulkAction.status_kepegawaian) {
        showWarning(
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
            showSuccess(
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
            showError("Gagal!", "Terjadi kesalahan saat mengupdate data");
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
                showSuccess(
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
                showError("Gagal!", "Terjadi kesalahan saat menghapus data");
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
        showWarning("Peringatan!", "Silakan pilih file Excel terlebih dahulu");
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
                 showSuccess(
                    "Import Selesai!", 
                    `Sukses: ${result.successCount}, Gagal: ${result.errorCount}`
                );
            } else {
                 showSuccess("Sukses!", "Data guru berhasil diimport");
            }
        },
        onError: (errors) => {
            importing.value = false;
            console.log("Import errors:", errors);
            showError("Gagal!", "Terjadi kesalahan saat import data");
        },
    });
};

const downloadTemplate = () => {
    window.location.href = "/admin/guru/download-template";
};

// Teacher CRUD Operations
const viewTeacherDetail = (teacher) => {
    selectedTeacher.value = teacher;
    showDetailModal.value = true;
};

const editTeacher = (teacher) => {
    isEditing.value = true;
    form.id = teacher.id;
    form.nip = teacher.nip;
    form.nama = teacher.nama;
    form.bidang_studi = teacher.bidang_studi;
    form.jenis_kelamin = teacher.jenis_kelamin;
    form.tempat_lahir = teacher.tempat_lahir;
    form.tanggal_lahir = teacher.tanggal_lahir;
    form.alamat = teacher.alamat;
    form.email = teacher.email;
    form.no_telepon = teacher.no_telepon;
    form.status_kepegawaian = teacher.status_kepegawaian;
    form.status_aktif = teacher.status_aktif;
    form.pendidikan_terakhir = teacher.pendidikan_terakhir;
    form.tanggal_mulai_bekerja = teacher.tanggal_mulai_bekerja;

    showAddModal.value = true;
    showDetailModal.value = false;
    activeTab.value = 'manual';
};

const confirmDelete = (teacher) => {
    teacherToDelete.value = teacher;
    showConfirmModal.value = true;
};

const deleteTeacher = () => {
    if (!teacherToDelete.value || deleting.value) return;

    deleting.value = true;

    form.delete(`/admin/guru/${teacherToDelete.value.id}`, {
        preserveScroll: true,
        onSuccess: () => {
            showSuccess(
                "Sukses!",
                `Berhasil menghapus data guru: ${teacherToDelete.value.nama}`
            );
            showConfirmModal.value = false;
            teacherToDelete.value = null;
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

const saveTeacher = () => {
    if (!form.nip || !form.nama || !form.jenis_kelamin) {
        showWarning("Peringatan!", "Harap isi semua field yang wajib diisi!");
        return;
    }

    if (isEditing.value) {
        form.put(`/admin/guru/${form.id}`, {
            preserveScroll: true,
            onSuccess: () => {
                showSuccess("Sukses!", "Data guru berhasil diperbarui");
                closeModal();
            },
            onError: (errors) => {
                console.log("Update errors:", errors);
                showError("Gagal!", "Terjadi kesalahan saat menyimpan data");
            },
        });
    } else {
        form.post("/admin/guru", {
            preserveScroll: true,
            onSuccess: () => {
                showSuccess("Sukses!", "Data guru berhasil ditambahkan");
                closeModal();
            },
            onError: (errors) => {
                console.log("Create errors:", errors);
                showError("Gagal!", "Terjadi kesalahan saat menambah data");
            },
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
/* All styles have been moved to admin-theme.css */
</style>

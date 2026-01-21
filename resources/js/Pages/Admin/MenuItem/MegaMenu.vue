<template>
    <AdminLayout>
        <div class="content container-fluid">
            <!-- Radiant Header -->
            <div class="card border-0 rounded-4 mb-4 overflow-hidden" style="background: linear-gradient(135deg, #4361ee 0%, #3a0ca3 100%);">
                <div class="card-body p-4 position-relative">
                    <div class="d-flex align-items-center gap-4 position-relative z-1">
                        <div class="icon-box bg-white bg-opacity-25 rounded-4 p-3 d-flex align-items-center justify-content-center backdrop-blur" style="width: 64px; height: 64px;">
                            <i class="fas fa-sitemap fa-2x text-white"></i>
                        </div>
                        <div>
                            <h3 class="fw-bold mb-1 text-white">Kelola Menu: {{ menu.nama }}</h3>
                            <p class="mb-0 text-white-50">Atur konten dan struktur menu {{ menu.nama }}</p>
                        </div>
                    </div>
                    <!-- Decorative Circle -->
                    <div class="position-absolute top-0 end-0 p-3 opacity-10 pointer-events-none">
                        <i class="fas fa-layer-group fa-10x text-white transform-rotate-12" style="margin-right: -40px; margin-top: -40px;"></i>
                    </div>
                </div>
            </div>

            <!-- Breadcrumb -->
            <div class="row align-items-center mb-4">
                <div class="col">
                     <nav aria-label="breadcrumb">
                        <ol class="breadcrumb alert alert-primary border-0 rounded-4 shadow-sm py-3 px-4 mb-0 align-items-center">
                            <li class="breadcrumb-item"><Link href="/admin/halaman" class="text-decoration-none fw-bold"><i class="fas fa-layer-group me-1"></i> Halaman</Link></li>
                            <li class="breadcrumb-item active text-primary fw-bold" aria-current="page">{{ menu.nama }}</li>
                        </ol>
                    </nav>
                </div>
            </div>



            <!-- Layout for Guru, Siswa, Alumni, PPDB (Modern Grid) -->
            <div v-if="['guru', 'siswa', 'alumni', 'ppdb'].includes(menu.slug)">
                <div class="d-flex align-items-center justify-content-between mb-4">
                    <!-- Removed redundant title -->
                </div>

                <div class="row g-4">
                    <!-- Cards Loop -->
                    <div v-for="item in groups" :key="item.id" class="col-md-6 col-lg-4">
                        <div class="card h-100 border-0 shadow-sm rounded-4 zoom-effect cursor-pointer group-card overflow-hidden" @click="handleCardClick($event, item)">
                            <!-- Decoration -->
                            <div class="position-absolute top-0 end-0 p-3 opacity-05">
                                <i class="fas fa-file-alt fa-5x text-primary transform-rotate-12"></i>
                            </div>

                            <div class="card-body p-4 position-relative z-1">
                                <div class="d-flex justify-content-between align-items-start mb-4">
                                    <div class="d-flex align-items-center gap-3">
                                        <!-- Gradient Icon -->
                                        <div class="icon-box rounded-circle p-3 d-flex align-items-center justify-content-center shadow-sm" style="width: 50px; height: 50px; background: linear-gradient(135deg, #4361ee 0%, #3a0ca3 100%); color: white;">
                                            <i class="fas fa-lg" :class="item.type === 'url' ? 'fa-link' : 'fa-file-alt'"></i>
                                        </div>
                                        <div>
                                            <h5 class="fw-bold text-dark mb-1">{{ item.nama }}</h5>
                                            <div class="d-flex gap-2">
                                                <span v-if="item.slug === 'direktori-guru'" class="badge bg-success-soft text-success rounded-pill px-3 border border-success-subtle">System Data</span>
                                                <span v-else-if="['direktori-siswa', 'data-siswa', 'siswa'].includes(item.slug)" class="badge bg-success-soft text-success rounded-pill px-3 border border-success-subtle">System Data</span>
                                                <span v-else-if="item.type === 'url'" class="badge bg-info-soft text-info rounded-pill px-3 border border-info-subtle">External Link</span>
                                                <span v-else class="badge bg-primary-soft text-primary rounded-pill px-3 border border-primary-subtle">Content Page</span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="dropdown">
                                        <button class="btn btn-sm btn-light btn-icon rounded-circle" type="button" data-bs-toggle="dropdown">
                                            <i class="fas fa-ellipsis-v text-muted"></i>
                                        </button>
                                        <ul class="dropdown-menu dropdown-menu-end border-0 shadow">
                                            <li>
                                                <button class="dropdown-item" @click="editLevel1Item(item)">
                                                    <i class="fas fa-pencil-alt me-2 text-muted"></i> Edit Menu
                                                </button>
                                            </li>
                                            <li><hr class="dropdown-divider"></li>
                                            <li>
                                                <button class="dropdown-item text-danger" @click="deleteItem(item)">
                                                    <i class="fas fa-trash-alt me-2"></i> Hapus
                                                </button>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                                
                                <div class="mb-4">
                                     <p class="text-muted small mb-0 text-truncate" v-if="item.type === 'url'">
                                        <i class="fas fa-link me-1"></i> {{ item.url }}
                                    </p>
                                    <p class="text-muted small mb-0" v-else-if="item.slug === 'direktori-guru'">
                                        Mengelola database guru dan staf pengajar.
                                    </p>
                                    <p class="text-muted small mb-0" v-else-if="['direktori-siswa', 'data-siswa', 'siswa'].includes(item.slug)">
                                        Mengelola database siswa aktif dan alumni.
                                    </p>
                                    <p class="text-muted small mb-0" v-else>
                                        Halaman konten statis untuk informasi sekolah.
                                    </p>
                                </div>

                                <div class="d-flex justify-content-between align-items-center pt-3 border-top border-light">
                                    <div class="d-flex align-items-center gap-2 form-check form-switch cursor-pointer mb-0 p-0" title="Tampilkan/Sembunyikan">
                                        <input class="form-check-input m-0" type="checkbox" :checked="item.is_active" @change="toggleStatus(item)" style="width: 2.5em; height: 1.25em;">
                                        <label class="form-check-label small fw-medium text-muted mb-0">{{ item.is_active ? 'Aktif' : 'Draft' }}</label>
                                    </div>
                                    
                                    <Link v-if="item.slug === 'direktori-guru'" href="/admin/guru" class="btn btn-sm btn-link text-decoration-none fw-bold text-primary p-0">
                                        Kelola <i class="fas fa-arrow-right ms-1"></i>
                                    </Link>

                                    <Link v-else-if="['direktori-siswa', 'data-siswa', 'siswa'].includes(item.slug)" href="/admin/siswa" class="btn btn-sm btn-link text-decoration-none fw-bold text-primary p-0">
                                        Kelola <i class="fas fa-arrow-right ms-1"></i>
                                    </Link>
                                    
                                    <button v-else-if="item.type === 'page'" class="btn btn-sm btn-link text-decoration-none fw-bold text-primary p-0" @click="goToEdit(item)">
                                        Edit Isi <i class="fas fa-arrow-right ms-1"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Add New Placeholder Card -->
                    <div class="col-md-6 col-lg-4">
                        <div class="card h-100 border-2 border-dashed shadow-none bg-light cursor-pointer hover-bg-primary group-add-card" @click="openAddLevel1Modal">
                            <div class="card-body d-flex flex-column align-items-center justify-content-center text-center py-5">
                                <div class="icon-circle mb-3 bg-white text-primary shadow-sm">
                                    <i class="fas fa-plus fa-lg"></i>
                                </div>
                                <h6 class="fw-bold text-primary mb-1">Tambah Menu Baru</h6>
                                <p class="small text-muted mb-0">Klik untuk menambahkan halaman atau link</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Original Mega Menu Layout (for Akademik etc) -->
            <div v-else>
                <!-- 4 Column Grid -->
                <div class="row g-4">
                    <!-- Column 1: Tentang Kami (Static) -->
                    <div class="col-md-6">
                        <div class="card h-100 border-0 shadow-sm mega-card">
                            <div class="card-body text-center p-4">
                                <div class="icon-circle bg-primary-soft text-primary mb-3">
                                    <i class="fas fa-info fa-lg"></i>
                                </div>
                                <h5 class="fw-bold mb-2">Tentang Kami</h5>
                                <p class="text-muted small mb-4">Kolom 1: Deskripsi singkat sekolah.</p>
                                <div class="mb-3 text-start">
                                    <label class="form-label small fw-bold">Konten Deskripsi:</label>
                                    <textarea 
                                        class="form-control bg-light" 
                                        rows="5" 
                                        v-model="aboutData.description" 
                                        placeholder="Tulis deskripsi singkat sekolah..."
                                    ></textarea>
                                </div>
                                <button class="btn btn-primary w-100 rounded-pill" @click="saveAbout" :disabled="saving">
                                    <span v-if="saving"><i class="fas fa-spinner fa-spin me-2"></i>Menyimpan...</span>
                                    <span v-else><i class="fas fa-save me-2"></i>Simpan Perubahan</span>
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Column 2: Profil Madrasah -->
                    <div class="col-md-6">
                         <div class="card h-100 border-0 shadow-sm mega-card">
                             <div class="card-header bg-white border-bottom-0 pt-4 pb-0 px-4">
                                 <div class="d-flex align-items-center mb-2 justify-content-between">
                                     <div class="d-flex align-items-center" v-if="!editing.profil">
                                         <div class="icon-circle me-3 bg-success-soft text-success"><i class="fas fa-school"></i></div>
                                         <h5 class="fw-bold mb-0 cursor-pointer" @click="startEdit('profil', 'profil')">{{ getGroupName('profil') || 'Profil' }}</h5>
                                         <button class="btn btn-sm btn-icon text-muted ms-2" @click="startEdit('profil', 'profil')"><i class="fas fa-pencil-alt small"></i></button>
                                     </div>
                                     <div class="d-flex align-items-center w-100" v-else>
                                        <input type="text" class="form-control form-control-sm me-2" v-model="editName.profil" @keyup.enter="saveGroupName('profil', 'profil')" ref="profilInput">
                                        <button class="btn btn-sm btn-success me-1" @click="saveGroupName('profil', 'profil')"><i class="fas fa-check"></i></button>
                                        <button class="btn btn-sm btn-light" @click="cancelEdit('profil')"><i class="fas fa-times"></i></button>
                                     </div>
                                 </div>
                             </div>
                             <div class="card-body px-0">
                                 <div class="list-group list-group-flush">
                                     <div v-for="item in getGroupItems('profil')" :key="item.id" class="list-group-item border-0 px-4 py-2 item-row">
                                         <div class="d-flex align-items-center justify-content-between p-2 rounded hover-bg">
                                             <div class="d-flex align-items-center text-truncate flex-grow-1" :class="{'cursor-pointer': !lockedItems.includes(item.slug)}" @click="!lockedItems.includes(item.slug) && goToEdit(item)">
                                                 <span class="fw-medium text-dark" :class="{'text-decoration-line-through opacity-50': !item.is_active}">{{ item.nama }}</span>
                                             </div>
                                             <div class="action-buttons">
                                                 <button class="btn btn-icon btn-sm text-muted" @click="toggleStatus(item)"><i class="fas" :class="item.is_active ? 'fa-eye' : 'fa-eye-slash text-danger'"></i></button>
                                                  <button v-if="!templateMapping[item.slug] && !lockedItems.includes(item.slug)" class="btn btn-icon btn-sm text-danger" @click="deleteItem(item)"><i class="fas fa-trash-alt"></i></button>
                                                 <button v-if="!lockedItems.includes(item.slug)" class="btn btn-icon btn-sm text-primary" @click="goToEdit(item)"><i class="fas fa-chevron-right"></i></button>
                                             </div>
                                         </div>
                                     </div>
                                 </div>
                             </div>
                             <div class="card-footer bg-white border-top-0 text-center pb-4">
                                 <button class="btn btn-light text-primary w-100 rounded-pill py-2" @click="openAddItemModal('profil')"><i class="fas fa-plus me-1"></i> Tambah Menu</button>
                             </div>
                         </div>
                    </div>

                    <!-- Column 3: Akademik -->
                    <div class="col-md-6">
                         <div class="card h-100 border-0 shadow-sm mega-card">
                             <div class="card-header bg-white border-bottom-0 pt-4 pb-0 px-4">
                                <div class="d-flex align-items-center mb-2 justify-content-between">
                                     <div class="d-flex align-items-center" v-if="!editing.akademik">
                                         <div class="icon-circle me-3 bg-primary-soft text-primary"><i class="fas fa-graduation-cap"></i></div>
                                         <h5 class="fw-bold mb-0 cursor-pointer" @click="startEdit('akademik', 'akademik-program')">{{ getGroupName('akademik-program') || 'Akademik' }}</h5>
                                         <button class="btn btn-sm btn-icon text-muted ms-2" @click="startEdit('akademik', 'akademik-program')"><i class="fas fa-pencil-alt small"></i></button>
                                     </div>
                                     <div class="d-flex align-items-center w-100" v-else>
                                        <input type="text" class="form-control form-control-sm me-2" v-model="editName.akademik" @keyup.enter="saveGroupName('akademik', 'akademik-program')" ref="akademikInput">
                                        <button class="btn btn-sm btn-success me-1" @click="saveGroupName('akademik', 'akademik-program')"><i class="fas fa-check"></i></button>
                                        <button class="btn btn-sm btn-light" @click="cancelEdit('akademik')"><i class="fas fa-times"></i></button>
                                     </div>
                                 </div>
                             </div>
                             <div class="card-body px-0">
                                 <div class="list-group list-group-flush">
                                     <div v-for="item in getGroupItems('akademik-program')" :key="item.id" class="list-group-item border-0 px-4 py-2 item-row">
                                         <div class="d-flex align-items-center justify-content-between p-2 rounded hover-bg">
                                             <div class="d-flex align-items-center text-truncate flex-grow-1" :class="{'cursor-pointer': !lockedItems.includes(item.slug)}" @click="!lockedItems.includes(item.slug) && goToEdit(item)">
                                                 <span class="fw-medium text-dark" :class="{'text-decoration-line-through opacity-50': !item.is_active}">{{ item.nama }}</span>
                                             </div>
                                             <div class="action-buttons">
                                                 <button class="btn btn-icon btn-sm text-muted" @click="toggleStatus(item)"><i class="fas" :class="item.is_active ? 'fa-eye' : 'fa-eye-slash text-danger'"></i></button>
                                                 <button v-if="!templateMapping[item.slug] && !lockedItems.includes(item.slug)" class="btn btn-icon btn-sm text-danger" @click="deleteItem(item)"><i class="fas fa-trash-alt"></i></button>
                                                 <button v-if="!lockedItems.includes(item.slug)" class="btn btn-icon btn-sm text-primary" @click="goToEdit(item)"><i class="fas fa-chevron-right"></i></button>
                                             </div>
                                         </div>
                                     </div>
                                 </div>
                             </div>
                             <div class="card-footer bg-white border-top-0 text-center pb-4">
                                 <button class="btn btn-light text-primary w-100 rounded-pill py-2" @click="openAddItemModal('akademik-program')"><i class="fas fa-plus me-1"></i> Tambah Menu</button>
                             </div>
                         </div>
                    </div>

                    <!-- Column 4: Layanan -->
                    <div class="col-md-6">
                        <div class="card h-100 border-0 shadow-sm mega-card">
                             <div class="card-header bg-white border-bottom-0 pt-4 pb-0 px-4">
                                 <div class="d-flex align-items-center mb-2 justify-content-between">
                                     <div class="d-flex align-items-center" v-if="!editing.layanan">
                                         <div class="icon-circle me-3 bg-info-soft text-info"><i class="fas fa-concierge-bell"></i></div>
                                         <h5 class="fw-bold mb-0 cursor-pointer" @click="startEdit('layanan', 'layanan')">{{ getGroupName('layanan') || 'Layanan' }}</h5>
                                         <button class="btn btn-sm btn-icon text-muted ms-2" @click="startEdit('layanan', 'layanan')"><i class="fas fa-pencil-alt small"></i></button>
                                     </div>
                                     <div class="d-flex align-items-center w-100" v-else>
                                        <input type="text" class="form-control form-control-sm me-2" v-model="editName.layanan" @keyup.enter="saveGroupName('layanan', 'layanan')" ref="layananInput">
                                        <button class="btn btn-sm btn-success me-1" @click="saveGroupName('layanan', 'layanan')"><i class="fas fa-check"></i></button>
                                        <button class="btn btn-sm btn-light" @click="cancelEdit('layanan')"><i class="fas fa-times"></i></button>
                                     </div>
                                 </div>
                             </div>
                             <div class="card-body px-0">
                                 <div class="list-group list-group-flush">
                                     <div v-for="item in getGroupItems('layanan')" :key="item.id" class="list-group-item border-0 px-4 py-2 item-row">
                                         <div class="d-flex align-items-center justify-content-between p-2 rounded hover-bg">
                                             <div class="d-flex align-items-center text-truncate flex-grow-1" :class="{'cursor-pointer': !lockedItems.includes(item.slug)}" @click="!lockedItems.includes(item.slug) && goToEdit(item)">
                                                 <span class="fw-medium text-dark" :class="{'text-decoration-line-through opacity-50': !item.is_active}">{{ item.nama }}</span>
                                             </div>
                                             <div class="action-buttons">
                                                 <button class="btn btn-icon btn-sm text-muted" @click="toggleStatus(item)"><i class="fas" :class="item.is_active ? 'fa-eye' : 'fa-eye-slash text-danger'"></i></button>
                                                  <button v-if="!templateMapping[item.slug] && !lockedItems.includes(item.slug)" class="btn btn-icon btn-sm text-danger" @click="deleteItem(item)"><i class="fas fa-trash-alt"></i></button>
                                                 <button v-if="!lockedItems.includes(item.slug)" class="btn btn-icon btn-sm text-primary" @click="goToEdit(item)"><i class="fas fa-chevron-right"></i></button>
                                             </div>
                                         </div>
                                     </div>
                                 </div>
                             </div>
                             <div class="card-footer bg-white border-top-0 text-center pb-4">
                                 <button class="btn btn-light text-primary w-100 rounded-pill py-2" @click="openAddItemModal('layanan')"><i class="fas fa-plus me-1"></i> Tambah Menu</button>
                             </div>
                        </div>
                    </div>
                </div>
            </div>



            <!-- Modal Tambah Item (Generic) -->
            <div class="modal fade" id="addItemModal" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content border-0">
                        <div class="modal-header border-bottom-0 pb-0">
                            <h5 class="modal-title fw-bold">{{ newItemData.isLevel1 ? 'Tambah Menu Utama' : 'Tambah Sub Menu' }}</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>
                        <div class="modal-body">
                            <div class="mb-3">
                                <label class="form-label">Nama Menu</label>
                                <input type="text" class="form-control" v-model="newItemData.nama">
                            </div>
                            
                            <div class="mb-3">
                                <label class="form-label">Tipe Link</label>
                                <div class="form-check mb-2">
                                    <input class="form-check-input" type="radio" value="page" v-model="newItemData.type">
                                    <label class="form-check-label">Halaman Konten</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" value="url" v-model="newItemData.type">
                                    <label class="form-check-label">Link Eksternal</label>
                                </div>
                            </div>

                            <div class="mb-3" v-if="newItemData.type === 'url'">
                                <label class="form-label">URL Tujuan</label>
                                <input type="text" class="form-control bg-light" v-model="newItemData.url" placeholder="https://...">
                            </div>
                        </div>
                        <div class="modal-footer border-top-0 pt-0">
                            <button type="button" class="btn btn-light" data-bs-dismiss="modal">Batal</button>
                            <button type="button" class="btn btn-primary px-4" @click="saveNewItem" :disabled="saving">Tambahkan</button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Modal Edit Level 1 -->
             <div class="modal fade" id="editLevel1Modal" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content border-0 shadow-lg rounded-4">
                        <div class="modal-header text-white" style="background: linear-gradient(135deg, #4361ee 0%, #3a0ca3 100%);">
                            <div class="d-flex align-items-center gap-3">
                                <div class="icon-box bg-white bg-opacity-25 rounded-3 p-2 d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                                    <i class="fas fa-pen text-white"></i>
                                </div>
                                <div>
                                    <h5 class="modal-title fw-bold mb-0 text-white">Edit Menu</h5>
                                    <p class="mb-0 text-white-50 small">Perbarui data menu ini</p>
                                </div>
                            </div>
                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                        </div>
                        <div class="modal-body">
                            <div class="mb-3">
                                <label class="form-label">Nama Menu</label>
                                <input type="text" class="form-control" v-model="editItemData.nama">
                            </div>
                             <div class="mb-3">
                                <label class="form-label">Tipe Menu</label>
                                <select class="form-select" v-model="editItemData.type">
                                    <option value="page">Halaman Konten</option>
                                    <option value="url">Link Eksternal</option>
                                </select>
                            </div>
                            <div class="mb-3" v-if="editItemData.type === 'url'">
                                <label class="form-label">URL Tujuan</label>
                                <input type="text" class="form-control" v-model="editItemData.url" placeholder="https://...">
                            </div>
                        </div>
                        <div class="modal-footer border-top-0 pt-0">
                            <button type="button" class="btn btn-light" data-bs-dismiss="modal">Batal</button>
                            <button type="button" class="btn btn-primary px-4" @click="updateLevel1Item" :disabled="saving">Simpan Perubahan</button>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </AdminLayout>
</template>

<script setup>
import { ref, reactive, onMounted, nextTick } from 'vue';
import AdminLayout from '@/Layouts/AdminLayout.vue';
import { Link, router } from '@inertiajs/vue3';
import Swal from 'sweetalert2';
import { showSuccess, showError } from '@/Utils/notifications';

const props = defineProps({
    menu: Object,
    groups: Array,
    items: Array,
    aboutDescription: String
});

const saving = ref(false);
let addItemModal = null;
let editLevel1Modal = null;

const aboutData = ref({ description: props.aboutDescription || '' });
const newItemData = reactive({ group_slug: '', nama: '', url: '', type: 'page', isLevel1: false });
const editItemData = reactive({ id: null, nama: '', url: '', type: 'page' });
const editingItemId = ref(null);
const editNameInput = ref('');

// Template/Page mapping for standardized pages
const templateMapping = {
    'visi-misi': 'visi_misi',
    'sejarah': 'sejarah',
    'struktur-organisasi': 'organisasi',
    'sambutan': 'sambutan',
    'kepala-madrasah': 'sambutan',
    'sarana-prasarana': 'sarpras',
    'tujuan': 'tujuan',
    'kurikulum-mapel': 'kurikulum',
    'syarat-alur': 'ppdb_syarat',
    'jadwal-kuota': 'ppdb_kuota',
};

// Items that cannot be edited or deleted from here (managed in separate modules)
const lockedItems = ['berita-pengumuman', 'gallery'];

// State handling
const editing = reactive({ profil: false, akademik: false, layanan: false });
const editName = reactive({ profil: '', akademik: '', layanan: '' });

// Refs for focus
const profilInput = ref(null);
const akademikInput = ref(null);
const layananInput = ref(null);

onMounted(() => {
    if (typeof bootstrap !== 'undefined') {
        const addItemModalEl = document.getElementById('addItemModal');
        if(addItemModalEl) addItemModal = new bootstrap.Modal(addItemModalEl);

        const editLevel1ModalEl = document.getElementById('editLevel1Modal');
        if(editLevel1ModalEl) editLevel1Modal = new bootstrap.Modal(editLevel1ModalEl);
    }
});

const getGroupItems = (slug) => {
    const group = props.groups.find(g => g.slug === slug);
    return group ? props.items.filter(item => item.parent_id === group.id) : [];
};

const getGroupName = (slug) => {
    if(!slug) return '';
    const group = props.groups.find(g => g.slug === slug);
    return group ? group.nama : '';
};

// New Name Editing Logic
const startEdit = (key, slug) => {
    const group = props.groups.find(g => g.slug === slug);
    if(group) {
        editName[key] = group.nama;
        editing[key] = true;
        // Simple nextTick focus
        nextTick(() => {
            if(key === 'profil' && profilInput.value) profilInput.value.focus();
            if(key === 'akademik' && akademikInput.value) akademikInput.value.focus();
            if(key === 'layanan' && layananInput.value) layananInput.value.focus();
        });
    }
};

const cancelEdit = (key) => {
    editing[key] = false;
};

const saveGroupName = (key, slug) => {
    const group = props.groups.find(g => g.slug === slug);
    if(!group) return;

    if (!editName[key] || editName[key].trim() === '') return;

    router.put(`/admin/halaman/items/${group.id}`, {
        nama: editName[key],
        is_active: true
    }, {
        onSuccess: () => {
            editing[key] = false;
            showSuccess('Berhasil', 'Nama grup berhasil diubah');
        }
    });
};

const goToEdit = (item) => {
    const url = getEditUrl(item);
    if(url) router.visit(url);
};

const getEditUrl = (item) => {
    const key = templateMapping[item.slug];
    if (key) return '/admin/pages/' + key + '/edit';
    return '/admin/halaman/items/' + item.id + '/content';
};

// CRUD Actions
const saveAbout = () => {
    saving.value = true;
    router.put(`/admin/halaman/${props.menu.id}/mega-menu/about`, { description: aboutData.value.description }, {
        onSuccess: () => showSuccess('Berhasil', 'Deskripsi berhasil disimpan!'),
        onFinish: () => saving.value = false
    });
};

// Level 1 (Guru) Modal Actions
const openAddLevel1Modal = () => {
    newItemData.group_slug = null;
    newItemData.isLevel1 = true;
    newItemData.nama = '';
    newItemData.url = '';
    newItemData.type = 'page';
    addItemModal?.show();
};

const editLevel1Item = (item) => {
    editItemData.id = item.id;
    editItemData.nama = item.nama;
    editItemData.url = item.url;
    editItemData.type = item.type;
    editLevel1Modal?.show();
};

const updateLevel1Item = () => {
    if (!editItemData.nama) return;
    saving.value = true;
    router.put(`/admin/halaman/items/${editItemData.id}`, {
        nama: editItemData.nama,
        type: editItemData.type,
        url: editItemData.type === 'url' ? editItemData.url : null,
        is_active: true
    }, {
        onSuccess: () => { 
            editLevel1Modal?.hide(); 
            Swal.fire({
                icon: 'success',
                title: 'Berhasil!',
                text: 'Data menu telah diperbarui.',
                confirmButtonColor: '#4361ee',
                timer: 2000,
                timerProgressBar: true
            });
        },
        onFinish: () => saving.value = false
    });
};

const openAddItemModal = (groupSlug) => {
    newItemData.group_slug = groupSlug;
    newItemData.isLevel1 = false;
    newItemData.nama = '';
    newItemData.url = '';
    newItemData.type = 'page';
    addItemModal?.show();
};

const saveNewItem = () => {
    if (!newItemData.nama) return;
    saving.value = true;

    if (newItemData.isLevel1) {
        // Use generic menu item store for Level 1
        router.post('/admin/halaman/items', {
            menu_navbar_id: props.menu.id,
            parent_id: null,
            nama: newItemData.nama,
            type: newItemData.type,
            url: newItemData.type === 'url' ? newItemData.url : null
        }, {
            onSuccess: () => { 
                addItemModal?.hide(); 
                Swal.fire({
                    icon: 'success',
                    title: 'Berhasil!',
                    text: 'Menu utama berhasil ditambahkan!',
                    confirmButtonColor: '#4361ee',
                    timer: 2000,
                    timerProgressBar: true
                });
            },
            onFinish: () => saving.value = false
        });
    } else {
        // Use mega menu sub-item store
        router.post(`/admin/halaman/${props.menu.id}/mega-menu/item`, {
            group_slug: newItemData.group_slug,
            nama: newItemData.nama,
            url: newItemData.type === 'url' ? newItemData.url : null
        }, {
            onSuccess: () => { 
                addItemModal?.hide(); 
                Swal.fire({
                    icon: 'success',
                    title: 'Berhasil!',
                    text: 'Menu berhasil ditambahkan!',
                    confirmButtonColor: '#4361ee',
                    timer: 2000,
                    timerProgressBar: true
                });
            },
            onFinish: () => saving.value = false
        });
    }
};

const toggleStatus = (item) => {
    router.put(`/admin/halaman/items/${item.id}`, {
        nama: item.nama,
        is_active: !item.is_active
    }, { 
        preserveScroll: true,
        onSuccess: () => {
             showSuccess('Status Diperbarui', `Menu sekarang ${!item.is_active ? 'Aktif' : 'Draft'}`);
        }
    });
};

const deleteItem = (item) => {
    Swal.fire({
        title: 'Hapus Item?',
        text: `Hapus menu "${item.nama}"?`,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#ef4444',
        confirmButtonText: 'Ya, Hapus',
        cancelButtonText: 'Batal'
    }).then((result) => {
        if (result.isConfirmed) {
            router.delete(`/admin/halaman/items/${item.id}`, {
                onSuccess: () => showSuccess('Berhasil', 'Item terhapus.'),
                preserveScroll: true
            });
        }
    });
};

const handleCardClick = (event, item) => {
    // Prevent if clicking on dropdown or buttons
    if (event.target.closest('.dropdown') || event.target.closest('.btn') || event.target.closest('.form-check')) {
        return;
    }

    if (item.slug === 'direktori-guru') {
        router.visit('/admin/guru');
    } else if (['direktori-siswa', 'data-siswa', 'siswa'].includes(item.slug)) {
        router.visit('/admin/siswa');
    } else if (item.type === 'page') {
        goToEdit(item);
    }
};

</script>

<style scoped>
.bg-primary-soft { background-color: #e0f2fe; }
.bg-success-soft { background-color: #dcfce7; }
.bg-warning-soft { background-color: #fef9c3; }
.bg-info-soft { background-color: #cffafe; }

.icon-circle {
    width: 48px;
    height: 48px;
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    margin-right: 12px;
}

.mega-card {
    transition: transform 0.2s ease, box-shadow 0.2s ease;
}
.mega-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 10px 30px rgba(0,0,0,0.08) !important;
}

.hover-bg {
    transition: background-color 0.2s;
}
.hover-bg:hover {
    background-color: #f8fafc;
}

.cursor-pointer {
    cursor: pointer;
}

.btn-icon {
    width: 32px;
    height: 32px;
    padding: 0;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    border-radius: 6px;
    transition: all 0.2s;
}
.btn-icon:hover {
    background-color: rgba(0,0,0,0.05);
}

.hover-opacity-100:hover {
    opacity: 1 !important;
}

.hover-top {
    transition: transform 0.2s, box-shadow 0.2s;
}
.hover-top:hover {
    transform: translateY(-5px);
    box-shadow: 0 .5rem 1rem rgba(0,0,0,.15)!important;
}

.hover-shadow-lg:hover {
    box-shadow: 0 1rem 3rem rgba(0,0,0,.175)!important;
    transform: translateY(-5px);
}

.transition-all {
    transition: all 0.2s ease-in-out;
}

.hover-bg-primary {
    transition: all 0.2s;
}
.hover-bg-primary:hover {
    background-color: var(--bs-primary-bg-subtle, #cfe2ff) !important;
    border-color: var(--bs-primary) !important;
}
.bg-success-soft {
    background-color: rgba(25, 135, 84, 0.1) !important;
}
.zoom-effect {
    transition: transform 0.3s cubic-bezier(0.34, 1.56, 0.64, 1), box-shadow 0.3s ease;
}
.zoom-effect:hover {
    transform: translateY(-8px) scale(1.02);
    box-shadow: 0 1rem 3rem rgba(67, 97, 238, 0.15) !important;
    z-index: 10;
}

.opacity-05 {
    opacity: 0.05;
}

.transform-rotate-12 {
     transform: rotate(12deg);
}

.group-add-card:hover .icon-circle {
    background-color: var(--bs-primary) !important;
    color: white !important;
}
</style>

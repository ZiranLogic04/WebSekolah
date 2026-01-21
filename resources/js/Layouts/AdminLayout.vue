<template>
    <div class="main-wrapper">
        <div class="header">
            <div class="header-left">
                <Link href="/" class="logo text-decoration-none">
                    <span class="logo-lg d-flex align-items-center gap-2">
                        <i class="fas fa-layer-group text-primary fa-lg"></i>
                        <span class="fw-bold text-dark" style="font-size: 1.35rem; letter-spacing: -0.5px;">Admin Panel</span>
                    </span>
                </Link>
                <Link href="/" class="logo logo-small text-decoration-none">
                     <i class="fas fa-layer-group text-primary fa-2x"></i>
                </Link>
            </div>
            
            <div class="menu-toggle">
                <a href="javascript:void(0);" id="toggle_btn" @click="toggleSidebar">
                    <i class="fas fa-bars"></i>
                </a>
            </div>
            
            <!-- Greeting Text Replacement -->
            <div class="top-nav-search">
                <div class="d-flex align-items-center h-100 py-3">
                    <h4 class="mb-0 text-dark fw-bold d-none d-md-block" style="font-size: 1.2rem;">
                        <span class="text-muted fw-normal">Selamat Datang,</span> {{ page.props.auth?.user?.name || 'Admin' }} 👋
                    </h4>
                </div>
            </div>
            <a class="mobile_btn" id="mobile_btn" @click="toggleMobileSidebar">
                <i class="fas fa-bars"></i>
            </a>

            <ul class="nav user-menu">
                <li class="nav-item dropdown has-arrow new-user-menus">
                    <a href="#" class="dropdown-toggle nav-link" data-bs-toggle="dropdown">
                        <span class="user-img">
                            <div class="user-text">
                                <h6>{{ page.props.auth?.user?.name || 'User' }}</h6>
                                <p class="text-muted mb-0">{{ roleLabel }}</p>
                            </div>
                        </span>
                    </a>
                    <div class="dropdown-menu">
                        <div class="user-header">
                            <div class="user-text">
                                <h6>{{ page.props.auth?.user?.name || 'User' }}</h6>
                                <p class="text-muted mb-0">{{ roleLabel }}</p>
                            </div>
                        </div>

                        <Link class="dropdown-item" href="/logout" method="post" as="button">Logout</Link>
                    </div>
                </li>
            </ul>
            
        </div>

        <div class="sidebar-overlay" :class="{ opened: mobileSidebarOpen }" @click="toggleMobileSidebar"></div>

        <div class="sidebar" id="sidebar" ref="sidebarEl">
            <div class="sidebar-inner slimscroll" ref="scrollEl">
                <div id="sidebar-menu" class="sidebar-menu">
                    <ul>
                        <li class="menu-title">
                            <span>Main Menu</span>
                        </li>

                        <!-- Dashboard - Semua user -->
                        <li :class="{ active: isUrlActive('/admin/dashboard') }">
                            <Link href="/admin/dashboard">
                                <i class="feather-grid"></i>
                                <span>Dashboard</span>
                            </Link>
                        </li>

                        <!-- Post/Berita - Admin only -->
                        <li v-if="isAdmin" :class="{ active: isUrlActive('/admin/posts') }">
                            <Link href="/admin/posts">
                                <i class="fas fa-newspaper"></i>
                                <span>Post/Berita</span>
                            </Link>
                        </li>



                        <!-- Menu Navbar -->
                        <li v-if="isAdmin" :class="{ active: isUrlActive(['/admin/halaman', '/admin/pages', '/admin/visi-misi', '/admin/sejarah', '/admin/organisasi', '/admin/slider', '/admin/features', '/admin/about', '/admin/counter', '/admin/program', '/admin/video', '/admin/team', '/admin/choose', '/admin/portfolio', '/admin/event', '/admin/testimonial', '/admin/partner', '/admin/footer-links']) }">
                            <Link href="/admin/halaman">
                                <i class="feather-layout"></i>
                                <span>Halaman</span>
                            </Link>
                        </li>





                        <!-- Master Induk - Admin only -->
                        <li v-if="isAdmin" class="submenu" :class="{ active: isUrlActive(['/admin/siswa', '/admin/guru', '/admin/lembaga', '/admin/kelas', '/admin/pengaturan-surat', '/admin/akun', '/admin/kenaikan-kelas', '/admin/alumni']) }">
                            <a href="#" @click.prevent="toggleSubmenu('master')" :class="{ subdrop: openSubmenu === 'master' }">
                                <i class="fas fa-database"></i>
                                <span>Master Induk</span>
                                <span class="menu-arrow"></span>
                            </a>
                            <transition @enter="enter" @after-enter="afterEnter" @leave="leave">
                                <ul v-show="openSubmenu === 'master'">
                                    <li><Link href="/admin/siswa" :class="{ active: isUrlActive('/admin/siswa') }">Data Siswa</Link></li>
                                    <li><Link href="/admin/kelas" :class="{ active: isUrlActive('/admin/kelas') }">Data Kelas</Link></li>
                                    <li><Link href="/admin/guru" :class="{ active: isUrlActive('/admin/guru') }">Data Guru</Link></li>
                                    <li><Link href="/admin/lembaga" :class="{ active: isUrlActive('/admin/lembaga') }">Data Lembaga</Link></li>
                                    <li><Link href="/admin/pengaturan-surat" :class="{ active: isUrlActive('/admin/pengaturan-surat') }">Pengaturan Surat</Link></li>
                                    <li><Link href="/admin/akun" :class="{ active: isUrlActive('/admin/akun') }">Pengaturan Akun</Link></li>
                                    <li><Link href="/admin/kenaikan-kelas" :class="{ active: isUrlActive('/admin/kenaikan-kelas') }">Kenaikan Kelas</Link></li>
                                    <li><Link href="/admin/alumni" :class="{ active: isUrlActive('/admin/alumni') }">Data Alumni</Link></li>
                                </ul>
                            </transition>
                        </li>



                        <!-- Surat-Surat - Admin & Staf Administrasi -->
                        <li v-if="canAccessSurat" class="submenu" :class="{ active: isUrlActive(['/admin/surat', '/admin/surat-masuk', '/admin/surat-keluar', '/admin/rekap-surat']) }">
                            <a href="#" @click.prevent="toggleSubmenu('letters')" :class="{ subdrop: openSubmenu === 'letters' }">
                                <i class="fas fa-envelope"></i>
                                <span>Surat-Surat</span>
                                <span class="menu-arrow"></span>
                            </a>
                            <transition @enter="enter" @after-enter="afterEnter" @leave="leave">
                                <ul v-show="openSubmenu === 'letters'">
                                    <li><Link href="/admin/surat/create" :class="{ active: $page.url.includes('/admin/surat/create') || ($page.url.includes('/admin/surat/') && !$page.url.includes('surat-')) }">Buat Surat</Link></li>
                                    <li><Link href="/admin/surat-masuk" :class="{ active: $page.url.includes('/admin/surat-masuk') }">Surat Masuk</Link></li>
                                    <li><Link href="/admin/surat-keluar" :class="{ active: $page.url.includes('/admin/surat-keluar') }">Surat Keluar</Link></li>
                                    <li><Link href="/admin/rekap-surat" :class="{ active: $page.url.includes('/admin/rekap-surat') }">Rekap Surat</Link></li>
                                </ul>
                            </transition>
                        </li>

                        <!-- Keuangan - Admin & Staf Keuangan -->
                        <li v-if="canAccessKeuangan" class="submenu" :class="{ active: isUrlActive(['/admin/transaksi', '/admin/tagihan', '/admin/jenis-tagihan', '/admin/uang-sekolah', '/admin/pengeluaran', '/admin/transaksi-manual', '/admin/piutang']) }">
                            <a href="#" @click.prevent="toggleSubmenu('finance')" :class="{ subdrop: openSubmenu === 'finance' }">
                                <i class="fas fa-wallet"></i>
                                <span>Keuangan</span>
                                <span class="menu-arrow"></span>
                            </a>
                            <transition @enter="enter" @after-enter="afterEnter" @leave="leave">
                                <ul v-show="openSubmenu === 'finance'">
                                    <li><Link href="/admin/transaksi-manual" :class="{ active: isUrlActive('/admin/transaksi-manual') }">Transaksi Manual</Link></li>
                                    <li><Link href="/admin/tagihan" :class="{ active: isUrlActive('/admin/tagihan') || isUrlActive('/admin/jenis-tagihan') }">Daftar Tagihan</Link></li>
                                    <li><Link href="/admin/transaksi" :class="{ active: isUrlActive('/admin/transaksi') && !$page.url.includes('manual') }">Transaksi Pemasukan</Link></li>
                                    <li><Link href="/admin/piutang" :class="{ active: isUrlActive('/admin/piutang') }">Daftar Piutang</Link></li>
                                    <li><Link href="/admin/uang-sekolah" :class="{ active: isUrlActive('/admin/uang-sekolah') && !$page.url.includes('action=create') }">Rekap Keuangan</Link></li>
                                </ul>
                            </transition>
                        </li>

                        <!-- Gallery -->
                        <li v-if="isAdmin" :class="{ active: isUrlActive('/admin/gallery') }">
                            <Link href="/admin/gallery">
                                <i class="fas fa-photo-video"></i>
                                <span>Gallery</span>
                            </Link>
                        </li>


                        
                    </ul>
                </div>
            </div>
        </div>

        <div class="page-wrapper">
            <slot />
            <footer>
                <p>Copyright © {{ new Date().getFullYear() }} {{ $page.props.lembaga?.nama_sekolah || 'WebSekolah' }}. All rights reserved.</p>
            </footer>
        </div>
    </div>
</template>

<script setup>
import { ref, onMounted, onBeforeUnmount, computed } from "vue";
import { Link, usePage } from "@inertiajs/vue3";

const page = usePage();

// Get user role
const userRole = computed(() => page.props.auth?.user?.role || 'user');

// Role checks
const isAdmin = computed(() => userRole.value === 'admin');
const canAccessSurat = computed(() => ['admin', 'staf_administrasi'].includes(userRole.value));
const canAccessKeuangan = computed(() => ['admin', 'staf_keuangan'].includes(userRole.value));

// Role label for display
const roleLabel = computed(() => {
    const labels = {
        'admin': 'Administrator',
        'staf_keuangan': 'Staf Keuangan',
        'staf_administrasi': 'Staf Administrasi',
        'guru': 'Guru',
        'siswa': 'Siswa',
    };
    return labels[userRole.value] || 'User';
});

// State
const openSubmenu = ref(null);
const activeDropdown = ref(null);
const mobileSidebarOpen = ref(false);
const sidebarCollapsed = ref(false);
const sidebarEl = ref(null);
const scrollEl = ref(null);

// Fungsi cek apakah URL aktif
const isUrlActive = (paths) => {
    const currentUrl = page.url;
    if (Array.isArray(paths)) {
        return paths.some(path => currentUrl.startsWith(path));
    }
    return currentUrl.startsWith(paths);
};

// Animasi masuk (slide down)
const enter = (element) => {
    element.style.height = '0';
    element.style.display = 'block';
    element.offsetHeight; 
    element.style.transition = 'height 0.3s ease';
    element.style.height = element.scrollHeight + 'px';
};

const afterEnter = (element) => {
    element.style.height = 'auto';
};

const leave = (element) => {
    element.style.height = element.scrollHeight + 'px';
    element.offsetHeight;
    element.style.transition = 'height 0.3s ease';
    element.style.height = '0';
};

const toggleSubmenu = (menu) => {
    openSubmenu.value = openSubmenu.value === menu ? null : menu;
};

const toggleDropdown = (dropdown) => {
    activeDropdown.value = activeDropdown.value === dropdown ? null : dropdown;
};

const toggleSidebar = () => {
    sidebarCollapsed.value = !sidebarCollapsed.value;
    if (sidebarCollapsed.value) {
        document.body.classList.add("mini-sidebar");
    } else {
        document.body.classList.remove("mini-sidebar");
    }
};

const toggleMobileSidebar = () => {
    mobileSidebarOpen.value = !mobileSidebarOpen.value;
    document.body.classList.toggle("slide-nav", mobileSidebarOpen.value);
};

const handleClickOutside = (e) => {
    if (activeDropdown.value && !e.target.closest(".dropdown")) {
        activeDropdown.value = null;
    }
};

onMounted(() => {
    document.addEventListener("click", handleClickOutside);

    if (scrollEl.value) {
        scrollEl.value.style.overflowY = "auto";
        scrollEl.value.style.maxHeight = "calc(100vh - 60px)";
    }

    // Auto-open submenu berdasarkan URL
    if (isUrlActive(['/posts', '/drafts', '/posts/create', '/admin/posts'])) openSubmenu.value = 'posts';
    else if (isUrlActive(['/admin/siswa', '/admin/guru', '/admin/lembaga', '/admin/kelas', '/admin/pengaturan-surat', '/admin/akun', '/admin/kenaikan-kelas', '/admin/alumni'])) openSubmenu.value = 'master';
    else if (isUrlActive(['/admin/surat', '/admin/surat-masuk', '/admin/surat-keluar', '/admin/rekap-surat'])) openSubmenu.value = 'letters';
    else if (isUrlActive(['/admin/transaksi', '/admin/tagihan', '/admin/hutang', '/admin/rekap-keuangan', '/admin/pengeluaran', '/admin/transaksi-manual', '/admin/jenis-tagihan', '/admin/uang-sekolah', '/admin/piutang'])) openSubmenu.value = 'finance';
});

onBeforeUnmount(() => {
    document.removeEventListener("click", handleClickOutside);
});
</script>

<style scoped>
.sidebar-menu ul ul {
    overflow: hidden;
}
</style>
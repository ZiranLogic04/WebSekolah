<template>
    <div class="main-wrapper">
        <div class="header">
            <div class="header-left">
                <Link href="/" class="logo">
                    <img src="/assets/img/logo.png" alt="Logo" />
                </Link>
                <Link href="/" class="logo logo-small">
                    <img src="/assets/img/logo-small.png" alt="Logo" width="30" height="30" />
                </Link>
            </div>
            
            <div class="menu-toggle">
                <a href="javascript:void(0);" id="toggle_btn" @click="toggleSidebar">
                    <i class="fas fa-bars"></i>
                </a>
            </div>
            
            <div class="top-nav-search">
                <form @submit.prevent>
                    <input type="text" class="form-control" placeholder="Search here" />
                    <button class="btn" type="submit"><i class="fas fa-search"></i></button>
                </form>
            </div>
            <a class="mobile_btn" id="mobile_btn" @click="toggleMobileSidebar">
                <i class="fas fa-bars"></i>
            </a>

            <ul class="nav user-menu">
                <li class="nav-item dropdown has-arrow new-user-menus">
                    <a href="#" class="dropdown-toggle nav-link" data-bs-toggle="dropdown">
                        <span class="user-img">
                            <img class="rounded-circle" src="/assets/img/profiles/avatar-01.jpg" width="31" alt="User Image">
                            <div class="user-text">
                                <h6>{{ page.props.auth?.user?.name || 'Administrator' }}</h6>
                                <p class="text-muted mb-0">Admin</p>
                            </div>
                        </span>
                    </a>
                    <div class="dropdown-menu">
                        <div class="user-header">
                            <div class="avatar avatar-sm">
                                <img src="/assets/img/profiles/avatar-01.jpg" alt="User Image" class="avatar-img rounded-circle">
                            </div>
                            <div class="user-text">
                                <h6>{{ page.props.auth?.user?.name || 'Administrator' }}</h6>
                                <p class="text-muted mb-0">Admin</p>
                            </div>
                        </div>
                        <Link class="dropdown-item" href="/admin/profile">Profil Saya</Link>
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

                        <!-- Dashboard -->
                        <li :class="{ active: isUrlActive('/admin/dashboard') }">
                            <Link href="/admin/dashboard">
                                <i class="feather-grid"></i>
                                <span>Dashboard</span>
                            </Link>
                        </li>

                        <!-- Post/Berita -->
                        <!-- Post/Berita -->
                        <li :class="{ active: isUrlActive('/admin/posts') }">
                            <Link href="/admin/posts">
                                <i class="fas fa-newspaper"></i>
                                <span>Post/Berita</span>
                            </Link>
                        </li>

                        <!-- Halaman -->
                        <li :class="{ active: isUrlActive('/admin/pages') }">
                            <Link href="/admin/pages">
                                <i class="fas fa-file-alt"></i>
                                <span>Halaman</span>
                            </Link>
                        </li>

                        <!-- Master Induk -->
                        <li class="submenu" :class="{ active: isUrlActive(['/admin/siswa', '/admin/guru', '/admin/lembaga']) }">
                            <a href="#" @click.prevent="toggleSubmenu('master')" :class="{ subdrop: openSubmenu === 'master' }">
                                <i class="fas fa-database"></i>
                                <span>Master Induk</span>
                                <span class="menu-arrow"></span>
                            </a>
                            <transition @enter="enter" @after-enter="afterEnter" @leave="leave">
                                <ul v-show="openSubmenu === 'master'">
                                    <li><Link href="/admin/siswa" :class="{ active: isUrlActive('/admin/siswa') }">Data Siswa</Link></li>
                                    <li><Link href="/admin/guru" :class="{ active: isUrlActive('/admin/guru') }">Data Guru</Link></li>
                                    <li><Link href="/admin/lembaga" :class="{ active: isUrlActive('/admin/lembaga') }">Data Lembaga</Link></li>
                                    <li><Link href="/admin/surat" :class="{ active: isUrlActive('/admin/surat') }">Pengaturan Surat</Link></li>
                                    <li><Link href="/admin/akun" :class="{ active: isUrlActive('/admin/akun') }">Pengaturan Akun</Link></li>
                                </ul>
                            </transition>
                        </li>

                        <!-- Surat-Surat -->
                        <li class="submenu" :class="{ active: isUrlActive(['/admin/surat-masuk', '/admin/surat-keluar', '/admin/rekap-surat']) }">
                            <a href="#" @click.prevent="toggleSubmenu('letters')" :class="{ subdrop: openSubmenu === 'letters' }">
                                <i class="fas fa-envelope"></i>
                                <span>Surat-Surat</span>
                                <span class="menu-arrow"></span>
                            </a>
                            <transition @enter="enter" @after-enter="afterEnter" @leave="leave">
                                <ul v-show="openSubmenu === 'letters'">
                                    <li><Link href="/admin/surat-masuk" :class="{ active: isUrlActive('/admin/surat-masuk') }">Surat Masuk</Link></li>
                                    <li><Link href="/admin/surat-keluar" :class="{ active: isUrlActive('/admin/surat-keluar') }">Surat Keluar</Link></li>
                                    <li><Link href="/admin/rekap-surat" :class="{ active: isUrlActive('/admin/rekap-surat') }">Rekap Surat</Link></li>
                                </ul>
                            </transition>
                        </li>

                        <!-- Keuangan -->
                        <li class="submenu" :class="{ active: isUrlActive(['/admin/transaksi', '/admin/tagihan', '/admin/jenis-tagihan', '/admin/uang-sekolah', '/admin/pengeluaran', '/admin/transaksi-manual']) }">
                            <a href="#" @click.prevent="toggleSubmenu('finance')" :class="{ subdrop: openSubmenu === 'finance' }">
                                <i class="fas fa-wallet"></i>
                                <span>Keuangan</span>
                                <span class="menu-arrow"></span>
                            </a>
                            <transition @enter="enter" @after-enter="afterEnter" @leave="leave">
                                <ul v-show="openSubmenu === 'finance'">
                                    <li><Link href="/admin/jenis-tagihan" :class="{ active: isUrlActive('/admin/jenis-tagihan') }">Daftar Jenis Tagihan</Link></li>
                                    <li><Link href="/admin/transaksi-manual" :class="{ active: isUrlActive('/admin/transaksi-manual') }">Transaksi Manual</Link></li>
                                    <li><Link href="/admin/tagihan" :class="{ active: isUrlActive('/admin/tagihan') && !$page.url.includes('status=Belum') }">Daftar Tagihan</Link></li>
                                    <li><Link href="/admin/transaksi" :class="{ active: isUrlActive('/admin/transaksi') && !$page.url.includes('manual') }">Transaksi Pemasukan</Link></li>
                                    <li><Link href="/admin/pengeluaran" :class="{ active: isUrlActive('/admin/pengeluaran') }">Transaksi Pengeluaran</Link></li>
                                    <li><Link href="/admin/piutang" :class="{ active: isUrlActive('/admin/piutang') }">Daftar Piutang</Link></li>
                                    <li><Link href="/admin/uang-sekolah" :class="{ active: isUrlActive('/admin/uang-sekolah') && !$page.url.includes('action=create') }">Rekap Keuangan</Link></li>
                                </ul>
                            </transition>
                        </li>

                        <!-- Galeri (Coming Soon) -->
                        <!-- Menu Galeri akan ditambahkan setelah route tersedia -->
                        
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
    else if (isUrlActive(['/admin/siswa', '/admin/guru', '/admin/lembaga', '/admin/surat', '/admin/akun'])) openSubmenu.value = 'master';
    else if (isUrlActive(['/admin/surat-masuk', '/admin/surat-keluar', '/admin/rekap-surat'])) openSubmenu.value = 'letters';
    else if (isUrlActive(['/admin/transaksi', '/admin/tagihan', '/admin/hutang', '/admin/rekap-keuangan', '/admin/pengeluaran', '/admin/transaksi-manual', '/admin/jenis-tagihan', '/admin/uang-sekolah'])) openSubmenu.value = 'finance';
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